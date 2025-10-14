<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title></title>
<meta name="language" content="English">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&family=Source+Sans+Pro:wght@300;400;600;700;900&display=swap" rel="stylesheet">
<style type="text/css">
    @media print { 
        @page { 
            margin-top: 0; 
            margin-bottom: 0; 
            /*margin-left: 5px;*/
            margin-left: 3px;
        } 
        body { 
            /*padding-top: 72px; 
            padding-bottom: 72px ;*/
            padding-top: 5px; 
            padding-bottom: 5px ; 
            width: 60mm ;
            font-size: 8px !importent;
             line-height: 1;
            /*margin-left: 50px;*/
             overflow-y:hidden;
        } 
     
                      
        table{
            display:table!important
        }
        #non-printable { display: none; }
    }
</style>
</head>
<body>
<table align="center" width="220">
    <tr>
        <td style="text-align:center;">
            <button style="margin: 5px 0px;" onclick="window.print()" id="non-printable">Print</button>
        </td>
    </tr>
    <tr>
        <td style="text-align:center;">
            <img src="{{ asset('public/images/logo1.png') }}" alt="">
        </td>
    </tr>
    <tr>
        <td style="width:100%; font-family: 'Poppins', sans-serif; text-align:center; padding:10px 0 20px;  font-size:16px; color:#222222; font-weight:400; line-height: 12px;">
            {{ $order_details->store ? $order_details->store->name . ', ' . $order_details->store->address . ', ' . $order_details->store->zipcode : '' }}<br><br>
        <strong>P. {{ $order_details->store ? $order_details->store->phone_number : '' }}</strong>
        </td>
    </tr>
    <tr>
        <td style="font-family: 'Poppins', sans-serif; width:100%;
    padding:15px 0 15px 10px; border-top:1px solid #222222;
    border-bottom:1px solid #222222; font-size:18px; color:#000000;
    font-weight:700; margin:20px 0 0 0;">
            Customer Details
        </td>
    </tr>
    <tr>
        <td style="font-family: 'Poppins', sans-serif; font-size:14px; color:#222222; font-weight:400;  padding:10px 0 0 10px;">
            <strong>Customer Name:</strong> {{ $order_details->shipping_name }}
        </td>
    </tr>
    <tr>
        <td style="font-family: 'Poppins', sans-serif; font-size:14px; color:#222222; font-weight:400;  padding:10px 0 0 10px;">
            <strong>Customer Phone:</strong> {{ $order_details->shipping_phone }}
        </td>
    </tr>
    <tr>
        <td style="font-family: 'Poppins', sans-serif; font-size:14px; color:#222222; font-weight:400;  padding:10px 0 0 10px;">
            <strong>Customer Address:</strong> {{ $order_details->shipping_address . ', ' . $order_details->shipping_zipcode }}
        </td>
    </tr>
    @if($order_details->delivery_instructions)
        <tr>
            <td style="font-family: 'Poppins', sans-serif; font-size:14px; color:#222222; font-weight:400;  padding:10px 0 0 10px;">
                <strong>Special Instructions:</strong> {{ $order_details->delivery_instructions}}
            </td>
        </tr>
    @endif
    <tr height="20px">
        <td>
            
        </td>
    </tr>
    <tr>
        <td style="font-family: 'Poppins', sans-serif; width:100%;
    padding:15px 0 15px 10px; border-top:1px solid #222222;
    border-bottom:1px solid #222222; font-size:16px; color:#000000;
    font-weight:700; margin:20px 0 0 0;">
            Order Details Bill No. {{ $order_details->order_number }}
        </td>
    </tr>
    
    
    <tr>
        <td>
            <table>
                <tr>
                    <td style="font-family: 'Poppins', sans-serif; font-size:14px; width:200px; color:#222222; font-weight:400; padding:10px 0 0 10px;">
                        <strong>Item Name</strong>
                    </td>
                    <td style="font-family: 'Poppins', sans-serif; font-size:14px; width:35px; color:#222222; font-weight:400;  padding:10px 0 0 10px;">
                        <strong>Qty</strong>
                    </td>
                    <td style="font-family: 'Poppins', sans-serif; font-size:14px; width:35px; color:#222222; font-weight:400;  padding:10px 0 0 10px;">
                        <strong>Rate</strong> 
                    </td>
                </tr>
                @foreach ($order_details->orderDetails as $item)
                    <tr>
                        <td style="font-family: 'Poppins', sans-serif; font-size:14px; width:200px; color:#222222; font-weight:400; padding:10px 0 0 10px;">
                            {{ $item->product->name }}
                        </td>
                        <td style="font-family: 'Poppins', sans-serif; font-size:14px; width:35px; color:#222222; font-weight:400;  padding:10px 0 0 10px;">
                            {{ $item->quantity }}
                        </td>
                        <td style="font-family: 'Poppins', sans-serif; font-size:14px; width:35px; color:#222222; font-weight:400;  padding:10px 0 0 10px;">
                            <span>&#163;</span>{{ number_format($item['price'], 2, '.', '') }}
                        </td>
                    </tr>
                @endforeach
            </table>
        </td>
    </tr>
    
    <tr>
                <td style="font-family: 'Poppins', sans-serif; width:100%;
            padding:15px 0 15px 10px; border-top:1px solid #222222;
            border-bottom:1px solid #222222; font-size:18px; color:#000000; text-align:right; font-weight:400; margin:20px 0 0 0;">
                    <strong>Total:</strong> <span>&#163;</span>{{ number_format($order_details->pay_amount, 2, '.', '') }}
                </td>
    </tr>
</table>
</body>
</html>
