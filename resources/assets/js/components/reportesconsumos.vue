<template>
   <main class="main" >
      <div class="col-12">
         <div class="content-header">Reporte Consumos</div>
      </div>
      <br>
      <input type="button" id="btnExport" class="btn btn-raised gradient-ibiza-sunset white sidebar-shadow btn-sm" value="Exportar Excel" />
      <section id="extended">
         <div class="row">
            <div v-if="tipoCarga==1">
               <div class="loader loader-default is-active" data-text></div>
            </div>
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title"></h4>
                  <div class="input-group">
                     <datepicker v-model="fecha_filtro" :language="es" class="form-control border-primary"></datepicker>
                     <button class="btn btn-raised gradient-pomegranate white big-shadow" type="submit" @click="ListarItems()">
                     <span class="btn-icon"><i class="la la-search"></i>Filtrar</span>
                     </button>
                     <br>
                  </div>

               </div>
            </div>
            <div class="col-sm-12">
               <div class="card">
                  <div class="card-header">
                     <h4 class="content-header" style="font-size: 14px;">VENTAS HABITACIONES</h4>
                  </div>
                  <div class="card-body">
                     <div class="card-body" style="height:auto !important;">
                        <div class="card-block">
                          <table class="table table-responsive-md-md text-center">
                              <thead>
                                  <tr>
                                      <th>Consumos</th>
                                      <th>Precio Neto</th>
                                      <th>Precio Total</th>
                                  </tr>
                              </thead>
                              <tbody v-for="detalles in ArrayDetalles" :key="detalles.id">
                                  <div style="position:absolute;">
                                    <span class="badge badge-warning">Habitación {{detalles.numero}}</span>
                                  </div>
                                  <br>
                                  <tr v-for="detalle in detalles.consumosExtras" :key="detalle.id">
                                      <td v-text="detalle.nombre"></td>
                                      <td>{{detalle.total_extras_neto}}</td>
                                      <td>{{detalle.total_consumo}}</td>
                                  </tr>
                                  <tr>
                                      <td v-text="detalles.numero"></td>
                                      <td>{{detalles.precio_neto}}</td>
                                      <td>{{detalles.precio_bruto}}</td>
                                  </tr>
                                  <tr>
                                      <td>{{detalles.fechaAnterior}}</td>
                                      <td></td>
                                      <td>{{detalles.diaAyer}}</td>
                                  </tr>
                              </tbody>
                              <tfoot>
                              <tr>
                                <th id="total" colspan="1">Total :</th>
                                <td><span class="badge badge-primary">{{precio_neto_total}}</span></td>
                                <td><span class="badge badge-primary">{{precio_bruto_total}}</span></td>
                              </tr>
                             </tfoot>
                          </table>

                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="card-block" id="dvData" style="display: none;">
           <table>
               <thead>
                   <tr>
                       <th>Consumos</th>
                       <th>Precio Neto</th>
                       <th>Precio Total</th>
                   </tr>
               </thead>
               <tbody v-for="detalles in ArrayDetalles" :key="detalles.id">

                   <br>
                   <tr v-for="detalle in detalles.consumosExtras" :key="detalle.id">
                       <td v-text="detalle.nombre"></td>
                       <td>{{detalle.total_extras_neto}}</td>
                       <td>{{detalle.total_consumo}}</td>
                   </tr>
                   <tr>
                       <td v-text="detalles.numero"></td>
                       <td>{{detalles.precio_neto}}</td>
                       <td>{{detalles.precio_bruto}}</td>
                   </tr>
               </tbody>
               <tfoot>
               <tr>
                 <td></td>
                 <td>{{precio_neto_total}}</td>
                 <td>{{precio_bruto_total}}</td>
               </tr>
              </tfoot>
           </table>

         </div>
      </section>
   </main>
