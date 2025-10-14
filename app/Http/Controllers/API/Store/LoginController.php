<?php

namespace App\Http\Controllers\API\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Session;
use Validator;
use App\OauthClient;
use App\User;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function store_login(Request $request)
    { 

        //--- Validation Section
        $rules = [
            'email'   => 'required|email',
            'password' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) 
        {
            return response()->json(['status' => 0, 'message' => $validator->errors()->first()])->setStatusCode(200);
        }

        $params = $request->post();

        // Attempt to log the user in
        if (Auth::attempt(['email' => $params['email'], 'password' => $params['password'], 'user_role' => 3]))
        {
            // if successful, then redirect to their intended location
            $user = User::where(['email' => $params['email']])->first();

            // Check If Email is verified or not
            if($user->is_store_paid == 0)
            { 
                return response()->json(['status' => 0, 'message' => 'Your membership amount is pending!'])->setStatusCode(200);
            }

            if($user->status == 0)
            {
                return response()->json(['status' => 0, 'message' => 'Your Account Has Been Deactivated.'])->setStatusCode(200); 
            }

            $api_token =  Str::random(80) . time();

            $token = new OauthClient;
            $token->user_id = $user->id;
            $token->api_token = $api_token;
            
            if(isset($params['device_platform']) && !empty($params['device_platform']))
            {
                $token->device_platform = intval($params['device_platform']);
            }

            if(isset($params['device_token']) && !empty($params['device_token']))
            {
                $token->device_token = $params['device_token'];
            }

            if($user->save() && $token->save())
            {
                return response()->json(['status' => 1, 'message' => 'success', 'user' => $user, 'token' => $token])->setStatusCode(200);
            }
            else
            {
                return response()->json(['status' => 0, 'message' => "Something went wrong."])->setStatusCode(200);
            }
        }

        // if unsuccessful
        return response()->json(['status' => 0, 'message' => "Credentials Mismatched!"])->setStatusCode(200);
    }

    public function logout(Request $request)
    {
        //--- Validation Section
        $rules = [
                  'api_token' => 'required'
                ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) 
        {
            return response()->json(['status' => 0, 'message' => 'API Token required'])->setStatusCode(200);
        }
        //--- Validation Section Ends

        $checkTokenExistance = OauthClient::with('user')->where(['api_token' => $request->api_token])->first();
        if($checkTokenExistance)
        {
            if($checkTokenExistance->delete())
            {
                return response()->json(['status' => 1, 'message' => 'Logout successfully.'])->setStatusCode(200);
            }
            else
            {
                return response()->json(['status' => 0, 'message' => 'Something went wrong.' ])->setStatusCode(200);
            }
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'No user found' ])->setStatusCode(200);
        }
    }
}