<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use PGSchema;
use Auth;

class DashboardController extends Controller
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
    public function __invoke(Request $request)
    {
        $anio=date('Y');
        $ingresos=DB::table('ingresos as i')
            ->select(DB::raw('MONTH(i.fecha_hora) as mes'),
                DB::raw('YEAR(i.fecha_hora) as anio'),
                DB::raw('SUM(i.total) as total'))
            ->whereYear('i.fecha_hora',$anio)
            ->groupBy(DB::raw('MONTH(i.fecha_hora)'),DB::raw('YEAR(i.fecha_hora)'))
            ->get();

        $ventas=DB::table('ventas as v')
            ->select(DB::raw('MONTH(v.fecha_hora) as mes'),
                DB::raw('YEAR(v.fecha_hora) as anio'),
                DB::raw('SUM(v.total) as total'))
            ->whereYear('v.fecha_hora',$anio)
            ->groupBy(DB::raw('MONTH(v.fecha_hora)'),DB::raw('YEAR(v.fecha_hora)'))
            ->get();

        return ['ingresos'=>$ingresos,'ventas'=>$ventas,'anio'=>$anio];

    }
}
