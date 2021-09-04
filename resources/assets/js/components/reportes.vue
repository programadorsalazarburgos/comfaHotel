<template>
<main class="main" >
	<div class="col-12">
		<div class="content-header">Reporte Ventas</div>
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
					<div class="alert alert-warning alert-dismissible fade show" v-if="cadenaFechas.length > 0">
				    <strong>Realizar reporte de fechas: </strong> {{cadenaFechas}}
				    <button type="button" class="close" data-dismiss="alert">&times;</button>
				</div>
				</div>
			</div>
			<div class="col-sm-12">
				<div class="card">
					<div class="card-header">
						<h4 class="content-header" style="font-size: 14px;">VENTAS HABITACIONES</h4>
					</div>
					<div class="card-body">
						<div class="card-body">
							<div class="card-block">
								<table class="table table-responsive-md-md text-center">
									<thead>
										<tr>

											<th>DATO</th>
											<th>VENTAS DIA</th>
											<th>VENTAS ACUMULADO MES</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="datos in ArrayDatos" :key="datos.id">
											<td v-text="datos.nombre"></td>
											<td>
												<div v-if="datos.total_dia==0">
													<span class="badge badge-success">0.0</span>
												</div>
												<div v-else>
													<span class="badge badge-danger">{{(datos.total_dia).toFixed(2)}}</span>
												</div>

											</td>
											<td>
												<div v-if="datos.total_mes==0">
													<span class="badge badge-success">0.0</span>
												</div>
												<div  v-else>
													<span class="badge badge-danger">{{(datos.total_mes).toFixed(2)}}</span>
												</div>

											</td>

										</tr>
										<tr>
											<td><strong>Total Habitaciones</strong></td>
											<td><strong>{{(suma_total_dia).toFixed(2)}}</strong></td>
											<td><strong>{{(suma_total_mes).toFixed(2)}}</strong></td>

										</tr>


									<table class="table table-responsive-md-md text-center">
										<tbody>
											<tr v-for="datos in ArrayDatos2" :key="datos.id">
												<td><strong>{{datos.nombre}}</strong>
													<tr v-for="datos2 in datos.puntos_de_venta" :key="datos2.id">
															<td>{{datos2.nombre}}</td>
															<td style="position: absolute;left: 548px;">{{(datos2.venta_dia).toFixed(2)}}</td>
															<td style="position: absolute;left: 896px;">{{(datos2.venta_mes).toFixed(2)}}</td>
													</tr>

												</td>
											</tr>
											<tr>
											<td><strong>Total Consumos</strong></td>
											<td style="position: absolute;left: 548px;"><strong>{{(suma_total_venta_dia).toFixed(2)}}</strong></td>
											<td style="position: absolute;left: 896px;"><strong>{{(suma_total_venta_mes).toFixed(2)}}</strong></td>

										</tr>
										</tbody>
									</table>

<table>
	<tbody>
		<tr>
			<td><strong>Desayuno Incluido</strong></td>
			<td style="position: absolute;left: 548px;">{{(totalDia).toFixed(2)}}</td>
			<td style="position: absolute;left: 896px;">{{(totalMes).toFixed(2)}}</td>
		</tr>

	</tbody>

</table>


<table>
	<tbody>
		<tr>
			<td><strong>TOTAL VENTAS NETAS</strong></td>
			<td style="position: absolute;left: 548px;">{{(totalReporteDia).toFixed(2)}}</td>
			<td style="position: absolute;left: 896px;">{{(totalReporteMes).toFixed(2)}}</td>
		</tr>

	</tbody>

</table>



<table>
	<tbody>
		<tr  v-for="datos in ArrayDatos4" :key="datos.id">
			<td><strong>{{datos.nombre}}</strong></td>
			<td style="position: absolute;left: 548px;">{{(sum).toFixed(2)}}</td>
			<td style="position: absolute;left: 896px;">{{(sum2).toFixed(2)}}</td>
		</tr>

	</tbody>

</table>


<table>
	<tbody>
		<tr  v-for="datos in ArrayDatos5" :key="datos.id">
			<td><strong>{{datos.nombre}}</strong></td>
			<td style="position: absolute;left: 548px;">{{(sum_impuesto2).toFixed(2)}}</td>
			<td style="position: absolute;left: 896px;">{{(sum2_impuesto2).toFixed(2)}}</td>
		</tr>

	</tbody>

