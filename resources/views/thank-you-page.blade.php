<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-PVW9G7P');</script>
    <!-- End Google Tag Manager -->
    
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Thank You</title>

    <meta content="" name="descriptison">

    <meta content="" name="keywords">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('public/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/vendor/icofont/icofont.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <!-- Template Main CSS File -->
    <link href="{{ asset('public/css/style.css') }}" rel="stylesheet">
    <!--custom css -->
    <link href="{{ asset('public/css/custom.css') }}" rel="stylesheet">
    <!--custom css-->
    
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PVW9G7P"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <!-- ======= Top Bar ======= -->
    <div class="fixed-header">
        <div class="container">
            <div class="top-logo"> <a href="{{ url('/') }}"><img src="{{ isset($data['homeData']['logo']['primary_logo']) ? asset($data['homeData']['logo']['primary_logo']) : asset('public/img/v3logo.png') }}" alt="" class="img-fluid"></a></div>
        </div>
    </div>

    <!-- ======= Header ======= -->
    <!-- ======= Hero Section ======= -->
    <main id="main" class="home no_sp thankyou">

        <div class="container">
        <div class="thankyou-content">
            <div class="thank-image"><img src="{{ asset('public/img/thankyou-icon.png') }}" alt=""></div>
            <div class="gracias-txt">Gracias por registrar tu factura.</div>
            <div class="participando-txt">!Ya ESTAS participando, mucha suerte!</div>
            <p>Revisa Términos y Condiciones <a href="javascript:void(0);" data-toggle="modal" data-target="#exampleModal">AQUÍ </a></p>
            <p><a href="{{ url('/') }}">de vuelta a casa</a></p>
        </div>
    </div>
    </main>
    <!-- End #main -->


<!-- Modal -->
<div class="modal fade terms" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Términos y Condiciones</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @if(isset($data['tnc']) && !empty($data['tnc']))
            {!! $data['tnc'] !!}
        @else
            <h5>1. YOUR AGREEMENT</h5>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>

            <h5>2. YOUR AGREEMENT</h5>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>

            <h5>3. YOUR AGREEMENT</h5>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>

            <h5>4. YOUR AGREEMENT</h5>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
        @endif
      </div>
    </div>
  </div>
</div>



    <!-- Vendor JS Files -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ asset('public/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('public/vendor/jquery.easing/jquery.easing.min.js') }}"></script>
    <!-- Template Main JS File -->

</body>
</html>