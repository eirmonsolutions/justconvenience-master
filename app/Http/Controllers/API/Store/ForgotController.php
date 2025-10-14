<?php

namespace App\Http\Controllers\API\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Validator;
use Hash;
use App\User;
use Illuminate\Support\Str;

class ForgotController extends Controller
{
    public function forgot(Request $request)
    {
        //--- Validation Section
        $rules = [
            'email'   => 'required|email'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()->first()])->setStatusCode(200);
        }
        //--- Validation Section Ends

        $params =  $request->post();
        $user = User::where(['email' => $params['email'], 'status' => 1, 'user_role' => 3])->first();
        if($user)
        {
            $user->reset_code = $autopass = Str::random(8);
            if($user->save())
            {
                $subject = "Reset Password Code";
                $msg = "Your reset code is : ". $user->reset_code;

                \Mail::send('email_templates.forgot_email_template', ['msg' => $msg], function($message) use($user, $subject, $msg)
                {
                    $message->to($user->email)->subject($subject);
                });

                return response()->json(['status' => 1, 'message' => 'Your Password code has been sent to your email.'])->setStatusCode(200);
            }
            else
            {
                return response()->json(['status' => 0, 'message' => 'Something went wrong.'])->setStatusCode(200);
            }
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'No Account Exist.'])->setStatusCode(200);
        }  
    }

    public function resetPassword(Request $request)
    {
        //--- Validation Section
        $rules = [
            'email'   => 'required|email',
            'reset_code' => 'required',
            'password' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()->first()])->setStatusCode(200);
        }
        //--- Validation Section Ends

        $params =  $request->post();

        $checkUserExistance = User::where(['email' => $params['email'], 'status' => 1, 'user_role' => 3])->first();
        if($checkUserExistance)
        {
            if($checkUserExistance->reset_code != $params['reset_code'])
            {
                return response()->json(['status' => 0, 'message' => 'Reset code mismatched.'])->setStatusCode(200);
            }

            $checkUserExistance->password = bcrypt($params['password']);
            $checkUserExistance->reset_code = NULL;
            if($checkUserExistance->save())
            {
                return response()->json(['status' => 1, 'message' => 'Your Password been updated.'])->setStatusCode(200);
            }
            else
            {
                return response()->json(['status' => 0, 'message' => 'Something went wrong.'])->setStatusCode(200);
            }
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'No Account Exist.'])->setStatusCode(200);
        }
    }
}