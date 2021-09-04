<template>
	<div>
		<div class="wrapper nav-collapsed menu-collapsed" id="app" style="height: 1000px;">
			<!-- main menu-->
			<!--.main-menu(class="#{menuColor} #{menuOpenType}", class=(menuShadow == true ? 'menu-shadow' : ''))-->
			<div data-active-color="white" data-background-color="man-of-steel" data-image="img/sidebar-bg/01.jpg" class="app-sidebar" id="app">
				<!-- main menu header-->
				<!-- Sidebar Header starts-->
				<div class="sidebar-header">
					<div class="logo clearfix">
						<router-link :to="{ name: 'inicio' }" :class="'logo-text float-left'">
							<div class="logo-img"><img :src="base+'img/logo.png'"/>
							</div>
							<span class="text align-middle" style="font-size: 12px;">NANAIMO</span>
						</router-link>
						<a id="sidebarToggle" href="javascript:;" class="nav-toggle d-none d-sm-none d-md-none d-lg-block"><i data-toggle="expanded" class="ft-toggle-right toggle-icon"></i>
						</a>
						<a id="sidebarClose" href="javascript:;" class="nav-close d-block d-md-block d-lg-none d-xl-none"><i class="ft-x"></i>
						</a>
					</div>
				</div>
				<div class="sidebar-content" v-if="mostrarMenu">
					<div class="nav-container">
						<ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">
							<li class="has-sub nav-item">
								<router-link :to="{ name: 'estados' }">
									<i class="ft-grid"></i><span data-i18n="" class="menu-title">estados</span>
								</router-link>
							</li>

							<li class="has-sub nav-item">
							<a href="#"><i class="ft-phone-outgoing"></i><span data-i18n="" class="menu-title">Reservas</span></a>
							<ul class="menu-content">
								<li>
									<router-link :to="{ name: 'reservas' }"><i class="ft-phone-outgoing mr-2"></i><h6 style="font-size:14px;">Calendario</h6></router-link>
								</li>
								<li>
									<router-link :to="{ name: 'reservasfiltradas' }"><i class="ft-sliders mr-2"></i><h6 style="font-size:14px;">Filtradas</h6></router-link>
								</li>

						</ul>
					</li>



							<li class="has-sub nav-item">
								<a href="#"><i class="ft-settings"></i><span data-i18n="" class="menu-title">Configuración</span></a>
								<ul class="menu-content">

									<li>
										<router-link :to="{ name: 'cliente' }"><i class="ft-settings mr-2"></i><h6 style="font-size:14px;">Clientes</h6></router-link>
									</li>

									<li>
										<router-link :to="{ name: 'impuestostasas' }"><i class="ft-settings mr-2"></i><h6 style="font-size:14px;">Impuesto & Tasas</h6></router-link>
									</li>

									<li>
										<router-link :to="{ name: 'planestarifarios' }"><i class="ft-settings mr-2"></i><h6 style="font-size:14px;">Planes tarifarios</h6></router-link>
									</li>

									<li>
										<router-link :to="{ name: 'tarifas' }"><i class="ft-settings mr-2"></i><h6 style="font-size:14px;">Tarifas</h6></router-link>
									</li>

									<li>
										<router-link :to="{ name: 'tipohabitaciones' }" :class="'menu-item'"><i class="ft-settings mr-2"></i><h6 style="font-size:14px;">Tipo Habitaciones</h6></router-link>
									</li>
									<li>
										<router-link :to="{ name: 'habitaciones' }" :class="'menu-item'"><i class="ft-settings mr-2"></i><h6 style="font-size:14px;">Habitaciones</h6></router-link>
									</li>

									<li>
										<router-link :to="{ name: 'puntosdeventas' }"><i class="ft-settings mr-2"></i><h6 style="font-size:14px;">Puntos de Venta</h6></router-link>
									</li>

									<li>
										<router-link :to="{ name: 'categoriasproductos' }"><i class="ft-settings mr-2"></i><h6 style="font-size:14px;">Categorias</h6></router-link>
									</li>


									<li>
										<router-link :to="{ name: 'productos' }"><i class="ft-settings mr-2"></i><h6 style="font-size:14px;">Productos</h6></router-link>
									</li>

									<li>
										<router-link :to="{ name: 'tipopagos' }"><i class="ft-settings mr-2"></i><h6 style="font-size:14px;">Tipos de pago</h6></router-link>
									</li>


								</ul>
							</li>
							<li class="has-sub nav-item" v-if="sesion_supervisor==true">
								<router-link :to="{ name: 'hoteles' }">
									<i class="ft-home"></i><span data-i18n="" class="menu-title">Hoteles</span>
								</router-link>
							</li>
							<li class="has-sub nav-item">
								<router-link :to="{ name: 'inicio' }"><i class="ft-cpu"></i><span data-i18n="" class="menu-title">Inicio</span></router-link>
								<ul class="menu-content">

									<li>
										<router-link :to="{ name: 'user' }" :class="'menu-item'"><i class="ft-users"></i><h6 style="font-size:14px;">Usuarios</h6></router-link>
									</li>
									<li>
										<router-link :to="{ name: 'rol' }" :class="'menu-item'"><i class="ft-watch"></i><h6 style="font-size:14px;">Roles</h6></router-link>
									</li>


								</ul>
							</li>

						</ul>
					</div>
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
								<!-- <li class="nav-item mr-2 d-none d-lg-block">
									<a id="navbar-fullscreen" href="javascript:;" class="nav-link apptogglefullscreen">
										<i class="ft-maximize font-medium-3 blue-grey darken-4"></i>
										<p class="d-none">fullscreen</p>
									</a>
								</li> -->
								<!-- <li class="dropdown nav-item">
									<a id="dropdownBasic3" href="#" data-toggle="dropdown" class="nav-link position-relative dropdown-toggle"><i class="ft-flag font-medium-3 blue-grey darken-4"></i><span class="selected-language d-none"></span></a>
									<div class="dropdown-menu dropdown-menu-right text-left"><a href="javascript:;" class="dropdown-item py-1"><img src="img/flags/us.png" class="langimg"/><span> English</span></a><a href="javascript:;" class="dropdown-item py-1"><img src="img/flags/es.png" class="langimg"/><span> Spanish</span></a><a href="javascript:;" class="dropdown-item py-1"><img src="img/flags/br.png" class="langimg"/><span> Portuguese</span></a><a href="javascript:;" class="dropdown-item"><img src="img/flags/de.png" class="langimg"/><span> French</span></a></div>
								</li> -->
								<!-- <li class="dropdown nav-item">
									<a id="dropdownBasic2" href="#" data-toggle="dropdown" class="nav-link position-relative dropdown-toggle">
										<i class="ft-bell font-medium-3 blue-grey darken-4"></i><span class="notification badge badge-pill badge-danger">5</span>
										<p class="d-none">Notifications</p>
									</a>
									<div class="notification-dropdown dropdown-menu dropdown-menu-right">
										<div class="noti-list"><a class="dropdown-item noti-container py-3 border-bottom border-bottom-blue-grey border-bottom-lighten-4"><i class="ft-bell info float-left d-block font-large-1 mt-1 mr-2"></i><span class="noti-wrapper"><span class="noti-title line-height-1 d-block text-bold-400 info">New Order Received</span><span class="noti-text">Lorem ipsum dolor sit ametitaque in, et!</span></span></a><a class="dropdown-item noti-container py-3 border-bottom border-bottom-blue-grey border-bottom-lighten-4"><i class="ft-bell warning float-left d-block font-large-1 mt-1 mr-2"></i><span class="noti-wrapper"><span class="noti-title line-height-1 d-block text-bold-400 warning">New User Registered</span><span class="noti-text">Lorem ipsum dolor sit ametitaque in </span></span></a><a class="dropdown-item noti-container py-3 border-bottom border-bottom-blue-grey border-bottom-lighten-4"><i class="ft-bell danger float-left d-block font-large-1 mt-1 mr-2"></i><span class="noti-wrapper"><span class="noti-title line-height-1 d-block text-bold-400 danger">New Order Received</span><span class="noti-text">Lorem ipsum dolor sit ametest?</span></span></a><a class="dropdown-item noti-container py-3"><i class="ft-bell success float-left d-block font-large-1 mt-1 mr-2"></i><span class="noti-wrapper"><span class="noti-title line-height-1 d-block text-bold-400 success">New User Registered</span><span class="noti-text">Lorem ipsum dolor sit ametnatus aut.</span></span></a></div>
										<a class="noti-footer primary text-center d-block border-top border-top-blue-grey border-top-lighten-4 text-bold-400 py-1">Read All Notifications</a>
									</div>
								</li> -->
								<li class="dropdown nav-item">
									<a id="dropdownBasic3" href="#" data-toggle="dropdown" class="nav-link position-relative dropdown-toggle">
										<i class="ft-user font-medium-3 blue-grey darken-4"></i>
										<p class="d-none">User Settings</p>
									</a>
									<div ngbdropdownmenu="" aria-labelledby="dropdownBasic3" class="dropdown-menu text-left dropdown-menu-right">
										<!-- <a href="javascript:;" class="dropdown-item py-1"><i class="ft-settings mr-2"></i><span>Salir</span></a><a href="javascript:;" class="dropdown-item py-1"><i class="ft-edit mr-2"></i><span>Edit Profile</span></a><a href="javascript:;" class="dropdown-item py-1"><i class="ft-mail mr-2"></i><span>My Inbox</span></a> -->
										<div class="dropdown-divider"></div>
										<a href="#" @click.prevent="logout" class="dropdown-item"><i class="ft-power mr-2"></i><span>Logout</span></a>
									</div>
								</li>
								<!-- <li class="nav-item d-none d-lg-block">
									<a href="javascript:;" class="nav-link position-relative notification-sidebar-toggle">
										<i class="ft-align-left font-medium-3 blue-grey darken-4"></i>
										<p class="d-none">Notifications Sidebar</p>
									</a>
								</li> -->
							</ul>
						</div>
					</div>
				</div>
			</nav>
			<div class="main-panel">
				<div class="main-content">
					<div class="content-wrapper">
						<div id="app">
							<router-view class="font"></router-view>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		data: () => ({
             csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
             }),
	    data()
		{
			return {
                base:'',
                sesion_supervisor:'',
                mostrarMenu:true
            }
		},
		computed: {
			count () {
			this.$store.state;
			return this.$store.state.posts
			},
		},
	    methods:{
	        cargarmenu()
	        {

	        },
					UsuarioSupervisor() {
			        let me = this;
			        var url = 'usuario_supervisor';
			        axios.get(me.base + url).then(function(response) {
			                var respuesta = response.data.datos;
											me.sesion_supervisor = respuesta;

			            })
			            .catch(function(error, otracosa) {
			                // var response = error.response.data;
			            });
			    },

	        logout:function(){
               axios.post('logout').then(response => {
                  if (response.status === 302 || 401) {
                    console.log('logout')
                    window.location.href = "/login"
                  }
                  else {
                    // throw error and go to catch block
                  }
                }).catch(error => {

              });
            },
	    },
	    mounted()
			{
        this.base=base;
        this.cargarmenu();
        this.UsuarioSupervisor();
			}
	}
</script>
