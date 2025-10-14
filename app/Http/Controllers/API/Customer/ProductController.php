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

class ProductController extends Controller
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

        $products = Product::with([
            'category' => function($query) {
                $query->where(["status" => 1]);
            },
            'subcategory' => function($query) {
                $query->where(["status" => 1]);
            }
        ])->where(['type' => 2, 'user_id' => $params['store_id'], 'status' => 1])->where('price', '<>', 0);
        if(!empty($params['category_id']))
        {
            $products = $products->where('category_id', $params['category_id']);
        }

        if(!empty($params['subcategory_id']))
        {
            $products = $products->where('subcategory_id', $params['subcategory_id']);
        }

        if(isset($params['q']) && !empty($params['q']))
        {
            $products = $products->where(function($query) use($params) {
                $query->where('name', 'Like', '%' . $params['q'] . '%');
                // $query->orWhere('description', 'Like', $params['q'].'%');
                // $query->orWhere('price', 'Like', $params['q'].'%');
            });
        }

        $products = $products->orderBy('name', 'asc')->paginate();
        return response()->json(['status' => 1, 'message' => 'Store Products.', 'data' => $products])->setStatusCode(200);
    }

    public function offerProducts(Request $request)
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

        $products = Product::with([
            'category' => function($query) {
                $query->where(["status" => 1]);
            },
            'subcategory' => function($query) {
                $query->where(["status" => 1]);
            }
        ])->where(['type' => 2, 'user_id' => $params['store_id'], 'is_offer' => 1, 'status' => 1])->where('price', '<>', 0);
        if(!empty($params['category_id']))
        {
            $products = $products->where('category_id', $params['category_id']);
        }

        if(!empty($params['subcategory_id']))
        {
            $products = $products->where('subcategory_id', $params['subcategory_id']);
        }

        if(isset($params['q']) && !empty($params['q']))
        {
            $products = $products->where(function($query) use($params) {
                $query->where('name', 'Like', '%' . $params['q'] . '%');
            });
        }

        $products = $products->orderBy('name', 'asc')->paginate();
        return response()->json(['status' => 1, 'message' => 'Store Offer Products.', 'data' => $products])->setStatusCode(200);
    }
    
    public function details(Request $request, $id)
    {
        $getProductData = Product::with(['category', 'subcategory'])->find($id);
        if ($getProductData)
        {
            return response()->json(['status' => 1, 'message' => 'Product Data.', 'data' => $getProductData])->setStatusCode(200);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'No Product Found'])->setStatusCode(200);
        }
    }
}