<?php

namespace App\Http\Controllers\API\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use Session;

use App\Product;
use App\User;
use App\Order;
use App\PushNotify;
use App\orderDetail;

class OrderController extends Controller
{
    public function myOrders(Request $request)
    {
        $user = $request->checkTokenExistance->user;
        $params = $request->all();

        $getOrders = Order::with('orderDetails.product')->where(function($q) use ($user) {
                                        
                                        $q->where(['store_id' => $user->id]);
                                        
                                    });

        /*if (isset($params['start_date']) && !empty($params['start_date']) && isset($params['end_date']) && !empty($params['end_date']))
        {
            $start_date = date('Y-m-d', strtotime($params['start_date']));
            $end_date = date('Y-m-d', strtotime($params['end_date']));

            $getOrders->whereDate('created_at' , '>=', $start_date);
            $getOrders->whereDate('created_at' , '<=', $end_date);
        }
        else if(isset($params['start_date']) && !empty($params['start_date']))
        {
            $start_date = date('Y-m-d', strtotime($params['start_date']));
            $getOrders->whereDate('created_at' , '>=', $start_date);

        }
        else if(isset($params['end_date']) && !empty($params['end_date']))
        {
            $end_date = date('Y-m-d', strtotime($params['end_date']));
            $getOrders->whereDate('created_at' , '<=', $end_date);
        }*/

        if (isset($params['date']) && !empty($params['date']))
        {
            $date = date('Y-m-d', strtotime($params['date']));

            $getOrders->whereDate('created_at', $date);
        }

        if (isset($params['status']) && !empty($params['status']))
        {
            $getOrders->where('status', $params['status']);
        }

        $getOrders = $getOrders->orderBy('id', 'desc')->paginate();
        return response()->json(['status' => 1, 'message' => 'My Orders', 'data' => $getOrders])->setStatusCode(200);
    }

    public function orderDetails(Request $request)
    {
        $params = $request->all();
        $user = $request->checkTokenExistance->user;
        $getOrderDetails = Order::with('orderDetails.product')->where('id', $params['order_id'])->where(function($q) use ($user) {
                                        
                                        $q->where(['store_id' => $user->id]);
                                        
                                    })->first();
        
        if($getOrderDetails)
        {
            return response()->json(['status' => 1, 'message' => 'Order Details.', 'data' => $getOrderDetails])->setStatusCode(200);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'No order found.'])->setStatusCode(200);
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

        $getOrderData = Order::find($params['id']);
        if (!$getOrderData)
        {
            return response()->json(['status' => 0, 'message' => 'No Order Found'])->setStatusCode(200);
        }

        $message = 'Order status updated successfully';
        
        $old_status = $getOrderData->status;
        $getOrderData->status = $params['status'];

        if ($getOrderData->save())
        {
            if($old_status != $params['status'])
            {
                try 
                {
                    switch ($old_status) {
                        case ORDER_READY_STATUS:
                            $from = "Ready";
                            break;

                        case ORDER_COMPLETED_STATUS:
                            $from = "Completed";
                            break;

                        case ORDER_DECLINED_STATUS:
                            $from = "Declined";
                            break;

                        case ORDER_ONDELIVERY_STATUS:
                            $from = "Delivered";
                            break;
                        
                        default:
                            $from = "Pending";
                            break;
                    }

                    switch ($params['status']) {
                        case ORDER_READY_STATUS:
                            $to = "Ready";
                            break;

                        case ORDER_COMPLETED_STATUS:
                            $to = "Completed";
                            break;

                        case ORDER_DECLINED_STATUS:
                            $to = "Declined";
                            break;

                        case ORDER_ONDELIVERY_STATUS:
                            $to = "Delivered";
                            break;
                        
                        default:
                            $to = "Pending";
                            break;
                    }

                    $push_data = array(
                        'body' => "Your Order status has been updated from {$from} to {$to}",
                        'title' => 'Order Status'
                    );
                    PushNotify::send_notif($getOrderData->user_id, $push_data, ORDER_NOTIFICATION_CASE, $getOrderData);
                } 
                catch (Exception $e) 
                {
                    #code...
                }
            }

            return response()->json(['status' => 1, 'message' => $message])->setStatusCode(200);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'Something went wrong.'])->setStatusCode(200);
        }
    }
}