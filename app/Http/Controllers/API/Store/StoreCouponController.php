<?php

namespace App\Http\Controllers\API\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\storeCoupon;
use Validator;

class StoreCouponController extends Controller
{
    public function couponDetails(Request $request)
    {
        //--- Validation Section
        $rules = [
                  'code' => 'required'
                ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) 
        {
            return response()->json(['status' => 0, 'message' => 'Coupon code required'])->setStatusCode(200);
        }
        //--- Validation Section Ends

        $params = $request->all();
        $checkCouponExistance = storeCoupon::where(['code' => $params['code'], 'status' => 1])->first();
        if($checkCouponExistance)
        {
            return response()->json(['status' => 1, 'message' => 'Coupon Details.', 'coupon' => $checkCouponExistance])->setStatusCode(200);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'No coupon found' ])->setStatusCode(200);
        }
    }
}