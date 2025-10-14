@extends('layouts.admin')

@section('content')
	@if(Auth::user()->user_role == 2)
		<div class="page-breadcrumb">
	        <div class="row">
	        	<div class="col-4 align-self-center">
	        	
	            </div>

	            <div class="col-8 align-self-center">
	                <a class="btn btn-primary float-right btn-view-all" href="{{ route('add-store') }}" role="button"><i data-feather="plus" class="width15"></i> Add Store</a>
	            </div>
	            
	        </div>
	    </div>
	@endif
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card">
				    <div class="card-body">
				        <div class="mb-4">
				            <h4 class="card-title">Stores </h4>
				            
				            	<div id="button" class="datatable-btns"></div>
				            	<div class="col-3 float-right search-csv">
					            	<div class="customize-input">
					                    <input class="form-control custom-shadow custom-radius border-0 bg-white myInputTextField" type="text" placeholder="Search" aria-label="Search">
					                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search form-control-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
					                </div>
				            	</div>
				            
				            <!-- <input type="text" class="myInputTextField"> -->
				        </div>
						<div class="table-responsive">
							<table id="stores" class="table no-wrap v-middle mb-0 dashboard-table responsive nowrap">
								<thead>
									<tr class="border-0">
										<th class="border-0 font-14 font-weight-medium text-muted">Sr.</th>
										<th class="border-0 font-14 font-weight-medium text-muted noExport">Action</th>
										<th class="border-0 font-14 font-weight-medium text-muted">Name</th>
										<th class="border-0 font-14 font-weight-medium text-muted">Phone Number</th>
										<th class="border-0 font-14 font-weight-medium text-muted">Email</th>
										@if(Auth::user()->user_role == 2)
											<th class="border-0 font-14 font-weight-medium text-muted noExport">Store Image</th>
										@endif
										<th class="border-0 font-14 font-weight-medium text-muted">Store Name</th>
										<th class="border-0 font-14 font-weight-medium text-muted">Store Postcode</th>
										<th class="border-0 font-14 font-weight-medium text-muted">Merchant ID</th>
										<th class="border-0 font-14 font-weight-medium text-muted">Merchant Secret</th>
										@if(Auth::user()->user_role == 2)
											<th class="border-0 font-14 font-weight-medium text-muted">Store Paid</th>
										@endif
									</tr>
								</thead>
								<tbody>
									@foreach ($stores as $key => $store) 
										<tr>
											<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $key+1 }}</td>
											<td class="border-top-0 px-2 py-4" style="padding-left: 0px !important;">
												@if(Auth::user()->user_role == 2)
													<span class="switch-list switch">
														<input type="checkbox" data-id="{{ $store->id }}" class="switch update_store_status" id="switch-id-{{$store->id}}" value="{{ $store->status }}" {{ ($store->status == 1) ? 'checked' : '' }}>
														<label for="switch-id-{{$store->id}}"></label>
													</span>
												@endif
												<a href="{{ route('edit-store', ['store_id' => $store->id])  }}" class="change-psd-btn"><i class="fas fa-edit"></i></a>
											</td>
											<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $store->name }}</td>
											<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $store->phone_number }}</td>
											<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $store->email }}</td>
											@if(Auth::user()->user_role == 2)
												<td class="border-top-0 text-muted px-2 py-4 font-14">
													@if($store->image)
														<img style="max-width: 50px;" src="{{ url('/') . '/' . $store->image }}">
													@endif
												</td>
											@endif
											<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $store->store_name }}</td>
											<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $store->zipcode }}</td>
											<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $store->merchantID }}</td>
											<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $store->merchantSecret }}</td>
											@if(Auth::user()->user_role == 2)
												<td class="border-top-0 text-muted px-2 py-4 font-14">{{ ($store->is_store_paid) ? 'Yes' : 'No' }}</td>
											@endif
										</tr>
									@endforeach 
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
    <script type="text/javascript">
    	$(document).on('change', '.update_store_status', function() {
            currentElement = $(this);

            var currentValue = currentElement.prop('checked');
            var status = currentValue ? 1 : 0;
            var id = currentElement.attr('data-id');

            $.ajax({
                url:"{{ url('') }}/update-store-status/" + id + "/" + status,
                method:"GET",
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(data)
                {
                    if (data.status == 1)
                    {
                        window.location.reload();
                        toastr.success(data.message);
                    }
                    else
                    {
                        window.location.reload();
                        toastr.error(data.message);
                    }
                }
            }).fail(function (jqXHR, textStatus, error) {
                // window.location.reload();
                toastr.error('Something went wrong');
            });
        });
    </script>
@endpush