<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CategoriaProducto;
use PGSchema;
use Auth;
use DB;


class TblCategoriasProductosController extends Controller
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

    }


    public function index()
    {

       $datos = CategoriaProducto::select('id','nombre')
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

        return CategoriaProducto::select('id','nombre')->get();
    }
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        try {
          DB::beginTransaction();
          $data = new CategoriaProducto();
          $data->nombre = $request->input('nombre');
          $data->save();
          $data->validate=true;
          DB::commit();
          return $data;

        } catch(\Illuminate\Database\QueryException $ex){
          return false;
        }
    }
    public function editar(Request $request)
    {
        $data = CategoriaProducto::find($request->input('id'));
        $data->nombre = $request->input('nombre');
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


}
