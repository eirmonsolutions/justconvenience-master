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
						<form action="{{ route('send-contest-customer-email') }}" method="POST" id="send_email">
							@csrf
								<div class="row">
									<div class="col-md-12 customers-cnt">
										
											<strong class="label-txt">Contests</strong>
										    <select id="multiple-checkboxes" name="contest_id" required>
										    	@foreach ($contests as $key => $contest)
										        	<option value="{{ $contest->id }}">{{ $contest->title }}</option>
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