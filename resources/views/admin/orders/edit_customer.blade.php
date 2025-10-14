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
        <div class="col-10 offset-1 form_v">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Customer Details</h4>
                    <form action="{{ route('update-customer-details') }}" method="POST" id="Login" enctype="multipart/form-data">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 floating-label-wrap">
                                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                    <input type="hidden" name="user_id" value="{{ $customer->id }}">
                                    <input type="text" name="name" placeholder="Name*" class="floating-label-field floating-label-field--s1 name" id="inputName" value="{{ $customer->name }}" required>
                                    <label for="" class="floating-label">Name*</label>
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