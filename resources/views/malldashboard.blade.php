<!DOCTYPE html> 
<head>
	<meta charset="utf-8">

	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" />


	<title>::::</title>
	<meta content="" name="descriptison">
	<meta content="" name="keywords">
	<link href="{{ asset('public/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">

	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.dataTables.min.css">

	<link href="{{ asset('public/css/custom.css') }}" rel="stylesheet">
	<link href="{{ asset('public/css/bootstrap-datepicker.css') }}" rel="stylesheet">

	<style type="text/css">
		#login.form_width {
			width: 100%;
			float: left;
			margin: 2rem 0;
		}
		.form_width label {
			display: block;
			font-size: 15px;
			font-weight: bold;
			margin-bottom: 1rem;
		}

		.top_head_S {
			background: #028499;
			height: 90px;
			position: relative;
			width: 100%;
			margin-bottom: 15px;
		}
		.top_head_S img {
			max-width: 180px;
			margin: 0 auto;
			display: block;
			position: relative;
			top: 12px;
		}
		a.sign_out {
			position: absolute;
			right: 30px;
			color: #333;
			top: 50%;
			transform: translateY(-50%);
			font-size: 15px;
			font-weight: bold;
			background: #fff;
			padding: 10px 20px;

		}
		a.sign_out:hover{text-decoration:none;}
		.custom-color , .custom-color:hover{background:#028499;}

		#toast-container {position: fixed !important; z-index: 999999 !important; pointer-events: none !important; top: 0 !important; left: 0 !important; margin-left: 0 !important; background: rgba(0,0,0,.8) !important; height: 100% !important; right: 0 !important; }
		#toast-container>.toast-success{    position: absolute !important; top: 50% !important; left: 50% !important; margin-left: -150px !important;}
	</style>
</head>
<body>
	
<div class="header">
	<div class="container">
		<nav class="navbar navbar-expand-lg navbar-light">
			  <a class="navbar-brand logo" href="javascript:void(0)" ><img src="{{ asset('public/img/v3logo.png') }}" alt="" class="img-fluid"></a>
			  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
			    <span class="navbar-toggler-icon"></span>
			  </button>
			  <div class="collapse navbar-collapse" id="navbarNav">
			    <ul class="navbar-nav ">
			      <li class="nav-item ">
			        <a class="nav-link" href="{{ route('users') }}">Users <span class="sr-only">(current)</span></a>
			      </li>
			      <li class="nav-item active">
			        <a class="nav-link" href="{{ route('malls') }}">Malls</a>
			      </li>
			      <li class="nav-item ml-auto">
			        <a class="nav-link logout" href="{{ route('signout') }}">Signout</a>
			      </li>
			    </ul>
			  </div>
			</nav>
		</div>
</div>
	<!-- <div class="top_head_S">
		<a href="" class="sign_out"></a></div> -->

		<div class="container">
			<div class="row">
				 <div class="col-6 align-self-center filter-form">
		            <form  action="{{ route('malls') }}" class="filter-form">
		            <div class="form-group width230 float-left">
		               <span> Date</span><input type="text" class="date form-control" id="datatable_startdate"  name="chosen_date" placeholder="Select Date" autocomplete="off" value="{{ isset($params['chosen_date']) && !empty($params['chosen_date']) ? date('d M Y', strtotime($params['chosen_date'])) : '' }}">
		            </div>
		             <div class="customize-input float-left inputsubmit"><input type="submit"  name="" class="btn btn-primary filter-btn" value="Submit"></div>
		        </form>
	        </div>

	        <div class="col-6 align-self-right in-out-txt">
	        	<div class="fecha-txt">Fecha Inicio: <span>{{ isset($params['start_date']) && !empty($params['start_date']) ? date('d-M-Y', strtotime($params['start_date'])) : '' }}</span></div>
	        	<div class="fecha-txt">Fecha In: <span>{{ isset($params['end_date']) && !empty($params['end_date']) ? date('d-M-Y', strtotime($params['end_date'])) : '' }}</span></div>
	        </div>
						
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<table id="example" class="display table custom-table responsive nowrap dataTable no-footer dtr-inline" >
							<thead>
								<tr>
									<th>Centro Comercial</th>
									<th>No. Clients</th>
									<th>No. Facturas</th>
									<th>Fact Promedio</th>
									<th>Monto Total</th>
									<th>Comparativo Clients Vs Semana Anterior</th>
									<th>Comparativo Facturas Vs Semana Anterior</th>
									<th>Comparativo FAct Promedio Vs Semana Anterior</th>
									<th>Top 10 Locales Según Numero De Facturas Registradas</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($data as $key => $malldata) 
								<tr>
									<td>{{ $malldata['mall_name'] }}</td>
									<td>{{ isset($malldata['current_week_client']) ? $malldata['current_week_client'] : '' }}</td>
									<td>{{ isset($malldata['current_week_invoices']) ? $malldata['current_week_invoices'] : '' }}</td>
									<td>$ {{ isset($malldata['current_week_avg_amount']) ? $malldata['current_week_avg_amount'] : '' }}</td>
									<td>$ {{ isset($malldata['current_week_total_amount']) ? $malldata['current_week_total_amount'] : '' }}</td>
									<td>
										<div class="progress">
  											<div class="progress-bar" role="progressbar" style="width: {{ isset($malldata['client_ratio']) ? $malldata['client_ratio'] : 0 }}%;" aria-valuenow="{{ isset($malldata['client_ratio']) ? $malldata['client_ratio'] : 0 }}" aria-valuemin="0" aria-valuemax="{{ isset($malldata['client_ratio']) ? $malldata['client_ratio'] : 0 }}"></div>
  											<div class="progress-bar-title">{{ isset($malldata['client_ratio']) ? $malldata['client_ratio'] : 0 }}%</div>
										</div>
									</td>
									<td>
										<div class="progress"><div class="progress-bar" role="progressbar" style="width: {{ isset($malldata['invoices_ratio']) ? $malldata['invoices_ratio'] : 0 }}%;" aria-valuenow="{{ isset($malldata['invoices_ratio']) ? $malldata['invoices_ratio'] : 0 }}" aria-valuemin="0" aria-valuemax="{{ isset($malldata['invoices_ratio']) ? $malldata['invoices_ratio'] : 0 }}"></div>
										<div class="progress-bar-title">{{ isset($malldata['invoices_ratio']) ? $malldata['invoices_ratio'] : 0 }}%</div>
									</div>
									</td>
									<td>
										<div class="progress"><div class="progress-bar" role="progressbar" style="width: {{ isset($malldata['avg_amount_ratio']) ? $malldata['avg_amount_ratio'] : 0 }}%;" aria-valuenow="{{ isset($malldata['avg_amount_ratio']) ? $malldata['avg_amount_ratio'] : 0 }}" aria-valuemin="0" aria-valuemax="{{ isset($malldata['avg_amount_ratio']) ? $malldata['avg_amount_ratio'] : 0 }}" ></div>
										<div class="progress-bar-title">{{ isset($malldata['avg_amount_ratio']) ? $malldata['avg_amount_ratio'] : 0 }}%</div>
									</div>
									</td>
									<td>{{ isset($malldata['top_ten_shop']) ? $malldata['top_ten_shop'] : '' }}</td>

								</tr>
								@endforeach 
							</tbody>

						</table>
					</div>
				</div>
			</div></div>
			<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
			<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
			<!-- <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script> -->
			<!-- <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script> -->
			<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script> -->
			<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script> -->
			<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script> -->
			<!-- <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script> -->
			<!-- <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script> -->
			<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js"></script>

			<script>

				$(document).ready(function() {
					$('#example1').DataTable( {
						dom: 'Bfrtip',
						// buttons: [
						// // 'csv', 'excel', 'pdf', 'print'
						// {
		    //                 extend: 'excel',
		    //                 title: 'Data export',
		    //                 exportOptions: {
		    //                     columns: "thead th:not(.noExport)"
		    //                 }
		    //             }, {
		    //                 extend: 'csv',
		    //                 title: 'Data export',
		    //                 exportOptions: {
		    //                     columns: "thead th:not(.noExport)"
		    //                 }
		    //             }
						// ]
					} );
				} );
			</script>

			<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
				<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
			<script src="{{ asset('public/js/bootstrap-datepicker.js') }}"></script>
			<script type="text/javascript">
		     $('body').on('focus',".date", function(){
		         $(this).datepicker({
		             format: 'dd M yyyy',
		             todayHighlight:'TRUE',
		             autoclose: true,
		             orientation: "bottom"
		         });
		     });
		   </script>

			@if(Session::get('class') == 'success')
			   <script>toastr.success('{{ Session::get("message") }}');</script>
			@elseif(Session::get('class') == 'danger')
			   <script>toastr.error('{{ Session::get("message") }}');</script>
			@endif
		</body>
		</html>
