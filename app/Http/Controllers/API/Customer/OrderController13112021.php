<?php

namespace App\Http\Controllers\API\Customer;

include(app_path('payments/gateway.php'));

use GlobalPayments\Api\Entities\Address;
use GlobalPayments\Api\Entities\Enums\AddressType;
use GlobalPayments\Api\ServiceConfigs\Gateways\GpEcomConfig;
use GlobalPayments\Api\HostedPaymentConfig;
use GlobalPayments\Api\Entities\HostedPaymentData;
use GlobalPayments\Api\Entities\Enums\HppVersion;
use GlobalPayments\Api\Entities\Exceptions\ApiException;
use GlobalPayments\Api\Services\HostedService;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use Session;

use App\Product;
use App\User;
use App\Order;
use App\orderDetail;
use App\paymentRequest;

// use \P3\SDK\Gateway;

class OrderController extends Controller
{
    public function createOrder(Request $request)
    {
        $user = $request->checkTokenExistance->user;
        // create the validation rules ------------------------
        $rules = [
            'store_id'   => 'required',
            'order_type'   => 'required',
            // 'delivery_instructions'   => 'required',
            'payment_method'   => 'required',
            'total_quantity'   => 'required',
            'pay_amount'   => 'required',
            'payment_status'   => 'required',
            // 'shipping_name'   => 'required',
            // 'shipping_email'   => 'required',
            // 'shipping_phone'   => 'required',
            // 'shipping_address'   => 'required',
            // 'shipping_city'   => 'required',
            // 'shipping_state'   => 'required',
            // 'shipping_zipcode'   => 'required',
            // 'shipping_country'   => 'required',
            'service_charges'   => 'required',
            'sub_total'   => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()->first()])->setStatusCode(200);
        }

        $params = $request->post();

        $items = $params['items'];
        if(sizeof($items) < 1)
        {
            return response()->json(['status' => 0, 'message' => 'Please select products.'])->setStatusCode(200);
        }

        $order = new Order;
        $order->store_id = $params['store_id'];

        $getStoreDetails = User::where('id', $params['store_id'])->where('status', 1)->first();
        if(empty($getStoreDetails))
        {
            return response()->json(['status' => 0, 'message' => 'Store is unavailable.'])->setStatusCode(200);
        }

        $order->user_id = $user->id;
        $order->order_type = $params['order_type'];
        $order->delivery_instructions = $params['delivery_instructions'];
        $order->payment_method = $params['payment_method'];
        $order->total_quantity = $params['total_quantity'];
        $order->sub_total = $params['sub_total'];
        $order->pay_amount = $params['pay_amount'];
        $order->txnid = $params['txnid'];
        $order->charge_id = $params['charge_id'];
        $order->order_number = $user->id . time();
        $order->payment_status = $params['payment_status'];
        $order->shipping_name = $params['shipping_name'];
        $order->shipping_email = $params['shipping_email'];
        $order->shipping_phone = $params['shipping_phone'];
        $order->shipping_address = $params['shipping_address'];
        $order->shipping_city = $params['shipping_city'];
        $order->shipping_state = $params['shipping_state'];
        $order->shipping_zipcode = $params['shipping_zipcode'];
        $order->shipping_country = $params['shipping_country'];
        $order->order_note = $params['order_note'];

        $order->delivery_charges = $params['delivery_charges'];        
        $order->service_charges = $params['service_charges'];

        if($order->save())
        {
            foreach ($items as $keyI => $valueI) 
            {
                $order_details = new orderDetail;
                $order_details->order_id = $order->id;
                $order_details->user_id = $user->id;
                $order_details->store_id = $params['store_id'];
                $order_details->product_id = $valueI['product_id'];
                $order_details->quantity = $valueI['quantity'];
                $order_details->price = $valueI['price'];

                $order_details->save();
            }

            $subject = "Order Placed";
            $msg = "Hey, " . $user->name . ", We have got your order! Order No. ". $order->order_number;

            \Mail::send('email_templates.order_placed_email_template', ['msg' => $msg], function($message) use($user, $subject, $msg)
            {
                $message->to($user->email)->subject($subject);
            });

            // Store Owner
            $msg = $user->name . ", have placed an order! Order No. ". $order->order_number;

            $store_email = $order->store->email;
            \Mail::send('email_templates.order_placed_email_template', ['msg' => $msg], function($message) use($user, $store_email, $subject, $msg)
            {
                $message->to($store_email)->subject($subject);
            });

            $admin = User::where('user_role', 2)->first();

            if($admin)
            {
                \Mail::send('email_templates.order_placed_email_template', ['msg' => $msg], function($message) use($user, $admin, $subject, $msg)
                {
                    $message->to($admin->email)->subject($subject);
                });
            }
            return response()->json(['status' => 1, 'message' => 'Your order has created successfully.'])->setStatusCode(200);
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'Something went wrong.'])->setStatusCode(200);
        }
    }

    public function myOrders(Request $request)
    {
        $user = $request->checkTokenExistance->user;
        $params = $request->all();

        $getOrders = Order::with('orderDetails.product')->where(function($q) use ($user) {
                                        
                                        $q->where(['user_id' => $user->id]);
                                        
                                    });

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
                                        
                                        $q->where(['user_id' => $user->id]);
                                        
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

    public function paymentTransaction(Request $request)
    {
        $rules = [
            'store_id'   => 'required',
            'amount'   => 'required',
            'cardNumber'   => 'required',
            'cardExpiryMonth'   => 'required',
            'cardExpiryYear'   => 'required',
            'cardCVV'   => 'required',
            'customerName'   => 'required',
            'customerEmail'   => 'required',
            'customerAddress'   => 'required',
            'orderRef'   => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()->first()])->setStatusCode(200);
        }

        $params = $request->post();

        $getStoreDetails = User::where('id', $params['store_id'])->where('status', 1)->first();
        if(empty($getStoreDetails))
        {
            return response()->json(['status' => 0, 'message' => 'Store is unavailable.'])->setStatusCode(200);
        }
        else
        {
            if(empty($getStoreDetails->merchantID) || empty($getStoreDetails->merchantSecret))
            {
                return response()->json(['status' => 0, 'message' => 'Store payment details missing.'])->setStatusCode(200);
            }
        }

        $CSGW = new \P3\SDK\Gateway;

        $merchantID = $getStoreDetails->merchantID;
        $key = $getStoreDetails->merchantSecret; // Should be $merchantSecret from the file gateway.php -> change if needed
        $action = 'SALE';
        $type = 1;
        $countryCode = 826;
        $currencyCode = 826;

        // Pass our Credentials
        $CSGW::$merchantID = $merchantID;
        $CSGW::$merchantSecret = $key;

        // Request
        $req = array(
            'merchantID'=> $merchantID, // Should be $merchantID from the file gateway.php -> change if needed
            'action'=> $action,
            'type'=> $type,
            'countryCode'=> $countryCode,
            'currencyCode'=> $currencyCode,
            'amount'=> $params['amount'],
            'cardNumber'=> $params['cardNumber'],
            'cardExpiryMonth'=> $params['cardExpiryMonth'],
            'cardExpiryYear'=> $params['cardExpiryYear'],
            'cardCVV'=> $params['cardCVV'],
            'customerName'=> $params['customerName'],
            'customerEmail'=> $params['customerEmail'],
            // 'customerPhone'=> $params['customerPhone'], // '+44 (0) 123 45 67 890'
            'customerAddress'=> $params['customerAddress'],
            'customerPostCode'=> $params['customerPostCode'],
            'orderRef'=> $params['orderRef'],
            'transactionUnique'=> (isset($params['transactionUnique']) ? $params['transactionUnique'] : uniqid()),
            'threeDSMD'=> (isset($_REQUEST['MD']) ? $_REQUEST['MD'] : null),
            'threeDSPaRes'=> (isset($_REQUEST['PaRes']) ? $_REQUEST['PaRes'] : null),
            'threeDSPaReq'=> (isset($_REQUEST['PaReq']) ? $_REQUEST['PaReq'] : null)
        );

        $res = $CSGW->directRequest($req);
        
        if($res)
        {
            if($res['responseCode'] == 0)
            {
                return response()->json(['status' => 1, 'message' => 'Success'])->setStatusCode(200);
            }
            else if ($res['responseCode'] == 65802) {

                $paymentRequest = new paymentRequest;
                $paymentRequest->store_id = $params['store_id'];
                $paymentRequest->merchantID = $getStoreDetails->merchantID;
                $paymentRequest->merchantSecret = $getStoreDetails->merchantSecret;
                $paymentRequest->MD = $res['threeDSMD'];
                $paymentRequest->PaReq = $res['threeDSPaReq'];
                if($paymentRequest->save())
                {
                    $pageUrl =  url('api/3Ds-payment-request');
                    $formHTML = "<!DOCTYPE html>
                                    <html>
                                    <head>
                                        <meta charset='utf-8'>
                                        <meta name='viewport' content='width=device-width, initial-scale=1'>
                                        <title>Payment</title>
                                    </head>
                                    <body>
                                        <form style=\"display: flex; justify-content: center; align-items: center; height: 90vh;\" action=\"" . htmlentities($res['threeDSACSURL']) . "\" method=\"post\">
                                            <input type=\"hidden\" name=\"MD\" value=\"" . htmlentities($res['threeDSMD']) . "\">
                                            <input type=\"hidden\" name=\"PaReq\" value=\"" . htmlentities($res['threeDSPaReq']) . "\">
                                            <input type=\"hidden\" name=\"TermUrl\" value=\"" . htmlentities($pageUrl) . "\">
                                            <input type=\"submit\" value=\"Proceed To 3D Secure Authentication\" style=\"margin: 0 auto; display:flex; justify-content:center; background-color: #de6b12; color: white; border: none; border-radius: 7px; padding: 10px 15px; font-size: 16px;\">
                                        </form>
                                    </body>
                                </html>";

                    return response()->json(['status' => 2, 'message' => 'HTML Part', 'formHTML' => $formHTML, 'formAction' => $res['threeDSACSURL'], 'MD' => $res['threeDSMD'], 'PaReq' => $res['threeDSPaReq'], 'TermUrl' => $pageUrl])->setStatusCode(200);
                }
                else
                {
                    return response()->json(['status' => 0, 'message' => 'Request Failed'])->setStatusCode(200);
                }
            }
            else
            {
                return response()->json(['status' => 0, 'message' => $res['responseMessage']])->setStatusCode(200);
            }
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'Something went wrong.'])->setStatusCode(200);
        }
    }

    public function ThreeDsPaymentRequest(Request $request)
    {
        $rules = [
            'MD'   => 'required',
            'PaRes'   => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()->first()])->setStatusCode(200);
        }

        $params = $request->post();

        $checkPaymentRequest = paymentRequest::where(['MD' => $params['MD']])->first();
        if(empty($checkPaymentRequest))
        {
            return response()->json(['status' => 0, 'message' => 'Invalid Request'])->setStatusCode(200);
        }

        $getStoreDetails = User::where(['id' => $checkPaymentRequest->store_id, 'merchantID' => $checkPaymentRequest->merchantID, 'merchantSecret' => $checkPaymentRequest->merchantSecret])->where('status', 1)->first();
        if(empty($getStoreDetails))
        {
            return response()->json(['status' => 0, 'message' => 'Invalid Request'])->setStatusCode(200);
        }

        $CSGW = new \P3\SDK\Gateway;

        $merchantID = $getStoreDetails->merchantID;
        $key = $getStoreDetails->merchantSecret; // Should be $merchantSecret from the file gateway.php -> change if needed
        $action = 'SALE';
        $type = 1;
        $countryCode = 826;
        $currencyCode = 826;

        // Pass our Credentials
        $CSGW::$merchantID = $merchantID;
        $CSGW::$merchantSecret = $key;

        // Request
        $req = array(
            'merchantID'=> $merchantID, // Should be $merchantID from the file gateway.php -> change if needed
            'action'=> $action,
            'type'=> $type,
            'countryCode'=> $countryCode,
            'currencyCode'=> $currencyCode,
            'threeDSMD'=> (isset($_REQUEST['MD']) ? $_REQUEST['MD'] : null),
            'threeDSPaRes'=> (isset($_REQUEST['PaRes']) ? $_REQUEST['PaRes'] : null),
            'threeDSPaReq'=> (isset($_REQUEST['PaReq']) ? $_REQUEST['PaReq'] : null)
        );

        $res = $CSGW->directRequest($req);
        
        if($res)
        {
            if($res['responseCode'] == 0)
            {
                $checkPaymentRequest->PaRes = $_REQUEST['PaRes'];
                $checkPaymentRequest->responseMessage = $res['responseMessage'];

                if($checkPaymentRequest->save())
                {
                    return redirect()->route('order-status', ['response' => 'success', 'message' => $res['responseMessage']]);
                }
                else
                {
                    return redirect()->route('order-status', ['response' => 'failure', 'message' => $res['responseMessage']]);
                }
            }
            else
            {
                return redirect()->route('order-status', ['response' => 'failure', 'message' => $res['responseMessage']]);
                return response()->json(['status' => 0, 'message' => $res['responseMessage']])->setStatusCode(200);
            }
        }
        else
        {
            return redirect()->route('order-status', ['response' => 'failure']);
            return response()->json(['status' => 0, 'message' => 'Something went wrong.'])->setStatusCode(200);
        }
    }

    public function orderStatus(Request $request)
    {
        $params = $request->all();
        if (isset($params['response']))
        {  
            if($params['response'] == 'success')
            {
                return response()->json(['status' => 1, 'message' => 'Success'])->setStatusCode(200);
            }
            else
            {
                $response = ['status' => 0, 'message' => 'Something went wrong.'];
                if(isset($params['message']))
                {
                    $response['message'] = $params['message'];
                }

                return response()->json($response)->setStatusCode(200);
            }
        }
        else
        {
            $response = ['status' => 0, 'message' => 'Something went wrong.'];
            if(isset($params['message']))
            {
                $response['message'] = $params['message'];
            }

            return response()->json($response)->setStatusCode(200);
        }
    }

    public function paymentTransaction_13112021(Request $request)
    {
        // $CSGW = new P3\SDK\Gateway;

        $merchantID = 155831;
        $merchantSecret = 'fs8723r23g';
        $action = 'SALE';
        $type = 1;
        $countryCode = 826;
        $currencyCode = 826;

        $params = $request->all();

        Gateway::$merchantID = $merchantID;
        Gateway::$merchantSecret = $merchantSecret;

        // Gateway URL
        $url = 'https://gw1.tponlinepayments.com/direct/';

        // Request
        $req = array(
            'merchantID'=> $merchantID,
            'action'=> $action,
            'type'=> $type,
            'countryCode'=> $countryCode,
            'currencyCode'=> $currencyCode,
            'amount'=> $params['amount'],
            'cardNumber'=> $params['cardNumber'],
            'cardExpiryMonth'=> $params['cardExpiryMonth'],
            'cardExpiryYear'=> $params['cardExpiryYear'],
            'cardCVV'=> $params['cardCVV'],
            'customerName'=> $params['customerName'],
            'customerEmail'=> $params['customerEmail'],
            'customerPhone'=> $params['customerPhone'], // '+44 (0) 123 45 67 890'
            'customerAddress'=> $params['customerAddress'],
            'customerPostCode'=> $params['customerPostCode'],
            'orderRef'=> $params['orderRef'],
            'transactionUnique'=> (isset($params['transactionUnique']) ? $params['transactionUnique'] : uniqid()),
            // 'threeDSMD'=> (isset($_REQUEST['MD']) ? $_REQUEST['MD'] : null),
            // 'threeDSPaRes'=> (isset($_REQUEST['PaRes']) ? $_REQUEST['PaRes'] : null),
            // 'threeDSPaReq'=> (isset($_REQUEST['PaReq']) ? $_REQUEST['PaReq'] : null)
        );

        // Create the signature using the function called below.
        $req['signature'] = $this->createSignature($req, $merchantSecret);

        // Initiate and set curl options to post to the gateway
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($req));
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Send the request and parse the response
        parse_str(curl_exec($ch), $res);
        // $result = curl_exec($ch);

        curl_close($ch);

        // Extract the return signature as this isn't hashed
        $signature = null;
        if (isset($res['signature'])) {
            $signature = $res['signature'];
            unset($res['signature']);
        }

        // Check the return signature
        if (!$signature || $signature !== createSignature($res, $key)) {
            // You should exit gracefully
            return response()->json(['status' => 0, 'message' => 'Sorry, the signature check failed'])->setStatusCode(200);
        }

        if($res)
        {
            if($res['responseCode'] === "0") {
                return response()->json(['status' => 1, 'message' => 'Thank you for your payment.<'])->setStatusCode(200);
            }
            else if ($res['responseCode'] == 65802) {
                return response()->json(['status' => 0, 'message' => 'Please disable 3D Secure Authentication'])->setStatusCode(200);
            }
            else
            {
                return response()->json(['status' => 0, 'message' => "Failed to take payment: " . $res['responseMessage']])->setStatusCode(200);
            }
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'Something went wrong.'])->setStatusCode(200);
        }
    }

    public function old_paymentTransaction(Request $request)
    {
        $merchantID = 126549;
        // $merchantID = 101599;
        $merchantSecret = 'pvK4ERrE37BqvuPM';
        $action = 'SALE';
        $type = 1;
        $countryCode = 826;
        $currencyCode = 826;


        $params = $request->all();

        Gateway::$merchantID = $merchantID;
        Gateway::$merchantSecret = $merchantSecret;

        // Gateway URL
        $url = 'https://gateway.retailmerchantservices.co.uk/direct/';
        
        // Request
        $req = array(
            'merchantID'=> $merchantID,
            'action'=> $action,
            'type'=> $type,
            'countryCode'=> $countryCode,
            'currencyCode'=> $currencyCode,
            'amount'=> $params['amount'],
            'cardNumber'=> $params['cardNumber'],
            'cardExpiryMonth'=> $params['cardExpiryMonth'],
            'cardExpiryYear'=> $params['cardExpiryYear'],
            'cardCVV'=> $params['cardCVV'],
            'customerName'=> $params['customerName'],
            'customerEmail'=> $params['customerEmail'],
            'customerPhone'=> $params['customerPhone'], // '+44 (0) 123 45 67 890'
            'customerAddress'=> $params['customerAddress'],
            'customerPostCode'=> $params['customerPostCode'],
            'orderRef'=> $params['orderRef'],
            'transactionUnique'=> (isset($params['transactionUnique']) ? $params['transactionUnique'] : uniqid()),
            // 'threeDSMD'=> (isset($_REQUEST['MD']) ? $_REQUEST['MD'] : null),
            // 'threeDSPaRes'=> (isset($_REQUEST['PaRes']) ? $_REQUEST['PaRes'] : null),
            // 'threeDSPaReq'=> (isset($_REQUEST['PaReq']) ? $_REQUEST['PaReq'] : null)
        );

        // Create the signature using the function called below.
        $req['signature'] = $this->createSignature($req, $merchantSecret);

        // print_r($req['signature']); die();

        // Initiate and set curl options to post to the gateway
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($req));
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Send the request and parse the response
        parse_str(curl_exec($ch), $res);
        // $result = curl_exec($ch);

        // Close the connection to the gateway
        // $result = curl_close($ch);
        curl_close($ch);
        // print_r($res); die();

        if($res)
        {
            if($res['responseStatus'] == 0)
            {
                return response()->json(['status' => 1, 'message' => 'Success'])->setStatusCode(200);
            }
            else
            {
                return response()->json(['status' => 0, 'message' => $res['responseMessage']])->setStatusCode(200);
            }
        }
        else
        {
            return response()->json(['status' => 0, 'message' => 'Something went wrong.'])->setStatusCode(200);
        }
    }

    // Function to create a message signature
    function createSignature(array$data, $key) {
        // Sort by field name
        ksort($data);

        // Create the URL encoded signaturestring
        $ret = http_build_query($data, '', '&');

        // Normalise all line endings (CRNL|NLCR|NL|CR) to just NL (%0A)
        $ret = str_replace(array('%0D%0A', '%0A%0D', '%0D'), '%0A', $ret);

        // Hash the signature string and the key togetherreturn

        return hash('SHA512', $ret. $key);
    }
}