<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use App\ConfigVentaAyer;
use App\FuenteReserva;
use App\PagadorFactura;
use App\CategoriaProducto;
use App\Producto;
use App\PagoTotal;
use App\ConsumoExtra;
use App\Desayuno;
use App\TipoPago;
use App\TipoPagoTotalDetalle;
use App\Models\TblReservasDetalle;
use App\Models\TblImpuesto;
use DB;
use PGSchema;
use Auth;


class ReporteController extends Controller
{

  /**
      * All of the current user's projects.
      */
     protected $projects;
     /**
      * Create a new controller instance.
      *
      * @return void
      */
    function __construct()
    {
      $this->middleware(function ($request, $next) {
           $this->projects = Auth::user()->schema;
           PGSchema::switchTo($this->projects);
           return $next($request);
       });
    }


function toFixed($number, $decimals) {
  return number_format($number, $decimals, '.', "");
}

  function iva($iva, $sin_iva) {
  $con_iva = $sin_iva + ($iva*($sin_iva/100));
  $con_iva = round($con_iva, 2);
  return $con_iva;
  }

 public function reservas(Request $request)
 {

    $data = TblReservasDetalle::select(
        'tbl_reservas_detalle.id',
        'tbl_reservas_detalle.id_habitacion_tipo',
        'tbl_reservas_detalle.id_habitacion',
        'tbl_reservas_detalle.id_reservas_grupo',
        'tbl_reservas_detalle.huespedes_cantidad',
        'tbl_reservas_detalle.habitaciones_cantidad',
        'tbl_reservas_detalle.adultos_cantidad',
        'tbl_reservas_detalle.ninos_cantidad',
        'tbl_reservas_detalle.infantes_cantidad',
        'tbl_reservas_detalle.habitacion_precio_total',
        'tbl_reservas_detalle.precio_total',
        'tbl_reservas_detalle.precio_total',
        'tbl_reservas_detalle.requisitos',
        'tbl_reservas_detalle.comentarios',
        'tbl_reservas_detalle.value',
        'tbl_reservas_detalle.check_in_fecha',
        'tbl_reservas_detalle.check_out_fecha',
        'tbl_reservas_detalle.check_out_fecha',
        'tbl_reservas_detalle.id_reserva_detalle_estado_habitacion',
        'tbl_reservas_detalle.precio_neto',
        'tbl_reservas_detalle.salida',
        'tbl_reservas_detalle.total_diario',
        'tbl_habitaciones_tipo.nombre as tipo_habitacion',
        'tbl_habitaciones.numero'
        )
        ->join('tbl_habitaciones_tipo', 'tbl_reservas_detalle.id_habitacion_tipo', '=', 'tbl_habitaciones_tipo.id')
        ->leftjoin('tbl_habitaciones', 'tbl_reservas_detalle.id_habitacion', '=', 'tbl_habitaciones.id')
        ->whereDate('check_in_fecha', $request->fecha_filtro)
        ->get();

        $data_suma_habitaciones = $data->sum('total_diario');
  return [
      'datos' => $data,
      'data_suma_habitaciones' => $data_suma_habitaciones
      ];

 }
 public function reservas_mes(Request $request)
 {

    $data = TblReservasDetalle::select(
        'tbl_reservas_detalle.id',
        'tbl_reservas_detalle.id_habitacion_tipo',
        'tbl_reservas_detalle.id_habitacion',
        'tbl_reservas_detalle.id_reservas_grupo',
        'tbl_reservas_detalle.huespedes_cantidad',
        'tbl_reservas_detalle.habitaciones_cantidad',
        'tbl_reservas_detalle.adultos_cantidad',
        'tbl_reservas_detalle.ninos_cantidad',
        'tbl_reservas_detalle.infantes_cantidad',
        'tbl_reservas_detalle.habitacion_precio_total',
        'tbl_reservas_detalle.precio_total',
        'tbl_reservas_detalle.precio_total',
        'tbl_reservas_detalle.requisitos',
        'tbl_reservas_detalle.comentarios',
        'tbl_reservas_detalle.value',
        'tbl_reservas_detalle.check_in_fecha',
        'tbl_reservas_detalle.check_out_fecha',
        'tbl_reservas_detalle.check_out_fecha',
        'tbl_reservas_detalle.id_reserva_detalle_estado_habitacion',
        'tbl_reservas_detalle.precio_neto',
        'tbl_reservas_detalle.salida',
        'tbl_reservas_detalle.total_diario',
        'tbl_habitaciones_tipo.nombre as tipo_habitacion',
        'tbl_habitaciones.numero'
        )
        ->join('tbl_habitaciones_tipo', 'tbl_reservas_detalle.id_habitacion_tipo', '=', 'tbl_habitaciones_tipo.id')
        ->leftjoin('tbl_habitaciones', 'tbl_reservas_detalle.id_habitacion', '=', 'tbl_habitaciones.id')
        ->whereMonth('check_in_fecha', $request->mes)
        ->get();

        $data_suma_habitaciones = $data->sum('total_diario');
  return [
      'datos' => $data,
      'data_suma_habitaciones' => $data_suma_habitaciones
      ];

 }


}
