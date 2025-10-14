@extends('layouts.admin')

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 align-self-center">
            <a href="{{ route('customers', ['contest_id' => $data['current_active_contest_id']])  }}"><i class="fas fa-angle-left"></i> Back to Customers Leads</a>
            <a class="btn btn-primary float-right btn-view-all" href="{{ route('customer-tickets', ['customer_id' => $customer->id])  }}" role="button">Tickets</a>
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
                        <h4 class="card-title">Customers Detail </h4>
                        @if(Auth::user()->user_role == 2)
                            <div class="float-right customers-btns">
                                <a href="{{ route('edit-customer-details', ['customer_id' => $customer->id])  }}" class="btn btn-primary float-right btn-view-all" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="far fa-edit"></i></a>
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-6 customer-row">
                                <label>Name</label>
                                {{ $customer->name }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Last Name</label>
                                {{ $customer->last_name }}
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
                                <label>Direction</label>
                                {{ $customer->direction }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Store</label>
                                {{ implode(', ', $customer->invoices->pluck('local')->toArray()) }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Total Value of receipts</label>
                                {{ $customer->invoices->sum('invoice_amount') }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Number of receipts</label>
                                {{ $customer->invoices->count() }}
                            </div>
                            <div class="col-6 customer-row">
                                <label>Id Number/ Ruc/ Passport </label>
                                {{ $customer->indentification_card }}
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="row">
							<div class="col-12 customer-row">
								<form method="post" action="{{route('customers-invoice-details')}}" id="from1" name="from1">
									@csrf
								
									
										
										
										<div style="margin-bottom: 30px;">
											<h4 class="card-title">Invoice Detail </h4>
												<div class="float-right customers-btns" id="approveds" style="display:none">
													<button class="btn btn-primary float-right btn-view-all approved" type="button"  > All Approve</button>
												</div>
											<hr>
										</div>
									
									<?php 
									$invoices = $customer->invoices;
									$i = 0;
									if (sizeof($invoices) > 0) 
									{   
										foreach ($invoices as $keyI => $valueI)
										{
											?>
											<div class="repeat-bill row" style="margin-top: 10px;">
												<div class="col-lg-12 col-md-12 bill-txt"><h4 class="card-title bill-text">Bill {{ $keyI+1 }}</h4>
													@if($valueI->tag != 'approved')
														<div class="float-right customers-btns" id="main_div">
															@if(Auth::user()->user_role == 2)
																<a href="javascript:void(0)" data-url="{{ route('delete-invoice', ['id' => $valueI->id, 'customer_id' => $customer->id])  }}" class="btn btn-primary float-right btn-view-all delete_invoice"  data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="fas fa-trash"></i></a>

																<a href="{{ route('edit-invoice', ['id' => $valueI->id, 'customer_id' => $customer->id])  }}" class="btn btn-primary float-right btn-view-all" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="far fa-edit"></i></a>
															@endif
						
																<!-- <a href="javascript:void(0);" data-url="{{ route('customers-invoice-approved', ['id' => $valueI->id, 'customer_id' => $customer->id])  }}" class="btn btn-primary float-right btn-view-all invoice_approved" data-toggle="tooltip" data-placement="top" data-original-title="Approve" ><i class="fas fa-check-circle"></i></a> -->

																<!--a href="javascript:void(0);" class="btn btn-primary float-right btn-view-all approved_button" data-toggle="tooltip" data-placement="top" title="" data-original-title="Approve" data-id="{{ $valueI->id }}" data-customer="{{ $customer->id }}"><i class="fas fa-check-circle"></i></a-->
														</div>
														
													@endif
												</div>

												<div class="col-lg-6 col-md-6 col-xs-12 paddL0">

												<div class="col-lg-12 col-md-12 col-xs-12">
													<label>LOCAL:</label> {{ $valueI->local }}
												</div>
												<div class="col-lg-12 col-md-12 col-xs-12">
													<label>MONTO FACTURA:</label> {{ $valueI->invoice_amount }}
												</div>
												<div class="col-lg-12 col-md-12 col-xs-12">
													<label>NO. FACTURA:</label> {{ $valueI->num_bill }}
												</div>
												<div class="col-lg-12 col-md-12 col-xs-12">
													<label>FECHA(DD / MM / AAAA):</label> {{ date('d M Y', strtotime($valueI->invoice_date)) }}
												</div>
												<div class="col-lg-12 col-md-12 col-xs-12 tag_div">
													<label>Tag:</label>
													@if($valueI->tag == 'approved')
														<span class="btn btn-sm btn-success btn-rounded invoice_tag">{{ $valueI->tag }}</span>
													@elseif($valueI->tag == 'edited')
														<span class="btn btn-sm btn-light btn-rounded invoice_tag">{{ $valueI->tag }}</span>
													@else
														<span class="btn btn-sm btn-danger btn-rounded invoice_tag">{{ $valueI->tag }}</span>
													@endif
													<!-- <span class="invoice_tag">{{ $valueI->tag }}</span> -->
												</div>
												</div>
												@if($valueI->attchment)
													<div class="col-lg-6 col-md-6 col-xs-12 pop-content">
														<a href="javascript:void(0);" data-toggle="modal" data-target="#imgModel_<?php echo $keyI ?>" data-backdrop="static" data-keyboard="false">
															@if (strtolower(pathinfo($valueI->attchment, PATHINFO_EXTENSION)) == 'pdf')
																<iframe src="{{ Storage::disk('s3')->url($valueI->attchment)}}"  height="150" width="150" class="iframe-popup"></iframe>
															@else
															<img src="{{ Storage::disk('s3')->url($valueI->attchment)}}" class="width30 large-img" height="150" width="150">

																<!-- <img src="{{ url('/') . '/' . $valueI->attchment }}" class="width30 large-img" height="150" width="150"> -->
															@endif
															</a>
														<div class="modal fade invoice-popup" id="imgModel_<?php echo $keyI ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
															<div class="modal-dialog" role="document">
																<div class="modal-content">
																	<div class="modal-header">
																		<h5 class="modal-title" id="exampleModalLabel">Invoice Images</h5>
																		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																			<span aria-hidden="true">&times;</span>
																		</button>
																	</div>
																	<div class="modal-body">
																		<div id="carouselExampleControls_<?php echo $keyI ?>" class="carousel slide" data-ride="carousel">
																			<div class="carousel-inner">
																				<div class="carousel-item active">
																					@if (strtolower(pathinfo($valueI->attchment, PATHINFO_EXTENSION)) == 'pdf')
																						<embed src="{{ Storage::disk('s3')->url($valueI->attchment)}}" type="application/pdf"  height="700px" width="100%">
																					@else
																						<img src="{{ \Storage::disk('s3')->url($valueI->attchment)}}"  class="zoom-images">
																						<!-- <img src="{{ url('/') . '/' . $valueI->attchment }}"  class="zoom-images"> -->
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
											</div> 
											<?php if($valueI->tag != 'approved'){ ?>
												<input type="hidden" name="customer_invoice_id[]" value="<?php echo $valueI->id;?>" >
											<?php }?>
											<!-- <a href="javascript:void(0);" data-toggle="modal" data-target="#imgModel_1" data-backdrop="static" data-keyboard="false"><img src="{{ url('/') . '/' . $valueI->attchment }}" class="width30"></a> -->
											<?php
											
											if($valueI->tag != 'approved'){
												$i++;
											}
										}
									}
									?>
									<input type="hidden" name="user_id" value="<?php echo $customer->id; ?>" >
									<?php if($i > 0){?>
										<script>
											document.getElementById('approveds').style.display = 'block';
										</script>
										<!--button class="btn btn-primary float-right btn-view-all approved" type="button"  > All Approve</button-->
									<?php }?>
								</form>
								
							</div>
		
                        </div>

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
		$(document).ready(function(){ 
			 $(document).on('click', '.invoice_approved', function() {
				currentElement = $(this);
				URL = currentElement.attr('data-url');
				swal({
					title: "Are you sure?",
					text: "Are you sure you want to approve all pending receipt?",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: '#DD6B55',
					confirmButtonText: 'Yes, approve it!',
					cancelButtonText: "No, cancel it!",
					closeOnConfirm: false,
					closeOnCancel: true
				},
				function(isConfirm) {
					if (isConfirm) {
						window.location = URL;
					} 
				});
			});
	    });
		
		$(document).ready(function(){ 	
			$('.approved').on('click',function(e) 
			{
				event.preventDefault();
				swal({
					title: "Are you sure?",
					text: "Are you sure you want to approve all pending receipts?",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: '#DD6B55',
					confirmButtonText: 'Yes, approve it!',
					cancelButtonText: "No, cancel it!",
					closeOnConfirm: false,
					closeOnCancel: true
				},
				function(isConfirm) {
					if (isConfirm) {
						document.forms["from1"].submit();
					}else{

					}
				});
			});
		});
		
		
       $(document).on('click', '.approved_button', function() {
            currentElement = $(this);
            var id = currentElement.attr('data-id');
            var customer_id = currentElement.attr('data-customer');
            
            /*event.preventDefault();*/
            $.ajax({
                url:"{{ url('') }}/approved-invoice/" + id + "/" + customer_id,
                method:"GET",
                // data: {id: id, customer_id: customer_id},
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(data)
                {
                    if(data.status == 1)
                    {
                        $('#empModal').modal({backdrop: 'static', keyboard: false})  
                        // Add response in Modal body
                        $('.return_message').val(data.message);
                        $('.modal_customer_id').val(customer_id);
                        // Display Modal
                        $('#empModal').modal('show');

                        currentElement.parent().parent().parent().children('.paddL0').children('.tag_div').children('.invoice_tag').html('approved');
                        currentElement.parent().remove();
                    }
                    else
                    {
                        alert(data.message);
                    }
                }
            }).fail(function (jqXHR, textStatus, error) {
                
            });
        });

	    $(document).ready(function(){
	       	$(document).on('click', '.delete_invoice', function(e) {
	       		currentElement = $(this);
        		URL = currentElement.attr('data-url');
				swal({
					title: "Are you sure?",
					text: "Are you sure you want to delete this receipt?",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: '#DD6B55',
					confirmButtonText: 'Yes, delete it!',
					cancelButtonText: "No, cancel it!",
					closeOnConfirm: false,
					closeOnCancel: true
				 },
				function(isConfirm){
				  	if (isConfirm) {
				  		$(".confirm").attr('disabled', 'disabled'); 
				  		window.location = URL;
				  }else{
				  }					  
				});
	        }); 
	    });
	    
		var $ = jQuery.noConflict();
		$(document).ready(function(){
			// Image zoom plugin code
			var zoomImages = $('.zoom-images');
			zoomImages.each(function() {
				$(this).imageZoom();
			});
		});
	</script>
@endpush