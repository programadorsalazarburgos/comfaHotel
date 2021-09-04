<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Persona;
use App\User;
use App\PuntoVenta;
use App\ImpuestoProducto;
use App\TblReservaEstado;
use App\FechaBloqueo;
use App\FuenteReserva;
use App\idioma;
use App\InventarioHabitacion;
use App\Models\TblPaise;
use App\Models\TblDepartamento;
use App\Models\ContactoTipo;
use App\Models\TblDocumentoTipo;
use App\Models\TblGenero;
use App\Models\TblHabitacione;
use App\Models\TblHabitacionesTipo;
use App\Models\TblImpuesto;
use App\Models\TblReservasDetalle;
use Auth;
use Saldo;
use DB;
use PGSchema;

class GlobalesController extends Controller
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

        public function idiomas()
        {

            $datos = idioma::find(1);
            return ['datos' => $datos];
        }

    public function selectPaises(){
        $paises = TblPaise::all();
        return ['paises' => $paises];
    }
    public function tipo_habitacion_nueva(Request $request)
    {
      $datos = TblHabitacione::select('id_habitacion_tipo')->where('id', $request->tipo_habitacion)->firstOrfail();
      return ['datos' => $datos];
    }
    public function obtener_estados()
    {
       $data = TblReservaEstado::all();
       return ['datos' => $data];
    }
    public function obtener_tipos_habitacion()
    {
       $data = TblHabitacionesTipo::all();
       return ['datos' => $data];
    }
    public function obtener_fuentes_reservas()
    {
       $data = FuenteReserva::all();
       return ['datos' => $data];
    }

    public function listar_habitacion(Request $request)
    {
      $habitacion = TblHabitacione::find((int)$request->habitacion);
      return ['data' => $habitacion];
    }
    public function listar_bloqueos_habitaciones(Request $request)
    {
      $bloqueos = FechaBloqueo::where('habitacion_id', $request->habitacion)->get();
      return ['data' => $bloqueos];
    }

     public function selectDepartamentos(Request $request){


        $departamentos = TblDepartamento::where('id_pais', '=', $request->pais_id)->get();
        return ['departamentos' => $departamentos];
    }

     public function selectTipoContacto(){


        $tipo_contactos = ContactoTipo::all();
        return ['tipo_contactos' => $tipo_contactos];
    }

     public function selectTipoDocumento(){

        $tipo_documentos = TblDocumentoTipo::all();
        return ['tipo_documentos' => $tipo_documentos];
    }

    public function selectSexo(){

        $sexos = TblGenero::all();
        return ['sexos' => $sexos];
    }
    private function filterData($fecha_llegada,$fecha_salida,$request)
    {
        $res=[];
        $data=DB::
        table('tbl_reservas_detalle')->
        where('tbl_reservas_detalle.id_habitacion_tipo','=',1)->
        whereNotNull('tbl_reservas_grupo.id_reservas_estado')->
        whereNotNull('tbl_reservas_detalle.id_habitacion')->
        where(DB::raw('date(\''.$fecha_llegada.'\')'),'>=',DB::raw('date(tbl_reservas.check_in_fecha)'))->
        where(DB::raw('date(\''.$fecha_salida.'\')'),'<=',DB::raw('date(tbl_reservas.check_out_fecha)'))->
        join('tbl_reservas_grupo', 'tbl_reservas_detalle.id_reservas_grupo', '=', 'tbl_reservas_grupo.id')->
        join('tbl_reservas','tbl_reservas_grupo.id_reservas','=','tbl_reservas.id')->
        select('tbl_reservas_detalle.id_habitacion')->
        get();
        // dd($data);
        foreach($data as $temp)
        {
            $res[]=$temp->id_habitacion;
        }
        return $res;
    }

    public function obtener_habitaciones(Request $request)
    {
      $tipo = TblHabitacione::where('id_habitacion_tipo', (int)$request->habitacion_id)->get();
      $arreglo = array();
      $datetime1 = new \DateTime($request->fecha_llegada);
      $datetime2 = new \DateTime($request->fecha_salida);
      $dias = $datetime1->diff($datetime2);
      foreach ($tipo as $value) {
        $data = InventarioHabitacion::select('habitacion_id')->whereBetween('start', [$request->fecha_llegada, date("Y-m-d",strtotime($request->fecha_salida."- 1 day"))])
        ->where('habitacion_id', (int)$value['id'])->where('disponibilidad', '=', true)->count();
        if ($data === (int)$dias->format('%a')) {
          $arreglo[] = (int)$value['id'];
        }
      }
      $tipos = TblHabitacione::whereIn('id', $arreglo)->get();
      return ["datos" => $tipos];

    }

    public function obtener_impuesto()
    {

      $impuestos = TblImpuesto::where('principal', true)->get();
      return ['impuestos' => $impuestos];

    }

    public function obtener_puntosventa()
    {

        $datos = PuntoVenta::all();
        return ['datos' => $datos];
    }


    public function impuesto_seleccionado(Request $request)
    {

        $datos = ImpuestoProducto::find($request->impuesto);
        return ['datos' => $datos];
    }

    public function numero_impuesto()
    {

        $datos = TblImpuesto::select('valor')->where('principal', true)->firstOrfail();
        return ['datos' => $datos];

    }

    public function obtener_cajeros()
    {
      PGSchema::switchTo('master');
      $datos = User::where('users.schema', '=', Auth::user()->schema)
            ->select('id','nombres')->orderBy('nombres', 'asc')->get();
            return ['datos' => $datos];
    }
    public function cargar_tipo_habitaciones()
    {
      $datos = TblHabitacionesTipo::select('id as code', 'nombre as name')->get();
      return ['datos' => $datos];
    }

}
