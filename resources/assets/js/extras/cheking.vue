<template>
    <div>
           <div class="col-sm-12">
			<div class="content-header">Hacer check-In</div>
		</div>
		<section id="extendeds">
			<div class="row">
				<div class="col-sm-12">
					<div class="card">
						<div class="card-header">
							<div class="row">
                <div v-if="tipoCarga==1">
                  <div class="loader loader-default is-active" data-text></div>
                </div>

								<div class="col-md-12" v-if="chekin==1">
									<h5>
										<span class="badge badge-info">DATOS GENERALES</span>
										<span class="badge badge-success">CLIENTE: {{nombre_cliente}}  <i class="fa fa-user-circle"></i></span>

									</h5>
									<div class="clearfix"></div><br>
									<div class="row" style="display:none;">
										<div class="col-sm-6">
											<label>llegada</label>
											<input type="text" v-model="reservas.llegada" style="display:none;" class="form form-controll">
										</div>
										<div class="col-sm-6">
											<label>Salida</label>
											<input type="text" v-model="reservas.salida" style="display:none;" class="form form-controll">
										</div>
									</div>
									<div class="clearfix"></div><br>
									<div class="row">
										<div class="col-sm-12">
											<h5><span class="badge badge-primary">Habitaciones Elegidas</span></h5>
										</div>
										<div class="col-md-12">
											<table class="table">
                        <thead class="thead-default">
                           <tr>
                              <th>#</th>
                              <th>Habitación</th>
                              <th>Numero Huspedes</th>
                              <th>Fecha Ingreso</th>
                              <th>Fecha Salida</th>

                           </tr>
                        </thead>
												<tbody>
													<tr v-for="(grupo,index) in reservas.habitaciones" :key="index">
														<td>
															{{index+1}}
														</td>
														<td>
															<i class="fa fa-trash" @click="eliminar(index)"></i>  {{grupo.habitacion_numero}}
															{{grupo.habitacion_tipo}}
														</td>
														<td>
                              {{grupo.cantidad_huespedes}} Huespedes
															<i class="fas fa-male"></i>
															<span v-if="grupo.habitacion_personas_maximo<(grupo.cantidad_infantes+grupo.cantidad_ninos+grupo.cantidad_adultos)" class="label label-danger">Maximo {{grupo.habitacion_personas_maximo}} personas - [{{grupo.cantidad_huespedes}}]</span>
														</td>
                            <td><span class="badge badge-danger">{{grupo.fecha_ingreso}}</span></td>
                            <td><span class="badge badge-info" style="background-image: linear-gradient(45deg, rgb(202, 216, 83), rgb(255, 160, 0));">{{grupo.fecha_salida}}</span></td>
													</tr>
												</tbody>
											</table>
										</div>
										<div class="col-md-12">
											<button @click="guardar_ckeckin()" class="btn btn-raised gradient-pomegranate white big-shadow">Confirmar Check-In</button>
										</div>
									</div>
								</div>

							</div>
							<div class="row">
								<div class="col-md-12">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
        			<!--Nuevo cliente-->

    </div>

</template>
<script>
import VueSweetalert2 from 'vue-sweetalert2';
import Autocomplete from 'vuejs-auto-complete';
import crearcliente from '../components/InsertCliente';

