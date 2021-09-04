<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Alimento;
use App\CategoriaProducto;
use App\Producto;
use App\Factura;
use App\ImpuestoProducto;
use App\ConsumoExtra;
use App\TotalFactura;
use App\PagoTotal;
use App\TipoPagoTotal;
use App\PagadorFactura;
use App\TipoPagoTotalDetalle;
use App\Models\TblImpuesto;
use App\Models\TblReservasGrupo;
use App\Models\TblHabitacione;
use DB;
use PGSchema;
use Auth;


class ComidaController extends Controller
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
            $alimentos = Alimento::
                select('tbl_alimentos.id','tbl_alimentos.nombre', 'tbl_alimentos.valor')
                ->orderBy('tbl_alimentos.id', 'desc')->paginate(config('app.pagination'));
        }
        else{
            $alimentos = Alimento::
                select('tbl_alimentos.id','tbl_alimentos.nombre', 'tbl_alimentos.valor')
                ->where('tbl_alimentos.'.$criterio, 'like', '%'. $buscar . '%')
                ->orderBy('tbl_alimentos.id', 'desc')->paginate(config('app.pagination'));
        }


        return [
            'pagination' => [
                'total'        => $alimentos->total(),
                'current_page' => $alimentos->currentPage(),
                'per_page'     => $alimentos->perPage(),
                'last_page'    => $alimentos->lastPage(),
                'from'         => $alimentos->firstItem(),
                'to'           => $alimentos->lastItem(),
            ],
            'alimentos' => $alimentos
        ];
    }

    public function impuesto_producto()
    {

      $datos = ImpuestoProducto::select('valor')->where('iva', true)->firstOrfail();
      return ['datos' => $datos];


    }

    public function store(Request $request)
    {

        if (!$request->ajax()) return redirect('/');

        $datos = new Alimento();
        $datos->nombre = $request->nombre;
        $datos->valor = $request->valor;
        $datos->save();
        return ['validate'=>true];

    }

    public function update(Request $request)
    {

    	if (!$request->ajax()) return redirect('/');
        $articulo = Alimento::findOrFail($request->id);
        $articulo->nombre = $request->nombre;
        $articulo->valor = $request->valor;
        $articulo->save();

    }

    public function obtener_categorias()
    {

        $datos = CategoriaProducto::all();
        return ['datos' => $datos];
    }


        public function obtener_categorias_f(Request $request)
    {



        $datos = Producto::select('tbl_productos.categoria_id', 'tbl_categorias.nombre')
        ->join('tbl_categorias', 'tbl_productos.categoria_id', '=', 'tbl_categorias.id')
        ->where('tbl_productos.punto_de_venta_id', '=', (int)$request->venta)

->groupBy(
            'tbl_productos.categoria_id',
            'tbl_categorias.nombre'
        )
        ->get();

        return ['datos' => $datos];
    }

    public function obtener_productos(Request $request)
    {

        $datos = Producto::select('tbl_productos.id', 'tbl_productos.nombre', 'tbl_productos.precio', 'tbl_productos.categoria_id')
        ->join('tbl_categorias', 'tbl_productos.categoria_id', '=', 'tbl_categorias.id')
        ->where('tbl_productos.categoria_id', '=', $request->categoria)
        ->where('tbl_productos.punto_de_venta_id', '=', $request->venta)
        ->get();

        return ['datos' => $datos];
    }


    public function datos_productos(Request $request)
    {

        $datos = Producto::select('tbl_productos.id', 'tbl_productos.nombre', 'tbl_productos.precio', 'tbl_productos.categoria_id')
        ->join('tbl_categorias', 'tbl_productos.categoria_id', '=', 'tbl_categorias.id')
        ->where('tbl_productos.id', '=', $request->producto)->firstOrfail();

         return ['datos' => $datos];
    }


     public function impuestos_productos()
    {

        $datos = ImpuestoProducto::select('tbl_impuestos_productos.id', 'tbl_impuestos_productos.valor', 'tbl_impuestos_productos.iva', 'tbl_impuestos_productos.servicio',  DB::raw("CONCAT(tbl_impuestos_productos.nombre,'- ',tbl_impuestos_productos.valor,'%') as nombre_impuesto"))->get();

        return ['datos' => $datos];

    }

    public function impuestos_habitaciones()
   {

       $datos = TblImpuesto::select('tbl_impuestos.id', 'tbl_impuestos.valor', 'tbl_impuestos.principal as iva', 'tbl_impuestos.ish',  DB::raw("CONCAT(tbl_impuestos.nombre,'- ',tbl_impuestos.valor,'%') as nombre_impuesto"))
       ->where('tbl_impuestos.principal', true)
       ->orWhere('tbl_impuestos.ish', true)
       ->get();

       return ['datos' => $datos];

   }

    public function consumos_extras(Request $request)
    {

        $iva = array();
        $servicio = array();
        $iva_id = array();
        $servicio_id = array();

        foreach ($request->impuesto as $value) {

          if ($value['iva'] == true) {
            $consultaIva = ImpuestoProducto::where('iva', true)->firstOrfail();
            $iva[] = $consultaIva['valor'];
            $iva_id[] = $consultaIva['id'];
          }
          if ($value['servicio'] == true) {
            $consultaServicio = ImpuestoProducto::where('servicio', true)->firstOrfail();
            $servicio[] = $consultaServicio['valor'];
            $servicio_id[] = $consultaServicio['id'];
          }
        }
        if ($iva == []) {
          $iva = 0;
        }
        else {
          $iva = $iva[0];
        }

        if ($servicio == []) {
          $servicio = 0;
        }
        else {
            $servicio = $servicio[0];
        }


        if ($iva_id == []) {
          $iva_id = null;
        }
        else {
          $iva_id = $iva_id[0];
        }

        if ($servicio_id == []) {
          $servicio_id = null;
        }
        else {
            $servicio_id = $servicio_id[0];
        }


        $sumaImpuestos = (int)$iva + (int)$servicio;
        $total_consumo1 = ((float)$request->total_extras_neto * (float)$sumaImpuestos)/100;
        $total_extras =  ((float)$request->total_extras_neto + (float)$total_consumo1);
        $nombre_producto = Producto::find((int)$request->producto);
        $datos = new ConsumoExtra();
        $datos->producto_id = $request->producto;
        $datos->impuesto_id = $iva_id;
        $datos->servicio_id = $servicio_id;
        $datos->cliente_id = $request->cliente;
        $datos->reserva_detalle_id = $request->detalle_reserva;
        $datos->total_consumo = $total_extras;
        $datos->numero_habitacion = $request->numero;
        $datos->fecha = $request->fecha;
        $datos->punto_de_venta_id = $request->venta;
        $datos->categoria_id = $request->categoria;
        $datos->unidad = $request->unidad;
        $datos->factura_id = $request->factura;
        $datos->total_extras_neto = $request->total_extras_neto;
        $datos->DesProdu = $nombre_producto['nombre'];
        $datos->PUnitario = $request->total_extras_neto;
        $datos->save();
        return ['datos' => $datos];
    }


    public function actualizar_extras(Request $request)
    {


      $iva = array();
      $servicio = array();
      $iva_id = array();
      $servicio_id = array();
      foreach ($request->impuesto as $value) {

        if ($value['iva'] == true) {
          $consultaIva = ImpuestoProducto::where('iva', true)->firstOrfail();
          $iva[] = $consultaIva['valor'];
          $iva_id[] = $consultaIva['id'];
        }
        if ($value['servicio'] == true) {
          $consultaServicio = ImpuestoProducto::where('servicio', true)->firstOrfail();
          $servicio[] = $consultaServicio['valor'];
          $servicio_id[] = $consultaServicio['id'];
        }
      }
      if ($iva == []) {
        $iva = 0;
      }
      else {
        $iva = $iva[0];
      }

      if ($servicio == []) {
        $servicio = 0;
      }
      else {
          $servicio = $servicio[0];
      }


      if ($iva_id == []) {
        $iva_id = null;
      }
      else {
        $iva_id = $iva_id[0];
      }

      if ($servicio_id == []) {
        $servicio_id = null;
      }
      else {
          $servicio_id = $servicio_id[0];
      }



      $sumaImpuestos = (int)$iva + (int)$servicio;
      $total_consumo1 = ((float)$request->total_extras_neto * (float)$sumaImpuestos)/100;
      $total_extras =  ((float)$request->total_extras_neto + (float)$total_consumo1);



        $datos = ConsumoExtra::find($request->id_consumo_extra);
        $datos->producto_id = $request->producto;
        $datos->impuesto_id = $iva_id;
        $datos->servicio_id = $servicio_id;
        $datos->cliente_id = $request->cliente;
        $datos->reserva_detalle_id = $request->detalle_reserva;
        $datos->total_extras_neto= (float)$request->total_extras_neto;
        $datos->total_consumo = (float)$total_extras;
        $datos->numero_habitacion = $request->numero;
        $datos->fecha = $request->fecha;
        $datos->punto_de_venta_id = $request->venta;
        $datos->categoria_id = $request->categoria;
        $datos->save();
        return ['datos' => $datos];
    }

    public function obtener_consumos_extras(Request $request)
    {


 if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){

       $datos = ConsumoExtra::select(
        'tbl_consumos_extras.id',
         'tbl_consumos_extras.producto_id',
          'tbl_consumos_extras.impuesto_id',
          'tbl_consumos_extras.servicio_id',
          'tbl_consumos_extras.reserva_pagador_id',
          'tbl_consumos_extras.cliente_id',
          'tbl_consumos_extras.reserva_detalle_id',
          'tbl_consumos_extras.total_consumo',
           'tbl_consumos_extras.numero_habitacion',
            'tbl_consumos_extras.fecha',
            'tbl_productos.nombre as producto',
             'tbl_impuestos_productos.nombre as impuesto',
             'tbl_impuestos_productos2.nombre as servicio',
             'tbl_impuestos_productos.valor as valor_impuesto',
             'tbl_impuestos_productos2.valor as valor_servicio',
              'tbl_puntos_ventas.nombre as punto_venta',
              'tbl_consumos_extras.punto_de_venta_id',
              'tbl_consumos_extras.categoria_id',
              'tbl_consumos_extras.unidad',
              'tbl_consumos_extras.total_extras_neto',
              'tbl_consumos_extras.CodProdu',
              'tbl_consumos_extras.DesProdu',
              'tbl_consumos_extras.UnmProdu',
              'tbl_consumos_extras.PUnitario',
              'tbl_consumos_extras.MtoTotal',
              'tbl_consumos_extras.PorImpu',
              'tbl_consumos_extras.PorServi'

          )
        ->where('tbl_consumos_extras.cliente_id', '=', $request->cliente)
        ->where('tbl_consumos_extras.reserva_detalle_id', '=', $request->detalle_reserva)
        ->join('tbl_productos', 'tbl_consumos_extras.producto_id', '=', 'tbl_productos.id')
        ->leftjoin('tbl_impuestos_productos', 'tbl_consumos_extras.impuesto_id', '=', 'tbl_impuestos_productos.id')
        ->leftjoin('tbl_impuestos_productos as tbl_impuestos_productos2', 'tbl_consumos_extras.servicio_id', '=', 'tbl_impuestos_productos2.id')
         ->join('tbl_puntos_ventas', 'tbl_productos.punto_de_venta_id', '=', 'tbl_puntos_ventas.id')

        ->paginate(config('app.pagination'));


  $sum_total_consumo = 0;
        foreach ($datos as $valor) {
            $sum_total_consumo += $valor['total_consumo'];
        }


        }
        else if ($request->criterio == 'puntoVenta')
            # code...
        {



           $datos = ConsumoExtra::select(
             'tbl_consumos_extras.id',
              'tbl_consumos_extras.producto_id',
               'tbl_consumos_extras.impuesto_id',
               'tbl_consumos_extras.servicio_id',
               'tbl_consumos_extras.reserva_pagador_id',
               'tbl_consumos_extras.cliente_id',
               'tbl_consumos_extras.reserva_detalle_id',
               'tbl_consumos_extras.total_consumo',
                'tbl_consumos_extras.numero_habitacion',
                 'tbl_consumos_extras.fecha',
                 'tbl_productos.nombre as producto',
                  'tbl_impuestos_productos.nombre as impuesto',
                  'tbl_impuestos_productos2.nombre as servicio',
                  'tbl_impuestos_productos.valor as valor_impuesto',
                  'tbl_impuestos_productos2.valor as valor_servicio',
                   'tbl_puntos_ventas.nombre as punto_venta',
                   'tbl_consumos_extras.punto_de_venta_id',
                   'tbl_consumos_extras.categoria_id',
                   'tbl_consumos_extras.unidad',
                   'tbl_consumos_extras.total_extras_neto',
                   'tbl_consumos_extras.CodProdu',
                   'tbl_consumos_extras.DesProdu',
                   'tbl_consumos_extras.UnmProdu',
                   'tbl_consumos_extras.PUnitario',
                   'tbl_consumos_extras.MtoTotal',
                   'tbl_consumos_extras.PorImpu',
                   'tbl_consumos_extras.PorServi'


          )
        ->where('reserva_pagador_id', '=', $request->pagador)
        ->where('reserva_detalle_id', '=', $request->detalle_reserva)
        ->join('tbl_productos', 'tbl_consumos_extras.producto_id', '=', 'tbl_productos.id')
        ->leftjoin('tbl_impuestos_productos', 'tbl_consumos_extras.impuesto_id', '=', 'tbl_impuestos_productos.id')
        ->leftjoin('tbl_impuestos_productos as tbl_impuestos_productos2', 'tbl_consumos_extras.servicio_id', '=', 'tbl_impuestos_productos2.id')
         ->join('tbl_puntos_ventas', 'tbl_productos.punto_de_venta_id', '=', 'tbl_puntos_ventas.id')
        ->where('tbl_consumos_extras.punto_de_venta_id', '=',  $buscar)
        ->paginate(config('app.pagination'));



           $datos2 = ConsumoExtra::select(
             'tbl_consumos_extras.id',
              'tbl_consumos_extras.producto_id',
               'tbl_consumos_extras.impuesto_id',
               'tbl_consumos_extras.servicio_id',
               'tbl_consumos_extras.reserva_pagador_id',
               'tbl_consumos_extras.cliente_id',
               'tbl_consumos_extras.reserva_detalle_id',
               'tbl_consumos_extras.total_consumo',
                'tbl_consumos_extras.numero_habitacion',
                 'tbl_consumos_extras.fecha',
                 'tbl_productos.nombre as producto',
                  'tbl_impuestos_productos.nombre as impuesto',
                  'tbl_impuestos_productos2.nombre as servicio',
                  'tbl_impuestos_productos.valor as valor_impuesto',
                  'tbl_impuestos_productos2.valor as valor_servicio',
                   'tbl_puntos_ventas.nombre as punto_venta',
                   'tbl_consumos_extras.punto_de_venta_id',
                   'tbl_consumos_extras.categoria_id',
                   'tbl_consumos_extras.unidad',
                   'tbl_consumos_extras.total_extras_neto',
                   'tbl_consumos_extras.CodProdu',
                   'tbl_consumos_extras.DesProdu',
                   'tbl_consumos_extras.UnmProdu',
                   'tbl_consumos_extras.PUnitario',
                   'tbl_consumos_extras.MtoTotal',
                   'tbl_consumos_extras.PorImpu',
                   'tbl_consumos_extras.PorServi'

)
        ->where('reserva_pagador_id', '=', $request->pagador)
        ->where('reserva_detalle_id', '=', $request->detalle_reserva)
        ->join('tbl_productos', 'tbl_consumos_extras.producto_id', '=', 'tbl_productos.id')
        ->leftjoin('tbl_impuestos_productos', 'tbl_consumos_extras.impuesto_id', '=', 'tbl_impuestos_productos.id')
        ->leftjoin('tbl_impuestos_productos as tbl_impuestos_productos2', 'tbl_consumos_extras.servicio_id', '=', 'tbl_impuestos_productos2.id')
         ->join('tbl_puntos_ventas', 'tbl_productos.punto_de_venta_id', '=', 'tbl_puntos_ventas.id')
        ->where('tbl_consumos_extras.punto_de_venta_id', '=',  $buscar)
        ->get();

        $sum_total_consumo = 0;
        foreach ($datos2 as $valor) {
            $sum_total_consumo += $valor['total_consumo'];
        }



 }
        return [
            'pagination' => [
                'total'        => $datos->total(),
                'current_page' => $datos->currentPage(),
                'per_page'     => $datos->perPage(),
                'last_page'    => $datos->lastPage(),
                'from'         => $datos->firstItem(),
                'to'           => $datos->lastItem(),
            ],
            'datos' => $datos, 'sum_total_consumo' => $sum_total_consumo
        ];




    }

   public function obtener_consumos_extras2(Request $request)
    {


 if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar==''){

       $datos = ConsumoExtra::select(
         'tbl_consumos_extras.id',
          'tbl_consumos_extras.producto_id',
           'tbl_consumos_extras.impuesto_id',
           'tbl_consumos_extras.servicio_id',
           'tbl_consumos_extras.reserva_pagador_id',
           'tbl_consumos_extras.cliente_id',
           'tbl_consumos_extras.reserva_detalle_id',
           'tbl_consumos_extras.total_consumo',
            'tbl_consumos_extras.numero_habitacion',
             'tbl_consumos_extras.fecha',
             'tbl_productos.nombre as producto',
              'tbl_impuestos_productos.nombre as impuesto',
              'tbl_impuestos_productos2.nombre as servicio',
              'tbl_impuestos_productos.valor as valor_impuesto',
              'tbl_impuestos_productos2.valor as valor_servicio',
               'tbl_puntos_ventas.nombre as punto_venta',
               'tbl_consumos_extras.punto_de_venta_id',
               'tbl_consumos_extras.categoria_id',
               'tbl_consumos_extras.unidad',
               'tbl_consumos_extras.total_extras_neto',
               'tbl_consumos_extras.CodProdu',
               'tbl_consumos_extras.DesProdu',
               'tbl_consumos_extras.UnmProdu',
               'tbl_consumos_extras.PUnitario',
               'tbl_consumos_extras.MtoTotal',
               'tbl_consumos_extras.PorImpu',
               'tbl_consumos_extras.PorServi'

          )

        ->where('tbl_consumos_extras.cliente_id', '=', $request->cliente)
        ->where('tbl_consumos_extras.reserva_detalle_id', '=', $request->detalle_reserva)
        ->leftjoin('tbl_productos', 'tbl_consumos_extras.producto_id', '=', 'tbl_productos.id')
        ->leftjoin('tbl_impuestos_productos', 'tbl_consumos_extras.impuesto_id', '=', 'tbl_impuestos_productos.id')
        ->leftjoin('tbl_impuestos_productos as tbl_impuestos_productos2', 'tbl_consumos_extras.servicio_id', '=', 'tbl_impuestos_productos2.id')
         ->leftjoin('tbl_puntos_ventas', 'tbl_productos.punto_de_venta_id', '=', 'tbl_puntos_ventas.id')

        ->paginate(config('app.pagination'));

// dd($datos);

  $sum_total_consumo = 0;
        foreach ($datos as $valor) {
            $sum_total_consumo += $valor['total_consumo'];
        }


        }
        else if ($request->criterio == 'puntoVenta')
            # code...
        {



           $datos = ConsumoExtra::select(
             'tbl_consumos_extras.id',
              'tbl_consumos_extras.producto_id',
               'tbl_consumos_extras.impuesto_id',
               'tbl_consumos_extras.servicio_id',
               'tbl_consumos_extras.reserva_pagador_id',
               'tbl_consumos_extras.cliente_id',
               'tbl_consumos_extras.reserva_detalle_id',
               'tbl_consumos_extras.total_consumo',
                'tbl_consumos_extras.numero_habitacion',
                 'tbl_consumos_extras.fecha',
                 'tbl_productos.nombre as producto',
                  'tbl_impuestos_productos.nombre as impuesto',
                  'tbl_impuestos_productos2.nombre as servicio',
                  'tbl_impuestos_productos.valor as valor_impuesto',
                  'tbl_impuestos_productos2.valor as valor_servicio',
                   'tbl_puntos_ventas.nombre as punto_venta',
                   'tbl_consumos_extras.punto_de_venta_id',
                   'tbl_consumos_extras.categoria_id',
                   'tbl_consumos_extras.unidad',
                   'tbl_consumos_extras.total_extras_neto',
                   'tbl_consumos_extras.CodProdu',
                   'tbl_consumos_extras.DesProdu',
                   'tbl_consumos_extras.UnmProdu',
                   'tbl_consumos_extras.PUnitario',
                   'tbl_consumos_extras.MtoTotal',
                   'tbl_consumos_extras.PorImpu',
                   'tbl_consumos_extras.PorServi'
          )
   ->where('tbl_consumos_extras.cliente_id', '=', $request->cliente)
        ->where('tbl_consumos_extras.reserva_detalle_id', '=', $request->detalle_reserva)
        ->join('tbl_productos', 'tbl_consumos_extras.producto_id', '=', 'tbl_productos.id')
        ->leftjoin('tbl_impuestos_productos', 'tbl_consumos_extras.impuesto_id', '=', 'tbl_impuestos_productos.id')
        ->leftjoin('tbl_impuestos_productos as tbl_impuestos_productos2', 'tbl_consumos_extras.servicio_id', '=', 'tbl_impuestos_productos2.id')
         ->join('tbl_puntos_ventas', 'tbl_productos.punto_de_venta_id', '=', 'tbl_puntos_ventas.id')
        ->where('tbl_consumos_extras.punto_de_venta_id', '=',  $buscar)
        ->paginate(config('app.pagination'));



           $datos2 = ConsumoExtra::select(
             'tbl_consumos_extras.id',
              'tbl_consumos_extras.producto_id',
               'tbl_consumos_extras.impuesto_id',
               'tbl_consumos_extras.servicio_id',
               'tbl_consumos_extras.reserva_pagador_id',
               'tbl_consumos_extras.cliente_id',
               'tbl_consumos_extras.reserva_detalle_id',
               'tbl_consumos_extras.total_consumo',
                'tbl_consumos_extras.numero_habitacion',
                 'tbl_consumos_extras.fecha',
                 'tbl_productos.nombre as producto',
                  'tbl_impuestos_productos.nombre as impuesto',
                  'tbl_impuestos_productos2.nombre as servicio',
                  'tbl_impuestos_productos.valor as valor_impuesto',
                  'tbl_impuestos_productos2.valor as valor_servicio',
                   'tbl_puntos_ventas.nombre as punto_venta',
                   'tbl_consumos_extras.punto_de_venta_id',
                   'tbl_consumos_extras.categoria_id',
                   'tbl_consumos_extras.unidad',
                   'tbl_consumos_extras.total_extras_neto',
                   'tbl_consumos_extras.CodProdu',
                   'tbl_consumos_extras.DesProdu',
                   'tbl_consumos_extras.UnmProdu',
                   'tbl_consumos_extras.PUnitario',
                   'tbl_consumos_extras.MtoTotal',
                   'tbl_consumos_extras.PorImpu',
                   'tbl_consumos_extras.PorServi'
)
        ->where('tbl_consumos_extras.cliente_id', '=', $request->cliente)
        ->where('tbl_consumos_extras.reserva_detalle_id', '=', $request->detalle_reserva)
        ->join('tbl_productos', 'tbl_consumos_extras.producto_id', '=', 'tbl_productos.id')
        ->leftjoin('tbl_impuestos_productos', 'tbl_consumos_extras.impuesto_id', '=', 'tbl_impuestos_productos.id')
        ->leftjoin('tbl_impuestos_productos as tbl_impuestos_productos2', 'tbl_consumos_extras.servicio_id', '=', 'tbl_impuestos_productos2.id')
         ->join('tbl_puntos_ventas', 'tbl_productos.punto_de_venta_id', '=', 'tbl_puntos_ventas.id')
        ->where('tbl_consumos_extras.punto_de_venta_id', '=',  $buscar)
        ->get();

        $sum_total_consumo = 0;
        foreach ($datos2 as $valor) {
            $sum_total_consumo += $valor['total_consumo'];
        }



 }
        return [
            'pagination' => [
                'total'        => $datos->total(),
                'current_page' => $datos->currentPage(),
                'per_page'     => $datos->perPage(),
                'last_page'    => $datos->lastPage(),
                'from'         => $datos->firstItem(),
                'to'           => $datos->lastItem(),
            ],
            'datos' => $datos, 'sum_total_consumo' => $sum_total_consumo
        ];




    }

    public function eliminar_extras(Request $request)
    {

        $datos = ConsumoExtra::find($request->id);
        $datos->delete();
        return ['datos' => $datos];

    }

    public function factura_pagos(Request $request)
    {


      $cantidad_servicio = 0;
      foreach ($request->facturacion as $factura) {

        $EstadoGrupo = TblReservasGrupo::where('id', '=', $factura['id'])->where('facturado', '=', false)->get();
        foreach ($EstadoGrupo as $grupo) {
            $grupoFactura = TblReservasGrupo::find((int)$grupo['id']);
            $grupoFactura->facturado= true;
            $grupoFactura->save();
        }

        foreach ($factura['pagadores'] as $pagador) {

          PagadorFactura::where('id', $pagador['id_tbl_pagador_factura'])->update(['fecha_facturado' => date('Y-m-d'), 'facturado' => true]);


          $datos = TotalFactura::firstOrNew([
                        'cliente_id' => $pagador['cliente_id'],
                        'grupo_id' => $pagador['id_reservas_grupo'],
                        'fecha' => $pagador['check_in_fecha'],
                        'numero' => $factura['numero'],
                    ]);
          $datos->numero = $factura['numero'];
          $datos->fecha = $pagador['check_in_fecha'];
          $datos->cliente_id = $pagador['cliente_id'];
          $datos->grupo_id = $pagador['id_reservas_grupo'];
          $datos->reserva_detalle_id = $pagador['reservas_detalle_id'];
          $datos->total_a_pagar =  $pagador['total_temp'];
          $datos->factura_id =  $factura['factura_id'];
          $datos->servicio =  $pagador['servicio'];
          $datos->numero_impuesto1 =  $pagador['numero_impuesto1'];
          $datos->numero_impuesto2 =  $pagador['numero_impuesto2'];
          $datos->reserva_id =  $request->reserva;
          $datos->cajero_id =  Auth::user()->id;
          $datos->save();


          $datos2 = PagoTotal::select(DB::raw("SUM(tbl_pagos_totales.total_a_pagar) as total_a_pagar", 'factura_id', 'cliente_id'))
          ->where('reserva_id',$request->reserva)
          ->where('cliente_id', '=', $pagador['cliente_id'])
          ->get();


          foreach ($datos2 as $registrar) {



           $datos3 = TipoPagoTotal::firstOrNew([
                            'cliente_id' => $pagador['cliente_id'],
                            'reserva_id' => $request->reserva,

                        ]);

              $datos3->cliente_id = $pagador['cliente_id'];
              $datos3->factura_id = $factura['factura_id'];
              $datos3->valor_a_pagar = $registrar['total_a_pagar'];
              $datos3->servicio = $pagador['servicio'];
              $datos3->cajero_id =  Auth::user()->id;
              $datos3->reserva_id =  $request->reserva;
              $datos3->save();

          }

        }



      }

         return ['datos' => $datos->factura_id];
    }



    public function total_facturas(Request $request)
    {

        $numero_habitacion = TblHabitacione::find($request->habitacion_id);
        $info = PagoTotal::select(DB::raw('concat_ws(\' \',tbl_clientes.nombre,tbl_clientes.apellido) as cliente'), 'tbl_pagos_totales.factura_id', 'tbl_pagos_totales.cliente_id', 'tbl_pagos_totales.reserva_id')
        ->join('tbl_clientes', 'tbl_pagos_totales.cliente_id', '=', 'tbl_clientes.id')
        ->where('tbl_pagos_totales.reserva_id', '=',$request->reserva_id)
        // ->where('tbl_pagos_totales.numero', '=',$numero_habitacion['numero'])
        ->groupBy(
          'tbl_clientes.nombre',
           'tbl_clientes.apellido',
           'tbl_pagos_totales.factura_id',
            'tbl_pagos_totales.cliente_id',
             'tbl_pagos_totales.reserva_id'
           )->get();

           $facturaIdentificador = '';
           foreach ($info as $key => $temp) {
             $facturaIdentificador = $temp->factura_id;
             $temp->total_a_pagar = $this->totalaPagar($request->reserva_id, $temp->cliente_id);
             $temp->total_pagado = $this->totalPagado($temp->factura_id, $temp->cliente_id);
          }

            $data = $info->where('factura_id', $facturaIdentificador);

      return ['datos' => $data];

    }


    public function totalPagado($reserva_id, $cliente)
    {

            $datos = TipoPagoTotalDetalle::select(DB::raw("SUM(tbl_tipo_pagos_totales_detalles.valor_pagado) as valor_pagado"))->join('tbl_tipo_pagos_totales', 'tbl_tipo_pagos_totales_detalles.tipo_pago_total_id', '=', 'tbl_tipo_pagos_totales.id')
            ->join('tbl_tipo_pagos', 'tbl_tipo_pagos_totales_detalles.tipo_pago_id', '=', 'tbl_tipo_pagos.id')
            ->where('tbl_tipo_pagos_totales.factura_id', '=', $reserva_id)
            ->where('tbl_tipo_pagos_totales.cliente_id', '=', $cliente)
            ->get();


// dd($datos);

      return $datos[0]['valor_pagado'];


    }




    public function totalaPagar($reserva_id, $cliente)
    {


      $datos = PagoTotal::select(DB::raw("SUM(tbl_pagos_totales.total_a_pagar) as total_a_pagar"))
      ->where('reserva_id', $reserva_id)
      ->where('cliente_id', '=', $cliente)
      ->get();

      return $datos[0]['total_a_pagar'];



    }

    public function pagos_facturacion(Request $request)
    {


       $datos = TotalFactura::select('tbl_total_facturas.numero', 'tbl_total_facturas.cliente_id', 'tbl_total_facturas.factura_id',  DB::raw("SUM(tbl_total_facturas.total_a_pagar) as precio_bruto"))
       ->join('tbl_clientes', 'tbl_total_facturas.cliente_id', '=', 'tbl_clientes.id')
       ->where('tbl_total_facturas.reserva_id','=', $request->reserva)

       ->groupBy(
            'tbl_total_facturas.numero',
            'tbl_total_facturas.cliente_id',
            'tbl_total_facturas.factura_id',
            'tbl_clientes.nombre'
        )
      ->get();

      foreach ($datos as $valor) {

          $datos = PagoTotal::firstOrNew([
                        'numero' => $valor['numero'],
                        'cliente_id' => $valor['cliente_id'],
                        'factura_id' => $valor['factura_id'],
                        'reserva_id' =>  $request->reserva,

                    ]);

          $datos->numero = $valor['numero'];
          $datos->factura_id = $valor['factura_id'];
          $datos->cliente_id = $valor['cliente_id'];
          $datos->total_a_pagar = $valor['precio_bruto'];
          $datos->cajero_id = Auth::user()->id;
          $datos->reserva_id = $request->reserva;
          $datos->valor_pagado = 0;
          $datos->save();


      }



      // $TipoPagos = TipoPagoTotal::where('factura_id', '=', $datos->factura_id)->delete();


      // $info = PagoTotal::select(DB::raw('concat_ws(\' \',tbl_clientes.nombre,tbl_clientes.apellido) as cliente'), 'tbl_pagos_totales.numero', 'tbl_pagos_totales.factura_id', 'tbl_pagos_totales.cliente_id', 'tbl_pagos_totales.total_a_pagar', 'tbl_pagos_totales.valor_pagado', 'tbl_pagos_totales.id')->join('tbl_clientes', 'tbl_pagos_totales.cliente_id', '=', 'tbl_clientes.id')->where('tbl_pagos_totales.factura_id', '=', $datos->factura_id)->orderBy('tbl_pagos_totales.numero', 'asc')->get();

      return ['datos' => $datos];



    }

    public function impuesto_ish()
    {

      $datos = TblImpuesto::select('valor')->where('ish', true)->firstOrfail();
      return ['datos' => $datos];
    }


        public function impuesto_servicio()
    {

      $datos = ImpuestoProducto::select('valor')->where('servicio', true)->firstOrfail();
      return ['datos' => $datos];
    }

        public function impuesto_servicio_habitacion()
    {

      $datos = TblImpuesto::select('valor')->where('servicio', true)->firstOrfail();
      return ['datos' => $datos];
    }


      public function impuesto_reserva()
    {

      $datos = TblImpuesto::select('valor')->where('principal', true)->firstOrfail();
      return ['datos' => $datos];
    }

}
