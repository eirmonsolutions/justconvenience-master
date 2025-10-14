<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use Session;

use App\User;
use Yajra\Datatables\Datatables;
use App\Exports\Customers;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    
   public function __construct()
	{
	    
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

        return view('admin.customers.index', ['params' => $params]);
    }

    public function get_customers(Request $request)
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

        $total_records = User::where('user_role', 4);

        if(isset($params['total_invoice_val']) && !empty($params['total_invoice_val']))
        {
            $total_records->where('total_invoice_val', '>=', $params['total_invoice_val']);
        }

        if(isset($params['search_fields']) && !empty($params['search_fields']))
        {
            $total_records->where(function($qs) use($params) {
                $qs->where('email', 'Like', $params['search_fields'].'%');
                $qs->orWhere('name', 'Like', $params['search_fields'].'%');
                $qs->orWhere('phone_number', 'Like', $params['search_fields'].'%');
                $qs->orWhere('city', 'Like', $params['search_fields'].'%');
                $qs->orWhere('state', 'Like', $params['search_fields'].'%'); 
                $qs->orWhere('zipcode', 'Like', $params['search_fields'].'%'); 
                $qs->orWhere('id', 'Like', $params['search_fields'].'%'); 
            });
        }

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

        $total_records = $total_records1 = $total_records->count();


        $getCustomers = User::where('user_role', 4);

        if(isset($params['total_invoice_val']) && !empty($params['total_invoice_val']))
        {
            $getCustomers->where('total_invoice_val', '>=', $params['total_invoice_val']);
        }

        if(isset($params['search_fields']) && !empty($params['search_fields']))
        {
            $getCustomers->where(function($qs) use($params) {
                $qs->where('email', 'Like', $params['search_fields'].'%');
                $qs->orWhere('name', 'Like', $params['search_fields'].'%');
                $qs->orWhere('phone_number', 'Like', $params['search_fields'].'%');
                $qs->orWhere('city', 'Like', $params['search_fields'].'%');
                $qs->orWhere('state', 'Like', $params['search_fields'].'%'); 
                $qs->orWhere('zipcode', 'Like', $params['search_fields'].'%'); 
                $qs->orWhere('id', 'Like', $params['search_fields'].'%'); 
            });
        }

        if (isset($params['start_date']) && !empty($params['start_date']) && isset($params['end_date']) && !empty($params['end_date']))
        {
            $start_date = date('Y-m-d', strtotime($params['start_date']));
            $end_date = date('Y-m-d', strtotime($params['end_date']));

            $getCustomers->whereDate('created_at' , '>=', $start_date);
            $getCustomers->whereDate('created_at' , '<=', $end_date);
        }
        else if(isset($params['start_date']) && !empty($params['start_date']))
        {
            $start_date = date('Y-m-d', strtotime($params['start_date']));
            $getCustomers->whereDate('created_at' , '>=', $start_date);

        }
        else if(isset($params['end_date']) && !empty($params['end_date']))
        {
            $end_date = date('Y-m-d', strtotime($params['end_date']));
            $getCustomers->whereDate('created_at' , '<=', $end_date);
        }

        $getCustomers = $getCustomers->skip($start)->take($limit)->orderBy('id', 'desc')->get();

        return Datatables::of($getCustomers)
        ->with([
            "recordsTotal" => $total_records,
            "recordsFiltered" => $total_records1,
        ])
        ->addColumn('name', function($getCustomers) {
            return $getCustomers->name;
        })
        ->addColumn('email', function($getCustomers) {
            return $getCustomers->email;
        })
        ->addColumn('phone_number', function($getCustomers) {
            return $getCustomers->phone_number;
        })
        ->addColumn('city', function($getCustomers) {
            return $getCustomers->city;
        })
        ->addColumn('state', function($getCustomers) {
            return $getCustomers->state;
        })
        ->addColumn('action', function($getRecords) {
            $action = '<a href="'. route("customer-details", ["id" => $getRecords->id]).'" class="change-psd-btn"><i class="fas fa-eye"></i></a>';
            return $action;
        })
        ->skipPaging()
        ->rawColumns(['action'])
        ->make(); 
    }

    public function customerDetails(Request $request, $customer_id)
    {
        $params = $request->post();

        $getCustomerDetails = User::where(['id' => $customer_id, 'user_role' => 4])->first();
        if ($getCustomerDetails)
        {
            return view('admin.customers.customer_details', ['customer' => $getCustomerDetails, 'params' => $params]);
        }
        else
        {
            Session::flash('message', 'Customer not found.'); 
            Session::flash('class', 'danger');
        }

        return redirect()->route('customers');
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