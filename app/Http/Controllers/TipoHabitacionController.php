<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\EsquemaController;
use App\Models\TblHabitacione;
use App\Models\TblReserva;
use App\Models\TblConfig;
use App\Models\TblReservasDetalle;
use App\Models\TblReservaPagador;
use App\Models\TblClienteContacto;
use App\Models\TblCliente;
use App\Models\TblHabitacionesTipo;
use App\Models\TblReservasGrupo;
use App\Models\TblImpuesto;
use App\Models\TblReservasDetalleHuespedes;
use App\Models\TblHabitacionesDetalleEstado;
use App\Models\TblHabitacionesEstado;
use App\TotalFactura;
use App\MensajesFactura;
use App\Factura;
use App\FechaBloqueo;
use App\User;
use App\Iframe;
use App\idioma;
use App\Bitacora;
use App\FacturaDetalle;
use App\PagadorFactura;
use App\ImpuestoFactura;
use App\TipoPago;
use App\TipoPagoPagador;
use App\ImpuestoProducto;
use App\FuenteReserva;
use App\Comentario;
use App\TipoPagoTotal;
use App\PagoTotal;
use App\PrepagoNota;
use App\TipoPagoTotalDetalle;
use App\Inventario;
use App\Imports\ReservasImport;
use DB;
use App\Http\Controllers\ApiController;
use App\ConsumoExtra;
use PGSchema;
use Auth;
use DateTime;
use App\Models\TblHotel;
use SoapClient;
use SoapHeader;
use GuzzleHttp\Client;
use Excel;
use Validator;
//Empezar
class TipoHabitacionController extends Controller
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

      $datosHotel = TblHotel::find(1);
      date_default_timezone_set($datosHotel->zona_horaria);
      $this->middleware(function ($request, $next) {
           $this->projects = Auth::user()->schema;
           PGSchema::switchTo($this->projects);
           return $next($request);
       });
    }


    public function listar()
    {

      $datos = TblHabitacionesTipo::select('id', 'nombre as name')->get();
      return $datos;
    }

    public function Inventario()
    {
        $data = DB::table('tbl_inventarios')->select(
            'tbl_inventarios.id as identificador',
            'tbl_inventarios.tipo_habitacion_id as resource',
            'tbl_inventarios.color as barColor',
            'tbl_inventarios.start',
            'tbl_inventarios.end',
            'tbl_inventarios.disponibilidad as text',
            'tbl_habitaciones_tipo.nombre as tipo_habitacion'

            )
            ->groupBy(
            'tbl_inventarios.id',
            'tbl_inventarios.tipo_habitacion_id',
            'tbl_inventarios.color',
            'tbl_inventarios.start',
            'tbl_inventarios.end',
            'tbl_inventarios.disponibilidad',
            'tbl_habitaciones_tipo.nombre'
            )
        ->join('tbl_habitaciones_tipo', 'tbl_inventarios.tipo_habitacion_id', '=', 'tbl_habitaciones_tipo.id')
        ->get();
        foreach ($data as $key => $temp) {
            unset($temp->barcolor);
            $data[$key]->id = ($key + 1);
        }
        return $data;
    }


  }
