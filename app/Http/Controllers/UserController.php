<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\idioma;
use App\Persona;
use App\Models\TblImpuesto;
use Auth;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use PGSchema;
use DB;

class UserController extends Controller
{


    public function idiomas()
    {

        $datos = idioma::find(1);
        return ['datos' => $datos];
    }

    public function index(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $personas = User::select('users.id','users.usuario', 'users.nombres', 'users.tipo_documento', 'users.documento', 'users.telefono', 'users.email','users.password')
            ->where('users.schema', '=', Auth::user()->schema)
            ->orderBy('users.id', 'desc')->paginate(config('app.pagination'));
        }
        else if($criterio != 'descripcion'){
            $personas = User::select('users.id','users.usuario', 'users.nombres', 'users.tipo_documento', 'users.documento', 'users.telefono', 'users.email','users.password')
            ->where('users.schema', '=', Auth::user()->schema)
            ->where('users.'.$criterio, 'ILIKE', '%'. $buscar . '%')
            ->orderBy('id', 'desc')->paginate(config('app.pagination'));
        }

         else {
            $personas = User::select('users.id','users.usuario', 'users.nombres', 'users.tipo_documento', 'users.documento', 'users.telefono', 'users.email','users.password')
            ->where('users.schema', '=', Auth::user()->schema)
            ->where('roles.'.$criterio, 'ILIKE', '%'. $buscar . '%')
            ->orderBy('id', 'desc')->paginate(config('app.pagination'));
        }

        return [
            'pagination' => [
                'total'        => $personas->total(),
                'current_page' => $personas->currentPage(),
                'per_page'     => $personas->perPage(),
                'last_page'    => $personas->lastPage(),
                'from'         => $personas->firstItem(),
                'to'           => $personas->lastItem(),
            ],
            'personas' => $personas
        ];
    }

    public function usuario_supervisor()
    {


      $datos = 'supervisor';
      $usuario = User::where('usuario', $datos)->exists();
      return ['datos'  => $usuario];

    }
    public function obtener_cajeros()
    {
      $datos = User::where('users.schema', '=', Auth::user()->schema)
            ->select('id','nombres')->orderBy('nombres', 'asc')->get();
            return ['datos' => $datos];
    }

    public function store(Request $request)
    {

         if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();

            $user = new User();
            $user->nombres = $request->nombres;
            $user->tipo_documento = $request->tipo_documento;
            $user->documento = $request->num_documento;
            $user->telefono = $request->telefono;
            $user->email = $request->email;
            $user->usuario = $request->usuario;
            $user->password = bcrypt( $request->password);
            $user->schema = auth()->user()->schema;
            $role = Role::findById($request->idrol);
            $user->assignRole($role);
            $user->save();

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();

            $user = User::findOrFail($request->id);
            $user->nombres = $request->nombres;
            $user->tipo_documento = $request->tipo_documento;
            $user->documento = $request->num_documento;
            $user->telefono = $request->telefono;
            $user->email = $request->email;
            $user->idrol = $request->idrol;
            $user->usuario = $request->usuario;
            $user->password = bcrypt( $request->password);
            $user->condicion = '1';
            $user->schema = auth()->user()->schema;
            $user->save();

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condicion = '0';
        $user->save();
    }

    public function activar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $user = User::findOrFail($request->id);
        $user->condicion = '1';
        $user->save();
    }

    /**
     * Return data Users.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function selectUsuarios(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $usuarios = User::where('condicion','=','1')
                        ->where('idrol','!=','1')
                        ->where('idrol','!=','5')
                        ->select('id','usuario')
                        ->get();
        return ['usuarios' => $usuarios];
    }

    /**
     * Return data Users.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

}
