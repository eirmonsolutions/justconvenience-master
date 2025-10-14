@extends('layouts.admin')

@section('content')
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 align-self-center">
                <a href="{{ route('contests') }}"><i class="fas fa-angle-left"></i> Back to Contests</a>
            </div>

        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 form_v">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Contest Detail - {{ $contest->title }}</h4>
                        <div class="row">
                            <div class="col-6 customer-row">
                                <label>Customer Count:</label>
                                {{ $contest->customerCount }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Today's Customer Count:</label>
                               {{ $contest->todayCustomerCount }}
                            </div>

                            <div class="col-6 customer-row">
                                <label>Total Receipts:</label>
                                {{ $contest->totalReciepts }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Today's Receipts:</label>
                               {{ $contest->todayTotalReciepts }}
                            </div>

                            <div class="col-6 customer-row">
                                <label>Total invoice value:</label>
                                {{ $contest->invoiceAmountSum }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Today's Invoice value:</label>
                               {{ $contest->todayInvoiceAmountSum }}
                            </div>
                            
                            <div class="col-6 customer-row">
                                <label>Highest Invoice Value:</label>
                                {{ $contest->invoiceAmountMax }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Today's Highest Invoice Value:</label>
                                {{ $contest->todayInvoiceAmountMax }}
                            </div>
                            
                            <div class="col-6 customer-row">
                                <label>Avg. customer count per day:</label>
                                {{ $contest->customerCountAvg }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Avg. receipts count per day:</label>
                                {{ $contest->recieptCountAvg }}
                            </div>
                            
                            <div class="col-6 customer-row">
                                <label>Avg. number of receipts per week:</label>
                                {{ $contest->weeklyRecieptCountAvg }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Weekly's Receipts:</label>
                                {{ $contest->lastWeekTotalReciepts }}
                            </div>
                            
                            <div class="col-6 customer-row">
                                <label>Avg. invoice value:</label>
                                {{ $contest->invoiceAmountAvg }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Total Tickets:</label>
                                {{ $contest->invoice_details_count }}
                            </div>
                            
                            <div class="col-6 customer-row">
                                <label>Today's Tickets:</label>
                                {{ $contest->today_invoice_details_count }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection