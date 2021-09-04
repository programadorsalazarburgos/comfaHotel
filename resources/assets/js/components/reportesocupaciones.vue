<template>
    <main class="main">

        <div class="col-12">
            <div class="content-header">Reporte Ocupaciones</div>
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
                                <h4 class="content-header " style="font-size: 14px; "><span class="badge badge-danger mb-1 mr-2">Reporte Ocupación</span></h4>
                                <div class="col-sm-4 col-md-2">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Fecha Reporte</label>
                                    <datepicker @opened="datepickerAbierto" @selected="fechaSeleccionada" @closed="datepickerCerrado" style="width: 100% !important;" :format="customFormatter" class="form-calendario" v-model="fecha_filtro" :bootstrap-styling="true"></datepicker>
                                </div>
                               </div>
                                <div class="clearfix"></div><br>
                                <div class="card-body">
                                <span class="badge badge-success mb-1 mr-2">Ocupacion Habitaciones: {{ocupadas_diaria}}</span>
                                <span class="badge badge-info mb-1 mr-2">Porcentaje Ocupación: {{Math.round(ocupacion_porcentaje)}} %</span>
                            </div>
                            </div>

                            <div class="card-body ">
                                <div class="card-body " style="height:auto !important; ">
                                    <div class="card-block ">
                                        <table class="table table-responsive-md-md text-center ">
                                            <thead>
                                                <tr>
                                                    <th style="font-size:12px; ">Fecha</th>
                                                    <th style="font-size:12px; ">Tipo Habitación</th>
                                                    <th style="font-size:12px; ">Cantidad Habitaciones</th>
                                                    <th style="font-size:12px; ">Disponibilidad</th>
                                                    <th style="font-size:12px; ">Ocupación x Tipo Habitación</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <tr v-for="detalles in ArrayDetalles " :key="detalles.id ">

                                                    <td style="font-size:12px; ">{{detalles.start}}</td>
                                                    <td style="font-size:12px; ">{{detalles.tipo_habitacion}}</td>
                                                    <td style="font-size:12px; ">{{detalles.cantidadTipoHabitaciones}}</td>
                                                    <td style="font-size:12px; ">{{detalles.disponibilidad}} </td>
                                                    <td style="font-size:12px; ">{{detalles.ocupacion}} </td>
                                                </tr>

                                            </tbody>

                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalDetalle}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                    <div class="modal-dialog modal-primary modal-lg" role="document">
                        <div class="modal-content" style="height: 500px;">
                            <div class="modal-header">
                                <h4 class="modal-title" v-text="tituloModal"></h4>
                                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div id="detalles_habitacion">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="card-block">
                                                        <h5><i class="fas fa-align-justify danger"></i> DETALLES DE LA RESERVA</h5>
                                                        <span class="display-block overflow-hidden">{{nombre_fuente}}</span>
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-12 col-md-6 col-lg-4">
                                                                <ul class="no-list-style">
                                                                    <li class="mb-2">
                                                                        <span class="text-bold-500 primary"><a><i class="icon-present font-small-3"></i> Fecha Llegada:</a></span>
                                                                        <span class="display-block overflow-hidden"><span class="badge badge-danger">{{llegada_fecha_dia}}</span> {{llegada_fecha}}</span>
                                                                    </li>
                                                                    <li class="mb-2">
                                                                        <span class="text-bold-500 primary"><a><i class="ft-map-pin font-small-3"></i>Tipo Habitación:</a></span>
                                                                        <span class="display-block overflow-hidden">{{nombre_tipo}}</span>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="col-12 col-md-6 col-lg-4">
                                                                <ul class="no-list-style">
                                                                    <li class="mb-2">
                                                                        <span class="text-bold-500 primary"><a><i class="ft-user font-small-3"></i> Fecha Salida:</a></span>
                                                                        <span class="display-block overflow-hidden"><span class="badge badge-danger">{{salida_dia}}</span> {{salida}}
                                                                        </span>
                                                                    </li>
                                                                    <li class="mb-2">
                                                                        <span class="text-bold-500 primary"><a><i class="ft-mail font-small-3"></i> Habitacion:</a></span>
                                                                        <a class="display-block overflow-hidden">{{numero}}</a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="col-12 col-md-6 col-lg-4">
                                                                <ul class="no-list-style">
                                                                    <li class="mb-2">
                                                                        <span class="text-bold-500 primary"><a><i class="ft-smartphone font-small-3"></i> Huesped:</a></span>
                                                                        <span class="display-block overflow-hidden">{{cliente}}</span>
                                                                    </li>

                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" v-on:click="cerrarModal()">Cerrar</button>
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


 <div class="col-sm-12 ">
     <div class="card ">
         <div class="card-header ">


