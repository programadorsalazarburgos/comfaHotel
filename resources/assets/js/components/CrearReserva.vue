<template>
    <main class="main" style="
        width: 100%;
        ">
        <section id="list">
            <div class="row">
                <div class="col-sm-12">
                  
                    <!--             <p class="content-sub-header">List groups are a flexible and powerful component for displaying not only simple lists of elements, but complex ones with custom content.</p>
                        -->        
                </div>
            </div>

            <div class="row match-height">
                <!--Custom content Starts-->
                <div class="col-sm-12 col-md-6">
                        <div class="card-header">                 
                            <a href="javascript:void(0)" class="btn btn-raised gradient-mint white shadow-z-4" @click="abrirModal('categoria', 'registrar')">Nueva Reserva</a>
                            <span class="ml-2"></span>

                   </div>
                </div>
                <!--Contextual Action Starts-->        
            </div>
        </section>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none; height: 1000px;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header" style=" height: 20px;">
                      
                        <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                        <span aria-hidden="true" style="position: absolute;top: 6px;right: 15px;">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                        
                                

<form-wizard>
   <tab-content title="Selección">
      <div class="bubble" id="TravelData">
         <h1>Fecha del viaje <span style="float:right;">29.11.2018 15:54</span></h1>
         <!-- <form id="ChangeTravelData" method="post" action="default.aspx"> -->

<div style="
    position: relative;
    left: 125px;
">

<vue-datepicker :format="YYYY/MM/DD" v-model="date" />

</div>
<br>

         <!-- </form> -->
         <div style="text-align:center; margin:5px 0px; font-weight:bold;"><span style="padding:3px 10px; background-color:#E6E4E3; color:red;">La reserva es posible sin tarjeta de crédito</span></div>
      </div>
      <div class="bubble" id="RoomList">
         <div style="padding:2px; text-align:right;">
            Elija moneda:
            <select  name="SelectedCurrency" id="SelectedCurrency" class="field">
               <option selected="selected" value="USD">USD</option>
               <option value="EUR">EUR</option>
               <option value="GBP">GBP</option>
            </select>
         </div>
         <!-- ROOM NUMBER AND ROOM TYPE -->
         <form id="ActionForm" action="default.aspx" method="post">
            <input type="hidden" name="ID" value="0">
            <input type="hidden" name="src" value="pms">
            <input type="hidden" name="Tentative" value="">
            <h1>Número y tipo de habitación</h1>
            <div>
               <div class="row mb-3" matchheight="card">
                  <div class="col-xl-4 col-lg-12">
                     <div class="card">
                        <strong>EXECUTIVE SUITE</strong>
                        <div class="card-body">
                           <div class="card-img">
                              <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                                 <ol class="carousel-indicators">
                                    <li data-target="#carousel-example-generic" data-slide-to="0" class=""></li>
                                    <li data-target="#carousel-example-generic" data-slide-to="1" class="active"></li>
                                    <li data-target="#carousel-example-generic" data-slide-to="2" class=""></li>
                                 </ol>
                                 <div class="carousel-inner" role="listbox">
                                    <div class="carousel-item">
                                       <img src="img/1.jpg" class="d-block w-100" alt="First slide">
                                    </div>
                                    <div class="carousel-item active">
                                       <img src="img/2.jpg" class="d-block w-100" alt="Second slide">
                                    </div>
                                    <div class="carousel-item">
                                       <img src="img/3.jpg" class="d-block w-100" alt="Third slide">
                                    </div>
                                 </div>
                                 <a class="carousel-control-prev" href="#carousel-example-generic" role="button" data-slide="prev">
                                 <span class="fa fa-angle-left icon-prev" aria-hidden="true"></span>
                                 <span class="sr-only">Previous</span>
                                 </a>
                                 <a class="carousel-control-next" href="#carousel-example-generic" role="button" data-slide="next">
                                 <span class="fa fa-angle-right icon-next" aria-hidden="true"></span>
                                 <span class="sr-only">Next</span>
                                 </a>
                              </div>
                              <a class="btn btn-floating halfway-fab btn-large gradient-blackberry"><i class="ft-plus"></i></a>
                           </div>
                           <div class="card-block mt-3" style="display: none;">
                              <h4 class="card-title">Card title</h4>
                              <p class="card-text">Sweet halvah dragée jelly-o halvah carrot cake oat cake. Donut jujubes jelly chocolate cake.</p>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-8 col-lg-12">
                     <div class="card">
                        <div class="card-body">
                           <div class="container-fluid">
                              <div class="row">
                                 <div class="col-md-12">
                                    <div class="row">
                                       <div class="col-md-6">
                                          <div style="text-align:right; cursor:pointer;">
                                             <span><a style=" font-weight:bold; color: #800000; text-decoration:underline;" class="deflink">Condiciones de anulación</a></span>
                                          </div>
                                          <div style="text-align: right;">
                                             Precio por Noche 
                                             <strong>(USD)</strong>
                                             <div class="price" style="font-weight:bold;">100.00</div>
                                             <div class="price" style="font-weight:bold;">100.00</div>
                                             <div class="price" style="font-weight:bold;">100.00</div>
                                          </div>
                                       </div>
                                       <div class="col-md-6">
                                          <div style="color:green; font-size:90%; text-align: right;">
                                             Disponible: <span id="msgMinRoomFree_165">7</span>
                                             Número de habitaciones:
                                             <select name="RoomNumber165" id="RoomNumber165" style="width:70px; padding:3px; font-size:120%;  text-align:center;" class="field">
                                                <option selected="" value="0">0 </option>
                                                <option value="1">1 </option>
                                                <option value="2">2 </option>
                                             </select>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </form>
      </div>
   </tab-content>
   <tab-content title="Contacto">
   </tab-content>
   <tab-content title="Confirmación">
   </tab-content>
