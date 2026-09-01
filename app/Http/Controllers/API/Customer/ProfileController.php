<?php

namespace App\Http\Controllers\API\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Session;
use Validator;
use App\User;
use App\Order;
use App\OauthClient;
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
        $customer = $request->checkTokenExistance->user;
        //--- Validation Section

        $rules = [
            'name'   => 'required',
            'phone_number'   => 'required',
            'email'   => 'required|email|unique:users,email,'.$customer->id.',id,deleted_at,NULL',
            'address'   => 'required',
            'city'   => 'required',
            'state'   => 'required',
            'country'   => 'required',
            'zipcode'   => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()->first()])->setStatusCode(200);
        }
        //--- Validation Section Ends

        $params = $request->post();
        $customer->name = $params['name'];
        $customer->phone_number = $params['phone_number'];
        $customer->email = $params['email'];
        $customer->address = $params['address'];
        $customer->city = $params['city'];
        $customer->state = $params['state'];
        $customer->country = $params['country'];
        $customer->zipcode = $params['zipcode'];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/customers');
            $image->move($destinationPath, $name);
            
            $customer->image = 'customers/' . $name;
        }

        if($customer->save())
        {
            return response()->json(['status' => 1, 'message' => 'Your account has been updated Successfully.'])->setStatusCode(200);
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

    public function deleteAccount(Request $request)
    {
        if (!\Request::isMethod('post')) {
            return response()->json(['status' => 0, 'message' => 'Method not allowed.'])->setStatusCode(200);
        }

        $customer = $request->checkTokenExistance->user;

        if ($customer->user_role != 4) {
            return response()->json(['status' => 0, 'message' => 'Unauthorized.'])->setStatusCode(200);
        }

        $rules = [
            'password' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()->first()])->setStatusCode(200);
        }

        if (!\Hash::check($request->password, $customer->password)) {
            return response()->json(['status' => 0, 'message' => 'Password is wrong.'])->setStatusCode(200);
        }

        $activeOrders = Order::where('user_id', $customer->id)
            ->whereIn('status', [ORDER_PENDING_STATUS, ORDER_READY_STATUS, ORDER_ONDELIVERY_STATUS])
            ->exists();

        if ($activeOrders) {
            return response()->json(['status' => 0, 'message' => 'You cannot delete your account while you have active orders. Please wait until they are completed.'])->setStatusCode(200);
        }

        OauthClient::where('user_id', $customer->id)->delete();

        $customer->status = 0;
        if ($customer->save() && $customer->delete()) {
            return response()->json(['status' => 1, 'message' => 'Your account has been deleted successfully.'])->setStatusCode(200);
        }

        return response()->json(['status' => 0, 'message' => 'Something went wrong.'])->setStatusCode(200);
    }
}