<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use Session;

use App\Setting;

class TncController extends Controller
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

	public function editTnc(Request $request)
	{
	    return view('admin.tnc.edit_tnc');
	}

	public function updateTnc(Request $request)
	{
	    if(!Auth::check())
	    {
	        return redirect()->route('signin');
	    }

	    if(Auth::user()->user_role == 1)
	    {
	        return redirect()->route('users');
	    }

	    if (\Request::isMethod('get'))
	    {
	        return redirect()->route('edit-tnc');
	    }

	    $postedParams = $request->post();
	    $meta_key = 'tnc';

	    $serializeData = serialize($postedParams['meta_value']);

	    $settings = Setting::where('meta_key', $meta_key)->first();
	    if($settings)
	    {
	        $settings->meta_value = $serializeData;
	    }
	    else
	    {
	        $settings = new Setting();
	        $settings->meta_key = $meta_key;
	        $settings->meta_value = $serializeData;
	    }

	    if ($settings->save())
	    {
	        Session::flash('message', 'Settings updated successfully'); 
	        Session::flash('class', 'success');
	    }
	    else
	    {
	        Session::flash('message', 'Something went wrong.'); 
	        Session::flash('class', 'danger'); 
	    }

	    return redirect()->route('edit-tnc');
	}
}