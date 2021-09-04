<template>
	<main class="main" >
		<div class="col-12">
			<div class="content-header">Turnos</div>
		</div>
		<section id="extended">
			<div class="row">
				<div class="col-sm-6">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title"></h4>
							<button class="btn btn-raised gradient-mint white shadow-z-4" @click="Nuevo()">
							<span class="btn-icon"><i class="la la-user-plus"></i>Nuevo Turno</span>
							</button>
							<div class="input-group">
								<select class="form-control col-md-3" v-model="criterio">
									<option value="nombre">Nombre</option>
								</select>
								<input type="text" v-model="buscar" @keyup.enter="ListarItems(1,buscar)" class="form-control" placeholder="Texto a buscar">
								<button class="btn btn-raised gradient-pomegranate white big-shadow" type="submit" @click="ListarItems(1,buscar)">
								<span class="btn-icon"><i class="la la-search"></i>Buscar</span>
								</button>
							</div>
						</div>
						<div class="card-body">
							<div class="card-block">
								<div id="scroll">
									<div class="list-group" v-for="turnos in arrayTurnos" :key="turnos.id">
										<span class="list-group-item" :class="[turnos.id == selected ? 'active':'']" @click="VerDetalle(turnos.id)">
											<span class="title-list">{{turnos.nombre}}</span>
											<h5>{{turnos.nombre}}</h5>
											<span class="title-sub">
											Hora inicio:{{turnos.hora_inicio}}
											-
											Hora fin:{{turnos.hora_fin}}
											</span>
										</span>
									</div>
									<ul class="pagination">
                    <li v-for="(pagina, index) in Paginas" :key="pagina.id" :class="((index +1) ==pag_actual)?'page-item active':'page-item'">
                      <i @click="ListarItems(((index +1)),buscar)" class="page-link">{{(index +1)}}</i>
                    </li>
                  </ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="card">
						<div class="card-header">
							<h5 class="card-title">Turno</h5>
							<div class="container-fluid">
								<form  id="registerForm" method="POST" @submit.prevent="guardarCambios">
									<div class="col-sm-12">
										<label for="">Nombre</label>
										<input type="text" class="form form-control" v-on:keyup="validar()"  v-model="nombre"  >
										<span v-if="nombre==''" class="label label-danger">Requerido</span>										
									</div>
									<div class="col-sm-6">
										<label for="">Inicio</label>
										<input type="text" class="form form-control" id="hora_fin" v-on:keyup="validar()"  v-model="hora_fin"  >									
										<span v-if="hora_fin==''" class="label label-danger">Requerido</span>		
									</div>
									<div class="col-sm-6">
										<label for="">Fin</label>
										<input type="text" class="form form-control" id="hora_inicio" v-on:keyup="validar()"  v-model="hora_inicio"  >									
										<span v-if="hora_inicio==''" class="label label-danger">Requerido</span>		
									</div>
									<div class="col-sm-12">
										<br>
										<button :disabled="disablebutton" v-if="tipoGuardar==1 && SivalidaForm" @click="GuardarEditar()" type="button" class="btn btn-raised gradient-ibiza-sunset white sidebar-shadow"><i class="fa fa-pencil" aria-hidden="true"></i> Actualizar Turno</button>
										<button :disabled="disablebutton" v-if="tipoGuardar==2 && SivalidaForm" @click="GuardarNuevo()" type="button" class="btn btn-raised gradient-nepal white card-shadow"><i class="fa fa-plus" aria-hidden="true"></i> Guardar Turno</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</main>
