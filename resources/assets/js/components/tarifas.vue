<template>
    <main class="main">
        <div class="col-12">
            <div class="content-header">Tarifas</div>
        </div>
        <section id="extended">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title"></h4>
                            <button class="btn btn-raised gradient-mint white shadow-z-4" @click="abrirModal('tarifa','registrar')">
                                <span class="btn-icon"><i class="la la-user-plus"></i>Nuevo </span><i class="ft-plus-circle"></i>
                            </button>
                            <form action="" method="post" @submit.prevent="BuscarData" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group m-0">
                                            <label for="valid-state">Año</label>
                                            <select name="year" v-model="curYear" required @change="getCalendarYear" class="form-control">
                                                <option v-if="year >= nowYear-prevYears" v-for="(year, index) in nowYear+futureYears" :value="year">{{year}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group m-0">
                                            <label for="invalid-state">Mes</label>
                                            <select name="month" v-model="curMonth" required @change="getCalendarMonth" class="form-control">
                                                <option v-for="(month, index) in 12" :value="index">{{monthsArray[index]}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div><br>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group m-0">
                                            <label for="valid-state">Plan Tarifario</label>
                                            <select name="year" v-model="plan" required @change="getTipoHabitacion" class="form-control">
                                                <option v-for="data in arrayData" :key="data.id" :value="data.id">{{data.nombre}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group m-0">
                                            <label for="invalid-state">Tipo Habitación</label>
                                            <select name="month" v-model="tipo" required class="form-control" @change="selectTipoHabitacion">
                                                <option v-for="data in arrayHabitacionesTipo" :key="data.code" :value="data.code">{{data.name}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div><br>
                                <button type="submit" v-if="tipoGuardar==1" class="btn gradient-ibiza-sunset" style="background-image: linear-gradient(45deg, #83ef40, rgb(202, 197, 47)); color:white;">Buscar <i class="fa fa-search"></i></button>
                                <br>
                                <br>
                                <transition name="fade">
                                    <div class="alert alert-success" v-if="filterDate != undefined"> Fecha seleccionada: {{formattedDate}}</div>
                                </transition>
                            </form>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div v-if="gridArray" class="el-calendar">
                                    <div class="el-calendar__body">
                                        <table class="el-calendar-table">
                                            <thead>
                                                <tr>
                                                    <th class="center-title" colspan="1">
                                                        <button v-on:click="previousMonth" type="button" class="btn btn-danger"><i class="ft-chevrons-left"></i><span> Anterior</span></button>
                                                    </th>
                                                    <th colspan="5" class="center-title">
                                                        {{`${monthsArray[curMonth]} ${curYear}  `}}
                                                    </th>
                                                    <th class="center-title" colspan="1">
                                                        <button v-on:click="nextMonth" type="button" class="btn btn-success"><span>Siguiente </span><i class="ft-chevrons-right"></i></button>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th v-for="(i, index) in 7">{{daysArray[index]}}</th>
                                                </tr>
                                            </thead>
                                            <tbody data-bind="foreach:gridArray">
                                                <tr class="el-calendar-table__row" v-for="(item, index) in calendarMatrix">

                                                    <td class="current" v-for="(data, i) in item" :style="{'background-color': isToday(data) ?  '#409eff' : findAttendance(data.date) ? '#67c23a': '#fff'}"
                                                      :class="{'is-today': isToday(data), 'is-selected':isActive(data.date), 'occassion': data.occassion ? data.occassion : false}">

                                                        <div v-on:click="setDate(data.date)" v-bind:class="{'is-selected':isActive(data.date),'weekend':  (!(i%7)||!((i+1)%7))&& !isToday(data)}">
                                                            <div class="el-calendar-day"><span
                                                                  :class="get_brightness(findAttendance(data.date) ? '#67c23a': '#f56c6c') > 175 ? 'colorblackshade' : 'colorwhiteshade' ">{{data ? data.date.getDate() : ''}}</span>
                                                                <div style="color: #1cbcd8;" class="content-to-hide">
                                                                    <div v-for="event in monthEvents">
                                                                        <div v-if="data ? data.date.getDay() == eventsArray[event].day && eventsArray[event].occurrence == 'Weekly' : false">
                                                                          <h6 style="font-size:14px !important; margin-top: -30px; margin-left: 57px;">Tarifa x Hab:{{eventsArray[event].memo}}{{`${changeOccurrence(index, i)}`}} <br><span>Tarifa x Pers:{{eventsArray[event].memo2}}</span> <br>Tarifa x Niños:{{eventsArray[event].memo3}} </h6>
                                                                          </div>
                                                                        <div v-else-if="data ? data.date.getDate() == eventsArray[event].day && eventsArray[event].occurrence != 'Weekly': false">
                                                                          <h6 style="font-size:14px !important; margin-top: -30px; margin-left: 57px;"><span class="badge badge-info mb-1 mr-2">Tarifa x Hab:{{eventsArray[event].memo}}{{`${changeOccurrence(index, i)}`}}</span> <br><span class="badge badge-danger mb-1 mr-2">Tarifa x Pers:{{eventsArray[event].memo2}}</span> <br><span class="badge badge-success mb-1 mr-2">Tarifa x Niños:{{eventsArray[event].memo3}}</span></h6>

                                                                        </div>
                                                                        <div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="7">
                                                        <div style="margin-top: 10px;">
                                                            <div class="legendcurrent"></div>
                                                            Día actual
                                                        </div>
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none; height: 10000px;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" v-text="tituloModal"></h4>
                            <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                            <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <form action="" method="post" @submit.prevent="RegistrarData" enctype="multipart/form-data">
                        <div class="modal-body" style="height: 500px !important;position: relative !important;z-index: 1000 !important;max-height: none !important;display: block;">
                              <div class="container-fluid">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group m-0">
                                            <label for="valid-state">Fecha Desde(*)</label>
                                            <datepicker :format="customFormatter" required :typeable="true" style="width:100% !important;" v-model="fecha_desde" :bootstrap-styling="true"></datepicker>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group m-0">
                                            <label for="invalid-state">Fecha Hasta(*)</label>
                                            <datepicker :format="customFormatter" required :typeable="true" style="width:100% !important;" :bootstrap-styling="true" v-model="fecha_hasta"></datepicker>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div class="container-fluid">
                                	<div class="row">
                                		<div class="col-md-12">
                                			<div class="row">
                                				<div class="col-md-6">
                                          <div class="form-group m-0">
                                              <label for="valid-state">Plan Tarifario(*)</label>
                                              <select name="year" v-model="plan_tarifa" required @change="getTipoHabitacionTarifa" class="form-control">
                                                  <option v-for="data in arrayData" :key="data.id" :value="data.id">{{data.nombre}}</option>
                                              </select>
                                          </div>
                                				</div>
                                				<div class="col-md-6">
                                          <div class="form-group m-0">
                                              <label for="invalid-state">Tipo Habitación(*)</label>
                                              <select name="month" v-model="tipo_tarifa" required class="form-control">
                                                  <option v-for="data in arrayHabitacionesTipo" :key="data.code" :value="data.code">{{data.name}}</option>
                                              </select>
                                          </div>
                                				</div>
                                			</div>
                                		</div>
                                	</div>
                                </div><br>
                                <div class="container-fluid">
                                	<div class="row">
                                		<div class="col-md-12">
                                			<div class="row">
                                				<div class="col-md-4">
                                          <div class="form-group m-0">
                                              <label for="invalid-state">Tarifa x Habitación</label>
                                                <input type="number" required v-model="tarifa_x_habitacion" class="form-control"  v-validate="'required'" name="valor a pagar" />
                                          </div>
                                				</div>
                                				<div class="col-md-4">
                                          <div class="form-group m-0">
                                              <label for="invalid-state">Tarifa x Persona</label>
                                                <input type="number" required v-model="tarifa_x_persona" class="form-control"  v-validate="'required'" name="valor a pagar" />
                                          </div>
                                				</div>
                                				<div class="col-md-4">
                                          <div class="form-group m-0">
                                              <label for="invalid-state">Tarifa x Niño</label>
                                                <input type="number" required v-model="tarifa_x_nino" class="form-control"  v-validate="'required'" name="valor a pagar" />
                                          </div>
                                				</div>
                                			</div>
                                		</div>
                                	</div>
                                </div><br>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="cerrarModal()">Cerrar</button>
                            <button type="submit"  class="btn btn-primary">Guardar</button>
                        </div>
                      </form>
                    </div>
                </div>
            </div>
            <!--Fin del modal-->

        </section>
    </main>
</template>
<script>
    import VueSweetAlert from 'vue-sweetalert';
    import Multiselect from 'vue-multiselect'
    import Datepicker from 'vuejs-datepicker';
    import Vue from 'vue';
    import ToggleButton from 'vue-js-toggle-button'
    import moment from 'moment'
    import {
        Calendar
    } from 'vue-bootstrap4-calendar';
    import {
        messages
    } from 'vue-bootstrap4-calendar';
    Vue.use(ToggleButton)
    Vue.use(require('moment'));

    export default {
        data() {
            var today = new Date(),
                nowMonth = today.getMonth(),
                nowYear = today.getFullYear()
            return {
                prevYears: 10, // number of years before current date
                futureYears: 10,
                nowMonth,
                nowYear,
                today,
                curMonth: nowMonth,
                curYear: nowYear,
                filterDate: new Date(),
                selectedMonth: new Date(),
                eventsArray: [],
                attendances: [{
                        date: new Date(2019, 6, 27)
                    },
                    {
                        date: new Date(2019, 6, 26)
                    },
                    {
                        date: new Date(2019, 6, 23)
                    },
                    {
                        date: new Date(2019, 6, 24)
                    },
                    {
                        date: new Date(2019, 6, 25)
                    },
                ],
                calendarMatrix: [],
                monthsArray: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
                daysArray: ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"],
                base: '',
                capacidad: '',
                ocupacion_tarifa: '',
                menores_incluidos: '',
                tituloModal: '',
                pag_actual: 1,
                Paginas: 0,
                selected: null,
                SivalidaForm: false,
                tipoGuardar: 1,
                id: null,
                disablebutton: false,
                id_habitacion_tipo: null,
                nombre: '',
                codigo_reservas: '',
                codigo_pms: '',
                descripcion: '',
                plan: '',
                tipo: '',
                plan_tarifa: '',
                tipo_tarifa: '',
                tarifa_x_habitacion: '',
                tarifa_x_persona: '',
                tarifa_x_nino: '',
                id_plan_tarifario: '',
                arrayData: [],
                arrayPlanes: [],
                arrayHabitacionesTipo: [],
                options: [],
                value: [],
                events: [],
                pagination: {
                    total: 0,
                    current_page: 0,
                    per_page: 0,
                    last_page: 0,
                    from: 0,
                    to: 0
                },
                buscar: "",
                submitted: false,
                buscar: "",
                criterio: "numero",
                Paginas: 0,
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
                modal: 0,
                fecha_desde: '',
                fecha_hasta: '',
            };
        },

        components: {
            Multiselect,
            Calendar,
            Datepicker
        },
        computed: {
            count() {
                this.$store.state;
                return this.$store.state.posts
            },
            monthEvents() {
                var vm = this,
                    monthEvents = []
                for (var i = 0; i < vm.eventsArray.length; i++) {
                    if (vm.eventsArray[i].month == (vm.curMonth + 1) && (vm.eventsArray[i].year == vm.curYear || vm.eventsArray[i].occurrence == "Annual")) {
                        monthEvents[monthEvents.length] = i;
                    } else if (vm.eventsArray[i].occurrence == "Monthly" || vm.eventsArray[i].occurrence == "Weekly") {
                        monthEvents[monthEvents.length] = i;
                    }
                }
                return monthEvents
            },
            gridArray: function() {
                this.getCalendarMatrix(this.selectedMonth);
                return this.selectedMonth;
            },
            formattedDate: function() {
                return this.filterDate ? moment(this.filterDate).format('lll') : '';
            },

            isActived: function() {
                return this.pagination.current_page;
            },
            //Calcula los elementos de la paginación
            pagesNumber: function() {
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
            handleSubmit(e) {
                console.log(e);
            },
            eventAdded(event) {
                this.events.push(event);
            },
            eventDeleted(event) {
                this.events.splice(this.events.indexOf(event), 1);
            },
            cambiarPagina(page, buscar, criterio) {
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.ListarItems(page, buscar, criterio);
            },
            get_brightness(hexCode) {
                // strip off any leading #
                hexCode = hexCode.replace('#', '');

                var c_r = parseInt(hexCode.substr(0, 2), 16);
                var c_g = parseInt(hexCode.substr(2, 2), 16);
                var c_b = parseInt(hexCode.substr(4, 2), 16);

                return ((c_r * 299) + (c_g * 587) + (c_b * 114)) / 1000;
            },
            customFormatter(date) {
                return moment(date).format('YYYY-MM-DD');
            },
            getTipoHabitacion() {
                let me = this;
                var url = '/planes_tarifarios/obtener_tipo_habitaciones?plan_id=' + me.plan;
                axios.get(url).then(function(response) {
                        var respuesta = response.data.datos;
                        me.arrayHabitacionesTipo = respuesta;
                    })
                    .catch(function(error) {
                        var response = error.response.data;
                        console.log(response.message,
                            response.exception,
                            response.file,
                            response.line);
                    });
            },
            cerrarModal()
            {
              let me = this;
              me.modal = 0;
            },
            getTipoHabitacionTarifa() {
                let me = this;
                var url = '/planes_tarifarios/obtener_tipo_habitaciones?plan_id=' + me.plan_tarifa;
                axios.get(url).then(function(response) {
                        var respuesta = response.data.datos;
                        me.arrayHabitacionesTipo = respuesta;
                    })
                    .catch(function(error) {
                        var response = error.response.data;
                        console.log(response.message,
                            response.exception,
                            response.file,
                            response.line);
                    });
            },
            findAttendance(date) {
                var d = date

                return d ? _.find(this.attendances, function(attendance) {
                    var a = attendance.date
                    return `${a.getFullYear()}-${a.getMonth()}-${a.getDate()}` === `${d.getFullYear()}-${d.getMonth()}-${d.getDate()}`
                }) : false
            },
            isToday(data) {
                return data.date ? `${data.date.getDate()}-${data.date.getMonth()}-${data.date.getFullYear()}` === `${new Date().getDate()}-${new Date().getMonth()}-${new Date().getFullYear()}` : false
            },
            newEvent(day, month, year, memo, memo2, memo3, occurrence) {
                return {
                    day,
                    month,
                    year,
                    memo,
                    memo2,
                    memo3,
                    occurrence
                }
            },
            previousMonth: function() {
                var vm = this
                vm.curMonth -= 1;
                var tmpDate = this.selectedMonth;
                vm.curMonth = tmpDate.getMonth() - 1;
                if (vm.curMonth < 0) {
                    vm.curMonth = 11;
                    if (vm.curYear > (vm.nowYear - vm.prevYears)) {
                        vm.curYear -= 1;
                    }
                }
                tmpDate.setFullYear(vm.curYear)
                tmpDate.setMonth(vm.curMonth)
                vm.selectedMonth = new Date(tmpDate);
            },
            nextMonth: function() {
                var vm = this
                vm.curMonth += 1;

                var tmpDate = this.selectedMonth;
                vm.curMonth = tmpDate.getMonth() + 1;
                if (vm.curMonth > 11) {
                    vm.curMonth = 0;
                    if (vm.curYear < (vm.nowYear + vm.futureYears)) {
                        vm.curYear += 1;
                    }
                }
                tmpDate.setFullYear(vm.curYear)
                tmpDate.setMonth(vm.curMonth)
                vm.selectedMonth = new Date(tmpDate);
            },
            setDate: function(date) {
                if (date == this.filterDate) {
                    console.log('setting undefined');
                    this.filterDate = undefined;
                    //unselected
                } else {
                    this.filterDate = date;
                }

            },
            isActive: function(date) {
                return date == this.filterDate;
            },
            monthLength(date) {
                var monLength = 31;
                if (date.getMonth() == 3 || date.getMonth() == 5 || date.getMonth() == 8 || date.getMonth() == 10) monLength = 30;
                if (date.getMonth() == 1) {
                    if (date.getYear() % 4) {
                        monLength = 28;
                    } else {
                        monLength = 29;
                    }
                }
                return monLength;
            },
            getCalendarMatrix: function(date) {
                var vm = this;
                var calendarMatrix = []

                var startDay = new Date(date.getFullYear(), date.getMonth(), 1)
                var today = startDay.getDay()
                var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0)

                var week = []
                for (var i = 0; i < 42; i++) {

                    if (i < today) {
                        week.push('');
                    } else {
                        if (!(i % 7) || !((i + 1) % 7)) { // get days of the weekend
                            if (!(i % 7) && i > 0) {
                                calendarMatrix.push(week);
                                week = []
                            }
                            week.push({
                                date: new Date(startDay),
                                occassion: false
                            });
                            startDay.setDate(startDay.getDate() + 1)
                        } else {
                            week.push({
                                date: new Date(startDay),
                                occassion: false
                            });
                            startDay.setDate(startDay.getDate() + 1)
                        }
                    }
                }
                vm.calendarMatrix = calendarMatrix;
            },
            changeOccurrence(grid, item) {
                var vm = this;
                vm.$set(vm.calendarMatrix[grid][item], 'occassion', true)
                return ``
            },
            getCalendarYear() {
                var vm = this;
                var tmpDate = this.selectedMonth;
                tmpDate.setFullYear(vm.curYear)
                vm.selectedMonth = new Date(tmpDate);
            },
            getCalendarMonth() {
                var vm = this
                var tmpDate = vm.selectedMonth;
                tmpDate.setMonth(vm.curMonth)
                vm.selectedMonth = new Date(tmpDate);
            },

            ListarItems(page, buscar, criterio) {
                let me = this;
                var url = '/planes_tarifarios/ver_planes_tarifarios?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response) {
                        var respuesta = response.data;
                        me.arrayData = respuesta.items.data;
                        me.pagination = respuesta.pagination;
                    })
                    .catch(function(error) {
                        var response = error.response.data;
                        console.log(response.message,
                            response.exception,
                            response.file,
                            response.line);
                    });
            },
            convert(str) {
                var date = new Date(str),
                    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
                    day = ("0" + date.getDate()).slice(-2);
                return [date.getFullYear(), mnth, day].join("-");
            },
            RegistrarData()
            {
              let me = this;
              axios.post(me.base + "/planes_tarifarios/crear_tarifa_fecha", {
                      fecha_desde: me.convert(me.fecha_desde),
                      fecha_hasta: me.convert(me.fecha_hasta),
                      plan_tarifa: me.plan_tarifa,
                      tipo_tarifa: me.tipo_tarifa,
                      tarifa_x_habitacion: me.tarifa_x_habitacion,
                      tarifa_x_persona: me.tarifa_x_persona,
                      tarifa_x_nino: me.tarifa_x_nino

                  })
                  .then(function(response) {
                    Vue.swal("Muy Bien!", "Registros Almacenados", "success");
                    me.fecha_desde = '';
                    me.fecha_hasta = '';
                    me.plan_tarifa = '';
                    me.tipo_tarifa = '';
                    me.tarifa_x_habitacion = '';
                    me.tarifa_x_persona = '';
                    me.tarifa_x_nino = '';
                    me.cerrarModal();
                  })
                  .catch(function(error, otracosa) {});
            },
            EditarPlan(plan) {
                let me = this;
                me.value = plan.tipo_habitaciones;
                me.nombre = plan.nombre;
                me.descripcion = plan.descripcion;
                me.codigo_reservas = plan.codigo_reservas;
                me.codigo_pms = plan.codigo_pms;
                me.id_plan_tarifario = plan.id;
                me.tipoGuardar = 2;

            },
            TipoHabitaciones(page, buscar, criterio) {
                let me = this;
                var url = '/cargar_tipo_habitaciones';
                axios.get(url).then(function(response) {
                        var respuesta = response.data;
                        me.options = respuesta.datos;
                    })
                    .catch(function(error) {
                        var response = error.response.data;
                        console.log(response.message,
                            response.exception,
                            response.file,
                            response.line);
                    });
            },
            onChangeEventHandler(plan) {
                let me = this;
                axios.post(me.base + '/planes_tarifarios/actualizar_estado', {
                    'data': plan,
                }).then(function(response) {}).catch(function(error) {
                    alert('Error');

                });

            },
            abrirModal(modelo, accion, data = []) {
                switch (modelo) {
                    case 'tarifa': {
                        switch (accion) {
                            case 'registrar': {
                                this.modal = 1;
                                this.tituloModal = 'Registrar Tarifa';
                                break;
                            }
                        }
                    }
                }
            },
            planesTarifarios() {
                let me = this;
                axios
                    .get(me.base + "/planes_tarifarios/listar")
                    .then(function(response) {
                        me.arrayPlanes = response.data.datos;
                    })
                    .catch(function(error, otracosa) {});
            },
            Nuevo() {
                let me = this;
                me.id_plan_tarifario = '';
                me.nombre = '';
                me.value = [];
                me.descripcion = '';
                me.codigo_reservas = '';
                me.codigo_pms = '';
                me.tipoGuardar = 1;
            },
            selectTipoHabitacion()
            {
              let me = this;
              me.eventsArray = [];
            },
            BuscarData() {
                let me = this;
                  var mes = me.curMonth + 1
                  var url = '/planes_tarifarios/ver_agenda?anio=' + me.curYear + '&mes=' + mes + '&plan=' + me.plan + '&tipo=' + me.tipo;
                  axios.get(url).then(function(response) {
                          var respuesta = response.data.datos;
                          me.eventsArray = respuesta;
                      })
                      .catch(function(error) {
                      });
            },

            addTag(newTag) {
                const tag = {
                    name: newTag,
                    code: newTag.substring(0, 2) + Math.floor((Math.random() * 10000000))
                }
                this.options.push(tag)
                this.value.push(tag)
            },

        },
        mounted() {
            this.base = base;
            this.ListarItems(1, '');
            this.planesTarifarios();
            this.TipoHabitaciones();
            setTimeout(function() {
                this.events = [ // you can make ajax call here
                    {
                        id: 1,
                        title: 'Event 1',
                        description: 'Dummy Desc',
                        color: 'card-danger card-inverse',
                        date: new Date()
                    },

                ];
            }, 1000);
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

    .today {
        width: 21px;
        height: 21px;
        text-align: center;
        color: #FFFF00;
        background-color: #005999;
    }

    .fullscreen {
        height: 600px;
    }

    .fade-enter-active,
    .fade-leave-active {
        transition: opacity .5sabove
    }

    .fade-enter,
    .fade-leave-active {
        opacity: 0
    }

    .thead-default,
    .thead-default a {
        background-color: #00bcd4;
        color: #fff;
    }

    .tbody-default {
        background-color: #eee;
    }

    .center-title {
        text-align: center;
    }

    .calendar {
        width: 100%;
        border: 1px solid #ddd;
    }

    .calendar td a {
        text-decoration: none;
        color: #006699;
    }

    .calendar td a:hover {
        text-decoration: underline;
        color: #FF0000;
    }

    .calendar td,
    .calendar th {
        width: 13%;
        border: 1px solid #ddd;
        padding: 3px 3px 3px 3px;
    }

    .calendar th {
        background-color: #005999;
        color: #FFFF00;
        font-weight: bold;
    }

    .calendar td {
        height: 50px;
        font-weight: bold;
        vertical-align: top;
    }

    .blank {
        background-color: #ddd;
    }

    .weekend {
        color: #999;
    }

    .occassion {
        background-color: #ffffbf;
    }


    @media (max-width: 767px) {
        .content-to-hide {
            display: none;
        }
    }


    .colorwhiteshade {
        color: black;
        text-shadow: 1px 1px 1px #423e3e;
        text-transform: uppercase
    }

    .colorblackshade {
        color: #000 !important;
        text-shadow: 1px 1px 1px #fff;
        text-transform: uppercase
    }

    .el-calendar-table .el-calendar-day:hover {
        cursor: pointer;
        background-color: #e6a23c;
    }

    .legendblock {
        width: 25px;
        height: 25px;
        margin-right: 6px;
        border: 2px solid #999;
        background: rgb(245, 108, 108);
        display: inline-block;
        vertical-align: bottom;
    }

    .legendpresent {
        width: 25px;
        height: 25px;
        margin-right: 6px;
        border: 2px solid #999;
        background: rgb(103, 194, 58);
        display: inline-block;
        vertical-align: bottom;
    }


    .legendcurrent {
        width: 25px;
        height: 25px;
        margin-right: 6px;
        border: 2px solid #999;
        background: rgb(64, 158, 255);
        display: inline-block;
        vertical-align: bottom;
    }
</style>
