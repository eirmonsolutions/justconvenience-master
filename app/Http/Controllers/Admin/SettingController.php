<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use Session;

use App\Setting;

class SettingController extends Controller
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
	public function editSettings(Request $request)
	{
	    return view('admin.settings.edit_settings');
	}

	public function updateSettings(Request $request)
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
	        return redirect()->route('edit-settings');
	    }

	    $postedParams = $request->post();
	    $meta_key = 'home_page';

	    $params = [];

	    if ($request->hasFile('primary_logo')) {
	        $image = $request->file('primary_logo');
	        $name = \Str::uuid() . time().'.'.$image->getClientOriginalExtension();
	        $destinationPath = public_path('/img/upload_files');
	        $image->move($destinationPath, $name);
	        
	        $params['logo']['primary_logo'] = 'img/upload_files/' . $name;
	    }
	    else
	    {
	        $params['logo']['primary_logo'] = $postedParams['old_primary_logo'];   
	    }

	    $params['heading'] = $postedParams['heading'];
	    
	    $params['information']['address_line'] = $postedParams['address_line'];

	    $params['information']['phone_number'] = $postedParams['phone_number'];
	    $params['information']['email'] = $postedParams['email'];

	    if ($request->hasFile('secondary_logo')) {
	        $image = $request->file('secondary_logo');
	        $name = \Str::uuid() . time().'.'.$image->getClientOriginalExtension();
	        $destinationPath = public_path('/img/upload_files');
	        $image->move($destinationPath, $name);
	        
	        $params['logo']['secondary_logo'] = 'img/upload_files/' . $name;
	    }
	    else
	    {
	        $params['logo']['secondary_logo'] = $postedParams['old_secondary_logo'];   
	    }

	    $params['email_template']['welcome_email_text'] = $postedParams['welcome_email_text'];
	    
	    $serializeData = serialize($params);

	    $homePageSettings = Setting::where('meta_key', $meta_key)->first();
	    if($homePageSettings)
	    {
	        $homePageSettings->meta_value = $serializeData;
	    }
	    else
	    {
	        $homePageSettings = new Setting();
	        $homePageSettings->meta_key = $meta_key;
	        $homePageSettings->meta_value = $serializeData;
	    }

	    if ($homePageSettings->save())
	    {
	        Session::flash('message', 'Settings updated successfully'); 
	        Session::flash('class', 'success');
	    }
	    else
	    {
	        Session::flash('message', 'Something went wrong.'); 
	        Session::flash('class', 'danger'); 
	    }

	    return redirect()->route('edit-settings');
	}
}