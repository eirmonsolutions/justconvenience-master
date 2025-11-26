<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use Session;

use App\Category;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $params = $request->post();

        $getCategories = Category::where(['type' => 1])->get();

        return view('admin.category.index', ['categories' => $getCategories, 'params' => $params]);
    }

    public function addCategory(Request $request)
    {
        return view('admin.category.add_category');
    }

    public function saveCategory(Request $request)
    {
        // create the validation rules ------------------------
        $rules = [
                'name'   => 'required|unique:categories,name,NULL,id,type,1,deleted_at,NULL',
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

            return redirect()->route('add-category')->withInput($request->all());
        }

        $params = $request->post();

        $category = new Category;  
        $category->name = $params['name'];
        $category->is_age_restricted = $params['is_age_restricted'];

        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            // echo $image->storeAs('uploads', $name);

            $destinationPath = public_path('/categories');
            $image->move($destinationPath, $name);
            
            $category->featured_image = 'categories/' . $name;
        }

        if($category->save())
        {
            Session::flash('message', 'Category has created successfully.'); 
            Session::flash('class', 'success');
        }
        else
        {
            Session::flash('message', 'Something went wrong.'); 
            Session::flash('class', 'danger'); 
        }

        return redirect()->route('categories');
    }

    public function editCategory(Request $request, $id)
    {
        $getCategoryData = Category::find($id);
        if ($getCategoryData)
        {
            return view('admin.category.edit_category', ['data' => $getCategoryData]);
        }
        else
        {
            Session::flash('message', 'No Category Found'); 
            Session::flash('class', 'danger');  
            return redirect()->route('categories');
        }
    }

    public function updateCategory(Request $request)
    {
        // create the validation rules ------------------------
        $rules = array(
            'name'   => 'required',
            // 'name'   => 'required|unique:categories,name,'.$request->id.',id,type,1,deleted_at,NULL',
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

            return redirect()->route('edit-category', ['category_id' => $request->id])->withInput($request->all());
        }

        $params = $request->post();

        $category = Category::find($params['id']);
        if (!$category)
        {
            Session::flash('message', 'No Category Found'); 
            Session::flash('class', 'danger');  
            return redirect()->route('categories');
        }

        $category->name = $params['name'];
        $category->is_age_restricted = $params['is_age_restricted'];

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

                return redirect()->route('edit-category', ['category_id' => $request->id])->withInput($request->all());
            }

            $image = $request->file('featured_image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/categories');
            $image->move($destinationPath, $name);
            
            $category->featured_image = 'categories/' . $name;
        }

        if($category->save())
        {
            Session::flash('message', 'Category has updated successfully.'); 
            Session::flash('class', 'success');
        }
        else
        {
            Session::flash('message', 'Something went wrong.'); 
            Session::flash('class', 'danger'); 
        }

        return redirect()->route('categories');
    }

    public function updateStatus(Request $request, $id, $status)
    {
        if (\Request::isMethod('post'))
        {
            return redirect()->route('categories');
        }

        $getCategoryData = Category::find($id);
        if (!$getCategoryData)
        {
            Session::flash('message', 'No Category Found'); 
            Session::flash('class', 'danger');  
            return redirect()->route('categories');
        }

        $message = 'Category Deactivated successfully';
        if ($status)
        {
            $message = 'Category Activated successfully';
        }

        $getCategoryData->status = $status;

        if ($getCategoryData->save())
        {
            return response()->json(['message' => $message, 'status' => 1]);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'Something went wrong.']);
        }
    }
}