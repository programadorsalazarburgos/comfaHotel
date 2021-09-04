<template>
    <main class="main">

        <div class="col-12">
            <div class="content-header">Reporte Diario Reservas</div>
        </div>
        <br>

        <div v-if="mostrar_reservas">
            <section id="extended">
                <div class="row">
                    <div v-if="tipoCarga==1">
                        <div class="loader loader-default is-active" data-text></div>
                    </div>
                    <div class="col-sm-12 ">
                        <div class="card ">
                            <div class="card-header ">
                                <button type="button" class="btn btn-info round mr-1 mb-1" @click="diario()">Diario <i class="fa fa-newspaper-o"></i></button>
                                <button type="button" class="btn btn-warning round mr-1 mb-1" @click="mensual()">Mensual <i class="fa fa-newspaper-o"></i></button>
                                <template v-if="mostrar_reporte==1">
                                <h4 class="content-header " style="font-size: 14px; "><span class="badge badge-danger mb-1 mr-2">Reporte Diario</span></h4>
                                <div class="col-sm-4 col-md-2">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Fecha Reporte</label>
                                    <datepicker @opened="datepickerAbierto" @selected="fechaSeleccionada" @closed="datepickerCerrado" style="width: 100% !important;" :format="customFormatter" class="form-calendario" v-model="fecha_filtro" :bootstrap-styling="true"></datepicker>
                                </div>
                               </div>
                                <div class="clearfix"></div><br>
                            <div class="card-body">
                                <span class="badge badge-success mb-1 mr-2">Total Diario x Habitaciones: ${{total_diario}}</span>
                            </div>
                            <div class="card-body ">
                                <div class="card-body " style="height:auto !important; ">
                                    <div class="card-block ">
                                        <table class="table table-responsive-md-md text-center ">
                                            <thead>
                                                <tr>
                                                    <th style="font-size:12px; ">Fecha</th>
                                                    <th style="font-size:12px; ">Tipo Habitación</th>
                                                    <th style="font-size:12px; ">Habitación</th>
                                                    <th style="font-size:12px; ">Cantidad adultos</th>
                                                    <th style="font-size:12px; ">Cantidad Niños</th>
                                                    <th style="font-size:12px; ">Total Diario</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <tr v-for="detalles in ArrayDetalles " :key="detalles.id ">

                                                    <td style="font-size:12px; ">{{detalles.check_in_fecha}}</td>
                                                    <td style="font-size:12px; ">{{detalles.tipo_habitacion}}</td>
                                                    <td style="font-size:12px; ">{{detalles.numero}}</td>
                                                    <td style="font-size:12px; ">{{detalles.adultos_cantidad}}</td>
                                                    <td style="font-size:12px; ">{{detalles.ninos_cantidad}}</td>
                                                    <td style="font-size:12px; ">{{detalles.total_diario}}</td>
                                                </tr>

                                            </tbody>

                                        </table>

                                    </div>
                                </div>
                            </div>
                            </template>
                            <template v-if="mostrar_reporte==2">
                                <h4 class="content-header " style="font-size: 14px; "><span class="badge badge-danger mb-1 mr-2">Reporte Mes</span></h4>
                                <div class="col-md-8">
                                <div class="form-group">
                                <label for="exampleInputEmail1">Mes Reporte</label>
                                <select class="form-control col-md-3" v-model="mes" v-on:change="selectMes()">
									<option value="01">Enero</option>
									<option value="02">Febrero</option>
									<option value="03">Marzo</option>
									<option value="04">Abril</option>
									<option value="05">Mayo</option>
									<option value="06">Junio</option>
									<option value="07">Julio</option>
									<option value="08">Agosto</option>
									<option value="09">Septiembre</option>
									<option value="10">Octubre</option>
									<option value="11">Noviembre</option>
									<option value="12">Diciembre</option>
								</select>
                                </div>
                               </div>
                                <div class="clearfix"></div><br>
                            <div class="card-body">
                                <span class="badge badge-success mb-1 mr-2">Total Mensual x Habitaciones: ${{dataMensual}}</span>
                            </div>
                            <div class="card-body ">
                                <div class="card-body " style="height:auto !important; ">
                                    <div class="card-block ">
                                        <table class="table table-responsive-md-md text-center ">
                                            <thead>
                                                <tr>
                                                    <th style="font-size:12px; ">Fecha</th>
                                                    <th style="font-size:12px; ">Tipo Habitación</th>
                                                    <th style="font-size:12px; ">Habitación</th>
                                                    <th style="font-size:12px; ">Cantidad adultos</th>
                                                    <th style="font-size:12px; ">Cantidad Niños</th>
                                                    <th style="font-size:12px; ">Total Diario</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <tr v-for="data in ArrayMensual " :key="data.id ">

                                                    <td style="font-size:12px; ">{{data.check_in_fecha}}</td>
                                                    <td style="font-size:12px; ">{{data.tipo_habitacion}}</td>
                                                    <td style="font-size:12px; ">{{data.numero}}</td>
                                                    <td style="font-size:12px; ">{{data.adultos_cantidad}}</td>
                                                    <td style="font-size:12px; ">{{data.ninos_cantidad}}</td>
                                                    <td style="font-size:12px; ">{{data.total_diario}}</td>
                                                </tr>

                                            </tbody>

                                        </table>

                                    </div>
                                </div>
                            </div>

                            </template>
                            </div>
                        </div>

                    </div>

                </div>
