<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\EsquemaController;

use App\User;
use App\Iframe;
use App\idioma;
use App\Inventario;
use App\Models\TblHabitacionesTipo;
use App\InventarioHabitacion;
use App\Models\TblHabitacione;
use DB;
use PGSchema;
use Auth;
use DateTime;
use App\Models\TblHotel;
use SoapClient;
use SoapHeader;
use GuzzleHttp\Client;
use Excel;
use Validator;
//Empezar

class InventarioController extends Controller
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
      $this->middleware(function ($request, $next) {
           $this->projects = Auth::user()->schema;
           PGSchema::switchTo($this->projects);
           return $next($request);
       });
    }
    private function formatDate($date)
    {
        $date = (string)$date;
        $date = \date('Y-m-d', strtotime($date));
        return ($date);
    }

    public function registrar(Request $request)
    {
      $fecha1 = $this->formatDate($request->fecha_desde);
      $fecha2 = $this->formatDate($request->fecha_hasta);
      for($i=$fecha1;$i<=$fecha2;$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
      $data = Inventario::firstOrNew([
          'tipo_habitacion_id' => (int)$request->tipo_habitacion,
          'start' => $i]);
      $data->disponibilidad = (int)$request->disponibilidad;
      $data->tipo_habitacion_id = (int)$request->tipo_habitacion;
      $data->start = $i;
      $data->color = "##1976d2";
      $data->end = date("Y-m-d", strtotime ( '+1 day' , strtotime( $i )));
      $data->save();
      }

      $data_habitaciones = TblHabitacione::where('id_habitacion_tipo', (int)$request->tipo_habitacion)->get();
      $operacion = (int)count($data_habitaciones) - (int)$request->disponibilidad;
      $data_disponibles_habitaciones = TblHabitacione::where('id_habitacion_tipo', (int)$request->tipo_habitacion)->take((int)$request->disponibilidad)->get();
      $data_no_disponibles = TblHabitacione::where('id_habitacion_tipo', (int)$request->tipo_habitacion)->latest()
     ->take($operacion)
     ->get();

      foreach ($data_disponibles_habitaciones as $habitacion) {
        for($i=$fecha1;$i<=$fecha2;$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
          $data_save = InventarioHabitacion::firstOrNew([
              'tipo_habitacion_id' => (int)$request->tipo_habitacion,
              'habitacion_id' => (int)$habitacion['id'],
              'start' => $i]);
          $data_save->disponibilidad = true;
          $data_save->tipo_habitacion_id = (int)$request->tipo_habitacion;
          $data_save->habitacion_id = (int)$habitacion['id'];
          $data_save->start = $i;
          $data_save->estado = 'Disponible';
          $data_save->color = "#1976d2";
          $data_save->end = date("Y-m-d", strtotime ( '+1 day' , strtotime( $i )));
          $data_save->save();

        }
      }
      foreach ($data_no_disponibles as $habitacion) {
        for($i=$fecha1;$i<=$fecha2;$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
          $data_save = InventarioHabitacion::firstOrNew([
              'tipo_habitacion_id' => (int)$request->tipo_habitacion,
              'habitacion_id' => (int)$habitacion['id'],
              'start' => $i]);
          $data_save->disponibilidad = false;
          $data_save->tipo_habitacion_id = (int)$request->tipo_habitacion;
          $data_save->habitacion_id = (int)$habitacion['id'];
          $data_save->start = $i;
          $data_save->estado = 'No Disponible';
          $data_save->color = "#FF586B";
          $data_save->end = date("Y-m-d", strtotime ( '+1 day' , strtotime( $i )));
          $data_save->save();

        }
      }


  }
    public function editar_inventario(Request $request)
    {
      $data = Inventario::find((int)$request->id);
      $data->disponibilidad = (int)$request->disponibilidad;
      $data->save();
    }

    public function habitaciones_inventarios()
    {
      $data = DB::table('tbl_inventarios_habitaciones')->select(
          'tbl_inventarios_habitaciones.id as identificador',
          'tbl_inventarios_habitaciones.habitacion_id as resource',
          'tbl_inventarios_habitaciones.color as barColor',
          'tbl_inventarios_habitaciones.start',
          'tbl_inventarios_habitaciones.end',
          'tbl_inventarios_habitaciones.estado as text'
          )
          ->groupBy(
          'tbl_inventarios_habitaciones.id',
          'tbl_inventarios_habitaciones.tipo_habitacion_id',
          'tbl_inventarios_habitaciones.color',
          'tbl_inventarios_habitaciones.start',
          'tbl_inventarios_habitaciones.end',
          'tbl_inventarios_habitaciones.disponibilidad'
          )
      ->join('tbl_habitaciones_tipo', 'tbl_inventarios_habitaciones.tipo_habitacion_id', '=', 'tbl_habitaciones_tipo.id')
      ->get();
      foreach ($data as $key => $temp) {
          unset($temp->barcolor);
          $data[$key]->id = ($key + 1);
      }
      return $data;
    }
    public function total_habitaciones(Request $request)
    {
      $data_habitaciones = TblHabitacione::where('id_habitacion_tipo', (int)$request->tipo_habitacion)->count();
      return ["datos" => $data_habitaciones];
    }
    public function ocupaciones(Request $request)
    {
        $now = new \DateTime();
        $data = Inventario::select(
            'tbl_inventarios.id',
            'tbl_inventarios.tipo_habitacion_id',
            'tbl_inventarios.disponibilidad',
            'tbl_inventarios.start',
            'tbl_habitaciones_tipo.nombre as tipo_habitacion'
            )->join('tbl_habitaciones_tipo', 'tbl_inventarios.tipo_habitacion_id', '=', 'tbl_habitaciones_tipo.id')
            ->whereDate('start', $request->fecha_filtro)
            ->get();

         foreach ($data as $key => $temp)
        {
            $temp1 = $this->cantidadTipoHabitaciones($temp->tipo_habitacion_id);
            $data[$key]->ocupacion = (int)$temp1['datos'] - (int)$temp->disponibilidad;
            $data[$key]->cantidadTipoHabitaciones = $temp1['datos'];


        }

        $data_suma_habitaciones = $data->sum('cantidadTipoHabitaciones');
        $data_suma_disponibilidad = $data->sum('disponibilidad');
        $ocupadas_diaria = (int)$data_suma_habitaciones - (int)$data_suma_disponibilidad;
        $ocupacion_porcentaje =  ((int)$ocupadas_diaria / (int)$data_suma_habitaciones)*100;

      return [
          'datos' => $data,
          'data_suma_habitaciones' => $data_suma_habitaciones,
          'data_suma_disponibilidad' => $data_suma_disponibilidad,
          'ocupadas_diaria' => $ocupadas_diaria,
          'ocupacion_porcentaje' => $ocupacion_porcentaje
          ];
    }
    public function cantidadTipoHabitaciones($tipo_habitacion_id)
    {
        $data_habitaciones = TblHabitacione::where('id_habitacion_tipo', (int)$tipo_habitacion_id)->count();
        return ["datos" => $data_habitaciones];
    }

}
