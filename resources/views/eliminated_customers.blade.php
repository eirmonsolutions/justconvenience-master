@extends('layouts.admin')

@section('content')
	<div class="page-breadcrumb">
	    <div class="row">
	        <!-- <div class="col-2 align-self-center">
	            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Filters</h4>
	        </div> -->
	        <div class="col-12 align-self-center">
	        	<form action="{{ route('eliminated-customers') }}" method="GET" id="login" class="filter-form">
	            <div class="form-group width230 float-left">
	               <span> From</span>
	               <input type="text" class="date form-control" name="start_date" placeholder="Start Date" autocomplete="off" value="{{ isset($params['start_date']) && !empty($params['start_date']) ? date('d M Y', strtotime($params['start_date'])) : '' }}">
	            </div>
	            <div class="form-group width230 float-left Ml20"><span>To</span>
	                <input type="text" class="date form-control" name="end_date" placeholder="End Date" autocomplete="off" value="{{ isset($params['end_date']) && !empty($params['end_date']) ? date('d M Y', strtotime($params['end_date'])) : '' }}">
	            </div>
	            <!-- <div class="form-group width230 float-left Ml20"><span>Count</span>
	                <input type="number" min="0" class="form-control" name="ticket_count" placeholder="Ticket Count" autocomplete="off" value="{{ isset($params['ticket_count']) && !empty($params['ticket_count']) ? $params['ticket_count'] : '' }}">
	            </div> -->
	            <div class="customize-input float-left Ml20">
	                <select class="custom-select custom-select-set form-control bg-white border-0 custom-shadow custom-radius" name="contest_id">
	                    @if(sizeof($contests) > 0)
							<!-- <option value="">All</option> -->
							@foreach($contests as $key => $contest)
								<option value="{{ $contest->id }}" {{ (isset($params['contest_id']) && !empty($params['contest_id']) && $params['contest_id'] == $contest->id) ? "selected" : '' }}>{{ $contest->title }}</option>
							@endforeach
						@endif
	                </select>
	            </div>
	            <div class="customize-input float-left Ml20 shops-dropdown">
	                <select class="custom-select custom-select-set form-control bg-white border-0 custom-shadow custom-radius select2" name="shop_id">
						@if(sizeof($shops) > 0)
							<option value="">All</option>
							@foreach($shops as $key => $shop)
								<option value="{{ $shop->id }}" {{ (isset($params['shop_id']) && !empty($params['shop_id']) && $params['shop_id'] == $shop->id) ? "selected" : '' }}>{{ $shop->shop_name }}{{ ', ' . $shop->contract_number }}{{ ', ' . $shop->shopping_center_id }}</option>
							@endforeach
						@endif
	                </select>
	            </div>
	            <div class="customize-input float-right">
	            	<input type="submit" class="btn btn-primary filter-btn" value="Filter">
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
				            <h4 class="card-title">Eliminated Customers Leads </h4>
				            <div id="button" class="datatable-btns"></div>
				            	<div class="col-3 float-right search-csv">
					            	<div class="customize-input">
					                    <input class="form-control custom-shadow custom-radius border-0 bg-white myInputTextField" type="text" placeholder="Search" aria-label="Search">
					                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search form-control-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
					                </div>
				            	</div>
				            
				        </div>
						<div class="table-responsive">
							<table id="eliminated_customers"  class="table no-wrap v-middle mb-0 dashboard-table responsive nowrap">
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
                                        <th class="border-0 font-14 font-weight-medium text-muted">Id Number/ Ruc/ Passport</th>
                                        <th class="border-0 font-14 font-weight-medium text-muted noExport">Invoice Images</th>
                                    </tr>
								</thead>
								<tbody>
                                	@if(sizeof($customers) > 0)
                                		@foreach ($customers as $key => $customer)
		                                   	<tr class="<?php if($customer->total_invoice_val > $data['max_value_limit']) echo 'text-danger'; ?>">
		                                   		<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $key+1 }}</td>
		                                        <td class="border-top-0 px-2 py-4">
		                                            <div class="d-flex no-block align-items-center">
		                                                <div class="mr-3"></div>
		                                                <div class="user-detail">
		                                                    <h5 class="text-dark mb-0 font-16 font-weight-medium">{{ $customer->name }} {{ $customer->last_name }}</h5>
		                                                </div>
		                                            </div>
		                                        </td>
		                                        <td class="border-top-0 text-muted px-2 py-4 font-14">{{ $customer->email }}</td>
		                                        <td class="border-top-0 text-muted px-2 py-4 font-14">{{ $customer->phone_number }}</td>
		                                        <td class="border-top-0 px-2 py-4 font-14">{{ $customer->direction }}
		                                        </td>
		                                        <td class="border-top-0 text-muted px-2 py-4 font-14"><span class="overflow-text" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ implode('||||', $customer->invoices->pluck('local')->toArray()) }}">{{ implode('||||', $customer->invoices->pluck('local')->toArray()) }}</span></td>

		                                        <?php 
		                                        	$invoice_str = '';
		                                        	$invoice_dates = $customer->invoices->pluck('invoice_date')->toArray();

		                                        	$totalCount = sizeof($invoice_dates);
		                                        	if($totalCount > 0)
		                                        	{
		                                        		foreach ($invoice_dates as $keyID => $valueID)
		                                        		{
		                                        			$invoice_str .= date('d M Y', strtotime($valueID));
		                                        			if (($totalCount - 1) > $keyID) 
		                                        			{
		                                        				$invoice_str .= '||||';
		                                        			}
		                                        		}
		                                        	}
		                                        ?>
		                                        <td class="border-top-0 text-muted px-2 py-4 font-14"><span class="overflow-text" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ $invoice_str }}">{{ $invoice_str }}</span></td>
		                                        <td
		                                            class="border-top-0 text-muted px-2 py-4 font-14 text-center">
		                                          {{ $customer->invoices->sum('invoice_amount') }}
		                                        </td>
		                                        <td class="border-top-0 text-muted px-2 py-4 font-14 text-center">{{ $customer->invoices->count() }}</td>
		                                        <td class="border-top-0 text-muted px-2 py-4 font-14">{{ $customer->indentification_card }}</td>
		                                        <td class="border-top-0 px-2 py-4">
		                                        	<?php 
		                                        		$invoices = $customer->invoices;
		                                        		$count = 0;
		                                        		if (sizeof($invoices) > 0) 
		                                        		{
		                                        			foreach ($invoices as $keyI => $valueI)
                                                            {
                                                                if ($count > 0)
                                                                {
                                                                    break;
                                                                }
                                                                if($valueI->attchment)
                                                                {
		                                        	?>
		                                        		<a href="javascript:void(0);" data-toggle="modal" data-target="#imgModel_<?php echo $customer->id ?>" data-backdrop="static" data-keyboard="false"><img src="{{ url('/') . '/' . $valueI->attchment }}" class="width30">
		                                        		</a>
		                                        	<?php
                                                                $count++;
                                                                }
                                                            }
		                                        		}
		                                        	?>

		                                        	<div class="modal fade invoice-popup" id="imgModel_<?php echo $customer->id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
													    <div class="modal-dialog" role="document">
													        <div class="modal-content">
													            <div class="modal-header">
													                <h5 class="modal-title" id="exampleModalLabel">Invoice Images</h5>
													                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
													                    <span aria-hidden="true">&times;</span>
													                </button>
													            </div>
													            <div class="modal-body">
													                <div id="carouselExampleControls_<?php echo $customer->id ?>" class="carousel slide" data-ride="carousel">
													                    <div class="carousel-inner">
																	  		<?php
																	  			$counter = 0;
									  											$invoices = $customer->invoices;
									  											if (sizeof($invoices) > 0) 
									  											{
									  												foreach ($invoices as $keyI => $valueI)
									  												{
									  													if($valueI->attchment)
                                                                                        {
                                                                                        	$counter++;
									  										?>
																		  		<div class="carousel-item <?php if($counter == 1) { echo 'active'; } ?>">
																		  		    <img src="{{ url('/') . '/' . $valueI->attchment }}">
																		  		</div>
																		  	<?php
																		  				}
																		  			}
																		  		}
																		  	?>
																		</div>
																		@if(sizeof($invoices) > 1)
																		    <a class="carousel-control-prev" href="#carouselExampleControls_<?php echo $customer->id ?>" role="button" data-slide="prev">
																		        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
																		        <span class="sr-only">Previous</span>
																		    </a>
																		    <a class="carousel-control-next" href="#carouselExampleControls_<?php echo $customer->id ?>" role="button" data-slide="next">
																		        <span class="carousel-control-next-icon" aria-hidden="true"></span>
																		        <span class="sr-only">Next</span>
																		    </a>
																		@endif
																	</div>
																</div>
													        </div>
													    </div>
													</div>
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
