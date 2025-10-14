@extends('layouts.admin')

@section('content')

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 align-self-center">
            <a href="{{ route('customer-details', ['id' => $invoice->user_id])  }}"><i class="fas fa-angle-left"></i> Back to Customers Details</a>
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
        <div class="col-12 form_v">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Invoice Details</h4>
                    <form action="{{ route('update-invoice') }}" method="POST" id="Login" enctype="multipart/form-data">
                        <div class="form-body">
                            <div class="row">
                                @if($invoice->attchment)
                                    <div class="col-lg-12 col-md-12 col-xs-12 text-center pop-content">
                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#imgModel" data-backdrop="static" data-keyboard="false">
                                            @if (strtolower(pathinfo($invoice->attchment, PATHINFO_EXTENSION)) == 'pdf')
                                            <iframe src="{{ Storage::disk('s3')->url($invoice->attchment)}}"  height="150" width="150" class="iframe-popup"></iframe>
                                            @else
                                            <img src="{{ Storage::disk('s3')->url($invoice->attchment)}}" class="width120">
                                            @endif
                                        </a>

                                        <div class="modal fade invoice-popup" id="imgModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Invoice Images</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                                            <div class="carousel-inner">
                                                                <div class="carousel-item active">
                                                                    @if (strtolower(pathinfo($invoice->attchment, PATHINFO_EXTENSION)) == 'pdf')
                                                                        <embed src="{{ Storage::disk('s3')->url($invoice->attchment)}}
" type="application/pdf"  height="700px" width="100%">
                                                                    @else
                                                                    <img src="{{ Storage::disk('s3')->url($invoice->attchment)}}
">
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-lg-12 col-md-12 floating-label-wrap">
                                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                    <input type="hidden" name="id" value="{{ $invoice->id }}">
                                    <input type="hidden" name="user_id" value="{{ $invoice->user_id }}">
                                    <!-- <input name="local" type="text" class="floating-label-field floating-label-field--s1 local" id="inputLocal" placeholder="LOCAL*" value="{{ $invoice->local }}" required>
                                    <label for="" class="floating-label">LOCAL*</label> -->
                                    <label for="" class="floating-label local-txt comercial-label">LOCAL COMERCIAL*</label>
                                    <select class="form-control select2 local select_val" name="shop_id" required>
                                        @foreach ($shops as $key => $shop)
                                            <option value="{{ $shop->id }}" {{ ($invoice->shop_id == $shop->id) ? "selected" : '' }}>{{ $shop->shop_name }}{{ ', ' . $shop->contract_number }}{{ ', ' . $shop->shopping_center_id }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-12 col-md-12 floating-label-wrap">
                                    <input name="invoice_amount" type="number" step="any" class="floating-label-field floating-label-field--s1 amount" id="inputInvoiceAmount" placeholder="MONTO FACTURA*:" value="{{ $invoice->invoice_amount }}" required>
                                    <label for="" class="floating-label">MONTO FACTURA*:</label>
                                </div>
                                <div class="col-lg-12 col-md-12 floating-label-wrap">
                                    <input name="num_bill" type="number" class="floating-label-field floating-label-field--s1 num_bill" id="inputNumBill" placeholder="NO. FACTURA*" value="{{ $invoice->num_bill }}" required>
                                    <label for="" class="floating-label">NO. FACTURA*</label>
                                </div>
                                <div class="col-lg-12 col-md-12 floating-label-wrap">
                                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                    <input name="invoice_date" type="text" class="floating-label-field floating-label-field--s1 date" id="inputTitle" placeholder="FECHA(DD / MM / AAAA)*" value="{{ date('d M Y', strtotime($invoice->invoice_date)) }}" required autocomplete="off">
                                    <label for="" class="floating-label">FECHA(DD / MM / AAAA)*</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <div class="text-right MT30">
                                <button type="submit" class="btn btn-info">Update</button>
                            </div>
                        </div>
                                
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- *************************************************************** -->
    <!-- End Top Leader Table -->
    <!-- *************************************************************** -->
<!-- ============================================================== -->
<!-- End Container fluid  -->
<!-- ============================================================== -->
<!-- ============================================================== -->
@endsection