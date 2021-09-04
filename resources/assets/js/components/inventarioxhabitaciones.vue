<template>
    <main class="main">
        <div v-if="mostrar_reservas">
            <div class="col-sm-12">
                <div class="content-header"><span class="badge badge-danger">Inventario Habitaciones</span> </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="basicTextarea"><strong><span class="badge badge-primary">{{count["filtro_calendario"]}}</span></strong></label>
                                <datepicker :format="customFormatter" v-model="filtro_calendario" :bootstrap-styling="true" @opened="datepickerAbierto" @selected="fechaSeleccionada" @closed="datepickerCerrado"></datepicker>
                                <button type="button" class="btn btn-raised gradient-mint white shadow-z-4" v-on:click="iniciar2()" name="button" style="
    position: absolute;
    left: 243px;
    top: 32px;
"><i class="fab fa-searchengin"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div></div>
            <section id="extended">
                <div class="row">
                    <div v-if="tipoCarga==1">
                        <div class="loader loader-default is-active" data-text></div>
                    </div>
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <DayPilotScheduler id="dp" :config="config" ref="scheduler" />
                                </h4>
                            </div>
                        </div>
                    </div>

                </div>
                <div id="dp" />
            </section>
        </div>
        <!-- Modal -->

        <!-- Comentario -->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalCalendario}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content" style="width: 85% !important;">
                    <div class="modal-header">
                        <button type="button" class="close" v-on:click="cerrarModalCalendario()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <DayPilotNavigator id="nav" style="width:200px" :config="config" ref="navigator" />
                    </div>
                </div>
            </div>
        </div>
        <!-- Marcar como no valido -->

        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content" style="height: 700px; width: 106% !important;">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" @submit.prevent="registrarData" enctype="multipart/form-data" class="form-horizontal">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="userinput4">Tipo Habitación</label>
                                                <select class="form-control" v-model="tipo_habitacion" required>
                                                    <option value=" 0" disabled>Seleccione</option>
                                                    <option v-for="(tipo,index) in ArrayTipoHabitaciones" :key="index" :value="tipo.id" v-text="tipo.name"></option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group" style="position: relative;left: -14px;">
                                                <label for="userinput4">Fecha Desde</label>
                                                <datepicker :format="customFormatter" v-model="fecha_desde" :bootstrap-styling="true" :required="true"></datepicker>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group" style="position: relative; left: -52px;">
                                                <label for="userinput4">Fecha Hasta</label>
                                                <datepicker :format="customFormatter" v-model="fecha_hasta" :bootstrap-styling="true" :required="true"></datepicker>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="userinput4">Disponibilidad</label>
                                                <input type="text" v-model="disponibilidad" required class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer" style="position: relative;left: -56px;">
                                <button type="submit" class="btn btn-primary">Guardar <i class="fa fa-fw fa-save"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalEditar}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Editar Inventario {{tipo_habitacion_nombre}}</h4>
                        <button type="button" class="close" @click="cerrarModalEdicion()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" @submit.prevent="registrarDataEdicion" enctype="multipart/form-data" class="form-horizontal">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="userinput4">Disponibilidad</label>
                                                <input type="number" v-model="disponibilidadEdicion" required class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Guardar <i class="fa fa-fw fa-save"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>
