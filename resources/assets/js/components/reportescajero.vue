<template>
<main class="main" >
	<div class="col-12">
		<div class="content-header">Reporte Ventas Cajero</div>
	</div>
	<br>
	<section id="extended">
			<div class="card">
				<div class="card-header">
					<h4 class="card-title"></h4>
					<div class="container-fluid">

							<div class="col-md-12">
								<div class="row">
									<div class="col-md-4">
										<datepicker v-model="fecha_filtro" :language="es" class="form-control border-primary"></datepicker>
									</div>
									<div class="col-md-4" style="position: relative;right: 136px;">


											<select class="form-control" v-model="cajero">
															<option value="0" disabled>Seleccione</option>
															<option v-for="cajero in arrayCajeros" :key="cajero.id" :value="cajero.id" v-text="cajero.nombres"></option>
											</select>


									</div>
									<div class="col-md-4" style="position: relative;right: 136px;">
										<button class="btn btn-raised gradient-pomegranate white big-shadow" type="submit" @click="ImprimirFacturaCajero()">
										<span class="btn-icon"><i class="la la-search"></i>Generar Reporte</span>
										</button>

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
				es: es,
				base:'',
				totalReporteDia:0.0,
				totalReporteMes:0.0,
				totalReporteAnio:0.0,
				cajero:'',
				arrayCajeros:[],
				ArrayDatos:[],
				ArrayDatos2:[],
				ArrayDatos3:[],
				ArrayDatos4:[],
				ArrayDatos5:[],
				ArrayDatos6:[],
				ValorDesayuno:'',
				nombre:'',
				impuestoValor:0,
				suma_total_dia: 0.0,
				suma_total_mes: 0.0,
				suma_total_anio: 0.0,
				suma_total_venta_dia: 0.0,
				suma_total_venta_mes: 0.0,
				suma_total_venta_anio: 0.0,
				totalDia: 0.0,
				totalMes: 0.0,
				totalAnio: 0.0,
				selected:0,
				tipoGuardar:2,
				disablebutton:false,
				fecha_filtro: new Date(),
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




					selectCajeros(){
                let me=this;
                var url= '/obtener/selectCajeros';
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayCajeros = respuesta.datos;
                })
                .catch(function (error) {
                    var response = error.response.data;
                    console.log(response.message,
                        response.exception,
                        response.file,
                        response.line);
                });
            },

						ImprimirFacturaCajero()
						{
						let me = this;
						window.open(me.base + 'reservas/pdfCajero/' + me.cajero + '/'  +  me.convert(me.fecha_filtro)  + ',' + '_blank');

						},

				ListarItems()
					{
						let me=this;
			var url= me.base+'/listar/reporte?fecha_filtro=' + me.convert(me.fecha_filtro);
			axios.get(url).then(function (response) {
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
			me.sum3_impuesto2 = respuesta.sum3_impuesto2;
			me.sum_consumo = respuesta.sum_consumo;
			me.sum_consumo2 = respuesta.sum_consumo2;
			me.sum_consumo3 = respuesta.sum_consumo3;
			me.SumaDiaAnterior = respuesta.SumaDiaAnterior;
			me.SumaMesAnterior = respuesta.SumaMesAnterior;
			me.SumaAntioAnterior = respuesta.SumaAntioAnterior;
			me.SumaDiaHoy = respuesta.SumaDiaHoy;
			me.SumaMesHoy = respuesta.SumaMesHoy;
			me.SumaAnioHoy = respuesta.SumaAnioHoy;
			me.ArrayDatos6 = respuesta.tipo_pagos;



				me.totalDia = parseFloat(me.ArrayDatos3.totalDia);
				me.totalMes = parseFloat(me.ArrayDatos3.totalMes);
				me.totalAnio = parseFloat(me.ArrayDatos3.totalAnio);

				me.ArrayDatos6.map(function(dato, key) {

				dato.total_dia_tipo = (dato.total_dia_tipo == null) ? 0.0 : parseFloat(dato.total_dia_tipo);
				dato.total_mes_tipo = (dato.total_mes_tipo == null) ? 0.0 : parseFloat(dato.total_mes_tipo);
				dato.total_anio_tipo = (dato.total_anio_tipo == null) ? 0.0 : parseFloat(dato.total_anio_tipo);
				return dato;
			});
				me.ArrayDatos.map(function(dato, key) {

				dato.total_dia = (dato.total_dia == null) ? 0 : dato.total_dia;
				dato.total_mes = (dato.total_mes == null) ? 0 : dato.total_mes;
				dato.total_anio = (dato.total_anio == null) ? 0 : dato.total_anio;
				return dato;
			});
			me.suma_total_dia = 0.0;
			me.suma_total_mes = 0.0;
			me.suma_total_anio = 0.0;
			for (var i = 0; i < me.ArrayDatos.length; i++) {
				// console.log(me.ArrayDatos[i].total_dia)
				me.suma_total_dia+=parseFloat(me.ArrayDatos[i].total_dia);
				me.suma_total_mes+=parseFloat(me.ArrayDatos[i].total_mes);
				me.suma_total_anio+=parseFloat(me.ArrayDatos[i].total_anio);
			}
			// console.log(me.suma_total_dia)
			me.suma_total_venta_dia = 0.0;
			me.suma_total_venta_mes = 0.0;
			me.suma_total_venta_anio = 0.0;
			me.ArrayDatos2.map(function(dato, key) {
			for (var i = 0; i < dato.puntos_de_venta.length; i++) {
				me.suma_total_venta_dia+=parseFloat(dato.puntos_de_venta[i].venta_dia);
				me.suma_total_venta_mes+=parseFloat(dato.puntos_de_venta[i].venta_mes);
				me.suma_total_venta_anio+=parseFloat(dato.puntos_de_venta[i].venta_anio);
			}

			});
			me.totalReporteDia = parseFloat(me.suma_total_dia) + parseFloat(me.suma_total_venta_dia) + parseFloat(me.ArrayDatos3.totalDia);
			me.totalReporteMes = parseFloat(me.suma_total_mes) + parseFloat(me.suma_total_venta_mes) + parseFloat(me.ArrayDatos3.totalMes);
			me.totalReporteAnio = parseFloat(me.suma_total_anio) + parseFloat(me.suma_total_venta_anio) + parseFloat(me.ArrayDatos3.totalAnio);
				me.ArrayDatos4.map(function(dato, key) {
				dato.total_dia = (me.suma_total_dia * dato.valor)/100;
				dato.total_mes = (me.suma_total_mes * dato.valor)/100;
				dato.total_anio = (me.suma_total_anio * dato.valor)/100;
				return dato;
			});
			console.log(me.ArrayDatos4)
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
						this.selectCajeros();
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

			.vdp-datepicker {

    width: 225px !important;
}
		</style>
