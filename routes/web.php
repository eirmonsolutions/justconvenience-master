<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

// Auth::routes();

// Admin/Super Admin Auth
Route::any('signin', 'IndexController@signin')->name('signin');
Route::get('signout', 'IndexController@signOut')->name('signout');
// privacy-statement-just-convenience
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', 'IndexController@index')->name('index');

Route::get('/privacy-statement-just-convenience', 'IndexController@privacy')->name('privacy');
Route::get('/terms-and-conditions', 'IndexController@terms')->name('terms');



Route::get('order-invoice/{id}', 'Common\OrderController@orderInvoice')->name('order-invoice');

//Super Admin Routes
Route::group(['middleware' => ['SuperAdminUserType']], function () {
	Route::any('users', 'UserController@index')->name('users');
	Route::any('add-user', 'UserController@addUser')->name('add-user');
	Route::post('save-user', 'UserController@saveUser')->name('save-user');
	Route::get('delete-user/{id}', 'UserController@deleteUser')->name('delete-user');
	Route::any('change-password/{id}', 'UserController@changePassword')->name('change-password');
	Route::get('malls', 'MallDashoardController@index')->name('malls');
});

Route::group(['middleware' => ['CommonUserType']], function ()
{
	Route::group(['middleware' => ['AdminUserType']], function ()
	{
		// Route::get('customers', 'Admin\CustomerController@index')->name('customers');
		// Route::any('customer-details/{id}', 'Admin\CustomerController@customerDetails')->name('customer-details');
		Route::get('eliminated-customers', 'IndexController@eliminatedCustomers')->name('eliminated-customers');
		Route::any('delete-customer/{customer_id}', 'Admin\CustomerController@deleteCustomer')->name('delete-customer');
		Route::get('customer-tickets/{customer_id}', 'Admin\CustomerController@customerTickets')->name('customer-tickets');
		Route::get('invoices/{customer_id}', 'Admin\InvoiceController@index')->name('invoices');
		Route::get('pending_invoices', 'Admin\InvoiceController@pendingInvoices')->name('pending_invoices');
		Route::get('tickets', 'Admin\TicketsController@index')->name('tickets');
		Route::get('random-winner', 'IndexController@randomWinner')->name('random-winner');
		Route::get('send_email/{customer_id}', 'IndexController@sendEmail')->name('send_email');

		Route::post('customers-invoice-details', 'Admin\CustomerController@customerInvoiceDetails')->name('customers-invoice-details');
		
		Route::any('customers-invoice-approved/{id}/{customer_id}', 'Admin\CustomerController@customerInvoiceApproved')->name('customers-invoice-approved');

		Route::any('send-customer-email', 'IndexController@sendCustomerEmail')->name('send-customer-email');
		Route::any('send-contest-customer-email', 'Admin\EmailAreaController@sendContestCustomerEmail')->name('send-contest-customer-email');
		Route::get('get-winners', 'IndexController@getWinners')->name('get-winners');

		Route::post('send_winner_email', 'IndexController@sendWinnerEmail')->name('send_winner_email');

		Route::any('edit-invoice/{id}/{customer_id}', 'Admin\InvoiceController@editCustomerInvoice')->name('edit-invoice');
		Route::post('update-invoice', 'Admin\InvoiceController@updateInvoice')->name('update-invoice');

		Route::any('approved-invoice/{id}/{customer_id}', 'Admin\CustomerController@approvedCustomerInvoice')->name('approved-invoice');
		Route::any('approved-all-invoice', 'IndexController@approvedAllCustomerInvoice')->name('approved-all-invoice');
		Route::post('send-approved-email', 'IndexController@sendApprovedEmail')->name('send-approved-email');
		Route::any('delete-invoice/{id}/{customer_id}', 'Admin\InvoiceController@deleteCustomerInvoice')->name('delete-invoice');

		// Edit Settings
		Route::get('edit-settings', 'Admin\SettingController@editSettings')->name('edit-settings');
		Route::any('update-settings', 'Admin\SettingController@updateSettings')->name('update-settings');

		// Store Coupons
		Route::get('store-coupons', 'Admin\StoreCouponController@index')->name('store-coupons');
		Route::get('add-store-coupon', 'Admin\StoreCouponController@add')->name('add-store-coupon');
		Route::post('save-store-coupon', 'Admin\StoreCouponController@save')->name('save-store-coupon');
		Route::get('update-store-coupon-status/{store_coupon_id}/{status}', 'Admin\StoreCouponController@updateStatus')->name('update-store-coupon-status');

		// Categories
		Route::get('cat', 'Admin\CategoryController@index')->name('categories');
		Route::get('add-category', 'Admin\CategoryController@addCategory')->name('add-category');
		Route::post('save-category', 'Admin\CategoryController@saveCategory')->name('save-category');
		Route::get('edit-category/{category_id}', 'Admin\CategoryController@editCategory')->name('edit-category');
		Route::post('update-category', 'Admin\CategoryController@updateCategory')->name('update-category');
		Route::get('update-category-status/{category_id}/{status}', 'Admin\CategoryController@updateStatus')->name('update-category-status');

		// Sub Categories
		Route::get('sub-categories', 'Admin\SubCategoryController@index')->name('sub-categories');
		Route::get('add-sub-category', 'Admin\SubCategoryController@addSubCategory')->name('add-sub-category');
		Route::post('save-sub-category', 'Admin\SubCategoryController@saveSubCategory')->name('save-sub-category');
		Route::get('edit-sub-category/{sub_category_id}', 'Admin\SubCategoryController@editSubCategory')->name('edit-sub-category');
		Route::post('update-sub-category', 'Admin\SubCategoryController@updateSubCategory')->name('update-sub-category');
		Route::get('update-sub-category-status/{sub_category_id}/{status}', 'Admin\SubCategoryController@updateStatus')->name('update-sub-category-status');

		// Products
		Route::get('prod', 'Admin\ProductController@index')->name('products');
		Route::get('disabled-products', 'Admin\ProductController@disabledProducts')->name('disabled-products');
		Route::get('add-product', 'Admin\ProductController@add')->name('add-product');
		Route::post('save-product', 'Admin\ProductController@save')->name('save-product');
		Route::get('product-details/{product_id}', 'Admin\ProductController@details')->name('product-details');
		Route::get('edit-product/{product_id}', 'Admin\ProductController@edit')->name('edit-product');
		Route::post('update-product', 'Admin\ProductController@update')->name('update-product');
		Route::post('update-product-details', 'Admin\ProductController@updateDetails')->name('update-product-details');
		Route::get('update-product-status/{product_id}/{status}', 'Admin\ProductController@updateStatus')->name('update-product-status');

		// Get sub categories according to category json format
		Route::get('get-sub-categories/{category_id?}', 'Admin\ProductController@getSubCategories')->name('get-sub-categories');

		// Customers
		Route::get('cust', 'Admin\CustomerController@index')->name('customers');
		Route::get('get_customers', 'Admin\CustomerController@get_customers')->name('get_customers');
		Route::any('customer-details/{id}', 'Admin\CustomerController@customerDetails')->name('customer-details');
		Route::any('edit-customer-details/{customer_id}', 'Admin\CustomerController@editCustomerDetails')->name('edit-customer-details');
		Route::post('update-customer-details', 'Admin\CustomerController@updateCustomerDetails')->name('update-customer-details');
		Route::get('download-customer-excel', 'Admin\CustomerController@downloadCustomerExcel')->name('download-customer-excel');

		// Orders
		Route::get('orders', 'Admin\OrderController@index')->name('orders');
		Route::get('get_orders', 'Admin\OrderController@get_orders')->name('get_orders');
		Route::any('order-details/{id}', 'Admin\OrderController@orderDetails')->name('order-details');

		// Supervisor
		Route::any('supervisors', 'Admin\SupervisorController@index')->name('supervisors');
		Route::any('add-supervisor', 'Admin\SupervisorController@addUser')->name('add-supervisor');
		Route::post('save-supervisor', 'Admin\SupervisorController@saveUser')->name('save-supervisor');
		Route::get('delete-supervisor/{id}', 'Admin\SupervisorController@deleteUser')->name('delete-supervisor');
		Route::any('supervisor-change-password/{id}', 'Admin\SupervisorController@changePassword')->name('supervisor-change-password');

		// Contests
		Route::get('contest', 'Admin\ContestController@index')->name('contests');
		Route::get('add-contest', 'Admin\ContestController@addContest')->name('add-contest');
		Route::post('save-contest', 'Admin\ContestController@saveContest')->name('save-contest');
		Route::get('edit-contest/{contest_id}', 'Admin\ContestController@editContest')->name('edit-contest');
		Route::post('update-contest', 'Admin\ContestController@updateContest')->name('update-contest');
		Route::get('contest-details/{contest_id}', 'Admin\ContestController@contestDetails')->name('contest-details');
		Route::get('update-contest-status/{contest_id}/{status}', 'Admin\ContestController@updateContestStatus')->name('update-contest-status');



		//Shops
		Route::get('shops', 'Admin\ShopController@index')->name('shops');
		Route::get('add-shop', 'Admin\ShopController@addShop')->name('add-shop');
		Route::post('save-shop', 'Admin\ShopController@saveShop')->name('save-shop');
		Route::get('edit-shop/{shop_id}', 'Admin\ShopController@editShop')->name('edit-shop');
		Route::post('update-shop', 'Admin\ShopController@updateShop')->name('update-shop');
		Route::any('delete-shop/{shop_id}', 'Admin\ShopController@deleteShop')->name('delete-shop');
		Route::get('shop-details/{shop_id}', 'Admin\ShopController@shopDetails')->name('shop-details');
		Route::any('delete-all-shops', 'Admin\ShopController@deleteAllShops')->name('delete-all-shops');
		Route::any('delete-selected-shops', 'Admin\ShopController@deleteSelectedShops')->name('delete-selected-shops');
		Route::get('insert-shops', 'IndexController@insertShops')->name('insert-shops');
		Route::post('import_records', 'HomeController@import_records')->name('import_records');

		// Term and Conditions
		Route::get('edit-tnc', 'Admin\TncController@editTnc')->name('edit-tnc');
		Route::any('update-tnc', 'Admin\TncController@updateTnc')->name('update-tnc');

		Route::get('get_tickets', 'Admin\TicketsController@get_tickets')->name('get_tickets');
		// Route::get('get_customers', 'Admin\CustomerController@getCustomers')->name('get_customers');
		Route::get('get_customers1', 'Admin\CustomerController@getCustomers1')->name('get_customers1');
		Route::get('download_excel', 'Admin\TicketsController@downloadExcel')->name('download_excel');
		Route::get('download_customers', 'Admin\CustomerController@downloadCustomers')->name('download_customers');

		// Stores
		Route::get('add-store', 'Admin\StoreController@addStore')->name('add-store');
		Route::post('save-store', 'Admin\StoreController@saveStore')->name('save-store');
		Route::get('update-store-status/{store_id}/{status}', 'Admin\StoreController@updateStoreStatus')->name('update-store-status');
	});

	Route::get('dashboard', 'IndexController@dashboard')->name('dashboard');

	// Stores
	Route::get('store', 'Admin\StoreController@index')->name('stores');
	Route::get('edit-store/{store_id}', 'Admin\StoreController@editStore')->name('edit-store');
	Route::post('update-store', 'Admin\StoreController@updateStore')->name('update-store');
	Route::get('store-details/{store_id}', 'Admin\StoreController@storeDetails')->name('store-details');
});