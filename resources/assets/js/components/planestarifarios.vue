<template>
    <main class="main">
        <div class="col-12">
            <div class="content-header">Planes Tarifarios</div>
        </div>
        <section id="extended">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"></h4>
                            <div class="input-group">
                                <select class="form-control" v-model="criterio">
                                    <option value="nombre">Nombre plan</option>
                                    <option value="codigo_reservas">Código reserva</option>
                                    <option value="codigo_pms">Código pms</option>
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="ListarItems(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                <button class="btn btn-raised gradient-pomegranate white big-shadow" type="submit" @click="ListarItems(1,buscar,criterio)">
                                    <span class="btn-icon"><i class="fa fa-search"></i>Buscar</span>
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
                                                <th>Tipo Habitaciones</th>
                                                <th>Codigo Reserva</th>
                                                <th>Codigo Pms</th>
                                                <th>Acción & Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="planes in arrayData" :key="planes.id">
                                                <td v-text="planes.nombre"></td>
                                                <td>
                                                  <div v-for="data in planes.tipo_habitaciones">
                                                    <span class="badge badge-info mb-1 mr-2">{{data.name}}</span>
                                                  </div>
                                                </td>
                                                <td v-text="planes.codigo_reservas"></td>
                                                <td v-text="planes.codigo_pms"></td>
                                                <td>
                                                    <button type="button" class="btn-warning" title="Editar" @click="EditarPlan(planes)" style="background-color: #f7d24acc; border-color: #f7d24acc;">
                                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                                    </button>
                                                		<toggle-button :value="true":labels="{checked: 'On', unchecked: 'Off'}" v-model="planes.estado" @change="onChangeEventHandler(planes)"/>
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
                            <h5 class="card-title">ALTA/ACTUALIZACIÓN DE PLANES TARIFARIOS</h5>
                            <div class="container-fluid">
                                <form action="" method="post" @submit.prevent="registrarData" enctype="multipart/form-data">
                                    <div class="col-sm-12">
                                        <label for="">Nombre</label>
                                        <input required type="text" class="form form-control" name="nombre" v-model="nombre">
                                    </div>
                                    <div class="col-sm-12">
                                        <label for="">Descripción</label>
                                        <textarea required type="text" class="form form-control" v-model="descripcion"></textarea>
                                    </div>
                                    <div class="col-sm-12">
                                        <label for="">Tipos de habitación donde aplica</label>
                                        <multiselect v-model="value" tag-placeholder="Add this as new tag"  label="name" track-by="code" :options="options" :multiple="true" :taggable="true" @tag="addTag">
                                        </multiselect>
                                    </div>
                                    <div class="col-sm-12">
                                        <label for="">Código motor de reservas</label>
                                        <input required type="text" class="form form-control" v-model="codigo_reservas">
                                    </div>
                                    <div class="col-sm-12">
                                        <label for="">Código PMS</label>
                                        <input required type="text" class="form form-control" v-model="codigo_pms">
                                    </div>
                                    <div class="col-sm-12">
                                        <label for="">Ocupación Tarifa</label>
                                        <input required type="number" class="form form-control" v-model="ocupacion_tarifa">
                                    </div>
                                    <div class="col-sm-12">
                                        <label for="">Menores Incluidos</label>
                                        <input required type="number" class="form form-control" v-model="menores_incluidos">
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
    import VueSweetAlert from 'vue-sweetalert';
    import Multiselect from 'vue-multiselect'
    import Vue from 'vue';
    import ToggleButton from 'vue-js-toggle-button'
    Vue.use(ToggleButton)

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
                nombre: '',
                codigo_reservas: '',
                codigo_pms: '',
                ocupacion_tarifa: '',
                menores_incluidos: '',
                descripcion: '',
                id_plan_tarifario: '',
                arrayData: [],
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

            cambiarPagina(page, buscar, criterio) {
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.ListarItems(page, buscar, criterio);
            },

            ListarItems(page, buscar, criterio) {
                let me = this;
                var url = '/planes_tarifarios/ver_planes_tarifarios?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response) {
                        var respuesta = response.data;
                        me.arrayData = respuesta.items.data;
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
            EditarPlan(plan)
            {
              let me = this;
              me.value = plan.tipo_habitaciones;
              me.nombre = plan.nombre;
              me.descripcion = plan.descripcion;
              me.codigo_reservas = plan.codigo_reservas;
              me.codigo_pms = plan.codigo_pms;
              me.id_plan_tarifario = plan.id;
              me.ocupacion_tarifa = plan.ocupacion_tarifa;
              me.menores_incluidos = plan.menores_incluidos;
              me.tipoGuardar = 2;

            },
            TipoHabitaciones(page, buscar, criterio) {
                let me = this;
                var url = '/cargar_tipo_habitaciones';
                axios.get(url).then(function(response) {
                        var respuesta = response.data;
                        me.options = respuesta.datos;
                    })
                    .catch(function(error) {
                        var response = error.response.data;
                        console.log(response.message,
                            response.exception,
                            response.file,
                            response.line);
                    });
            },
            onChangeEventHandler(plan)
            {
              let me = this;
              axios.post(me.base + '/planes_tarifarios/actualizar_estado', {
                  'data': plan,
              }).then(function(response) {
              }).catch(function(error) {
                  alert('Error');

              });

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
            registrarData() {
                let me = this;
                if (me.tipoGuardar == 1) {
                    axios
                        .post(me.base + '/planes_tarifarios/nuevo', {
                            nombre: me.nombre,
                            value: me.value,
                            descripcion: me.descripcion,
                            codigo_reservas: me.codigo_reservas,
                            codigo_pms: me.codigo_pms,
                            ocupacion_tarifa: me.ocupacion_tarifa,
                            menores_incluidos: me.menores_incluidos,
                        })
                        .then(function(response) {
                            me.ListarItems(1, '');
                            me.nombre = '';
                            me.value = [];
                            me.descripcion = '';
                            me.codigo_reservas = '';
                            me.codigo_pms = '';
                            me.ocupacion_tarifa = '';
                            me.menores_incluidos = '';
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
                        .post(me.base + '/planes_tarifarios/editar', {
                            id: me.id_plan_tarifario,
                            nombre: me.nombre,
                            value: me.value,
                            descripcion: me.descripcion,
                            codigo_reservas: me.codigo_reservas,
                            codigo_pms: me.codigo_pms,
                            ocupacion_tarifa: me.ocupacion_tarifa,
                            menores_incluidos: me.menores_incluidos,

                        })
                        .then(function(response) {
                          me.ListarItems(1, '');
                          me.id_plan_tarifario = '';
                          me.nombre = '';
                          me.value = [];
                          me.descripcion = '';
                          me.codigo_reservas = '';
                          me.codigo_pms = '';
                          me.ocupacion_tarifa = '';
                          me.menores_incluidos = '';
                          me.tipoGuardar = 1;
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
                    code: newTag.substring(0, 2) + Math.floor((Math.random() * 10000000))
                }
                this.options.push(tag)
                this.value.push(tag)
            },

        },
        mounted() {
            this.base = base;
            this.ListarItems(1, '');
            this.tipoHabitacion();
            this.TipoHabitaciones();
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
