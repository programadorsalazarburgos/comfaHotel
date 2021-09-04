<template>
	<main class="main" >
		<div class="col-12">
			<div class="content-header">Exchange rate</div>
		</div>
		<section id="extended">
			<div class="row">
				<div class="col-sm-6">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title"></h4>
							<div class="input-group">
								<select class="form-control col-md-3" v-model="criterio">
									<option value="codigo_iso">Moneda</option>
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
                  <div class="list-group" v-for="moneda in arrayMonedas" :key="moneda.id">
                    <span class="list-group-item" :class="[moneda.id == selected ? 'active':'']" @click="VerDetalle(moneda.id)">
                      <h5>{{moneda.codigo_iso}}</h5>
                      <span class="title-sub">
                        {{moneda.nombre}}
                      </span>
                    </span>
                  </div>
                </div>
                  <ul class="pagination">
                    <li v-for="(pagina, index) in Paginas" :key="index" :class="((index +1) ==pag_actual)?'page-item active':'page-item'">
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
							<h5 class="card-title">Exchange rate</h5>
							<div class="container-fluid"  v-if="mostrar_editar">
							<form  id="registerForm" method="POST" @submit.prevent="guardarCambios">
								<div class="col-sm-12">
									<label for="">{{codigo_iso}}:{{nombre}}</label>
                                    <input type="number" min="1" class="form form-control" v-model="valor_usd" >									
                                    <small>0.01 {{codigo_iso}} a USD</small>
                                </div>

                
							<div class="col-sm-12">
                            <br>
								<button :disabled="disablebutton" @click="GuardarEditar()" type="button" class="btn btn-raised gradient-ibiza-sunset white sidebar-shadow"><i class="fa fa-pencil" aria-hidden="true"></i> Actualizar moneda</button>
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
      codigo_iso: null,
      nombre: null,
      valor_usd: null,
      arrayMonedas: [],
      mostrar_editar:false,
      //===============================basicos===============================//
        buscar:'',
	      criterio: "codigo_iso",
      	Paginas:0
    };
  },
  methods: {
    //=======================================Principales=======================================//
    VerDetalle(id) {
      let me = this;
      me.mostrar_editar=true;
      me.selected = id;
      var data = me.arrayMonedas.find(moneda => moneda.id === id);
      me.id = id;
      (me.codigo_iso = data.codigo_iso),
        (me.nombre = data.nombre),
        (me.valor_usd = data.valor_usd);
      me.tipoGuardar = 1;
    },
    ListarItems(page,filtro) {
      let me = this;
      me.pag_actual=page;
      me.arrayMonedas = [];
      axios
        .get(me.base+"/moneda/index?criterio="+me.criterio+"&page="+page+"&filtro=" + filtro)
        .then(function(response) {
          var respuesta = response.data;
          me.arrayMonedas = respuesta.moneda;
          me.Paginas=respuesta.pages;
        })
        .catch(function(error, otracosa) {});
    },
    GuardarEditar() {
      let me = this;
      me.disablebutton = true;
      axios
        .post(me.base+"/moneda/Editar", {
            id:me.id,
          valor_usd: me.valor_usd
        })
        .then(function(response) {
          console.log(response);
          if (response.data.validate) {
            Vue.swal("se ha editado la moneda " + me.nombre + " con éxito");
            me.ListarItems(1,'');
            me.VerDetalle(me.id);
          } else {
            Vue.swal(
              "Se presento un problema al editar la moneda " + me.nombre + "."
            );
          }
          me.disablebutton = false;
        })
        .catch(function(error) {
          me.disablebutton = false;
          console.log(error);
        });
    }
    //=======================================Principales=======================================//
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
#scroll {
  overflow-y: scroll;
  height: 360px;
}
</style>