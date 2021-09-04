<template>
<div>
   <div class="col-sm-12">
      <div class="content-header">Clientes</div>
   </div>
   <section id="extendeds">
      <div class="container-fluid">
         <div class="row">
            <div class="col-md-12">
               <div class="card">
                  <div class="card-header">
                     <h4 class="card-title">
                        <button class="btn btn-danger" @click="recargar()">
                        <i class="icon-arrow-left" ></i>
                        </button> 
                        <span class="badge badge-info">Registro Cliente Alojados</span>
                     </h4>
                  </div>
                  <div class="card-body">
                     <div class="card-block">
                        <p style="display: none;">Takes the basic nav from above and adds the <code>.nav-tabs</code> class to generate a tabbed interface. </p>
                        <ul class="nav nav-tabs">
                           <li class="nav-item">
                              <a class="nav-link active" id="base-tab1" data-toggle="tab" aria-controls="tab1" 	href="#tab1" aria-expanded="false">Creacion Clientes</a>
                           </li>
                        </ul>
                        <div class="clearfix"></div><br>
						
						<div class="container-fluid">
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-6">
										<autocomplete input-class="form-control"
										:source="arrayClientes"  @selected="addDistributionGroup"></autocomplete>
									</div>
									<div class="col-md-6">
										<button type="button" class="btn mr-1 mb-1 btn-success" @click="abrirModal('rol','registrar')"><i class="fa fa-user"></i> Agregar Nuevo Cliente</button>
									</div>
								</div>
							</div>
						</div>
					</div>
						<section id="extended">
							<div class="row">
								<div class="col-sm-12">
									<div class="card">
										<div class="card-header">
											<h4 class="card-title"><span class="badge badge-warning" style="color: white;">LISTA DE CLIENTES HOSPEDADOS</span></h4>
											<span class="badge badge-danger">Cantidad: {{clientes_hospedados}}</span>
										</div>
										<!-- <crearcliente></crearcliente> -->
										<div class="card-body">
											<div class="card-block">
												<table class="table table-responsive-md-md text-center">
													<thead>
														<tr>
															<th>Nombres</th>
															<th>Apellidos</th>
															<th>Acción</th>
														</tr>
													</thead>
													<tbody>
														<tr v-for="(cliente, index) in ReservasHospedados" :key="index">
															<td v-text="cliente.nombre"></td>
															<td v-text="cliente.apellido"></td>
															<td>
																<a class="danger p-0" @click="eliminarCliente(cliente.id_cliente_huesped)" data-original-title="" title="">
																<i class="ft-x font-medium-3 mr-2"></i>
																</a>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</section>
                        <div class="tab-content px-1 pt-1">
                           
                           <div class="tab-pane" id="tab3" aria-labelledby="base-tab3" aria-expanded="true">
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
            <div class="modal-content" style="height: 602px;">
               <div class="modal-header">
                  <span class="badge badge-danger mr-1">Crear Clientes</span>
                  <!--  <h5 class="modal-title" ></h5> -->
                  <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                  <span aria-hidden="true">×</span>
                  </button>
               </div>
               <div class="modal-body">
                 

				<crearcliente></crearcliente>

               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
               </div>
            </div>
            <!-- /.modal-content -->
         </div>
         <!-- /.modal-dialog -->
      </div>
      <!--Fin del modal-->
   </section>
</div>
</template>
<script>
import Autocomplete from 'vuejs-auto-complete';
import Vue from 'vue';
import VueSweetAlert from 'vue-sweetalert'
import VueSweetalert2 from 'vue-sweetalert2';
import crearcliente from '../components/InsertCliente';


export default {
props: ['id_reserva', 'id_habitacion', 'cliente_id', 'reserva_detalle_id', 'id_grupo', 'clientes_hospedados'],
data()
{ 
    return {
		grupos_select: '',
		resultado:0.0,
		base:'',
		arrayClientes:[],
		data: '',
		ReservasGrupos:[],
		ReservasHospedados: [],
		GruposHabitaciones: [],
		Facturacion: [],
		modal: 0,
		tituloModal: '',
		pago_a_realizar: 0,
		tipo_pago: null,
		resultado_pagos: 0,
		tipoAccion: 0,
		TipoPagos: [],
		Pagos: [],
		GruposHabitaciones: [],
		Impuestos: [],
		tituloModal: '',
		valor_a_pagar_f: '',
		diferencia_pagos: '',
		options: [],

    }
},
components: {
	Autocomplete,
	crearcliente
},
methods:{
    iniciar2()
    {

	let me = this;
	me.seleccionarClientes();
		
	// axios
	// .post(me.base+"/reservas/agregar_cliente_hospedado", {
	// 	id_cliente: me.cliente_id,
	// 	id_grupo: me.id_grupo	
	// })
	// .then(function (response) {	

	me.ClientesHospedados();

	// })
	// .catch(function (error, otracosa) {});


	},	

	 cerrarModal() {
            this.modal = 0;

        },


	seleccionarClientes()
			{
				let me = this;
				var url = 'obtener/clientes';
				axios.get(me.base+url).then(function (response) {
						me.arrayClientes = response.data.clientes
					})
					.catch(function (error) {
						var response = error.response.data;
					});
			},

	addDistributionGroup(group) {	
				let me = this;
				axios
					.post(me.base+"/reservas/agregar_cliente_detalle_hospedado", {
						id_cliente: group.selectedObject.id,
						id_grupo: me.id_grupo		
					})
					.then(function (response) {	
						me.ClientesHospedados();						
					})
					.catch(function (error, otracosa) {});
			},	

		ClientesHospedados()
		{
			

				let me = this;
				var url = 'reservas/clientes_hospedados?id_grupo=' + me.id_grupo;

				axios.get(me.base+url).then(function (response) {
						
						me.ReservasHospedados = response.data.clientes_hospedados
					})
					.catch(function (error) {
						var response = error.response.data;
					});

		},

		eliminarCliente(cliente_id)
		{

				let me = this;

				axios
					.post(me.base+"/reservas/eliminar_cliente_hospedado", {
						id_cliente: cliente_id,
						id_grupo: me.id_grupo		
					})
					.then(function (response) {	
						me.ClientesHospedados();						
					})
					.catch(function (error, otracosa) {});


		},

		recargar() {
				let me = this;
				me.$router.go(me.$router.currentRoute)
			},

		   abrirModal(modelo, accion, data = []) {
            
            switch (modelo) {
                case 'rol': {
                    switch (accion) {
                        case 'registrar': {
                            this.modal = 1;
                            break;
                        }
                       
                    }
                }
            }
        },
	
},
mounted()
{
   this.base = base;
   this.iniciar2();
   this.ClientesHospedados();
}
}
</script>