<div class="small">
  <div class="card">
    <div class="card-body">


 <div class="card">
    <div class="card-body">
      <h2 class="card-title">Bar</h2>
    </div>

    <div class="card-img-bottom">
      <canvas id="fooCanvas" count="2" />

      <chartjs-bar
        v-for="(item, index) in types"
        :key="index"
        :backgroundcolor="item.bgColor"
        :beginzero="beginZero"
        :bind="true"
        :bordercolor="item.borderColor"
        :data="item.data"
        :datalabel="item.dataLabel"
        :labels="labels"
        target="fooCanvas"
      />
    </div>
  </div>


    </div>



     <div class="card">
    <div class="card-body">
      <h2 class="card-title">Doughnut</h2>
    </div>

    <div class="card-img-bottom">
      <chartjs-doughnut
        :bind="true"
        :datasets="datasets"
        :labels="labels"
        :option="option"
      />
    </div>
  </div>
  </div>
  </div>


  </div>
  </div>
  </div>



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
                fecha_llegada: '',
                fecha_salida: '',
                comentario: '',
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
                datacollection: null,
                bgColor: "#e83e8c",
                beginZero: true,
                borderColor: "#e83e8c",
                data: {
                    day: [2, 3, 2, 3, 2],
                    week: [12, 14, 16, 18, 11, 13, 15]
                },
                dataLabel: "Ocupaciones",
/*                 labels: {
                    day: ["Double romm", "Standar Double", "Quadroople romm", "Standar King", "Deluxe room"],
                },
 */                radio: "day",
                datasets: [
                        {
                        data: [],
                        backgroundColor: ["#f36e60", "#ffdb3b", "#185190"],
                        hoverBackgroundColor: ["#fbd2cd", "#fef5c9", "#d1e3f7"]
                        }
                    ],
                    labels: [],
                 option: {},
                 beginZero: true,
                 types: [
        {
          bgColor: '#'+(Math.random()*0xFFFFFF<<0).toString(16),
          borderColor: "0c0306",
          data: [],
          dataLabel: "Bar"
        },
      ]

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


            ListarItems(page, buscar, criterio, fecha_filtro) {
                let me = this;
                me.labels = [];
                me.datasets[0].data = [];
                var url = '/inventarios/ocupaciones?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio + '&fecha_filtro=' + fecha_filtro;
                axios
                    .get(me.base + url)
                    .then(function(response) {
                        var respuesta = response.data;
                        me.ArrayDetalles = respuesta.datos;
                        me.pagination = respuesta.pagination;
                        me.json_data = respuesta.datos.data;
                        me.ocupadas_diaria = respuesta.ocupadas_diaria;
                        me.ocupacion_porcentaje =  respuesta.ocupacion_porcentaje;
                        $.each(respuesta.datos, function(key, reservacion) {
                            me.labels.push(reservacion.tipo_habitacion);
                            me.datasets[0].data.push(reservacion.ocupacion);
                            me.types[0].data.push(reservacion.ocupacion);
                        });

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
            console.log(this.labels, 2);
            this.ListarItems(1, this.buscar, this.criterio, this.customFormatter(new Date()));
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
</style>
