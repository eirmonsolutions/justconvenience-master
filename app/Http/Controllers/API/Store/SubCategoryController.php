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


use Illuminate\Support\Facades\Log;
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


    public function saveSubCategory(Request $request)
{
    // Step 1: Log incoming request
    Log::info('=== saveSubCategory API Called ===', [
        'ip' => $request->ip(),
        'user_agent' => $request->header('User-Agent'),
        'all_input' => $request->except(['featured_image']), // avoid huge file logs
    ]);

    // Step 2: Validate token existence
    // if (!$request->has('checkTokenExistance') || !$request->checkTokenExistance?->user) {
    //     Log::warning('Unauthorized: Invalid or missing token');
    //     return response()->json([
    //         'status' => 0,
    //         'message' => 'Unauthorized. Invalid token.'
    //     ], 401);
    // }

    $user = $request->checkTokenExistance->user;
    Log::info('Authenticated User', ['user_id' => $user->id, 'name' => $user->name]);

    // Step 3: Validation rules
    $rules = [
        'name'          => 'required|string|max:255',
        'category_id'   => 'required|integer|exists:categories,id',
        'featured_image'=> 'required|file|mimes:jpeg,jpg,png,svg|max:5120', // 5MB
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $error = $validator->errors()->first();
        Log::warning('Validation Failed', ['error' => $error, 'input' => $request->all()]);
        return response()->json(['status' => 0, 'message' => $error], 200);
    }

    // Step 4: Log sanitized input
    $params = $request->only(['name', 'category_id', 'is_offer', 'offer_quantity', 'offer_price']);
    Log::info('Validated Input', $params);

    try {
        // Step 5: Create subcategory
        $subCategory = new subCategory;
        $subCategory->name = $params['name'];
        $subCategory->user_id = $user->id;
        $subCategory->type = 2;
        $subCategory->category_id = $params['category_id'];

        // Step 6: Handle image upload
        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');

            // Log file details
            Log::info('Image Upload Received', [
                'original_name' => $image->getClientOriginalName(),
                'mime' => $image->getMimeType(),
                'size' => $image->getSize(),
                'extension' => $image->getClientOriginalExtension(),
            ]);

            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $path = public_path('sub_categories');

            // Ensure directory exists
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
                Log::info('Created directory', ['path' => $path]);
            }

            $moved = $image->move($path, $filename);

            if ($moved) {
                $subCategory->featured_image = 'public/sub_categories/' . $filename;
                Log::info('Image saved successfully', ['path' => $subCategory->featured_image]);
            } else {
                Log::error('Failed to move uploaded file');
                return response()->json(['status' => 0, 'message' => 'Image upload failed.'], 200);
            }
        } else {
            Log::warning('No file uploaded for featured_image');
            return response()->json(['status' => 0, 'message' => 'Image is required.'], 200);
        }

        // Step 7: Handle offer fields
        if ($request->has('is_offer') && $request->filled('is_offer')) {
            $isOffer = (int) $request->input('is_offer');
            $subCategory->is_offer = $isOffer;

            if ($isOffer === 1) {
                $subCategory->offer_quantity = $request->filled('offer_quantity')
                    ? (int) $request->input('offer_quantity')
                    : null;

                $subCategory->offer_price = $request->filled('offer_price')
                    ? (float) $request->input('offer_price')
                    : null;

                Log::info('Offer enabled', [
                    'quantity' => $subCategory->offer_quantity,
                    'price' => $subCategory->offer_price,
                ]);
            }
        }

        // Step 8: Save to DB
        $saved = $subCategory->save();

        if ($saved) {
            Log::info('Subcategory created successfully', [
                'subcategory_id' => $subCategory->id,
                'name' => $subCategory->name,
            ]);

            return response()->json([
                'status' => 1,
                'message' => 'Sub Category has created successfully.',
                'data' => $subCategory
            ], 200);
        } else {
            Log::error('Database save failed', ['model' => $subCategory->toArray()]);
            return response()->json(['status' => 0, 'message' => 'Failed to save subcategory.'], 200);
        }

    } catch (\Exception $e) {
        Log::error('Unexpected error in saveSubCategory', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'status' => 0,
            'message' => 'Server error. Please try again.'
        ], 500);
    } }

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