<!DOCTYPE html>
<html lang="en" class="loading">

<!-- Mirrored from pixinvent.com/apex-angular-4-bootstrap-admin-template/html-demo-1/ by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 08 Nov 2018 14:37:43 GMT -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Apex admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Apex admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
     <meta name="userId" content="{{Auth::check() ? Auth::user()->id : ''}}">
    <title>DIAMOND PMS</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="60x60" href="img/ico/apple-icon-60.html">
    <link rel="apple-touch-icon" sizes="76x76" href="img/ico/apple-icon-76.html">
    <link rel="apple-touch-icon" sizes="120x120" href="img/ico/apple-icon-120.html">
    <link rel="apple-touch-icon" sizes="152x152" href="img/ico/apple-icon-152.html">
    <link rel="shortcut icon" type="image/x-icon" href="img/ico/favicon.ico">
    <link rel="shortcut icon" type="image/png" href="img/ico/favicon-32.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,500,700,900|Montserrat:300,400,500,600,700,800,900" rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <!-- font icons-->

    <link rel="stylesheet" type="text/css" href="css/plantilla.css">
    <link rel="stylesheet" type="text/css" href="app-assets/fonts/feather/style.min.css">
    <link rel="stylesheet" type="text/css" href="app-assets/fonts/simple-line-icons/style.css">
    <link rel="stylesheet" type="text/css" href="app-assets/fonts/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="app-assets/css/app.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">


    <script>
        var roomID = 0;
        var sbRoomID = 0;
        var actionType = "";
        var startDate = null;
        var endDate = null;
        var startDateString = null;
        var endDateString = null;
        var sourceData = [];
        var contextwalkin = false;
        var selectedBar = null;
        var timer;
        var scrollTop = 0;
        var scrollLeft = 0;
    </script>

    <link rel="stylesheet" href="https://unpkg.com/vue-form-wizard/dist/vue-form-wizard.min.css">
    <script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
