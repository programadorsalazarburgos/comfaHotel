<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Persona;
use App\User;
use App\Models\TblClientesTipo;
use App\Models\TblCliente;
use App\Models\TblClienteContacto;
use Auth;
use Saldo;
use PGSchema;
use DB;


class ClienteController extends Controller
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

    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){
            $clientes = TblCliente::select('tbl_clientes.*')
            ->orderBy('nombre','ASC')
            ->orderBy('apellido','ASC')
            ->paginate(config('app.pagination'));


        }

        else{
            $clientes = TblCliente::select('tbl_clientes.*')->where($criterio, 'like', '%'. $buscar . '%')
            ->orderBy('nombre','ASC')
            ->orderBy('apellido','ASC')
            ->paginate(config('app.pagination'));
        }


        return [
            'pagination' => [
                'total'        => $clientes->total(),
                'current_page' => $clientes->currentPage(),
                'per_page'     => $clientes->perPage(),
                'last_page'    => $clientes->lastPage(),
                'from'         => $clientes->firstItem(),
                'to'           => $clientes->lastItem(),
            ],
            'clientes' => $clientes
        ];
    }

    private function ContactosCliente($contacto_id)
    {
        $contactos = TblClienteContacto::select('contacto_tipo_id as tipoContacto','informacion as numero')->where('cliente_id', '=', $contacto_id)->get();
        return $contactos;
    }

    public function selectTipoCliente(){

        $clientes = TblClientesTipo::all();
        return ['clientes' => $clientes];
    }


    public function store(Request $request)
    {

      if (!$request->ajax()) return redirect('/');
      try {
        DB::beginTransaction();
        $cliente = new TblCliente();
        $cliente->id_clientes_tipo = (int)$request->tipoCliente;
        $cliente->nombre = $request->nombres;
        if ((int)$request->tipoCliente === 1) {
          $cliente->apellido = $request->apellidos;
        }
        if ((int)$request->tipoCliente === 2) {
          $cliente->apellido = "Empresa";
        }
        if ((int)$request->tipoCliente === 3) {
          $cliente->apellido = "Agencia";
        }
        $cliente->genero_id = $request->sexo;
        $cliente->fecha_nacimiento = date('Y-m-d',strtotime($request->fecha_nacimiento));
        $cliente->pais_id = (int)$request->pais;
        $cliente->id_documento_tipo = $request->tipoDocumento;
        $cliente->no_documento = $request->documento;
        $cliente->telefono = $request->telefono;
        $cliente->identificacion_fiscal = $request->identificacion_fiscal;
        $cliente->celular = $request->celular;
        $cliente->direccion = $request->direccion;
        $cliente->email = $request->correo;
        $cliente->save();
        DB::commit();

      } catch(\Illuminate\Database\QueryException $ex){
        return false;
      }
    }




public function actualizar(Request $request)
{

     if (!$request->ajax()) return redirect('/');
        //Datos Cliente Persona
        $cliente = TblCliente::find($request->id);
        $cliente->id_clientes_tipo = (int)$request->tipoCliente;
        $cliente->nombre = $request->nombres;
        if ((int)$request->tipoCliente === 1) {
          $cliente->apellido = $request->apellidos;
        }
        if ((int)$request->tipoCliente === 2) {
          $cliente->apellido = "Empresa";
        }
        if ((int)$request->tipoCliente === 3) {
          $cliente->apellido = "Agencia";
        }
        $cliente->genero_id = $request->sexo;
        $cliente->fecha_nacimiento = date('Y-m-d',strtotime($request->fecha_nacimiento));
        $cliente->pais_id = (int)$request->pais;
        $cliente->id_documento_tipo = $request->tipoDocumento;
        $cliente->no_documento = $request->documento;
        $cliente->telefono = $request->telefono;
        $cliente->identificacion_fiscal = $request->identificacion_fiscal;
        $cliente->celular = $request->celular;
        $cliente->direccion = $request->direccion;
        $cliente->estado = $request->estado;
        $cliente->postal = $request->postal;
        $cliente->email = $request->correo;
        $cliente->fecha_emision = date('Y-m-d',strtotime($request->fecha_emision));
        $cliente->pais_expedidor_id = $request->pais_expedidor;
        $cliente->fecha_caducidad = date('Y-m-d',strtotime($request->fecha_caducidad));
        $cliente->save();

        return ['validate'=>true];


}



}
