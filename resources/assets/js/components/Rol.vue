<template>
<main class="main">
    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Roles del Hotel</h5>
                    </div>
                    <div class="card-body collapse show">
                        <div class="card-block card-dashboard">
                            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                                <div class="flexbox mb-4">
                                    <div class="flexbox">
                                        <div class="card-header">
                                            <button href="javascript:void(0)" class="btn btn-raised gradient-mint white shadow-z-4" @click="abrirModal('rol','registrar')">Nuevo Rol</button>
                                            <span class="ml-2"></span>
                                        </div>
                                        <div class="input-group">
                                            <select class="form-control col-md-3" v-model="criterio">
                                                <option value="nombre">Nombre</option>
                                            </select>
                                            <input type="text" v-model="buscar" @keyup.enter="listarRol(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                            <button type="button" class="btn btn-raised gradient-pomegranate white big-shadow" @click="listarRol(1,buscar,criterio)"><i class="fa fa-search"></i> Buscar</button>
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
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="rol in arrayRol" :key="rol.id">
                                                    <td align="center">
                                                        <button type="button" class="btn btn-raised gradient-ibiza-sunset white sidebar-shadow btn-sm" @click="abrirModal('rol','actualizar',rol)"><i class="fas fa-edit"></i></button>
                                                        <button type="button" class="btn btn-raised gradient-mint white btn-sm" @click="abrirModalRol(rol.id)">Permisos</button>
                                                    </td>
                                                    <td v-text="rol.name"></td>
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
                                <label class="col-md-3 form-control-label" for="text-input">Nombre Rol(*)</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombre" class="form-control" placeholder="Nombre Rol">
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
                        <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarRol()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarRol()">Actualizar</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Fin del modal-->
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal2}" role="dialog" aria-labelledby="myModalLabel" style="display: none; height: 10000px;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal2()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <button @click="simulateAjax" class="btn btn-raised gradient-pomegranate white big-shadow">Todos</button> | <button @click="clear" class="btn btn-raised gradient-ibiza-sunset white sidebar-shadow">Limpiar</button>
                        </div>
                        <div class="wrap">
                            <div class="left">
                                <h5>Selecciona Permiso que deseas asignar a este rol:</h5>
                                <ul>
                                    <li v-for="task in arrayPermisos" :key="task.id">
                                        <input type="checkbox" :id="task.id" :value="task" v-model="selectedTasks" @change="handleTasks(task)">
                                        <label :for="task.name">{{task.name}}</label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" v-if="tipoAccion==1" class="btn btn-success" @click="guardarPermisos(rol_id)">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarRol()">Actualizar</button>
                        <button type="button" class="btn btn-secondary" @click="cerrarModal2()">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
</template>

<script>
import VueSweetAlert from 'vue-sweetalert'
import swal from "sweetalert2"
import Vue from 'vue'

