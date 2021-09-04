<template>
	<main class="main" >
		<div class="col-md-12">
			<div class="content-header">Configuracion del hotel</div>
		</div>
		<section id="extended">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<div class="input-group">
                            </div>
						</div>
						<div class="card-body">
							<div class="card-block">
								<div class="container-fluid">
                                    <div class="col-md-12">

<iframe :src="imgPreUrl" width="100%" height="925" frameborder="0"></iframe>

                                        </div>
                                    </div>
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
    import VueSweetAlert from 'vue-sweetalert'
    import Vue from 'vue';
import VueFriendlyIframe from 'vue-friendly-iframe';

Vue.component('vue-friendly-iframe', VueFriendlyIframe);
    export default
    {
        data ()
        {
            return {
							  imgPreUrl: '',
                base:'',
                impuestos:'0'
            }
        },
        methods :
        {
            checked_impuestos()
            {
                let me = this;
                if(me.impuestos=='0')
                {
                    me.impuestos='1';
                }
                else if(me.impuestos=='1'){
                    me.impuestos='0';
                }
                console.log(me.impuestos);
            },
//Cargas Iframe
						Cargas()
						{
								let me=this;
								axios.get(me.base+'/cargarIframe').then(function (response)
								{

										me.imgPreUrl = response.data.datos;
								})
								.catch(function (error, otracosa) {
										console.log(error, otracosa);
								});
						},
            Cargar()
            {
                let me=this;
                axios.get(me.base+'/config/get/impuestos_incluidos').then(function (response)
                {
                    if(response.validate)
                    {
                        me.impuestos=response.data.value;
                    }
                    else
                    {
                        me.impuestos='0';
                    }
                    console.log(response.data);
                })
                .catch(function (error, otracosa) {
                    console.log(error, otracosa);
                });
            }
        },


        mounted() {
            this.base=base;
            this.Cargar();
						this.Cargas();
        }
    }
</script>
<style>

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

.main{
    width: 100%;
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

.onoffswitch {
    position: relative; width: 90px;
    -webkit-user-select:none; -moz-user-select:none; -ms-user-select: none;
}
.onoffswitch-checkbox {
    display: none;
}
.onoffswitch-label {
    display: block; overflow: hidden; cursor: pointer;
    border: 2px solid #999999; border-radius: 20px;
}
.onoffswitch-inner {
    display: block; width: 200%; margin-left: -100%;
    transition: margin 0.3s ease-in 0s;
}
.onoffswitch-inner:before, .onoffswitch-inner:after {
    display: block; float: left; width: 50%; height: 30px; padding: 0; line-height: 30px;
    font-size: 14px; color: white; font-family: Trebuchet, Arial, sans-serif; font-weight: bold;
    box-sizing: border-box;
}
.onoffswitch-inner:before {
    content: "SI";
    padding-left: 10px;
    background-color: #1ab1cb;
    color: #FFFFFF;
}
.onoffswitch-inner:after {
    content: "NO";
    padding-right: 10px;
    background-color: #ff586b;
    color: #FFFFFF;
    text-align: right;
}
.onoffswitch-switch {
    display: block; width: 18px; margin: 6px;
    background: #FFFFFF;
    position: absolute; top: 0; bottom: 0;
    right: 56px;
    border: 2px solid #999999; border-radius: 20px;
    transition: all 0.3s ease-in 0s;
}
.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-inner {
    margin-left: 0;
}
.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-switch {
    right: 0px;
}
</style>
