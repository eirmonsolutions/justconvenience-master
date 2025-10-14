<?php

namespace App\Http\Controllers\API\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Validator;

class RegisterController extends Controller
{
	public function store_register(Request $request)
    {
        //--- Validation Section
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
            'is_store_paid'   => 'required',
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
        $user->store_name = $params['store_name'];
        $user->address = $params['address'];
        $user->city = $params['city'];
        $user->state = $params['state'];
        $user->country = $params['country'];
        $user->zipcode = $params['zipcode'];
        $user->delivery_service = $params['delivery_service'];

        if(!empty($request->delivery_service))
        {
			//--- Validation Section
        	$rules = [
        		'minimum_order_amount' => 'min:0',
        		'delivery_charges'  => 'min:0'
        	];

        	$validator = Validator::make($request->all(), $rules);
        	if ($validator->fails()) 
        	{
        		return response()->json(['status' => 0, 'message' => $validator->errors()->first()])->setStatusCode(200);
        	}

        	$user->minimum_order_amount = $params['minimum_order_amount'];
        	$user->delivery_charges = $params['delivery_charges'];
        }

        $user->is_store_paid = $params['is_store_paid'];
        
        $user->status = 0;
        if(isset($params['latitudes']) && !empty($params['latitudes']))
        {
            $user->latitudes = $params['latitudes'];
        }

        if(isset($params['longitudes']) && !empty($params['longitudes']))
        {
            $user->longitudes = $params['longitudes'];
        }

        if($params['is_store_paid'])
        {
        	$user->status = 1;
        }
        
        $user->user_role = 3;
        if($user->save())
        {
        	return response()->json(['status' => 1, 'message' => 'Thank you for signing up with us. We will contact you shortly.'])->setStatusCode(200);
        }
        else
        {
        	return response()->json(['status' => 0, 'message' => 'Something went wrong.'])->setStatusCode(200);
        }
    }
}