<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use Session;

use App\User;
use App\Shop;

use App\Contest;
use App\customerInvoice;

use Carbon\Carbon;

class InvoiceController extends Controller
{
   protected $current_active_contest_id;
   public function __construct()
	{
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
	    $this->middleware(function ($request, $next) {
	        if(Auth::user()->user_role == 3)
	        {
	            return redirect()->route('dashboard');
	        }

	        return $next($request);
	    });
	}
   
    public function index(Request $request, $user_id = null)
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

        if(isset($user_id) && !empty($user_id))
        {
            $getCustomerDetails = User::where('status', 1)->where('user_role', 4)->where(['id' => $user_id])->first();
            if ($getCustomerDetails)
            {
                $getCustomerDetails->is_reviewed = 1;
                $getCustomerDetails->save();
            }
            else
            {
                Session::flash('message', 'Customer not found.'); 
                Session::flash('class', 'danger');

                return redirect()->route('customers', ['contest_id' => $this->current_active_contest_id]);
            }
        }
        
        if(isset($params['tag']) && !empty($params['tag'])){
            
            if($params['tag'] == 'all')
            {
                $customer_invoices = customerInvoice::where(array('user_id' => $user_id))->get();
            }
            else
            {
                if($params['tag'] == 'needs')
                {
                    $needs = 'needs attention';
                    $customer_invoices = customerInvoice::where(array('user_id' => $user_id,'tag' => $needs))->get();
                }
                else
                {
                    $customer_invoices = customerInvoice::where(array('user_id' => $user_id,'tag' => $params['tag']))->get();
                }
            }
        }
        else
        {
            $customer_invoices = customerInvoice::where(array('user_id' => $user_id))->get();
        }
        
