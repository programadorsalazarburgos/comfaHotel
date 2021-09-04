/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Vue from 'vue';
import VueSweetalert2 from 'vue-sweetalert2';
import VeeValidate from "vee-validate";
import VueRouter from 'vue-router'
import VueFormWizard from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'
// import VueCtkDateTimePicker from 'vue-ctk-date-time-picker';
import CtkDateTimePicker from 'vue-ctk-date-time-picker'
import 'vue-ctk-date-time-picker/dist/vue-ctk-date-time-picker.min.css';
import VueDatepicker from '@aries0d0f/vue2-datepicker'
import Permissions from './mixins/Permissions';
import Vuex from 'vuex'

Vue.use(Vuex)
Vue.mixin(Permissions);
const VueValidationEs = require('vee-validate/dist/locale/es');

Vue.use(VueRouter)

Vue.use(VeeValidate, {
    locale: 'es',
    dictionary: {
      es: VueValidationEs
    }
});
Vue.use(VueFormWizard)
Vue.component('CtkDateTimePicker', CtkDateTimePicker);
Vue.component('VueDatepicker', VueDatepicker);

Vue.use(VueSweetalert2);
require('./bootstrap');
window.$ = window.jQuery = require('jquery');
window.Vue = require('vue');
window.vSelect = require('vue-select');
Vue.use(require('vue-moment'));


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
Vue.component('v-select', vSelect.VueSelect)



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// =================Componentes Camilo============
Vue.component('habitaciones',require('./components/Habitaciones.vue'));
Vue.component('tipohabitaciones',require('./components/TipoHabitaciones.vue'));
Vue.component('turnos',require('./components/Turnos.vue'));
Vue.component('exchangerate',require('./components/ExchangeRate.vue'));
Vue.component('reservas',require('./components/Reservas.vue'));
Vue.component('reservasreload',require('./components/reservasreload.vue'));
Vue.component('configuracion',require('./components/configuracion.vue'));
Vue.component('impuestos',require('./components/impuestos.vue'));
Vue.component('hoteles',require('./components/hoteles.vue'));
Vue.component('estados',require('./components/estados.vue'));
import App from './components/template.vue';
import habitaciones from './components/Habitaciones.vue';
import reservas from './components/Reservas.vue';
import tipohabitaciones from './components/TipoHabitaciones.vue';
import turnos from './components/Turnos.vue';
import exchangerate from './components/ExchangeRate.vue';
import configuracion from './components/configuracion.vue';
import impuestos from './components/impuestos.vue';
import hoteles from './components/hoteles.vue';
import estados from './components/estados.vue';

// =================Componentes Camilo============
// =================Componentes Johan============
Vue.component('cliente', require('./components/Cliente.vue'));
Vue.component('insert-cliente', require('./components/InsertCliente.vue'));
Vue.component('rol', require('./components/Rol.vue'));
Vue.component('user', require('./components/User.vue'));
Vue.component('dashboard', require('./components/Dashboard.vue'));
Vue.component('crearreserva', require('./components/CrearReserva.vue'));
Vue.component('disponibilidad', require('./components/Disponibilidad.vue'));
Vue.component('reservas2',require('./components/Reservas2.vue'));
Vue.component('reservas3',require('./components/reserva3.vue'));
import cliente from './components/Cliente.vue';
import insert_cliente from './components/InsertCliente.vue';
import rol from './components/Rol.vue';
import user from './components/User.vue';
import dashboard from './components/Dashboard.vue';
import crearreserva from './components/CrearReserva.vue';
import disponibilidad from './components/Disponibilidad.vue';
import reservas2 from './components/Reservas2.vue';
import reservas3 from './components/reserva3.vue';
import alimentacion from './components/alimentacion.vue';
import impuestosproductos from './components/impuestosproductos.vue';
import categoriasproductos from './components/categoriasproductos.vue';
import productos from './components/productos.vue';
import puntosdeventas from './components/puntosdeventa.vue';
import reportes from './components/reportes.vue';
import desayunos from './components/Desayuno.vue';
import crearreservacion from './components/crearReservacion.vue';
import reportescajero from './components/reportescajero.vue';
import tipopagos from './components/tipopagos.vue';
import reservamanual from './components/reservaManual.vue';
import reportesconsumos from './components/reportesconsumos.vue';
import reservasfiltradas from './components/ReservasFiltradas.vue';
import importarReservas from './components/ImportarReservas.vue';
import inventarioReservas from './components/InventariosReservas.vue';
import inventarioxhabitacion from './components/inventarioxhabitaciones.vue';
import impuestostasas from './components/impuestotasas.vue';
import configuracioncuentas from './components/configuracioncuentas.vue';
import planestarifarios from './components/planestarifarios.vue';
import tarifas from './components/tarifas.vue';
import reportesocupaciones from './components/reportesocupaciones.vue';
import reportereservas from './components/reportereservas.vue';
// =================Componentes Johan============

