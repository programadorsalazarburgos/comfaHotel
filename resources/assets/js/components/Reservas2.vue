<template>
	<main class="main">
		<div v-if="mostrar_facturacion
		">
			<div class="col-sm-12">
				<div class="content-header">Habitaciones <i  @click="ampliar = !ampliar" class="ft-maximize font-medium-3 blue-grey darken-4"></i> </div>
			</div>
			<section id="extended">
				<div class="row">
					<div :class="ampliar?'col-sm-12':'col-sm-8'">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">
									Reservaciones &nbsp;&nbsp;
									<button type="button"  @click="Listar()" class="btn btn-raised gradient-crystal-clear white shadow-big-navbar"> Buscar  <i class="fas fa-search-location"></i></button>
									<DayPilotScheduler id="dp" :config="config" ref="scheduler" />
								</h4>
							</div>
						</div>
					</div>
					<div v-if="!ampliar" class="col-sm-4">
						<div class="card">
							<div class="card-header">
								<div class="row">
									<div class="col-md-12">
										<div v-bind:class="'small c100 p'+porcentaje">
											<span>{{tiempo}} </span>
											<div class="slice">
												<div class="bar"></div>
												<div class="fill"></div>
											</div>
										</div>
										<h5 class="card-title reservaciones-title">Reservaciones</h5>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12">
										<ul class="list-group">
											<li @click="ModalReservas(reserva)" class="list-group-item" v-for="(reserva,index) in ArrayReservas" :key="index">
												<span v-text="reserva.cliente_nombre"></span>
												<br>
												<small>{{reserva.habitacion_nombre}}</small>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
		<div v-if="!mostrar_facturacion">
			<div class="col-sm-12">
				<div class="content-header">Facturación</div>
			</div>
			<section id="extendeds">


<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">

<div class="card">
        <div class="card-header">
          <h4 class="card-title">Realiza la Facturación</h4>
        </div>




        <div class="card-body">
          <div class="card-block">
            <p style="display: none;">Takes the basic nav from above and adds the <code>.nav-tabs</code> class to generate a tabbed interface. </p>
            <ul class="nav nav-tabs">
              <li class="nav-item">
                <a class="nav-link active" id="base-tab1" data-toggle="tab" aria-controls="tab1" href="#tab1" aria-expanded="false">Gastos</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="base-tab2" data-toggle="tab" aria-controls="tab2" href="#tab2" aria-expanded="false">Pagadores</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="base-tab3" data-toggle="tab" aria-controls="tab3" href="#tab3" aria-expanded="true">Facturas</a>
              </li>


       



            </ul>
            <div class="tab-content px-1 pt-1">
              <div role="tabpanel" class="tab-pane active" id="tab1" aria-expanded="false" aria-labelledby="base-tab1">


<div class="container-fluid">
	


<div class="card">
                <div class="card-header">
                	<div class="form-group">
 
    <label for="Inputselect-label">Filtro de Habitaciones</label>
                	<select  class="form-control" v-model="grupos_select" v-on:change="facturacion(data)">
			<option value="0" disabled="true">Seleccione</option>
			<option value="todas">Todas las habitaciones</option>
			<option v-for="(habitacion,index) in GruposHabitaciones" :key="index" :value="habitacion.id" v-text="habitacion.numero"></option>
			
</select>
</div>
                </div>
                <div class="card-body">
                    <div class="card-block" style="overflow-x:auto;">
                        <table class="table table-responsive-md-md text-center" style="font-size: 12px;">
                            <thead>
                                <tr>
                                    <th class="table-active" style="background-color: #519bbd; color: white">Pagadores</th>
                                    <th class="table-active" style="background-color: #519bbd; color: white">Fecha</th>
                                    <th class="table-active" style="background-color: #519bbd; color: white">Precio Unitario</th>
                                    
                                    <th class="table-active" style="background-color: #519bbd; color: white">Impuesto</th>
                                    <th class="table-active" style="background-color: #519bbd; color: white">Precio Bruto</th>  
                                    <th class="table-active" style="background-color: #519bbd; color: white">Descuento %</th>
                                    <th class="table-active" style="background-color: #519bbd; color: white"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(reserva, index) in ReservasGrupos" :key="index">
                                    <td class="table-danger" style="width: 40%; background-color: #eee;">HAB {{reserva.numero}} &nbsp;
                                    	<a data-original-title="" title="" class="info p-0"><i class="ft-user font-medium-3 mr-2" @click="listar_pagadores()"></i>
                                    	</a>

<table style="width:100%;">
  <tr>
    <th>Nombre Pagador</th>
    <th>Monto a Pagar</th> 
    <th style="width: 30%;"></th> 
  </tr>
  <tr v-for="(pagador, index2) in reserva.pagadores" :key="index2">
<td  style="width: 200px; font-size: 10px;" v-text="pagador.nombre + ' ' + pagador.apellido"></td>
<td>	
	<input type="text" v-model="pagador.valor_a_pagar" class="" style="width: 80px; text-align: right;"  v-on:keyup="validar_total(ReservasGrupos, pagador, index, index2, pagador.valor_a_pagar, reserva.precio_bruto)"> 
