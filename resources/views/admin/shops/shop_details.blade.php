@extends('layouts.admin')

@section('content')
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 align-self-center">
                <a href="{{ route('shops') }}"><i class="fas fa-angle-left"></i> Back to Shops</a>
            </div>

        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 form_v">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Shop Detail - {{ $shop->shop_name }}</h4>
                        <div class="row">
                            <div class="col-6 customer-row">
                                <label>Customer Count:</label>
                                {{ $shop->customerCount }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Today's Customer Count:</label>
                               {{ $shop->todayCustomerCount }}
                            </div>

                            <div class="col-6 customer-row">
                                <label>Total Receipts:</label>
                                {{ $shop->totalReciepts }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Today's Receipts:</label>
                               {{ $shop->todayTotalReciepts }}
                            </div>

                            <div class="col-6 customer-row">
                                <label>Total invoice value:</label>
                                {{ $shop->invoiceAmountSum }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Today's Invoice value:</label>
                               {{ $shop->todayInvoiceAmountSum }}
                            </div>
                            
                            <div class="col-6 customer-row">
                                <label>Highest Invoice Value:</label>
                                {{ $shop->invoiceAmountMax }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Today's Highest Invoice Value:</label>
                                {{ $shop->todayInvoiceAmountMax }}
                            </div>
                            
                            <div class="col-6 customer-row">
                                <label>Avg. customer count per day:</label>
                                {{ $shop->customerCountAvg }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Avg. receipts count per day:</label>
                                {{ $shop->recieptCountAvg }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Avg. number of receipts per week:</label>
                                {{ $shop->weeklyRecieptCountAvg }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Weekly's Receipts:</label>
                                {{ $shop->lastWeekTotalReciepts }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Avg. invoice value:</label>
                                {{ $shop->invoiceAmountAvg }}
                            </div>
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection