<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ImpuestoProducto;
use PGSchema;
use Auth;

class TblImpuestosProductosController extends Controller
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


       $impuestos = ImpuestoProducto::select('id','nombre','valor', 'iva', 'servicio')
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

        return ImpuestoProducto::select('id','nombre','valor')->get();
    }
    public function Nueva(Request $request)
    {
        $data = new ImpuestoProducto();
        $data->nombre = $request->input('nombre');
        $data->valor = $request->input('valor');
        $data->save();
        $data->validate=true;
        return $data;
    }
    public function editar(Request $request)
    {
        $data = ImpuestoProducto::find($request->input('id'));
        $data->nombre = $request->input('nombre');
        $data->valor = $request->input('valor');
        $data->save();
        $data->validate=true;
        return $data;
    }
    public function borrar(Request $request)
    {
        $data = ImpuestoProducto::find($request->input('id'));
        $data->delete();
        return ['validate'=>true,'data'=>$data];
    }


       public function principal(Request $request)
    {


            $data_count = ImpuestoProducto::where('iva', '=', true)->get();

            foreach ($data_count as $datos) {

               $data_change = ImpuestoProducto::find($datos['id']);
               $data_change->iva = false;
               $data_change->save();
            }

            $data = ImpuestoProducto::find($request->input('impuesto_id'));
            $data->iva = true;
            $data->save();
            return ['validate'=> 2];

    }

        public function servicio(Request $request)
    {


            $data_count = ImpuestoProducto::where('servicio', '=', true)->get();

            foreach ($data_count as $datos) {

               $data_change = ImpuestoProducto::find($datos['id']);
               $data_change->servicio = false;
               $data_change->save();
            }

            $data = ImpuestoProducto::find($request->input('impuesto_id'));
            $data->servicio = true;
            $data->save();
            return ['validate'=> 2];

    }


}
