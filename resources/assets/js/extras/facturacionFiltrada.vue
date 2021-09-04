<template>
    <div>
        <div class="col-sm-12">
            <div class="content-header">Detalles Reserva</div>
        </div>

        <section id="extendeds">
            <div class="container-fluid">
                <div class="row">
                    <div v-if="tipoCarga==1">
                        <div class="loader loader-default is-active" data-text></div>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    <button class="btn btn-danger" v-on:click="recargar()">
                                        <i class="ft-chevrons-left"></i>
                                    </button>
                                    <h5>
                                        <span class="badge badge-success">RESERVANTE ID: {{id_reserva}}</span>
                                    </h5>
                                </h4>
                                <div class="row">
                                    <div class="col-md-2 col-md-offset-1"><strong>RESERVANTE:</strong><br>{{data_reserva.text}}</div>
                                    <div class="col-md-2"><strong>NÚMERO DE HUÉSPEDES:</strong><br>{{data_reserva.cantidad_huespedes}}</div>
                                    <div class="col-md-2"><strong>FECHA DE RESERVA:</strong><br>{{data_reserva.fecha_reserva}}</div>
                                    <div class="col-md-2"><strong>ESTADO RESERVA:</strong><br>RESERVADO</div>
                                    <div class="col-md-2"><strong>TOTAL RESERVA:</strong><br>${{data_reserva.precio_total}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2"><strong>HABITACIONES:</strong><br>
                                        <span v-for="dato in data_reserva.grupos">
                                            {{dato.habitacion_numero}} HAB.
                                        </span>

                                    </div>
                                    <div class="col-md-2"><strong>FECHA ENTRADA:</strong><br>{{data_reserva.check_in_fecha}}</div>
                                    <div class="col-md-2"><strong>FECHA DE SALIDA:</strong><br>{{data_reserva.fecha_salida}}</div>
                                    <div class="col-md-2"><strong>FUENTE DE RESERVA:</strong><br>{{data_reserva.nombre_fuente}}</div>
                                    <div class="col-md-2"><strong>MONTO PAGADO:</strong><br>${{monto_pago}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 col-md-offset-1"><strong>EMPRESA:</strong><br>N/A</div>
                                    <div class="col-md-2"><strong>TIPO DE HABITACIÓN:</strong><br>
                                        <span v-for="dato in data_reserva.grupos">
                                            {{dato.habitacion_tipo}}.
                                        </span>
                                    </div>
                                    <div class="col-md-2"><strong>SALDO PENDIENTE:</strong><br>${{data_reserva.precio_total - monto_pago}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 ">
                <div class="card ">
                    <div class="card-header ">
                        <h4 class="content-header " style="font-size: 14px;">
                            <span class="badge badge-warning" style="color:white;">HABITACIONES</span>
                        </h4>
                            <button type="submit" style="background-color: #409eff; border-color: #fff;" class="btn btn-primary linea" @click="abrirModal('data','registrar')">Abonar  <i class="fas fa-wallet"></i></button>
                            <button type="submit" style="background-color: #ff8d60; border-color: #fff;" class="btn btn-primary linea" @click="abrirModalConsumos('data','registrar')">Consumos  <i class="fas fa-utensils"></i></button>

                    </div>
                    <div class="card-body ">
                        <div class="card-body " style="height: auto !important;">
                            <div class="card-block" style="overflow-x:auto;">
                                <table class="table table-responsive-md-md text-center ">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="font-size: 12px;">ID</th>
                                            <th style="font-size: 12px;">TIPO DE HABITACIÓN</th>
                                            <th style="font-size: 12px;">HABITACIÓN ASIGNADA</th>
                                            <th style="font-size: 12px;">OCUPACIÓN</th>
                                            <th style="font-size: 12px;">CHECK-IN</th>
                                            <th style="font-size: 12px;">CHECK-OUT</th>
                                            <th style="font-size: 12px;">PLAN TARIFARIO</th>
                                            <th style="font-size: 12px;">TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="dato in data_reserva.grupos" :key="dato.id ">
                                            <td style="font-size: 12px;">{{dato.clave_grupo}}</td>
                                            <td style="font-size: 12px;">{{dato.habitacion_tipo}}</td>
                                            <td style="font-size: 12px;">{{dato.habitacion_numero}}</td>
                                            <td style="font-size: 12px;">{{dato.cantidad_huespedes}}</td>
                                            <td style="font-size: 12px;">{{dato.fecha_ingreso}}</td>
                                            <td style="font-size: 12px;">{{dato.fecha_salida}}</td>
                                            <td style="font-size: 12px;">{{dato.plan_tarifario}}</td>
                                            <td style="font-size: 12px;">{{dato.total_habitacion}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 ">
                <div id="accordion">
                    <div class="card" v-for="dato in miarreglo" :key="dato.id ">
                        <div class="card-header" v-if="dato.cuentas.length>0">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" :data-target="'#' + dato.id" aria-expanded="false" aria-controls="collapseTwo">
                                    <span class="badge badge-danger">Cuenta N<sup>o</sup> : {{dato.nombre}}</span>
                                </button>
                            </h5>
                        </div>
                        <div :id="dato.id" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                            <div class="col-sm-12" style="overflow-x:auto;">
                                <table class="table table-responsive-md-md text-center ">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="font-size: 12px;">ID MOVIMIENTO</th>
                                            <th style="font-size: 12px;">FECHA</th>
                                            <th style="font-size: 12px;">HABITACIÓN</th>
                                            <th style="font-size: 12px;">CONCEPTO</th>
                                            <th style="font-size: 12px;">NOTAS</th>
                                            <th style="font-size: 12px;">CARGO</th>
                                            <th style="font-size: 12px;">ABONO</th>
                                            <th style="font-size: 12px;">ACCIÓN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="data in dato.cuentas" :key="data.id ">
                                            <td style="font-size: 12px;">{{data.id}}</td>
                                            <td style="font-size: 12px;">{{data.fecha_hora}}</td>
                                            <td style="font-size: 12px;">{{data.habitacion}}</td>
                                            <td style="font-size: 12px;">{{data.concepto}}</td>
                                            <td style="font-size: 12px;">{{data.notas}}</td>
                                            <td style="font-size: 12px;">{{data.cargo}}</td>
                                            <td style="font-size: 12px;">{{data.abono}}</td>
                                            <td style="font-size: 12px;">
                                                <template v-if="data.condicion==2">
                                                    <button type="button" class="btn-danger" title="eliminar abono" @click="eliminarAbono(data.id)"> <i class="fa fa-trash" aria-hidden="true"></i></button>
                                                </template>
                                                <template v-if="data.condicion==3">
                                                    <button type="button" class="btn-danger" title="eliminar Consumo" @click="eliminarConsumo(data.tipo_movimiento, data.reserva_id)"> <i class="fa fa-trash" aria-hidden="true"></i></button>
                                                </template>
                                                <button type="button" title="Cambiar Cuenta" class="shadow-z-2 btn-success" @click="abrirModalCambio('data','registrar', data)"> <i class="fa fa-exchange-alt" aria-hidden="true"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>Total</td>
                                            <td>{{dato.total_cargo}}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none; height: 10000px;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content" style="height: 602px;">
                        <div class="modal-header">
                            <span class="badge badge-info mr-1">Abono Realizado: {{abono_realizado_cuenta}}</span>
                            <span class="badge badge-danger mr-1">Saldo Pendiente: {{saldo_pendiente_cuenta}}</span>
                            <!--  <h5 class="modal-title" ></h5> -->
                            <button type="button" class="close" v-on:click="cerrarModal()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="post" @submit.prevent="registrarPago" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Cuenta(*)</label>
                                    <div class="col-md-9">
                                        <select required v-on:change="selectCuenta" class="form form-control" v-model="cuenta_abono" name="tipo de pago">
                                            <option v-for="cuenta_abono in arrayCuentas"  :key="cuenta_abono.id" :value="cuenta_abono.id">{{cuenta_abono.nombre}}</option>
                                        </select>
                                        <ul class="list-group">
                                            <li class="list-group-item list-group-item-danger" v-for="error in errors.collect('tipo de pago')" :key="error">{{ error }}</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Tipo de Pago(*)</label>
                                    <div class="col-md-9">
                                        <select required class="form form-control" v-model="tipo_pago" name="tipo de pago">
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
                                        <input type="number" step="0.01" v-model="valor_a_pagar_f" class="form-control" placeholder="Valor a Pagar" required name="valor a pagar" />
                                        <ul class="list-group">
                                            <li class="list-group-item list-group-item-danger" v-for="error in errors.collect('valor a pagar')" :key="error">{{ error }}</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">Fecha Abono</label>
                                    <div class="col-md-9">
                                        <datetime type="datetime" required v-model="fecha_abono" format="yyyy-MM-dd HH:mm:ss"></datetime>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <!-- <label class="col-md-3 form-control-label" for="email-input">Valor a Pagar</label> -->
                                    <div class="col-md-9">
                                        <button type="submit" class="btn btn-raised gradient-ibiza-sunset white sidebar-shadow" v-if="tipoAccion==1">Guardar</button>
                                        <button type="button" v-if="tipoAccion==2" class="btn btn-raised gradient-pomegranate white big-shadow" v-on:click="NuevoPago()">Nuevo</button>

                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" v-on:click="cerrarModal()">Cerrar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalCambio}" role="dialog" aria-labelledby="myModalLabel" style="display: none; height: 10000px;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content" style="height: 580px;">
                        <div class="modal-header">
                            <span class="badge badge-info mr-1">HABITACIÓN: {{NombreHabitacion}}</span>
                            <span class="badge badge-success mr-1">CONCEPTO: {{NombreConcepto}}</span>
                            <span class="badge badge-danger mr-1">CARGO: {{NombreCargo}}</span><br>
                            <span class="badge badge-warning mr-1" style="color:white;">{{NombreCuenta}}</span>
                            <!--  <h5 class="modal-title" ></h5> -->
                            <button type="button" class="close" v-on:click="cerrarModalCambio()" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                          <button class="btn btn-success" v-if="crearmiscuentas===0" v-on:click="eventoMostrarCuenta()">
                           Agregar Cuenta  <i class="fas fa-plus-circle"></i>
                          </button>
                            <form action="" method="post" v-if="crearmiscuentas===1" @submit.prevent="registrarCuenta" enctype="multipart/form-data" class="form-horizontal">
                                <center><h5 style="padding:0px;">Creación Cuenta <hr style="width:70%;border:3px dotted green;"></h5></center>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Nombre Cuenta(*)</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" v-model="micuenta">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <!-- <label class="col-md-3 form-control-label" for="email-input">Valor a Pagar</label> -->
                                    <div class="col-md-9">
                                        <button type="submit"  class="btn btn-raised gradient-ibiza-sunset white sidebar-shadow" v-if="tipoAccion==1" style="background-image: linear-gradient(45deg, #009da0, #0cc27e);">Crear <i class="fa fa-plus-circle"></i></button>
                                    </div>
                                </div>
                            </form>
                            <form action="" method="post" @submit.prevent="registrarCambio" enctype="multipart/form-data" class="form-horizontal">
                                <center><h5 style="padding:0px;">Cambio de Cuenta <hr style="width:70%;border:3px dotted #1976d2;"></h5></center>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">Cuenta(*)</label>
                                    <div class="col-md-9">
                                        <select required v-on:change="selectCuenta" class="form form-control" v-model="cuenta_cambio">
                                            <option v-for="cuenta_abono in arrayCuentas" :key="cuenta_abono.id" :value="cuenta_abono.id">{{cuenta_abono.nombre}}</option>
                                        </select>
                                        <ul class="list-group">
                                            <li class="list-group-item list-group-item-danger" v-for="error in errors.collect('tipo de pago')" :key="error">{{ error }}</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <!-- <label class="col-md-3 form-control-label" for="email-input">Valor a Pagar</label> -->
                                    <div class="col-md-9">
                                        <button type="submit" style="background-image: linear-gradient(45deg, #ee0979, #1976d2);" class="btn btn-raised gradient-ibiza-sunset white sidebar-shadow" v-if="tipoAccion==1">Cambiar <i class="fa fa-exchange-alt"></i></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" v-on:click="cerrarModalCambio()">Cerrar</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <div class="modal fade" tabindex="-1" :class="{'mostrar' : modalProductos}" role="dialog" aria-labelledby="myModalLabel" style="display: none; height: 10000px;" aria-hidden="true">
           <div class="modal-dialog modal-primary modal-lg" role="document">
              <div class="modal-content" style="height: 580px;">
                 <div class="modal-header">
                    <h4 class="modal-title"><span class="badge badge-succes">Registrar Consumos Extras</span></h4>
                    <button type="button" class="close" v-on:click="cerrarModalProductos()" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                 </div>
                 <br>
                 <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data" @submit.prevent="registrarConsumo" class="form-horizontal">
                       <div class="container-fluid">
                          <div class="row">
                             <div class="col-md-12">
                                <div class="row">
                                   <div class="col-md-6">
                                      <label for="exampleInputEmail1">Seleccione Punto de Venta(*)</label>
                                      <select class="form-control" required v-model="venta" v-on:change="obtenerCategorias(venta)">
                                         <option value="0" disabled="true">Seleccione Punto de Venta</option>
                                         <option v-for="(venta,index) in ArrayPuntosVenta" :key="index" :value="venta.id" v-text="venta.nombre"></option>
                                      </select>
                                   </div>
                                   <div class="col-md-6">
                                      <label for="exampleInputEmail1">Seleccione Categoria(*)</label>
                                      <select class="form-control" required v-model="categoria" v-on:change="seleccioneCategorias()">
                                         <option value="0" disabled="true">Seleccione Categoria</option>
                                         <option v-for="(categoria,index) in arrayCategoriasProductos" :key="index" :value="{id:categoria.categoria_id, nombre_categoria:categoria.nombre}" v-text="categoria.nombre"></option>
                                      </select>
                                   </div>
                                </div>
                             </div>
                          </div>
                       </div>
                       <div class="container-fluid">
                          <div class="row">
                             <div class="col-md-12">
                                <div class="row">
                                   <div class="col-md-6">
                                      <label for="exampleInputEmail1">Seleccione Producto(*)</label>
                                      <select class="form-control" required v-model="producto" v-on:change="seleccioneProducto()">
                                         <option value="0" disabled="true">Seleccione Producto</option>
                                         <option v-for="(producto,index) in arrayProductos" :key="index" :value="{id:producto.id, nombre_producto:producto.nombre}" v-text="producto.nombre"></option>
                                      </select>
                                   </div>
                                   <div class="col-md-6">
                                      <label for="exampleInputEmail1">$Precio(*)</label>
                                      <input type="text" required v-model="precio_producto" disabled class="form-control" placeholder="Precio Producto">
                                   </div>
                                </div>
                             </div>
                          </div>
                       </div>
                       <div class="container-fluid">
                          <div class="row">
                             <div class="col-md-12">
                                <div class="row">
                                   <div class="col-md-6">
                                      <label for="exampleInputEmail1">Unidad(*)</label>
                                      <input type="text" required v-on:keyup="calcularTotal()" v-model="unidad" class="form-control" placeholder="Unidad">
                                   </div>
                                   <div class="col-md-6">
                                     <label for="exampleInputEmail1">Cuenta(*)</label>
                                     <select required  required class="form form-control" v-model="cuenta_consumo">
                                       <option v-for="cuenta_abono in arrayCuentas" :key="cuenta_abono.id" :value="cuenta_abono.id">{{cuenta_abono.nombre}}</option>
                                     </select>
                                   </div>
                                </div>
                             </div>
                          </div>
                       </div>
                       <div class="container-fluid">
                          <div class="row">
                             <div class="col-md-12">
                                <div class="row">
                                   <div class="col-md-6">
                                      <label for="exampleInputEmail1">$Total Neto</label>
                                      <h5 v-model="total_extras"><span class="badge badge-danger">{{total_extras_neto}}</span></h5>
                                   </div>
                                </div>
                             </div>
                          </div>
                       </div>
                       <div class="modal-footer" align="right">
                          <button type="submit" class="btn btn-danger">Guardar</button>
                       </div>
                    </form>

                 </div>
                 <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" v-on:click="cerrarModalProductos()">Cerrar</button>
                 </div>
              </div>
           </div>
        </div>
        </section>
    </div>
</template>
<script>
    import Autocomplete from 'vuejs-auto-complete';
    import Vue from 'vue';
    import VueSweetAlert from 'vue-sweetalert'
    import VueSweetalert2 from 'vue-sweetalert2';
    import VeeValidate from "vee-validate";
    import {
        Datetime
    } from 'vue-datetime'
    // You need a specific loader for CSS files
    import 'vue-datetime/dist/vue-datetime.css'

    Vue.use(Datetime)
    import swal from "sweetalert2"
    import Datepicker from 'vuejs-datepicker';
    import moment from 'moment'
    const VueValidationEs = require('vee-validate/dist/locale/es');
    Vue.use(VeeValidate, {
        locale: 'es',
        dictionary: {
            es: VueValidationEs
        }
    });

    export default {
        props: ['id_reserva', 'id_habitacion', 'data_reserva'],
        data() {
            return {
                modalProductos: 0,
                crearmiscuentas: 0,
                AccionPagador: 0,
                modalPrepagos: 0,
                checkSelected: true,
                time: '19:06',
                date: '2018-05-12T00:00:00.000Z',
                datetime: '2018-05-12T17:19:06.151Z',
                datetime12: '2018-05-12T17:19:06.151Z',
                cuenta_reserva_id: '',
                fecha_abono: '',
                datetimeEmpty: '',
                pagosNotas: '',
                cuenta_cambio: '',
                pagador_cliente: '',
                micuenta: '',
                cuenta_abono: '',
                cuenta_tipo_movimiento: '',
                cuenta_fecha: '',
                cuenta_habitacion: '',
                nombre_cliente: '',
                cantidadPrepagos: '',
                cuenta_consumo: '',
                monto_pago: '',
                abono_realizado_cuenta: '',
                saldo_pendiente_cuenta: '',
                tipoCarga: 0,
                NombreCuenta: '',
                NombreCargo: '',
                NombreConcepto: '',
                NombreHabitacion: '',
                tipoFacturar: 1,
                BotonesPrefacturacion: 1,
                tipoAccionGenerar: 0,
                AccionImpuesto: 1,
                servicio: [{
                    name: 2
                }],
                no_contrato: 2,
                modalCambio: 0,
                valorImpuesto: 0,
                totalParcial: 0.0,
                aplicar: true,
                quitar: false,
                aplicar2: true,
                quitar2: false,
                isDisabled1: false,
                unidad: 0,
                tipoAccionMensaje: 1,
                id_consumo_extra: '',
                factura_id: '',
                reserva_id_detalle: '',
                total_consumo_extra: '',
                identificador_pagador: '',
                identificador_cliente: '',
                reserva_detalle_r1: '',
                tituloPagadorNombre: '',
                tituloPagadorApellido: '',
                tituloNumero: '',
                comentario: '',
                tituloFecha: '',
                total_consumo1: '',
                total_consumo2: '',
                impuesto: '',
                errorPersona: 0,
                reserva_id: '',
                miarreglo: [],
                ArrayDetalles: [],
                arrayCuentas: [],
                PagosPrepagosNotas: [],
                errorMostrarMsjPersona: [],
                arrayMensajesFactura: [],
                arrayConsumosExtras: [],
                modalCliente: false,
                producto: '',
                tarifa: '',
                total_extras: 0.0,
                precio_producto: '',
                puntoVenta: '',
                grupos_select: '',
                resultado: 0.0,
                resultado_neto: 0.0,
                base: '',
                categoria: '',
                arrayClientes: [],
                TotalFacturacion: [],
                data: '',
                ReservasGrupos: [],
                arrayCategoriasProductos: [],
                arrayImpuestosProductos: [],
                ReservasPagadores: [],
                arrayProductos: [],
                GruposHabitaciones: [],
                Facturacion: [],
                modal: 0,
                modalNuevaTarifa: 0,
                modalProductos2: 0,
                modalInfo: 0,
                tituloModal: '',
                pago_a_realizar: 0,
                tipo_pago: null,
                resultado_pagos: 0.0,
                tipoAccion: 0,
                TipoPagos: [],
                Pagos: [],
                prueba: [],
                arrayConsumosExtras2: [],
                GruposHabitaciones: [],
                Impuestos: [],
                tituloModal: '',
                valor_a_pagar_f: '',
                diferencia_pagos: 0.0,
                options: [],
                impuesto_valor: 13,
                id_reservas_grupo: '',
                reserva_precio_bruto: '',
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
                impuesto_valor_seleccionado: '',
                arrayCategoria: [],
                arrayCliente: [],
                arrayTipoCliente: [],
                arrayPais: [],
                arrayDepartamento: [],
                arrayDepartamentoFactura: [],
                arrayTipoContacto: [],
                arrayTipoDocumento: [],
                arraySexo: [],
                tituloModal: '',
                tipoAccion: 0,
                impuestoProducto: '',
                resultado_factura: 0.0,
                tipoAccionF: 1,
                errorCategoria: 0,
                result: 0,
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
                impuesto_ish: '',
                impuesto_servicio_habitacion: '',
                mensaje_id: '',
                impuesto_servicio: '',
                isOpen: '',
                nombres: '',
                apellidos: '',
                sexo: '',
                venta: '',
                nacionalidad: '',
                formula: '',
                titulo: '',
                isOpen2: '',
                isOpen3: '',
                fecha_nacimiento: '',
                apartments: [],
                ArrayPuntosVenta: [],
                PagosNotasPrepagos: [],
                tipoCliente: 0,
                cliente_pago: '',
                factura_idPago: '',
                idTotalPago: '',
                numeroPago: '',
                ValorPagado: '',
                nombrePagador: '',
                factura_idClave: '',
                no_pagadores: '',
                impuestorReserva: '',
                total_extras_neto: '',
                clave_reserva: '',
                checkedCategories: [],
                mainCategories: [{
                    merchantId: '1'
                }, {
                    merchantId: '2'
                }],
            }
        },
        components: {
            Autocomplete,
            Datepicker,
            datetime: Datetime
        },
        computed: {
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

            check: function(e) {
                if (e.target.checked) {
                    // console.log(e.target.value)
                }
            },
            listarCuentas() {
                let me = this;
                var url = 'reservas/tasas_impuestos_detalles?reserva_id=' + me.id_reserva;
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data.datos;
                        me.ArrayDetalles = respuesta;
                    })
                    .catch(function(error, otracosa) {
                        // var response = error.response.data;
                    });

            },
            eventoMostrarCuenta()
            {
              let me = this;
              me.crearmiscuentas = 1;
            },
            iva(iva, sin_iva) {
                var con_iva = parseFloat(sin_iva) + (parseFloat(iva) * (parseFloat(sin_iva) / 100));
                return parseFloat(con_iva).toFixed(2);

            },

            cerrarModalPrepago() {
                this.modalPrepagos = 0;
            },

            VerPrepagos() {

                let me = this;
                me.modalPrepagos = 1;
                me.tipoAccion = 1;
                me.AccionPagador = 0;
                me.listarPrepagos(me.id_reserva);
            },

            asignarPagoPrepago(pago) {
                let me = this;
                me.AccionPagador = 1;
                me.tipoAccion = 1;
                me.pagosNotas = pago;
                me.listarPrepagos(me.id_reserva);
            },

            AgregarPagoCliente(pagador_cliente, pagosNotas) {
                let me = this;
                axios.post(me.base + '/reservas/asignar_pagos_notas', {
                    'pagador_cliente': pagador_cliente,
                    'pagosNotas': pagosNotas

                }).then(function(response) {
                    me.tipoAccion = 0;
                    me.listarPrepagos(me.id_reserva);
                    me.total_facturas(me.factura_id);

                }).catch(function(error) {
                    console.log(error);
                });
            },
            rowTotal(base) {
                return this.pass.reduce((sum, cur) => sum + cur[base], 0);
            },
            listarPrepagos(reserva_id) {
                let me = this;
                var url = '/reservas/obtener_prepagos_notas?reserva_id=' + reserva_id;
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data;
                        me.PagosNotasPrepagos = respuesta.datos;

                    })
                    .catch(function(error, otracosa) {

                    });

            },


            CambioTarifaIndividual(reserva_detalle, precio_neto, numero_impuesto, numero_impuesto2, servicio, index) {
                let me = this;
                me.resultado = 0.0;
                me.resultado_neto = 0.0;
                servicio = (servicio == null) ? 0 : servicio;
                numero_impuesto = (numero_impuesto == null) ? 0 : numero_impuesto;
                numero_impuesto2 = (numero_impuesto2 == null) ? 0 : numero_impuesto2;

                axios.post(me.base + '/cambiar_tarifa/individual', {
                    'reserva_detalle': reserva_detalle,
                    'precio_neto': precio_neto,

                }).then(function(response) {
                    me.Facturacion = [];
                    // me.iniciar();

                    me.tipoFacturar = 1;
                    me.tipoAccionGenerar = 0;
                    me.resultado_factura = '';
                    me.BotonesPrefacturacion = 0;

                }).catch(function(error) {
                    console.log(error);
                });


                $.each(me.ReservasGrupos, function(key, reserva) {
                    if (index == key) {
                        reserva.precio_bruto = me.iva((parseFloat(numero_impuesto) + parseFloat(numero_impuesto2) + parseFloat(servicio)), parseFloat(precio_neto));
                    }
                });

                for (var i = 0; i < me.ReservasGrupos.length; i++) {

                    me.resultado = (parseFloat(me.resultado) + parseFloat(me.ReservasGrupos[i].precio_bruto));
                    me.resultado_neto = (parseFloat(me.resultado_neto) + (parseFloat(me.ReservasGrupos[i].precio_neto))).toFixed(2);
                }

                me.ReservasGrupos.map(function(reservacion, key) {

                    if (reservacion.pagadores_agrupados.length > 0) {
                        reservacion.pagadores_agrupados[0].valor_a_pagar = (parseFloat(reservacion.precio_bruto)).toFixed(2);
                    }

                })
            },
            async selectCuenta() {
                let me = this;
                var url = '/obtener_abonos?cuenta_id=' + me.cuenta_abono + '&reserva_id=' + me.id_reserva
                let data = await axios.get(url);
                me.abono_realizado_cuenta = parseFloat(data.data.suma_cuenta_abono);
                me.saldo_pendiente_cuenta = parseFloat(data.data.suma_cuenta_reserva) - parseFloat(data.data.suma_cuenta_abono);
            },
            registrarConsumo()
            {
              let me = this;
              if (me.unidad === 0 || me.unidad === null || me.unidad === '') {
                me.$swal('Verfica campo', 'Revisa campo unidad', 'error');
              }
              else {
              axios.post(me.base + '/reservas/crear_consumos', {
                  venta: me.venta,
                  categoria: me.categoria,
                  producto: me.producto,
                  precio_producto: me.precio_producto,
                  unidad: me.unidad,
                  total_extras_neto: me.total_extras_neto,
                  reserva_id: me.id_reserva,
                  cuenta_consumo:me.cuenta_consumo

              }).then(function(response) {
                  me.venta = '';
                  me.categoria = '';
                  me.producto = '';
                  me.precio_producto = '';
                  me.unidad = '';
                  me.total_extras_neto = '';
                  me.reserva_id = '';
                  me.cuenta_consumo = '';
                  me.miarreglo = [];
                  me.data_reserva.precio_total = response.data.datos;
                  var url_detalles = '/obtener/cuentasDetalles?reserva_id=' + me.id_reserva;
                  axios.get(me.base + url_detalles).then(function(response) {
                          var respuesta = response.data;

                          me.miarreglo = respuesta;
                      })
                      .catch(function(error) {
                          var response = error.response.data;
                          console.log(response.message,
                              response.exception,
                              response.file,
                              response.line);
                      });
                  me.cerrarModalProductos();

              }).catch(function(error) {
                  console.log(error);
              });
              }

            },
            cancelarFactura() {

                let me = this;
                axios.post(me.base + '/cancelar/factura', {
                    'datos': me.Facturacion

                }).then(function(response) {
                    me.Facturacion = [];
                    me.iniciar();

                    me.tipoFacturar = 1;
                    me.tipoAccionGenerar = 0;
                    me.resultado_factura = '';
                    me.BotonesPrefacturacion = 0;

                }).catch(function(error) {
                    console.log(error);
                });

            },
            async monto_pagado() {
                let me = this;
                var url = '/reservas/monto_pagado?reserva_id=' + me.id_reserva;
                let data = await axios.get(url);
                console.log(data.data.datos);
                me.monto_pago = parseFloat(data.data.datos);
            },
            guardarTarifa() {

                let me = this;
                axios.post(me.base + '/reservas/actualizar_tarifa', {
                    'data': me.ReservasGrupos,
                    'tarifa': me.tarifa

                }).then(function(response) {

                    me.resultado = 0;
                    for (var i = 0; i < me.ReservasGrupos.length; i++) {
                        me.resultado = me.resultado + (parseInt(me.ReservasGrupos[i].precio_bruto))
                        me.iniciar();

                    }

                    me.iniciar();
                    me.modalNuevaTarifa = 0;
                    me.tarifa = '';

                    me.$swal('Almacenado', 'Registro Almacenado', 'success');

                }).catch(function(error) {
                    console.log(error);
                });

            },

            cerrarModalTarifa() {
                let me = this;
                me.modalNuevaTarifa = 0;
            },

            registrarFacturas(factura_id) {
                let me = this;
                axios.post(me.base + '/registrar/pagos_facturacion', {
                    'factura_id': factura_id,
                    'reserva': me.clave_reserva

                }).then(function(response) {
                    me.total_facturas(factura_id)
                }).catch(function(error) {
                    console.log(error);
                });
            },
            total_facturas(factura_id) {
                // console.log(factura_id)
                let me = this;
                me.factura_id = factura_id;
                var url = '/obtener/total_facturas?factura_id=' + factura_id + '&reserva_id=' + me.clave_reserva + '&habitacion_id=' + me.id_habitacion;
                axios.get(me.base + url).then(function(response) {
                        me.TotalFacturacion = response.data.datos;

                        $.each(me.TotalFacturacion, function(key, facturacion) {

                            if (facturacion.total_pagado == null) {


                                facturacion.total_pagado = 0;
                            }

                            facturacion.total_a_pagar = parseFloat(facturacion.total_a_pagar).toFixed(2);
                            facturacion.total_pagado = parseFloat(facturacion.total_pagado).toFixed(2);

                            return facturacion;
                        });

                        $.each(me.TotalFacturacion, function(key, facturacion) {

                            if (facturacion.total_pagado == null) {


                                facturacion.total_pagado = 0;
                            }

                            facturacion.total_a_pagar = parseFloat(facturacion.total_a_pagar).toFixed(2);
                            facturacion.total_pagado = parseFloat(facturacion.total_pagado).toFixed(2);

                            return facturacion;
                        });



                    })
                    .catch(function(error, otracosa) {

                    });
            },

            CambioTarifa() {

                let me = this;
                me.modalNuevaTarifa = 1;

            },
            aplicarImpuesto() {
                let me = this;
                me.resultado = 0.0;
                me.resultado_neto = 0.0;
                me.aplicar = false;
                me.quitar = true;

                var result2 = 0;
                var impuestos2 = 0;

                me.ReservasGrupos.map(function(reservacion, key) {

                    if (reservacion.servicio == 0 || reservacion.servicio == null) {

                        reservacion.impuestos = (parseFloat(reservacion.numero_impuesto) + parseFloat(reservacion.numero_impuesto2) + parseFloat(me.impuesto_servicio_habitacion)) / 100;
                        me.result = (parseFloat(reservacion.impuestos) * parseFloat(reservacion.precio_neto)).toFixed(2);
                        reservacion.precio_bruto = (parseFloat(reservacion.precio_neto) + parseFloat(me.result)).toFixed(2);
                        reservacion.servicio = me.impuesto_servicio_habitacion;

                        reservacion.pagadores_agrupados.map(function(pagador, key2) {
                            if (pagador.valor_a_pagar > 0) {
                                pagador.neto = ((parseFloat(pagador.valor_a_pagar)) / ((100 + (parseFloat(reservacion.numero_impuesto) + parseFloat(reservacion.numero_impuesto2))) / 100)).toFixed(2);
                                pagador.impuestos = (parseFloat(reservacion.numero_impuesto) + parseFloat(reservacion.numero_impuesto2)) + parseFloat(me.impuesto_servicio_habitacion);
                                pagador.valor_a_pagar = parseFloat(reservacion.precio_bruto).toFixed(2);
                            }
                        });
                        return reservacion;
                    }
                });

                for (var i = 0; i < me.ReservasGrupos.length; i++) {
                    me.resultado = me.resultado + (parseFloat(me.ReservasGrupos[i].precio_bruto))
                    me.resultado_neto = me.resultado_neto + (parseFloat(me.ReservasGrupos[i].precio_neto))
                    // me.iniciar();

                }

                axios.post(me.base + '/aplicar_servicio/reserva', {
                    'reservas': me.ReservasGrupos


                }).then(function(response) {
                    me.registrarFacturas(response.data.datos);
                    me.$swal('Almacenado', 'Registro Almacenado', 'success');

                }).catch(function(error) {
                    console.log(error);
                });

            },


            quitarImpuesto() {
                let me = this;
                me.resultado = 0.0;
                me.resultado_neto = 0.0;
                me.aplicar = true;
                me.quitar = false;

                me.ReservasGrupos.map(function(reservacion, key) {

                    if (reservacion.servicio != null) {

                        reservacion.impuestos = parseFloat(reservacion.numero_impuesto) + parseFloat(reservacion.numero_impuesto2);
                        me.result = (parseFloat(reservacion.precio_neto) * parseFloat(reservacion.impuestos)) / 100
                        reservacion.precio_bruto = (parseFloat(reservacion.precio_neto) + parseFloat(me.result)).toFixed(2);
                        reservacion.servicio = 0;

                        reservacion.pagadores_agrupados.map(function(pagador, key2) {
                            if (pagador.valor_a_pagar > 0) {
                                pagador.valor_a_pagar = parseFloat(reservacion.precio_bruto).toFixed(2);
                            }
                        });
                    }
                });


                for (var i = 0; i < me.ReservasGrupos.length; i++) {
                    me.resultado = me.resultado + (parseInt(me.ReservasGrupos[i].precio_bruto))
                    me.resultado_neto = me.resultado_neto + (parseFloat(me.ReservasGrupos[i].precio_neto))

                }

                axios.post(me.base + '/quitar_servicio/reserva', {
                    'reservas': me.ReservasGrupos


                }).then(function(response) {
                    me.registrarFacturas(response.data.datos);
                    me.$swal('Almacenado', 'Registro Almacenado', 'success');

                }).catch(function(error) {
                    console.log(error);
                });


            },


            aplicarServicio() {
                let me = this;
                me.resultado = 0.0;
                me.aplicar2 = false;
                me.quitar2 = true;

                $.each(me.Facturacion, function(key, reservacion) {

                    $.each(reservacion.pagadores, function(key, pagador) {

                        pagador.total_neto = (parseFloat(pagador.total_consumos)) / ((100 + parseFloat(me.impuestoProducto)) / 100);
                        me.result = (parseFloat(pagador.total_neto) * parseFloat(me.impuesto_servicio)) / 100
                        pagador.total_consumos = parseFloat(pagador.total_consumos) + parseFloat(me.result);
                        pagador.servicio = me.impuesto_servicio;
                        pagador.total_temp = parseFloat(pagador.total_consumos) + parseFloat(pagador.valor_a_pagar);
                        return pagador;


                    });

                });

                var suma_factura = 0;
                $.each(me.Facturacion, function(key, facturacion) {
                    for (var i = 0; i < facturacion.pagadores.length; i++) {
                        suma_factura += facturacion.pagadores[i].total_temp;
                    }
                    me.resultado_factura = suma_factura.toFixed(2);

                });


            },
            eliminarAbono(id) {
                let me = this;
                me.$swal({
                    title: '¿Está seguro de eliminar este registro?',
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    reverseButtons: true,
                }).then(result => {
                    if (result.value) {
                        let me = this;

                        axios
                            .put('/reservas/eliminar_abono', {
                                id: id,
                            })
                            .then(function(response) {
                                me.$swal('Eliminado', 'El registro se elimino', 'success');
                                me.monto_pagado();
                                me.miarreglo = [];
                                var url_detalles = '/obtener/cuentasDetalles?reserva_id=' + me.id_reserva;
                                axios.get(me.base + url_detalles).then(function(response) {
                                        var respuesta = response.data;
                                        me.miarreglo = respuesta;
                                    })
                                    .catch(function(error) {
                                        var response = error.response.data;
                                        console.log(response.message,
                                            response.exception,
                                            response.file,
                                            response.line);
                                    });


                            })
                            .catch(function(error) {
                                var response = error.response.data;
                                console.log(response.message, response.exception, response.file, response.line);
                            });
                    } else if (

                        result.dismiss === swal.DismissReason.cancel
                    ) {

                    }
                });
            },
            eliminarConsumo(id, reserva_id) {
                let me = this;
                me.$swal({
                    title: '¿Está seguro de eliminar este registro?',
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    reverseButtons: true,
                }).then(result => {
                    if (result.value) {
                        let me = this;

                        axios
                            .put('/reservas/eliminar_consumo', {
                                id: id,
                                reserva_id:reserva_id
                            })
                            .then(function(response) {
                                me.$swal('Eliminado', 'El registro se elimino', 'success');
                                me.monto_pagado();
                                me.miarreglo = [];
                                me.data_reserva.precio_total = response.data.data;
                                var url_detalles = '/obtener/cuentasDetalles?reserva_id=' + me.id_reserva;
                                axios.get(me.base + url_detalles).then(function(response) {
                                        var respuesta = response.data;
                                        me.miarreglo = respuesta;

                                    })
                                    .catch(function(error) {
                                        var response = error.response.data;
                                        console.log(response.message,
                                            response.exception,
                                            response.file,
                                            response.line);
                                    });


                            })
                            .catch(function(error) {
                                var response = error.response.data;
                                console.log(response.message, response.exception, response.file, response.line);
                            });
                    } else if (

                        result.dismiss === swal.DismissReason.cancel
                    ) {

                    }
                });
            },

            quitarServicio() {

                let me = this;
                me.resultado = 0.0;
                me.aplicar2 = true;
                me.quitar2 = false;
                var result = 0;
                $.each(me.Facturacion, function(key, reservacion) {

                    $.each(reservacion.pagadores, function(key, pagador) {


                        pagador.total_consumos = (parseFloat(pagador.total_neto) * parseFloat(me.impuestoProducto) / 100) + parseFloat(pagador.total_neto)
                        pagador.servicio = 0;
                        pagador.total_temp = (parseFloat(pagador.total_neto) * parseFloat(me.impuestoProducto) / 100) + parseFloat(pagador.total_neto)
                        return pagador;

                    });

                });

                var suma_factura = 0;
                $.each(me.Facturacion, function(key, facturacion) {



                    for (var i = 0; i < facturacion.pagadores.length; i++) {
                        suma_factura += facturacion.pagadores[i].total_temp;
                    }
                    me.resultado_factura = suma_factura.toFixed(2);

                });

            },

            generarFactura(resultado_factura) {
                let me = this;
                $.each(me.Facturacion, function(key, facturacion) {
                    $.each(facturacion.pagadores, function(key, pagador) {
                        pagador.total_temp = parseFloat(pagador.total_consumos) + parseFloat(pagador.valor_a_pagar);
                        return pagador;
                    });
                });
                axios.post(me.base + '/registrar/factura_pagos', {
                    'facturacion': me.Facturacion,
                    'resultado_factura': resultado_factura,
                    'reserva': me.clave_reserva

                }).then(function(response) {
                    me.registrarFacturas(response.data.datos);
                    me.$swal('Almacenado', 'Registro Almacenado', 'success');

                }).catch(function(error) {
                    console.log(error);
                });
            },
            seleccionarPuntosVenta() {
                let me = this;
                var url = 'obtener_puntosventa';
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data.datos;
                        me.ArrayPuntosVenta = respuesta;
                        // console.log(me.ArrayPuntosVenta)
                    })
                    .catch(function(error, otracosa) {});
            },

            cambiarPagina(page, buscar, criterio) {
                let me = this;
                //Actualiza la página actual
                me.pagination.current_page = page;
                //Envia la petición para visualizar la data de esa página
                me.obtenerConsumosExtras(page, buscar, criterio, me.identificador_cliente, me.reserva_detalle_r1);
            },

            registrarExtra() {
                let me = this;
                axios.post(me.base + '/registrar/consumo_extra', {
                    'cliente': me.identificador_cliente,
                    'numero': me.tituloNumero,
                    'detalle_reserva': me.reserva_detalle_r1,
                    'fecha': me.tituloFecha,
                    'producto': me.producto,
                    'total_extras': me.total_extras,
                    'impuesto': me.checkedCategories,
                    'venta': me.venta,
                    'categoria': me.categoria,
                    'unidad': me.unidad,
                    'factura': me.factura_idClave,
                    'total_extras_neto': me.total_extras_neto

                }).then(function(response) {
                    me.seleccionarClientes();
                    me.obtenerConsumosExtras(1, me.buscar, me.criterio, me.identificador_cliente, me.reserva_detalle_r1);
                    me.$swal('Almacenado', 'Registro Almacenado', 'success');

                    me.venta = '';
                    me.categoria = '';
                    me.producto = '';
                    me.precio_producto = '';
                    me.unidad = '';
                    me.impuesto = '';
                    me.total_extras = 0.0;
                    me.checkedCategories = [];

                }).catch(function(error) {
                    console.log(error);
                });
            },
            registrarExtra2() {
                let me = this;
                // console.log(me.reserva_id_detalle)
                axios.post(me.base + '/registrar/consumo_extra', {
                    'cliente': me.identificador_cliente,
                    'pagador': me.identificador_pagador,
                    'numero': me.tituloNumero,
                    'detalle_reserva': me.reserva_detalle_r1,
                    'fecha': me.tituloFecha,
                    'producto': me.producto,
                    'total_extras': me.total_extras,
                    'impuesto': me.checkedCategories,
                    'venta': me.venta,
                    'categoria': me.categoria,
                    'unidad': me.unidad,
                    'factura': me.factura_idClave,
                    'total_extras_neto': me.total_extras_neto
                }).then(function(response) {
                    me.seleccionarClientes();
                    // me.obtenerConsumosExtras2(1,me.buscar,me.criterio, me.identificador_pagador, me.reserva_detalle_r1);
                    me.obtenerConsumosExtras2(1, me.buscar, me.criterio, me.identificador_cliente, me.reserva_detalle_r1);
                    me.$swal('Almacenado', 'Registro Almacenado', 'success');

                    me.facturas(me.reserva_id_detalle)
                    me.venta = '';
                    me.categoria = '';
                    me.producto = '';
                    me.precio_producto = '';
                    me.unidad = '';
                    me.impuesto = '';
                    me.total_extras = 0.0;
                    me.checkedCategories = [];

                }).catch(function(error) {
                    console.log(error);
                });
            },
            eliminarConsumoExtra(consumo) {
                let me = this;
                axios
                    .post(me.base + "/consumo_extra/eliminar", {
                        id: consumo.id
                    })
                    .then(function(response) {
                        me.seleccionarClientes();
                        me.obtenerConsumosExtras(1, me.buscar, me.criterio, me.identificador_cliente, me.reserva_detalle_r1);
                        me.$swal('Almacenado', 'Registro Almacenado', 'success');
                        me.facturas(me.reserva_id_detalle)
                        me.tipoCliente = '';
                        me.nombres = '';
                        me.apellidos = '';
                    })
                    .catch(function(error, otracosa) {});
            },
            eliminarConsumoExtra2(consumo) {
                let me = this;
                axios
                    .post(me.base + "/consumo_extra/eliminar", {
                        id: consumo.id
                    })
                    .then(function(response) {
                        me.seleccionarClientes();
                        me.obtenerConsumosExtras2(1, me.buscar, me.criterio, me.identificador_cliente, me.reserva_detalle_r1);
                        me.$swal('Almacenado', 'Registro Almacenado', 'success');
                        me.facturas(me.reserva_id_detalle)
                        me.tipoCliente = '';
                        me.nombres = '';
                        me.apellidos = '';
                    })
                    .catch(function(error, otracosa) {});
            },
            NuevoExtra() {
                let me = this;
                me.reserva_detalle_r1 = '';
                me.tituloFecha = '';
                me.producto = '';
                me.total_extras = 0.0;
                me.impuesto = '';
                me.venta = '';
                me.categoria = '';
                me.total_extras = 0.0;
                me.tipoAccion = 1;
                me.precio_producto = '';
            },
            actualizarExtra(id_consumo_extra) {
                // console.log(id_consumo_extra)
                let me = this;
                axios.post(me.base + '/actualizar/consumo_extra', {
                    'id_consumo_extra': me.id_consumo_extra,
                    'cliente': me.identificador_cliente,
                    'pagador': me.identificador_pagador,
                    'numero': me.tituloNumero,
                    'detalle_reserva': me.reserva_detalle_r1,
                    'fecha': me.tituloFecha,
                    'producto': me.producto,
                    'total_extras': me.total_extras,
                    'total_extras_neto': me.total_extras,
                    'impuesto': me.checkedCategories,
                    'venta': me.venta,
                    'categoria': me.categoria,
                    'unidad': me.unidad,
                }).then(function(response) {
                    me.seleccionarClientes();
                    me.obtenerConsumosExtras(1, me.buscar, me.criterio, me.identificador_cliente, me.reserva_detalle_r1);
                    me.$swal('Almacenado', 'Registro Almacenado', 'success');
                    // me.cerrarModalProductos();
                    me.tipoCliente = '';
                    me.nombres = '';
                    me.apellidos = '';
                    me.venta = '';
                    me.categoria = '';
                    me.producto = '';
                    me.precio_producto = '';
                    me.unidad = '';
                    me.impuesto = '';
                    me.total_extras = 0.0;
                    me.checkedCategories = [];

                }).catch(function(error) {
                    console.log(error);
                });
            },
            actualizarExtra2(id_consumo_extra) {
                // console.log(id_consumo_extra)
                let me = this;
                axios.post(me.base + '/actualizar/consumo_extra', {
                    'id_consumo_extra': me.id_consumo_extra,
                    'cliente': me.identificador_cliente,
                    'pagador': me.identificador_pagador,
                    'numero': me.tituloNumero,
                    'detalle_reserva': me.reserva_detalle_r1,
                    'fecha': me.tituloFecha,
                    'producto': me.producto,
                    'total_extras': me.total_extras,
                    'total_extras_neto': me.total_extras,
                    'impuesto': me.checkedCategories,
                    'venta': me.venta,
                    'categoria': me.categoria,
                    'unidad': me.unidad,
                }).then(function(response) {
                    me.seleccionarClientes();
                    me.obtenerConsumosExtras2(1, me.buscar, me.criterio, me.identificador_cliente, me.reserva_detalle_r1);
                    me.$swal('Almacenado', 'Registro Almacenado', 'success');
                    // me.cerrarModalProductos();
                    me.tipoCliente = '';
                    me.nombres = '';
                    me.apellidos = '';
                    me.venta = '';
                    me.categoria = '';
                    me.producto = '';
                    me.precio_producto = '';
                    me.unidad = '';
                    me.impuesto = '';
                    me.total_extras = 0.0;
                    me.checkedCategories = [];

                }).catch(function(error) {
                    console.log(error);
                });
            },
            cerrarModalCliente() {
                let me = this;
                me.modalCliente = false;
            },
            toggle: function(tipoCliente) {
                let me = this;
                if (tipoCliente == 1) {
                    me.isOpen = !me.isOpen
                    me.isOpen2 = false
                    me.isOpen3 = false
                }
                if (tipoCliente == 2) {
                    me.isOpen = false
                    me.isOpen3 = false
                    me.isOpen2 = !me.isOpen2
                }
                if (tipoCliente == 3) {
                    me.isOpen = false
                    me.isOpen2 = false
                    me.isOpen3 = !me.isOpen3
                }
            },
            NuevoCliente() {
                this.tipoAccionF = 1;
                let me = this;
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
            },
            registrarCliente() {
                let me = this;
                axios.post(me.base + '/cliente/registrar', {
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
                }).then(function(response) {
                    me.seleccionarClientes();
                    me.$swal('Almacenado', 'Registro Almacenado', 'success');
                    me.cerrarModalCliente();
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
                }).catch(function(error) {
                    console.log(error);
                });
            },
            registrarCuenta()
            {
              let me = this;
              axios.post(me.base + "/reservas/crear_cuentas", {
                      cuenta: me.micuenta,
                      reserva_id: me.id_reserva,

                  }).then(function(response) {
                      me.micuenta = '';
                      Vue.swal("Guardado con éxito");
                      me.miscuentas();
                  })
                  .catch(function(error, otracosa) {});

            },
            miscuentas()
            {
              let me = this;
              var url = '/reservas/miscuentas?reserva_id=' + me.id_reserva;
              axios.get(me.base + url).then(function(response) {
                      var respuesta = response.data;
                      me.arrayCuentas = respuesta.datos;
                      console.log(me.arrayCuentas);
                  })
                  .catch(function(error, otracosa) {
                      // var response = error.response.data;
                  });

            },
            selectPais() {
                let me = this;
                var url = '/obtener/selectPais';
                axios.get(me.base + url).then(function(response) {
                        // console.log(response);
                        var respuesta = response.data;
                        me.arrayPais = respuesta.paises;
                    })
                    .catch(function(error) {
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
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data;
                        me.arrayDepartamento = respuesta.departamentos;
                    })
                    .catch(function(error, otracosa) {
                        // var response = error.response.data;
                    });
            },
            selectDepartamentoFactura() {
                let me = this;
                var url = '/obtener/selectDepartamento?pais_id=' + me.pais_factura;
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data;
                        me.arrayDepartamentoFactura = respuesta.departamentos;
                    })
                    .catch(function(error, otracosa) {
                        // var response = error.response.data;
                    });
            },
            selectTipoContacto() {
                let me = this;
                var url = '/obtener/selectTipoContacto';
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data;
                        me.arrayTipoContacto = respuesta.tipo_contactos;
                    })
                    .catch(function(error, otracosa) {
                        // var response = error.response.data;
                    });
            },
            selectTipoDocumento() {
                let me = this;
                var url = '/obtener/selectTipoDocumento';
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data;
                        me.arrayTipoDocumento = respuesta.tipo_documentos;
                    })
                    .catch(function(error, otracosa) {
                        // var response = error.response.data;
                    });
            },
            selectSexo() {
                let me = this;
                var url = '/obtener/selectSexo';
                axios.get(me.base + url).then(function(response) {
                        // console.log(response);
                        var respuesta = response.data;
                        me.arraySexo = respuesta.sexos;
                    })
                    .catch(function(error) {
                        var response = error.response.data;
                        console.log(response.message,
                            response.exception,
                            response.file,
                            response.line);
                    });
            },
            customFormatter(date) {
                return moment(date).format('YYYY-MM-DD');
            },
            addNewApartment: function() {
                this.apartments.push(Vue.util.extend({}, this.apartment))
            },
            removeApartment: function(index) {
                Vue.delete(this.apartments, index);
            },
            selectTipoCliente() {
                let me = this;
                var url = '/cliente/selectTipoCliente';
                axios.get(me.base + url).then(function(response) {
                        // console.log(response);
                        var respuesta = response.data;
                        me.arrayTipoCliente = respuesta.clientes;
                    })
                    .catch(function(error) {
                        var response = error.response.data;
                        console.log(response.message,
                            response.exception,
                            response.file,
                            response.line);
                    });
            },


            iniciar() {
                let me = this;
                me.aplicar = true;
                me.quitar = false;
                this.seleccionarClientes();
                this.seleccionarHabitaciones(me.id_reserva);
                this.facturas(me.id_reserva);

                // this.obtenerDetalleFactura();
                if (me.grupos_select == '') {
                    me.id_habitacion_grupo = me.id_habitacion;
                } else if (me.grupos_select != null) {
                    me.id_habitacion_grupo = me.grupos_select;
                    me.ReservasGrupos = [];
                }
            },
            listarCantidadPrepagos(reserva_id) {
                let me = this;
                var url = '/reservas/obtener_prepagos_notas2?reserva_id=' + reserva_id;
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data;
                        me.PagosPrepagosNotas = respuesta.datos;
                        me.cantidadPrepagos = respuesta.cantidad;

                    })
                    .catch(function(error, otracosa) {

                    });


            },
            cerrarModalCliente() {
                let me = this;
                me.modalCliente = false;
            },
            cerrarModalProductos() {
                let me = this;
                me.modalProductos = false;
            },
            cerrarModalProductos2() {
                let me = this;
                me.modalProductos2 = false;
            },

            eliminarMensaje(mensajes) {

                let me = this;
                axios
                    .post(me.base + "/reservas/eliminar_mensaje", {
                        mensaje_id: mensajes.id
                    })
                    .then(function(response) {
                        me.MensajeRealizado(mensajes.factura_id, mensajes.cliente_id);

                    })
                    .catch(function(error, otracosa) {});

            },
            eliminarPagador(pagadores, index) {
                let me = this;

                $.each(me.ReservasGrupos, function(key, reserva) {

                    me.no_pagadores = reserva.pagadores_agrupados.length;

                });

                axios
                    .post(me.base + "/reservas/eliminar_pagador", {
                        cliente_id: pagadores.cliente_id,
                        grupo_id: pagadores.reserva_grupo_id
                    })
                    .then(function(response) {
                        me.ReservasPagadores.splice(index, 1);
                        $.each(me.ReservasGrupos, function(key, reservas) {
                            reservas.pagadores_agrupados.splice(index, 1)
                        });
                    })
                    .catch(function(error, otracosa) {});
            },
            eliminarPagadorF(pagador, index2) {
                let me = this;
                axios
                    .post(me.base + "/reservas/eliminar_pagador", {
                        cliente_id: pagador.cliente_id,
                        grupo_id: pagador.reserva_grupo_id
                    })
                    .then(function(response) {
                        me.ReservasPagadores.splice(index2, 1);
                        $.each(me.ReservasGrupos, function(key, reservas) {
                            if (reservas.id_reservas_grupo == pagador.reserva_grupo_id) {
                                reservas.pagadores_agrupados.splice(index2, 1)
                            }
                        });
                    })
                    .catch(function(error, otracosa) {});
            },
            nuevoCliente() {
                let me = this;
                me.modalCliente = true;
                me.seleccionarClientes();
                me.tipoCliente = 0;
            },
            listarImpuestos() { //
                let me = this;
                var url = 'reservas/impuestos';
                me.options = [];
                axios.get(me.base + url).then(function(response) {
                        // console.log(response)
                        var respuesta = response.data;
                        me.Impuestos = respuesta.impuestos;
                        me.options.push({
                            libs: me.Impuestos
                        })
                        // console.log(me.options);
                    })
                    .catch(function(error, otracosa) {
                        // var response = error.response.data;
                    });
            },
            seleccionarClientes() {
                let me = this;
                var url = 'obtener/clientes';
                axios.get(me.base + url).then(function(response) {
                        me.arrayClientes = response.data.clientes
                    })
                    .catch(function(error) {
                        var response = error.response.data;
                    });
            },
            seleccionarHabitaciones(id_reservas) {
                let me = this;
                var url = 'reservas/grupo_habitaciones?reserva_id=' + id_reservas;
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data.habitaciones;
                        me.GruposHabitaciones = response.data.habitaciones;
                    })
                    .catch(function(error, otracosa) {});
            },
            groupBy(array, key) {
                const result = {}
                array.forEach(item => {
                    if (!result[item[key]]) {
                        result[item[key]] = []
                    }
                    result[item[key]].push(item)
                })
                return result
            },
            facturas(id_reservas) {
                let me = this;
                var url = 'reservas/reserva_factura?reserva_id=' + id_reservas + '&habitacion_id=' + me.id_habitacion;
                axios.get(me.base + url).then(function(response) {
                        me.clave_reserva = id_reservas;
                        var respuesta = response.data;
                        me.Facturacion = Object.values(respuesta.FacturaDetalle.reduce((prev, next) => Object.assign(prev, {
                            [next.numero]: next
                        }), {}));
                        me.factura_idClave = response.data.detalles.factura_id;
                        me.total_facturas(me.factura_idClave);
                        me.reserva_id_detalle = id_reservas;
                        var sum = 0;
                        var sum2 = 0;
                        var sum3 = 0;
                        $.each(me.Facturacion, function(key, facturacion) {

                            let rep2 = facturacion.pagadores.reduce((acc2, user2) => {
                                sum = (parseFloat(user2.valor_a_pagar)) + user2.total_consumos
                                user2.clave = sum;
                                (acc2[user2.check_in_fecha] = acc2[user2.check_in_fecha])
                                return acc2
                            }, {});
                            me.prueba = rep2;
                        });
                        var suma_factura = 0;
                        $.each(me.Facturacion, function(key, facturacion) {

                            $.each(facturacion.pagadores, function(key, pagador) {

                                pagador.servicio = 0;
                                pagador.total_temp = parseFloat(pagador.total_consumos) + parseFloat(pagador.valor_a_pagar);
                                return pagador;



                            });

                            for (var i = 0; i < facturacion.pagadores.length; i++) {
                                suma_factura += facturacion.pagadores[i].clave;
                            }
                            me.resultado_factura = suma_factura.toFixed(2);
                            // console.log(suma_factura)
                        });
                    })
                    .catch(function(error, otracosa) {
                        // var response = error.response.data;
                    });
            },
            addDistributionGroup(group) {
                let me = this;
                $.each(me.ReservasGrupos, function(key, reserva) {
                    me.no_pagadores = reserva.pagadores_agrupados.length;
                });
                axios
                    .post(me.base + "/reservas/agregar_pagador", {
                        id_cliente: group.selectedObject.id,
                        ReservasGrupos: me.ReservasGrupos
                    })
                    .then(function(response) {
                        // me.iniciar();
                        // console.log(response.nuevo_pagador)
                        if (response.data.nuevo_pagador == true) {
                            Vue.swal("Lo siento!", "Este cliente ya se registro!", "error");
                        } else {
                            $.each(me.ReservasGrupos, function(key, reservas) {
                                // console.log(reservas.pagadores_agrupados.length);
                                $.each(response.data.nuevo_pagador2, function(key2, reservas2) {
                                    // console.log(reservas2)
                                    if (reservas.id_reservas_grupo == reservas2[0].reserva_grupo_id) {
                                        reservas.pagadores_agrupados.push(reservas2[0]);
                                    }
                                });
                            });
                            me.ReservasPagadores.push(response.data.nuevo_pagador);
                            $.each(me.ReservasGrupos, function(key, reserva) {
                                if (reserva.pagadores_agrupados.length > 0) {

                                    reserva.pagadores_agrupados[0].valor_a_pagar = reserva.precio_bruto;
                                }
                            });
                        }
                    })
                    .catch(function(error, otracosa) {});
            },
            onChange(descuento, precio_bruto, precio_total, reserva, index) {
                let me = this;
                me.resultado_impuesto = 0;
                for (var i = 0; i < reserva.length; i++) {
                    me.resultado_impuesto = me.resultado_impuesto + (parseInt(reserva[i].valor))
                }
            },

            CargarConsumos() {
                let me = this;
                axios.post(me.base + "/reservas/cargar_consumos", {
                        reservas: me.ReservasGrupos

                    })
                    .then(function(response) {


                    })
                    .catch(function(error, otracosa) {});
            },
            GuardarFactura() {
                let me = this;
                me.tipo = 0;
                me.tipoCarga = 1;
                me.longitud_reservas = 0;
                $.each(me.ReservasGrupos, function(key, reserva) {
                    me.longitud_reservas += reserva.pagadores_agrupados.length;
                });

                if (me.longitud_reservas == 0) {
                    Vue.swal("Lo siento!", "No puedes generar una factura sin pagadores!", "error");
                } else {
                    axios.post(me.base + "/reservas/guardar_factura", {
                            reservas: me.ReservasGrupos,
                            total: me.resultado,
                            total_neto: me.resultado_neto,
                            reserva_id: me.clave_reserva

                        })
                        .then(function(response) {

                            me.tipoCarga = 0;
                            me.tipoAccionGenerar = 1;
                            me.BotonesPrefacturacion = 1;
                            Vue.swal("Muy bien!", "Realizo su facturación!", "success");
                            me.iniciar();
                            me.resultado = 0;
                            me.resultado_neto = 0;

                        })
                        .catch(function(error, otracosa) {});
                }
            },
            validar_total(ReservasGrupos, pagador, index2, index, valor_a_pagar, precio_bruto) {
                let me = this;

                $.each(ReservasGrupos, function(key, reserva) {

                    if (index2 == key) {

                        me.validacion_precio_bruto = 0;

                        $.each(reserva.pagadores_agrupados, function(key2, pagador) {

                            me.validacion_precio_bruto = me.validacion_precio_bruto + (($.trim(pagador.valor_a_pagar) == '') ? 0 : parseFloat(pagador.valor_a_pagar));

                        });
                        if (me.validacion_precio_bruto > precio_bruto) {
                            pagador.valor_a_pagar = '';
                            Vue.swal('Error', "Excede el precio de pago de este registro");
                        }
                    }
                });
            },
            calcularImpuesto(impuesto, precio_total, index) {
                let me = this;
                var valor_impuesto = impuesto[1] / 100;
                $.each(me.ReservasGrupos, function(key, reserva) {
                    if (index == key) {
                        reserva.precio_bruto = (reserva.precio_total * (1 + valor_impuesto));
                    }
                });
            },

            EliminarServicio(servicio, impuesto1, impuesto2, precio_bruto, precio_total, index) {

                let me = this;
                me.resultado = 0.0;
                me.resultado_neto = 0.0;
                me.resultado_impuesto = 0;

                $.each(me.ReservasGrupos, function(key, reserva) {
                    if (index == key) {
                        reserva.impuestos = (parseFloat(impuesto1) + parseFloat(impuesto2));
                        me.result = (parseFloat(reserva.precio_neto) * parseFloat(reserva.impuestos)) / 100
                        reserva.precio_bruto = (parseFloat(reserva.precio_neto) + parseFloat(me.result)).toFixed(2);
                        reserva.servicio = null;

                        axios.post(me.base + '/quitar_servicio_individual/reserva', {
                            'reserva': reserva.id


                        }).then(function(response) {
                            me.registrarFacturas(response.data.datos);
                            // me.$swal('Almacenado', 'Registro Almacenado', 'success');

                        }).catch(function(error) {
                            console.log(error);
                        });

                        return reserva;
                    }
                });
                for (var i = 0; i < me.ReservasGrupos.length; i++) {
                    me.resultado = me.resultado + (parseFloat(me.ReservasGrupos[i].precio_bruto))
                    me.resultado_neto = me.resultado_neto + (parseFloat(me.ReservasGrupos[i].precio_neto))

                }
            },


            AgregarServicio(servicio, impuesto1, impuesto2, precio_bruto, precio_total, index) {

                let me = this;


                me.resultado = 0.0;
                me.resultado_neto = 0.0;
                me.resultado_impuesto = 0;
                $.each(me.ReservasGrupos, function(key, reserva) {
                    if (index == key) {

                        axios.post(me.base + '/agregar_servicio_individual/reserva', {
                            'reserva': reserva.id,
                            'servicio': me.impuesto_servicio_habitacion


                        }).then(function(response) {
                            me.registrarFacturas(response.data.datos);


                        }).catch(function(error) {
                            console.log(error);
                        });

                        if (reserva.servicio == 0 || reserva.servicio == null) {

                            reserva.impuestos = (parseFloat(reserva.numero_impuesto) + parseFloat(reserva.numero_impuesto2) + parseFloat(me.impuesto_servicio_habitacion)) / 100;
                            me.result = (parseFloat(reserva.impuestos) * parseFloat(reserva.precio_neto)).toFixed(2);
                            reserva.precio_bruto = (parseFloat(reserva.precio_neto) + parseFloat(me.result)).toFixed(2);
                            reserva.servicio = me.impuesto_servicio_habitacion;

                            reserva.pagadores_agrupados.map(function(pagador, key2) {
                                if (pagador.valor_a_pagar > 0) {
                                    pagador.neto = ((parseFloat(pagador.valor_a_pagar)) / ((100 + (parseFloat(reserva.numero_impuesto) + parseFloat(reserva.numero_impuesto2))) / 100)).toFixed(2);
                                    pagador.impuestos = (parseFloat(reserva.numero_impuesto) + parseFloat(reserva.numero_impuesto2)) + parseFloat(me.impuesto_servicio_habitacion);
                                    pagador.valor_a_pagar = parseFloat(reserva.precio_bruto).toFixed(2);
                                }
                            });
                            return reserva;


                        }

                    }
                });
                for (var i = 0; i < me.ReservasGrupos.length; i++) {
                    me.resultado = me.resultado + (parseFloat(me.ReservasGrupos[i].precio_bruto))
                    me.resultado_neto = me.resultado_neto + (parseFloat(me.ReservasGrupos[i].precio_neto))

                }
            },


            calcularDescuentoImpuesto1(impuesto, precio_bruto, index) {
                var total = 0;
                var str1 = '1.';
                var res = str1.concat(impuesto);
                let me = this;
                me.resultado = 0.0;
                me.resultado_neto = 0.0;
                me.resultado_impuesto = 0;

                this.$swal({
                    title: 'Asignar',
                    text: '¿Desea Asignar Impuesto del ' + me.valorImpuesto + '%',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Asignar',
                    cancelButtonText: 'No',
                    showCloseButton: true,
                    showLoaderOnConfirm: true
                }).then((result) => {
                    if (result.value) {

                        $.each(me.ReservasGrupos, function(key, reserva) {
                            if (index == key) {

                                axios.post(me.base + "/reservas/actualizar_numeroimpuesto", {
                                        id: reserva.id,
                                        valorImpuesto: me.valorImpuesto

                                    })
                                    .then(function(response) {
                                        me.$swal('Excelente', 'Asignaste impuesto a esta fecha', 'success');
                                        me.AccionImpuesto = 1;

                                    })
                                    .catch(function(error, otracosa) {});

                                reserva.precio_bruto = (parseFloat(reserva.precio_neto) + ((parseFloat(reserva.precio_neto) * me.valorImpuesto) / 100)).toFixed(2);
                                reserva.numero_impuesto = me.valorImpuesto;

                                ((parseFloat(reserva.precio_neto)) / ((100 + parseFloat(13)) / 100)).toFixed(2);
                                return reserva;
                            }
                        });

                        for (var i = 0; i < me.ReservasGrupos.length; i++) {

                            me.resultado = (parseFloat(me.resultado) + parseFloat(me.ReservasGrupos[i].precio_bruto));
                            me.resultado_neto = (parseFloat(me.resultado_neto) + (parseFloat(me.ReservasGrupos[i].precio_neto))).toFixed(2);
                        }

                        me.ReservasGrupos.map(function(reservacion, key) {

                            if (reservacion.pagadores_agrupados.length > 0) {
                                reservacion.pagadores_agrupados[0].valor_a_pagar = (parseFloat(reservacion.precio_bruto)).toFixed(2);
                            }

                        })

                    } else {
                        this.$swal('Cancelled', 'Canelaste la operacion', 'info')
                    }
                })

            },

            calcularDescuentoImpuesto2(impuesto, precio_bruto, index) {
                var total = 0;
                var str1 = '1.';
                var res = str1.concat(impuesto);
                let me = this;
                me.resultado = 0.0;
                me.resultado_neto = 0.0;
                me.resultado_impuesto = 0;

                this.$swal({
                    title: 'Asignar',
                    text: '¿Desea Quitar Impuesto',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Quitar',
                    cancelButtonText: 'No',
                    showCloseButton: true,
                    showLoaderOnConfirm: true
                }).then((result) => {
                    if (result.value) {

                        $.each(me.ReservasGrupos, function(key, reserva) {
                            if (index == key) {

                                axios.post(me.base + "/reservas/actualizar_numeroimpuesto2", {
                                        id: reserva.id

                                    })
                                    .then(function(response) {

                                        this.$swal('Excelente', 'Asignaste impuesto a esta fecha', 'success')
                                        me.AccionImpuesto = 1;

                                    })
                                    .catch(function(error, otracosa) {});

                                reserva.precio_bruto = ((parseFloat(reserva.precio_bruto)) / ((100 + parseFloat(me.valorImpuesto)) / 100)).toFixed(2);
                                reserva.numero_impuesto = 0;

                                ((parseFloat(reserva.precio_neto)) / ((100 + parseFloat(13)) / 100)).toFixed(2);
                                return reserva;
                            }
                        });
                        for (var i = 0; i < me.ReservasGrupos.length; i++) {

                            me.resultado = (parseFloat(me.resultado) + parseFloat(me.ReservasGrupos[i].precio_bruto));
                            me.resultado_neto = (me.resultado_neto + (parseFloat(me.ReservasGrupos[i].precio_neto))).toFixed(2);
                        }

                        me.ReservasGrupos.map(function(reservacion, key) {
                            reservacion.numero_impuesto = 0;

                            if (reservacion.pagadores_agrupados.length > 0) {
                                reservacion.pagadores_agrupados[0].valor_a_pagar = (parseFloat(reservacion.precio_bruto)).toFixed(2);
                            }

                        })

                    } else {
                        this.$swal('Cancelled', 'Canelaste la operacion', 'info')
                    }
                })

            },

            tipoPagos() {
                let me = this;
                var url = 'reservas/tipo_pagos';
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data;
                        me.TipoPagos = respuesta.tipo_pagos;
                    })
                    .catch(function(error, otracosa) {
                        // var response = error.response.data;
                    });
            },
            pagoRealizado(factura_id, cliente_id) {

                let me = this;
                var url = 'reservas/tipo_pagos_pagadores?factura_id=' + factura_id + '&cliente_id=' + cliente_id + '&reserva_id=' + me.clave_reserva;
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data;
                        me.Pagos = respuesta.tipo_pagos;
                        // console.log(response)
                        // console.log(me.Pagos)
                        me.resultado_pagos = 0;
                        me.diferencia_pagos = 0;
                        for (var i = 0; i < me.Pagos.length; i++) {
                            me.resultado_pagos = (parseFloat(me.resultado_pagos) + (parseFloat(me.Pagos[i].valor_pagado)));
                        }
                        me.diferencia_pagos = (parseFloat(me.pago_a_realizar) - parseFloat(me.resultado_pagos)).toFixed(2);
                    })
                    .catch(function(error, otracosa) {});
            },

            MensajeRealizado(factura_id, cliente_id) {

                let me = this;
                var url = 'reservas/mensajes_realizados?factura_id=' + factura_id + '&cliente_id=' + cliente_id;
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data;

                        me.arrayMensajesFactura = respuesta.data;

                    })
                    .catch(function(error, otracosa) {});
            },

            ModalPagar(modelo, accion, data = []) {
                switch (modelo) {
                    case 'pagar': {
                        switch (accion) {
                            case 'crear': {

                                this.modal = 1;
                                this.tituloModal = 'Realizar Pago';
                                this.tipoAccion = 1;
                                this.pago_a_realizar = parseFloat(data['total_a_pagar']);
                                this.cliente_pago = data['cliente_id'];
                                this.factura_idPago = data['factura_id'];
                                this.idTotalPago = data['id'];
                                this.numeroPago = data['numero'];
                                this.ValorPagado = data['valor_pagado'];
                                this.nombrePagador = data['cliente'];
                                this.tipoPagos();
                                this.pagoRealizado(data['factura_id'], data['cliente_id']);
                                break;
                            }
                            case 'info': {

                                this.modalInfo = 1;
                                this.cliente_pago = data['cliente_id'];
                                this.factura_idPago = data['factura_id'];
                                this.idTotalPago = data['id'];
                                this.numeroPago = data['numero'];
                                this.MensajeRealizado(data['factura_id'], data['cliente_id']);

                                break;
                            }
                        }
                    }
                }
            },
            cerrarModal() {
                this.modalComentario = 0;
                this.modalDetalle = 0;
                this.modalAlojados = 0;
                this.modalnoValido = 0;
                this.modal = 0;
            },
            cerrarModalCambio() {
                this.modalCambio = 0;
                this.crearmiscuentas = 0;
            },
            cerrarModalnfo() {
                this.modalInfo = 0;
                this.comentario = '';
            },

            registrarMensaje(factura_idPago, cliente_pago) {

                let me = this;

                axios.post(me.base + "/reservas/guardar_mensajes", {
                        factura_idPago: factura_idPago,
                        cliente_pago: cliente_pago,
                        comentario: me.comentario,

                    }).then(function(response) {
                        me.MensajeRealizado(factura_idPago, cliente_pago);

                        Vue.swal("Guardado con éxito");
                    })
                    .catch(function(error, otracosa) {});

            },
            convert(str) {
                var date = new Date(str),
                    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
                    day = ("0" + date.getDate()).slice(-2);
                return [date.getFullYear(), mnth, day].join("-");
            },

            registrarPago() {

                let me = this;
                if (me.valor_a_pagar_f > me.saldo_pendiente_cuenta) {
                    Vue.swal('Error', "Excede el monto del pago");
                }

                if (me.valor_a_pagar_f <= me.saldo_pendiente_cuenta) {
                    axios.post(me.base + "/reservas/guardar_tipopago_pagadores", {
                            id_reserva: me.id_reserva,
                            cuenta_abono: me.cuenta_abono,
                            tipo_pago: me.tipo_pago,
                            valor_a_pagar_f: me.valor_a_pagar_f,
                            fecha_abono: moment(me.fecha_abono).format('YYYY-MM-DD hh:mm:ss'),

                        }).then(function(response) {
                            me.cerrarModal();
                            me.monto_pagado();
                            me.miarreglo = [];
                            var url_detalles = '/obtener/cuentasDetalles?reserva_id=' + me.id_reserva;
                            axios.get(me.base + url_detalles).then(function(response) {
                                    var respuesta = response.data;
                                    me.miarreglo = respuesta;
                                })
                                .catch(function(error) {
                                    var response = error.response.data;
                                    console.log(response.message,
                                        response.exception,
                                        response.file,
                                        response.line);
                                });
                            Vue.swal("Guardado con éxito");
                        })
                        .catch(function(error, otracosa) {});

                }


            },
            registrarCambio() {

                let me = this;
                axios.post(me.base + "/reservas/actualizar_cuenta_reserva", {
                        reserva_id: me.reserva_id,
                        cuenta_reserva_id: me.cuenta_reserva_id,
                        cuenta_cambio: me.cuenta_cambio,
                        cuenta_tipo_movimiento: me.cuenta_tipo_movimiento,
                        cuenta_fecha: me.cuenta_fecha,
                        cuenta_habitacion: me.cuenta_habitacion,

                    }).then(function(response) {
                        me.cerrarModalCambio();
                        me.miarreglo = [];
                        me.cuenta_cambio = '';
                        var url_detalles = '/obtener/cuentasDetalles?reserva_id=' + me.id_reserva;
                        axios.get(me.base + url_detalles).then(function(response) {

                                var respuesta = response.data;
                                console.log(me.data_reserva.cuentas);
                                me.miarreglo = respuesta;
                            })
                            .catch(function(error) {
                                var response = error.response.data;
                                console.log(response.message,
                                    response.exception,
                                    response.file,
                                    response.line);
                            });
                        Vue.swal("Guardado con éxito");
                    })
                    .catch(function(error, otracosa) {});

            },
            editarPago(pago) {
                let me = this;
                me.tipoAccion = 2;
                me.tipo_pago = pago.tipo_pago_id;
                me.valor_a_pagar_f = pago.valor_pagado;
                me.pago_id = pago.id;
            },
            editarMensaje(mensaje) {

                let me = this;
                me.tipoAccionMensaje = 2;
                me.comentario = mensaje.mensaje;
                me.mensaje_id = mensaje.id;
            },
            eliminarPago(pago, idTotalPago) {
                let me = this;

                axios.post(me.base + "/reservas/eliminar_tipopago_pagadores", {
                        id: pago.id,
                        cliente_id: pago.cliente_id,
                        factura_id: me.factura_idClave
                        // idTotalPago:idTotalPago,
                        // valor_pagado:pago.valor_pagado
                    })
                    .then(function(response) {
                        me.pagoRealizado(response.data.factura_id, response.data.cliente_id)
                        me.total_facturas(me.factura_idClave);
                    })
                    .catch(function(error, otracosa) {});
            },
            actualizarPago(id, pago_a_realizar, cliente_pago) {
                let me = this;
                axios.post(me.base + "/reservas/actualizar_tipopago_pagadores", {
                        id: id,
                        tipo_pago: me.tipo_pago,
                        valor_a_pagar: me.valor_a_pagar_f,
                        factura_id: me.factura_idClave,
                        cliente_id: cliente_pago
                    })
                    .then(function(response) {
                        me.pagoRealizado(response.data.factura_id, response.data.cliente_id)
                    })
                    .catch(function(error, otracosa) {});
            },

            actualizarMensaje(id, cliente_pago) {
                let me = this;
                axios.post(me.base + "/reservas/actualizar_mensajes", {
                        id: id,
                        cliente_id: cliente_pago,
                        comentario: me.comentario

                    })
                    .then(function(response) {
                        me.MensajeRealizado(response.data.factura_id, response.data.cliente_id)
                    })
                    .catch(function(error, otracosa) {});
            },

            editarConsumoExtra(consumo) {
                let me = this;
                me.tipoAccion = 2;
                me.venta = consumo.punto_de_venta_id;
                me.obtenerCategorias(me.venta);
                me.seleccioneCategorias(me.venta, consumo.categoria_id);
                me.categoria = consumo.categoria_id;
                // me.seleccioneProducto(consumo.producto_id);
                me.producto = consumo.producto_id;
                me.precio_producto = consumo.PUnitario;
                // me.seleccioneImpuesto2(consumo.impuesto_id);
                me.impuesto = consumo.impuesto_id;
                me.total_extras = parseFloat(consumo.total_extras_neto).toFixed(2);
                me.id_consumo_extra = consumo.id;
                me.unidad = consumo.unidad;
            },

            NuevoPago() {
                let me = this;
                me.tipo_pago = '';
                me.valor_a_pagar_f = '';
                me.tipoAccion = 1;
            },

            ImprimirFacturaDetalle(totalfactura) {

                let me = this;
                window.open(me.base + 'reservas/pdf2/' + me.factura_idClave + '/' + me.id_reserva + '/' + totalfactura.cliente_id + '/' + totalfactura.total_a_pagar + '/' + totalfactura.total_pagado + ',' + '_blank');

            },

            ImprimirFactura(factura, pagador) {
                let me = this;
                window.open(me.base + 'reservas/pdf/' + me.factura_idClave + '/' + me.id_reserva + ',' + '_blank');
            },

            ImprimirFacturaCajero() {
                let me = this;
                window.open(me.base + 'reservas/pdfCajero/' + 1 + ',' + '_blank');

            },

            pasar_efectivo(ReservasGrupos, pagador, index2, index, valor_a_pagar) {
                // console.log(ReservasGrupos, pagador, index2, index, valor_a_pagar)
                let me = this;
                $.each(ReservasGrupos, function(key, reserva) {
                    if (index2 == key) {
                        // console.log(reserva)
                        $.each(reserva.pagadores_agrupados, function(key2, pagadores) {
                            var indice = index + 1;
                            if (index == key2) {
                                pagadores.valor_a_pagar = ''
                            }
                            if (indice == key2) {
                                pagadores.valor_a_pagar = valor_a_pagar
                                return pagadores
                            }
                        });
                    }
                });
            },
            abrirModal(modelo, accion, data = []) {

                switch (modelo) {
                    case 'data': {
                        switch (accion) {
                            case 'registrar': {

                                this.modal = 1;
                                this.tituloModal = 'Nuevo Abono';
                                this.profesion = '';
                                this.descripcion = '';
                                this.tipoAccion = 1;
                                this.tipoPagos();
                                break;
                            }
                        }
                    }
                }
            },
            abrirModalConsumos(modelo, accion, data = []) {

                switch (modelo) {
                    case 'data': {
                        switch (accion) {
                            case 'registrar': {

                                this.modalProductos = 1;
                                this.tituloModal = 'Registar Consumos Extras';
                                this.seleccionarPuntosVenta();
                                this.miscuentas();
                                break;
                            }
                        }
                    }
                }
            },
            abrirModalCambio(modelo, accion, data = []) {

                switch (modelo) {
                    case 'data': {
                        switch (accion) {
                            case 'registrar': {
                                console.log(data);
                                this.modalCambio = 1;
                                this.tituloModal = 'Cambiar Cuenta';
                                this.tipoAccion = 1;
                                this.NombreCargo = data['cargo'];
                                this.NombreConcepto = data['concepto'];
                                this.NombreHabitacion = data['habitacion'];
                                this.NombreCuenta = data['cuenta'];
                                this.cuenta_reserva_id = data['id'];
                                this.cuenta_tipo_movimiento = data['tipo_movimiento'];
                                this.cuenta_fecha = data['fecha_hora'];
                                this.cuenta_habitacion = data['habitacion_id'];
                                this.reserva_id = data['reserva_id'];
                                this.miscuentas();
                                console.log(data);
                                break;
                            }
                        }
                    }
                }
            },

            // abrirModal(modelo, accion, data = [], numero, check_in_fecha, reserva_detalle_r, reserva_id) {
            //     this.obtenerImpuestosProductos();
            //     this.seleccionarPuntosVenta();
            //     switch (modelo) {
            //         case 'servicios': {
            //             switch (accion) {
            //                 case 'registrar': {
            //                     this.modalProductos = 1;
            //                     this.tituloModal = 'Registar Consumos Extras';
            //                     this.nombre = '';
            //                     this.descripcion = '';
            //                     this.tipoAccion = 1;
            //                     this.tituloNumero = numero;
            //                     this.tituloPagadorNombre = data.nombre;
            //                     this.tituloPagadorApellido = data.apellido;
            //                     this.identificador_pagador = data.id;
            //                     this.identificador_cliente = data.identificador_cliente;
            //                     this.tituloFecha = check_in_fecha;
            //                     this.reserva_detalle_r1 = reserva_detalle_r;
            //                     this.obtenerConsumosExtras(1, this.buscar, this.criterio, this.identificador_cliente, this.reserva_detalle_r1);
            //                     this.isDisabled1 = true;
            //                     break;
            //                 }
            //                 case 'actualizar': {
            //                     // console.log(data);
            //
            //                     this.modalProductos = 1;
            //                     this.tituloModal = 'Actualizar Rol';
            //                     this.tipoAccion = 2;
            //                     this.rol_id = data['id'];
            //                     this.nombre = data['name'];
            //                     this.descripcion = data['guard_name'];
            //                     break;
            //                 }
            //             }
            //         }
            //     }
            // },




            obtenerCategorias(venta) {
                // console.log(2)
                let me = this;
                var url = '/obtener/categoriasFacturas?venta=' + venta;
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data;
                        me.arrayCategoriasProductos = respuesta.datos;
                        // console.log(me.arrayCategoriasProductos)
                    })
                    .catch(function(error) {
                        var response = error.response.data;
                        console.log(response.message,
                            response.exception,
                            response.file,
                            response.line);
                    });
            },
            obtenerImpuestosProductos() {
                let me = this;
                var url = '/obtener/impuestos_productos';
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data;
                        me.arrayImpuestosProductos = respuesta.datos;
                        // console.log(me.arrayImpuestosProductos)
                    })
                    .catch(function(error) {
                        var response = error.response.data;
                        console.log(response.message,
                            response.exception,
                            response.file,
                            response.line);
                    });
            },
            seleccioneCategorias() {

                let me = this;
                var url = '/obtener/productos?venta=' + me.venta + '&categoria=' + me.categoria.id;
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data;
                        me.arrayProductos = respuesta.datos;
                    })
                    .catch(function(error) {
                        var response = error.response.data;
                        console.log(response.message,
                            response.exception,
                            response.file,
                            response.line);
                    });
            },
            seleccioneProducto() {
                let me = this;
                var url = '/obtener/datos_productos?producto=' + me.producto.id;
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data;
                        me.precio_producto = respuesta.datos['precio'];
                    })
                    .catch(function(error) {
                        var response = error.response.data;
                        console.log(response.message,
                            response.exception,
                            response.file,
                            response.line);
                    });
            },
            obtenerImpuesto(impuesto) {
                let me = this;
                var url = '/obtener/impuesto_seleccionado?impuesto=' + impuesto;
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data;
                        me.impuesto_valor_seleccionado = respuesta.datos['valor'];
                        // console.log(me.impuesto_valor_seleccionado)
                    })
                    .catch(function(error) {
                        var response = error.response.data;
                        console.log(response.message,
                            response.exception,
                            response.file,
                            response.line);
                    });
            },
            calcularTotal() {
                let me = this;
                me.isDisabled1 = false;
                var num = parseFloat(me.precio_producto) * parseFloat(me.unidad);
                me.total_extras = (parseFloat(me.precio_producto) * parseFloat(me.unidad)).toFixed(2);
                me.total_extras_neto = (parseFloat(me.precio_producto) * parseFloat(me.unidad)).toFixed(2);
                me.total_extras_neto = ( me.total_extras_neto === NaN || me.total_extras_neto === 'NaN') ? 0 : me.total_extras_neto;
                console.log(me.total_extras_neto);
            },


            groupBy(array, key) {
                const result = {}
                array.forEach(item => {
                    if (!result[item[key]]) {
                        result[item[key]] = []
                    }
                    result[item[key]].push(item)
                })
                return result
            },

            seleccioneImpuesto(impuesto) {
                let me = this;
                var url = '/obtener/impuesto_seleccionado?impuesto=' + impuesto;
                axios.get(me.base + url).then(function(response) {
                        var respuesta = response.data;
                        var total_parcial = parseFloat(me.precio_producto) * parseFloat(me.unidad);
                        me.impuesto_valor_seleccionado = respuesta.datos['valor'];
                        me.total_consumo1 = (parseFloat(total_parcial) * parseFloat(me.impuesto_valor_seleccionado)) / 100;
                        me.total_extras = (parseFloat(total_parcial) + parseFloat(me.total_consumo1)).toFixed(2);
                        // console.log(me.total_extras)
                    })
                    .catch(function(error) {
                        var response = error.response.data;
                        console.log(response.message,
                            response.exception,
                            response.file,
                            response.line);
                    });
            },
            recargar() {
                let me = this;
                me.$router.go(me.$router.currentRoute)
            },
            pasar_efectivo_up(ReservasGrupos, pagador, index2, index, valor_a_pagar) {
                let me = this;
                $.each(ReservasGrupos, function(key, reserva) {
                    if (index2 == key) {
                        $.each(reserva.pagadores_agrupados, function(key2, pagadores) {
                            var indice = index - 1;
                            if (index == key2) {
                                pagadores.valor_a_pagar = ''
                            }
                            if (indice == key2) {
                                pagadores.valor_a_pagar = valor_a_pagar
                                return pagadores
                            }
                        });
                    }
                });
            },
        },
        mounted() {
            this.base = base;
            this.iniciar();
            this.selectTipoCliente();
            this.listarCuentas();
            this.monto_pagado();
            this.miscuentas();
            this.miarreglo = this.data_reserva.cuentas;
     alert(2);
            $.each(this.data_reserva.grupos, function(key, reservacion) {
                // console.log(reservacion);
                reservacion
            });
            console.log(this.data_reserva, 'este');



        }
    }
</script>
<style>
    .input-append .btn {
        float: none !important;
        margin-left: 0 !important;
    }

    input.datepicker {
        border: 1px solid #ff0000;
    }

    .modal-content {
        width: 100% !important;
        position: absolute !important;
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

    /* The container */
    .container-checkbox {
        display: inline;
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

    .vdatetime-popup {
        box-sizing: border-box;
        z-index: 2000;
        position: inherit !important;

    }

    .mostrar {
        padding-top: 50px;
        display: block !important;
        opacity: 1 !important;
        background-color: #3c29297a !important;
    }

    .modal-lg {
        max-width: 800px !important;
        width: 100% !important;
        margin: 0 auto 0 !important;
    }

    .modal-content {
        width: 100% !important;
        max-width: 800px;
        margin: 0 auto 0 !important;
        position: absolute !important;
        top: 0;
        min-height: 422px;
    }

    .vdatetime-popup {
        box-sizing: border-box;
        z-index: 1000;
        position: fixed !important;
        top: 60px;
        left: auto;
        -webkit-transform: none;
        transform: none;

    }
    .linea
{
    display: inline-block;
}
</style>
