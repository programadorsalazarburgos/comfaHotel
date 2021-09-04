<template>
	<main class="main" >
		<div class="col-12">
			<div class="content-header">Plan</div>
		</div>
		<section id="extended">
			<div class="row">
				<div class="col-sm-6">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title"></h4>
							<button class="btn btn-raised gradient-mint white shadow-z-4" @click="NuevoDato()">
							<span class="btn-icon"><i class="la la-user-plus"></i>PLAN</span>
							</button>
							<div class="input-group">
								<select class="form-control" v-model="criterio">
									<option value="nombre">Nombre</option>
								</select>
								<input  type="text" v-model="buscar" @keyup.enter="ListarItems(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
								<button class="btn btn-raised gradient-pomegranate white big-shadow" type="submit" @click="ListarItems(1,buscar,criterio)">
								<span class="btn-icon"><i class="la la-search"></i>Buscar</span>
								</button>
							</div>
						</div>
										<div class="card-body">
					<div class="card-body">
                    <div class="card-block">
                        <table class="table table-responsive-md-md text-center">
                            <thead>
                                <tr>
                                
                                    <th>Nombre Plan</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="datos in arrayItems" :key="datos.id">
                                    <td v-text="datos.nombre"></td>
                                    <td v-text="datos.valor"></td>
                                   
                                    <td>
                                       
                                        <a class="success p-0" data-original-title="" title=""  @click="verComida(datos.id)">
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
				<div class="col-sm-6">
					<div class="card">
						<div class="card-header">
							<h5 class="card-title">Plan</h5>
							<div class="container-fluid">
								<form  @submit.prevent="handleSubmit">
									<div class="col-sm-12">
										<label for="">Nombre Plan</label>
										<input  v-validate="'required'" type="text" class="form form-control" name="nombre" v-model="nombre">
					                    <ul class="list-group">
					                      <li class="list-group-item list-group-item-danger" v-for="error in errors.collect('nombre')" :key="error">{{ error }}</li>
					                    </ul>									
									</div>

									<div class="col-sm-12">
										<label for="">Valor Plan</label>
										<input  v-validate="'required'" type="text" class="form form-control" name="valor" v-model="valor">
					                    <ul class="list-group">
					                      <li class="list-group-item list-group-item-danger" v-for="error in errors.collect('valor')" :key="error">{{ error }}</li>
					                    </ul>									
									</div>

									<div class="col-sm-12">
										<br>
										<button  v-if="tipoGuardar==1" @click="GuardarEditarDato()" type="button" class="btn btn-raised gradient-ibiza-sunset white sidebar-shadow"><i class="fa fa-pencil" aria-hidden="true"></i> Actualizar Plan</button>
										<button :disabled="disablebutton" v-if="tipoGuardar==2" @click="GuardarNuevoDato()" type="button" class="btn btn-raised gradient-nepal white card-shadow"><i class="fa fa-plus" aria-hidden="true"></i> Guardar Plan</button>
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
  import VueSweetalert2 from 'vue-sweetalert2';
	export default {
	  data() {
	    return {
		  base:'',
		  nombre: '',
		  valor: '',
	      pag_actual:1,
	      Paginas:0,
	      selected: null,
	      SivalidaForm:false,
	      tipoGuardar: 2,
	      id: null,
	      disablebutton: false,
	      id_cama: '',
	      arrayItems: [],
	      pagination: {
	        total: 0,
	        current_page: 0,
	        per_page: 0,
	        last_page: 0,
	        from: 0,
	        to: 0
	      },
	      buscar: "",
	      submitted: false,
	      	      buscar: "",
	      criterio: "nombre",
	      Paginas:0,
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
	    };
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

	  methods: {
	    handleSubmit(e) {console.log(e);},
	    verComida(id) {
	      let me = this;
	      me.selected = id;
	      me.tipoGuardar = 1;
	      var data = me.arrayItems.find(
	        Comidas => Comidas.id === id
	      );
	      console.log(data)

	      me.id = id;
	      me.nombre = data.nombre;
	      me.valor = data.valor;
	      me.tipoGuardar = 1;
	    }, 

	     ListarItems (page,buscar,criterio){
                let me=this;
                var url= '/comidas?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(url).then(function (response) {
                    var respuesta= response.data;
                    me.arrayItems = respuesta.alimentos.data;
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

	
	    NuevoDato() {
	      let me = this;
	      me.tipoGuardar = 2;
	      me.id = '';
	      me.id_habitacion_tipo = '';
	      me.nombre = '';
	      me.valor = '';
	    },
	    GuardarNuevoDato() {
	      this.submitted = true;
		      this.$validator.validate().then(valid => 
		      {
		        if (valid) 
		        {
							let me = this;
							me.tipoGuardar = 1;
							me.disablebutton = true;
							axios
							.post(me.base+"/comidas/Nueva", {
								nombre: me.nombre,
								valor: me.valor
							})
	        .then(function(response) 
	        {

	        	me.$swal('Almacenado', 'Tu registro ha sido guardado', 'success')
	        	me.ListarItems(1,'','nombre');



	        }).catch(function(error) {console.log(error)});
	        }


		    });
	    },
	    GuardarEditarDato() {
	
	this.submitted = true;
		      this.$validator.validate().then(valid => 
		      {
		        if (valid) 
		        {
	      let me = this;
	      console.log(me.id);
	      me.disablebutton = true;
	      axios
	        .post(me.base+"/comidas/Editar", {
	          id: me.id,
	          nombre: me.nombre,
	          valor: me.valor
	        })
	        .then(function(response) {
	          	me.$swal('Almacenado', 'Se ha editado la comida con éxito', 'success')
	        	me.ListarItems(1,'','nombre');
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
	/*.title-list {
	text-transform:uppercase;
	color: #519bbd;
	font-weight: bold;
	font-size: 0.7em;
	}
	.title-sub {
	color: #c3c3c3;
	font-size: 9px;
	}
	h5{
	font-size: 1.2em;
	text-transform:uppercase;
	}*/
	#scroll {
	overflow-y: scroll;
	height: 360px;
	}
</style>