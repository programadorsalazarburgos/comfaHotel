<template>
<main class="main" >
  <div class="col-12">
    <div class="content-header">Adelantos Prepagos</div>
  </div>
		<section id="extendeds">

      <div class="row">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-header">
              <h4 class="card-title"></h4>
              <h4 class="card-title">
                 <button class="btn btn-danger" v-on:click="recargar()">
                 <i class="ft-chevrons-left" ></i>
                 </button>
              </h4>
              <label for="basicTextarea"><strong><span class="badge badge-primary">Filtro Prepago</span></strong></label>
              <datepicker :format="customFormatter" v-model="filtro_calendario" :bootstrap-styling="true"  @opened="datepickerAbierto"
        @selected="fechaSeleccionada"
        @closed="datepickerCerrado"></datepicker>
        <button type="button" class="btn btn-raised gradient-mint white shadow-z-4" v-on:click="filtrarPrepagos()" name="button" style="
        position: absolute;
        left: 251px;
        top: 119px;
    "><i class="fab fa-searchengin"></i></button>


            </div>
            <div class="container-fluid">
              <div class="row">
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-6">
                      <download-excel
                    class   = "btn btn-success"

                    :data   = "json_data"
                    :fields = "json_fields"
                    worksheet = "My Worksheet"
                    name    = "filename.xls">
                    <i class="fa fa-download"></i>
                    Reporte Excel

                    </download-excel>
                    </div>
                    <!-- <div class="col-md-6" style="position: absolute; left: 152px;">
                      <button class="btn btn-raised mr-1 shadow-z-2 btn-primary white shadow-z-4" v-on:click="modalPrepago()">Importar  <i class="fas fa-book-medical" aria-hidden="true"></i></button>
                    </div> -->
                  </div>
                </div>
              </div>
            </div>

            <div class="card-body">
              <div class="card-body">
                    <div class="card-block">
                        <table class="table table-responsive-md-md text-center">
                            <thead>
                                <tr>

                                    <th>No Reserva</th>
                                    <th>Habitaciones</th>
                                    <th>Tipo Pago</th>
                                    <th>Valor Pagado</th>
                                    <th>Fecha Prepago</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="impuestos in ArrayPrepagos" :key="impuestos.id">
                                    <td v-text="impuestos.id"></td>
                                    <td v-text="impuestos.habitaciones"></td>
                                    <td v-text="impuestos.nombre"></td>
                                    <td v-text="impuestos.valor_pagado"></td>
                                    <td v-text="impuestos.created_at"></td>
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

		</section>
	</main>

</template>
<script>
import VueSweetalert2 from 'vue-sweetalert2';
import VueSweetAlert from 'vue-sweetalert';
import Autocomplete from 'vuejs-auto-complete';
import Vue from 'vue';
import Datepicker from 'vuejs-datepicker';
import excel from 'vue-excel-export'
import JsonExcel from 'vue-json-excel'
Vue.component('downloadExcel', JsonExcel)

Vue.use(require('vue-moment'));
var moment = require('moment');// in my gulp file

