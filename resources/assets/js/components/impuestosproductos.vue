<template>
	<main class="main" >
		<div class="col-12">
			<div class="content-header">{{count["impuesto_producto"]}}</div>
		</div>
		<section id="extended">
			<div class="row">
				<div class="col-sm-8">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title"></h4>
							<button class="btn btn-raised gradient-mint white shadow-z-4" @click="NuevaImpuesto()">
							<span class="btn-icon"><i class="la la-user-plus"></i>{{count["nuevo"]}}</span>
							</button>
						</div>
						<div class="card-body">
							<div class="card-body">
                    <div class="card-block">
                        <table class="table table-responsive-md-md text-center">
                            <thead>
                                <tr>

                                    <th>{{count["impuesto_producto"]}}</th>
                                    <th>{{count["valor"]}}</th>
                                    <th>{{count["iva"]}}</th>
                                    <th>{{count["servicio"]}}</th>
                                    <th>{{count["accion	"]}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="impuestos in ArrayImpuestos" :key="impuestos.id">
                                    <td v-text="impuestos.nombre"></td>
                                    <td v-text="impuestos.valor + '%'"></td>
                                    <td>
                                    	<template v-if="impuestos.iva == true">
                                    		<span class="badge badge-danger">{{count["iva"]}}</span>
                                    	</template>
                                    </td>
                                    <td>
                                    	<template v-if="impuestos.servicio == true">
                                    		<span class="badge badge-success">{{count["servicio"]}}</span>
                                    	</template>
                                    </td>

                                    <td>
                                         <a class="info p-0" data-original-title="" title="" @click="IvaImpuesto(impuestos)">
                                            <i class="ft-check-square font-medium-3 mr-2"></i>
                                        </a>

 										<a class="info p-0" data-original-title="" title="" @click="ServicioImpuesto(impuestos)">
                                            <i class="fas fa-anchor font-medium-3 mr-2"></i>
                                        </a>
                                        <a class="success p-0" data-original-title="" title="" @click="VerImpuesto(impuestos)">
                                            <i class="ft-edit-2 font-medium-3 mr-2"></i>
                                        </a>

                                    </td>
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
				<div class="col-sm-4">
					<div class="card">
						<div class="card-header">
							<h5 class="card-title">{{count["impuestos"]}}</h5>
							<div class="container-fluid">
								<form  @submit.prevent="handleSubmit">
									<div class="col-sm-12">
										<label for="">{{count["nombre"]}}</label>
										<input type="text" v-validate="'required'" class="form form-control" v-model="impuestoNombre" :class="{ 'is-invalid': submitted && errors.has('password') }"/>
										<div v-if="submitted && errors.has('impuestoNombre')" class="invalid-feedback">{{ errors.first('impuestoNombre') }}</div>
									</div>
									<div class="col-sm-12">
										<label for="">{{count["valor"]}} (%)</label>
										<input type="number" max="100" min="0" v-validate="'required'" class="form form-control" v-model="impuestoValor" :class="{ 'is-invalid': submitted && errors.has('password') }"/>
										<div v-if="submitted && errors.has('impuestoValor')" class="invalid-feedback">{{ errors.first('impuestoValor') }}</div>
									</div>
									<br>
									<div class="col-sm-12">
										<button :disabled="disablebutton" v-if="tipoGuardar==1" @click="GuardarEditarImpuestos()" class="btn btn-raised gradient-ibiza-sunset white sidebar-shadow"> {{count["actualizar"]}}</button>
										<button :disabled="disablebutton" v-if="tipoGuardar==1" @click="BorrarImpuestos()" class="btn btn-danger"> {{count["borrar"]}}</button>
										<button :disabled="disablebutton" v-if="tipoGuardar==2" @click="GuardarNuevoImpuestos()" class="btn btn-raised gradient-nepal white card-shadow"><i class="fa fa-plus" aria-hidden="true"></i> {{count["guardar"]}}</button>
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
	import VueSweetAlert from 'vue-sweetalert';
	export default {
	  data() {
	    return {
				base:'',
	      ArrayImpuestos:[],
	      impuestoNombre:'',
	      impuestoValor:0,
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
          impuesto_id:''
	    }
	  },
	   computed:{
			 count () {
				this.$store.state;
				return this.$store.state.posts
						},
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
	  methods: {
	    handleSubmit(e) {},

	       IvaImpuesto(data)
	    {
	        let me= this;
	        me.impuesto_id=data.id;

 			me.$swal(
	        {
	            title: "Principal",
	            text: "¿Desea que este impuesto sea el iva?",
	            type: "warning",
	            showCancelButton: true,
	            confirmButtonColor: "#DD6B55",
	            confirmButtonText: "Si, Asignar"
	        }).then(function(isConfirm)
	        {
	            axios
	            .post(me.base+"/impuestos_productos/principal",{impuesto_id:me.impuesto_id})
	            .then(function(response)
	            {

	            	console.log(response.data.validate)


	                if (isConfirm)
	                {
	                    me.$swal
	                    ({
	                        title: 'Iva',
	                        text: 'Este impuesto quedo como iva',
	                        icon: 'success'
	                    }).
	                    then(function()
	                    {
	                        me.ListarImpuestos();
	                    });
	                }
	            })
	            .catch(function(error, otracosa) {});
	             });



	    },


  ServicioImpuesto(data)
	    {
	        let me= this;
	        me.impuesto_id=data.id;

 			me.$swal(
	        {
	            title: "Impuesto Servicio",
	            text: "¿Desea que este impuesto sea el Servicio?",
	            type: "warning",
	            showCancelButton: true,
	            confirmButtonColor: "#DD6B55",
	            confirmButtonText: "Si, Asignar"
	        }).then(function(isConfirm)
	        {
	            axios
	            .post(me.base+"/impuestos_productos/servicio",{impuesto_id:me.impuesto_id})
	            .then(function(response)
	            {

	            	console.log(response.data.validate)


	                if (isConfirm)
	                {
	                    me.$swal
	                    ({
	                        title: 'Servicio',
	                        text: 'Este impuesto quedo como Servicio',
	                        icon: 'success'
	                    }).
	                    then(function()
	                    {
	                        me.ListarImpuestos();
	                    });
	                }
	            })
	            .catch(function(error, otracosa) {});
	             });



	    },

	    BorrarImpuestos()
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
	            .post(me.base+"/impuestos_productos/borrar",{id:me.selected})
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
	                        me.ListarImpuestos();
	                    });
	                }
	            })
	            .catch(function(error, otracosa) {});
	        });
	    },

	    ListarImpuestos (page){
                let me=this;
                var url= me.base+'/impuestos_productos/index?page=' + page;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    console.log(respuesta)
                    me.ArrayImpuestos = respuesta.impuestos.data;
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


	    NuevaImpuesto()
	    {
	        let me = this;
	        me.selected=0;
	        me.tipoGuardar = 2;
	        me.impuestoNombre='';
	        me.impuestoValor=0;
	    },
	    VerImpuesto(data)
	    {
	        let me= this;
	        me.tipoGuardar = 1;
	        me.selected=data.id;
	        me.impuestoNombre=data.nombre;
	        me.impuestoValor=data.valor;
	    },


	    GuardarNuevoImpuestos()
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
	            .post(me.base+"/impuestos_productos/Nueva", {
	                nombre : me.impuestoNombre,
	                valor: me.impuestoValor
	            })
	            .then(function(response)
	            {
	              if (response.data.validate)
	              {
	                me.disablebutton = false;
	                me.id = response.data.id;
	                me.$swal("se ha guardado el impuesto " + me.impuestoNombre + " con éxito");
	                me.ListarImpuestos();
	                me.VerImpuesto(response.data);
	              } else {
	                me.disablebutton = false;
	                me.$swal("No se ha podido guardar el impuesto " + me.numero);
	              }
	            }).catch(function(error) {console.log(error)});
	        }
	        else
	        {
	          me.$swal("Por favor ingrese todos los campos");
	        }
	      });
	    },
	    GuardarEditarImpuestos()
	    {
				let me = this;
	      this.submitted = true;
	      this.$validator.validate().then(valid =>
	      {
	        if (valid)
	        {
	          me.disablebutton = true;
	          axios
	            .post(me.base+"/impuestos_productos/Editar", {
	              id: me.selected,
	              nombre : me.impuestoNombre,
	              valor: me.impuestoValor
	            })
	            .then(function(response) {
	              if (response.data.validate) {
	                me.$swal("se ha editado el impuesto " + me.impuestoNombre + " con éxito");
									me.ListarImpuestos();
	                me.VerImpuesto(response.data);
	              } else {
	                me.$swal("Se presento un problema al editar impuesto " + me.impuestoNombre + ".");
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
	    this.ListarImpuestos();
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
</style>
