<template>
	<main class="main">
		<div class="col-12">
			<div class="content-header">Creación de Reservas Manuales</div>
		</div><br>
		<button type="submit" class="btn gradient-ibiza-sunset" @click="nueva_busqueda" style="background-image: linear-gradient(45deg, rgb(131, 239, 64), rgb(202, 197, 47)); color: white;">Nueva Busqueda <i class="fa fa-search" aria-hidden="true"></i></button>
		<section id="extended">
			<form action="" method="post" @submit.prevent="cargarTipoHabitaciones" enctype="multipart/form-data" class="form-horizontal">
				<div class="row">
					<div class="col-sm-6">
						<div class="card">
							<br>
							<div class="card-body">
								<div class="card-body">
									<div class="container-fluid">
										<div class="row">
											<div class="col-md-12">
												<div class="row">
													<div class="col-md-4">
														<div class="form-group">
															<label for="userinput1">Fuente de Reserva</label>
															<select  class="form-control" v-model="fuente" required>
																<option value="0" disabled>Seleccione</option>
																<option v-for="fuente in fuentes" :key="fuente.id" :value="fuente.id" v-text="fuente.descripcion"></option>
															</select>
														</div>
													</div>
													<div class="col-md-4">
														<label for="">Fecha Ingreso</label>
														<datepicker @opened="datepickerAbierto" @selected="fechaSeleccionada" @closed="datepickerCerrado" :format="customFormatter" :disabled="DisabledFecha" required :typeable="true"
														  v-model="fecha_ingreso" :bootstrap-styling="true"></datepicker>
													</div>
													<div class="col-md-4">
														<label for="">Fecha Salida</label>
														<datepicker :format="customFormatter" :disabled="DisabledFecha" @closed="datepickerCerradoSalida" required :typeable="true" v-model="fecha_salida" :bootstrap-styling="true"></datepicker>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="card">
							<br>
							<div class="card-body">
								<div class="card-body">
									<div class="container-fluid">
										<div class="row">
											<div class="col-md-12">
												<div class="row">
													<div class="col-md-4">
														<label for="">Cantidad Adultos</label>
														<input required type="number" :disabled="isDisabledAdultos"  v-model="cantidad_adultos" minlength="1" name="adultos" class="form form-control">
														<ul class="list-group">
															<li class="list-group-item list-group-item-danger" v-for="error in errors.collect('adultos')" :key="error">{{ error }}</li>
														</ul>
													</div>
													<div class="col-md-4">
														<label for="">Cantidad Niños</label>
														<input type="number" v-model="cantidad_ninos" :disabled="isDisabledNinos"  name="ninos" class="form form-control">
													</div>
													<div class="col-md-4">
														<div class="form-group">
															<button type="submit" style="margin-top: 32px;" class="btn btn-info">Disponibilidad <i class="fa fa-bed"></i></button>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</section>
		<form action="" method="post" @submit.prevent="AgregarReserva" enctype="multipart/form-data" class="form-horizontal">
			<section id="extended">
				<div class="row">
					<div class="col-sm-6" v-if="mostrarModulo===1">
						<div class="card">
							<br>
							<div class="card-body">
								<div class="card-body">
									<div class="container-fluid">
										<div class="row">
											<div class="col-md-12">
												<div class="row">
													<div class="col-md-6">
														<label for="">Tipo Habitación</label>
														<select v-on:change="selectTipoHabitacion()" class="form-control" v-model="tipoHabitacion" required>
															<option value="0" disabled>Seleccione</option>
															<option v-for="data in arrayTipoHabitaciones" :key="data.id" :value="{id_tipo_habitacion: data.id, nombre_tipo_habitacion: data.nombre}" v-text="data.nombre"></option>
														</select>
													</div>
													<div class="col-md-6">
														<label for="">Habitaciones</label>
														<select class="form-control" v-model="habitacion" required>
															<option value=" 0" disabled>Seleccione</option>
															<option v-for="(habitacion,index) in arrayHabitacion" :key="index" :value="{id_habitacion: habitacion.id, numero_habitacion: habitacion.numero}" v-text="habitacion.numero"></option>
														</select>
														<br>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<br>
							</div>
						</div>
					</div>
					<div class="col-sm-6" v-if="mostrarModulo===1">
						<div class="card">
							<br>
							<div class="card-body" style="height: 90 !important;">
								<div class="card-body" style="height: 90 !important;">

									<div class="container-fluid">
										<div class="row">
											<div class="col-md-12">
												<div class="row">
													<div class="col-md-6">
														<div class="custom-search">
															<label for="">Plan Tarifario</label>
															 <!-- <input type="text" class="custom-search-input" placeholder="Enter your email"> -->
															<select name="year" v-on:change="selectPlan()" v-model="plan_tarifa" required class="custom-search-input">
																<option v-for="data in arrayPlanes" :key="data.id" :value="{id_plan: data.id, ocupacion_tarifa: data.ocupacion_tarifa, menores_incluidos: data.menores_incluidos, nombre_plan: data.nombre}">
																	{{data.nombre}}
																</option>
															</select>
														  <button class="custom-search-botton"   @click="abrirModal('data','verDetalle')" type="button">Ver Detalle</button>
														</div>
														<br>
													</div>
													<div class="col-md-4">
														<div class="form-group">
														<h4 style="margin-top: 36px;margin-left: 33px;"><span class="badge badge-danger mb-1 mr-2">Total Reserva: {{resultado_reserva}}</span></h4>
														</div>
													</div><br>
												</div>
											</div>

										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-12">
						<div class="card">
							<br>
							<div class="card-body" style="height: 90 !important;">
								<div class="card-body" style="height: 90 !important;">

									<div class="container-fluid">
										<div class="row">
											<div class="col-md-12">
												<div class="row">
													<div class="col-md-4">
														<button v-on:click="nuevoCliente()" type="button" style="margin-top: 13px;margin-left: 10px;" class="btn btn-success">Agregar Cliente <i class="fa fa-plus-square"></i></button>
														<v-select :options="arrayClientes" :custom-label="nameWithLang" placeholder="Seleccionar:" label="name" track-by="name" v-model="cliente">
															<template #search="{attributes, events}">
																<input class="vs__search" :required="!cliente" v-bind="attributes" v-on="events" />
															</template>
														</v-select>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<section id="extended" v-if="mostrarModulo===1">
				<div class="card">
					<br>
					<div class="card-body">
						<div class="card-body">
							<div class="container-fluid">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-4">
												<button style="background-image: linear-gradient(45deg, #65ec55, #6dc138);" type="submit" class="btn btn-raised gradient-ibiza-sunset white sidebar-shadow">Agregar Reserva <i
													  class="fa fa-american-sign-language-interpreting" aria-hidden="true"></i></button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<br>
					</div>
				</div>
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12">

						</div>
					</div>
				</div>

			</section>
		</form>
		<section>

			<div class="modal fade bs-example-modal-lg" aria-labelledby="myLargeModalLabel" :class="{'mostrar' : modal}" role="dialog" style="display: none; height: 10000px;" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content" style="height:512px;">
						<div class="modal-header">
							<h4 class="modal-title" id="myLargeModalLabel" style="color:black;">{{tituloModal}}</h4>
							<button type="button" @click="cerrarModal()" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						</div>
						<div class="modal-body">
							<div class="col-12">
								<div class="card">
									<div class="card-content">
										<div class="card-body">
											<div class="table-responsive">
												<table class="table m-0">
													<thead class="thead-light">
														<tr>
															<th>Fecha Reserva</th>
															<th>Tarifa</th>
														</tr>
													</thead>
													<tbody>
														<tr v-for="data in dataArrayDetalle">
															<th scope="row"><span class="badge badge-info mb-1 mr-2">{{data.Fecha}}</span></th>
															<td><span class="badge badge-danger mb-1 mr-2">{{data.Tarifa}}</span></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-danger" @click="cerrarModal()" data-dismiss="modal">Cerrar <i class="fa fa-window-close"></i></button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade bs-example-modal-lg" aria-labelledby="myLargeModalLabel" :class="{'mostrar' : modalDetalle}" role="dialog" style="display: none; height: 10000px;" aria-hidden="true">
				<div class="modal-dialog modal-lg">
					<div class="modal-content" style="height:512px;">
						<div class="modal-header">
							<h4 class="modal-title" id="myLargeModalLabel" style="color:black;">{{tituloModal}}</h4>
							<button type="button" @click="cerrarModalDetalle()" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						</div>
						<div class="modal-body">
							<div class="col-12">
								<div class="card">
									<div class="card-content">
										<div class="card-body">
											<div class="table-responsive">
												<table class="table m-0">
													<thead class="thead-light">
														<tr>
															<th>Fecha Reserva</th>
															<th>Tarifa</th>
														</tr>
													</thead>
													<tbody>
														<tr v-for="data in ArrayDetalles">
															<th scope="row"><span class="badge badge-info mb-1 mr-2">{{data.Fecha}}</span></th>
															<td><span class="badge badge-danger mb-1 mr-2">{{data.Tarifa}}</span></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								<button type="button" class="btn btn-danger" @click="cerrarModalDetalle()" data-dismiss="modal">Cerrar <i class="fa fa-window-close"></i></button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--Nuevo cliente-->
			<div class="modal fade" tabindex="-1" :class="{'mostrar' : modalCliente}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
				<div class="modal-dialog modal-primary modal-lg" role="document">
					<div class="modal-content" style="height: 550px;">
						<div class="modal-header">
							<h5 class="modal-title"><span class="badge badge-primary mb-1 mr-2" style="background-color: #1976d2;">Crear Cliente <i class="fa fa-users"></i></span></h5>
							<button type="button" class="close" v-on:click="cerrarModalCliente()" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
						</div>
						<div class="modal-body">
							<!-- <crearcliente></crearcliente> -->
							<form class="form" action="" method="post" @submit.prevent="registrarCliente" enctype="multipart/form-data">
								<div class="form-body">
									<h4 class="form-section"><i class="fa fa-address-book"></i> Datos</h4>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="userinput1">Tipo de cliente</label>
												<select v-on:change="toggle(tipoCliente)" class="form-control" v-model="tipoCliente" :required="tipoRequerido">
													<option value="0" disabled>Seleccione</option>
													<option v-for="tipoCliente in arrayTipoCliente" :key="tipoCliente.id" :value="tipoCliente.id" v-text="tipoCliente.nombre"></option>
												</select>
											</div>
										</div>
									</div>
									<template v-if="mostrarVista===1">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="userinput1">Nombres</label>
													<input type="text" v-model="nombres" :required="nombreRequerido" class="form-control border-primary">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="userinput2">Apellidos</label>
													<input type="text" v-model="apellidos" :required="apellidoRequerido" id="userinput2" class="form-control border-primary" name="company">
												</div>
											</div>
										</div>
										</template>

									<template v-if="mostrarVista===2">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="userinput1">Nombre de la Empresa</label>
													<input type="text" v-model="nombres" :required="empresaRequerido" class="form-control border-primary">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="userinput2">N<sup>o</sup> identificación Fiscal de la Empresa</label>
												<input type="text" v-model="identificacion_fiscal" class="form-control border-primary">
												</div>
											</div>
										</div>
										</template>

									<template v-if="mostrarVista===3">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="userinput1">Nombre de la Agencia</label>
													<input type="text" v-model="nombres" :required="agenciaRequerido" class="form-control border-primary">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="userinput2">N<sup>o</sup> identificación Fiscal de la Agencia</label>
												<input type="text" v-model="identificacion_fiscal" class="form-control border-primary">
												</div>
											</div>
										</div>
										</template>




										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="userinput1">Correo electrónico</label>
													<input type="text" v-model="correo" :required="correoRequerido" class="form-control border-primary">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="userinput2">Telefono</label>
													<input type="text" v-model="telefono" id="userinput4" class="form-control border-primary" name="nickname">
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="userinput1">Celular</label>
													<input type="text" v-model="celular" class="form-control border-primary">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="userinput2">Fecha de Nacimiento</label>
														<datepicker :format="customFormatter" v-model="fecha_nacimiento" :bootstrap-styling="true"></datepicker>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="userinput1">Género</label>
													<select class="form-control" v-model="sexo">
														<option value="0" disabled>Seleccione</option>
														<option v-for="sexo in arraySexo" :key="sexo.id" :value="sexo.id" v-text="sexo.nombre"></option>
													</select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="userinput2">N<sup>o</sup> fiscal del huesped</label>
														<input type="text" v-model="identificacion_fiscal" class="form-control border-primary">
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<label for="userinput1">Dirección del huesped</label>
													<input type="text" v-model="direccion" class="form-control border-primary">
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="userinput3">Pais</label>
													<select class="form-control" v-model="pais" :required="paisRequerido">
														<option value="0" disabled>Seleccione</option>
														<option v-for="nacionalidad in arrayPais" :key="nacionalidad.id" :value="nacionalidad.id" v-text="nacionalidad.nombre_pais"></option>
													</select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="userinput4">Estado</label>
													<input type="text" v-model="estado" id="userinput4" class="form-control border-primary" name="nickname">
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="userinput3">Código Postal</label>
													<input type="text" v-model="postal" id="userinput4" class="form-control border-primary" name="nickname">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="userinput4">Documento del Huésped</label>
													<select class="form-control" v-model="tipoDocumento">
														<option value="0" disabled>Seleccione</option>
														<option v-for="tipoDocumento in arrayTipoDocumento" :key="tipoDocumento.id" :value="tipoDocumento.id" v-text="tipoDocumento.descripcion"></option>
													</select>
												</div>
											</div>
										</div>




										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="userinput3">N<sup>o</sup> Documento Huésped</label>
													<input type="number" v-model="documento" id="userinput4" class="form-control border-primary" name="nickname">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="userinput4">Fecha de emisión del documento</label>
													<datepicker :format="customFormatter" v-model="fecha_emision" :bootstrap-styling="true"></datepicker>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label for="userinput3">País expedidor del Documento</label>
													<select class="form-control" v-model="pais_expedidor">
														<option value="0" disabled>Seleccione</option>
														<option v-for="nacionalidad in arrayPais" :key="nacionalidad.id" :value="nacionalidad.id" v-text="nacionalidad.nombre_pais"></option>
													</select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label for="userinput4">Fecha de caducidad del Documento</label>
													<datepicker :format="customFormatter" v-model="fecha_caducidad" :bootstrap-styling="true"></datepicker>
												</div>
											</div>
										</div>

								</div>
								<div class="form-actions right">
									<button type="submit" class="btn btn-raised btn-primary" v-if="tipoAccionF==1">
										<i class="fa fa-check-square-o"></i> Guardar
									</button>
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" v-on:click="modalCliente=false">Cerrar</button>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title">Reservas Grupales y Walkins</h4>
						</div>
						<div class="card-body" style="height: auto !important;">
							<div class="card-body" style="height: auto !important;">
								<div class="card-block">
									<table class="table table-responsive-md-md text-center">
										<thead>
											<tr>

												<th>Ingeso</th>
												<th>Salida</th>
												<th>Tipo Habitación</th>
												<th>Habitación</th>
												<th>Total Ocupantes</th>
												<th>Tarifa</th>
												<th>Total</th>
												<th></th>

											</tr>
										</thead>
										<tbody>
											<tr v-for="(dato, index) in ArrayReservas" :key="index">
												<td v-text="dato.ingreso"></td>
												<td v-text="dato.salida"></td>
												<td v-text="dato.nombre_tipo_habitacion"></td>
												<td v-text="dato.nombre_habitacion"></td>
												<td v-text="dato.total_ocupantes"></td>
												<td v-text="dato.nombre_plan"></td>
												<td v-text="dato.total_reserva"></td>
												<td>
													<a data-original-title="" style="color: #2196f3 !important;" @click="abrirModal('data','actualizar',dato)" title="" class="warning p-0"><i class="fa fa-binoculars font-medium-3 mr-2"></i></a>
													<a data-original-title="" v-on:click="eliminarReserva(dato, index)" title="" class="danger p-0"><i class="fa fa-trash-o font-medium-3 mr-2"></i></a>
												</td>
											</tr>


										</tbody>
									</table>
									<div class="container-fluid">
										<div class="row">
											<div class="col-md-12">
												<div class="row" v-if="ArrayReservas.length>0">
													<button type="button" v-if="tipoGuardarReserva==1" @click="GuardarReservas()" name="button" style="background-image: linear-gradient(45deg, rgb(88, 224, 205), rgb(97, 164, 222));"
													  class="btn btn-raised gradient-ibiza-sunset white sidebar-shadow">Guardar Reservas</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</section>
	</main>
