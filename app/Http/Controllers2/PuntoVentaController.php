<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PuntoVenta;
use PGSchema;
use Auth;

class PuntoVentaController extends Controller
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

       $datos = PuntoVenta::select('id','nombre')
                ->orderBy('nombre', 'desc')->paginate(config('app.pagination'));

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

        return PuntoVenta::select('id','nombre')->get();
    }
    public function Nueva(Request $request)
    {
        $data = new PuntoVenta();
        $data->nombre = $request->input('nombre');
        $data->save();
        $data->validate=true;
        return $data;
    }
    public function editar(Request $request)
    {
        $data = PuntoVenta::find($request->input('id'));
        $data->nombre = $request->input('nombre');
        $data->save();
        $data->validate=true;
        return $data;
    }
    public function borrar(Request $request)
    {
        $data = PuntoVenta::find($request->input('id'));
        $data->delete();
        return ['validate'=>true,'data'=>$data];
    }


}
