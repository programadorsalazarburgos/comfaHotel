<template>
<form class="form">
<div class="form-body">
	<h4 class="form-section"><i class="ft-info"></i> Datos</h4>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label for="userinput1">Tipo de cliente</label>
				<select v-on:change="toggle" class="form-control" v-model="tipoCliente">
					<option value="0" disabled>Seleccione</option>
					<option v-for="tipoCliente in arrayTipoCliente" :key="tipoCliente.id" :value="tipoCliente.id" v-text="tipoCliente.nombre"></option>
				</select>
			</div>
		</div>
	</div>
	<span  v-show="isOpen">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="userinput1">Nombres</label>
					<input type="text" v-model="nombres" class="form-control border-primary">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="userinput2">Apellidos</label>
					<input type="text" v-model="apellidos" id="userinput2" class="form-control border-primary" name="company">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="userinput4">Fecha Nacimiento</label>
					<datepicker :format="customFormatter" v-model="fecha_nacimiento" :bootstrap-styling="true"></datepicker>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="userinput3">Sexo</label>
					<select  class="form-control" v-model="sexo">
						<option value="0" disabled>Seleccione</option>
						<option v-for="sexo in arraySexo" :key="sexo.id" :value="sexo.id" v-text="sexo.nombre"></option>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="userinput3">Nacionalidad</label>
					<select class="form-control" v-model="nacionalidad">
						<option value="0" disabled>Seleccione</option>
						<option v-for="nacionalidad in arrayPais" :key="nacionalidad.id" :value="nacionalidad.id" v-text="nacionalidad.nombre_pais"></option>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="userinput3">Formula</label>
					<input type="text" v-model="formula" id="userinput3" class="form-control border-primary" name="username">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="userinput4">Titulo</label>
					<input type="text" v-model="titulo" id="userinput4" class="form-control border-primary" name="nickname">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="userinput3">Tipo de documento</label>
					<select class="form-control" v-model="tipoDocumento">
						<option value="0" disabled>Seleccione</option>
						<option v-for="tipoDocumento in arrayTipoDocumento" :key="tipoDocumento.id" :value="tipoDocumento.id" v-text="tipoDocumento.descripcion"></option>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="userinput4">Nro. del documento</label>
					<input type="text" v-model="no_documento" id="userinput4" class="form-control border-primary" name="nickname">
				</div>
			</div>
		</div>
	</span>
	<span  v-show="isOpen2">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="userinput1">Nombre de empresa</label>
					<input type="text" v-model="nombre_empresa" id="userinput1" class="form-control border-primary" name="name">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="userinput2">Nro. ID empresa</label>
					<input type="text" v-model="no_empresa" id="userinput2" class="form-control border-primary" name="company">
				</div>
			</div>
		</div>
	</span>
	<span  v-show="isOpen3">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="userinput1">Nombre de Agencia</label>
					<input type="text" v-model="nombre_agencia" id="userinput1" class="form-control border-primary" name="name">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="userinput2">Ruc</label>
					<input type="text" v-model="no_agencia" id="userinput2" class="form-control border-primary" name="company">
				</div>
			</div>
		</div>
	</span>
	<h4 class="form-section"><i class="ft-mail"></i> Dirección </h4>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label for="userinput3">Calle</label>
				<input type="text" v-model="calle" id="userinput3" class="form-control border-primary" name="username">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label for="userinput3">Lugar</label>
				<input type="text" v-model="lugar" id="userinput3" class="form-control border-primary" name="username">
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label for="userinput4">Codigo postal</label>
				<input type="text" v-model="codigo_postal" id="userinput4" class="form-control border-primary" name="nickname">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				<label for="userinput1">Pais</label>
				<select v-on:change="selectDepartamento(pais)" class="form-control" v-model="pais">
					<option value="0" disabled>Seleccione</option>
					<option v-for="pais in arrayPais" :key="pais.id" :value="pais.id" v-text="pais.nombre_pais"></option>
				</select>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				<label for="userinput4">Departamento</label>
				<select v-on:change="selectDepartamento" class="form-control" v-model="departamento">
					<option value="0" disabled>Seleccione</option>
					<option v-for="departamento in arrayDepartamento" :key="departamento.id" :value="departamento.id" v-text="departamento.nombre_departamento"></option>
				</select>
			</div>
		</div>
	</div>
	<h4 class="form-section"><i class="ft-mail"></i> Contacto
	</h4>
