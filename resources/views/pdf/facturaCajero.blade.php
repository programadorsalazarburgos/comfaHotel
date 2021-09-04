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
        <div style="
          position: relative;
          left: 20px;
          ">Cajero: {{$nombre_cajero}}</div>
        </div>
      </div>
    </header>
    <main>
      <div id="details" class="clearfix">

        <div id="invoice" class="to">

          <div style="position: relative; top: 20px; left:170px;" class="date">Fecha Reporte: <?=date('Y-m-d');?></div>
        </div>

      <table border="0" cellspacing="0" cellpadding="0" id="items">
        <thead>
          <tr>
            <th class="">FECHA</th>
            <th class="">NO FACTURA</th>
            <th class="">PAGADOR</th>
            <th class="">MONTO</th>
            <th class="">TIPO PAGO</th>
         </tr>
        </thead>
        <tbody>
         @foreach ($pagos as $det)
                <tr>
                    <td>{{$det->created_at}}</td>
                    <td>{{$det->factura_id}}</td>
                    <td>{{$det->cliente}}</td>
                    <td>{{$det->valor_pagado}}</td>
                    <td>{{$det->nombre}}</td>


        @endforeach
        </tbody>


       </tfoot>
      </table>
    <br>

    <h4>TOTAL POR TIPO DE PAGOS:</h4>
    <table border="0" cellspacing="0" cellpadding="0" id="items">
      <thead>
        <tr>
          <th class="">TIPO DE PAGO</th>
          <th class="">TOTAL</th>

       </tr>
      </thead>
      <tbody>
       @foreach ($tipo_pagos as $det)
              <tr>
                  <td>{{$det->nombre}}</td>
                  <td>{{$det->total_tipo_pago['suma_tipo']}}</td>



      @endforeach
      </tbody>


     </tfoot>
    </table>
      <div id="notices">

      </div>
    </main>

  </body>
</html>