</template>
<script>
	import VueSweetAlert from 'vue-sweetalert';
	import swal from 'vue-sweetalert2';
	import Vue from 'vue';
	import Datepicker from 'vuejs-datepicker';
	import moment from 'moment'
	import Autocomplete from 'vuejs-auto-complete';
	import Query from 'json-object-query'
	import vSelect from "vue-select";
	Vue.component("v-select", vSelect);
	import "vue-select/dist/vue-select.css";
	const Swal = require('sweetalert2')

	window.swal = swal
	export default {
		data() {
			return {
				tipoRequerido: true,
				nombreRequerido: false,
				apellidoRequerido: false,
				empresaRequerido: false,
				agenciaRequerido: false,
				correoRequerido: false,
				paisRequerido: true,
				tipoCliente: '',
				apellidos: '',
				identificacion_fiscal: '',
				nombres: '',
				identificacion_fiscal: '',
				correo: '',
				telefono: '',
				celular: '',
				fecha_nacimiento: '',
				sexo: '',
				identificacion_fiscal: '',
				direccion: '',
				pais: '',
				estado: '',
				postal: '',
				fuentes: [
			    { id: 10, descripcion: 'Directa Teléfono' },
			    { id: 11, descripcion: 'Directa Agencia' },
			    { id: 12, descripcion: 'Directa Empresa' },
			    { id: 13, descripcion: 'Directa Walkin' },
			  ],
				tipoDocumento: '',
				documento: '',
				fecha_emision: '',
				pais_expedidor: '',
				fecha_caducidad: '',
				ArrayDetalles: [],
				dataArrayDetalle: [],
				checkedCategories: [],
				arrayDetalleReserva: [],
				mainCategories: [{
					merchantId: '1'
				}, {
					merchantId: '2'
				}],
				ArrayReservas: [],
				arrayImpuestosHabitaciones: [],
				plan_tarifa: '',
				fuente: '',
				tarifa: '',
				arrayPlanes: [],
				ocupacion: '',
				cliente: '',
				adultos_extra: '',
				no_contacto: '',
				ninos_extra: '',
				nombre_cliente: '',
				mostrarModulo: 0,
				tipoCarga: 0,
				mostrarVista: 0,
				DisabledFecha: false,
				total_tarifa_reserva_dia: 0.0,
				total_adultos_extra_dia: 0.0,
				total_ninos_extra_dia: 0.0,
				resultado_x_fecha: 0.0,
				tipoGuardarReserva: 1,
				tipoFacturar: 1,
				resultado_ocupacion: '',
				resultado_reserva: 0.0,
				BotonesPrefacturacion: 1,
				tipoAccionGenerar: 0,
				AccionImpuesto: 1,
				ish: [{
					name: 2
				}],
				resultado_ocupacion_dos: '',
				no_contrato: 2,
				valorImpuesto: 0,
				totalParcial: 0.0,
				aplicar: true,
				quitar: false,
				aplicar2: true,
				quitar2: false,
				isDisabledAdultos: false,
				isDisabledNinos: false,
				unidad: '',
				tipoAccionMensaje: 1,
				id_consumo_extra: '',
				reserva_id_detalle: '',
				total_consumo_extra: '',
				identificador_pagador: '',
				identificador_cliente: '',
				reserva_detalle_r1: '',
				tituloPagadorNombre: '',
				tituloPagadorApellido: '',
				tituloNumero: '',
				comentario: '',
				tituloFecha: '',
				total_consumo1: '',
				total_consumo2: '',
				impuesto: '',
				errorPersona: 0,
				errorMostrarMsjPersona: [],
				arrayMensajesFactura: [],
				arrayConsumosExtras: [],
				modalCliente: false,
				producto: '',
				tarifa: '',
				total_extras: 0.0,
				precio_producto: '',
				puntoVenta: '',
				grupos_select: '',
				resultado: 0.0,
				resultado_neto: 0.0,
				base: '',
				categoria: '',
				arrayClientes: [],
				TotalFacturacion: [],
				data: '',
				ReservasGrupos: [],
				arrayCategoriasProductos: [],
				arrayImpuestosProductos: [],
				ReservasPagadores: [],
				arrayProductos: [],
				GruposHabitaciones: [],
				Facturacion: [],
				modal: 0,
				modalDetalle: 0,
				modalNuevaTarifa: 0,
				modalProductos: 0,
				modalProductos2: 0,
				modalInfo: 0,
				pago_a_realizar: 0,
				tipo_pago: null,
				resultado_pagos: 0.0,
				tipoAccion: 0,
				TipoPagos: [],
				Pagos: [],
				prueba: [],
				arrayConsumosExtras2: [],
				GruposHabitaciones: [],
				Impuestos: [],
				tituloModal: '',
				valor_a_pagar_f: '',
				diferencia_pagos: 0.0,
				options: [],
				impuesto_valor: 13,
				id_reservas_grupo: '',
				reserva_precio_bruto: '',
				tipoCliente: 0,
				modalCliente: false,
				arrayClientes: [],
				cantidad_adultos: 1,
				cantidad_ninos: 0,
				fecha_ingreso: '',
				fecha_salida: '',
				base: '',
				habitacion: '',
				ArrayDatos: [],
				arrayTipoHabitaciones: [],
				arrayHabitacion: [],
				valor: '',
				tipoHabitacion: '',
				impuestoValor: 0,
				selected: 0,
				tipoGuardar: 2,
				disablebutton: false,
				submitted: false,
				pagination: {
					'total': 0,
					'current_page': 0,
					'per_page': 0,
					'last_page': 0,
					'from': 0,
					'to': 0,
				},
				offset: 3,
				criterio: 'valor',
				buscar: '',
				image: '',
				impuesto_id: '',
				tipoDocumento: '',
				no_documento: '',
				nombre_empresa: '',
				no_empresa: '',
				nombre_agencia: '',
				no_agencia: '',
				lugar: '',
				codigo_postal: '',
				pais: '',
				calle: '',
				departamento: '',
				calle_factura: '',
				lugar_factura: '',
				codigo_postal_factura: '',
				pais_factura: '',
				departamento_factura: '',
				comentarios: '',
				descripcion: '',
				impuesto_valor_seleccionado: '',
				arrayCategoria: [],
				arrayCliente: [],
				arrayTipoCliente: [],
				arrayPais: [],
				arrayDepartamento: [],
				arrayDepartamentoFactura: [],
				arrayTipoContacto: [],
				arrayTipoDocumento: [],
				arraySexo: [],
				tipoAccion: 0,
				impuestoProducto: '',
				resultado_factura: 0.0,
				tipoAccionF: 1,
				errorCategoria: 0,
				result: 0,
				errorMostrarMsjCategoria: [],
				pagination: {
					'total': 0,
					'current_page': 0,
					'per_page': 0,
					'last_page': 0,
					'from': 0,
					'to': 0,
				},
				offset: 3,
				criterio: 'nombre',
				impuesto: '',
				buscar: '',
				impuesto_ish: '',
				mensaje_id: '',
				impuesto_servicio: '',
				isOpen: '',
				nombres: '',
				apellidos: '',
				sexo: '',
				venta: '',
				nacionalidad: '',
				formula: '',
				titulo: '',
				isOpen2: '',
				isOpen3: '',
				fecha_nacimiento: '',
				apartments: [],
				ArrayPuntosVenta: [],
				arrayImpuestos: [],
				tipoCliente: 0,
				cliente_pago: '',
				factura_idPago: '',
				idTotalPago: '',
				numeroPago: '',
				ValorPagado: '',
				nombrePagador: '',
				factura_idClave: '',
				no_pagadores: '',
				impuestorReserva: '',
				total_extras_neto: '',
				clave_reserva: '',
				cliente_id: '',
			}
		},
		computed: {
			isActived: function() {
				return this.pagination.current_page;
			},
			//Calcula los elementos de la paginación
			pagesNumber: function() {
				if (!this.pagination.to) {
					return [];
				}

				var from = this.pagination.current_page - this.offset;
				if (from < 1) {
					from = 1;
				}

				var to = from + (this.offset * 2);
				if (to >= this.pagination.last_page) {
					to = this.pagination.last_page;
				}

				var pagesArray = [];
				while (from <= to) {
					pagesArray.push(from);
					from++;
				}
				return pagesArray;

			}
		},

		components: {
			Datepicker,
			Autocomplete
		},

		methods: {
			handleSubmit(e) {},
			customFormatter(date) {
				return moment(date).format('YYYY-MM-DD');
			},

			convert(str) {
				var date = new Date(str),
					mnth = ("0" + (date.getMonth() + 1)).slice(-2),
					day = ("0" + date.getDate()).slice(-2);
				return [date.getFullYear(), mnth, day].join("-");
			},

			toggle: function(tipoCliente) {
				let me = this;
				if (tipoCliente == 1) {
					me.mostrarVista = 1
					me.nombreRequerido = true;
					me.apellidoRequerido = true;
					me.correoRequerido = true;
					me.paisRequerido = true;
					me.empresaRequerido = false;
					me.agenciaRequerido = false;

				}
				if (tipoCliente == 2) {
					me.mostrarVista = 2
					me.nombreRequerido = false;
					me.apellidoRequerido = false;
					me.correoRequerido = true;
					me.paisRequerido = true;
					me.empresaRequerido = true;
					me.agenciaRequerido = false;

				}
				if (tipoCliente == 3) {
					me.mostrarVista = 3
					me.nombreRequerido = false;
					me.apellidoRequerido = false;
					me.correoRequerido = true;
					me.paisRequerido = true;
					me.empresaRequerido = false;
					me.agenciaRequerido = true;

				}
			},


			GuardarReservas() {
				let me = this;
					axios.post(me.base + '/reservas/reservas_manuales', {
						'datos': me.ArrayReservas,
						'cliente_id': me.cliente.code,
						'fuente_id': me.fuente 
					}).then(function(response) {
						Vue.swal("Muy bien!", "Realizo reserva!", "success");
						me.UpdateInventarios(me.ArrayReservas);
						me.ArrayReservas = [];
						me.tipoHabitacion = '';
						me.habitacion = '';
						me.cantidad_adultos = 1;
						me.cantidad_ninos = 0;
						me.plan_tarifa = '';
						me.tarifa = 0;
						me.fecha_ingreso = '';
						me.fecha_salida = '';
						me.total_facturas(factura_id)
					}).catch(function(error) {
						console.log(error);
					});

			},//
			async UpdateInventarios(args) {
				let me = this;
				axios.post(me.base + "/reservas/modificar_inventario_manual", {
						data: args
					})
					.then(function(response) {})
					.catch(function(error, otracosa) {});
			},
			nueva_busqueda()
			{
				let me = this;
				me.isDisabledAdultos = false;
				me.isDisabledNinos = false;
				me.mostrarModulo = 0;
				me.fecha_ingreso = '';
				me.fecha_salida = '';
				me.cantidad_adultos = '';
				me.cantidad_ninos = '';
				me.arrayTipoHabitaciones = [];
				me.arrayHabitacion = [];
				me.arrayPlanes = [];
				me.resultado_reserva = 0.0;
			},
			eliminarReserva(reservas, index) {
				let me = this;
				me.ArrayReservas.splice(index, 1);
			},
			selectPlanes(tipohabitacion) {
			
				let me = this;
				var url = '/planes_tarifarios/obtener?tipo_habitacion_id=' + tipohabitacion;
				axios.get(me.base + url).then(function(response) {
						console.log(response, "planes");
						var respuesta = response.data;
						me.arrayPlanes = respuesta.datos;
					})
					.catch(function(error) {
						var response = error.response.data;
					});
			},

			AgregarReserva() {
				let me = this;
				if(me.resultado_reserva === 0)
				{
					me.$swal({
						title: 'Cuidado',
						text: 'Revisa este plan tarifario no tiene tarifa establecida',
						icon: 'error'
					});
				}
				if(me.resultado_reserva > 0)
				{
				var arreglo = me.ArrayReservas.filter(item =>
					item.habitacion_id === me.habitacion.id_habitacion &&
					moment(item.ingreso).format('YYYY-MM-DD') >= moment(me.fecha_ingreso).format('YYYY-MM-DD') &&
					moment(me.fecha_salida).format('YYYY-MM-DD') <= moment(me.fecha_salida).format('YYYY-MM-DD')
				);
				if (arreglo.length > 0) {
					me.$swal({
						title: "Habitación ya esta asignada",
						icon: "error",
						button: "Aww yiss!",
					});
				} else {
					me.mostrarModulo = 0;
					me.ArrayReservas.push({
						ingreso: me.convert(me.fecha_ingreso),
						salida: me.convert(me.fecha_salida),
						cantidad_adultos: me.cantidad_adultos,
						cantidad_ninos: me.cantidad_ninos,
						nombre_tipo_habitacion: me.tipoHabitacion.nombre_tipo_habitacion,
						tipo_habitacion_id: me.tipoHabitacion.id_tipo_habitacion,
						nombre_habitacion: me.habitacion.numero_habitacion,
						habitacion_id: me.habitacion.id_habitacion,
						total_ocupantes: parseInt(me.cantidad_adultos) + parseInt(me.cantidad_ninos),
						plan_tarifa_id: me.plan_tarifa['id_plan'],
						nombre_plan: me.plan_tarifa['nombre_plan'],
						detalle_reserva: me.arrayDetalleReserva,
						total_reserva: me.resultado_reserva,
						promedio_reserva: me.promedio_reserva,
						datos_reserva: me.ArrayDetalles

					});

					me.tipoHabitacion = '';
					me.habitacion = '';
					me.cantidad_adultos = 1;
					me.cantidad_ninos = 0;
					me.tarifa = 0;
					me.plan_tarifa = '';
					me.checkedCategories = [];
					me.ArrayDetalles = [];
					me.arrayDetalleReserva = [];
			    	}
				}
			},
			datepickerAbierto: function() {},
			fechaSeleccionada: function() {},
			datepickerCerrado: function() {
				var fecha = new Date(this.convert(this.fecha_ingreso));
				var dias = 2; // Número de días a agregar
				fecha.setDate(fecha.getDate() + dias);
				this.fecha_salida = fecha;
				this.mostrarModulo = 0;
			},
			datepickerCerradoSalida: function() {
				this.mostrarModulo = 0;
			},
			cerrarModal() {
				let me = this;
				me.modal = 0;
			},
			cerrarModalDetalle() {
				let me = this;
				me.modalDetalle = 0;
			},
			registrarCliente() {
				let me = this;
					axios.post(me.base + '/cliente/registrar', {
					tipoCliente: me.tipoCliente,
					apellidos: me.apellidos,
					identificacion_fiscal: me.identificacion_fiscal,
					nombres: me.nombres,
					identificacion_fiscal: me.identificacion_fiscal,
					correo: me.correo,
					telefono: me.telefono,
					celular: me.celular,
					fecha_nacimiento: me.convert(me.fecha_nacimiento),
					sexo: me.sexo,
					identificacion_fiscal: me.identificacion_fiscal,
					direccion: me.direccion,
					pais: me.pais,
					estado: me.estado,
					postal: me.postal,
					tipoDocumento: me.tipoDocumento,
					documento: me.documento,
					fecha_emision: me.convert(me.fecha_emision),
					pais_expedidor: me.pais_expedidor,
					fecha_caducidad: me.convert(me.fecha_caducidad)


					}).then(function(response) {
						me.seleccionarClientes();
						me.$swal('Almacenado', 'Registro Almacenado', 'success');
						me.cerrarModalCliente();
						me.tipoCliente = "";
						me.apellidos = "";
						me.identificacion_fiscal = "";
						me.nombres = "";
						me.identificacion_fiscal = "";
						me.correo = "";
						me.telefono = "";
						me.celular = "";
						me.fecha_nacimiento = "";
						me.sexo = "";
						me.identificacion_fiscal = "";
						me.direccion = "";
						me.pais = "";
						me.estado = "";
						me.postal = "";
						me.tipoDocumento = "";
						me.documento = "";
						me.fecha_emision = "";
						me.pais_expedidor = "";
						me.fecha_caducidad = "";
					}).catch(function(error) {
						console.log(error);
					});
			},
			addNewApartment: function() {
				this.apartments.push(Vue.util.extend({}, this.apartment))
			},
			removeApartment: function(index) {
				Vue.delete(this.apartments, index);
			},
			selectDepartamentoFactura() {
				let me = this;
				var url = '/obtener/selectDepartamento?pais_id=' + me.pais_factura;
				axios.get(me.base + url).then(function(response) {
						var respuesta = response.data;
						me.arrayDepartamentoFactura = respuesta.departamentos;
					})
					.catch(function(error, otracosa) {
					});
			},
			cargarTipoHabitaciones() {
				let me = this;
				if (me.cantidad_adultos == 0 || me.cantidad_adultos === '') {
					me.$swal({
						title: 'Cuidado',
						text: 'Cantidad adultos no puede ser 0 o vacio',
						icon: 'error'
					});
				}
				else {
				me.tipoHabitacion = '';
				me.habitacion = '';
				me.plan_tarifa = '';
				me.mostrarModulo = 1;
				var url = '/disponibilidades/tipo_habitaciones?fecha_ingreso=' + me.convert(me.fecha_ingreso) + '&fecha_salida=' + me.convert(me.fecha_salida) + '&cantidad_adultos=' + me.cantidad_adultos + '&cantidad_ninos=' + me.cantidad_ninos;
				axios.get(me.base + url).then(function(response) {
						var respuesta = response.data;
						me.arrayTipoHabitaciones = respuesta.datos;
						me.isDisabledAdultos = true;
						me.isDisabledNinos = true;
					})
					.catch(function(error) {
						var response = error.response.data;
					});
				}
			},
			selectPais() {
				let me = this;
				var url = '/obtener/selectPais';
				axios.get(me.base + url).then(function(response) {
						// console.log(response);
						var respuesta = response.data;
						me.arrayPais = respuesta.paises;
					})
					.catch(function(error) {
						var response = error.response.data;
						console.log(response.message,
							response.exception,
							response.file,
							response.line);
					});
			},
			selectDepartamento(pais) {
				let me = this;
				var url = '/obtener/selectDepartamento?pais_id=' + me.pais;
				axios.get(me.base + url).then(function(response) {
						var respuesta = response.data;
						me.arrayDepartamento = respuesta.departamentos;
					})
					.catch(function(error, otracosa) {
						// var response = error.response.data;
					});
			},
			cerrarModalCliente() {
				let me = this;
				me.modalCliente = false;
			},
			nuevoCliente() {
				let me = this;
				me.modalCliente = true;
				me.tipoCliente = 0;
				this.selectTipoCliente();
				this.selectPais();
				this.selectTipoContacto();
				this.selectTipoDocumento();
				this.selectSexo();
			},
			nameWithLang({
				name,
				code
			}) {
				return `${name}`
			},
			seleccionarClientes() {
				let me = this;
				var url = 'obtener/clientes';
				axios.get(me.base + url).then(function(response) {
						me.arrayClientes = response.data.datos
					})
					.catch(function(error) {
						var response = error.response.data;
					});
			},
			selectTipoHabitacion() {
				
				let me = this;
				me.arrayHabitacion = [];
				me.habitacion = '';
				me.plan_tarifa = '';
				var url = '/obtener/habitaciones?habitacion_id=' + me.tipoHabitacion.id_tipo_habitacion + '&fecha_llegada=' + me.convert(me.fecha_ingreso) + '&fecha_salida=' + me.convert(me.fecha_salida);
				axios.get(me.base + url).then(function(response) {
						var respuesta = response.data;
						me.arrayHabitacion = respuesta.datos;
					})
					.catch(function(error) {
						var response = error.response.data;
					});
				console.log(4444);
				me.selectPlanes(me.tipoHabitacion.id_tipo_habitacion);
			},
			diasEntreFechas(desde, hasta) {
				var dia_actual = desde;
				var fechas = [];
				while (dia_actual.isSameOrBefore(hasta)) {
					fechas.push(dia_actual.format('DD-MM-YYYY'));
					dia_actual.add(1, 'days');
				}
				return fechas;
			},
			abrirModal(modelo, accion, data = []) {

				switch (modelo) {
					case 'data': {
						switch (accion) {
							case 'actualizar': {
								this.dataArrayDetalle = [];
								this.modal = 1;
								this.tituloModal = 'Detalle Tarifa';
								this.dataArrayDetalle = data['datos_reserva'];
								break;
							}
							case 'verDetalle': {
								console.log(data, 11);
								this.dataArrayDetalle = [];
								this.modalDetalle = 1;
								this.tituloModal = 'Detalle Tarifa';
								this.dataArrayDetalle = data['detalle_reserva'];
								break;
							}
						}
					}
				}
			},

			async selectPlan() {

				let me = this;
				var fecha1 = moment(me.convert(me.fecha_ingreso));
				var fecha2 = moment(me.convert(me.fecha_salida));
				var dias = fecha2.diff(fecha1, 'days');
				me.arrayDetalleReserva = [];
				me.dataArrayDetalle = [];
				me.ArrayDetalles = [];
				me.ocupacion = parseInt(me.cantidad_adultos) + parseInt(me.cantidad_ninos);
				me.resultado_ocupacion = parseInt(me.ocupacion) - parseInt(me.plan_tarifa['ocupacion_tarifa']);
				if (me.resultado_ocupacion <= 0) {
					var fechaInicio = new Date(me.convert(me.fecha_ingreso));
					var fechaFin = new Date(me.convert(me.fecha_salida));
					me.resultado_reserva = 0.0;
					me.promedio_reserva = 0.0;
					while (fechaFin.getTime() > fechaInicio.getTime()) {
						fechaInicio.setDate(fechaInicio.getDate() + 1);
						var url = '/planes_tarifarios/calculo_tarifa?plan_tarifario_id=' + me.plan_tarifa['id_plan'] + '&tipo_habitacion_id=' + me.tipoHabitacion.id_tipo_habitacion + '&fecha=' + fechaInicio.getFullYear() + '-' + (fechaInicio
							.getMonth() + 1) + '-' + fechaInicio.getDate();
						let tarifa_x_dia = await axios.get(url);
						me.resultado_reserva += parseFloat(tarifa_x_dia.data.datos);
						me.promedio_reserva = parseFloat(me.resultado_reserva) / dias;
						me.arrayDetalleReserva.push('fecha: ' + fechaInicio.getFullYear() + '-' + (fechaInicio
							.getMonth() + 1) + '-' + fechaInicio.getDate() + ' ' + 'Tarifa: ' + tarifa_x_dia.data.datos);
						me.ArrayDetalles.push({
							Fecha: fechaInicio.getFullYear() + '-' + (fechaInicio.getMonth() + 1) + '-' + fechaInicio.getDate(),
							Tarifa: tarifa_x_dia.data.datos,
							id_habitacion: me.habitacion.id_habitacion,
						});
					}
				} else {
					me.resultado_ocupacion_dos = parseInt(me.cantidad_adultos) - parseInt(me.plan_tarifa['ocupacion_tarifa']);
					if (me.resultado_ocupacion_dos >= 0) {
						me.adultos_extra = parseInt(me.cantidad_adultos) - parseInt(me.plan_tarifa['ocupacion_tarifa']);
						if (parseInt(me.cantidad_ninos) - parseInt(me.plan_tarifa['menores_incluidos']) >= 0) {
							me.ninos_extra = parseInt(me.cantidad_ninos) - parseInt(me.plan_tarifa['menores_incluidos']);
						}
						if (parseInt(me.cantidad_ninos) - parseInt(me.plan_tarifa['menores_incluidos']) < 0) {
							me.ninos_extra = 0;
						}
						var fechaInicio = new Date(me.convert(me.fecha_ingreso));
						var fechaFin = new Date(me.convert(me.fecha_salida));
						me.resultado_reserva = 0.0;
						me.promedio_reserva = 0.0;
						while (fechaFin.getTime() > fechaInicio.getTime()) {
							fechaInicio.setDate(fechaInicio.getDate() + 1);
							var url = '/planes_tarifarios/calculo_tarifa?plan_tarifario_id=' + me.plan_tarifa['id_plan'] + '&tipo_habitacion_id=' + me.tipoHabitacion.id_tipo_habitacion + '&fecha=' + fechaInicio.getFullYear() + '-' + (fechaInicio
								.getMonth() + 1) + '-' + fechaInicio.getDate();
							let tarifa_x_dia = await axios.get(url);
							console.log(tarifa_x_dia);
							me.total_adultos_extra_dia = parseFloat(me.adultos_extra) * parseFloat(tarifa_x_dia.data.tarifa_x_persona);
							me.total_ninos_extra_dia = parseFloat(me.ninos_extra) * parseFloat(tarifa_x_dia.data.tarifa_x_nino);
							me.resultado_x_fecha = parseFloat(me.total_adultos_extra_dia) + parseFloat(me.total_ninos_extra_dia) + parseFloat(tarifa_x_dia.data.datos);
							me.resultado_reserva += parseFloat(me.resultado_x_fecha);
							me.promedio_reserva = parseFloat(me.resultado_reserva) / dias;
							me.arrayDetalleReserva.push('fecha: ' + fechaInicio.getFullYear() + '-' + (fechaInicio
								.getMonth() + 1) + '-' + fechaInicio.getDate() + ' ' + 'Tarifa: ' + me.resultado_x_fecha);
							me.ArrayDetalles.push({
								Fecha: fechaInicio.getFullYear() + '-' + (fechaInicio.getMonth() + 1) + '-' + fechaInicio.getDate(),
								Tarifa: me.resultado_x_fecha,
								id_habitacion: me.habitacion.id_habitacion,
							});
						}
					} else {
						me.adultos_extra = parseInt(me.cantidad_adultos) - parseInt(me.plan_tarifa['ocupacion_tarifa']);
						me.ninos_extra = parseInt(me.cantidad_ninos) - parseInt(me.plan_tarifa['menores_incluidos']);
						var fechaInicio = new Date(me.convert(me.fecha_ingreso));
						var fechaFin = new Date(me.convert(me.fecha_salida));
						me.resultado_reserva = 0.0;
						me.promedio_reserva = 0.0;

						while (fechaFin.getTime() > fechaInicio.getTime()) {
							fechaInicio.setDate(fechaInicio.getDate() + 1);
							var url = '/planes_tarifarios/calculo_tarifa?plan_tarifario_id=' + me.plan_tarifa['id_plan'] + '&tipo_habitacion_id=' + me.tipoHabitacion.id_tipo_habitacion + '&fecha=' + fechaInicio.getFullYear() + '-' + (fechaInicio
								.getMonth() + 1) + '-' + fechaInicio.getDate();
							let tarifa_x_dia = await axios.get(url);
							console.log(3);
							me.ninos_extra = parseInt(me.cantidad_adultos) - parseInt(me.plan_tarifa['ocupacion_tarifa']) + parseInt(me.cantidad_ninos) - parseInt(me.plan_tarifa['menores_incluidos']);
							me.total_ninos_extra_dia = parseFloat(me.ninos_extra) * parseFloat(tarifa_x_dia.data.tarifa_x_nino);
							me.total_tarifa_reserva_dia = parseFloat(me.total_ninos_extra_dia) + parseFloat(tarifa_x_dia.data.datos);
							me.resultado_reserva += parseFloat(me.total_tarifa_reserva_dia);
							me.promedio_reserva = parseFloat(me.resultado_reserva) / dias;
							me.arrayDetalleReserva.push('fecha: ' + fechaInicio.getFullYear() + '-' + (fechaInicio
								.getMonth() + 1) + '-' + fechaInicio.getDate() + ' ' + 'Tarifa: ' + me.total_tarifa_reserva_dia);
							me.ArrayDetalles.push({
								Fecha: fechaInicio.getFullYear() + '-' + (fechaInicio.getMonth() + 1) + '-' + fechaInicio.getDate(),
								Tarifa: me.total_tarifa_reserva_dia,
								id_habitacion: me.habitacion.id_habitacion,
							});
						}
					}
				}
			},

			BorrarDatos() {
				let me = this;
				me.selected
				me.$swal({
					title: "Borrando",
					text: "¿Desea borrar de forma permanente este impuesto?",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#DD6B55",
					confirmButtonText: "Si, borrar"
				}).then(function(isConfirm) {
					axios
						.post(me.base + "/categorias_productos/borrar", {
							id: me.selected
						})
						.then(function(response) {
							if (isConfirm) {
								me.$swal({
									title: 'Eliminado',
									text: 'Ha impuesto ha sido borrado',
									icon: 'success'
								}).
								then(function() {
									me.ListarDatos();
								});
							}
						})
						.catch(function(error, otracosa) {});
				});
			},

			ListarDatos(page) {
				let me = this;
				var url = me.base + '/desayuno/index?page=' + page;
				axios.get(url).then(function(response) {
						var respuesta = response.data;
						console.log(respuesta)
						me.ArrayDatos = respuesta.datos.data;
						me.pagination = respuesta.pagination;
					})
					.catch(function(error) {
						var response = error.response.data;
						console.log(response.message,
							response.exception,
							response.file,
							response.line);
					});
			},


			NuevoRegistro() {
				let me = this;
				me.selected = 0;
				me.tipoGuardar = 2;
				me.valor = '';
				me.impuestoValor = 0;
			},
			VerDato(data) {
				let me = this;
				me.tipoGuardar = 1;
				me.selected = data.id;
				me.valor = data.valor;
				me.impuestoValor = data.valor;
			},


			GuardarNuevoDatos() {
				this.submitted = true;
				this.$validator.validate().then(valid => {
					if (valid) {
						let me = this;
						me.tipoGuardar = 1;
						me.disablebutton = true;
						axios
							.post(me.base + "/desayuno/Nueva", {
								valor: me.valor

							})
							.then(function(response) {

								console.log(response.data)
								if (response.data == 2) {
									Vue.swal("Lo sentimos!", "No puedes realizar mas registros!", "error");
								} else {
									me.disablebutton = false;
									me.id = response.data.id;
									me.$swal("se ha guardado el valor del Desayuno con éxito");
									me.ListarDatos();
									me.VerDato(response.data);
								}

							}).catch(function(error) {
								console.log(error)
							});
					} else {
						me.$swal("Por favor ingrese todos los campos");
					}
				});
			},

			selectTipoCliente() {
				let me = this;
				var url = '/cliente/selectTipoCliente';
				axios.get(me.base + url).then(function(response) {
						// console.log(response);
						var respuesta = response.data;
						me.arrayTipoCliente = respuesta.clientes;
					})
					.catch(function(error) {
						var response = error.response.data;
						console.log(response.message,
							response.exception,
							response.file,
							response.line);
					});
			},

			selectTipoContacto() {
				let me = this;
				var url = '/obtener/selectTipoContacto';
				axios.get(me.base + url).then(function(response) {
						var respuesta = response.data;
						me.arrayTipoContacto = respuesta.tipo_contactos;
					})
					.catch(function(error, otracosa) {
						// var response = error.response.data;
					});
			},

			selectTipoDocumento() {
				let me = this;
				var url = '/obtener/selectTipoDocumento';
				axios.get(me.base + url).then(function(response) {
						var respuesta = response.data;
						me.arrayTipoDocumento = respuesta.tipo_documentos;
					})
					.catch(function(error, otracosa) {
						// var response = error.response.data;
					});
			},
			selectSexo() {
				let me = this;
				var url = '/obtener/selectSexo';
				axios.get(me.base + url).then(function(response) {
						// console.log(response);
						var respuesta = response.data;
						me.arraySexo = respuesta.sexos;
					})
					.catch(function(error) {
						var response = error.response.data;
						console.log(response.message,
							response.exception,
							response.file,
							response.line);
					});
			},

			GuardarEditarDatos() {
				let me = this;
				this.submitted = true;
				this.$validator.validate().then(valid => {
					if (valid) {
						me.disablebutton = true;
						axios
							.post(me.base + "/desayuno/Editar", {
								id: me.selected,
								valor: me.valor,
								valor: me.impuestoValor
							})
							.then(function(response) {
								if (response.data.validate) {
									me.$swal("se ha editado el impuesto " + me.valor + " con éxito");
									me.ListarDatos();
									me.VerDato(response.data);
								} else {
									me.$swal("Se presento un problema al editar impuesto " + me.valor + ".");
								}
								me.disablebutton = false;
							})
							.catch(function(error) {
								me.disablebutton = false;
								console.log(error)
							});
					} else {
						me.$swal("Por favor ingrese todos los campos");
					}
				});
			}
		},
		mounted() {
			this.base = base;
			this.ListarDatos();
			this.seleccionarClientes();

		}
	};