</table>

<table>
	<tbody>
		<tr>
			<td><strong>Servicio</strong></td>
			<td style="position: absolute;left: 548px;">{{(sum2_impuesto_servicio_dia).toFixed(2)}}</td>
			<td style="position: absolute;left: 896px;">{{(sum2_impuesto_servicio_mes).toFixed(2)}}</td>
		</tr>

	</tbody>

</table>


<table>
	<tbody>
		<tr>
			<td><strong>IGV PRODUCTOS</strong></td>
			<td style="position: absolute;left: 548px;">{{(sum_consumo).toFixed(2)}}</td>
			<td style="position: absolute;left: 896px;">{{(sum_consumo2).toFixed(2)}}</td>
		</tr>
		<tr>
			<td><strong>SERVICIO PRODUCTOS</strong></td>
			<td style="position: absolute;left: 548px;">{{(sum_consumo_servicio).toFixed(2)}}</td>
			<td style="position: absolute;left: 896px;">{{(sum_consumo2_servicio).toFixed(2)}}</td>
		</tr>

	</tbody>

</table>


<table>
	<tbody>
		<tr  v-for="datos in ArrayDatos6" :key="datos.id">
			<td><strong>{{datos.nombre}}</strong></td>
			<td style="position: absolute;left: 548px;">{{(datos.total_dia_tipo).toFixed(2)}}</td>
			<td style="position: absolute;left: 896px;">{{(datos.total_mes_tipo).toFixed(2)}}</td>
		</tr>

	</tbody>

</table>


<table>
	<tbody>
		<tr>
			<td><strong>CXC clientes alojados Ayer</strong></td>
			<td style="position: absolute;left: 548px;">{{(SumaDiaAnterior)}}</td>
			<td style="position: absolute;left: 896px;">{{(SumaMesAnterior)}}</td>
		</tr>

	</tbody>

</table>


<table>
	<tbody>
		<tr>
			<td><strong>CXC clientes alojados Hoy</strong></td>
			<td style="position: absolute;left: 548px;">{{(clientesAlojadosHoyDia).toFixed(2)}}</td>
			<td style="position: absolute;left: 896px;">{{(clientesAlojadosHoyMes).toFixed(2)}}</td>
		</tr>

	</tbody>

</table>



</tbody>
								</table>


									</div>
								</div>
							</div>
						</div>
					</div>




							<div class="card-block" id="dvData" style="display: none;">
								<table class="table table-responsive-md-md text-center">
									<thead>
										<tr>

											<th>DATO</th>
											<th>DIA</th>
											<th>MES</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="datos in ArrayDatos" :key="datos.id">
											<td v-text="datos.nombre"></td>
											<td>
												<div v-if="datos.total_dia==0">
													<span class="badge badge-success">0.0</span>
												</div>
												<div v-else>
													<span class="badge badge-danger">{{(datos.total_dia).toFixed(2)}}</span>
												</div>

											</td>
											<td>
												<div v-if="datos.total_mes==0">
													<span class="badge badge-success">0.0</span>
												</div>
												<div  v-else>
													<span class="badge badge-danger">{{(datos.total_mes).toFixed(2)}}</span>
												</div>

											</td>

										</tr>
										<tr>
											<td><strong>Total Habitaciones</strong></td>
											<td><strong>{{(suma_total_dia).toFixed(2)}}</strong></td>
											<td><strong>{{(suma_total_mes).toFixed(2)}}</strong></td>
										</tr>


									<table class="table table-responsive-md-md text-center">
										<tbody>
											<tr v-for="datos in ArrayDatos2" :key="datos.id">
												<td><strong>{{datos.nombre}}</strong>
													<tr v-for="datos2 in datos.puntos_de_venta" :key="datos2.id">
															<td>{{datos2.nombre}}</td>
															<td>{{(datos2.venta_dia).toFixed(2)}}</td>
															<td>{{(datos2.venta_mes).toFixed(2)}}</td>
													</tr>

												</td>
											</tr>
											<tr>
											<td><strong>Total Consumos</strong></td>
											<td><strong>{{(suma_total_venta_dia).toFixed(2)}}</strong></td>
											<td><strong>{{(suma_total_venta_mes).toFixed(2)}}</strong></td>
										</tr>
										</tbody>
									</table>