</td>
<td>
	<a class="success" data-original-title="" title="" @click="pasar_efectivo(ReservasGrupos, pagador, index, index2, pagador.valor_a_pagar)">
	    <i class="ft-corner-right-down font-medium-3"></i>
	</a>
	<a class="danger" data-original-title="" title="" @click="pasar_efectivo_up(ReservasGrupos, pagador, index, index2, pagador.valor_a_pagar)">
	    <i class="ft-corner-right-up font-medium-3"></i>
	</a>
</td>
  </tr>

</table>

                                </td>
                                    <td  style="width: 40%; text-align: center; font-size: 12px;" v-text="reserva.check_in_fecha"></td>
                                    <td  style="text-align: center; width: 20%;">
                                    	<input class="form-control" type="text" v-model="reserva.precio_total" v-on:keyup="calcularDescuento(reserva.descuento, reserva.precio_bruto, reserva.precio_total, reserva.impuesto, index)" style="width: 118px; text-align: right; font-size: 12px;">
                                    </td>
                                    <td  style="text-align: center; width: 20%;">
                                    
<div>
 
  <multiselect v-model="reserva.value" :options="options" :multiple="true" group-values="libs"  :group-select="true" placeholder="Seleccione Impuestos" track-by="nombre" label="nombre"  @input="calcularDescuento(reserva.descuento, reserva.precio_bruto, reserva.precio_total,reserva.value, index)"><span slot="noResult">No se encontraron resultados.</span></multiselect>

</div>



                                    </td>
                                  
                                    <td  style="text-align: center; width: 20%;">
                                    	<input class="form-control" type="text" v-model="reserva.precio_bruto"  style="width: 118px; text-align: right; font-size: 12px;">
                                    </td> 

                                    <td  style="text-align: center; width: 20%;">
                                    	<div class="input-group" style="width: 120%;">
                                    	<input class="form-control" type="text" v-model="reserva.descuento" v-on:keyup="calcularDescuento(reserva.descuento, reserva.precio_bruto, reserva.precio_total, reserva.impuesto, index)"  style=" text-align: right; font-size: 12px; height: 32px;">
                                    	<div class="input-group-append">
											<span style="height: 32px; width: 1px; font-size: 12px;" class="input-group-text" id="sizing-addon2">%</span>
										</div>
                                    	</div>

                                    </td>
									<td style="width: 20%;">
                                      
                                        <a class="danger p-0" data-original-title="" title="">
                                            <i class="ft-trash font-medium-3"></i>
                                        </a>
                                    </td>
                                </tr>
  
                            </tbody>
                            <tfoot>
<tr>
<th class="table-active"></th>
<th class="table-active"></th>
<th class="table-active"></th>
<th class="table-active">Total</th>
<th class="table-active">{{resultado}}</th>
<th class="table-active"></th>
<th class="table-active"></th>
</tr>
</tfoot>
                        </table>
                        		<a href="#" @click="GuardarFactura()" class="btn btn-raised gradient-nepal white">Guardar Factura</a>

                    </div>
                </div>
            </div>

</div>


              </div>
              <div class="tab-pane" id="tab2" aria-labelledby="base-tab2" aria-expanded="false">
               <autocomplete input-class="form-control"
  :source="arrayClientes"  @selected="addDistributionGroup">
</autocomplete>


<section id="extended">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">LISTA DE PAGADORES</h4>
                </div>
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
                            	
                                <tr v-for="(pagadores, index) in ReservasPagadores" :key="index">
                                   
                                    
                                    <td v-text="pagadores.nombre"></td>
                                    <td v-text="pagadores.apellido"></td>
                                   
                                    <td>
                                      
                                        <a class="danger p-0" data-original-title="" title="">
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






              </div>
              <div class="tab-pane" id="tab3" aria-labelledby="base-tab3" aria-expanded="true">
                <p>Biscuit ice cream halvah candy canes bear claw ice cream cake chocolate bar donut. Toffee cotton candy liquorice. Oat cake lemon drops gingerbread dessert caramels. Sweet dessert jujubes powder sweet sesame snaps.</p>
              </div>


        

            </div>
          </div>
        </div>
      </div>

		</div>
	</div>