<div class="row"></div>


            </section>
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
    import factura from '../extras/facturacion';
    import JsonExcel from 'vue-json-excel'
    Vue.component('downloadExcel', JsonExcel)
    Vue.use(require('vue-moment'));
    var moment = require('moment'); // in my gulp file
    import LineChart from './LineChart.js'
      import "chart.js";
    import "hchs-vue-charts";
    Vue.use(window.VueCharts);


    import {
        es
    }
    from 'vuejs-datepicker/dist/locale'
    export default {
        data() {
            return {
                datacollection: null,
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
                mostrar_reporte: 1,
                ArrayMensual: [],
                file: '',
                no_reserva: '',
                fecha_reserva: '',
                fecha_llegada: '',
                fecha_salida: '',
                dataMensual: '',
                comentario: '',
                mes: new Date().getMonth() + 1,
                ocupadas_diaria: '',
                ocupacion_porcentaje: '',
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
                total_diario: '',
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
            factura,
            LineChart
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
            fillData ()
    {
      this.datacollection = {
        labels: ['Lunes','Martes','Miercoles','Jueves','Viernes', 'Sabado' , 'Domingo'],
        datasets: [
          {
            label: 'Ventas',
            backgroundColor: '#FF0066',
            data: [ 20, 40, 50, 20, 50, 40]
          },
        ]
      }
    },
            selectMes()
            {
                this.ListarItemsMensual(1, this.buscar, this.criterio,  this.mes);
            },
            mensual()
            {

                this.mostrar_reporte = 2;
                var f=new Date();
                this.ListarItemsMensual(1, this.buscar, this.criterio,  f.getMonth() + 1);
                this.ArrayDetalles = [];
                this.fecha_filtro = '';
            },
            diario()
            {
                this.mostrar_reporte = 1;
                this.fecha_filtro = new Date();
                this.ListarItems(1, this.buscar, this.criterio,  this.customFormatter(new Date()));
                this.ArrayMensual = [];
            },
            handleFileUpload() {
                this.file = this.$refs.file.files[0];
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
			convert(str) {
				var date = new Date(str),
					mnth = ("0" + (date.getMonth() + 1)).slice(-2),
					day = ("0" + date.getDate()).slice(-2);
				return [date.getFullYear(), mnth, day].join("-");
			},
            datepickerAbierto: function() {
                },
                fechaSeleccionada: function() {
                },
                datepickerCerrado: function() {
                    this.ListarItems(1, this.buscar, this.criterio, this.customFormatter(this.fecha_filtro));
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
                me.modalDetalle = 1;
                me.email = data.email;
                me.regalo = data.regalo;
                me.cliente = data.nombre + ' ' + data.apellido;
                me.direccion = data.direccion;
                me.zipcode = data.zipcode;
                me.ciudad = data.ciudad;
                me.pais = data.pais;
                me.telefono = data.telefono;
                me.email = data.email;
                me.language = data.language;
                me.llegada_hora = data.llegada_hora;
                me.llegada_fecha = data.llegada_fecha;
                me.salida = data.salida;
                me.noches = data.noches;
                me.habitacion_tipo = data.habitacion_tipo;
                me.habitacion_personas = data.habitacion_personas;
                me.habitacion_precio = data.habitacion_precio;
                me.booking_number = data.booking_number;
                me.cliente_pagina = data.cliente_pagina;
                me.llegada_fecha_dia = data.check_in_fecha;
                me.salida_dia = data.check_out_fecha;
                me.numero = data.numero;
                me.nombre_tipo = data.nombre_tipo;
                me.cantidad_huespedes = data.cantidad_huespedes;
                me.nombre_fuente = data.nombre_fuente;

            },

            ListarItemsMensual(page, buscar, criterio, mes) {
                let me = this;
                var url = '/reportes/reservas_mes?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio + '&mes=' + mes;
                axios
                    .get(me.base + url)
                    .then(function(response) {
                        var respuesta = response.data;
                        me.ArrayMensual = respuesta.datos;
                        me.pagination = respuesta.pagination;
                        me.dataMensual = respuesta.data_suma_habitaciones;
                    })
                    .catch(function(error) {
                        var response = error.response.data;
                        console.log(response.message, response.exception, response.file, response.line);
                    });
            },


            ListarItems(page, buscar, criterio, fecha_filtro) {
                let me = this;
                var url = '/reportes/reservas?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio + '&fecha_filtro=' + fecha_filtro;
                axios
                    .get(me.base + url)
                    .then(function(response) {
                        var respuesta = response.data;
                        me.ArrayDetalles = respuesta.datos;
                        me.pagination = respuesta.pagination;
                        me.json_data = respuesta.datos.data;
                        me.total_diario = respuesta.data_suma_habitaciones;
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



            cargarInfo() {
                let me = this;
                axios.get('/listarall_reservas', {
                    params: {
                        fecha_reserva: me.convert(me.fecha_reserva),
                        fecha_llegada: me.convert(me.fecha_llegada),
                        fecha_salida: me.convert(me.fecha_salida),
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
            this.fillData();
            this.ListarItems(1, this.buscar, this.criterio,  this.customFormatter(new Date()));
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
    .btn-warning {
    color: #212529 !important;
    background-color: #F77E17 !important;
    border-color: #F77E17 !important;
    color: #FFF !important;
}
.small {
  max-width: 1000px;
  /* max-height: 500px; */
  margin:  50px auto;
}
</style>
