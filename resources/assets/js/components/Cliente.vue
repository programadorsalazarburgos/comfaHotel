<template>
<main class="main" style="width: 100%;">
    <section id="list">
        <div class="row">
            <div class="col-sm-12">
                <div class="content-header">Listado y Registro Clientes</div>
            </div>
        </div>
        <div class="row match-height">
            <div class="col-sm-12 col-md-6">
                <div class="card" style="height: 811px;">
                    <div class="card-header">
                        <a href="javascript:void(0)" class="btn btn-raised gradient-mint white shadow-z-4" @click="NuevoCliente()">Nuevo Cliente</a>
                        <span class="ml-2"></span>
                        <div class="card-header">
                            <h4 class="card-title"></h4>
                            <div class="input-group">
                                <select class="form-control" v-model="criterio">
                                    <option value="nombre">Cliente</option>
                                    <option value="no_documento">Documento</option>
                                </select>
                                <input type="text" v-model="buscar" @keyup.enter="listarCliente(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                <button class="btn btn-raised gradient-pomegranate white big-shadow" type="submit" @click="listarCliente(1,buscar,criterio)">
                                    <span class="btn-icon"><i class="la la-search"></i>Buscar</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-body">
                            <div class="card-block">
                                <table class="table table-responsive-md-md text-center">
                                    <thead>
                                        <tr>
                                            <th>Cliente</th>
                                            <th>Documento</th>
                                            <th>Telefono</th>
                                            <th>Correo</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="data in arrayData" :key="data.id">
                                            <td>{{data.nombre}} {{data.apellido}}</td>
                                            <td v-text="data.no_documento"></td>
                                            <td v-text="data.telefono"></td>
                                            <td v-text="data.email"></td>
                                            <td>
                                                <button type="button" class="btn-warning" title="Editar" @click="verData(data)" style="background-color: #f7d24acc; border-color: #f7d24acc;">
                                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                                </button>
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
            <div class="col-sm-12 col-md-6">
                <div class="card" style="overflow: auto;">
                    <div class="card-header">
                        <h4 class="card-title" id="basic-layout-colored-form-control">Info General</h4>
                    </div>
                    <div class="card-body">
                        <div class="px-3">
                            <form class="form" action="" style="overflow: auto;" method="post" @submit.prevent="registrarCliente" enctype="multipart/form-data">
                                <div class="form-body">
                                    <h4 class="form-section"><i class="fa fa-address-book"></i> Datos</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="userinput1">Tipo de cliente</label>
                                                <select v-on:change="toggle(tipoCliente)" class="form-control" v-model="tipoCliente" :required="tipoRequerido">
                                                    <option value="0" disabled>Seleccione</option>
                                                    <option v-for="tipoCliente in arrayTipoCliente" :key="tipoCliente.id" :value="tipoCliente.id" v-text="tipoCliente.nombre"></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <template v-if="mostrarVista===1">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="userinput1">Nombres</label>
                                                    <input type="text" v-model="nombres" :required="nombreRequerido" class="form-control border-primary">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="userinput2">Apellidos</label>
                                                    <input type="text" v-model="apellidos" :required="apellidoRequerido" id="userinput2" class="form-control border-primary" name="company">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="userinput4">Tipo Documento</label>
                                                    <select class="form-control" v-model="tipoDocumento">
                                                        <option value="0" disabled>Seleccione</option>
                                                        <option v-for="tipoDocumento in arrayTipoDocumento" :key="tipoDocumento.id" :value="tipoDocumento.id" v-text="tipoDocumento.descripcion"></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="userinput3">No Documento</label>
                                                    <input type="text" v-model="documento" id="userinput4" class="form-control border-primary" name="nickname">
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <template v-if="mostrarVista===2">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="userinput1">Nombre de la Empresa</label>
                                                    <input type="text" v-model="nombres" :required="empresaRequerido" class="form-control border-primary">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="userinput2">N<sup>o</sup> identificación Fiscal de la Empresa</label>
                                                    <input type="text" v-model="identificacion_fiscal" class="form-control border-primary">
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <template v-if="mostrarVista===3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="userinput1">Nombre de la Agencia</label>
                                                    <input type="text" v-model="nombres" :required="agenciaRequerido" class="form-control border-primary">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="userinput2">N<sup>o</sup> identificación Fiscal de la Agencia</label>
                                                    <input type="text" v-model="identificacion_fiscal" class="form-control border-primary">
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="userinput1">Correo electrónico</label>
                                                <input type="text" v-model="correo" :required="correoRequerido" class="form-control border-primary">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="userinput2">Telefono</label>
                                                <input type="text" v-model="telefono" id="userinput4" class="form-control border-primary" name="nickname">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="userinput1">Celular</label>
                                                <input type="text" v-model="celular" class="form-control border-primary">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="userinput2">Fecha de Nacimiento</label>
                                                <datepicker :format="customFormatter" v-model="fecha_nacimiento" :bootstrap-styling="true"></datepicker>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="userinput1">Género</label>
                                                <select class="form-control" v-model="sexo">
                                                    <option value="0" disabled>Seleccione</option>
                                                    <option v-for="sexo in arraySexo" :key="sexo.id" :value="sexo.id" v-text="sexo.nombre"></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                             <div class="form-group">
                                                <label for="userinput3">Pais</label>
                                                <select class="form-control" v-model="pais" :required="paisRequerido">
                                                    <option value="0" disabled>Seleccione</option>
                                                    <option v-for="nacionalidad in arrayPais" :key="nacionalidad.id" :value="nacionalidad.id" v-text="nacionalidad.nombre_pais"></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>                                                                    
                                    <div class="form-actions right">
                                        <button type="submit" class="btn btn-raised btn-primary" v-if="tipoAccionF===1" name="button"><i class="fa fa-check-square-o"></i> Guardar</button>
                                        <button type="submit" class="btn btn-raised btn-primary" v-if="tipoAccionF===2" name="button"><i class="fa fa-check-square-o"></i> Actualizar</button>
                                    </div>
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
import Datepicker from 'vuejs-datepicker';
import moment from 'moment'
import VueSweetAlert from 'vue-sweetalert'

