@extends('layouts.admin')

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 align-self-center">
            <a href="{{ route('orders') }}"><i class="fas fa-angle-left"></i> Back to Orders</a>
            <!-- <a class="btn btn-primary float-right btn-view-all" href="javascript:void(0);" role="button">Orders</a> -->
        </div>

    </div>
</div>
<!-- ============================================================== -->
<!-- End Bread crumb and right sidebar toggle -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Container fluid  -->
<!-- ============================================================== -->
<div class="container-fluid">
    <!-- *************************************************************** -->

    <!-- *************************************************************** -->
    <!-- *************************************************************** -->

    <!-- Start Top Leader Table -->
    <!-- *************************************************************** -->
    <div class="row">
        <div class="col-12">
			@if(Session::has('message'))
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
					<strong>{{ Session::get('message') }}</strong> 
				</div>
			@endif

            <div class="card">
                <div class="card-body">
                    <div class="">
                        <h4 class="card-title text-center w-100">Customer & Order Details</h4>
                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="col-6 customer-row">
                                <label>Customer Name: </label>
                                {{ $order->user->name }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Customer Tel. : </label>
                                {{ $order->user->phone_number }}
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <hr>
                        <div class="row">
                            <div class="col-6 customer-row">
                                <label>Store Name: </label>
                                {{ $order->store->name }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Order Type: </label>
                                @if($order->order_type == 1)
                                    Delivery
                                @else
                                    Pickup
                                @endif
                            </div>
                            <div class="col-6 customer-row">
                                <label>Total Quantity: </label>
                                {{ $order->total_quantity }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Order Amount: </label>
                                {{ $order->pay_amount }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Service Charge: </label>
                                {{ $order->service_charges }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Order Status: </label>
                                @if($order->status == 1)
                                    Pending
                                @elseif($order->status == 2)
                                    Ready
                                @elseif($order->status == 3)
                                    Completed
                                @elseif($order->status == 4)
                                    Declined
                                @endif
                            </div>
                            <div class="col-6 customer-row">
                                <label>Order Delivery Instructions: </label>
                                {{ $order->delivery_instructions }}
                            </div>
                            @if($order->order_note)
                                <div class="col-6 customer-row">
                                    <label>Order Note: </label>
                                    {{ $order->order_note }}
                                </div>
                            @endif
                        </div>
                        <div class="clearfix"></div>
                        <hr>
                        <div class="row">
                            <div class="col-6 customer-row">
                                <label>Shipping Name: </label>
                                {{ $order->shipping_name }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Shipping Tel. : </label>
                                {{ $order->shipping_phone }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Shipping Address: </label>
                                {{ $order->shipping_address }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Shipping City:</label>
                                {{ $order->shipping_city }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Shipping State:</label>
                                {{ $order->shipping_state }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Shipping Zipcode:</label>
                                {{ $order->shipping_zipcode }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <h4 class="card-title">Order Item Details</h4>
                        <div id="button" class="datatable-btns"></div>
                        <div class="col-3 float-right search-csv">
                            <div class="customize-input">
                                <input class="form-control custom-shadow custom-radius border-0 bg-white myInputTextField" type="text" placeholder="Search" aria-label="Search">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search form-control-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            </div>
                        </div>                          
                    </div>
                    <div class="table-responsive">
                        <table id="products"  class="table no-wrap v-middle mb-0 dashboard-table responsive nowrap">
                            <thead>
                                <tr class="border-0">
                                    <th class="border-0 font-14 font-weight-medium text-muted">Sr.</th>
                                    <th class="border-0 font-14 font-weight-medium text-muted">Product Name</th>
                                    <th class="border-0 font-14 font-weight-medium text-muted">Product Quantity</th>
                                    <th class="border-0 font-14 font-weight-medium text-muted">Product Unit Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderDetails as $keyOD => $order_details)
                                    <tr>
                                        <td class="border-top-0 text-muted px-2 py-4 font-14">{{ $keyOD+1 }}</td>
                                        <td class="border-top-0 text-muted px-2 py-4 font-14">{{ $order_details->product->name }}</td>
                                        <td class="border-top-0 text-muted px-2 py-4 font-14">{{ $order_details->quantity }}</td>
                                        <td class="border-top-0 text-muted px-2 py-4 font-14">{{ $order_details->price }}</td>
                                    </tr>
                                @endforeach 
                            </tbody>
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- *************************************************************** -->
    <!-- End Top Leader Table -->
    <!-- *************************************************************** -->
</div>
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
<!-- ============================================================== -->
@endsection
@push('scripts')
    <script>

	</script>
@endpush