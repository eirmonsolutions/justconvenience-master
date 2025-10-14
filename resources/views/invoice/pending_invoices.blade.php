@extends('layouts.admin')

@section('content')

	<div class="page-breadcrumb">
        <div class="row">
	        <div class="col-9 align-self-center filter-form">
	        	<form action="{{ route('pending_invoices') }}" id="login" class="filter-form">
	            <div class="form-group width230 float-left">
	               <span> From</span>
	               <input type="text" class="date form-control" name="start_date" placeholder="Start Date" autocomplete="off" value="{{ isset($params['start_date']) && !empty($params['start_date']) ? date('d M Y', strtotime($params['start_date'])) : '' }}">
	            </div>
	            <div class="form-group width230 float-left Ml20"><span>To</span>
	                <input type="text" class="date form-control" name="end_date" placeholder="End Date" autocomplete="off" value="{{ isset($params['end_date']) && !empty($params['end_date']) ? date('d M Y', strtotime($params['end_date'])) : '' }}">
	            </div>
	            <div class="customize-input float-right">
	            	<input type="submit" class="btn btn-primary filter-btn" value="Filter">
	            </div>
	        </div>
        </div>
    </div>

	<div class="container-fluid">
		<div class="row">
		    <div class="col-12">
		        <div class="card">
		            <div class="card-body">
		                <div class="mb-4">
		                    <h4 class="card-title">Customers Invoices</h4>
							  @if(Auth::user()->user_role == 2)
					            	<div id="button" class="datatable-btns"></div>
					            @endif
							<div class="col-3 float-right search-csv">
								<div class="customize-input">
									<input class="form-control custom-shadow custom-radius border-0 bg-white myInputTextField" type="text" placeholder="Search" aria-label="Search">
									<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search form-control-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
								</div>
							</div>
								
		                </div>
						<div class="table-responsive">
							<table id="pending_invoices"  class="table no-wrap v-middle mb-0 dashboard-table responsive nowrap">
								<thead>
									<tr class="border-0">
	                                    <th class="border-0 font-14 font-weight-medium text-muted">Customer Name</th>
	                                    <th class="border-0 font-14 font-weight-medium text-muted">Bill Number</th>
	                                    <th class="border-0 font-14 font-weight-medium text-muted">Local Address</th>
	                                    <th class="border-0 font-14 font-weight-medium text-muted">Amount</th>
	                                   
	                                    <th class="border-0 font-14 font-weight-medium text-muted">Tag</th>
	                                    <th class="border-0 font-14 font-weight-medium text-muted">Payment Method</th>
	                                    <th class="border-0 font-14 font-weight-medium text-muted noExport">Images</th>
										<th class="border-0 font-14 font-weight-medium text-muted">Invoice Date</th>
	                                    <th class="border-0 font-14 font-weight-medium text-muted">Created Date</th>
	                                   	<th class="border-0 font-14 font-weight-medium text-muted noExport">Action</th>
	                                </tr>
								</thead>
								<tbody>
									@if(sizeof($customer_invoices) > 0)
										@foreach ($customer_invoices as $key => $customer_arr) 
											<tr>
												<td class="border-top-0 text-muted px-2 py-4 font-14">
													<a target="_blank" href="{{ route('customer-details', ['id' => $customer_arr->user->id])  }}">
														{{ $customer_arr->user->name . ' ' . $customer_arr->user->last_name }}
													</a>
												</td>
												<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $customer_arr->num_bill }}</td>
												<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $customer_arr->local }}</td>
												<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $customer_arr->invoice_amount }}</td>
												<td class="border-top-0 text-muted px-2 py-4 font-14">
													@if($customer_arr->tag == 'approved')
														<span class="btn btn-sm btn-success btn-rounded">{{ $customer_arr->tag }}</span>
													@elseif($customer_arr->tag == 'edited')
														<span class="btn btn-sm btn-light btn-rounded">{{ $customer_arr->tag }}</span>
													@else
														<span class="btn btn-sm btn-danger btn-rounded">{{ $customer_arr->tag }}</span>
													@endif
												</td>
												<!--td class="border-top-0 text-muted px-2 py-4 font-14">{{ $customer_arr->tag }}</td-->
												<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $customer_arr->payment_method }}</td>
												<td class="border-top-0 text-muted px-2 py-4 font-14 pop-content">
													@if(!empty($customer_arr->attchment))
														<a href="javascript:void(0);" data-toggle="modal" data-target="#imgModel_<?php echo $customer_arr->id ?>" data-backdrop="static" data-keyboard="false">
															@if (strtolower(pathinfo($customer_arr->attchment, PATHINFO_EXTENSION)) == 'pdf')
																<iframe src="{{ Storage::disk('s3')->url($customer_arr->attchment)}}
"  height="60" width="40" class="iframe-popup"></iframe>
															@else
																<img src="{{ Storage::disk('s3')->url($customer_arr->attchment)}}
" class="width30">
															@endif
		                                        		</a>
														<div class="modal fade invoice-popup" id="imgModel_<?php echo $customer_arr->id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
															<div class="modal-dialog" role="document">
																<div class="modal-content">
																	<div class="modal-header">
																		<h5 class="modal-title" id="exampleModalLabel">Invoice Images</h5>
																		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																			<span aria-hidden="true">&times;</span>
																		</button>
																	</div>
																	<div class="modal-body">
																		<div id="carouselExampleControls_<?php echo $customer_arr->id ?>" class="carousel slide" data-ride="carousel">
																			<div class="carousel-item active">
																				@if (strtolower(pathinfo($customer_arr->attchment, PATHINFO_EXTENSION)) == 'pdf')
																						<embed src="{{ Storage::disk('s3')->url($customer_arr->attchment)}}
" type="application/pdf"  height="700px" width="100%">
																					@else
																						<img src="{{ Storage::disk('s3')->url($customer_arr->attchment)}}
">
																					@endif
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													@endif
												</td>
												<td class="border-top-0 text-muted px-2 py-4 font-14">{{ date('Y-m-d',strtotime($customer_arr->invoice_date))  }}</td>
												<td class="border-top-0 text-muted px-2 py-4 font-14">{{ date('Y-m-d',strtotime($customer_arr->created_at))  }}</td>
												<td class="border-top-0 text-muted px-2 py-4 font-14">
													<a href="{{ route('edit-invoice', ['id' => $customer_arr->id, 'customer_id' => $customer_arr->user->id])  }}" class="btn btn-primary float-right btn-view-all" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit" target="_blank"><i class="far fa-edit"></i></a>
												</td>
											</tr>
										@endforeach
									@endif
								</tbody>
								
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection