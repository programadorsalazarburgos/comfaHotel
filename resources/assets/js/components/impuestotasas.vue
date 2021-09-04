<template>
<main class="main">
    <div class="col-12">
        <div class="content-header">Impuestos & Tasas</div>
    </div>
    <section id="extended">
        <div class="row">
            <div class="col-sm-9">
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
                                            <th>Importe</th>
                                            <th>Nombre</th>
                                            <th>Valor</th>
                                            <th>Aplica a la reserva</th>
                                            <th>Aplica a los productos</th>
                                            <th>Formato</th>
                                            <th>Acción</th>
                                            <th>Estatus</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="impuestos in ArrayImpuestos" :key="impuestos.id">
                                            <td v-text="impuestos.importe"></td>
                                            <td v-text="impuestos.nombre"></td>
                                            <td v-text="impuestos.valor"></td>
                                            <td>
                                                <template v-if="impuestos.hospedaje == true">
                                                    <span class="badge badge-success">SI</span>
                                                </template>
                                                <template v-if="impuestos.hospedaje == false">
                                                    <span class="badge badge-danger">NO</span>
                                                </template>
                                            </td>
                                            <td>
                                                <template v-if="impuestos.producto_servicio == true">
                                                    <span class="badge badge-success">SI</span>
                                                </template>
                                                <template v-if="impuestos.producto_servicio == false">
                                                    <span class="badge badge-danger">NO</span>
                                                </template>
                                            </td>
                                            <td v-text="impuestos.formato"></td>
                                            <td>
                                                <a class="success p-0" data-original-title="" title="" @click="EditarImpuesto(impuestos)">
                                                    <i class="ft-edit-2 font-medium-3 mr-2"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <toggle-button :value="true" :labels="{checked: 'On', unchecked: 'Off'}" v-model="impuestos.estado" @change="onChangeEventHandler(impuestos)" />
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
            <div class="col-sm-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Impuestos & Tasas</h5>
                        <div class="container-fluid">
                            <form action="" method="post" @submit.prevent="registrarData" enctype="multipart/form-data">
                                <div class="col-sm-12">
                                    <label for="">Importe</label>
                                    <select required class="form form-control" v-model="importe">
                                        <option v-for="importe in ArrayImportes" :key="importe.id" :value="importe.id">{{importe.name}}</option>
                                    </select>
                                </div>
                                <div class="col-sm-12">
                                    <label for="">Nombre</label>
                                    <input type="text" required class="form-control" v-model="nombre" />
                                </div>
                                <div class="col-sm-12">
                                    <label for="">Valor</label>
                                    <input type="number" max="100" min="0" required class="form-control" v-model="valor" />
                                </div>
                                <div class="col-sm-12">
                                    <label for="">Aplica para</label><br>
                                    <input type="checkbox" id="hospedaje" value="hospedaje" v-model="checkedNames">
                                    <label for="jack">Hospedaje</label>
                                    <input type="checkbox" id="producto" value="producto" v-model="checkedNames">
                                    <label for="john">Producto/Servicio</label><br>
                                </div>
                                <div class="col-sm-12">
                                    <label for="">Tipo Formato</label>
                                    <select required class="form form-control" v-model="formato">
                                        <option v-for="formato in ArrayFormatos" :key="formato.id" :value="formato.id">{{formato.nombre}}</option>
                                    </select>
                                </div>
                                <br>
                                <div class="col-sm-12">
                                    <button type="submit" v-if="tipoGuardar==1" class="btn btn-raised gradient-nepal white card-shadow">Guardar <i class="fa fa-fw fa-save"></i></button>
                                    <button type="submit" v-if="tipoGuardar==2" class="btn btn-raised gradient-ibiza-sunset white sidebar-shadow">Actualizar <i class="fa fa-fw fa-save"></i></button>
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
import Vue from 'vue';
import ToggleButton from 'vue-js-toggle-button'
Vue.use(ToggleButton)

