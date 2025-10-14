<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use Session;

use App\User;

class StoreController extends Controller
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

    public function index(Request $request)
    {
        $params = $request->post();

        $getStores = User::where(array('user_role' => 3))->orderBy('id', 'desc')->get();

        return view('admin.stores.index', ['stores' => $getStores, 'params' => $params]);
    }

    public function addStore(Request $request)
    {
        return view('admin.stores.add_store');
    }

    public function saveStore(Request $request)
    {
        // create the validation rules ------------------------
        $rules = [
                'name'   => 'required',
                'phone_number'   => 'required',
                'email'   => 'required|email|unique:users',
                'password' => 'required|min:4',
                'store_name'   => 'required',
                'address'   => 'required',
                'city'   => 'required',
                'state'   => 'required',
                'country'   => 'required',
                'zipcode'   => 'required',
                'delivery_service'   => 'required',
        ];

        // do the validation ----------------------------------
        // validate against the inputs from our form
        $validator = Validator::make($request->all(), $rules);

        // check if the validator failed -----------------------
        if ($validator->fails()) {

            // get the error messages from the validator
            // $messages = $validator->messages();

            Session::flash('message', $validator->errors()->first()); 
            Session::flash('class', 'danger');

            return redirect()->route('add-store')->withInput($request->all());
        }

        $params = $request->post();

        $store = new User;  
        $store->name = $params['name'];
        $store->phone_number = $params['phone_number'];
        $store->email = $params['email'];
        $store->password = bcrypt($params['password']);
        $store->store_name = $params['store_name'];
        $store->address = $params['address'];
        $store->city = $params['city'];
        $store->state = $params['state'];
        $store->country = $params['country'];
        $store->zipcode = $params['zipcode'];
        $store->delivery_service = $params['delivery_service'];

        if(!empty($request->delivery_service))
        {
            $rules = [
                'minimum_order_amount' => 'min:0',
                'delivery_charges'  => 'min:0'
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) 
            {
                Session::flash('message', $validator->errors()->first()); 
                Session::flash('class', 'danger');

                return redirect()->route('add-store')->withInput($request->all());
            }

            $store->minimum_order_amount = $params['minimum_order_amount'];
            $store->delivery_charges = $params['delivery_charges'];
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/stores');
            $image->move($destinationPath, $name);
            
            $store->image = 'public/stores/' . $name;
        }

        $store->store_opening_status = $params['store_opening_status'];
        $store->is_store_paid = $params['is_store_paid'];
        $store->merchantID = $params['merchantID'];
        $store->merchantSecret = $params['merchantSecret'];
        
        $store->status = 1;
        $store->user_role = 3;
        if($store->save())
        {
        	Session::flash('message', 'Store has created successfully.'); 
        	Session::flash('class', 'success');
        }
        else
        {
            Session::flash('message', 'Something went wrong.'); 
            Session::flash('class', 'danger'); 
        }

        return redirect()->route('stores');
    }

    public function editStore(Request $request, $id)
    {
        $getContestData = User::find($id);
        if ($getContestData)
        {
        	return view('admin.stores.edit_store', ['data' => $getContestData]);
        }
        else
        {
        	Session::flash('message', 'No Store Found'); 
        	Session::flash('class', 'danger');	
        	return redirect()->route('stores');
        }

    }

    public function updateStore(Request $request)
    {
        $params = $request->post();
        $store = User::find($params['id']);
        if (!$store)
        {
            Session::flash('message', 'No Store Found'); 
            Session::flash('class', 'danger');  
            return redirect()->route('stores');
        }
        
        if(Auth::user()->user_role == 2)
        {
            // create the validation rules ------------------------
            $rules = array(
                'name'   => 'required',
                'phone_number'   => 'required',
                'email'   => 'required|email|unique:users,email,'.$request->id.',id,deleted_at,NULL',
                'store_name'   => 'required',
                'address'   => 'required',
                'city'   => 'required',
                'state'   => 'required',
                'country'   => 'required',
                'zipcode'   => 'required',
                'delivery_service'   => 'required',
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

                return redirect()->route('edit-store', ['store_id' => $request->id])->withInput($request->all());
            }
        
            $store->name = $params['name'];
            $store->phone_number = $params['phone_number'];
            $store->email = $params['email'];
            // $store->password = bcrypt($params['password']);
            $store->store_name = $params['store_name'];
            $store->address = $params['address'];
            $store->city = $params['city'];
            $store->state = $params['state'];
            $store->country = $params['country'];
            $store->zipcode = $params['zipcode'];
            $store->delivery_service = $params['delivery_service'];

            if(!empty($request->delivery_service))
            {
                $rules = [
                    'minimum_order_amount' => 'min:0',
                    'delivery_charges'  => 'min:0'
                ];

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) 
                {
                    Session::flash('message', $validator->errors()->first()); 
                    Session::flash('class', 'danger');

                    return redirect()->route('add-store')->withInput($request->all());
                }

                $store->minimum_order_amount = $params['minimum_order_amount'];
                $store->delivery_charges = $params['delivery_charges'];
            }

            $store->store_opening_status = $params['store_opening_status'];
            $store->is_store_paid = $params['is_store_paid'];

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/stores');
                $image->move($destinationPath, $name);
                
                $store->image = 'public/stores/' . $name;
            }
        }

        $store->merchantID = $params['merchantID'];
        $store->merchantSecret = $params['merchantSecret'];

        if($store->save())
        {
        	Session::flash('message', 'Store has updated successfully.'); 
        	Session::flash('class', 'success');
        }
        else
        {
            Session::flash('message', 'Something went wrong.'); 
            Session::flash('class', 'danger'); 
        }

        return redirect()->route('stores');
    }

    public function updatestoreStatus(Request $request, $id, $status)
    {
        if (\Request::isMethod('post'))
        {
            return redirect()->route('stores');
        }

        $getStoreData = User::find($id);
        if (!$getStoreData)
        {
            Session::flash('message', 'No Store Found'); 
            Session::flash('class', 'danger');  
            return redirect()->route('stores');
        }

        $message = 'Store Deactivated successfully';
        if ($status)
        {
            $message = 'Store Activated successfully';
        }

        $getStoreData->status = $status;

        if ($getStoreData->save())
        {
            return response()->json(['message' => $message, 'status' => 1]);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'Something went wrong.']);
        }
    }
}