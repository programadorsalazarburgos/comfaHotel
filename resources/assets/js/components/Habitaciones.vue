<template>
    <main class="main">
        <div class="col-12">
            <div class="content-header">Habitaciones</div>
        </div>
        <section id="extended">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"></h4>
                            <div class="input-group">
                                <select class="form-control" v-model="criterio">
                                    <option value="numero">Numero</option>
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="ListarItems(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
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
                                                <th>Nombre</th>
                                                <th>Numero</th>
                                                <th>Estado</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="Habitaciones in arrayHabitaciones" :key="Habitaciones.id">
                                                <td v-text="Habitaciones.nombre"></td>
                                                <td v-text="Habitaciones.numero"></td>
                                                <td>
                                                    <template v-if="Habitaciones.condicion===true">
                                                        <span class="badge badge-info">Activado</span>
                                                    </template>
                                                    <template v-if="Habitaciones.condicion===false">
                                                        <span class="badge badge-danger">Desactivado</span>
                                                    </template>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn-warning" title="Editar" @click="verhabitacion(Habitaciones.id)" style="background-color: #f7d24acc; border-color: #f7d24acc;">
                                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                                    </button>
                                                    <template v-if="Habitaciones.condicion==1">
                                                        <button type="button" class="btn-danger" title="Desactivar" @click="desactivarRegistro(Habitaciones.id)"> <i class="fa fa-minus-square-o" aria-hidden="true"></i></button>
                                                    </template>
                                                    <template v-if="Habitaciones.condicion==0">
                                                        <button type="button" title="Activar" class="btn-primary" @click="activarRegistro(Habitaciones.id)"> <i class="fa fa-check-square-o" aria-hidden="true"></i></button>
                                                    </template>
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
                            <h5 class="card-title">Habitaciones</h5>
                            <div class="container-fluid">
                                <form action="" method="post" @submit.prevent="registrarData" enctype="multipart/form-data">
                                    <div class="col-sm-12">
                                        <label for="">tipo habitacion</label>
                                        <select required class="form form-control" v-model="id_habitacion_tipo" name="tipo de habitacion">
                                            <option v-for="habitaciones in arrayHabitacionesTipo" :key="habitaciones.id" :value="habitaciones.id">{{habitaciones.nombre}}</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-12">
                                        <label for="">Número</label>
                                        <input required type="text" class="form form-control" name="numero" v-model="numero">
                                    </div>
                                    <div class="col-sm-12">
                                        <label for="">Ubicación</label>
                                        <select class="form form-control" v-model="ubicacion" name="ubicacion">
                                            <option v-for="data in arrayUbicaciones" :key="data.id" :value="data.id">{{data.name}}</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-12">
                                        <br>
                                        <button type="submit" v-if="tipoGuardar==1" class="btn btn-raised gradient-nepal white card-shadow">Guardar<i class="fa fa-fw fa-save"></i></button>
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
    import VueSweetAlert from 'vue-sweetalert';
    import Multiselect from 'vue-multiselect'
    import swal from "sweetalert2"
    import Vue from 'vue'
    import VueSweetalert2 from 'vue-sweetalert2';
    Vue.use(VueSweetalert2);
    export default {
        data() {
            return {
                base: '',
                pag_actual: 1,
                Paginas: 0,
                selected: null,
                SivalidaForm: false,
                tipoGuardar: 1,
                id: null,
                disablebutton: false,
                id_habitacion_tipo: null,
                numero: '',
                piso: '',
                ubicacion: '',
                personas_minimo: 1,
                personas_maximo: 1,
                id_cama: '',
                arrayCamas: [],
                arrayHabitacionesTipo: [],
                arrayUbicaciones: [],
                arrayHabitaciones: [],
                arrayPlanes: [],
                options: [],
                value: [],
                pagination: {
                    total: 0,
                    current_page: 0,
                    per_page: 0,
                    last_page: 0,
                    from: 0,
                    to: 0
                },
                // value: [{
                //     nombre: 'comida',
                //     id: 2
                // }],
                // options: [{
                //     name: 'Desayuno',
                //     id: 'Desayuno'
                // }, {
                //     name: 'Almuerzo',
                //     id: 'Almuerzo'
                // }, {
                //     name: 'Comida',
                //     id: 'Comida'
                // }],
                buscar: "",
                submitted: false,
                buscar: "",
                criterio: "numero",
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
            };
        },

        components: {
            Multiselect
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
            handleSubmit(e) {
                console.log(e);
            },
            verhabitacion(id) {
                let me = this;
                me.value = [];
                me.selected = id;
                var data = me.arrayHabitaciones.find(
                    Habitaciones => Habitaciones.id === id
                );
                me.ListarPlanesHabitacion(me.selected);
                me.id = id;
                me.id_habitacion_tipo = data.id_tipo;
                me.numero = data.numero;
                me.ubicacion = data.ubicacion;
                me.tipoGuardar = 2;
            },


            cambiarPagina(page, buscar, criterio) {
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.ListarItems(page, buscar, criterio);
            },



            ListarPlanesHabitacion(id) {
                let me = this;
                var url = '/habitaciones/ver_habitaciones_planes?id=' + id;
                axios.get(url).then(function(response) {
                        var respuesta = response.data;
                        console.log(respuesta.planes);
                        me.value = respuesta.planes;
                    })
                    .catch(function(error) {
                        var response = error.response.data;
                        console.log(response.message,
                            response.exception,
                            response.file,
                            response.line);
                    });

            },

            ListarItems(page, buscar, criterio) {
                let me = this;
                var url = '/habitaciones/ver_habitaciones?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response) {
                        var respuesta = response.data;
                        me.arrayHabitaciones = respuesta.items.data;
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



            ListarPlanes(page, buscar, criterio) {
                let me = this;
                var url = '/habitaciones/ver_planes';
                axios.get(url).then(function(response) {
                        var respuesta = response.data;
                        me.options = respuesta.planes;
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
            tipoHabitacion() {
                let me = this;
                axios
                    .get(me.base + "/habitaciones/obtener")
                    .then(function(response) {
                        console.log(response)
                        me.arrayHabitacionesTipo = response.data.habitaciones;
                    })
                    .catch(function(error, otracosa) {});
            },
            ubicaciones() {
                let me = this;
                axios
                    .get(me.base + "/habitaciones/ubicaciones")
                    .then(function(response) {
                        console.log(response)
                        me.arrayUbicaciones = response.data.datos;
                    })
                    .catch(function(error, otracosa) {});
            },

            desactivarRegistro(id) {
                let me = this;
                me.$swal({
                    title: 'Esta seguro de desactivar este registro?',
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
                            .put('/habitaciones/desactivar', {
                                id: id,
                            })
                            .then(function(response) {
                                me.ListarItems(1, '');
                                me.$swal('Desactivado!', 'El registro ha sido desactivado con éxito.', 'success');
                            })
                            .catch(function(error) {
                                var response = error.response.data;
                                console.log(response.message, response.exception, response.file, response.line);
                            });
                    } else if (

                        result.dismiss === swal.DismissReason.cancel
                    ) {

                    }
                });
            },
            activarRegistro(id) {
                let me = this;
                me.$swal({
                    title: 'Esta seguro de activar este registro?',
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
                            .put('/habitaciones/activar', {
                                id: id,
                            })
                            .then(function(response) {
                                me.ListarItems(1, '');
                                me.$swal('Activado!', 'El registro ha sido activado con éxito.', 'success');
                            })
                            .catch(function(error) {
                                var response = error.response.data;
                                console.log(response.message, response.exception, response.file, response.line);
                            });
                    } else if (
                        // Read more about handling dismissals
                        result.dismiss === swal.DismissReason.cancel
                    ) {}
                });
            },
            inventario() {
                console.log(2111);
                axios
                    .post(me.base + '/habitaciones/inventario_nuevo', {
                        id_habitacion_tipo: me.id_habitacion_tipo,
                        numero: me.numero
                    })
                    .then(function(response) {})
                    .catch(function(error) {});
            },
            registrarData() {
                let me = this;
                if (me.tipoGuardar == 1) {
                    axios
                        .post(me.base + '/habitaciones/nueva', {
                            id_habitacion_tipo: me.id_habitacion_tipo,
                            numero: me.numero,
                            ubicacion: me.ubicacion
                        })
                        .then(function(response) {
                            me.ListarItems(1, '');
                            Vue.swal({
                                title: "Registro almacenado",
                                icon: "success",
                                button: "Aww yiss!",
                            });
                            axios
                                .post(me.base + '/habitaciones/inventario_nuevo', {
                                    id_habitacion_tipo: me.id_habitacion_tipo,
                                    numero: me.numero,
                                    habitacion_id: response.data['id']
                                })
                                .then(function(response) {})
                                .catch(function(error) {});
                                me.id_habitacion_tipo = '';
                                me.numero = '';
                                me.ubicacion = '';
                                me.disablebutton = false;


                        })
                        .catch(function(error) {
                            Vue.swal({
                                title: "Dato existente",
                                icon: "error",
                                button: "Aww yiss!",
                            });
                            me.id_habitacion_tipo = '';
                            me.numero = '';
                            me.ubicacion = '';
                            me.disablebutton = false;
                        });
                }
                if (me.tipoGuardar == 2) {
                    axios
                        .post(me.base + '/habitaciones/Editar', {
                            id: me.id,
                            id_habitacion_tipo: me.id_habitacion_tipo,
                            numero: me.numero,
                            ubicacion: me.ubicacion

                        })
                        .then(function(response) {
                            me.id_habitacion_tipo = '';
                            me.numero = '';
                            me.ubicacion = '';
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

            addTag(newTag) {
                const tag = {
                    name: newTag,
                    id: newTag.substring(0, 2) + Math.floor((Math.random() * 10000000))
                }
                this.options.push(tag)
                this.value.push(tag)
            },
            GuardarEditarHabitacion() {

                this.submitted = true;
                this.$validator.validate().then(valid => {
                    if (valid) {
                        let me = this;
                        console.log(me.id);
                        me.disablebutton = true;
                        axios
                            .post(me.base + "/habitaciones/Editar", {
                                id: me.id,
                                id_habitacion_tipo: me.id_habitacion_tipo,
                                numero: me.numero,
                                piso: me.piso,
                                personas_minimo: me.personas_minimo,
                                personas_maximo: me.personas_maximo,
                                id_cama: me.id_cama
                            })
                            .then(function(response) {
                                if (response.data.validate) {
                                    me.$swal("se ha editado la habitacion " + me.numero + " con éxito");
                                    me.ListarItems(1, '');
                                    me.verhabitacion(me.id);
                                } else {
                                    me.$swal("Se presento un problema al editar habitacion " + me.numero + ".");
                                }
                                me.disablebutton = false;
                            })
                            .catch(function(error) {
                                me.disablebutton = false;
                            });
                    } else {
                        me.$swal("Por favor valide todos los campos");
                    }
                });

            }
        },
        mounted() {
            this.base = base;
            this.ListarItems(1, '');
            this.tipoHabitacion();
            this.tipoCamas();
            this.ListarPlanes();
            this.ubicaciones();
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
</style>
