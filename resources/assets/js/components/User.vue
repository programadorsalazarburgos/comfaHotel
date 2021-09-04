<template>
<main class="main">
    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Usuarios Hotel</h5>
                    </div>
                    <div class="card-body collapse show">
                        <div class="card-block card-dashboard">
                            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                                <div class="flexbox mb-4">
                                    <div class="flexbox">
                                        <div class="card-header">
                                            <button href="javascript:void(0)" class="btn btn-raised gradient-mint white shadow-z-4" @click="abrirModal('persona','registrar')">Nuevo Usuario</button>
                                            <span class="ml-2"></span>
                                        </div>
                                        <div class="input-group">
                                            <select class="form-control col-md-3" v-model="criterio">
                                                <option value="nombre">Nombre</option>
                                                <option value="documento">Numero Documento</option>
                                                <option value="email">Correo</option>
                                                <option value="telefono">Telefono</option>
                                            </select>
                                            <input type="text" v-model="buscar" @keyup.enter="listarPersona(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                            <button type="button" class="btn btn-raised gradient-pomegranate white big-shadow" @click="listarPersona(1,buscar,criterio)"><i class="fa fa-search"></i> Buscar</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table table-striped table-bordered zero-configuration dataTable" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info">
                                            <thead>
                                                <tr role="row">
                                                    <th class="sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Name: activate to sort column descending" style="width: 226px;">Opciones</th>
                                                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Position: activate to sort column ascending" style="width: 202px;">Nombre</th>
                                                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Office: activate to sort column ascending" style="width: 89px;">Tipo Documento</th>
                                                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Age: activate to sort column ascending" style="width: 33px;">Numero Documento</th>
                                                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Start date: activate to sort column ascending" style="width: 85px;">Telefono</th>
                                                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Salary: activate to sort column ascending" style="width: 69px;">Correo</th>
                                                    <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Salary: activate to sort column ascending" style="width: 69px;">Usuario</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="persona in arrayPersona" :key="persona.id">
                                                    <td align="center">
                                                        <button type="button" class="btn btn-raised gradient-ibiza-sunset white sidebar-shadow btn-sm" @click="abrirModal('persona','actualizar',persona)"><i class="fas fa-edit"></i></button>
                                                    </td>
                                                    <td v-text="persona.nombres"></td>
                                                    <td v-text="persona.tipo_documento"></td>
                                                    <td v-text="persona.documento"></td>
                                                    <td v-text="persona.telefono"></td>
                                                    <td v-text="persona.email"></td>
                                                    <td v-text="persona.usuario"></td>
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
                </div>
            </div>
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none; height: 10000px;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Nombre(*)</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombres" class="form-control" placeholder="Nombre de la persona">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">Tipo Documento</label>
                                <div class="col-md-9">
                                    <select v-model="tipo_documento" class="form-control">
                                        <option value="dni">DNI</option>
                                        <option value="ruc">RUC</option>
                                        <option value="cedula">CEDULA</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Numero Documento</label>
                                <div class="col-md-9">
                                    <input type="email" v-model="num_documento" class="form-control" placeholder="Número de documento">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Telefono</label>
                                <div class="col-md-9">
                                    <input type="email" v-model="telefono" class="form-control" placeholder="Teléfono">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Correo</label>
                                <div class="col-md-9">
                                    <input type="email" v-model="email" class="form-control" placeholder="Email">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Role</label>
                                <div class="col-md-9">
                                    <select v-model="idrol" class="form-control">
                                        <option value="0" disabled>Seleccione</option>
                                        <option v-for="role in arrayRol" :key="role.id" :value="role.id" v-text="role.name"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">Usuario</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="usuario" class="form-control" placeholder="Nombre del usuario">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="email-input">password</label>
                                <div class="col-md-9">
                                    <input type="password" v-model="password" class="form-control" placeholder="password del usuario">
                                </div>
                            </div>
                            <div v-show="errorPersona" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjPersona" :key="error" v-text="error"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarPersona()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarPersona()">Actualizar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
    </section>
</main>
</template>
<script>

