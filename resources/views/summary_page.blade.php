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
    <title>::::</title>
    <meta content="" name="descriptison">
    <meta content="" name="keywords">
    <!-- Vendor CSS Files -->

    <link href="{{ asset('public/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/vendor/icofont/icofont.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/css/bootstrap-datepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('public/css/select2.min.css') }}" rel="stylesheet">
    <!-- Template Main CSS File -->

    <link href="{{ asset('public/css/style.css') }}" rel="stylesheet">
    <!--custom css -->

    <link href="{{ asset('public/css/custom.css') }}" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <!--custom css-->
 
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PVW9G7P"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <div class="loader-outer d-none">
        <div class="loader1">
            <div class="bar1"></div>
        </div>
    </div>

    <div class="content"></div>
    <!-- ======= Top Bar ======= -->
    <div class="fixed-header">
        <div class="container">
            <div class="top-logo">
                <a href="{{ url('/') }}"><img src="{{ isset($data['homeData']['logo']['primary_logo']) ? asset($data['homeData']['logo']['primary_logo']) : asset('public/img/v3logo.png') }}" alt="" class="img-fluid"></a>
            </div>
        </div>
    </div>
        <!-- ======= Header ======= -->
      <!--   <div class="clear90"></div> -->
        <!-- ======= Hero Section ======= -->
        <main id="main" class="home no_sp">
            <div class="preview-page">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-xs-12 mobile-half">
                                    <div class="heading">Summary</div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 mobile-half">
                                    <input type="button" value="+ Add bill" class="btn btn-default add addp-btn" align="center" data-toggle="modal" data-target="#exampleModal">
                                </div>
                            </div>
                            <div class="row preview-data">
                                <div class="col-lg-12 col-md-12 form-heading">1. INFORMACIÓN DEL CLIENTE</div>
                                <div class="col-lg-6 col-md-6 col-xs-12">
                                    <label>NOMBRE:</label> {{ $customer->name }}
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12">
                                    <label>APELLIDO:</label> {{ $customer->last_name }}
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12">
                                    <label>CEDULA / PASAPORTE / RUC:</label> {{ $customer->indentification_card }}
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12">
                                    <label>CELULAR:</label> {{ $customer->phone_number }}
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12">
                                    <label>CORREO:</label> {{ $customer->email }}
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12">
                                    <label>DIRECCIÓN:</label> {{ $customer->direction }}
                                </div>
                            </div>
                            <div class="row preview-data">
                                
                                <div class="col-lg-12 col-md-12 form-heading">2. DETALLE POR FACTURA</div>

                                <?php 
                                    $bill_number = 1;
                                    $invoices = $customer->invoices;
                                    if (sizeof($invoices) > 0) 
                                    {
                                        foreach ($invoices as $keyI => $valueI)
                                        {
                                ?>
                                    <div class="repeat-bill row">
                                        <div class="col-lg-12 col-md-12 bill-txt">Bill {{ $keyI+1 }}</div>
                                        <div class="col-lg-6 col-md-6 col-xs-12">
                                            <label>LOCAL:</label> {{ $valueI->local }}
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12">
                                            <label>MONTO FACTURA:</label> {{ $valueI->invoice_amount }}
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12">
                                            <label>NO. FACTURA:</label> {{ $valueI->num_bill }}
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12">
                                            <label>FECHA(DD / MM / AAAA):</label> {{ date('d M Y', strtotime($valueI->invoice_date)) }}
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12">
                                            <label>FORMA DE PAGO:</label> {{ $valueI->payment_method }}
                                        </div>
                                    </div>
                                <?php
                                        $bill_number = $keyI + 1;
                                        }
                                    }
                                ?>
                                <div class="col-lg-6 col-md-6 col-xs-12">
                                    <label>TOTAL BILL:</label> ${{ number_format($customer->invoices->sum('invoice_amount'), 2)}}
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12">
                                    <form action="{{ route('final-form-submission') }}" method="POST">
                                        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                        <input type="submit" name="SUBMIT" class="submit-btn">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!-- End #main -->
        <!-- Modal -->
        <div class="modal fade summary-pop" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add New Bill</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-lg-12 col-md-12 form_v">
                            <form class="main_form" action="{{ route('bill-form-submission') }}" method="POST" id="login" enctype="multipart/form-data">
                                <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 {{ (isset($data['image_upload']) && $data['image_upload'] == 1) ? '' : 'd-none' }}">

                                        <div class="bottom-filled">
                                            <p>ADJUNTA FOTO DE TU FACTURA</p>
                                            <div class="upload-btn-wrapper">
                                                <button class="btn">SUBIR</button>
                                                <input class="image_file" type="file" name="attchment" accept="image/*" />
                                            </div>
                                        </div>
                                    </div>
                                     <div class="col-lg-12 col-md-12">
                                        <div class="secondary_row w-100 row">
                                        <div class="col-lg-6 col-md-6 floating-label-wrap">
                                            <input type="hidden" name="API_local" class="local">
                                            <!-- <input type="text" name="local" placeholder="LOCAL" class="floating-label-field floating-label-field--s1 local" required> -->
                                            <label for="" class="floating-label local-txt">LOCAL COMERCIAL</label>
                                            <select class="form-control select2 local select_val" name="shop_id" required>
                                                @foreach ($shops as $key => $shop)
                                                    <option value="{{ $shop->id }}">{{ $shop->shop_name }}{{ ', ' . $shop->contract_number }}{{ ', ' . $shop->shopping_center_id }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-6 col-md-6 floating-label-wrap">
                                            <input type="hidden" name="API_invoice_amount" class="amount">
                                            <input type="number" step="0.01" class="amount floating-label-field floating-label-field--s1" name="invoice_amount" placeholder="MONTO FACTURA (INCLUÍDO IVA)" required onkeypress="limitKeypress(event, this.value, 7)">
                                            <label for="" class="floating-label">MONTO FACTURA (INCLUÍDO IVA)</label>
                                        </div>

                                        <div class="col-lg-6 col-md-6 floating-label-wrap">
                                            <input type="hidden" name="API_num_bill" class="num_bill">
                                            <input type="number" class="num_bill floating-label-field floating-label-field--s1" name="num_bill" placeholder="NO. FACTURA (6 ÚLTIMOS DÍGITOS)" required onkeypress="limitKeypress(event, this.value, 6)">
                                            <label for="" class="floating-label">NO. FACTURA (6 ÚLTIMOS DÍGITOS)</label>
                                        </div>
                                        <div class="col-lg-6 col-md-6 floating-label-wrap">
                                            <input type="hidden" name="API_invoice_date" class="date">
                                            <input type="text" class="date floating-label-field floating-label-field--s1" name="invoice_date" placeholder="FECHA FACTURA (DD / MM / AAAA)" required autocomplete="off">
                                            <label for="" class="floating-label">FECHA FACTURA (DD / MM / AAAA)</label>
                                        </div>
                                        <div class="col-lg-12 de-pago">
                                            <label>FORMA DE PAGO:</label>
                                            <div class="form-check terms-check">
                                                <input type="radio" class="form-check-input" id="EFECTIVO" name="payment_method" value="EFECTIVO" required>
                                                <label for="EFECTIVO" class="form-check-label">EFECTIVO</label>
                                            </div>
                                            <div class="form-check terms-check">
                                                <input type="radio" class="form-check-input" id="TARJETA" name="payment_method" value="TARJETA CRÉDITO" required>
                                                <label for="TARJETA" class="form-check-label" for="first2" name="card">TARJETA CRÉDITO</label>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 text-center">
                                            <input type="submit" value="¡REGISTRATE!" name="go" class="button"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ======= Footer ======= -->
        <footer id="footer">
            <div class="f-top white_Color">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 float-left" >
                            <div class="footer-info">
                                <h4>UBICACIÓN</h4>
                                <p>
                                    Avenida Naciones Unidas <br> entre Avenida 6 de Dicie,bre y <br>Avenida De Los Shyris.<br> Quito, Ecuador <br>+59322464526
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 float-left f-logo"> <img src="{{ isset($data['homeData']['logo']['secondary_logo']) ? asset($data['homeData']['logo']['secondary_logo']) : asset('public/img/footerlogo.png') }}" class="img-fluid"> </div>
                        <div class="col-lg-4 col-md-4 float-left">
                            <h4>AVISO LEGAL</h4>
                            <ul>
                                <li> <a href="#">+59322464526</a></li>
                                <li> <a href="#">example@quicentro.com</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- End Footer -->
        <a href="#" class="back-to-top"><i class="icofont-simple-up top_up"></i></a>
        
    <!--   <script src="vendor/jquery/jquery.min.js"></script> -->

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script src="{{ asset('public/js/bootstrap-datepicker.js') }}"></script>

    <script src="{{ asset('public/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('public/vendor/jquery.easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('public/js/jquery.repeatable.js') }}"></script>
    <script src="{{ asset('public/js/select2.min.js') }}"></script>


    <!-- Template Main JS File -->


    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>


    @if(Session::get('class') == 'success')
       <script>toastr.success('{{ Session::get("message") }}');</script>
    @elseif(Session::get('class') == 'danger')
       <script>toastr.error('{{ Session::get("message") }}');</script>
    @endif
        <script>

            $( document ).ready(function() {
                $('.select2').select2();
            });

            var start_date = '<?php echo date('d M Y', strtotime($contest->start_date)) ?>';
            console.log(start_date);
            $('body').on('focus',".date", function(){
                $(this).datepicker({
                    format: 'dd M yyyy',
                    todayHighlight:'TRUE',
                    autoclose: true,
                    startDate: start_date,
                    endDate: new Date(),
                    orientation: "bottom"
                });
            });

            $(document).on('blur', ".amount", function () {
                $(this).val(parseFloat($(this).val()).toFixed(2));
            });

            function limitKeypress(event, value, maxLength) {
                if (value != undefined && value.toString().length >= maxLength) {
                    event.preventDefault();
                }
            }

            $(document).on('change', '.image_file', function() {
                var filePath = $(this).val();
                var data = new FormData();
                data.append('attchment', $(this).prop('files')[0]);
                // $('.secondary_row').removeClass('d-none');

                // $('.loader-outer').removeClass('d-none');
                
                /*setTimeout(RemoveClass, 30000);
                function RemoveClass() {
                    $('.loader-outer').addClass("d-none");
                }*/
                /*event.preventDefault();*/
                /*$.ajax({
                    url:"{{ route('image-upload') }}",
                    method:"POST",
                    data: data,
                    dataType:'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success:function(data)
                    {
                        if(data.status == 1)
                        {
                            $('.local').val(data.result.local);
                            $('.num_bill').val(data.result.invoice_num);
                            $('.date').val(data.result.invoice_date);

                            $(".select_val").val(data.result.local).change();

                            if(data.result.total > 0)
                            {
                                var Total = parseFloat(data.result.total);
                                $('.amount').val(Total.toFixed(2));
                            }

                            $('.loader-outer').addClass('d-none');
                        }
                    }
                }).fail(function (jqXHR, textStatus, error) {
                    $('.loader-outer').addClass('d-none');
                    // Handle error here
                });*/
            });
            
        </script>
    </body>
</html>