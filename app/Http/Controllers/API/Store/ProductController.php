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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class ProductController extends Controller
{
    public function commonProducts(Request $request)
    {
        $user = $request->checkTokenExistance->user;
        $params = $request->post();

        $getProductsFromCommon = Product::select('name')->where(['user_id' => $user->id, 'type' => 2, 'status' => 1]);

        /*if(!empty($params['category_id']))
        {
            $category = Category::select('name')->where('id', $params['$category_id'])->first();

            if($category)
            {
                $getStoreCategoryId = Category::select('id')->where('name', $category->name)->first();
                if($getStoreCategoryId)
                {
                    $products = $products->where('category_id', $getStoreCategoryId);
                }
            }
        }*/
        
        $getProductsFromCommon = $getProductsFromCommon->distinct()->pluck('name');

        $products = Product::where(['type' => 1, 'status' => 1]);

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

        $products = $products->whereNotIn('name', $getProductsFromCommon)->orderBy('name', 'asc')->get();

        return response()->json(['status' => 1, 'message' => 'Common Products.', 'data' => $products])->setStatusCode(200);
    }

    public function index(Request $request)
    {
        $user = $request->checkTokenExistance->user;
        $params = $request->all();

        $products = Product::with(['category', 'subcategory'])->where(['type' => 2, 'user_id' => $user->id]);
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

        $products = $products->where(['status' => 1]);
        if(isset($params['sort_type']) && !empty($params['sort_type']))
        {
            if($params['sort_type'] == 1)
            {
                // Alphabetically Descending
                $products = $products->orderBy('name', 'desc');
            }
            else if($params['sort_type'] == 2)
            {
                // Latest
                $products = $products->orderBy('id', 'desc');
            }
            else if($params['sort_type'] == 3)
            {
                // Oldest
                $products = $products->orderBy('id', 'asc');
            }
            else
            {
                $products = $products->orderBy('name', 'asc');
            }
        }
        else
        {
            $products = $products->orderBy('name', 'asc');
        }

        $products = $products->paginate();
        return response()->json(['status' => 1, 'message' => 'Store Products.', 'data' => $products])->setStatusCode(200);
    }

    public function disabledProducts(Request $request)
    {
        $user = $request->checkTokenExistance->user;
        $params = $request->all();

        $products = Product::with(['category', 'subcategory'])->where(['type' => 2, 'user_id' => $user->id]);
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

        $products = $products->where(['status' => 0]);
        if(isset($params['sort_type']) && !empty($params['sort_type']))
        {
            if($params['sort_type'] == 1)
            {
                // Alphabetically Descending
                $products = $products->orderBy('name', 'desc');
            }
            else if($params['sort_type'] == 2)
            {
                // Latest
                $products = $products->orderBy('id', 'desc');
            }
            else if($params['sort_type'] == 3)
            {
                // Oldest
                $products = $products->orderBy('id', 'asc');
            }
            else
            {
                $products = $products->orderBy('name', 'asc');
            }
        }
        else
        {
            $products = $products->orderBy('name', 'asc');
        }

        $products = $products->paginate();
        return response()->json(['status' => 1, 'message' => 'Disabled Store Products.', 'data' => $products])->setStatusCode(200);
    }


public function save(Request $request)
{
    $start = microtime(true);
    $user = $request->checkTokenExistance->user;

    Log::info('Product Save Started', [
        'user_id' => $user->id,
        'request_ip' => $request->ip(),
        'params' => $request->except('featured_image'), // hide file
    ]);

    try {
        // ------------------------------------------------------------------
        // 1. VALIDATION RULES (dynamic for add/edit)
        // ------------------------------------------------------------------
        $isEdit = $request->filled('product_id');
        $rules = [
            'name'          => 'required|string|max:255',
            'category_id'   => 'required|exists:categories,id',
            'price'         => 'required|numeric|min:0',
            'subcategory_id'=> 'nullable',
            'bar_code'      => 'nullable|string|max:100',
            'is_offer'      => 'nullable|in:0,1',
            'offer_description' => 'required_if:is_offer,1|string|max:500',
            'offer_quantity'    => 'required_if:is_offer,1|integer|min:1',
            'offer_price'       => 'required_if:is_offer,1|numeric|min:0',
        ];

        // Image required only on create
        if (!$isEdit) {
       $rules['featured_image'] = 'required|image|mimes:jpeg,jpg,png,svg|max:2048';
        } else {
            $rules['featured_image'] = 'nullable|image|mimes:jpeg,jpg,png,svg|max:2048';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Log::warning('Product Validation Failed', [
                'errors' => $validator->errors()->toArray(),
                'user_id' => $user->id,
            ]);
            return response()->json([
                'status'  => 0,
                'message' => $validator->errors()->first()
            ], 200);
        }

        // ------------------------------------------------------------------
        // 2. PREPARE DATA
        // ------------------------------------------------------------------
        $params = $request->all();

        // Start DB transaction
        return DB::transaction(function () use ($request, $user, $params, $isEdit, $start) {
            try {
                $product = $isEdit
                    ? Product::where('id', $params['product_id'])
                        ->where('user_id', $user->id)
                        ->firstOrFail()
                    : new Product();

                // Basic fields
                $product->name           = $params['name'];
                $product->description    = $params['description'] ?? null;
                $product->category_id    = $params['category_id'];
                $product->subcategory_id = $params['subcategory_id'] ?? 0;
                $product->price          = $params['price'];
                $product->bar_code       = $params['bar_code'] ?? null;
                $product->user_id        = $user->id;
                $product->type           = 2;

                // ------------------------------------------------------------------
                // 3. HANDLE IMAGE
                // ------------------------------------------------------------------
                if ($request->hasFile('featured_image')) {
                    /** @var UploadedFile $image */
                    $image = $request->file('featured_image');

                    Log::info('Uploading product image', [
                        'original_name' => $image->getClientOriginalName(),
                        'size' => $image->getSize(),
                        'mime' => $image->getMimeType(),
                    ]);

                    // Delete old image if exists (edit mode)
                    if ($isEdit && $product->featured_image && Storage::exists($product->featured_image)) {
                        Storage::delete($product->featured_image);
                        Log::info('Old image deleted', ['path' => $product->featured_image]);
                    }

                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('products', $filename, 'public');

                    $product->featured_image = 'storage/products/' . $filename;

                    Log::info('New image saved', ['path' => $product->featured_image]);
                } elseif (!$isEdit) {
                    // Should not reach here due to validation
                    throw new \Exception('Featured image is required for new product.');
                }

                // ------------------------------------------------------------------
                // 4. OFFER LOGIC (clean & safe)
                // ------------------------------------------------------------------
                $isOffer = isset($params['is_offer']) && $params['is_offer'] == 1;

                $product->is_offer = $isOffer ? 1 : 0;

                if ($isOffer) {
                    $product->offer_description = $params['offer_description'];
                    $product->offer_quantity    = $params['offer_quantity'];
                    $product->offer_price       = $params['offer_price'];
                    Log::info('Offer applied to product', [
                        'desc' => $product->offer_description,
                        'qty'  => $product->offer_quantity,
                        'price'=> $product->offer_price,
                    ]);
                } else {
                    $product->offer_description = null;
                    $product->offer_quantity    = null;
                    $product->offer_price       = null;
                }

                // ------------------------------------------------------------------
                // 5. SAVE
                // ------------------------------------------------------------------
                $product->save();

                $duration = round((microtime(true) - $start) * 1000, 2);

                Log::info('Product Saved Successfully', [
                    'product_id' => $product->id,
                    'is_edit'    => $isEdit,
                    'duration_ms'=> $duration,
                ]);

                return response()->json([
                    'status'  => 1,
                    'message' => $isEdit
                        ? 'Product updated successfully.'
                        : 'Product created successfully.',
                    'product_id' => $product->id
                ], 200);

            } catch (\Exception $e) {
                Log::error('Product Save Failed in Transaction', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'user_id' => $user->id,
                ]);

                throw $e; // rollback
            }
        });

    } catch (\Exception $e) {
        Log::critical('Product Save Critical Failure', [
            'error' => $e->getMessage(),
            'file'  => $e->getFile(),
            'line'  => $e->getLine(),
        ]);

        return response()->json([
            'status'  => 0,
            'message' => 'Something went wrong. Please try again.'
        ], 200);
    }
}

    public function details(Request $request, $id)
    {
        $getProductData = Product::with(['category', 'subcategory'])->where(['id' => $id, 'type' => 2])->first();
        if ($getProductData)
        {
            return response()->json(['status' => 1, 'message' => 'Product Data.', 'data' => $getProductData])->setStatusCode(200);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'No Product Found'])->setStatusCode(200);
        }
    }

    public function detailsByBarCode(Request $request, $bar_code)
    {
        $getProductData = Product::with(['category', 'subcategory'])->where(['bar_code' => $bar_code, 'type' => 2])->first();
        if ($getProductData)
        {
            return response()->json(['status' => 1, 'message' => 'Product Data.', 'data' => $getProductData])->setStatusCode(200);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'No Product Found'])->setStatusCode(200);
        }
    }

    public function update(Request $request)
    {
        $user = $request->checkTokenExistance->user;
        // create the validation rules ------------------------
        $rules = array(
            'product_id' => 'required',
            'name'   => 'required',
            // 'bar_code'   => 'required|unique:products,bar_code,'.$request->product_id.',id,type,2,user_id,' . $user->id .',deleted_at,NULL',
            'category_id'   => 'required',
            'price'   => 'required|min:0',
        );

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()->first()])->setStatusCode(200);
        }

        $params = $request->post();

        $product = Product::find($params['product_id']);
        if (!$product)
        {
            return response()->json(['status' => 0, 'message' => 'No Product Found'])->setStatusCode(200);
        }

        $product->name = $params['name'];
        
        if(isset($params['bar_code']) && !empty($params['bar_code']))
        {
            $product->bar_code = $params['bar_code'];
        }

        $product->description = $params['description'];
        $product->category_id = $params['category_id'];
        $product->price = $params['price'];
        $product->subcategory_id = (isset($params['subcategory_id']) && !empty($params['subcategory_id'])) ? $params['subcategory_id'] : 0;

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
            $destinationPath = public_path('/products');
            $image->move($destinationPath, $name);
            
            $product->featured_image = 'products/' . $name;
        }

        if(isset($params['is_offer']))
        {
            $product->is_offer = $params['is_offer'];
            
            if(isset($params['offer_description']) && !empty($params['offer_description']))
            {
                $product->offer_description = $params['offer_description'];
            }

            if(isset($params['offer_quantity']) && !empty($params['offer_quantity']))
            {
                $product->offer_quantity = $params['offer_quantity'];
            }

            if(isset($params['offer_price']) && !empty($params['offer_price']))
            {
                $product->offer_price = $params['offer_price'];
            }
        }

        if($product->save())
        {
            return response()->json(['status' => 1, 'message' => 'Product has updated successfully.'])->setStatusCode(200);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'Something went wrong.'])->setStatusCode(200); 
        }
    }

    public function updateProductStatus(Request $request)
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

        $getProductData = Product::find($params['id']);
        if (!$getProductData)
        {
            return response()->json(['status' => 0, 'message' => 'No Product Found'])->setStatusCode(200);
        }

        $message = 'Product Deactivated successfully';
        if ($params['status'])
        {
            $message = 'Product Activated successfully';
        }

        $getProductData->status = $params['status'];

        if ($getProductData->save())
        {
            return response()->json(['status' => 1, 'message' => $message])->setStatusCode(200);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'Something went wrong.'])->setStatusCode(200);
        }
    }

    public function importProducts(Request $request)
    {
        $user = $request->checkTokenExistance->user;

        $params = $request->post();
        if(!empty($params['category_name']))
        {
            $storeCategory = Category::where(['user_id' => $user->id, 'type' => 2, 'name' => $params['category_name']])->first();
            if(empty($storeCategory))
            {
                $category = Category::where(['type' => 1, 'name' => $params['category_name']])->first();

                $storeCategory = $category->replicate();
                $storeCategory->user_id = $user->id;
                $storeCategory->type = 2;
                $storeCategory->save();
            }
        }

        if(!empty($params['subcategory_name']))
        {
            $storeSubCategory = subCategory::where(['user_id' => $user->id, 'type' => 2, 'name' => $params['subcategory_name']])->first();
            if(empty($storeSubCategory))
            {
                $subCategory = subCategory::where(['type' => 1, 'name' => $params['subcategory_name']])->first();

                $storeSubCategory = $subCategory->replicate();
                $storeSubCategory->category_id = $storeCategory->id;
                $storeSubCategory->user_id = $user->id;
                $storeSubCategory->type = 2;
                $storeSubCategory->save();
            }
        }

        if(sizeof($params['productIds']) > 0)
        {
            foreach ($params['productIds'] as $keyP => $valueP)
            {
                $commonProduct = Product::find($valueP);
                $product = $commonProduct->replicate();

                $product->user_id = $user->id;
                $product->type = 2;

                if(!empty($params['category_name']))
                {
                    $product->category_id = $storeCategory->id;
                }

                if(!empty($params['subcategory_name']))
                {
                    $product->subcategory_id = $storeSubCategory->id;
                }

                $product->save();
            }

            return response()->json(['status' => 1, 'message' => 'Product imported successfully.'])->setStatusCode(200);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'Please select alteast one product.'])->setStatusCode(200);
        }
        
        return response()->json(['sub_categories' => $getSubCategories, 'status' => 1]);
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