<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Factura</title>
    <link rel="stylesheet" href="style.css" media="all" />
    <style>
        #items {
            clear: both;
            width: 100%;
            margin: 30px 0 0 0;
            border: 1px solid black;
        }

        #items th {
            background: #eee;
            color: black;
        }

        #items tr.item-row td {
            border: 1;
            vertical-align: top;
        }
    </style>
</head>

<body>
    <header class="clearfix">
        <div id="logo">
            <img src="logo.png">
        </div>
        <div id="company">
            <h1 class="name" style="

        ">{{$hotel_nombre}}</h1>
            <div style="
          position: relative;
          left: 55px;
        ">{{$ruc_hotel}}</div>
            <div class="padre">Dirección: {{$hotel_direccion}}</div>
            <div style="
          position: relative;
          left: 20px;
          ">Telf: {{$hotel_telefono}} {{$hotel_correo}}</div>
        </div>
        </div>
    </header>
    <main>
        <div id="details" class="clearfix">
            <div id="client">
                <div class="to">FACTURA PARA:</div>
                <div class="address" style="color: #060000;">{{$reservante}}</div>
                <div class="address" style="color: #060000;">SHOO152807S29</div>
                <div class="address" style="color: #060000;">30086144890</div>
                <div class="address" style="color: blue;">{{$email}}</div>
                <div class="address" style="color: red;">Pagadores:</div>
                @foreach ($info_pagadores as $info)
                <div class="address" style="color: #060000;">{{$info->cliente}}</div>
                @endforeach

            </div>
            <div id="invoice" style="background-color: antiquewhite;width: 414px;" class="to">
                <p style="color: red;">No Factura: {{str_pad($no_factura,8,"0",STR_PAD_LEFT)}} </p>
                <div style="position: relative; top: -20px;" class="date">Fecha:
                    <?=date('Y-m-d');?>
                </div>
            </div>

            <table border="0" cellspacing="0" cellpadding="0" id="items">
                <thead>
                    <tr>
                        <th class="">No HAB/FECHA</th>
                        <th class="">VALOR HAB</th>
                        <th class="">CONSUMO</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($factura as $det)
                    <tr>
                        <td>{{$det->numero}} / {{$det->check_in_fecha}}</td>
                        <td style="text-align: center;">{{$hotel_moneda}}{{$det->precio_neto_2}}</td>
                        <td>
                            <table border="0" style="border: 0;" cellspacing="0" cellpadding="0" id="items">
                                <thead>
                                    <tr>
                                        <th class="">Punto de Venta</th>
                                        <th class="">Valor</th>
                                        <th class="" style="font-size: 12px;">Fecha Pago</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($det->consumos_extras as $consumos)
                                    <tr>
                                        <td>{{$consumos->nombre}}</td>
                                        <td>{{$consumos->total_consumo_neto}} </td>
                                        <td style="text-align: center; font-size: 12px;">{{$tipo->fecha_pago}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="1"></td>
                        <td colspan="1" style="text-align: center; font-size: 14px;">{{$hotel_moneda}}{{$total_neto_hab}}</td>
                        <td style="font-size: 14px;">{{$hotel_moneda}}{{$total_neto_consumos}}</td>
                    </tr>
                    <tr>
                        <td colspan="1"></td>
                        @if($servicio != 0)
                        <td colspan="1" style="text-align: center; font-size: 14px;">{{$servicioImpuesto}} {{$servicio}}% {{$hotel_moneda}}{{$valor_servicio}}</td>
                        @endif
                        @if($servicio_consumo != 0)
                        <td style="font-size: 14px;">{{$factura_servicio}} {{$servicio_consumo}}% {{$hotel_moneda}}{{$valor_servicio_producto}}</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="1"></td>
                        <td colspan="1" style="text-align: center; font-size: 14px;">IVA {{$impuesto_hab}}% {{$hotel_moneda}}{{$valor_impuesto}}</td>
                        <td style="font-size: 14px;">IVA {{$impuesto_producto}}% {{$hotel_moneda}}{{$valor_impuesto_producto}}</td>
                    </tr>

                    <tr>
                        <td colspan="1"></td>
                        <td colspan="1" style="text-align: center; font-size: 14px;">SUBTOTAL. {{$hotel_moneda}}{{$total_factuta}}</td>
                        <td style="font-size: 14px;">SUBTOTAL {{$hotel_moneda}}{{$subtotal_consumos}}</td>
                    </tr>

                    <tr>
                        <td colspan="1"></td>
                        <td colspan="1">
                            <table>
                                <thead>
                                    <tr>
                                        <th class="" style="font-size: 12px;">Tipo Pago</th>
                                        <th class="" style="font-size: 12px;">Valor</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($det->tipo_pagos['pagos'] as $tipo)
                                    <tr>
                                        <td style="font-size: 12px;">{{$tipo->nombre}}</td>
                                        <td style="text-align: center; font-size: 12px;">{{$hotel_moneda}}{{$tipo->valor_pagado}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="1"></td>

                                        <td style="font-size: 12px;">TOTAL: {{$hotel_moneda}}{{$det->tipo_pagos['suma_tipo']}}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </td>
                        <td style="font-size: 14px;">TOTAL {{$hotel_moneda}}{{$gran_total}}</td>
                    </tr>
                </tfoot>
            </table>
            <br>
            <div id="notices">

            </div>
    </main>
</body>
</html>