<link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
  </head>
  <body data-col="2-columns" class=" 2-columns ">
    <!-- ////////////////////////////////////////////////////////////////////////////-->
    <div class="wrapper nav-collapsed menu-collapsed" id="app" style="height: 1000px;">


      <!-- main menu-->
      <!--.main-menu(class="#{menuColor} #{menuOpenType}", class=(menuShadow == true ? 'menu-shadow' : ''))-->
      <div data-active-color="white" data-background-color="man-of-steel" data-image="img/sidebar-bg/01.jpg" class="app-sidebar" id="app">
        <!-- main menu header-->
        <!-- Sidebar Header starts-->
        <div class="sidebar-header">
          <div class="logo clearfix">
            <a href="index-2.html" class="logo-text float-left">
              <div class="logo-img"><img src="img/logo.png"/>
              </div>
              <span class="text align-middle" style="font-size: 12px;">DIAMOND PMS</span>
            </a>
            <a id="sidebarToggle" href="javascript:;" class="nav-toggle d-none d-sm-none d-md-none d-lg-block"><i data-toggle="expanded" class="ft-toggle-right toggle-icon"></i>
            </a>
            <a id="sidebarClose" href="javascript:;" class="nav-close d-block d-md-block d-lg-none d-xl-none"><i class="ft-x"></i>
            </a>
          </div>
        </div>
        <!-- Sidebar Header Ends-->
        <!-- / main menu header-->
        <!-- main menu content-->
        <div class="sidebar-content">

         @if(Auth::check())
                @include('plantilla.menu_administrador')

        @endif




        </div>
        <!-- main menu content-->
        <div class="sidebar-background"></div>
        <!-- main menu footer-->
        <!-- include includes/menu-footer-->
        <!-- main menu footer-->
      </div>
      <!-- / main menu-->


      <!-- Navbar (Header) Starts-->
      <nav class="navbar navbar-expand-lg navbar-light bg-faded header-navbar" style="
    background-color: #eee;">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" data-toggle="collapse" class="navbar-toggle d-lg-none float-left"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><span class="d-lg-none navbar-right navbar-collapse-toggle"><a aria-controls="navbarSupportedContent" href="javascript:;" class="open-navbar-container black"><i class="ft-more-vertical"></i></a></span>
            <form role="search" class="navbar-form navbar-right mt-1">
              <div class="position-relative has-icon-right">
               <!--  <input type="text" placeholder="Search" class="form-control round"/> -->
                <!-- <div class="form-control-position"><i class="ft-search"></i></div> -->
              </div>
            </form>
          </div>
          <div class="navbar-container">
            <div id="navbarSupportedContent" class="collapse navbar-collapse">
              <ul class="navbar-nav">
                <li class="nav-item mr-2 d-none d-lg-block"><a id="navbar-fullscreen" href="javascript:;" class="nav-link apptogglefullscreen"><i class="ft-maximize font-medium-3 blue-grey darken-4"></i>
                    <p class="d-none">fullscreen</p></a></li>
                <li class="dropdown nav-item"><a id="dropdownBasic3" href="#" data-toggle="dropdown" class="nav-link position-relative dropdown-toggle"><i class="ft-flag font-medium-3 blue-grey darken-4"></i><span class="selected-language d-none"></span></a>
                  <div class="dropdown-menu dropdown-menu-right text-left"><a href="javascript:;" class="dropdown-item py-1"><img src="img/flags/us.png" class="langimg"/><span> English</span></a><a href="javascript:;" class="dropdown-item py-1"><img src="img/flags/es.png" class="langimg"/><span> Spanish</span></a><a href="javascript:;" class="dropdown-item py-1"><img src="img/flags/br.png" class="langimg"/><span> Portuguese</span></a><a href="javascript:;" class="dropdown-item"><img src="img/flags/de.png" class="langimg"/><span> French</span></a></div>
                </li>
                <li class="dropdown nav-item"><a id="dropdownBasic2" href="#" data-toggle="dropdown" class="nav-link position-relative dropdown-toggle"><i class="ft-bell font-medium-3 blue-grey darken-4"></i><span class="notification badge badge-pill badge-danger">4</span>
                    <p class="d-none">Notifications</p></a>
                  <div class="notification-dropdown dropdown-menu dropdown-menu-right">
                    <div class="noti-list"><a class="dropdown-item noti-container py-3 border-bottom border-bottom-blue-grey border-bottom-lighten-4"><i class="ft-bell info float-left d-block font-large-1 mt-1 mr-2"></i><span class="noti-wrapper"><span class="noti-title line-height-1 d-block text-bold-400 info">New Order Received</span><span class="noti-text">Lorem ipsum dolor sit ametitaque in, et!</span></span></a><a class="dropdown-item noti-container py-3 border-bottom border-bottom-blue-grey border-bottom-lighten-4"><i class="ft-bell warning float-left d-block font-large-1 mt-1 mr-2"></i><span class="noti-wrapper"><span class="noti-title line-height-1 d-block text-bold-400 warning">New User Registered</span><span class="noti-text">Lorem ipsum dolor sit ametitaque in </span></span></a><a class="dropdown-item noti-container py-3 border-bottom border-bottom-blue-grey border-bottom-lighten-4"><i class="ft-bell danger float-left d-block font-large-1 mt-1 mr-2"></i><span class="noti-wrapper"><span class="noti-title line-height-1 d-block text-bold-400 danger">New Order Received</span><span class="noti-text">Lorem ipsum dolor sit ametest?</span></span></a><a class="dropdown-item noti-container py-3"><i class="ft-bell success float-left d-block font-large-1 mt-1 mr-2"></i><span class="noti-wrapper"><span class="noti-title line-height-1 d-block text-bold-400 success">New User Registered</span><span class="noti-text">Lorem ipsum dolor sit ametnatus aut.</span></span></a></div><a class="noti-footer primary text-center d-block border-top border-top-blue-grey border-top-lighten-4 text-bold-400 py-1">Read All Notifications</a>
                  </div>
                </li>
                <li class="dropdown nav-item"><a id="dropdownBasic3" href="#" data-toggle="dropdown" class="nav-link position-relative dropdown-toggle"><i class="ft-user font-medium-3 blue-grey darken-4"></i>
                    <p class="d-none">User Settings</p></a>
                  <div ngbdropdownmenu="" aria-labelledby="dropdownBasic3" class="dropdown-menu text-left dropdown-menu-right"><a href="javascript:;" class="dropdown-item py-1"><i class="ft-settings mr-2"></i><span>Settings</span></a><a href="javascript:;" class="dropdown-item py-1"><i class="ft-edit mr-2"></i><span>Edit Profile</span></a><a href="javascript:;" class="dropdown-item py-1"><i class="ft-mail mr-2"></i><span>My Inbox</span></a>
                    <div class="dropdown-divider"></div><a href="javascript:;" class="dropdown-item"><i class="ft-power mr-2"></i><span>Logout</span></a>
                  </div>
                </li>
                <li class="nav-item d-none d-lg-block"><a href="javascript:;" class="nav-link position-relative notification-sidebar-toggle"><i class="ft-align-left font-medium-3 blue-grey darken-4"></i>
                    <p class="d-none">Notifications Sidebar</p></a></li>
              </ul>
            </div>
          </div>
        </div>
      </nav>
      <!-- Navbar (Header) Ends-->

      <div class="main-panel">
        <div class="main-content">
          <div class="content-wrapper">
            <!--Statistics cards Starts-->

            @yield('contenido')

</div>
</div>
</div>

