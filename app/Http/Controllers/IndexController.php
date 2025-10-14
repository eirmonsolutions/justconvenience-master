<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use Session;

use App\Lead;
use App\Contest;
use App\Customer;
use App\customerInvoice;
use App\invoiceDetail;
use App\Setting;
use App\Shop;
use App\User;
use App\Order;
use Carbon\Carbon;

class IndexController extends Controller
{
    protected $current_active_contest_id;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        $todayDate = date('Y-m-d');
        $activeContestRecord = Contest::where(function ($query) use ($todayDate) {
                                            $query->whereDate('start_date', '<=', $todayDate)
                                                    ->whereDate('end_date', '>=', $todayDate);
                                        })
                                        ->first();
        if($activeContestRecord)
        {
            $this->current_active_contest_id = $activeContestRecord->id;
        }
        else
        {
            $this->current_active_contest_id = '';
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $shops = Shop::get();

        $todayDate = date('Y-m-d');
        $getContestRecord = Contest::where(function ($query) use ($todayDate) {
                                            $query->whereDate('start_date', '<=', $todayDate)
                                                    ->whereDate('end_date', '>=', $todayDate);
                                        })
                                        ->where('status', 1)
                                        ->first();
        $data = ['is_contest' => false];
        if ($getContestRecord)
        {
            $data = ['contest' => $getContestRecord, 'is_contest' => true, 'shops' => $shops];
        }
        return view('index', ['data2' => $data, 'page_title' => 'Home Page']);
    }


     public function privacy()
    {

        return view('privacy', ['page_title' => 'Privacy Policy']);
    }

     public function terms()
    {

        return view('terms', ['page_title' => 'Terms & Conditions']);
    }

    //terms

    

    public function signin(Request $request)
    {
        if(Auth::check())
        {
            if(Auth::user()->user_role == 1)
            {
                return redirect()->route('users');
            }
            else if(Auth::user()->user_role == 2 || Auth::user()->user_role == 5)
            {
                return redirect()->route('dashboard');
            }
        }
        if (\Request::isMethod('post'))
        {
            // create the validation rules ------------------------
            $rules = array(
                // 'name'             => 'required',                        // just a normal required validation
                'email'            => 'required|email',     // required and must be unique in the ducks table
                'password'         => 'required',
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

                return redirect()->route('signin')->withInput($request->all());
            }

            $params = $request->post();
            if(Auth::attempt(array('email' => $params['email'], 'password' => $params['password'])))
            {
                Session::flash('message', 'Logged in successfully'); 
                Session::flash('class', 'success');

                if(Auth::user()->user_role == 1)
                {
                    return redirect()->route('users');
                }
                else if(Auth::user()->user_role == 2 || Auth::user()->user_role == 5)
                {
                    return redirect()->route('dashboard');
                }
            }
            else
            {
                Session::flash('message', 'Invalid Credentials'); 
                Session::flash('class', 'danger'); 
                return redirect()->route('signin')->withInput($request->all());
            }
        }

        return view('signin');
    }

    public function signOut()
    {
        Session::flash('message', 'Logout successfully.'); 
        Session::flash('class', 'success');

        Auth::logout();
        return redirect()->route('signin');   
    }

    public function dashboard(Request $request)
    {
        $params = $request->post();

        // $getCustomers = Customer::whereHas('invoiceDetails')->get();

        $stats = [];

        $today = Carbon::today()->toDateString();
        $stats['todayOrderCount'] = Order::whereHas('user', function($q) {
            $q->where('status', 1)
            ->where('user_role', 4);
        })->whereDate('created_at', $today)->count();
        
        $stats['todayCustomerCount'] = User::where('user_role', 4)
                                            // ->where('status', 1)
                                            ->whereDate('created_at', $today)
                                            ->count();
		 
		 
        $currentDate = \Carbon\Carbon::now();
        $pastWeekStartDate = $currentDate->subWeek()->startOfWeek()->format('Y-m-d');
        $currentDate = \Carbon\Carbon::now();
        $pastWeekEndDate = $currentDate->subWeek()->endOfWeek()->format('Y-m-d');

        $stats['storeCount'] = User::where(array('user_role' => 3))->count();

        $getCustomers = User::where('user_role', 4)->where('status', 1)->orderBy('id', 'desc')->take(5)->get();

        $getContests = Contest::get();

        return view('dashboard', ['customers' => $getCustomers, 'params' => $params, 'stats' => $stats, 'contests' => $getContests]);
    }

