<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Producto;
use App\CategoriaProducto;
use PGSchema;
use Auth;

class TblProductosController extends Controller
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

        $datos = Producto::select('tbl_productos.id', 'tbl_productos.nombre', 'tbl_categorias.nombre as categoria', 'tbl_productos.precio', 'tbl_categorias.id as categoria_id', 'tbl_puntos_ventas.nombre as venta', 'tbl_productos.punto_de_venta_id')
            ->join('tbl_categorias', 'tbl_productos.categoria_id', '=', 'tbl_categorias.id')
            ->join('tbl_puntos_ventas', 'tbl_productos.punto_de_venta_id', '=', 'tbl_puntos_ventas.id')



            ->orderBy('tbl_productos.nombre', 'desc')->paginate(config('app.pagination'));

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

        return Producto::select('id', 'nombre')->get();
    }
    public function store(Request $request)
    {
        $data = new Producto();
        $data->nombre = $request->input('nombre');
        $data->categoria_id = $request->input('categoria');
        $data->precio = $request->input('precio');
        $data->punto_de_venta_id = $request->input('venta');
        $data->save();
        $data->validate = true;
        return $data;
    }
    public function editar(Request $request)
    {
        $data = Producto::find($request->input('id'));
        $data->nombre = $request->input('nombre');
        $data->categoria_id = $request->input('categoria');
        $data->precio = $request->input('precio');
        $data->punto_de_venta_id = $request->input('venta');
        $data->save();
        $data->validate = true;
        return $data;
    }
    public function borrar(Request $request)
    {
        $data = Producto::find($request->input('id'));
        $data->delete();
        return ['validate' => true, 'data' => $data];
    }

    public function obtener_categorias()
    {

        $data = CategoriaProducto::all();
        return ['datos' => $data];
    }
}
