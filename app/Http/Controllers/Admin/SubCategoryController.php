<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use Session;

use App\Category;
use App\Product;
use App\subCategory;

class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        $params = $request->post();

        $getSubCategories = subCategory::where(['type' => 1])->get();

        return view('admin.sub_category.index', ['sub_categories' => $getSubCategories, 'params' => $params]);
    }

    public function addSubCategory(Request $request)
    {
        $getCategories = Category::where(['type' => 1])->get();
        return view('admin.sub_category.add', ['categories' => $getCategories]);
    }

    public function saveSubCategory(Request $request)
    {
        // create the validation rules ------------------------
        $rules = [
                'name'   => 'required|unique:sub_categories,name,NULL,id,type,1,deleted_at,NULL',
                'category_id'   => 'required',
                'featured_image'   => 'required|mimes:jpeg,jpg,png,svg',
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

            return redirect()->route('add-sub-category')->withInput($request->all());
        }

        $params = $request->post();

        $subCategory = new subCategory;  
        $subCategory->name = $params['name'];
        $subCategory->category_id = $params['category_id'];

        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/sub_categories');
            $image->move($destinationPath, $name);
            
            $subCategory->featured_image = 'sub_categories/' . $name;
        }

        if($subCategory->save())
        {
            Session::flash('message', 'Sub Category has created successfully.'); 
            Session::flash('class', 'success');
        }
        else
        {
            Session::flash('message', 'Something went wrong.'); 
            Session::flash('class', 'danger'); 
        }

        return redirect()->route('sub-categories');
    }

    public function editSubCategory(Request $request, $id)
    {
        $getCategories = Category::where(['type' => 1])->get();
        $getSubCategoryData = subCategory::find($id);
        if ($getSubCategoryData)
        {
            return view('admin.sub_category.edit', ['data' => $getSubCategoryData, 'categories' => $getCategories]);
        }
        else
        {
            Session::flash('message', 'No Sub Category Found'); 
            Session::flash('class', 'danger');  
            return redirect()->route('sub-categories');
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

       \Log::info('Validation Rules:', $rules);


        // do the validation ----------------------------------
        // validate against the inputs from our form
        $validator = Validator::make($request->all(), $rules);

        // check if the validator failed -----------------------
        if ($validator->fails()) {

            // get the error messages from the validator
            // $messages = $validator->messages();
            

            Session::flash('message', $validator->errors()->first()); 
            Session::flash('class', 'danger');

            return redirect()->route('edit-sub-category', ['sub_category_id' => $request->id])->withInput($request->all());
        }

        $params = $request->post();

        $subCategory = subCategory::find($params['id']);
        if (!$subCategory)
        {
            Session::flash('message', 'No Sub Category Found'); 
            Session::flash('class', 'danger');  
            return redirect()->route('sub-categories');
        }

        \Log::info('Validation Errors:', [
            'db_category_id' => $subCategory->category_id,
            'request_category_id' => $params['category_id'],
        ]);

        if($subCategory->category_id != $params['category_id'])
        {
            $product = Product::where(['subcategory_id' => $subCategory->id])->first();
            if($product)
            {
                Session::flash('message', "you cant change the category"); 
                Session::flash('class', 'danger');  
                return redirect()->route('edit-sub-category', ['sub_category_id' => $request->id])->withInput($request->all());
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
            if ($validator->fails()) 
            {
                Session::flash('message', $validator->errors()->first()); 
                Session::flash('class', 'danger');

                return redirect()->route('edit-sub-category', ['sub_category_id' => $request->id])->withInput($request->all());
            }

            $image = $request->file('featured_image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/sub_categories');
            $image->move($destinationPath, $name);
            
            $subCategory->featured_image = 'sub_categories/' . $name;
        }

        if($subCategory->save())
        {
            Session::flash('message', 'Sub Category has updated successfully.'); 
            Session::flash('class', 'success');
        }
        else
        {
            Session::flash('message', 'Something went wrong.'); 
            Session::flash('class', 'danger'); 
        }

        return redirect()->route('sub-categories');
    }

    public function updateStatus(Request $request, $id, $status)
    {
        if (\Request::isMethod('post'))
        {
            return redirect()->route('sub-categories');
        }

        $getSubCategoryData = subCategory::find($id);
        if (!$getSubCategoryData)
        {
            Session::flash('message', 'No Sub Category Found'); 
            Session::flash('class', 'danger');  
            return redirect()->route('sub-categories');
        }

        $message = 'Sub Category Deactivated successfully';
        if ($status)
        {
            $message = 'Sub Category Activated successfully';
        }

        $getSubCategoryData->status = $status;

        if ($getSubCategoryData->save())
        {
            return response()->json(['message' => $message, 'status' => 1]);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'Something went wrong.']);
        }
    }
}