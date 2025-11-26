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

class CategoryController extends Controller
{
    public function commonCategories(Request $request)
    {
        $getCategories = Category::with('subCategories')->where(['type' => 1, 'status' => 1])->orderBy('name', 'asc')->get();
        return response()->json(['status' => 1, 'message' => 'Common Categories.', 'data' => $getCategories])->setStatusCode(200);
    }

    public function index(Request $request)
    {
        $user = $request->checkTokenExistance->user;
        $getCategoriesIdsFromCommon = Product::where(['user_id' => $user->id, 'type' => 2, 'status' => 1])->distinct()->pluck('category_id');

        $getCategories = Category::with('subCategories')->where(function($q) use ($user, $getCategoriesIdsFromCommon) {
                                          $q->where(['type' => 2, 'user_id' => $user->id])
                                            ->orWhereIn('id', $getCategoriesIdsFromCommon);
                                      })->orderBy('name', 'asc')->get();
        return response()->json(['status' => 1, 'message' => 'Store Categories.', 'data' => $getCategories])->setStatusCode(200);
    }

    public function saveCategory(Request $request)
    {
        $user = $request->checkTokenExistance->user;
        // create the validation rules ------------------------
        $rules = [
                'name'   => 'required',
                'featured_image'   => 'required|mimes:jpeg,jpg,png,svg',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()->first()])->setStatusCode(200);
        }

        $params = $request->post();

        $category = new Category;
        $category->name = $params['name'];
        $category->user_id = $user->id;
        $category->is_age_restricted = $params['is_age_restricted'];
        $category->type = 2;

        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/categories');
            $image->move($destinationPath, $name);
            
            $category->featured_image = 'categories/' . $name;
        }

        if($category->save())
        {
            return response()->json(['status' => 1, 'message' => 'Category has created successfully.'])->setStatusCode(200);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'Something went wrong.'])->setStatusCode(200);
        }
    }

    public function categoryDetails(Request $request, $id)
    {
        $user = $request->checkTokenExistance->user;
        $getCategoryData = Category::where(['id' => $id])->first();
        if ($getCategoryData)
        {
            return response()->json(['status' => 1, 'message' => 'Category Data.', 'data' => $getCategoryData])->setStatusCode(200);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'No category found.'])->setStatusCode(200);
        }
    }

    public function updateCategory(Request $request)
    {
        // create the validation rules ------------------------
        $rules = array(
            'name'   => 'required',
            // 'name'   => 'required|unique:categories,name,'.$request->id.',id,deleted_at,NULL'
        );

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()->first()])->setStatusCode(200);
        }

        $params = $request->post();

        $category = Category::find($params['id']);
        if (!$category)
        {
            return response()->json(['status' => 0, 'message' => 'No Category Found'])->setStatusCode(200);
        }

        $category->name = $params['name'];
        $category->is_age_restricted = $params['is_age_restricted'];

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
            $destinationPath = public_path('/categories');
            $image->move($destinationPath, $name);
            
            $category->featured_image = 'categories/' . $name;
        }

        if($category->save())
        {
            return response()->json(['status' => 1, 'message' => 'Category has updated successfully.'])->setStatusCode(200);
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

        $getCategoryData = Category::find($params['id']);
        if (!$getCategoryData)
        {
            return response()->json(['status' => 0, 'message' => 'No Category Found'])->setStatusCode(200);
        }

        $message = 'Category Deactivated successfully';
        if ($params['status'])
        {
            $message = 'Category Activated successfully';
        }

        $getCategoryData->status = $params['status'];

        if ($getCategoryData->save())
        {
            return response()->json(['status' => 1, 'message' => $message])->setStatusCode(200);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'Something went wrong.'])->setStatusCode(200);
        }
    }

    public function getSubCategories(Request $request, $id=0)
    {
        $getSubCategories = [];
        if (\Request::isMethod('post'))
        {
            return response()->json(['status' => 1, 'message' => 'Sub Category Data.', 'data' => $getCategoryData])->setStatusCode(200);
        }

        $getCategoryData = Category::find($id);
        if (empty($getCategoryData))
        {
            return response()->json(['status' => 1, 'message' => 'Sub Category Data.', 'data' => $getCategoryData])->setStatusCode(200);
        }

        $getSubCategories = subCategory::with('category')->where(['category_id' => $id])->orderBy('name', 'asc')->get();
        
        return response()->json(['status' => 1, 'message' => 'Sub Category Data.', 'data' => $getSubCategories])->setStatusCode(200);
    }
}