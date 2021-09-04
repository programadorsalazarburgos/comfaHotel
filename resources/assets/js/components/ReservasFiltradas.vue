<template>
    <main class="main">

        <div class="col-12">
            <div class="content-header">Reservas filtradas</div>
        </div>
        <br>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- <router-link :to="{ name: 'crearreservacion' }" tag="button" class="btn btn-raised gradient-mint white shadow-z-4">{{count["crear_reserva_orbe"]}} <i class="fas fa-book-medical"></i></router-link> -->
                            <router-link :to="{ name: 'reservamanual' }" tag="button" style="background-image: linear-gradient(45deg, rgb(202, 216, 83), #ffa000);" class="btn btn-raised gradient-mint white shadow-z-4">
                                Nueva Reserva <i class="fas fa-book-medical"></i></router-link>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div v-if="mostrar_reservas">
            <section id="extended">
                <div class="row">
                    <div v-if="tipoCarga==1">
                        <div class="loader loader-default is-active" data-text></div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"></h4>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-sm-4 col-md-2">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">No reserva</label>
                                                    <input type="text" style="width: 100%;" v-model="no_reserva" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-md-2">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Fecha reserva desde</label>
                                                    <datepicker style="width: 100% !important;" :format="customFormatter" class="form-calendario"  @closed="datepickerCerradoReserva" v-model="fecha_reserva" :bootstrap-styling="true"></datepicker>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-md-2">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Fecha reserva hasta</label>
                                                    <datepicker style="width: 100% !important;" :format="customFormatter" class="form-calendario"  v-model="fecha_reserva_hasta" :bootstrap-styling="true"></datepicker>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-md-2 col-md-push-2">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Fecha llegada desde</label>
                                                    <datepicker style="width: 100% !important;" :format="customFormatter" class="form-calendario" @closed="llegadaDesde" v-model="fecha_llegada" :bootstrap-styling="true"></datepicker>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-md-2 col-md-push-2">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Fecha llegada hasta</label>
                                                    <datepicker style="width: 100% !important;" :format="customFormatter" class="form-calendario" v-model="fecha_llegada_hasta" :bootstrap-styling="true"></datepicker>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-md-2 col-md-push-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Fecha salida desde</label>
                                                    <datepicker style="width: 100% !important;" :format="customFormatter" @closed="salidaDesde" class="form-calendario" v-model="fecha_salida" :bootstrap-styling="true"></datepicker>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-md-2 col-md-push-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Fecha salida hasta</label>
                                                    <datepicker style="width: 100% !important;" :format="customFormatter" class="form-calendario" v-model="fecha_salida_hasta" :bootstrap-styling="true"></datepicker>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-md-2 col-md-pull-4">
                                                <div class="form-group" style="width: 100%;">
                                                    <label for="exampleInputEmail1">Estado</label>
                                                    <select class="form-control" v-model="estado">
                                                        <option value="0 " disabled>Seleccione</option>
                                                        <option value="todas">Todas</option>
                                                        <option v-for="(estado,index) in arrayEstados " :key="index " :value="estado.id " v-text="estado.descripcion "></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-md-2 col-md-pull-2 ">
                                                <div class="form-group " style=" width: 100%; ">
                                                    <label for="exampleInputEmail1 ">Tipo habitación</label>
                                                    <select class="form-control " v-model="tipoHabitacion">
                                                        <option value="0" disabled>Seleccione</option>
                                                        <option value="todas">Todas</option>
                                                        <option v-for="(tipoHabitacion,index) in arrayTiposHabitaciones" :key="index" :value="tipoHabitacion.id" v-text="tipoHabitacion.nombre"></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-4 col-md-2">
                                                <div class="form-group" style="width: 100%;">
                                                    <label for="exampleInputEmail1">Fuente reserva</label>
                                                    <select class="form-control" v-model="fuente" v-on:change="selectHabitacion()">
                                                        <option value="0 " disabled>Seleccione</option>
                                                        <option value="todas">Todas</option>
                                                        <option v-for="(fuente,index) in arrayFuentesReservas " :key="index " :value="fuente.id " v-text="fuente.nombre "></option>
                                                    </select>
                                                </div>

                                            </div>
                                            <div class="container-fluid ">
                                                <div class="row ">
                                                    <div class="col-md-12 ">
                                                        <div class="form-group row ">
                                                            <div class="col-md-9 ">
                                                                <button type="button " class="btn btn-raised gradient-ibiza-sunset white sidebar-shadow " @click="cargarInfo">Filtrar <i class="fa fa-search-plus"
                                                                      aria-hidden="true"></i></button>
                                                                <button type="button " class="btn btn-raised gradient-ibiza-sunset white sidebar-shadow limpiar" @click="limpiarCampos">Limpiar <i class="fas fa-check-circle"></i></button>

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
                    <div class="col-sm-12 ">
                        <div class="card ">
                            <div class="card-header ">
                                <h4 class="content-header " style="font-size: 14px; ">Reservas</h4>
                            </div>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <download-excel class="btn btn-success" :data="json_data" :fields="json_fields" worksheet="My Worksheet" name="filename.xls">
                                                    <i class="fa fa-download"></i>
                                                    Reporte Excel

                                                </download-excel>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body ">
                                <div class="card-body " style="height:auto !important; ">
                                    <div class="card-block ">
                                        <table class="table table-responsive-md-md text-center ">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th style="font-size:12px; ">Reservas</th>
                                                    <th style="font-size:12px; ">Nombres</th>
                                                    <th style="font-size:12px; ">Fecha reserva</th>
                                                    <th style="font-size:12px; ">No habitación</th>
                                                    <th style="font-size:12px; ">Fecha llegada</th>
                                                    <th style="font-size:12px; ">Fecha salida</th>
                                                    <th style="font-size:12px; ">Dias estadia</th>
                                                    <th style="font-size:12px; ">Precio Total</th>
                                                    <th style="font-size:12px; ">Estado</th>
                                                    <th style="font-size:12px; ">Origen</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <tr v-for="detalles in ArrayDetalles " :key="detalles.id ">

                                                    <td style="font-size:12px; ">
                                                        <div class="dropdown ">
                                                            <button class="btn btn-raised mr-1 shadow-z-2 btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                                            </button>
                                                            <div class="dropdown-menu " aria-labelledby="dropdownMenuButton ">
                                                                <a class="dropdown-item" v-on:click="fnmodalDetalle(detalles)">Ver Detalles</a>
                                                                <a class="dropdown-item " v-on:click="Comentarios(detalles)">Comentarios</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td style="font-size:12px; ">{{detalles.id_reservas}}</td>
                                                    <td style="font-size:12px; ">{{detalles.nombre}} {{detalles.apellido}}</td>
                                                    <td style="font-size:12px; ">{{detalles.created_at}}</td>
                                                    <td style="font-size:12px; ">{{detalles.numero}}</td>
                                                    <td style="font-size:12px; ">{{detalles.check_in_fecha}}</td>
                                                    <td style="font-size:12px; ">{{detalles.check_out_fecha}}</td>
                                                    <td style="font-size:12px; ">{{detalles.dias}}</td>
                                                    <td style="font-size:12px; ">{{detalles.precio_total}}</td>
                                                    <td style="font-size:12px; ">
                                                        <template v-if="detalles.estado_reserva_descripcion=='Salidas'">
                                                            <span _ngcontent-ksb-c34="" class="badge badge-success">{{detalles.estado_reserva_descripcion}}</span>
                                                        </template>
                                                        <template v-if="detalles.estado_reserva_descripcion=='LLegadas'">
                                                            <span _ngcontent-ksb-c34="" class="badge badge-danger">{{detalles.estado_reserva_descripcion}}</span>
                                                        </template>
                                                        <template v-if="detalles.estado_reserva_descripcion=='Alojado'">
                                                            <span _ngcontent-ksb-c34="" class="badge badge-info">{{detalles.estado_reserva_descripcion}}</span>
                                                        </template>
                                                        <template v-if="detalles.estado_reserva_descripcion=='Checkout'">
                                                            <span _ngcontent-ksb-c34="" class="badge badge-secondary">{{detalles.estado_reserva_descripcion}}</span>
                                                        </template>


                                                    </td>
                                                    <td style="font-size:12px; ">{{detalles.nombre_fuente}}</td>
                                                </tr>

                                            </tbody>

                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="modal fade text-left" :class="{'mostrar' : modalComentarios}" id="large" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content" style="height: 550px;">
                            <div class="modal-header" style="background-color: white;">
                                <span class="badge badge-info">Comentarios</span>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" v-on:click="cerrarModalComentario()">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <fieldset class="form-group">
                                            <label for="basicTextarea">Escribe tu comentario</label>
                                            <textarea class="form-control" v-model="comentario"></textarea>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-raised gradient-crystal-clear white shadow-big-navbar" data-dismiss="modal" v-on:click="GuardarComentario(id_grupos)">Guardar</button>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title">Mis Comentarios</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="card-block">
                                                    <table class="table">
                                                        <thead class="thead-default">
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Comentario Reserva</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr v-for="comentarios in ArrayComentarios" :key="comentarios.id">
                                                                <th v-text="comentarios.id">1</th>
                                                                <th v-text="comentarios.comentario">1</th>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade text-left" :class="{'mostrar' : modalPrepagos}" id="large" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content" style="height: 550px;">
                            <div class="modal-header" style="background-color: white;">
                                <span class="badge badge-info">Importar Reservas</span>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" v-on:click="cerrarPrepagos()">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form>
                                            <div class="form-group-row">
                                                <h3>Subir Excel</h3>
                                            </div>

                                            <div class="form-group-row">
                                                <div class="col-sm-10">
                                                    <input type="file" id="file" ref="file" v-on:change="handleFileUpload()" accept=".XLSX, .CSV" class="form-control">
                                                </div>
                                            </div>

                                            <br>

                                            <button v-on:click="EventSubir()" class="btn btn-primary">Subir</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </section>
        </div>
        <div v-if="mostrar_checkin">
            <checkingin :data="data_checking"></checkingin>
        </div>
        <div v-if="modalDetalle">
            <factura :id_reserva="id_reserva_seleccionada" :id_habitacion="id_habitacion_seleccionada" :data_reserva="args"></factura>
        </div>
    </main>