import VueSweetAlert from 'vue-sweetalert'
import swal from "sweetalert2"
export default {
	data() {
		return {
			persona_id: 0,
            nombre: '',
			nombres: '',
			tipo_documento: '',
			num_documento: '',
			direccion: '',
			telefono: '',
			email: '',
			usuario: '',
			password: '',
			idrol: '',
			arrayPersona: [],
			arrayRol: [],
			modal: 0,
			tituloModal: '',
			tipoAccion: 0,
			errorPersona: 0,
			errorMostrarMsjPersona: [],
			pagination: {
				total: 0,
				current_page: 0,
				per_page: 0,
				last_page: 0,
				from: 0,
				to: 0,
			},
			offset: 3,
			criterio: 'nombre',
			buscar: '',
		};
	},
	computed: {
    count () {
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

			var to = from + this.offset * 2;
			if (to >= this.pagination.last_page) {
				to = this.pagination.last_page;
			}

			var pagesArray = [];
			while (from <= to) {
				pagesArray.push(from);
				from++;
			}
			return pagesArray;
		},
	},
	methods: {
		listarPersona(page, buscar, criterio) {
			let me = this;
			var url = '/user?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
			axios
				.get(me.base+url)
				.then(function(response) {
					var respuesta = response.data;
					me.arrayPersona = respuesta.personas.data;
					me.pagination = respuesta.pagination;
				})
				.catch(function(error) {
					var response = error.response.data;
					console.log(response.message, response.exception, response.file, response.line);
				});
		},
		selectRol() {
			let me = this;
			var url = '/rol/selectRol';
			axios
				.get(me.base+url)
				.then(function(response) {
					// console.log(response);
					var respuesta = response.data;
					me.arrayRol = respuesta.roles;
				})
				.catch(function(error) {
					var response = error.response.data;
					console.log(response.message, response.exception, response.file, response.line);
				});
		},

		cambiarPagina(page, buscar, criterio) {
			let me = this;
			//Actualiza la página actual
			me.pagination.current_page = page;
			//Envia la petición para visualizar la data de esa página
			me.listarPersona(page, buscar, criterio);
		},
		registrarPersona() {
			// if (this.validarPersona()) {
			// 	return;
			// }

			let me = this;

			axios
				.post(me.base+'/user/registrar', {
					nombres: this.nombres,
					tipo_documento: this.tipo_documento,
					num_documento: this.num_documento,
					telefono: this.telefono,
					email: this.email,
					idrol: this.idrol,
					usuario: this.usuario,
					password: this.password,
				})
				.then(function(response) {
					me.cerrarModal();
					me.listarPersona(1, '', 'nombre');
                     swal({
                      title: "Muy bien!",
                      text: "Registro Almacenado!",
                      icon: "success",
                      button: "Aww yiss!",
                    });
				})
				.catch(function(error) {
					var response = error.response.data;
					console.log(response.message, response.exception, response.file, response.line);
				});
		},
		actualizarPersona() {
			// if (this.validarPersona()) {
			// 	return;
			// }

			let me = this;

			axios
				.put('/user/actualizar', {
					nombres: this.nombres,
                    tipo_documento: this.tipo_documento,
                    num_documento: this.num_documento,
                    telefono: this.telefono,
                    email: this.email,
                    idrol: this.idrol,
                    usuario: this.usuario,
                    password: this.password,
					id: this.persona_id,
				})
				.then(function(response) {
					me.cerrarModal();
					me.listarPersona(1, '', 'nombre');
				})
				.catch(function(error) {
					var response = error.response.data;
					console.log(response.message, response.exception, response.file, response.line);
				});
		},
		validarPersona() {
			this.errorPersona = 0;
			this.errorMostrarMsjPersona = [];

			if (!this.nombre) this.errorMostrarMsjPersona.push('El nombre de la pesona no puede estar vacío.');
			if (!this.usuario) this.errorMostrarMsjPersona.push('El nombre de usuario no puede estar vacío.');
			if (!this.password) this.errorMostrarMsjPersona.push('La password del usuario no puede estar vacía.');
			if (this.idrol == 0) this.errorMostrarMsjPersona.push('Seleccione una Role.');
			if (this.errorMostrarMsjPersona.length) this.errorPersona = 1;

			return this.errorPersona;
		},
		cerrarModal() {
			this.modal = 0;
			this.tituloModal = '';
			this.nombre = '';
			this.tipo_documento = 'DNI';
			this.num_documento = '';
			this.direccion = '';
			this.telefono = '';
			this.email = '';
			this.usuario = '';
			this.password = '';
			this.idrol = 0;
			this.errorPersona = 0;
		},
		abrirModal(modelo, accion, data = []) {
			this.selectRol();
			switch (modelo) {
				case 'persona': {
					switch (accion) {
						case 'registrar': {
							this.modal = 1;
							this.tituloModal = 'Registrar Usuario';
							this.nombre = '';
							this.tipo_documento = 'DNI';
							this.num_documento = '';
							this.direccion = '';
							this.telefono = '';
							this.email = '';
							this.usuario = '';
							this.password = '';
							this.idrol = 0;
							this.tipoAccion = 1;
							break;
						}
						case 'actualizar': {
							console.log(data);
							this.modal = 1;
							this.tituloModal = 'Actualizar Usuario';
							this.tipoAccion = 2;
							this.persona_id = data['id'];
							this.nombres = data['nombres'];
							this.tipo_documento = data['tipo_documento'];
							this.num_documento = data['documento'];
							this.direccion = data['direccion'];
							this.telefono = data['telefono'];
							this.email = data['email'];
							this.usuario = data['usuario'];
							this.password = data['password'];
							this.idrol = data['idrol'];
							break;
						}
					}
				}
			}
		},
		desactivarUsuario(id) {

			swal({
				title: 'Esta seguro de desactivar este usuario?',
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Aceptar!',
				cancelButtonText: 'Cancelar',
				confirmButtonClass: 'btn btn-success',
				cancelButtonClass: 'btn btn-danger',
				buttonsStyling: false,
				reverseButtons: true,
			}).then(result => {
				if (result.value) {
					let me = this;

					axios
						.put('/user/desactivar', {
							id: id,
						})
						.then(function(response) {
							me.listarPersona(1, '', 'nombre');
							swal('Desactivado!', 'El registro ha sido desactivado con éxito.', 'success');
						})
						.catch(function(error) {
							var response = error.response.data;
							console.log(response.message, response.exception, response.file, response.line);
						});
				} else if (
					// Read more about handling dismissals
					result.dismiss === swal.DismissReason.cancel
				) {
				}
			});
		},
		activarUsuario(id) {
			swal({
				title: 'Esta seguro de activar este usuario?',
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Aceptar!',
				cancelButtonText: 'Cancelar',
				confirmButtonClass: 'btn btn-success',
				cancelButtonClass: 'btn btn-danger',
				buttonsStyling: false,
				reverseButtons: true,
			}).then(result => {
				if (result.value) {
					let me = this;

					axios
						.put('/user/activar', {
							id: id,
						})
						.then(function(response) {
							me.listarPersona(1, '', 'nombre');
							swal('Activado!', 'El registro ha sido activado con éxito.', 'success');
						})
						.catch(function(error) {
							var response = error.response.data;
							console.log(response.message, response.exception, response.file, response.line);
						});
				} else if (
					// Read more about handling dismissals
					result.dismiss === swal.DismissReason.cancel
				) {
				}
			});
		},
	},
	mounted() {
		this.base=base;
		this.listarPersona(1, this.buscar, this.criterio);
	},
};
</script>
<style>
.modal-content {
	width: 100% !important;
	position: absolute !important;
}
.mostrar {
	display: block !important;
	opacity: 1 !important;
	position: absolute !important;
	background-color: #3c29297a !important;
	height: 100vh;
}
.div-error {
	display: flex;
	justify-content: center;
}
.text-error {
	color: red !important;
	font-weight: bold;
}

@media (max-width: 768px) {
	.mostrar {
		height: 150%;
	}
}
</style>
