<template>
        <main class="main">
    <div class="col-12">
        <div class="content-header">Tipo de documento</div>
    </div>
<section id="extended">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Action Buttons</h4>
                      <button class="btn btn-gradient-blue btn-fix" @click="abrirModal('documento','registrar')">
                                    <span class="btn-icon"><i class="la la-user-plus"></i>Nuevo</span>
                        </button>

                         <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre">Nombre</option>
                                      <option value="descripcion">Descripción</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup.enter="listarDocumentos(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                   
                                     <button class="btn btn-gradient-silver btn-fix" type="submit" @click="listarDocumentos(1,buscar,criterio)">
                                    <span class="btn-icon"><i class="la la-search"></i>Buscar</span>
                                     </button>
                                </div>
                </div>
                <div class="card-body">
                    <div class="card-block">
                        <table class="table table-responsive-md-md text-center">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="documento in arrayDocumentos" :key="documento.id">
                                     <td v-text="documento.descripcion"></td>
                                    <td v-text="documento.descripcion"></td>
                                   <td>
                                   
                                        <a class="success p-0" data-original-title="" title="" @click="abrirModal('documento','actualizar',documento)">
                                            <i class="ft-edit-2 font-medium-3 mr-2"></i>
                                        </a>
                                     
                                        
                                         

                                        <a class="danger p-0" data-original-title="" title="" @click="desactivarCategoria(documento.id)">
                                            <i class="ft-x font-medium-3 mr-2"></i>
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
</section>
            <!--Inicio del modal agregar/actualizar-->
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
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
                                <div v-show="errorCategoria" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjCategoria" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-primary" @click="registrarCategoria()">Guardar</button>
                            <button type="button" v-if="tipoAccion==2" class="btn btn-primary" @click="actualizarCategoria()">Actualizar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->
        </main>
</template>

<script>
    export default {
        data (){
            return {
                base:'',
                categoria_id: 0,
                nombre : '',
                descripcion : '',
                arrayDocumentos : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorCategoria : 0,
                errorMostrarMsjCategoria : [],
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
                buscar : ''
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
        methods : {
            listarDocumentos (page,buscar,criterio)
            {
                let me=this;
                var url= '/documento?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                axios.get(me.base+url).then(function (response) 
                {
                    console.log(response);
                    var respuesta= response.data;
                    me.arrayDocumentos = respuesta.TipoDocumento.data;
                    me.pagination= respuesta.pagination;
                })
                .catch(function (error, otracosa) {
                    console.log(error, otracosa);
                });
            },
            cambiarPagina(page,buscar,criterio){
                let me = this;
                me.pagination.current_page = page;
                me.listarDocumentos(page,buscar,criterio);
            },
            registrarCategoria(){
                if (this.validarCategoria()){
                    return;
                }
                
                let me = this;

                axios.post(me.base+'/categoria/registrar',{
        
                    'descripcion': this.descripcion
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarCategoria(1,'','nombre');
                }).catch(function (error) {
                    var response = error.response.data;
                    console.log(response.message, 
                        response.exception, 
                        response.file, 
                        response.line);
                });
            },
            actualizarCategoria(){
               if (this.validarCategoria()){
                    return;
                }
                
                let me = this;

                axios.put('/categoria/actualizar',{
                    'nombre': this.nombre,
                    'descripcion': this.descripcion,
                    'id': this.categoria_id
                }).then(function (response) {
                    me.cerrarModal();
                    me.listarCategoria(1,'','nombre');
                }).catch(function (error) {
                    var response = error.response.data;
                    console.log(response.message, 
                        response.exception, 
                        response.file, 
                        response.line);
                }); 
            },
            desactivarCategoria(id){
               swal({
                title: 'Esta seguro de desactivar esta categoría?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/categoria/desactivar',{
                        'id': id
                    }).then(function (response) {
                        me.listarCategoria(1,'','nombre');
                        swal(
                        'Desactivado!',
                        'El registro ha sido desactivado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        var response = error.response.data;
                        console.log(response.message, 
                        response.exception, 
                        response.file, 
                        response.line);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
            activarCategoria(id){
               swal({
                title: 'Esta seguro de activar esta categoría?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Aceptar!',
                cancelButtonText: 'Cancelar',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true
                }).then((result) => {
                if (result.value) {
                    let me = this;

                    axios.put('/categoria/activar',{
                        'id': id
                    }).then(function (response) {
                        me.listarCategoria(1,'','nombre');
                        swal(
                        'Activado!',
                        'El registro ha sido activado con éxito.',
                        'success'
                        )
                    }).catch(function (error) {
                        var response = error.response.data;
                        console.log(response.message, 
                        response.exception, 
                        response.file, 
                        response.line);
                    });
                    
                    
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    
                }
                }) 
            },
            validarCategoria(){
                this.errorCategoria=0;
                this.errorMostrarMsjCategoria =[];

                if (!this.nombre) this.errorMostrarMsjCategoria.push("El nombre de la categoría no puede estar vacío.");

                if (this.errorMostrarMsjCategoria.length) this.errorCategoria = 1;

                return this.errorCategoria;
            },
            cerrarModal(){
                this.modal=0;
                this.tituloModal='';
                this.nombre='';
                this.descripcion='';
            },
            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "documento":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Tipo Documento';
                                this.descripcion = '';
                                this.tipoAccion = 1;
                                break;
                            }
                            case 'actualizar':
                            {
                                //console.log(data);
                                this.modal=1;
                                this.tituloModal='Actualizar categoría';
                                this.tipoAccion=2;
                                this.categoria_id=data['id'];
                                this.nombre = data['nombre'];
                                this.descripcion= data['descripcion'];
                                break;
                            }
                        }
                    }
                }
            }
        },
        mounted() //$(function) equivalente
        {
            this.base=base;
            this.listarDocumentos(1,this.buscar,this.criterio);
        }
    }
</script>
<style>    
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
</style>
