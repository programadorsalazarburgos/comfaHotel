<?php

namespace App\Http\Controllers;
use App\Role;
use PGSchema;
use Auth;

use Illuminate\Http\Request;

class RoleController extends Controller
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

        if ($buscar==''){
            $roles = Role::orderBy('id', 'desc')->paginate(config('app.pagination'));
        }
        else{
            $roles = Role::where($criterio, 'ILIKE', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(config('app.pagination'));
        }


        return [
            'pagination' => [
                'total'        => $roles->total(),
                'current_page' => $roles->currentPage(),
                'per_page'     => $roles->perPage(),
                'last_page'    => $roles->lastPage(),
                'from'         => $roles->firstItem(),
                'to'           => $roles->lastItem(),
            ],
            'roles' => $roles
        ];
    }

    public function selectRole(Request $request)
    {
        //if (!$request->ajax()) return redirect('/');
        $roles = Role::where('condicion', '=', '1')->select('id','nombre')->orderBy('nombre', 'asc')->get();
        return ['roles' => $roles];
    }

}
