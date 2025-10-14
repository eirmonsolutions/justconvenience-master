<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use Session;
use App\User;
use Carbon\Carbon;
use DB;
use Config;

class SupervisorController extends Controller
{
    protected $current_active_contest_id;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $params = $request->input();

        $users = \DB::table('users')->where(array('user_role' => 3, 'deleted_at' => NULL))->get();

        return view('admin.supervisor.index', ['users' => $users]);
    }


    public function addUser(Request $request)
    {
        return view('admin.supervisor.add_supervisor');
    }

    public function saveUser(Request $request)
    {    
        $params = $request->post(); 
        // create the validation rules ------------------------
        $rules = array(
            'name'             => 'required',
            'last_name'             => 'required',
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

            return redirect()->route('add-supervisor')->withInput($request->all());
        }
 
        $user = new User;
        $user->name = $params['name'];
        $user->last_name = $params['last_name'];
        $user->email = $params['email']; 
        $user->password = bcrypt($params['password']);
        $user->user_role = 3;

        if($user->save())
        {
            try{

                \Mail::send('welcome_email_template', ['params' => $params], function($message) use($params){

                        $message->to($params['email'])->subject('Welcome');

                    });

                Session::flash('message', 'Supervisor has created successfully.'); 
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

        return redirect()->route('supervisors');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function deleteUser(Request $request, $id)
    {    
        $params = $request->input();

        $checkUserExistance = User::find($id);

        if ($checkUserExistance) 
        {
            if($checkUserExistance->delete())
            {
                Session::flash('message', 'Supervisor deleted successfully.'); 
                Session::flash('class', 'success');
            }
            else
            {
                Session::flash('message', 'Something went wrong'); 
                Session::flash('class', 'danger');

            }

            return redirect()->route('supervisors');
        }
        else
        {
            Session::flash('message', 'No user exist.'); 
            Session::flash('class', 'danger');

            return redirect()->route('supervisors');
        }
    }

    public function changePassword(Request $request, $id)
    {    
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

                return view('admin.supervisor.supervisor_change_password', ['id' => $id]);
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

            return redirect()->route('supervisors');
        }

        return view('admin.supervisor.supervisor_change_password', ['id' => $id]);
    }

}