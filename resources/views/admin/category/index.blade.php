@extends('layouts.admin')

@section('content')
	<div class="page-breadcrumb">
        <div class="row">
        	<div class="col-4 align-self-center">
        	
            </div>

            <div class="col-8 align-self-center">
                <a class="btn btn-primary float-right btn-view-all" href="{{ route('add-category') }}" role="button"><i data-feather="plus" class="width15"></i> Add Category</a>
            </div>
            
        </div>
    </div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card">
				    <div class="card-body">
				        <div class="mb-4">
				            <h4 class="card-title">Category </h4>
			            	<div id="button" class="datatable-btns"></div>
			            	<div class="col-3 float-right search-csv">
				            	<div class="customize-input">
				                    <input class="form-control custom-shadow custom-radius border-0 bg-white myInputTextField" type="text" placeholder="Search" aria-label="Search">
				                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search form-control-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
				                </div>
			            	</div>				            
				        </div>
						<div class="table-responsive">
							<table id="categories"  class="table no-wrap v-middle mb-0 dashboard-table responsive nowrap">
								<thead>
									<tr class="border-0">
										<th class="border-0 font-14 font-weight-medium text-muted">Sr.</th>
										<th class="border-0 font-14 font-weight-medium text-muted">Name</th>
										<th class="border-0 font-14 font-weight-medium text-muted noExport">Featured Image</th>
										<th class="border-0 font-14 font-weight-medium text-muted">Age Restricted</th>
										<th class="border-0 font-14 font-weight-medium text-muted noExport">Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($categories as $key => $category)

									<tr>
										<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $key+1 }}</td>
										<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $category->name }}</td>
										<td class="border-top-0 text-muted px-2 py-4 font-14">
											@if($category->featured_image)
												<img style="max-width: 50px;" src="{{ url('/') . '/' . $category->featured_image }}">
											@endif
										</td>
										<td class="border-top-0 text-muted px-2 py-4 font-14">{{ ($category->is_age_restricted) ? 'Yes' : 'No' }}</td>
										<td class="border-top-0 px-2 py-4">
											<span class="switch-list switch">
												<input type="checkbox" data-id="{{ $category->id }}" class="switch update_category_status" id="switch-id-{{$category->id}}" value="{{ $category->status }}" {{ ($category->status == 1) ? 'checked' : '' }}>
												<label for="switch-id-{{$category->id}}"></label>
											</span>
											<a href="{{ route('edit-category', ['category_id' => $category->id])  }}" class="change-psd-btn"><i class="fas fa-edit"></i></a>
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
    	$(document).on('change', '.update_category_status', function() {
    	    currentElement = $(this);

    	    var currentValue = currentElement.prop('checked');
    	    var status = currentValue ? 1 : 0;
    	    var id = currentElement.attr('data-id');

    	    $.ajax({
    	        url:"{{ url('') }}/update-category-status/" + id + "/" + status,
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