export default {
    data()
    {
        return {
            base:'',
            json_fields: {
                       'Reserva': 'reserva_id',
                       'Habitaciones': 'habitaciones',
                       'Tipo de Pago': 'nombre',
                       'Valor Pagado': 'valor_pagado',
                       'Fecha Prepago': 'created_at',
                   },
                   json_data: [

                   ],
                   json_meta: [
                       [
                           {
                               'key': 'charset',
                               'value': 'utf-8'
                           }
                       ]
                   ],

            ArrayPrepagos:[],
            pagination : {
                        'total' : 0,
                        'current_page' : 0,
                        'per_page' : 0,
                        'last_page' : 0,
                        'from' : 0,
                        'to' : 0,
                    },
              offset : 3,
              criterio : 'nombre',
              buscar : '',
              filtro_calendario : '',


        }
    },
    props:['data'],
    components: {
        Autocomplete,
        Datepicker

	},
  computed:{
         isActived: function(){
             return this.pagination.current_page;
         },
         //Calcula los elementos de la paginación
         pagesNumber: function() {
             if(!this.pagination.to) {
                 return [];
             }

             var from = this.pagination.current_page - this.offset;
             if(from < 1) {
                 from = 1;
             }

             var to = from + (this.offset * 2);
             if(to >= this.pagination.last_page){
                 to = this.pagination.last_page;
             }

             var pagesArray = [];
             while(from <= to) {
                 pagesArray.push(from);
                 from++;
             }
             return pagesArray;

         }
     },

    methods:{


    	datos()
    	{
    		let me = this;

    	},
      recargar() {
          let me = this;
          me.$router.go(me.$router.currentRoute)
      },

      customFormatter(date) {
          return moment(date).format('YYYY-MM-DD');
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


      },

      filtrarPrepagos(page)
      {
        let me = this;
        var url= me.base+'/prepagos/listar?page=' + page + '&fecha=' + me.convert(me.filtro_calendario);
        axios.get(url).then(function (response) {
            var respuesta= response.data;
            console.log(respuesta)
            me.ArrayPrepagos = respuesta.datos.data;
            me.json_data = respuesta.datos.data;
            me.pagination= respuesta.pagination;
        })
        .catch(function (error) {
            var response = error.response.data;
            console.log(response.message,
                response.exception,
                response.file,
                response.line);
        });
      },

      convert(str) {
      var date = new Date(str),
        mnth = ("0" + (date.getMonth() + 1)).slice(-2),
        day = ("0" + date.getDate()).slice(-2);
      return [date.getFullYear(), mnth, day].join("-");
      },

      ListarDatos (page){
                let me=this;
                var url= me.base+'/prepagos/index?page=' + page;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    console.log(respuesta)
                    me.ArrayPrepagos = respuesta.datos.data;
                    console.log(me.ArrayPrepagos);
                    me.json_data = respuesta.datos.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error) {
                    var response = error.response.data;
                    console.log(response.message,
                        response.exception,
                        response.file,
                        response.line);
                });
            },

    },
    mounted()
	{
	   this.base = base;
     this.ListarDatos();


	}
}
</script>
<style>
	#gantt_container{
	width: 100%;
	overflow-y: auto;
	overflow-x: hidden;
	}
	.asd{
	width: 30px;
	height: 30px;
	}
	.asd2{
	background-color:#ff6347;
	}
	.asd1{
	background-color:#65cc00;
	}
	.main{
	width: 100%;
	}
	.bold{
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
	font-size: 11px;}
	.datra {
	display: inline-table;
	height: 16px;
	width: 16px;
	margin: 3px;
	}
	* .wd{
	box-sizing: content-box;
	}
	.modal-content{
	width: 100% !important;
	position: absolute !important;
	}
	.mostrar{
	display: list-item !important;
	opacity: 1 !important;
	position: absolute !important;
	background-color: #3c29297a !important;
	}
	.div-error{
	display: flex;
	justify-content: center;
	}
	.text-error{
	color: red !important;
	font-weight: bold;
	}
	#scroll {
	overflow-y: scroll;
	height: 560px;
	}
	.modal-body{
	overflow-y: scroll;
	height: 240px;
	}
	.c100.small{
	font-size:35px;
	}
	.list-group-item{
	padding:0px;
	}
	.reservaciones-title{
	padding-top:6px;
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
	.icon{
	padding: 1px 1px 0px 8px;
	}
	.icon-blue{
	background-color: #1155cc;
	}
	.icon-green{
	background-color: #6aa84f;
	}
	.icon-yellow{
	background-color: #f1c232;
	}
	.icon-red{
	background-color: #cc0000;
	}
	.scheduler_default_corner_inner div{
	color:#F3F3F3 !important;
	background-color:#F3F3F3 !important;
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
.scheduler_default_event_bar{

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
.scheduler_default_event_bar{
	/*	background: transparent;
*/}
.scheduler_default_event_bar_inner{
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
.ex1 input:checked + span {
background-color: #fff;
box-shadow: 0 0 5px 2px rgba(0, 0, 0, 0.1);
}
.ex1 .red input:checked + span {
color: red;
border-color: red;
}
.ex1 .red input:checked + span:before {
background-color: red;
}
.ex1 .blue input:checked + span {
color: blue;
border-color: blue;
}
.ex1 .blue input:checked + span:before {
background-color: blue;
}
.ex1 .orange input:checked + span {
color: orange;
border-color: orange;
}
.ex1 .orange input:checked + span:before {
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
    border:  solid 4px transparent;
    border-top-color:  #00C8B1 !important;
    border-left-color: #00C8B1 !important;
    border-radius: 50%;
}

@keyframes loading-bar-spinner {
  0%   { transform: rotate(0deg);   transform: rotate(0deg); }
  100% { transform: rotate(360deg); transform: rotate(360deg); }
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

</style>