</form-wizard>





                           
                           
                        </form>
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
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment'
    import VueSweetAlert from 'vue-sweetalert'
    export default {
        data (){
             
            return {
                base:'',
                isOpen: false,
                isOpen2: false,
                isOpen3: false,
                nombres: '',
                categoria_id: 0,
                nombre : '',
                apellidos : '',
                fecha_nacimiento : '',
                sexo : '',
                nacionalidad : '',
                formula : '',
                titulo : '',
                tipoDocumento : '',
                no_documento : '',
                nombre_empresa : '',
                no_empresa : '',
                nombre_agencia : '',
                no_agencia : '',
                lugar : '',
                codigo_postal : '',
                pais : '',
                calle : '',
                departamento : '',
                calle_factura : '',
                lugar_factura : '',
                codigo_postal_factura : '',
                pais_factura : '',
                departamento_factura : '',
                comentarios : '',
                descripcion : '',
                arrayCategoria : [],
                arrayCliente : [],
                arrayTipoCliente : [],
                arrayPais : [],
                arrayDepartamento : [],
                arrayDepartamentoFactura : [],
                arrayTipoContacto : [],
                arrayTipoDocumento : [],
                arraySexo : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                tipoAccionF : 1,
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
                buscar : '',
    
    
                apartments: [],


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
    Datepicker
    },
    

props: {
   title: {
    type: String,
    default: 'Johan Salazar Burgos'
  },

subtitle: {
    type: String,
    default: 'Split a complicated flow in multiple steps'
  },
  nextButtonText: {
    type: String,
    default: 'Next'
  },

  backButtonText: {
    type: String,
    default: 'Back'
  },
  finishButtonText: {
    type: String,
    default: 'Finish'
  },

},



        methods : {


            abrirModal(modelo, accion, data = []){
                switch(modelo){
                    case "categoria":
                    {
                        switch(accion){
                            case 'registrar':
                            {
                                this.modal = 1;
                                this.nombre= '';
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
        mounted() {
            this.base=base;

        }
    }
</script>
<style> 


.vue-form-wizard.md .wizard-icon-circle
{

    width: 50px ! important;
    height: 50px ! important;
    font-size: 20px ! important;
}

.wizard-title
{
    display: none !important;
}

.category
{
 display: none !important;   
}

.Container {

    display:none; 
    width:350px; 
    padding:0px; 
    text-align:justify; 
    position:absolute;
}

.style1 {
    cursor:pointer; 
    float:left;
}

.style2 {

    float:left;
}

.style3 {

   text-align:center; 
   font-size:110%; 
   font-weight:bold;
}
.style4 {

  width: 200px; 
  height: 133px; 
  position: relative; 
  z-index: 0; 
  overflow: hidden; 
  padding: 0px;
}

.style5 {

  text-align:right; 
  cursor:pointer;
}
.style6 {

  border-collapse:collapse;
}


.roomTable {
    background-color: rgb(238, 238, 238);

}

.BigFloater {
    background-color: #FFFFFF;
    color: #000000;
    border: 1px solid #444444;
    overflow: hidden;
    width: 110px;
}

.smallRoomImageShadow {
    box-shadow: 5px 5px 5px 0px rgba(0, 0, 0, 0.6);
}

.smallRoomImage {
    border: 2px solid #444444;
    -moz-border-radius: 6px;
    -webkit-border-radius: 6px;
    -khtml-border-radius: 6px;
    padding: 2px;
    background-color: white;
    width: 400px; 
    position: absolute; 
    z-index: 99999; 
    display: none;
}

section.awSlider .carousel{
  display:table;
  z-index:2;
  -moz-box-shadow: 0 0 4px #444;
    -webkit-box-shadow: 0 0 4px #444;
    box-shadow: 0 0 15px rgba(1,1,1,.5);
}

section.awSlider{
  margin:30px auto;
  padding:30px;
  position:relative;
  display:table;
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -khtml-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

section.awSlider:hover > img{
  -ms-transform: scale(1.2);
  -webkit-transform: scale(1.2);
  transform: scale(1.2);
  opacity:1;
}

section.awSlider img{
   pointer-events: none;
}

section.awSlider > img{
  position:absolute;
  top:30px;
  z-index:1;
  transition:all .3s;
  filter: blur(1.8vw);
  -webkit-filter: blur(2vw);
  -moz-filter: blur(2vw); 
  -o-filter: blur(2vw); 
  -ms-filter: blur(2vw);
  -ms-transform: scale(1.1);
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
  opacity:.5;
}


.main-content {

    position: initial !important;
}

div.bubble {
    background-color: white;
    padding: 5px;
}


div.bubble h1 {
    background: #444444;
    color: white;
    font-family: Segoe UI,Verdana;
    font-size: 16px;
    margin: 0px 0px;
    padding: 4px;
}



    input.datepicker {
    border: 1px solid #ff0000;  
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


.modal-body {
    max-height: calc(220vh - 210px);
    overflow-y: auto;
}

</style>