</template>
<script>
	export default {
	  data() {
	    return {
				base:'',
	      selected: null,
	      tipoGuardar: 2,
	      disablebutton: false,
	//===============================basicos===============================//      
	      id: null,
	      nombre: null,
	      hora_inicio: null,
	      hora_fin: null,
				arrayTurnos: [],
				SivalidaForm:false,
	//===============================basicos===============================//
				pag_actual:0,
				buscar:'',
	      criterio: "nombre",
      	Paginas:0
	    };
	  },
	  methods: {
			//=======================================Principales=======================================//
			validar()
			{
				let me = this;
				var required=3;
				required=(me.hora_inicio=='')?required+1:required-1;
				required=(me.hora_fin=='')?required+1:required-1;
				required=(me.nombre=='')?required+1:required-1;
				me.SivalidaForm=(required==0)?true:false;
			},
	    VerDetalle(id) {
	      let me = this;
	      me.selected = id;
	      var data = me.arrayTurnos.find(
	        Turnos => Turnos.id === id
	      );
	      me.id = id;
	      me.hora_inicio= data.hora_inicio,
	      me.hora_fin= data.hora_fin,
	      me.nombre= data.nombre
	      me.tipoGuardar = 1;
	    },
	    ListarItems(page,filtro) {
				let me = this;
				me.pag_actual=page;
	      me.arrayTurnos = [];
	      axios
	        .get(me.base+"/turnos/index?criterio="+me.criterio+"&page="+page+"&filtro=" + filtro)
	        .then(function(response) {
	          var respuesta = response.data;
						me.arrayTurnos = respuesta.turno;
						me.Paginas=respuesta.pages;
	        })
	        .catch(function(error, otracosa) {});
	    },
	    Nuevo() {
				let me = this;
	      me.tipoGuardar = 2;
	      me.id = null;
				me.nombre=null;
				me.hora_inicio=null;
				me.hora_fin=null;
				
	    },
	    GuardarNuevo() {
	      let me = this;
	      me.disablebutton = true;
	      axios
	        .post(me.base+"/turnos/Nueva", {
	          hora_inicio: me.hora_inicio,
	          hora_fin: me.hora_fin,
	          nombre: me.nombre
	        })
	        .then(function(response) {
	            me.disablebutton = false;
	          if (response.data.validate) {
							me.tipoGuardar=1;
	            me.$swal("se ha guardado el turno " + me.nombre + " con éxito");
	            me.ListarItems(1,'');
	            me.VerDetalle(response.data.id);
	          } else {
	            me.$swal("se ha ha presentado un error al guardar");
	          }
	        })
	        .catch(function(error) {console.log(error);me.disablebutton = false;});
	    },
	    GuardarEditar() {
	      let me = this;
				me.disablebutton = true;
				var data={
						id: 					me.id,
						hora_inicio: 	me.hora_inicio,
	          fin: 					me.hora_fin,
	          nombre:				me.nombre
					};
					console.log(data);
	      axios
	        .post(me.base+"/turnos/Editar", data)
	        .then(function(response) {
	          me.disablebutton = false;
	          if (response.data.validate) {
	            me.$swal("se ha editado el turno " + me.nombre + " con éxito");
	            me.ListarItems(1,'');
	            me.VerDetalle(me.id);
	          } else {
	            me.$swal(
	              "Se presento un problema al editar habitacion " + me.nombre + "."
	            );
	          }
	        })
	        .catch(function(error) {
	          me.disablebutton = false;
	          console.log(error);
	        });
			},
			time_clockpicker()
			{
				let me = this;
				$('#hora_inicio').clockpicker({
							donetext: 'Guardar',
							placement: 'bottom',
							align: 'left',
							autoclose: true,
							twelvehour: true,
							vibrate: true,
							afterDone: function(data) 
							{
								me.hora_inicio=$('#hora_inicio').val();
              }
				});
				$('#hora_fin').clockpicker({
							donetext: 'Guardar',
							placement: 'bottom',
							align: 'left',
							autoclose: true,
							twelvehour: true,
							vibrate: true,
							afterDone: function(data) 
							{
								me.hora_fin=$('#hora_fin').val();
              }
					});
			}
			
	    //=======================================Principales=======================================//
	  },
		mounted() 
		{
			this.base=base;
			this.time_clockpicker();
	    this.ListarItems(1,'');
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
</style>