<script>
    import {
        DayPilot,
        DayPilotScheduler,
        DayPilotNavigator
    } from 'daypilot-pro-vue'
    import Vue from 'vue';
    import Datepicker from 'vuejs-datepicker';
    import VueSweetAlert from 'vue-sweetalert'
    import Autocomplete from 'vuejs-auto-complete';
    import VueSweetalert2 from 'vue-sweetalert2';
    import swal from 'vue-sweetalert2';
    import Multiselect from 'vue-multiselect'
    import VeeValidate from "vee-validate";
    import factura from '../extras/facturacion';
    import crearcliente from '../extras/crearcliente';
    import checkingin from '../extras/cheking';
    import prepagos from '../extras/prepagos';
    import {
        VueSpinners
    } from '@saeris/vue-spinners'
    import {
        BarLoader
    } from '@saeris/vue-spinners'
    import PulseLoader from 'vue-spinner/src/PulseLoader.vue'

    Vue.use(VueSpinners)
    Vue.use(require('vue-moment'));
    var moment = require('moment'); // in my gulp file
    const VueValidationEs = require('vee-validate/dist/locale/es');
    const Swal = require('sweetalert2')
    Vue.use(VeeValidate, {
        locale: 'es',
        dictionary: {
            es: VueValidationEs
        }
    });

    window.swal = swal
    // register globally
    Vue.component('multiselect', Multiselect)
    Vue.use(VeeValidate);

    export default {
        props: ['myResult'],
        name: 'Scheduler',
        data() {
            return {
                verDetalles: 0,
                id_edicion: '',
                disponibilidadEdicion: '',
                tipo_habitacion_nombre: '',
                modalEditar: '',
                start_edicion: '',
                disponibilidad_edicion: '',
                habitacionNumero: '',
                disponibilidad: '',
                fecha_filtro: '',
                tipo_habitacion: '',
                fecha_desde: '',
                fecha_hasta: '',
                isLoad: false,
                tipoCarga: 0,
                color: '#bada55',
                loading: false,
                TipoPagos: [],
                ArrayTipoHabitaciones: [],
                id_habitacion_tipo_modal: '',
                base: '',
                chekin: 1,
                mostrar_clientes: false,
                mostrar_reservas: true,
                guardar: true,
                config: [],
                configs: [],
                arrayBloqueosHabitaciones: [],
                fecha_llegada: '',
                fecha_salida: '',
                fecha_inicio: '',
                filtro_calendario: '',
                fecha_fin: '',
                habitacion_nombre: '',
                huespedes_cantidad: '',
                id_reservacion: 1,
                selected: null,
                tipoGuardar: 2,
                tipoBloqueo: 1,
                tipoAsignar: 0,
                disablebutton: false,
                //===============================basicos===============================//
                id: null,
                ArrayReservas: [],
                ArrayComentarios: [],
                arrayHabitacion: [],
                modalComentario: 0,
                modalCalendario: 0,
                modalAsignacion: 0,
                modalComentarios: 0,
                modalDetallesInfo: 0,
                modalDetalle: 0,
                modalBloqueos: 0,
                modalAlojados: 0,
                tituloModal: '',
                modalnoValido: 0,
                modalCargando: 1,
                descripcion: '',
                listado: 1,
                //=================================DETALLES=================================//
                habitacion_tipo: '',
                habitacionId: '',
                resultado: 0.0,
                options: [],
                value: [],
                arrayBloqueos: [],
                tipoAccion: 0,
                modal: 0,
                idiomas: [],
                submitted: false,
                show: true,
                dp: '',
                nav: '',
                busquemos: '',
                valor: '',
                separators: '',
                filtro: '',
            }
        },

        computed: {
            count() {
                this.$store.state;
                return this.$store.state.posts
            },

            // DayPilot.Scheduler object - https://api.daypilot.org/daypilot-scheduler-class/
            scheduler: function() {
                return this.$refs.scheduler.control;
            }
        },
        components: {
            checkingin,
            prepagos,
            factura,
            DayPilotScheduler,
            DayPilotNavigator,
            Autocomplete,
            Multiselect,
            crearcliente,
            BarLoader,
            PulseLoader,
            Datepicker
        },

        crearImagen() {
            this.cargarImagen()
        },
        methods: {

            registrarData() {
                let me = this;
                if (me.fecha_desde === '' || me.fecha_hasta === '') {
                    me.$swal({
                        title: "Revisa tus campos!",
                        text: "Verifique campos por llenar",
                        icon: "error",
                        button: "Aww yiss!",
                    });
                } else {
                    if ((new Date(me.fecha_desde).getTime() > new Date(me.fecha_hasta).getTime())) {
                        me.fecha_hasta = '';
                        me.$swal({
                            title: "Tu fecha esta mal!",
                            text: "Verifica la fecha",
                            icon: "error",
                            button: "Aww yiss!",
                        });
                    } else {
                        axios
                            .post(me.base + '/inventarios/registrar', {
                                tipo_habitacion: me.tipo_habitacion,
                                fecha_desde: me.fecha_desde,
                                fecha_hasta: me.fecha_hasta,
                                disponibilidad: me.disponibilidad
                            })
                            .then(function(response) {
                                me.datosHabitaciones();
                                me.cerrarModal();
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
                }
            },

            registrarDataEdicion() {
                let me = this;
                axios
                    .post(me.base + '/inventarios/editar', {
                        disponibilidad: me.disponibilidadEdicion,
                        id: me.id_edicion
                    })
                    .then(function(response) {
                        me.datosHabitaciones();
                        me.cerrarModalEdicion();
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
            },

            cerrarModalBloqueo() {
                let me = this;
                me.modalBloqueos = 0;
            },
            dataIdiomas() {
                let me = this;
                var url = '/obtener/idioma';
                var arreglo = [];
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data;
                        me.idiomas = respuesta.datos;
                        arreglo.push(respuesta.datos);
                    })
                    .catch(function(error) {
                        var response = error.response.data;
                    });
                return "Detalles";


            },
            abrirModal(modelo, accion, data = []) {
                switch (modelo) {
                    case 'data': {
                        switch (accion) {
                            case 'registrar': {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Inventario';
                                break;
                            }
                        }
                    }
                }
            },


            cerrarModalCalendario() {
                let me = this;
                me.modalCalendario = 0;
            },
            datepickerCerrado() {
                let me = this;
                me.fecha_date = 2;

            },
            customFormatter(date) {
                return moment(date).format('YYYY-MM-DD');
            },

            Pruebacalendario() {
                let me = this;
                // me.iniciar2();
            },

            NuevoPagoPrepago() {
                let me = this;
                me.tipoAccion = 1;
                me.tipo_pago = '';
                me.valor_a_pagar_f = '';

            },

            adelantos() {
                let me = this;
                me.mostrar_reservas = false;
                me.mostrar_prepagos = true;
            },

            editarPagoPrepago(pago) {
                let me = this;
                me.tipoAccion = 2;
                me.tipo_pago = pago.tipo_pago_id;
                me.valor_a_pagar_f = pago.valor_pagado;
                me.IdPrepago = pago.id;

            },

            eliminarPagoPrepago(pago) {
                let me = this;
                me.$swal({
                    title: 'Desea eliminar esta nota prepago?',
                    text: 'Eliminar Nota Prepago',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si Eliminar!',
                    cancelButtonText: 'No, Cancelar!',
                    showCloseButton: true,
                    showLoaderOnConfirm: true
                }).then((result) => {
                    if (result.value) {
                        axios
                            .post(me.base + "/reservas/eliminar_nota_prepago", {
                                idPago: pago.id,
                            })
                            .then(function(response) {
                                me.$swal('Eliminado', 'Esta nota prepago fue eliminada', 'success')
                                me.listarPrepagos(pago.reserva_id);

                            })
                            .catch(function(error, otracosa) {});

                    } else {
                        me.$swal('Canelado', 'Cancelaste esta operación', 'info')
                    }
                })

            },

            datepickerAbierto: function() {
                let me = this;
                // console.log(me.filtro_calendario);
            },
            fechaSeleccionada: function() {
                let me = this;
                // console.log(me.filtro_calendario);
            },
            datepickerCerrado: function() {
                let me = this;
                me.filtro_calendario = me.filtro_calendario;

                if (me.filtro_calendario != '') {

                    me.prueba3 = me.filtro_calendario;
                }

            },

            convert(str) {
                var date = new Date(str),
                    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
                    day = ("0" + date.getDate()).slice(-2);
                return [date.getFullYear(), mnth, day].join("-");
            },

            obtenerImpuestosProductos() {
                let me = this;
                var url = '/obtener/impuestos_habitaciones';
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data;
                        me.arrayImpuestosHabitaciones = respuesta.datos;

                    })
                    .catch(function(error) {
                        var response = error.response.data;
                    });
            },

            cerrarModalInfo() {
                let me = this;
                me.modalDetallesInfo = 0;
            },


            quitarFecha(fecha) {
                var fechaValor = new Date(fecha);
                var otraFecha = fechaValor.setDate(fechaValor.getDate() - 1);
                return otraFecha;

            },

            formatDate(date) {
                var d = new Date(date),
                    month = '' + (d.getMonth() + 1),
                    day = '' + d.getDate(),
                    year = d.getFullYear();

                if (month.length < 2) month = '0' + month;
                if (day.length < 2) day = '0' + day;

                return [year, month, day].join('-');
            },

            bloquearHabitacion() {
                let me = this;
                me.tipoBloqueo = 2;
                axios.post(me.base + "/reservas/bloquear_habitaciones", {
                        fecha_inicio: me.fecha_inicio,
                        fecha_fin: me.formatDate(me.fecha_fin),
                        habitacion: me.habitacionId

                    })
                    .then(function(response) {
                        // me.modalBloqueos = 0;
                        Vue.swal("Muy bien!", "Realizo bloqueo a esta habitación!", "success");
                        me.listarBloqueosHabitaciones(me.habitacionId);
                        me.iniciar();
                        me.tipoBloqueo = 1;
                        // me.$router.go(me.$router.currentRoute)
                    })
                    .catch(function(error, otracosa) {});

            },


            ModalInfoCalendario() {
                let me = this;
                me.modalCalendario = 1;
            },

            esperarTiempo() {
                setTimeout(function() {
                    this.isLoad = true
                }.bind(this), 3000);
            },

            busqueda(filtro) {
                let me = this;
                me.dp.events.filter("POPAYAN123", true)
            },
            filter(valor) {
                let me = this;

            },

            selectHabitacion() {
                let me = this;
                if (me.habitacion > 0) {
                    me.tipoAsignar = 1;
                }

            },

            seleccionarHabitaciones(data) {
                let me = this;
                var url = '/reservas/grupo_habitaciones?reserva_id=' + data.id_reservas;
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data.habitaciones;
                        me.GruposHabitaciones = response.data.habitaciones;
                    })
                    .catch(function(error, otracosa) {});
            },
            facturas(data) {
                let me = this;
                var url = '/reservas/reserva_factura?reserva_id=' + data.id_reservas;
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data;
                        me.Facturacion = respuesta.FacturaDetalle;
                    })
                    .catch(function(error, otracosa) {
                        // var response = error.response.data;
                    });
            },
            rerender() {
                this.show = false
                this.$nextTick(() => {
                    this.show = true
                    this.$nextTick(() => {
                    })
                })
            },

            iniciar() {
                let me = this;
                me.dp = new DayPilot.Scheduler("dp");
                me.nav = new DayPilot.Navigator("nav");
                me.dp.onEventFilter = function(args) {

                    if (args.e.text().toUpperCase().indexOf(args.filter.toUpperCase()) === -1) {
                        args.visible = false;
                    }
                };

                me.array = [];
                me.configs = {
                        showMonths: 1,

                        onTimeRangeSelected: function(args) {
                            me.dp.startDate = args.start;
                        },
                    },
                    me.nav.init();
                me.dp.init();

                var f = new Date();
                f.setDate(f.getDate() - 5);
                f = f.getFullYear() + '-' + (((f.getMonth() + 1) < 10) ? '0' : '') + (f.getMonth() + 1) + '-' + (f.getDate() < 10 ? '0' : '') + f.getDate()

                me.config = {
                    days: 666,
                    timeHeaders: [{
                            groupBy: "Month"
                        },
                        {
                            groupBy: "Week"
                        },
                        {
                            groupBy: "Day",
                            format: "ddd d"
                        }
                    ],
                    cellWidth: 90,
                    separators: [{
                        color: "red",
                        location: new DayPilot.Date.today().addDays(0),
                        layer: "AboveEvents"
                    }],
                    events: [],
                    resources: [],
                    scale: "Day",
                    contextMenu: null,
                    eventMoveHandling: "Disabled",
                    eventResizeHandling: "Disabled",
                    moveVDisabled: true,
                    moveHDisabled: true,
                    scrollTo: f,
                    CssClassPrefix: "navigator_green",
                    onBeforeCellRender: function(args) {},
                    days: DayPilot.Date.today().daysInYear(),
                    startDate: DayPilot.Date.today().addDays(0),
                    onBeforeEventRender: function(args) {
                        args.data.backColor = "transparent";
                    },
                    onTimeRangeSelected: function(args) {
                        me.modalBloqueo(args);
                    },
                    onTimeRangeSelecting: function(args) {
                        me.modalBloqueo(args);
                    },
                    onEventFilter: function(args) {
                        if (args.e.text().toUpperCase().indexOf(args.filter.toUpperCase()) === -1) {
                            args.visible = false;
                        }
                    },
                    onBeforeCellRender: function(args) {
                        if (args.cell.start <= DayPilot.Date.today() && DayPilot.Date.today() < args.cell.end) {
                            args.cell.backColor = "#ffcccc";
                        }
                        if (args.cell.resource === "A" && args.cell.start.getDay() === 1) {

                            args.cell.backColor = "#ffffd5";
                            args.cell.html = "<div style='position:absolute;right:2px;bottom:2px;font-size:8pt;color:#666;'>Maintenance</div>";
                        }
                    },
                    onRowFilter: function(args) {
                        if (args.row.name.toUpperCase().indexOf(args.filter.toUpperCase()) === -1) {
                            args.visible = false;
                        }
                    },
                    onBeforeTimeHeaderRender: function(args) {
                        if (args.header.level === 1) {
                            args.header.html = "Semana " + args.header.start.weekNumberISO();
                        }
                    },
                    onBeforeCellRender: function(args) {
                        let list = [];
                        if (args.cell.start < DayPilot.Date.today()) {
                            args.cell.areas = [{
                                left: 0,
                                right: 0,
                                top: 0,
                                bottom: 0,
                                style: "background-image: linear-gradient(135deg, #ddd 10%, transparent 10%, transparent 50%, #ddd 50%, #ddd 60%, transparent 60%, transparent); background-size: 20px 20px; width:100px"
                            }];
                        }
                    },
                };
                me.datepickerCerrado();
                me.dataIdiomas();
                me.config.contextMenu = new DayPilot.Menu({
                    items: [{
                            text: "Editar",
                            icon: "fas fa-info-circle",
                            onClick: function(args, args2) {
                                me.fnmodalDetalle(args.source.data)
                            }
                        },
                        {
                            text: "-"
                        }
                    ],
                });
            },

            iniciar2() {

                let me = this;


                me.dp = new DayPilot.Scheduler("dp");
                me.nav = new DayPilot.Navigator("nav");
                me.dp.onEventFilter = function(args) {
                    if (args.e.text().toUpperCase().indexOf(args.filter.toUpperCase()) === -1) {
                        args.visible = false;
                    }
                };

                me.array = [];
                me.configs = {
                        showMonths: 1,
                        onTimeRangeSelected: function(args) {
                            me.dp.startDate = args.start;
                        },
                    },
                    me.nav.init();
                me.dp.init();
                var f = new Date();
                f.setDate(f.getDate() - 5);
                f = f.getFullYear() + '-' + (((f.getMonth() + 1) < 10) ? '0' : '') + (f.getMonth() + 1) + '-' + (f.getDate() < 10 ? '0' : '') + f.getDate()
                me.config = {
                    days: 666,
                    timeHeaders: [{
                            groupBy: "Month"
                        },
                        {
                            groupBy: "Week"
                        },
                        {
                            groupBy: "Day",
                            format: "ddd d"
                        }
                    ],
                    cellWidth: 90,
                    separators: [{
                        color: "red",
                        location: new DayPilot.Date.today().addDays(0),
                        layer: "AboveEvents"
                    }],
                    events: [],
                    resources: [],
                    scale: "Day",
                    contextMenu: null,
                    scrollTo: f,
                    eventMoveHandling: "Disabled",
                    eventResizeHandling: "Disabled",
                    moveVDisabled: true,
                    moveHDisabled: true,
                    CssClassPrefix: "navigator_green",
                    onBeforeCellRender: function(args) {

                    },
                    days: DayPilot.Date.today().daysInYear(),
                    startDate: new DayPilot.Date(me.convert(me.filtro_calendario)),
                    onBeforeEventRender: function(args) {
                        args.data.backColor = "transparent";
                    },
                    onEventClicked: function(args) {},
                    onEventClick: function(args) {},
                    onTimeRangeSelected: function(args) {
                    },
                    onTimeRangeSelecting: function(args) {
                    },
                    onEventFilter: function(args) {
                        if (args.e.text().toUpperCase().indexOf(args.filter.toUpperCase()) === -1) {
                            args.visible = false;
                        }
                    },
                    onBeforeCellRender: function(args) {
                        if (args.cell.start <= DayPilot.Date.today() && DayPilot.Date.today() < args.cell.end) {
                            args.cell.backColor = "#ffcccc";
                        }
                        if (args.cell.resource === "A" && args.cell.start.getDay() === 1) {

                            args.cell.backColor = "#ffffd5";
                            args.cell.html = "<div style='position:absolute;right:2px;bottom:2px;font-size:8pt;color:#666;'>Maintenance</div>";
                        }
                    },
                    onEventFilter: function(args) {
                        if (args.e.text().toUpperCase().indexOf(args.filter.toUpperCase()) === -1) {
                            args.visible = false;
                        }
                    },
                    onRowFilter: function(args) {
                        if (args.row.name.toUpperCase().indexOf(args.filter.toUpperCase()) === -1) {
                            args.visible = false;
                        }
                    },
                    onBeforeTimeHeaderRender: function(args) {
                        if (args.header.level === 1) {
                            args.header.html = "Semana " + args.header.start.weekNumberISO();
                        }
                    },
                    onBeforeCellRender: function(args) {
                        let list = [];
                        $.each(me.arrayBloqueos, function(key, value) {
                            var desde = moment(value.fecha_desde);
                            var hasta = moment(value.fecha_hasta);
                            var results = me.diasEntreFechas(desde, hasta);

                            $.each(results, function(key2, value2) {

                                if (args.cell.start === new DayPilot.Date(value2) && args.cell.resource === value.habitacion_id) {
                                    args.cell.disabled = true;
                                    args.cell.backColor = "red";
                                }
                            });
                        });

                        if (args.cell.start < DayPilot.Date.today()) {
                            args.cell.areas = [{
                                left: 0,
                                right: 0,
                                top: 0,
                                bottom: 0,
                                style: "background-image: linear-gradient(135deg, #ddd 10%, transparent 10%, transparent 50%, #ddd 50%, #ddd 60%, transparent 60%, transparent); background-size: 20px 20px; width:100px"
                            }];
                        }
                    },
                };

                me.datepickerCerrado();
                me.config.contextMenu = new DayPilot.Menu({
                  items: [{
                          text: "Editar",
                          icon: "fas fa-info-circle",
                          onClick: function(args, args2) {
                              me.fnmodalDetalle(args.source.data)
                          }
                      },
                      {
                          text: "-"
                      }
                  ],
                    onShow: function(args) {


                    },
                });
                try {
                    me.mostrar_reservas = true;
                    me.cargarHabitaciones();
                    me.datosHabitaciones();
                    me.Listar();
                } catch (error) {
                    console.log(error)
                }


            },

            diasEntreFechas(desde, hasta) {
                var dia_actual = desde;
                var fechas = [];
                while (dia_actual.isSameOrBefore(hasta)) {
                    fechas.push(dia_actual.format('YYYY-MM-DD'));
                    dia_actual.add(1, 'days');
                }
                return fechas;
            },


            modalBloqueo(data) {

                let me = this;
                var url = '/obtener/listar_habitacion?habitacion=' + data.resource;
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data.data;

                        me.habitacionNumero = respuesta.numero;
                        me.habitacionId = respuesta.id;

                    })
                    .catch(function(error, otracosa) {});
                me.listarBloqueosHabitaciones(data.resource);
                me.modalBloqueos = 1;
                me.fecha_inicio = data.start.value;
                me.fecha_fin = me.quitarFecha(data.end.value);

            },


            listarPrepagos(reserva_id) {
                let me = this;
                var url = '/reservas/obtener_prepagos_notas?reserva_id=' + reserva_id;
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data;
                        me.Pagos = respuesta.datos;

                    })
                    .catch(function(error, otracosa) {

                    });

            },

            listarBloqueosHabitaciones(data) {
                let me = this;
                var url = '/obtener/listar_bloqueos_habitaciones?habitacion=' + data;
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data.data;
                        me.arrayBloqueosHabitaciones = respuesta;

                    })
                    .catch(function(error, otracosa) {});
            },


            updateColor: function(e, color) {
                let me = this;
                axios
                    .post(me.base + "/reservas/changecolor", {
                        id_reserva: e.data.id,
                        color: color
                    })
                    .then(function(response) {
                        me.datosHabitaciones();
                    })
                    .catch(function(error, otracosa) {});
            },
            validar(id_reservas) {
                let me = this;
                return axios
                    .post(me.base + "reservas/ReservasCompletas", {
                        id_reservas: id_reservas
                    })
                    .then(function(response) {
                        var data = response.data;
                        return data.validate;
                    })
                    .catch(function(error, otracosa) {
                        return false
                    });
            },
            cargarHabitaciones() {
                let me = this;
                axios
                    .get(me.base + "/habitaciones/index")
                    .then(function(response) {
                        var data = response.data;
                        me.resources = data.datos;
                        me.ArrayTipoHabitaciones = data;
                        me.scheduler.update({
                            resources: data
                        });

                    })
                    .catch(function(error, otracosa) {});
            },
            datosHabitaciones() {
                let me = this;

                axios
                    .get(me.base + "/inventarios/inventario_habitaciones")
                    .then(function(response) {
                        var data = response.data;
                        me.scheduler.update({
                            events: response.data
                        });
                    })
                    .catch(function(error, otracosa) {
                        console.log('error', error)
                    });
            },
            actualizar() {
                let me = this;
                me.datosHabitaciones();
            },
            ModalReservas(reserva) {

                this.obtenerImpuestosProductos();
                this.modalAsignacion = 1;
                this.id_grupo_reserva = reserva.id_grupo
                this.fecha_llegada = reserva.fecha_llegada;
                this.fecha_salida = reserva.fecha_salida;
                this.huespedes_cantidad = parseInt(reserva.adultos_cantidad) + parseInt(reserva.infantes_cantidad) + parseInt(reserva.ninos_cantidad);
                this.infantes_cantidad = reserva.infantes_cantidad;
                this.ninos_cantidad = reserva.ninos_cantidad;
                this.adultos_cantidad = reserva.adultos_cantidad;
                this.habitacion_nombre = reserva.habitacion_nombre;
                this.precio_total = reserva.precio_total;
                this.id_habitacion_tipo_modal = reserva.id_habitacion_tipo
                this.info = reserva;

                let me = this;
                me.arrayHabitacion = [];
                var url = '/obtener/habitaciones?tipo_habitacion=' + reserva.id_habitacion_tipo + '&fecha_llegada=' + reserva.fecha_llegada + '&fecha_salida=' + reserva.fecha_salida;
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data;
                        me.arrayHabitacion = respuesta.habitaciones;
                    })
                    .catch(function(error) {
                        var response = error.response.data;

                    });


                var id_tipo_habitacion = reserva.id_tipo_habitacion;
                var fecha_llegada = reserva.fecha_llegada;
                var fecha_salida = reserva.fecha_salida;
            },

            Asignar(info, habitacion) {
                let me = this;
                me.loading = true;
                me.tipoCarga = 1;
                if (me.habitacion != '0') {
                    axios.post(me.base + '/reservas/actualizar', {
                        'data': info,
                        'habitacion': habitacion,
                        'dato_impuesto': me.dato_impuesto,
                        'numero_impuesto': me.numero_impuesto,
                        'id_grupo_reserva': me.id_grupo_reserva,
                        'impuesto': me.checkedCategories,

                    }).then(function(response) {
                        me.tipoCarga = 0;
                        me.datosHabitaciones();
                        me.Listar();
                        me.cerrarModalAsignacion();
                        me.habitacion = 0;
                        me.tipoAsignar = 0;
                        me.cargarHabitaciones();
                    }).catch(function(error) {
                        alert('Error');

                    });
                }
            },
            cerrarModal() {
                this.modal = 0;
                this.tipo_habitacion = '';
                this.fecha_desde = '';
                this.fecha_hasta = '';
                this.disponibilidad = '';

            },
            cerrarModalEdicion() {
                this.modalEditar = 0;
                this.disponibilidadEdicion = '';

            },
            cerrarModalAsignacion() {
                this.modalAsignacion = 0;
                this.habitacion = 0;
            },
            cerrarModalComentario() {
                this.modalComentarios = 0;
            },
            cerrarModalPrepago() {
                this.modalPrepagos = 0;
            },
            nuevoCliente() {
                let me = this;
                me.modalCliente = true;
            },
            Listar() {
                let me = this;
                me.ArrayReservas = [];
                axios
                    .get(me.base + "/reservas/VerReservasChanel")
                    .then(function(response) {

                        me.ArrayReservas = response.data;
                    })
                    .catch(function(error, otracosa) {});
            },
            startInterval() {
                let me = this;
                if (me.test) {
                    setInterval(function() {
                        if (me.tiempo == 0) {
                            me.tiempo = 60;
                            me.Listar();
                            me.porcentajes = 100;
                        }
                        me.porcentaje = Math.round((me.tiempo * 100) / 60);
                        me.tiempo = me.tiempo - 1;
                    }, 1000);
                }
            },
            fnmodalDetalle(data) {
                let me = this;
                me.modalEditar = 1;
                me.start_edicion = data.start;
                me.disponibilidad_edicion = data.text;
                me.tipo_habitacion_nombre = data.tipo_habitacion;
                me.id_edicion = data.identificador;

            },
            fnmodalAlojados(data) {
                this.modalAlojados = 1;
            },
            fnmodalComentario(data) {
                this.requisitos_especiales = data.requisitos;
                this.comentarios = data.comentarios;
                this.modalComentario = 1;
            },
            savemodalComentario(data) {
                let me = this;
                axios.
                post(me.base + "reservas/save/comentarios/" + me.id, {
                    requisitos_especiales: me.requisitos_especiales,
                    comentarios: me.comentarios,
                }).
                then(function(response) {
                    if (response.data.validate) {
                        me.Listar();
                        me.modalComentario = 0;
                        Vue.swal("Guardado el comentario con éxito");
                    } else {
                        alert('Error');
                    }
                });

            },
            fnmodalnoValido(data) {
                this.modalnoValido = 1;
            },
            recargar() {
                let me = this;
                me.$router.go(me.$router.currentRoute)
            },


        },

        created() {

            Echo.join(`chat`)
                .here((users) => {
                    //
                })
                .joining((user) => {
                })
                .leaving((user) => {
                });
        },


        mounted() {

            this.base = base;
            let me = this;
            this.iniciar();
            this.dataIdiomas();
            this.cargarHabitaciones();
            //this.scheduler.message("Bienvenidos");
            this.datosHabitaciones();
            this.Listar();
            this.startInterval();

            Echo.join(`chat`)
                .here((users) => {
                    //
                })
                .joining((user) => {
                })
                .leaving((user) => {
                });
        }
    }
