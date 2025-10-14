<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Store Authentication Part
    Route::get('store-coupon-details', 'API\Store\StoreCouponController@couponDetails');
    Route::post('store-register','API\Store\RegisterController@store_register');
    Route::post('store-login', 'API\Store\LoginController@store_login');
    Route::post('store-forgot-password', 'API\Store\ForgotController@forgot')->name('store-forgot-password');
    Route::post('store-reset-password', 'API\Store\ForgotController@resetPassword')->name('store-reset-password');
//Authentication Part Ends

// Customer Authentication Part
    Route::post('customer-register','API\Customer\RegisterController@customer_register');
    Route::post('customer-login', 'API\Customer\LoginController@customer_login');
    Route::post('customer-forgot-password', 'API\Customer\ForgotController@forgot')->name('customer-forgot-password');
    Route::post('customer-reset-password', 'API\Customer\ForgotController@resetPassword')->name('customer-reset-password');
//Authentication Part Ends

// Customer APP
    // Store List
        Route::get('store-list', 'API\Customer\StoreController@index')->name('store-list');
    // Store List Ends

    // Categories
        Route::get('store-categories', 'API\Customer\CategoryController@index')->name('store-categories');
    // Categories Part Ends

    // Products
        Route::get('store-products', 'API\Customer\ProductController@index')->name('store-products');
        Route::get('store-offer-products', 'API\Customer\ProductController@offerProducts')->name('store-offer-products');
        Route::get('store-product-details/{product_id}', 'API\Customer\ProductController@details')->name('store-product-details');
    // Products Parts End

    // Get sub categories according to category json format
    Route::get('get-customer-side-sub-categories/{category_id?}', 'API\Customer\CategoryController@getSubCategories')->name('get-customer-side-sub-categories');

// Customer APP Ends
    
Route::group(['prefix' => '', 'middleware' => 'checkAppAuth'], function()
{
    // Start Store APP APIS

        // User Profile
            Route::post('store-logout', 'API\Store\LoginController@logout')->name('store-logout');
            Route::get('store-profile', 'API\Store\ProfileController@profile');
            Route::get('store-earning', 'API\Store\ProfileController@earning');
            Route::post('store-profile-update', 'API\Store\ProfileController@profileupdate');
            Route::post('store-opening-status-update', 'API\Store\ProfileController@storeOpeningStatusupdate');
            Route::post('store-change-password', 'API\Store\ProfileController@changePassword');
        // User Profile Ends

        // Common Categories
            Route::get('common-categories', 'API\Store\CategoryController@commonCategories')->name('common-categories');

        // Categories
            Route::get('categories', 'API\Store\CategoryController@index')->name('categories');
            Route::post('save-category', 'API\Store\CategoryController@saveCategory')->name('save-category');
            Route::get('category-details/{category_id}', 'API\Store\CategoryController@categoryDetails')->name('category-details');
            Route::post('update-category', 'API\Store\CategoryController@updateCategory')->name('update-category');
            Route::post('update-category-status', 'API\Store\CategoryController@updateStatus')->name('update-category-status');
        // Categories Part Ends

        // Get sub categories according to category json format
        Route::get('get-sub-categories/{category_id?}', 'API\Store\CategoryController@getSubCategories')->name('get-sub-categories');

        // Sub Categories
            Route::get('sub-categories', 'API\Store\SubCategoryController@index')->name('sub-categories');
            Route::post('save-sub-category', 'API\Store\SubCategoryController@saveSubCategory')->name('save-sub-category');
            Route::get('sub-category-details/{sub_category_id}', 'API\Store\SubCategoryController@subCategoryDetails')->name('sub-category-details');
            Route::post('update-sub-category', 'API\Store\SubCategoryController@updateSubCategory')->name('update-sub-category');
            Route::post('update-sub-category-status', 'API\Store\SubCategoryController@updateStatus')->name('update-sub-category-status');
        // Sub Categories Part End
        
        // Products
            Route::post('common-products', 'API\Store\ProductController@commonProducts')->name('common-products');
            Route::get('products', 'API\Store\ProductController@index')->name('products');
            Route::get('disabled-products', 'API\Store\ProductController@disabledProducts');
            Route::post('save-product', 'API\Store\ProductController@save')->name('save-product');
            Route::get('product-details/{product_id}', 'API\Store\ProductController@details')->name('product-details');
            Route::get('product-details-by-barcode/{bar_code}', 'API\Store\ProductController@detailsByBarCode')->name('product-details-by-barcode');
            Route::post('update-product', 'API\Store\ProductController@update')->name('update-product');
            Route::post('update-product-status', 'API\Store\ProductController@updateProductStatus')->name('update-product-status');

            Route::post('import-products', 'API\Store\ProductController@importProducts')->name('import-products');
        // Products Parts End

        // Order Part Start
            Route::get('store-orders', 'API\Store\OrderController@myOrders');
            Route::get('store-orders-details', 'API\Store\OrderController@orderDetails');
            Route::post('update-order-status', 'API\Store\OrderController@updateStatus')->name('update-order-status');
        // Order Part Ends

    // End Store APP APIS

    // Customer APP APIS

        // User Profile
            Route::post('customer-logout', 'API\Customer\LoginController@logout')->name('customer-logout');
            Route::get('customer-profile', 'API\Customer\ProfileController@profile');
            Route::post('customer-profile-update', 'API\Customer\ProfileController@profileupdate');
            Route::post('customer-change-password', 'API\Customer\ProfileController@changePassword');
        // User Profile Ends

        // Order Part Start
            Route::post('create-order', 'API\Customer\OrderController@createOrder');
            Route::get('my-orders', 'API\Customer\OrderController@myOrders');
            Route::get('orders-details', 'API\Customer\OrderController@orderDetails');
        // Order Part Ends

    // End Customer APP APIS

    //Common APIs
    Route::get('order-invoice/{id}', 'API\Common\OrderController@orderInvoice');
    Route::post('payment-transaction', 'API\Customer\OrderController@paymentTransaction');
});

Route::post('3Ds-payment-request', 'API\Customer\OrderController@ThreeDsPaymentRequest');
Route::get('order-status', 'API\Customer\OrderController@orderStatus')->name('order-status');