<!DOCTYPE html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">  
        <title>Print Order | Kalyan's Hunger Breakout</title>  
        <meta name="language" content="English">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&family=Source+Sans+Pro:wght@300;400;600;700;900&display=swap" rel="stylesheet">     
        <style>
            @media print { 
                @page { 
                    margin-top: 0; 
                    margin-bottom: 0; 
                    margin-left: 2px;
                    margin-right: 2px;
                } 
                body {
                    padding-top: 5px; 
                    padding-bottom: 5px ; 
                    width: 60mm ;
                    font-size: 8px !importent;
                    line-height: 1;
                    overflow-y:hidden;
                } 


                table{
                    display:table!important
                }
                #non-printable { display: none; }                
            } 
            .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
                padding: 0px;

            }

            th, td {

                border-bottom: 1px solid #000000;
            }                 


            .h4, .h5, .h6, h4, h5, h6 {
                margin-top: 0px;
                margin-bottom: 0px;
            }
            .center {
                margin-left: auto;
                margin-right: auto;
            }
        </style>
    </head>
    <body>
        <div class="container" style="width:100%;">

            <center>
                <button style="margin: 5px 0px;" onclick="window.print()" id="non-printable">Print</button>
            </center>
            <center>
                <a href="javascript:void(0);"> 
                    <img src="{{ asset('public/images/logo_black.png') }}" alt="">
                </a>
                <p style="width:100%; font-family: 'Poppins', sans-serif;  color:#222222; ">{{ $order_details->store ? $order_details->store->store_name . ', ' . $order_details->store->address }}<br>{{ $order_details->store->zipcode : '' }}<br>
                Phone: {{ $order_details->store ? $order_details->store->phone_number : '' }}</p>
            </center>

            <table class="center" style="width:100%; font-family: 'Poppins', sans-serif;       color:#222222;">
                <tbody>
                    <tr>  
                        <td><b>Customer Details</b></td>  
                    </tr>  
                    <tr>  
                        <td>  
                            <p>{{ $order_details->shipping_name }}<br>  
                                {{ $order_details->shipping_phone }}<br>
                                {{ $order_details->shipping_address }}<br>
                                {{ $order_details->shipping_zipcode }}
                                @if($order_details->delivery_instructions)
                                    <br>{{ $order_details->delivery_instructions}}
                                @endif
                            </p>
                        </td>
                    </tr>  
                    <tr>
                        <td class="text-sm"><b>Order No. {{ $order_details->order_number }}</b></td>  
                    </tr>
                </tbody>
            </table>


            <table class="center"  style="width:100%; font-family: 'Poppins', sans-serif;       color:#222222; margin-bottom: 0px;">  
                <tbody>
                    <tr>
                        <th width="60%">Item Name</th>
                        <th width="10%" align="left">Qty</th>
                        <th width="30%" align="left">Amt</th>
                    </tr>  

                    @foreach ($order_details->orderDetails as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>  
                            <td>{{ number_format($item['price'], 2, '.', '') }}</td>  
                        </tr>
                    @endforeach

                    <tr>
                        <td colspan="2" align="right">Service Charge &#163; </td>  
                        <td><label>{{ number_format($order_details->service_charges, 2, '.', '') }}</label></td>  
                    </tr>

                    @if($order_details->order_type == 1)
                        <tr>
                            <td colspan="2" align="right">Delivery Charge &#163; </td>  
                            <td><label>{{ number_format($order_details->delivery_charge, 2, '.', '') }}</label></td>  
                        </tr>
                    @endif
                    @php
                        $subTotal = $order_details->pay_amount - $order_details->delivery_charge - $order_details->service_charges
                    @endphp
                    <tr>
                        <td colspan="2" align="right">Total Amount &#163; </td>  
                        <td><label>{{ number_format($order_details->total_amount, 2, '.', '') }}</label></td>  
                    </tr>

                    <tr>
                        <td colspan="2" align="right">Total Discount &#163; </td>  
                        <td><label>{{ number_format($order_details->total_discount, 2, '.', '') }}</label></td>  
                    </tr>
                    
                    <tr>
                        <td colspan="2" align="right"><label>Amount Paid &#163;</label></td>
                        <td>{{ number_format($order_details->pay_amount, 2, '.', '') }}</td>
                    </tr>
                </tbody>
            </table>

            <center><small>Powered By {{ config('app.name', 'Just Convenience') }}</small></center>
        </div>
    </body>
</html>