    public function eliminatedCustomers(Request $request)
    {
        if(Auth::user()->user_role == 3)
        {
            return redirect()->route('dashboard');
        }

        $params = $request->post();

        $stats = [];
        $getCustomers = User::where('status', 4)->where('user_role', 4);   

        if(isset($params['shop_id']) && !empty($params['shop_id']))
        {
            $getCustomers = $getCustomers->whereHas('invoices', function ($query) use ($params) {
                    $query->where('shop_id', $params['shop_id']);
            });
        }

        if (isset($params['start_date']) && !empty($params['start_date']) && isset($params['end_date']) && !empty($params['end_date']))
        {
            $getCustomers = $getCustomers->whereHas('invoices', function ($query) use ($params) {
                $start_date = date('Y-m-d', strtotime($params['start_date']));
                $end_date = date('Y-m-d', strtotime($params['end_date']));
                
                $query->whereDate('invoice_date' , '>=', $start_date);
                $query->whereDate('invoice_date' , '<=', $end_date);
            });
        }
        else if(isset($params['start_date']) && !empty($params['start_date']))
        {
            $getCustomers = $getCustomers->whereHas('invoices', function ($query) use ($params) {
                $start_date = date('Y-m-d', strtotime($params['start_date']));
                $query->whereDate('invoice_date' , '>=', $start_date);
            });

        }
        else if(isset($params['end_date']) && !empty($params['end_date']))
        {
            $getCustomers = $getCustomers->whereHas('invoices', function ($query) use ($params) {
                $end_date = date('Y-m-d', strtotime($params['end_date']));
                $query->whereDate('invoice_date' , '<=', $end_date);
            });
        }
        
        if(isset($params['total_invoice_val']) && !empty($params['total_invoice_val']))
        {
            $getCustomers = $getCustomers->where('total_invoice_val', '>=', $params['total_invoice_val']);
        }
        
        if(isset($params['ticket_count']) && !empty($params['ticket_count']))
        {
            $getCustomers = $getCustomers->where('ticket_count', '>=', $params['ticket_count']);
        }

        if (isset($params['contest_id']) && !empty($params['contest_id']))
        {
            $getCustomers = $getCustomers->where('contest_id', $params['contest_id']);
        }


        $getCustomers = $getCustomers->orderBy('id', 'DESC')->get();

        $getContests = Contest::orderBy('id', 'desc')->get();

        $getShops = Shop::orderBy('shop_name', 'asc')->get();

        return view('eliminated_customers', ['customers' => $getCustomers, 'params' => $params, 'stats' => $stats, 'contests' => $getContests, 'shops' => $getShops]);
    }

    public function sendEmail(Request $request, $customer_id)
    {
        if(!Auth::check())
        {
            return redirect()->route('signin');
        }

        if(Auth::user()->user_role == 1)
        {
            return redirect()->route('users');
        }

        $params = $request->post();

        $customerDetails = Customer::find($customer_id);
        if ($customerDetails)
        {
            try{

                \Mail::send('winner_email_template', ['params' => $customerDetails], function($message) use($customerDetails){

                        // $message->from('pranav@1touch-dev.com', 'name');

                        $message->to($customerDetails->email)->subject('Felicitaciones!');

                    });

                Session::flash('message', 'Mail sent successfully.'); 
                Session::flash('class', 'success');
            }
            catch(\Exception $e)
            {
                Session::flash('message', $e->getMessage()); 
                Session::flash('class', 'danger');
            } 
        }
        else
        {
            Session::flash('message', 'Something went wrong.'); 
            Session::flash('class', 'danger');
        }

        return redirect()->route('tickets', ['contest_id' => $this->current_active_contest_id]);

    }
}