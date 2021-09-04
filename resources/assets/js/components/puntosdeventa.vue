<template>
<main class="main">
    <div class="col-12">
        <div class="content-header">Punto de Venta</div>
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
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="datos in ArrayDatos" :key="datos.id">
                                            <td v-text="datos.nombre"></td>
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
                        <h5 class="card-title">PUNTO DE VENTA</h5>
                        <div class="container-fluid">
                            <form @submit.prevent="registrarPunto">
                                <div class="col-sm-12">
                                    <label for="">Nombre</label>
                                    <input type="text" v-validate="'required'" class="form form-control" v-model="nombre" :class="{ 'is-invalid': submitted && errors.has('password') }" />
                                    <div v-if="submitted && errors.has('nombre')" class="invalid-feedback">{{ errors.first('nombre') }}</div>
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
            ArrayDatos: [],
            nombre: '',
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
            impuesto_id: ''
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
        registrarPunto(e) {},
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
                    .post(me.base + "/categorias_productos/borrar", {
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
            var url = me.base + '/punto_ventas/index?page=' + page;
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
            let me = this;
            me.tipoGuardar = 1;
            me.selected = data.id;
            me.nombre = data.nombre;
            me.impuestoValor = data.valor;
        },

        GuardarNuevoDatos() {
            this.submitted = true;
            this.$validator.validate().then(valid => {
                if (valid) {
                    let me = this;
                    me.tipoGuardar = 1;
                    me.disablebutton = true;
                    axios
                        .post(me.base + "/punto_ventas/store", {
                            nombre: me.nombre

                        })
                        .then(function (response) {
                            if (response.data.validate) {
                                me.disablebutton = false;
                                me.id = response.data.id;
                                me.$swal("se ha guardado el punto de venta " + me.nombre + " con éxito");
                                me.ListarDatos();
                                me.VerDato(response.data);
                            } else {
                                me.disablebutton = false;
                                me.$swal("No se ha podido guardar el punto de venta " + me.numero);
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
                    me.disablebutton = true;
                    axios
                        .post(me.base + "/punto_ventas/Editar", {
                            id: me.selected,
                            nombre: me.nombre,
                            valor: me.impuestoValor
                        })
                        .then(function (response) {
                            if (response.data.validate) {
                                me.$swal("se ha editado el impuesto " + me.nombre + " con éxito");
                                me.ListarDatos();
                                me.VerDato(response.data);
                            } else {
                                me.$swal("Se presento un problema al editar impuesto " + me.nombre + ".");
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