</div>
<div class="row">
	<div class="col-xs-2">
		<button type="button" v-on:click="addNewApartment" class="btn btn-raised btn-icon btn-pure success mr-1">
		<i class="ft-plus-circle"></i>
		</button>
	</div>
	<div class="col-xs-10">
		Agrega tu contacto
	</div>
</div>
<div v-for="(apartment, index) in apartments" :key="index">
	<div class="row">
		<div class="col-xs-2">
			<label>&nbsp;</label>
			<button type="button" v-on:click="removeApartment(index)" class="btn btn-block btn-danger">
			<i class="ft-trash-2"></i>
			</button>
		</div>
		<div class="form-group col-xs-5">
			<label>Seleccione</label>
			<select v-model="apartment.tipoContacto" name="apartments[][tipoContacto]" class="form-control">
				<option value="0" disabled>Seleccione</option>
				<option v-for="tipoContacto in arrayTipoContacto" :key="tipoContacto.id" :value="tipoContacto.id" v-text="tipoContacto.nombre"></option>
			</select>
		</div>
		<div class="form-group col-xs-5">
			<label>Digite</label>
			<input v-model="apartment.numero" type="text"
				name="apartments[][numero]" class="form-control">
		</div>
	</div>
</div>

<h4 class="form-section"><i class="ft-mail"></i> Direccion de factura
</h4>
<div class="row">
	<div class="col-md-6">
		<div class="form-group">
			<label for="userinput3">Calle</label>
			<input type="text" v-model="calle_factura" id="userinput3" class="form-control border-primary" name="username">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="form-group">
			<label for="userinput3">Lugar</label>
			<input type="text" v-model="lugar_factura" id="userinput3" class="form-control border-primary" name="username">
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			<label for="userinput4">Codigo postal</label>
			<input type="text" v-model="codigo_postal_factura" id="userinput4" class="form-control border-primary" name="nickname">
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="form-group">
			<label for="userinput1">Pais</label>
			<select v-on:change="selectDepartamentoFactura" class="form-control" v-model="pais_factura">
				<option value="0" disabled>Seleccione</option>
				<option v-for="pais_facura in arrayPais" :key="pais_facura.id" :value="pais_facura.id" v-text="pais_facura.nombre_pais"></option>
			</select>
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			<label for="userinput4">Departamento</label>
			<select v-on:change="selectDepartamento" class="form-control" v-model="departamento_factura">
				<option value="0" disabled>Seleccione</option>
				<option v-for="departamento_factura in arrayDepartamentoFactura" :key="departamento_factura.id" :value="departamento_factura.id" v-text="departamento_factura.nombre_departamento"></option>
			</select>
		</div>
	</div>
</div>
<h4 class="form-section"><i class="ft-mail"></i> Comentarios
</h4>
<div class="form-group">
	<label for="userinput3">Hotel Manager</label>
	<textarea type="text" v-model="comentarios" id="userinput3" class="form-control border-primary" name="username"></textarea>
</div>
<div class="form-actions right">
	<button type="button" class="btn btn-raised btn-warning mr-1">
	<i class="ft-x"></i> Cancelar
	</button>
	<button type="button" @click="registrarCliente()" class="btn btn-raised btn-primary" v-if="tipoAccionF==1">
	<i class="fa fa-check-square-o"></i> Guardar
	</button>

	<button type="button" @click="actualizarCliente()" class="btn btn-raised btn-primary" v-if="tipoAccionF==2">
	<i class="fa fa-check-square-o"></i> Actualizar
	</button>
