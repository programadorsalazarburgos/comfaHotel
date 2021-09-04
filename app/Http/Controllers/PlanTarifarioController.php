<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\EsquemaController;
use App\User;
use Illuminate\Support\Facades\DB;
use PGSchema;
use Auth;
use DateTime;
use Validator;
use App\Models\TblHotel;
use App\PlanTarifario;
use App\PlanTarifarioHabitacion;
use App\TarifaFecha;
//Empezar
class PlanTarifarioController extends Controller
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
      // $datosHotel = TblHotel::find(1);
      // date_default_timezone_set($datosHotel->zona_horaria);
      // $this->middleware(function ($request, $next) {
      //      $this->projects = Auth::user()->schema;
      //      PGSchema::switchTo($this->projects);
      //      return $next($request);
      //  });
    }
    public function nuevo(Request $request)
    {
      if (!$request->ajax()) return redirect('/');

      try {
        DB::beginTransaction();
        $data = new PlanTarifario();
        $data->nombre = $request->nombre;
        $data->descripcion = $request->descripcion;
        $data->codigo_reservas = $request->codigo_reservas;
        $data->codigo_pms = $request->codigo_pms;
        $data->ocupacion_tarifa = $request->ocupacion_tarifa;
        $data->menores_incluidos = $request->menores_incluidos;
        $data->save();

        foreach ($request->value as $valor) {
          $data_habitacion = new PlanTarifarioHabitacion();
          $data_habitacion->plan_tarifario_id = (int)$data->id;
          $data_habitacion->tipo_habitacion_id = (int)$valor['code'];
          $data_habitacion->save();
        }
        DB::commit();

      } catch(\Illuminate\Database\QueryException $ex){
        return false;
      }
    }
    public function editar(Request $request)
    {
      if (!$request->ajax()) return redirect('/');

      try {
        DB::beginTransaction();
        $data = PlanTarifario::find((int)$request->id);
        $data->nombre = $request->nombre;
        $data->descripcion = $request->descripcion;
        $data->codigo_reservas = $request->codigo_reservas;
        $data->codigo_pms = $request->codigo_pms;
        $data->ocupacion_tarifa = $request->ocupacion_tarifa;
        $data->menores_incluidos = $request->menores_incluidos;
        $data->save();
        PlanTarifarioHabitacion::where('plan_tarifario_id', (int)$request->id)->delete();
        foreach ($request->value as $valor) {
          $data_habitacion = new PlanTarifarioHabitacion();
          $data_habitacion->plan_tarifario_id = (int)$data->id;
          $data_habitacion->tipo_habitacion_id = (int)$valor['code'];
          $data_habitacion->save();
        }
        DB::commit();

      } catch(\Illuminate\Database\QueryException $ex){
        return false;
      }
    }

    public function ver_planes_tarifarios(Request $request)
    {


      if (!$request->ajax()) return redirect('/');

              $buscar = $request->buscar;
              $criterio = $request->criterio;

              if ($buscar==''){
                  $items = PlanTarifario::select(
                    'tbl_planes_tarifarios.id',
                    'tbl_planes_tarifarios.nombre',
                    'tbl_planes_tarifarios.descripcion',
                     'tbl_planes_tarifarios.codigo_reservas',
                     'tbl_planes_tarifarios.codigo_pms',
                      'tbl_planes_tarifarios.estado',
                      'tbl_planes_tarifarios.ocupacion_tarifa',
                      'tbl_planes_tarifarios.menores_incluidos'

                      )
                      ->orderBy('tbl_planes_tarifarios.nombre', 'desc')
                      ->paginate(50);

                      foreach ($items as $key => $temp)
                      {
                          $temp->tipo_habitaciones = $this->tipoHabitacionesPlanes($temp->id);
                      }
              }
              else
              {

                  $items = PlanTarifario::select(
                    'tbl_planes_tarifarios.id',
                    'tbl_planes_tarifarios.nombre',
                    'tbl_planes_tarifarios.descripcion',
                     'tbl_planes_tarifarios.codigo_reservas',
                     'tbl_planes_tarifarios.codigo_pms',
                      'tbl_planes_tarifarios.estado',
                      'tbl_planes_tarifarios.ocupacion_tarifa',
                      'tbl_planes_tarifarios.menores_incluidos'


                      )
                      ->where('tbl_planes_tarifarios.'.$criterio, 'ILIKE', '%'. $buscar . '%')
                      ->orderBy('tbl_planes_tarifarios.nombre', 'desc')->paginate(50);
                      foreach ($items as $key => $temp)
                      {
                          $temp->tipo_habitaciones = $this->tipoHabitacionesPlanes($temp->id);
                      }
              }


              return [
                  'pagination' => [
                      'total'        => $items->total(),
                      'current_page' => $items->currentPage(),
                      'per_page'     => $items->perPage(),
                      'last_page'    => $items->lastPage(),
                      'from'         => $items->firstItem(),
                      'to'           => $items->lastItem(),
                  ],
                  'items' => $items
              ];
    }

    public function tipoHabitacionesPlanes($plan_tarifario_id)
    {
      $data = PlanTarifarioHabitacion::select('tbl_habitaciones_tipo.id as code', 'tbl_habitaciones_tipo.nombre as name')->join('tbl_habitaciones_tipo', 'tbl_planes_tarifarios_habitaciones.tipo_habitacion_id', '=', 'tbl_habitaciones_tipo.id')
      ->where('tbl_planes_tarifarios_habitaciones.plan_tarifario_id', $plan_tarifario_id)
      ->get();
      return $data;
    }
    public function actualizar_estado(Request $request)
    {
      $data = PlanTarifario::find((int)$request->data['id']);
      $data->estado = $request->data['estado'];
      $data->save();
    }
    public function listar()
    {
      $datos = PlanTarifario::all();
      return ["datos" =>$datos];
    }
    public function obtener_tipo_habitaciones(Request $request)
    {
      $datos = PlanTarifarioHabitacion::select('tbl_habitaciones_tipo.id as code', 'tbl_habitaciones_tipo.nombre as name')->join('tbl_habitaciones_tipo', 'tbl_planes_tarifarios_habitaciones.tipo_habitacion_id', '=', 'tbl_habitaciones_tipo.id')
      ->where('tbl_planes_tarifarios_habitaciones.plan_tarifario_id', (int)$request->plan_id)
      ->get();
      return ["datos" =>$datos];
    }
    public function ver_agenda(Request $request)
    {
      $datos = TarifaFecha::selectRaw(
        "date_part('year', fecha) as year,
        date_part('month', fecha) as month,
        date_part('day', fecha) as day,
         tarifa_x_habitacion as memo,
         persona_extra as memo2,
         ninos as memo3")
      ->whereYear('fecha', $request->anio)
      ->whereMonth('fecha', $request->mes)
      ->where('plan_tarifario_id', (int)$request->plan)
      ->where('tipo_habitacion_id', (int)$request->tipo)
      ->get();
      return ["datos" =>$datos];
    }
    public function crear_tarifa_fecha(Request $request)
    {
      for($i=$request->fecha_desde;$i<=$request->fecha_hasta;$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
      $data = TarifaFecha::firstOrNew([
          'fecha' => $i,
          'plan_tarifario_id' => (int)$request->plan_tarifa,
          'tipo_habitacion_id' => (int)$request->tipo_tarifa
        ]);
      $data->plan_tarifario_id = (int)$request->plan_tarifa;
      $data->tipo_habitacion_id = (int)$request->tipo_tarifa;
      $data->fecha = $i;
      $data->tarifa_x_habitacion = (float)$request->tarifa_x_habitacion;
      $data->persona_extra = (float)$request->tarifa_x_persona;
      $data->ninos = (float)$request->tarifa_x_nino;
      $data->save();
      }
    }
    private function formatDate($date)
    {
        $date = (string)$date;
        $date = \date('Y-m-d', strtotime($date));
        return ($date);
    }

    public function obtener_planes(Request $request)
    {
      $datos = PlanTarifarioHabitacion::select(
        'tbl_planes_tarifarios.id',
        'tbl_planes_tarifarios.nombre',
        'tbl_planes_tarifarios.ocupacion_tarifa' ,
        'tbl_planes_tarifarios.menores_incluidos'
        )
        ->join('tbl_planes_tarifarios', 'tbl_planes_tarifarios_habitaciones.plan_tarifario_id', '=', 'tbl_planes_tarifarios.id')
        ->where('tbl_planes_tarifarios_habitaciones.tipo_habitacion_id', (int)$request->tipo_habitacion_id)
        ->where('tbl_planes_tarifarios.estado', true) 
        ->get();
      return ["datos" =>$datos];
    }
    public function calculo_tarifa(Request $request)
    {

      $datos = TarifaFecha::whereDate('fecha', $this->formatDate($request->fecha))
      ->where('plan_tarifario_id', (int)$request->plan_tarifario_id)
      ->where('tipo_habitacion_id', (int)$request->tipo_habitacion_id)
      ->firstOrfail();
      return ["datos" =>$datos->tarifa_x_habitacion, "tarifa_x_persona" => $datos->persona_extra, "tarifa_x_nino" => $datos->ninos];
    }
  }
