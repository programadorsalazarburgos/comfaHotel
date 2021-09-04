<!DOCTYPE html>
<html lang="es" class="loading">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
		<meta name="description" content="Apex admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
		<meta name="keywords" content="admin template, Apex admin template, dashboard template, flat admin template, responsive admin template, web app">
		<meta name="author" content="PIXINVENT">
		<meta name="userId" content="{{Auth::check() ? Auth::user()->id : ''}}">
		<title>NANAIMO</title>
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
		<link rel='stylesheet' href='https://unpkg.com/element-ui@2.11.1/lib/theme-chalk/index.css'>
		<link rel="stylesheet" type="text/css" href="{{ asset('css/plantilla.css') }}">
		<link rel="stylesheet" type="text/css" href="{{url('')}}/app-assets/fonts/feather/style.min.css">
		<link rel="stylesheet" type="text/css" href="{{url('')}}/app-assets/fonts/simple-line-icons/style.css">
		<link rel="stylesheet" type="text/css" href="{{url('')}}/app-assets/fonts/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="{{url('')}}/app-assets/css/app.css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
		<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
		<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/vue-select/3.10.8/vue-select.css" rel="stylesheet"> -->
		<link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/css-loader/3.3.3/css-loader.css" rel="stylesheet">

		<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css"> -->
		<link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
		<script src="https://kit.fontawesome.com/e62e5cd685.js"></script>

		<script>
			var base='{{url('')}}/';
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
    <div id="app">
      <app></app>
    </div>
		<script src="{{url('')}}/js/app.js?n=1"></script>
		<script src="https://unpkg.com/vue"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/prettify/r298/prettify.min.js"></script>
		<script src="{{url('')}}/js/plantilla.js" type="text/javascript"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
		<script src="https://unpkg.com/vue-form-wizard/dist/vue-form-wizard.js"></script>
		<script src='https://cdnjs.cloudflare.com/ajax/libs/element-ui/2.11.1/index.js'></script>
		<script src='https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.15/lodash.min.js'></script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>

			window.backupAlert = window.alert;
			window.alert = function () {return true};
			var scripts =  document.getElementsByTagName('script');
			var torefreshs = ['app.js'] ; // list of js to be refresh
			var key = 1; // change this key every time you want force a refresh
			for(var i=0;i<scripts.length;i++){
			   for(var j=0;j<torefreshs;j++){
			      if(scripts[i].src && (scripts[i].src.indexOf(torefreshs[j]) > -1)){
			        new_src = scripts[i].src.replace(torefreshs[j],torefreshs[j] + 'k=' + key );
			        scripts[i].src = new_src; // change src in order to refresh js
			      }
			   }
			}
		</script>
>	</body>
</html>
