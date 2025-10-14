<?php

namespace App\Http\Controllers\API\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use Session;

use App\Product;
use App\Category;
use App\subCategory;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        //--- Validation Section
        $rules = [
            'store_id'   => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()->first()])->setStatusCode(200);
        }
        //--- Validation Section Ends

        $params = $request->all();

        $getCategories = Category::with([
            'subCategories' => function($query) {
            $query->where(["status" => 1]);
        }])->where(function($q) use ($params) {
                                        
                                        $q->where(['type' => 2, 'status' => 1]);
                                        $q->where(['user_id' => $params['store_id']]);

                                    })->orderBy('name', 'asc')->get();

        return response()->json(['status' => 1, 'message' => 'Store Categories.', 'data' => $getCategories])->setStatusCode(200);
    }

    public function getSubCategories(Request $request, $id=0)
    {
        $getSubCategories = [];
        if (\Request::isMethod('post'))
        {
            return response()->json(['status' => 1, 'message' => 'Sub Category Data.', 'data' => $getCategoryData])->setStatusCode(200);
        }

        $getCategoryData = Category::where(['status' => 1, 'id' => $id])->first();
        if (empty($getCategoryData))
        {
            return response()->json(['status' => 1, 'message' => 'Sub Category Data.', 'data' => $getCategoryData])->setStatusCode(200);
        }

        $getSubCategories = subCategory::with(['category' => function($query) {
                    $query->where(["status" => 1]);
                }])->where(['category_id' => $id, 'status' => 1])->orderBy('name', 'asc')->get();
        
        return response()->json(['status' => 1, 'message' => 'Sub Category Data.', 'data' => $getSubCategories])->setStatusCode(200);
    }
}