<table>
	<tbody>
		<tr>
			<td><strong>Desayuno Incluido</strong></td>
			<td>{{(totalDia).toFixed(2)}}</td>
			<td>{{(totalMes).toFixed(2)}}</td>
		</tr>

	</tbody>

</table>


<table>
	<tbody>
		<tr>
			<td><strong>TOTAL VENTAS NETAS</strong></td>
			<td>{{(totalReporteDia).toFixed(2)}}</td>
			<td>{{(totalReporteMes).toFixed(2)}}</td>
		</tr>

	</tbody>

</table>



<table>
	<tbody>
		<tr  v-for="datos in ArrayDatos4" :key="datos.id">
			<td><strong>{{datos.nombre}}</strong></td>
			<td>{{(sum).toFixed(2)}}</td>
			<td>{{(sum2).toFixed(2)}}</td>
		</tr>

	</tbody>

</table>



<table>
	<tbody>
		<tr  v-for="datos in ArrayDatos5" :key="datos.id">
			<td><strong>{{datos.nombre}}</strong></td>
			<td>{{(sum_impuesto2).toFixed(2)}}</td>
			<td>{{(sum2_impuesto2).toFixed(2)}}</td>
		</tr>

	</tbody>

</table>

<table>
	<tbody>
		<tr>
			<td><strong>Servicio</strong></td>
			<td>{{(sum2_impuesto_servicio_dia).toFixed(2)}}</td>
			<td>{{(sum2_impuesto_servicio_mes).toFixed(2)}}</td>
		</tr>

	</tbody>

</table>


<table>
	<tbody>
		<tr>
			<td><strong>IGV PRODUCTOS</strong></td>
			<td>{{(sum_consumo).toFixed(2)}}</td>
			<td>{{(sum_consumo2).toFixed(2)}}</td>
		</tr>
		<tr>
			<td><strong>SERVICIO PRODUCTOS</strong></td>
			<td>{{(sum_consumo_servicio).toFixed(2)}}</td>
			<td>{{(sum_consumo2_servicio).toFixed(2)}}</td>
		</tr>
	</tbody>
</table>

<!--
<table>
	<tbody>

		<tr>
			<td><strong>IGV PRODUCTOS</strong></td>
			<td style="position: absolute;left: 548px;">{{(sum_consumo).toFixed(2)}}</td>
			<td style="position: absolute;left: 896px;">{{(sum_consumo2).toFixed(2)}}</td>
		</tr>
		<tr>
			<td><strong>SERVICIO PRODUCTOS</strong></td>
			<td style="position: absolute;left: 548px;">{{(sum_consumo_servicio).toFixed(2)}}</td>
			<td style="position: absolute;left: 896px;">{{(sum_consumo2_servicio).toFixed(2)}}</td>
		</tr>

	</tbody>

</table> -->


<table>
	<tbody>
		<tr  v-for="datos in ArrayDatos6" :key="datos.id">
			<td><strong>{{datos.nombre}}</strong></td>
			<td>{{(datos.total_dia_tipo).toFixed(2)}}</td>
			<td>{{(datos.total_mes_tipo).toFixed(2)}}</td>
		</tr>

	</tbody>

</table>


<table>
	<tbody>
		<tr>
			<td><strong>CXC clientes alojados Ayer</strong></td>
			<td>{{(SumaDiaAnterior).toFixed(2)}}</td>
			<td>{{(SumaMesAnterior).toFixed(2)}}</td>
		</tr>

	</tbody>

</table>


<table>
	<tbody>
		<tr>
			<td><strong>CXC clientes alojados Hoy</strong></td>
			<td>{{(clientesAlojadosHoyDia)}}</td>
			<td>{{(clientesAlojadosHoyMes)}}</td>
		</tr>

	</tbody>

</table>