        return view('invoice.index', ['customer_invoices' => $customer_invoices,'user_id' => $user_id, 'params' => $params]);
    } 

    public function pendingInvoices(Request $request)
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


        $customer_invoices = customerInvoice::whereHas('user', function($q) {
            $q->where('status', 1);
        })->where('tag' , '!=', 'approved')->where(['status' => 1]);


        if (isset($params['start_date']) && !empty($params['start_date']) && isset($params['end_date']) && !empty($params['end_date']))
        {
            $customer_invoices = $customer_invoices->where(function ($query) use ($params) {

                $start_date = date('Y-m-d', strtotime($params['start_date']));

                    $query->whereDate('invoice_date', '>=', $start_date)
                          ->orWhereDate('created_at', '>=', $start_date);
                })->where(function ($query) use ($params) {
                    
                    $end_date = date('Y-m-d', strtotime($params['end_date']));
                    
                    $query->whereDate('invoice_date', '<=', $end_date)
                          ->orWhereDate('created_at', '<=', $end_date);
                });
        }
        else if(isset($params['start_date']) && !empty($params['start_date']))
        {
            $customer_invoices = $customer_invoices->where(function ($query) use ($params) {

                $start_date = date('Y-m-d', strtotime($params['start_date']));
                
                $query->whereDate('invoice_date', '>=', $start_date)
                      ->orWhereDate('created_at', '>=', $start_date);
            });

        }
        else if(isset($params['end_date']) && !empty($params['end_date']))
        {
            $customer_invoices = $customer_invoices->where(function ($query) use ($params) {

                $end_date = date('Y-m-d', strtotime($params['end_date']));

                $query->whereDate('invoice_date', '<=', $end_date)
                      ->orWhereDate('created_at', '<=', $end_date);
            });
        }

        $customer_invoices = $customer_invoices->get();
        
        return view('invoice.pending_invoices', ['customer_invoices' => $customer_invoices, 'params' => $params]);
    } 
    
    public function editCustomerInvoice(Request $request, $id, $customer_id)
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

        $shops = Shop::get();

        $getCustomerInvoiceDetails = customerInvoice::whereHas('user')->where(['id' => $id, 'user_id' => $customer_id])->first();
        if ($getCustomerInvoiceDetails)
        {
            return view('admin.customers.edit_invoice', ['invoice' => $getCustomerInvoiceDetails, 'params' => $params, 'shops' => $shops]);
        }
        else
        {
            Session::flash('message', 'Invoice not found.'); 
            Session::flash('class', 'danger');
        }

        return redirect()->route('customers', ['contest_id' => $this->current_active_contest_id]);
    }


    public function updateInvoice(Request $request)
    {
        if(!Auth::check())
        {
            return redirect()->route('signin');
        }

        if(Auth::user()->user_role == 1)
        {
            return redirect()->route('users');
        }
        // create the validation rules ------------------------
        $rules = array(
            'id'             => 'required',
            'num_bill'             => 'required',
            'shop_id'         => 'required',
            'invoice_date'         => 'required',
            'invoice_amount'         => 'required',
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

            return redirect()->route('edit-invoice', ['id' => $request->id, 'customer_id' => $request->user_id])->withInput($request->all());
        }

        $params = $request->post();

        $getShopId = Shop::where('id', $params['shop_id'])->first();
        if(!$getShopId)
        {
            Session::flash('message', 'Invalid Store Selected.'); 
            Session::flash('class', 'danger');

            return redirect()->route('customers', ['contest_id' => $this->current_active_contest_id]);
        }

        $customer_invoice = customerInvoice::find($params['id']);
        if (!$customer_invoice)
        {
            Session::flash('message', 'No Invoice Found'); 
            Session::flash('class', 'danger');  
            return redirect()->route('customers', ['contest_id' => $this->current_active_contest_id]);
        }

        $customer_invoice->local = $getShopId->shop_name . ', ' . $getShopId->contract_number . ', ' . $getShopId->shopping_center_id;
        $customer_invoice->shop_id = $getShopId->id;
        $customer_invoice->invoice_amount = $params['invoice_amount'];
        $customer_invoice->num_bill = $params['num_bill'];
        $customer_invoice->invoice_date = date('Y-m-d', strtotime($params['invoice_date']));
        $customer_invoice->tag = 'edited';

        if($customer_invoice->save())
        {
            /*$customerDetails = Customer::whereHas('invoices')->where(['id' => $params['customer_id']])->first();
            $customerDetails->total_invoice_val = $customerDetails->invoices->sum('invoice_amount');


            $unitAmount = 25;

            $entriesCount = (int) ($customerDetails->total_invoice_val / $unitAmount);

            $alreadyCount = $customerDetails->invoiceDetails->count();

            if ($alreadyCount > $entriesCount)
            {
                $deletedRows = $alreadyCount - $entriesCount;
                $customerDetails->ticket_count = $customerDetails->ticket_count - $deletedRows;

                invoiceDetail::where('customer_id', $params['customer_id'])->orderBy('id', 'DESC')->take($deletedRows)->delete();
            }
            else if($alreadyCount < $entriesCount)
            {
                $addingRows = $entriesCount - $alreadyCount;

                for ($i=1; $i <= $addingRows; $i++) 
                {
                    $InvoiceDetail = new invoiceDetail;
                    $InvoiceDetail->contest_id = $customerDetails->contest_id;
                    $InvoiceDetail->customer_id = $customerDetails->id;
                    $InvoiceDetail->invoice_amount = $unitAmount;
                    $InvoiceDetail->save();
                }

                $customerDetails->ticket_count = $customerDetails->ticket_count + $addingRows;
            }

            $customerDetails->save();*/
            $getCustomerDetails = User::where(['id' => $params['user_id']])->first();
        
            $params_mail = array(
                'email' => $getCustomerDetails->email,
                'id' =>  $params['num_bill']
            );
            \Mail::send('edit_invoice_email_template', ['params' => $params_mail], function($message) use($params_mail){

                    $message->to($params_mail['email'])->subject('Estado de la factura');

                });
            Session::flash('message', 'Invoice has updated successfully.'); 
            Session::flash('class', 'success');
        }
        else
        {
            Session::flash('message', 'Something went wrong.'); 
            Session::flash('class', 'danger'); 
        }

        return redirect()->route('customer-details', ['id' => $request->user_id]);
    }
    public function deleteCustomerInvoice(Request $request, $id, $customer_id)
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
        $getCustomerDetails = User::where(['id' => $customer_id])->first();
        $getCustomerInvoiceDetails = customerInvoice::whereHas('user')->where(['id' => $id, 'user_id' => $customer_id])->first();
        $params_mail = array(
            'email' => $getCustomerDetails->email,
            'id' =>  $getCustomerInvoiceDetails->num_bill
        );
        if ($getCustomerInvoiceDetails)
        {
            if($getCustomerInvoiceDetails->delete())
            {
                \Mail::send('delete_invoice_email_template', ['params' => $params_mail], function($message) use($params_mail){

                    $message->to($params_mail['email'])->subject('Estado de la factura');

                });
                Session::flash('message', 'Invoice deleted successfully.'); 
                Session::flash('class', 'success'); 
            }
            else
            {
                Session::flash('message', 'Something went wrong.'); 
                Session::flash('class', 'danger'); 
            }

            return redirect()->route('customer-details', ['id' => $customer_id]);
        }
        else
        {
            Session::flash('message', 'Invoice not found.'); 
            Session::flash('class', 'danger');
        }

        return redirect()->route('customers', ['contest_id' => $this->current_active_contest_id]);
    }
   
}
