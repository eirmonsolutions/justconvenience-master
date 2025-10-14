@extends('layouts.admin')

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 align-self-center">
            <a href="{{ route('customers') }}"><i class="fas fa-angle-left"></i> Back to Customers</a>
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
        <div class="col-10 offset-1">
			@if(Session::has('message'))
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
					<strong>{{ Session::get('message') }}</strong> 
				</div>
			@endif

            <div class="card">
                <div class="card-body">
                    <div class="">
                        <h4 class="card-title text-center w-100">Customers Detail</h4>
                        @if(Auth::user()->user_role == 2)
                            <!-- <div class="float-right customers-btns">
                                <a href="{{ route('edit-customer-details', ['customer_id' => $customer->id])  }}" class="btn btn-primary float-right btn-view-all" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="far fa-edit"></i></a>
                            </div> -->
                        @endif
                        <div class="row">
                            <div class="col-6 customer-row">
                                <label>Name</label>
                                {{ $customer->name }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Email</label>
                                {{ $customer->email }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Phone Number</label>
                                {{ $customer->phone_number }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>City</label>
                                {{ $customer->city }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>State</label>
                                {{ $customer->state }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Zipcode</label>
                                {{ $customer->zipcode }}
                            </div>
                            <!-- <div class="col-6 customer-row">
                                <label>Status</label>
                                {{ ($customer->status) ? 'Active' : 'Inactive' }}
                            </div> -->
                            <div class="col-6 customer-row">
                                <label>Registration Date</label>
                                {{ date('Y-m-d', strtotime($customer->created_at)) }}
                            </div>
                        </div>
                        <div class="clearfix"></div>
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