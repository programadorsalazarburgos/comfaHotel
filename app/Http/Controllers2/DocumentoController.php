<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TblDocumentoTipo;
use PGSchema;
use Auth;

class DocumentoController extends Controller
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
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar=='')
        {
            $TipoDocumento = TblDocumentoTipo::orderBy('id', 'desc')->paginate(config('app.pagination'));
        }
        else
        {
            $TipoDocumento = TblDocumentoTipo::where($criterio, 'ILIKE', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(config('app.pagination'));
        }


        return [
            'pagination' => [
                'total'        => $TipoDocumento->total(),
                'current_page' => $TipoDocumento->currentPage(),
                'per_page'     => $TipoDocumento->perPage(),
                'last_page'    => $TipoDocumento->lastPage(),
                'from'         => $TipoDocumento->firstItem(),
                'to'           => $TipoDocumento->lastItem(),
            ],
            'TipoDocumento' => $TipoDocumento
        ];
    }
}
