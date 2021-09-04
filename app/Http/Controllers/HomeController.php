<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function pruebas3()
    {
      return view('pdf.demo');

    }

    public function pruebas()
    {

 $fecha1 = "2019-09-01";
       $fecha2 = "2019-09-". ''. 30;

$fechaInicio=strtotime("2019-09-01");
$fechaFin=strtotime($fecha2);



for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){



    $fecha_dia = date("Y-m-d", $i);
    var_dump($fecha_dia);
    }

    }

    public function prueba()
    {
        return view('frontwnd.index2');

    }

    public function prueba2()
    {
        return view('pdf.factura');

    }

    public function permisos()
    {
        //crear permiso
        Permission::create(['name'=> 'crear']);
        Permission::create(['name'=> 'editar']);
        Permission::create(['name'=> 'eliminarr']);
        // $role = Role::findById(1);
        // $permission = Permission::findById(5);
        // // // //Asignar permiso a rol admin
        // //Crear permiso
        // $role->givePermissionTo($permission);
        // //Eliminar permiso
        // // $role->revokePermissionTo($permission);




        // //permisos usuario
        // auth()->user()->givePermissionTo($permission);
        //model_has_permission
        //model_id es el id del usuario logueado
        // auth()->user()->assignRole($role);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('frontwnd.index');
    }
    public function plantilla()
    {
        return view('frontwnd.index');
    }
}