export default {
    data()
    {
        return {
            guardar:true,
            modalCliente: false,
            chekin:true,
            base:'',
            tipoCarga : 0,
			tipoAccion:1,
			arrayGrupos: [],
            reservas: {
				llegada: '',
				salida: '',
				habitaciones: '' //habitaciones:{id_grupo,infantes_cantidad,ninnos_cantidad,adultos_cantidad}
			},

     		adultos: [
			    {id: 1, nombre: '1'},
			    {id: 2, nombre: '2'},
			    {id: 3, nombre: '3'},
			    {id: 4, nombre: '4'},
			    {id: 5, nombre: '5'},
			    {id: 6, nombre: '6'},
			    {id: 7, nombre: '7'},
			    {id: 8, nombre: '8'},
		 	],
		 	nombre_cliente:'',
		 	mostrar_checking: '',
			mostrar_estados: '',
			mostrar_busqueda: '',



        }
    },
    props:['data'],
    components: {
        Autocomplete,
        crearcliente

	},
    methods:{


        validar(id_reservas)
		{
			let me = this;
			return axios
				.post(me.base+"reservas/ReservasCompletas", {
					id_reservas: id_reservas
				})
				.then(function (response) {
					var data = response.data;
					return data.validate;
				})
				.catch(function (error, otracosa) {
					return false
				});
        },
        recargar() {
			let me = this;
			me.$router.go(me.$router.currentRoute)
		},

		mostrarWinLoading :function(sMensaje){
			Swal.fire({
				type: 'info',
                title: 'Por Favor Espere !',
                html: "Cargando Información",
				showConfirmButton: false,
                allowOutsideClick: false,
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
            });
		}, 
        async hacerChecking()
        {

			let me = this;
			me.mostrarWinLoading();
			var url = 'reservas/data_grupos?reserva_id=' + me.data.id_reservas;
			axios.get(me.base+url).then(function (response)
			{
				swal.close();
				me.arrayGrupos = response.data;
				if (me.data.id_estado_reserva == 1)
			{
				if (me.validar(me.data.id_reservas))
				{
					var grupos = [];
					$.each(me.arrayGrupos, function (index, value)
					{

						if (value.id_reservas_estado == 1)
						{
							grupos.push(value);
						}
					});

					me.reservas.llegada = me.data.check_in_fecha;
					me.reservas.salida = me.data.check_out_fecha;
					me.reservas.habitaciones = grupos;
				} else
				{
					alert('Aun no a asignado todas las habitaciones de esta reserva');
				}
			}
			else
			{
				alert('Ya realizó el check in');
			}


			})
			.catch(function (error, otracosa)
			{
				// var response = error.response.data;
			});
        },
        borrar_huesped(detail, index, index2) {
			let me = this;
			(me.reservas.habitaciones[index].huespedes_data).splice(index2, 1)
			me.$forceUpdate();
        },
        clienteseleccionado(data){
			let me = this;
			var index = parseInt(data.selectedObject.id_index1);
			var index2 = parseInt(data.selectedObject.id_index2);
			me.reservas.habitaciones[index].huespedes_data[index2].id_huesped = data.selectedObject.id
			me.reservas.habitaciones[index].huespedes_data[index2].nombre_huesped = data.selectedObject.name;
		},
        eliminar(index)
        {
			let me = this;
			me.reservas.habitaciones.splice(index, 1);
        },
        validar_checkin(data)
        {
			var validate_name = true;
			var validate_principal = false;
			var validate_pagador = false;
			$.each(data, function (index, value) {
				validate_principal = false;
				$.each(value.huespedes_data, function (i, v) {
					validate_principal = (v.id_huesped_principal) ? true : validate_principal;
					validate_pagador = (v.id_pagador) ? true : validate_pagador;
					//validate_name = ($.trim(v.id_huesped)=='')?false:validate_name;
				});
			})
			return ((validate_principal && validate_name && validate_pagador));
		},
        guardar_ckeckin() {
			let me = this;
      me.tipoAccion = 0;
      me.tipoCarga = 1;
				axios
					.post(me.base+"/reservas/GuardarChekin", me.reservas.habitaciones)
					.then(function (response) {
            me.tipoCarga = 0;
						alert("Se ha guardado con éxito");
						me.guardar = false;
						me.mostrar_reservas = true;
						// me.datosHabitaciones()jsb
						me.$router.go(me.$router.currentRoute)
						me.mostrar_checking = false;
						me.mostrar_estados = true;
						me.mostrar_busqueda = true;

					})
					.catch(function (error, otracosa) {

					});

		},
        generar_huespedes() {

				let me = this;
				var i = 0;
				var total = 0;
				me.chekin = 2;
				$.each(me.reservas.habitaciones, function (index, value) {
					total = me.reservas.habitaciones[index].cantidad_huespedes;

					var temp = new Array();
					for (i = 0; i < total; i++) {
						temp.push({
							id_huesped: null,
							nombre_huesped: '',
							id_huesped_principal: false,
							id_pagador: false,
							grupo_id: me.reservas.habitaciones[index].id_grupo,
						});
					}
					me.reservas.habitaciones[index].huespedes_data = temp;
				});
			},

		nombreCLiente()
		{

			let me = this;
			var url = 'reservas/nombre_reservante?reserva_id=' + me.data.id_reservas;
			axios.get(me.base+url).then(function (response)
			{
				var respuesta = response.data.nombre_reserva;
				me.nombre_cliente = respuesta.cliente_nombre;

			})
			.catch(function (error, otracosa)
			{
				// var response = error.response.data;
			});


		},

    },
    mounted()
	{
	   this.base = base;
	   this.hacerChecking();
	   this.nombreCLiente();
	   //this.datos();
	   console.log(this.data, 5);

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
