@extends('layouts.admin')

@section('content')
	<div class="page-breadcrumb">
        <div class="row">
        	<div class="col-4 align-self-center">
        	
            </div>

            <div class="col-8 align-self-center">
                <a class="btn btn-primary float-right btn-view-all" href="{{ route('add-contest') }}" role="button"><i data-feather="plus" class="width15"></i> Add Contest</a>
            </div>
            
        </div>
    </div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card">
				    <div class="card-body">
				        <div class="mb-4">
				            <h4 class="card-title">Contests </h4>
				            
				            	<div id="button" class="datatable-btns"></div>
				            	<div class="col-3 float-right search-csv">
					            	<div class="customize-input">
					                    <input class="form-control custom-shadow custom-radius border-0 bg-white myInputTextField" type="text" placeholder="Search" aria-label="Search">
					                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search form-control-icon"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
					                </div>
				            	</div>
				            
				            <!-- <input type="text" class="myInputTextField"> -->
				        </div>
						<div class="table-responsive">
							<table id="contest"  class="table no-wrap v-middle mb-0 dashboard-table responsive nowrap">
								<thead>
									<tr class="border-0">
										<th class="border-0 font-14 font-weight-medium text-muted">Sr.</th>
										<th class="border-0 font-14 font-weight-medium text-muted">Title</th>
										<th class="border-0 font-14 font-weight-medium text-muted">Total # of Tickets</th>
										<th class="border-0 font-14 font-weight-medium text-muted">Total # of Customer Leads</th>
										<th class="border-0 font-14 font-weight-medium text-muted noExport">Featured Image</th>
										<th class="border-0 font-14 font-weight-medium text-muted" class="border-0 font-14 font-weight-medium text-muted">Start Date</th>
										<th class="border-0 font-14 font-weight-medium text-muted">End Date</th>
										<th class="border-0 font-14 font-weight-medium text-muted noExport">Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($contests as $key => $contest) 

										<?php
										$contest_str = 'upcoming';
										if($contest->status == 0 || $contest->end_date < date('Y-m-d'))
										{
											$contest_str = 'inactive';
										}
										else if($contest->start_date <= date('Y-m-d') && $contest->end_date >= date('Y-m-d') && $contest->status == 1)
										{
											$contest_str = 'running';
										}
										?>

									<tr>
										<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $key+1 }}</td>
										<td class="border-top-0 text-muted px-2 py-4 font-14">
											<a href="{{ route('contest-details', ['contest_id' => $contest->id])  }}" class="change-psd-btn">{{ $contest->title }} <span class="{{ $contest_str }} contest_status_span">({{ ucfirst($contest_str) }})</span></a>
										</td>
										<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $contest->invoice_details_count }}</td><td class="border-top-0 text-muted px-2 py-4 font-14">{{ $contest->users_count }}</td>
										<td class="border-top-0 text-muted px-2 py-4 font-14"><img style="max-width: 50px;" src="{{ url('/') . '/' . $contest->featured_image }}"></td>
										<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $contest->start_date }}</td>
										<td class="border-top-0 text-muted px-2 py-4 font-14">{{ $contest->end_date }}</td>
										<td class="border-top-0 px-2 py-4">
											<span class="switch-list switch">
												<input type="checkbox" data-id="{{ $contest->id }}" class="switch update_contest_status" id="switch-id-{{$contest->id}}" value="{{ $contest->status }}" {{ ($contest->status == 1) ? 'checked' : '' }}>
												<label for="switch-id-{{$contest->id}}"></label>
											</span>
											<a href="{{ route('edit-contest', ['contest_id' => $contest->id])  }}" class="change-psd-btn"><i class="fas fa-edit"></i></a>
											<a href="{{ route('customers', ['contest_id' => $contest->id])  }}" class="change-psd-btn px-2"><i class="fas fa-users"></i></a>
											<a href="{{ route('tickets', ['contest_id' => $contest->id])  }}" class="change-psd-btn px-2"><i data-feather="tag" class="feather-icon"></i></a></td>
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