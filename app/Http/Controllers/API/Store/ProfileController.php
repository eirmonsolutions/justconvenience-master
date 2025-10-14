<?php

namespace App\Http\Controllers\API\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Session;
use Validator;

use App\User;
use App\Order;

use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        $user = $request->checkTokenExistance->user;
        return response()->json(['status' => 1, 'message' => 'User Data.', 'user' => $user])->setStatusCode(200);
    }

    public function profileupdate(Request $request)
    {
        $store = $request->checkTokenExistance->user;
        //--- Validation Section

        $rules = [
            'name'   => 'required',
            'phone_number'   => 'required',
            'email'   => 'required|email|unique:users,email,'.$store->id.',id,deleted_at,NULL',
            // 'password' => 'required|min:4',
            'store_name'   => 'required',
            'address'   => 'required',
            'city'   => 'required',
            'state'   => 'required',
            'country'   => 'required',
            'zipcode'   => 'required',
            'delivery_service'   => 'required',
            'store_opening_status'   => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()->first()])->setStatusCode(200);
        }
        //--- Validation Section Ends

        $params = $request->post();
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
            if ($validator->fails()) {
                return response()->json(['status' => 0, 'message' => $validator->errors()->first()])->setStatusCode(200);
            }

            $store->minimum_order_amount = $params['minimum_order_amount'];
            $store->delivery_charges = $params['delivery_charges'];
        }

        $store->store_opening_status = $params['store_opening_status'];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/stores');
            $image->move($destinationPath, $name);
            
            $store->image = 'public/stores/' . $name;
        }

        if(isset($params['latitudes']) && !empty($params['latitudes']))
        {
            $store->latitudes = $params['latitudes'];
        }

        if(isset($params['longitudes']) && !empty($params['longitudes']))
        {
            $store->longitudes = $params['longitudes'];
        }

        if($store->save())
        {
            return response()->json(['status' => 1, 'message' => 'Your store has been updated Successfully.'])->setStatusCode(200);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'Something went wrong.'])->setStatusCode(200);
        }
    }

    public function storeOpeningStatusupdate(Request $request)
    {
        $store = $request->checkTokenExistance->user;
        //--- Validation Section

        $rules = [
            'store_opening_status'   => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()->first()])->setStatusCode(200);
        }
        //--- Validation Section Ends

        $params = $request->post();
        
        $store->store_opening_status = $params['store_opening_status'];
        if($store->save())
        {
            return response()->json(['status' => 1, 'message' => 'Opening status has been updated Successfully.'])->setStatusCode(200);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'Something went wrong.'])->setStatusCode(200);
        }
    }

    public function changePassword(Request $request)
    {
        if (\Request::isMethod('post'))
        {
            $store = $request->checkTokenExistance->user;

            $params = $request->post(); 
            
            // create the validation rules ------------------------
            $rules = array(
                'current_password' => 'required',
                'new_password' => 'required|min:5|different:current_password',
                'password_confirmation' => 'required|same:new_password'
            );

            // do the validation ----------------------------------
            // validate against the inputs from our form
            $validator = Validator::make($request->all(), $rules);

            // check if the validator failed -----------------------
            if ($validator->fails()) {
                return response()->json(['status' => 0, 'message' => $validator->errors()->first()])->setStatusCode(200);
            }

            if (!\Hash::check($params['current_password'], $store->password)) 
            {
                return response()->json(['status' => 0, 'message' => 'Current password is wrong.'])->setStatusCode(200);
            }

            $store->password = bcrypt($params['new_password']);
            if ($store->save())
            {
                return response()->json(['status' => 1, 'message' => 'Password has updated successfully.'])->setStatusCode(200);
            }
            else
            {
                return response()->json(['status' => 0, 'message' => 'Something went wrong.'])->setStatusCode(200);
            }
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'Method not allowed.'])->setStatusCode(200);
        }
    }

    public function earning(Request $request)
    {
        $user = $request->checkTokenExistance->user;

        $getEarning = Order::where(function($q) use ($user) {
                                        $q->where(['store_id' => $user->id, 'status' => 3]);
                                    });

        if (isset($params['start_date']) && !empty($params['start_date']) && isset($params['end_date']) && !empty($params['end_date']))
        {
            $start_date = date('Y-m-d', strtotime($params['start_date']));
            $end_date = date('Y-m-d', strtotime($params['end_date']));

            $getEarning->whereDate('created_at' , '>=', $start_date);
            $getEarning->whereDate('created_at' , '<=', $end_date);
        }
        else if(isset($params['start_date']) && !empty($params['start_date']))
        {
            $start_date = date('Y-m-d', strtotime($params['start_date']));
            $getEarning->whereDate('created_at' , '>=', $start_date);

        }
        else if(isset($params['end_date']) && !empty($params['end_date']))
        {
            $end_date = date('Y-m-d', strtotime($params['end_date']));
            $getEarning->whereDate('created_at' , '<=', $end_date);
        }

        if (isset($params['date']) && !empty($params['date']))
        {
            $date = date('Y-m-d', strtotime($params['date']));

            $getEarning->whereDate('created_at', $date);
        }
        else
        {
            $getEarning->whereDate('created_at', date('Y-m-d'));    
        }

        $getEarning = $getEarning->get();

        $todayTotalAmount = $getEarning->sum('pay_amount');
        $todayTotalServiceCharges = $getEarning->sum('service_charges');

        $todayEarning = $todayTotalAmount - $todayTotalServiceCharges;

        return response()->json(['status' => 1, 'message' => 'Earning Amount.', 'todayEarning' => $todayEarning])->setStatusCode(200);
    }
}