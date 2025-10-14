@extends('layouts.admin')

@section('content')

	<div class="container-fluid">
		<div class="row">
		    <div class="col-12">
		        <div class="card">
		            <div class="card-body">
		                <div class="mb-4">
		                    <h4 class="card-title">Customer Tickets </h4>
		                    
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
								<tbody>
									@if(sizeof($tickets) > 0)
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
									@else
										<tr>
											<td colspan="7" class="text-center">No Tickets</td>
										</tr>
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