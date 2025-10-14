@extends('layouts.admin')

@section('content')

	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card">
				    <div class="card-body">
				        <div class="mb-4">
				            <h4 class="card-title">Email </h4>
				            
				        </div>
						<form action="{{ route('send-customer-email') }}" method="POST" id="send_email">
							<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
								<div class="row">
									<div class="col-md-12 customers-cnt">
										
											<strong class="label-txt">Customers</strong>
										    <select id="multiple-checkboxes" multiple="multiple" name="customers[]" required>
										    	@foreach ($customers as $key => $customer)
										        	<option value="{{ $customer->id }}">{{ $customer->name }}</option>
										        @endforeach
										    </select>
										
									</div>
									<div class="col-md-12">
										<strong class="label-txt">Message</strong>
										<textarea class="form-control mail_message_box" name="message" required></textarea>	
										<button type="submit" class="btn btn-primary send-mail-btn float-right">Send Mail</button>
									</div>
								</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection