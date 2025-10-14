@extends('layouts.admin')

@section('content')

	<div class="page-breadcrumb">
	    <div class="row">
	         
	        <div class="col-9 align-self-center filter-form">
	            <!-- <form  action="{{ route('tickets') }}" class="filter-form"> -->
	            <div class="form-group width230 float-left">
	               <span> From</span><input type="text" class="date form-control" id="datatable_startdate"  name="start_date" placeholder="Start Date" autocomplete="off" value="{{ isset($params['start_date']) && !empty($params['start_date']) ? date('d M Y', strtotime($params['start_date'])) : '' }}">
	            </div>
	            <div class="form-group width230 float-left Ml20"><span>To</span>
	                <input type="text" class="date form-control"   id="datatable_enddate" name="end_date" placeholder="End Date" autocomplete="off" value="{{ isset($params['end_date']) && !empty($params['end_date']) ? date('d M Y', strtotime($params['end_date'])) : '' }}">
	            </div>
	            <div class="customize-input float-right">
	                <select class="custom-select custom-select-set form-control bg-white border-0 custom-shadow custom-radius" id="datatable_contest_id" name="contest_id">
	                    @if(sizeof($contests) > 0)
							<!-- <option value="">All</option> -->
							@foreach($contests as $key => $contest)
								<option value="{{ $contest->id }}" {{ (isset($params['contest_id']) && !empty($params['contest_id']) && $params['contest_id'] == $contest->id) ? "selected" : '' }}>{{ $contest->title }}</option>
							@endforeach
						@endif
	                </select>
	            </div>
	             <div class="customize-input float-right"><input type="submit" id="datatable_submit" name="" class="btn btn-primary filter-btn" value="Filter"></div>
	        <!-- </form> -->
	      
	        </div>
	        <div class="col-3 align-self-center text-right">
	        	@if(Auth::user()->user_role == 2)
		        	@if(sizeof($tickets) > 0)
		              <a href="javascript:void(0);" class="select_count_winner btn btn-primary" data-toggle="modal" data-target="#countWinnerModal"><i class="icon-trophy"></i> Escoge un Ganador!</a>
		            @endif
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
		                    <h4 class="card-title">Tickets </h4>
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
							<table id="tickets"  class="table no-wrap v-middle mb-0 dashboard-table responsive nowrap">
								<thead>
									<tr class="border-0">                                           
	                                    <th class="border-0 font-14 font-weight-medium text-muted">Ticket Number
	                                    </th>
	                                    <th class="border-0 font-14 font-weight-medium text-muted">Name</th>
	                                    <th class="border-0 font-14 font-weight-medium text-muted">
	                                        Last Name
	                                    </th>
	                                    <th class="border-0 font-14 font-weight-medium text-muted">
	                                        Email
	                                    </th>
	                                    <th class="border-0 font-14 font-weight-medium text-muted">Phone Number</th>
	                                    <th class="border-0 font-14 font-weight-medium text-muted">Id Number/ Ruc/ Passport</th>
	                                    <th class="border-0 font-14 font-weight-medium text-muted">Direction</th>
	                                </tr>
								</thead>
								<!-- <tbody>
									@foreach ($tickets as $key => $ticket) 
									<tr>
										<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $ticket->id }}</td>
										<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $ticket->customer->name }}</td>
										<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $ticket->customer->last_name }}</td>
										<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $ticket->customer->email }}</td>
										<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $ticket->customer->phone_number }}</td>
										<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $ticket->customer->indentification_card }}</td>
										<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $ticket->customer->direction }}</td>
									</tr>
									@endforeach 
								</tbody> -->
								
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="modal fade random-winners" id="countWinnerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Lucky Winners</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="{{ route('get-winners') }}" id="Login">
						<label for="exampleFormControlSelect1">Select Winners</label>
						<select class="form-control mb-3" name="winner_count" id="exampleFormControlSelect1">
							@if(sizeof($tickets) > 0)
								@foreach($tickets as $key => $values)
									@if($key < 10)
										<option value="{{ $key+1 }}">{{ $key+1 }}</option>
									@else
										break;
									@endif
								@endforeach
							@endif
						</select>

						<label for="exampleFormControlStartDate">Start Date</label>
						<input type="text" class="date form-control mb-3" name="start_date" placeholder="Start Date" autocomplete="off" value="{{ isset($params['start_date']) && !empty($params['start_date']) ? date('d M Y', strtotime($params['start_date'])) : '' }}">
						
						<label for="exampleFormControlSelect1">End Date</label>
						<input type="text" class="date form-control mb-3" name="end_date" placeholder="End Date" autocomplete="off" value="{{ isset($params['end_date']) && !empty($params['end_date']) ? date('d M Y', strtotime($params['end_date'])) : '' }}">
						
						<label for="exampleFormControlSelect2">Select Contest</label>
						<select class="form-control mb-3" name="contest_id" id="exampleFormControlSelect2">
	                    @if(sizeof($contests) > 0)
							<option value="">All</option>
							@foreach($contests as $key => $contest)
								<option value="{{ $contest->id }}" {{ (isset($params['contest_id']) && !empty($params['contest_id']) && $params['contest_id'] == $contest->id) ? "selected" : '' }}>{{ $contest->title }}</option>
							@endforeach
						@endif
	                </select>
						<button type="submit" class="btn btn-primary btn-view-all float-right">Winners</button>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection