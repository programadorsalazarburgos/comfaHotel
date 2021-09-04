<template>
<main class="main">
    <div v-if="mostrar_reservas">
        <div class="col-sm-12">
            <div class="content-header"><span class="badge badge-danger" style="background-color: #2196f3;">Ampliar <i class="fas fa-building"></i></span> <i v-on:click="ampliar = !ampliar" class="fas fa-arrows-alt-h blue-grey darken-4"></i> </div>
            <div class="card-body">
                <router-link :to="{ name: 'reservamanual' }" tag="button" class="btn btn-primary round mr-1 mb-1" style="background-color: #1cbcd8;">
                    Nueva Reserva <i class="fas fa-book-medical"></i>
                </router-link>
            </div>

            <div class="container-fluid">
                <div class="md-form input-group mb-3">
                    <datepicker class="startDate" :format="customFormatter" style="width:20% !important;" v-model="filtro_calendario" :bootstrap-styling="true" @opened="datepickerAbierto" @selected="fechaSeleccionada" @closed="datepickerCerrado">
                    </datepicker>
                    <div>
                        <button class="btn btn-raised gradient-mint white shadow-z-4" style="background-image: linear-gradient(45deg, rgb(231 234 32), rgb(189 184 41));" v-on:click="iniciar()" type="button" id="MaterialButton-addon2">Buscar <i class="fab fa-searchengin"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div><br>
        <section id="extended" style="position: relative;top: -48px;">
            <div class="row">
                <div v-if="tipoCarga==1">
                    <div class="loader loader-default is-active" data-text></div>
                </div>
                <div :class="ampliar?'col-sm-12':'col-sm-8'">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <scale-loader :loading="loadingActualizarReserva" :size="120" :color="color" :height="20" :width="80"></scale-loader>
                                <button type="button" v-on:click="Listar()" class="btn btn-raised gradient-mint white shadow-z-4">ACTUALIZAR <i class="fas fa-search-location"></i></button>
                                &nbsp;<span class="text-bold-500 blue" style="font-size:12px;"><i class="fas fa-bed"></i> RESERVADO</span>
                                <span class="text-bold-500 green" style="font-size:12px;"><i class="fas fa-bed"></i> HOSPEDADO</span>
                                <span class="text-bold-500 orange" style="font-size:12px;"><i class="fas fa-bed"></i> SALIDA</span>
                                <span class="text-bold-500 gray" style="font-size:12px; color: #616161;"><i class="fas fa-bed"></i> CHECK-OUT</span>
                                <!-- <DayPilotScheduler id="dp" :config="config" ref="scheduler" /> -->

                                <DayPilotScheduler id="dp" :config="config" ref="scheduler" />
                            </h4>
                        </div>
                    </div>
                </div>
                <div v-if="!ampliar" class="col-sm-4">
                    <div class="card" style="height: auto !important;">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-12">
                                    <div v-bind:class="'small c100 p'+porcentaje">
                                        <span>{{tiempo}} </span>
                                        <div class="slice">
                                            <div class="bar"></div>
                                            <div class="fill"></div>
                                        </div>
                                    </div>
                                    <span class="badge badge-info">Reservaciones</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="list-group">
                                        <li v-on:click="ModalReservas(reserva)" class="list-group-item" v-for="(reserva,index) in ArrayReservas" :key="index">
                                            <span class="badge badge-success"><span v-text="reserva.cliente_nombre"></span></span>
                                            <br>
                                            <span class="badge badge-danger"><span v-text="reserva.habitacion_nombre"></span></span>
                                            <h5 style="font-size: 12px"><strong>FECHA LLEGADA: {{reserva.fecha_llegada}}</strong></h5>
                                            <h5 style="font-size: 12px"><strong>FECHA SALIDA: {{reserva.fecha_salida}}</strong></h5>
                                            <h5 style="font-size: 12px"><strong>FUENTE: {{reserva.nombre_fuente}}</strong></h5>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="dp" />
        </section>
    </div>
    <div v-if="mostrar_checkin">
        <checkingin :data="data_checking"></checkingin>
    </div>
    <div v-if="mostrar_prepagos">
        <prepagos></prepagos>
    </div>
    <div id="modals">
    </div>
    <!-- nuevo cliente-->
    <!-- Comentario -->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalComentario}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Comentarios</h4>
                    <button type="button" class="close" v-on:click="cerrarModal()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label>Descripción</label>
                                <textarea v-model="requisitos_especiales" class="form-control" placeholder="Ingrese descripción"></textarea>
                            </div>
                            <div class="col-md-12">
                                <label>Comentarios</label>
                                <textarea v-model="comentarios" class="form-control" placeholder="Ingrese descripción"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" v-on:click="cerrarModal()">Cerrar</button>
                    <button type="button" class="btn btn-primary" v-on:click="savemodalComentario()">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Comentario -->
    <!-- Modal -->
    <div class="modal fade text-left" :class="{'mostrar' : modalAsignacion}" id="large" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="height: 550px;">
                <div class="modal-header" style="background-color: white;">
                    <span class="badge badge-info">asdas</span>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" v-on:click="cerrarModalAsignacion()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-4">
                            <ul class="no-list-style">
                                <li class="mb-2">
                                    <span class="text-bold-500 primary"><a><i class="icon-present font-small-3"></i> asd:</a></span>
                                    <span class="display-block overflow-hidden"><span class="badge badge-danger">{{fecha_llegada}}</span> {{llegada_fecha}}</span>
                                </li>
                                <li class="mb-2">
                                    <span class="text-bold-500 primary"><a><i class="ft-map-pin font-small-3"></i>asd:</a></span>
                                    <span class="display-block overflow-hidden"><span class="badge badge-warning" style="background-color: #ffa000; color: white;">{{huespedes_cantidad}}</span></span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <ul class="no-list-style">
                                <li class="mb-2">
                                    <span class="text-bold-500 primary"><a><i class="ft-user font-small-3"></i> asd:</a></span>
                                    <span class="display-block overflow-hidden"><span class="badge badge-danger">{{fecha_salida}}</span> {{salida}}
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <ul class="no-list-style">
                                <li class="mb-2">
                                    <span class="text-bold-500 primary"><a><i class="ft-smartphone font-small-3"></i> Tipo Habitación:</a></span>
                                    <span class="display-block overflow-hidden"><span class="badge badge-warning" style="background-color: #ffa000; color: white;">{{habitacion_nombre}}</span></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <button class="btn btn-raised gradient-pomegranate white big-shadow" v-on:click="AnularReservaGrupo(id_grupo_reserva, id_habitacion_tipo_modal, fecha_llegada, fecha_salida)">asdsad <i class="fas fa-trash-restore-alt"></i></button>
                    <hr>
                    <div class="clearfix"></div>
                    <br>
                    <span class="badge badge-info">Seleccionar Habitación</span>
                    <div class="clearfix"></div>
                    <br>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- <label>Habitación</label><br> -->
                                <select class="form-control" v-model="habitacion" v-on:change="selectHabitacion()">
                                    <option value=" 0" disabled>asdsad</option>
                                    <option v-for="(habitacion,index) in arrayHabitacion" :key="index" :value="habitacion.id" v-text="habitacion.numero"></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-raised gradient-crystal-clear white shadow-big-navbar" v-if="tipoAsignar==1" data-dismiss="modal" v-on:click="Asignar(info, habitacion)">asdsa</button>
                </div>
            </div>
        </div>
    </div>
    <!-- detalle -->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalDetalle}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content" style="height: 500px;">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" class="close" @click="cerrarModal()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="detalles_habitacion">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-block">
                                            <h5><i class="fas fa-align-justify danger"></i> DETALLES DE LA RESERVA</h5>
                                            <span class="display-block overflow-hidden">{{nombre_fuente}}</span>
                                            <span class="badge badge-success">NUMERO RESERVA: {{referenciaReserva}}</span>
                                            <hr>
                                            <div class="row">
                                                <div class="col-12 col-md-6 col-lg-4">
                                                    <ul class="no-list-style">
                                                        <li class="mb-2">
                                                            <span class="text-bold-500 primary"><a><i class="icon-present font-small-3"></i> Fecha Llegada:</a></span>
                                                            <span class="display-block overflow-hidden"><span class="badge badge-danger">{{llegada_fecha_dia}}</span> {{llegada_fecha}}</span>
                                                        </li>
                                                        <li class="mb-2">
                                                            <span class="text-bold-500 primary"><a><i class="ft-map-pin font-small-3"></i>Huespedes:</a></span>
                                                            <span class="display-block overflow-hidden">{{cantidad_huespedes}}</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col-12 col-md-6 col-lg-4">
                                                    <ul class="no-list-style">
                                                        <li class="mb-2">
                                                            <span class="text-bold-500 primary"><a><i class="ft-user font-small-3"></i> Fecha Salida:</a></span>
                                                            <span class="display-block overflow-hidden"><span class="badge badge-danger">{{salida_dia}}</span> {{salida}}
                                                            </span>
                                                        </li>
                                                        <li class="mb-2">
                                                            <span class="text-bold-500 primary"><a><i class="ft-mail font-small-3"></i> Tipo Habitacion:</a></span>
                                                            <a class="display-block overflow-hidden">{{nombre_tipo}}</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col-12 col-md-6 col-lg-4">
                                                    <ul class="no-list-style">
                                                        <li class="mb-2">
                                                            <span class="text-bold-500 primary"><a><i class="ft-smartphone font-small-3"></i> Pagador:</a></span>
                                                            <span class="display-block overflow-hidden">{{cliente}}</span>
                                                        </li>
                                                        <li class="mb-2">
                                                            <span class="text-bold-500 primary"><a><i class="ft-briefcase font-small-3"></i> No Habitación:</a></span>
                                                            <span class="display-block overflow-hidden">{{numero}}</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" v-on:click="cerrarModal()">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade text-left" :class="{'mostrar' : modalComentarios}" id="large" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="height: 550px;">
                <div class="modal-header" style="background-color: white;">
                    <span class="badge badge-info">Comentarios Reservas</span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" v-on:click="cerrarModalComentario()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label for="basicTextarea">Escribe tu comentario</label>
                                <textarea class="form-control" v-model="comentario"></textarea>
                            </fieldset>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-raised gradient-crystal-clear white shadow-big-navbar" data-dismiss="modal" v-on:click="GuardarComentario()">Guardar</button>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Mis Comentarios</h4>
                                </div>
                                <div class="card-body">
                                    <div class="card-block">
                                        <table class="table">
                                            <thead class="thead-default">
                                                <tr>
                                                    <th>Comentario Reserva</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="comentarios in ArrayComentarios" :key="comentarios.id">
                                                    <th v-text="comentarios.comentario"></th>
                                                    <th><button type="button" v-on:click="EliminaComentario(comentarios.id)" title="Eliminar" class="btn-danger"><i aria-hidden="true" class="fa fa-trash"></i></button></th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <!-- Modal Tarifa -->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalTarifa}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span class="badge badge-primary" style="font-size: 14px;background-color: #ff586b;">Cambio de Tarifa</span></h4>
                    <button type="button" class="close" v-on:click="cerrarModalTarifa()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" @submit.prevent="registrarData" enctype="multipart/form-data">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label>Tarifa Diaria</label>
                                <input type="decimal" v-model="tarifa_diaria" required class="form-control" placeholder="Tarifa Diaria">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" v-on:click="cerrarModalTarifa()">Cerrar</button>
                            <button type="submit" class="btn btn-raised gradient-nepal white card-shadow" style="background-image: linear-gradient(45deg, #0cc27e, #2657eb);">Guardar <i class="fa fa-fw fa-save"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Tarifa -->
    <!-- detalle -->
    <!-- Clientes alojados -->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalAlojados}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" class="close" v-on:click="cerrarModal()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label">Descripción</label>
                            <div class="col-md-9">
                                <input type="text" v-model="descripcion" class="form-control" placeholder="Ingrese descripción">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" v-on:click="cerrarModal()">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Clientes alojados -->
    <!-- Marcar como no valido -->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalnoValido}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" class="close" v-on:click="cerrarModal()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label">Descripción</label>
                            <div class="col-md-9">
                                <input type="text" v-model="descripcion" class="form-control" placeholder="Ingrese descripción">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" v-on:click="cerrarModal()">Cerrar</button>
                    <button type="button" class="btn btn-primary" v-on:click="registrarCategoria()">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade text-left" :class="{'mostrar' : modalDetallesInfo}" id="large" tabindex="-1" role="dialog" aria-labelledby="myModalLabel17" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="height: 550px;">
                <div class="modal-header" style="background-color: white;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" v-on:click="cerrarModalInfo()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-block">
                                <h5><i class="fas fa-align-justify danger"></i> INFORMACIÓN COLORES</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <ul class="no-list-style">
                                            <li class="mb-2">
                                                <span class="text-bold-500 danger"><a><i class="fas fa-bed"></i> LLegadas</a></span>
                                            </li>
                                            <li class="mb-2">
                                                <span class="text-bold-500 blue1"><a><i class="fas fa-bed"></i> CheckOut</a></span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <ul class="no-list-style">
                                            <li class="mb-2">
                                                <span class="text-bold-500 blue1"><a><i class="fas fa-bed"></i> CheckIn:</a></span>
                                            </li>
                                            <li class="mb-2">
                                                <span class="text-bold-500 primary"><a><i class="ft-mail font-small-3"></i> Tipo Habitacion:</a></span>
                                                <a class="display-block overflow-hidden">sdfsdf</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <ul class="no-list-style">
                                            <li class="mb-2">
                                                <span class="text-bold-500 verde"><a><i class="fas fa-bed"></i> Salidas:</a></span>
                                            </li>
                                            <li class="mb-2">
                                                <span class="text-bold-500 primary"><a><i class="ft-briefcase font-small-3"></i> No Habitación:</a></span>
                                                <span class="display-block overflow-hidden">sdfdsf</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Comentario -->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalCalendario}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content" style="width: 85% !important;">
                <div class="modal-header">
                    <button type="button" class="close" v-on:click="cerrarModalCalendario()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <DayPilotNavigator id="nav" style="width:200px" :config="config" ref="navigator" />
                </div>
            </div>
        </div>
    </div>
    <!-- Marcar como no valido -->
    <div v-if="mostrar_facturacion">
        <factura :id_reserva="id_reserva_seleccionada" :id_habitacion="id_habitacion_seleccionada" :data_reserva="args"></factura>
    </div>
    <div v-if="mostrar_clientes">
        <crearcliente :id_reserva="id_reserva_seleccionada" :id_habitacion="id_habitacion_seleccionada" :cliente_id="cliente_id" :reserva_detalle_id="reserva_detalle_id" :id_grupo="id_grupo" :clientes_hospedados="clientes_hospedados">
        </crearcliente>
    </div>
    <!--Inicio del modal agregar/actualizar-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none; height: 10000px;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content" style="height: 602px;">
                <div class="modal-header">
                    <span class="badge badge-danger mr-1" v-text="tituloModal + ' ' + pago_a_realizar"></span>
                    <!--  <h5 class="modal-title" ></h5> -->
                    <button type="button" class="close" v-on:click="cerrarModal()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Tipo de Pago(*)</label>
                            <div class="col-md-9">
                                <select v-validate="'required'" class="form form-control" v-model="tipo_pago" name="tipo de pago">
                                    <option v-for="tip in TipoPagos" :key="tip.id" :value="tip.id">{{tip.nombre}}</option>
                                </select>
                                <ul class="list-group">
                                    <li class="list-group-item list-group-item-danger" v-for="error in errors.collect('tipo de pago')" :key="error">{{ error }}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Valor a Pagar</label>
                            <div class="col-md-9">
                                <input type="number" v-model="valor_a_pagar_f" class="form-control" placeholder="Valor a Pagar" v-validate="'required'" name="valor a pagar" />
                                <ul class="list-group">
                                    <li class="list-group-item list-group-item-danger" v-for="error in errors.collect('valor a pagar')" :key="error">{{ error }}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="form-group row">
                            <!-- <label class="col-md-3 form-control-label" for="email-input">Valor a Pagar</label> -->
                            <div class="col-md-9">
                                <button type="button" class="btn btn-raised gradient-ibiza-sunset white sidebar-shadow" v-if="tipoAccion==1" v-on:click="registrarPago(pago_a_realizar)">Guardar</button>
                                <button type="button" v-if="tipoAccion==2" class="btn btn-raised gradient-mint white shadow-z-4" v-on:click="actualizarPago(pago_id)">Actualizar</button>
                                <button type="button" v-if="tipoAccion==2" class="btn btn-raised gradient-pomegranate white big-shadow" v-on:click="NuevoPago()">Nuevo</button>
                            </div>
                        </div>
                    </form>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Pago Total <span class="badge badge-success mr-1" v-text="resultado_pagos"></span> Saldo Pendiente: <span class="badge badge-danger mr-1" v-text="diferencia_pagos"></span></h5>
                        </div>
                        <div class="card-body">
                            <div class="card-block">
                                <table class="table table-responsive-md-md text-center">
                                    <thead>
                                        <tr>
                                            <th>Tipo Pago</th>
                                            <th>Valor</th>
                                            <th>Fecha</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(pago,index) in Pagos" :key="index">
                                            <td v-text="pago.nombre"></td>
                                            <td v-text="pago.valor_pagado"></td>
                                            <td v-text="pago.fecha_creacion"></td>
                                            <td>
                                                <a class="success p-0" data-original-title="" title="" v-on:click="editarPago(pago)">
                                                    <i class="ft-edit-2 font-medium-3 mr-2"></i>
                                                </a>
                                                <a class="warning p-0" data-original-title="" title="" v-on:click="eliminarPago(pago)">
                                                    <i class="ft-trash-2 font-medium-3 mr-2"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @v-on:click="cerrarModal()">Cerrar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!--Fin del modal-->

    <!--Inicio del modal agregar/actualizar-->
    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalPrepagos}" role="dialog" aria-labelledby="myModalLabel" style="display: none; height: 10000px;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content" style="height: 602px;">
                <div class="modal-header">
                    <button type="button" class="close" v-on:click="cerrarModalPrepago()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">Tipo de Pago(*)</label>
                            <div class="col-md-9">
                                <select v-validate="'required'" class="form form-control" v-model="tipo_pago" name="tipo de pago">
                                    <option v-for="tip in TipoPagos" :key="tip.id" :value="tip.id">{{tip.nombre}}</option>
                                </select>
                                <ul class="list-group">
                                    <li class="list-group-item list-group-item-danger" v-for="error in errors.collect('tipo de pago')" :key="error">{{ error }}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="email-input">Valor a Pagar</label>
                            <div class="col-md-9">
                                <input type="number" step="0.01" v-model="valor_a_pagar_f" class="form-control" placeholder="Valor a Pagar" v-validate="'required'" name="valor a pagar" />
                                <ul class="list-group">
                                    <li class="list-group-item list-group-item-danger" v-for="error in errors.collect('valor a pagar')" :key="error">{{ error }}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="form-group row">
                            <!-- <label class="col-md-3 form-control-label" for="email-input">Valor a Pagar</label> -->
                            <div class="col-md-9">
                                <button type="button" class="btn btn-raised gradient-ibiza-sunset white sidebar-shadow" v-if="tipoAccion==1" v-on:click="registrarPagoPrepago(tipo_pago, valor_a_pagar_f, IdReserva)">Guardar</button>
                                <button type="button" v-if="tipoAccion==2" class="btn btn-raised gradient-mint white shadow-z-4" v-on:click="actualizarPagoPrepago(IdPrepago, tipo_pago, valor_a_pagar_f, IdReserva)">Actualizar</button>
                                <button type="button" v-if="tipoAccion==2" class="btn btn-raised gradient-pomegranate white big-shadow" v-on:click="NuevoPagoPrepago()">Nuevo</button>
                            </div>
                        </div>
                    </form>
                    <div class="card">

                        <div class="card-body" style="height: auto !important;">
                            <div class="card-block">
                                <table class="table table-responsive-md-md text-center">
                                    <thead>
                                        <tr>
                                            <th>Tipo Pago</th>
                                            <th>Valor</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(pago,index) in Pagos" :key="index">
                                            <td v-text="pago.nombre"></td>
                                            <td v-text="pago.valor_pagado"></td>
                                            <td>
                                                <a class="success p-0" data-original-title="" title="" v-on:click="editarPagoPrepago(pago)">
                                                    <i class="ft-edit-2 font-medium-3 mr-2"></i>
                                                </a>
                                                <a class="warning p-0" data-original-title="" title="" v-on:click="eliminarPagoPrepago(pago)">
                                                    <i class="ft-trash-2 font-medium-3 mr-2"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" v-on:click="cerrarModalPrepago()">Cerrar</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalBloqueos}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-primary modal-lg" role="document">
            <div class="modal-content" style="height: 500px;">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="tituloModal"></h4>
                    <button type="button" class="close" @click="cerrarModalBloqueo()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="detalles_habitacion">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-block">
                                            <h5><i class="fas fa-align-justify danger"></i> BLOQUEO HABITACIÓN</h5>
                                            <hr>
                                            <div class="container-fluid">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="userinput4">Fecha Inicio</label>
                                                                    <datepicker :format="customFormatter" v-model="fecha_inicio" :bootstrap-styling="true"></datepicker>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="userinput4">Fecha Fin</label>
                                                                    <datepicker :format="customFormatter" v-model="fecha_fin" :bootstrap-styling="true"></datepicker>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="userinput4">Habitación</label>
                                                                    <input type="text" v-model="habitacionNumero" disabled="true" class="form-control">
                                                                </div>
                                                            </div>

                                                            <div class="container-fluid">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <button type="button" v-if="tipoBloqueo==1" v-on:click="bloquearHabitacion()" class="btn btn-raised btn-success">
                                                                            <i class="fa fa-check-square-o"></i> Bloquear
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="card-block">
                                                                    <table class="table">
                                                                        <thead class="thead-default">
                                                                            <tr>
                                                                                <th>Fecha Inicio</th>
                                                                                <th>Fecha Fin</th>
                                                                                <th>Acción</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <tr v-for="bloqueo in arrayBloqueosHabitaciones" :key="bloqueo.id">
                                                                                <td v-text="bloqueo.fecha_desde"></td>
                                                                                <td v-text="bloqueo.fecha_hasta"></td>
                                                                                <td>
                                                                                    <button type="button" v-on:click="DesbloquearHabitacion(bloqueo.id, bloqueo.habitacion_id)" class="btn btn-raised btn-danger">
                                                                                        <i class="fa fa-check-square-o"></i> Desbloquear
                                                                                    </button>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            < </div> </div> </div> <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" v-on:click="cerrarModalBloqueo()">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
</main>
</template>

<script>
import {
    DayPilot,
    DayPilotScheduler,
    DayPilotNavigator
} from 'daypilot-pro-vue'

import Vue from 'vue';
import Datepicker from 'vuejs-datepicker';
import VueSweetAlert from 'vue-sweetalert'
import Autocomplete from 'vuejs-auto-complete';
import VueSweetalert2 from 'vue-sweetalert2';
import swal from 'vue-sweetalert2';
import Multiselect from 'vue-multiselect'
import VeeValidate from "vee-validate";
import factura from '../extras/facturacion';
import crearcliente from '../extras/crearcliente';
import checkingin from '../extras/cheking';
import prepagos from '../extras/prepagos';
window.Swal = swal;
import {
    VueSpinners
} from '@saeris/vue-spinners'
import {
    BarLoader
} from '@saeris/vue-spinners'
import PulseLoader from 'vue-spinner/src/PulseLoader.vue'

Vue.use(VueSpinners)
Vue.use(require('vue-moment'));
var moment = require('moment'); // in my gulp file
const VueValidationEs = require('vee-validate/dist/locale/es');
const Swal = require('sweetalert2')
Vue.use(VeeValidate, {
    locale: 'es',
    dictionary: {
        es: VueValidationEs
    }
});

window.swal = swal
// register globally
Vue.component('multiselect', Multiselect)
Vue.use(VeeValidate);

export default {
    props: ['myResult'],
    name: 'Scheduler',
    data() {
        return {
            verDetalles: 0,
            globalVar: 'global',
            imagen: [
                'http://blog.nubecolectiva.com/wp-content/uploads/2018/10/img_destacada_blog_devs-13-950x305.png'
            ],
            referenciaReserva: '',
            fecha_filtro: '',
            tarifa_diaria: '',
            IdReserva: '',
            total_ninos: '',
            modalTarifa: 0,
            tipo_habitacion_nueva: [],
            data_args: [],
            isLoad: false,
            tipoCarga: 0,
            color: '#bada55',
            loading: false,
            loadingActualizarReserva: false,
            dato_impuesto: 0,
            a: 0,
            b: 1,
            TipoPagos: [],
            numero_impuesto: '',
            id_habitacion_tipo_modal: '',
            data_checking: '',
            base: '',
            chekin: 1,
            mostrar_clientes: false,
            mostrar_reservas: true,
            guardar: true,
            config: [],
            configs: [],
            arrayBloqueosHabitaciones: [],
            fecha_llegada: '',
            fecha_salida: '',
            fecha_inicio: '',
            pruebar: '',
            filtro_calendario: '',
            fecha_fin: '',
            habitacion_nombre: '',
            huespedes_cantidad: '',
            precio_total: '',
            id_reservacion: 1,
            selected: null,
            tipoGuardar: 2,
            tipoBloqueo: 1,
            tipoAsignar: 0,
            disablebutton: false,
            //===============================basicos===============================//
            test: true,
            ampliar: false,
            habitacion: '0',
            tiempo: 60,
            porcentaje: 100,
            id: null,
            ArrayReservas: [],
            ArrayComentarios: [],
            arrayHabitacion: [],
            modalComentario: 0,
            modalCalendario: 0,
            modalAsignacion: 0,
            modalComentarios: 0,
            modalPrepagos: 0,
            modalDetallesInfo: 0,
            modalDetalle: 0,
            modalBloqueos: 0,
            modalAlojados: 0,
            tituloModal: '',
            modalnoValido: 0,
            modalCargando: 1,
            descripcion: '',
            listado: 1,
            //===============================COMENTARIOS===============================//
            requisitos_especiales: '',
            comentarios: '',
            //===============================COMENTARIOS===============================//
            //=================================DETALLES=================================//
            comentario: '',
            prueba3: '',
            regalo: '',
            cliente: '',
            direccion: '',
            zipcode: '',
            ciudad: '',
            pais: '',
            telefono: '',
            email: '',
            language: '',
            llegada_hora: '',
            llegada_fecha: '',
            salida: '',
            noches: '',
            habitacion_tipo: '',
            habitacion_personas: '',
            habitacion_precio: '',
            habitacion_precio_unitario: '',
            booking_number: '',
            cliente_pagina: '',
            llegada_fecha_dia: '',
            salida_dia: '',
            id_grupo: '',
            id_grupo_reserva: '',
            grupos_id: '',
            IdPrepago: '',
            //=================================DETALLES=================================//
            //=================================RESERVAS=================================//
            reservas: {
                llegada: '',
                salida: '',
                habitaciones: '' //habitaciones:{id_grupo,infantes_cantidad,ninnos_cantidad,adultos_cantidad}
            },
            checkedCategories: [],
            mainCategories: [{
                merchantId: '1'
            }, {
                merchantId: '2'
            }],

            //=================================RESERVAS=================================//

            //===============================basicos===============================//

            //================================Johan================================//

            mostrar_facturacion: false,
            mostrar_pagadores: false,
            mostrar_checkin: false,
            mostrar_prepagos: false,
            id_habitacion_grupo: '',
            habitacionNumero: '',
            habitacionId: '',
            ReservasPagadores: [],
            resultado: 0.0,
            ReservasGrupos: [],
            Impuestos: [],
            options: [],
            value: [],
            arrayImpuestosHabitaciones: [],
            GruposHabitaciones: [],
            Facturacion: [],
            arrayBloqueos: [],
            tipoAccion: 0,
            modal: 0,
            tipo_pago: null,
            valor_a_pagar_f: '',
            cliente_pago: 0,
            factura_detalle_id: 0,
            Pagos: [],
            idiomas: [],
            pago_id: 0,
            resultado_pagos: 0,
            pago_a_realizar: 0,
            submitted: false,
            diferencia_pagos: 0,
            show: true,
            tipoCliente: 0,
            grupos_select: '',
            id_reserva_seleccionada: '',
            id_habitacion_seleccionada: '',
            cliente_id: '',
            fecha_nacimiento: '',
            reserva_detalle_id: '',
            numero: '',
            nombre_tipo: '',
            cantidad_huespedes: '',
            clientes_hospedados: '',
            nombre_fuente: '',
            dp: '',
            nav: '',
            busquemos: '',
            valor: '',
            separators: '',
            filtro: '',
            fecha_date: '',
            numeroHabitacion: '',
            config: '',
        }
    },

    computed: {
        count() {
            this.$store.state;
            return this.$store.state.posts
        },

        // DayPilot.Scheduler object - https://api.daypilot.org/daypilot-scheduler-class/
        scheduler: function () {
            return this.$refs.scheduler.control;
        }
    },
    components: {
        checkingin,
        prepagos,
        factura,
        DayPilotScheduler,
        DayPilotNavigator,
        Autocomplete,
        Multiselect,
        crearcliente,
        BarLoader,
        PulseLoader,
        Datepicker
    },

    crearImagen() {
        this.cargarImagen()
    },
    methods: {
        loadEvents() {
            this.datosHabitaciones();
        }, 
        loadResources() {
            this.cargarHabitaciones();
        },
        cerrarModalBloqueo() {
            let me = this;
            me.modalBloqueos = 0;
        },
        dataIdiomas() {
            let me = this;
            var url = '/obtener/idioma';
            var arreglo = [];
            axios.get(me.base + url).then(function (response) {
                    var respuesta = response.data;
                    me.idiomas = respuesta.datos;
                    arreglo.push(respuesta.datos);
                })
                .catch(function (error) {
                    var response = error.response.data;
                });
            return "Detalles";
        },
        cerrarModalCalendario() {
            let me = this;
            me.modalCalendario = 0;
        },
        datepickerCerrado() {
            let me = this;
            me.fecha_date = 2;

        },
        customFormatter(date) {
            return moment(date).format('YYYY-MM-DD');
        },
        NuevoPagoPrepago() {
            let me = this;
            me.tipoAccion = 1;
            me.tipo_pago = '';
            me.valor_a_pagar_f = '';
        },
        adelantos() {
            let me = this;
            me.mostrar_reservas = false;
            me.mostrar_prepagos = true;
        },

        editarPagoPrepago(pago) {
            let me = this;
            me.tipoAccion = 2;
            me.tipo_pago = pago.tipo_pago_id;
            me.valor_a_pagar_f = pago.valor_pagado;
            me.IdPrepago = pago.id;

        },
        cerrarModalTarifa() {
            let me = this;
            me.modalTarifa = 0;
            me.tarifa_diaria = '';
            me.iniciar();
        },
        eliminarPagoPrepago(pago) {
            let me = this;
            me.$swal({
                title: 'Desea eliminar esta nota prepago?',
                text: 'Eliminar Nota Prepago',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si Eliminar!',
                cancelButtonText: 'No, Cancelar!',
                showCloseButton: true,
                showLoaderOnConfirm: true
            }).then((result) => {
                if (result.value) {
                    axios
                        .post(me.base + "/reservas/eliminar_nota_prepago", {
                            idPago: pago.id,
                        })
                        .then(function (response) {
                            me.$swal('Eliminado', 'Esta nota prepago fue eliminada', 'success')
                            me.listarPrepagos(pago.reserva_id);

                        })
                        .catch(function (error, otracosa) {});

                } else {
                    me.$swal('Canelado', 'Cancelaste esta operación', 'info')
                }
            })

        },

        datepickerAbierto: function () {
            let me = this;
            // console.log(me.filtro_calendario);
        },
        fechaSeleccionada: function () {
            let me = this;
            // console.log(me.filtro_calendario);
        },
        datepickerCerrado: function () {
            let me = this;
            me.filtro_calendario = me.filtro_calendario;

            if (me.filtro_calendario != '') {

                me.prueba3 = me.filtro_calendario;
            }

        },
        convert(str) {
            var date = new Date(str),
                mnth = ("0" + (date.getMonth() + 1)).slice(-2),
                day = ("0" + date.getDate()).slice(-2);
            return [date.getFullYear(), mnth, day].join("-");
        },
        obtenerImpuestosProductos() {
            let me = this;
            var url = '/obtener/impuestos_habitaciones';
            axios.get(me.base + url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayImpuestosHabitaciones = respuesta.datos;

                })
                .catch(function (error) {
                    var response = error.response.data;
                });
        },
        cerrarModalInfo() {
            let me = this;
            me.modalDetallesInfo = 0;
        },
        tipoPagos() {
            let me = this;
            var url = 'reservas/tipo_pagos';
            axios.get(me.base + url).then(function (response) {
                    var respuesta = response.data;
                    me.TipoPagos = respuesta.tipo_pagos;
                })
                .catch(function (error, otracosa) {
                    // var response = error.response.data;
                });
        },
        ModalInfoReservas() {
            let me = this;
            me.modalDetallesInfo = 1;
        },

        quitarFecha(fecha) {
            var fechaValor = new Date(fecha);
            var otraFecha = fechaValor.setDate(fechaValor.getDate() - 1);
            return otraFecha;
        },
        registrarPagoPrepago(tipo_pago, valor_a_pagar, reserva_id) {
            let me = this;
            axios.post(me.base + "/reservas/agregar_prepago_nota", {
                    tipo_pago: tipo_pago,
                    valor_a_pagar: valor_a_pagar,
                    reserva_id: reserva_id,
                })
                .then(function (response) {
                    me.listarPrepagos(reserva_id);
                    Vue.swal("Muy bien!", "Realizo Nota prepago!", "success");
                })
                .catch(function (error, otracosa) {});
        },
        actualizarPagoPrepago(idPago, tipo_pago, valor_a_pagar, reserva_id) {
            let me = this;
            axios.post(me.base + "/reservas/actualizar_prepago_nota", {
                    idPago: idPago,
                    tipo_pago: tipo_pago,
                    valor_a_pagar: valor_a_pagar,
                })
                .then(function (response) {
                    me.listarPrepagos(reserva_id);
                    Vue.swal("Muy bien!", "Realizo Nota prepago!", "success");
                })
                .catch(function (error, otracosa) {});
        },
        formatDate(date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();
            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;

            return [year, month, day].join('-');
        },
        bloquearHabitacion() {
            let me = this;
            me.tipoBloqueo = 2;
            axios.post(me.base + "/reservas/bloquear_habitaciones", {
                    fecha_inicio: me.fecha_inicio,
                    fecha_fin: me.formatDate(me.fecha_fin),
                    habitacion: me.habitacionId
                })
                .then(function (response) {
                    // me.modalBloqueos = 0;
                    Vue.swal("Muy bien!", "Realizo bloqueo a esta habitación!", "success");
                    me.listarBloqueosHabitaciones(me.habitacionId);
                    me.iniciar();
                    me.tipoBloqueo = 1;
                    // me.$router.go(me.$router.currentRoute)
                })
                .catch(function (error, otracosa) {});
        },
        DesbloquearHabitacion(id, habitacion_id) {

            let me = this;
            Vue.swal({
                title: 'Deseas desbloquear esta fecha?',
                text: "Estas seguro de desbloquear esta habitación!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si Desbloquear!'
            }).then(function () {
                axios
                    .post(me.base + "/reservas/desbloquear_habitacion?id=" + id + '&habitacion=' + habitacion_id)
                    .then(function (response) {
                        Vue.swal({
                            title: "Desbloqueada!",
                            text: "Habitación Desbloqueada!",
                            icon: "success",
                            button: "Aww yiss!",
                        });
                        me.listarBloqueosHabitaciones(habitacion_id);
                        me.iniciar();
                    })
                    .catch(function (error, otracosa) {});
            }).catch(function (reason) {});
        },
        ModalInfoCalendario() {
            let me = this;
            me.modalCalendario = 1;
        },
        cargarImagen() {
            this.isLoad = false
            this.$nextTick(() => {
                this.imagen = this.imagen[this.imagen];
            })
        },
        esperarTiempo() {
            setTimeout(function () {
                this.isLoad = true
            }.bind(this), 3000);
        },
        busqueda(filtro) {
            let me = this;
            me.dp.events.filter("POPAYAN123", true)
        },
        filter(valor) {
            let me = this;
        },
        selectHabitacion() {
            let me = this;
            if (me.habitacion > 0) {
                me.tipoAsignar = 1;
            }
        },
        seleccionarClientes() {
            let me = this;
            var url = '/obtener/clientes';
            axios.get(me.base + url).then(function (response) {
                    me.arrayClientes = response.data.clientes
                })
                .catch(function (error) {
                    var response = error.response.data;
                });
        },
        seleccionarHabitaciones(data) {
            let me = this;
            var url = '/reservas/grupo_habitaciones?reserva_id=' + data.id_reservas;
            axios.get(me.base + url).then(function (response) {
                    var respuesta = response.data.habitaciones;
                    me.GruposHabitaciones = response.data.habitaciones;
                })
                .catch(function (error, otracosa) {});
        },
        facturas(data) {
            let me = this;
            var url = '/reservas/reserva_factura?reserva_id=' + data.id_reservas;
            axios.get(me.base + url).then(function (response) {
                    var respuesta = response.data;
                    me.Facturacion = respuesta.FacturaDetalle;
                })
                .catch(function (error, otracosa) {
                    // var response = error.response.data;
                });
        },
        facturacion(data) {
            console.log(data, "mi data");
            let me = this;
            me.id_reserva_seleccionada = data.id_reservas;
            me.mostrar_reservas = false;
            me.mostrar_facturacion = true;
            me.mostrar_pagadores = false;
            this.seleccionarClientes();
            this.seleccionarHabitaciones(data);
            this.facturas(data);
            if (me.grupos_select == '') {
                me.id_habitacion_grupo = data.resource;
            } else {
                me.id_habitacion_grupo = me.grupos_select;
                me.ReservasGrupos = [];
            }
            var url = '/reservas/reserva_grupo?reserva_id=' + data.id_reservas + '&habitacion=' + me.id_habitacion_grupo;
            axios.get(me.base + url).then(function (response) {
                    var respuesta = response.data;                  
                    me.resultado = 0;
                    me.ReservasGrupos = respuesta.reservas;
                })
                .catch(function (error, otracosa) {
                });

        },
        rerender() {

            this.show = false
            this.$nextTick(() => {
                this.show = true
                this.$nextTick(() => {})
            })
        },

        clienteseleccionado(data) {
            let me = this;
            var index = parseInt(data.selectedObject.id_index1);
            var index2 = parseInt(data.selectedObject.id_index2);
            me.reservas.habitaciones[index].huespedes_data[index2].id_huesped = data.selectedObject.id
            me.reservas.habitaciones[index].huespedes_data[index2].nombre_huesped = data.selectedObject.name;
        },
        updateColor: function (e, color) {
            let me = this;
            axios
                .post(me.base + "/reservas/changecolor", {
                    id_reserva: e.data.id,
                    color: color
                })
                .then(function (response) {
                    me.datosHabitaciones();
                })
                .catch(function (error, otracosa) {});
        },

        EliminaComentario(comentario) {
            let me = this;
            me.$swal({
                title: '¿Está seguro de eliminar este comentario?',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si',
                cancelButtonText: 'No',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true,
            }).then(result => {
                if (result.value) {
                    let me = this;

                    axios
                        .put('/reservas/elimina_comentario', {
                            id: comentario,
                        })
                        .then(function (response) {
                            me.listarComentarios(me.grupos_id);
                        })
                        .catch(function (error) {
                            var response = error.response.data;
                        });
                } else if (

                    result.dismiss === swal.DismissReason.cancel
                ) {

                }
            });
        },

        iniciar() {

                let me = this;
                  me.config = {
                timeHeaders: [{
                    "groupBy": "Month"
                }, {
                    "groupBy": "Day",
                    "format": "d"
                }],
                scale: "Day",
                days: DayPilot.Date.today().daysInMonth(),
                startDate: DayPilot.Date.today().firstDayOfMonth(),
                timeRangeSelectedHandling: "Enabled",
                onTimeRangeSelected: async (args) => {
                    const dp = args.control;
                    const modal = await DayPilot.Modal.prompt("Create a new event:", "Event 1");
                    dp.clearSelection();
                    if (modal.canceled) {
                        return;
                    }
                    dp.events.add({
                        start: args.start,
                        end: args.end,
                        id: DayPilot.guid(),
                        resource: args.resource,
                        text: modal.result
                    });
                },
                eventMoveHandling: "Update",
                onEventMoved: (args) => {
                    args.control.message("Reserva Modificada: " + args.e.text());
                },
                eventResizeHandling: "Update",
                onEventResized: (args) => {
                    args.control.message("Reserva Mofidicada: " + args.e.text());
                },
                eventDeleteHandling: "Update",
                onEventDeleted: (args) => {
                    args.control.message("Reserva Eliminada: " + args.e.text());
                },
                eventClickHandling: "Disabled",
                treeEnabled: true,
            },

              me.config.contextMenu=new DayPilot.Menu({
	                    items: [
	                        {
	                            text: "Eliminar",
	                            icon : 'fa fa-trash',
	                            onClick: function(args) {
	                                
	                            }
	                        },
	                        {
	                            text: "Ver detalles",
	                            icon: "fas fa-info-circle",
	                            onClick: function(args,args2) { me.fnmodalDetalle(args.source.data.detalles)}
	                        },
							{
	                            text: "Hacer check in",
	                            icon: "fas fa-address-card",
	                            onClick: function(args,args2) { me.CheckIn(args.source.data)}
	                        },
							
	                        {
	                            text: "-"
	                        },
	
							{
	                            text: "facturacion",
	                            icon: "fas fa-address-card",
	                            onClick: function(args,args2) 
	                            {

	                             me.args = args;
	                             me.facturacion(args.source.data)}
	                        },


	                    ]
	                });

        this.loadResources();
        this.loadEvents();

        this.scheduler.message("Welcome!");
        Echo.join(`chat`)
            .here((users) => {
                //
            })
            .joining((user) => {})
            .leaving((user) => {});
        },

     
        diasEntreFechas(desde, hasta) {
            var dia_actual = desde;
            var fechas = [];
            while (dia_actual.isSameOrBefore(hasta)) {
                fechas.push(dia_actual.format('YYYY-MM-DD'));
                dia_actual.add(1, 'days');
            }
            return fechas;
        },
        listar_bloqueos() {
            let me = this;
            axios
                .get(me.base + "/reservas/listar_bloqueos")
                .then(function (response) {
                    var data = response.data;
                    me.arrayBloqueos = data.data;
                })
                .catch(function (error, otracosa) {});
        },
        AnularReservaGrupo(grupo, tipo_habitacion, fecha_llegada, fecha_salida) {
            let me = this;
            me.$swal({
                title: 'Desea anular esta reserva?',
                text: 'Anular Reserva',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si Cancelar!',
                cancelButtonText: 'No!',
                showCloseButton: true,
                showLoaderOnConfirm: true
            }).then((result) => {
                if (result.value) {
                    axios
                        .post(me.base + "/reservas/anular_reserva_grupo", {
                            grupo: grupo,
                            tipo_habitacion: tipo_habitacion,
                            fecha_llegada: fecha_llegada,
                            fecha_salida: fecha_salida,
                        })
                        .then(function (response) {
                            me.$swal('Anulado', 'Esta reserva fue anulada', 'success')
                            me.iniciar();
                            me.Listar();
                            me.modalAsignacion = 0;
                        })
                        .catch(function (error, otracosa) {});

                } else {
                    me.$swal('Canelado', 'Cancelaste esta operación', 'info')
                }
            })
        },
        desasignarReserva(data) {
            let me = this;
            me.$swal({
                title: 'Desea desasignar esta reserva?',
                text: 'Desasignar Reserva',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si Desasignar!',
                cancelButtonText: 'No, Cancelar!',
                showCloseButton: true,
                showLoaderOnConfirm: true
            }).then((result) => {
                if (result.value) {
                    axios
                        .post(me.base + "/reservas/desasignar", {
                            grupo: data.id_grupo,
                            fecha_ingreso: data.check_in_fecha,
                            fecha_salida: data.check_out_fecha,
                            id_habitacion_tipo: data.id_habitacion_tipo,
                            habitacion_id: data.resource,

                        })
                        .then(function (response) {
                            me.datosHabitaciones();
                            me.Listar();
                            me.$swal('Desasignar', 'Esta reserva fue desasignada', 'success')
                        })
                        .catch(function (error, otracosa) {});

                } else {
                    me.$swal('Canelado', 'Cancelaste esta operación', 'info')
                }
            })

        },
        AnularReserva(data) {

            let me = this;
            me.$swal({
                title: 'Desea cancelar esta reserva?',
                text: 'Cancelar Reserva',
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Si Cancelar!',
                cancelButtonText: 'No!',
                showCloseButton: true,
                showLoaderOnConfirm: true
            }).then((result) => {
                if (result.value) {
                    axios
                        .post(me.base + "/reservas/anular_reserva", {
                            data: data,
                        })
                        .then(function (response) {
                            me.$swal('Anulado', 'Esta reserva fue cancelada', 'success')
                            me.iniciar();

                        })
                        .catch(function (error, otracosa) {});

                } else {
                    me.$swal('Canelado', 'Cancelaste esta operación', 'info')
                }
            })

        },

        listarBloqueosHabitaciones(data) {
            let me = this;
            var url = '/obtener/listar_bloqueos_habitaciones?habitacion=' + data;
            axios.get(me.base + url).then(function (response) {
                    var respuesta = response.data.data;
                    me.arrayBloqueosHabitaciones = respuesta;

                })
                .catch(function (error, otracosa) {});
        },
        modalBloqueo(data) {
            let me = this;

            var url = '/obtener/listar_habitacion?habitacion=' + data.resource;
            axios.get(me.base + url).then(function (response) {
                    var respuesta = response.data.data;

                    me.habitacionNumero = respuesta.numero;
                    me.habitacionId = respuesta.id;

                })
                .catch(function (error, otracosa) {});

            me.listarBloqueosHabitaciones(data.resource);

            me.modalBloqueos = 1;
            me.fecha_inicio = data.start.value;
            me.fecha_fin = me.quitarFecha(data.end.value);

        },

        Comentarios(args) {
            let me = this;
            me.modalComentarios = 1;

            me.grupos_id = args.id_grupo;
            me.listarComentarios(me.grupos_id);

        },

        listarPrepagos(reserva_id) {
            let me = this;
            var url = '/reservas/obtener_prepagos_notas?reserva_id=' + reserva_id;
            axios.get(me.base + url).then(function (response) {
                    var respuesta = response.data;
                    me.Pagos = respuesta.datos;

                })
                .catch(function (error, otracosa) {

                });

        },

        prueba() {
            let me = this;
            me.dp.events.filter(me.busquemos);
        },
        checkOut(args) {
            let me = this;
            me.$swal({
                title: 'Realizar CheckOut?',
                text: "Desea relizar el CheckOut de esta habitacion!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si',
                cancelButtonText: 'No',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                reverseButtons: true,
            }).then(result => {
                if (result.value) {
                    let me = this;
                    axios.post(me.base + "/reservas/checkOut", {
                            grupo_id: args.id_grupo,
                            check_out_fecha: args.check_out_fecha,
                            habitacion: args.resource
                        })
                        .then(function (response) {
                            Vue.swal("Muy bien!", "Realizo CheckOut a esta reserva!", "success");
                            me.$router.go(me.$router.currentRoute)
                        })
                        .catch(function (error, otracosa) {});
                } else if (

                    result.dismiss === swal.DismissReason.cancel
                ) {

                }
            });
        },
        GuardarComentario() {
            let me = this;
            axios.post(me.base + "/reservas/comentario", {
                    grupo_id: me.grupos_id,
                    comentario: me.comentario,
                })
                .then(function (response) {
                    Vue.swal("Guardado el comentario con éxito");
                    me.listarComentarios(me.grupos_id);
                })
                .catch(function (error, otracosa) {});
        },
        listarComentarios(grupos_id) {
            let me = this;
            var url = '/reservas/listar_comentarios?grupo_id=' + grupos_id;
            axios.get(me.base + url).then(function (response) {
                    var respuesta = response.data.comentarios;
                    me.ArrayComentarios = respuesta;

                })
                .catch(function (error, otracosa) {
                    // var response = error.response.data;
                });
        },
        async UpdateInventarios(args) {
            let me = this;
            var nueva_fecha_llegada = args.newStart.value;
            var nueva_fecha_salida = args.newEnd.value;
            var nueva_habitacion = args.newResource;
            axios.post(me.base + "/reservas/modificar_inventario", {
                    tipo_habitacion_nueva: me.tipo_habitacion_nueva,
                    tipo_habitacion_anterior: args.tipo_habitacion_anterior,
                    id_habitacion_nueva: args.newResource,
                    numero_habitacion_anterior: args.e.data.numero,
                    fecha_llegada_nueva: nueva_fecha_llegada,
                    fecha_salida_nueva: nueva_fecha_salida,
                    fecha_llegada_anterior: args.e.data.check_in_fecha,
                    fecha_salida_anterior: args.e.data.check_out_fecha,
                })
                .then(function (response) {})
                .catch(function (error, otracosa) {});
        },
        async UpdateInventariosExpandir(args) {
            let me = this;
            var nueva_fecha_llegada = args.newStart.value;
            var nueva_fecha_salida = args.newEnd.value;
            var nueva_habitacion = args.newResource;
            axios.post(me.base + "/reservas/modificar_inventario_expandir", {
                    tipo_habitacion_nueva: me.tipo_habitacion_nueva,
                    tipo_habitacion_anterior: args.tipo_habitacion_anterior,
                    id_habitacion_nueva: args.newResource,
                    numero_habitacion_anterior: args.e.data.numero,
                    fecha_llegada_nueva: nueva_fecha_llegada,
                    fecha_salida_nueva: nueva_fecha_salida,
                    fecha_llegada_anterior: args.e.data.check_in_fecha,
                    fecha_salida_anterior: args.e.data.check_out_fecha,
                })
                .then(function (response) {})
                .catch(function (error, otracosa) {});
        },
        async MoverReserva(args) {
            let me = this;
            var nueva_fecha_llegada = args.newStart.value;
            var nueva_fecha_salida = args.newEnd.value;
            var nueva_habitacion = args.newResource;
            me.tipoCarga = 1;
            axios.post(me.base + "/reservas/ActualizarFecha", {
                    numero: args.e.data.numero,
                    id_habitacion_tipo: args.e.data.id_reservas,
                    id_reserva: args.e.data.id_reservas,
                    id_grupo: args.e.data.id_grupo,
                    nueva_fecha_llegada: nueva_fecha_llegada,
                    nueva_fecha_salida: nueva_fecha_salida,
                    nueva_habitacion: nueva_habitacion,
                    fecha_llegada_anterior: args.e.data.check_in_fecha,
                    fecha_salida_anterior: args.e.data.check_out_fecha,
                    cantidad_adultos: args.e.data.adultos,
                    cantidad_ninos: args.e.data.ninos,
                    tarifa_promedio_diaria_ci: args.e.data.tarifa_promedio_diaria_ci,
                })
                .then(function (response) {
                    if (!response.data.validate) {
                        me.tipoCarga = 0;
                        if (response.data.data == 1) {
                            me.UpdateInventarios(args);
                        }
                    }
                    if (response.data.validate) {
                        Vue.swal("Muy bien!", "Actualizado!", "success");
                        location.reload();
                        me.tipoCarga = 0;
                    }
                    me.datosHabitaciones();
                })
                .catch(function (error, otracosa) {
                    me.datosHabitaciones();
                });
        },
        async NuevaActualizacionMover(args) {
            let me = this;
            var url_1 = '/obtener/tipo_habitacion_nueva?tipo_habitacion=' + args.newResource;
            let response = await axios.get(me.base + url_1);
            me.tipo_habitacion_nueva = response.data.datos.id_habitacion_tipo;
            if (args.tipo_habitacion_anterior !== me.tipo_habitacion_nueva) {
                Vue.swal({
                    title: 'Esta cambiando de tipo de habitación desea cambiar la tarifa?',
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si!',
                    cancelButtonText: 'No',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    reverseButtons: true,
                }).then(result => {
                    if (result.value) {
                        let me = this;
                        me.modalTarifa = 1;
                        me.tarifa_diaria = '';
                        me.data_args = args;
                    } else {
                        me.MoverReserva(args);
                    }
                });
            } else {
                me.MoverReserva(args);
            }
        },
        registrarData() {
            let me = this;
            var nueva_fecha_llegada = me.data_args.newStart.value;
            var nueva_fecha_salida = me.data_args.newEnd.value;
            var nueva_habitacion = me.data_args.newResource;
            me.tipoCarga = 1;
            axios.post(me.base + "/reservas/ActualizarFecha", {
                    numero: me.data_args.e.data.numero,
                    id_habitacion_tipo: me.data_args.e.data.id_reservas,
                    id_reserva: me.data_args.e.data.id_reservas,
                    id_grupo: me.data_args.e.data.id_grupo,
                    nueva_fecha_llegada: nueva_fecha_llegada,
                    nueva_fecha_salida: nueva_fecha_salida,
                    nueva_habitacion: nueva_habitacion,
                    fecha_llegada_anterior: me.data_args.e.data.check_in_fecha,
                    fecha_salida_anterior: me.data_args.e.data.check_out_fecha,
                    cantidad_adultos: me.data_args.e.data.adultos,
                    cantidad_ninos: me.data_args.e.data.ninos,
                    tarifa_promedio_diaria_ci: me.tarifa_diaria,
                })
                .then(function (response) {
                    if (!response.data.validate) {
                        me.tipoCarga = 0;
                        me.tarifa_diaria = '';
                        me.cerrarModalTarifa();
                        me.UpdateInventarios(me.data_args);
                    }
                    if (response.data.validate) {
                        Vue.swal("Muy bien!", "Actualizado!", "success");
                        location.reload();
                        me.tipoCarga = 0;
                    }
                    me.datosHabitaciones();
                })
                .catch(function (error, otracosa) {
                    me.datosHabitaciones();
                });
        },
        NuevaActualizacionExpandir(args) {

            let me = this;
            var nueva_fecha_llegada = args.newStart.value;
            var nueva_fecha_salida = args.newEnd.value;
            var nueva_habitacion = args.newResource;
            me.tipoCarga = 1;
            axios.post(me.base + "/reservas/expandir_reserva", {
                    numero: args.e.data.numero,
                    id_habitacion_tipo: args.e.data.id_reservas,
                    id_reserva: args.e.data.id_reservas,
                    id_grupo: args.e.data.id_grupo,
                    nueva_fecha_llegada: nueva_fecha_llegada,
                    nueva_fecha_salida: nueva_fecha_salida,
                    nueva_habitacion: nueva_habitacion,
                    fecha_llegada_anterior: args.e.data.check_in_fecha,
                    fecha_salida_anterior: args.e.data.check_out_fecha,
                    cantidad_adultos: args.e.data.adultos,
                    cantidad_ninos: args.e.data.ninos,
                    tarifa_promedio_diaria_ci: args.e.data.tarifa_promedio_diaria_ci,
                })
                .then(function (response) {
                    if (!response.data.validate) {
                        me.tipoCarga = 0;
                        me.UpdateInventariosExpandir(args);
                    }
                    if (response.data.validate) {
                        Vue.swal("Muy bien!", "Actualizado!", "success");
                        location.reload();
                        me.tipoCarga = 0;
                    }
                    me.datosHabitaciones();
                })
                .catch(function (error, otracosa) {
                    me.datosHabitaciones();
                });
        },

        updateColor: function (e, color) {
            let me = this;
            axios
                .post(me.base + "/reservas/changecolor", {
                    id_reserva: e.data.id,
                    color: color
                })
                .then(function (response) {
                    me.datosHabitaciones();
                })
                .catch(function (error, otracosa) {});
        },
        validar(id_reservas) {
            let me = this;
            return axios
                .post(me.base + "reservas/ReservasCompletas", {
                    id_reservas: id_reservas
                })
                .then(function (response) {
                    var data = response.data;
                    return data.validate;
                })
                .catch(function (error, otracosa) {
                    return false
                });
        },
        CheckIn(data) {
            let me = this;
            me.data_checking = data;
            me.mostrar_reservas = false;
            me.mostrar_checkin = true;
        },
        cargarHabitaciones() {
            let me = this;
            axios
                .get(me.base + "/habitaciones/index")
                .then(function (response) {
                    var data = response.data;
                    me.resources = data;
                    me.scheduler.update({
                        resources: data
                    }); //Pero aqui si funciona

                })
                .catch(function (error, otracosa) {});
        },
        datosHabitaciones() {
            let me = this;
            axios
                .get(me.base + "/reservas/data_reservas")
                .then(function (response) {
                    var data = response.data;
                    me.scheduler.update({
                        events: response.data
                    });
                })
                .catch(function (error, otracosa) {});
        },
        actualizar() {
            let me = this;
            me.datosHabitaciones();
        },
        ModalReservas(reserva) {
            this.modalAsignacion = 1;
            this.id_grupo_reserva = reserva.id_grupo
            this.fecha_llegada = reserva.fecha_llegada;
            this.fecha_salida = reserva.fecha_salida;
            this.huespedes_cantidad = parseInt(reserva.adultos_cantidad) + parseInt(reserva.infantes_cantidad) + parseInt(reserva.ninos_cantidad);
            this.infantes_cantidad = reserva.infantes_cantidad;
            this.ninos_cantidad = reserva.ninos_cantidad;
            this.adultos_cantidad = reserva.adultos_cantidad;
            this.habitacion_nombre = reserva.habitacion_nombre;
            this.precio_total = reserva.precio_total;
            this.id_habitacion_tipo_modal = reserva.id_habitacion_tipo
            this.info = reserva;
            this.total_ninos = parseInt(this.infantes_cantidad) + parseInt(this.ninos_cantidad);
            let me = this;
            me.arrayHabitacion = [];
            var url = '/disponibilidades/tipo_habitaciones_modal?fecha_ingreso=' + reserva.fecha_llegada + '&fecha_salida=' + reserva.fecha_salida + '&cantidad_adultos=' + this.adultos_cantidad + '&cantidad_ninos=' + this.total_ninos + '&tipo_habitacion=' + reserva.id_habitacion_tipo;
            // var url = '/obtener/habitaciones?tipo_habitacion=' + reserva.id_habitacion_tipo + '&fecha_llegada=' + reserva.fecha_llegada + '&fecha_salida=' + reserva.fecha_salida;
            axios.get(me.base + url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayHabitacion = respuesta.datos;
                })
                .catch(function (error) {
                    var response = error.response.data;

                });

            var id_tipo_habitacion = reserva.id_tipo_habitacion;
            var fecha_llegada = reserva.fecha_llegada;
            var fecha_salida = reserva.fecha_salida;
        },

        Asignar(info, habitacion) {
            let me = this;
            me.loading = true;
            me.tipoCarga = 1;
            if (me.habitacion != '0') {
                axios.post(me.base + '/reservas/actualizar', {
                    'data': info,
                    'habitacion': habitacion,
                    'numero_impuesto': me.numero_impuesto,
                    'id_grupo_reserva': me.id_grupo_reserva,

                }).then(function (response) {
                    me.tipoCarga = 0;
                    me.datosHabitaciones();
                    me.Listar();
                    me.cerrarModalAsignacion();
                    me.habitacion = 0;
                    me.tipoAsignar = 0;
                    me.cargarHabitaciones();
                }).catch(function (error) {
                    alert('Error');

                });
            }
        },
        cerrarModal() {

            this.modalComentario = 0;
            this.modalDetalle = 0;
            this.modalAlojados = 0;
            this.modalnoValido = 0;
            this.modal = 0;

        },
        cerrarModalAsignacion() {
            this.modalAsignacion = 0;
            this.habitacion = 0;
        },
        cerrarModalComentario() {
            this.modalComentarios = 0;
        },
        cerrarModalPrepago() {
            this.modalPrepagos = 0;
        },
        abrirModal(modelo, accion, data = []) {
            this.modal = 1;
        },
        nuevoCliente() {
            let me = this;
            me.modalCliente = true;
        },
        Listar() {
            let me = this;
            me.ArrayReservas = [];
            axios
                .get(me.base + "/reservas/VerReservasChanel")
                .then(function (response) {

                    me.ArrayReservas = response.data;
                })
                .catch(function (error, otracosa) {});
        },
        startInterval() {
            let me = this;
            if (me.test) {
                setInterval(function () {
                    if (me.tiempo == 0) {
                        me.tiempo = 60;
                        me.Listar();
                        me.porcentajes = 100;
                    }
                    me.porcentaje = Math.round((me.tiempo * 100) / 60);
                    me.tiempo = me.tiempo - 1;
                }, 1000);
            }
        },
        fnmodalDetalle(data, dataReserva) {
            let me = this;
            me.referenciaReserva = dataReserva.id_reservas;
            me.modalDetalle = 1;
            me.email = data.email;
            me.regalo = data.regalo;
            me.cliente = data.cliente;
            me.direccion = data.direccion;
            me.zipcode = data.zipcode;
            me.ciudad = data.ciudad;
            me.pais = data.pais;
            me.telefono = data.telefono;
            me.email = data.email;
            me.language = data.language;
            me.llegada_hora = data.llegada_hora;
            me.llegada_fecha = data.llegada_fecha;
            me.salida = data.salida;
            me.noches = data.noches;
            me.habitacion_tipo = data.habitacion_tipo;
            me.habitacion_personas = data.habitacion_personas;
            me.habitacion_precio = data.habitacion_precio;
            me.booking_number = data.booking_number;
            me.cliente_pagina = data.cliente_pagina;
            me.llegada_fecha_dia = data.llegada_fecha_dia;
            me.salida_dia = data.salida_dia;
            me.numero = data.numero;
            me.nombre_tipo = data.nombre_tipo;
            me.cantidad_huespedes = data.cantidad_huespedes;
            me.nombre_fuente = data.nombre_fuente;

        },
        fnmodalAlojados(data) {
            this.modalAlojados = 1;
        },
        fnmodalComentario(data) {
            this.requisitos_especiales = data.requisitos;
            this.comentarios = data.comentarios;
            this.modalComentario = 1;
        },
        savemodalComentario(data) {
            let me = this;
            axios.
            post(me.base + "reservas/save/comentarios/" + me.id, {
                requisitos_especiales: me.requisitos_especiales,
                comentarios: me.comentarios,
            }).
            then(function (response) {
                if (response.data.validate) {
                    me.Listar();
                    me.modalComentario = 0;
                    Vue.swal("Guardado el comentario con éxito");
                } else {
                    alert('Error');
                }
            });

        },
        fnmodalnoValido(data) {
            this.modalnoValido = 1;
        },
        recargar() {
            let me = this;
            me.$router.go(me.$router.currentRoute)
        },

    },

    created() {

        Echo.join(`chat`)
            .here((users) => {
                //
            })
            .joining((user) => {})
            .leaving((user) => {});
    },

    mounted() {

        this.base = base;
        this.iniciar();
        //this.dataIdiomas();
       // this.cargarHabitaciones();
        // this.datosHabitaciones();
        // this.Listar();
        // this.startInterval();

  

    }

}
</script>

<style>
#gantt_container {
    width: 100%;
    overflow-y: auto;
    overflow-x: hidden;
}

.asd {
    width: 30px;
    height: 30px;
}

.asd2 {
    background-color: #ff6347;
}

.asd1 {
    background-color: #65cc00;
}

.main {
    width: 100%;
}

.bold {
    font-weight: bold;
}

/*======================================================================*/
.context-menu-list {
    cursor: pointer;
    margin: 0;
    padding: 0;
    min-width: 120px;
    max-width: 250px;
    display: inline-block;
    position: absolute;
    list-style-type: none;
    border: 1px solid hsl(0, 0%, 87%);
    background: hsl(0, 0%, 93%);
    -webkit-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
    -moz-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
    -ms-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
    -o-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 11px;
}

.datra {
    display: inline-table;
    height: 16px;
    width: 16px;
    margin: 3px;
}

* .wd {
    box-sizing: content-box;
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

.modal-body {
    overflow-y: scroll;
    height: 240px;
}

.c100.small {
    font-size: 35px;
}

.list-group-item {
    padding: 0px;
}

.reservaciones-title {
    padding-top: 6px;
}

.splittwo {
    width: 50%;
    float: left;
}

.bold {
    font-weight: bold;
    font-size: 13px;
}

/*======================================================================*/
.icon {
    padding: 1px 1px 0px 8px;
}

.icon-blue {
    background-color: #1155cc;
}

.icon-green {
    background-color: #6aa84f;
}

.icon-yellow {
    background-color: #f1c232;
}

.icon-red {
    background-color: #cc0000;
}

.scheduler_default_corner_inner div {
    color: #F3F3F3 !important;
    background-color: #F3F3F3 !important;
}

span.error {
    color: #9F3A38;
}

.scheduler_default_corner div:nth-of-type(2) {
    display: none !important;
}

.scheduler_default_event_inner {
    color: black;
    border-radius: 0px;
    font-size: 9px;
    background: #ffffff !important;
    position: relative;
    top: 13px;
    left: 0px;
    height: 15px;
    /*  background-color: #cfdde8;
   */
}

.scheduler_default_event_bar {
    /*	border-radius: 14px;
   font-size: 9px;
   position: absolute;
   left: 6040px;
   top: 175px;
   width: 40px;
   height: 35px;
   white-space: nowrap;
   overflow: hidden;
   cursor: pointer;
   border-radius: 14px;
   */
}

/*.scheduler_default_event_bar_inner{
   display: none;
   }
   .scheduler_default_event_bar {
   display: none;
   }
   */
.scheduler_default_event_bar {
    /*	background: transparent;
   */
}

.scheduler_default_event_bar_inner {
    height: 13px;
    white-space: nowrap;
}

.example {
    margin: 20px;
}

.example input {
    display: none;
}

.example label {
    margin-right: 20px;
    display: inline-block;
    cursor: pointer;
}

.ex1 span {
    display: block;
    padding: 5px 10px 5px 25px;
    border: 2px solid #ddd;
    border-radius: 5px;
    position: relative;
    transition: all 0.25s linear;
}

.ex1 span:before {
    content: '';
    position: absolute;
    left: 5px;
    top: 50%;
    -webkit-transform: translatey(-50%);
    transform: translatey(-50%);
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background-color: #ddd;
    transition: all 0.25s linear;
}

.ex1 input:checked+span {
    background-color: #fff;
    box-shadow: 0 0 5px 2px rgba(0, 0, 0, 0.1);
}

.ex1 .red input:checked+span {
    color: red;
    border-color: red;
}

.ex1 .red input:checked+span:before {
    background-color: red;
}

.ex1 .blue input:checked+span {
    color: blue;
    border-color: blue;
}

.ex1 .blue input:checked+span:before {
    background-color: blue;
}

.ex1 .orange input:checked+span {
    color: orange;
    border-color: orange;
}

.ex1 .orange input:checked+span:before {
    background-color: orange;
}

.cargando {
    background-image: url('/img/cargando.gif');
    background-repeat: no-repeat;
    background-position-x: center;
    background-position-y: 30px;
    height: 100px;
    margin-top: 30px;
    margin-bottom: 30px;
}

#loading-bar-spinner.spinner {
    left: 50%;
    margin-left: -20px;
    top: 50%;
    margin-top: -20px;
    position: absolute;
    z-index: 19 !important;
    animation: loading-bar-spinner 400ms linear infinite;
}

