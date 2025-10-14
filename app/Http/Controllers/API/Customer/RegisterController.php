<?php

namespace App\Http\Controllers\API\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Validator;

class RegisterController extends Controller
{
	public function customer_register(Request $request)
    {
        //--- Validation Section
        $rules = [
	        'name'   => 'required',
	        'phone_number'   => 'required',
	        'email'   => 'required|email|unique:users',
	        'password' => 'required|min:4',
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

        $user = new User;  
        $user->name = $params['name'];
        $user->phone_number = $params['phone_number'];
        $user->email = $params['email'];
        $user->password = bcrypt($params['password']);
        $user->address = $params['address'];
        $user->city = $params['city'];
        $user->state = $params['state'];
        $user->country = $params['country'];
        $user->zipcode = $params['zipcode'];
        
        $user->user_role = 4;
        if($user->save())
        {
        	return response()->json(['status' => 1, 'message' => 'Your account has been created Successfully.'])->setStatusCode(200);
        }
        else
        {
        	return response()->json(['status' => 0, 'message' => 'Something went wrong.'])->setStatusCode(200);
        }
    }
}