<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use Session;
use App\User;
use App\Contest;
use App\Customer;

class EmailAreaController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	    // $this->middleware('auth');
	    $this->middleware(function ($request, $next) {
	        if(Auth::user()->user_role == 3)
	        {
	            return redirect()->route('dashboard');
	        }

	        return $next($request);
	    });
	}
	public function sendContestCustomerEmail(Request $request)
	{
	    if (\Request::isMethod('post'))
	    {
	        // create the validation rules ------------------------
	        $rules = array(
	            'contest_id'            => 'required',
	            'message'         => 'required'
	        );

	        // do the validation ----------------------------------
	        // validate against the inputs from our form
	        $validator = Validator::make($request->all(), $rules);

	        // check if the validator failed -----------------------
	        if ($validator->fails()) {

	            // get the error messages from the validator
	            // $messages = $validator->messages();

	            Session::flash('message', $validator->errors()->first()); 
	            Session::flash('class', 'danger');

	            return redirect()->route('send-contest-customer-email'); 
	        }

	        $params = $request->post();

	        $checkContestExistance = Contest::find($params['contest_id']);
	        if(!$checkContestExistance)
	        {
	            Session::flash('message', 'No Contest Exist.'); 
	            Session::flash('class', 'danger');

	            return redirect()->route('send-contest-customer-email');
	        }

	        //$getCustomerEmails = Customer::where('status', 1)->where('contest_id', $params['contest_id'])->distinct()->pluck('email');
	        $getUserEmails = User::where(static function ($query) use ($params) {
						                $query->whereHas('invoices', function ($q) use ($params) {
			                                    $q->where('contest_id', $params['contest_id']);
			                                })
						                    ->orWhere('contest_id', $params['contest_id']);
						            })
	        						/*whereHas('invoices', function ($query) use ($params) {
	                                    $query->where('contest_id', $params['contest_id']);
	                                })*/
	        						// ->where('contest_id', $params['contest_id'])
	        						->where('status', 1)
	        						->where('user_role', 4)
	        						->distinct()
	        						->pluck('email');
			
	        $emailArr = [];
	        if (sizeof($getUserEmails) > 0)
	        {
	            $emailArr = $getUserEmails->toArray();
	        }

	        if (sizeof($emailArr) > 0)
	        {
	            foreach ($emailArr as $keyEA => $valueEA)
	
	            {
	                try{

	                    \Mail::send('customer_email_template', ['params' => $params], function($message) use($valueEA){

	                            // $message->from('pranav@1touch-dev.com', 'name');

	                           $message->to($valueEA)->subject('Notificación!');

	                        });

	                    Session::flash('message', 'Mail sent successfully.'); 
	                    Session::flash('class', 'success');
	                }
	                catch(\Exception $e)
	                {
	                	// print_r($e->getMessage()); die();
	                    Session::flash('message', 'Email not sent'); 
	                    Session::flash('class', 'danger');
	                }
	            }
	        }
	        else
	        {
	            Session::flash('message', 'Email not sent'); 
	            Session::flash('class', 'danger');
	        }
	    }

	    /*$getContests = Contest::whereHas('users', function ($query) {
                                    $query->where('user_role', 4);
                                })->get();*/

        $getContests = Contest::whereHas('customerInvoices')
        						->orWhereHas('users', function ($query) {
                                            $query->where('user_role', 4);
                                        })->get();
	    return view('admin.email.send_contest_customer_email', ['contests' => $getContests]);
	}
}