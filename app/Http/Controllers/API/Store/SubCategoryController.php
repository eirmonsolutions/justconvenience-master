<?php

namespace App\Http\Controllers\API\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use Session;

use App\Product;
use App\Category;
use App\subCategory;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->checkTokenExistance->user;
        $getSubCategoriesIdsFromCommon = Product::where(['user_id' => $user->id, 'type' => 2])->distinct()->pluck('subcategory_id');

        $getSubCategories = subCategory::with('category')->where(function($q) use ($user, $getSubCategoriesIdsFromCommon) {
                                          $q->where(['type' => 2, 'user_id' => $user->id, 'status' => 1])
                                            ->orWhereIn('id', $getSubCategoriesIdsFromCommon);
                                      })->orderBy('name', 'asc')->get();

        return response()->json(['status' => 1, 'message' => 'Store Sub Categories.', 'data' => $getSubCategories])->setStatusCode(200);
    }

    public function saveSubCategory(Request $request)
    {
        $user = $request->checkTokenExistance->user;
        // create the validation rules ------------------------
        $rules = [
                'name'   => 'required',
                'category_id'   => 'required',
                'featured_image'   => 'required|mimes:jpeg,jpg,png,svg',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()->first()])->setStatusCode(200);
        }

        $params = $request->post();

        $subCategory = new subCategory;  
        $subCategory->name = $params['name'];
        $subCategory->user_id = $user->id;
        $subCategory->type = 2;
        $subCategory->category_id = $params['category_id'];

        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/sub_categories');
            $image->move($destinationPath, $name);
            
            $subCategory->featured_image = 'public/sub_categories/' . $name;
        }

        if(isset($params['is_offer']))
        {
            $subCategory->is_offer = $params['is_offer'];
            if(!empty($params['is_offer']))
            {
                if(isset($params['offer_quantity']) && !empty($params['offer_quantity']))
                {
                    $subCategory->offer_quantity = $params['offer_quantity'];
                }

                if(isset($params['offer_price']) && !empty($params['offer_price']))
                {
                    $subCategory->offer_price = $params['offer_price'];
                }
            }
        }

        if($subCategory->save())
        {
            return response()->json(['status' => 1, 'message' => 'Sub Category has created successfully.'])->setStatusCode(200);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'Something went wrong.'])->setStatusCode(200);
        }
    }

    public function subCategoryDetails(Request $request, $id)
    {
        $getSubCategoryData = subCategory::with('category')->find($id);
        if ($getSubCategoryData)
        {
            return response()->json(['status' => 1, 'message' => 'Sub Category Data.', 'data' => $getSubCategoryData])->setStatusCode(200);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'No Sub Category Found'])->setStatusCode(200);
        }
    }

    public function updateSubCategory(Request $request)
    {
        // create the validation rules ------------------------
        $rules = array(
            'name'   => 'required',
            // 'name'   => 'required|unique:categories,name,'.$request->id.',id,deleted_at,NULL',
            'category_id'   => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()->first()])->setStatusCode(200);
        }

        $params = $request->post();

        $subCategory = subCategory::find($params['id']);
        if (!$subCategory)
        {
            return response()->json(['status' => 0, 'message' => 'No Sub Category Found'])->setStatusCode(200);
        }

        if($subCategory->category_id != $params['category_id'])
        {
            $product = Product::where(['subcategory_id' => $subCategory->id])->first();
            if($product)
            {
                return response()->json(['status' => 0, 'message' => 'you cant change the category'])->setStatusCode(200);
            }
        }

        $subCategory->name = $params['name'];
        $subCategory->category_id = $params['category_id'];

        if ($request->hasFile('featured_image')) 
        {
            $rules = [
                'featured_image'   => 'required|mimes:jpeg,jpg,png,svg'
            ];

            $validator = Validator::make($request->all(), $rules);
                    
            if ($validator->fails()) {
                return response()->json(['status' => 0, 'message' => $validator->errors()->first()])->setStatusCode(200);
            }

            $image = $request->file('featured_image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/sub_categories');
            $image->move($destinationPath, $name);
            
            $subCategory->featured_image = 'public/sub_categories/' . $name;
        }

        if(isset($params['is_offer']))
        {
            $subCategory->is_offer = $params['is_offer'];
            if(!empty($params['is_offer']))
            {
                if(isset($params['offer_quantity']) && !empty($params['offer_quantity']))
                {
                    $subCategory->offer_quantity = $params['offer_quantity'];
                }

                if(isset($params['offer_price']) && !empty($params['offer_price']))
                {
                    $subCategory->offer_price = $params['offer_price'];
                }
            }
        }

        if($subCategory->save())
        {
            return response()->json(['status' => 1, 'message' => 'Sub Category has updated successfully.'])->setStatusCode(200);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'Something went wrong.'])->setStatusCode(200); 
        }
    }

    public function updateStatus(Request $request)
    {
        // create the validation rules ------------------------
        $rules = array(
            'id'   => 'required',
            'status'   => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()->first()])->setStatusCode(200);
        }

        $params = $request->post();

        $getSubCategoryData = subCategory::find($params['id']);
        if (!$getSubCategoryData)
        {
            return response()->json(['status' => 0, 'message' => 'No Sub Category Found'])->setStatusCode(200);
        }

        $message = 'Sub Category Deactivated successfully';
        if ($params['status'])
        {
            $message = 'Sub Category Activated successfully';
        }

        $getSubCategoryData->status = $params['status'];

        if ($getSubCategoryData->save())
        {
            return response()->json(['status' => 1, 'message' => $message])->setStatusCode(200);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'Something went wrong.'])->setStatusCode(200);
        }
    }
}