</div>


			</section>
		</div>
		<div id="modals">
			<!-- Comentario -->
			<div class="modal fade" tabindex="-1" :class="{'mostrar' : modalComentario}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
				<div class="modal-dialog modal-primary modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title" >Comentarios</h4>
							<button type="button" class="close" @click="cerrarModal()" aria-label="Close">
							<span aria-hidden="true">×</span>
							</button>
						</div>
						<div class="modal-body">
							<form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
								<div class="form-group row">
									<div class="col-md-12">
										<label>Descripción</label>
										<textarea v-model="requisitos_especiales" class="form-control" placeholder="Ingrese descripción"></textarea>
									</div>
									<div class="col-md-12">
										<label>Comentarios</label>
										<textarea v-model="comentarios" class="form-control" placeholder="Ingrese descripción"></textarea>
									</div>
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
							<button type="button" class="btn btn-primary" @click="savemodalComentario()">Guardar</button>
						</div>
					</div>
				</div>
			</div>
			<!-- Comentario -->
			<!-- Modal -->
			<div class="modal fade text-left" :class="{'mostrar' : modalAsignacion}" id="large" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header bg-primary white">
							<h4 class="modal-title" id="myModalLabel17">Asignar habitacion</h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="cerrarModalAsignacion()" >
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<h5>Info reserva</h5>
							<div class="container-fluid">
								<div class="row">
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-6">
												<div class="splittwo">
													<label>Llegada / Salida</label><br>
													<label id="assignPeriod" class="bold">{{fecha_llegada}} - {{fecha_salida}}</label>
													<label>Tipo Habitación</label><br>
													<label id="assignRoom" class="bold">{{habitacion_nombre}}</label>
												</div>
											</div>
											<div class="col-md-6">
												<div class="splittwo">
													<label>Clientes</label><br>
													<label id="assignPeriod" class="bold">{{huespedes_cantidad}}</label><br>
													<label>Monto total</label><br>
													<label id="assignRoom" class="bold">{{precio_total}} USD</label>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<hr>
							<h5>Asignar habitacion</h5>
							<div class="container-fluid">
								<div class="row">
									<div class="col-md-12">
										<label>Habitación</label><br>
										<select  class="form-control" v-model="habitacion">
											<option value="0" disabled>Seleccione</option>
											<option v-for="(habitacion,index) in arrayHabitacion" :key="index" :value="habitacion.id" v-text="habitacion.numero + ' (max.' + habitacion.personas_maximo + ')'"></option>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-raised gradient-crystal-clear white shadow-big-navbar" data-dismiss="modal"  @click="Asignar(info, habitacion)">Asignar</button>
							<button type="button" class="btn btn-raised gradient-pomegranate white big-shadow">Anular</button>
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade" tabindex="-1" :class="{'mostrar' : modalDetalle}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
				<div class="modal-dialog modal-primary modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title" v-text="tituloModal"></h4>
							<button type="button" class="close" @click="cerrarModal()" aria-label="Close">
							<span aria-hidden="true">×</span>
							</button>
						</div>
						<div class="modal-body">
							<div id="detalles_habitacion">
								<table width="100%" border="0" cellspacing="2" cellpadding="1">
									<tbody>
										<tr>
											<td>
												<table width="100%" border="0" cellpadding="2" cellspacing="2">
													<tbody>
														<tr>
															<td style="font-size:14px;">

															</td>
															<td style="font-size:14px;" align="right"><strong>Booking Number: <span style="font-size:16px;">{{booking_number}}</span></strong><br></td>
														</tr>
														<!-- SALES GIFT -->
														<tr>
															<td colspan="2">
																<h1>Regalo</h1>
															</td>
														</tr>
														<tr>
															<td colspan="2" style="font-weight:bold;">{{regalo}}</td>
														</tr>
														<tr>
															<td colspan="2">
																<h1>Cliente</h1>
															</td>
														</tr>
														<tr valign="top">
															<td width="50%">
																<table width="100%" cellpadding="1" cellspacing="1" border="0">
																	<tbody>
																		<tr>
																			<td width="50%">Guest:</td>
																			<td width="50%">{{cliente}}</td>
																		</tr>
																		<tr>
																			<td>Address:</td>
																			<td>{{direccion}}</td>
																		</tr>
																		<tr>
																			<td>Zip Code:</td>
																			<td>{{zipcode}}</td>
																		</tr>
																		<tr>
																			<td>City:</td>
																			<td>{{ciudad}}</td>
																		</tr>
																		<tr>
																			<td>Country:</td>
																			<td>{{pais}}</td>
																		</tr>
																		<tr>
																			<td>Telephone:</td>
																			<td>{{telefono}}</td>
																		</tr>
																		<tr>
																			<td>Email:</td>
																			<td><a class="deflink" v-bind:href="'mailto:'+email">{{email}}</a></td>
																		</tr>
																		<tr valign="top">
																			<td>Language reservation page (client):</td>
																			<td>{{language}}</td>
																		</tr>
																	</tbody>
																</table>
															</td>
															<td width="50%">
																<table width="100%" cellpadding="1" cellspacing="1" border="0">
																	<tbody>
																		<tr>
																			<td>Arrival Time ca.:</td>
																			<td>{{llegada_hora}}</td>
																		</tr>
																	</tbody>
																</table>
															</td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
										<tr>
											<td>
												<!-- TRAVEL DATA -->
												<table width="100%" cellpadding="2" cellspacing="2" border="0">
													<tbody>
														<tr>
															<td colspan="4">
																<h1>Fecha de viaje</h1>
															</td>
														</tr>
														<tr>
															<td width="25%"><strong>Arrival:</strong></td>
															<td width="13%">{{llegada_fecha_dia}}</td>
															<td width="12%">{{llegada_fecha}}</td>
															<td width="60%" rowspan="2">Noches: {{noches}}</td>
														</tr>
														<tr>
															<td><strong>Departure:</strong></td>
															<td>{{salida_dia}}</td>
															<td>{{salida}}</td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
										<tr>
											<td>
												<!-- ROOM TYPES -->
												<table width="100%" border="0" cellpadding="2" cellspacing="2">
													<tbody>
														<tr>
															<td colspan="2">
																<h1>Tipo de habitación</h1>
															</td>
														</tr>
														<tr valign="top">
															<td width="25%" style="font-size:14px;"><strong>Room Type</strong></td>
															<td width="75%" style="font-size:14px;">
																<strong>{{habitacion_tipo}}</strong>
															</td>
														</tr>
														<tr valign="top">
															<td>Room 1</td>
															<td>
																{{habitacion_personas}} Personas
															</td>
														</tr>
													</tbody>
												</table>
												<table width="100%" border="0" cellpadding="2" cellspacing="2">
													<tbody>
														<tr>
															<td colspan="4">
																<h1>Precio </h1>
															</td>
														</tr>
														<tr valign="bottom">
															<td colspan="2"><strong>Room 1</strong></td>
														</tr>
														<tr>
														</tr>
														<tr>
															<td width="30%">{{habitacion_personas}} Persons</td>
															<td width="30%">{{habitacion_personas}} Nights = {{habitacion_personas}} x</td>
															<td width="20%" align="right">{{habitacion_precio}} USD</td>
															<td width="20%" align="right">{{habitacion_precio_unitario}} USD</td>
														</tr>
														<tr>
															<td></td>
															<td></td>
															<td align="right"></td>
															<td align="right"></td>
														</tr>
														<!-- TAXES -->
														<tr>
															<td colspan="4">
																<div style="font-weight:bold;">Discount</div>
															</td>
														</tr>
														<tr>
															<td>Early Bird Discount</td>
															<td>- 30.00 %</td>
															<td align="right">500.00 USD</td>
															<td align="right">- 150.00 USD</td>
														</tr>
														<tr>
															<td colspan="4" style="font-weight:bold;"><strong>Taxes</strong></td>
														</tr>
														<tr>
															<td>IMPUESTO</td>
															<td>18.00 % </td>
															<td align="right">350.00 USD</td>
															<td align="right">63.00 USD</td>
														</tr>
														<tr>
															<td><strong>VAT</strong></td>
														</tr>
														<tr>
															<td>VAT</td>
															<td>12.00 %</td>
															<td align="right">350.00 USD</td>
															<td align="right">42.00 USD</td>
														</tr>
													</tbody>
												</table>
												<table width="100%" cellpadding="2" cellspacing="2" border="0">
												</table>
												<!-- ADVANCED SERVICES -->
												<!-- TOTAL PRICE -->
												<table width="100%" cellpadding="2" cellspacing="2">
													<tbody>
														<tr>
															<td style="font-size:16px; font-weight:bold;">Total Price:</td>
															<td style="font-size:16px; font-weight:bold;" align="right">455.00 USD</td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
										<tr>
											<td>
												<h1>&nbsp;</h1>
												<table width="100%" border="0" cellpadding="2" cellspacing="2">
													<tbody>
														<tr>
															<td>
															</td>
														</tr>
													</tbody>
												</table>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
							<button type="button" class="btn btn-primary" @click="registrarCategoria()">Guardar</button>
						</div>
					</div>
				</div>
			</div>
			<!-- detalle -->
			<!-- Clientes alojados -->
			<div class="modal fade" tabindex="-1" :class="{'mostrar' : modalAlojados}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
									<label class="col-md-3 form-control-label">Descripción</label>
									<div class="col-md-9">
										<input type="text" v-model="descripcion" class="form-control" placeholder="Ingrese descripción">
									</div>
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
						</div>
					</div>
				</div>
			</div>
			<!-- Clientes alojados -->
			<!-- Marcar como no valido -->
			<div class="modal fade" tabindex="-1" :class="{'mostrar' : modalnoValido}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
									<label class="col-md-3 form-control-label">Descripción</label>
									<div class="col-md-9">
										<input type="text" v-model="descripcion" class="form-control" placeholder="Ingrese descripción">
									</div>
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
							<button type="button" class="btn btn-primary" @click="registrarCategoria()">Guardar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>
