<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use Auth;
use Session;
use App\User;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if(!Auth::check())
        {
            return redirect()->route('signin');
        }

        if(Auth::check() && Auth::user()->user_role == 2)
        {
            return redirect()->route('signin');
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if(!Auth::check())
        {
            return redirect()->route('signin');
        }

        if(Auth::check() && Auth::user()->user_role == 2)
        {
            return redirect()->route('customers');
        }
        $params = $request->input();

        $users = \DB::table('users')->where(array('user_role' => 2, 'deleted_at' => NULL))->get();

        return view('superadmin.users', ['users' => $users, 'params' => $params]);
    }


    public function addUser(Request $request)
    {
        if(!Auth::check())
        {
            return redirect()->route('signin');
        }

        if(Auth::check() && Auth::user()->user_role == 2)
        {
            return redirect()->route('customers');
        }
        return view('superadmin.add_user');
    }

    public function saveUser(Request $request)
    {
        // create the validation rules ------------------------
        $rules = array(
            'name'             => 'required',
            'email'            => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            'password'         => 'required'
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

            return redirect()->route('add-user')->withInput($request->all());
        }

        $params = $request->post();

        $user = new User;
        $user->name = $params['name'];
        $user->email = $params['email'];
        $user->password = bcrypt($params['password']);
        $user->user_role = 2;

        if($user->save())
        {
            try{

                \Mail::send('welcome_email_template', ['params' => $params], function($message) use($params){

                        $message->to($params['email'])->subject('Welcome');

                    });

                Session::flash('message', 'User has created successfully.'); 
                Session::flash('class', 'success');
            }
            catch(\Exception $e)
            {
                $user->email_sent = 0;
                $user->save();

                Session::flash('message', $e->getMessage()); 
                Session::flash('class', 'danger');
            }
        }
        else
        {
            Session::flash('message', 'Something went wrong.'); 
            Session::flash('class', 'danger'); 
        }

        return redirect()->route('users');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function deleteUser(Request $request, $id)
    {
        if(!Auth::check())
        {
            return redirect()->route('signin');
        }
        if(Auth::user()->user_role == 2)
        {
            return redirect()->route('customers');
        }
        
        $params = $request->input();

        $checkUserExistance = User::find($id);

        if ($checkUserExistance) 
        {
            if($checkUserExistance->delete())
            {
                Session::flash('message', 'User deleted successfully.'); 
                Session::flash('class', 'success');
            }
            else
            {
                Session::flash('message', 'Something went wrong'); 
                Session::flash('class', 'danger');

            }

            return redirect()->route('users');
        }
        else
        {
            Session::flash('message', 'No user exist.'); 
            Session::flash('class', 'danger');

            return redirect()->route('users');
        }
    }

    public function changePassword(Request $request, $id)
    {
        if(!Auth::check())
        {
            return redirect()->route('signin');
        }

        if(Auth::check() && Auth::user()->user_role == 2)
        {
            return redirect()->route('customers');
        }
        if (\Request::isMethod('post'))
        {
            // create the validation rules ------------------------
            $rules = array(
                'password'             => 'required'
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

                return view('superadmin.change_password', ['id' => $id]);
            }

            $params = $request->post();

            $user = User::find($id);

            if($user)
            {
                $user->password = bcrypt($params['password']);
                if ($user->save())
                {
                    Session::flash('message', 'Password has been changed successfully.'); 
                    Session::flash('class', 'success');
                }
                else
                {
                    Session::flash('message', 'Something went wrong.'); 
                    Session::flash('class', 'danger'); 
                }
            }
            else
            {
                Session::flash('message', 'No user exist.'); 
                Session::flash('class', 'danger'); 
            }

            return redirect()->route('users');
        }

        return view('superadmin.change_password', ['id' => $id]);
    }

}