<!--Statistics cards Ends-->


    </aside>
    <!-- END Notification Sidebar-->
    <!-- Theme customizer Starts-->
   <!--  <div class="customizer border-left-blue-grey border-left-lighten-4 d-none d-sm-none d-md-block"><a class="customizer-close"><i class="ft-x font-medium-3"></i></a><a id="rtl-icon" href="https://pixinvent.com/apex-angular-4-bootstrap-admin-template/html-demo-6/" target="_blank" class="customizer-toggle bg-dark "><span class="font-medium-1 white align-middle">RTL</span></a><a id="customizer-toggle-icon" class="customizer-toggle bg-danger"><i class="ft-settings font-medium-4 fa fa-spin white align-middle"></i></a> -->
    <!--   <div data-ps-id="df6a5ce4-a175-9172-4402-dabd98fc9c0a" class="customizer-content p-3 ps-container ps-theme-dark"> -->
       <!--  <h4 class="text-uppercase mb-0 text-bold-400">Theme Customizer</h4>
        <p>Customize & Preview in Real Time</p> -->
      <!--   <hr> -->
        <!-- Sidebar Options Starts-->
        <!-- <h6 class="text-center text-bold-500 mb-3 text-uppercase">Sidebar Color Options</h6> -->
      <!--   <div class="cz-bg-color">
          <div class="row p-1">
            <div class="col"><span style="width:20px; height:20px;" data-bg-color="pomegranate" class="gradient-pomegranate d-block rounded-circle"></span></div>
            <div class="col"><span style="width:20px; height:20px;" data-bg-color="king-yna" class="gradient-king-yna d-block rounded-circle"></span></div>
            <div class="col"><span style="width:20px; height:20px;" data-bg-color="ibiza-sunset" class="gradient-ibiza-sunset d-block rounded-circle"></span></div>
            <div class="col"><span style="width:20px; height:20px;" data-bg-color="flickr" class="gradient-flickr d-block rounded-circle"></span></div>
            <div class="col"><span style="width:20px; height:20px;" data-bg-color="purple-bliss" class="gradient-purple-bliss d-block rounded-circle"></span></div>
            <div class="col"><span style="width:20px; height:20px;" data-bg-color="man-of-steel" class="gradient-man-of-steel d-block rounded-circle"></span></div>
            <div class="col"><span style="width:20px; height:20px;" data-bg-color="purple-love" class="gradient-purple-love d-block rounded-circle"></span>
            </div>
          </div> -->
          <!-- <div class="row p-1">
            <div class="col"><span style="width:20px; height:20px;" data-bg-color="black" class="bg-black d-block rounded-circle"></span></div>
            <div class="col"><span style="width:20px; height:20px;" data-bg-color="white" class="bg-grey d-block rounded-circle"></span></div>
            <div class="col"><span style="width:20px; height:20px;" data-bg-color="primary" class="bg-primary d-block rounded-circle"></span></div>
            <div class="col"><span style="width:20px; height:20px;" data-bg-color="success" class="bg-success d-block rounded-circle"></span></div>
            <div class="col"><span style="width:20px; height:20px;" data-bg-color="warning" class="bg-warning d-block rounded-circle"></span></div>
            <div class="col"><span style="width:20px; height:20px;" data-bg-color="info" class="bg-info d-block rounded-circle"></span></div>
            <div class="col"><span style="width:20px; height:20px;" data-bg-color="danger" class="bg-danger d-block rounded-circle"></span>
            </div> -->
          <!-- </div> -->
      <!--   </div> -->
        <!-- Sidebar Options Ends-->
      <!--   <hr> -->
        <!-- Sidebar BG Image Starts-->
<!--         <h6 class="text-center text-bold-500 mb-3 text-uppercase">Sidebar Bg Image</h6>
 -->
        <!-- Sidebar BG Image Ends-->
<!--         <hr>
 -->        <!-- Sidebar BG Image Toggle Starts-->
<!--         <div class="togglebutton">
          <div class="switch"><span>Sidebar Bg Image</span>
            <div class="float-right">
              <div class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0">
                <input id="sidebar-bg-img" type="checkbox" checked="" class="custom-control-input cz-bg-image-display">
                <label for="sidebar-bg-img" class="custom-control-label"></label>
              </div>
            </div>
          </div>
        </div>
 -->        <!-- Sidebar BG Image Toggle Ends-->
<!--         <hr>
 -->        <!-- Compact Menu Starts-->
<!--         <div class="togglebutton">
          <div class="switch"><span>Compact Menu</span>
            <div class="float-right">
              <div class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0">
                <input id="cz-compact-menu" type="checkbox" class="custom-control-input cz-compact-menu">
                <label for="cz-compact-menu" class="custom-control-label"></label>
              </div>
            </div>
          </div>
        </div>
 -->        <!-- Compact Menu Ends-->
<!--         <hr>
 -->        <!-- Sidebar Width Starts-->
<!--         <div>
          <label for="cz-sidebar-width">Sidebar Width</label>
          <select id="cz-sidebar-width" class="custom-select cz-sidebar-width float-right">
            <option value="small">Small</option>
            <option value="medium" selected="">Medium</option>
            <option value="large">Large</option>
          </select>
        </div>
 -->        <!-- Sidebar Width Ends-->


      </div>
    </div>
    <!-- Theme customizer Ends-->
    <!-- BEGIN VENDOR JS-->
     <script src="js/app.js"></script>
     <script src="https://unpkg.com/vue"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.js"></script>
    <script src="js/plantilla.js" type="text/javascript"></script>
    <script src="https://unpkg.com/vue-form-wizard/dist/vue-form-wizard.js"></script>

  </body>

<!-- Mirrored from pixinvent.com/apex-angular-4-bootstrap-admin-template/html-demo-1/ by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 08 Nov 2018 14:39:17 GMT -->
</html>
