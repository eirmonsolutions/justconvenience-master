<?php

namespace App\Http\Controllers\API\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Order;
use PDF;

class OrderController extends Controller
{
    public function orderInvoice(Request $request, $order_id)
    {
        $params = $request->all();
        $user = $request->checkTokenExistance->user;

        if($user->user_role == 4)
        {
            $getOrderDetails = Order::with('orderDetails.product')->where('id', $order_id)->where(function($q) use ($user) {
                                            $q->where(['user_id' => $user->id]);
                                        })->first();    
        }
        else if($user->user_role == 3)
        {
            $getOrderDetails = Order::with('orderDetails.product')->where('id', $order_id)->where(function($q) use ($user) {
                                            $q->where(['store_id' => $user->id]);
                                        })->first();
        }
        else
        {
            $getOrderDetails = Order::with('orderDetails.product')->where('id', $order_id)->first();
        }

        if($getOrderDetails)
        {
            $html = view('invoices.order_invoice', ['order_details' => $getOrderDetails])->render();
            // return view('invoices.order_invoice', ['order_details' => $getOrderDetails]);
            $pdf_doc = app('dompdf.wrapper');
            $context = stream_context_create([
                        'ssl' => [
                            'allow_self_signed'=> TRUE,
                            'verify_peer' => FALSE,
                            'verify_peer_name' => FALSE,
                        ]
                    ]);

            $fileName = 'pdf/' . $getOrderDetails->order_number . '_invoice.pdf';
            $pdf_doc = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
            $pdf_doc->getDomPDF()->setHttpContext($context);
            $pdf_doc = PDF::loadView('invoices.order_invoice', ['order_details' => $getOrderDetails])->save(public_path($fileName));
            // $pdf_doc = PDF::loadView('invoices.order_invoice', ['order_details' => $getOrderDetails])->setPaper('a5', 'landscape')->save(public_path($fileName));

            $pdfURL = url('/') . '/' . $fileName;
            return response()->json(['status' => 1, 'message' => 'Order Details.', 'data' => $getOrderDetails, 'order_invoice_url' => $pdfURL, 'html' => $html, 'webURL' => route('order-invoice', ['id' => $getOrderDetails->id])])->setStatusCode(200);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'No order found.'])->setStatusCode(200);
        }
    }
}
