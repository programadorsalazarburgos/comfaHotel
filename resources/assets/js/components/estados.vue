<template>
<div class="container-fluid">
    <div class="row" v-if="MostrarEstados">
        <div :class="ampliar?'col-sm-8':'col-sm-12'">
            <div class="card">
                <div class="card-body" style="height: auto !important">
                    <div class="clearfix"></div>
                    <br v-if="mostrar_busqueda">
                    <br v-if="mostrar_busqueda">
                    <div class="container-fluid" v-if="mostrar_busqueda">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="card-title">
                                <span class="badge badge-info">Detalle Habitación</span>
                                <i  @click="ampliar = !ampliar" class="ft-maximize font-medium-3 blue-grey darken-4"></i>
                                </h5>{{openDate}}
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <br>
                    <div class="container-fluid">
                        <br>
                        <div v-if="mostrar_checking">
                            <div>
                                <div class="col-sm-12">
                                    <div class="content-header">check in</div>
                                </div>
                                <section id="extendeds">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="row">
                                                        <div class="col-md-12" v-if="chekin==1">
                                                            <h5>
                                                            <span class="badge badge-info">DATOS GENERALES</span>
                                                            <span class="badge badge-success">RESERVANTE: {{nombre_cliente}}</span>
                                                            </h5>
                                                            <div class="clearfix"></div>
                                                            <br>
                                                            <div class="row">
                                                                <div class="col-sm-6">
                                                                    <label>llegada</label>
                                                                    <input type="text" v-model="reservas.llegada" class="form form-controll">
                                                                </div>
                                                                <div class="col-sm-6">
                                                                    <label>Salida</label>
                                                                    <input type="text" v-model="reservas.salida" class="form form-controll">
                                                                </div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                            <br>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <h5><span class="badge badge-primary">Habitaciones Elegidas</span></h5>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <table class="table">
                                                                        <tbody>
                                                                            <tr v-for="(grupo,index) in reservas.habitaciones" :key="index">
                                                                                <td>
                                                                                    {{index+1}}
                                                                                </td>
                                                                                <td>
                                                                                    <i class="fa fa-trash" @click="eliminar(index)"></i> {{grupo.habitacion_numero}} {{grupo.habitacion_tipo}}
                                                                                </td>
                                                                                <td>
                                                                                    <i class="fas fa-male"></i>
                                                                                    <select v-model="grupo.cantidad_adultos" @change="grupo.cantidad_huespedes=(parseInt(grupo.cantidad_adultos) + parseInt(grupo.cantidad_ninos) + parseInt(grupo.cantidad_infantes))">
                                                                                        <option value="0">0</option>
                                                                                        <option v-for="(index,maximo) in grupo.habitacion_personas_maximo" :data-max="maximo" :key="index" :value="index">{{index}}</option>
                                                                                    </select>
                                                                                    <img src="/Images/child_24.png" />
                                                                                    <select name="" id="" v-model="grupo.cantidad_ninos" @change="grupo.cantidad_huespedes=(grupo.cantidad_adultos+grupo.cantidad_ninos+grupo.cantidad_infantes)">
                                                                                        <option value="0">0</option>
                                                                                        <option v-for="(index,maximo) in grupo.habitacion_personas_maximo" :data-max="maximo" :key="index" :value="index">{{index}}</option>
                                                                                    </select>
                                                                                    <i class="fas fa-baby"></i>
                                                                                    <select name="" id="" v-model="grupo.cantidad_infantes" @change="grupo.cantidad_huespedes=(grupo.cantidad_adultos+grupo.cantidad_ninos+grupo.cantidad_infantes)">
                                                                                        <option value="0">0</option>
                                                                                        <option v-for="(index,maximo) in grupo.habitacion_personas_maximo" :data-max="maximo" :key="index" :value="index">{{index}}</option>
                                                                                    </select>
                                                                                    <span v-if="grupo.habitacion_personas_maximo<(grupo.cantidad_infantes+grupo.cantidad_ninos+grupo.cantidad_adultos)" class="label label-danger">Maximo {{grupo.habitacion_personas_maximo}} personas - [{{grupo.cantidad_huespedes}}]</span>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <div class="col-md-12">
                                                                    <button @click="recargar()" class="btn btn-raised gradient-mint white shadow-z-4">Anterior</button>
                                                                    <button @click="generar_huespedes()" class="btn btn-raised gradient-pomegranate white big-shadow">Siguiente</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12" v-if="chekin==2">
                                                            <h5>
                                                            <span class="badge badge-info">DATOS GENERALES</span>
                                                            <span class="badge badge-success">RESERVANTE: {{nombre_cliente}}</span>
                                                            </h5>
                                                            <div class="col-md-12">
                                                                <table class="table table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Habitacion</th>
                                                                            <th>
                                                                            <button @click="nuevoCliente()" class="btn btn-danger"><i class="fa fa-plus"></i></button> Nombre del cliente</th>
                                                                            <th>Remover</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody v-for="(clientes,index) in reservas.habitaciones" :key="index">
                                                                        <tr v-for="(detail,index2) in clientes.huespedes_data" :key="index2">
                                                                            <td v-if="index2==0" :rowspan="reservas.habitaciones[index].huespedes_data.length">
                                                                                {{clientes.habitacion_numero}}
                                                                            </td>
                                                                            <td>
                                                                                <autocomplete :value="true" :source="base+'reservas/VerClientes?index='+index+'&index2='+index2+'&vmodel=' + detail.id_huesped + '&q='" placeholder="Ingrese nombre o numero de documento" input-class="form-control" results-property="items" @selected="clienteseleccionado">
                                                                                </autocomplete>
                                                                            </td>
                                                                            <td><i class="fa fa-trash" @click="borrar_huesped(detail,index,index2)"></i></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div v-if="guardar" class="col-md-12">
                                                                <button @click="chekin=1" class="btn btn-raised gradient-mint white shadow-z-4">Anterior</button>
                                                                <button @click="guardar_ckeckin()" class="btn btn-raised gradient-pomegranate white big-shadow">Guardar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <!--Nuevo cliente-->
                                <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalCliente}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                                    <div class="modal-dialog modal-primary modal-lg" role="document">
                                        <div class="modal-content" style="height: 550px;">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Crear Cliente</h4>
                                                <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <crearcliente></crearcliente>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" @click="modalCliente=false">Cerrar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--                             <div v-if="mostrar_generar_factura">
                            <factura :id_reserva="datagrupo.id_reserva" :id_habitacion="habitacion_seleccionada"></factura>
                        </div> -->
                        <div v-if="mostrar_estados">
                            <div class="col-md-12">
                                <div v-for="(item, i) in arrayEstadosLLegadas" :key="i">
                                    <div class="row">
                                        <h5>
                                        <span class="badge badge-success">LLegadas</span>
                                        </h5>
                                    </div>
                                    <div class="row">
                                        <div v-for="(habitaciones,i) in arrayLLegadas" :key="'A'+ i">
                                            <div v-if="item.id==habitaciones.id_estado_reserva">
                                                <div class="room_block"  style="user-select: none;" v-on:click="click_habitacion(habitaciones)">
                                                    <div class="selector triangle_tr">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                    <div class="roomtype">
                                                        <div class="splittwo">{{habitaciones.tipo_habitacion}}</div>
                                                        <div class="splittwo alignright"></div>
                                                    </div>
                                                    <div class="roomnumber">{{habitaciones.numero}}</div>
                                                    <div class="roompersons cf">
                                                        <div class="splittwo">
                                                            <i class="fas fa-male"></i> {{habitaciones.personas_minimo}} - {{habitaciones.personas_maximo}}
                                                        </div>
                                                    </div>
                                                    <div class="roomblocks cf">
                                                        <div class="room_block_details rbd1 splittwo " :class="'backred'">{{habitaciones.libre}} </div>
                                                        <div class="room_block_details rbd2 splittwo" :style="'background-color:'+habitaciones.color">{{habitaciones.estado}} </div>
                                                    </div>
                                                    <div class="roomguest overflowhidden">
                                                        <img src="icons/arrivalstay.png" width="16" height="16">
                                                        <span>{{habitaciones.nombre_reservante}}</span>
                                                    </div>
                                                    <div class="roomguest overflowhidden">&nbsp;
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="col-md-12">
                                <div v-for="(item, i) in arrayEstadosSalidas" :key="i">
                                    <div class="row">
                                        <h5>
                                        <span class="badge badge-success">Salidas</span>
                                        </h5>
                                    </div>
                                    <div class="row">
                                        <div v-for="(habitaciones_salidas,i) in arraySalidas" :key="'B'+ i">
                                            <!--
                                            -->                                                <div>
                                                <div class="room_block" style="user-select: none;" @click="click_habitacion(habitaciones_salidas)">
                                                    <div class="selector triangle_tr">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                    <div class="roomtype">
                                                        <div class="splittwo">{{habitaciones_salidas.tipo_habitacion}}</div>
                                                        <div class="splittwo alignright"></div>
                                                    </div>
                                                    <div class="roomnumber">{{habitaciones_salidas.numero}}</div>
                                                    <div class="roompersons cf">
                                                        <div class="splittwo">
                                                            <i class="fas fa-male"></i> {{habitaciones_salidas.personas_minimo}} - {{habitaciones_salidas.personas_maximo}}
                                                        </div>
                                                    </div>
                                                    <div class="roomblocks cf">
                                                        <div class="room_block_details rbd1 splittwo " :class="'backred'">{{habitaciones_salidas.libre}} </div>
                                                        <div class="room_block_details rbd2 splittwo" :style="'background-color:'+habitaciones_salidas.color">{{habitaciones_salidas.estado}} </div>
                                                    </div>
                                                    <div class="roomguest overflowhidden">
                                                        <img src="icons/arrivalstay.png" width="16" height="16">
                                                        <span>{{habitaciones_salidas.nombre_reservante}}</span>
                                                    </div>
                                                    <div class="roomguest overflowhidden">&nbsp;
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="col-md-12">
                            <div v-for="(item, i) in arrayEstadosAlojados" :key="i">
                                <div class="row">
                                    <h5>
                                    <span class="badge badge-success">Alojados</span>
                                    </h5>
                                </div>
                                <div class="row">
                                    <div v-for="(habitaciones,i) in arrayAlojados" :key="'A'+ i">
                                        <div v-if="item.id==habitaciones.id_reservas_estado">
                                            <div class="room_block"  style="user-select: none;" @click="click_habitacion(habitaciones)">
                                                <div class="selector triangle_tr">
                                                    <i class="fas fa-check"></i>
                                                </div>
                                                <div class="roomtype">
                                                    <div class="splittwo">{{habitaciones.tipo_habitacion}}</div>
                                                    <div class="splittwo alignright"></div>
                                                </div>
                                                <div class="roomnumber">{{habitaciones.numero}}</div>
                                                <div class="roompersons cf">
                                                    <div class="splittwo">
                                                        <i class="fas fa-male"></i> {{habitaciones.personas_minimo}} - {{habitaciones.personas_maximo}}
                                                    </div>
                                                </div>
                                                <div class="roomblocks cf">
                                                    <div class="room_block_details rbd1 splittwo " :class="'backred'">{{habitaciones.libre}} </div>
                                                    <div class="room_block_details rbd2 splittwo" :style="'background-color:'+habitaciones.color">{{habitaciones.estado}} </div>
                                                </div>
                                                <div class="roomguest overflowhidden">
                                                    <img src="icons/arrivalstay.png" width="16" height="16">
                                                    <span>{{habitaciones.detalles.pagador}}</span>
                                                </div>
                                                <div class="roomguest overflowhidden">&nbsp;
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row">
                                    <h5>
                                        <span class="badge badge-success">Disponibles</span>
                                    </h5>
                            </div>
                            <div>
                                <div class="row">
                                    <div v-for="(habitaciones,index) in arrayDisponibles" :key="index">
                                        <div v-if="habitaciones.libreValor=='1'">
                                            <div class="room_block"  style="user-select: none;"  v-on:click="click_habitacion(habitaciones)">
                                                <div class="selector triangle_tr">
                                                    <i class="fas fa-check"></i>
                                                </div>
                                                <div class="roomtype">
                                                    <div class="splittwo">{{habitaciones.tipo_habitacion}}</div>
                                                    <div class="splittwo alignright"></div>
                                                </div>
                                                <div class="roomnumber">{{habitaciones.numero}}</div>
                                                <div class="roompersons cf">
                                                    <div class="splittwo">
                                                        <i class="fas fa-male"></i> {{habitaciones.personas_minimo}} - {{habitaciones.personas_maximo}}
                                                    </div>
                                                </div>
                                                <div class="roomblocks cf">
                                                    <div class="room_block_details rbd1 splittwo " :class="habitaciones.libre=='ocupado'?'backred':'backgreen'">{{habitaciones.libre}} </div>
                                                    <div class="room_block_details rbd2 splittwo" :style="'background-color:'+habitaciones.color">{{habitaciones.estado}} </div>
                                                </div>
                                                <div class="roomguest overflowhidden">
                                                    <img src="icons/arrivalstay.png" width="16" height="16">
                                                    <span>{{habitaciones.detalles.pagador}}</span>
                                                </div>
                                                <div class="roomguest overflowhidden">&nbsp;
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <hr/> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4" >
        <div class="card" v-if="MostrarDetallesReserva">
          <!--  <div class="card-body">
                <div v-if="MostrarAccion">
                    <h5 class="card-title">Accion</h5>
                    <hr>
                    <div class="container"></div>
                </div>
            </div>
          -->
            <div class="clearfix"></div>
            <br>
            <div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="card-title">
                                    <span class="badge badge-danger">Detalle Habitación<span class="badge badge-success">{{numero_habitacion}}</span></span>
                                    </h5>
                                </div>
                                <div class="col-md-6">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="container">
                    <br>Huesped:
                    <div>
                        <h5 v-text="nombre_reservante" style="font-size: 14px;"></h5>
                    </div>
                    <div class="clearfix"></div><br>
                    <div class="container">

                        <label>Limpieza</label>
                        <select v-model="limpieza_estado" @change="bloquearFechas()" class="form form-control">
                            <option v-for="(limpieza,index) in arrayLimpieza" :key="index" :value="limpieza.id">{{limpieza.nombre}}</option>
                        </select>
                        <label>Descripción</label>
                        <textarea rows="5" v-model="descripcion" class="form-control"></textarea>
                        <br/>


                      <div v-if="limpieza_estado==5 || limpieza_estado==6">
                        <label for="userinput4"><span class="badge badge-primary">Bloquear Habitación Desde</span></label>
                        <datepicker :format="customFormatter" v-model="desde" :bootstrap-styling="true" :language="es"></datepicker>

                        <label for="userinput4"><span class="badge badge-primary">Bloquear Habitación Hasta</span></label>
                        <datepicker :format="customFormatter" v-model="hasta" :bootstrap-styling="true" :language="es"></datepicker>

                      </div>

                        <button class="btn btn-raised gradient-nepal white card-shadow" v-if="tipoAccion==1"  @click="guardarDescripcion()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-raised gradient-ibiza-sunset white sidebar-shadow" @click="actualizarBloqueos()">Desbloquear</button>
                    </div>
                    <hr>
                    <div v-for="(historico,index) in arrayHistorico" :key="index">
                        <div class="container-fluid">
                            <label><span class="badge badge-info">Fecha:{{historico.fecha}}</span></label>
                            <br>
                            <label><span class="badge badge-danger">Estado:{{historico.nombre}}</span></label>
                            <br>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--
