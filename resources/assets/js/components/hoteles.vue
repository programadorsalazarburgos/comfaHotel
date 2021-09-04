<template>
	<main class="main" >
		<div class="col-12">
			<div class="content-header">Hoteles</div>
		</div>
		<section id="extended">
			<div class="row">
				<div class="col-sm-6">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title"></h4>
							<button class="btn btn-raised gradient-mint white shadow-z-4" @click="NuevoHotel()">
							<span class="btn-icon"><i class="la la-user-plus"></i>Nuevo hotel</span>
							</button>
							<div class="input-group">
								<select class="form-control col-md-3" v-model="criterio">
									<option value="nombre">Hotel</option>
									<option value="responsable_nombre">Responsable</option>
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
									<div class="list-group" v-for="Hoteles in arrayHoteles" :key="Hoteles.id">
										<span class="list-group-item" :class="[Hoteles.id == selected ? 'active':'']" @click="verHoteles(Hoteles)">
											<span class="title-list">{{Hoteles.nombre}}</span>
											<h5></h5>
											<span class="title-sub">responsable:{{Hoteles.responsable_nombre}}</span>
										</span>
									</div>
								</div>
								<ul class="pagination">
									<li v-for="(pagina, index) in Paginas" :key="pagina" :class="((index +1) ==pag_actual)?'page-item active':'page-item'">
										<i @click="ListarItems(((index +1)),buscar)" class="page-link">{{(index +1)}}</i>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="card">
						<div class="card-header">
							<h5 class="card-title">Hoteles</h5>
							<div class="container-fluid">
								<form  @submit.prevent="handleSubmit">
									<div class="col-sm-12">
                    <label for="">Usuario</label>
                    <input  v-validate="'required'" type="text" v-model="usuario" name="usuario" class="form form-control">
                    <ul class="list-group">
                      <li class="list-group-item list-group-item-danger" v-for="error in errors.collect('usuario')" :key="error">{{ error }}</li>
                    </ul>
                    </div>
									<div class="col-sm-12">
                    <label for="">Hotel</label>
                    <input  v-validate="'required'" type="text" v-model="hotel_nombre" name="hotel nombre" class="form form-control">
                    <ul class="list-group">
                      <li class="list-group-item list-group-item-danger" v-for="error in errors.collect('hotel nombre')" :key="error">{{ error }}</li>
                    </ul>
                    </div>
									<div class="col-sm-12">
										<label for="">NIT</label>
										<input v-validate="'required'" type="text" v-model="hotel_nit" name="NIT" :class="'form form-control'">
                    <ul class="list-group">
                      <li class="list-group-item list-group-item-danger" v-for="error in errors.collect('NIT')" :key="error">{{ error }}</li>
                    </ul>
									</div>
									<div class="col-sm-12">
										<label for="">Direccion</label>
										<input  v-validate="'required'" type="text" v-model="hotel_direccion"  name="direccion" class="form form-control">
                    <ul class="list-group">
                      <li class="list-group-item list-group-item-danger" v-for="error in errors.collect('direccion')" :key="error">{{ error }}</li>
                    </ul>
									</div>
									<div class="col-sm-12">
										<label for="">Nombre del responsable</label>
										<input  v-validate="'required'" type="text" v-model="hotel_responsable_nombre" name="nombre del responsable" class="form form-control">
                    <ul class="list-group">
                      <li class="list-group-item list-group-item-danger" v-for="error in errors.collect('nombre del responsable')" :key="error">{{ error }}</li>
                    </ul>
									</div>
									<div class="col-sm-12">
										<label for="">Telefono del reponsable</label>
										<input  v-validate="'required'" type="text" v-model="hotel_responsable_telefono" name="Telefono" class="form form-control">
                    <ul class="list-group">
                      <li class="list-group-item list-group-item-danger" v-for="error in errors.collect('Telefono')" :key="error">{{ error }}</li>
                    </ul>
									</div>
									<div class="col-sm-12">
										<label for="">Usuario ORBE</label>
										<input type="text"  v-validate="'required'" v-model="api_user" class="form form-control" name="Usuario orbe">
                    <ul class="list-group">
                      <li class="list-group-item list-group-item-danger" v-for="error in errors.collect('Usuario orbe')" :key="error">{{ error }}</li>
                    </ul>
									</div>
									<div class="col-sm-12">
										<label for="">Password ORBE</label>
										<input type="text"  v-validate="'required'" v-model="api_password" class="form form-control" name="password">
                    <ul class="list-group">
                      <li class="list-group-item list-group-item-danger" v-for="error in errors.collect('password')" :key="error">{{ error }}</li>
                    </ul>
									</div>
									<div class="col-sm-12">
										<label for="">Code ORBE</label>
										<input type="text" v-validate="'required'"  v-model="api_code" class="form form-control" name="code">
                    <ul class="list-group">
                      <li class="list-group-item list-group-item-danger" v-for="error in errors.collect('code')" :key="error">{{ error }}</li>
                    </ul>
									</div>
									<div class="col-sm-12">
										<label for="">Zona Horaria</label>
										<v-select :options="arrayZonas" :custom-label="nameWithLang" placeholder="Seleccionar:" label="name" track-by="name" v-model="zona">
											<template #search="{attributes, events}">
												<input class="vs__search" :required="!zona" v-bind="attributes" v-on="events" />
											</template>
										</v-select>
									</div>
									<div class="col-sm-12">
										<label for="">Hora Cuenta</label>
										<vue-clock-picker v-model="value"></vue-clock-picker>
									</div>
									<div class="col-sm-12">
										<br>
										<button :disabled="disablebutton" v-if="tipoGuardar==1" @click="GuardarEditarHotel()" type="button" class="btn btn-raised gradient-ibiza-sunset white sidebar-shadow"><i class="fa fa-pencil" aria-hidden="true"></i> Actualizar hotel</button>
										<button :disabled="disablebutton" v-if="tipoGuardar==2" @click="GuardarNuevoHotel()" type="button" class="btn btn-raised gradient-nepal white card-shadow"><i class="fa fa-plus" aria-hidden="true"></i> Guardar Hotel</button>
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
	import DatePicker from 'vue2-datepicker';
	import VueSweetAlert from 'vue-sweetalert';
	import VueClockPicker from '@pencilpix/vue2-clock-picker';
	import '@pencilpix/vue2-clock-picker/dist/vue2-clock-picker.min.css';
	import Vue from 'vue';
	import moment from 'moment'
	import Autocomplete from 'vuejs-auto-complete';
	import vSelect from "vue-select";
	Vue.component("v-select", vSelect);
	import "vue-select/dist/vue-select.css";
	import '@pencilpix/vue2-clock-picker/dist/vue2-clock-picker.min.css';
	const VueClockPickerPlugin = require('@pencilpix/vue2-clock-picker/dist/vue2-clock-picker.plugin.js')
	Vue.use(VueClockPickerPlugin)

	export default {
	  data() {
	    return {
				base:'',
				value:'',
	      //==============================DATEPICKER==============================//
	      format:'DD-MM-YYYY',
	      lang: {
	        days: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
	        months: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
	        pickers: ['Siguiente 7 dias', 'Siguiente 30 dias', 'Anteriores 7 dias', 'Anteriores 30 dias'],
	        placeholder: {
	          date: 'Seleccione fecha',
	          dateRange: 'Seleccione rango de fecha'
	        }
	      },
	      //==============================DATEPICKER==============================//
	      selected: null,
	      tipoGuardar: 2,
	      disablebutton:false,
	      //===========================================================================//
	      id:'',
	      usuario:'',
	      schema:'',
	      hotel_nombre:'',
	      hotel_fecha_inicio:'',
	      hotel_fecha_fin:'',
	      hotel_nit:'',
	      hotel_direccion:'',
	      hotel_responsable_nombre:'',
	      hotel_responsable_telefono:'',
	      hotel_comentario:'',
	      password:'',
	      zona:'',
	      api_user:'',
	      api_password:'',
	      api_code:'',
	      arrayHoteles:[],
	      arrayZonas:[],
	      //===========================================================================//
					buscar:'',
		      criterio: "nombre",
	        Paginas:0,
	        pag_actual:0,
	        submitted: false
	    };
	  },
		components: {
		 VueClockPicker,
		 Autocomplete
 		},
	  computed: {
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

	      var to = from + this.offset * 2;
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
	  methods: {
	    handleSubmit(e) {},
	    verHoteles(data)
	    {
				console.log(data);
				let me = this;
				me.tipoGuardar=1;
				me.selected 									= data.id;
	      me.api_code										=	data.api_code;
	      me.api_password								=	data.api_password;
	      me.api_user										=	data.api_user;
	      me.hotel_comentario						=	data.comentario;
	      me.hotel_direccion						=	data.direccion;
	      me.hotel_fecha_inicio					=	data.fecha_inicio;
	      me.hotel_fecha_fin						=	data.fecha_fin;
				me.id													=	data.id;
	      me.hotel_nit									=	data.nit;
	      me.hotel_nombre								=	data.nombre;
	      me.hotel_responsable_nombre		=	data.responsable_nombre;
	      me.hotel_responsable_telefono	=	data.responsable_telefono;
	      me.usuario										=	data.schema;
	      me.value									  	=	data.hora_cuenta;
				var vm = new Vue({
				data: {
					code: 1,
					name: data.zona_horaria
					}
				})
				this.zona = vm._data;
	    },
	    ListarItems(page,filtro) {
	      let me = this;
	      me.pag_actual=page;
	      me.arrayHoteles = [];
	      axios
	        .get(me.base+"/Hoteles/index?criterio="+me.criterio+"&page="+page+"&filtro=" + filtro)
	        .then(function(response)
	        {
	          var respuesta   = response.data;
	          me.arrayHoteles = respuesta.hoteles;
	          me.Paginas      = respuesta.pages;
	        })
	        .catch(function(error, otracosa) {});
	    },
	    NuevoHotel() {
	      let me = this;
				me.selected=0;
	      me.tipoGuardar = 2;
				me.id 												= '';
	      me.api_code										=	'';
	      me.api_password								=	'';
	      me.api_user										=	'';
	      me.hotel_comentario						=	'';
	      me.hotel_direccion						=	'';
	      me.hotel_fecha_inicio					=	'';
	      me.hotel_fecha_fin						=	'';
	      me.hotel_nit									=	'';
	      me.hotel_nombre								=	'';
	      me.hotel_responsable_nombre		=	'';
	      me.hotel_responsable_telefono	=	'';
	      me.usuario										=	'';

	    },
	    GuardarNuevoHotel() {

	       this.submitted = true;
		      this.$validator.validate().then(valid =>
		      {
						let me = this;
		        if (valid)
		        {
	      me.tipoGuardar = 1;
	      me.disablebutton = true;
	      axios
	        .post(me.base+"/Hoteles/Nueva", {
	          usuario: me.usuario ,
	          schema: me.schema ,
	          hotel_nombre: me.hotel_nombre ,
	          hotel_fecha_inicio: me.hotel_fecha_inicio ,
	          hotel_fecha_fin: me.hotel_fecha_fin ,
	          hotel_nit: me.hotel_nit ,
	          hotel_direccion: me.hotel_direccion ,
	          hotel_responsable_nombre: me.hotel_responsable_nombre ,
	          hotel_responsable_telefono: me.hotel_responsable_telefono ,
	          hotel_comentario: me.hotel_comentario ,
	          password: me.password ,
	          api_user: me.api_user ,
	          api_password: me.api_password ,
	          api_code: me.api_code,
	          api_code: me.api_code,
						hora_cuenta: me.value,
						zona_horaria: me.zona.name
	        })
	        .then(function(response)
	        {
	          if (response.data.validate)
	          {
	            me.disablebutton = false;
	            me.id = response.data.id;
	            me.$swal("se ha guardado el hotel " + me.hotel_nombre  + " con éxito");
	            me.ListarItems(1,'');
	            me.verHoteles(response.data);
	          } else {
	            me.disablebutton = false;
	            me.tipoGuardar = 2;
							me.$swal("No se ha podido editar el hotel");
	          }
	        }).catch(function(error) {
	          me.$swal("Error " + me.hotel_nombre  + " con éxito");
	          me.tipoGuardar = 2;
	          me.disablebutton = false;
	        });
	          }
		        else
		        {
		          me.$swal("Por favor valide todos los campos");
		        }
		      });
	    },
			convert(str) {
					var date = new Date(str),
							mnth = ("0" + (date.getMonth() + 1)).slice(-2),
							day = ("0" + date.getDate()).slice(-2);
					return [date.getFullYear(), mnth, day].join("-");
			},
			nameWithLang({
				name,
				code
			}) {
				return `${name}`
			},

			seleccionarZonas() {
				let me = this;
				var url = 'obtener/zonas';
				axios.get(me.base + url).then(function(response) {
						me.arrayZonas = response.data.datos
						console.log(me.arrayZonas);
					})
					.catch(function(error) {
						var response = error.response.data;
					});
			},

	    GuardarEditarHotel() {
				let me = this;
	      this.submitted = true;
		      this.$validator.validate().then(valid =>
		      {
		        if (valid)
		        {
	      let me = this;
	      me.disablebutton = true;
	      axios
	        .post(me.base+"/Hoteles/Editar", {
						id: me.id,
						usuario: me.usuario ,
	          schema: me.schema ,
	          hotel_nombre: me.hotel_nombre ,
	          hotel_fecha_inicio: me.hotel_fecha_inicio ,
	          hotel_fecha_fin: me.hotel_fecha_fin ,
	          hotel_nit: me.hotel_nit ,
	          hotel_direccion: me.hotel_direccion ,
	          hotel_responsable_nombre: me.hotel_responsable_nombre ,
	          hotel_responsable_telefono: me.hotel_responsable_telefono ,
	          hotel_comentario: me.hotel_comentario ,
	          password: me.password ,
	          api_user: me.api_user ,
	          api_password: me.api_password ,
	          api_code: me.api_code,
						hora_cuenta:me.value
	        })
	        .then(function(response) {
	          if (response.data.validate) {
	            me.$swal("se ha editado el hotel con éxito");
							me.ListarItems(1,'');
							me.selected=response.data.data.id;
							me.verHoteles(response.data.data);
	          } else {
	            me.$swal("Se presento un problema al editar el hotel");
	          }
	          me.disablebutton = false;
	        })
	        .catch(function(error) {
	          me.disablebutton = false;
	        });
	    }
		        else
		        {
	            me.$swal("Por favor valide todos los campos");
		        }
		      });
	      }
	  },
	  mounted() {
			this.base=base;
	    this.ListarItems(1,'');
	    this.seleccionarZonas();
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
	.mx-datepicker {
	width: 100% !important;
	}
</style>
