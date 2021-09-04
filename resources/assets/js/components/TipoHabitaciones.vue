C<template>
	<main class="main">
		<div class="col-12">
			<div class="content-header">Tipo habitaciones</div>
		</div>
		<section id="extended">
			<div class="row">
				<div class="col-sm-6">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title"></h4>
							<div class="input-group">
								<select class="form-control col-md-3" v-model="criterio">
									<option value="nombre">Nombre Tipo Habitación</option>
									<option value="codigo">Codigo</option>
								</select>
								<input type="text" v-model="buscar" @keyup.enter="ListarItems(1,buscar)" class="form-control" placeholder="Texto a buscar">
								<button class="btn btn-raised gradient-pomegranate white big-shadow" type="submit" @click="ListarItems(1,buscar,criterio)">
									<span class="btn-icon"><i class="la la-search"></i>Buscar</span>
								</button>
							</div>
						</div>
						<div class="card-body">
							<div class="card-body">
								<div class="card-block">
									<table class="table table-responsive-md-md text-center">
										<thead>
											<tr>
												<th>Codigo</th>
												<th>Nombre</th>
												<th>Acción</th>
											</tr>
										</thead>
										<tbody>
											<tr v-for="HabitacionesTipo in arrayHabitacionesTipo" :key="HabitacionesTipo.id">
												<td v-text="HabitacionesTipo.codigo"></td>
												<td v-text="HabitacionesTipo.nombre"></td>
												<td>
													<button type="button" class="btn-warning" title="Editar"@click="vertipohabitacion(HabitacionesTipo.id)" style="background-color: #f7d24acc; border-color: #f7d24acc;">
														 <i class="fa fa-pencil" aria-hidden="true"></i>
												 </button>
												</td>
											</tr>
										</tbody>
									</table>
									<nav>
										<ul class="pagination">
											<li class="page-item" v-if="pagination.current_page > 1">
												<a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
											</li>
											<li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
												<a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
											</li>
											<li class="page-item" v-if="pagination.current_page < pagination.last_page">
												<a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
											</li>
										</ul>
									</nav>
								</div>
							</div>
						</div>
					</div>

				</div>
				<div class="col-sm-6">
					<div class="card">
						<div class="card-header">
							<h5 class="card-title">Creación Tipo Habitación</h5>
							<div class="container-fluid">
								<form action="" method="post" @submit.prevent="registrarData" enctype="multipart/form-data">
									<div class="col-sm-12">
										<label for="">Codigo</label>
										<input type="text" required  class="form form-control" v-model="codigo" name="codigo" />
									</div>
									<div class="col-sm-12">
										<label for="">Room Type</label>
										<input type="text" required class="form form-control" v-model="room_type" name="room type" />
									</div>
									<div class="col-sm-12">
										<label for="">Nombre</label>
										<input type="text"  required class="form form-control" v-model="nombre" name="nombre" />
									</div>
									<div class="col-sm-12">
										<label for="">Maximo Personas</label>
										<input type="number"  required placeholder="Maximo de personas" class="form form-control" v-model="maximo" name="maximo" />
									</div>
									<div class="col-sm-12">
										<label for="">Tipo Cama</label>
										<select class="form form-control" required  v-model="camas_tipo" name="tipo de cama">
											<option value="">Seleccione</option>
											<option v-for="cama in arrayCamas" :key="cama.id" :value="cama.id" v-text="cama.descripcion"></option>
										</select>
									</div>
									<div class="col-sm-12">
										<br>
										<button type="submit" v-if="tipoGuardar==1" class="btn btn-raised gradient-nepal white card-shadow">Guardar <i class="fa fa-fw fa-save"></i></button>
									  <button type="submit" v-if="tipoGuardar==2" class="btn btn-raised gradient-ibiza-sunset white sidebar-shadow">Actualizar <i class="fa fa-fw fa-save"></i></button>

									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</main>
</template>
<script>
import swal from "sweetalert2"
import Vue from 'vue'
import VueSweetalert2 from 'vue-sweetalert2';

