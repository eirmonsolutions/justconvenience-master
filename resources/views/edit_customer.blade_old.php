@extends('layouts.admin')

@section('content')

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 align-self-center">
            <a href="{{ route('customer-details', ['id' => $customer->id])  }}"><i class="fas fa-angle-left"></i> Back to Customers Details</a>
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
                    <h4 class="card-title">Customer Details</h4>
                    <form action="{{ route('update-customer-details') }}" method="POST" id="Login" enctype="multipart/form-data">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 floating-label-wrap">
                                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                                    <input type="text" name="name" placeholder="NOMBRE*" class="floating-label-field floating-label-field--s1 name" id="inputName" value="{{ $customer->name }}" required>
                                    <label for="" class="floating-label">NOMBRE*</label>
                                </div>
                                <div class="col-lg-12 col-md-12 floating-label-wrap">
                                    <input type="text" name="last_name" placeholder="APELLIDO*" class="floating-label-field floating-label-field--s1 last_name" value="{{ $customer->last_name }}" required>
                                    <label for="" class="floating-label">APELLIDO*</label>
                                </div>
                                <div class="col-lg-12 col-md-12 floating-label-wrap">
                                    <input type="text" name="indentification_card" placeholder="CEDULA / PASAPORTE / RUC*" class="floating-label-field floating-label-field--s1 indentification_card" value="{{ $customer->indentification_card }}" required pattern="[a-zA-Z0-9 ]+">
                                    <label for="" class="floating-label">CEDULA / PASAPORTE / RUC*</label>
                                </div>
                                <div class="col-lg-12 col-md-12 floating-label-wrap">
                                    <input type="text" name="phone_number" placeholder="CELULAR*" class="floating-label-field floating-label-field--s1 phone_number" value="{{ $customer->phone_number }}" required pattern="[0-9]{10}" onkeypress="limitKeypress(event, this.value, 10)">
                                    <label for="" class="floating-label">CELULAR*</label>
                                </div>
                                <div class="col-lg-12 col-md-12 floating-label-wrap">
                                    <input type="email" name="email" placeholder="CORREO*" class="floating-label-field floating-label-field--s1 email" value="{{ $customer->email }}" required>
                                <label for="" class="floating-label">CORREO*</label>
                                </div>
                                <div class="col-lg-12 col-md-12 floating-label-wrap">
                                    <input type="text" name="direction" placeholder="DIRECCIÓN*" class="floating-label-field floating-label-field--s1 direction" value="{{ $customer->direction }}" required>
                                <label for="" class="floating-label">DIRECCIÓN*</label>
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