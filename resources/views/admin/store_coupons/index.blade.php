@extends('layouts.admin')

@section('content')
	<div class="page-breadcrumb">
        <div class="row">
        	<div class="col-4 align-self-center">
        	
            </div>

            <div class="col-8 align-self-center">
                <a class="btn btn-primary float-right btn-view-all" href="{{ route('add-store-coupon') }}" role="button"><i data-feather="plus" class="width15"></i> Add Store Coupon</a>
            </div>
            
        </div>
    </div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card">
				    <div class="card-body">
				        <div class="mb-4">
				            <h4 class="card-title">Store Coupons </h4>
			            	<div id="button" class="datatable-btns"></div>
			            	<div class="col-3 float-right search-csv">
				            	<div class="customize-input">
				                    <input class="form-control custom-shadow custom-radius border-0 bg-white myInputTextField" type="text" placeholder="Search" aria-label="Search">
				                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search form-control-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
				                </div>
			            	</div>				            
				        </div>
						<div class="table-responsive">
							<table id="store_coupons"  class="table no-wrap v-middle mb-0 dashboard-table responsive nowrap">
								<thead>
									<tr class="border-0">
										<th class="border-0 font-14 font-weight-medium text-muted">Sr.</th>
										<th class="border-0 font-14 font-weight-medium text-muted">Coupon Code</th>
										<th class="border-0 font-14 font-weight-medium text-muted noExport">Coupon Type</th>
										<th class="border-0 font-14 font-weight-medium text-muted noExport">Coupon Value</th>
										<th class="border-0 font-14 font-weight-medium text-muted noExport">Coupon Applied</th>
										<th class="border-0 font-14 font-weight-medium text-muted noExport">Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($coupons as $key => $coupon)
									<tr>
										<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $key+1 }}</td>
										<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $coupon->code }}</td>
										<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $coupon->type }}</td>
										<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $coupon->coupon_value }}</td>
										<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $coupon->stores->count() }}</td>
										
										<td class="border-top-0 px-2 py-4">
											<span class="switch-list switch">
												<input type="checkbox" data-id="{{ $coupon->id }}" class="switch update_coupon_status" id="switch-id-{{$coupon->id}}" value="{{ $coupon->status }}" {{ ($coupon->status == 1) ? 'checked' : '' }}>
												<label for="switch-id-{{$coupon->id}}"></label>
											</span>
										</td>
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
    	$(document).ready(function()
    	{
    		constantTable = $('#store_coupons').DataTable( {
    		    dom: 'Bfrtip',
    		    buttons: [{
    		    //here comes your button definitions
    		    }]
    		} );

    		$('.myInputTextField').keyup(function(){
    		    constantTable.search($(this).val()).draw() ;
    		});

    		var buttons = new $.fn.dataTable.Buttons(constantTable, {
    		    buttons: [
    		    {
    		        extend: 'excel',
    		        title: 'Store coupons Excel',
    		        exportOptions: {
    		            columns: "thead th:not(.noExport)"
    		        }
    		    }, {
    		        extend: 'csv',
    		        title: 'Store coupons Csv',
    		        exportOptions: {
    		            columns: "thead th:not(.noExport)"
    		        }
    		    }
    		    ]
    		}).container().appendTo($('#button'));
    	});

    	$(document).on('change', '.update_coupon_status', function() {
    	    currentElement = $(this);

    	    var currentValue = currentElement.prop('checked');
    	    var status = currentValue ? 1 : 0;
    	    var id = currentElement.attr('data-id');

    	    $.ajax({
    	        url:"{{ url('') }}/update-store-coupon-status/" + id + "/" + status,
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