<template>
    <main class="main">

        <div class="col-12">
            <div class="content-header"><h5>Importar Reservas</h5></div>
        </div>
        <br>
        <section id="extended">
          <form>
              <div class="form-group-row">
                  <h5><span _ngcontent-vvq-c18="" class="badge badge-danger">Cargar Excel</span></h5>
              </div>
              <div class="form-group-row">
                  <div class="col-sm-10">
                      <input type="file" id="file" ref="file" v-on:change="handleFileUpload()" accept=".XLSX, .CSV" class="form-control">
                  </div>
              </div>
              <br>
              <a v-on:click="EventSubir()" class="btn btn-raised gradient-mint white shadow-z-4">Subir</a>
          </form>
          <section id="extended" v-if="accion==1">
      			<div class="row">
      				<div class="col-sm-12">
      					<div class="card">
      						<div class="card-header">
      							<h4 class="card-title"></h4>

      						</div>
      						<div class="card-body" style="height: auto !important;">
      							<div class="card-body" style="height: auto !important;">
                          <div class="card-block">
                              <table class="table table-responsive-md-md text-center">
                                  <thead>
                                      <tr>

                                          <th>Errores</th>

                                      </tr>
                                  </thead>
                                  <tbody>

                                      <tr v-for="errores in ArrayErrores">
                                          <td v-text="errores"></td>



                                      </tr>


                                  </tbody>
                              </table>

                          </div>
                      </div>

      						</div>
      					</div>
      				</div>

      			</div>
      		</section>




      </section>

       </main>
</template>
<script>
  import Vue from 'vue';
  import Datepicker from 'vuejs-datepicker';
  import VueSweetAlert from 'vue-sweetalert'
  import VueSweetalert2 from 'vue-sweetalert2';
  import swal from 'vue-sweetalert2';
  const Swal = require('sweetalert2')
export default {
    data(){
        return {
            file: '',
            accion: 0,
            ArrayErrores: [],
        }
    },
    computed:{
    count () {
    this.$store.state;
    console.log(this.$store.state);
    return this.$store.state.posts
        },
    },
    methods: {
        EventSubir(){
            let formData = new FormData();
            let me = this;
            formData.append('file', this.file);
            axios
                .post( '/import-excel-reservas',
                    formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then(function(response){
                    console.log(response.data.data);
                    if (response.data.data == true) {
                      me.$swal('Almacenado', 'Su información ha sido almacenada', 'success')
                    }
                    else {
                      me.accion = 1;
                      me.ArrayErrores = response.data.data;
                    }

                })
                .catch(function(data){

                    console.log(1);
                });
        },
        handleFileUpload(){
            this.file = this.$refs.file.files[0];
        }
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
   content: " ";
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

   .card .card-body {
    padding: 0 !important;
    height: auto !important;
  }

  .form-calendario{
     width: 116% !important;
  }


</style>
