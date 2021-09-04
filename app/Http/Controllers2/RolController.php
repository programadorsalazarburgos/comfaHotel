<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Rol;
use App\User;
use App\RoleHasPermission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use PGSchema;
use Auth;

class RolController extends Controller
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
            $roles = Role::orderBy('id', 'desc')->paginate(5);
        }
        else{
            $roles = Role::where($criterio, 'ILIKE', '%'. $buscar . '%')->orderBy('id', 'desc')->paginate(5);
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
    public function selectRol(Request $request)
    {

         PGSchema::switchTo('master');
         $roles = Role::where('schema', $this->projects)->get();
        return ['roles' => $roles];
    }

    public function permisos()
    {
        $permisos = Permission::All();
        return ['permisos' => $permisos];
    }

    public function asignar_permisos(Request $request)
    {

       $role = Role::where('id', '=', (int)$request->rol_id)->firstOrfail();
       foreach ($request->permisos as $permiso) {
           $permission = Permission::findById($permiso['id']);
           $role->givePermissionTo($permission);

       }
    }


    public function quitar_permiso(Request $request)
    {


        $role = Role::where('id', '=', (int)$request->rol_id)->firstOrfail();
        $permission = Permission::findById((int)$request->permiso['id']);
        $role->revokePermissionTo($permission);

    }
    public function quitar_permisos_all(Request $request)
    {


        $role = Role::where('id', '=', (int)$request->rol_id)->firstOrfail();

        foreach ($request->permisos as $permiso) {
           $permission = Permission::findById($permiso['id']);
           $role->revokePermissionTo($permission);
        }


    }


    public function permisos_asignados(Request $request)
    {
        $permission = RoleHasPermission::select('permissions.created_at', 'permissions.guard_name', 'permissions.id', 'permissions.name', 'permissions.updated_at')->where('role_id', '=', $request->rol_id)
        ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
        ->get();
        return ['permisos' => $permission];

    }

      public function store(Request $request)
    {


        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();
            PGSchema::switchTo($this->projects);
            $rol = new Rol();
            $rol->name = $request->nombre;
            $rol->guard_name = 'web';
            $rol->schema = $this->projects;
            $rol->save();

            $this->guardarRolMaster($request->nombre);
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    private function guardarRolMaster($nombre)
    {
      PGSchema::switchTo('master');
      $rol = new Rol();
      $rol->name = $nombre;
      $rol->guard_name = 'web';
      $rol->schema = $this->projects;
      $rol->save();
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();

            $rol = Role::findOrFail($request->id);
            $rol->name = $request->nombre;
            $rol->guard_name = 'web';
            $rol->save();

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

     public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = Rol::findOrFail($request->id);
        $user->condicion = '0';
        $user->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = Rol::findOrFail($request->id);
        $user->condicion = '1';
        $user->save();
    }
}