</div>
</form>
</template>

<script>
import Datepicker from 'vuejs-datepicker';
import moment from 'moment'
import VueSweetAlert from 'vue-sweetalert'

export default {
data (){

return {
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
isOpen: '',
nombres:'',
apellidos:'',
sexo:'',
nacionalidad:'',
formula:'',
titulo:'',
isOpen2:'',
isOpen3:'',
fecha_nacimiento:'',


apartments: [],
tipoCliente:0


}

console.log(data);
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

methods : {


NuevoCliente(){
this.tipoAccionF = 1;
let me = this;
me.tipoCliente='';
me.nombres='';
me.apellidos='';
me.fecha_nacimiento='';
me.sexo='';
me.calle='';
me.nacionalidad='';
me.formula='';
me.titulo='';
me.tipoDocumento='';
me.no_documento='';
me.nombre_empresa='';
me.no_empresa='';
me.nombre_agencia='';
me.no_agencia='';
me.lugar='';
me.codigo_postal='';
me.pais='';
me.departamento='';
me.calle_factura='';
me.lugar_factura='';
me.codigo_postal_factura='';
me.pais_factura='';
me.departamento_factura='';
me.comentarios='';
me.apartments = [];

},

listarCliente (page,buscar,criterio){
let me=this;
var url= '/cliente?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
axios.get(me.base+url).then(function (response) {
var respuesta= response.data;
me.arrayCliente = respuesta.clientes.data;
console.log(me.arrayCliente);
me.pagination= respuesta.pagination;
})
.catch(function (error, otracosa) {
// var response = error.response.data;
console.log(error, otracosa);
//     error.response,
//     response.message,
//     response.exception,
//     response.file,
//     response.line);
});
},
cambiarPagina(page,buscar,criterio){
let me = this;
//Actualiza la página actual
me.pagination.current_page = page;
//Envia la petición para visualizar la data de esa página
me.listarCliente(page,buscar,criterio);
},
registrarCategoria(){
if (this.validarCategoria()){
return;
}

let me = this;

axios.post(me.base+'/categoria/registrar',{
'nombre': this.nombre,
'descripcion': this.descripcion
}).then(function (response) {
me.cerrarModal();
me.listarCliente(1,'','nombre');
}).catch(function (error) {
var response = error.response.data;
console.log(response.message,
response.exception,
response.file,
response.line);
});
},


ObtenerCliente(data = [])
{


let me = this;
this.id = data['id'];
this.tipoAccionF = 2;
this.nombres = data['nombre'];
this.apellidos = data['apellido'];
this.fecha_nacimiento = data['fecha_nacimiento'];
this.sexo = data['genero_id'];
this.nacionalidad = data['id_nacionalidad'];
this.formula = data['formula'];
this.titulo = data['titulo'];
this.tipoDocumento = data['id_documento_tipo'];
this.no_documento = data['no_documento'];
this.nombre_empresa = data['nombre_empresa'];
this.no_empresa = data['no_empresa'];
this.nombre_agencia = data['nombre_agencia'];
this.no_agencia = data['no_agencia'];
this.calle = data['calle_residencia'];
this.lugar = data['lugar_residencia'];
this.codigo_postal = data['codigo_postal_residencia'];
this.pais = data['id_pais_residencia'];

this.calle_factura = data['calle_factura'];
this.lugar_factura = data['lugar_factura'];
this.codigo_postal_factura = data['codigo_postal_factura'];
this.pais_factura = data['id_pais_factura'];

this.comentarios = data['comentarios'];
this.tipoCliente = data['id_clientes_tipo']
this.apartments = data['apartments'];


this.selectDepartamento(data['id_pais_residencia']);
this.departamento = data['id_departamento_residencia'];
this.selectDepartamentoFactura(data['id_pais_factura']);
this.departamento_factura = data['id_departamento_factura'];

if (data['id_clientes_tipo'] === 1)
{

me.isOpen = true
me.isOpen2 = false
me.isOpen3 = false


}
if (data['id_clientes_tipo'] === 2)
{
me.isOpen = false
me.isOpen3 = false
me.isOpen2 = true

}

if (data['id_clientes_tipo'] === 3)
{
me.isOpen = false
me.isOpen2 = false
me.isOpen3 = true

}

var apartments = [];

},


registrarCliente(){

let me = this;

axios.post(me.base+'/cliente/registrar',{

'tipoCliente':this.tipoCliente,
'nombres':this.nombres,
'apellidos':this.apellidos,
'sexo':this.sexo,
'fecha_nacimiento':this.fecha_nacimiento,
'nacionalidad':this.nacionalidad,
'formula':this.formula,
'titulo':this.titulo,
'tipoDocumento':this.tipoDocumento,
'no_documento':this.no_documento,
'nombre_empresa':this.nombre_empresa,
'no_empresa':this.no_empresa,
'nombre_agencia':this.nombre_agencia,
'no_agencia':this.no_agencia,
'calle':this.calle,
'lugar':this.lugar,
'codigo_postal':this.codigo_postal,
'pais':this.pais,
'departamento':this.departamento,
'apartment':me.apartments,
'calle_factura':this.calle_factura,
'lugar_factura':this.lugar_factura,
'codigo_postal_factura':this.codigo_postal_factura,
'pais_factura':this.pais_factura,
'departamento_factura':this.departamento_factura,
'comentarios':this.comentarios


}).then(function (response) {
me.$swal('Almacenado', 'Registro Almacenado', 'success');
me.listarCliente(1,'','nombres');
me.tipoCliente='';
me.nombres='';
me.apellidos='';
me.fecha_nacimiento='';
me.sexo='';
me.calle='';
me.nacionalidad='';
me.formula='';
me.titulo='';
me.tipoDocumento='';
me.no_documento='';
me.nombre_empresa='';
me.no_empresa='';
me.nombre_agencia='';
me.no_agencia='';
me.lugar='';
me.codigo_postal='';
me.pais='';
me.departamento='';
me.calle_factura='';
me.lugar_factura='';
me.codigo_postal_factura='';
me.pais_factura='';
me.departamento_factura='';
me.comentarios='';
me.apartments = [];


}).catch(function (error) {
console.log(error);

});
},


actualizarCliente()
{
let me = this;

axios.post(me.base+'/cliente/actualizar',{

'id': me.id,
'tipoCliente':this.tipoCliente,
'nombres':this.nombres,
'apellidos':this.apellidos,
'sexo':this.sexo,
'fecha_nacimiento':this.fecha_nacimiento,
'nacionalidad':this.nacionalidad,
'formula':this.formula,
'titulo':this.titulo,
'tipoDocumento':this.tipoDocumento,
'no_documento':this.no_documento,
'nombre_empresa':this.nombre_empresa,
'no_empresa':this.no_empresa,
'nombre_agencia':this.nombre_agencia,
'no_agencia':this.no_agencia,
'calle':this.calle,
'lugar':this.lugar,
'codigo_postal':this.codigo_postal,
'pais':this.pais,
'departamento':this.departamento,
'apartment':me.apartments,
'calle_factura':this.calle_factura,
'lugar_factura':this.lugar_factura,
'codigo_postal_factura':this.codigo_postal_factura,
'pais_factura':this.pais_factura,
'departamento_factura':this.departamento_factura,
'comentarios':this.comentarios


}).then(function (response) {


me.swal('Almacenado', 'Registro Actualizado', 'success');


me.listarCliente(1,'','nombres');
me.tipoCliente='';
me.nombres='';
me.apellidos='';
me.fecha_nacimiento='';
me.sexo='';
me.calle='';
me.nacionalidad='';
me.formula='';
me.titulo='';
me.tipoDocumento='';
me.no_documento='';
me.nombre_empresa='';
me.no_empresa='';
me.nombre_agencia='';
me.no_agencia='';
me.lugar='';
me.codigo_postal='';
me.pais='';
me.departamento='';
me.calle_factura='';
me.lugar_factura='';
me.codigo_postal_factura='';
me.pais_factura='';
me.departamento_factura='';
me.comentarios='';
me.apartments = [];


}).catch(function (error) {


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
me.listarCliente(1,'','nombre');
}).catch(function (error) {
var response = error.response.data;
console.log(response.message,
response.exception,
response.file,
response.line);
});
},

toggle: function(){
let me = this;


if (me.tipoCliente == 1)
{
this.isOpen = !this.isOpen
me.isOpen2 = false
me.isOpen3 = false

}
if (me.tipoCliente == 2)
{
me.isOpen = false
me.isOpen3 = false
this.isOpen2 = !this.isOpen2

}

if (me.tipoCliente == 3)
{
me.isOpen = false
me.isOpen2 = false
this.isOpen3 = !this.isOpen3

}

},


selectPais(){
let me=this;
var url= '/obtener/selectPais';
axios.get(me.base+url).then(function (response) {
console.log(response);
var respuesta= response.data;
me.arrayPais = respuesta.paises;
})
.catch(function (error) {
var response = error.response.data;
console.log(response.message,
response.exception,
response.file,
response.line);
});
},


selectDepartamento(pais){


let me=this;
var url= '/obtener/selectDepartamento?pais_id=' + me.pais;
axios.get(me.base+url).then(function (response) {
var respuesta= response.data;
me.arrayDepartamento = respuesta.departamentos;
})
.catch(function (error, otracosa) {
// var response = error.response.data;

});


},

selectDepartamentoFactura(){

let me=this;
var url= '/obtener/selectDepartamento?pais_id=' + me.pais_factura;
axios.get(me.base+url).then(function (response) {
var respuesta= response.data;
me.arrayDepartamentoFactura = respuesta.departamentos;
})
.catch(function (error, otracosa) {
// var response = error.response.data;

});


},


selectTipoContacto(){

let me=this;
var url= '/obtener/selectTipoContacto';
axios.get(me.base+url).then(function (response) {
var respuesta= response.data;
me.arrayTipoContacto = respuesta.tipo_contactos;
})
.catch(function (error, otracosa) {
// var response = error.response.data;

});


},


selectTipoDocumento(){

let me=this;
var url= '/obtener/selectTipoDocumento';
axios.get(me.base+url).then(function (response) {
var respuesta= response.data;
me.arrayTipoDocumento = respuesta.tipo_documentos;
})
.catch(function (error, otracosa) {
// var response = error.response.data;

});


},




selectSexo(){
let me=this;
var url= '/obtener/selectSexo';
axios.get(me.base+url).then(function (response) {
console.log(response);
var respuesta= response.data;
me.arraySexo = respuesta.sexos;
})
.catch(function (error) {
var response = error.response.data;
console.log(response.message,
response.exception,
response.file,
response.line);
});
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



customFormatter(date) {
return moment(date).format('YYYY-MM-DD');
},


addNewApartment: function () {
this.apartments.push(Vue.util.extend({}, this.apartment))
},
removeApartment: function (index) {
Vue.delete(this.apartments, index);
},


selectTipoCliente(){
let me=this;
var url= '/cliente/selectTipoCliente';
axios.get(me.base+url).then(function (response) {
console.log(response);
var respuesta= response.data;
me.arrayTipoCliente = respuesta.clientes;
})
.catch(function (error) {
var response = error.response.data;
console.log(response.message,
response.exception,
response.file,
response.line);
});
},




abrirModal(modelo, accion, data = []){
switch(modelo){
case "categoria":
{
switch(accion){
case 'registrar':
{
this.modal = 1;
this.tituloModal = 'Registrar Categoría';
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
this.selectTipoCliente();
this.selectPais();
this.selectTipoContacto();
this.selectTipoDocumento();
this.selectSexo();
this.apartments = JSON.parse(this.$el.dataset.apartments)
}
}
</script>
<style>
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
</style>
