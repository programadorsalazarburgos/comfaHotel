<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TblImpuesto;
use App\Importe;
use App\ImpuestoTasa;
use App\FormatoAplicacion;
use PGSchema;
use Auth;


class TblImpuestosController extends Controller
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


    public function index()
    {
        $impuestos = TblImpuesto::select('id','nombre','valor', 'principal', 'ish', 'servicio')
                ->orderBy('nombre', 'desc')->paginate(config('app.pagination'));

        return [
        'pagination' => [
            'total'        => $impuestos->total(),
            'current_page' => $impuestos->currentPage(),
            'per_page'     => $impuestos->perPage(),
            'last_page'    => $impuestos->lastPage(),
            'from'         => $impuestos->firstItem(),
            'to'           => $impuestos->lastItem(),
        ],
         'impuestos' => $impuestos
];

        return TblImpuesto::select('id','nombre','valor')->get();
    }

    public function index_hotel(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $datos = ImpuestoTasa::select(
              'tbl_impuestos_tasas.id',
              'tbl_impuestos_tasas.importe_id',
              'tbl_impuestos_tasas.nombre',
              'tbl_impuestos_tasas.valor',
              'tbl_impuestos_tasas.hospedaje',
              'tbl_impuestos_tasas.producto_servicio',
              'tbl_impuestos_tasas.formato_id',
              'tbl_impuestos_tasas.estado',
              'tbl_importes.name as importe',
              'tbl_formato_aplicacion.nombre as formato'
              )
            ->leftjoin('tbl_importes', 'tbl_impuestos_tasas.importe_id', '=', 'tbl_importes.id')
            ->leftjoin('tbl_formato_aplicacion', 'tbl_impuestos_tasas.formato_id', '=', 'tbl_formato_aplicacion.id')
            ->orderBy('tbl_impuestos_tasas.id', 'desc')
            ->paginate(50);
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
    }

    public function get_importes()
    {
      $data = Importe::all();
      return ['datos' => $data];
    }

    public function get_formatos()
    {
      $data = FormatoAplicacion::all();
      return ['datos' => $data];
    }

    public function nuevo(Request $request)
    {
        $data = new ImpuestoTasa();
        $data->importe_id = $request->input('importe');
        $data->nombre = $request->input('nombre');
        $data->valor = $request->input('valor');
        $data->hospedaje = in_array('hospedaje', $request->checkedNames);
        $data->producto_servicio = in_array('producto', $request->checkedNames);
        $data->formato_id = $request->input('formato');
        $data->save();
        return $data;
    }
    public function editar(Request $request)
    {
        $data = ImpuestoTasa::find($request->input('id'));
        $data->importe_id = $request->input('importe');
        $data->nombre = $request->input('nombre');
        $data->valor = $request->input('valor');
        $data->hospedaje = in_array('hospedaje', $request->checkedNames);
        $data->producto_servicio = in_array('producto', $request->checkedNames);
        $data->formato_id = $request->input('formato');
        $data->save();
        return $data;
    }
    public function borrar(Request $request)
    {
        $data = TblImpuesto::find($request->input('id'));
        $data->delete();
        return ['validate'=>true,'data'=>$data];
    }

    public function principal(Request $request)
    {


            $data_count = TblImpuesto::where('principal', '=', true)->get();
            foreach ($data_count as $datos) {
               var_dump($datos['id']);
               $data_change = TblImpuesto::find($datos['id']);
               $data_change->principal = false;
               $data_change->save();
            }

            $data = TblImpuesto::find($request->input('impuesto_id'));
            $data->principal = true;
            $data->save();
            return ['validate'=> 2];

    }

      public function ish(Request $request)
    {


            $data_count = TblImpuesto::where('ish', '=', true)->get();
            foreach ($data_count as $datos) {
               var_dump($datos['id']);
               $data_change = TblImpuesto::find($datos['id']);
               $data_change->ish = false;
               $data_change->save();
            }

            $data = TblImpuesto::find($request->input('impuesto_id'));
            $data->ish = true;
            $data->save();
            return ['validate'=> 2];

    }

    public function servicio(Request $request)
  {


          $data_count = TblImpuesto::where('servicio', '=', true)->get();
          foreach ($data_count as $datos) {
             var_dump($datos['id']);
             $data_change = TblImpuesto::find($datos['id']);
             $data_change->servicio = false;
             $data_change->save();
          }

          $data = TblImpuesto::find($request->input('impuesto_id'));
          $data->servicio = true;
          $data->save();
          return ['validate'=> 2];
  }
  public function actualizar_estado(Request $request)
  {
    $data = ImpuestoTasa::find((int)$request->data['id']);
    $data->estado = $request->data['estado'];
    $data->save();
  }
}