const router = new VueRouter({
    routes: [
        {
            path: '/index/inicio',
            component: dashboard,
            name: 'inicio'
        },
        {path: '/index/cliente',              component: cliente,              name: 'cliente'},
        {path: '/index/configuracion',        component: configuracion,        name: 'configuracion'},
        {path: '/index/crearreserva',         component: crearreserva,         name: 'crearreserva'},
        {path: '/index/dashboard',            component: dashboard,            name: 'dashboard'},
        {path: '/index/disponibilidad',       component: disponibilidad,       name: 'disponibilidad'},
        {path: '/index/estados',              component: estados,              name: 'estados'},
        {path: '/index/exchangerate',         component: exchangerate,         name: 'exchangerate'},
        {path: '/index/habitaciones',         component: habitaciones,         name: 'habitaciones'},
        {path: '/index/hoteles',              component: hoteles,              name: 'hoteles'},
        {path: '/index/impuestos',            component: impuestos,            name: 'impuestos'},
        {path: '/index/insert_cliente',       component: insert_cliente,       name: 'insert_cliente'},
        {path: '/index/reservas',             component: reservas,             name: 'reservas'},
        {path: '/index/reservas2',            component: reservas2,            name: 'reservas2'},
        {path: '/index/reservas3',            component: reservas3,            name: 'reservas3'},
        {path: '/index/rol',                  component: rol,                  name: 'rol'},
        {path: '/index/tipohabitaciones',     component: tipohabitaciones,     name: 'tipohabitaciones'},
        {path: '/index/turnos',               component: turnos,               name: 'turnos'},
        {path: '/index/user',                 component: user,                 name: 'user'},
        {path: '/index/disponibilidad',       component: disponibilidad,       name: 'disponibilidad'},
        {path: '/index/alimentacion',         component: alimentacion,         name: 'alimentacion'},
        {path: '/index/impuestosproductos',   component: impuestosproductos,   name: 'impuestosproductos'},
        {path: '/index/categoriasproductos',  component: categoriasproductos,  name: 'categoriasproductos'},
        {path: '/index/productos',            component: productos,            name: 'productos'},
        {path: '/index/puntosdeventas',       component: puntosdeventas,       name: 'puntosdeventas'},
        {path: '/index/reportes',             component: reportes,             name: 'reportes'},
        {path: '/index/desayunos',            component: desayunos,            name: 'desayunos'},
        {path: '/index/crearreservacion',     component: crearreservacion,     name: 'crearreservacion'},
        {path: '/index/reportescajero',       component: reportescajero,       name: 'reportescajero'},
        {path: '/index/tipopagos',            component: tipopagos,            name: 'tipopagos'},
        {path: '/index/reservamanual',        component: reservamanual,        name: 'reservamanual'},
        {path: '/index/reportesconsumos',     component: reportesconsumos,     name: 'reportesconsumos'},
        {path: '/index/reservasfiltradas',    component: reservasfiltradas,    name: 'reservasfiltradas'},
        {path: '/index/importarReservas',     component: importarReservas,     name: 'importarReservas'},
        {path: '/index/inventarioxtipo',      component: inventarioReservas,   name: 'inventarioReservas'},
        {path: '/index/impuestostasas',       component: impuestostasas,       name: 'impuestostasas'},
        {path: '/index/inventarioxhabitacion',component: inventarioxhabitacion,name: 'inventarioxhabitacioninventarioxhabitacion'},
        {path: '/index/configuracioncuentas', component: configuracioncuentas, name: 'configuracioncuentas'},
        {path: '/index/planestarifarios',     component: planestarifarios,     name: 'planestarifarios'},
        {path: '/index/tarifas',              component: tarifas,              name: 'tarifas'},
        {path: '/index/reportesocupaciones',  component: reportesocupaciones,  name: 'reportesocupaciones'},
        {path: '/index/reportereservas',      component: reportereservas,      name: 'reportereservas'},
    ],
    linkActiveClass: "active",
    mode: "history"
})

const URL = '/obtener/idioma';

const store = new Vuex.Store({
  state: {
    posts: [],
    loading: true
  },
  actions: {
    loadData({
      commit
    }) {
      axios.get(URL).then((response) => {
        commit('updatePosts', response.data.datos)
        commit('changeLoadingState', false)
      })
    }
  },
  mutations: {
    increment (state) {
    },
    updatePosts(state, posts) {
      state.posts = posts
    },
    changeLoadingState(state, loading) {
      state.loading = loading
    }
  }
})
Vue.mixin({
     data: function() {
       return {
         myGlobalVar:'this is my ItSolutionStuff.com'
       }
     }
   })
const app = new Vue({
    el: '#app',
    computed: Vuex.mapState(['posts', 'loading']),
    store,
    data: {
        myResult: 'hola'
      },
    components: { App },
    router,

    created() {
      this.$store.dispatch('loadData')
      Echo.join(`chat`)
      .here((users) => {
          console.log(2)
      })
      .joining((user) => {
          console.log(3);
      })
      .leaving((user) => {
          console.log(5);
      });
    }
  })