Vue.use(VueSweetalert2);
	export default {
		data() {
			return {
				base: '',
				categoria_id: 0,
				disablebutton: false,
				room_type: '',
				tipoGuardar: 1,
				descripcion: "",
				codigo: "",
				nombre: "",
				selected: '',
				minimo: "",
				maximo: "",
				camas_tipo: "",
				habitacion_tipo: "",
				superbooking_tipo: "",
				precio: "",
				arrayHabitacionesTipo: [],
				arrayCamas: [],
				arrayTipoSuperBooking: [],
				modal: 0,
				tituloModal: "",
				tipoAccion: 0,
				errorCategoria: 0,
				errorMostrarMsjCategoria: [],
				buscar: "",
				criterio: "nombre",
				Paginas: 0,
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
				criterio: 'nombre',
				buscar: '',
			};
		},

		computed: {
			count() {
				this.$store.state;
				return this.$store.state.posts
			},
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
		methods: {
			handleSubmit(e) {},

			ListarItems(page, buscar, criterio) {
				let me = this;
				var url = '/habitaciones/tipo?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
				axios.get(url).then(function(response) {
						var respuesta = response.data;
						me.arrayHabitacionesTipo = respuesta.items.data;
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


			tipoCamas() {
				let me = this;
				axios
					.get(me.base + "/habitaciones/camastipo")
					.then(function(response) {
						me.arrayCamas = response.data.Tipo;
					})
					.catch(function(error, otracosa) {});
			},
			superbookingtipo() {

				let me = this;
				axios
					.get(me.base + "/habitaciones/superbookingtipo")
					.then(function(response) {
						me.arrayTipoSuperBooking = response.data.Tipo;
					})
					.catch(function(error, otracosa) {});
			},
			registrarData() {
							let me = this;
							if (me.tipoGuardar == 1) {
									axios
											.post(me.base + '/habitaciones/NuevaTipo', {
												codigo: me.codigo,
												room_type: me.room_type,
												nombre: me.nombre,
												maximo: me.maximo,
												camas_tipo: me.camas_tipo

											})
											.then(function(response) {
													me.codigo = '';
													me.room_type = '';
													me.nombre = '';
													me.maximo = '';
													me.camas_tipo = '';
										  		me.disablebutton = false;
										  		me.ListarItems(1, '');
													me.$swal({
															title: "Muy bien!",
															text: "Registro Almacenado!",
															icon: "success",
															button: "Aww yiss!",
													});
											})
											.catch(function(error) {
													me.$swal({
															title: "Lo sentimos!",
															text: "Dato Existente",
															icon: "error",
															button: "Aww yiss!",
													});
											});
							}
							if (me.tipoGuardar == 2) {
								axios
										.post(me.base + '/habitaciones/EditarTipo', {
											id: me.id,
											codigo: me.codigo,
											room_type: me.room_type,
											nombre: me.nombre,
											maximo: me.maximo,
											camas_tipo: me.camas_tipo

										})
										.then(function(response) {
												me.codigo = '';
												me.room_type = '';
												me.nombre = '';
												me.maximo = '';
												me.camas_tipo = '';
												me.descripcion = '';
												me.tipoGuardar = 1;
												me.ListarItems(1, '');
												me.$swal({
														title: "Muy bien!",
														text: "Registro Almacenado!",
														icon: "success",
														button: "Aww yiss!",
												});
										})
										.catch(function(error) {
												me.$swal({
														title: "Lo sentimos!",
														text: "Dato Existente",
														icon: "error",
														button: "Aww yiss!",
												});
										});
							}
					},


			vertipohabitacion(id) {


				let me = this;
				me.selected = id;
				me.tipoGuardar = 2;

				var url = '/habitaciones/obtener_tipo?id=' + id;
				axios.get(url).then(function(response) {
					console.log(response, "data");
						var respuesta = response.data.items;
						me.codigo = respuesta.codigo;
						me.id = id;
						me.room_type = respuesta.room_type;
						me.nombre = respuesta.nombre;
						me.minimo = respuesta.persona_minimo;
						me.maximo = respuesta.persona_maximo;
						me.camas_tipo = respuesta.id_camas;
						me.superbooking_tipo = respuesta.id_tipo_habitacion_superbooking;
						me.descripcion = respuesta.Descripcion;

					})
					.catch(function(error) {
						var response = error.response.data;

					});

			},

		},
		mounted() {
			this.base = base;
			this.ListarItems(1, '');
			this.tipoCamas();
			this.superbookingtipo();
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
</style>
