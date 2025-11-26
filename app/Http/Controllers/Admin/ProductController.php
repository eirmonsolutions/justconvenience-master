<?php

namespace App\Http\Controllers\Admin;

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
    public function __construct()
    {
        ini_set('memory_limit','512M');
        ini_set('max_execution_time', '0');
        $this->middleware(function ($request, $next) {
            if(Auth::user()->user_role == 3)
            {
                return redirect()->route('dashboard');
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $params = $request->post();
        $getSubCategories = [];

        $products = Product::where(['type' => 1]);
        if(!empty($params['category_id']))
        {
            $products = $products->where('category_id', $params['category_id']);

            $getSubCategories = subCategory::where(['category_id' => $params['category_id']])->get();
        }

        if(!empty($params['subcategory_id']))
        {
            $products = $products->where('subcategory_id', $params['subcategory_id']);
        }
        $products = $products->where(['status' => 1])->get();
        $getCategories = Category::where(['type' => 1])->orderBy('name', 'asc')->get();

        return view('admin.products.index', ['products' => $products, 'params' => $params, 'categories' => $getCategories, 'sub_categories' => $getSubCategories]);
    }

    public function disabledProducts(Request $request)
    {
        $params = $request->post();
        $getSubCategories = [];

        $products = Product::where(['type' => 1]);
        if(!empty($params['category_id']))
        {
            $products = $products->where('category_id', $params['category_id']);

            $getSubCategories = subCategory::where(['category_id' => $params['category_id']])->get();
        }

        if(!empty($params['subcategory_id']))
        {
            $products = $products->where('subcategory_id', $params['subcategory_id']);
        }
        $products = $products->where(['status' => 0])->get();
        $getCategories = Category::where(['type' => 1])->orderBy('name', 'asc')->get();

        return view('admin.disabled_products.index', ['products' => $products, 'params' => $params, 'categories' => $getCategories, 'sub_categories' => $getSubCategories]);
    }

    public function add(Request $request)
    {
        $getCategories = Category::where(['type' => 1])->orderBy('name', 'asc')->get();
        return view('admin.products.add', ['categories' => $getCategories]);
    }

    public function save(Request $request)
    {
        // create the validation rules ------------------------
        $rules = [
                'name'   => 'required|unique:products,name,NULL,id,type,1,deleted_at,NULL',
                'bar_code'   => 'required|unique:products,bar_code,NULL,id,type,1,deleted_at,NULL',
                'category_id'   => 'required',
                'price'   => 'required',
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

            return redirect()->route('add-product')->withInput($request->all());
        }

        $params = $request->post();

        $product = new Product;  
        $product->name = $params['name'];
        $product->bar_code = $params['bar_code'];
        $product->price = $params['price'];
        $product->description = $params['description'];
        $product->category_id = $params['category_id'];
        $product->subcategory_id = (isset($params['subcategory_id']) && !empty($params['subcategory_id'])) ? $params['subcategory_id'] : 0;

        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/products');
            $image->move($destinationPath, $name);
            
            $product->featured_image = 'products/' . $name;
        }

        if($product->save())
        {
            Session::flash('message', 'Product has created successfully.'); 
            Session::flash('class', 'success');
        }
        else
        {
            Session::flash('message', 'Something went wrong.'); 
            Session::flash('class', 'danger'); 
        }

        return redirect()->route('products');
    }

    public function details(Request $request, $id)
    {
        $getProductData = Product::with(['category', 'subcategory'])->find($id);
        if ($getProductData)
        {
            $getSubCategories = subCategory::where(['category_id' => $getProductData->category_id])->get();
            return response()->json(['data' => $getProductData, 'status' => 1]);
        }
        else
        {
            Session::flash('message', 'No Product Found'); 
            Session::flash('class', 'danger');  
            return redirect()->route('products');
        }
    }

    public function updateDetails(Request $request)
    {
        // create the validation rules ------------------------
        $rules = array(
            'name'   => 'required',
            'bar_code'   => 'required|unique:products,bar_code,'.$request->product_id.',id,type,1,deleted_at,NULL',
            'price'   => 'required'
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

            return response()->json(['message' => $validator->errors()->first(), 'status' => 0]);
        }

        $params = $request->post();

        $product = Product::find($params['product_id']);
        if (!$product)
        {
            Session::flash('message', 'No Product Found'); 
            Session::flash('class', 'danger');  
            return redirect()->route('products');
        }

        $product->name = $params['name'];
        $product->bar_code = $params['bar_code'];
        $product->price = $params['price'];
        $product->description = $params['description'];

        if($product->save())
        {
            $product = Product::with(['category', 'subcategory'])->find($product->id);
            return response()->json(['data' => $product, 'status' => 1, 'message' => 'Product has updated successfully.']);
        }
        else
        {
            return response()->json(['message' => 'Something went wrong.', 'status' => 0]);
        }

        return redirect()->route('products');
    }

    public function edit(Request $request, $id)
    {
        $getCategories = Category::where(['type' => 1])->get();
        $getProductData = Product::find($id);
        if ($getProductData)
        {
            $getSubCategories = subCategory::where(['category_id' => $getProductData->category_id])->get();
            return view('admin.products.edit', ['data' => $getProductData, 'categories' => $getCategories, 'sub_categories' => $getSubCategories]);
        }
        else
        {
            Session::flash('message', 'No Product Found'); 
            Session::flash('class', 'danger');  
            return redirect()->route('products');
        }
    }

    public function update(Request $request)
    {
        // create the validation rules ------------------------
        $rules = array(
            'name'   => 'required',
            'bar_code'   => 'required|unique:products,bar_code,'.$request->id.',id,type,1,deleted_at,NULL',
            'price'   => 'required',
            'category_id'   => 'required',
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

            return redirect()->route('edit-product', ['product_id' => $request->id])->withInput($request->all());
        }

        $params = $request->post();

        $product = Product::find($params['id']);
        if (!$product)
        {
            Session::flash('message', 'No Product Found'); 
            Session::flash('class', 'danger');  
            return redirect()->route('products');
        }

        $product->name = $params['name'];
        $product->bar_code = $params['bar_code'];
        $product->price = $params['price'];
        $product->description = $params['description'];
        $product->category_id = $params['category_id'];
        $product->subcategory_id = (isset($params['subcategory_id']) && !empty($params['subcategory_id'])) ? $params['subcategory_id'] : 0;

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

                return redirect()->route('edit-product', ['product_id' => $request->id])->withInput($request->all());
            }

            $image = $request->file('featured_image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/products');
            $image->move($destinationPath, $name);
            
            $product->featured_image = 'products/' . $name;
        }

        if($product->save())
        {
            Session::flash('message', 'Product has updated successfully.'); 
            Session::flash('class', 'success');
        }
        else
        {
            Session::flash('message', 'Something went wrong.'); 
            Session::flash('class', 'danger'); 
        }

        return redirect()->route('products');
    }

    public function updateStatus(Request $request, $id, $status)
    {
        if (\Request::isMethod('post'))
        {
            return redirect()->route('products');
        }

        $getProductData = Product::find($id);
        if (!$getProductData)
        {
            Session::flash('message', 'No Product Found'); 
            Session::flash('class', 'danger');  
            return redirect()->route('products');
        }

        $message = 'Product Deactivated successfully';
        if ($status)
        {
            $message = 'Product Activated successfully';
        }

        $getProductData->status = $status;

        if ($getProductData->save())
        {
            return response()->json(['message' => $message, 'status' => 1]);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'Something went wrong.']);
        }
    }

    public function getSubCategories(Request $request, $id=0)
    {
        $getSubCategories = [];
        if (\Request::isMethod('post'))
        {
            return response()->json(['sub_categories' => $getSubCategories, 'status' => 1]);
        }

        $getCategoryData = Category::find($id);
        if (empty($getCategoryData))
        {
            return response()->json(['sub_categories' => $getSubCategories, 'status' => 1]);
        }

        $getSubCategories = subCategory::where(['category_id' => $id])->orderBy('name', 'asc')->get();
        return response()->json(['sub_categories' => $getSubCategories, 'status' => 1]);
    }
}