</tbody>
								</table>


									</div>
















					<!-- 			<div class="col-sm-12">
						<div class="card">
								<div class="card-header">
											<h4 class="content-header" style="font-size: 14px;">VENTAS PUNTO DE VENTAS</h4>
								</div>
								<div class="card-body">
											<div class="card-body">
														<div class="card-block">
																	<table class="table table-responsive-md-md text-center">
																				<thead>
																							<tr>

																										<th>DATO</th>
																										<th>DIA</th>
																										<th>MES</th>
																										<th>AÑO</th>
																							</tr>
																				</thead>
																				<tbody>
																							<tr v-for="datos in ArrayDatos2" :key="datos.id">
																										<td><strong>{{datos.nombre}}</strong>
																													<table style="position: relative; left: 100px;">
																																<thead>
																																			<tr>
																																			</tr>
																																</thead>
																																<tbody>
																																			<tr v-for="datos2 in datos.puntos_de_venta" :key="datos2.id">
																																						<td>{{datos2.nombre}}</td>

																																			</tr>


																																</tbody>
																													</table>
																										</td>
																										<td>
																													<br>

																													<table>
																																<thead>
																																			<tr>



																																			</tr>
																																</thead>
																																<tbody>
																																			<tr v-for="datos2 in datos.puntos_de_venta" :key="datos2.id">
																																						<td style="position: relative; left: 80px;">
																																									<div v-if="datos2.venta_dia==0">
																																												<span class="badge badge-success">0</span>
																																									</div>
																																									<div  v-else>
																																												<span class="badge badge-danger">{{(datos2.venta_dia).toFixed(2)}}</span>
																																									</div>
																																						</td>

																																			</tr>


																																</tbody>
																													</table>

																										</td>
																										<td>
																													<br>

																													<table>
																																<thead>
																																			<tr>



																																			</tr>
																																</thead>
																																<tbody>
																																			<tr v-for="datos2 in datos.puntos_de_venta" :key="datos2.id">
																																						<td style="position: relative; left: 80px;">

																																									<div v-if="datos2.venta_mes==0">
																																												<span class="badge badge-success">0</span>
																																									</div>
																																									<div  v-else>
																																												<span class="badge badge-danger">{{(datos2.venta_mes).toFixed(2)}}</span>
																																									</div>
																																						</td>

																																			</tr>


																																</tbody>
																													</table>

																										</td>
																										<td>
																													<br>

																													<table>
																																<thead>
																																			<tr>



																																			</tr>
																																</thead>
																																<tbody>
																																			<tr v-for="datos2 in datos.puntos_de_venta" :key="datos2.id">
																																						<td style="position: relative; left: 80px;">
																																									<div v-if="datos2.venta_anio==0">
																																												<span class="badge badge-success">0</span>
																																									</div>
																																									<div  v-else>
																																												<span class="badge badge-danger">{{(datos2.venta_anio).toFixed(2)}}</span>
																																									</div>

																																						</td>

																																			</tr>


																																</tbody>
																													</table>

																										</td>
																							</tr>


																				</tbody>
																				<tfoot>

																				<tr>
																							<td colspan="1"></td>

																							<td style="font-size: 12px;"><span class="badge badge-info">{{(suma_total_venta_dia).toFixed(2)}}</span></td>
																							<td style="font-size: 12px;"><span class="badge badge-info">{{(suma_total_venta_mes).toFixed(2)}}</span></td>
																							<td style="font-size: 12px;"><span class="badge badge-info">{{(suma_total_venta_anio).toFixed(2)}}</span></td>
																				</tr>
																				</tfoot>
																	</table>

														</div>
											</div>
								</div>
						</div>
					</div> -->
					<!-- 		<div class="col-sm-12">
							<div class="card">
										<div class="card-header">
													<h4 class="content-header" style="font-size: 14px;">DESAYUNO INCLUIDO</h4>
										</div>
										<div class="card-body">
													<div class="card-body">
																<div class="card-block">
																			<table class="table table-responsive-md-md text-center">
																						<thead>
																									<tr>

																												<th>DATO</th>
																												<th>DIA</th>
																												<th>MES</th>
																												<th>AÑO</th>
																									</tr>
																						</thead>
																						<tbody>

																									<tr>
																												<tr>
																														<td>{{ArrayDatos3.nombre}}</td>
																														<td>
																																	<div v-if="ArrayDatos3.totalDia==0">
																																				<span class="badge badge-success">0</span>
																																	</div>
																																	<div v-else>
																																				<span class="badge badge-danger">{{ArrayDatos3.totalDia}}</span>
																																	</div>

																														</td>
																														<td>
																																	<div v-if="ArrayDatos3.totalMes==0">
																																				<span class="badge badge-success">0</span>
																																	</div>
																																	<div  v-else>
																																				<span class="badge badge-danger">{{ArrayDatos3.totalMes}}</span>
																																	</div>

																														</td>
																														<td>
																																	<div v-if="ArrayDatos3.totalAnio==0">
																																				<span class="badge badge-success">0</span>
																																	</div>
																																	<div  v-else>
																																				<span class="badge badge-danger">{{ArrayDatos3.totalAnio}}</span>
																																	</div>

																														</td>
																											</tr>


																								</tbody>

																					</table>

																		</div>
															</div>
												</div>
									</div>
						</div>
					</div>
					-->
					<!--  <div class="col-sm-12">
											<div class="card">
														<div class="card-header">
																	<h4 class="content-header" style="font-size: 14px;">TOTAL VENTAS NETAS</h4>
														</div>
														<div class="card-body">
																	<div class="card-body">
																				<div class="card-block">
																							<table class="table table-responsive-md-md text-center">
																										<thead>
																													<tr>

																																<th>DATO</th>
																																<th>DIA</th>
																																<th>MES</th>
																																<th>AÑO</th>
																													</tr>
																										</thead>
																										<tbody>

																													<tr>
																																<tr>
																																		<td><strong>TOTAL</strong></td>
																																		<td>
																																					<div v-if="totalReporteDia==0">
																																								<span class="badge badge-success">0</span>
																																					</div>
																																					<div v-else>
																																								<span class="badge badge-danger">{{totalReporteDia}}</span>
																																					</div>

																																		</td>
																																		<td>
																																					<div v-if="totalReporteMes==0">
																																								<span class="badge badge-success">0</span>
																																					</div>
																																					<div  v-else>
																																								<span class="badge badge-danger">{{totalReporteMes}}</span>
																																					</div>

																																		</td>
																																		<td>
																																					<div v-if="totalReporteAnio==0">
																																								<span class="badge badge-success">0</span>
																																					</div>
																																					<div  v-else>
																																								<span class="badge badge-danger">{{totalReporteAnio}}</span>
																																					</div>

																																		</td>
																															</tr>


																												</tbody>

																									</table>

																						</div>
																			</div>
																</div>
													</div>
										</div>
					-->		</div>

					<!-- 		<div class="col-sm-12">
							<div class="card">
										<div class="card-header">
													<h4 class="content-header" style="font-size: 14px;">TIPO PAGOS</h4>
										</div>
										<div class="card-body">
													<div class="card-body">
																<div class="card-block">
																			<table class="table table-responsive-md-md text-center">
																						<thead>
																									<tr>

																												<th>DATO</th>
																												<th>DIA</th>
																												<th>MES</th>
																												<th>AÑO</th>
																									</tr>
																						</thead>
																						<tbody>
																									<tr v-for="datos in ArrayDatos6" :key="datos.id">
																												<td v-text="datos.nombre"></td>
																												<td>

																															<div>
																																		<span class="badge badge-danger">{{datos.total_dia_tipo}}</span>
																															</div>

																												</td>
																												<td>

																															<div>
																																		<span class="badge badge-danger">{{datos.total_mes_tipo}}</span>
																															</div>

																												</td>
																												<td>

																															<div>
																																		<span class="badge badge-danger">{{datos.total_anio_tipo}}</span>
																															</div>

																												</td>
																									</tr>


																						</tbody>

																			</table>

																</div>
													</div>
										</div>
							</div>
					</div>
					-->
					<!-- 		<div class="col-sm-12">
							<div class="card">
										<div class="card-header">
													<h4 class="content-header" style="font-size: 14px;">VENTAS</h4>
										</div>
										<div class="card-body">
													<div class="card-body">
																<div class="card-block">

																</div>
													</div>
										</div>
							</div>
					</div>
					-->
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
				sum: '',
				sum2: '',
				sum3: '',
				SumaDiaAnterior: 0.0,
				SumaMesAnterior: 0.0,
				SumaAntioAnterior: 0.0,
				SumaDiaHoy: 0.0,
				SumaMesHoy: 0.0,
				SumaAnioHoy: 0.0,
				sum_impuesto2: '',
				sum2_impuesto2: '',
				sum3_impuesto2: '',
				sum_consumo: 0.0,
				sum_consumo2: 0.0,
				sum_consumo3: 0.0,
				sum_consumo_servicio: 0.0,
				sum_consumo2_servicio: 0.0,
				es: es,
				base:'',
				cadenaFechas:'',
				totalReporteDia:0.0,
				totalReporteMes:0.0,
				totalReporteAnio:0.0,
				ArrayDatos:[],
				ArrayDatos2:[],
				ArrayDatos3:[],
				ArrayDatos4:[],
				ArrayDatos5:[],
				ArrayDatos6:[],
				ValorDesayuno:'',
				nombre:'',
				impuestoValor:0,
				clientesAlojadosHoyDia: 0.0,
				clientesAlojadosHoyMes: 0.0,
				suma_total_pagos: 0.0,
				suma_total_pagosDia: 0.0,
				suma_total_dia: 0.0,
				suma_total_mes: 0.0,
				suma_total_anio: 0.0,
				suma_total_venta_dia: 0.0,
				suma_total_venta_mes: 0.0,
				suma_total_venta_anio: 0.0,
				sum2_impuesto_servicio_dia: 0.0,
				sum2_impuesto_servicio_mes: 0.0,
				totalDia: 0.0,
				totalMes: 0.0,
				totalAnio: 0.0,
				selected:0,
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
			var url= me.base+'/listar/reporte?fecha_filtro=' + me.convert(me.fecha_filtro);
			axios.get(url).then(function (response) {
			me.tipoCarga = 0;

			var info = response.data;


			if (info == 'igual') {
				me.ArrayDatos = 0;
				me.ArrayDatos2 = 0;
				me.ArrayDatos3 = 0;


				me.ArrayDatos4 = 0;
				me.ArrayDatos5 = 0;
				me.ValorDesayuno = 0;
				me.sum = 0;
				me.sum2 = 0;
				me.sum3 = 0;
				me.sum_impuesto2 = 0;
				me.sum2_impuesto2 = 0;
				me.sum2_impuesto_servicio_dia = 0;
				me.sum2_impuesto_servicio_mes = 0;
				me.sum3_impuesto2 = 0;
				me.sum_consumo = 0;
				me.sum_consumo2 = 0;
				me.sum_consumo3 = 0;
				me.sum_consumo_servicio = 0;
				me.sum_consumo2_servicio = 0;
				me.SumaDiaAnterior = 0;
				me.SumaMesAnterior = 0;
				me.SumaDiaHoy = 0;
				me.SumaMesHoy = 0;
				me.ArrayDatos6 = 0;



					me.totalDia = 0;
					me.totalMes = 0;

			}

			if (info.Res.length > 0) {
				var cadena = info.Res;

			 var x = cadena.toString();
			 me.cadenaFechas = '';
			 me.cadenaFechas = x;
			}

			else {

			var respuesta= response.data;
			me.ArrayDatos = respuesta.datos;
			me.ArrayDatos2 = respuesta.datos2;
			me.ArrayDatos3 = respuesta.datos3;
			me.ArrayDatos4 = respuesta.datos4;
			me.ArrayDatos5 = respuesta.datos5;
			me.ValorDesayuno = respuesta.valor_desayuno;
			me.sum = respuesta.sum;
			me.sum2 = respuesta.sum2;
			me.sum3 = respuesta.sum3;
			me.sum_impuesto2 = respuesta.sum_impuesto2;
			me.sum2_impuesto2 = respuesta.sum2_impuesto2;
			me.sum2_impuesto_servicio_dia = respuesta.suma_impuesto_servicio;
			me.sum2_impuesto_servicio_mes = respuesta.sum2_impuesto_servicio;

			me.sum_consumo = respuesta.sum_consumo;
			me.sum_consumo2 = respuesta.sum_consumo2;
			me.sum_consumo_servicio = respuesta.sum_consumo_servicio;
			me.sum_consumo2_servicio = respuesta.sum_consumo2_servicio;
			me.sum_consumo3 = respuesta.sum_consumo3;
			me.SumaDiaAnterior = parseFloat(respuesta.SumaDiaAnterior);
			me.SumaMesAnterior = parseFloat(respuesta.SumaMesAnterior);
			me.SumaDiaHoy = parseFloat(respuesta.SumaDiaHoy);
			me.SumaMesHoy = parseFloat(respuesta.SumaMesHoy);
			me.ArrayDatos6 = respuesta.tipo_pagos;
			me.totalDia = parseFloat(me.ArrayDatos3.totalDia);
			me.totalMes = parseFloat(me.ArrayDatos3.totalMes);
			me.suma_total_pagos = 0.0;
			me.suma_total_pagosDia = 0.0;
			me.ArrayDatos6.map(function(dato, key) {
			dato.total_dia_tipo = (dato.total_dia_tipo == null) ? 0.0 : parseFloat(dato.total_dia_tipo);
			dato.total_mes_tipo = (dato.total_mes_tipo == null) ? 0.0 : parseFloat(dato.total_mes_tipo);
			me.suma_total_pagos+=parseFloat(dato.total_mes_tipo);
			me.suma_total_pagosDia+=parseFloat(dato.total_dia_tipo);
			return dato;
		});


				me.ArrayDatos.map(function(dato, key) {
				dato.total_dia = (dato.total_dia == null) ? 0 : dato.total_dia;
				dato.total_mes = (dato.total_mes == null) ? 0 : dato.total_mes;
				return dato;
			});


			me.suma_total_dia = 0.0;
			me.suma_total_mes = 0.0;
			for (var i = 0; i < me.ArrayDatos.length; i++) {
				// console.log(me.ArrayDatos[i].total_dia)
				me.suma_total_dia+=parseFloat(me.ArrayDatos[i].total_dia);
				me.suma_total_mes+=parseFloat(me.ArrayDatos[i].total_mes);
			}
			// console.log(me.suma_total_dia)
			me.suma_total_venta_dia = 0.0;
			me.suma_total_venta_mes = 0.0;
			me.ArrayDatos2.map(function(dato, key) {
			for (var i = 0; i < dato.puntos_de_venta.length; i++) {
				me.suma_total_venta_dia+=parseFloat(dato.puntos_de_venta[i].venta_dia);
				me.suma_total_venta_mes+=parseFloat(dato.puntos_de_venta[i].venta_mes);
			}

			});
			me.totalReporteDia = parseFloat(me.suma_total_dia) + parseFloat(me.suma_total_venta_dia) + parseFloat(me.ArrayDatos3.totalDia);
			me.totalReporteMes = parseFloat(me.suma_total_mes) + parseFloat(me.suma_total_venta_mes) + parseFloat(me.ArrayDatos3.totalMes);
			me.clientesAlojadosHoyMes = (parseFloat(me.totalReporteMes) + parseFloat(me.sum2 )+ parseFloat(me.sum2_impuesto2) + parseFloat(me.sum2_impuesto_servicio_mes) + parseFloat(me.sum_consumo2) + parseFloat(me.sum_consumo2_servicio) + parseFloat(me.SumaMesAnterior)) - parseFloat(me.suma_total_pagos);
			me.clientesAlojadosHoyDia = (parseFloat(me.totalReporteDia) + parseFloat(me.sum)+ parseFloat(me.sum_impuesto2) + parseFloat(me.sum2_impuesto_servicio_dia) + parseFloat(me.sum_consumo) + parseFloat(me.sum_consumo_servicio) + parseFloat(me.SumaDiaAnterior)) - parseFloat(me.suma_total_pagosDia);
			me.ArrayDatos4.map(function(dato, key) {
			dato.total_dia = (me.suma_total_dia * dato.valor)/100;
			dato.total_mes = (me.suma_total_mes * dato.valor)/100;
			return dato;
		});
		}
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
		</style>
