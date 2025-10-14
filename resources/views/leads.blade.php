<!DOCTYPE html> 
<head>
	 <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>::::</title>
  <meta content="" name="descriptison">
  <meta content="" name="keywords">
	<link href="{{ asset('public/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
	
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">

	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">



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

@media(max-width:767px) {
	.top_head_S img {
	max-width: 160px;
	margin: 0 20px;
	
}
}
</style>
</head>
<body>
<div class="top_head_S">

<img src="{{ asset('public/img/v3logo.png') }}" alt="" class="img-fluid">

	<a href="{{ route('signout') }}" class="sign_out">Signout</a></div>
	<div class="container">
		<div class="row">
			<form action="{{ route('leads') }}" id="login" class="form_width">

				<div class="col-lg-5 col-md-5 float-left">
				<label>Select Invoice Date</label>
				<select name="date" class="form-control">
					<option value="">All</option>
					<option {{ (isset($params['date']) && !empty($params['date']) && $params['date'] == 'today') ? "selected" : '' }} value="today">Today</option>
					<option {{ (isset($params['date']) && !empty($params['date']) && $params['date'] == 'this_week') ? "selected" : '' }} value="this_week">This Week</option>
					<option {{ (isset($params['date']) && !empty($params['date']) && $params['date'] == 'past_week') ? "selected" : '' }} value="past_week">Past Week</option>
				</select>
</div>
<div class="col-lg-5 col-md-5 float-left">

				<label>Minimum Invoice Value</label>
				<input value="{{ isset($params['total_invoice_val']) && !empty($params['total_invoice_val']) ? $params['total_invoice_val'] : '' }}" type="number" name="total_invoice_val" class="form-control">

				
			</div>
<div class="col-lg-2 col-md-2 float-left">
	<label>&nbsp; </label>
<input type="submit" value="Apply Filter" name="Apply Filter" class="btn btn-primary custom-color"/>
</div>


			</form>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
				<table id="example" class="display" class="table">
					<thead>
						<tr>
							<th>No Señor.</th>
							<th>IMAGEN</th>
							<th>NOMBRE</th>
							<th>APELLIDO</th>
							<th>E-MAIL</th>
							<th>NÚMERO DE TELÉFONO</th>
							<th>DIRECCION</th>
							<th>NO. FACTURA</th>
							<th>LOCAL</th>
							<th>FECHA FACTURA</th>
							<th>VALOR TOTAL FACTURA</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($leads as $key => $lead) 
						<tr>
							<td>{{ $key+1 }}</td>
							<td><img class="img-thumbnail" width="70px" src="{{ url('/') . '/' . $lead->attchment }}"/></td>
							<td>{{ $lead->name }}</td>
							<td>{{ $lead->last_name }}</td>
							<td>{{ $lead->email }}</td>
							<td>{{ $lead->phone_number }}</td>
							<td>{{ $lead->direction }}</td>
							<td>{{ $lead->num_bill }}</td>
							<td>{{ $lead->local }}</td>
							<td>{{ date('d/m/Y' ,strtotime($lead->invoice_date)) }}</td>
							<td>{{ $lead->total_invoice_val }}</td>
						</tr>
						@endforeach 
					</tbody>
					
				</table>
			</div>
			</div>
		</div></div>
		<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
		<script>
			/*$(document).ready(function() {
				$('#example').DataTable( {
					order: [[2, 'asc']],
					rowGroup: {
						dataSrc: 2
					},
					buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
				} );
			} );*/

			$(document).ready(function() {
			    $('#example').DataTable( {
			        dom: 'Bfrtip',
			        buttons: [
			            'csv', 'excel'
			        ]
			    } );
			} );
		</script>

		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>


		@if(Session::get('class') == 'success')
		   <script>toastr.success('{{ Session::get("message") }}');</script>
		@elseif(Session::get('class') == 'danger')
		   <script>toastr.error('{{ Session::get("message") }}');</script>
		@endif
	</body>
	</html>
