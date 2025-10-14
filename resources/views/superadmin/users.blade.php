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
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.dataTables.min.css">

	<link href="{{ asset('public/css/custom.css') }}" rel="stylesheet">

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
			background: #3ebc9f;
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
		.custom-color , .custom-color:hover{background:#3ebc9f;}

		#toast-container {position: fixed !important; z-index: 999999 !important; pointer-events: none !important; top: 0 !important; left: 0 !important; margin-left: 0 !important; background: rgba(0,0,0,.8) !important; height: 100% !important; right: 0 !important; }
		#toast-container>.toast-success{    position: absolute !important; top: 50% !important; left: 50% !important; margin-left: -150px !important;}
	</style>
</head>
<body>
	<div class="top_head_S">

		<img src="{{ asset('public/assets/images/logo.png') }}" alt="" class="img-fluid">

		<a href="{{ route('signout') }}" class="sign_out">Signout</a></div>
		<div class="container">
			<div class="row btn-outer">
				<div class="add-user-btn">
					<a href="{{ route('add-user') }}">Add User</a>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<table id="example" class="display table custom-table responsive nowrap" >
							<thead>
								<tr>
									<th>Sr. No.</th>
									<th>Name</th>
									<th>Email</th>
									<th>Joining Date</th>
									<th class='noExport'>Actions</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($users as $key => $user) 
								<tr>
									<td>{{ $key+1 }}</td>
									<td>{{ $user->name }} {{ isset($user->last_name)? $user->last_name: '' }}</td>
									<td>{{ $user->email }}</td>
									<td>{{ date('d/m/Y' ,strtotime($user->created_at)) }}</td>
									<td><a href="{{ route('delete-user', ['id' => $user->id])  }}" class="delete-btn" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a><a href="{{ route('change-password', ['id' => $user->id])  }}" class="change-psd-btn">Change Password</a></td>
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
			<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js"></script>

			<script>

				$(document).ready(function() {
					$('#example').DataTable( {
						dom: 'Bfrtip',
						buttons: [
						// 'csv', 'excel', 'pdf', 'print'
						{
		                    extend: 'excel',
		                    title: 'Data export',
		                    exportOptions: {
		                        columns: "thead th:not(.noExport)"
		                    }
		                }, {
		                    extend: 'csv',
		                    title: 'Data export',
		                    exportOptions: {
		                        columns: "thead th:not(.noExport)"
		                    }
		                }
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
