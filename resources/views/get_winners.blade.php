@extends('layouts.admin')

@section('content')
	<div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 align-self-center">
            	<form action="{{ route('send_winner_email') }}" method="POST" id="login" class="form_width winner-page">
            		<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
            		<div class="float-right">
            			@foreach($ids as $key => $id)
            				<input type="hidden" name="ticketIds[]" value="{{ $id }}">
            			@endforeach
            		
            			<input type="submit" value="Send mail" name="Send Mail" class="btn btn-primary custom-color float-right"/>
            		</div>
            	</form>
            </div>
            
        </div>
    </div>
	<div class="container-fluid">
		<div class="row">
		    <div class="col-12">
		        <div class="card">
		            <div class="card-body">
		                <div class="mb-4">
		                    <h4 class="card-title">Winners </h4>
		                    
		                </div>
						<div class="table-responsive">
						<table id="example" class="table no-wrap v-middle mb-0 dashboard-table responsive nowrap">
							<thead>
								<tr class="border-0">
									<th class="border-0 font-14 font-weight-medium text-muted">Sr. No.</th>
									<th class="border-0 font-14 font-weight-medium text-muted">Ticket Number</th>
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
								@foreach ($invoiceData as $key => $ticket) 
								<tr>
									<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $key+1 }}</td>
									<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $ticket->id }}</td>
									<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $ticket->user->name }}</td>
									<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $ticket->user->last_name }}</td>
									<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $ticket->user->email }}</td>
									<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $ticket->user->phone_number }}</td>
									<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $ticket->user->indentification_card }}</td>
									<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $ticket->user->direction }}</td>
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