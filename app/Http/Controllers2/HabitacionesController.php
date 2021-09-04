<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TblHabitacionesTipo;
use App\Models\TblHabitacione;
use App\Models\TblCama;
use App\TblReservaEstado;
use App\Inventario;
use App\FechaBloqueo;
use App\InventarioHabitacion;
use App\Models\TblHotel;
use DB;
use App\Alimento;
use App\PlanHabitacion;
use App\Ubicacion;
use PGSchema;
use Auth;
use App\Http\Controllers\ApiController;

class HabitacionesController extends Controller
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
        $this->middleware(function ($request, $next)
        {
            $this->projects = Auth::user()->schema;
            PGSchema::switchTo($this->projects);
            return $next($request);
        });

    }

    public function VerHabitacionesTipo(Request $request)
    {
        return TblHabitacionesTipo::find($request->input('id'));
    }
    public function ver_habitaciones(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '')
        {
            $items = TblHabitacione::select('tbl_habitaciones.id', 'tbl_habitaciones.numero', 'tbl_habitaciones_tipo.nombre', 'tbl_habitaciones_tipo.id as id_tipo', 'tbl_habitaciones.ubicacion_id as ubicacion', 'tbl_habitaciones.condicion')->join('tbl_habitaciones_tipo', 'tbl_habitaciones.id_habitacion_tipo', '=', 'tbl_habitaciones_tipo.id')

                ->orderBy('tbl_habitaciones_tipo.nombre', 'desc')
                ->paginate(50);
        }
        else
        {

            $items = TblHabitacione::select('tbl_habitaciones.id', 'tbl_habitaciones.numero', 'tbl_habitaciones_tipo.nombre', 'tbl_habitaciones_tipo.id as id_tipo', 'tbl_habitaciones.ubicacion_id as ubicacion', 'tbl_habitaciones.condicion')->join('tbl_habitaciones_tipo', 'tbl_habitaciones.id_habitacion_tipo', '=', 'tbl_habitaciones_tipo.id')
                ->where('tbl_habitaciones.' . $criterio, 'like', '%' . $buscar . '%')->orderBy('tbl_habitaciones_tipo.nombre', 'desc')
                ->paginate(50);
        }

        return ['pagination' => ['total' => $items->total() , 'current_page' => $items->currentPage() , 'per_page' => $items->perPage() , 'last_page' => $items->lastPage() , 'from' => $items->firstItem() , 'to' => $items->lastItem() , ], 'items' => $items];

    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $data = TblHabitacione::findOrFail($request->id);
        $data->condicion = false;
        $data->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $data = TblHabitacione::findOrFail($request->id);
        $data->condicion = true;
        $data->save();
    }

    public function VerHabitaciones(Request $request)
    {
        if (!is_null($request->input('page')))
        {
            $limit = 10;
            $page = $request->input('page') - 1;
            if (!is_null($request->input('filtro')))
            {

                $data = TblHabitacione::with('tbl_habitaciones_tipo')->with('tbl_cama')
                    ->skip($page)->take($limit)->where($request->input('criterio') , 'ILIKE', '%' . $request->input('filtro') . '%')
                    ->get();
                $total = TblHabitacione::where($request->input('criterio') , 'ILIKE', '%' . $request->input('filtro') . '%')
                    ->count();
            }
            else
            {
                $total = TblHabitacione::count();
                $data = TblHabitacione::with('tbl_habitaciones_tipo')->with('tbl_cama')
                    ->skip($page)->take($limit)->get();
            }
            return (['habitaciones' => $data, 'pages' => ceil($total / $limit) ]);
        }
        else
        {
            $data = DB::table('tbl_habitaciones')->select('tbl_habitaciones.id', DB::raw('concat_ws(\'-\',tbl_habitaciones.numero,  tbl_habitaciones_tipo.nombre) as name'))
                ->join('tbl_habitaciones_tipo', 'tbl_habitaciones.id_habitacion_tipo', '=', 'tbl_habitaciones_tipo.id')
                ->orderBy('tbl_habitaciones_tipo.nombre', 'asc')
                ->orderBy('tbl_habitaciones.numero', 'ASC')
                ->get();
            return $data;
        }
    }

    public function obtener_bloqueos(Request $request)
    {

        $datos = FechaBloqueo::where('habitacion_id', (int)$request->habitacion_id)
            ->first();
        $res = ($datos === null) ? 0 : 1;
        return ['datos' => $datos, 'contador' => $res];
    }

    public function SaveNewTipo(Request $request)
    {

        $data = new TblHabitacionesTipo();
        $data->codigo = $request->input('codigo');
        $data->nombre = $request->input('nombre');
        $data->room_type = $request->input('room_type');
        $data->persona_minimo = 1;
        $data->persona_maximo = $request->input('maximo');
        $data->id_camas = $request->input('camas_tipo');
        $data->Save();
        return response()
            ->json(['validate' => true, 'id' => $data->id]);

    }
    public function SaveEditTipo(Request $request)
    {
        $data = TblHabitacionesTipo::find($request->input('id'));
        $data->codigo = $request->input('codigo');
        $data->nombre = $request->input('nombre');
        $data->room_type = $request->input('room_type');
        $data->persona_minimo = 1;
        $data->persona_maximo = $request->input('maximo');
        $data->id_camas = $request->input('camas_tipo');
        $data->Save();
        return response()
            ->json(['validate' => true, 'id' => $data->id]);
    }

    public function SaveNewHabitacion(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try
        {
            DB::beginTransaction();
            $data = new TblHabitacione();
            $data->id_habitacion_tipo = (int)$request->input('id_habitacion_tipo');
            $data->numero = $request->input('numero');
            $data->ubicacion_id = $request->input('ubicacion');
            $data->Save();
            DB::commit();
            return response()->json(['validate'=>true,'id'=>$data->id]);

        }
        catch(\Illuminate\Database\QueryException $ex)
        {
            return false;
        }
    }
    public function inventario_nuevo(Request $request)
    {
        $fecha_actual = date('Y-m-d');
        $date = strtotime('+2 year', strtotime($fecha_actual));
        $fecha_salida = date('Y-m-d', $date);
        for ($i = $fecha_actual;$i <= $fecha_salida;$i = date("Y-m-d", strtotime($i . "+ 1 days")))
        {
            $data = Inventario::firstOrNew(['tipo_habitacion_id' => $request->input('id_habitacion_tipo') , 'start' => $i]);
            $data->disponibilidad = 1 + $data->disponibilidad;
            $data->tipo_habitacion_id = (int)$request->input('id_habitacion_tipo');
            $data->start = $i;
            $data->color = "##1976d2";
            $data->end = date("Y-m-d", strtotime('+1 day', strtotime($i)));
            $data->save();
            $data_save = InventarioHabitacion::firstOrNew(['tipo_habitacion_id' => $request->input('id_habitacion_tipo') , 'habitacion_id' => (int)$request->habitacion_id, 'start' => $i]);
            $data_save->disponibilidad = true;
            $data_save->tipo_habitacion_id = (int)$request->id_habitacion_tipo;
            $data_save->habitacion_id = (int)$request->habitacion_id;
            $data_save->start = $i;
            $data_save->estado = 'Disponible';
            $data_save->color = "#1976d2";
            $data_save->end = date("Y-m-d", strtotime('+1 day', strtotime($i)));
            $data_save->save();
        }

    }
    public function actualizarInventario(Request $request, $fecha_actual, $fecha_salida, $id_habitacion_tipo)
    {
        $b = new ApiController($request);
        $result = $b->CrearInventario($fecha_actual, $fecha_salida, $id_habitacion_tipo);
        return $result;
    }
    public function SaveEditHabitacion(Request $request)
    {

      if (!$request->ajax()) return redirect('/');

      try
      {
          DB::beginTransaction();
          $data = TblHabitacione::find($request->input('id'));
          $data->id_habitacion_tipo = $request->input('id_habitacion_tipo');
          $data->numero = $request->input('numero');
          $data->ubicacion_id = (int)$request->input('ubicacion');
          $data->Save();
          DB::commit();
          return response()->json(['validate'=>true,'id'=>$data->id]);

      }
      catch(\Illuminate\Database\QueryException $ex)
      {
          return false;
      }

    }

    public function ListarHabitacionesTipo(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar == '')
        {
            $items = TblHabitacionesTipo::select('id', 'codigo', 'nombre')->orderBy('nombre', 'desc')
                ->paginate(20);
        }
        else
        {

            $items = TblHabitacionesTipo::select('id', 'codigo', 'nombre')->where('tbl_habitaciones_tipo.' . $criterio, 'like', '%' . $buscar . '%')->orderBy('nombre', 'desc')
                ->paginate(20);
        }

        return ['pagination' => ['total' => $items->total() , 'current_page' => $items->currentPage() , 'per_page' => $items->perPage() , 'last_page' => $items->lastPage() , 'from' => $items->firstItem() , 'to' => $items->lastItem() , ], 'items' => $items];

    }
    public function VerCamasTipo()
    {
        return response()->json(['Tipo' => TblCama::all() ]);
    }
    public function VerSuperbookingTipo()
    {
        return response()
            ->json(['Tipo' => array(
            ['id' => 1,
            'descripcion' => 'prueba 1'],
            ['id' => 2,
            'descripcion' => 'prueba 2'],
            ['id' => 3,
            'descripcion' => 'prueba 3'],
            ['id' => 4,
            'descripcion' => 'prueba 4'],
        ) ]);
    }
    public function Pisos()
    {
        return ['data' => TblHabitacione::groupBy('piso')
            ->select('piso')
            ->get() ];
    }

    public function ver_estados()
    {

        $estados = TblReservaEstado::orderBy('orden', 'asc')->whereNotNull('orden')
            ->get();
        return $estados;
    }

    public function ver_estados_salidas()
    {

        $estados = TblReservaEstado::where('descripcion', '=', 'Salidas')->get();
        return $estados;
    }

    public function ver_estados_llegadas()
    {

        $estados = TblReservaEstado::where('descripcion', '=', 'LLegadas')->get();
        return $estados;
    }

    public function ver_estados_alojados()
    {

        $estados = TblReservaEstado::where('descripcion', '=', 'Alojado')->get();
        return $estados;
    }

    public function ver_planes()
    {
        $planes = Alimento::all();
        return ['planes' => $planes];
    }

    public function ver_habitaciones_planes(Request $request)
    {

        $planes = PlanHabitacion::select('tbl_alimentos.id', 'tbl_alimentos.nombre')->where('tbl_plan_habitaciones.habitacion_id', '=', $request->id)
            ->join('tbl_alimentos', 'tbl_plan_habitaciones.plan_id', 'tbl_alimentos.id')
            ->get();
        return ['planes' => $planes];

    }

    public function ubicaciones()
    {
        $datos = Ubicacion::all();
        return ['datos' => $datos];
    }

}