</script>
<style>
	.modal-content {
		width: 100% !important;
		position: absolute !important;
	}

	.mostrar {
		display: list-item !important;
		opacity: 1 !important;
		position: absolute !important;
		background-color: #3c29297a !important;
	}

	.div-error {
		display: flex;
		justify-content: center;
	}

	.text-error {
		color: red !important;
		font-weight: bold;
	}

	main {
		width: 100%;
	}

	#scroll {
		overflow-y: scroll;
		height: 360px;
	}

	.title-sub {
		color: #c3c3c3;
		font-size: 9px;
	}

	.container-checkbox {
		display: inline;
		position: relative;
		padding-left: 35px;
		margin-bottom: 12px;
		cursor: pointer;
		font-size: 16px;
		-webkit-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
	}

	/* Hide the browser's default checkbox */

	.container-checkbox input {
		position: absolute;
		opacity: 0;
		cursor: pointer;
	}

	/* Create a custom checkbox */

	.container-checkbox .checkmark {
		position: absolute;
		top: 0;
		left: 0;
		height: 25px;
		width: 25px;
		background-color: #eee;
	}

	/* On mouse-over, add a grey background color */

	.container-checkbox:hover input~.checkmark {
		background-color: #ccc;
	}

	/* When the checkbox is checked, add a blue background */

	.container-checkbox input:checked~.checkmark {
		background-color: #2196F3;
	}

	/* Create the checkmark/indicator (hidden when not checked) */

	.container-checkbox .checkmark:after {
		content: "";
		position: absolute;
		display: none;
	}

	/* Show the checkmark when checked */

	.container-checkbox input:checked~.checkmark:after {
		display: block;
	}

	/* Style the checkmark/indicator */

	.container-checkbox .checkmark:after {
		left: 9px;
		top: 5px;
		width: 5px;
		height: 10px;
		border: solid white;
		border-width: 0 3px 3px 0;
		-webkit-transform: rotate(45deg);
		-ms-transform: rotate(45deg);
		transform: rotate(45deg);
	}

	/* The container */

	.container-radio {
		display: block;
		position: relative;
		padding-left: 35px;
		margin-bottom: 12px;
		cursor: pointer;
		font-size: 22px;
		-webkit-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
	}

	/* Hide the browser's default radio button */

	.container-radio input {
		position: absolute;
		opacity: 0;
		cursor: pointer;
	}

	/* Create a custom radio button */

	.container-radio .checkmark {
		position: absolute;
		top: 0;
		left: 0;
		height: 25px;
		width: 25px;
		background-color: #eee;
		border-radius: 50%;
	}

	/* On mouse-over, add a grey background color */

	.container-radio:hover input~.checkmark {
		background-color: #ccc;
	}

	/* When the radio button is checked, add a blue background */

	.container-radio input:checked~.checkmark {
		background-color: #2196F3;
	}

	/* Create the indicator (the dot/circle - hidden when not checked) */

	.container-radio .checkmark:after {
		content: "";
		position: absolute;
		display: none;
	}

	/* Show the indicator (dot/circle) when checked */

	.container-radio input:checked~.checkmark:after {
		display: block;
	}

	/* Style the indicator (dot/circle) */

	.container-radio .checkmark:after {
		top: 9px;
		left: 9px;
		width: 8px;
		height: 8px;
		border-radius: 50%;
		background: white;
	}

	.card {
		border: 0;
		margin: 18px 0;
		box-shadow: 0 6px 0px 0 rgba(0, 0, 0, 0.01), 0 15px 32px 0 rgba(0, 0, 0, 0.06);
		border-radius: 4px;
		height: 95%;
	}

	.vdp-datepicker {
		width: 100% !important;
	}
</style>
