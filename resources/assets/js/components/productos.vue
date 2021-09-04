<template>
<main class="main">
    <div class="col-12">
        <div class="content-header">Productos</div>
    </div>
    <section id="extended">
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"></h4>
                    </div>
                    <div class="card-body">
                        <div class="card-body">
                            <div class="card-block">
                                <table class="table table-responsive-md-md text-center">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Punto de Venta</th>
                                            <th>Categoria</th>
                                            <th>Precio</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="datos in ArrayDatos" :key="datos.id">
                                            <td v-text="datos.nombre"></td>
                                            <td v-text="datos.venta"></td>
                                            <td v-text="datos.categoria"></td>
                                            <td v-text="datos.precio"></td>
                                            <td>
                                                <a class="success p-0" data-original-title="" title="" @click="VerDato(datos)">
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
                        <h5 class="card-title">PRODUCTOS</h5>
                        <div class="container-fluid">
                            <form @submit.prevent="handleSubmit">
                                <div class="col-sm-12">
                                    <label for="">Nombre</label>
                                    <input type="text" v-validate="'required'" class="form form-control" v-model="nombre" :class="{ 'is-invalid': submitted && errors.has('password') }" />
                                    <div v-if="submitted && errors.has('nombre')" class="invalid-feedback">{{ errors.first('nombre') }}</div>
                                </div>
                                <div class="col-sm-12">
                                    <label for="">Punto de Venta</label>
                                    <select class="form-control" v-validate="'required'" v-model="venta" :class="{ 'is-invalid': submitted && errors.has('venta') }">
                                        <option value="0" disabled="true">Seleccione</option>
                                        <option v-for="(venta,index) in ArrayPuntosVenta" :key="index" :value="venta.id" v-text="venta.nombre"></option>
                                    </select>
                                    <div v-if="submitted && errors.has('categoria')" class="invalid-feedback">{{ errors.first('categoria') }}</div>
                                </div>
                                <div class="col-sm-12">
                                    <label for="">Categoria</label>
                                    <select class="form-control" v-validate="'required'" v-model="categoria" :class="{ 'is-invalid': submitted && errors.has('categoria') }">
                                        <option value="0" disabled="true">Seleccione</option>
                                        <option v-for="(categoria,index) in ArrayCategorias" :key="index" :value="categoria.id" v-text="categoria.nombre"></option>
                                    </select>
                                    <div v-if="submitted && errors.has('categoria')" class="invalid-feedback">{{ errors.first('categoria') }}</div>
                                </div>
                                <div class="col-sm-12">
                                    <label for="">Precio</label>
                                    <input type="text" v-validate="'required'" class="form form-control" v-model="precio" :class="{ 'is-invalid': submitted && errors.has('precio') }" />
                                    <div v-if="submitted && errors.has('precio')" class="invalid-feedback">{{ errors.first('precio') }}</div>
                                </div>
                                <br>
                                <div class="col-sm-12">
                                    <button :disabled="disablebutton" v-if="tipoGuardar==1" @click="GuardarEditarDatos()" class="btn btn-raised gradient-ibiza-sunset white sidebar-shadow"> Actualizar</button>
                                    <button :disabled="disablebutton" v-if="tipoGuardar==1" @click="BorrarDatos()" class="btn btn-danger"> Borrar</button>
                                    <button :disabled="disablebutton" v-if="tipoGuardar==2" @click="GuardarNuevoDatos()" class="btn btn-raised gradient-nepal white card-shadow"><i class="fa fa-plus" aria-hidden="true"></i> Guardar</button>
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
            base: '',
            ArrayPuntosVenta: [],
            ArrayDatos: [],
            ArrayCategorias: [],
            nombre: '',
            categoria: '',
            precio: '',
            impuestoValor: 0,
            selected: 0,
            tipoGuardar: 2,
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
            impuesto_id: '',
            venta: ''
        }
    },
    computed: {
        count() {
            this.$store.state;
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

        seleccionarCategorias() {
            let me = this;
            var url = 'listar_productos/obtener_categorias';
            axios.get(me.base + url).then(function (response) {

                    var respuesta = response.data.datos;
                    me.ArrayCategorias = respuesta;
                })
                .catch(function (error, otracosa) {});
        },

        seleccionarPuntosVenta() {
            let me = this;
            var url = 'obtener_puntosventa';
            axios.get(me.base + url).then(function (response) {

                    var respuesta = response.data.datos;
                    me.ArrayPuntosVenta = respuesta;
                    console.log(me.ArrayPuntosVenta)
                })
                .catch(function (error, otracosa) {});
        },

        BorrarDatos() {
            let me = this;
            me.selected
            me.$swal({
                title: "Borrando",
                text: "¿Desea borrar de forma permanente este impuesto?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Si, borrar"
            }).then(function (isConfirm) {
                axios
                    .post(me.base + "/listar_productos/borrar", {
                        id: me.selected
                    })
                    .then(function (response) {
                        if (isConfirm) {
                            me.$swal({
                                title: 'Eliminado',
                                text: 'Ha impuesto ha sido borrado',
                                icon: 'success'
                            }).
                            then(function () {
                                me.ListarDatos();
                            });
                        }
                    })
                    .catch(function (error, otracosa) {});
            });
        },

        ListarDatos(page) {
            let me = this;
            var url = me.base + '/listar_productos/index?page=' + page;
            axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    console.log(respuesta)
                    me.ArrayDatos = respuesta.datos.data;
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

        VerDato(data) {

            console.log(data)
            let me = this;
            me.tipoGuardar = 1;
            me.selected = data.id;
            me.nombre = data.nombre;
            me.categoria = data.categoria_id;
            me.precio = data.precio;
            me.venta = data.punto_de_venta_id;

        },

        GuardarNuevoDatos() {
            this.submitted = true;
            this.$validator.validate().then(valid => {
                if (valid) {
                    let me = this;
                    me.tipoGuardar = 1;
                    me.disablebutton = true;
                    axios
                        .post(me.base + "/listar_productos/store", {
                            nombre: me.nombre,
                            categoria: me.categoria,
                            precio: me.precio,
                            venta: me.venta

                        })
                        .then(function (response) {
                            if (response.data.validate) {
                                me.disablebutton = false;
                                me.id = response.data.id;
                                me.$swal("se ha guardado el producto " + me.nombre + " con éxito");
                                me.ListarDatos();
                                me.nombre = '';
                                me.precio = '';
                                me.categoria = '';
                                me.tipoGuardar = 2;
                                // me.VerDato(response.data);
                            } else {
                                me.disablebutton = false;
                                me.$swal("No se ha podido guardar el producto " + me.nombre);
                            }
                        }).catch(function (error) {
                            console.log(error)
                        });
                } else {
                    me.$swal("Por favor ingrese todos los campos");
                }
            });
        },
        GuardarEditarDatos() {
            let me = this;
            this.submitted = true;
            this.$validator.validate().then(valid => {
                if (valid) {

                    console.log(me.selected)
                    console.log(me.nombre)
                    console.log(me.categoria)
                    me.disablebutton = true;
                    axios
                        .post(me.base + "/listar_productos/Editar", {
                            id: me.selected,
                            nombre: me.nombre,
                            categoria: me.categoria,
                            precio: me.precio,
                            venta: me.venta
                        })
                        .then(function (response) {
                            if (response.data.validate) {
                                me.$swal("se ha editado el impuesto " + me.nombre + " con éxito");
                                me.ListarDatos();
                                me.VerDato(response.data);
                            }
                            me.disablebutton = false;
                        })
                        .catch(function (error) {
                            me.disablebutton = false;
                            console.log(error)
                        });
                } else {
                    me.$swal("Por favor ingrese todos los campos");
                }
            });
        }
    },
    mounted() {
        this.base = base;
        this.ListarDatos();
        this.seleccionarCategorias();
        this.seleccionarPuntosVenta();
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