</template>
<script>
   import Vue from 'vue';
   import VueSweetAlert from 'vue-sweetalert';
   import Datepicker from 'vuejs-datepicker';
   import {
   es
   }
   from 'vuejs-datepicker/dist/locale'
   export default {
   data() {
   return {
    tipoCarga : 0,

   es: es,
   base:'',
   cadenaFechas:'',
   ArrayDetalles:[],
   selected:0,
   precio_neto_total:0,
   precio_bruto_total:0,
   tipoGuardar:2,
   disablebutton:false,
   submitted: false,
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
   image: '',
   impuesto_id:'',
   fecha_filtro: new Date(),
   }
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
   components: {
   Datepicker,
   },
   methods: {
   handleSubmit(e) {},
   convert(str) {
   	var date = new Date(str),
   	mnth = ("0" + (date.getMonth() + 1)).slice(-2),
   	day = ("0" + date.getDate()).slice(-2);
   	return [date.getFullYear(), mnth, day].join("-");
   	},
   ListarItems()
   	{
   		let me=this;
   		me.tipoCarga = 1;
   		me.cadenaFechas = '';
   var url= me.base+'/listar/reporte_consumos?fecha_filtro=' + me.convert(me.fecha_filtro);
   axios.get(url).then(function (response) {

		 console.log(response.data.datos);
     me.ArrayDetalles = response.data.datos;
     me.precio_neto_total = response.data.total_neto;
     me.precio_bruto_total = response.data.total_bruto;

     	me.tipoCarga = 0;

   })
   .catch(function (error) {
   var response = error.response.data;
   console.log(response.message,
   response.exception,
   response.file,
   response.line);
   });
   },
   BorrarDatos()
   {
   let me = this;
   me.selected
   me.$swal(
   {
   title: "Borrando",
   text: "¿Desea borrar de forma permanente este impuesto?",
   type: "warning",
   showCancelButton: true,
   confirmButtonColor: "#DD6B55",
   confirmButtonText: "Si, borrar"
   }).then(function(isConfirm)
   {
   axios
   .post(me.base+"/categorias_productos/borrar",{id:me.selected})
   .then(function(response)
   {
   if (isConfirm)
   {
   me.$swal
   ({
   title: 'Eliminado',
   text: 'Ha impuesto ha sido borrado',
   icon: 'success'
   }).
   then(function()
   {
   });
   }
   })
   .catch(function(error, otracosa) {});
   });
   },
   NuevoRegistro()
   {
   let me = this;
   me.selected=0;
   me.tipoGuardar = 2;
   me.nombre='';
   me.impuestoValor=0;
   },
   VerDato(data)
   {
   let me= this;
   me.tipoGuardar = 1;
   me.selected=data.id;
   me.nombre=data.nombre;
   me.impuestoValor=data.valor;
   },

   GuardarNuevoDatos()
   {
   this.submitted = true;
   this.$validator.validate().then(valid =>
   {
   if (valid)
   {
   let me = this;
   me.tipoGuardar = 1;
   me.disablebutton = true;
   axios
   .post(me.base+"/punto_ventas/Nueva", {
   nombre : me.nombre

   })
   .then(function(response)
   {
   if (response.data.validate)
   {
   me.disablebutton = false;
   me.id = response.data.id;
   me.$swal("se ha guardado el punto de venta " + me.nombre + " con éxito");
   me.VerDato(response.data);
   } else {
   me.disablebutton = false;
   me.$swal("No se ha podido guardar el punto de venta " + me.numero);
   }
   }).catch(function(error) {console.log(error)});
   }
   else
   {
   me.$swal("Por favor ingrese todos los campos");
   }
   });
   },
   GuardarEditarDatos()
   {
   			let me = this;
   this.submitted = true;
   this.$validator.validate().then(valid =>
   {
   if (valid)
   {
   me.disablebutton = true;
   axios
   .post(me.base+"/punto_ventas/Editar", {
   id: me.selected,
   nombre : me.nombre,
   valor: me.impuestoValor
   })
   .then(function(response) {
   if (response.data.validate) {
   me.$swal("se ha editado el impuesto " + me.nombre + " con éxito");
   me.VerDato(response.data);
   } else {
   me.$swal("Se presento un problema al editar impuesto " + me.nombre + ".");
   }
   me.disablebutton = false;
   })
   .catch(function(error) {
   me.disablebutton = false;
   							console.log(error)
   });
   }
   else
   {
   me.$swal("Por favor ingrese todos los campos");
   }
   });
   }
   },
   mounted() {
   		this.base=base;
   		$("#btnExport").click(function(e) {
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
   .blue1{
   color: rgb(20, 103, 239);
   }
   .verde{
   color: rgb(20, 179, 25);
   }
   .gris{
   color: rgb(117, 111, 111);
   }

   .card .card-body {
    padding: 0 !important;
    height: auto !important;
  }
</style>
