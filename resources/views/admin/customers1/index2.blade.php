@extends('layouts.admin')

@section('content')
	<div class="page-breadcrumb">
	    <div class="row">
	        <!-- <div class="col-2 align-self-center">
	            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Filters</h4>
	        </div> -->
	        <div class="col-12 align-self-center customer-leads v3">
<!-- 	        	<form action="{{ route('get_customers') }}" id="login" class="filter-form">
 -->	            <div class="form-group width230 float-left">
	               <span> From</span>
	               <input type="text" class="date form-control" name="start_date" id="datatable_startdate" placeholder="Start Date" autocomplete="off" value="{{ isset($params['start_date']) && !empty($params['start_date']) ? date('d M Y', strtotime($params['start_date'])) : '' }}">
	            </div>
	            <div class="form-group width230 float-left Ml20"><span>To</span>
	                <input type="text" class="date form-control" name="end_date"  id="datatable_enddate"  placeholder="End Date" autocomplete="off" value="{{ isset($params['end_date']) && !empty($params['end_date']) ? date('d M Y', strtotime($params['end_date'])) : '' }}">
	            </div>
	            <div class="form-group width230 float-left Ml20"><span>Count</span>
	                <input type="number" min="0" class="form-control" name="ticket_count"  id="datatable_ticket_count"  placeholder="Ticket Count" autocomplete="off" value="{{ isset($params['ticket_count']) && !empty($params['ticket_count']) ? $params['ticket_count'] : '' }}">
	            </div>
	            <div class="customize-input float-left Ml20">
	                <select class="custom-select custom-select-set form-control bg-white border-0 custom-shadow custom-radius"  id="datatable_tag" name="tag">
	                	<option value="">All</option>
						<option value="new" {{ (isset($params['tag']) && !empty($params['tag']) && $params['tag'] == 'new') ? "selected" : '' }}>New</option>
						<option value="approved" {{ (isset($params['tag']) && !empty($params['tag']) && $params['tag'] == 'approved') ? "selected" : '' }}>Approved</option>
						<option value="pending" {{ (isset($params['tag']) && !empty($params['tag']) && $params['tag'] == 'pending') ? "selected" : '' }}>Pending</option>
	                </select>
	            </div>
	            <div class="customize-input float-left Ml20 contest-input">
	                <select class="custom-select custom-select-set form-control bg-white border-0 custom-shadow custom-radius" name="contest_id" id="datatable_contest_id">
	                	@php
	                	$customers = array();
	                	@endphp
	                    @if(sizeof($contests) > 0)
							<!-- <option value="">All</option> -->
							@foreach($contests as $key => $contest)
								<option value="{{ $contest->id }}" {{ (isset($params['contest_id']) && !empty($params['contest_id']) && $params['contest_id'] == $contest->id) ? "selected" : '' }}>{{ $contest->title }}</option>
							@endforeach
						@endif
	                </select>
	            </div>
	            <div class="customize-input float-left Ml20 shops-dropdown">
	                <select class="custom-select custom-select-set form-control bg-white border-0 custom-shadow custom-radius select2" name="shop_id" id="shop_id">
						@if(sizeof($shops) > 0)
							<option value="">All</option>
							@foreach($shops as $key => $shop)
								<option value="{{ $shop->id }}" {{ (isset($params['shop_id']) && !empty($params['shop_id']) && $params['shop_id'] == $shop->id) ? "selected" : '' }}>{{ $shop->shop_name }}{{ ', ' . $shop->contract_number }}{{ ', ' . $shop->shopping_center_id }}</option>
							@endforeach
						@endif
	                </select>
	            </div>
	            <div class="customize-input float-right">
	            	<input type="submit" class="btn btn-primary filter-btn" id="datatable_submit" value="Filter">
	            </div>

	            <!-- <div class="customize-input float-right">
	                <input value="{{ isset($params['ticket_count']) && !empty($params['ticket_count']) ? $params['ticket_count'] : '' }}" type="number" name="ticket_count" class="form-control">
	            </div> -->
	        </div>
	        
	    </div>
	</div>

	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card">
				    <div class="card-body">
				        <div class="mb-4">
				            <h4 class="card-title">Customers Leads </h4>
				            <a id="download_allCustomer" href="javascript:void(0);" class="btn btn-primary float-right btn-view-all ml-2">Download All</a>
					            @if(Auth::user()->user_role == 2)
					            	<div id="button" class="datatable-btns"></div>
					            @endif
				            	<div class="col-3 float-right search-csv">
					            	<div class="customize-input">
					                    <input class="form-control custom-shadow custom-radius border-0 bg-white myInputTextField12 customer_search" id="customer_search" type="text" placeholder="Search" aria-label="Search">
					                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search form-control-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
					                </div>
				            	</div>
				            
				        </div>
						<div class="table-responsive">
							<table id="customers"  class="table no-wrap v-middle mb-0 dashboard-table responsive nowrap">
								<thead>
									<tr class="border-0">
                                        <th class="border-0 font-14 font-weight-medium text-muted">Sr.
                                        <th class="border-0 font-14 font-weight-medium text-muted">Customer Name
                                        </th>
                                        <th class="border-0 font-14 font-weight-medium text-muted">Email
                                        </th>
                                        <th class="border-0 font-14 font-weight-medium text-muted px-2">Phone
                                        </th>
                                        <th class="border-0 font-14 font-weight-medium text-muted">Direction</th>
                                        <th class="border-0 font-14 font-weight-medium text-muted">
                                            Store
                                        </th>
                                        <th class="border-0 font-14 font-weight-medium text-muted">Date of receipts</th>
                                        <th class="border-0 font-14 font-weight-medium text-muted text-center">
                                            Total Value of receipts
                                        </th>
                                        <th class="border-0 font-14 font-weight-medium text-muted">No. of receipts</th>
                                        <th class="border-0 font-14 font-weight-medium text-muted">No. of tickets</th>
                                        <th class="border-0 font-14 font-weight-medium text-muted">Id Number/ Ruc/ Passport</th>
                                        <!-- <th class="border-0 font-14 font-weight-medium text-muted">Approved By</th>-->
                                        <th class="border-0 font-14 font-weight-medium text-muted noExport">Action</th>
                                    </tr>
								</thead>
					
								
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<style type="text/css">
		#download_allCustomer{padding: 8px 20px;margin-top: 0;}
		@media (max-width: 767px) {#download_allCustomer{padding: 6px 12px; margin-top: 0; font-size: 13px; margin-left: 5px !important;}}
	</style>
@endsection
@push('scripts')
<script type="text/javascript">
		$(document).ready(function(){
			ticketsTable = $('#customers').DataTable({ 
                processing: true,
                serverSide: true, 
                searching: true,
                // pageLength: 5,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
                    search: ''
                },
                dom: 'Blfrtip',
                // buttons: [
                //     {
                //        extend: 'excel',
                //        title: 'Ticket Excel',
                //        exportOptions: {
                //            columns: "thead th:not(.noExport)"
                //        }
                //     }, {
                //        extend: 'csv',
                //        title: 'Contest Csv',
                //        exportOptions: {
                //            columns: "thead th:not(.noExport)"
                //        }
                //     }
                // ],
                // buttons: [
                //      'csv', 'excel', 'pdf'
                // ],
                ajax: {
                    url: "{{ url('get_customers') }}",
                    type: 'GET',
                    data: function (d) {
                        d.start_date = $('#datatable_startdate').val();
                        d.end_date = $('#datatable_enddate').val();
                        d.ticket_count = $('#datatable_ticket_count').val(); 
                        d.search_fields = $('#customer_search').val();
                        d.contest_id = $("#datatable_contest_id option:selected").val();
                        d.tag = $("#datatable_tag option:selected").val();
                        d.shop_id = $("#shop_id option:selected").val();
                        },
                    }, 
                columns: [ 
                    { data: 'id', name: 'id', class: 'border-top-0 px-2 py-4', searchable: false, visible: true, orderable: false, render: function (data, type, row, meta) {
				        return meta.row + meta.settings._iDisplayStart + 1;
				    } },
                    { data: null, name: 'name', class: 'border-top-0 px-2 py-4', render:  function (data, type, row, meta) {
				         return '<div class="d-flex no-block align-items-center">                                                        <div class="mr-3"></div><div class="user-detail"><a href="'+ data.user_url +'"><h5 class="text-dark mb-0 font-16 font-weight-medium">'+data.name+ '- <span class="'+ data.user_review.toLowerCase() +'">'+ data.user_review +'</span></h5></a></div></div>';
				    } },
                    { data: 'email', class:'border-top-0 text-muted px-2 py-4 font-14'  }, 
                    { data: 'phone_number', class:'border-top-0 text-muted px-2 py-4 font-14'  }, 
                    { data: 'direction', class:'border-top-0 text-muted px-2 py-4 font-14'  }, 
                    { data: 'store', name: 'store', class:'border-top-0 text-muted px-2 py-4 font-14', searchable: false, visible: true, orderable: false, }, 
                    { data: 'invoice_str', name: 'invoice_str', class:'border-top-0 text-muted px-2 py-4 font-14', searchable: false, visible: true, orderable: false,  }, 
                    { data: 'invoice_amount', name: 'invoice_amount', class:'border-top-0 text-muted px-2 py-4 font-14 text-center', searchable: false, visible: true, orderable: false,   }, 
                    { data: 'invoice_count', name: 'invoice_count', class:'border-top-0 text-muted px-2 py-4 font-14 text-center', searchable: false, visible: true, orderable: false,   }, 
                    { data: 'ticket_count', class:'border-top-0 text-muted px-2 py-4 font-14 text-center'  }, 
                    { data: 'indentification_card', name: 'indentification_card', class:'border-top-0 text-muted px-2 py-4 font-14'  }, 
                    { data: 'action', name: 'action', class:'border-top-0 text-muted px-2 py-4 font-14', searchable: false, visible: true, orderable: false,  },  
                ], 
            });

           

             
            $(document.body).on("keyup","#customer_search", function(){
                 $('#customers').DataTable().draw(true);
            }); 

            //$('.myInputTextField').keyup(function(){
            	// $('#customers').DataTable().draw(true);
                  // ticketsTable.search($(this).val()).draw(false) ;
            //});

            $(document.body).on("click","#datatable_submit", function(){
                $('#customers').DataTable().draw(true);
            });

             var buttons = new $.fn.dataTable.Buttons(ticketsTable, {
                    buttons: [
                        {
                           extend: 'excel',
                           title: 'Ticket Excel',
                           exportOptions: {
                               columns: "thead th:not(.noExport)"
                           }
                        }, {
                           extend: 'csv',
                           title: 'Ticket Csv',
                           exportOptions: {
                               columns: "thead th:not(.noExport)"
                           }
                        }
                    ]
                }).container().appendTo($('#button'));
		});

	$(document).on("click", "#download_allCustomer", function(){
        start_date = $('#datatable_startdate').val();
        end_date = $('#datatable_enddate').val();
        ticket_count = $('#datatable_ticket_count').val();  
        contest_id = $("#datatable_contest_id option:selected").val();
        tag = $("#datatable_tag option:selected").val();
        shop_id = $("#shop_id option:selected").val();
        search_fields = $('#customer_search').val();
        // console.log(start_date);
        // console.log(end_date);
        // console.log(ticket_count);
        // console.log(tag);
        // console.log(shop_id);

        var obj = {
            start_date    : start_date,
            end_date    : end_date,
            ticket_count    : ticket_count,
            tag    : tag,
            contest_id    : contest_id,
            shop_id    : shop_id,
            search_fields    : search_fields,
           
        };
        var url = "{{URL::to('download_customers')}}?" + $.param(obj);
        
        window.location = url;
        return; 
    });
	</script>
@endpush