#loading-bar-spinner.spinner .spinner-icon {
    width: 40px;
    height: 40px;
    border: solid 4px transparent;
    border-top-color: #00C8B1 !important;
    border-left-color: #00C8B1 !important;
    border-radius: 50%;
}

@keyframes loading-bar-spinner {
    0% {
        transform: rotate(0deg);
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
        transform: rotate(360deg);
    }
}

.loader.is-active:before {
    display: block;
    position: absolute;
    top: 214px;
}

.loader-default:after {
    content: "";
    position: fixed;
    width: 48px;
    height: 48px;
    border: 8px solid #fff;
    border-left-color: transparent;
    border-radius: 50%;
    top: calc(17% - 24px);
    left: calc(50% - 24px);
    animation: rotation 1s linear infinite;
}

.blue1 {
    color: rgb(20, 103, 239);
}

.verde {
    color: rgb(20, 179, 25);
}

.gris {
    color: rgb(117, 111, 111);
}

.navigator_default_titleleft {
    background-image: linear-gradient(45deg, #23BCBB, #45E994);
}

.navigator_default_titleright {
    background-image: linear-gradient(45deg, #23BCBB, #45E994);
}

.navigator_default_title {
    background-image: linear-gradient(45deg, rgb(202, 216, 83), rgb(255, 160, 0));
}

.navigator_8_main {
    font-family: Segoe UI Light, Segoe UI, Arial, Helvetica, Verdana, Sans Serif;
}

.navigator_8_main {
    border-left: 1px solid #A0A0A0;
    border-right: 1px solid #A0A0A0;
    border-bottom: 1px solid #A0A0A0;
    background-color: white;
    color: #000000;
}

.navigator_8_month {
    font-size: 8pt;
    /*border: 1px solid black;*/
}

.navigator_8_day {
    color: black;
    /*background-color: white;*/
}

.navigator_8_weekend {
    background-color: #f0f0f0;
}

.navigator_8_dayheader {
    color: black;
}

.navigator_8_line {
    border-bottom: 1px solid #A0A0A0;
}

.navigator_8_dayother {
    color: gray;
}

.navigator_8_todaybox {
    border: 1px solid red;
}

.navigator_8_select {
    background-color: #FFE794;
}

.navigator_8_title,
.navigator_8_titleleft,
.navigator_8_titleright {
    border-top: 1px solid #A0A0A0;
    color: #ffffff;
    background: #646464;
    -ms-transition: background-color .3s linear;
    -moz-transition: background-color .3s linear;
    -webkit-transition: background-color .3s linear;
    transition: background-color .3s linear;
}

.navigator_8_busy {
    font-weight: bold;
}

.container-checkbox {
    display: block;
    position: relative;
    padding-left: 35px;
    margin-bottom: 12px;
    cursor: pointer;
    font-size: 16px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

/* Hide the browser's default checkbox */

.container-checkbox input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

/* Create a custom checkbox */

.container-checkbox .checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 25px;
    width: 25px;
    background-color: #eee;
}

/* On mouse-over, add a grey background color */

.container-checkbox:hover input~.checkmark {
    background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */

.container-checkbox input:checked~.checkmark {
    background-color: #2196F3;
}

/* Create the checkmark/indicator (hidden when not checked) */

.container-checkbox .checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

/* Show the checkmark when checked */

.container-checkbox input:checked~.checkmark:after {
    display: block;
}

/* Style the checkmark/indicator */

.container-checkbox .checkmark:after {
    left: 9px;
    top: 5px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 3px 3px 0;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
}

/* The container */

.container-radio {
    display: block;
    position: relative;
    padding-left: 35px;
    margin-bottom: 12px;
    cursor: pointer;
    font-size: 22px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

/* Hide the browser's default radio button */

.container-radio input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

/* Create a custom radio button */

.container-radio .checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 25px;
    width: 25px;
    background-color: #eee;
    border-radius: 50%;
}

/* On mouse-over, add a grey background color */

.container-radio:hover input~.checkmark {
    background-color: #ccc;
}

/* When the radio button is checked, add a blue background */

.container-radio input:checked~.checkmark {
    background-color: #2196F3;
}

/* Create the indicator (the dot/circle - hidden when not checked) */

.container-radio .checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

/* Show the indicator (dot/circle) when checked */

.container-radio input:checked~.checkmark:after {
    display: block;
}

/* Style the indicator (dot/circle) */

.container-radio .checkmark:after {
    top: 9px;
    left: 9px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: white;
}

.startDate {
    width: 150px;
}

.custom-search {
    position: relative;
    width: 300px;
}

.custom-search-input {
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 100px;
    padding: 10px 100px 10px 20px;
    line-height: 1;
    box-sizing: border-box;
    outline: none;
}

.custom-search-botton {
    position: absolute;
    right: 3px;
    top: 38px;
    bottom: 3px;
    border: 0;
    background: #d1095e;
    color: #fff;
    outline: none;
    margin: 0;
    padding: 0 10px;
    border-radius: 100px;
    z-index: 2;
}

.input-group>.input-group-append>.btn,
.input-group>.input-group-append>.input-group-text,
.input-group>.input-group-prepend:not(:first-child)>.btn,
.input-group>.input-group-prepend:not(:first-child)>.input-group-text,
.input-group>.input-group-prepend:first-child>.btn:not(:first-child),
.input-group>.input-group-prepend:first-child>.input-group-text:not(:first-child) {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

.btn:not(:disabled):not(.disabled) {
    cursor: pointer;
}

.btn.btn-md {
    padding: .7rem 1.6rem;
    font-size: .7rem;
}

.input-group-prepend .btn,
.input-group-append .btn {
    position: relative;
    z-index: 2;
}

.md-form.input-group .form-control {
    padding: .375rem .75rem;
    margin: 0;
}
</style>
