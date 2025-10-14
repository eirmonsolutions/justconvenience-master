<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Order;

class OrderController extends Controller
{
    public function orderInvoice(Request $request, $order_id)
    {
        $getOrderDetails = Order::with('orderDetails.product')->where('id', $order_id)->first();
        if($getOrderDetails)
        {
            return view('invoices.order_invoice', ['order_details' => $getOrderDetails]);
        }

        Session::flash('message', 'No order found.'); 
        Session::flash('class', 'danger');

        return redirect()->route('index');
    }
}
