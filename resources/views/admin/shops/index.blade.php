@extends('layouts.admin')

@section('content')
	<div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 align-self-center">
                <a class="btn btn-primary float-right btn-view-all" href="{{ route('add-shop') }}" role="button"><i data-feather="plus" class="width15"></i> Add Shop</a>
                @if(sizeof($shops) > 0)
                	<a class="btn btn-primary float-right btn-view-all MR10 btn-danger delete_all_shops" href="javascript:void(0)" role="button"><i data-feather="plus" class="icon-trash width15"></i> Delete All Shops</a>
                @endif
            </div>
            
        </div>
    </div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card">
				    <div class="card-body">
				        <div class="mb-4">
				            <h4 class="card-title">Shops </h4>
				            	<div id="button" class="datatable-btns"></div>
				            	<div class="col-3 float-right search-csv">
					            	<div class="customize-input">
					                    <input class="form-control custom-shadow custom-radius border-0 bg-white myInputTextField" type="text" placeholder="Search" aria-label="Search">
					                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search form-control-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
					                </div>
				            	</div>
				            	 <div class="col-4 float-right upload-shops">
						           <form method="POST" action="{{ action('HomeController@import_records') }}" enctype="multipart/form-data">
									    <div class="input-group">
									        <div class="custom-file">
									            @csrf
									            <input type="file" name="file_upload" value="" id="file_upload" required="required" class="custom-file-input">
									            <label class="custom-file-label" for="inputGroupFile04">Choose file</label>
									        </div>
									        <div class="input-group-append">
									            <button class="shops-submit">submit</button>
									        </div>
									    </div>
									</form>
								</div>
				            
				            <!-- <input type="text" class="myInputTextField"> -->
				        </div>
				        <form action="{{ route('delete-selected-shops') }}" method="POST" id="shop_list_form">
				            @csrf
							<div class="table-responsive">
								<table id="shops"  class="table no-wrap v-middle mb-0 dashboard-table responsive nowrap">
									<thead>
										<tr class="border-0">
											<th class="border-0 font-14 font-weight-medium text-muted select-td noExport">
												<div class="custom-control custom-checkbox">
	                                                <!-- <input type="checkbox" class="custom-control-input checkAl" id="drop-remove"> -->
	                                                <input type="checkbox" id="drop-remove">
	                                                <label for="drop-remove"></label>
	                                                <div class="dropdown delete-all-dropdown">
							                            <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown"
							                                aria-haspopup="true" aria-expanded="false">
							                                <span class="d-none d-lg-inline-block"> <span
							                                        class="text-dark"></span> 
							                                        <i data-feather="chevron-down"
							                                        class="svg-icon"></i>
							                                 </span>
							                            </a>
							                            <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
							                                <a class="dropdown-item delete_selected_shops" href="javascript:void(0)">Delete all</a>
			                                        		</a>
			                                        		<!-- <button type="submit" class="btn btn-success" name="save">Delete all</button> -->
							                            </div>
							                        </div>
	                                            </div>
											</th>
											<th class="border-0 font-14 font-weight-medium text-muted">Sr.</th>
											<th class="border-0 font-14 font-weight-medium text-muted">Shop Name</th>
											<th class="border-0 font-14 font-weight-medium text-muted">Contract Number</th>
											<th class="border-0 font-14 font-weight-medium text-muted">Shopping Center ID</th>
											<th class="border-0 font-14 font-weight-medium text-muted">Shopping Center Name</th>
											<th class="border-0 font-14 font-weight-medium text-muted">Customers</th>
											<th class="border-0 font-14 font-weight-medium text-muted">Total Receipts</th>
											<th class="border-0 font-14 font-weight-medium text-muted">Avg Receipt Value</th>
											<th class="border-0 font-14 font-weight-medium text-muted">Highest receipt value</th>
											<th class="border-0 font-14 font-weight-medium text-muted">Total receipt value</th>
											<th class="border-0 font-14 font-weight-medium text-muted">Avg. number of customer per day</th>
											<th class="border-0 font-14 font-weight-medium text-muted">Avg. number of receipts per day</th>
											<th class="border-0 font-14 font-weight-medium text-muted">Avg. number of receipts per week</th>
											<th class="border-0 font-14 font-weight-medium text-muted">Weekly's Receipts</th>
											<th class="border-0 font-14 font-weight-medium text-muted">Today's Customer</th>
											<th class="border-0 font-14 font-weight-medium text-muted">Today's Receipts</th>
											<th class="border-0 font-14 font-weight-medium text-muted">Today's Highest Invoice Value</th>
											<th class="border-0 font-14 font-weight-medium text-muted">Today's Total Invoice Value</th>
											<th class="border-0 font-14 font-weight-medium text-muted noExport">Action</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($shops as $key => $shop) 
										<tr>
											<td class="border-top-0 text-muted px-2 py-4 font-14">
												<div class="custom-control custom-checkbox">
	                                                <!-- <input type="checkbox" class="custom-control-input checkItem" id="drop-remove" name="shop_id[]"> -->
	                                                <input type="checkbox" id="checkItem" name="shop_id[]" value="{{ $shop->id }}">
	                                                <label for="drop-remove"></label>
	                                            </div>
	                                        </td>
											<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $key+1 }}</td>
											<td class="border-top-0 text-muted px-2 py-4 font-14"><a href="{{ route('shop-details', ['shop_id' => $shop->id])  }}" class="change-psd-btn">{{ $shop->shop_name }}</a></td>
											<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $shop->contract_number }}</td>
											<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $shop->shopping_center_id }}</td>
											<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $shop->shopping_center_name }}</td>
											<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $shop->customerCount }}</td>
											<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $shop->totalReciepts }}</td>
											<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $shop->invoiceAmountAvg }}</td>
											<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $shop->invoiceAmountMax }}</td>
											<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $shop->invoiceAmountSum }}</td>
											<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $shop->customerCountAvg }}</td>
											<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $shop->recieptCountAvg }}</td>
											<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $shop->weeklyRecieptCountAvg }}</td>
											<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $shop->lastWeekTotalReciepts }}</td>
											<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $shop->todayCustomerCount }}</td>
											<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $shop->todayTotalReciepts }}</td>
											<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $shop->todayInvoiceAmountMax }}</td>
											<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $shop->todayInvoiceAmountSum }}</td>
											<td class="border-top-0 px-2 py-4">
												<a href="{{ route('edit-shop', ['shop_id' => $shop->id])  }}" class="change-psd-btn"><i class="fas fa-edit"></i></a>
												<!-- <a href="{{ route('delete-shop', ['shop_id' => $shop->id])  }}" class="change-psd-btn px-2"><i class="fas fa-trash"></i></a> -->
												<a href="javascript:void(0);" data-url="{{ route('delete-shop', ['shop_id' => $shop->id])  }}" class="change-psd-btn px-2 delete_shop" ><i class="fas fa-trash"></i></a>
												<a href="{{ route('shop-details', ['shop_id' => $shop->id])  }}" class="change-psd-btn px-2"><i class="fas fa-eye"></i></a>
											</td>
										</tr>
										@endforeach 
									</tbody>
									
								</table>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

<!-- Modal-->
<div class="modal fade alert-confirmtion" id="imgModel_1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h2>Are you Sure?</h2>
                <p>All the shops will be delete.</p>
                <div class="col-12 align-self-center MT30">
                	<a class="btn btn-primary btn-view-all btn-light MR10" href="javascript:void(0);" role="button" data-dismiss="modal" aria-label="Close"> Cancel</a>
                <a class="btn btn-primary btn-view-all" href="javascript:void(0);" role="button"> Delete All</a>
            </div>
			</div>
        </div>
    </div>
</div>
<!-- //Mod-->
@endsection