<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\TblTurnosTrabajo;
use PGSchema;
use Auth;

class turnosController extends Controller
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

    public function SaveEditTurno(Request $request)
    {
        $data=TblTurnosTrabajo::find($request->input('id'));
        $data->nombre=$request->input('nombre');
        $data->hora_inicio=new Carbon($request->input('hora_inicio'));
        $data->hora_fin=new Carbon($request->input('hora_fin'));
        $data->Save();
        return response()->json(['validate'=>true,'id'=>$data->id]);
    }
    public function SaveNewTurno(Request $request)
    {
        try
        {
            $data = new TblTurnosTrabajo();
            $data->nombre=$request->input('nombre');
            $data->hora_inicio=new Carbon($request->input('hora_inicio'));
            $data->hora_fin=new Carbon($request->input('hora_fin'));
            $data->Save();
            return response()->json(['validate'=>true,'msj'=>NULL,'id'=>$data->id]);
        }
        catch(\Exception $e)
        {
            return response()->json(['validate'=>false,'msj'=>$e->getMessage(),'id'=>NULL]);
        }
    }
    public function VerTurnos(Request $request)
    {

        if(!is_null($request->input('page')))
        {
            $limit=10;
            $page=$request->input('page')-1;
            if(!is_null($request->input('filtro')))
            {

                $data = TblTurnosTrabajo::skip($page)->take($limit)
                ->where($request->input('criterio'),'ILIKE','%'.$request->input('filtro').'%')
                ->get();
                foreach($data as $key=>$temp)
                {
                    $data[$key]->hora_fin = date('h:iA',strtotime($temp->hora_fin));
                    $data[$key]->hora_inicio = date('h:iA',strtotime($temp->hora_inicio));
                }
                $total = TblHabitacionesTipo
                ::where($request->input('criterio'),'ILIKE','%'.$request->input('filtro').'%')
                ->count();
            }
            else
            {
                $data = TblTurnosTrabajo::skip($page)->take($limit)->get();
                foreach($data as $key=>$temp)
                {
                    $data[$key]->hora_fin = date('h:iA',strtotime($temp->hora_fin));
                    $data[$key]->hora_inicio = date('h:iA',strtotime($temp->hora_inicio));
                }
                $total= TblTurnosTrabajo::count();
            }
            return (['turno'  => $data,'pages'=>ceil($total/$limit)]);
        }
        else{//Cuando no tiene filtros ni nada
            $data = TblTurnosTrabajo::all();
            foreach($data as $key=>$temp)
            {
                $data[$key]->hora_fin = date('h:iA',strtotime($temp->hora_fin));
                $data[$key]->hora_inicio = date('h:iA',strtotime($temp->hora_inicio));
            }
            return response()->json($data);
        }

    }
}