<div class="row" v-if="!MostrarEstados">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">facturar</h5>
                <div class="container-fluid">
                    <div class="row">
                        factura
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
-->
<div v-if="mostrar_facturacion">
    <factura :id_reserva="id_reserva_seleccionada" :id_habitacion="id_habitacion_seleccionada"></factura>
</div>
</div>
</template>
<script>
import swal from "sweetalert2"
import Datepicker from 'vuejs-datepicker';
import moment from 'moment';
// import DatePicker from 'vue2-datepicker';
import VueDatepicker from '@aries0d0f/vue2-datepicker';
import checkingin from '../extras/cheking';
import factura from '../extras/facturacion';
import VueSweetalert2 from 'vue-sweetalert2';
import VueSweetAlert from 'vue-sweetalert';
import Autocomplete from 'vuejs-auto-complete';
import crearcliente from '../components/InsertCliente';
import Vue from 'vue';
import { es } from 'vuejs-datepicker/dist/locale'

Vue.use(VueSweetalert2);
export default {
data() {
return {
es: es,
tipoAccion:1,
desde:'',
hasta:'',
nombre_reservante: '',
id_grupo: 0,
id_estado_reserva: 0,
MostrarContenido: true,
mostrar_busqueda: true,
mostrar_checking: false,
mostrar_estados: true,
mostrar_estados_llegadas: true,
mostrar_generar_factura: false,
MostrarDetallesReserva: false,
ampliar: false,
base: '',
tipo: null,
fecha: '',
agrupar: '',
MostrarEstados: true,
arrayAgrupar: ['ESTANCIA', 'PISO', 'ESTADO LIMPIEZA', 'SALIDAS'],
arrayHabitaciones: [],
pisos: [],
datagrupo: [],
tiposHabitacion: [],
arrayLimpieza: [],
limpieza: '',
habitacion_seleccionada: '',
limpieza_estado: '',
descripcion: '',
disabled: false,
pagador: '',
arrayHuespedes: [],
arrayHistorico: [],
huespedes_habitaciones: [],
pagadores_habitaciones: [],
data_habitaciones: [],
arraySalidas: [],
arrayLLegadas: [],
arrayAlojados: [],
arrayDisponibles: [],
MostrarAccion: false,
MostrarDetalle: false,
Mostrarcheckout: false,
mostrar_facturacion: false,
estado_reserva: 0,
arrayEstados: [],
arrayEstadosSalidas: [],
arrayEstadosLLegadas: [],
arrayEstadosAlojados: [],
cliente_id_reserva: '',
format: 'DD-MM-YYYY',
guardar: true,
modalCliente: false,
chekin: true,
base: '',
nombres_huespedes: '',
nombre_cliente: '',
id_reserva_seleccionada: '',
id_habitacion_seleccionada: '',
numero_habitacion:'',
reservas: {
llegada: '',
salida: '',
habitaciones: '' //habitaciones:{id_grupo,infantes_cantidad,ninnos_cantidad,adultos_cantidad}
},
openDate: new Date(),
adultos: [{
id: 1,
nombre: '1'
}, {
id: 2,
nombre: '2'
}, {
id: 3,
nombre: '3'
}, {
id: 4,
nombre: '4'
}, {
id: 5,
nombre: '5'
}, {
id: 6,
nombre: '6'
}, {
id: 7,
nombre: '7'
}, {
id: 8,
nombre: '8'
}, ],
lang: {
days: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
months: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
pickers: ['Siguiente 7 dias', 'Siguiente 30 dias', 'Anteriores 7 dias', 'Anteriores 30 dias'],
placeholder: {
date: 'Seleccione fecha',
dateRange: 'Seleccione rango de fecha'
}
},
//===============================basicos===============================//
};
},
props: ['data'],
components: {
Datepicker,
factura,
checkingin,
Autocomplete,
crearcliente
},
computed: {
  count () {
  this.$store.state;
  console.log(this.$store.state);
  return this.$store.state.posts
  },
},
methods: {
customFormatter(date) {
return moment(date).format('D MMMM YYYY');
},


checkOut(args)
{

let me = this;

Vue.swal({
    title: "Realizar CheckOut?",
    text: "Desea relizar el CheckOut de esta habitacion!",
    type: "warning",
    showCancelButton: false,
    confirmButtonColor: '#DD6B55',
    confirmButtonText: 'Si!',
    cancelButtonText: "No, cancela!"
 }).then(
    function () {

    axios.post(me.base+"/reservas/checkOut", {
        grupo_id: args.id_reservas_grupo,
        check_out_fecha: args.check_out_fecha,
        habitacion: args.id

    })
    .then(function(response)
    {

        Vue.swal("Muy bien!", "Realizo CheckOut a esta reserva!", "success");
        me.$router.go(me.$router.currentRoute)
    })
    .catch(function(error, otracosa)
    {
    });



    },
       function () {
        console.log(2)
        Vue.swal("No!", "No Realizo CheckOut a esta reserva!", "error");


         });

},

bloquearFechas()
{

let me = this;

// console.log(me.numero_habitacion);
// console.log(me.limpieza_estado);
// console.log(me.id_habitacion_tipo);
if (me.limpieza_estado != 5 || me.limpieza_estado != 6) {

  me.desde = '';
  me.hasta = '';
}


//jsb
},

Anulacion(data)
{
let me = this;
swal({
title: 'Deseas anular esta reserva?',
text: "Estas seguro de anular esta reserva!",
type: 'warning',
showCancelButton: true,
confirmButtonColor: '#3085d6',
cancelButtonColor: '#d33',
confirmButtonText: 'Si Anular!'
}).then(function(){
axios
.post(me.base + "/reservas/anularReserva?grupo=" + data.id_reservas_grupo)
.then(function(response) {
swal({
title: "Anulada!",
text: "Reserva Anulada!",
icon: "success",
button: "Aww yiss!",
});
me.ListarItems();
})
.catch(function(error, otracosa) {
});
}).catch(function(reason){
console.log(3);
});
},

obtenerFechaBloqueo(id)
{

let me=this;
var url= '/habitaciones/selectBloqueos?habitacion_id=' + id;
axios.get(me.base+url).then(function (response) {
var respuesta= response.data;
var respuesta2 = respuesta.datos;
var respuesta3 = respuesta.contador;

console.log(respuesta3);
if (respuesta3 == 1) {
  me.tipoAccion = 2;
}
else {
  me.tipoAccion = 1;
}


me.desde = new Date(respuesta2.fecha_desde + ' 00:00:00');
me.hasta =  new Date(respuesta2.fecha_hasta + ' 00:00:00')


})
.catch(function (error, otracosa) {
// var response = error.response.data;
});



},
click_habitacion(habitaciones) {
let me = this;
// console.log(habitaciones)

me.obtenerFechaBloqueo(habitaciones.id);

me.limpieza_estado = habitaciones.id_estado;
me.arrayHuespedes = '';
me.datagrupo = habitaciones;
me.disabled = false;
me.MostrarDetallesReserva = true;
me.ampliar = true;
me.id_habitacion_tipo = habitaciones.id_habitacion_tipo;
me.nombre_reservante = habitaciones.nombre_reservante;
me.id_estado_reserva = habitaciones.id_estado;
// me.limpieza_estado = habitaciones.id_estado;
me.habitacion_seleccionada = habitaciones.id;
me.numero_habitacion = habitaciones.numero;
me.nombres_huespedes = habitaciones.grupos[0].huespedes;
me.MostrarDetalle = me.Mostrarcheckout;
me.pagador = habitaciones.detalles.pagador;
me.arrayHuespedes = habitaciones.pagadores;
me.Mostrarcheckout = (habitaciones.libre == 'ocupado');
me.descripcion = habitaciones.descripcion;
me.id_grupo = me.id_grupo;
me.arrayHistorico = habitaciones.detalles.historico;
me.huespedes_habitaciones = habitaciones.grupos[0].huespedes;
me.pagadores_habitaciones = habitaciones.grupos[0].pagadores;
me.estado_reserva = habitaciones.id_estado_reserva;
me.data_habitaciones = habitaciones;
me.cliente_id_reserva = habitaciones.cliente_id;
},
OcultarModulo()
{
let me = this;
me.MostrarDetallesReserva = false;
me.disabled = false;
me.habitacion_seleccionada = 12;
console.log(me.habitacion_seleccionada);
},
click_habitacion2(habitaciones) {
console.log(2);
},
estados() {
let me = this;
axios.get(me.base + 'habitaciones/estados').then(function(response) {
me.arrayEstados = response.data;
console.log(me.arrayEstados);
})
.catch(function(error, otracosa) {
console.log(error, otracosa);
});
},
estadosSalidas() {
let me = this;
axios.get(me.base + 'habitaciones/estados_salidas').then(function(response) {
me.arrayEstadosSalidas = response.data;
})
.catch(function(error, otracosa) {
console.log(error, otracosa);
});
},
estadosLLegadas() {
let me = this;
axios.get(me.base + 'habitaciones/estados_llegadas').then(function(response) {
me.arrayEstadosLLegadas = response.data;
})
.catch(function(error, otracosa) {
console.log(error, otracosa);
});
},
estadosAlojados() {
let me = this;
axios.get(me.base + 'habitaciones/estados_alojados').then(function(response) {
me.arrayEstadosAlojados = response.data;
})
.catch(function(error, otracosa) {
console.log(error, otracosa);
});
},
CheckIn(data) {
let me = this;
me.data_checking = data;
me.mostrar_checking = true;
me.mostrar_estados = false;
me.mostrar_estados_llegadas = false;
me.mostrar_busqueda = false;
me.MostrarDetallesReserva = true;
me.hacerChecking();
me.nombreCLiente();
},
Facturacion(data)
{
let me = this;
me.mostrar_facturacion = true;
me.mostrar_estados = false;
me.mostrar_estados_llegadas = false;
me.mostrar_busqueda = false;
me.mostrar_checking = false;
me.id_reserva_seleccionada = data.id_reserva;
me.id_habitacion_seleccionada = data.id;
me.MostrarDetallesReserva = false;
me.MostrarContenido = false;
me.MostrarEstados = false;
},
mirarchecking() {
let me = this;
me.mostrar_checking = true;
me.mostrar_estados = false;
me.mostrar_estados_llegadas = false;
},
facturar() {
let me = this;
me.mostrar_estados = false;
me.mostrar_estados_llegadas = false;
me.mostrar_generar_factura = true;
},

actualizarBloqueos()
{

  let me = this;
  me.disabled = true;
  var data = {
  id_habitacion: me.habitacion_seleccionada,
  id_habitacion_estado: me.limpieza_estado,
  fecha: me.fecha,
  descripcion: me.descripcion,
  desde:me.desde,
  hasta:me.hasta
  };
  axios.post(me.base + 'habitaciones/cambiarestado_desbloquear', data).then(function(response) {
  swal({
  title: "Guardado!",
  text: "Registro Almacenado!",
  icon: "success",
  button: "Aww yiss!",
  });
  me.tipoAccion = 1;
  me.desde = '';
  me.hasta = '';
  me.limpieza_estado = 1;
  me.disabled = false;
  me.ListarItems();
  })


},

guardarDescripcion() {
let me = this;
me.disabled = true;
var data = {
id_habitacion: me.habitacion_seleccionada,
id_habitacion_estado: me.limpieza_estado,
fecha: me.fecha,
descripcion: me.descripcion,
desde:me.desde,
hasta:me.hasta
};
axios.post(me.base + 'habitaciones/cambiarestado', data).then(function(response) {
swal({
title: "Guardado!",
text: "Registro Almacenado!",
icon: "success",
button: "Aww yiss!",
});
me.tipoAccion = 2;
me.disabled = false;
me.ListarItems();
})
},
VerLimpieza() {
let me = this;
axios.get(me.base + 'habitaciones/EstadoLimpieza').then(function(response) {
me.arrayLimpieza = response.data.data;
})
.catch(function(error, otracosa) {
// console.log(error, otracosa);
});
},

formatPrice(value) {
let val = (value / 1).toFixed(2).replace('.', ',')
return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
},
vaciar() {
let me = this;
},
ListarItems() {
let me = this;
var f = new Date();
me.fecha = f.getFullYear() + '-' + (((f.getMonth() + 1) < 10) ? '0' : '') + (f.getMonth() + 1) + '-' + (f.getDate() < 10 ? '0' : '') + f.getDate();
axios.get(me.base + 'habitaciones/VerEstadosReservas?fecha=' + me.fecha).then(function(response) {

var respuesta = response.data;
me.arraySalidas = response.data.salidas;
me.arrayLLegadas = response.data.llegadas;
me.arrayAlojados = response.data.alojados;
me.arrayDisponibles = response.data.disponibles;
console.log(me.arrayDisponibles)

})
.catch(function(error, otracosa) {
// console.log(error, otracosa);
});
},
ListarItems2() {
let me = this;
var f = new Date();
me.fecha = f.getFullYear() + '-' + (((f.getMonth() + 1) < 10) ? '0' : '') + (f.getMonth() + 1) + '-' + (f.getDate() < 10 ? '0' : '') + f.getDate();
axios.get(me.base + 'habitaciones/VerEstadosReservas?fecha=' + me.fecha).then(function(response) {
var respuesta = response.data;
me.arrayLLegadas = response.data.llegadas;
})
.catch(function(error, otracosa) {
// console.log(error, otracosa);
});
},
nuevoCliente() {
let me = this;
me.modalCliente = true;
},
validar(id_reservas) {
let me = this;
return axios
.post(me.base + "reservas/ReservasCompletas", {
id_reservas: id_reservas
})
.then(function(response) {
var data = response.data;
return data.validate;
})
.catch(function(error, otracosa) {
return false
});
},
recargar() {
let me = this;
me.mostrar_estados = true;
me.mostrar_checking = false;
// me.$router.go(me.$router.currentRoute)
},
hacerChecking() {
let me = this;
if (me.data_habitaciones.id_estado_reserva == 1) {
if (me.validar(me.data_habitaciones.id_reserva)) {
var grupos = [];
$.each(me.data_habitaciones.grupos, function(index, value) {
if (value.id_reservas_estado == 1) {
grupos.push(value);
}
});
me.reservas.llegada = me.data_habitaciones.check_in_fecha;
me.reservas.salida = me.data_habitaciones.check_out_fecha;
me.reservas.habitaciones = grupos;
} else {
alert('Aun no a asignado todas las habitaciones de esta reserva');
// Vue.swal("Aun no a asignado todas las habitaciones de esta reserva");
}
} else {
alert('Ya realizó el check in');
// Vue.swal('Ya realizó el check in');
}
},
borrar_huesped(detail, index, index2) {
let me = this;
(me.reservas.habitaciones[index].huespedes_data).splice(index2, 1)
me.$forceUpdate();
},
clienteseleccionado(data) {
let me = this;
var index = parseInt(data.selectedObject.id_index1);
var index2 = parseInt(data.selectedObject.id_index2);
me.reservas.habitaciones[index].huespedes_data[index2].id_huesped = data.selectedObject.id
me.reservas.habitaciones[index].huespedes_data[index2].nombre_huesped = data.selectedObject.name;
},
eliminar(index) {
let me = this;
me.reservas.habitaciones.splice(index, 1);
},
validar_checkin(data) {
var validate_name = true;
var validate_principal = false;
var validate_pagador = false;
$.each(data, function(index, value) {
validate_principal = false;
$.each(value.huespedes_data, function(i, v) {
validate_principal = (v.id_huesped_principal) ? true : validate_principal;
validate_pagador = (v.id_pagador) ? true : validate_pagador;
//validate_name = ($.trim(v.id_huesped)=='')?false:validate_name;
});
})
return ((validate_principal && validate_name && validate_pagador));
},
guardar_ckeckin() {
let me = this;
// // var res = me.validar_checkin(me.reservas.habitaciones);
// if (res) {
axios
.post(me.base + "/reservas/GuardarChekin", me.reservas.habitaciones)
.then(function(response) {
swal({
title: "Guardado!",
text: "Registro Almacenado!",
icon: "success",
button: "Aww yiss!",
});
me.guardar = false;
me.mostrar_reservas = true;
me.ListarItems();
me.mostrar_checking = false;
me.mostrar_estados = true;
me.mostrar_estados_llegadas = true;
me.mostrar_busqueda = true;
// me.$router.go(me.$router.currentRoute)
})
.catch(function(error, otracosa) {
});
// } else {
//  alert('Debe rellenar todos los campos');
//  // Vue.swal('Debe rellenar todos los campos');
// }
},
generar_huespedes() {
let me = this;
var i = 0;
var total = 0;
me.chekin = 2;
$.each(me.reservas.habitaciones, function(index, value) {
total = me.reservas.habitaciones[index].cantidad_huespedes;
var temp = new Array();
for (i = 0; i < total; i++) {
temp.push({
id_huesped: null,
nombre_huesped: '',
id_huesped_principal: false,
id_pagador: false,
grupo_id: me.reservas.habitaciones[index].id_grupo,
});
}
me.reservas.habitaciones[index].huespedes_data = temp;
});
},
nombreCLiente() {
let me = this;
console.log(me.habitaciones);
var url = 'reservas/nombre_reservante?reserva_id=' + me.reservas.habitaciones[0].id_reservas;
axios.get(me.base + url).then(function(response) {
var respuesta = response.data.nombre_reserva;
me.nombre_cliente = respuesta.cliente_nombre;
})
.catch(function(error, otracosa) {
// var response = error.response.data;
});
},
habitacionestipo() {
let me = this;
axios.get(me.base + '../habitaciones/tipo').then(function(response) {
me.tiposHabitacion = response.data.Tipo;
})
.catch(function(error, otracosa) {
// console.log(error, otracosa);
});
}
//=======================================Principales=======================================//
},
mounted() {
this.base = base;
this.VerLimpieza();
this.ListarItems();
this.ListarItems2();
this.habitacionestipo();
this.estados();
this.estadosSalidas();
this.estadosLLegadas();
this.estadosAlojados();
}
};
</script>
<style>
.room_block {
background-color: hsl(0, 0%, 20%);
border: 2px solid hsl(0, 0%, 20%);
color: hsl(0, 0%, 100%);
width: 13em;
height: 136px;
margin: 10px -6px 10px 10px;
float: left;
cursor: pointer;
}
.room_block .selector {
display: none;
}
.triangle_tr {
width: 0px;
height: 0px;
border-style: solid;
border-width: 0 2.5em 2.5em 0;
border-color: hsla(0, 0%, 0%, 0) hsl(211, 100%, 50%) hsla(0, 0%, 0%, 0) hsla(0, 0%, 0%, 0);
}
.room_block .roomtype {
text-transform: uppercase;
font-size: 0.6em;
overflow: hidden;
white-space: nowrap;
padding: 2px;
}
.room_block .roomnumber {
font-size: 1.2em;
text-align: center;
}
.room_block_details {
background-color: hsl(211, 100%, 50%);
text-transform: uppercase;
font-size: 0.7em;
overflow: hidden;
white-space: nowrap;
padding: 2px;
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box;
box-sizing: border-box;
}
.backred {
background-color: hsl(9, 100%, 64%)!important;
}
.room_block .roompersons {
font-size: 0.9em;
text-align: left;
padding: 2px;
}
.backgreen {
background-color: hsl(90, 100%, 40%)!important;
}
.splittwo {
width: 50%;
float: left;
}
.user_selected {
border: 4px solid hsl(0, 100%, 50%);
}
.user_selected2 {
border: 4px solid hsl(0, 100%, 50%);
}
.pointer {
cursor: pointer;
color: #d2691e;
}
.card .card-body {
    padding: 0 !important;
    height: auto !important;
}
</style>
