<!DOCTYPE html>
<html dir="ltr" lang="en">

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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css" integrity="sha512-hwwdtOTYkQwW2sedIsbuP1h0mWeJe/hFOfsvNKpRB3CkRxq8EW7QMheec1Sgd8prYxGm1OM9OZcGW7/GUud5Fw==" crossorigin="anonymous" />

    <!-- Custom CSS -->
    <link href="{{ asset('dist/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-datepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
       <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
       <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
   <![endif]-->
   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">

   <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.dataTables.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">

   <script src="https://cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
</head>

<body>
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
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar" data-navbarbg="skin6">
            <nav class="navbar top-navbar navbar-expand-md">
                <div class="navbar-header" data-logobg="skin6">
                    <!-- This is for the sidebar toggle which is visible on mobile only -->
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                            class="ti-menu ti-close"></i></a>
                    <!-- ============================================================== -->
                    <!-- Logo -->
                    <!-- ============================================================== -->
                    <div class="navbar-brand">
                        <!-- Logo icon -->
                        <a href="{{route('dashboard') }}">
                            <!-- Logo text -->
                            <span class="logo-text">
                                <!-- dark Logo text -->
                                <img src="{{ asset('assets/images/logo.png') }}" alt="homepage" class="dark-logo" />
                            </span>
                        </a>
                    </div>
                    <!-- ============================================================== -->
                    <!-- End Logo -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- Toggle which is visible on mobile only -->
                    <!-- ============================================================== -->
                    <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
                        data-toggle="collapse" data-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i
                            class="ti-more"></i></a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse collapse" id="navbarSupportedContent">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-left mr-auto ml-3 pl-1">
                        <!-- Notification -->
                       <!-- Notification -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle pl-md-3 position-relative" href="javascript:void(0)"
                                id="bell" role="button" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <span><i data-feather="bell" class="svg-icon"></i></span>
                                <span class="badge badge-primary notify-no rounded-circle">5</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-left mailbox animated bounceInDown">
                                <ul class="list-style-none">
                                    <li>
                                        <div class="message-center notifications position-relative">
                                            <!-- Message -->
                                            <a href="javascript:void(0)"
                                                class="message-item d-flex align-items-center border-bottom px-3 py-2">
                                                
                                                <div class="w-75 d-inline-block v-middle pl-2">
                                                    <h6 class="message-title mb-0 mt-1">Mails sent Sucessfully</h6>
                                                    <span class="font-12 text-nowrap d-block text-muted">Lorem Ipsum is simply dummy text of the printing 
                                                        admin!</span>
                                                    <span class="font-12 text-nowrap d-block text-muted">9:30 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)"
                                                class="message-item d-flex align-items-center border-bottom px-3 py-2">
                                                
                                                <div class="w-75 d-inline-block v-middle pl-2">
                                                    <h6 class="message-title mb-0 mt-1">Mails sent Sucessfully</h6>
                                                    <span class="font-12 text-nowrap d-block text-muted">Lorem Ipsum is simply dummy text of the printing 
                                                        admin!</span>
                                                    <span class="font-12 text-nowrap d-block text-muted">9:30 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                           <a href="javascript:void(0)"
                                                class="message-item d-flex align-items-center border-bottom px-3 py-2">
                                                
                                                <div class="w-75 d-inline-block v-middle pl-2">
                                                    <h6 class="message-title mb-0 mt-1">Mails sent Sucessfully</h6>
                                                    <span class="font-12 text-nowrap d-block text-muted">Lorem Ipsum is simply dummy text of the printing 
                                                        admin!</span>
                                                    <span class="font-12 text-nowrap d-block text-muted">9:30 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)"
                                                class="message-item d-flex align-items-center border-bottom px-3 py-2">
                                                
                                                <div class="w-75 d-inline-block v-middle pl-2">
                                                    <h6 class="message-title mb-0 mt-1">Mails sent Sucessfully</h6>
                                                    <span class="font-12 text-nowrap d-block text-muted">Lorem Ipsum is simply dummy text of the printing 
                                                        admin!</span>
                                                    <span class="font-12 text-nowrap d-block text-muted">9:30 AM</span>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link pt-3 text-center text-dark" href="javascript:void(0);">
                                            <strong>Check all notifications</strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- End Notification -->
                        <!-- End Notification -->
                        <!-- ============================================================== -->
              
                    </ul>
                    <!-- ============================================================== -->
                    <!-- Right side toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-right">
                        <!-- ============================================================== -->
                        <!-- Search -->
                 
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="javascript:void(0)" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                               <i class="icon-user user-circle"></i>
                                <span class="ml-2 d-none d-lg-inline-block"> <span
                                        class="text-dark">{{ Auth::user()->name }}</span> <i data-feather="chevron-down"
                                        class="svg-icon"></i></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                        
                                <a class="dropdown-item" href="{{ route('signout') }}"><i data-feather="power"
                                        class="svg-icon mr-2 ml-1"></i>
                                    Logout</a>
               
                         
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar" data-sidebarbg="skin6">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar" data-sidebarbg="skin6">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        @if(Auth::user()->user_role == 2)
                            <li class="sidebar-item {{ (\Request::route()->getName() == 'dashboard') ? 'selected' : '' }}"> <a class="sidebar-link sidebar-link active" href="{{ route('dashboard') }}"
                                    aria-expanded="false"><i data-feather="home" class="feather-icon"></i><span
                                        class="hide-menu">Dashboard</span></a></li>

                            <li class="sidebar-item {{ (\Request::route()->getName() == 'supervisors' || \Request::route()->getName() == 'add-supervisor') ? 'selected' : '' }}"> <a class="sidebar-link" href="{{ route('supervisors') }}"
                                    aria-expanded="false"><i class="fas fa-user"></i><span
                                        class="hide-menu">Supervisors
                                    </span></a>
                            </li>
                            <li class="sidebar-item {{ (\Request::route()->getName() == 'contests' || \Request::route()->getName() == 'add-contest' || \Request::route()->getName() == 'edit-contest') ? 'selected' : '' }}"> <a class="sidebar-link" href="{{ route('contests') }}"
                                    aria-expanded="false"><i class="fas fa-trophy"></i><span
                                        class="hide-menu">Contests
                                    </span></a>
                            </li>
                            <li class="sidebar-item {{ (\Request::route()->getName() == 'shops' || \Request::route()->getName() == 'add-shop' || \Request::route()->getName() == 'edit-shop') ? 'selected' : '' }}"> <a class="sidebar-link" href="{{ route('shops') }}"
                                    aria-expanded="false"><i class="feather-icon fas fa-shopping-bag"></i><span
                                        class="hide-menu">Shops
                                    </span></a>
                            </li>
                            <li class="sidebar-item {{ (\Request::route()->getName() == 'customers' || \Request::route()->getName() == 'customer-details' || \Request::route()->getName() == 'customer-tickets') ? 'selected' : '' }}"> <a class="sidebar-link sidebar-link" href="{{ route('customers', ['contest_id' => $data['current_active_contest_id']])  }}"
                                    aria-expanded="false"><i data-feather="users" class="feather-icon"></i><span
                                        class="hide-menu">Customers Leads</span></a></li>
                            <li class="sidebar-item {{ (\Request::route()->getName() == 'eliminated-customers') ? 'selected' : '' }}"> <a class="sidebar-link sidebar-link" href="{{ route('eliminated-customers', ['contest_id' => $data['current_active_contest_id']])  }}"
                                    aria-expanded="false"><i data-feather="users" class="feather-icon"></i><span
                                        class="hide-menu">Eliminated Customers Leads</span></a></li>
                            <li class="sidebar-item {{ (\Request::route()->getName() == 'tickets' || \Request::route()->getName() == 'get-winners') ? 'selected' : '' }}"> <a class="sidebar-link sidebar-link" href="{{ route('tickets', ['contest_id' => $data['current_active_contest_id']])  }}"
                                    aria-expanded="false"><i data-feather="tag" class="feather-icon"></i><span
                                        class="hide-menu">Tickets</span></a></li>
                            <!-- <li class="sidebar-item {{ (\Request::route()->getName() == 'customers' || \Request::route()->getName() == 'customer-details' || \Request::route()->getName() == 'customer-tickets') ? 'selected' : '' }}"> <a class="sidebar-link sidebar-link" href="{{ route('customers') }}"
                                    aria-expanded="false"><i data-feather="users" class="feather-icon"></i><span
                                        class="hide-menu">Customers Leads</span></a></li>
                            <li class="sidebar-item {{ (\Request::route()->getName() == 'tickets' || \Request::route()->getName() == 'get-winners') ? 'selected' : '' }}"> <a class="sidebar-link sidebar-link" href="{{ route('tickets') }}"
                                    aria-expanded="false"><i data-feather="tag" class="feather-icon"></i><span
                                        class="hide-menu">Tickets</span></a></li> -->
                            <!-- <li class="sidebar-item {{ (\Request::route()->getName() == 'send-customer-email') ? 'selected' : '' }}"> <a class="sidebar-link sidebar-link" href="{{ route('send-customer-email') }}"
                                    aria-expanded="false"> <i class="icon-envelope-open" class="feather-icon"></i><span
                                        class="hide-menu">Email Area</span></a></li> -->
                            <li class="sidebar-item {{ (\Request::route()->getName() == 'send-contest-customer-email') ? 'selected' : '' }}"> <a class="sidebar-link sidebar-link" href="{{ route('send-contest-customer-email') }}"
                                    aria-expanded="false"> <i class="icon-envelope-open" class="feather-icon"></i><span
                                        class="hide-menu">Email Area</span></a></li>
                            <li class="sidebar-item {{ (\Request::route()->getName() == 'edit-settings') ? 'selected' : '' }}"> <a class="sidebar-link sidebar-link" href="{{ route('edit-settings') }}"
                                    aria-expanded="false"> <i class="icon-settings" class="feather-icon"></i><span
                                        class="hide-menu">Form Settings</span></a></li>
                            <li class="sidebar-item {{ (\Request::route()->getName() == 'edit-tnc') ? 'selected' : '' }}"> <a class="sidebar-link sidebar-link" href="{{ route('edit-tnc') }}"
                                    aria-expanded="false"> <i class="icon-info" class="feather-icon"></i><span
                                        class="hide-menu">Terms & Conditions</span></a></li>

                            <li class="list-divider"></li>
                         
                      
                            <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="{{ route('signout') }}"
                                    aria-expanded="false"><i data-feather="log-out" class="feather-icon"></i><span
                                        class="hide-menu">Logout</span></a></li>
                        @elseif(Auth::user()->user_role == 3)
                            <li class="sidebar-item {{ (\Request::route()->getName() == 'dashboard') ? 'selected' : '' }}"> <a class="sidebar-link sidebar-link active" href="{{ route('dashboard') }}"
                                    aria-expanded="false"><i data-feather="home" class="feather-icon"></i><span
                                        class="hide-menu">Dashboard</span></a></li>

                            <li class="sidebar-item {{ (\Request::route()->getName() == 'customers' || \Request::route()->getName() == 'customer-details' || \Request::route()->getName() == 'customer-tickets') ? 'selected' : '' }}"> <a class="sidebar-link sidebar-link" href="{{ route('customers', ['contest_id' => $data['current_active_contest_id']])  }}"
                                    aria-expanded="false"><i data-feather="users" class="feather-icon"></i><span
                                        class="hide-menu">Customers Leads</span></a></li>
                            <li class="sidebar-item {{ (\Request::route()->getName() == 'tickets' || \Request::route()->getName() == 'get-winners') ? 'selected' : '' }}"> <a class="sidebar-link sidebar-link" href="{{ route('tickets', ['contest_id' => $data['current_active_contest_id']])  }}"
                                    aria-expanded="false"><i data-feather="tag" class="feather-icon"></i><span
                                        class="hide-menu">Tickets</span></a></li>
                            <li class="list-divider"></li>
                            
                            
                            <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="{{ route('signout') }}"
                                    aria-expanded="false"><i data-feather="log-out" class="feather-icon"></i><span
                                        class="hide-menu">Logout</span></a></li>
                        @endif
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->

            @yield('content')
             <!-- footer -->
             <!-- ============================================================== -->
             <footer class="footer text-center text-muted">
                Copyright {{ date('Y') }} {{ config('app.name', 'Quicentro Shopping') }} - All Rights Reserved.
             </footer>
             <!-- ============================================================== -->
             <!-- End footer -->
             <!-- ============================================================== -->
         </div>
         <!-- ============================================================== -->
         <!-- End Page wrapper  -->
         <!-- ============================================================== -->
   </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->


    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- apps -->
    <!-- apps -->
    <script src="{{ asset('dist/js/app-style-switcher.js') }}"></script>
    <script src="{{ asset('dist/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>

    <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
 
   <script>
      var base_url = '<?php echo url('/') ?>';

      $(document).ready(function() {
        $('.select2').select2(); 

        $("#drop-remove").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
          

          $('.dataTables_filter').addClass('d-none');
          // $('.dt-buttons').addClass('d-none');

          var whichPage = '<?php echo \Request::route()->getName();  ?>';

          if(whichPage == 'contests')
          {
            constantTable = $('#contest').DataTable( {
                dom: 'Bfrtip',
                buttons: [{
                    //here comes your button definitions
                  }]
               /* buttons: [
                    {
                         extend: 'excel',
                         title: 'Contest Excel',
                         exportOptions: {
                             columns: "thead th:not(.noExport)"
                         }
                     }, {
                         extend: 'csv',
                         title: 'Contest Csv',
                         exportOptions: {
                             columns: "thead th:not(.noExport)"
                         }
                     }
                ]*/
            } );

            $('.myInputTextField').keyup(function(){
                  constantTable.search($(this).val()).draw() ;
            });

              var buttons = new $.fn.dataTable.Buttons(constantTable, {
                    buttons: [
                        {
                           extend: 'excel',
                           title: 'Contest Excel',
                           exportOptions: {
                               columns: "thead th:not(.noExport)"
                           }
                        }, {
                           extend: 'csv',
                           title: 'Contest Csv',
                           exportOptions: {
                               columns: "thead th:not(.noExport)"
                           }
                        }
                    ]
                }).container().appendTo($('#button'));
          }

          if(whichPage == 'shops')
          {
            constantTable = $('#shops').DataTable( {
                dom: 'Bfrtip',
                buttons: [{
                    //here comes your button definitions
                  }]
            } );

            $('.myInputTextField').keyup(function(){
                  constantTable.search($(this).val()).draw() ;
            });

              var buttons = new $.fn.dataTable.Buttons(constantTable, {
                    buttons: [
                        {
                           extend: 'excel',
                           title: 'Contest Excel',
                           exportOptions: {
                               columns: "thead th:not(.noExport)"
                           }
                        }, {
                           extend: 'csv',
                           title: 'Contest Csv',
                           exportOptions: {
                               columns: "thead th:not(.noExport)"
                           }
                        }
                    ]
                }).container().appendTo($('#button'));
          }

          if(whichPage == 'customers')
          {
              customerTable = $('#customers').DataTable( {
                  dom: 'Bfrtip',
                  buttons: [
                      {
                           extend: 'excel',
                           title: 'Customers Excel',
                           exportOptions: {
                               columns: "thead th:not(.noExport)"
                           }
                       }, {
                           extend: 'csv',
                           title: 'Customers Csv',
                           exportOptions: {
                               columns: "thead th:not(.noExport)"
                           }
                       }
                  ]
              } );

              $('.myInputTextField').keyup(function(){
                    customerTable.search($(this).val()).draw() ;
              });

              var buttons = new $.fn.dataTable.Buttons(customerTable, {
                    buttons: [
                        {
                           extend: 'excel',
                           title: 'Contest Excel',
                           exportOptions: {
                               columns: "thead th:not(.noExport)"
                           }
                        }, {
                           extend: 'csv',
                           title: 'Contest Csv',
                           exportOptions: {
                               columns: "thead th:not(.noExport)"
                           }
                        }
                    ]
                }).container().appendTo($('#button'));
          }

          if(whichPage == 'supervisors')
          {
              supervisorsTable = $('#supervisors').DataTable( {
                  dom: 'Bfrtip',
                  buttons: [
                      {
                           extend: 'excel',
                           title: 'Customers Excel',
                           exportOptions: {
                               columns: "thead th:not(.noExport)"
                           }
                       }, {
                           extend: 'csv',
                           title: 'Customers Csv',
                           exportOptions: {
                               columns: "thead th:not(.noExport)"
                           }
                       }
                  ]
              } );

              $('.myInputTextField').keyup(function(){
                    supervisorsTable.search($(this).val()).draw() ;
              });

              // var buttons = new $.fn.dataTable.Buttons(customerTable, {
              //       buttons: [
              //           {
              //              extend: 'excel',
              //              title: 'Contest Excel',
              //              exportOptions: {
              //                  columns: "thead th:not(.noExport)"
              //              }
              //           }, {
              //              extend: 'csv',
              //              title: 'Contest Csv',
              //              exportOptions: {
              //                  columns: "thead th:not(.noExport)"
              //              }
              //           }
              //       ]
              //   }).container().appendTo($('#button'));
          }

          if(whichPage == 'eliminated-customers')
          {
              customerTable = $('#eliminated_customers').DataTable( {
                  dom: 'Bfrtip',
                  buttons: [
                      {
                           extend: 'excel',
                           title: 'Customers Excel',
                           exportOptions: {
                               columns: "thead th:not(.noExport)"
                           }
                       }, {
                           extend: 'csv',
                           title: 'Customers Csv',
                           exportOptions: {
                               columns: "thead th:not(.noExport)"
                           }
                       }
                  ]
              } );

              $('.myInputTextField').keyup(function(){
                    customerTable.search($(this).val()).draw() ;
              });

              var buttons = new $.fn.dataTable.Buttons(customerTable, {
                    buttons: [
                        {
                           extend: 'excel',
                           title: 'Contest Excel',
                           exportOptions: {
                               columns: "thead th:not(.noExport)"
                           }
                        }, {
                           extend: 'csv',
                           title: 'Contest Csv',
                           exportOptions: {
                               columns: "thead th:not(.noExport)"
                           }
                        }
                    ]
                }).container().appendTo($('#button'));
          }

          if(whichPage == 'tickets')
          {

            ticketsTable = $('#tickets').DataTable({ 
                processing: true,
                serverSide: true, 
                searching: true,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
                    search: ''
                },
                dom: 'Blfrtip',
                buttons: [
                    {
                       extend: 'excel',
                       title: 'Ticket Excel',
                       exportOptions: {
                           columns: "thead th:not(.noExport)"
                       }
                    }, {
                       extend: 'csv',
                       title: 'Contest Csv',
                       exportOptions: {
                           columns: "thead th:not(.noExport)"
                       }
                    }
                ],
                buttons: [
                     'csv', 'excel', 'pdf'
                ],
                ajax: {
                    url: "{{ url('get_tickets') }}",
                    type: 'GET',
                    data: function (d) {
                        d.start_date = $('#datatable_startdate').val();
                        d.end_date = $('#datatable_enddate').val();
                        d.search_fields = $('#myInputTextField').val();
                        d.contest_id = $("#datatable_contest_id option:selected").val();
                        },
                    }, 
                columns: [ 
                    { data: 'id', name: 'id', class:'border-top-0 text-muted px-2 py-4 font-14' },
                    { data: 'name', name: 'name', class:'border-top-0 text-muted px-2 py-4 font-14'  },
                    { data: 'last_name', name: 'last_name', class:'border-top-0 text-muted px-2 py-4 font-14'  }, 
                    { data: 'email', name: 'email', class:'border-top-0 text-muted px-2 py-4 font-14'  }, 
                    { data: 'phone_number', name: 'phone_number', class:'border-top-0 text-muted px-2 py-4 font-14'  }, 
                    { data: 'indentification_card', name: 'indentification_card', class:'border-top-0 text-muted px-2 py-4 font-14'  }, 
                    { data: 'direction', name: 'direction', class:'border-top-0 text-muted px-2 py-4 font-14'  }, 
                ], 
            });

            $('.myInputTextField').keyup(function(){
                  ticketsTable.search($(this).val()).draw() ;
            });

            // $(document.body).on("keyup","#myInputTextField", function(){
            //     $('#tickets_test').DataTable().draw(true);
            // }); 

            $(document.body).on("click","#datatable_submit", function(){
                $('#tickets').DataTable().draw(true);
            });

             var buttons = new $.fn.dataTable.Buttons(ticketsTable, {
                    buttons: [
                        {
                           extend: 'excel',
                           title: 'Ticket Excel',
                           exportOptions: {
                               columns: "thead th:not(.noExport)"
                           }
                        }, {
                           extend: 'csv',
                           title: 'Ticket Csv',
                           exportOptions: {
                               columns: "thead th:not(.noExport)"
                           }
                        }
                    ]
                }).container().appendTo($('#button'));


              // ticketsTable = $('#tickets').DataTable( {
              //     dom: 'Bfrtip',
              //     buttons: [
              //         {
              //              extend: 'excel',
              //              title: 'Tickets Excel',
              //              exportOptions: {
              //                  columns: "thead th:not(.noExport)"
              //              }
              //          }, {
              //              extend: 'csv',
              //              title: 'Tickets Csv',
              //              exportOptions: {
              //                  columns: "thead th:not(.noExport)"
              //              }
              //          }
              //     ]
              // } );

              // $('.myInputTextField').keyup(function(){
              //       ticketsTable.search($(this).val()).draw() ;
              // });

              // var buttons = new $.fn.dataTable.Buttons(ticketsTable, {
              //       buttons: [
              //           {
              //              extend: 'excel',
              //              title: 'Contest Excel',
              //              exportOptions: {
              //                  columns: "thead th:not(.noExport)"
              //              }
              //           }, {
              //              extend: 'csv',
              //              title: 'Contest Csv',
              //              exportOptions: {
              //                  columns: "thead th:not(.noExport)"
              //              }
              //           }
              //       ]
              //   }).container().appendTo($('#button'));
          }
      } );
   </script>

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

   <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js" integrity="sha512-MqEDqB7me8klOYxXXQlB4LaNf9V9S0+sG1i8LtPOYmHqICuEZ9ZLbyV3qIfADg2UJcLyCm4fawNiFvnYbcBJ1w==" crossorigin="anonymous"></script>

   <script type="text/javascript">
    $(document).on('click', '.delete_all_shops', function() {
        swal({
            title: "Are you sure?",
            text: "All the shops will be deleted.",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel plx!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm) {
            if (isConfirm) {
                /*event.preventDefault();*/
                $.ajax({
                    url:"{{ route('delete-all-shops') }}",
                    method:"GET",
                    // data: {id: id, customer_id: customer_id},
                    dataType:'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success:function(data)
                    {
                        if(data.status == 1)
                        {
                            // swal("Deleted!", "Your shops has been deleted.", "success");
                            swal("Deleted!", data.message, "success");
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        }
                        else
                        {
                            // swal("Cancelled", "Your shops are safe :)", "error");
                            swal("Cancelled", data.message, "error");
                            window.location.reload();
                        }
                    }
                }).fail(function (jqXHR, textStatus, error) {
                    swal("Deleted!", "Something went wrong.", "error");
                    window.location.reload();
                });
            } else {
                swal("Cancelled", "Your shops are safe :)", "error");
                // window.location.reload();
            }
        });
    });

    $(document).on('click', '.delete_shop', function() {
        currentElement = $(this);
        URL = currentElement.attr('data-url');
        swal({
            title: "Are you sure?",
            text: "Shop will be deleted.",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel plx!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm) {
            if (isConfirm) {
                /*event.preventDefault();*/
                window.location = URL;
            } else {
                swal("Cancelled", "Your shop is safe :)", "error");
            }
        });
    });

    $(document).on('click', '.delete_selected_shops', function() {
        swal({
            title: "Are you sure?",
            text: "Selected Shops will be deleted.",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel plx!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm) {
            if (isConfirm) {
                /*event.preventDefault();*/
                $('#shop_list_form').submit();
            } else {
                swal("Cancelled", "Your shops are safe :)", "error");
            }
        });
    });

    $(document).on('click', '.delete_supervisor', function() {
        currentElement = $(this);
        URL = currentElement.attr('data-url');
        swal({
            title: "Are you sure?",
            text: "Supervisor will be deleted.",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel plx!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm) {
            if (isConfirm) {
                /*event.preventDefault();*/
                window.location = URL;
            } else {
                swal("Cancelled", "Your supervisor is safe :)", "error");
            }
        });
    });

    $(document).on('click', '.delete_customer', function() {
        currentElement = $(this);
        URL = currentElement.attr('data-url');
        swal({
            title: "Are you sure?",
            text: "Customer will be eliminated.",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes, eliminated it!",
            cancelButtonText: "No, cancel plx!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm) {
            if (isConfirm) {
                /*event.preventDefault();*/
                window.location = URL;
            } else {
                swal("Cancelled", "Your customer is safe :)", "error");
            }
        });
    });
   </script>

   @if(Session::get('class') == 'success')
      <script>toastr.success('{{ Session::get("message") }}');</script>
   @elseif(Session::get('class') == 'danger')
      <script>toastr.error('{{ Session::get("message") }}');</script>
   @endif
    <!--Custom JavaScript -->
    <script src="{{ asset('dist/js/custom.min.js') }}"></script>

    <script type="text/javascript">
      $(document).ready(function() {
          $('#multiple-checkboxes').multiselect({
            includeSelectAllOption: true,
          });
      });
    </script>

    <script type="text/javascript">
        $(document).on('click', '.approved_button', function() {
            currentElement = $(this);
            
            var id = currentElement.attr('data-id');
            var customer_id = currentElement.attr('data-customer');
            
            /*event.preventDefault();*/
            $.ajax({
                url:"{{ url('') }}/approved-invoice/" + id + "/" + customer_id,
                method:"GET",
                // data: {id: id, customer_id: customer_id},
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(data)
                {
                    if(data.status == 1)
                    {
                        $('#empModal').modal({backdrop: 'static', keyboard: false})  
                        // Add response in Modal body
                        $('.return_message').val(data.message);
                        $('.modal_customer_id').val(customer_id);
                        // Display Modal
                        $('#empModal').modal('show');

                        currentElement.parent().parent().parent().children('.paddL0').children('.tag_div').children('.invoice_tag').html('approved');
                        currentElement.parent().remove();
                    }
                    else
                    {
                        alert(data.message);
                    }
                }
            }).fail(function (jqXHR, textStatus, error) {
                
            });
        });

        $(document).on('change', '.checkbox_switch', function() {
            currentElement = $(this);
            var currentValue = $(this).prop('checked');


            var status = currentValue ? 1 : 0;

            $.ajax({
                url:"{{ url('') }}/update-image-upload-setting/" + status,
                method:"GET",
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(data)
                {
                    if (data.status == 1)
                    {
                        window.location.reload();
                        toastr.success(data.message);
                    }
                    else
                    {
                        window.location.reload();
                        toastr.error(data.message);
                    }
                }
            }).fail(function (jqXHR, textStatus, error) {
                // window.location.reload();
                toastr.error('Something went wrong');
            });
        });

        $(document).on('change', '.update_contest_status', function() {
            currentElement = $(this);

            var currentValue = currentElement.prop('checked');
            var status = currentValue ? 1 : 0;
            var id = currentElement.attr('data-id');

            $.ajax({
                url:"{{ url('') }}/update-contest-status/" + id + "/" + status,
                method:"GET",
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(data)
                {
                    if (data.status == 1)
                    {
                        window.location.reload();
                        toastr.success(data.message);
                    }
                    else
                    {
                        window.location.reload();
                        toastr.error(data.message);
                    }
                }
            }).fail(function (jqXHR, textStatus, error) {
                // window.location.reload();
                toastr.error('Something went wrong');
            });
        });
    </script>

    <div class="modal fade" id="empModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Message</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('send-approved-email') }}" id="Login" method="POST"> 
                        <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" /> 
                        <input type="hidden" name="customer_id" class="modal_customer_id" value=""> 
                        <label for="exampleFormControlSelect1">Message</label>
                        <textarea name="message" class="form-control mb-3 return_message"></textarea>
                        <button type="submit" class="btn btn-primary btn-view-all float-right">Send</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $('#empModal').on('hidden.bs.modal', function (e) {
            window.location.reload();
            toastr.success('Invoice has approved successfully.');
        })
    </script>
    @stack('scripts')
</body>

</html>