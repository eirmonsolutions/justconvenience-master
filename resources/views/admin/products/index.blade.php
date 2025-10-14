@extends('layouts.admin')

@section('content')
	<div class="page-breadcrumb">
        <div class="row">
        	<div class="col-9 align-self-center filter-form">
	        	<form action="{{ route('products') }}" id="login" class="filter-form">
		            <div class="customize-input float-left Ml20 contest-input">
		                <select class="custom-select custom-select-set form-control bg-white border-0 custom-shadow custom-radius" name="category_id" id="categories">
		                	<option value="">Select Category</option>
		                    @if(sizeof($categories) > 0)
								@foreach($categories as $key => $category)
									<option value="{{ $category->id }}" {{ (isset($params['category_id']) && !empty($params['category_id']) && $params['category_id'] == $category->id) ? "selected" : '' }}>{{ $category->name }}</option>
								@endforeach
							@endif
		                </select>
		            </div>
		            <div class="customize-input float-left Ml20 contest-input">
		                <select class="custom-select custom-select-set form-control bg-white border-0 custom-shadow custom-radius" name="subcategory_id" id="subcategories">
		                    <option value="">Select Subcategory</option>
		                    @if(sizeof($sub_categories) > 0)
								@foreach($sub_categories as $key => $sub_category)
									<option value="{{ $sub_category->id }}" {{ (isset($params['subcategory_id']) && !empty($params['subcategory_id']) && $params['subcategory_id'] == $sub_category->id) ? "selected" : '' }}>{{ $sub_category->name }}</option>
								@endforeach
							@endif
		                </select>
		            </div>
		            <div class="customize-input float-right">
		            	<input type="submit" class="btn btn-primary filter-btn" value="Filter">
		            </div>
	        	</form>
	        </div>

            <div class="col-3 align-self-center">
                <a class="btn btn-primary float-right btn-view-all" href="{{ route('add-product') }}" role="button"><i data-feather="plus" class="width15"></i> Add Product</a>
            </div>
            
        </div>
    </div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card">
				    <div class="card-body">
				        <div class="mb-4">
				            <h4 class="card-title">Products </h4>
			            	<div id="button" class="datatable-btns"></div>
			            	<div class="col-3 float-right search-csv">
				            	<div class="customize-input">
				                    <input class="form-control custom-shadow custom-radius border-0 bg-white myInputTextField" type="text" placeholder="Search" aria-label="Search">
				                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search form-control-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
				                </div>
			            	</div>				            
				        </div>
						<div class="table-responsive">
							<table id="products"  class="table no-wrap v-middle mb-0 dashboard-table responsive nowrap">
								<thead>
									<tr class="border-0">
										<th class="border-0 font-14 font-weight-medium text-muted">Sr.</th>
										<th class="border-0 font-14 font-weight-medium text-muted">Name</th>
										<th class="border-0 font-14 font-weight-medium text-muted">Bar Code</th>
										<th class="border-0 font-14 font-weight-medium text-muted">Price</th>
										<th class="border-0 font-14 font-weight-medium text-muted">Category</th>
										{{--<th class="border-0 font-14 font-weight-medium text-muted">Sub Category</th>
										<th class="border-0 font-14 font-weight-medium text-muted noExport">Featured Image</th>--}}
										<th class="border-0 font-14 font-weight-medium text-muted noExport">Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($products as $key => $product)
									<tr data-id="{{ $product->id }}">
										<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $key+1 }}</td>
										<td class="border-top-0 text-muted px-2 py-4 font-14 product_name" id="product_name_{{ $product->id }}">{{ $product->name }}</td>
										<td class="border-top-0 text-muted px-2 py-4 font-14" id="product_bar_code_{{ $product->id }}">{{ $product->bar_code }}</td>
										<td class="border-top-0 text-muted px-2 py-4 font-14 product_type" id="product_price_{{ $product->id }}">{{ $product->price }}</td>
										<td class="border-top-0 text-muted px-2 py-4 font-14">{{ ($product->category) ? $product->category->name : '' }}</td>
										{{--<td class="border-top-0 text-muted px-2 py-4 font-14">{{ ($product->subcategory) ? $product->subcategory->name : '' }}</td>
										<td class="border-top-0 text-muted px-2 py-4 font-14">
											@if($product->featured_image)
												<img style="max-width: 50px;" src="{{ url('/') . '/' . $product->featured_image }}">
											@endif
										</td>--}}
										<td class="border-top-0 px-2 py-4">
											<span class="switch-list switch">
												<input type="checkbox" data-id="{{ $product->id }}" class="switch update_product_status" id="switch-id-{{$product->id}}" value="{{ $product->status }}" {{ ($product->status == 1) ? 'checked' : '' }}>
												<label for="switch-id-{{$product->id}}"></label>
											</span>
											<a href="{{ route('edit-product', ['product_id' => $product->id])  }}" class="change-psd-btn"><i class="fas fa-edit"></i></a>

											<a href="javascript:void(0)" data-id="{{ $product->id }}" class="change-psd-btn Qedit"><i class="fa fa-fast-forward" aria-hidden="true"></i></a>
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


	<div class="modal fade" id="quick_product_edit" role="dialog">
	    <div class="modal-dialog">

	        <!-- Modal content-->
	        <div class="modal-content">
	            <div class="modal-header">
	                <h4 class="modal-title">Category Details - <span id="cat_position"></span></h4>
	                <button type="button" class="close" data-dismiss="modal">&times;</button>
	            </div>
	            <div class="modal-body">
	                <form class="tagForm" action="{{ route('update-product-details') }}" id="update-product-form" method="POST"> 
	                    @csrf
	                    <input type="hidden" name="product_id" class="modal_product_id p_id" value=""> 
	                    
	                    <label for="exampleFormControlSelect1">Product Name:</label>
	                    <input name="name" type="text" class="form-control mb-3 p_name" id="exampleFormControlSelect1" placeholder="Name*" value="" required>
	                    
	                    <label for="exampleFormControlSelect1">Bar Code:</label>
	                    <input name="bar_code" type="text" class="form-control mb-3 p_bar_code" id="exampleFormControlSelect1" placeholder="Bar Code*" value="" required> 
	                    
	                    <label for="exampleFormControlSelect1">Price</label>
	                    <input name="price" type="text" class="form-control mb-3 p_price decimal_format" id="exampleFormControlSelect1" placeholder="Price*" value="" required>
	                    
	                    <label for="exampleFormControlSelect1">Description</label>
	                    <textarea name="description" class="form-control mb-3 p_description"></textarea>
	                    
	                    <button type="submit" class="btn btn-primary btn-view-all float-right">Update</button>
	                </form>
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	            </div>
	        </div>
	    </div>
	</div>
