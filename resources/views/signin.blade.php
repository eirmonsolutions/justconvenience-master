<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon.png') }}">
    <title>{{ config('app.name', 'Quicentro Shopping') }}</title>

    <!-- Custom CSS -->
    <link href="{{ asset('dist/css/style.css') }}" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <!--custom css-->
</head>

<body>

    <div class="main-wrapper">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class="preloader">
            <div class="lds-ripple">
                <div class="lds-pos"></div>
                <div class="lds-pos"></div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
        <div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative">
            <div class="auth-box row">
                <div class="col-lg-12 col-md-12 bg-white">
                    <div class="login-page login-form form_v">
                        <div class="text-center">
                            <img src="{{ asset('assets/images/logo.png') }}" alt="Quicentro" class="login-logo">
                        </div>
                       <!--  <h2 class="mt-3 text-center">Admin Panel</h2>
                        <p class="text-center">Please enter your email and password</p> -->
                        <div class="mt-4">
                            <div class="row">
                                   
                                <form action="{{ route('signin') }}" method="POST" id="Login">
                                    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />

                                    <div class="col-lg-12 col-md-12 floating-label-wrap">
                                         <input name="email" type="email" id="inputEmail" placeholder="Email Address" class="floating-label-field floating-label-field--s1 name" required value="{{ old('email') }}">
                                            <label for="" class="floating-label">Email Address</label>
                                        </div>

                                        <div class="col-lg-12 col-md-12 floating-label-wrap">
                                            <input type="password" name="password"  id="inputPassword" placeholder="Password" class="floating-label-field floating-label-field--s1 name" required autocomplete="off">
                                            <label for="" class="floating-label">Password</label>
                                        </div>

                                         <div class="col-lg-12 col-md-12 floating-label-wrap">
                                                 <button type="submit" class="btn btn-primary login-btn">Login</button>
                                         </div>
                                   
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
    </div>

    <!-- ======= Login Section ======= -->



    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script>
        $(".preloader ").fadeOut();
    </script>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>


    @if(Session::get('class') == 'success')
    <script>toastr.success('{{ Session::get("message") }}');</script>
    @elseif(Session::get('class') == 'danger')
    <script>toastr.error('{{ Session::get("message") }}');</script>
    @endif



</body>

</html>