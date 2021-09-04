<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PrepagoNota;
use App\Models\TblReservasDetalle;
use App\Models\TblHotel;
use PGSchema;
use Auth;

class PrepagoController extends Controller
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
      $datosHotel = TblHotel::find(1);
      date_default_timezone_set($datosHotel->zona_horaria);
      $fecha_actual=date("Y-m-d");
      $this->middleware(function ($request, $next) {
           $this->projects = Auth::user()->schema;
           PGSchema::switchTo($this->projects);
           return $next($request);
       });
    }


    public function index()
    {

       $datosHotel = TblHotel::find(1);
       date_default_timezone_set($datosHotel->zona_horaria);
       $fecha_actual=date("Y-m-d");
       $datos = PrepagoNota::
                select(
                  'tbl_prepagos_notas.id',
                  'tbl_prepagos_notas.valor_pagado',
                  'tbl_prepagos_notas.reserva_id',
                  'tbl_tipo_pagos.nombre',
                  'tbl_prepagos_notas.created_at'
                  )
                ->join('tbl_tipo_pagos', 'tbl_prepagos_notas.tipo_pago_id', '=', 'tbl_tipo_pagos.id')
                ->orderBy('tbl_prepagos_notas.id', 'desc')
                ->orderBy('tbl_prepagos_notas.id', 'desc')->paginate(100);

        foreach ($datos as $key => $temp) {

            $temp->habitaciones = $this->habitaciones($temp->reserva_id);
            $datos[$key] = $temp;
        }


        return [
        'pagination' => [
            'total'        => $datos->total(),
            'current_page' => $datos->currentPage(),
            'per_page'     => $datos->perPage(),
            'last_page'    => $datos->lastPage(),
            'from'         => $datos->firstItem(),
            'to'           => $datos->lastItem(),
        ],
         'datos' => $datos
];

        return PrepagoNota::select('id','tipo_pago_id','valor_pagado', 'reserva_id', 'cliente_id')->get();
    }

    public function listar(Request $request)
    {

       $datos = PrepagoNota::
                select(
                  'tbl_prepagos_notas.id',
                  'tbl_prepagos_notas.valor_pagado',
                  'tbl_prepagos_notas.reserva_id',
                  'tbl_tipo_pagos.nombre',
                  'tbl_prepagos_notas.created_at'
                  )
                ->join('tbl_tipo_pagos', 'tbl_prepagos_notas.tipo_pago_id', '=', 'tbl_tipo_pagos.id')
                ->orderBy('tbl_prepagos_notas.id', 'desc')
                ->whereDate('tbl_prepagos_notas.created_at', $request->fecha)
                ->paginate(100);

        foreach ($datos as $key => $temp) {

            $temp->habitaciones = $this->habitaciones($temp->reserva_id);
            $datos[$key] = $temp;
        }


        return [
        'pagination' => [
            'total'        => $datos->total(),
            'current_page' => $datos->currentPage(),
            'per_page'     => $datos->perPage(),
            'last_page'    => $datos->lastPage(),
            'from'         => $datos->firstItem(),
            'to'           => $datos->lastItem(),
        ],
         'datos' => $datos
];

        return PrepagoNota::select('id','tipo_pago_id','valor_pagado', 'reserva_id', 'cliente_id')->get();
    }

    public function habitaciones($reserva_id)
    {
      $datos = TblReservasDetalle::select('tbl_reservas.id', 'tbl_reservas_detalle.id_habitacion', 'tbl_habitaciones.numero')
      ->join('tbl_reservas_grupo', 'tbl_reservas_detalle.id_reservas_grupo', '=', 'tbl_reservas_grupo.id')
      ->join('tbl_reservas', 'tbl_reservas_grupo.id_reservas', '=', 'tbl_reservas.id')
      ->join('tbl_habitaciones', 'tbl_reservas_detalle.id_habitacion', '=', 'tbl_habitaciones.id')
      ->where('tbl_reservas_grupo.id_reservas', $reserva_id)
      ->groupBy(
          'tbl_reservas.id',
          'tbl_reservas_detalle.id_habitacion',
          'tbl_habitaciones.numero'
      )
      ->get();
      $data_agrupados = array();
      foreach ($datos as $key => $temp) {

          $data_agrupados[] = $temp->numero;
      }

      $cadena_habitaciones = implode("-", $data_agrupados);
      return $cadena_habitaciones;
    }
}
