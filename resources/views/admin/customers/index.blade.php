@extends('layouts.admin')

@section('content')

	<div class="page-breadcrumb">
	    <div class="row">
	         
	        <div class="col-9 align-self-center filter-form">
	            <!-- <form  action="" class="filter-form"> -->
	            <!-- <div class="form-group width230 float-left">
	               <span> From</span><input type="text" class="date form-control" id="datatable_startdate"  name="start_date" placeholder="Start Date" autocomplete="off" value="{{ isset($params['start_date']) && !empty($params['start_date']) ? date('d M Y', strtotime($params['start_date'])) : '' }}">
	            </div>
	            <div class="form-group width230 float-left Ml20"><span>To</span>
	                <input type="text" class="date form-control"   id="datatable_enddate" name="end_date" placeholder="End Date" autocomplete="off" value="{{ isset($params['end_date']) && !empty($params['end_date']) ? date('d M Y', strtotime($params['end_date'])) : '' }}">
	            </div>
	             <div class="customize-input float-right"><input type="submit" id="datatable_submit" name="" class="btn btn-primary filter-btn" value="Filter"></div> -->
	        <!-- </form> -->
	      
	        </div>
	        
	    </div>
	</div>

	<div class="container-fluid">
		<div class="row">
		    <div class="col-12">
		        <div class="card">
		            <div class="card-body">
		                <div class="mb-4">
		                    <h4 class="card-title">Customers </h4>
		                    <!-- <a id="download_all" href="javascript:void(0);" class="btn btn-primary float-right btn-view-all ml-2">Download All</a> -->
		                    @if(Auth::user()->user_role == 2)
		                    	<div id="button" class="datatable-btns"></div>
		                    @endif

				            	<div class="col-3 float-right search-csv">
					            	<div class="customize-input">
					                    <input class="form-control custom-shadow custom-radius border-0 bg-white myInputTextField" id="myInputTextField" type="text" placeholder="Search" aria-label="Search">
					                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search form-control-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
					                </div>
				            	</div>
		                    
		                </div>
						<div class="table-responsive">
							<table id="customers"  class="table no-wrap v-middle mb-0 dashboard-table responsive nowrap">
								<thead>
									<tr class="border-0">                                           
	                                    <!-- <th class="border-0 font-14 font-weight-medium text-muted">Sr. No.
	                                    </th> -->
	                                    <th class="border-0 font-14 font-weight-medium text-muted">Name</th>
	                                    <th class="border-0 font-14 font-weight-medium text-muted">
	                                        Email
	                                    </th>
	                                    <th class="border-0 font-14 font-weight-medium text-muted">Phone Number</th>
	                                    <th class="border-0 font-14 font-weight-medium text-muted">City</th>
	                                    <th class="border-0 font-14 font-weight-medium text-muted">State</th>
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
		#download_all{padding: 8px 20px;margin-top: 0;}
		@media (max-width: 767px) {#download_all{padding: 6px 12px; margin-top: 0; font-size: 13px; margin-left: 5px !important;}}
	</style>
@endsection

@push('scripts')
	<script>
		$(document).ready(function()
		{
			customersTable = $('#customers').DataTable({ 
			    processing: true,
			    serverSide: true, 
			    searching: true,
			    language: {
			        processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
			        search: ''
			    },
			    dom: 'Blfrtip',
			    buttons: [
			    {
			        extend: 'excel',
			        title: 'Customers Excel',
			        exportOptions: {
			            columns: "thead th:not(.noExport)"
			        }
			    }, {
			        extend: 'csv',
			        title: 'Customers Csv',
			        exportOptions: {
			            columns: "thead th:not(.noExport)"
			        }
			    }
			    ],
			    buttons: [
			    'csv', 'excel', 'pdf'
			    ],
			    ajax: {
			        url: "{{ url('get_customers') }}",
			        type: 'GET',
			        data: function (d) {
			            d.start_date = $('#datatable_startdate').val();
			            d.end_date = $('#datatable_enddate').val();
			            d.search_fields = $('#myInputTextField').val();
			        },
			    }, 
			    columns: [
			    // { data: 'id', name: 'id', class:'border-top-0 text-muted px-2 py-4 font-14' },
			    { data: 'name', name: 'name', class:'border-top-0 text-muted px-2 py-4 font-14'  }, 
			    { data: 'email', email: 'email', class:'border-top-0 text-muted px-2 py-4 font-14'  }, 
			    { data: 'phone_number', name: 'phone_number', class:'border-top-0 text-muted px-2 py-4 font-14'  }, 
			    { data: 'city', name: 'city', class:'border-top-0 text-muted px-2 py-4 font-14'  },
			    { data: 'state', name: 'state', class:'border-top-0 text-muted px-2 py-4 font-14'  },
			    { data: 'action', name: 'action', class:'border-top-0 text-muted px-2 py-4 font-14'  },
			    ], 
			});

			$('.myInputTextField').keyup(function(){
			    customersTable.search($(this).val()).draw() ;
			});


			$(document.body).on("click","#datatable_submit", function(){
			    $('#customers').DataTable().draw(true);
			});

			/*var buttons = new $.fn.dataTable.Buttons(customersTable, {
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
			}).container().appendTo($('#button'));*/

			$(document).on("click", "#download_all", function(){
			    var start_date = $("#datatable_startdate").val();
			    var end_date = $("#datatable_enddate").val();
			    var contest_id = $("#datatable_contest_id").val();
			    console.log(start_date);
			    console.log(end_date);
			    console.log(contest_id);

			    var obj = {
			        start_date    : start_date,
			        end_date    : end_date
			    };
			    var url = "{{URL::to('download-customer-excel')}}?" + $.param(obj)

			    window.location = url;
			    return;
			    $.ajax({
		            url:"{{ url('') }}/download-customer-excel",
		            method:"GET",
		            success:function(data)
		            {
		            }
		        }).fail(function (jqXHR, textStatus, error) {
		            
		        });
			});
	    });
	</script>
@endpush