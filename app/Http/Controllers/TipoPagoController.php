<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TipoPago;
use PGSchema;
use Auth;


class TipoPagoController extends Controller
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

        $datos = TipoPago::select('id', 'nombre')
            ->orderBy('nombre', 'desc')->paginate(50);

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

        return TipoPago::select('id', 'nombre')->get();
    }
    public function store(Request $request)
    {
        $data = new TipoPago();
        $data->nombre = $request->input('nombre');
        $data->save();
        $data->validate = true;
        return $data;
    }
    public function editar(Request $request)
    {
        $data = TipoPago::find($request->input('id'));
        $data->nombre = $request->input('nombre');
        $data->save();
        $data->validate = true;
        return $data;
    }
    public function borrar(Request $request)
    {
        $data = TipoPago::find($request->input('id'));
        $data->delete();
        return ['validate' => true, 'data' => $data];
    }
}