</template>
<script>
	import {DayPilot, DayPilotScheduler} from 'daypilot-pro-vue'
	import Vue from 'vue';
	import VueSweetalert2 from 'vue-sweetalert2';
	import Autocomplete from 'vuejs-auto-complete'
	import Multiselect from 'vue-multiselect'

  // register globally
  Vue.component('multiselect', Multiselect)

		export default
		{
			name: 'Scheduler',
			data() 
			{
				return {
					base:'',

 	  options: [],
      value: [],



					mostrar_facturacion:true,
					      config: {
	        timeHeaders: [
	          {groupBy: "Month"},
	          {groupBy: "Day", format: "d"}
	        ],
	        scale: "Day",
	        startDate: DayPilot.Date.today().firstDayOfYear(),
	        days: DayPilot.Date.today().daysInYear(),
	        resources: [],
	        events: [],
	        scrollTo: DayPilot.Date.today(),
	        eventMoveHandling: "Update",
	        onEventMoved: function (args) {
				var nueva_fecha_llegada=args.newStart.value;
	            var nueva_fecha_salida=args.newEnd.value;
	            var nueva_habitacion = args.newResource;
				let me = this;
			
	            axios
		        .post(me.base+"/reservas/ActualizarFecha",{
					id_reserva:args.e.data.id,
					id_grupo:args.e.data.id_grupo,
	                nueva_fecha_llegada:nueva_fecha_llegada,
	                nueva_fecha_salida: nueva_fecha_salida,
	                nueva_habitacion:nueva_habitacion
	            })
		        .then(function(response) 
		        {
	                me.actualizar();
		        })
		        .catch(function(error, otracosa) {});
	            this.message("Se ha actualizado la fecha");
	        },
	        onEventResized:function(args)
	        {
	            
	            var nueva_fecha_llegada=args.newStart.value;
	            var nueva_fecha_salida=args.newEnd.value;
	            var nueva_habitacion = args.newResource;
				let me = this;
				
	            axios
		        .post(me.base+"/reservas/ActualizarFecha",{
	                id_reserva:args.e.data.id,
					id_grupo:args.e.data.id_grupo,
	                nueva_fecha_llegada:nueva_fecha_llegada,
	                nueva_fecha_salida: nueva_fecha_salida,
	                nueva_habitacion:nueva_habitacion
	            })
		        .then(function(response) 
		        {
	                me.actualizar();
		        })
		        .catch(function(error, otracosa) {});
	            this.message("Se ha actualizado la fecha");
	        },
	        contextMenu: null
	      },
	

					grupos_select:'',
					id_habitacion_grupo:'',
					fecha_llegada:'',
					fecha_salida:'',
					habitacion_nombre:'',
					huespedes_cantidad:'',
					precio_total:'',
					id_reservacion:1,
					selected: null,
					tipoGuardar: 2,
					disablebutton: false,
					//===============================basicos===============================//
					test:true,
					ampliar:false,
					habitacion:'0',
					tiempo: 60,
					porcentaje: 100,
					id: null,
					ArrayReservas:[],
					arrayHabitacion:[],
					ReservasGrupos:[],
					arrayClientes:[],
					ReservasPagadores:[],
					GruposHabitaciones:[],
					Impuestos:[],
					modalComentario : 0,
					modalAsignacion : 0,
					modalDetalle : 0,
					args : 0,
					modalAlojados : 0,
					tituloModal:'',
					modalnoValido : 0,
					resultado : 0.0,
					resultado_impuesto : 0.0,
					validacion_precio_bruto : 0.0,
					mostrar_pagadores : false,
					descripcion:'',
	
	
	
					//===============================COMENTARIOS===============================//
					requisitos_especiales:'',
					comentarios:'',
					//===============================COMENTARIOS===============================//
					//=================================DETALLES=================================//
					regalo:'',
					cliente:'',
					direccion:'',
					zipcode:'',
					ciudad:'',
					pais:'',
					telefono:'',
					email:'',
					language:'',
					llegada_hora:'',
					llegada_fecha:'',
					salida:'',
					noches:'',
					habitacion_tipo:'',
					habitacion_personas:'',
					habitacion_precio:'',
					habitacion_precio_unitario:'',
					booking_number:'',
					cliente_pagina:'',
					llegada_fecha_dia:'',
					salida_dia:'',


					 tasks: [
        { title: 'Go to the store' },
        { title: 'Clean the house' },
        { title: 'Learn Vue.js' }
      ],
      selectedTasks: [],
					//=================================DETALLES=================================//
	
	
					
					//===============================basicos===============================//
				}
			},
			computed: {
	    // DayPilot.Scheduler object - https://api.daypilot.org/daypilot-scheduler-class/
	    scheduler: function () {
	      return this.$refs.scheduler.control;
	    }
	  },
			components: {DayPilotScheduler, Autocomplete, Multiselect},
			
		    methods: 
		    {
				    updateColor: function(e, color) 
	    {
	         let me = this;
	          axios
		        .post(me.base+"/reservas/changecolor",{id_reserva:e.data.id, color:color})
		        .then(function(response) 
		        {
	                me.datosHabitaciones();
		        })
		        .catch(function(error, otracosa) {});
	    },
	      iniciar()
	      {
	          let me = this;
	          me.config.contextMenu=new DayPilot.Menu({
	                    items: [
	                        {
	                            text: "Eliminar",
	                            icon : 'fa fa-trash',
	                            onClick: function(args) {
	                                
	                            }
	                        },
	                        {
	                            text: "Ver detalles",
	                            icon: "fas fa-info-circle",
	                            onClick: function(args,args2) { me.fnmodalDetalle(args.source.data.detalles)}
	                        },
							{
	                            text: "Hacer check in",
	                            icon: "fas fa-address-card",
	                            onClick: function(args,args2) { me.CheckIn(args.source.data)}
	                        },
							
	                        {
	                            text: "-"
	                        },
	                        {
	                            text: "Azul",
	                            icon: "icon icon-blue",
	                            color: "#1155cc",
	                            onClick: function(args) {me.updateColor(args.source, args.item.color); }
	                        },
	                        {
	                            text: "Verde",
	                            icon: "icon icon-green",
	                            color: "#6aa84f",
	                            onClick: function(args) {me.updateColor(args.source, args.item.color); }
	                        },
	                        {
	                            text: "Amarillo",
	                            icon: "icon icon-yellow",
	                            color: "#f1c232",
	                            onClick: function(args) {me.updateColor(args.source, args.item.color); }
	                        },
	                        {
	                            text: "Rojo",
	                            icon: "icon icon-red",
	                            color: "#cc0000",
	                            onClick: function(args) {me.updateColor(args.source, args.item.color); }
	                        },
	
							{
	                            text: "facturacion",
	                            icon: "fas fa-address-card",
	                            onClick: function(args,args2) 
	                            {

	                             me.args = args;
	                             me.facturacion(args.source.data)}
	                        },


	                    ]
	                });
	      },

	      listar_pagadores()
	      {
	      	let me = this;
	      	me.mostrar_pagadores=true;
	      },

	       updateColor: function(e, color) 
	     {
	         let me = this;
	          axios
		        .post(me.base+"/reservas/changecolor",{id_reserva:e.data.id, color:color})
		        .then(function(response) 
		        {
	                me.datosHabitaciones();
		        })
		        .catch(function(error, otracosa) {});
		 },
		 CheckIn(data)
		 {
			 let me = this;
			 me.mostrar_facturacion=false;
			
		 },
	      cargarHabitaciones()
	      {
	          let me = this;
	          axios
		        .get(me.base+"/habitaciones/index")
		        .then(function(response) 
		        {
	                me.resources = response.data;
	                me.scheduler.update({resources:response.data});//Pero aqui si funciona 
	
		        })
		        .catch(function(error, otracosa) {});
	      },
	      datosHabitaciones()
	      {
	          let me = this;
	            axios
		        .get(me.base+"/reservas/data_reservas")
		        .then(function(response) 
		        {
	                var data = response.data;
	                $.each(data,function(i,value)
	                {
	                    data[i].barColor=value.barcolor;
	                    delete data[i].barcolor;
	                });
	                me.scheduler.update({events: data});
		        })
		        .catch(function(error, otracosa) {});
	      },
	      actualizar()
	      {
	          let me = this;
	          me.datosHabitaciones();
	      },
				ModalReservas(reserva)
				{
					this.modalAsignacion = 1;
					this.fecha_llegada = reserva.fecha_llegada;
					this.fecha_salida = reserva.fecha_salida;
					this.huespedes_cantidad = reserva.huespedes_cantidad;
					this.infantes_cantidad = reserva.infantes_cantidad;
					this.ninos_cantidad = reserva.ninos_cantidad;
					this.adultos_cantidad = reserva.adultos_cantidad;
					this.habitacion_nombre = reserva.habitacion_nombre;
					
					this.precio_total = reserva.precio_total;
					this.info = reserva;
	
	   				let me=this;
		            var url= '/obtener/habitaciones?tipo_habitacion=' + reserva.id_habitacion_tipo + '&fecha_llegada='+ reserva.fecha_llegada + '&fecha_salida='+ reserva.fecha_salida;
		            axios.get(me.base+url).then(function (response) {
		                var respuesta= response.data;
		                me.arrayHabitacion = respuesta.habitaciones;
		            })
		            .catch(function (error) {
		                var response = error.response.data;
		                
		            });
	
	
					
					var id_tipo_habitacion=reserva.id_tipo_habitacion;
					var fecha_llegada=reserva.fecha_llegada;
					var fecha_salida=reserva.fecha_salida;
				},
	
				Asignar(info, habitacion)
				{
					let me = this;
					if(me.habitacion!='0')
					{
						axios.post(me.base+'/reservas/actualizar',{
							'data': info,
							'habitacion': habitacion
	
						}).then(function (response) {                  
							me.datosHabitaciones();
							me.Listar();
							me.cerrarModalAsignacion();
							me.cargarHabitaciones();
						}).catch(function (error) {
							alert('Error');
							
						});
					}
				},
				cerrarModal(){
	
					this.modalComentario = 0;
					this.modalDetalle = 0;
					this.modalAlojados = 0;
					this.modalnoValido = 0;
					
				},	
				cerrarModalAsignacion(){
					this.modalAsignacion = 0;},
				abrirModal(modelo, accion, data = [])
				{
					this.modal = 1;
				},
				Listar()
				{
					let me = this;
					me.ArrayReservas = [];
					axios
						.get(me.base+"/reservas/VerReservasChanel")
						.then(function(response) {
							
						me.ArrayReservas = response.data;
						})
						.catch(function(error, otracosa) {});
				},
				startInterval() 
				{
					let me = this;
					if(me.test)
					{
						setInterval(function()
						{
							if (me.tiempo == 0)
							{
				          		me.tiempo = 60;
				          		me.Listar();
				          		me.porcentajes = 100;
				        	}
				        	me.porcentaje = Math.round((me.tiempo * 100) / 60);
				        	me.tiempo = me.tiempo - 1;
					  }, 1000);
					}
				},
				fnmodalDetalle(data)
				{
					let me = this;
					me.modalDetalle 		= 1;
					me.email 				= data.email;
					me.regalo 				= data.regalo;
					me.cliente 				= data.cliente;
					me.direccion			= data.direccion;
					me.zipcode				= data.zipcode;
					me.ciudad				= data.ciudad;
					me.pais					= data.pais;
					me.telefono				= data.telefono;
					me.email				= data.email;
					me.language				= data.language;
					me.llegada_hora			= data.llegada_hora;
					me.llegada_fecha		= data.llegada_fecha;
					me.salida				= data.salida;
					me.noches				= data.noches;
					me.habitacion_tipo		= data.habitacion_tipo;
					me.habitacion_personas	= data.habitacion_personas;
					me.habitacion_precio	= data.habitacion_precio;
					me.booking_number		= data.booking_number;
					me.cliente_pagina		= data.cliente_pagina;
					me.llegada_fecha_dia	= data.llegada_fecha_dia;
					me.salida_dia 			= data.salida_dia;
					
				},
				fnmodalAlojados(data)
				{
					this.modalAlojados=1;
				},
				fnmodalComentario(data)
				{
					this.requisitos_especiales=data.requisitos;
					this.comentarios=data.comentarios;
					this.modalComentario=1;
				},
				savemodalComentario(data)
				{
					let me = this;
					axios.
					post(me.base+"reservas/save/comentarios/"+me.id,{
						requisitos_especiales: me.requisitos_especiales,
						comentarios : me.comentarios,
					}).
					then(function(response) 
					{
						if(response.data.validate)
						{
							me.Listar();
							me.modalComentario=0;
							Vue.swal("Guardado el comentario con éxito");
						}
						else
						{
							alert('Error');
						}
					});
	
				},
				fnmodalnoValido(data)
				{
					this.modalnoValido=1;
				},

		
			seleccionarHabitaciones(data)
			{

				
 				let me = this;
				var url= '/reservas/grupo_habitaciones?reserva_id=' + data.id;

	            axios.get(me.base+url).then(function (response) {
	                var respuesta= response.data.habitaciones;
					 
					me.GruposHabitaciones = response.data.habitaciones; 

		   
	            })
	            .catch(function (error, otracosa) {
	                // var response = error.response.data;
	                
	            });


			},


			facturacion(data)
		
			 {
			
			 let me = this;
			 me.mostrar_facturacion=false;
			 me.mostrar_pagadores=false;
			 me.data = data;
			 this.listarImpuestos();
			 this.seleccionarClientes();
			 this.seleccionarHabitaciones(data);


			 if (me.grupos_select == '') 
			 {
			 	me.id_habitacion_grupo = data.grupos[0].id_habitacion;
			 	alert(data.grupos[0].id_habitacion)
			 	
			 }

			 else
			 {
			 	me.id_habitacion_grupo = me.grupos_select;
			 
			 }
			 
	            var url= '/reservas/reserva_grupo?reserva_id=' + data.id + '&habitacion='+ me.id_habitacion_grupo;

	            axios.get(me.base+url).then(function (response) {
	                var respuesta= response.data;
	         
					me.ReservasPagadores = respuesta.datos_pagadores_agrupados;

				$.each(respuesta.reservas, function(key, reservacion) {    

					reservacion.precio_bruto = reservacion.precio_total;
					reservacion.descuento = 0;


					return reservacion;


				   });
               
                 for(var i=0;i<respuesta.reservas.length;i++){
                    
                  	
                  	me.resultado = me.resultado + (parseInt(respuesta.reservas[i].precio_bruto))
                    
                 }
	         		
	                me.ReservasGrupos = respuesta.reservas;
	           
	            })
	            .catch(function (error, otracosa) {
	                // var response = error.response.data;
	                
	            });



		 },

	listarImpuestos()
				{

 			let me = this;
			
			 
	            var url= '/reservas/impuestos';

	            axios.get(me.base+url).then(function (response) {
	                var respuesta= response.data;
	                
	                me.Impuestos = respuesta.impuestos;
	             
	                me.options.push(
					{
					libs: 
            				me.Impuestos
          				
					}
					

				)
	              
	               
	            })
	            .catch(function (error, otracosa) {
	                // var response = error.response.data;
	              
	            });



				},


		calcularImpuesto(impuesto, precio_total, index)
		{
			let me = this;
			
			var valor_impuesto = impuesto[1]/100; 
		
			$.each(me.ReservasGrupos, function(key, reserva) {
    		
				 if (index == key) { 

				 	
				 	reserva.precio_bruto = (reserva.precio_total * (1+ valor_impuesto));
				 	

				 }

   			});


		},





		calcularDescuento(descuento, precio_bruto, precio_total, impuesto, index)
		{


			

			let me = this;
			me.resultado = 0.0;

		 me.resultado_impuesto = 0;
		 for(var i=0;i<impuesto.length;i++){
                               	
             me.resultado_impuesto = me.resultado_impuesto + (parseInt(impuesto[i].valor))
                
         }

			// impuesto = (typeof impuesto == "undefined")?0:(typeof impuesto[1]  == "undefined")?impuesto:impuesto[1];


			$.each(me.ReservasGrupos, function(key, reserva) {
    		
				 if (index == key) { 



				 reserva.precio_bruto = parseFloat(precio_total) * (1+(parseFloat(me.resultado_impuesto)/100))*(1-(parseFloat(descuento)/100));	



				 return reserva;
				
				 }
			});


				 for(var i=0;i<me.ReservasGrupos.length;i++){
                    
                  
                  	me.resultado = me.resultado + (parseInt(me.ReservasGrupos[i].precio_bruto))
                    
                 }


		},

		pasar_efectivo(ReservasGrupos, pagador, index2, index, valor_a_pagar)
		{
			

		
			let me=this;
		

			$.each(ReservasGrupos, function(key, reserva) {
    		
				 if (index2 == key) { 
	 	
				$.each(reserva.pagadores, function(key2, pagadores) {

				var indice = index + 1;
				if (index == key2) 
				{
					pagadores.valor_a_pagar = ''
				}

				 if (indice == key2) { 

					pagadores.valor_a_pagar = valor_a_pagar
					
					 return pagadores
				 	}

				});

				 }
			
			});

		},




		pasar_efectivo_up(ReservasGrupos, pagador, index2, index, valor_a_pagar)
		{
			

			let me=this;
		
			$.each(ReservasGrupos, function(key, reserva) {
    		
				 if (index2 == key) { 
	 	
				$.each(reserva.pagadores, function(key2, pagadores) {

				var indice = index - 1;
				if (index == key2) 
				{
					pagadores.valor_a_pagar = ''
				}

				 if (indice == key2) { 

					pagadores.valor_a_pagar = valor_a_pagar
					
					 return pagadores
				 	}

				});

				 }
			
			});

		},



seleccionarClientes()
{
	let me=this;
	var url= '/obtener/clientes';
	axios.get(me.base+url).then(function (response) {
		
	    me.arrayClientes = response.data.clientes
	   
	})
	.catch(function (error) {
	    var response = error.response.data;
	    
	});
	
},

addDistributionGroup (group) {
  	
  	let me=this;
  	
  	 axios
    .post(me.base+"/reservas/agregar_pagador",
    	{
    		id_cliente:group.selectedObject.id,
    		ReservasGrupos:me.ReservasGrupos


    	})
    .then(function(response) 
    {

    
    	
		$.each(me.ReservasGrupos, function(key, reservas) 
		{
		

			$.each(response.data.nuevo_pagador2, function(key2, reservas2) 
			{

		

			if (reservas.id == reservas2[0].reserva_detalle_id) 
			{

				reservas.pagadores.push(reservas2[0]);
			}


			
			});
			

	 	});

    	me.ReservasPagadores.push(response.data.nuevo_pagador);
      
    })
    .catch(function(error, otracosa) {});


  },

onChange(descuento,precio_bruto,precio_total,reserva, index)
{

	let me=this;

		 me.resultado_impuesto = 0;
		 for(var i=0;i<reserva.length;i++){
                               	
             me.resultado_impuesto = me.resultado_impuesto + (parseInt(reserva[i].valor))
                
         }
	         	

},


GuardarFactura()
{
	let me=this;

	  axios
		        .post(me.base+"/reservas/guardar_factura",{
	                reservas:me.ReservasGrupos,
	                total:me.resultado

	            })
		        .then(function(response) 
		        {
	              
		        })
		        .catch(function(error, otracosa) {});

},

		validar_total(ReservasGrupos, pagador, index2, index, valor_a_pagar, precio_bruto)
		{

			let me=this;
		
			

			$.each(ReservasGrupos, function(key, reserva) {
			
 			if (index2 == key) { 
	 	
				me.validacion_precio_bruto=0;
	 			$.each(reserva.pagadores, function(key2, pagador) 
	 			{
	 				me.validacion_precio_bruto=me.validacion_precio_bruto+(($.trim(pagador.valor_a_pagar)=='')?0:parseFloat(pagador.valor_a_pagar));


	 				
	
			 	});
			
			if (me.validacion_precio_bruto > precio_bruto) {
								
				pagador.valor_a_pagar = '';
				Vue.swal('Error', "Excede el precio de pago de este registro");
		 	}

						 	
			
			 }


			});

		},

		

	},
			mounted() 
			{
				this.base=base;
				let me = this;
				this.iniciar();
				this.cargarHabitaciones();
				//this.scheduler.message("Bienvenidos");
				this.datosHabitaciones();
				this.Listar();
				this.startInterval();
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

code {
  background: #f2f2f2;
  padding: 0 0.5rem;
}

.mainBody {
  border-top: 1px solid #ddd;
  border-bottom: 1px solid #ddd;
  margin: 1rem 0;
  padding: 1rem 0;
}

	.wrap {
  display: flex;
  align-items: center;
  padding: 1rem;
}

	.left,
.right {
  flex: 1;
}
</style>