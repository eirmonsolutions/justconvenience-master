<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use Session;

use App\User;
use App\Order;
use App\orderDetail;
use Yajra\Datatables\Datatables;
use App\Exports\Customers;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function __construct()
	{
        ini_set('memory_limit','512M');
        ini_set('max_execution_time', '0');
	    $this->middleware(function ($request, $next) {
	        if(Auth::user()->user_role == 3)
	        {
	            return redirect()->route('dashboard');
	        }

	        return $next($request);
	    });
	}

    public function downloadCustomerExcel(Request $request)
    {
        $params = $request->input();
        ob_end_clean();
        return Excel::download(new Customers($params), 'Customers.xlsx');
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

        $params = $request->input();
        $getStores = User::where(['user_role' => 3])->orderBy('id', 'desc')->get();

        return view('admin.orders.index', ['params' => $params, 'stores' => $getStores]);
    }

    public function get_orders(Request $request)
    {
        $params = $request->input();

        if(isset($params['length']) && !empty($params['length']))
        {
            $limit = $params['length'];
        }
        else
        {
            $limit = 10;
        }
        $start = $params['start'];

        $total_records = Order::whereHas('user', function($q) use($params) {
                        /*$q->where('status', 1);
                        
                        if(isset($params['total_invoice_val']) && !empty($params['total_invoice_val']))
                        {
                            $q->where('total_invoice_val', '>=', $params['total_invoice_val']);
                        }

                        if(isset($params['search_fields']) && !empty($params['search_fields']))
                        {
                            $q->where(function($qs) use($params) {
                                $qs->where('email', 'Like', $params['search_fields'].'%');
                                $qs->orWhere('name', 'Like', $params['search_fields'].'%');
                                $qs->orWhere('last_name', 'Like', $params['search_fields'].'%');
                                $qs->orWhere('phone_number', 'Like', $params['search_fields'].'%');
                                $qs->orWhere('indentification_card', 'Like', $params['search_fields'].'%');
                                $qs->orWhere('direction', 'Like', $params['search_fields'].'%'); 
                                $qs->orWhere('invoice_details.id', 'Like', $params['search_fields'].'%'); 
                             });
                        }*/
                    });

                    
        if (isset($params['start_date']) && !empty($params['start_date']) && isset($params['end_date']) && !empty($params['end_date']))
        {
            $start_date = date('Y-m-d', strtotime($params['start_date']));
            $end_date = date('Y-m-d', strtotime($params['end_date']));

            $total_records->whereDate('created_at' , '>=', $start_date);
            $total_records->whereDate('created_at' , '<=', $end_date);
        }
        else if(isset($params['start_date']) && !empty($params['start_date']))
        {
            $start_date = date('Y-m-d', strtotime($params['start_date']));
            $total_records->whereDate('created_at' , '>=', $start_date);

        }
        else if(isset($params['end_date']) && !empty($params['end_date']))
        {
            $end_date = date('Y-m-d', strtotime($params['end_date']));
            $total_records->whereDate('created_at' , '<=', $end_date);
        }

        if (isset($params['store_id']) && !empty($params['store_id']))
        {
            $total_records->where('store_id', $params['store_id']);
        }

        if (isset($params['status']) && !empty($params['status']))
        {
            $total_records->where('status', $params['status']);
        }
        $total_records = $total_records1 = $total_records->count();


        $getOrders = Order::whereHas('user', function($q) use($params) {
                /*$q->where('status', 1);
                
                if(isset($params['total_invoice_val']) && !empty($params['total_invoice_val']))
                {
                    $q->where('total_invoice_val', '>=', $params['total_invoice_val']);
                }

                if(isset($params['search_fields']) && !empty($params['search_fields']))
                {
                    $q->where(function($qs) use($params) {
                        $qs->where('email', 'Like', $params['search_fields'].'%');
                        $qs->orWhere('name', 'Like', $params['search_fields'].'%');
                        $qs->orWhere('last_name', 'Like', $params['search_fields'].'%');
                        $qs->orWhere('phone_number', 'Like', $params['search_fields'].'%');
                        $qs->orWhere('indentification_card', 'Like', $params['search_fields'].'%');
                        $qs->orWhere('direction', 'Like', $params['search_fields'].'%');
                        $qs->orWhere('invoice_details.id', 'Like', $params['search_fields'].'%'); 
                     });
                }*/
            });

        if (isset($params['start_date']) && !empty($params['start_date']) && isset($params['end_date']) && !empty($params['end_date']))
        {
            $start_date = date('Y-m-d', strtotime($params['start_date']));
            $end_date = date('Y-m-d', strtotime($params['end_date']));

            $getOrders->whereDate('created_at' , '>=', $start_date);
            $getOrders->whereDate('created_at' , '<=', $end_date);
        }
        else if(isset($params['start_date']) && !empty($params['start_date']))
        {
            $start_date = date('Y-m-d', strtotime($params['start_date']));
            $getOrders->whereDate('created_at' , '>=', $start_date);

        }
        else if(isset($params['end_date']) && !empty($params['end_date']))
        {
            $end_date = date('Y-m-d', strtotime($params['end_date']));
            $getOrders->whereDate('created_at' , '<=', $end_date);
        }

        if (isset($params['store_id']) && !empty($params['store_id']))
        {
            $getOrders->where('store_id', $params['store_id']);
        }

        if (isset($params['status']) && !empty($params['status']))
        {
            $getOrders->where('status', $params['status']);
        }
        $getOrders = $getOrders->skip($start)->take($limit)->orderBy('id', 'desc')->get();

        return Datatables::of($getOrders)
        ->with([
            "recordsTotal" => $total_records,
            "recordsFiltered" => $total_records1,
        ])
        ->addColumn('customer_name', function($getOrders) {
            return $getOrders->user->name;
        })
        ->addColumn('order_number', function($getOrders) {
            return $getOrders->order_number;
        })
        ->addColumn('delivery_time', function($getOrders) {
            return $getOrders->delivery_time;
        })
        ->addColumn('total_quantity', function($getOrders) {
            return $getOrders->total_quantity;
        })
        ->addColumn('service_charges', function($getOrders) {
            return $getOrders->service_charges;
        })
        ->addColumn('pay_amount', function($getOrders) {
            return $getOrders->pay_amount;
        })
        ->addColumn('status', function($getOrders) {

            $status = 'Unknown';
            if($getOrders->status == 1)
            {
                $status = 'Pending';
            }
            else if($getOrders->status == 2)
            {
                $status = 'Ready';
            }
            else if($getOrders->status == 3)
            {
                $status = 'Completed';
            }
            else if($getOrders->status == 4)
            {
                $status = 'Declined';
            }

            return $status;
        })
        ->addColumn('action', function($getOrders) {
            $action = '<a href="'. route("order-details", ["id" => $getOrders->id]).'" class="change-psd-btn"><i class="fas fa-eye"></i></a>';
            return $action;
        })
        ->skipPaging()
        ->rawColumns(['action'])
        ->make();
    }

    public function orderDetails(Request $request, $order_id)
    {
        $params = $request->post();

        $getOrderDetails = order::with('orderDetails')->where(['id' => $order_id])->first();
        if ($getOrderDetails)
        {
            return view('admin.orders.order_details', ['order' => $getOrderDetails, 'params' => $params]);
        }
        else
        {
            Session::flash('message', 'Order not found.'); 
            Session::flash('class', 'danger');
        }

        return redirect()->route('orders');
    }

    public function editCustomerDetails(Request $request, $customer_id)
    {
        $params = $request->post();

        $customerDetails = User::where(['id' => $customer_id, 'user_role' => 4])->first();
        if ($customerDetails)
        {
            return view('admin.customers.edit_customer', ['customer' => $customerDetails, 'params' => $params]);
        }
        else
        {
            Session::flash('message', 'Customer not found.'); 
            Session::flash('class', 'danger');
        }

        return redirect()->route('customers');
    }

    public function updateCustomerDetails(Request $request)
    {
        // create the validation rules ------------------------
        $rules = array(
            'name'             => 'required',
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

        $customerDetails = User::where(['id' => $request->user_id, 'user_role' => 4])->first();
        if (!$customerDetails)
        {
            Session::flash('message', 'No Customer Found'); 
            Session::flash('class', 'danger');  
            return redirect()->route('customers');
        }

        $customerDetails->name = $params['name'];

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
}