export default {
    data() {
        return {
            base: '',

            tasks: [{
                    id: 1,
                    title: 'generar-factura'
                },
                {
                    id: 2,
                    title: 'crear-reserva'
                },
                {
                    id: 3,
                    title: 'editar-reserva'
                },
                {
                    id: 4,
                    title: 'eliminar-reserva'
                },
                {
                    id: 5,
                    title: 'crear-usuario'
                },
                {
                    id: 6,
                    title: 'editar-usuario'
                },
            ],
            selectedTasks: [],
            rol_id: 0,
            nombre: '',
            descripcion: '',
            arrayRol: [],
            arrayPermisos: [],
            arrayPermisosAsignados: [],
            nombre: '',
            modal: 0,
            modal2: 0,
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

    watch: {
        selectedUsers: function (newVal, oldVal) {
            // Do what you want with the selected objects:
            console.log(newVal)
        }
    },

    computed: {
        count() {
            this.$store.state;
            return this.$store.state.posts
        },
        isActived: function () {
            return this.pagination.current_page;
        },
        //Calcula los elementos de la paginación
        pagesNumber: function () {
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
        listarRol(page, buscar, criterio) {
            let me = this;
            var url = '/rol?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(me.base + url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayRol = respuesta.roles.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    var response = error.response.data;
                    console.log(response.message,
                        response.exception,
                        response.file,
                        response.line);
                });
        },

        handleTasks(task) {
            // Do what you want with the selected objects:
            let me = this;
            axios
                .post(me.base + '/quitar/permiso', {
                    permiso: task,
                    rol_id: me.rol_id

                })
                .then(function (response) {
                    alert('guardado');

                })
                .catch(function (error) {
                    var response = error.response.data;
                    console.log(response.message, response.exception, response.file, response.line);
                });

        },

        guardarPermisos() {
            console.log(333);
            let me = this;

            axios
                .post(me.base + '/asignar/permisos', {
                    permisos: me.selectedTasks,
                    rol_id: me.rol_id

                })
                .then(function (response) {
                    alert('guardado');
                    me.modal2 = 0;

                })
                .catch(function (error) {
                    var response = error.response.data;
                    console.log(response.message, response.exception, response.file, response.line);
                });
        },

        obtenerPermisos() {

            let me = this;
            axios
                .get(me.base + '/obtener/permisos', {

                })
                .then(function (response) {
                    var respuesta = response.data;
                    me.arrayPermisos = respuesta.permisos;
                    console.log(me.arrayPermisos)

                })
                .catch(function (error) {
                    var response = error.response.data;
                    console.log(response.message, response.exception, response.file, response.line);
                });
        },

        simulateAjax() {
            let me = this;
            this.selectedTasks = me.arrayPermisos;
            console.log(this.selectedTasks)
        },

        clear() {

            let me = this;
            axios
                .post(me.base + '/quitar/permisos_all', {
                    permisos: me.selectedTasks,
                    rol_id: me.rol_id

                })
                .then(function (response) {
                    me.selectedTasks = []

                })
                .catch(function (error) {
                    var response = error.response.data;
                    console.log(response.message, response.exception, response.file, response.line);
                });

        },

        cambiarPagina(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            //Envia la petición para visualizar la data de esa página
            me.listarRol(page, buscar, criterio);
        },
        registrarRol() {
            let me = this;
            axios
                .post(me.base + '/rol/registrar', {
                    nombre: this.nombre

                })
                .then(function (response) {
                    me.cerrarModal();
                    me.listarRol(1, '', 'nombre');
                    swal({
                        title: "Muy bien!",
                        text: "Registro Almacenado!",
                        icon: "success",
                        button: "Aww yiss!",
                    });
                })
                .catch(function (error) {
                    var response = error.response.data;
                    console.log(response.message, response.exception, response.file, response.line);
                });
        },
        actualizarRol() {

            let me = this;

            axios
                .put('/rol/actualizar', {
                    nombre: this.nombre,
                    id: this.rol_id,
                })
                .then(function (response) {
                    me.cerrarModal();
                    me.listarRol(1, '', 'nombre');
                })
                .catch(function (error) {
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

        cerrarModal2() {
            this.modal2 = 0;
            this.tituloModal = '';

        },
        abrirModal(modelo, accion, data = []) {

            switch (modelo) {
                case 'rol': {
                    switch (accion) {
                        case 'registrar': {
                            this.modal = 1;
                            this.tituloModal = 'Registrar Rol';
                            this.nombre = '';
                            this.descripcion = '';
                            this.tipoAccion = 1;
                            break;
                        }
                        case 'actualizar': {
                            console.log(data);
                            this.modal = 1;
                            this.tituloModal = 'Actualizar Rol';
                            this.tipoAccion = 2;
                            this.rol_id = data['id'];
                            this.nombre = data['name'];
                            this.descripcion = data['guard_name'];
                            break;
                        }
                    }
                }
            }
        },

        abrirModalRol(id) {

            this.modal2 = 1;
            this.tituloModal = 'Asignar Permisos';
            this.tipoAccion = 1;
            this.rol_id = id;
            this.obtenerPermisos();
            this.permisosAsignados(this.rol_id);

        },

        permisosAsignados(rol_id) {

            let me = this;
            var url = '/obtener/permiso_asignado?rol_id=' + rol_id;
            axios.get(me.base + url).then(function (response) {

                    var respuesta = response.data;
                    me.selectedTasks = respuesta.permisos;
                    console.log(me.selectedTasks)

                })
                .catch(function (error) {
                    var response = error.response.data;
                    console.log(response.message,
                        response.exception,
                        response.file,
                        response.line);
                });

        },

        desactivarRol(id) {

            swal({
                title: 'Esta seguro de desactivar este rol?',
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
                        .put('/rol/desactivar', {
                            id: id,
                        })
                        .then(function (response) {
                            me.listarRol(1, '', 'nombre');
                            swal('Desactivado!', 'El registro ha sido desactivado con éxito.', 'success');
                        })
                        .catch(function (error) {
                            var response = error.response.data;
                            console.log(response.message, response.exception, response.file, response.line);
                        });
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {}
            });
        },
        activarRol(id) {
            swal({
                title: 'Esta seguro de activar este rol?',
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
                        .put('/rol/activar', {
                            id: id,
                        })
                        .then(function (response) {
                            me.listarRol(1, '', 'nombre');
                            swal('Activado!', 'El registro ha sido activado con éxito.', 'success');
                        })
                        .catch(function (error) {
                            var response = error.response.data;
                            console.log(response.message, response.exception, response.file, response.line);
                        });
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {}
            });
        },
    },
    mounted() {
        this.base = base;
        this.listarRol(1, this.buscar, this.criterio);
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
