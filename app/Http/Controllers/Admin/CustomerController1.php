<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

use Carbon\Carbon;
use QrCode;
use Yajra\Datatables\Datatables;
use DB;
use App\Exports\Customers;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{   
    protected $current_active_contest_id;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
        ini_set('memory_limit','2000M');
        ini_set('max_execution_time', '0');
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

	    $this->middleware(function ($request, $next) {
	        if(Auth::user()->user_role == 3)
	        {
	            return redirect()->route('dashboard');
	        }

	        return $next($request);
	    });
	}

	public function index(Request $request)
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

        $stats = [];

        $getCustomers = User::where('status', 1)->where('user_role', 4);

        if(isset($params['tag']) && !empty($params['tag']) && $params['tag'] == 'new')
        {
            $getCustomers = $getCustomers->where('is_reviewed', 0);
        }


        if(isset($params['shop_id']) && !empty($params['shop_id']))
        {
            $getCustomers = $getCustomers->whereHas('invoices', function ($query) use ($params) {
                    $query->where('shop_id', $params['shop_id']);
            });
        }

        if(isset($params['tag']) && !empty($params['tag']) && ($params['tag'] == 'approved' || $params['tag'] == 'pending'))
        {
            $getCustomers = $getCustomers->whereHas('invoices', function ($query) use ($params) {

                if($params['tag'] == 'approved')
                {
                    $query->where('tag', $params['tag']);
                }
                else
                {
                    $query->where('tag', '!=','approved');
                }
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
            // $getCustomers = $getCustomers->where('contest_id', $params['contest_id']);
            $getCustomers = $getCustomers->where(static function ($query) use ($params) {
                                        $query->whereHas('invoices', function ($q) use ($params) {
                                                $q->where('contest_id', $params['contest_id']);
                                            })
                                            ->orWhere('contest_id', $params['contest_id']);
                                    });
        }


        // $getCustomers = $getCustomers->orderBy('id', 'DESC')->get();
        $getCustomers = $getCustomers->orderBy('id', 'DESC')->get();;

        $getContests = Contest::orderBy('id', 'desc')->get();

        $getShops = Shop::orderBy('shop_name', 'asc')->get();

        return view('admin.customers.index', ['customers' => $getCustomers, 'params' => $params, 'stats' => $stats, 'contests' => $getContests, 'shops' => $getShops]);
    
	}

    //New Format to show data in datatable start

    public function downloadCustomers(Request $request)
    {
        $params = $request->input();
        ob_end_clean();
        return Excel::download(new Customers($params), 'Customers.xlsx');
    }

    public function getCustomers(Request $request){

        $params = $request->post();  
        $column_no = (isset($params['order'][0]['column']))? $params['order'][0]['column'] : '2';
        $dir = (isset($params['order'][0]['dir']))? $params['order'][0]['dir'] : 'asc';
        $sorting_array = array('id', 'name', 'email', 'phone_number', 'direction', 'store' ,'invoice_str', 'invoice_amount', 'invoice_count', 'ticket_count','indentification_card');
        // $params['contest_id'] = 1;
        if($column_no == '' || $column_no < 1){
            $sort_by = 'name';
        }else{
            $sort_by = $sorting_array[$column_no];
        }
        // echo "<pre>";
        // print_r($params);die;
        if(isset($params['length']) && !empty($params['length'])){
            $limit = $params['length'];
        }else{
            $limit = 10;
        } 
        $start = (isset($params['start']))? $params['start'] : '0';

        //Fetch Record to show in the datatable
        $getCustomers = User::where('status', 1)->where('user_role', 4);
        if(isset($params['tag']) && !empty($params['tag']) && $params['tag'] == 'new')
        {
            $getCustomers = $getCustomers->where('is_reviewed', 0);
        }

        if(isset($params['search_fields']) && !empty($params['search_fields']))
        {
            $getCustomers->where(function($qs) use($params) {
                $qs->where('email', 'Like', '%'.$params['search_fields'].'%');
                $qs->orWhere('name', 'Like', $params['search_fields'].'%');
                $qs->orWhere('last_name', 'Like', $params['search_fields'].'%');
                $qs->orWhere('phone_number', 'Like', $params['search_fields'].'%');
                $qs->orWhere('indentification_card', 'Like', $params['search_fields'].'%');
                $qs->orWhere('direction', 'Like', $params['search_fields'].'%');
             });
        }

        if(isset($params['shop_id']) && !empty($params['shop_id']))
        {
            $getCustomers = $getCustomers->whereHas('invoices', function ($query) use ($params) {
                    $query->where('shop_id', $params['shop_id']);
            });
        }

        if(isset($params['tag']) && !empty($params['tag']) && ($params['tag'] == 'approved' || $params['tag'] == 'pending'))
        {
            $getCustomers = $getCustomers->whereHas('invoices', function ($query) use ($params) { 
                if($params['tag'] == 'approved')
                {
                    $query->where('tag', $params['tag']);
                }
                else
                {
                    $query->where('tag', '!=','approved');
                }
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

     
        // if (isset($params['contest_id']) && !empty($params['contest_id']))
        // {
        //     // $getCustomers = $getCustomers->where('contest_id', $params['contest_id']);
        //     $getCustomers = $getCustomers->where(static function ($query) use ($params) {
        //                                 $query->whereHas('invoices', function ($q) use ($params) {
        //                                         $q->where('contest_id', $params['contest_id']);
        //                                     })
        //                                     ->orWhere('contest_id', $params['contest_id']);
        //                             });
        // } 
        // Count total records here
        $total_records = $total_records1 = $getCustomers->count();

        // Get data to show on datatable
        $getCustomers = $getCustomers->orderBy($sort_by, $dir)->skip($start)->take($limit)->get(); 
        // echo $total_records.'---'.$total_records1;die;
        return Datatables::of($getCustomers)
            ->with([
                "recordsTotal" => $total_records,
                "recordsFiltered" => $total_records1,
              ])
            ->addColumn('name', function($getCustomers) { 
                return $getCustomers->name.' '.$getCustomers->last_name;
            })
            ->addColumn('user_url', function($getCustomers) { 
                return $user_url = route('customer-details', ['id' => $getCustomers->id]) ;
            })
            ->addColumn('user_review', function($getCustomers) {
                return $classname = ($getCustomers->is_reviewed) ? 'Reviewed' : 'New'; 
            })
            ->addColumn('last_name', function($getCustomers) {
                return $getCustomers->last_name;
            })
            ->addColumn('email', function($getCustomers) {
                return $getCustomers->email;
            })
            ->addColumn('phone_number', function($getCustomers) {
                return $getCustomers->phone_number;
            })
            ->addColumn('direction', function($getCustomers) {
                return $getCustomers->direction;
            })
            ->addColumn('store', function($getCustomers) {
                return '<span class="overflow-text" data-toggle="tooltip" data-placement="top" title="" data-original-title="'. implode('||||', $getCustomers->invoices->pluck('local')->toArray()) .'">'. implode('||||', $getCustomers->invoices->pluck('local')->toArray()) .'</span>';
            })
            ->addColumn('invoice_str',function($getCustomers){  
                $invoice_str = '';
                $invoice_dates = $getCustomers->invoices->pluck('invoice_date')->toArray();

                $totalCount = sizeof($invoice_dates);
                if($totalCount > 0)
                {
                    foreach ($invoice_dates as $keyID => $valueID)
                    {
                        $invoice_str .= date('d M Y', strtotime($valueID));
                        if (($totalCount - 1) > $keyID) 
                        {
                            $invoice_str .= '||||';
                        }
                    }
                }

              return '<span class="overflow-text" data-toggle="tooltip" data-placement="top" title="" data-original-title="'. $invoice_str .'">'. $invoice_str .'</span>';
            })
            ->addColumn('invoice_amount', function($getCustomers) {
                return $getCustomers->invoices->sum('invoice_amount');
            })
            ->addColumn('invoice_count', function($getCustomers) {
                return $getCustomers->invoices->count();
            })
            ->addColumn('ticket_count', function($getCustomers) {
                return $getCustomers->ticket_count;
            })
            ->addColumn('indentification_card', function($getCustomers) {
                return $getCustomers->indentification_card;
            })
            ->addColumn('action', function($getCustomers) {
              $action =  '<a href="'.route('invoices', ['customer_id' => $getCustomers->id])  .'" class="btn btn-primary">Receipts</a>
                            <a href="'. route('customer-tickets', ['customer_id' => $getCustomers->id])  .'" class="btn btn-primary">Tickets</a>';
                            if(Auth::user()->user_role == 2){

                               $action .=  '<a href="javascript:void(0);" data-url="'. route('delete-customer', ['customer_id' => $getCustomers->id])  .'" class="change-psd-btn px-2 delete_customer" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete customer"><i class="fas fa-trash"></i></a>';
                            }
                             
                return $action;
            })
              
            ->skipPaging() 
            ->rawColumns(['name','store','invoice_str','action'])
        ->make(true); 
    } 

    public function getCustomers1(Request $request){
        // $this->getdata();
        // die();
        $params = $request->post();

        $stats = [];
         $getContests = Contest::orderBy('id', 'desc')->get();

        $getShops = Shop::orderBy('shop_name', 'asc')->get();
        $getCustomers = array();
        return view('admin.customers.index2', ['customers' => $getCustomers, 'params' => $params, 'stats' => $stats, 'contests' => $getContests, 'shops' => $getShops]);
        return view('admin.customers.index2');
    }

    //New Format to show data in datatable end

    public function customerDetails(Request $request, $customer_id)
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

        // $getCustomerDetails = Customer::whereHas('invoices')->where(['id' => $customer_id])->first();
        $getCustomerDetails = User::where('status', 1)->where(['id' => $customer_id])->first();
        if ($getCustomerDetails)
        {
            $getCustomerDetails->is_reviewed = 1;
            $getCustomerDetails->save();
            return view('admin.customers.customer_details', ['customer' => $getCustomerDetails, 'params' => $params]);
        }
        else
        {
            Session::flash('message', 'Customer not found.'); 
            Session::flash('class', 'danger');
        }

        return redirect()->route('customers', ['contest_id' => $this->current_active_contest_id]);
    }

    public function deleteCustomer(Request $request, $id)
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

        $customer = User::find($id);

        if (!$customer)
        {
            Session::flash('message', 'No Customer Found'); 
            Session::flash('class', 'danger');  
            return redirect()->route('customers', ['contest_id' => $this->current_active_contest_id]);
        }

        $customer->status = 4;
        if ($customer->save())
        {
            Session::flash('message', 'Customer has deleted successfully.'); 
            Session::flash('class', 'success');
        }
        else
        {
            Session::flash('message', 'No Customer Found'); 
            Session::flash('class', 'danger');  
        }
        return redirect()->route('customers', ['contest_id' => $this->current_active_contest_id]);

    }

    public function customerTickets(Request $request, $customer_id)
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

        $getCustomerDetails = User::where('status', 1)->where(['id' => $customer_id])->first();
        if ($getCustomerDetails)
        {
            $getCustomerDetails->is_reviewed = 1;
            $getCustomerDetails->save();
        }
        else
        {
            Session::flash('message', 'Customer not found.'); 
            Session::flash('class', 'danger');

            return redirect()->back();
        }

        $getInvoices = invoiceDetail::where(array('user_id' => $customer_id))->get();
        return view('admin.customers.customer_tickets', ['tickets' => $getInvoices, 'params' => $params]);
    }

    public function approvedCustomerInvoice(Request $request, $id, $customer_id)
    {   
        ini_set('memory_limit','2000M');
        ini_set('max_execution_time', '0');
        if(Auth::user()->user_role == 1)
        {
            return redirect()->route('users');
        }
        $params = $request->post();

        $getCustomerInvoiceDetails = customerInvoice::whereHas('user', function($q) {
            $q->where('status', 1);
        })->where(['id' => $id, 'user_id' => $customer_id])->first();
        if ($getCustomerInvoiceDetails && $getCustomerInvoiceDetails->tag != 'approved')
        {
            $getCustomerInvoiceDetails->tag = 'approved';
            $getCustomerInvoiceDetails->approved_by = Auth::user()->id;
            if($getCustomerInvoiceDetails->save())
            {
                $customerDetails = User::whereHas('invoices')->where(['id' => $customer_id])->first();
                if($customerDetails)
                {
                    $customerDetails->total_invoice_val = $customerDetails->total_invoice_val + $getCustomerInvoiceDetails->invoice_amount;
                }
                else
                {
                    $customerDetails = User::where(['id' => $customer_id])->first();
                    if($customerDetails)
                    {
                        $customerDetails->total_invoice_val = $customerDetails->total_invoice_val + $getCustomerInvoiceDetails->invoice_amount;
                    }
                }
                
                $unitAmount = 25;

                if($customerDetails->total_invoice_val > 0)
                {
                    $entriesCount = (int) ($customerDetails->total_invoice_val / $unitAmount);
                }
                else
                {
                    $entriesCount = 0;
                }
                $alreadyCount = $customerDetails->invoiceDetails->count();
                if($alreadyCount < $entriesCount)
                {
                    $addingRows = $entriesCount - $alreadyCount;

                    for ($i=1; $i <= $addingRows; $i++) 
                    {
                        $InvoiceDetail = new invoiceDetail;
                        $InvoiceDetail->contest_id = $customerDetails->contest_id;
                        $InvoiceDetail->user_id = $customerDetails->id;
                        $InvoiceDetail->invoice_id = $getCustomerInvoiceDetails->id;
                        $InvoiceDetail->invoice_amount = $unitAmount;
                        $InvoiceDetail->save();
                    }
                    $customerDetails->ticket_count = $customerDetails->ticket_count + $addingRows;
                }
                $customerDetails->save();

                return response()->json(['status' => 1, 'message' => 'Tu factura No. ' . $getCustomerInvoiceDetails->num_bill . ' de $' . $getCustomerInvoiceDetails->invoice_amount . ' ha sido aprobada. Mucha suerte', 'invoiceData' => $getCustomerInvoiceDetails ]);
            }
            else
            {
                return response()->json(['status' => 0, 'message' => 'Something went wrong.']);
            }
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'Invoice not found.']);
        }
    }


    public function editCustomerDetails(Request $request, $customer_id)
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

        $customerDetails = User::whereHas('invoices')->where(['id' => $customer_id])->first();
        if ($customerDetails)
        {
            return view('admin.customers.edit_customer', ['customer' => $customerDetails, 'params' => $params]);
        }
        else
        {
            Session::flash('message', 'Customer not found.'); 
            Session::flash('class', 'danger');
        }

        return redirect()->route('customers', ['contest_id' => $this->current_active_contest_id]);
    }

    public function updateCustomerDetails(Request $request)
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
            'name'             => 'required',
            'last_name'             => 'required',
            'indentification_card'             => 'required',
            'email'            => 'required|email',
            'phone_number'         => 'required',
            'direction'         => 'required',
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

            return redirect()->route('edit-customer-details', ['customer_id' => $request->user_id])->withInput($request->all());
        }

        $params = $request->post();

        $customerDetails = User::whereHas('invoices')->where(['id' => $params['user_id']])->first();
        if (!$customerDetails)
        {
            Session::flash('message', 'No Customer Found'); 
            Session::flash('class', 'danger');  
            return redirect()->route('customers', ['contest_id' => $this->current_active_contest_id]);
        }

        $customerDetails->name = $params['name'];
        $customerDetails->last_name = $params['last_name'];
        $customerDetails->indentification_card = $params['indentification_card'];
        $customerDetails->email = $params['email'];
        $customerDetails->phone_number = $params['phone_number'];
        $customerDetails->direction = $params['direction'];

        if($customerDetails->save())
        {
            Session::flash('message', 'Customer has updated successfully.'); 
            Session::flash('class', 'success');
        }
        else
        {
            Session::flash('message', 'Something went wrong.'); 
            Session::flash('class', 'danger'); 
        }

        return redirect()->route('customer-details', ['id' => $request->user_id]);
    }
	
    public function customerInvoiceDetails(Request $request)
    {	
		if(!empty($_POST['customer_invoice_id']))
        {
            $customer_invoice_id = $_POST['customer_invoice_id'];
            $customer_Invoices = customerInvoice::where('user_id', $_POST['user_id'])->whereIn('id', $customer_invoice_id)->where('tag', '!=' , 'approved')->orderBy('contest_id', 'asc')->get();

            if(sizeof($customer_Invoices))
            {
                $customer_Invoices = $customer_Invoices->groupBy('contest_id');
            }
            /*echo "<pre>";
            print_r($customer_Invoices); die;*/

            foreach ($customer_Invoices as $key => $value)
            {
                $contestId = $key;
                $totalticket = 0;
                $totalinv = 0;
                $pacifica_total_amount = 0;
                $normal_total_amount = 0;
                
                $pacifica_count = 0;
                $normal_count = 0;
                
                $extra_ticket_count = 0;
                foreach($value as $k => $invoice_ids) 
                {
                    $customerInvoice  =  customerInvoice::find($invoice_ids['id']);
                    if ($customerInvoice && $customerInvoice->tag != 'approved')
                    {
                        if($customerInvoice->payment_method == 'PACIFICA')
                        {                         
                            $pacifica_total_amount =  $pacifica_total_amount + $customerInvoice->invoice_amount;
                        }
                        else
                        {                      
                            $normal_total_amount = $normal_total_amount + $customerInvoice->invoice_amount;
                        }

                        $totalinv = $totalinv + $customerInvoice->invoice_amount;
                        $customerInvoice->tag = 'approved';
                        $customerInvoice->approved_by = Auth::user()->id;
                        $customerInvoice->save();   
                    }
                }

                if($pacifica_total_amount > 0)
                {
                    // $pacifica_count = floor($pacifica_total_amount/12.5); 
                    $pcount = $pacifica_total_amount/12.5;
                    $pacifica_count = (int) $pcount;
                    $extra_ticket_count = $extra_ticket_count +  ($pcount - $pacifica_count);
                }

                if($normal_total_amount > 0)
                {
                    // $normal_count = floor($normal_total_amount/25); 
                    $ncount = $normal_total_amount/25;
                    $normal_count = (int) $ncount;
                    $extra_ticket_count = $extra_ticket_count +  ($ncount - $normal_count);
                }   

                $UserUpdate  =  User::find($_POST['user_id']);
                if(!empty($UserUpdate))
                {
                    if($UserUpdate->total_invoice_val == '')
                    {
                        $UserUpdate->total_invoice_val = $totalinv;
                    }
                    else
                    {
                        $UserUpdate->total_invoice_val = $UserUpdate->total_invoice_val + $totalinv;
                    }

                    $u_extra_ticket = 0;
                    
                    /*$current_year = date('Y'); 
                    $contest_ids = Contest::whereYear('start_date', $current_year)->pluck('id')->toArray();*/

                    $exist = invoiceDetail::where('user_id', $_POST['user_id'])->where('contest_id', $contestId)->first(); 
                    if($exist)
                    {
                        $u_extra_ticket = $UserUpdate->extra_ticket_count;
                    }

                    $eticket_count = $extra_ticket_count + $u_extra_ticket;
                    $extra_ticket_count = (int) $eticket_count;
                    $remaining_extra_ticket_count = $eticket_count - $extra_ticket_count;

                    $totalticket = $pacifica_count + $normal_count + $extra_ticket_count;
                    $normal_count = $normal_count + $extra_ticket_count;

                    $UserUpdate->extra_ticket_count = $remaining_extra_ticket_count;
                    
                    if($UserUpdate->ticket_count == 0)
                    {
                        $UserUpdate->ticket_count = $totalticket;
                    }
                    else
                    {
                        $UserUpdate->ticket_count = $UserUpdate->ticket_count + $totalticket;
                    }
                    
                    if($UserUpdate->invoiceDetails()->where('contest_id', $contestId)->count() > 14)
                    {
                        $permissions = 'Acceso permitido';
                        $name = "Nombre: ". $UserUpdate->name . ' ' . $UserUpdate->last_name;
                        $number = "Numero Celular: " . $UserUpdate->phone_number;
                        $ruc = "Cedula/RUC/Passport: " . $UserUpdate->indentification_card;
                        $ticket = "Entradas: " . $UserUpdate->invoiceDetails()->where('contest_id', $contestId)->count();
                        
                        $info = $permissions . "\n" . $name . "\n" . $number . "\n" . $ruc . "\n" . $ticket;
                        $qr_image = base64_encode(QrCode::format('png')->size(150)->generate($info));
                        $UserUpdate->qr_image = $qr_image;
                    }

                    $UserUpdate->save();
                    //Session::flash('message', 'Invoice successfully approved.'); 
                    //return redirect()->route('customer-details', ['id' => $_POST['user_id']]);
                } 
                            
                if($pacifica_count > 0)
                {
                    for($i = 0; $i < $pacifica_count; $i++)
                    {
                        $InvoiceDetail = new invoiceDetail;
                        $InvoiceDetail->contest_id = $contestId;
                        $InvoiceDetail->user_id = $_POST['user_id'];
                        $InvoiceDetail->invoice_id = '';
                        $InvoiceDetail->invoice_amount = 12.5;
                        //echo'<pre>';
                        //print_r($InvoiceDetail);
                        $InvoiceDetail->save();
                    }
                }

                if($normal_count > 0)
                {
                    for($i = 0; $i < $normal_count; $i++)
                    {
                        $InvoiceDetail = new invoiceDetail;
                        $InvoiceDetail->contest_id = $contestId;
                        $InvoiceDetail->user_id = $_POST['user_id'];
                        $InvoiceDetail->invoice_id = '';
                        $InvoiceDetail->invoice_amount = 25;                    
                        $InvoiceDetail->save();
                    }
                }
            }

            Session::flash('message', 'Invoice successfully approved.'); 
            return redirect()->route('customer-details', ['id' => $_POST['user_id']]);
        }
        else
        {
            return redirect()->route('customers');
        }
	}
    
	function customerInvoiceApproved($id, $customer_id){
		
		if($id > 0){
			$pacifica_total_amount = 0;
			$normal_total_amount = 0;
			$pacifica_count = 0;
			$normal_count = 0;
			$customerInvoice = customerInvoice::where('id', $id)->first(); 
			if ($customerInvoice && $customerInvoice->tag != 'approved'){
				if($customerInvoice->payment_method == 'PACIFICA'){ 
					$pacifica_total_amount =   $customerInvoice->invoice_amount;
				}else{					
					$normal_total_amount =  $customerInvoice->invoice_amount;
				}
				$totalinv = $customerInvoice->invoice_amount;
				$customerInvoice->tag = 'approved';
				$customerInvoice->approved_by = Auth::user()->id;
				$customerInvoice->save();	
				if($pacifica_total_amount > 0){
					$pacifica_count = floor($pacifica_total_amount/12.5); 
				}
				if($normal_total_amount > 0){
					 $normal_count = floor($normal_total_amount/25); 
				}
				$UserUpdate  =  User::find($customer_id);
				if(!empty($UserUpdate)){
					if($UserUpdate->total_invoice_val ==''){
						$UserUpdate->total_invoice_val = $totalinv;
					}else{
						$UserUpdate->total_invoice_val = $UserUpdate->total_invoice_val + $totalinv;
					}
					$totalticket = $pacifica_count + $normal_count;
					if($UserUpdate->ticket_count == 0){
						$UserUpdate->ticket_count = $totalticket;
					}else{
						$UserUpdate->ticket_count = $UserUpdate->ticket_count + $totalticket;
					}
					$UserUpdate->save();
				}
				if($pacifica_count > 0){
					for($i=0;$i<$pacifica_count;$i++){
						$InvoiceDetail = new invoiceDetail;
						$InvoiceDetail->contest_id = $customerInvoice->contest_id;
						$InvoiceDetail->user_id = $customer_id;
						$InvoiceDetail->invoice_id = '';
						$InvoiceDetail->invoice_amount = 12.5;
						//echo"<pre>";
						//print_r($InvoiceDetail);
						$InvoiceDetail->save();
					}
				}
				if($normal_count > 0){
					for($i=0;$i<$normal_count;$i++){
						$InvoiceDetail = new invoiceDetail;
						$InvoiceDetail->contest_id = $customerInvoice->contest_id;
						$InvoiceDetail->user_id = $customer_id;
						$InvoiceDetail->invoice_id = '';
						$InvoiceDetail->invoice_amount = 25;
						//echo"<pre>";
						//print_r($InvoiceDetail);						
						$InvoiceDetail->save();
					}
				}
				/* echo $totalinv;
				echo"<br>";
				echo $totalticket;
				echo"<br>";
				echo $pacifica_count;
				echo"<br>";
				echo $normal_count; */
				Session::flash('message', 'Invoice successfully approved.'); 
				return redirect()->route('customer-details', ['id' => $customer_id]);
			}
			//echo"<pre>";
			//print_r($customerInvoice);
			die;
		}else{
            return redirect()->route('customers');
        }
	}
}