@endsection

@push('scripts')
    <script type="text/javascript">
    	$(document).on('change', '.update_product_status', function() {
    	    currentElement = $(this);

    	    var currentValue = currentElement.prop('checked');
    	    var status = currentValue ? 1 : 0;
    	    var id = currentElement.attr('data-id');

    	    $.ajax({
    	        url:"{{ url('') }}/update-product-status/" + id + "/" + status,
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

    	$(document).on('change', '#categories', function () {
    	    var category_id = $(this).children("option:selected").val();
    	    $.ajax({
    	        url:"{{ url('') }}/get-sub-categories/" + category_id,
    	        method:"GET",
    	        dataType:'JSON',
    	        contentType: false,
    	        cache: false,
    	        processData: false,
    	        success:function(data)
    	        {
    	            if (data.status == 1)
    	            {
    	                $("#subcategories").empty();

    	                $('#subcategories').append('<option value="" selected>Select Subcategory</option>');
    	                $.each(data.sub_categories, function(key, value) 
    	                {
    	                    $('#subcategories').append('<option value="'+ value.id +'">'+ value.name +'</option>');
    	                });
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


    	$(document).on('click', '.Qedit', function() {
    	    currentElement = $(this);
    	    product_id = currentElement.attr('data-id');
    	    // console.log(currentElement.parent());

    	    $.ajax({
    	        url:"{{ url('') }}/product-details/" + product_id,
    	        method:"GET",
    	        dataType:'JSON',
    	        contentType: false,
    	        cache: false,
    	        processData: false,
    	        beforeSend: function (request) {
	                return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
	            },
    	        success:function(data)
    	        {
    	        	console.log(data);
    	            if (data.status == 1)
    	            {
    	            	console.log(data.data)
    	            	$('.p_id').val(data.data.id);
    	            	$('.p_name').val(data.data.name);
    	            	$('.p_bar_code').val(data.data.bar_code);
    	            	if(data.data.price > 0)
    	            	{
    	            		$('.p_price').val(data.data.price);
    	            	}
    	            	else
    	            	{
    	            		$('.p_price').val('');	
    	            	}
    	            	$('.p_description').val(data.data.description);

    	            	var cat_text = data.data.category.name;

    	            	if (data.data.subcategory == null || data.data.subcategory == undefined)
    	            	{
    	            	}
    	            	else
    	            	{
    	            		cat_text = cat_text + ' (' + data.data.subcategory.name + ')';
    	            	}

    	            	$('#cat_position').html(cat_text);

    	            	$('#quick_product_edit').modal('show')
    	            }
    	        }
    	    }).fail(function (jqXHR, textStatus, error) {
    	        // window.location.reload();
    	        toastr.error('Something went wrong');
    	    });
    	});

    	$('#update-product-form').submit(function(e) {
    		e.preventDefault();
    		console.log($('form.tagForm').serialize());
    	    $.ajax({
    	        type: "POST",
            	url: "{{ route('update-product-details') }}",
            	data: $('form.tagForm').serialize(),
    	        dataType:'JSON',
    	        // contentType: false,
    	        cache: false,
    	        processData: false,
    	        beforeSend: function (request) {
	                return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
	            },
    	        success:function(data)
    	        {
    	        	console.log(data);
    	            if (data.status == 1)
    	            {
    	            	console.log(data);
    	            	$('#quick_product_edit').modal('hide');

    	            	$('#product_name_' + data.data.id).html(data.data.name);
    	            	$('#product_bar_code_' + data.data.id).html(data.data.bar_code);
    	            	$('#product_price_' + data.data.id).html(data.data.price);
    	            }
    	            else
    	            {
    	                // window.location.reload();
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