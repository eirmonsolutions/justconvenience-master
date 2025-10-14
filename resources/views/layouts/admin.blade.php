<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon.png') }}">
    <title>{{ config('app.name', 'Just Convenience') }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css" integrity="sha512-hwwdtOTYkQwW2sedIsbuP1h0mWeJe/hFOfsvNKpRB3CkRxq8EW7QMheec1Sgd8prYxGm1OM9OZcGW7/GUud5Fw==" crossorigin="anonymous" />

    <!-- Custom CSS -->
    <link href="{{ asset('dist/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-datepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    
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
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="ti-more"></i>
                    </a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse collapse" id="navbarSupportedContent">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-left mr-auto ml-3 pl-1"></ul>
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
                                <span class="ml-2 d-none d-lg-inline-block">
                                    <span class="text-dark">{{ Auth::user()->name }}</span> <i data-feather="chevron-down" class="svg-icon"></i>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                                <a class="dropdown-item" href="{{ route('signout') }}">
                                    <i data-feather="power" class="svg-icon mr-2 ml-1"></i>Logout
                                </a>
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
                            <li class="sidebar-item {{ (\Request::route()->getName() == 'dashboard') ? 'selected' : '' }}">
                                <a class="sidebar-link sidebar-link active" href="{{ route('dashboard') }}" aria-expanded="false">
                                    <i data-feather="home" class="feather-icon"></i>
                                    <span class="hide-menu">Dashboard</span>
                                </a>
                            </li>

                            <li class="sidebar-item {{ (\Request::route()->getName() == 'stores' || \Request::route()->getName() == 'add-store' || \Request::route()->getName() == 'edit-store') ? 'selected' : '' }}">
                                <a class="sidebar-link" href="{{ route('stores') }}" aria-expanded="false">
                                    <i class="feather-icon fas fa-shopping-bag"></i>
                                    <span class="hide-menu">Stores</span>
                                </a>
                            </li>

                            <li class="sidebar-item {{ (\Request::route()->getName() == 'categories' || \Request::route()->getName() == 'add-category' || \Request::route()->getName() == 'edit-category') ? 'selected' : '' }}">
                                <a class="sidebar-link" href="{{ route('categories') }}" aria-expanded="false">
                                    <i class="fas fa-list"></i>
                                    <span class="hide-menu">Categories</span>
                                </a>
                            </li>

                            <li class="sidebar-item {{ (\Request::route()->getName() == 'sub-categories' || \Request::route()->getName() == 'add-sub-category' || \Request::route()->getName() == 'edit-sub-category') ? 'selected' : '' }}">
                                <a class="sidebar-link" href="{{ route('sub-categories') }}" aria-expanded="false">
                                    <i class="fa fa-list-alt"></i>
                                    <span class="hide-menu">Sub Categories</span>
                                </a>
                            </li>

                            <li class="sidebar-item {{ (\Request::route()->getName() == 'products' || \Request::route()->getName() == 'add-product' || \Request::route()->getName() == 'edit-product') ? 'selected' : '' }}">
                                <a class="sidebar-link" href="{{ route('products') }}" aria-expanded="false">
                                    <i class="fab fa-product-hunt"></i>
                                    <span class="hide-menu">Products</span>
                                </a>
                            </li>

                            <li class="sidebar-item {{ (\Request::route()->getName() == 'disabled-products') ? 'selected' : '' }}">
                                <a class="sidebar-link" href="{{ route('disabled-products') }}" aria-expanded="false">
                                    <i class="fab fa-product-hunt"></i>
                                    <span class="hide-menu">Disabled Products</span>
                                </a>
                            </li>

                            <li class="sidebar-item {{ (\Request::route()->getName() == 'customers' || \Request::route()->getName() == 'customer-details') ? 'selected' : '' }}">
                                <a class="sidebar-link sidebar-link" href="{{ route('customers') }}" aria-expanded="false">
                                    <i data-feather="users" class="feather-icon"></i>
                                    <span class="hide-menu">Customers</span>
                                </a>
                            </li>

                            <li class="sidebar-item {{ (\Request::route()->getName() == 'orders' || \Request::route()->getName() == 'order-details') ? 'selected' : '' }}">
                                <a class="sidebar-link sidebar-link" href="{{ route('orders') }}" aria-expanded="false">
                                    <i data-feather="users" class="feather-icon"></i>
                                    <span class="hide-menu">Orders</span>
                                </a>
                            </li>

                            <li class="sidebar-item {{ (\Request::route()->getName() == 'edit-settings') ? 'selected' : '' }}">
                                <a class="sidebar-link sidebar-link" href="{{ route('edit-settings') }}" aria-expanded="false">
                                    <i class="icon-settings" class="feather-icon"></i>
                                    <span class="hide-menu">Settings</span>
                                </a>
                            </li>

                            <li class="sidebar-item {{ (\Request::route()->getName() == 'edit-tnc') ? 'selected' : '' }}">
                                <a class="sidebar-link sidebar-link" href="{{ route('edit-tnc') }}" aria-expanded="false">
                                    <i class="icon-info" class="feather-icon"></i>
                                    <span class="hide-menu">Terms & Conditions</span>
                                </a>
                            </li>
                            <li class="list-divider"></li>
                        @elseif(Auth::user()->user_role == 5)
                            <li class="sidebar-item {{ (\Request::route()->getName() == 'stores' || \Request::route()->getName() == 'add-store' || \Request::route()->getName() == 'edit-store') ? 'selected' : '' }}">
                                <a class="sidebar-link" href="{{ route('stores') }}" aria-expanded="false">
                                    <i class="feather-icon fas fa-shopping-bag"></i>
                                    <span class="hide-menu">Stores</span>
                                </a>
                            </li>
                        @endif
                        <li class="sidebar-item">
                            <a class="sidebar-link sidebar-link" href="{{ route('signout') }}"
                                aria-expanded="false">
                                <i data-feather="log-out" class="feather-icon"></i>
                                <span class="hide-menu">Logout</span>
                            </a>
                        </li>
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
                Copyright {{ date('Y') }} {{ config('app.name', 'Just Convenience') }} - All Rights Reserved.
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
 
    <script type="text/javascript">
        var base_url = '<?php echo url('/') ?>';

        $(document).ready(function() {
            $('.select2').select2(); 

            $("#drop-remove").click(function () {
                $('input:checkbox').not(this).prop('checked', this.checked);
            });
              

            $('.dataTables_filter').addClass('d-none');
            // $('.dt-buttons').addClass('d-none');

            var whichPage = '<?php echo \Request::route()->getName();  ?>';

            if(whichPage == 'stores')
            {
                constantTable = $('#stores').DataTable( {
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
                        title: 'Stores Excel',
                        exportOptions: {
                            columns: "thead th:not(.noExport)"
                        }
                    }, {
                        extend: 'csv',
                        title: 'Stores Csv',
                        exportOptions: {
                            columns: "thead th:not(.noExport)"
                        }
                    }
                    ]
                }).container().appendTo($('#button'));
            }

            if(whichPage == 'categories')
            {
                constantTable = $('#categories').DataTable( {
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
                        title: 'Categories Excel',
                        exportOptions: {
                            columns: "thead th:not(.noExport)"
                        }
                    }, {
                        extend: 'csv',
                        title: 'Categories Csv',
                        exportOptions: {
                            columns: "thead th:not(.noExport)"
                        }
                    }
                    ]
                }).container().appendTo($('#button'));
            }

            if(whichPage == 'sub-categories')
            {
                constantTable = $('#sub_categories').DataTable( {
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
                        title: 'Sub Categories Excel',
                        exportOptions: {
                            columns: "thead th:not(.noExport)"
                        }
                    }, {
                        extend: 'csv',
                        title: 'Sub Categories Csv',
                        exportOptions: {
                            columns: "thead th:not(.noExport)"
                        }
                    }
                    ]
                }).container().appendTo($('#button'));
            }

            if(whichPage == 'products')
            {
                customerTable = $('#products').DataTable( {
                    dom: 'Bfrtip',
                    "pageLength": 30,
                    buttons: [
                    {
                        extend: 'excel',
                        title: 'Products Excel',
                        exportOptions: {
                            columns: "thead th:not(.noExport)"
                        }
                    }, {
                        extend: 'csv',
                        title: 'Products Csv',
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
                        title: 'Products Excel',
                        exportOptions: {
                            columns: "thead th:not(.noExport)"
                        }
                    }, {
                        extend: 'csv',
                        title: 'Products Csv',
                        exportOptions: {
                            columns: "thead th:not(.noExport)"
                        }
                    }
                    ]
                }).container().appendTo($('#button'));
            }

            if(whichPage == 'disabled-products')
            {
                customerTable = $('#disabled_products').DataTable( {
                    dom: 'Bfrtip',
                    "pageLength": 30,
                    buttons: [
                    {
                        extend: 'excel',
                        title: 'Disabled Products Excel',
                        exportOptions: {
                            columns: "thead th:not(.noExport)"
                        }
                    }, {
                        extend: 'csv',
                        title: 'Disabled Products Csv',
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
                        title: 'Disabled Products Excel',
                        exportOptions: {
                            columns: "thead th:not(.noExport)"
                        }
                    }, {
                        extend: 'csv',
                        title: 'Disabled Products Csv',
                        exportOptions: {
                            columns: "thead th:not(.noExport)"
                        }
                    }
                    ]
                }).container().appendTo($('#button'));
            }
        } );

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
                        @csrf
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
    <script type="text/javascript">
        $('.number').keypress(function(event) {
            var $this = $(this);
            if ((event.which != 46 || $this.val().indexOf('.') != -1) && ((event.which < 48 || event.which > 57) && (event.which != 0 && event.which != 8))) {
                event.preventDefault();
            }

            var text = $(this).val();
            if ((event.which == 46) && (text.indexOf('.') == -1)) {
                setTimeout(function() {
                    if ($this.val().substring($this.val().indexOf('.')).length > 3) {
                        $this.val($this.val().substring(0, $this.val().indexOf('.') + 3));
                    }
                }, 1);
            }

            if ((text.indexOf('.') != -1) && (text.substring(text.indexOf('.')).length > 2) &&
                (event.which != 0 && event.which != 8) && ($(this)[0].selectionStart >= text.length - 2)) {
                event.preventDefault();
            }
        });

        $('.number').bind("paste", function(e) {
            var text = e.originalEvent.clipboardData.getData('Text');
            if ($.isNumeric(text)) {
                if ((text.substring(text.indexOf('.')).length > 3) && (text.indexOf('.') > -1)) {
                    e.preventDefault();
                    $(this).val(text.substring(0, text.indexOf('.') + 3));
                }
            }
            else {
                e.preventDefault();
            }
        });

        $('.decimal_format').on('input', function () {
            this.value = this.value.match(/^\d+\.?\d{0,2}/);
        });
    </script>
    @stack('scripts')
</body>

</html>