</script>
<style>
    #gantt_container {
        width: 100%;
        overflow-y: auto;
        overflow-x: hidden;
    }

    .asd {
        width: 30px;
        height: 30px;
    }

    .asd2 {
        background-color: #ff6347;
    }

    .asd1 {
        background-color: #65cc00;
    }

    .main {
        width: 100%;
    }

    .bold {
        font-weight: bold;
    }

    /*======================================================================*/
    .context-menu-list {
        cursor: pointer;
        margin: 0;
        padding: 0;
        min-width: 120px;
        max-width: 250px;
        display: inline-block;
        position: absolute;
        list-style-type: none;
        border: 1px solid hsl(0, 0%, 87%);
        background: hsl(0, 0%, 93%);
        -webkit-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
        -moz-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
        -ms-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
        -o-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
        font-family: Verdana, Arial, Helvetica, sans-serif;
        font-size: 11px;
    }

    .datra {
        display: inline-table;
        height: 16px;
        width: 16px;
        margin: 3px;
    }

    * .wd {
        box-sizing: content-box;
    }

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

    #scroll {
        overflow-y: scroll;
        height: 560px;
    }

    .modal-body {
        overflow-y: scroll;
        height: 240px;
    }

    .c100.small {
        font-size: 35px;
    }

    .list-group-item {
        padding: 0px;
    }

    .reservaciones-title {
        padding-top: 6px;
    }

    .splittwo {
        width: 50%;
        float: left;
    }

    .bold {
        font-weight: bold;
        font-size: 13px;
    }

    /*======================================================================*/
    .icon {
        padding: 1px 1px 0px 8px;
    }

    .icon-blue {
        background-color: #1155cc;
    }

    .icon-green {
        background-color: #6aa84f;
    }

    .icon-yellow {
        background-color: #f1c232;
    }

    .icon-red {
        background-color: #cc0000;
    }

    .scheduler_default_corner_inner div {
        color: #F3F3F3 !important;
        background-color: #F3F3F3 !important;
    }

    span.error {
        color: #9F3A38;
    }

    .scheduler_default_corner div:nth-of-type(2) {
        display: none !important;
    }

    .scheduler_default_event_inner {
        color: black;
        border-radius: 0px;
        font-size: 9px;
        background: #14adce;
        position: relative;
        top: 13px;
        left: 0px;
        height: 15px;
        /*  background-color: #cfdde8;
   */
    }

    .scheduler_default_event_bar {
        /*	border-radius: 14px;
   font-size: 9px;
   position: absolute;
   left: 6040px;
   top: 175px;
   width: 40px;
   height: 35px;
   white-space: nowrap;
   overflow: hidden;
   cursor: pointer;
   border-radius: 14px;
   */
    }

    /*.scheduler_default_event_bar_inner{
   display: none;
   }
   .scheduler_default_event_bar {
   display: none;
   }
   */
    .scheduler_default_event_bar {
        /*	background: transparent;
   */
    }

    .scheduler_default_event_bar_inner {
        height: 13px;
        white-space: nowrap;
    }

    .example {
        margin: 20px;
    }

    .example input {
        display: none;
    }

    .example label {
        margin-right: 20px;
        display: inline-block;
        cursor: pointer;
    }

    .ex1 span {
        display: block;
        padding: 5px 10px 5px 25px;
        border: 2px solid #ddd;
        border-radius: 5px;
        position: relative;
        transition: all 0.25s linear;
    }

    .ex1 span:before {
        content: '';
        position: absolute;
        left: 5px;
        top: 50%;
        -webkit-transform: translatey(-50%);
        transform: translatey(-50%);
        width: 15px;
        height: 15px;
        border-radius: 50%;
        background-color: #ddd;
        transition: all 0.25s linear;
    }

    .ex1 input:checked+span {
        background-color: #fff;
        box-shadow: 0 0 5px 2px rgba(0, 0, 0, 0.1);
    }

    .ex1 .red input:checked+span {
        color: red;
        border-color: red;
    }

    .ex1 .red input:checked+span:before {
        background-color: red;
    }

    .ex1 .blue input:checked+span {
        color: blue;
        border-color: blue;
    }

    .ex1 .blue input:checked+span:before {
        background-color: blue;
    }

    .ex1 .orange input:checked+span {
        color: orange;
        border-color: orange;
    }

    .ex1 .orange input:checked+span:before {
        background-color: orange;
    }

    .cargando {
        background-image: url('/img/cargando.gif');
        background-repeat: no-repeat;
        background-position-x: center;
        background-position-y: 30px;
        height: 100px;
        margin-top: 30px;
        margin-bottom: 30px;
    }

    #loading-bar-spinner.spinner {
        left: 50%;
        margin-left: -20px;
        top: 50%;
        margin-top: -20px;
        position: absolute;
        z-index: 19 !important;
        animation: loading-bar-spinner 400ms linear infinite;
    }

    #loading-bar-spinner.spinner .spinner-icon {
        width: 40px;
        height: 40px;
        border: solid 4px transparent;
        border-top-color: #00C8B1 !important;
        border-left-color: #00C8B1 !important;
        border-radius: 50%;
    }

    @keyframes loading-bar-spinner {
        0% {
            transform: rotate(0deg);
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }

    .loader.is-active:before {
        display: block;
        position: absolute;
        top: 214px;
    }

    .loader-default:after {
        content: "";
        position: fixed;
        width: 48px;
        height: 48px;
        border: 8px solid #fff;
        border-left-color: transparent;
        border-radius: 50%;
        top: calc(17% - 24px);
        left: calc(50% - 24px);
        animation: rotation 1s linear infinite;
    }

    .blue1 {
        color: rgb(20, 103, 239);
    }

    .verde {
        color: rgb(20, 179, 25);
    }

    .gris {
        color: rgb(117, 111, 111);
    }

    .navigator_default_titleleft {
        background-image: linear-gradient(45deg, #23BCBB, #45E994);
    }

    .navigator_default_titleright {
        background-image: linear-gradient(45deg, #23BCBB, #45E994);
    }

    .navigator_default_title {
        background-image: linear-gradient(45deg, rgb(202, 216, 83), rgb(255, 160, 0));
    }

    .navigator_8_main {
        font-family: Segoe UI Light, Segoe UI, Arial, Helvetica, Verdana, Sans Serif;
    }

    .navigator_8_main {
        border-left: 1px solid #A0A0A0;
        border-right: 1px solid #A0A0A0;
        border-bottom: 1px solid #A0A0A0;
        background-color: white;
        color: #000000;
    }

    .navigator_8_month {
        font-size: 8pt;
        /*border: 1px solid black;*/
    }

    .navigator_8_day {
        color: black;
        /*background-color: white;*/
    }

    .navigator_8_weekend {
        background-color: #f0f0f0;
    }

    .navigator_8_dayheader {
        color: black;
    }

    .navigator_8_line {
        border-bottom: 1px solid #A0A0A0;
    }

    .navigator_8_dayother {
        color: gray;
    }

    .navigator_8_todaybox {
        border: 1px solid red;
    }

    .navigator_8_select {
        background-color: #FFE794;
    }

    .navigator_8_title,
    .navigator_8_titleleft,
    .navigator_8_titleright {
        border-top: 1px solid #A0A0A0;
        color: #ffffff;
        background: #646464;
        -ms-transition: background-color .3s linear;
        -moz-transition: background-color .3s linear;
        -webkit-transition: background-color .3s linear;
        transition: background-color .3s linear;
    }

    .navigator_8_busy {
        font-weight: bold;
    }

    .container-checkbox {
        display: block;
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

    .scheduler_default_event_bar_inner {
        height: 18px;
    }

    .scheduler_default_event_bar {
        /* position: absolute;
    left: -2px;
    height: 13px;
    width: 92px; */
        /* background-color: #1976d2; */
    }
</style>
