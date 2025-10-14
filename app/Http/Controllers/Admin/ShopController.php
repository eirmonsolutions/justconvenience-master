<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use Session;

use App\Shop;

use Carbon\Carbon;

class ShopController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        ini_set('memory_limit','2000M');
        ini_set('max_execution_time', '0');
        // $this->middleware('auth');
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

        $shops = Shop::withCount(['TodayCustomerInvoices'])->
                            /*    with(['customerInvoices' => function($query) {
                                    $query->whereDate('created_at', date('Y-m-d'))
                                        ->whereHas('customer', function ($query1) {
                                            $query1->where('status', 1);
                                    })->select(\DB::raw('SUM(invoice_amount) as total_invoice_amount'), 'shop_id');
                                }])
                            ->*/
                            with(['customerInvoices', 'Last30DaysCustomerInvoices', 'Last7DaysCustomerInvoices'])
                            ->orderBy('id', 'desc')->get();

        if(sizeof($shops) > 0)
        {
            foreach ($shops as $keyS => $valueS)
            {
                $shops[$keyS]->todayInvoiceAmountSum = $valueS->TodayCustomerInvoices->sum('invoice_amount');
                $shops[$keyS]->todayInvoiceAmountAvg = $valueS->TodayCustomerInvoices->avg('invoice_amount');
                $shops[$keyS]->todayInvoiceAmountMax = $valueS->TodayCustomerInvoices->max('invoice_amount');
                $shops[$keyS]->todayCustomerCount = $valueS->TodayCustomerInvoices->unique('user_id')->count();
                $shops[$keyS]->todayTotalReciepts = $valueS->TodayCustomerInvoices->count();

                $shops[$keyS]->invoiceAmountSum = $valueS->customerInvoices->sum('invoice_amount');
                $shops[$keyS]->invoiceAmountAvg = $valueS->customerInvoices->avg('invoice_amount');
                $shops[$keyS]->invoiceAmountMax = $valueS->customerInvoices->max('invoice_amount');
                $shops[$keyS]->customerCount = $valueS->customerInvoices->unique('user_id')->count();
                $shops[$keyS]->totalReciepts = $valueS->customerInvoices->count();
                
                $shops[$keyS]->lastWeekTotalReciepts = $valueS->Last7DaysCustomerInvoices->count();

                $shops[$keyS]->weeklyRecieptCountAvg = '';
                if ($shops[$keyS]->lastWeekTotalReciepts > 0)
                {
                    $shops[$keyS]->weeklyRecieptCountAvg = number_format($shops[$keyS]->lastWeekTotalReciepts/7, 2);
                }

                $shops[$keyS]->customerCountAvg = '';
                $getLast30DaysCustomerCount = $valueS->Last30DaysCustomerInvoices->unique('user_id')->count();
                if($getLast30DaysCustomerCount > 0)
                {
                    $shops[$keyS]->customerCountAvg = number_format($getLast30DaysCustomerCount/30, 2);
                }

                $shops[$keyS]->recieptCountAvg = '';
                $getLast30DaysRecieptCount = $valueS->Last30DaysCustomerInvoices->count();
                if($getLast30DaysRecieptCount > 0)
                {
                    $shops[$keyS]->recieptCountAvg = number_format($getLast30DaysRecieptCount/30, 2);
                }
            }

        }

        return view('admin.shops.index', ['shops' => $shops, 'params' => $params]);
    }

    public function addShop(Request $request)
    {
        return view('admin.shops.add_shop');
    }

    public function saveShop(Request $request)
    {
        // create the validation rules ------------------------
        $rules = array(
            'shop_name'             => 'required',
            'contract_number'             => 'required',
            'shopping_center_id'             => 'required',
            'shopping_center_name'             => 'required',
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

            return redirect()->route('add-shop')->withInput($request->all());
        }

        $params = $request->post();

        
        $shop = new Shop;
        $shop->shop_name = $params['shop_name'];
        $shop->contract_number = $params['contract_number'];
        $shop->shopping_center_id = $params['shopping_center_id'];
        $shop->shopping_center_name = $params['shopping_center_name'];

        if($shop->save())
        {
        	Session::flash('message', 'Shop has created successfully.'); 
        	Session::flash('class', 'success');
        }
        else
        {
            Session::flash('message', 'Something went wrong.'); 
            Session::flash('class', 'danger'); 
        }

        return redirect()->route('shops');
    }

    public function editShop(Request $request, $id)
    {
        $getShopData = Shop::find($id);
        if ($getShopData)
        {
        	return view('admin.shops.edit_shop', ['shop' => $getShopData]);
        }
        else
        {
        	Session::flash('message', 'No Shop Found'); 
        	Session::flash('class', 'danger');	
        	return redirect()->route('shops');
        }

    }

    public function updateShop(Request $request)
    {
        // create the validation rules ------------------------
        $rules = array(
            'shop_name'             => 'required',
            'contract_number'             => 'required',
            'shopping_center_id'             => 'required',
            'shopping_center_name'             => 'required',
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

            return redirect()->route('edit-shop', ['shop_id' => $request->id])->withInput($request->all());
        }

        $params = $request->post();

        $shop = Shop::find($params['id']);
        if (!$shop)
        {
        	Session::flash('message', 'No Shop Found'); 
        	Session::flash('class', 'danger');	
        	return redirect()->route('shops');
        }

        $shop->shop_name = $params['shop_name'];
        $shop->contract_number = $params['contract_number'];
        $shop->shopping_center_id = $params['shopping_center_id'];
        $shop->shopping_center_name = $params['shopping_center_name'];

        if($shop->save())
        {
        	Session::flash('message', 'Shop has updated successfully.'); 
        	Session::flash('class', 'success');
        }
        else
        {
            Session::flash('message', 'Something went wrong.'); 
            Session::flash('class', 'danger'); 
        }

        return redirect()->route('shops');
    }

    public function deleteShop(Request $request, $id)
    {
        $params = $request->post();

        $shop = Shop::find($id);

        if (!$shop)
        {
            Session::flash('message', 'No Shop Found'); 
            Session::flash('class', 'danger');  
            return redirect()->route('shops');
        }

        if ($shop->delete())
        {
            Session::flash('message', 'Shop has deleted successfully.'); 
            Session::flash('class', 'success');
        }
        else
        {
            Session::flash('message', 'No Shop Found'); 
            Session::flash('class', 'danger');  
        }
        return redirect()->route('shops');

    }

    public function deleteAllShops(Request $request)
    {
        $params = $request->post();

        if (Shop::whereNotNull('id')->delete())
        {
            return response()->json(['status' => 1, 'message' => 'Your shops has been deleted.']);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'Something went wrong.']);  
        }
        return redirect()->route('shops');
    }

    public function deleteSelectedShops(Request $request)
    {
        $params = $request->post();

        if (!array_key_exists('shop_id', $params))
        {
            Session::flash('message', 'No Shop Selected'); 
            Session::flash('class', 'danger');  
            return redirect()->route('shops');
        }

        if (Shop::whereIn('id', $params['shop_id'])->delete())
        {
            Session::flash('message', 'Selected Shops has deleted successfully.'); 
            Session::flash('class', 'success');
        }
        else
        {
            Session::flash('message', 'No Shop Found'); 
            Session::flash('class', 'danger');  
        }
        return redirect()->route('shops');
    }

    public function shopDetails(Request $request, $id)
    {
        $getShopData = Shop::withCount(['TodayCustomerInvoices'])->with(['customerInvoices', 'Last30DaysCustomerInvoices', 'Last7DaysCustomerInvoices'])->where('id', $id)->orderBy('id', 'desc')->first();
        if ($getShopData)
        {
            $getShopData->todayInvoiceAmountSum = $getShopData->TodayCustomerInvoices->sum('invoice_amount');
            $getShopData->todayInvoiceAmountAvg = $getShopData->TodayCustomerInvoices->avg('invoice_amount');
            $getShopData->todayInvoiceAmountMax = $getShopData->TodayCustomerInvoices->max('invoice_amount');
            $getShopData->todayCustomerCount = $getShopData->TodayCustomerInvoices->unique('user_id')->count();
            $getShopData->todayTotalReciepts = $getShopData->TodayCustomerInvoices->count();

            $getShopData->invoiceAmountSum = $getShopData->customerInvoices->sum('invoice_amount');
            $getShopData->invoiceAmountAvg = $getShopData->customerInvoices->avg('invoice_amount');
            $getShopData->invoiceAmountMax = $getShopData->customerInvoices->max('invoice_amount');
            $getShopData->customerCount = $getShopData->customerInvoices->unique('user_id')->count();
            $getShopData->totalReciepts = $getShopData->customerInvoices->count();

            $getShopData->lastWeekTotalReciepts = $getShopData->Last7DaysCustomerInvoices->count();

            $getShopData->weeklyRecieptCountAvg = '';
            if ($getShopData->lastWeekTotalReciepts > 0)
            {
                $getShopData->weeklyRecieptCountAvg = number_format($getShopData->lastWeekTotalReciepts/7, 2);
            }

            $getShopData->customerCountAvg = '';
            $getLast30DaysCustomerCount = $getShopData->Last30DaysCustomerInvoices->unique('user_id')->count();
            if($getLast30DaysCustomerCount > 0)
            {
                $getShopData->customerCountAvg = number_format($getLast30DaysCustomerCount/30, 2);
            }

            $getShopData->recieptCountAvg = '';
            $getLast30DaysRecieptCount = $getShopData->Last30DaysCustomerInvoices->count();
            if($getLast30DaysRecieptCount > 0)
            {
                $getShopData->recieptCountAvg = number_format($getLast30DaysRecieptCount/30, 2);
            }


            return view('admin.shops.shop_details', ['shop' => $getShopData]);
        }
        else
        {
            Session::flash('message', 'No Shop Found'); 
            Session::flash('class', 'danger');  
            return redirect()->route('shops');
        }

    }

}