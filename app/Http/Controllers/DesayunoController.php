<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Desayuno;
use DB;
use PGSchema;
use Auth;

class DesayunoController extends Controller
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

       $datos = Desayuno::select('id','valor')
                ->orderBy('valor', 'desc')->paginate(config('app.pagination'));

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

        return Desayuno::select('id','valor')->get();
    }
    public function Nueva(Request $request)
    {

        $cantidad = DB::table('tbl_desayunos')->count();
        if($cantidad < 1){
        $data = new Desayuno();
        $data->valor = $request->input('valor');
        $data->save();
        return 1;
        }
        else
        {
         return 2;
        }

    }
    public function editar(Request $request)
    {
        $data = Desayuno::find($request->input('id'));
        $data->valor = $request->input('valor');
        $data->save();
        $data->validate=true;
        return $data;
    }
    public function borrar(Request $request)
    {
        $data = Desayuno::find($request->input('id'));
        $data->delete();
        return ['validate'=>true,'data'=>$data];
    }


}
