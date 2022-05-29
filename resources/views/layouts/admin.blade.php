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
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('public/assets/images/favicon.png') }}">
    <title>{{ config('app.name', 'Activities') }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css" integrity="sha512-hwwdtOTYkQwW2sedIsbuP1h0mWeJe/hFOfsvNKpRB3CkRxq8EW7QMheec1Sgd8prYxGm1OM9OZcGW7/GUud5Fw==" crossorigin="anonymous" />

    <!-- Custom CSS -->
    <link href="{{ asset('public/dist/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('public/css/bootstrap-datepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('public/css/select2.min.css') }}" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css">
    
    <!-- Full Calendar Css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />

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
                                <img src="{{ asset('public/assets/images/logo.png') }}" alt="homepage" class="dark-logo" />
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
                        <li class="sidebar-item {{ (\Request::route()->getName() == 'dashboard') ? 'selected' : '' }}">
                            <a class="sidebar-link sidebar-link active" href="{{ route('dashboard') }}" aria-expanded="false">
                                <i data-feather="home" class="feather-icon"></i>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ (\Request::route()->getName() == 'users' || \Request::route()->getName() == 'add-user' || \Request::route()->getName() == 'edit-change-password' || \Request::route()->getName() == 'user-activities' || \Request::route()->getName() == 'add-user-activity' || \Request::route()->getName() == 'edit-user-activity') ? 'selected' : '' }}">
                            <a class="sidebar-link sidebar-link" href="{{ route('users') }}" aria-expanded="false">
                                <i data-feather="users" class="feather-icon"></i>
                                <span class="hide-menu">Users</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ (\Request::route()->getName() == 'activities' || \Request::route()->getName() == 'add-activity' || \Request::route()->getName() == 'edit-activity') ? 'selected' : '' }}">
                            <a class="sidebar-link sidebar-link" href="{{ route('activities') }}" aria-expanded="false">
                                <i data-feather="tag" class="feather-icon"></i>
                                <span class="hide-menu">Activities</span>
                            </a>
                        </li>

                        <li class="list-divider"></li>
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
    <script src="{{ asset('public/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('public/assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('public/assets/libs/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    
    <!-- Full Calendar Js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
    
    <!-- apps -->
    <!-- apps -->
    <script src="{{ asset('public/dist/js/app-style-switcher.js') }}"></script>
    <script src="{{ asset('public/dist/js/feather.min.js') }}"></script>
    <script src="{{ asset('public/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>

    <script src="{{ asset('public/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('public/js/select2.min.js') }}"></script>

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

            if(whichPage == 'users')
            {
                customerTable = $('#users').DataTable( {
                    dom: 'Bfrtip',
                    "pageLength": 30,
                    buttons: [
                    {
                        extend: 'excel',
                        title: 'Users Excel',
                        exportOptions: {
                            columns: "thead th:not(.noExport)"
                        }
                    }, {
                        extend: 'csv',
                        title: 'Users Csv',
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
                        title: 'Users Excel',
                        exportOptions: {
                            columns: "thead th:not(.noExport)"
                        }
                    }, {
                        extend: 'csv',
                        title: 'Users Csv',
                        exportOptions: {
                            columns: "thead th:not(.noExport)"
                        }
                    }
                    ]
                }).container().appendTo($('#button'));
            }

            if(whichPage == 'activities')
            {
                customerTable = $('#activities').DataTable( {
                    dom: 'Bfrtip',
                    "pageLength": 30,
                    buttons: [
                    {
                        extend: 'excel',
                        title: 'Activities Excel',
                        exportOptions: {
                            columns: "thead th:not(.noExport)"
                        }
                    }, {
                        extend: 'csv',
                        title: 'Activities Csv',
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
                        title: 'Activities Excel',
                        exportOptions: {
                            columns: "thead th:not(.noExport)"
                        }
                    }, {
                        extend: 'csv',
                        title: 'Activities Csv',
                        exportOptions: {
                            columns: "thead th:not(.noExport)"
                        }
                    }
                    ]
                }).container().appendTo($('#button'));
            }

            if(whichPage == 'dashboard')
            {
                var calendar = $('#calendar').fullCalendar({
                                    events: base_url + "/dashboard",
                                    displayEventTime: false,
                                    editable: false,
                                    eventRender: function (event, element, view) {
                                        // if (event.allDay === 'true') {
                                        //         event.allDay = true;
                                        // } else {
                                        //         event.allDay = false;
                                        // }
                                    },
                                    selectable: false,
                                    selectHelper: false,
                                });
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

        $(document).on('click', '.delete_user', function() {
            currentElement = $(this);
            URL = currentElement.attr('data-url');
            swal({
                title: "Are you sure?",
                text: "User will be deleted.",
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
                    swal("Cancelled", "Your user is safe :)", "error");
                }
            });
        });

        $(document).on('click', '.delete_user_activity', function() {
            currentElement = $(this);
            URL = currentElement.attr('data-url');
            swal({
                title: "Are you sure?",
                text: "Activity will be deleted.",
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
                    swal("Cancelled", "Your Activity is safe :)", "error");
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
    <script src="{{ asset('public/dist/js/custom.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#multiple-checkboxes').multiselect({
                includeSelectAllOption: true,
            });
        });
    </script>

    <script type="text/javascript">

        $(document).on('change', '.update_activity_status', function() {
            currentElement = $(this);

            var currentValue = currentElement.prop('checked');
            var status = currentValue ? 1 : 0;
            var id = currentElement.attr('data-id');

            $.ajax({
                url: base_url + "/update-activity-status/" + id + "/" + status,
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
    @stack('scripts')
</body>

</html>