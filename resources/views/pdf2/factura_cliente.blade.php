<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Example 2</title>
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
        position: relative;
        left: -81px;
        ">HOTEL CASA SANTAMARIA</h1>
        <div style="
          position: relative;
          left: 55px;
        ">JATT850412RN0</div>
        <div class="padre">Dirección: Oriente No 5 Colonua Allende San Miguel de Guanuajuato Mexico</div>
        <div style="
          position: relative;
          left: 20px;
          ">Telf: 56246535353 hotelsantamaria@gmail.com</div>
        </div>
      </div>
    </header>
    <main>
      <div id="details" class="clearfix">
        <div id="client">
          <div class="to">FACTURA PARA:</div>
          <div class="address"  style="color: #060000;">{{$reservante}}</div>
          <div class="address"  style="color: #060000;">SHOO152807S29</div>
          <div class="address"  style="color: #060000;">30086144890</div>
          <div class="address"  style="color: blue;">{{$email}}</div>
          <div class="address" style="color: red;">Pagadores:</div>
          @foreach ($info_pagadores as $info)
          <div class="address" style="color: #060000;">{{$info->cliente}}</div>
          @endforeach

        </div>
        <div id="invoice" style="background-color: antiquewhite;width: 414px;" class="to">
        <p style="color: red;">No Factura: {{str_pad($no_factura,8,"0",STR_PAD_LEFT)}} </p>
          <div style="position: relative; top: -20px;" class="date">Fecha: <?=date('Y-m-d');?></div>
          <div style="position: relative; top: -20px; left: 0px;" class="date">Saldo Pendiente: 600</div>
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
                    <td style="text-align: center;">${{$det->precio_neto}}</td>

                    <td>
                       <table border="0" style="border: 0;" cellspacing="0" cellpadding="0" id="items">
        <thead>
          <tr>
            <th class="">Punto de Venta</th>
            <th class="">Valor</th>



   </tr>
        </thead>
        <tbody>
           @foreach ($det->consumos_extras as $consumos)
          <tr>
            <td>{{$consumos->nombre}}</td>
            <td>{{$consumos->total_consumo_neto}} </td>

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
          <td colspan="1" style="text-align: center; font-size: 14px;">${{$total_neto_hab}}</td>
          <td style="font-size: 14px;">${{$total_neto_consumos}}</td>
        </tr>
        <tr>
          <td colspan="1"></td>
          <td colspan="1" style="text-align: center; font-size: 14px;">ISH {{$ish}}% ${{$valor_ish}}</td>
          <td style="font-size: 14px;">SERV {{$factura_servicio}}% ${{$valor_servicio_producto}}</td>
        </tr>

        <tr>
          <td colspan="1"></td>
          <td colspan="1" style="text-align: center; font-size: 14px;">IVA {{$impuesto_hab}}% ${{$valor_impuesto}}</td>
          <td style="font-size: 14px;">IVA {{$impuesto_producto}}% ${{$valor_impuesto_producto}}</td>
        </tr>

         <tr>
          <td colspan="1"></td>
          <td colspan="1" style="text-align: center; font-size: 14px;">SUBTOTAL. ${{$total_factuta}}</td>
          <td style="font-size: 14px;">SUBTOTAL ${{$subtotal_consumos}}</td>
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
    <tr>
      <td style="font-size: 12px;">Visa</td>
      <td style="text-align: center; font-size: 12px;">200</td>
    </tr>
  </tbody>
  <tfoot>

  <tr>
    <td colspan="1"></td>

    <td style="font-size: 12px;">TOTAL: 200</td>
  </tr>
  </tfoot>
</table>


          </td>
          <td style="font-size: 14px;">TOTAL ${{$gran_total}}</td>
        </tr>
       </tfoot>
      </table>
    <br>
      <div id="notices">

      </div>
    </main>

  </body>
</html>
