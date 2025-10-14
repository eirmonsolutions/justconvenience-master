<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>::::</title>
  <meta content="" name="descriptison">
  <meta content="" name="keywords">


  <!-- Vendor CSS Files -->

  <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

  <link href="{{ asset('vendor/icofont/icofont.min.css') }}" rel="stylesheet">

  <link href="{{ asset('vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">



    <!-- Template Main CSS File -->

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">


<!--custom css -->
 <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

 <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
<!--custom css-->
</head>

<body class="login">




  <!-- ======= Login Section ======= -->
 
<div class="container">
    <div class="login-form">
        <div class="login-main-div">
            <div class="panel">
           <div class="login-heading">Change Password</div>
           </div>
           <form action="{{ route('change-password', ['id' => $id]) }}" method="POST" id="Login">
            @csrf

                <div class="form-group">
                    <input name="password" type="password" class="form-control" id="inputPassword" placeholder="Password" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
          </div>
          <div class="back-btn-outer">
               <a style="color: #000;" href="{{ route('users') }}">Back to user list</a>
           </div>
    </div>
</div>



  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

  <script src="{{ asset('vendor/jquery.easing/jquery.easing.min.js') }}"></script>
  <!-- Template Main JS File -->
  
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>


  @if(Session::get('class') == 'success')
     <script>toastr.success('{{ Session::get("message") }}');</script>
  @elseif(Session::get('class') == 'danger')
     <script>toastr.error('{{ Session::get("message") }}');</script>
  @endif



</body>

</html>