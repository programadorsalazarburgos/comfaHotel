<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf8mb4">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
     <meta name="userId" content="{{Auth::check() ? Auth::user()->id : ''}}">
    <title>DIAMOND PMS</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js">

    <!-- GLOBAL MAINLY STYLES-->
    <link href="./assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="./assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="./assets/vendors/line-awesome/css/line-awesome.min.css" rel="stylesheet" />
    <link href="./assets/vendors/themify-icons/css/themify-icons.css" rel="stylesheet" />
    <link href="./assets/vendors/animate.css/animate.min.css" rel="stylesheet" />
    <link href="./assets/vendors/toastr/toastr.min.css" rel="stylesheet" />
    <link href="./assets/vendors/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/chat-application.css') }}">
    <link href="css/plantilla.css" rel="stylesheet">
    <!-- PLUGINS STYLES-->
    <link href="./assets/vendors/jvectormap/jquery-jvectormap-2.0.3.css" rel="stylesheet" />
    <!-- THEME STYLES-->
    <link href="assets/css/main.min.css" rel="stylesheet" />
    <!-- PAGE LEVEL STYLES-->
</head>

<body class="fixed-navbar">
    <div class="page-wrapper" id="app">
        <!-- START HEADER-->
        <header class="header">
            <div class="page-brand">
                <a href="/main">
                    <span class="brand">DIAMOND PMS
                        <span class="brand-tip"></span>
                    </span>
                    <span class="brand-mini"></span>
                </a>
            </div>
            <div class="d-flex justify-content-between align-items-center flex-1">
                <!-- START TOP-LEFT TOOLBAR-->
                <ul class="nav navbar-toolbar">
                    <li>
                        <a class="nav-link sidebar-toggler js-sidebar-toggler" href="javascript:;">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </a>
                    </li>
                    {{-- <li>
                        <a class="nav-link search-toggler js-search-toggler" href="javascript:;"><i class="ti-search"></i>
                            <span>Search here...</span>
                        </a>
                    </li> --}}
                </ul>
                <!-- END TOP-LEFT TOOLBAR-->
                <!-- START TOP-RIGHT TOOLBAR-->
                <ul class="nav navbar-toolbar">
                 <notification :notifications="notifications"></notification>

                    <li class="dropdown dropdown-user">
                        <a class="nav-link dropdown-toggle link" data-toggle="dropdown">
                            <span>{{Auth::user()->usuario}}</span>
                            <img src="./assets/img/users/admin-image.png" alt="image" />
                        </a>
                        <div class="dropdown-menu dropdown-arrow dropdown-menu-right admin-dropdown-menu">
                            <div class="dropdown-arrow"></div>
                            <div class="dropdown-header">
                                <div class="admin-avatar">
                                    <img src="./assets/img/users/admin-image.png" alt="image" />
                                </div>
                                <div>
                                    <h5 class="font-strong text-white">{{Auth::user()->usuario}}</h5>

                                </div>
                            </div>

                            <div class="admin-menu-content">

                                <div class="d-flex justify-content-between mt-2">

                                    <a class="d-flex align-items-center" href="{{ route('logout') }}"
                                     onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                    >Salir<i class="ti-shift-right ml-2 font-20"></i></a>
                                     <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                       {{ csrf_field() }}
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>


                </ul>
                <!-- END TOP-RIGHT TOOLBAR-->
            </div>
        </header>
        <!-- END HEADER-->
        <!-- START SIDEBAR-->
         <div class="app-body">

              @if(Auth::check())
            @if (Auth::user()->idrol == 4)
                @include('plantilla.menu_administrador')
            @endif

        @endif


    </div>
        <!-- END SIDEBAR-->
        <!-- Contenido Principal -->

        <!-- /Fin del contenido principal -->
     <div class="wrapper content-wrapper">
            <!-- START PAGE CONTENT-->
            <div class="page-heading">
                @yield('contenido')


            </div>
        </div>

                            <hr>





                        </div>
                    </div>
                </div>

        </div>
    </div>
    <!-- END THEME CONFIG PANEL-->
    <!-- BEGIN PAGA BACKDROPS-->
    <div class="sidenav-backdrop backdrop" style="opacity:0;"></div>
    <div class="preloader-backdrop">
        <div class="page-preloader">Loading</div>
    </div>
    <!-- END PAGA BACKDROPS-->
    <!-- New question dialog-->
   {{--  <div class="modal fade" id="session-dialog">
        <div class="modal-dialog" style="width:400px;" role="document">
            <div class="modal-content timeout-modal">
                <div class="modal-body">
                    <button class="close" data-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mt-3 mb-4"><i class="ti-lock timeout-icon"></i></div>
                    <div class="text-center h4 mb-3">Set Auto Logout</div>
                    <p class="text-center mb-4">You are about to be signed out due to inactivity.<br>Select after how many minutes of inactivity you log out of the system.</p>
                    <div id="timeout-reset-box" style="display:none;">
                        <div class="form-group text-center">
                            <button class="btn btn-danger btn-fix btn-air" id="timeout-reset">Deactivate</button>
                        </div>
                    </div>
                    <div id="timeout-activate-box">
                        <form id="timeout-form" action="javascript:;">
                            <div class="form-group pl-3 pr-3 mb-4">
                                <input class="form-control form-control-line" type="text" name="timeout_count" placeholder="Minutes" id="timeout-count">
                            </div>
                            <div class="form-group text-center">
                                <button class="btn btn-primary btn-fix btn-air" id="timeout-activate">Activate</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- End New question dialog-->
    <!-- QUICK SIDEBAR-->

    <!-- END QUICK SIDEBAR-->
    <!-- CORE PLUGINS-->
     <!--Start of Tawk.to Script-->
    @if(env('APP_ENV') !== 'local')

    @endif
    <!--End of Tawk.to Script-->
    <script src="js/app.js"></script>
    <script src="js/plantilla.js"></script>
    <script src="./assets/vendors/jquery/dist/jquery.min.js"></script>
    <script src="./assets/vendors/popper.js/dist/umd/popper.min.js"></script>
    <script src="./assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="./assets/vendors/jquery-slimscroll/jquery.slimscroll.min.js"></script>
    <script src="./assets/vendors/jquery-idletimer/dist/idle-timer.min.js"></script>
    <script src="./assets/vendors/toastr/toastr.min.js"></script>
    <script src="./assets/vendors/jquery-validation/dist/jquery.validate.min.js"></script>
    <script src="./assets/vendors/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
    <!-- PAGE LEVEL PLUGINS-->
    <script src="./assets/vendors/chart.js/dist/Chart.min.js"></script>
    <script src="./assets/vendors/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js"></script>
    <script src="./assets/vendors/jvectormap/jquery-jvectormap-2.0.3.min.js"></script>
    <script src="./assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- CORE SCRIPTS-->
    <script src="assets/js/app.min.js"></script>
    <!-- PAGE LEVEL SCRIPTS-->
    <script src="./assets/js/scripts/dashboard_visitors.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.nav-link').dropdown();
        });
   </script>


</html>