</template>
<script>
    import Vue from 'vue';
    import excel from 'vue-excel-export'
    import VueSweetalert2 from 'vue-sweetalert2';
    import swal from 'vue-sweetalert2';
    import Datepicker from 'vuejs-datepicker';
    import checkingin from '../extras/cheking';
    import JsonExcel from 'vue-json-excel'
    import factura from '../extras/facturacionFiltrada';
    Vue.component('downloadExcel', JsonExcel)
    Vue.use(require('vue-moment'));
    var moment = require('moment'); // in my gulp file

    import {
        es
    }
    from 'vuejs-datepicker/dist/locale'
    export default {
        data() {
            return {

                json_fields: {
                    'Reserva': 'id_reservas',
                    'Nombres': 'nombre',
                    'Apellidos': 'apellido',
                    'Fecha Reserva': 'created_at',
                    'Habitacion': 'numero',
                    'Fecha LLegada': 'check_in_fecha',
                    'Fecha Salida': 'check_out_fecha',
                    'Dias Estadia': 'dias',
                    'Precio Neto': 'precio_neto',
                    'Estado': 'estado_reserva_descripcion',
                    'Origen': 'nombre_fuente',
                },
                json_data: [

                ],
                json_meta: [
                    [{
                        'key': 'charset',
                        'value': 'utf-8'
                    }]
                ],
                file: '',
                no_reserva: '',
                fecha_reserva: '',
                fecha_reserva_hasta: '',
                fecha_llegada: '',
                fecha_llegada_hasta: '',
                fecha_salida: '',
                fecha_salida_hasta: '',
                comentario: '',
                ArrayComentarios: [],
                grupos_id: '',
                modalComentarios: 0,
                mostrar_pagadores: false,
                mostrar_facturacion: false,
                id_reserva_seleccionada: '',
                id_habitacion_seleccionada: '',
                mostrar_reservas: true,
                email: '',
                regalo: '',
                cliente: '',
                direccion: '',
                zipcode: '',
                ciudad: '',
                pais: '',
                telefono: '',
                email: '',
                language: '',
                llegada_hora: '',
                llegada_fecha: '',
                salida: '',
                noches: '',
                habitacion_tipo: '',
                habitacion_personas: '',
                habitacion_precio: '',
                booking_number: '',
                cliente_pagina: '',
                llegada_fecha_dia: '',
                salida_dia: '',
                numero: '',
                nombre_tipo: '',
                cantidad_huespedes: '',
                nombre_fuente: '',
                tituloModal: '',
                modalPrepagos: 0,
                modalDetalle: 0,
                mostrar_checkin: false,
                tipoCarga: 0,
                fecha_inicio: '',
                estado: '',
                tipoHabitacion: '',
                fuente: '',
                es: es,
                base: '',
                data_checking: '',
                cadenaFechas: '',
                arrayEstados: [],
                ArrayDetalles: [],
                arrayTiposHabitaciones: [],
                arrayFuentesReservas: [],
                selected: 0,
                precio_neto_total: 0,
                precio_bruto_total: 0,
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
                criterio: 'reserva',
                buscar: '',
                image: '',
                impuesto_id: '',
                fecha_filtro: new Date(),
            }
        },
        computed: {
            count() {
                this.$store.state;
                console.log(this.$store.state);
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
        components: {
            Datepicker,
            checkingin,
            factura
        },
        methods: {
            handleSubmit(e) {},
            convert(str) {
                var date = new Date(str),
                    mnth = ("0 " + (date.getMonth() + 1)).slice(-2),
                    day = ("0 " + date.getDate()).slice(-2);
                return [date.getFullYear(), mnth, day].join("- ");
            },

            customFormatter(date) {
                return moment(date).format('YYYY-MM-DD');
            },

            EventSubir() {
                let formData = new FormData();
                formData.append('file', this.file);
                axios
                    .post('/import-excel-reservas',
                        formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                    ).then(function() {
                        console.log('SUCCESS!!');
                    })
                    .catch(function() {
                        console.log('FAILURE!!');
                    });
            },
            handleFileUpload() {
                this.file = this.$refs.file.files[0];
            },
            limpiarCampos()
            {
                this.fecha_reserva = '';
                this.fecha_reserva_hasta = '';
                this.fecha_llegada = '';
                this.fecha_llegada_hasta = '';
                this.fecha_salida = '';
                this.fecha_salida_hasta = '';
                this.estado = '';
                this.tipoHabitacion = '';
                this.fuente = '';
                this.ArrayDetalles = [];    
            },
            checkOut(args) {
                let me = this;
                Vue.swal({
                    title: "Realizar CheckOut?",
                    text: "Desea relizar el CheckOut de esta habitacion!",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Si!',
                    cancelButtonText: "No, cancela!"
                }).then(
                    function() {

                        axios.post(me.base + "/reservas/checkOut", {
                                grupo_id: args.id_grupo,
                                check_out_fecha: args.check_out_fecha,
                                habitacion: args.resource

                            })
                            .then(function(response) {
                                Vue.swal("Muy bien!", "Realizo CheckOut a esta reserva!", "success");
                                me.$router.go(me.$router.currentRoute)
                            })
                            .catch(function(error, otracosa) {});
                    },
                    function() {
                        Vue.swal("Muy bien!", "Realizo CheckOut a esta reserva!", "success");
                    });
            },

            modalPrepago() {
                let me = this;
                me.modalPrepagos = 1;

            },

            cerrarPrepagos() {
                let me = this;
                me.modalPrepagos = 0;
            },

            Comentarios(args) {
                let me = this;
                me.modalComentarios = 1;
                me.grupos_id = args.id_grupo;
                me.listarComentarios(me.grupos_id);

            },

            AnularReserva(data) {

                let me = this;
                me.$swal({
                    title: 'Desea anular esta reserva?',
                    text: 'Anular Reserva',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si Anular!',
                    cancelButtonText: 'No, Cancelar!',
                    showCloseButton: true,
                    showLoaderOnConfirm: true
                }).then((result) => {
                    if (result.value) {
                        axios
                            .post(me.base + "/reservas/anular_reserva", {
                                data: data,
                            })
                            .then(function(response) {
                                me.$swal('Anulado', 'Esta reserva fue anulada', 'success')
                                me.iniciar();

                            })
                            .catch(function(error, otracosa) {});

                    } else {
                        me.$swal('Canelado', 'Cancelaste esta operación', 'info')
                    }
                })

            },

            GuardarComentario(id_grupos) {
                let me = this;
                axios.post(me.base + "/reservas/comentario", {
                        grupo_id: me.grupos_id,
                        comentario: me.comentario,
                    })
                    .then(function(response) {
                        Vue.swal("Guardado el comentario con éxito");
                        me.listarComentarios(me.grupos_id);
                    })
                    .catch(function(error, otracosa) {});
            },
            listarComentarios(grupos_id) {
                let me = this;
                var url = '/reservas/listar_comentarios?grupo_id=' + grupos_id;
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data.comentarios;
                        me.ArrayComentarios = respuesta;

                    })
                    .catch(function(error, otracosa) {
                        // var response = error.response.data;
                    });
            },


            Facturacion(data) {
                let me = this;
                me.args = data;
                console.log(data);
                me.id_reserva_seleccionada = data.id_reservas;
                me.id_habitacion_seleccionada = data.resource;
                me.mostrar_reservas = false;
                me.mostrar_facturacion = true;
                me.mostrar_pagadores = false;

            },
            CheckIn(data) {
                let me = this;
                me.data_checking = data;

                me.mostrar_reservas = false;
                me.mostrar_checkin = true;
            },

            cerrarModal() {
                this.modalDetalle = 0;
            },

            cerrarModalComentario() {
                this.modalComentarios = 0;
            },

            fnmodalDetalle(data) {
                let me = this;
                console.log(data);
                me.args = data;
                me.id_reserva_seleccionada = data.id_reservas;
                me.id_habitacion_seleccionada = data.resource;
                me.modalDetalle = true;
                me.mostrar_reservas = false;
                me.mostrar_checkin = false;

            },


            ListarItems(page, buscar, criterio) {
                let me = this;
                var url = '/reservas/obtener_reservas_realizadas?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios
                    .get(me.base + url)
                    .then(function(response) {
                        var respuesta = response.data;
                        me.ArrayDetalles = respuesta.datos.data;
                        me.pagination = respuesta.pagination;
                        me.json_data = respuesta.datos.data;
                        console.log(me.json_data);
                        console.log(me.ArrayDetalles);
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
                me.ListarItems(page, buscar, criterio);
            },

             sumarDias(fecha, dias){
                fecha.setDate(fecha.getDate() + dias);
                return fecha;
            },
   
            datepickerCerradoReserva: function() {
               	var fecha = new Date(this.convert(this.fecha_reserva));
				var dias = 2; // Número de días a agregar
				fecha.setDate(fecha.getDate() + dias);
				this.fecha_reserva_hasta = fecha;
            },
            llegadaDesde: function() {
               	var fecha_l = new Date(this.convert(this.fecha_llegada));
				var dias = 2; // Número de días a agregar
				fecha_l.setDate(fecha_l.getDate() + dias);
				this.fecha_llegada_hasta = fecha_l;
            },
            salidaDesde: function() {
               	var fecha_s = new Date(this.convert(this.fecha_salida));
				var dias = 2; // Número de días a agregar
				fecha_s.setDate(fecha_s.getDate() + dias);
				this.fecha_salida_hasta = fecha_s;
            },
            cargarInfo() {
                let me = this;
                axios.get('/listarall_reservas', {
                    params: {
                        fecha_reserva: me.convert(me.fecha_reserva),
                        fecha_reserva_hasta: me.convert(me.fecha_reserva_hasta),
                        fecha_llegada: me.convert(me.fecha_llegada),
                        fecha_llegada_hasta: me.convert(me.fecha_llegada_hasta),
                        fecha_salida: me.convert(me.fecha_salida),
                        fecha_salida_hasta: me.convert(me.fecha_salida_hasta),
                        estado: me.estado,
                        tipoHabitacion: me.tipoHabitacion,
                        fuente: me.fuente,
                        no_reserva: me.no_reserva

                    }
                }).then(function(response) {
                    var respuesta = response.data;
                    console.log(respuesta);
                    me.ArrayDetalles = respuesta.datos.data;
                    me.json_data = respuesta.datos.data;
                    me.pagination = respuesta.pagination;
                })

            },


            convert(str) {
                var date = new Date(str),
                    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
                    day = ("0" + date.getDate()).slice(-2);
                return [date.getFullYear(), mnth, day].join("-");
            },


            obtenerEstados() {
                let me = this;
                var url = '/obtener_estados';
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data;
                        me.arrayEstados = respuesta.datos;

                    })
                    .catch(function(error) {
                        var response = error.response.data;
                        console.log(response.message,
                            response.exception,
                            response.file,
                            response.line);
                    });
            },

            obtenerTiposHabitacion() {
                let me = this;
                var url = '/obtener_tipos_habitacion';
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data;
                        me.arrayTiposHabitaciones = respuesta.datos;

                    })
                    .catch(function(error) {
                        var response = error.response.data;
                        console.log(response.message,
                            response.exception,
                            response.file,
                            response.line);
                    });
            },

            obtenerFuentesReservas() {
                let me = this;
                var url = '/obtener_fuentes_reservas';
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data;
                        me.arrayFuentesReservas = respuesta.datos;

                    })
                    .catch(function(error) {
                        var response = error.response.data;
                        console.log(response.message,
                            response.exception,
                            response.file,
                            response.line);
                    });
            },
        },
        mounted() {
            this.base = base;
            this.ListarItems(1, this.buscar, this.criterio);
            this.obtenerEstados();
            this.obtenerTiposHabitacion();
            this.obtenerFuentesReservas();
            $("#btnExport ").click(function(e) {
                window.open('data:application/vnd.ms-excel,' +
                    '<table>' + $('#dvData > table').html() + '</table>');
                e.preventDefault();
            });
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
        content: " ";
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
    .limpiar {
        background-image: linear-gradient(
        45deg, #09ee25, #859059);
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

    .card .card-body {
        padding: 0 !important;
        height: auto !important;
    }

    .form-calendario {
        width: 116% !important;
    }
</style>