export default {
    data() {

        return {
            tipoRequerido: true,
            mostrarVista: 0,
            correoRequerido: false,
            paisRequerido: true,
            fecha_emision: '',
            nombres: '',
            apellidos: '',
            identificacion_fiscal: '',
            correo: '',
            telefono: '',
            celular: '',
            fecha_nacimiento: '',
            sexo: '',
            direccion: '',
            pais: '',
            estado: '',
            postal: '',
            tipoDocumento: '',
            documento: '',
            pais_expedidor: '',
            fecha_caducidad: '',
            base: '',
            isOpen: false,
            isOpen2: false,
            isOpen3: false,
            nombres: '',
            categoria_id: 0,
            nombre: '',
            apellidos: '',
            fecha_nacimiento: '',
            sexo: '',
            nacionalidad: '',
            formula: '',
            titulo: '',
            tipoDocumento: '',
            no_documento: '',
            nombre_empresa: '',
            no_empresa: '',
            nombre_agencia: '',
            no_agencia: '',
            lugar: '',
            codigo_postal: '',
            pais: '',
            calle: '',
            departamento: '',
            calle_factura: '',
            lugar_factura: '',
            codigo_postal_factura: '',
            pais_factura: '',
            departamento_factura: '',
            comentarios: '',
            descripcion: '',
            arrayCategoria: [],
            arrayCliente: [],
            arrayTipoCliente: [],
            arrayPais: [],
            arrayDepartamento: [],
            arrayData: [],
            arrayDepartamentoFactura: [],
            arrayTipoContacto: [],
            arrayTipoDocumento: [],
            arraySexo: [],
            modal: 0,
            tituloModal: '',
            tipoAccion: 0,
            tipoAccionF: 1,
            errorCategoria: 0,
            errorMostrarMsjCategoria: [],
            pagination: {
                'total': 0,
                'current_page': 0,
                'per_page': 0,
                'last_page': 0,
                'from': 0,
                'to': 0,
            },
            offset: 3,
            criterio: 'nombre',
            buscar: '',
            apartments: [],
            tipoCliente: 0

        }

        console.log(data);
    },
    computed: {
        isActived: function () {
            return this.pagination.current_page;
        },
        //Calcula los elementos de la paginación
        pagesNumber: function () {
            if (!this.pagination.to) {
                return [];
            }

            var from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }

            var to = from + (this.offset * 2);
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

    components: {
        Datepicker
    },

    methods: {

        NuevoCliente() {
            let me = this;
            this.tipoAccionF = 1;
            this.mostrarVista = 0;
            this.correoRequerido = false;
            this.paisRequerido = true;
            this.tipoCliente = 0;
            this.id = '';
            this.nombres = '';
            this.apellidos = '';
            this.correo = '';
            this.telefono = '';
            this.celular = '';
            this.fecha_nacimiento = '';
            this.sexo = '';
            this.direccion = '';
            this.pais = '';
            this.tipoDocumento = '';
            this.documento = '';

        },

        listarCliente(page, buscar, criterio) {
            let me = this;
            var url = '/cliente?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            axios.get(me.base + url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayData = respuesta.clientes.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error, otracosa) {
                    console.log(error, otracosa);
                });
        },
        cambiarPagina(page, buscar, criterio) {
            let me = this;
            //Actualiza la página actual
            me.pagination.current_page = page;
            //Envia la petición para visualizar la data de esa página
            me.listarCliente(page, buscar, criterio);
        },

        verData(data = []) {
            console.log(data);
            let me = this;
            me.tipoAccionF = 2;
            if (data['id_clientes_tipo'] == 1) {
                me.mostrarVista = 1
                me.nombreRequerido = true;
                me.apellidoRequerido = true;
                me.correoRequerido = true;
                me.paisRequerido = true;
                me.empresaRequerido = false;
                me.agenciaRequerido = false;

            }
            if (data['id_clientes_tipo'] == 2) {
                me.mostrarVista = 2
                me.nombreRequerido = false;
                me.apellidoRequerido = false;
                me.correoRequerido = true;
                me.paisRequerido = true;
                me.empresaRequerido = true;
                me.agenciaRequerido = false;

            }
            if (data['id_clientes_tipo'] == 3) {
                me.mostrarVista = 3
                me.nombreRequerido = false;
                me.apellidoRequerido = false;
                me.correoRequerido = true;
                me.paisRequerido = true;
                me.empresaRequerido = false;
                me.agenciaRequerido = true;

            }
            this.id = data['id'];
            this.tipoCliente = data['id_clientes_tipo'];
            this.nombres = data['nombre'];
            this.apellidos = data['apellido'];
            this.identificacion_fiscal = data['identificacion_fiscal'];
            this.correo = data['email'];
            this.telefono = data['telefono'];
            this.celular = data['celular'];
            this.fecha_nacimiento = data['fecha_nacimiento'];
            this.sexo = data['genero_id'];
            this.direccion = data['direccion'];
            this.pais = data['pais_id'];
            this.tipoDocumento = data['id_documento_tipo'];
            this.documento = data['no_documento'];

        },

        registrarCliente() {
            let me = this;
            if (me.tipoAccionF === 1) {
                axios.post(me.base + '/cliente/registrar', {
                    tipoCliente: me.tipoCliente,
                    apellidos: me.apellidos,
                    identificacion_fiscal: me.identificacion_fiscal,
                    nombres: me.nombres,
                    correo: me.correo,
                    telefono: me.telefono,
                    celular: me.celular,
                    fecha_nacimiento: me.convert(me.fecha_nacimiento),
                    sexo: me.sexo,
                    direccion: me.direccion,
                    pais: me.pais,
                    tipoDocumento: me.tipoDocumento,
                    documento: me.documento

                }).then(function (response) {
                    me.$swal('Almacenado', 'Registro Almacenado', 'success');
                    me.listarCliente(1, '', 'nombres');
                    me.tipoCliente = "";
                    me.apellidos = "";
                    me.identificacion_fiscal = "";
                    me.nombres = "";
                    me.correo = "";
                    me.telefono = "";
                    me.celular = "";
                    me.fecha_nacimiento = "";
                    me.sexo = "";
                    me.direccion = "";
                    me.pais = "";
                    me.tipoDocumento = "";
                    me.documento = "";
                }).catch(function (error) {
                    console.log(error);
                });
            }
            if (me.tipoAccionF === 2) {

                console.log(3);
                axios.post(me.base + '/cliente/actualizar', {
                    id: me.id,
                    tipoCliente: me.tipoCliente,
                    apellidos: me.apellidos,
                    identificacion_fiscal: me.identificacion_fiscal,
                    nombres: me.nombres,
                    correo: me.correo,
                    telefono: me.telefono,
                    celular: me.celular,
                    fecha_nacimiento: me.convert(me.fecha_nacimiento),
                    sexo: me.sexo,
                    direccion: me.direccion,
                    pais: me.pais,
                    tipoDocumento: me.tipoDocumento,
                    documento: me.documento,

                }).then(function (response) {
                    me.$swal('Almacenado', 'Registro Almacenado', 'success');
                    me.listarCliente(1, '', 'nombres');
                    me.tipoCliente = "";
                    me.apellidos = "";
                    me.identificacion_fiscal = "";
                    me.nombres = "";
                    me.correo = "";
                    me.telefono = "";
                    me.celular = "";
                    me.fecha_nacimiento = "";
                    me.sexo = "";
                    me.direccion = "";
                    me.pais = "";
                    me.tipoDocumento = "";
                    me.documento = "";

                }).catch(function (error) {});
            }
        },

        convert(str) {
            var date = new Date(str),
                mnth = ("0" + (date.getMonth() + 1)).slice(-2),
                day = ("0" + date.getDate()).slice(-2);
            return [date.getFullYear(), mnth, day].join("-");
        },

        actualizarCliente() {
            let me = this;

            axios.post(me.base + '/cliente/actualizar', {

                'id': me.id,
                'tipoCliente': this.tipoCliente,
                'nombres': this.nombres,
                'apellidos': this.apellidos,
                'sexo': this.sexo,
                'fecha_nacimiento': this.fecha_nacimiento,
                'nacionalidad': this.nacionalidad,
                'formula': this.formula,
                'titulo': this.titulo,
                'tipoDocumento': this.tipoDocumento,
                'no_documento': this.no_documento,
                'nombre_empresa': this.nombre_empresa,
                'no_empresa': this.no_empresa,
                'nombre_agencia': this.nombre_agencia,
                'no_agencia': this.no_agencia,
                'calle': this.calle,
                'lugar': this.lugar,
                'codigo_postal': this.codigo_postal,
                'pais': this.pais,
                'departamento': this.departamento,
                'apartment': me.apartments,
                'calle_factura': this.calle_factura,
                'lugar_factura': this.lugar_factura,
                'codigo_postal_factura': this.codigo_postal_factura,
                'pais_factura': this.pais_factura,
                'departamento_factura': this.departamento_factura,
                'comentarios': this.comentarios

            }).then(function (response) {

                me.swal('Almacenado', 'Registro Actualizado', 'success');

                me.listarCliente(1, '', 'nombres');
                me.tipoCliente = '';
                me.nombres = '';
                me.apellidos = '';
                me.fecha_nacimiento = '';
                me.sexo = '';
                me.calle = '';
                me.nacionalidad = '';
                me.formula = '';
                me.titulo = '';
                me.tipoDocumento = '';
                me.no_documento = '';
                me.nombre_empresa = '';
                me.no_empresa = '';
                me.nombre_agencia = '';
                me.no_agencia = '';
                me.lugar = '';
                me.codigo_postal = '';
                me.pais = '';
                me.departamento = '';
                me.calle_factura = '';
                me.lugar_factura = '';
                me.codigo_postal_factura = '';
                me.pais_factura = '';
                me.departamento_factura = '';
                me.comentarios = '';
                me.apartments = [];

            }).catch(function (error) {

            });

        },

        toggle: function (tipoCliente) {
            let me = this;
            if (tipoCliente == 1) {
                me.mostrarVista = 1
                me.nombreRequerido = true;
                me.apellidoRequerido = true;
                me.correoRequerido = true;
                me.paisRequerido = true;
                me.empresaRequerido = false;
                me.agenciaRequerido = false;

            }
            if (tipoCliente == 2) {
                me.mostrarVista = 2
                me.nombreRequerido = false;
                me.apellidoRequerido = false;
                me.correoRequerido = true;
                me.paisRequerido = true;
                me.empresaRequerido = true;
                me.agenciaRequerido = false;

            }
            if (tipoCliente == 3) {
                me.mostrarVista = 3
                me.nombreRequerido = false;
                me.apellidoRequerido = false;
                me.correoRequerido = true;
                me.paisRequerido = true;
                me.empresaRequerido = false;
                me.agenciaRequerido = true;

            }
        },

        selectPais() {
            let me = this;
            var url = '/obtener/selectPais';
            axios.get(me.base + url).then(function (response) {
                    console.log(response);
                    var respuesta = response.data;
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

        selectDepartamento(pais) {

            let me = this;
            var url = '/obtener/selectDepartamento?pais_id=' + me.pais;
            axios.get(me.base + url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayDepartamento = respuesta.departamentos;
                })
                .catch(function (error, otracosa) {
                    // var response = error.response.data;

                });

        },

        selectDepartamentoFactura() {

            let me = this;
            var url = '/obtener/selectDepartamento?pais_id=' + me.pais_factura;
            axios.get(me.base + url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayDepartamentoFactura = respuesta.departamentos;
                })
                .catch(function (error, otracosa) {
                    // var response = error.response.data;

                });

        },

        selectTipoContacto() {

            let me = this;
            var url = '/obtener/selectTipoContacto';
            axios.get(me.base + url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayTipoContacto = respuesta.tipo_contactos;
                })
                .catch(function (error, otracosa) {
                    // var response = error.response.data;

                });

        },

        selectTipoDocumento() {

            let me = this;
            var url = '/obtener/selectTipoDocumento';
            axios.get(me.base + url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayTipoDocumento = respuesta.tipo_documentos;
                })
                .catch(function (error, otracosa) {
                    // var response = error.response.data;

                });

        },

        actualizarCategoria() {
            if (this.validarCategoria()) {
                return;
            }

            let me = this;

            axios.put('/categoria/actualizar', {
                'nombre': this.nombre,
                'descripcion': this.descripcion,
                'id': this.categoria_id
            }).then(function (response) {
                me.cerrarModal();
                me.listarCliente(1, '', 'nombre');
            }).catch(function (error) {
                var response = error.response.data;
                console.log(response.message,
                    response.exception,
                    response.file,
                    response.line);
            });
        },

        selectSexo() {
            let me = this;
            var url = '/obtener/selectSexo';
            axios.get(me.base + url).then(function (response) {
                    console.log(response);
                    var respuesta = response.data;
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

        validarCategoria() {
            this.errorCategoria = 0;
            this.errorMostrarMsjCategoria = [];

            if (!this.nombre) this.errorMostrarMsjCategoria.push("El nombre de la categoría no puede estar vacío.");

            if (this.errorMostrarMsjCategoria.length) this.errorCategoria = 1;

            return this.errorCategoria;
        },
        cerrarModal() {
            this.modal = 0;
            this.tituloModal = '';
            this.nombre = '';
            this.descripcion = '';
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

        selectTipoCliente() {
            let me = this;
            var url = '/cliente/selectTipoCliente';
            axios.get(me.base + url).then(function (response) {
                    console.log(response);
                    var respuesta = response.data;
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

        abrirModal(modelo, accion, data = []) {
            switch (modelo) {
                case "categoria": {
                    switch (accion) {
                        case 'registrar': {
                            this.modal = 1;
                            this.tituloModal = 'Registrar Categoría';
                            this.nombre = '';
                            this.descripcion = '';
                            this.tipoAccion = 1;
                            break;
                        }
                        case 'actualizar': {
                            //console.log(data);
                            this.modal = 1;
                            this.tituloModal = 'Actualizar categoría';
                            this.tipoAccion = 2;
                            this.categoria_id = data['id'];
                            this.nombre = data['nombre'];
                            this.descripcion = data['descripcion'];
                            break;
                        }
                    }
                }
            }

        }
    },
    mounted() {
        this.base = base;
        this.listarCliente(1, this.buscar, this.criterio);
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

#scroll {
    overflow-y: scroll;
    height: 560px;
}
</style>
