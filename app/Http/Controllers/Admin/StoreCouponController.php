<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use Session;

use App\storeCoupon;

class StoreCouponController extends Controller
{
    public function index(Request $request)
    {
        $params = $request->post();

        $getCoupons = storeCoupon::get();

        return view('admin.store_coupons.index', ['coupons' => $getCoupons, 'params' => $params]);
    }

    public function add(Request $request)
    {
        return view('admin.store_coupons.add');
    }

    public function save(Request $request)
    {
        // create the validation rules ------------------------
        $rules = [
                'code'   => 'required|unique:store_coupons,code,NULL,id,deleted_at,NULL',
                'type' => 'required|numeric',
                'coupon_value' => 'required|min:0'
        ];

        // do the validation ----------------------------------
        // validate against the inputs from our form
        $validator = Validator::make($request->all(), $rules);

        // check if the validator failed -----------------------
        if ($validator->fails()) {

            // get the error messages from the validator
            // $messages = $validator->messages();

            Session::flash('message', $validator->errors()->first()); 
            Session::flash('class', 'danger');

            return redirect()->route('add-store-coupon')->withInput($request->all());
        }

        $params = $request->post();

        $coupon = new storeCoupon;  
        $coupon->code = $params['code'];
        $coupon->type = $params['type'];
        $coupon->coupon_value = $params['coupon_value'];

        if($coupon->save())
        {
            Session::flash('message', 'Store Coupon has created successfully.'); 
            Session::flash('class', 'success');
        }
        else
        {
            Session::flash('message', 'Something went wrong.'); 
            Session::flash('class', 'danger'); 
        }

        return redirect()->route('store-coupons');
    }

    public function updateStatus(Request $request, $id, $status)
    {
        if (\Request::isMethod('post'))
        {
            return redirect()->route('store-coupons');
        }

        $getCouponData = storeCoupon::find($id);
        if (!$getCouponData)
        {
            Session::flash('message', 'No Store Coupon Found'); 
            Session::flash('class', 'danger');  
            return redirect()->route('store-coupons');
        }

        $message = 'Store Coupon Deactivated successfully';
        if ($status)
        {
            $message = 'Store Coupon Activated successfully';
        }

        $getCouponData->status = $status;

        if ($getCouponData->save())
        {
            return response()->json(['message' => $message, 'status' => 1]);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'Something went wrong.']);
        }
    }
}