export default {
    data() {
        return {
            base: '',
            myDataVariable: true,
            checkedNames: [],
            ArrayImpuestos: [],
            ArrayImportes: [],
            ArrayFormatos: [],
            id_tasa_impuesto: '',
            importe: '',
            nombre: '',
            valor: '',
            formato: '',
            data_hospedaje: '',
            data_producto: '',
            selected: 0,
            tipoGuardar: 1,
            disablebutton: false,
            submitted: false,
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
            image: '',
            impuesto_id: ''
        }
    },
    computed: {
        count() {
            this.$store.state;
            console.log(this.$store.state);
            return this.$store.state.posts
        },
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
    methods: {
        handleSubmit(e) {},

        ListarImpuestos(page) {
            let me = this;
            var url = me.base + '/impuestos/index_hotel?page=' + page;
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.ArrayImpuestos = respuesta.datos.data;
                    me.pagination = respuesta.pagination;
                })
                .catch(function (error) {
                    var response = error.response.data;
                    console.log(response.message,
                        response.exception,
                        response.file,
                        response.line);
                });
        },

        getImportes() {
            let me = this;
            var url = '/impuestos/importes';
            axios.get(me.base + url).then(function (response) {
                    me.ArrayImportes = response.data.datos
                    console.log(me.ArrayImportes);
                })
                .catch(function (error) {
                    var response = error.response.data;
                });
        },
        getFormatos() {
            let me = this;
            var url = '/impuestos/formatos';
            axios.get(me.base + url).then(function (response) {
                    me.ArrayFormatos = response.data.datos
                })
                .catch(function (error) {
                    var response = error.response.data;
                });
        },
        onChangeEventHandler(impuesto) {
            let me = this;
            axios.post(me.base + '/impuestos/actualizar_estado', {
                'data': impuesto,
            }).then(function (response) {}).catch(function (error) {
                alert('Error');

            });

        },
        registrarData() {
            let me = this;
            if (me.tipoGuardar == 1) {
                axios
                    .post(me.base + '/impuestos/nuevo', {
                        importe: me.importe,
                        nombre: me.nombre,
                        valor: me.valor,
                        formato: me.formato,
                        checkedNames: me.checkedNames
                    })
                    .then(function (response) {
                        me.ListarImpuestos(1)
                        me.importe = '';
                        me.nombre = '';
                        me.valor = '';
                        me.formato = '';
                        me.checkedNames = [];
                        me.disablebutton = false;
                        me.$swal({
                            title: "Muy bien!",
                            text: "Registro Almacenado!",
                            icon: "success",
                            button: "Aww yiss!",
                        });
                    })
                    .catch(function (error) {
                        me.$swal({
                            title: "Lo sentimos!",
                            text: "Dato Existente",
                            icon: "error",
                            button: "Aww yiss!",
                        });
                    });
            }
            if (me.tipoGuardar == 2) {
                axios
                    .post(me.base + '/impuestos/Editar', {
                        id: me.id_tasa_impuesto,
                        importe: me.importe,
                        nombre: me.nombre,
                        valor: me.valor,
                        formato: me.formato,
                        checkedNames: me.checkedNames

                    })
                    .then(function (response) {
                        me.ListarImpuestos(1)
                        me.id_tasa_impuesto = '';
                        me.importe = '';
                        me.nombre = '';
                        me.valor = '';
                        me.formato = '';
                        me.checkedNames = [];
                        me.tipoGuardar = 1;
                        me.disablebutton = false;
                        me.$swal({
                            title: "Muy bien!",
                            text: "Registro Almacenado!",
                            icon: "success",
                            button: "Aww yiss!",
                        });
                    })
                    .catch(function (error) {
                        me.$swal({
                            title: "Lo sentimos!",
                            text: "Dato Existente",
                            icon: "error",
                            button: "Aww yiss!",
                        });
                    });
            }
        },

        EditarImpuesto(data) {
            let me = this;
            me.importe = data['importe_id'];
            me.nombre = data['nombre'];
            me.valor = data['valor'];
            me.formato = data['formato_id'];
            me.data_hospedaje = (data['hospedaje'] === true) ? 'hospedaje' : '';
            me.data_producto = (data['producto_servicio'] === true) ? 'producto' : '';
            me.checkedNames = [me.data_hospedaje, me.data_producto];
            me.id_tasa_impuesto = data['id'];
            me.tipoGuardar = 2;
        },

    },
    mounted() {
        this.base = base;
        this.ListarImpuestos();
        this.getImportes();
        this.getFormatos();
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
