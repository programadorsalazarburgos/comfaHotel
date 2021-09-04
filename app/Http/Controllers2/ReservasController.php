<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\EsquemaController;
use App\Http\Controllers\SaveReservasController;
use App\Http\Controllers\CancelReservasController;
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
use App\ImpuestoTasa;
use App\TasaImpuestoDetalle;
use App\TotalFactura;
use App\Zona;
use App\MensajesFactura;
use App\Factura;
use App\FechaBloqueo;
use App\CuentaReserva;
use App\User;
use App\Iframe;
use App\idioma;
use App\InventarioHabitacion;
use App\Inventario;
use App\Bitacora;
use App\CuentaAbono;
use App\PlanTarifario;
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
use App\Cuenta;
use App\PrepagoNota;
use App\TipoPagoTotalDetalle;
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
use Illuminate\Support\Collection;
//Empezar
class ReservasController extends Controller
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
        $this->middleware(function ($request, $next)
        {
            $this->projects = Auth::user()->schema;
            PGSchema::switchTo($this->projects);
            return $next($request);
        });
    }

    public function tipoHabitaciones()
    {

        $datos = TblHabitacionesTipo::all();
        return ['datos' => $datos];
    }

    public function actualizar_tarifa(Request $request)
    {

        foreach ($request->data as $value)
        {

            $datos = TblReservasDetalle::where('id_reservas_grupo', (int)$value['id_reservas_grupo'])->get();

            foreach ($datos as $dato)
            {

                $actualizar = TblReservasDetalle::find($dato['id']);
                $impuestos = (float)$actualizar->numero_impuesto - (float)$actualizar->numero_impuesto2;
                $result = ((float)$request->tarifa * (float)$impuestos) / 100;
                $precio_bruto = (float)$request->tarifa + (float)$result;
                $actualizar->precio_neto = (float)$request->tarifa;
                $actualizar->precio_total = $precio_bruto;
                $actualizar->save();

            }

            return ['data' => 'Guardado'];

        }

    }

    public function anular_reserva(Request $request)
    {
        $room_type = $this->obtenerRoomTypeNumero($request->data['numero']);
        $actualizarOrbeBloqueo = $this->ActualizarOrbeBloqueoAgregar($room_type, $request->data['check_in_fecha'], $request->data['check_out_fecha']);

        $anularReservaDetalle = TblReservasDetalle::where('id_reservas_grupo', $request->data['id_grupo'])
            ->get();
        foreach ($anularReservaDetalle as $detalle)
        {
            $anular = TblReservasDetalle::find($detalle['id']);
            $anular->delete();
        }
        $anularGrupo = TblReservasGrupo::find($request->data['id_grupo']);
        $anularGrupo->delete();

        for($i=$request->data['check_in_fecha'];$i<$request->data['check_out_fecha'];$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
            $habitacion_false = InventarioHabitacion::whereDate('start', $i)
            ->where('habitacion_id', (int)$request->data['resource'])
            ->update(['disponibilidad' => true ]);
            $descontar_inventario = Inventario::where('tbl_inventarios.tipo_habitacion_id', '=', (int)$request->data['id_habitacion_tipo'])
            ->whereDate('tbl_inventarios.start', $i)
            ->firstOrfail();
            $descontar_inventario->disponibilidad = (int)$descontar_inventario->disponibilidad + 1;
            $descontar_inventario->save();
          }


        return ['data' => true];
    }
    public function anular_reserva_grupo(Request $request)
    {
        $room_type = $this->obtenerRoomTypeTipo($request->tipo_habitacion);
        $actualizarOrbeBloqueo = $this->ActualizarOrbeBloqueoAgregar($room_type, $request->fecha_llegada, $request->fecha_salida);
        $anularReservaDetalle = TblReservasDetalle::where('tbl_reservas_detalle.id_reservas_grupo', (int)$request->grupo)
            ->get();
        foreach ($anularReservaDetalle as $detalle)
        {
            $anular = TblReservasDetalle::find($detalle['id']);
            $anular->delete();
        }
        $anularGrupo = TblReservasGrupo::find($request->grupo);
        $anularGrupo->delete();
        return ['data' => true];
    }

    public function obtenerRoomTypeTipo($tipo_habitacion)
    {
        $datoTipoHabitacion = TblHabitacione::select('tbl_habitaciones_tipo.room_type')->join('tbl_habitaciones_tipo', 'tbl_habitaciones.id_habitacion_tipo', '=', 'tbl_habitaciones_tipo.id')
            ->where('tbl_habitaciones_tipo.id', $tipo_habitacion)->firstOrfail();
        return $datoTipoHabitacion->room_type;
    }

    public function obtenerRoomTypeNumero($numero)
    {
        $datoTipoHabitacion = TblHabitacione::select('tbl_habitaciones_tipo.room_type')->join('tbl_habitaciones_tipo', 'tbl_habitaciones.id_habitacion_tipo', '=', 'tbl_habitaciones_tipo.id')
            ->where('tbl_habitaciones.numero', $numero)->firstOrfail();
        return $datoTipoHabitacion->room_type;
    }

    public function ActualizarOrbeBloqueoAgregar($room_type, $fecha_inicio, $fecha_fin)
    {

        PGSchema::switchTo(Auth::user()->schema);
        $datos = [];
        $datos = TblConfig::select('value')->where('name', 'data_api')
            ->first();
        $datos = (is_null($datos)) ? ['validate' => false, 'value' => false] : ['validate' => true, 'value' => $datos->value];
        $res = (json_decode($datos['value']));
        $user = $res;

        $url = 'https://reservations.orbebooking.com/admin/OAF/AOBA-XML';
        $xmlRequest = '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
        $xmlRequest .= '<soap:Header>
              <HTNGHeader xmlns="http://htng.org/1.1/Header/"></HTNGHeader>
              <soap:Username>' . $user->user . '</soap:Username>
              <soap:Password>' . $user->pass . '</soap:Password>
              </soap:Header>
              <soap:Body>
              <InventoryUpdateRequest TimeStamp="2017-8-22T18:12:16" Version="1.00">
              <INVENTORY HotelCode="' . $user->code . '" HotelName="DIAMOND DEMO">' . "\n			";

        for ($i = $fecha_inicio;$i < $fecha_fin;$i = date("Y-m-d", strtotime($i . "+ 1 days")))
        {

            $xmlRequest .= '<Update Inv_Date="' . $i . '" Quantity="1" Room_Type="' . (string)$room_type . '"  Task="Add"/>';

        }

        $xmlRequest .= '</INVENTORY></InventoryUpdateRequest></soap:Body></soap:Envelope>';
        $res = $this->con($xmlRequest, $url);
        $data = $res->getBody();
        $data = simplexml_load_string($data);

        return ['validate' => true];
    }

    public function cambiar_tarifa_individual(Request $request)
    {

        $reserva_detalle = TblReservasDetalle::find($request->reserva_detalle);
        $reserva_detalle->precio_neto = $request->precio_neto;
        $reserva_detalle->save();
    }

    public function cargar_iframe()
    {

        $datos = Iframe::find(1);
        return ['datos' => $datos['iframe']];

    }

    public function reservas_manuales(Request $request)
    {
        $data = $request->datos;
        switch (gettype($request->datos))
        {
            case 'object':
                $id_reserva = null;
                $this->saveReservaDetailManual($request->datos, $request->cliente_id, $data, $id_reserva, $request->fuente_id);
            break;
            case 'array':
                $res = $request->datos;
                $id_reserva = null;
                for ($i = 0;$i < count($request->datos);$i++)
                {
                    $id_reserva = $this->saveReservaDetailManual($request->datos[$i], $request->cliente_id, $data, $id_reserva, $request->fuente_id);
                }
            break;
        }

    }
    private function saveReservaDetailManual($data_detail, $cliente_id, $data, $id_reserva, $fuente)
    {
      switch (gettype($data))
      {
          case 'object':
              if (!is_null($data))
              {
                  $data = $data;

                  if (is_null($id_reserva))
                  {
                      $total_reserva = 0;
                      $total_huespedes = 0;
                      $fechas_in = [];
                      foreach ($data as $value) {
                      $total_reserva += (float)$value['total_reserva'];
                      $total_huespedes += (int)$value['cantidad_adultos'] + (int)$value['cantidad_ninos'];
                      $fechas_in[] = $value['ingreso'];
                      $fechas_out[] = $value['salida'];
                      }
                      $min = min(array_map('strtotime', $fechas_in));
                      $max = max(array_map('strtotime', $fechas_out));
                      $reserva = new TblReserva();
                      $reserva->id_cliente = $cliente_id;
                      $reserva->check_in_fecha = date('Y-m-j', $min);
                      $reserva->check_out_fecha = date('Y-m-j', $max);
                      $reserva->fuente_reserva_id = $fuente;
                      $reserva->huespedes_cantidad = (int)$total_huespedes;
                      $reserva->precio_total = (int)$total_reserva;
                      $reserva->estado_reserva = 1;
                      $reserva->save();
                      $id_reserva = $reserva->id;
                      $cuenta = new Cuenta();
                      $cuenta->nombre = 'Hospedaje'.'-'.$reserva->id;
                      $cuenta->reserva_id = (int)$id_reserva;
                      $cuenta->save();
                  }
                  //====================CREAR RESERVACION DETALLE////====================//
                  $this->nuevos_grupos_manual($data, $id_reserva, $cliente_id, $data_detail);
                  //====================CREAR RESERVACION DETALLE====================//
                  return $id_reserva;

              }
          break;
          case 'array':
              foreach ($data as $key => $temp)
              {
                  if (is_null($id_reserva))
                  {
                      $total_reserva = 0;
                      $total_huespedes = 0;
                      $fechas_in = [];
                      foreach ($data as $value) {
                      $total_reserva += (float)$value['total_reserva'];
                      $total_huespedes += (int)$value['cantidad_adultos'] + (int)$value['cantidad_ninos'];
                      $fechas_in[] = $value['ingreso'];
                      $fechas_out[] = $value['salida'];
                      }
                      $min = min(array_map('strtotime', $fechas_in));
                      $max = max(array_map('strtotime', $fechas_out));
                      $reserva = new TblReserva();
                      $reserva->id_cliente = $cliente_id;
                      $reserva->check_in_fecha = date('Y-m-j', $min);
                      $reserva->check_out_fecha = date('Y-m-j', $max);
                      $reserva->fuente_reserva_id = $fuente;
                      $reserva->huespedes_cantidad = (int)$total_huespedes;
                      $reserva->precio_total = (int)$total_reserva;
                      $reserva->estado_reserva = 1;
                      $reserva->save();

                      $id_reserva = $reserva->id;
                      $cuenta = new Cuenta();
                      $cuenta->nombre = 'Hospedaje'.'-'.$reserva->id;
                      $cuenta->reserva_id = (int)$id_reserva;
                      $cuenta->save();
                  }
                  //====================CREAR RESERVACION DETALLE////====================//
                  $this->nuevos_grupos_manual($data, $id_reserva, $cliente_id, $data_detail);
                  //====================CREAR RESERVACION DETALLE====================//
                  return $id_reserva;

              }
          break;
      }
    }
    private function nuevos_grupos_manual($data, $id_reserva, $cliente, $data_detail)
    {
        $cuenta = Cuenta::where('reserva_id', (int)$id_reserva)->firstOrfail();
        $Porcentaje_monto_total= DB::select('SELECT "porcentaje_monto_total"()');
        $cantidad_x_persona_adulto = DB::select('SELECT "cantidad_x_persona_adulto"()');
        $cantidad_x_persona_nino = DB::select('SELECT "cantidad_x_persona_nino"()');
        $cantidad_fija_x_habitacion = DB::select('SELECT "cantidad_fija_x_habitacion"()');
        $id_grupo = $this->newGrupoManual($id_reserva, $data, $Porcentaje_monto_total, $cantidad_x_persona_adulto, $cantidad_x_persona_nino, $cantidad_fija_x_habitacion, $data_detail);
        $this->CrearReservaDetallesManual($id_grupo, $data, $data_detail, $id_reserva, $cliente, $Porcentaje_monto_total, $cantidad_x_persona_adulto, $cantidad_x_persona_nino, $cantidad_fija_x_habitacion, $cuenta->id);
    }
    private function CrearReservaDetallesManual($id_grupos, $ROOM_TYPE, $data_detail, $id_reserva, $cliente, $Porcentaje_monto_total, $cantidad_x_persona_adulto, $cantidad_x_persona_nino, $cantidad_fija_x_habitacion, $cuenta_id)
    {

        for($i=$data_detail['ingreso'];$i<$data_detail['salida'];$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
            $reserva = TblReserva::find($id_reserva);
            $date1 = new \DateTime($i);
            $date2 = new \DateTime(date("Y-m-d", strtotime('+1 day', strtotime($i))));
            $diff = $date1->diff($date2);
            $dias = $diff->days;
            $tarifa = 0;
            $decimal = (float)($Porcentaje_monto_total[0]->porcentaje_monto_total/100);
            $habitacion_disponible = InventarioHabitacion::select('habitacion_id')
            ->where('tipo_habitacion_id', '=', (int)$data_detail['tipo_habitacion_id'])
            ->where('disponibilidad', true)
            ->whereDate('start', $i)
            ->orderBy('id', 'asc')
            ->first();
            $id_reservas = TblReservasGrupo::where('id_reservas', '=', $reserva->id)
                ->first();
            $result_adulto = ((int)$data_detail['cantidad_adultos'] * (float)$cantidad_x_persona_adulto[0]->cantidad_x_persona_adulto);
            $result_nino = ((int)$data_detail['cantidad_ninos'] * (float)$cantidad_x_persona_nino[0]->cantidad_x_persona_nino);
            $ReservasDetalle = new TblReservasDetalle();
            $ReservasDetalle->id_reservas_grupo = $id_grupos;
            $ReservasDetalle->id_habitacion_tipo = (int)$data_detail['tipo_habitacion_id'];
            $ReservasDetalle->adultos_cantidad = (int)$data_detail['cantidad_adultos'];
            $ReservasDetalle->ninos_cantidad = (int)$data_detail['cantidad_ninos'];
            $ReservasDetalle->check_in_fecha = $i;
            $ReservasDetalle->check_out_fecha = $data_detail['salida'];
            $ReservasDetalle->id_reserva_detalle_estado_habitacion = 1;
            $ReservasDetalle->id_habitacion = $data_detail['habitacion_id'];
            foreach($data_detail['datos_reserva'] as $key => $value) {
                if ($this->formatDate($value['Fecha']) == $i && $value['id_habitacion'] == $data_detail['habitacion_id']) {
                    $tarifa = $value['Tarifa'];
                }
            }
            $ReservasDetalle->id_reserva_detalle_estado_habitacion = 1;
            $ReservasDetalle->monto_diario = (float)$tarifa;
            $ReservasDetalle->monto_consumos = 0;
            $ReservasDetalle->tarifa_neta = ((float)$tarifa)/(1 + ($Porcentaje_monto_total[0]->porcentaje_monto_total/100));
            $ReservasDetalle->tasa_impuestos = ($ReservasDetalle->check_in_fecha == $data_detail['ingreso']) ? (((float)$ReservasDetalle->tarifa_neta * (float)$decimal) + (float)$cantidad_fija_x_habitacion[0]->cantidad_fija_x_habitacion) + ($result_adulto) + ($result_nino) : ((float)$ReservasDetalle->tarifa_neta * (float)$decimal) + $result_adulto + $result_nino;
            $ReservasDetalle->total_diario = (float)$ReservasDetalle->tarifa_neta + (float)$ReservasDetalle->tasa_impuestos;
            $ReservasDetalle->save();

            $cuenta_reserva = new CuentaReserva();
            $cuenta_reserva->reserva_id = (int)$id_reserva;
            $cuenta_reserva->cuenta_id = (int)$cuenta_id;
            $cuenta_reserva->fecha_hora = $i;
            $cuenta_reserva->habitacion_id = $data_detail['habitacion_id'];
            $cuenta_reserva->concepto = 'Hospedaje';
            $cuenta_reserva->tipo_movimiento = 'H';
            $cuenta_reserva->grupo_id = $ReservasDetalle->id_reservas_grupo;
            $cuenta_reserva->cargo = $ReservasDetalle->tarifa_neta;
            $cuenta_reserva->save();


            if ($Porcentaje_monto_total > 0) {
                $this->SavePorcentajeMontoTotal($ReservasDetalle, $id_reserva, $cuenta_id);
            }
            if ($cantidad_fija_x_habitacion > 0) {
              $this->SaveCantidadFijaHabitacion($ReservasDetalle, $id_reserva, $cuenta_id);
            }
            if ($cantidad_x_persona_adulto > 0) {
              $this->SaveAdulto($ReservasDetalle, $id_reserva, $cuenta_id);
            }
            if ($cantidad_x_persona_nino > 0) {
              $this->SaveNino($ReservasDetalle, $id_reserva, $cuenta_id);
            }


            $habitacion_false = InventarioHabitacion::where('tipo_habitacion_id', '=', (int)$data_detail['tipo_habitacion_id'])
            ->whereDate('start', $i)
            ->where('habitacion_id', (int)$data_detail['habitacion_id'])
            ->update(['disponibilidad' => false ]);
            $descontar_inventario = Inventario::where('tipo_habitacion_id', '=', (int)$data_detail['tipo_habitacion_id'])
            ->whereDate('start', $i)
            ->firstOrfail();
            $descontar_inventario->disponibilidad = (int)$descontar_inventario->disponibilidad - 1;
            $descontar_inventario->save();
          }
    }

    private function newGrupoManual($id_reserva, $data, $Porcentaje_monto_total, $cantidad_x_persona_adulto, $cantidad_x_persona_nino, $cantidad_fija_x_habitacion , $data_detail)
    {

        PGSchema::switchTo(Auth::user()->schema);
        $esquema = Auth::user()->schema;
        $fecha1= new DateTime($this->formatDate($data_detail['ingreso']));
        $fecha2= new DateTime($this->formatDate($data_detail['salida']));
        $dias = $fecha1->diff($fecha2);
        $adulto = ((int)$data_detail['cantidad_adultos'] * (float)$cantidad_x_persona_adulto[0]->cantidad_x_persona_adulto) * ($dias->days);
        $nino = ((int)$data_detail['cantidad_ninos'] * (float)$cantidad_x_persona_nino[0]->cantidad_x_persona_nino) * ($dias->days);
        $valor_neto = ($data_detail['total_reserva'])/(1 + ($Porcentaje_monto_total[0]->porcentaje_monto_total/100));
        $reserva = new TblReservasGrupo();
        $reserva->id_reservas = $id_reserva;
        $reserva->id_reservas_estado = 1;
        $reserva->check_in_fecha = $this->formatDate($data_detail['ingreso']);
        $reserva->check_out_fecha = $this->formatDate($data_detail['salida']);
        $reserva->adultos = (int)$data_detail['cantidad_adultos'];
        $reserva->ninos = (int)$data_detail['cantidad_ninos'];
        $reserva->huespedes_cantidad = (int)$data_detail['cantidad_adultos'] + (int)$data_detail['cantidad_ninos'];
        $reserva->tarifa_promedio_diaria_ci = (float)$data_detail['promedio_reserva'];
        $reserva->valor_neto = (float)$valor_neto;
        $reserva->total_tarifa_ci = ((float)$data_detail['total_reserva']);
        $reserva->total_habitacion = ((float)$data_detail['total_reserva']);
        $reserva->plan_tarifa_id = (int)$data_detail['plan_tarifa_id'];
        $reserva->save();
        return $reserva->id;
    }

    private function CrearReservaDetalles2($id_grupos, $data, $data_detail, $id_reserva, $cliente)
    {

        $reserva = TblReserva::find($id_reserva);

        while (strtotime($reserva->check_in_fecha) < strtotime($reserva->check_out_fecha))
        {
            $this->CreaarReservaDetalle2($data_detail, $reserva, $cliente, $id_grupos);
            $reserva->check_in_fecha = date('Y-m-d', strtotime($reserva->check_in_fecha . "+ 1 days"));
        }
    }

    public function CreaarReservaDetalle2($data_detail, $reserva, $cliente, $id_grupo)
    {

        $iva = array();
        $impuesto2 = array();

        foreach ($data_detail['impuestos'] as $value)
        {

            if ($value['iva'] == true)
            {
                $consultaIva = TblImpuesto::where('principal', true)->firstOrfail();
                $iva[] = $consultaIva['valor'];

            }
            if ($value['impuesto2'] == true)
            {
                $consultaImpuesto2 = TblImpuesto::where('ish', true)->firstOrfail();
                $impuesto2[] = $consultaImpuesto2['valor'];

            }
        }
        if ($iva == [])
        {
            $iva = 0;
        }
        else
        {
            $iva = $iva[0];
        }

        if ($impuesto2 == [])
        {
            $impuesto2 = 0;
        }
        else
        {
            $impuesto2 = $impuesto2[0];
        }

        $id_reservas = TblReservasGrupo::where('id_reservas', '=', $reserva->id)
            ->first();
        $ReservasDetalle = new TblReservasDetalle();
        $ReservasDetalle->id_reservas_grupo = $id_grupo;
        $ReservasDetalle->id_habitacion = $data_detail['habitacion_id'];
        $ReservasDetalle->id_habitacion_tipo = $data_detail['tipo_habitacion_id'];
        $ReservasDetalle->adultos_cantidad = $data_detail['cantidad_adultos'];
        $ReservasDetalle->ninos_cantidad = $data_detail['cantidad_ninos'];
        $ReservasDetalle->infantes_cantidad = 0;
        $ReservasDetalle->numero_impuesto = $data_detail['numero_impuesto'];
        $ReservasDetalle->precio_total = $data_detail['tarifa'];
        $ReservasDetalle->precio_neto = $data_detail['tarifa'];
        $ReservasDetalle->check_in_fecha = $reserva->check_in_fecha;
        $ReservasDetalle->check_out_fecha = $reserva->check_out_fecha;
        $ReservasDetalle->id_reserva_detalle_estado_habitacion = 1;
        $ReservasDetalle->numero_impuesto = $iva;
        $ReservasDetalle->numero_impuesto2 = $impuesto2;
        $ReservasDetalle->servicio = 0;
        $ReservasDetalle->save();

    }

    private function DetalleReserva($data)
    {
        $total = TblReservasDetalle::where('tbl_reservas_grupo.id_reservas', '=', $data->id)
            ->whereNull('tbl_reservas_detalle.id_habitacion')
            ->join('tbl_habitaciones_tipo', 'tbl_reservas_detalle.id_habitacion_tipo', '=', 'tbl_habitaciones_tipo.id')
            ->join('tbl_reservas_grupo', 'tbl_reservas_detalle.id_reservas_grupo', '=', 'tbl_reservas_grupo.id')
            ->count();
        $res = TblReservasDetalle::select('tbl_reservas_detalle.id as id_reservas_detalle', 'tbl_reservas_detalle.id_habitacion', 'tbl_reservas_detalle.id as reserva_detalle',

        'tbl_habitaciones_tipo.id as id_habitacion_tipo', 'tbl_habitaciones_tipo.codigo', 'tbl_habitaciones_tipo.nombre as habitacion_nombre',

        DB::raw('tbl_reservas_detalle.precio_total') , DB::raw('tbl_reservas_detalle.precio_neto') , DB::raw('coalesce(tbl_reservas_detalle.infantes_cantidad,0) AS infantes_cantidad') , DB::raw('coalesce(tbl_reservas_detalle.ninos_cantidad,0) AS ninos_cantidad') , DB::raw('coalesce(tbl_reservas_detalle.adultos_cantidad,0) AS adultos_cantidad') , DB::raw('coalesce(tbl_reservas_detalle.huespedes_cantidad,0) AS huespedes_cantidad'))->where('tbl_reservas_grupo.id_reservas', '=', $data->id)
            ->whereNull('tbl_reservas_detalle.id_habitacion')
            ->join('tbl_habitaciones_tipo', 'tbl_reservas_detalle.id_habitacion_tipo', '=', 'tbl_habitaciones_tipo.id')
            ->join('tbl_reservas_grupo', 'tbl_reservas_detalle.id_reservas_grupo', '=', 'tbl_reservas_grupo.id')
            ->first();
        if (isset($res->id_habitacion_tipo))
        {
            $data->precio_total = number_format(($res->precio_total * $total) , 2, '.', ',');
            $data->id_reservas_detalle = $res->id_reservas_detalle;
            $data->reserva_detalle = $res->reserva_detalle;
            $data->codigo = $res->codigo;
            $data->infantes_cantidad = $res->infantes_cantidad;
            $data->ninos_cantidad = $res->ninos_cantidad;
            $data->adultos_cantidad = $res->adultos_cantidad;
            $data->huespedes_cantidad = $res->huespedes_cantidad;
            return $data;
        }
        else
        {
            return false;
        }
    }
    private function ReservacionesHechas()
    {

        $data = TblReserva::select('tbl_reservas.id as id', 'tbl_fuentes_reservas.nombre as nombre_fuente', 'tbl_reservas_grupo.id as id_grupo', 'tbl_reservas.check_in_fecha as fecha_llegada', 'tbl_reservas.check_out_fecha as fecha_salida', 'tbl_clientes.no_documento AS cliente_documento', DB::raw("concat_ws(' ', tbl_clientes.nombre, tbl_clientes.apellido) AS cliente_nombre") , 'tbl_habitaciones_tipo.id as id_habitacion_tipo', 'tbl_habitaciones_tipo.codigo as habitacion_tipo_codigo', 'tbl_habitaciones_tipo.nombre as habitacion_nombre')
            ->join('tbl_reservas_grupo', 'tbl_reservas.id', '=', 'tbl_reservas_grupo.id_reservas')
            ->join('tbl_reservas_detalle', 'tbl_reservas_grupo.id', '=', 'tbl_reservas_detalle.id_reservas_grupo')
            ->join('tbl_clientes', 'tbl_reservas.id_cliente', '=', 'tbl_clientes.id')
            ->join('tbl_habitaciones_tipo', 'tbl_reservas_detalle.id_habitacion_tipo', '=', 'tbl_habitaciones_tipo.id')
            ->join('tbl_fuentes_reservas', 'tbl_reservas.fuente_reserva_id', '=', 'tbl_fuentes_reservas.id')
            ->whereNull('tbl_reservas_detalle.id_habitacion')
            ->where('tbl_reservas_grupo.id_reservas_estado', '=', 1)
            ->where('tbl_reservas.estado_reserva', '=', 1)
            ->groupBy('tbl_reservas.id', 'tbl_fuentes_reservas.nombre', 'tbl_reservas_grupo.id', 'tbl_reservas_grupo.id_reservas', 'tbl_reservas.check_in_fecha', 'tbl_reservas.check_out_fecha', 'tbl_clientes.no_documento', 'tbl_clientes.nombre', 'tbl_clientes.apellido', 'tbl_habitaciones_tipo.id', 'tbl_habitaciones_tipo.codigo', 'tbl_habitaciones_tipo.nombre')
            ->whereNull('tbl_reservas_grupo.deleted_at')
            ->orderBy('tbl_reservas.id', 'desc')
            ->get();

        $res = [];
        foreach ($data as $temp)
        {
            $temp = $this->DetalleReserva($temp);
            if ($temp)
            {
                $res[] = $temp;
            }
        }
        return $res;
    }
    private function descripcion($data)
    {
        $html = '<div>
            <div class="bold">
            ' . $data->check_in_fecha . '
            </div>
            <div class="larger">
                Pagador:&nbsp;<span class="accent">' . $data->nombre . ' ' . $data->apellido . '</span>
            </div>
            <div style="max-width: 250px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap">
                Clientes:&nbsp;<span class="accent">' . $data->nombre . ' ' . $data->apellido . '</span>
            </div>
            <!--<div>' . $data->dias . '</div>-->
            <div>' . date('Y-m-d', strtotime($data->check_in_fecha)) . ' - ' . date('Y-m-d', strtotime($data->check_out_fecha)) . ' (' . $data->dias . ')</div>
            <div>' . $data->habitacion_numero . ' ' . $data->habitacion_tipo . '</div>
            <div>' . number_format($data->habitacion_precio_total) . ' USD</div>
            <div class="bold">pms/phone</div>
        </div>';
        return $html;
    }
    private function VerHabitaciones()
    {
        $data = TblHabitacione::with('tbl_habitaciones_tipo')->OrderBy('numero')
            ->get();
        $Res = array();
        foreach ($data as $i => $temp)
        {
            $Res[] = ['id' => $temp->id, "name" => $temp->numero . " - " . $temp
                ->tbl_habitaciones_tipo->nombre, "desc" => '<label class="asd asd2"></label><label class="asd asd1"></label>', "values" => $this->VerReservasHabitacion($temp->id) ];
        }
        return $Res;
    }
    private function dia($fecha)
    {
        $dia['Monday'] = "Lunes";
        $dia['Tuesday'] = "Martes";
        $dia['Wednesday'] = "Miércoles";
        $dia['Thursday'] = "Jueves";
        $dia['Friday'] = "Viernes";
        $dia['Saturday'] = "Sábado";
        $dia['Sunday'] = "Domingo";
        return $dia[\date('l', strtotime($fecha)) ];
    }
    private function detalles($data, $id)
    {
        $detalles = array();
        $detalles['regalo'] = '';
        $detalles['cantidad_huespedes'] = $data->cantidad_huespedes;
        $detalles['nombre_tipo'] = $data->nombre_tipo;
        $detalles['nombre_fuente'] = $data->nombre_fuente;
        $detalles['numero'] = $data->numero;
        $detalles['id_cliente'] = $data->id_cliente;
        $detalles['cliente'] = $data->nombre . ' ' . $data->apellido;
        $detalles['direccion'] = $data->calle_residencia;
        $detalles['zipcode'] = $data->codigo_postal_residencia;
        $detalles['ciudad'] = '-' . $data->nombre_departamento;
        $detalles['pais'] = $data->nombre_pais;
        $detalles['language'] = 'Español';
        $detalles['llegada_fecha'] = $data->check_in_fecha;
        $detalles['llegada_fecha_dia'] = $this->dia($data->check_in_fecha);
        $detalles['salida'] = $data->check_out_fecha;
        $detalles['salida_dia'] = $this->dia($data->check_out_fecha);
        $detalles['noches'] = $data->dias;
        $detalles['habitacion_tipo'] = $data->habitacion_tipo;
        return $detalles;
    }
    private function VerReservasHabitacion($id_habitacion)
    {
        $data = TblReserva::select(
            'tbl_clientes.nombre', 
            'tbl_clientes.id as id_cliente',
            'tbl_clientes.apellido',
            'tbl_clientes.calle_residencia',
            'tbl_clientes.codigo_postal_residencia',
            'tbl_reservas.id',
            'tbl_reservas_detalle.id as id_reserva',
            'tbl_reservas.check_in_fecha',
            'tbl_reservas.check_out_fecha',
            'tbl_reservas_detalle.habitacion_precio_total',
            'tbl_reservas.color',
            'tbl_reservas_detalle.requisitos',
            'tbl_reservas_detalle.comentarios',
            'tbl_reservas_detalle.huespedes_cantidad',
            'tbl_habitaciones_tipo.nombre as habitacion_tipo',
            'tbl_habitaciones.numero as habitacion_numero',
            'tbl_paises.nombre_pais',
            'tbl_departamentos.nombre_departamento'
            )->where('tbl_reservas_detalle.id_habitacion', '=', $id_habitacion)->leftJoin('tbl_clientes', 'tbl_reservas.id_cliente', '=', 'tbl_clientes.id')
            ->leftJoin('tbl_reservas_detalle', 'tbl_reservas.id', '=', 'tbl_reservas_detalle.id_reservas')
            ->leftJoin('tbl_habitaciones', 'tbl_reservas_detalle.id_habitacion', '=', 'tbl_habitaciones.id')
            ->leftJoin('tbl_habitaciones_tipo', 'tbl_habitaciones.id_habitacion_tipo', '=', 'tbl_habitaciones_tipo.id')
            ->leftJoin('tbl_paises', 'tbl_clientes.id_pais_residencia', '=', 'tbl_paises.id')
            ->leftJoin('tbl_departamentos', 'tbl_clientes.id_departamento_residencia', '=', 'tbl_departamentos.id')
            ->get();
        $res = [];
        foreach ($data as $i => $temp)
        {
            $de = $temp->check_in_fecha;
            $hasta = $temp->check_out_fecha;
            $datetime1 = new \DateTime($de);
            $datetime2 = new \DateTime($hasta);
            $interval = $datetime1->diff($datetime2);
            $temp->dias = $interval->format('%a');
            $res[] = ['from' => "/Date(" . strtotime($de) . "000)/", 'to' => "/Date(" . strtotime($hasta) . "000)/", 'fecha_from' => $de, 'fecha_to' => $hasta, 'id' => $temp->id_reserva, 'label' => $temp->nombre . ' ' . $temp->apellido, 'desc' => $this->descripcion($temp) , 'dias' => $temp->dias, 'customClass' => $temp->color, 'requisitos' => $temp->requisitos, 'comentarios' => $temp->comentarios, 'detalles' => $this->detalles($temp, $temp->id) ];
        }
        return $res;
    }
    public function VerReservacionesActuales()
    {
        $data = $this->VerHabitaciones();
        return json_encode($data, 128);
    }
    public function VerReservasChanel()
    {
        return response()->json($this->ReservacionesHechas());
    }

    public function SaveComentarios($id, Request $request)
    {
        try
        {
            $data = TblReservasDetalle::find($id);
            $data->requisitos = $request->input('requisitos_especiales');
            $data->comentarios = $request->input('comentarios');
            $data->Save();
            return response()
                ->json(['validate' => $data->id === (int)$id, 'id' => $data->id, 'msj' => null]);
        }
        catch(\Exception $e)
        {
            return response()->json(['validate' => false, 'id' => $id, 'msj' => $e->getMessage() ]);
        }
    }

    private function consultardisponibilidad($fecha, $habitaciones)
    {
        $habitaaciones = TblReservasDetalle::select(DB::raw('count(*) as total'))->join('tbl_reservas', 'tbl_reservas_detalle.id_reservas', '=', 'tbl_reservas.id')
            ->where(DB::raw('date(\'' . $fecha . '\')') , '>=', DB::raw('date(`tbl_reservas`.`check_in_fecha`)'))->where(DB::raw('date(\'' . $fecha . '\')') , '<', DB::raw('date(`tbl_reservas`.`check_out_fecha`)'))->first();

        return $habitaciones - $habitaaciones->total > 0;
    }
    public function habitacionesDisponibles()
    {
        $fecha_actual = date('Y-m-d');
        $res = array();
        $cuantas_habitaciones_hay = TblHabitacione::select(DB::raw('count(*) as total'))->first();
        for ($i = 0;$i < 365;$i++)
        {
            $temp = $this->consultardisponibilidad($fecha_actual, $cuantas_habitaciones_hay->total);
            $res[] = ['fecha' => $fecha_actual, 'disponible' => $temp];
            $fecha_actual = date("Y-m-d", strtotime($fecha_actual . "+ 1 days"));
        }
        return $res;
    }
    private function formatDate($date)
    {
        $date = (string)$date;
        $date = \date('Y-m-d', strtotime($date));
        return ($date);
    }

    function isHomogenous($arr) {
        $firstValue = current($arr);
        foreach ($arr as $val) {
            if ($firstValue !== $val) {
                return false;
            }
        }
        return true;
    }

    private function habDisponibles($tipo_habitacion_id, $llegada, $salida, $dias)
    {

        $habitacion_disponible = InventarioHabitacion::select('habitacion_id')
        ->where('tipo_habitacion_id', '=', $tipo_habitacion_id)
        ->where('disponibilidad', true)
        ->whereDate('start', $llegada)
        ->orderBy('id', 'asc')
        ->get();

        $cantidadHab1 = [];
        foreach ($habitacion_disponible as $value) {
           $cantidadHab2 = [];
           for($i=$this->formatDate($llegada);$i<$this->formatDate($salida);$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
            $habitacion = InventarioHabitacion::select('habitacion_id')
            ->where('tipo_habitacion_id', '=', $tipo_habitacion_id)
            ->where('habitacion_id', '=', (int)$value->habitacion_id)
            ->where('disponibilidad', true)
            ->whereDate('start', $i)
            ->orderBy('id', 'asc')
            ->first();
            if ($habitacion !=null) {
                $cantidadHab2[] = $habitacion->habitacion_id;
            }
           }
           if ($dias === count($cantidadHab2) && $this->isHomogenous($cantidadHab2) == true) {
            $cantidadHab1[] = $cantidadHab2[0];
           }

        }
        if(count($cantidadHab1) > 0)
        {
            $habitacion_asignar = $cantidadHab1[0];
        }
        else
        {
            $habitacion_asignar = null;
        }

        return $habitacion_asignar;
    }
    private function saveReservaDetail($data_detail, $cliente_id, $data, $id_reserva, $BookingChannel, $RequestorID)
    {
        switch (gettype($data->ROOM_TYPES->ROOM_TYPE))
        {
            case 'object':

            $sum = 0.0;
            $date1 = new \DateTime($data_detail->Arrival);
            $date2 = new \DateTime($data_detail->Departure);
            $diff = $date1->diff($date2);
            $dias = $diff->days;
            $habitaciones = [];
            $dataHaitaciones = TblHabitacionesTipo::where('nombre', $data->ROOM_TYPES->ROOM_TYPE->Type_Description)->firstOrFail();
            $habitaciones[] =  $dataHaitaciones->id;
            $collection = new Collection();
            foreach($habitaciones as $item){
            $collection->push((object)['tipo_habitacion_id' => $item]);
            }

            foreach ($collection as $key => $temp)
            {
                 $collection[$key]->habitacion_id = $this->habDisponibles($temp->tipo_habitacion_id, $data_detail->Arrival, $data_detail->Departure, $dias);
            }

                if (!is_null($data->ROOM_TYPES->ROOM_TYPE))
                {
                    
                        if ($data->Action == "Cancelled") {

                            $reserva = new CancelReservasController();
							$reserva->cancelacionReserva($data_detail, $cliente_id, $data, $id_reserva, $BookingChannel, $RequestorID, $collection);
                        }

                        if ($data->Action == "Create") {
                            
                            $reserva = new SaveReservasController();
                            $reserva->crearReserva($data_detail, $cliente_id, $data, $id_reserva, $BookingChannel, $RequestorID, $collection);
                        }


                        if ($data->Action == "Modify") {

                            $reserva = new ModifyReservasController();
                            $reserva->modificarReserva($data_detail, $cliente_id, $data, $id_reserva, $BookingChannel, $RequestorID, $collection);
                           
                        }  
                }

            break;
            case 'array':
                $sum = 0.0;
                $date1 = new \DateTime($data_detail->Arrival);
                $date2 = new \DateTime($data_detail->Departure);
                $diff = $date1->diff($date2);
                $dias = $diff->days;

                $habitaciones = [];
                foreach ($data->ROOM_TYPES->ROOM_TYPE as $value) {
                    $dataHaitaciones = TblHabitacionesTipo::where('nombre', $value->Type_Description)->firstOrFail();
                    $habitaciones[] = $dataHaitaciones->id;
                }

                $collection = new Collection();
                foreach($habitaciones as $item){
                $collection->push((object)['tipo_habitacion_id' => $item]);
                }

                foreach ($collection as $key => $temp)
                {
                     $collection[$key]->habitacion_id = $this->habDisponibles($temp->tipo_habitacion_id, $data_detail->Arrival, $data_detail->Departure, $dias);
                }

                foreach ($data
                    ->ROOM_TYPES->ROOM_TYPE as $key => $temp)
                {
                  $sum += $temp->Average_Rate * $dias;
                }

                foreach ($data
                    ->ROOM_TYPES->ROOM_TYPE as $key => $temp)
                {

                    $fuente = FuenteReserva::select('id')->where('id_ota', '=', $RequestorID)->first();
                    if (is_null($id_reserva))
                    {

                        if ($data->Action == "Cancelled") {

                            $reserva = new CancelReservasController();
							$reserva->cancelacionReservaTemp($data_detail, $cliente_id, $data, $id_reserva, $BookingChannel, $RequestorID, $collection, $temp);
                        }

                        if ($data->Action == "Create") {
                            
                            $reserva = new SaveReservasController();
                            $reserva->crearReservaTmp($data_detail, $cliente_id, $data, $id_reserva, $BookingChannel, $RequestorID, $collection, $temp);
                        }

                        if ($data->Action == "Modify") {

                            $reserva = new ModifyReservasController();
                            $reserva->modificarReservaTmp($data_detail, $cliente_id, $data, $id_reserva, $BookingChannel, $RequestorID, $collection, $temp);                           
                        }  

                    }

                }
            break;
        }

    }
    public function CrearReservaciones($data)
    {


        //======================_________CREAR Y BUSCAR CLIENTE======================//
        $cliente = TblCliente::firstOrNew(['email' => $data->Global_email]);
        if (is_null($cliente->nombre));
        {
            $cliente->nombre = $data->Global_Name;
            $cliente->apellido = $data->Global_Surname;
            $cliente->id_clientes_tipo = 1;
            $cliente->save();
        }
        //======================CREAR Y BUSCAR CLIENTE======================//
        //========================CREAR RESERVACION========================//
        switch (gettype($data
            ->ROOM_TYPES
            ->ROOM_TYPE))
        {

            case 'object':

                $id_reserva = null;
                $this->saveReservaDetail($data
                    ->ROOM_TYPES->ROOM_TYPE, $cliente->id, $data, $id_reserva, $data
                    ->SOURCE->BookingChannel, $data
                    ->SOURCE
                    ->RequestorID);
            break;
            case 'array':
                $res = $data
                    ->ROOM_TYPES->ROOM_TYPE;
                $id_reserva = null;
                for ($i = 0;$i < count($data
                    ->ROOM_TYPES
                    ->ROOM_TYPE);$i++)
                {

                    // dd($data->ROOM_TYPES->ROOM_TYPE[$i]);
                    $id_reserva = $this->saveReservaDetail($data
                        ->ROOM_TYPES
                        ->ROOM_TYPE[$i], $cliente->id, $data, $id_reserva, $data
                        ->SOURCE->BookingChannel, $data
                        ->SOURCE
                        ->RequestorID);
                }
            break;
        }
        //========================CREAR RESERVACION========================//

    }
    private function CrearReservaDetalles($id_grupos, $ROOM_TYPE, $id_reserva, $cliente, $Porcentaje_monto_total, $cantidad_x_persona_adulto, $cantidad_x_persona_nino, $cantidad_fija_x_habitacion, $cuenta_id, $collection, $accion)
    {

        $reserva = TblReserva::find($id_reserva);
        $precio_total = (double)$ROOM_TYPE->Average_Rate;
        $date1 = new \DateTime($reserva->check_in_fecha);
        $date2 = new \DateTime($reserva->check_out_fecha);
        $llegada = $reserva->check_in_fecha;
        $diff = $date1->diff($date2);
        $dias = $diff->days;
        $precio = $precio_total;
        while (strtotime($reserva->check_in_fecha) < strtotime($reserva->check_out_fecha))
        {
            $this->CreaarReservaDetalle($ROOM_TYPE, $reserva, $precio, $cliente, $id_grupos, $Porcentaje_monto_total, $cantidad_x_persona_adulto, $cantidad_x_persona_nino, $cantidad_fija_x_habitacion, $cuenta_id, $llegada, $collection, $accion);
            $reserva->check_in_fecha = date('Y-m-d', strtotime($reserva->check_in_fecha . "+ 1 days"));
        }
    }
    private function newGrupo($id_reserva, $data, $Porcentaje_monto_total, $cantidad_x_persona_adulto, $cantidad_x_persona_nino, $cantidad_fija_x_habitacion)
    {

        $plan_tarifario = PlanTarifario::where('codigo_reservas', $data->Plan_Code)->firstOrfail();
        PGSchema::switchTo(Auth::user()->schema);
        $esquema = Auth::user()->schema;
        $fecha1= new DateTime($this->formatDate($data->Arrival));
        $fecha2= new DateTime($this->formatDate($data->Departure));
        $dias = $fecha1->diff($fecha2);
        $adulto = ((int)$data->Adults * (float)$cantidad_x_persona_adulto[0]->cantidad_x_persona_adulto) * ($dias->days);
        $nino = ((int)$data->Children * (float)$cantidad_x_persona_nino[0]->cantidad_x_persona_nino) * ($dias->days);
        $valor_neto = ((float)$data->Average_Rate * (int)$dias->days)/(1 + ($Porcentaje_monto_total[0]->porcentaje_monto_total/100));
        $reserva = new TblReservasGrupo();
        $reserva->id_reservas = $id_reserva;
        $reserva->id_reservas_estado = 1;
        $reserva->check_in_fecha = $this->formatDate($data->Arrival);
        $reserva->check_out_fecha = $this->formatDate($data->Departure);
        $reserva->adultos = (int)$data->Adults;
        $reserva->ninos = (int)$data->Children;
        $reserva->huespedes_cantidad = (int)$data->Adults + (int)$data->Children + (int)$data->Infants;
        $reserva->tarifa_promedio_diaria_ci = (float)$data->Average_Rate;
        $reserva->valor_neto = (float)$valor_neto;
        $reserva->total_tarifa_ci = ((float)$data->Average_Rate * (int)$dias->days) + ((float)$cantidad_fija_x_habitacion[0]->cantidad_fija_x_habitacion) + ($adulto) + ($nino);
        $reserva->total_habitacion = ((float)$data->Average_Rate * (int)$dias->days) + ((float)$cantidad_fija_x_habitacion[0]->cantidad_fija_x_habitacion) + ($adulto) + ($nino);
        $reserva->plan_tarifa_id = (int)$plan_tarifario->id;
        $reserva->save();

        return $reserva->id;
    }


    public function nuevos_grupos($data, $id_reserva, $cliente, $collection, $accion)
    {

      $cuenta = Cuenta::where('reserva_id', (int)$id_reserva)->firstOrfail();
      $Porcentaje_monto_total= DB::select('SELECT "porcentaje_monto_total"()');
      $cantidad_x_persona_adulto = DB::select('SELECT "cantidad_x_persona_adulto"()');
      $cantidad_x_persona_nino = DB::select('SELECT "cantidad_x_persona_nino"()');
      $cantidad_fija_x_habitacion = DB::select('SELECT "cantidad_fija_x_habitacion"()');
      $id_grupo = $this->newGrupo($id_reserva, $data, $Porcentaje_monto_total, $cantidad_x_persona_adulto, $cantidad_x_persona_nino, $cantidad_fija_x_habitacion);
      $this->CrearReservaDetalles($id_grupo, $data, $id_reserva, $cliente, $Porcentaje_monto_total, $cantidad_x_persona_adulto, $cantidad_x_persona_nino, $cantidad_fija_x_habitacion, $cuenta->id, $collection, $accion);

    }

    public function CreaarReservaDetalle($ROOM_TYPE, $reserva, $precio, $cliente, $id_grupo, $Porcentaje_monto_total, $cantidad_x_persona_adulto, $cantidad_x_persona_nino, $cantidad_fija_x_habitacion, $cuenta_id, $llegada, $collection, $accion)
    {
        $decimal = (float)($Porcentaje_monto_total[0]->porcentaje_monto_total/100);
        $id = TblHabitacionesTipo::select('id')->where('codigo', '=', $ROOM_TYPE->Type_Code)
            ->first();
        $date1 = new \DateTime($llegada);
        $date2 = new \DateTime($reserva->check_out_fecha);
        $diff = $date1->diff($date2);
        $dias = $diff->days;
        $dataHab = $collection->firstWhere('tipo_habitacion_id', $id->id);
        $habitacion_asignar = $dataHab->habitacion_id;
        $id_reservas = TblReservasGrupo::where('id_reservas', '=', $reserva->id)
            ->first();
        $result_adulto = ((int)$ROOM_TYPE->Adults * (float)$cantidad_x_persona_adulto[0]->cantidad_x_persona_adulto);
        $result_nino = ((int)$ROOM_TYPE->Children * (float)$cantidad_x_persona_nino[0]->cantidad_x_persona_nino);
        $ReservasDetalle = new TblReservasDetalle();
        $ReservasDetalle->id_reservas_grupo = $id_grupo;
        $ReservasDetalle->id_habitacion_tipo = $id->id;
        $ReservasDetalle->adultos_cantidad = $ROOM_TYPE->Adults;
        $ReservasDetalle->ninos_cantidad = $ROOM_TYPE->Children;
        $ReservasDetalle->infantes_cantidad = $ROOM_TYPE->Infants;
        $ReservasDetalle->check_in_fecha = $reserva->check_in_fecha;
        $ReservasDetalle->check_out_fecha = $reserva->check_out_fecha;
        if ($accion == "Cancelled") {
          $ReservasDetalle->id_reserva_detalle_estado_habitacion = 6;
        }
        if ($accion != "Cancelled") {
          $ReservasDetalle->id_reserva_detalle_estado_habitacion = 1;
        }
        if ($habitacion_asignar !=null) {
          $ReservasDetalle->id_habitacion = (int)$habitacion_asignar;
        }
        if ($habitacion_asignar ==null) {
          $ReservasDetalle->id_habitacion = null;
        }
        $ReservasDetalle->id_reserva_detalle_estado_habitacion = 1;
        $ReservasDetalle->monto_consumos = 0;
        $ReservasDetalle->monto_diario = $ROOM_TYPE->Average_Rate;
        $ReservasDetalle->tarifa_neta = ((float)$ROOM_TYPE->Average_Rate)/(1 + ($Porcentaje_monto_total[0]->porcentaje_monto_total/100));
        $ReservasDetalle->tasa_impuestos = ($ReservasDetalle->check_in_fecha == $ROOM_TYPE->Arrival) ? (((float)$ReservasDetalle->tarifa_neta * (float)$decimal) + (float)$cantidad_fija_x_habitacion[0]->cantidad_fija_x_habitacion) + ($result_adulto) + ($result_nino) : ((float)$ReservasDetalle->tarifa_neta * (float)$decimal) + $result_adulto + $result_nino;
        $ReservasDetalle->total_diario = (float)$ReservasDetalle->tarifa_neta + (float)$ReservasDetalle->tasa_impuestos;
        $ReservasDetalle->save();

        if ($habitacion_asignar !=null) {
        $cuenta_reserva = new CuentaReserva();
        $cuenta_reserva->reserva_id = (int)$reserva->id;
        $cuenta_reserva->cuenta_id = (int)$cuenta_id;
        $cuenta_reserva->fecha_hora = $reserva->check_in_fecha;
        $cuenta_reserva->habitacion_id = (int)$habitacion_asignar;
        $cuenta_reserva->concepto = 'Hospedaje';
        $cuenta_reserva->tipo_movimiento = 'H';
        $cuenta_reserva->grupo_id = $ReservasDetalle->id_reservas_grupo;
        $cuenta_reserva->cargo = $ReservasDetalle->tarifa_neta;
        $cuenta_reserva->save();

        if ($Porcentaje_monto_total > 0) {
            $this->SavePorcentajeMontoTotal($ReservasDetalle, (int)$reserva->id, $cuenta_id);
        }
        if ($cantidad_fija_x_habitacion > 0) {
          $this->SaveCantidadFijaHabitacion($ReservasDetalle, (int)$reserva->id, $cuenta_id);
        }
        if ($cantidad_x_persona_adulto > 0) {
          $this->SaveAdulto($ReservasDetalle, (int)$reserva->id, $cuenta_id);
        }
        if ($cantidad_x_persona_nino > 0) {
          $this->SaveNino($ReservasDetalle, (int)$reserva->id, $cuenta_id);
        }

        if ($accion == "Create" || $accion == "Modify") {
        $habitacion_false = InventarioHabitacion::where('tipo_habitacion_id', '=', $id->id)
        ->whereDate('start', $reserva->check_in_fecha)
        ->where('habitacion_id', (int)$habitacion_asignar)
        ->update(['disponibilidad' => false ]);
        $descontar_inventario = Inventario::where('tipo_habitacion_id', '=', $id->id)
        ->whereDate('start', $reserva->check_in_fecha)
        ->firstOrfail();
        $descontar_inventario->disponibilidad = (int)$descontar_inventario->disponibilidad - 1;
        $descontar_inventario->save();
        }
      }
    }
    public function SavePorcentajeMontoTotal($data, $id_reserva, $cuenta_id)
    {

      $Porcentaje_monto_total = ImpuestoTasa::where('tbl_impuestos_tasas.formato_id', '=', 1)->where('tbl_impuestos_tasas.estado', true)->get();
      foreach ($Porcentaje_monto_total as $valor) {
        $data_detalle = TasaImpuestoDetalle::firstOrNew(['reserva_detalle_id' => $data->id, ]);
        $data_detalle->reserva_detalle_id = $data->id;
        $data_detalle->impuesto_tasa_id = $valor['id'];
        $data_detalle->save();

        $cuenta_reserva = new CuentaReserva();
        $cuenta_reserva->reserva_id = (int)$id_reserva;
        $cuenta_reserva->cuenta_id = (int)$cuenta_id;
        $cuenta_reserva->fecha_hora = $data->check_in_fecha;
        $cuenta_reserva->habitacion_id = $data->id_habitacion;
        $cuenta_reserva->concepto = $valor['nombre'].'/'.$valor['valor'];
        $cuenta_reserva->tipo_movimiento = 'H';
        $cuenta_reserva->grupo_id = $data->id_reservas_grupo;
        $cuenta_reserva->cargo = (float)$data->tarifa_neta * (((float)$valor['valor']/100));
        $cuenta_reserva->save();
      }
    }
    public function SaveCantidadFijaHabitacion($data, $id_reserva, $cuenta_id)
    {
      $Porcentaje_monto_total = ImpuestoTasa::where('tbl_impuestos_tasas.formato_id', '=', 2)->where('tbl_impuestos_tasas.estado', true)->get();
      foreach ($Porcentaje_monto_total as $valor) {
        $data_detalle = TasaImpuestoDetalle::firstOrNew(['reserva_detalle_id' => $data->id, ]);
        $data_detalle->reserva_detalle_id = $data->id;
        $data_detalle->impuesto_tasa_id = $valor['id'];
        $data_detalle->save();

        $cuenta_reserva = new CuentaReserva();
        $cuenta_reserva->reserva_id = (int)$id_reserva;
        $cuenta_reserva->cuenta_id = (int)$cuenta_id;
        $cuenta_reserva->fecha_hora = $data->check_in_fecha;
        $cuenta_reserva->habitacion_id = $data->id_habitacion;
        $cuenta_reserva->concepto = $valor['nombre'].'/'.$valor['valor'];
        $cuenta_reserva->tipo_movimiento = 'H';
        $cuenta_reserva->grupo_id = $data->id_reservas_grupo;
        $cuenta_reserva->cargo = (float)$valor['valor'];
        $cuenta_reserva->save();
      }
    }
    public function SaveAdulto($data, $id_reserva, $cuenta_id)
    {
      $Porcentaje_monto_total = ImpuestoTasa::where('tbl_impuestos_tasas.formato_id', '=', 3)->where('tbl_impuestos_tasas.estado', true)->get();
      foreach ($Porcentaje_monto_total as $valor) {
        $data_detalle = TasaImpuestoDetalle::firstOrNew(['reserva_detalle_id' => $data->id, ]);
        $data_detalle->reserva_detalle_id = $data->id;
        $data_detalle->impuesto_tasa_id = $valor['id'];
        $data_detalle->save();

        $cuenta_reserva = new CuentaReserva();
        $cuenta_reserva->reserva_id = (int)$id_reserva;
        $cuenta_reserva->cuenta_id = (int)$cuenta_id;
        $cuenta_reserva->fecha_hora = $data->check_in_fecha;
        $cuenta_reserva->habitacion_id = $data->id_habitacion;
        $cuenta_reserva->concepto = $valor['nombre'].'/'.$valor['valor'];
        $cuenta_reserva->tipo_movimiento = 'H';
        $cuenta_reserva->grupo_id = $data->id_reservas_grupo;
        $cuenta_reserva->cargo = (float)$valor['valor'];
        $cuenta_reserva->save();

      }
    }
    public  function SaveNino($data,  $id_reserva, $cuenta_id)
    {
      $Porcentaje_monto_total = ImpuestoTasa::where('tbl_impuestos_tasas.formato_id', '=', 4)->where('tbl_impuestos_tasas.estado', true)->get();
      foreach ($Porcentaje_monto_total as $valor) {
        $data_detalle = TasaImpuestoDetalle::firstOrNew(['reserva_detalle_id' => $data->id, ]);
        $data_detalle->reserva_detalle_id = $data->id;
        $data_detalle->impuesto_tasa_id = $valor['id'];
        $data_detalle->save();

        $cuenta_reserva = new CuentaReserva();
        $cuenta_reserva->cuenta_id = (int)$cuenta_id;
        $cuenta_reserva->fecha_hora = $data->check_in_fecha;
        $cuenta_reserva->habitacion_id = $data->id_habitacion;
        $cuenta_reserva->concepto = $valor['nombre'].'/'.$valor['valor'];
        $cuenta_reserva->tipo_movimiento = 'H';
        $cuenta_reserva->cargo = (float)$valor['valor'];
        $cuenta_reserva->grupo_id = $data->id_reservas_grupo;
        $cuenta_reserva->save();

      }
    }
    public function ReservasCompletas(Request $request)
    {
        $data = TblReservasGrupo::where('id_reservas', '=', $request->id_reservas)
            ->where('id_reservas_estado', '!=', '2')
            ->count();
        return ['validate' => ($data == 0) ];
    }

    public function ReservasRealizadas(Request $request)
    {

        $datosHotel = TblHotel::find(1);
        date_default_timezone_set($datosHotel->zona_horaria);
        $fecha_actual = date("Y-m-d");
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if ($buscar == '')
        {

            $datos = DB::table('tbl_reservas')->select(
                'tbl_reservas.created_at',
                'tbl_reservas.id as id_reservas',
                'tbl_habitaciones.numero as numero',
                'tbl_habitaciones_tipo.nombre as nombre_tipo',
                'tbl_reservas.id_cliente',
                'tbl_reservas.precio_total',
                'tbl_clientes.nombre',
                'tbl_clientes.apellido',
                'tbl_reservas_grupo.check_in_fecha',
                'tbl_reservas_detalle.precio_neto',
                'tbl_reservas_detalle.check_out_fecha',
                'tbl_reservas_detalle.adultos_cantidad',
                'tbl_reservas_detalle.ninos_cantidad',
                'tbl_reservas_detalle.infantes_cantidad',
                'tbl_fuentes_reservas.nombre as nombre_fuente',
                 DB::raw('(tbl_reservas_detalle.adultos_cantidad+tbl_reservas_detalle.ninos_cantidad+tbl_reservas_detalle.infantes_cantidad) AS huespedes_cantidad') ,
                'tbl_reservas_detalle.id_habitacion_tipo',
                'tbl_reservas_detalle.id_habitacion as resource',
                 DB::raw('TO_CHAR(date(tbl_reservas_detalle.check_out_fecha), \'yyyy-mm-dd\') as end') ,
                 DB::raw('concat_ws(\' \', tbl_clientes.nombre,tbl_clientes.apellido) as text') ,
                 DB::raw('sum(adultos_cantidad+ninos_cantidad+infantes_cantidad) as cantidad_huespedes') ,
                'tbl_reservas_estado.descripcion as estado_reserva_descripcion',
                'tbl_reservas_estado.id as id_estado_reserva',
                'tbl_reservas_detalle.id_reservas_grupo as id_grupo',
                DB::raw('COALESCE(tbl_reservas_estado.color,\'#000\') as barColor'),
              'tbl_reservas_grupo.facturado', 'tbl_reservas_grupo.adultos', 'tbl_reservas_grupo.ninos')
                ->groupBy('tbl_reservas.id', 'tbl_habitaciones.numero', 'tbl_habitaciones_tipo.nombre', 'tbl_reservas_estado.descripcion', 'tbl_reservas_detalle.id_habitacion_tipo', 'tbl_reservas_detalle.id_habitacion', 'tbl_reservas_detalle.adultos_cantidad', 'tbl_reservas_detalle.ninos_cantidad', 'tbl_reservas_detalle.precio_neto', 'tbl_reservas_detalle.infantes_cantidad', 'tbl_fuentes_reservas.nombre', 'tbl_reservas_detalle.id_reservas_grupo', 'tbl_reservas_estado.id', 'tbl_reservas.id', 'tbl_reservas.id_cliente', 'tbl_clientes.nombre', 'tbl_clientes.apellido', 'tbl_reservas_grupo.check_in_fecha', 'tbl_reservas.check_out_fecha', 'tbl_reservas_grupo.id', 'tbl_reservas_detalle.check_out_fecha', 'tbl_reservas_grupo.facturado', 'tbl_reservas_grupo.adultos', 'tbl_reservas_grupo.ninos', 'tbl_reservas.precio_total')
                ->join('tbl_clientes', 'tbl_reservas.id_cliente', 'tbl_clientes.id')
                ->leftJoin('tbl_reservas_grupo', 'tbl_reservas.id', 'tbl_reservas_grupo.id_reservas')
                ->leftJoin('tbl_reservas_detalle', 'tbl_reservas_grupo.id', 'tbl_reservas_detalle.id_reservas_grupo')
                ->join('tbl_reservas_estado', 'tbl_reservas_grupo.id_reservas_estado', 'tbl_reservas_estado.id')
                ->join('tbl_habitaciones', 'tbl_reservas_detalle.id_habitacion', 'tbl_habitaciones.id')
                ->join('tbl_habitaciones_tipo', 'tbl_habitaciones.id_habitacion_tipo', 'tbl_habitaciones_tipo.id')
                ->join('tbl_fuentes_reservas', 'tbl_reservas.fuente_reserva_id', 'tbl_fuentes_reservas.id')
                ->whereNotNull('tbl_reservas_detalle.id_habitacion')
                ->whereNull('tbl_reservas_detalle.deleted_at')
                ->whereDate('tbl_reservas.created_at', $fecha_actual)->paginate(50);

            foreach ($datos as $key => $temp)
            {
                $temp->barColor = $temp->barcolor;
                unset($temp->barcolor);
                $date1 = new \DateTime($temp->check_in_fecha);
                $date2 = new \DateTime($temp->check_out_fecha);
                $diff = $date1->diff($date2);
                $dias = $diff->days;
                $datos[$key]->id = ($key + 1);
                $temp->zipcode = '';
                $temp->ciudad = '';
                $temp->pais = '';
                $temp->dias = $dias;
                $temp->calle_residencia = '234234';
                $temp->codigo_postal_residencia = '';
                $temp->nombre_departamento = '';
                $temp->habitacion_tipo = $temp->id_habitacion_tipo;
                $temp->nombre_pais = '';
                $fecha_chec_in = TblReservasDetalle::where('id_reservas_grupo', '=', $temp->id_grupo)
                    ->orderBy('check_in_fecha')
                    ->first();
                $datos[$key]->check_in_fecha = $fecha_chec_in->check_in_fecha;
                $datos[$key]->start = date('Y-m-d', strtotime($fecha_chec_in->check_in_fecha));
                $datos[$key]->grupos = $this->detallesGrupos($temp->id_reservas);
                $datos[$key]->detalles = $this->detalles($temp, $temp->id_reservas);
                $temp->checkOut = $this->InfoPagos($temp->id_reservas, $temp->numero);
            }

        }

        else
        {

            $datos = Programa::select('tbl_programas.id', 'tbl_programas.nombre', 'tbl_programas.tenantId', 'tbl_programas.activos')->where('tbl_programas.activos', 0)
                ->where('tbl_programas.' . $criterio, 'LIKE', '%' . $buscar . '%')->orderBy('tbl_programas.nombre', 'asc')
                ->paginate(50);
        }

        return ['pagination' => ['total' => $datos->total() , 'current_page' => $datos->currentPage() , 'per_page' => $datos->perPage() , 'last_page' => $datos->lastPage() , 'from' => $datos->firstItem() , 'to' => $datos->lastItem() , ], 'datos' => $datos];

    }

    public function data_reservas()
    {
        $cantidadDiasAnterior = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime('-1 month')), date('Y', strtotime('-1 month'))) - 4;
        $mesInicio = date('Y-m', strtotime('-1 month')). "-".$cantidadDiasAnterior;
        $cantidadDias = cal_days_in_month(CAL_GREGORIAN, date("m"), date("Y"));
        $mes = date("Y-m");
        $mesFin = $mes. "-$cantidadDias";
        $data = DB::table('tbl_reservas')->select(
          'tbl_reservas.id as id_reservas',
          'tbl_habitaciones.numero as numero',
          'tbl_habitaciones_tipo.nombre as nombre_tipo',
          'tbl_reservas.id_cliente', 'tbl_clientes.nombre',
          'tbl_clientes.apellido', 'tbl_reservas.check_in_fecha',
          'tbl_reservas.check_out_fecha as fecha_salida',
          'tbl_reservas.precio_total',
          'tbl_reservas.created_at as fecha_reserva',
          'tbl_reservas_detalle.check_out_fecha',
          'tbl_reservas_detalle.adultos_cantidad',
          'tbl_reservas_detalle.ninos_cantidad',
          'tbl_reservas_detalle.infantes_cantidad',
          'tbl_fuentes_reservas.nombre as nombre_fuente',
          'tbl_fuentes_reservas.id as fuente_id',
          DB::raw('(tbl_reservas_detalle.adultos_cantidad+tbl_reservas_detalle.ninos_cantidad+tbl_reservas_detalle.infantes_cantidad) AS huespedes_cantidad') ,
          'tbl_reservas_detalle.id_habitacion_tipo',
          'tbl_reservas_detalle.id_habitacion as resource',
          DB::raw('TO_CHAR(date(tbl_reservas_detalle.check_out_fecha), \'yyyy-mm-dd\') as end') ,
          DB::raw('concat_ws(\' \', tbl_clientes.nombre,tbl_clientes.apellido) as text') ,
          'tbl_reservas.huespedes_cantidad as cantidad_huespedes',
          'tbl_reservas_estado.descripcion as estado_reserva_descripcion',
          'tbl_reservas_estado.id as id_estado_reserva',
          'tbl_reservas_detalle.id_reservas_grupo as id_grupo',
          DB::raw('COALESCE(tbl_reservas_estado.color,\'#000\') as barColor') ,
          'tbl_reservas_grupo.facturado', 'tbl_reservas_grupo.adultos',
          'tbl_reservas_grupo.ninos',
          'tbl_reservas_grupo.tarifa_promedio_diaria_ci',
          'tbl_reservas.abono'

          )
            ->groupBy(
              'tbl_reservas.id',
              'tbl_habitaciones.numero',
              'tbl_habitaciones_tipo.nombre',
              'tbl_reservas_estado.descripcion',
              'tbl_reservas_detalle.id_habitacion_tipo',
              'tbl_reservas_detalle.id_habitacion',
              'tbl_reservas_detalle.adultos_cantidad',
              'tbl_reservas_detalle.ninos_cantidad',
              'tbl_reservas_detalle.infantes_cantidad',
              'tbl_fuentes_reservas.nombre',
              'tbl_fuentes_reservas.id',
              'tbl_reservas_detalle.id_reservas_grupo',
              'tbl_reservas_estado.id', 'tbl_reservas.id',
              'tbl_reservas.id_cliente', 'tbl_clientes.nombre',
              'tbl_clientes.apellido',
              'tbl_reservas.check_in_fecha',
              'tbl_reservas.check_out_fecha',
              'tbl_reservas_grupo.id',
              'tbl_reservas_detalle.check_out_fecha',
              'tbl_reservas.created_at',
              'tbl_reservas.precio_total',
              'tbl_reservas_grupo.facturado',
              'tbl_reservas_grupo.adultos',
              'tbl_reservas_grupo.ninos',
              'tbl_reservas_grupo.tarifa_promedio_diaria_ci',
              'tbl_reservas.abono'
              )
            ->join('tbl_clientes', 'tbl_reservas.id_cliente', 'tbl_clientes.id')
            ->leftJoin('tbl_reservas_grupo', 'tbl_reservas.id', 'tbl_reservas_grupo.id_reservas')
            ->leftJoin('tbl_reservas_detalle', 'tbl_reservas_grupo.id', 'tbl_reservas_detalle.id_reservas_grupo')
            ->join('tbl_reservas_estado', 'tbl_reservas_grupo.id_reservas_estado', 'tbl_reservas_estado.id')
            ->join('tbl_habitaciones', 'tbl_reservas_detalle.id_habitacion', 'tbl_habitaciones.id')
            ->join('tbl_habitaciones_tipo', 'tbl_habitaciones.id_habitacion_tipo', 'tbl_habitaciones_tipo.id')
            ->join('tbl_fuentes_reservas', 'tbl_reservas.fuente_reserva_id', 'tbl_fuentes_reservas.id')
            ->where('tbl_reservas.estado_reserva', '=', 1)
            ->where('tbl_reservas_grupo.total_habitacion', '>', 0)
            ->whereNotNull('tbl_reservas_detalle.id_habitacion')
            ->whereNull('tbl_reservas_detalle.deleted_at')
            ->whereNull('tbl_reservas.deleted_at') 
            // ->where('tbl_reservas_grupo.check_in_fecha', '>=', $mesInicio)
            // ->where('tbl_reservas_grupo.check_out_fecha', '<=', $mesFin)
            ->get();
            
        foreach ($data as $key => $temp)
        {
            $temp->barColor = $temp->barcolor;
            unset($temp->barcolor);
            $date1 = new \DateTime($temp->check_in_fecha);
            $date2 = new \DateTime($temp->check_out_fecha);
            $diff = $date1->diff($date2);
            $dias = $diff->days;
            $data[$key]->id = ($key + 1);
            $temp->zipcode = '';
            $temp->ciudad = '';
            $temp->pais = '';
            $temp->dias = $dias;
            $temp->calle_residencia = '5555';
            $temp->codigo_postal_residencia = '';
            $temp->nombre_departamento = '';
            $temp->habitacion_tipo = $temp->id_habitacion_tipo;
            $temp->nombre_pais = '';
            $fecha_chec_in = TblReservasDetalle::where('id_reservas_grupo', '=', $temp->id_grupo)
                ->orderBy('check_in_fecha')
                ->first();
            $data[$key]->check_in_fecha = $fecha_chec_in->check_in_fecha;
            $data[$key]->start = date('Y-m-d', strtotime($fecha_chec_in->check_in_fecha));
            //$data[$key]->grupos = $this->detallesGrupos($temp->id_reservas);
            //$data[$key]->cuentas = $this->detallesCuentas($temp->id_reservas);
            $data[$key]->detalles = $this->detalles($temp, $temp->id_reservas); 
            $data[$key]->total_abono = $this->totalAbono($temp->id_reservas);
            $temp->saldo_pendiente = (float)$temp->precio_total - (float)$temp->total_abono;
            $temp->checkOut = $this->InfoPagos($temp->id_reservas, $temp->numero); 
        }
        return $data;
    }
    public function totalAbono($reserva_id)
    {
      $data = CuentaAbono::where('reserva_id', (int)$reserva_id)->sum('abono');
      return (float)$data;
    }
    public function InfoPagos($reserva_id, $numero)
    {
        $data = PagoTotal::where('numero', (int)$numero)->where('reserva_id', (int)$reserva_id)->get();
        if (count($data) === 0)
        {
            $pago = 1;
            return $pago;
        }
        else
        {
            $pago = 0;
            foreach ($data as $key => $temp)
            {
                if ($temp->valor_pagado < $temp->total_a_pagar)
                {
                    $pago = 1;
                    return $pago;
                }
                else
                {
                    $pago = 0;
                    return $pago;
                }
            }
        }
    }
    private function GuardarClientesReservasDetalles($request)
    {

        $idReservasDetalles = TblReservasDetalle::join('tbl_reservas_grupo', 'tbl_reservas_detalle.id_reservas_grupo', '=', 'tbl_reservas_grupo.id')->where('tbl_reservas_grupo.id_reservas', '=', $request->id_reservas)
            ->where('tbl_reservas_detalle.id_habitacion_tipo', '=', $request->id_habitacion_tipo)
            ->select('tbl_reservas_detalle.id')
            ->get();
        foreach ($idReservasDetalles as $ids)
        {
            foreach ($request->huespedes_data as $temp)
            {
                $temp = (object)$temp;
                if (!is_null($temp->id_huesped) && !is_null($ids->id))
                {
                    $new = TblReservasDetalleHuespedes::firstOrNew(['id_cliente_huesped' => $temp->id_huesped, 'reserva_grupo_id' => $temp->grupo_id, ]);
                    $new->id_cliente_huesped = $temp->id_huesped;
                    $new->id_reservas_detalle = $ids->id;
                    $new->reserva_grupo_id = $temp->grupo_id;
                    $new->Save();
                    unset($new);
                    $pagador = TblReservaPagador::firstOrNew(['cliente_id' => $temp->id_huesped,
                    // 'reserva_detalle_id' => $ids->id,
                    'reserva_grupo_id' => $temp->grupo_id, ]);
                    $pagador->cliente_id = $temp->id_huesped;
                    // $pagador->reserva_detalle_id=$ids->id;
                    $pagador->reserva_grupo_id = $temp->grupo_id;
                    $pagador->Save();
                }
            }
        }
    }
    public function VerClientes(Request $request)
    {
        $data = TblCliente::select(DB::raw('concat_ws(\' \',nombre,apellido) as name') , DB::raw('\'' . $request->vmodel . '\' as id_row') , DB::raw('\'' . $request->index . '\' as id_index1') , DB::raw('\'' . $request->index2 . '\' as id_index2') , 'id', 'id_clientes_tipo', 'nombre', 'apellido', 'genero_id', 'fecha_nacimiento', 'id_nacionalidad', 'formula', 'titulo', 'id_documento_tipo', 'no_documento', 'nombre_empresa', 'no_empresa', 'nombre_agencia', 'no_agencia', 'calle_residencia', 'lugar_residencia', 'codigo_postal_residencia', 'id_pais_residencia', 'id_departamento_residencia', 'calle_factura', 'lugar_factura', 'codigo_postal_factura', 'id_pais_factura', 'id_departamento_factura', 'comentarios', 'telefono', 'email')
            ->orWhere('nombre', 'ilike', '%' . $request->q . '%')
            ->orWhere('apellido', 'ilike', '%' . $request->q . '%')
            ->orWhere('no_documento', 'ilike', '%' . $request->q . '%')
            ->orWhere('nombre_empresa', 'ilike', '%' . $request->q . '%')
            ->orWhere('email', 'ilike', '%' . $request->q . '%')
            ->orderBy(DB::raw('1'))
            ->limit('10')
            ->get();
        return ['items' => $data, "total_count" => count($data) ];
    }


    public function GuardarChekin(Request $request)
    {

        $res = $request->all();
        foreach ($res as $temp)
        {
            $change = TblReservasGrupo::find($temp['id_grupo']);
            $change->id_reservas_estado = 2;
            $change->Save();

        }
        return ['validate' => true];
    }

    public function anularReserva(Request $request)
    {
        $res = $request->grupo;
        $change = TblReservasGrupo::find((int)$res);
        $change->id_reservas_estado = 6;
        $change->Save();
        return ['validate' => true];
    }

    private function detallesGrupos($id_reserva)
    {

        $data = DB::table('tbl_reservas_detalle')->select(
            'tbl_reservas_grupo.id_reservas_estado',
            'tbl_reservas_grupo.id as id_grupo',
            DB::raw('(CASE WHEN tbl_reservas_detalle.adultos_cantidad IS NULL THEN 0 ELSE tbl_reservas_detalle.adultos_cantidad END) AS cantidad_adultos'),
            DB::raw('(CASE WHEN tbl_reservas_detalle.ninos_cantidad IS NULL THEN 0 ELSE tbl_reservas_detalle.ninos_cantidad END) AS cantidad_ninos'),
            DB::raw('(CASE WHEN tbl_reservas_detalle.infantes_cantidad IS NULL THEN 0 ELSE tbl_reservas_detalle.infantes_cantidad END) AS cantidad_infantes'),
            'tbl_reservas_detalle.id_habitacion',
            'tbl_habitaciones_tipo.nombre as habitacion_tipo',
            'tbl_reservas_detalle.id_habitacion_tipo',
            'tbl_reservas_grupo.id_reservas',
            'tbl_reservas_grupo.id as clave_grupo',
            'tbl_reservas_grupo.total_habitacion',
            'tbl_habitaciones.numero as habitacion_numero',
            'tbl_planes_tarifarios.nombre as plan_tarifario'
            )
            ->join('tbl_habitaciones_tipo', 'tbl_reservas_detalle.id_habitacion_tipo', 'tbl_habitaciones_tipo.id')
            ->leftJoin('tbl_reservas_grupo', 'tbl_reservas_detalle.id_reservas_grupo', 'tbl_reservas_grupo.id')
            ->leftJoin('tbl_planes_tarifarios', 'tbl_reservas_grupo.plan_tarifa_id', 'tbl_planes_tarifarios.id')
            ->join('tbl_habitaciones', 'tbl_reservas_detalle.id_habitacion', 'tbl_habitaciones.id')
            ->where('tbl_reservas_grupo.id_reservas', '=', $id_reserva)->where('tbl_reservas_grupo.deleted_at', '=', null)
            ->whereNull('tbl_reservas_detalle.deleted_at')
            ->GroupBy(
              'tbl_reservas_grupo.id_reservas_estado',
              'tbl_reservas_grupo.id',
               'tbl_reservas_detalle.id_habitacion_tipo',
               'tbl_reservas_detalle.adultos_cantidad',
               'tbl_reservas_detalle.ninos_cantidad',
               'tbl_reservas_detalle.infantes_cantidad',
               'tbl_reservas_detalle.id_habitacion',
               'tbl_habitaciones_tipo.nombre',
               'tbl_reservas_grupo.id_reservas',
               'tbl_reservas_grupo.id',
               'tbl_reservas_grupo.total_habitacion',
               'tbl_habitaciones.numero',
               'tbl_planes_tarifarios.nombre'
               )
            ->get();
        foreach ($data as $key => $temp)
        {
            $temp->huespedes = $this->huespedes_habitacion($temp->id_grupo);
            $temp->pagadores = $this->pagadores_habitacion($temp->id_grupo);
            $temp->fecha_ingreso = $this->fechaIngreso($temp->id_grupo);
            $temp->fecha_salida = $this->fechaSalida($temp->id_grupo);
            $temp->cantidad_huespedes = (int)$temp->cantidad_adultos + (int)$temp->cantidad_ninos + (int)$temp->cantidad_infantes;
            $data[$key] = $temp;
        }
        return $data;
    }
    public function data_grupos(Request $request)
    {

        $data = DB::table('tbl_reservas_detalle')->select(
            'tbl_reservas_grupo.id_reservas_estado',
            'tbl_reservas_grupo.id as id_grupo',
            DB::raw('(CASE WHEN tbl_reservas_detalle.adultos_cantidad IS NULL THEN 0 ELSE tbl_reservas_detalle.adultos_cantidad END) AS cantidad_adultos'),
            DB::raw('(CASE WHEN tbl_reservas_detalle.ninos_cantidad IS NULL THEN 0 ELSE tbl_reservas_detalle.ninos_cantidad END) AS cantidad_ninos'),
            DB::raw('(CASE WHEN tbl_reservas_detalle.infantes_cantidad IS NULL THEN 0 ELSE tbl_reservas_detalle.infantes_cantidad END) AS cantidad_infantes'),
            'tbl_reservas_detalle.id_habitacion',
            'tbl_habitaciones_tipo.nombre as habitacion_tipo',
            'tbl_reservas_detalle.id_habitacion_tipo',
            'tbl_reservas_grupo.id_reservas',
            'tbl_reservas_grupo.id as clave_grupo',
            'tbl_reservas_grupo.total_habitacion',
            'tbl_habitaciones.numero as habitacion_numero',
            'tbl_planes_tarifarios.nombre as plan_tarifario'
            )
            ->join('tbl_habitaciones_tipo', 'tbl_reservas_detalle.id_habitacion_tipo', 'tbl_habitaciones_tipo.id')
            ->leftJoin('tbl_reservas_grupo', 'tbl_reservas_detalle.id_reservas_grupo', 'tbl_reservas_grupo.id')
            ->leftJoin('tbl_planes_tarifarios', 'tbl_reservas_grupo.plan_tarifa_id', 'tbl_planes_tarifarios.id')
            ->join('tbl_habitaciones', 'tbl_reservas_detalle.id_habitacion', 'tbl_habitaciones.id')
            ->where('tbl_reservas_grupo.id_reservas', '=', $request->reserva_id)->where('tbl_reservas_grupo.deleted_at', '=', null)
            ->whereNull('tbl_reservas_detalle.deleted_at')
            ->GroupBy(
              'tbl_reservas_grupo.id_reservas_estado',
              'tbl_reservas_grupo.id',
               'tbl_reservas_detalle.id_habitacion_tipo',
               'tbl_reservas_detalle.adultos_cantidad',
               'tbl_reservas_detalle.ninos_cantidad',
               'tbl_reservas_detalle.infantes_cantidad',
               'tbl_reservas_detalle.id_habitacion',
               'tbl_habitaciones_tipo.nombre',
               'tbl_reservas_grupo.id_reservas',
               'tbl_reservas_grupo.id',
               'tbl_reservas_grupo.total_habitacion',
               'tbl_habitaciones.numero',
               'tbl_planes_tarifarios.nombre'
               )
            ->get();
        foreach ($data as $key => $temp)
        {
            $temp->huespedes = $this->huespedes_habitacion($temp->id_grupo);
            $temp->pagadores = $this->pagadores_habitacion($temp->id_grupo);
            $temp->fecha_ingreso = $this->fechaIngreso($temp->id_grupo);
            $temp->fecha_salida = $this->fechaSalida($temp->id_grupo);
            $temp->cantidad_huespedes = (int)$temp->cantidad_adultos + (int)$temp->cantidad_ninos + (int)$temp->cantidad_infantes;
            $data[$key] = $temp;
        }

        
        return $data;
    }
    
    public function detalles_cuentas(Request $request)
    {
      $cuenta = Cuenta::all();
      foreach ($cuenta as $key => $temp)
      {
          $temp->cuentas = $this->cuentasReservas($temp->id, (int)$request->reserva_id);
          $temp->total_cargo = $this->totalCargo($temp->id, (int)$request->reserva_id);
          $cuenta[$key] = $temp;
      }
      return $cuenta;
    }
    private function detallesCuentas($id_reserva)
    {
        $cuenta = Cuenta::all();
        foreach ($cuenta as $key => $temp)
        {
            $temp->cuentas = $this->cuentasReservas($temp->id, $id_reserva);
            $temp->total_cargo = $this->totalCargo($temp->id, $id_reserva);
            $cuenta[$key] = $temp;
        }
        return $cuenta;
    }
    public function data_cuentas(Request $request)
    {
        $cuenta = Cuenta::all();
        foreach ($cuenta as $key => $temp)
        {
            $temp->cuentas = $this->cuentasReservas($temp->id, $request->reserva_id);
            $temp->total_cargo = $this->totalCargo($temp->id, $request->reserva_id);
            $cuenta[$key] = $temp;
        }
        return $cuenta;
    }
    public function cuentasReservas($cuenta_id, $id_reserva)
    {
      $data = CuentaReserva::select(
        'tbl_cuenta_reservas.id',
        'tbl_cuenta_reservas.reserva_id',
        'tbl_cuenta_reservas.cuenta_id',
        'tbl_cuenta_reservas.fecha_hora',
        'tbl_cuenta_reservas.habitacion_id',
        'tbl_cuenta_reservas.concepto',
        'tbl_cuenta_reservas.notas',
        'tbl_cuenta_reservas.cargo',
        'tbl_cuenta_reservas.abono',
        'tbl_cuentas.nombre as cuenta',
        'tbl_cuenta_reservas.condicion',
        'tbl_cuenta_reservas.tipo_movimiento',
        'tbl_habitaciones.numero as habitacion'
        )
      ->leftjoin('tbl_habitaciones', 'tbl_cuenta_reservas.habitacion_id', '=', 'tbl_habitaciones.id')
      ->join('tbl_cuentas', 'tbl_cuenta_reservas.cuenta_id', '=', 'tbl_cuentas.id')
      ->where('tbl_cuenta_reservas.reserva_id', (int)$id_reserva)
      ->where('tbl_cuenta_reservas.cuenta_id', (int)$cuenta_id)
      ->where('estado', true)
      ->orderBy('tbl_cuenta_reservas.id', 'asc')
      ->get();

      foreach ($data as $key => $temp)
      {
          $temp->cargo = round($temp->cargo, 2);
          $data[$key] = $temp;
      }
      return $data;
    }
    public function totalCargo($cuenta_id, $id_reserva)
    {
      $data = CuentaReserva::where('tbl_cuenta_reservas.reserva_id', (int)$id_reserva)
      ->where('tbl_cuenta_reservas.cuenta_id', (int)$cuenta_id)
      ->where('estado', true)
      ->sum('tbl_cuenta_reservas.cargo');
      return round($data, 2);
    }
    public function fechaIngreso($grupo)
    {
        $grupo = TblReservasGrupo::find($grupo);
        return $grupo->check_in_fecha;
    }

    public function fechaSalida($grupo)
    {
        $grupo = TblReservasGrupo::find($grupo);
        return $grupo->check_out_fecha;
    }

    public function huespedes_habitacion($grupo)
    {
        $huespedes_habitacion = TblReservasDetalleHuespedes::select('tbl_clientes.id', DB::raw('concat_ws(\' \', tbl_clientes.nombre, tbl_clientes.apellido) AS nombres_huespedes'))->join('tbl_clientes', 'tbl_reservas_detalle_huespedes.id_cliente_huesped', '=', 'tbl_clientes.id')
            ->where('reserva_grupo_id', '=', $grupo)->orderBy('tbl_clientes.id', 'asc')
            ->get();
        return $huespedes_habitacion;
    }

    public function pagadores_habitacion($grupo)
    {
        $pagadores = TblReservaPagador::select('tbl_clientes.id', DB::raw('concat_ws(\' \', tbl_clientes.nombre, tbl_clientes.apellido) AS nombres_huespedes'))->join('tbl_clientes', 'tbl_reservas_pagadores.cliente_id', '=', 'tbl_clientes.id')
            ->where('tbl_reservas_pagadores.reserva_grupo_id', '=', $grupo)->orderBy('tbl_clientes.id', 'asc')
            ->groupBy('tbl_clientes.id')
            ->get();
        return $pagadores;
    }

    public function ChangeColor(Request $request)
    {
        $data = TblReserva::find($request->input('id_reserva'));
        $data->color = $request->input('color');
        $data->save();
        return ['validate' => true];
    }
    private function tipo_habitacion($request)
    {
        $tipo = '';
        if (!is_null($request->nueva_habitacion))
        {
            $tipo = TblHabitacione::find($request->nueva_habitacion);
            $tipo = $tipo->id_habitacion_tipo;
        }
        else
        {
            $tipo = TblReservasDetalle::where('id_reservas_grupo', '=', $request->id_grupo)
                ->first();
            $tipo = $tipo->id_habitacion_tipo;
        }
        return $tipo;
    }
    private function validateNewFecha($request)
    {
        $tipo = $this->tipo_habitacion($request);
        $nueva_fecha_llegada = date("Y-m-d", strtotime($request->nueva_fecha_llegada));
        $nueva_fecha_salida = date("Y-m-d", strtotime($request->nueva_fecha_salida));
        $habitaaciones = TblReservasDetalle::select(DB::raw('count(*) as total'))->join('tbl_reservas_grupo', 'tbl_reservas_detalle.id_reservas_grupo', '=', 'tbl_reservas_grupo.id')
            ->where('tbl_reservas_detalle.id_habitacion_tipo', '=', $tipo)->where('tbl_reservas_grupo.id_reservas', '!=', $request->id_reserva)
            ->whereNotNull('tbl_reservas_detalle.id_habitacion')
            ->whereBetween(DB::raw('date(tbl_reservas_detalle.check_in_fecha)') , [$nueva_fecha_llegada, $nueva_fecha_salida])->first();
        return true; //($habitaaciones->total=='0');

    }

    public function ActualizarConsumosExtras($grupo, $reserva_detalle_id)
    {

        $reservasDetalles = TblReservasDetalle::where('id_reservas_grupo', $grupo)->get();
        foreach ($reservasDetalles as $value)
        {

            $cambiosConsumoExtra = ConsumoExtra::whereDate('fecha', $value['check_in_fecha'])->get();
            foreach ($cambiosConsumoExtra as $value2)
            {
                $consumoExtra = ConsumoExtra::find($value2['id']);
                $consumoExtra->reserva_detalle_id = $value['id'];
                $consumoExtra->save();
            }
        }
    }
    public function ActualizarFecha(Request $request)
    {
        $fecha1 = $this->formatDate($request->nueva_fecha_llegada);
        $fecha2 = $this->formatDate($request->nueva_fecha_salida);
        $cantidad = [];
        for($i=$fecha1;$i<$fecha2;$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
            $habitacion = InventarioHabitacion::select('habitacion_id')
            ->where('habitacion_id', '=', (int)$request->nueva_habitacion)
            ->where('disponibilidad', false)
            ->whereDate('start', $i)
            ->orderBy('id', 'asc')
            ->first();
            if ($habitacion !=null) {
              $cantidad[] = $habitacion->habitacion_id;
            }

        }
        if (count($cantidad) == 0) {
        PGSchema::switchTo(Auth::user()->schema);
        $tipoHabitacion = TblHabitacione::find($request->nueva_habitacion);
        $data = $request->all();
        $Porcentaje_monto_total= DB::select('SELECT "porcentaje_monto_total"()');
        $cantidad_x_persona_adulto = DB::select('SELECT "cantidad_x_persona_adulto"()');
        $cantidad_x_persona_nino = DB::select('SELECT "cantidad_x_persona_nino"()');
        $cantidad_fija_x_habitacion = DB::select('SELECT "cantidad_fija_x_habitacion"()');
        $decimal = (float)($Porcentaje_monto_total[0]->porcentaje_monto_total/100);
        $date1 = new \DateTime($request->input('nueva_fecha_llegada'));
        $date2 = new \DateTime($request->input('nueva_fecha_salida'));
        $diff = $date1->diff($date2);
        $dias = $diff->days;
        $esquema = Auth::user()->schema;
        $adulto = ((int)$request->cantidad_adultos * (float)$cantidad_x_persona_adulto[0]->cantidad_x_persona_adulto) * ($dias);
        $nino = ((int)$request->cantidad_ninos * (float)$cantidad_x_persona_nino[0]->cantidad_x_persona_nino) * ($dias);
        $valor_neto = ((float)$request->tarifa_promedio_diaria_ci * (int)$dias)/(1 + ($Porcentaje_monto_total[0]->porcentaje_monto_total/100));
        $grupo = TblReservasGrupo::find($request->id_grupo);
        $grupo->check_in_fecha = $request->input('nueva_fecha_llegada');
        $grupo->check_out_fecha = $request->input('nueva_fecha_salida');
        $grupo->tarifa_promedio_diaria_ci = (float)$request->tarifa_promedio_diaria_ci;
        $grupo->valor_neto = (float)$valor_neto;
        $grupo->total_tarifa_ci = ((float)$request->tarifa_promedio_diaria_ci * (int)$dias) + ((float)$cantidad_fija_x_habitacion[0]->cantidad_fija_x_habitacion) + ($adulto) + ($nino);
        $reserva = TblReserva::find((int)$request->id_reserva);
        $reserva->precio_total = (float)$reserva->precio_total - (float)$grupo->total_habitacion;
        $reserva->save();
        $grupo->total_habitacion = ((float)$request->tarifa_promedio_diaria_ci * (int)$dias) + ((float)$cantidad_fija_x_habitacion[0]->cantidad_fija_x_habitacion) + ($adulto) + ($nino);
        $reserva = TblReserva::find((int)$request->id_reserva);
        $reserva->precio_total = (float)$reserva->precio_total + (float)$grupo->total_habitacion;
        $reserva->save();
        $grupo->save();
        $fecha1 = $this->formatDate($request->nueva_fecha_llegada);
        $fecha2 = $this->formatDate($request->nueva_fecha_salida);
        TblReservasDetalle::where('id_reservas_grupo', (int)$request->id_grupo)->delete();
        CuentaReserva::where('grupo_id', (int)$request->id_grupo)->delete();
        $cuenta = Cuenta::where('reserva_id', (int)$request->id_reserva)->firstOrfail();
        for($i=$fecha1;$i<$fecha2;$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
          $result_adulto = ((int)$request->cantidad_adultos * (float)$cantidad_x_persona_adulto[0]->cantidad_x_persona_adulto);
          $result_nino = ((int)$request->cantidad_ninos * (float)$cantidad_x_persona_nino[0]->cantidad_x_persona_nino);
          $ReservasDetalle = new TblReservasDetalle();
          $ReservasDetalle->id_reservas_grupo = $grupo->id;
          $ReservasDetalle->id_habitacion_tipo = (int)$tipoHabitacion->id_habitacion_tipo;
          $ReservasDetalle->adultos_cantidad = $request->cantidad_adultos;
          $ReservasDetalle->ninos_cantidad = $request->cantidad_ninos;
          $ReservasDetalle->infantes_cantidad = 0;
          $ReservasDetalle->check_in_fecha = $i;
          $ReservasDetalle->check_out_fecha = $request->input('nueva_fecha_salida');
          $ReservasDetalle->id_reserva_detalle_estado_habitacion = 1;
          $ReservasDetalle->id_habitacion = (int)$request->nueva_habitacion;
          $ReservasDetalle->id_reserva_detalle_estado_habitacion = 1;
          $ReservasDetalle->monto_diario = $request->tarifa_promedio_diaria_ci;
          $ReservasDetalle->tarifa_neta = ((float)$request->tarifa_promedio_diaria_ci)/(1 + ($Porcentaje_monto_total[0]->porcentaje_monto_total/100));
          $ReservasDetalle->tasa_impuestos = ($ReservasDetalle->check_in_fecha == $request->tarifa_promedio_diaria_ci) ? (((float)$ReservasDetalle->tarifa_neta * (float)$decimal) + (float)$cantidad_fija_x_habitacion[0]->cantidad_fija_x_habitacion) + ($result_adulto) + ($result_nino) : ((float)$ReservasDetalle->tarifa_neta * (float)$decimal) + $result_adulto + $result_nino;
          $ReservasDetalle->total_diario = (float)$ReservasDetalle->tarifa_neta + (float)$ReservasDetalle->tasa_impuestos;
          $ReservasDetalle->save();

          $cuenta_reserva = new CuentaReserva();
          $cuenta_reserva->reserva_id = (int)$request->id_reserva;
          $cuenta_reserva->cuenta_id = (int)$cuenta->id;
          $cuenta_reserva->fecha_hora = $i;
          $cuenta_reserva->habitacion_id = (int)$ReservasDetalle->id_habitacion;
          $cuenta_reserva->concepto = 'Hospedaje';
          $cuenta_reserva->tipo_movimiento = 'H';
          $cuenta_reserva->grupo_id = $ReservasDetalle->id_reservas_grupo;
          $cuenta_reserva->cargo = $ReservasDetalle->tarifa_neta;
          $cuenta_reserva->save();

            if ($Porcentaje_monto_total > 0) {
                $this->SavePorcentajeMontoTotal($ReservasDetalle, $request->id_reserva, $cuenta->id);
            }
            if ($cantidad_fija_x_habitacion > 0) {
              $this->SaveCantidadFijaHabitacion($ReservasDetalle, $request->id_reserva, $cuenta->id);
            }
            if ($cantidad_x_persona_adulto > 0) {
              $this->SaveAdulto($ReservasDetalle, $request->id_reserva, $cuenta->id);
            }
            if ($cantidad_x_persona_nino > 0) {
              $this->SaveNino($ReservasDetalle, $request->id_reserva, $cuenta->id);
            }
          }
          return ['data' => 1];
        }
          if (count($cantidad) != 0) {
          return ['data' => 2];
          }
    }

    public function SavePorcentajeMontoTotalUpdate($data, $id_reserva)
    {
      $Porcentaje_monto_total = ImpuestoTasa::where('tbl_impuestos_tasas.formato_id', '=', 1)->where('tbl_impuestos_tasas.estado', true)->get();
      foreach ($Porcentaje_monto_total as $valor) {
        $cuenta_reserva = CuentaReserva::where('reserva_id', (int)$id_reserva)->firstOrfail();
        $cuenta_reserva->fecha_hora = $data->check_in_fecha;
        $cuenta_reserva->habitacion_id = $data->id_habitacion;
        $cuenta_reserva->concepto = $valor['nombre'].'/'.$valor['valor'];
        $cuenta_reserva->cargo = (float)$data->tarifa_neta * (((float)$valor['valor']/100));
        $cuenta_reserva->save();
      }
    }

    public function SaveCantidadFijaHabitacionUpdate($data, $id_reserva)
    {
      $Porcentaje_monto_total = ImpuestoTasa::where('tbl_impuestos_tasas.formato_id', '=', 2)->where('tbl_impuestos_tasas.estado', true)->get();
      foreach ($Porcentaje_monto_total as $valor) {
        $cuenta_reserva = CuentaReserva::where('reserva_id', (int)$id_reserva)->firstOrfail();
        $cuenta_reserva->fecha_hora = $data->check_in_fecha;
        $cuenta_reserva->habitacion_id = $data->id_habitacion;
        $cuenta_reserva->concepto = $valor['nombre'].'/'.$valor['valor'];
        $cuenta_reserva->cargo = (float)$valor['valor'];
        $cuenta_reserva->save();
      }
    }

    public function SaveAdultoUpdate($data, $id_reserva)
    {
      $Porcentaje_monto_total = ImpuestoTasa::where('tbl_impuestos_tasas.formato_id', '=', 3)->where('tbl_impuestos_tasas.estado', true)->get();
      foreach ($Porcentaje_monto_total as $valor) {
        $cuenta_reserva = CuentaReserva::where('reserva_id', (int)$id_reserva)->firstOrfail();
        $cuenta_reserva->fecha_hora = $data->check_in_fecha;
        $cuenta_reserva->habitacion_id = $data->id_habitacion;
        $cuenta_reserva->concepto = $valor['nombre'].'/'.$valor['valor'];
        $cuenta_reserva->cargo = (float)$valor['valor'];
        $cuenta_reserva->save();
      }
    }
    public  function SaveNinoUpdate($data,  $id_reserva)
    {
      $Porcentaje_monto_total = ImpuestoTasa::where('tbl_impuestos_tasas.formato_id', '=', 4)->where('tbl_impuestos_tasas.estado', true)->get();
      foreach ($Porcentaje_monto_total as $valor) {
        $cuenta_reserva = CuentaReserva::where('reserva_id', (int)$id_reserva)->firstOrfail();
        $cuenta_reserva->fecha_hora = $data->check_in_fecha;
        $cuenta_reserva->habitacion_id = $data->id_habitacion;
        $cuenta_reserva->concepto = $valor['nombre'].'/'.$valor['valor'];
        $cuenta_reserva->cargo = (float)$valor['valor'];
        $cuenta_reserva->save();

      }
    }

    public function expandir_reserva(Request $request)
    {

      PGSchema::switchTo(Auth::user()->schema);
      $tipoHabitacion = TblHabitacione::where('numero', $request->numero)->firstOrfail();
      $data = $request->all();
      $Porcentaje_monto_total= DB::select('SELECT "porcentaje_monto_total"()');
      $cantidad_x_persona_adulto = DB::select('SELECT "cantidad_x_persona_adulto"()');
      $cantidad_x_persona_nino = DB::select('SELECT "cantidad_x_persona_nino"()');
      $cantidad_fija_x_habitacion = DB::select('SELECT "cantidad_fija_x_habitacion"()');
      $decimal = (float)($Porcentaje_monto_total[0]->porcentaje_monto_total/100);
      $date1 = new \DateTime($request->input('nueva_fecha_llegada'));
      $date2 = new \DateTime($request->input('nueva_fecha_salida'));
      $diff = $date1->diff($date2);
      $dias = $diff->days;
      $esquema = Auth::user()->schema;
      $adulto = ((int)$request->cantidad_adultos * (float)$cantidad_x_persona_adulto[0]->cantidad_x_persona_adulto) * ($dias);
      $nino = ((int)$request->cantidad_ninos * (float)$cantidad_x_persona_nino[0]->cantidad_x_persona_nino) * ($dias);
      $valor_neto = ((float)$request->tarifa_promedio_diaria_ci * (int)$dias)/(1 + ($Porcentaje_monto_total[0]->porcentaje_monto_total/100));

      $grupo = TblReservasGrupo::find($request->id_grupo);
      $grupo->check_in_fecha = $request->input('nueva_fecha_llegada');
      $grupo->check_out_fecha = $request->input('nueva_fecha_salida');
      $grupo->tarifa_promedio_diaria_ci = (float)$request->tarifa_promedio_diaria_ci;
      $grupo->valor_neto = (float)$valor_neto;
      $grupo->total_tarifa_ci = ((float)$request->tarifa_promedio_diaria_ci * (int)$dias) + ((float)$cantidad_fija_x_habitacion[0]->cantidad_fija_x_habitacion) + ($adulto) + ($nino);
      $reserva = TblReserva::find((int)$request->id_reserva);
      $reserva->precio_total = (float)$reserva->precio_total - (float)$grupo->total_habitacion;
      $reserva->save();
      $grupo->total_habitacion = ((float)$request->tarifa_promedio_diaria_ci * (int)$dias) + ((float)$cantidad_fija_x_habitacion[0]->cantidad_fija_x_habitacion) + ($adulto) + ($nino);
      $reserva = TblReserva::find((int)$request->id_reserva);
      $reserva->precio_total = (float)$reserva->precio_total + (float)$grupo->total_habitacion;
      $reserva->save();
      $grupo->save();
      $fecha1 = $this->formatDate($request->nueva_fecha_llegada);
      $fecha2 = $this->formatDate($request->nueva_fecha_salida);
      TblReservasDetalle::where('id_reservas_grupo', (int)$request->id_grupo)->delete();
      CuentaReserva::where('grupo_id', (int)$request->id_grupo)->delete();
      $cuenta = Cuenta::where('reserva_id', (int)$request->id_reserva)->firstOrfail();
      for($i=$fecha1;$i<$fecha2;$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
        $result_adulto = ((int)$request->cantidad_adultos * (float)$cantidad_x_persona_adulto[0]->cantidad_x_persona_adulto);
        $result_nino = ((int)$request->cantidad_ninos * (float)$cantidad_x_persona_nino[0]->cantidad_x_persona_nino);
        $ReservasDetalle = new TblReservasDetalle();
        $ReservasDetalle->id_reservas_grupo = $grupo->id;
        $ReservasDetalle->id_habitacion_tipo = (int)$tipoHabitacion->id_habitacion_tipo;
        $ReservasDetalle->adultos_cantidad = $request->cantidad_adultos;
        $ReservasDetalle->ninos_cantidad = $request->cantidad_ninos;
        $ReservasDetalle->infantes_cantidad = 0;
        $ReservasDetalle->check_in_fecha = $i;
        $ReservasDetalle->check_out_fecha = $request->input('nueva_fecha_salida');
        $ReservasDetalle->id_reserva_detalle_estado_habitacion = 1;
        $ReservasDetalle->id_habitacion = (int)$tipoHabitacion->id;
        $ReservasDetalle->id_reserva_detalle_estado_habitacion = 1;
        $ReservasDetalle->monto_diario = $request->tarifa_promedio_diaria_ci;
        $ReservasDetalle->tarifa_neta = ((float)$request->tarifa_promedio_diaria_ci)/(1 + ($Porcentaje_monto_total[0]->porcentaje_monto_total/100));
        $ReservasDetalle->tasa_impuestos = ($ReservasDetalle->check_in_fecha == $request->tarifa_promedio_diaria_ci) ? (((float)$ReservasDetalle->tarifa_neta * (float)$decimal) + (float)$cantidad_fija_x_habitacion[0]->cantidad_fija_x_habitacion) + ($result_adulto) + ($result_nino) : ((float)$ReservasDetalle->tarifa_neta * (float)$decimal) + $result_adulto + $result_nino;
        $ReservasDetalle->total_diario = (float)$ReservasDetalle->tarifa_neta + (float)$ReservasDetalle->tasa_impuestos;
        $ReservasDetalle->save();

        $cuenta_reserva = new CuentaReserva();
        $cuenta_reserva->reserva_id = (int)$request->id_reserva;
        $cuenta_reserva->cuenta_id = (int)$cuenta->id;
        $cuenta_reserva->fecha_hora = $i;
        $cuenta_reserva->habitacion_id = (int)$ReservasDetalle->id_habitacion;
        $cuenta_reserva->concepto = 'Hospedaje';
        $cuenta_reserva->tipo_movimiento = 'H';
        $cuenta_reserva->grupo_id = $ReservasDetalle->id_reservas_grupo;
        $cuenta_reserva->cargo = $ReservasDetalle->tarifa_neta;
        $cuenta_reserva->save();

          if ($Porcentaje_monto_total > 0) {
              $this->SavePorcentajeMontoTotal($ReservasDetalle, $request->id_reserva, $cuenta->id);
          }
          if ($cantidad_fija_x_habitacion > 0) {
            $this->SaveCantidadFijaHabitacion($ReservasDetalle, $request->id_reserva, $cuenta->id);
          }
          if ($cantidad_x_persona_adulto > 0) {
            $this->SaveAdulto($ReservasDetalle, $request->id_reserva, $cuenta->id);
          }
          if ($cantidad_x_persona_nino > 0) {
            $this->SaveNino($ReservasDetalle, $request->id_reserva, $cuenta->id);
          }


      }


    }
    private function Updatehuespedes($id_reserva_detalle)
    {
        $data = TblReservasDetalleHuespedes::where('id_reservas_detalle', '=', $id_reserva_detalle)->get();
        foreach ($data as $temp)
        {
            $delete = TblReservasDetalleHuespedes::find($temp->id);
            $delete->delete();
        }
    }
    public function VerReservasGrupo(Request $request)
    {
    }
    private function CrearEstadoHabitacion($id_reservas_grupo, $id_habitacion)
    {
        $data = TblReservasDetalle::where('id_reservas_grupo', $id_reservas_grupo)->get();
        foreach ($data as $temp)
        {

            $estados = new TblHabitacionesDetalleEstado();
            $estados->id_habitacion = $id_habitacion;
            $estados->id_habitacion_estado = 1;
            $estados->fecha = $temp->check_in_fecha;
            $estados->save();
        }
    }

    public function obtenerRoomType($habitacion)
    {
        $datoTipoHabitacion = TblHabitacione::select('tbl_habitaciones_tipo.room_type')->join('tbl_habitaciones_tipo', 'tbl_habitaciones.id_habitacion_tipo', '=', 'tbl_habitaciones_tipo.id')
            ->where('tbl_habitaciones.id', $habitacion)->firstOrfail();
        return $datoTipoHabitacion->room_type;
    }

    public function ActualizarOrbeBloqueo($room_type, $fecha_inicio, $fecha_fin)
    {
        $date1 = $this->formatDate($fecha_inicio);
        $date2 = $this->formatDate($fecha_fin);

        PGSchema::switchTo(Auth::user()->schema);
        $datos = [];
        $datos = TblConfig::select('value')->where('name', 'data_api')
            ->first();
        $datos = (is_null($datos)) ? ['validate' => false, 'value' => false] : ['validate' => true, 'value' => $datos->value];
        $res = (json_decode($datos['value']));
        $user = $res;

        $url = 'https://reservations.orbebooking.com/admin/OAF/AOBA-XML';
        $xmlRequest = '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
        $xmlRequest .= '<soap:Header>
              <HTNGHeader xmlns="http://htng.org/1.1/Header/"></HTNGHeader>
              <soap:Username>' . $user->user . '</soap:Username>
              <soap:Password>' . $user->pass . '</soap:Password>
              </soap:Header>
              <soap:Body>
              <InventoryUpdateRequest TimeStamp="2017-8-22T18:12:16" Version="1.00">
              <INVENTORY HotelCode="' . $user->code . '" HotelName="DIAMOND DEMO">' . "\n			";

        for ($i = $date1;$i <= $date2;$i = date("Y-m-d", strtotime($i . "+ 1 days")))
        {
            $xmlRequest .= '<Update Inv_Date="' . $i . '" Quantity="-1" Room_Type="' . (string)$room_type . '"  Task="Add"/>';
            var_dump($i);
        }
        $xmlRequest .= '</INVENTORY></InventoryUpdateRequest></soap:Body></soap:Envelope>';
        $res = $this->con($xmlRequest, $url);
        $data = $res->getBody();
        $data = simplexml_load_string($data);
        return ['validate' => true];
    }
    public function desbloquear_habitacion(Request $request)
    {

        $room_type = $this->obtenerRoomType($request->habitacion);
        $FechaBloqueo = FechaBloqueo::find($request->id);
        $date1 = $this->formatDate($FechaBloqueo->fecha_desde);
        $date2 = $this->formatDate($FechaBloqueo->fecha_hasta);
        $tipoHabitacion = TblHabitacione::find((int)$request->habitacion);
        for($i=$date1;$i<=$date2;$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
            $habitacion_false = InventarioHabitacion::whereDate('start', $i)
            ->where('habitacion_id', (int)$request->habitacion)
            ->update(['disponibilidad' => true ]);
            $descontar_inventario = Inventario::where('tbl_inventarios.tipo_habitacion_id', '=', (int)$tipoHabitacion->id_habitacion_tipo)
            ->whereDate('tbl_inventarios.start', $i)
            ->firstOrfail();
            $descontar_inventario->disponibilidad = (int)$descontar_inventario->disponibilidad + 1;
            $descontar_inventario->save();
          }

        $this->aumentar_disponibilidad_orbe($FechaBloqueo->fecha_desde, $FechaBloqueo->fecha_hasta, $room_type);
        $FechaBloqueoEliminar = FechaBloqueo::find($request->id);
        $FechaBloqueoEliminar->delete();
    }
    public function bloquear_habitaciones(Request $request)
    {
        $tipo_habitacion_id = TblHabitacione::find((int)$request->habitacion);
        $room_type = $this->obtenerRoomType($request->habitacion);
        $FechaBloqueo = FechaBloqueo::firstOrNew(['habitacion_id' => (int)$request->habitacion, 'fecha_desde' => $request->fecha_inicio, 'fecha_hasta' => $request->fecha_fin]);
        $FechaBloqueo->habitacion_id = $request->habitacion;
        $FechaBloqueo->fecha_desde = $request->fecha_inicio;
        $FechaBloqueo->fecha_hasta = $request->fecha_fin;
        $FechaBloqueo->save();
        $fecha_llegada = $this->formatDate($request->fecha_inicio);
        $fecha_salida = $this->formatDate($request->fecha_fin);
        for($i=$fecha_llegada;$i<=$fecha_salida;$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
            $habitacion_false = InventarioHabitacion::whereDate('start', $i)
            ->where('habitacion_id', (int)$request->habitacion)
            ->update(['disponibilidad' => false ]);
            $descontar_inventario = Inventario::where('tbl_inventarios.tipo_habitacion_id', '=', (int)$tipo_habitacion_id->id_habitacion_tipo)
            ->whereDate('tbl_inventarios.start', $i)
            ->firstOrfail();
            $descontar_inventario->disponibilidad = (int)$descontar_inventario->disponibilidad - 1;
            $descontar_inventario->save();
          }

        $actualizarOrbeBloqueo = $this->ActualizarOrbeBloqueo($room_type, $request->fecha_inicio, $request->fecha_fin);
    }
    public function elimina_comentario(Request $request)
    {
      Comentario::where('id', (int)$request->id)->delete();
    }
    public function cambiarestado(Request $request)
    {
        $datoTipoHabitacion = TblHabitacione::select('tbl_habitaciones_tipo.room_type')->join('tbl_habitaciones_tipo', 'tbl_habitaciones.id_habitacion_tipo', '=', 'tbl_habitaciones_tipo.id')
            ->firstOrfail();
        $estadoHab = TblHabitacionesDetalleEstado::where('id_habitacion', '=', $request->id_habitacion)
            ->where('fecha', '=', $request->fecha)
            ->orderBy('id', 'desc')
            ->first();
        $estadoHab2 = TblHabitacionesDetalleEstado::firstOrNew(['id_habitacion' => $request->id_habitacion, 'fecha' => $request->fecha, ]);
        $estadoHab2->id_habitacion = $request->id_habitacion;
        $estadoHab2->fecha = $request->fecha;
        $estadoHab2->id_habitacion_estado = $request->id_habitacion_estado;
        $estadoHab2->descripcion = $request->descripcion;
        $estadoHab2->save();

        if ($request->desde != null && $request->hasta)
        {
            $FechaBloqueo = FechaBloqueo::firstOrNew(['habitacion_id' => $request->id_habitacion, ]);
            $FechaBloqueo->habitacion_id = $request->id_habitacion;
            $FechaBloqueo->fecha_desde = $request->desde;
            $FechaBloqueo->fecha_hasta = $request->hasta;
            $FechaBloqueo->save();
        }

        PGSchema::switchTo(Auth::user()
            ->schema);
        $datos = [];
        $datos = TblConfig::select('value')->where('name', 'data_api')
            ->first();
        $datos = (is_null($datos)) ? ['validate' => false, 'value' => false] : ['validate' => true, 'value' => $datos->value];
        $res = (json_decode($datos['value']));
        $user = $res;

        $url = 'https://reservations.orbebooking.com/admin/OAF/AOBA-XML';
        $xmlRequest = '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
        $xmlRequest .= '<soap:Header>
                <HTNGHeader xmlns="http://htng.org/1.1/Header/"></HTNGHeader>
                <soap:Username>' . $user->user . '</soap:Username>
                <soap:Password>' . $user->pass . '</soap:Password>
                </soap:Header>
                <soap:Body>
                <InventoryUpdateRequest TimeStamp="2017-8-22T18:12:16" Version="1.00">
                <INVENTORY HotelCode="' . $user->code . '" HotelName="DIAMOND DEMO">' . "\n			";

        for ($i = $request->desde;$i < $request->hasta;$i = date("Y-m-d", strtotime($i . "+ 1 days")))
        {

            $xmlRequest .= '<Update Inv_Date="' . $i . '" Quantity="-1" Room_Type="' . (string)$datoTipoHabitacion->room_type . '"  Task="Add"/>';

        }

        $xmlRequest .= '</INVENTORY></InventoryUpdateRequest></soap:Body></soap:Envelope>';
        $res = $this->con($xmlRequest, $url);
        $data = $res->getBody();
        $data = simplexml_load_string($data);

        return ['validate' => true];
    }

    public function aumentar_disponibilidad_orbe($fecha_inicio, $fecha_fin, $room_type)
    {
        PGSchema::switchTo(Auth::user()->schema);
        $datos = [];
        $datos = TblConfig::select('value')->where('name', 'data_api')
            ->first();
        $datos = (is_null($datos)) ? ['validate' => false, 'value' => false] : ['validate' => true, 'value' => $datos->value];
        $res = (json_decode($datos['value']));
        $user = $res;

        $url = 'https://reservations.orbebooking.com/admin/OAF/AOBA-XML';
        $xmlRequest = '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
        $xmlRequest .= '<soap:Header>
              <HTNGHeader xmlns="http://htng.org/1.1/Header/"></HTNGHeader>
              <soap:Username>' . $user->user . '</soap:Username>
              <soap:Password>' . $user->pass . '</soap:Password>
              </soap:Header>
              <soap:Body>
              <InventoryUpdateRequest TimeStamp="2017-8-22T18:12:16" Version="1.00">
              <INVENTORY HotelCode="' . $user->code . '" HotelName="DIAMOND DEMO">' . "\n			";
        $date1 = $this->formatDate($fecha_inicio);
        $date2 = $this->formatDate($fecha_fin);
        

        for ($i = $date1;$i <= $date2;$i = date("Y-m-d", strtotime($i . "+ 1 days")))
        {
            $xmlRequest .= '<Update Inv_Date="' . $i . '" Quantity="1" Room_Type="' . (string)$room_type . '"  Task="Add"/>';

        }
        $xmlRequest .= '</INVENTORY></InventoryUpdateRequest></soap:Body></soap:Envelope>';
        $res = $this->con($xmlRequest, $url);
        $data = $res->getBody();
        $data = simplexml_load_string($data);
        return ['validate' => true];
    }
    public function aumentar_disponibilidad_orbe_c($fecha_inicio, $fecha_fin, $room_type)
    {
        PGSchema::switchTo(Auth::user()->schema);
        $datos = [];
        $datos = TblConfig::select('value')->where('name', 'data_api')
            ->first();
        $datos = (is_null($datos)) ? ['validate' => false, 'value' => false] : ['validate' => true, 'value' => $datos->value];
        $res = (json_decode($datos['value']));
        $user = $res;

        $url = 'https://reservations.orbebooking.com/admin/OAF/AOBA-XML';
        $xmlRequest = '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
        $xmlRequest .= '<soap:Header>
              <HTNGHeader xmlns="http://htng.org/1.1/Header/"></HTNGHeader>
              <soap:Username>' . $user->user . '</soap:Username>
              <soap:Password>' . $user->pass . '</soap:Password>
              </soap:Header>
              <soap:Body>
              <InventoryUpdateRequest TimeStamp="2017-8-22T18:12:16" Version="1.00">
              <INVENTORY HotelCode="' . $user->code . '" HotelName="DIAMOND DEMO">' . "\n			";
        $date1 = $this->formatDate($fecha_inicio);
        $date2 = $this->formatDate($fecha_fin);
        

        for ($i = $date1;$i < $date2;$i = date("Y-m-d", strtotime($i . "+ 1 days")))
        {
            $xmlRequest .= '<Update Inv_Date="' . $i . '" Quantity="1" Room_Type="' . (string)$room_type . '"  Task="Add"/>';

        }
        $xmlRequest .= '</INVENTORY></InventoryUpdateRequest></soap:Body></soap:Envelope>';
        $res = $this->con($xmlRequest, $url);
        $data = $res->getBody();
        $data = simplexml_load_string($data);
        return ['validate' => true];
    }


    public function cambiarestado_desbloquear(Request $request)
    {
        $datoTipoHabitacion = TblHabitacione::select('tbl_habitaciones_tipo.room_type')->join('tbl_habitaciones_tipo', 'tbl_habitaciones.id_habitacion_tipo', '=', 'tbl_habitaciones_tipo.id')
            ->firstOrfail();
        $estadoHab = TblHabitacionesDetalleEstado::where('id_habitacion', '=', $request->id_habitacion)
            ->where('fecha', '=', $request->fecha)
            ->orderBy('id', 'desc')
            ->first();
        $estadoHab2 = TblHabitacionesDetalleEstado::firstOrNew(['id_habitacion' => $request->id_habitacion, 'fecha' => $request->fecha, ]);
        $estadoHab2->id_habitacion = $request->id_habitacion;
        $estadoHab2->fecha = $request->fecha;
        $estadoHab2->id_habitacion_estado = 1;
        $estadoHab2->descripcion = $request->descripcion;
        $estadoHab2->save();

        if ($request->desde != null && $request->hasta)
        {

            $FechaBloqueo = FechaBloqueo::where('habitacion_id', $request->id_habitacion)
                ->delete();
        }

        PGSchema::switchTo(Auth::user()
            ->schema);
        $datos = [];
        $datos = TblConfig::select('value')->where('name', 'data_api')
            ->first();
        $datos = (is_null($datos)) ? ['validate' => false, 'value' => false] : ['validate' => true, 'value' => $datos->value];
        $res = (json_decode($datos['value']));
        $user = $res;

        $url = 'https://reservations.orbebooking.com/admin/OAF/AOBA-XML';
        $xmlRequest = '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
        $xmlRequest .= '<soap:Header>
                <HTNGHeader xmlns="http://htng.org/1.1/Header/"></HTNGHeader>
                <soap:Username>' . $user->user . '</soap:Username>
                <soap:Password>' . $user->pass . '</soap:Password>
                </soap:Header>
                <soap:Body>
                <InventoryUpdateRequest TimeStamp="2017-8-22T18:12:16" Version="1.00">
                <INVENTORY HotelCode="' . $user->code . '" HotelName="DIAMOND DEMO">' . "\n			";

        for ($i = $request->desde;$i <= $request->hasta;$i = date("Y-m-d", strtotime($i . "+ 1 days")))
        {

            $xmlRequest .= '<Update Inv_Date="' . $i . '" Quantity="1" Room_Type="' . (string)$datoTipoHabitacion->room_type . '"  Task="Add"/>';

        }

        $xmlRequest .= '</INVENTORY></InventoryUpdateRequest></soap:Body></soap:Envelope>';
        $res = $this->con($xmlRequest, $url);
        $data = $res->getBody();
        $data = simplexml_load_string($data);

        return ['validate' => true];
    }

    private function con($xmlRequest, $url, $type = 'POST')
    {
        $client = new Client();
        $options = ['body' => $xmlRequest, 'headers' => ["Content-Type" => "text/xml", "accept" => "*/*", "accept-encoding" => "gzip, deflate"]];
        $res = $client->request($type, $url, $options);
        return $res;
    }

    public function EstadoLimpieza()
    {
        return ['data' => TblHabitacionesEstado::all() ];
    }

    public function listar_bloqueos()
    {

        $datos = FechaBloqueo::all();
        return ['data' => $datos];

    }
    public function actualizar_reserva(Request $request)
    {

        $Porcentaje_monto_total= DB::select('SELECT "porcentaje_monto_total"()');
        $cantidad_x_persona_adulto = DB::select('SELECT "cantidad_x_persona_adulto"()');
        $cantidad_x_persona_nino = DB::select('SELECT "cantidad_x_persona_nino"()');
        $cantidad_fija_x_habitacion = DB::select('SELECT "cantidad_fija_x_habitacion"()');
        TblReservasDetalle::where('id_reservas_grupo', $request->id_grupo_reserva)
            ->update(['id_habitacion' => $request->habitacion]);
        $this->CrearEstadoHabitacion($request->id_grupo_reserva, $request->habitacion);
        $reserva_grupo = TblReservasGrupo::find($request->id_grupo_reserva);
        $reserva_grupo->id_reservas_estado = 1;
        $reserva_grupo->tipo_habitacion_id = $request->data['id_habitacion_tipo'];
        $reserva_grupo->save();
        $cuenta = Cuenta::where('reserva_id', (int)$request->data['id'])->first();
        $cuenta_id = $cuenta->id;
        for ($i = $this->formatDate($request->data['fecha_llegada']);$i < $this->formatDate($request->data['fecha_salida']);$i = date("Y-m-d", strtotime($i . "+ 1 days")))
        {
          $reserva_detalle = TblReservasDetalle::whereDate('check_in_fecha', $i)->where('id_reservas_grupo', $request->data['id_grupo'])->first();
          $cuenta_reserva = new CuentaReserva();
          $cuenta_reserva->reserva_id = (int)$request->data['id'];
          $cuenta_reserva->cuenta_id = (int)$cuenta_id;
          $cuenta_reserva->fecha_hora = $i;
          $cuenta_reserva->habitacion_id = (int)$request->habitacion;
          $cuenta_reserva->concepto = 'Hospedaje';
          $cuenta_reserva->tipo_movimiento = 'H';
          $cuenta_reserva->grupo_id = (int)$request->data['id_grupo'];
          $cuenta_reserva->cargo = $reserva_detalle->tarifa_neta;
          $cuenta_reserva->save();
          if ($Porcentaje_monto_total > 0) {
              $this->SavePorcentajeMontoTotal($reserva_detalle, (int)$request->data['id'], $cuenta_id);
          }
          if ($cantidad_fija_x_habitacion > 0) {
            $this->SaveCantidadFijaHabitacion($reserva_detalle, (int)$request->data['id'], $cuenta_id);
          }
          if ($cantidad_x_persona_adulto > 0) {
            $this->SaveAdulto($reserva_detalle, (int)$request->data['id'], $cuenta_id);
          }
          if ($cantidad_x_persona_nino > 0) {
            $this->SaveNino($reserva_detalle, (int)$request->data['id'], $cuenta_id);
          }
          $habitacion_false = InventarioHabitacion::where('tipo_habitacion_id', '=', (int)$request->data['id_habitacion_tipo'])
          ->whereDate('start', $i)
          ->where('habitacion_id', (int)$request->habitacion)
          ->update(['disponibilidad' => false ]);
          $descontar_inventario = Inventario::where('tipo_habitacion_id', '=', (int)$request->data['id_habitacion_tipo'])
          ->whereDate('start', $i)
          ->firstOrfail();
          $descontar_inventario->disponibilidad = (int)$descontar_inventario->disponibilidad - 1;
          $descontar_inventario->save();
        }

        return ['data' => 'almacenado'];
    }

    public function desasignar(Request $request)
    {
      CuentaReserva::where('grupo_id', (int)$request->grupo)->delete();
      TblReservasDetalle::where('id_reservas_grupo', (int)$request->grupo)
          ->update(['id_habitacion' => null]);

      // for ($i = $this->formatDate($request->fecha_ingreso);$i < $this->formatDate($request->fecha_salida);$i = date("Y-m-d", strtotime($i . "+ 1 days")))
      // {
      //   $habitacion_false = InventarioHabitacion::where('tipo_habitacion_id', '=', (int)$request->id_habitacion_tipo)
      //   ->whereDate('start', $i)
      //   ->where('habitacion_id', (int)$request->habitacion_id)
      //   ->update(['disponibilidad' => true ]);
      //   $descontar_inventario = Inventario::where('tipo_habitacion_id', '=', (int)$request->id_habitacion_tipo)
      //   ->whereDate('start', $i)
      //   ->firstOrfail();
      //   $descontar_inventario->disponibilidad = (int)$descontar_inventario->disponibilidad + 1;
      //   $descontar_inventario->save();
      //
      // }

    }
    private function datos($id)
    {

        $pagadores = TblReservaPagador::select('tbl_reservas_pagadores.id', 'tbl_reservas_pagadores.cliente_id', 'tbl_reservas_pagadores.reserva_detalle_id', 'tbl_reservas_pagadores.valor_a_pagar', 'tbl_clientes.id as identificador_cliente', 'tbl_clientes.nombre', 'tbl_clientes.apellido', 'tbl_reservas_pagadores.reserva_grupo_id'
)->where('reserva_detalle_id', '=', $id)->join('tbl_clientes', 'tbl_clientes.id', '=', 'tbl_reservas_pagadores.cliente_id')
            ->get();
        return $pagadores;
    }

    private function datos_impuestos()
    {

        $valor_impuesto = TblImpuesto::where('principal', '=', true)->firstOrfail();
        $impuestos = floatval($valor_impuesto->valor);
        return $impuestos;
    }

    private function datos_pagadores_agrupados($id)
    {

        $pagadores = TblReservaPagador::select('tbl_reservas_pagadores.id', 'tbl_reservas_pagadores.cliente_id', 'tbl_reservas_pagadores.reserva_detalle_id', 'tbl_reservas_pagadores.valor_a_pagar', 'tbl_clientes.id as identificador_cliente', 'tbl_clientes.nombre', 'tbl_clientes.apellido', 'tbl_reservas_pagadores.reserva_grupo_id'
)->where('tbl_reservas_pagadores.reserva_grupo_id', '=', $id)->join('tbl_clientes', 'tbl_clientes.id', '=', 'tbl_reservas_pagadores.cliente_id')
            ->groupBy('tbl_reservas_pagadores.id', 'tbl_reservas_pagadores.cliente_id', 'tbl_reservas_pagadores.reserva_detalle_id', 'tbl_reservas_pagadores.valor_a_pagar', 'tbl_clientes.id', 'tbl_clientes.nombre', 'tbl_clientes.apellido'
)
            ->get();

        return $pagadores;
    }

    public function reservas_grupos(Request $request)
    {

        if ($request->habitacion == 'todas')
        {

            $reservas = TblReservasDetalle::select('tbl_reservas.id as reserva_id', 'tbl_reservas_detalle.id', 'tbl_reservas_detalle.id_reservas_grupo', 'tbl_reservas_detalle.id_habitacion_tipo', 'tbl_reservas_detalle.id_habitacion', 'tbl_reservas_detalle.adultos_cantidad', 'tbl_reservas_detalle.ninos_cantidad', 'tbl_reservas_detalle.infantes_cantidad', 'tbl_reservas_detalle.habitacion_precio_total', 'tbl_reservas_detalle.requisitos', 'tbl_reservas_detalle.comentarios', 'tbl_reservas_detalle.precio_total', 'tbl_reservas_detalle.precio_neto', 'tbl_reservas_detalle.check_in_fecha', 'tbl_reservas_detalle.check_out_fecha', 'tbl_reservas_detalle.numero_impuesto', 'tbl_reservas_detalle.numero_impuesto2', 'tbl_reservas_detalle.servicio', 'tbl_habitaciones.numero'
)
->where('tbl_reservas.id', '=', $request->reserva_id)
                ->where('tbl_reservas_detalle.facturado', '=', false)
                ->leftjoin('tbl_habitaciones', 'tbl_habitaciones.id', '=', 'tbl_reservas_detalle.id_habitacion')
                ->leftjoin('tbl_reservas_grupo', 'tbl_reservas_grupo.id', '=', 'tbl_reservas_detalle.id_reservas_grupo')
                ->leftjoin('tbl_reservas', 'tbl_reservas.id', '=', 'tbl_reservas_grupo.id_reservas')
                ->orderBy('tbl_habitaciones.numero', 'ASC')
                ->orderBy('tbl_reservas_detalle.check_in_fecha', 'ASC')
                ->get();

            $data_agrupados = array();
            foreach ($reservas as $key => $temp)
            {

                $temp->pagadores = $this->datos($temp->id);
                $temp->impuestos = $this->datos_impuestos();
                $temp->pagadores_agrupados = $this->datos_pagadores_agrupados($temp->id_reservas_grupo);
                $data_agrupados[] = $temp->pagadores_agrupados;
                $reservas[$key] = $temp;
            }

            return ['reservas' => $reservas, 'datos_pagadores_agrupados' => $data_agrupados[0]];
        }
        else
        {

            $reservas = TblReservasDetalle::select('tbl_reservas.id as reserva_id', 'tbl_reservas_detalle.id', 'tbl_reservas_detalle.id_reservas_grupo', 'tbl_reservas_detalle.id_habitacion_tipo', 'tbl_reservas_detalle.id_habitacion', 'tbl_reservas_detalle.adultos_cantidad', 'tbl_reservas_detalle.ninos_cantidad', 'tbl_reservas_detalle.infantes_cantidad', 'tbl_reservas_detalle.habitacion_precio_total', 'tbl_reservas_detalle.requisitos', 'tbl_reservas_detalle.comentarios', 'tbl_reservas_detalle.precio_total', 'tbl_reservas_detalle.precio_neto', 'tbl_reservas_detalle.check_in_fecha', 'tbl_reservas_detalle.check_out_fecha', 'tbl_reservas_detalle.numero_impuesto', 'tbl_reservas_detalle.numero_impuesto2', 'tbl_reservas_detalle.servicio', 'tbl_habitaciones.numero'
)
->where('tbl_reservas.id', '=', $request->reserva_id)
                ->where('tbl_reservas_detalle.id_habitacion', '=', $request->habitacion)
                ->where('tbl_reservas_detalle.facturado', '=', false)

                ->leftjoin('tbl_habitaciones', 'tbl_habitaciones.id', '=', 'tbl_reservas_detalle.id_habitacion')

                ->leftjoin('tbl_reservas_grupo', 'tbl_reservas_grupo.id', '=', 'tbl_reservas_detalle.id_reservas_grupo')

                ->leftjoin('tbl_reservas', 'tbl_reservas.id', '=', 'tbl_reservas_grupo.id_reservas')
                ->orderBy('tbl_reservas_detalle.check_in_fecha', 'ASC')

                ->get();

            $data_agrupados = array();
            foreach ($reservas as $key => $temp)
            {

                $temp->pagadores = $this->datos($temp->id);
                $temp->impuestos = $this->datos_impuestos();
                $temp->pagadores_agrupados = $this->datos_pagadores_agrupados($temp->id_reservas_grupo);
                $data_agrupados[] = $temp->pagadores_agrupados;
                $reservas[$key] = $temp;
            }

            if ($data_agrupados == [])
            {
                return ['reservas' => $reservas, 'datos_pagadores_agrupados' => ''];
            }
            else
            {
                return ['reservas' => $reservas, 'datos_pagadores_agrupados' => $data_agrupados[0]];
            }
        }
    }

    public function impuestos()
    {

        $impuestos = TblImpuesto::select('id', 'nombre', 'valor')->groupBy('id')
            ->get();
        return ['impuestos' => $impuestos];
    }

    public function elegir_pagador(Request $request)
    {

        $pagador = TblReservaPagador::where('reserva_detalle_id', '=', $request->reserva_detalle_id)
            ->where('valor_a_pagar', '>', 0)
            ->firstOrfail();

        $pagador_reserva = TblReservaPagador::where('reserva_detalle_id', '=', $request->reserva_detalle_id)
            ->where('id', '=', $request->pagador_id)
            ->firstOrfail();
        $pagador_reserva->valor_a_pagar = $pagador->valor_a_pagar;
        $pagador_reserva->save();
        return ['pagador_reserva' => $pagador_reserva];
    }

    public function get_clientes()
    {
      $datos = TblCliente::select('tbl_clientes.id as code', DB::raw("concat_ws(' ', nombre, apellido) AS name"))
      ->get();
      return ['datos' => $datos];
    }

    public function get_zonas()
    {
      $datos = Zona::select('tbl_zonas_horarias.id as code', 'tbl_zonas_horarias.name')
      ->get();
      return ['datos' => $datos];
    }

    public function agregar_pagador(Request $request)
    {

        $data_agrupados2 = array();
        foreach ($request->ReservasGrupos as $key => $data)
        {

            // $pagadores = TblReservaPagador::where('reserva_detalle_id', '=', $data['id'])->firstOrfail();
            $huesped_existencia = TblReservaPagador::where('cliente_id', '=', $request->id_cliente)
                ->where('reserva_grupo_id', '=', (int)$data['id_reservas_grupo'])->exists();

            if ($huesped_existencia == true)
            {

                return ['nuevo_pagador' => true];
            }
            else
            {
                $nuevo_pagador = TblReservaPagador::firstOrNew(['cliente_id' => $request->id_cliente, 'reserva_grupo_id' => (int)$data['id_reservas_grupo'], ]);
                $nuevo_pagador->cliente_id = $request->id_cliente;
                $nuevo_pagador->reserva_grupo_id = (int)$data['id_reservas_grupo'];
                $nuevo_pagador->valor_a_pagar = 0;
                $nuevo_pagador->save();

                $pagador2 = TblReservaPagador::select(

                'tbl_reservas_pagadores.id', 'tbl_reservas_pagadores.cliente_id', 'tbl_reservas_pagadores.reserva_detalle_id', 'tbl_reservas_pagadores.valor_a_pagar', 'tbl_clientes.id as identificador_cliente', 'tbl_clientes.nombre', 'tbl_clientes.apellido', 'tbl_reservas_pagadores.reserva_grupo_id')
->where('tbl_reservas_pagadores.reserva_grupo_id', '=', $data['id_reservas_grupo'])->where('cliente_id', '=', $nuevo_pagador->cliente_id)
                    ->join('tbl_clientes', 'tbl_clientes.id', '=', 'tbl_reservas_pagadores.cliente_id')

                    ->get();

                $data_agrupados2[] = $pagador2;
            }

            $pagador = TblReservaPagador::select(

            'tbl_reservas_pagadores.id', 'tbl_reservas_pagadores.cliente_id', 'tbl_reservas_pagadores.reserva_detalle_id', 'tbl_reservas_pagadores.valor_a_pagar', 'tbl_clientes.id as identificador_cliente', 'tbl_clientes.nombre', 'tbl_clientes.apellido', 'tbl_reservas_pagadores.reserva_grupo_id'
)->where('tbl_reservas_pagadores.reserva_grupo_id', '=', $nuevo_pagador->reserva_grupo_id)
                ->where('cliente_id', '=', $nuevo_pagador->cliente_id)
                ->join('tbl_clientes', 'tbl_clientes.id', '=', 'tbl_reservas_pagadores.cliente_id')

                ->firstOrfail();

            return ['nuevo_pagador' => $pagador, 'nuevo_pagador2' => $data_agrupados2];
        }
    }

    public function habitaciones_grupos(Request $request)
    {

        $habitaciones = TblReservasDetalle::select('tbl_habitaciones.id', 'tbl_habitaciones.numero'
)
->where('tbl_reservas_grupo.id_reservas', '=', $request->reserva_id)
            ->where('tbl_reservas_detalle.facturado', '=', false)
            ->join('tbl_habitaciones', 'tbl_habitaciones.id', '=', 'tbl_reservas_detalle.id_habitacion')

            ->join('tbl_reservas_grupo', 'tbl_reservas_grupo.id', '=', 'tbl_reservas_detalle.id_reservas_grupo')

            ->join('tbl_reservas', 'tbl_reservas.id', '=', 'tbl_reservas_grupo.id_reservas')

            ->groupBy('tbl_habitaciones.id', 'tbl_habitaciones.numero')

            ->get();

        return ['habitaciones' => $habitaciones];
    }

    public function cancelar_factura(Request $request)
    {

        foreach ($request->datos as $value)
        {

            $EstadoFactura = TblReservasDetalle::select('id_reservas_grupo', 'id_habitacion_tipo', 'adultos_cantidad', 'ninos_cantidad', 'infantes_cantidad', 'habitacion_precio_total', 'requisitos', 'comentarios', 'precio_total', 'check_in_fecha', 'check_out_fecha', 'facturado'
)->where('id_reservas_grupo', '=', $value['id'])->update(['facturado' => false]);

            foreach ($value['pagadores'] as $pagador)
            {

                $ConsumosExtras = ConsumoExtra::where('reserva_detalle_id', $pagador['reservas_detalle_id'])->delete();

            }

            $TblTotalFactura = TotalFactura::where('factura_id', $value['factura_id'])->get();
            foreach ($TblTotalFactura as $totalFactura)
            {
                $EliminarTotalFactura = TotalFactura::find($totalFactura['id']);
                $EliminarTotalFactura->delete();
            }

            $PagoTotal = PagoTotal::where('factura_id', $value['factura_id'])->get();
            foreach ($PagoTotal as $pago)
            {
                $pago = PagoTotal::find($pago['id']);
                $pago->delete();
            }

            $TipoPagoTotalDetalles = TipoPagoTotalDetalle::select('tbl_tipo_pagos_totales_detalles.id')->join('tbl_tipo_pagos_totales', 'tbl_tipo_pagos_totales_detalles.tipo_pago_total_id', '=', 'tbl_tipo_pagos_totales.id')
                ->where('tbl_tipo_pagos_totales.factura_id', $value['factura_id'])->get();
            foreach ($TipoPagoTotalDetalles as $tipoPagoDetalle)
            {
                $tipoPagoDetalle = TipoPagoTotalDetalle::find($tipoPagoDetalle['id']);
                $tipoPagoDetalle->delete();
            }

            $TipoPagoTotal = TipoPagoTotal::where('factura_id', $value['factura_id'])->get();
            foreach ($TipoPagoTotal as $tipoPago)
            {
                $tipoPago = TipoPagoTotal::find($tipoPago['id']);
                $tipoPago->delete();
            }

            $FacturaDetalle = FacturaDetalle::where('factura_id', $value['factura_id'])->get();
            foreach ($FacturaDetalle as $value2)
            {
                $PagadorFactura = PagadorFactura::where('factura_detalle_id', $value2['id'])->get();
                foreach ($PagadorFactura as $pagador)
                {
                    $eliminarPagadores = PagadorFactura::find($pagador['id']);
                    $eliminarPagadores->delete();
                }

                $FacturaDetalle2 = FacturaDetalle::find($value2['id']);
                $FacturaDetalle2->delete();

            }

            $Facturas = Factura::where('id', $value['factura_id'])->get();

            foreach ($Facturas as $factura)
            {
                $eliminarFactura = Factura::find($factura['id']);
                $eliminarFactura->delete();
            }
        }
        $TblReservasGrupo = TblReservasGrupo::where('id', $request->id)
            ->get();
        foreach ($TblReservasGrupo as $value3)
        {
            $TblReservasGrupo = TblReservasGrupo::find($value3['id']);
            $TblReservasGrupo->facturado = false;
            $TblReservasGrupo->save();
        }

    }

    public function guardar_factura(Request $request)
    {

        $servicio = (isset($request->reservas[0]['servicio'])) ? $request->reservas[0]['servicio'] : 0;
        $grupo_id = $request->reservas[0]['id_reservas_grupo'];

        $factura = Factura::firstOrNew(['reserva_id' => $request->reserva_id,

        ]);
        $factura->id_reservas_grupo = $grupo_id;
        $factura->total_factura = $request->total;
        $factura->total_neto = $request->total_neto;
        $factura->cajero_id = Auth::user()->id;
        $factura->reserva_id = $request->reserva_id;
        $factura->save();

        foreach ($request->reservas as $valor)
        {

            $servicio = (isset($valor['servicio'])) ? $valor['servicio'] : 0;
            $facturaDetalle = new FacturaDetalle();
            $facturaDetalle->factura_id = $factura->id;
            $facturaDetalle->reserva_detalle_id = $valor['id'];
            $facturaDetalle->check_in_fecha = $valor['check_in_fecha'];
            $facturaDetalle->id_habitacion_tipo = $valor['id_habitacion_tipo'];
            $facturaDetalle->id_habitacion = $valor['id_habitacion'];
            $facturaDetalle->precio_unitario = $valor['precio_neto'];
            $facturaDetalle->precio_bruto = $valor['precio_bruto'];
            $facturaDetalle->descuento = $valor['descuento'];
            $facturaDetalle->reserva_id = $valor['reserva_id'];
            $facturaDetalle->numero_impuesto1 = $valor['numero_impuesto'];
            $facturaDetalle->numero_impuesto2 = $valor['numero_impuesto2'];
            $facturaDetalle->servicio = $servicio;
            $facturaDetalle->cajero_id = Auth::user()->id;
            $facturaDetalle->facturado = false;
            $facturaDetalle->save();

            $EstadoFactura = TblReservasDetalle::select('id_reservas_grupo', 'id_habitacion_tipo', 'adultos_cantidad', 'ninos_cantidad', 'infantes_cantidad', 'habitacion_precio_total', 'requisitos', 'comentarios', 'precio_total', 'check_in_fecha', 'check_out_fecha', 'facturado'
)->where('id', '=', $valor['id'])->where('id_reservas_grupo', '=', $valor['id_reservas_grupo'])->update(['facturado' => true]);

            $EstadoGrupo = TblReservasGrupo::where('id', '=', $valor['id_reservas_grupo'])->where('facturado', '=', false)
                ->get();

            foreach ($EstadoGrupo as $grupo)
            {

                $grupoFactura = TblReservasGrupo::find((int)$grupo['id']);
                $grupoFactura->facturado = false;
                $grupoFactura->save();

            }

            foreach ($valor['pagadores_agrupados'] as $pagador)
            {

                if ($pagador['valor_a_pagar'] == null)
                {
                    $valor_pago = 0;
                }
                else
                {
                    $valor_pago = $pagador['valor_a_pagar'];
                }

                $PagadorFactura = new PagadorFactura();
                $PagadorFactura->factura_detalle_id = $facturaDetalle->id;
                $PagadorFactura->cliente_id = $pagador['cliente_id'];
                $PagadorFactura->valor_a_pagar = (float)$valor_pago;
                // $PagadorFactura->valor_a_pagar_neto = (((float)$valor_pago) / ((( 100+ (float)$suma_impuestos))/100));
                $PagadorFactura->cajero_id = Auth::user()->id;
                $PagadorFactura->save();

            }

        }
    }

    private function VerHabitacionesTiposDisponibles()
    {
        $data = TblHabitacionesTipo::all();

        $res = array();
        foreach ($data as $temp)
        {
            $total = TblHabitacione::where('id_habitacion_tipo', '=', $temp->id)
                ->count();
            for ($i = 0;$i < 70;$i++)
            {
                $temp2 = array();
                $temp2['check_in_fecha'] = date("Y-m-d", strtotime((date('Y-m-d')) . "+ " . ($i) . " days"));
                $temp2['room_type'] = $temp->room_type;
                $data1 = TblReservasDetalle::select(DB::raw('count(*) AS total'))->groupBy('tbl_reservas_detalle.check_in_fecha')
                    ->where('tbl_reservas_detalle.check_in_fecha', '=', $temp2['check_in_fecha'])->whereNull('tbl_reservas_detalle.id_habitacion')
                    ->where('tbl_reservas_detalle.id_habitacion_tipo', '=', $temp->id) //$temp->id es id_tipo_habitacion

                    ->orderBy('tbl_reservas_detalle.check_in_fecha')
                    ->first();
                //->toSql();
                $temp2['total'] = (is_null($data1) ? $total : $data1->total);
                $res[] = (object)$temp2;
            }

        }
        return $res;
    }
    private function cant_habitaciones($id_habitacion_tipo)
    {
    }

    public function actualizarInventario(Request $request, $llegada, $salida, $tipoHabitacion, $arregloBitacora)
    {
        $actualizar = new ApiController($request);
        $actualizar->CargarReservas(Auth::user()->schema);
        $data = $this->VerHabitacionesTiposDisponibles();
        $resxml = $actualizar->ActualizarInventario($data, $request->id_grupo, $llegada, $salida, $tipoHabitacion, $arregloBitacora);
        return $resxml;
    }
    private function total_habitaciones($total, $id_habitacion_tipo)
    {
        $data = TblHabitacione::where('tbl_habitaciones.id_habitacion_tipo', '=', $id_habitacion_tipo)->count();
        return ($data - $total < 0) ? 0 : $data - $total;
    }
    private function impuestos_factura($id)
    {
        $ImpuestoFactura = ImpuestoFactura::where('factura_detalle_id', '=', $id)->get();
        return $ImpuestoFactura;
    }
    private function pagos($id)
    {
        $pagos = TipoPagoPagador::join('tbl_tipo_pagos', 'tbl_tipo_pagos.id', '=', 'tbl_tipo_pagos_pagadores.tipo_pago_id')->get();
        return $pagos;
    }
    private function datos_pagadores_facturas($id)
    {

        $pagadores = PagadorFactura::select(

        'tbl_pagadores_facturas.cliente_id', 'tbl_clientes.nombre', 'tbl_clientes.apellido', 'tbl_reservas_detalle.id_reservas_grupo', DB::raw("SUM(tbl_pagadores_facturas.valor_a_pagar) as valor_a_pagar") , 'tbl_pagadores_facturas.id as id_tbl_pagador_factura', 'tbl_pagadores_facturas.valor_pagado', 'tbl_reservas_detalle.check_in_fecha', 'tbl_reservas_detalle.id as reservas_detalle_id', 'tbl_facturas_detalles.numero_impuesto1', 'tbl_facturas_detalles.numero_impuesto2', 'tbl_facturas_detalles.servicio'
)->join('tbl_clientes', 'tbl_clientes.id', '=', 'tbl_pagadores_facturas.cliente_id')
            ->join('tbl_facturas_detalles', 'tbl_pagadores_facturas.factura_detalle_id', '=', 'tbl_facturas_detalles.id')
            ->join('tbl_reservas_detalle', 'tbl_facturas_detalles.reserva_detalle_id', '=', 'tbl_reservas_detalle.id')
            ->where('tbl_reservas_detalle.id_reservas_grupo', '=', $id)
->groupBy('tbl_pagadores_facturas.cliente_id', 'tbl_clientes.nombre', 'tbl_clientes.apellido', 'tbl_reservas_detalle.id_reservas_grupo', 'tbl_pagadores_facturas.id', 'tbl_pagadores_facturas.valor_pagado', 'tbl_reservas_detalle.check_in_fecha', 'tbl_reservas_detalle.id', 'tbl_facturas_detalles.numero_impuesto1', 'tbl_facturas_detalles.numero_impuesto2', 'tbl_facturas_detalles.servicio'
)
            ->orderBy('tbl_clientes.nombre', 'asc')
            ->get();

        foreach ($pagadores as $key => $temp)
        {

            $temp->pagos = $this->pagos($temp->id);
            $temp->total_consumos = $this->total_consumos($temp->cliente_id, $temp->reservas_detalle_id);
            $temp->consumos_extras = $this->consumosExtras($temp->cliente_id, $temp->reservas_detalle_id);
            $pagadores[$key] = $temp;
        }

        return $pagadores;
    }

    private function datos_pagadores_facturas2($id, $cliente)
    {

        $pagadores = PagadorFactura::select(

        'tbl_pagadores_facturas.cliente_id', 'tbl_clientes.nombre', 'tbl_clientes.apellido', 'tbl_reservas_detalle.id_reservas_grupo', DB::raw("SUM(tbl_pagadores_facturas.valor_a_pagar) as valor_a_pagar") , 'tbl_pagadores_facturas.id as id_tbl_pagador_factura', 'tbl_pagadores_facturas.valor_pagado', 'tbl_reservas_detalle.check_in_fecha', 'tbl_reservas_detalle.id as reservas_detalle_id'
)->join('tbl_clientes', 'tbl_clientes.id', '=', 'tbl_pagadores_facturas.cliente_id')
            ->join('tbl_facturas_detalles', 'tbl_pagadores_facturas.factura_detalle_id', '=', 'tbl_facturas_detalles.id')
            ->join('tbl_reservas_detalle', 'tbl_facturas_detalles.reserva_detalle_id', '=', 'tbl_reservas_detalle.id')
            ->where('tbl_reservas_detalle.id_reservas_grupo', '=', $id)->where('tbl_pagadores_facturas.cliente_id', '=', $cliente)
->groupBy('tbl_pagadores_facturas.cliente_id', 'tbl_clientes.nombre', 'tbl_clientes.apellido', 'tbl_reservas_detalle.id_reservas_grupo', 'tbl_pagadores_facturas.id', 'tbl_pagadores_facturas.valor_pagado', 'tbl_reservas_detalle.check_in_fecha', 'tbl_reservas_detalle.id'
)
            ->orderBy('tbl_clientes.nombre', 'asc')
            ->get();

        foreach ($pagadores as $key => $temp)
        {

            $temp->pagos = $this->pagos($temp->id);
            $temp->total_consumos = $this->total_consumos($temp->cliente_id, $temp->reservas_detalle_id);
            $temp->consumos_extras = $this->consumosExtras($temp->cliente_id, $temp->reservas_detalle_id);
            $pagadores[$key] = $temp;
        }

        return $pagadores;
    }

    public function consumosExtras($cliente, $detalle_reserva)
    {

        $datos = ConsumoExtra::select('tbl_consumos_extras.id', 'tbl_consumos_extras.producto_id', 'tbl_consumos_extras.impuesto_id', 'tbl_consumos_extras.reserva_pagador_id', 'tbl_consumos_extras.cliente_id', 'tbl_consumos_extras.reserva_detalle_id', 'tbl_consumos_extras.total_consumo', 'tbl_consumos_extras.numero_habitacion', 'tbl_consumos_extras.fecha', 'tbl_productos.nombre as producto', 'tbl_impuestos_productos.nombre as impuesto', 'tbl_puntos_ventas.nombre as punto_venta', 'tbl_consumos_extras.punto_de_venta_id', 'tbl_consumos_extras.categoria_id', 'tbl_consumos_extras.unidad'
)
->where('tbl_consumos_extras.cliente_id', '=', $cliente)->where('tbl_consumos_extras.reserva_detalle_id', '=', $detalle_reserva)
->join('tbl_productos', 'tbl_consumos_extras.producto_id', '=', 'tbl_productos.id')
            ->join('tbl_impuestos_productos', 'tbl_consumos_extras.impuesto_id', '=', 'tbl_impuestos_productos.id')
            ->join('tbl_puntos_ventas', 'tbl_productos.punto_de_venta_id', '=', 'tbl_puntos_ventas.id')

            ->get();

        return $datos;

    }

    public function total_consumos($cliente, $reserva_detalle_id)
    {

        $datos = ConsumoExtra::where('tbl_consumos_extras.cliente_id', '=', $cliente)->where('tbl_consumos_extras.reserva_detalle_id', '=', $reserva_detalle_id)->get();

        $sum = 0;
        foreach ($datos as $variable)
        {

            $sum += (float)$variable['total_consumo'];
        }

        return $sum;

    }

    public function tipo_pagos_pagadores(Request $request)
    {

        $pagos = TipoPagoTotalDetalle::select('tbl_tipo_pagos.nombre', 'tbl_tipo_pagos_totales_detalles.id', 'tbl_tipo_pagos_totales_detalles.valor_pagado', 'tbl_tipo_pagos_totales_detalles.tipo_pago_id', 'tbl_tipo_pagos_totales.valor_a_pagar', 'tbl_tipo_pagos_totales.cliente_id', 'tbl_tipo_pagos_totales_detalles.created_at as fecha_pago')
->join('tbl_tipo_pagos_totales', 'tbl_tipo_pagos_totales_detalles.tipo_pago_total_id', '=', 'tbl_tipo_pagos_totales.id')
            ->join('tbl_tipo_pagos', 'tbl_tipo_pagos_totales_detalles.tipo_pago_id', '=', 'tbl_tipo_pagos.id')
            ->where('tbl_tipo_pagos_totales.reserva_id', '=', $request->reserva_id)
            ->where('tbl_tipo_pagos_totales.cliente_id', '=', $request->cliente_id)

            ->get();

        return ['tipo_pagos' => $pagos];

    }

    public function reserva_factura(Request $request)
    {

        $FacturaDetalle = FacturaDetalle::select(DB::raw("SUM(tbl_facturas_detalles.precio_bruto) as precio_bruto") ,

        'tbl_habitaciones.numero', 'tbl_reservas_grupo.id', 'tbl_facturas_detalles.factura_id', 'tbl_facturas_detalles.numero_impuesto1', 'tbl_facturas_detalles.numero_impuesto2', 'tbl_facturas_detalles.servicio', 'tbl_reservas.id as clave_reserva'
)->join('tbl_reservas_detalle', 'tbl_reservas_detalle.id', '=', 'tbl_facturas_detalles.reserva_detalle_id')

            ->join('tbl_reservas_grupo', 'tbl_reservas_grupo.id', '=', 'tbl_reservas_detalle.id_reservas_grupo')

            ->join('tbl_reservas', 'tbl_reservas.id', '=', 'tbl_reservas_grupo.id_reservas')

            ->join('tbl_habitaciones', 'tbl_habitaciones.id', '=', 'tbl_reservas_detalle.id_habitacion')

            ->leftjoin('tbl_pagadores_facturas', 'tbl_pagadores_facturas.factura_detalle_id', '=', 'tbl_facturas_detalles.id_habitacion')

            ->where('tbl_reservas.id', '=', (int)$request->reserva_id)

            ->groupBy('tbl_habitaciones.numero', 'tbl_reservas_grupo.id', 'tbl_facturas_detalles.precio_bruto', 'tbl_facturas_detalles.factura_id', 'tbl_facturas_detalles.numero_impuesto1', 'tbl_facturas_detalles.numero_impuesto2', 'tbl_facturas_detalles.servicio', 'tbl_reservas.id')

            ->get();

        $detalles = array();
        $facturaIdentificador = '';

        foreach ($FacturaDetalle as $key => $temp)
        {

            $temp->impuestos = $this->impuestos_factura($temp->id);
            $temp->pagadores = $this->datos_pagadores_facturas($temp->id);
            $FacturaDetalle[$key] = $temp;
            $facturaIdentificador = $temp->factura_id;
            $detalles['factura_id'] = $temp->factura_id;
        }

        $facturas = $FacturaDetalle->where('factura_id', $facturaIdentificador);
        return ['FacturaDetalle' => $facturas, 'detalles' => $detalles];
    }

    public function tipo_pagos()
    {

        $tipo_pagos = TipoPago::orderBy('nombre')->get();
        return ['tipo_pagos' => $tipo_pagos];
    }

    public function guardar_mensajes(Request $request)
    {

        $datos = new MensajesFactura();
        $datos->factura_id = $request->factura_idPago;
        $datos->cliente_id = $request->cliente_pago;
        $datos->mensaje = $request->comentario;
        $datos->save();

        return ['datos' => $datos];

    }

    public function mensajes_realizados(Request $request)
    {

        $datos = MensajesFactura::where('factura_id', $request->factura_id)
            ->where('cliente_id', $request->cliente_id)
            ->get();
        return ['data' => $datos];
    }

    public function guardar_tipopago_pagadores(Request $request)
    {
      $tipo_pago = TipoPago::find((int)$request->tipo_pago);
      $data_reserva = new CuentaReserva();
      $data_reserva->reserva_id = (int)$request->id_reserva;
      $data_reserva->cuenta_id = (int)$request->cuenta_abono;
      $data_reserva->fecha_hora =  $request->fecha_abono;
      $data_reserva->concepto = 'Pago'.'/'.$tipo_pago->nombre;
      $data_reserva->abono = $request->valor_a_pagar_f;
      $data_reserva->condicion = 2;
      $data_reserva->estado = true;
      $data_reserva->save();

      $data = new CuentaAbono();
      $data->reserva_id = (int)$request->id_reserva;
      $data->cuenta_id = (int)$request->cuenta_abono;
      $data->abono =  $request->valor_a_pagar_f;
      $data->tipo_pago_id = (int)$request->tipo_pago;
      $data->fecha_abono = $request->fecha_abono;
      $data->cuenta_reserva_id = (int)$data_reserva->id;
      $data->save();


        return 'guardado';
    }

    public function pago_realizado(Request $request)
    {
        $tipo_pagos = TipoPagoPagador::select('tbl_tipo_pagos_pagadores.id', 'tbl_tipo_pagos_pagadores.valor_pagado', 'tbl_tipo_pagos.nombre', 'tbl_tipo_pagos.id as identificador_tipo_pago')->join('tbl_tipo_pagos', 'tbl_tipo_pagos.id', '=', 'tbl_tipo_pagos_pagadores.tipo_pago_id')
            ->get();

        return ['tipo_pagos' => $tipo_pagos];
    }

    public function actualizar_tipopago_pagadores(Request $request)
    {

        $tipo_pago = TipoPagoTotalDetalle::where('id', '=', $request->id)
            ->firstOrfail();
        $tipo_pago->tipo_pago_id = $request->tipo_pago;
        $tipo_pago->valor_pagado = $request->valor_a_pagar;
        $tipo_pago->save();

        return ['factura_id' => $request->factura_id, 'cliente_id' => $request->cliente_id];

    }

    public function actualizar_mensajes(Request $request)
    {

        $mensaje = MensajesFactura::where('id', '=', $request->id)
            ->firstOrfail();
        $mensaje->mensaje = $request->comentario;
        $mensaje->save();

        return ['factura_id' => $request->factura_id, 'cliente_id' => $request->cliente_id];

    }

    public function eliminar_tipopago_pagadores(Request $request)
    {

        $tipo_pago = TipoPagoTotalDetalle::where('id', '=', $request->id)
            ->firstOrfail();
        $tipo_pago->delete();

        // $tipo_pago2 = PagoTotal::find($request->idTotalPago);
        // $tipo_pago2->valor_pagado = (float)$tipo_pago2->valor_pagado - (float)$request->valor_pagado;
        // $tipo_pago2->save();


        return ['factura_id' => $request->factura_id, 'cliente_id' => $request->cliente_id];
    }

    private function datos_pagos($pagador_factura_id)
    {

        $pagador_factura_id = TipoPagoPagador::join('tbl_tipo_pagos', 'tbl_tipo_pagos.id', '=', 'tbl_tipo_pagos_pagadores.tipo_pago_id')->get();

        return $pagador_factura_id;
    }

    public function NumeroId($numero)
    {

        $datos = TblHabitacione::select('id')->where('numero', '=', $numero)->firstOrfail();
        return $datos->id;
    }

    public function Resumenes($factura_detalle_id, $id_numero, $cliente)
    {

        $FacturaDetalle = FacturaDetalle::select(DB::raw("SUM(tbl_facturas_detalles.precio_bruto) as precio_bruto") ,

        'tbl_habitaciones.numero', 'tbl_reservas_grupo.id', 'tbl_facturas_detalles.factura_id'
)
->join('tbl_reservas_detalle', 'tbl_reservas_detalle.id', '=', 'tbl_facturas_detalles.reserva_detalle_id')

            ->join('tbl_reservas_grupo', 'tbl_reservas_grupo.id', '=', 'tbl_reservas_detalle.id_reservas_grupo')

            ->join('tbl_reservas', 'tbl_reservas.id', '=', 'tbl_reservas_grupo.id_reservas')

            ->join('tbl_habitaciones', 'tbl_habitaciones.id', '=', 'tbl_reservas_detalle.id_habitacion')

            ->leftjoin('tbl_pagadores_facturas', 'tbl_pagadores_facturas.factura_detalle_id', '=', 'tbl_facturas_detalles.id_habitacion')

            ->where('tbl_facturas_detalles.factura_id', '=', (int)$factura_detalle_id)->where('tbl_facturas_detalles.id_habitacion', '=', (int)$id_numero)->groupBy('tbl_habitaciones.numero', 'tbl_reservas_grupo.id', 'tbl_facturas_detalles.precio_bruto', 'tbl_facturas_detalles.factura_id'
)

            ->get();

        $detalles = array();

        foreach ($FacturaDetalle as $key => $temp)
        {

            $temp->impuestos = $this->impuestos_factura($temp->id);
            $temp->pagadores = $this->datos_pagadores_facturas2($temp->id, $cliente);
            $FacturaDetalle[$key] = $temp;
            $detalles['factura_id'] = $temp->factura_id;
        }

        return $FacturaDetalle;

    }

    public function pdfCajero($cajero_id, $fecha_filtro)
    {

        $fecha = explode(",", $fecha_filtro);
        $cajero = explode(",", $cajero_id);

        $pagos = TipoPagoTotalDetalle::select('tbl_tipo_pagos.nombre', 'tbl_tipo_pagos_totales_detalles.id', 'tbl_tipo_pagos_totales_detalles.valor_pagado', 'tbl_tipo_pagos_totales_detalles.tipo_pago_id', 'tbl_tipo_pagos_totales.valor_a_pagar', 'tbl_tipo_pagos_totales.cliente_id', 'tbl_tipo_pagos_totales_detalles.created_at', 'tbl_tipo_pagos_totales.factura_id', DB::raw('concat_ws(\' \',tbl_clientes.nombre,tbl_clientes.apellido) as cliente'))
->join('tbl_tipo_pagos_totales', 'tbl_tipo_pagos_totales_detalles.tipo_pago_total_id', '=', 'tbl_tipo_pagos_totales.id')
            ->join('tbl_tipo_pagos', 'tbl_tipo_pagos_totales_detalles.tipo_pago_id', '=', 'tbl_tipo_pagos.id')
            ->join('tbl_clientes', 'tbl_tipo_pagos_totales.cliente_id', '=', 'tbl_clientes.id')
            ->where('tbl_tipo_pagos_totales.cajero_id', '=', (int)$cajero[0])->whereDate('tbl_tipo_pagos_totales_detalles.created_at', '=', $fecha[0])->get();

        $nombre_cajero = User::find((int)$cajero[0]);
        //
        $tipo_pagos = TipoPago::all();
        //
        foreach ($tipo_pagos as $key => $temp)
        {

            $temp->total_tipo_pago = $this->totalTipoPago($temp->id, (int)$cajero[0], $fecha[0]);

        }

        $pdf = \PDF::loadView('pdf.facturaCajero', ['pagos' => $pagos, 'nombre_cajero' => $nombre_cajero->nombres, 'tipo_pagos' => $tipo_pagos]);

        return $pdf->stream('facturaCajero.pdf');

    }

    public function totalTipoPago($tipo_pago_id, $cajero_id, $fecha)
    {

        $pagos = TipoPagoTotalDetalle::select('tbl_tipo_pagos.nombre', 'tbl_tipo_pagos_totales_detalles.id', 'tbl_tipo_pagos_totales_detalles.valor_pagado', 'tbl_tipo_pagos_totales_detalles.tipo_pago_id', 'tbl_tipo_pagos_totales.valor_a_pagar', 'tbl_tipo_pagos_totales.cliente_id', 'tbl_tipo_pagos_totales_detalles.created_at', 'tbl_tipo_pagos_totales.factura_id', DB::raw('concat_ws(\' \',tbl_clientes.nombre,tbl_clientes.apellido) as cliente'))
->join('tbl_tipo_pagos_totales', 'tbl_tipo_pagos_totales_detalles.tipo_pago_total_id', '=', 'tbl_tipo_pagos_totales.id')
            ->join('tbl_tipo_pagos', 'tbl_tipo_pagos_totales_detalles.tipo_pago_id', '=', 'tbl_tipo_pagos.id')
            ->join('tbl_clientes', 'tbl_tipo_pagos_totales.cliente_id', '=', 'tbl_clientes.id')
            ->where('tbl_tipo_pagos_totales.cajero_id', '=', $cajero_id)->where('tbl_tipo_pagos_totales_detalles.tipo_pago_id', '=', $tipo_pago_id)->whereDate('tbl_tipo_pagos_totales_detalles.created_at', '=', $fecha)->get();

        $sum = 0;
        foreach ($pagos as $variable)
        {

            $sum += (float)$variable['valor_pagado'];
        }

        return ['suma_tipo' => $sum];

    }

    public function pdf($factura, $reserva)
    {

        $factura_detalle_id = explode(",", $factura);
        $reserva_id = explode(",", $reserva);
        $datosHotel = TblHotel::find(1);
        $hotel_nombre = $datosHotel->nombre;
        $hotel_direccion = $datosHotel->direccion;
        $ruc_hotel = $datosHotel->nit;
        $hotel_telefono = $datosHotel->responsable_telefono;
        $hotel_moneda = $datosHotel->moneda;
        $hotel_correo = $datosHotel->responsable_nombre;
        $pagadores = PagadorFactura::select(

        'tbl_reservas_detalle.check_in_fecha', 'tbl_reservas_detalle.precio_total', 'tbl_reservas_detalle.precio_neto as precio_neto_2', 'tbl_reservas_detalle.id_habitacion', 'tbl_reservas_detalle.id', 'tbl_habitaciones.numero', 'tbl_facturas_detalles.numero_impuesto1', 'tbl_facturas_detalles.numero_impuesto2', 'tbl_facturas_detalles.servicio', DB::raw("SUM(tbl_pagadores_facturas.valor_a_pagar) as precio_neto")
)
->join('tbl_clientes', 'tbl_clientes.id', '=', 'tbl_pagadores_facturas.cliente_id')
            ->join('tbl_facturas_detalles', 'tbl_pagadores_facturas.factura_detalle_id', '=', 'tbl_facturas_detalles.id')
            ->join('tbl_reservas_detalle', 'tbl_facturas_detalles.reserva_detalle_id', '=', 'tbl_reservas_detalle.id')
            ->join('tbl_habitaciones', 'tbl_reservas_detalle.id_habitacion', '=', 'tbl_habitaciones.id')

            ->where('tbl_facturas_detalles.factura_id', '=', $factura)
->groupBy(

        'tbl_reservas_detalle.check_in_fecha', 'tbl_reservas_detalle.precio_total', 'tbl_reservas_detalle.precio_neto', 'tbl_reservas_detalle.id_habitacion', 'tbl_reservas_detalle.id', 'tbl_habitaciones.numero', 'tbl_facturas_detalles.numero_impuesto1', 'tbl_facturas_detalles.numero_impuesto2', 'tbl_facturas_detalles.servicio')

            ->get();

        $factura_servicio = 44;
        $sum_total_neto = 0;
        $sum_servicio = 0;

        $impuesto = TblImpuesto::where('principal', '=', true)->firstOrfail();
        foreach ($pagadores as $key => $temp)
        {
            $temp->consumos_extras = $this->consumosExtrasF($temp->id, $temp->numero, 2);
            $temp->precio_neto = $this->toFixed(((float)($temp->precio_neto)) / ((100 + (float)($impuesto->valor)) / 100) , 2);
            $temp->precio_neto_3 = $this->toFixed($temp->precio_neto_2 / (1 + ((($temp->numero_impuesto1) + ($temp->numero_impuesto2) + ($temp->servicio)) / 100)) , 2);
            $sum_total_neto = $temp->precio_neto_2 + $sum_total_neto;
            $sum_servicio = $sum_total_neto * $temp->servicio / 100;
            $temp->tipo_pagos = $this->tipo_pagos_facturas2($factura);
            $pagadores[$key] = $temp;
        }

        $info_pagadores = PagoTotal::select(DB::raw('concat_ws(\' \',tbl_clientes.nombre,tbl_clientes.apellido) as cliente'))->join('tbl_clientes', 'tbl_pagos_totales.cliente_id', '=', 'tbl_clientes.id')
            ->where('tbl_pagos_totales.factura_id', '=', $factura)->groupBy('tbl_clientes.nombre', 'tbl_clientes.apellido')
            ->get();

        $total_extras = ConsumoExtra::select('tbl_impuestos_productos.valor', 'tbl_productos.nombre', 'tbl_productos.precio', 'tbl_consumos_extras.unidad', 'tbl_consumos_extras.total_consumo')->join('tbl_productos', 'tbl_consumos_extras.producto_id', '=', 'tbl_productos.id')
            ->join('tbl_reservas_detalle', 'tbl_consumos_extras.reserva_detalle_id', '=', 'tbl_reservas_detalle.id')
            ->join('tbl_facturas_detalles', 'tbl_facturas_detalles.reserva_detalle_id', '=', 'tbl_reservas_detalle.id')
            ->leftJoin('tbl_impuestos_productos', 'tbl_consumos_extras.servicio_id', '=', 'tbl_impuestos_productos.id')
            ->where('tbl_facturas_detalles.factura_id', $factura)->get();

        $total_neto = 0;
        $total_extra_servicio = 0;
        foreach ($total_extras as $key => $temp)
        {

            $temp->total_consumo_neto = (float)$temp->precio * (float)$temp->unidad;
            $total_neto = $temp->total_consumo_neto + $total_neto;
            $total_servicio_Extras = $temp->total_consumo_neto * $temp->valor / 100;
            $total_extra_servicio += $total_servicio_Extras;
            $total_extras[$key] = $temp;

        }

        $impuesto_producto = ImpuestoProducto::select('valor')->where('iva', true)
            ->firstOrfail();
        $valor_impuesto_producto = ($total_neto * $impuesto_producto->valor) / 100;
        $valor_servicio_producto = $total_extra_servicio;

        $reservante = TblReserva::select(DB::raw('concat_ws(\' \',tbl_clientes.nombre,tbl_clientes.apellido) as cliente') , 'tbl_clientes.telefono', 'tbl_clientes.email')->join('tbl_clientes', 'tbl_reservas.id_cliente', '=', 'tbl_clientes.id')
            ->where('tbl_reservas.id', '=', $reserva_id[0])->firstOrfail();
        $total_neto_factura = Factura::find($factura);
        $subtotal_consumos = $total_neto + $valor_servicio_producto + $valor_impuesto_producto;
        $impuesto = TblImpuesto::where('principal', '=', true)->firstOrfail();
        $valor_ish = ($total_neto_factura->total_neto * $total_neto_factura->ish) / 100;
        $valor_impuesto = ($total_neto_factura->total_neto * $impuesto->valor) / 100;
        $gran_total = $total_neto_factura->total_factura + $subtotal_consumos;
        $servicioImpuesto = TblImpuesto::where('servicio', true)->firstOrfail();
        $factura_servicio = ImpuestoProducto::where('servicio', true)->firstOrfail();
        $iva_consumo = ImpuestoProducto::where('iva', true)->firstOrfail();

        $pdf = \PDF::loadView('pdf.facturaTotal', [

        'hotel_moneda' => $hotel_moneda, 'hotel_telefono' => $hotel_telefono, 'hotel_correo' => $hotel_correo, 'hotel_nombre' => $hotel_nombre, 'hotel_direccion' => $hotel_direccion, 'ruc_hotel' => $ruc_hotel, 'factura_servicio' => $factura_servicio->nombre, 'servicio_consumo' => $factura_servicio->valor, 'servicioImpuesto' => $servicioImpuesto->nombre, 'servicio' => $servicioImpuesto->valor, 'valor_servicio' => $sum_servicio, 'factura' => $pagadores, 'no_factura' => $factura_detalle_id[0], 'reservante' => $reservante->cliente, 'telefono' => $reservante->telefono, 'email' => $reservante->email, 'pagadores' => $pagadores, 'total_neto_hab' => $this->toFixed(((float)$total_neto_factura->total_neto) , 2) , 'total_factuta' => $this->toFixed(((float)$total_neto_factura->total_factura) , 2) , 'ish' => $total_neto_factura->ish, 'valor_impuesto' => $this->toFixed(((float)$valor_impuesto) , 2) , 'valor_ish' => $this->toFixed(((float)$valor_ish) , 2) , 'ish' => $total_neto_factura->ish, 'impuesto_hab' => $impuesto->valor, 'total_neto_consumos' => $this->toFixed(((float)$total_neto) , 2) , 'impuesto_producto' => $impuesto_producto->valor, 'valor_servicio_producto' => $this->toFixed(((float)$valor_servicio_producto) , 2) , 'valor_impuesto_producto' => $this->toFixed(((float)$valor_impuesto_producto) , 2) , 'subtotal_consumos' => $this->toFixed(((float)$subtotal_consumos) , 2) , 'gran_total' => $this->toFixed(((float)$gran_total) , 2) , 'info_pagadores' => $info_pagadores]);

        return $pdf->stream('facturaTotal.pdf');
    }

    public function pdf2($factura, $reserva, $cliente, $total_a_pagar, $total_pagado)
    {

        $datosHotel = TblHotel::find(1);
        $hotel_nombre = $datosHotel->nombre;
        $hotel_direccion = $datosHotel->direccion;
        $ruc_hotel = $datosHotel->nit;
        $hotel_telefono = $datosHotel->responsable_telefono;
        $hotel_moneda = $datosHotel->moneda;
        $hotel_correo = $datosHotel->responsable_nombre;
        $cliente_id = explode(",", $cliente);
        $factura_detalle_id = explode(",", $factura);
        $reserva_id = explode(",", $reserva);
        $total_a_pagar = explode(",", $total_a_pagar);
        $total_pagado = explode(",", $total_pagado);
        $pagadores = PagadorFactura::select('tbl_reservas_detalle.check_in_fecha', 'tbl_reservas_detalle.precio_total', 'tbl_reservas_detalle.precio_neto as precio_neto_2', 'tbl_reservas_detalle.id_habitacion', 'tbl_reservas_detalle.id', 'tbl_habitaciones.numero', 'tbl_facturas_detalles.numero_impuesto1', 'tbl_facturas_detalles.numero_impuesto2', 'tbl_facturas_detalles.servicio',

        DB::raw("SUM(tbl_pagadores_facturas.valor_a_pagar) as precio_neto"))->join('tbl_clientes', 'tbl_clientes.id', '=', 'tbl_pagadores_facturas.cliente_id')
            ->join('tbl_facturas_detalles', 'tbl_pagadores_facturas.factura_detalle_id', '=', 'tbl_facturas_detalles.id')
            ->join('tbl_reservas_detalle', 'tbl_facturas_detalles.reserva_detalle_id', '=', 'tbl_reservas_detalle.id')
            ->join('tbl_habitaciones', 'tbl_reservas_detalle.id_habitacion', '=', 'tbl_habitaciones.id')
            ->where('tbl_facturas_detalles.factura_id', '=', $factura)->where('tbl_pagadores_facturas.cliente_id', '=', (int)$cliente_id[0])->groupBy('tbl_reservas_detalle.check_in_fecha', 'tbl_reservas_detalle.precio_total', 'tbl_reservas_detalle.precio_neto', 'tbl_reservas_detalle.id_habitacion', 'tbl_reservas_detalle.id', 'tbl_habitaciones.numero', 'tbl_facturas_detalles.numero_impuesto1', 'tbl_facturas_detalles.numero_impuesto2', 'tbl_facturas_detalles.servicio')
            ->get();

        $info_pagadores = PagoTotal::select(DB::raw('concat_ws(\' \',tbl_clientes.nombre,tbl_clientes.apellido) as cliente'))->join('tbl_clientes', 'tbl_pagos_totales.cliente_id', '=', 'tbl_clientes.id')
            ->where('tbl_pagos_totales.factura_id', '=', $factura)->where('tbl_pagos_totales.cliente_id', (int)$cliente_id[0])->groupBy('tbl_clientes.nombre', 'tbl_clientes.apellido')
            ->get();

        $impuesto = TblImpuesto::where('principal', '=', true)->firstOrfail();
        $impuesto2 = TblImpuesto::where('ish', '=', true)->firstOrfail();
        $sum_total_neto = 0;

        foreach ($pagadores as $key => $temp)
        {
            $temp->consumos_extras = $this->consumosExtrasF2($temp->id, $temp->numero, (int)$cliente_id[0]);
            $temp->precio_neto = $this->toFixed($temp->precio_neto / (1 + ((($temp->numero_impuesto1) + ($temp->numero_impuesto2) + ($temp->servicio)) / 100)) , 2);
            $sum_total_neto = $temp->precio_neto + $sum_total_neto;
            $temp->tipo_pagos = $this->tipo_pagos_facturas($factura, (int)$cliente_id[0]);
            $pagadores[$key] = $temp;
        }
        $total_extras = ConsumoExtra::select('tbl_productos.nombre', 'tbl_productos.precio', 'tbl_consumos_extras.unidad', 'tbl_consumos_extras.total_consumo', 'tbl_impuestos_productos.valor as valor_servicio', 'tbl_impuestos_productos_iva.valor as valor_iva')->leftJoin('tbl_productos', 'tbl_consumos_extras.producto_id', '=', 'tbl_productos.id')
            ->leftJoin('tbl_reservas_detalle', 'tbl_consumos_extras.reserva_detalle_id', '=', 'tbl_reservas_detalle.id')
            ->leftJoin('tbl_facturas_detalles', 'tbl_facturas_detalles.reserva_detalle_id', '=', 'tbl_reservas_detalle.id')
            ->leftJoin('tbl_impuestos_productos', 'tbl_consumos_extras.servicio_id', '=', 'tbl_impuestos_productos.id')
            ->leftJoin('tbl_impuestos_productos as tbl_impuestos_productos_iva', 'tbl_consumos_extras.impuesto_id', '=', 'tbl_impuestos_productos_iva.id')
            ->where('tbl_facturas_detalles.factura_id', $factura)
->where('tbl_consumos_extras.cliente_id', (int)$cliente_id[0])->get();

        $total_neto = 0;
        $sum_total_servicio = 0;
        $sum_total_iva = 0;
        foreach ($total_extras as $key => $temp)
        {
            $temp->total_consumo_neto = (float)$temp->precio * (float)$temp->unidad;
            $temp->total_consumo_neto2 = (float)$temp->precio * (float)$temp->unidad;
            $temp->total_servicio = ((float)$temp->total_consumo_neto2 * (float)$temp->valor_servicio) / 100;
            $temp->total_iva = ((float)$temp->total_consumo_neto2 * (float)$temp->valor_iva) / 100;
            $total_neto = $temp->total_consumo_neto + $total_neto;
            $sum_total_servicio += (float)$temp->total_servicio;
            $sum_total_iva += (float)$temp->total_iva;
            $total_extras[$key] = $temp;
        }

        $subtotal_consumos = $total_neto + $sum_total_servicio + $sum_total_iva;

        $impuesto_producto = ImpuestoProducto::select('valor')->where('iva', true)
            ->firstOrfail();

        $valor_impuesto_producto = ($total_neto * $impuesto_producto->valor) / 100;

        $valor_servicio_producto = ($total_neto) / 100;

        $reservante = TblReserva::select(DB::raw('concat_ws(\' \',tbl_clientes.nombre,tbl_clientes.apellido) as cliente') , 'tbl_clientes.telefono', 'tbl_clientes.email')->join('tbl_clientes', 'tbl_reservas.id_cliente', '=', 'tbl_clientes.id')
            ->where('tbl_reservas.id', '=', $reserva_id[0])->firstOrfail();

        $total_neto_factura = Factura::find($factura);

        $impuesto = TblReserva::where('id', '=', $reserva_id[0])->firstOrfail();

        $valor_servicio = ($sum_total_neto * (float)$pagadores[0]['servicio']) / 100;

        $valor_impuesto = ($sum_total_neto * (float)$pagadores[0]['numero_impuesto1']) / 100;

        $valor_impuesto2 = ($sum_total_neto * (float)$pagadores[0]['numero_impuesto2']) / 100;

        // $valor_impuesto2 = ($sum_total_neto * $impuesto2->numero_impuesto) / 100;
        $total_facturacion = (float)$sum_total_neto + (float)$valor_servicio + (float)$valor_impuesto + (float)$valor_impuesto2;

        $gran_total = (float)$total_facturacion + $subtotal_consumos;

        $impuestoNumero1 = TblImpuesto::where('principal', true)->firstOrfail();

        $impuestoNumero2 = TblImpuesto::where('ish', true)->firstOrfail();

        $servicioImpuesto = TblImpuesto::where('servicio', true)->firstOrfail();

        $saldo_pendiente = $this->toFixed(((float)$total_a_pagar[0] - (float)$total_pagado[0]) , 2);

        $factura_servicio = ImpuestoProducto::where('servicio', true)->firstOrfail();

        $iva_consumo = ImpuestoProducto::where('iva', true)->firstOrfail();

        $pdf = \PDF::loadView('pdf.factura', ['hotel_moneda' => $hotel_moneda, 'hotel_telefono' => $hotel_telefono, 'hotel_correo' => $hotel_correo, 'hotel_nombre' => $hotel_nombre, 'hotel_direccion' => $hotel_direccion, 'ruc_hotel' => $ruc_hotel, 'factura' => $pagadores, 'impuestoNumero1' => $impuestoNumero1->nombre, 'impuestoNumero2' => $impuestoNumero2->nombre, 'servicioImpuesto' => $servicioImpuesto->nombre, 'no_factura' => $factura_detalle_id[0], 'reservante' => $reservante->cliente, 'telefono' => $reservante->telefono, 'email' => $reservante->email, 'pagadores' => $pagadores, 'total_neto_hab' => $this->toFixed(((float)$sum_total_neto) , 2) , 'total_factuta' => $this->toFixed(((float)$total_facturacion) , 2) , 'servicio' => $servicioImpuesto->valor, 'valor_impuesto' => $this->toFixed(((float)$valor_impuesto) , 2) , 'valor_impuesto2' => $this->toFixed(((float)$valor_impuesto2) , 2) , 'valor_servicio' => $this->toFixed(((float)$valor_servicio) , 2) , 'ish' => $total_neto_factura->ish, 'impuesto_hab' => $impuestoNumero1->valor, 'impuesto_hab2' => $impuestoNumero2->valor, 'total_neto_consumos' => $this->toFixed(((float)$total_neto) , 2) , 'factura_servicio' => $factura_servicio->nombre, 'servicio_consumo' => $factura_servicio->valor, 'iva_nombre_consumo' => $iva_consumo->nombre, 'iva_valor_consumo' => $iva_consumo->valor, 'impuesto_producto' => $impuesto_producto->valor, 'valor_servicio_producto' => $this->toFixed(((float)$sum_total_servicio) , 2) , 'valor_impuesto_producto' => $this->toFixed(((float)$sum_total_iva) , 2) , 'subtotal_consumos' => $this->toFixed(((float)$subtotal_consumos) , 2) , 'gran_total' => $this->toFixed(((float)$gran_total) , 2) , 'info_pagadores' => $info_pagadores, 'saldo_pendiente' => $saldo_pendiente]);

        return $pdf->stream('factura.pdf');
    }

    public function tipo_pagos_facturas($factura, $cliente)
    {

        $pagos = TipoPagoTotalDetalle::select('tbl_tipo_pagos.nombre', 'tbl_tipo_pagos_totales_detalles.id', 'tbl_tipo_pagos_totales_detalles.valor_pagado', 'tbl_tipo_pagos_totales_detalles.tipo_pago_id', 'tbl_tipo_pagos_totales.valor_a_pagar', 'tbl_tipo_pagos_totales.cliente_id', 'tbl_tipo_pagos_totales.created_at as fecha_pago')
->join('tbl_tipo_pagos_totales', 'tbl_tipo_pagos_totales_detalles.tipo_pago_total_id', '=', 'tbl_tipo_pagos_totales.id')
            ->join('tbl_tipo_pagos', 'tbl_tipo_pagos_totales_detalles.tipo_pago_id', '=', 'tbl_tipo_pagos.id')
            ->where('tbl_tipo_pagos_totales.factura_id', '=', $factura)->where('tbl_tipo_pagos_totales.cliente_id', '=', $cliente)
->get();

        $sum = 0;
        foreach ($pagos as $variable)
        {

            $sum += (float)$variable['valor_pagado'];
        }

        return ['pagos' => $pagos, 'suma_tipo' => $sum];

    }

    public function tipo_pagos_facturasCajero($cajero)
    {

        $pagos = TipoPagoTotalDetalle::select('tbl_tipo_pagos.nombre', 'tbl_tipo_pagos_totales_detalles.id', 'tbl_tipo_pagos_totales_detalles.valor_pagado', 'tbl_tipo_pagos_totales_detalles.tipo_pago_id', 'tbl_tipo_pagos_totales.valor_a_pagar', 'tbl_tipo_pagos_totales.cliente_id')
->join('tbl_tipo_pagos_totales', 'tbl_tipo_pagos_totales_detalles.tipo_pago_total_id', '=', 'tbl_tipo_pagos_totales.id')
            ->join('tbl_tipo_pagos', 'tbl_tipo_pagos_totales_detalles.tipo_pago_id', '=', 'tbl_tipo_pagos.id')
            ->where('tbl_tipo_pagos_totales.cajero_id', '=', $cajero)
->get();

        $sum = 0;
        foreach ($pagos as $variable)
        {

            $sum += (float)$variable['valor_pagado'];
        }

        return ['pagos' => $pagos, 'suma_tipo' => $sum];

    }

    public function tipo_pagos_facturas2($factura)
    {

        $pagos = TipoPagoTotalDetalle::select('tbl_tipo_pagos.nombre', 'tbl_tipo_pagos_totales_detalles.id', 'tbl_tipo_pagos_totales_detalles.valor_pagado', 'tbl_tipo_pagos_totales_detalles.tipo_pago_id', 'tbl_tipo_pagos_totales.valor_a_pagar', 'tbl_tipo_pagos_totales.cliente_id', 'tbl_tipo_pagos_totales.created_at as fecha_pago')
->join('tbl_tipo_pagos_totales', 'tbl_tipo_pagos_totales_detalles.tipo_pago_total_id', '=', 'tbl_tipo_pagos_totales.id')
            ->join('tbl_tipo_pagos', 'tbl_tipo_pagos_totales_detalles.tipo_pago_id', '=', 'tbl_tipo_pagos.id')
            ->where('tbl_tipo_pagos_totales.factura_id', '=', $factura)
->get();

        $sum = 0;
        foreach ($pagos as $variable)
        {

            $sum += (float)$variable['valor_pagado'];
        }

        return ['pagos' => $pagos, 'suma_tipo' => $sum];

    }

    function toFixed($number, $decimals)
    {
        return number_format($number, $decimals, '.', "");
    }

    public function consumosExtrasF($reserva, $numero, $factura_servicio)
    {

        $impuesto = ImpuestoProducto::select('valor')->where('iva', true)
            ->firstOrfail();

        $datos = ConsumoExtra::select('tbl_consumos_extras.punto_de_venta_id', 'tbl_puntos_ventas.nombre', DB::raw("SUM(tbl_consumos_extras.total_consumo) as total_consumo"))->where('tbl_consumos_extras.reserva_detalle_id', $reserva)->where('tbl_consumos_extras.numero_habitacion', $numero)->join('tbl_puntos_ventas', 'tbl_consumos_extras.punto_de_venta_id', '=', 'tbl_puntos_ventas.id')
            ->groupBy('tbl_consumos_extras.punto_de_venta_id', 'tbl_puntos_ventas.nombre')
            ->get();

        $calculo = (float)$impuesto->valor;
        // dd($calculo);
        foreach ($datos as $key => $temp)
        {
            $temp->total_consumo_neto = $this->toFixed(((float)$temp->total_consumo) / ((100 + (float)$calculo) / 100) , 2);
            $datos[$key] = $temp;
        }

        return $datos;
    }

    public function consumosExtrasF2($reserva, $numero, $cliente)
    {

        // $impuesto = ImpuestoProducto::select('valor')->where('iva', true)->firstOrfail();
        $datos = ConsumoExtra::select('tbl_consumos_extras.punto_de_venta_id', 'tbl_puntos_ventas.nombre', 'tbl_consumos_extras.total_extras_neto', DB::raw("SUM(tbl_consumos_extras.total_consumo) as total_consumo"))->where('tbl_consumos_extras.reserva_detalle_id', $reserva)->where('tbl_consumos_extras.numero_habitacion', $numero)->where('tbl_consumos_extras.cliente_id', $cliente)->join('tbl_puntos_ventas', 'tbl_consumos_extras.punto_de_venta_id', '=', 'tbl_puntos_ventas.id')
            ->groupBy('tbl_consumos_extras.punto_de_venta_id', 'tbl_puntos_ventas.nombre', 'tbl_consumos_extras.total_extras_neto')
            ->get();

        foreach ($datos as $key => $temp)
        {
            $temp->total_consumo_neto = $temp->total_extras_neto;
            $datos[$key] = $temp;
        }
        return $datos;
    }

    private function datos_pagadores_facturas3($factura)
    {

        $pagadores = PagadorFactura::select(

        'tbl_reservas_detalle.check_in_fecha', 'tbl_reservas_detalle.precio_total', 'tbl_reservas_detalle.id_habitacion'
)->join('tbl_clientes', 'tbl_clientes.id', '=', 'tbl_pagadores_facturas.cliente_id')
            ->join('tbl_facturas_detalles', 'tbl_pagadores_facturas.factura_detalle_id', '=', 'tbl_facturas_detalles.id')
            ->join('tbl_reservas_detalle', 'tbl_facturas_detalles.reserva_detalle_id', '=', 'tbl_reservas_detalle.id')
        // ->where('tbl_reservas_detalle.id_reservas_grupo', '=', $id)

            ->where('tbl_facturas_detalles.factura_id', '=', $factura)
->groupBy(

        'tbl_reservas_detalle.check_in_fecha', 'tbl_reservas_detalle.precio_total', 'tbl_reservas_detalle.id_habitacion'
)
            ->get();

        foreach ($pagadores as $key => $temp)
        {

            $temp->pagos = $this->pagos($temp->id);
            $temp->total_consumos = $this->total_consumos($temp->cliente_id, $temp->reservas_detalle_id);
            $temp->consumos_extras = $this->consumosExtras($temp->cliente_id, $temp->reservas_detalle_id);
            $pagadores[$key] = $temp;
        }

        return $pagadores;
    }

    public function estadoHabitacionSalida($id_habitacion, $estadoNull, $estadoNullid, $fecha)
    {
        $res = TblReservasDetalle::where('tbl_reservas_detalle.id_habitacion', '=', 1)->where('tbl_reservas_detalle.check_out_fecha', '=', $fecha)->select('tbl_reservas_detalle.salida', 'tbl_reservas_detalle.id_habitacion', 'tbl_reservas_detalle.check_out_fecha', DB::raw('date(now())-date(tbl_reservas_detalle.check_out_fecha) AS dias') , 'tbl_habitaciones_estado.id', 'tbl_habitaciones_estado.color', 'tbl_habitaciones_estado.nombre', 'tbl_habitaciones_detalle_estado.descripcion', DB::raw('concat_ws(\' \',tbl_clientes.nombre,tbl_clientes.apellido) as cliente') , 'tbl_clientes.id as cliente_id')
            ->leftJoin('tbl_reservas_detalle_huespedes', 'tbl_reservas_detalle.id', '=', 'tbl_reservas_detalle_huespedes.id_reservas_detalle')
            ->leftJoin('tbl_clientes', 'tbl_reservas_detalle_huespedes.id_cliente_huesped', '=', 'tbl_clientes.id')
            ->leftJoin('tbl_habitaciones_detalle_estado', 'tbl_reservas_detalle.id_habitacion', '=', 'tbl_habitaciones_detalle_estado.id_habitacion')
            ->leftJoin('tbl_habitaciones_estado', 'tbl_habitaciones_detalle_estado.id_habitacion_estado', '=', 'tbl_habitaciones_estado.id')
            ->orderBy('tbl_reservas_detalle.check_out_fecha')
            ->first();

        $estadosValor = $this->EstadosValor($id_habitacion, $fecha);
        $salida = !isset($res->salida) ? '' : $res->salida;
        $descripcion = !isset($res->descripcion) ? '' : $res->descripcion;
        $id = !isset($res->id) ? $estadoNullid : $res->id;
        $nombre = $estadosValor->nombre;
        $cliente = !isset($res->cliente) ? '' : $res->cliente;
        $cliente_id = !isset($res->cliente_id) ? '' : $res->cliente_id;
        $color = $estadosValor->color;
        $libre = is_null($res) ? 'Libre' : 'ocupado';
        return (object)['nombre' => $nombre, 'id' => $id, 'libre' => $libre, 'cliente' => $cliente, 'cliente_id' => $cliente_id, 'check_out_fecha' => $fecha, 'color' => $color, 'descripcion' => $descripcion, 'salida' => $salida

        ];
    }

    public function EstadosValor($id_habitacion, $fecha)
    {
        $data = TblHabitacionesEstado::where('id_habitacion', '=', $id_habitacion)->where('fecha', $fecha)->select('tbl_habitaciones_estado.nombre', 'tbl_habitaciones_estado.color', 'tbl_habitaciones_detalle_estado.id')
            ->join('tbl_habitaciones_detalle_estado', 'tbl_habitaciones_estado.id', '=', 'tbl_habitaciones_detalle_estado.id_habitacion_estado')
            ->first();

        $datas = (is_null($data) ? TblHabitacionesEstado::find(1) : $data);
        return (object)(['nombre' => $datas->nombre, 'color' => $datas->color, 'id' => $datas->id]);
    }

    public function reservanteHabitacion($id_reservas)
    {

        $reservante = TblReserva::select(DB::raw('CONCAT(tbl_clientes.nombre, tbl_clientes.apellido) AS full_name'))->join('tbl_clientes', 'tbl_reservas.id_cliente', '=', 'tbl_clientes.id')
            ->where('tbl_reservas.id', '=', $id_reservas)->firstOrfail();

        return (object)['full_name' => $reservante->full_name

        ];
    }

    public function pagadoresHabitacion($id_reservas_grupo)
    {

        $pagadores = TblReservaPagador::select(DB::raw('CONCAT(tbl_clientes.nombre, tbl_clientes.apellido) AS full_name'))->join('tbl_clientes', 'tbl_reservas_pagadores.cliente_id', '=', 'tbl_clientes.id')
            ->where('tbl_reservas_pagadores.reserva_grupo_id', '=', $id_reservas_grupo)->exists();

        if ($pagadores == true)
        {
            $pagadores = TblReservaPagador::select(DB::raw('CONCAT(tbl_clientes.nombre, tbl_clientes.apellido) AS full_name'))->join('tbl_clientes', 'tbl_reservas_pagadores.cliente_id', '=', 'tbl_clientes.id')
                ->where('tbl_reservas_pagadores.reserva_grupo_id', '=', $id_reservas_grupo)->get();

            return (object)['pagadores' => $pagadores

            ];
        }
        else if ($pagadores == false)
        {

            return (object)['pagadores' => null

            ];
        }
    }

    public function VerEstadosReservas(Request $request)
    {

        $estado = TblHabitacionesEstado::find(1);
        $llegadas = TblReservasDetalle::select(

        'tbl_habitaciones.id', 'tbl_reservas_detalle.check_in_fecha', 'tbl_reservas_detalle.check_out_fecha', 'tbl_reservas_detalle.deleted_at', 'tbl_habitaciones.numero', 'tbl_habitaciones_tipo.nombre AS tipo_habitacion', 'tbl_reservas_detalle.id_reservas_grupo', 'tbl_reservas_grupo.id_reservas_estado', 'tbl_reservas_grupo.id_reservas', 'tbl_reservas_detalle.id_habitacion_tipo'
)->join('tbl_habitaciones', 'tbl_reservas_detalle.id_habitacion', '=', 'tbl_habitaciones.id')
            ->join('tbl_habitaciones_tipo', 'tbl_habitaciones.id_habitacion_tipo', '=', 'tbl_habitaciones_tipo.id')
            ->join('tbl_reservas_grupo', 'tbl_reservas_detalle.id_reservas_grupo', '=', 'tbl_reservas_grupo.id')
            ->join('tbl_reservas', 'tbl_reservas_grupo.id_reservas', '=', 'tbl_reservas.id')
            ->join('tbl_clientes', 'tbl_reservas.id_cliente', '=', 'tbl_clientes.id')

            ->whereNull('tbl_habitaciones.deleted_at')

            ->whereNull('tbl_reservas_detalle.deleted_at')

            ->where('tbl_reservas_detalle.check_in_fecha', '=', $request->fecha)
            ->where('tbl_reservas_grupo.id_reservas_estado', '=', 1)

            ->groupBy('tbl_habitaciones.id', 'tbl_reservas_detalle.check_in_fecha', 'tbl_reservas_detalle.check_out_fecha', 'tbl_reservas_detalle.deleted_at',  'tbl_habitaciones.numero', 'tbl_habitaciones_tipo.nombre', 'tbl_reservas_detalle.id_reservas_grupo', 'tbl_reservas_grupo.id_reservas_estado', 'tbl_reservas_grupo.id_reservas', 'tbl_reservas_detalle.id_habitacion_tipo'
)
            ->get();

        foreach ($llegadas as $key => $temp)
        {

            $temp2 = $this->estadoHabitacion($temp->id, $estado->nombre, $estado->id, $request->fecha);
            $temp3 = $this->reservanteHabitacion($temp->id_reservas);
            $temp4 = $this->pagadoresHabitacion($temp->id_reservas_grupo);
            $llegadas[$key]->pagadores = $temp4->pagadores;
            $llegadas[$key]->nombre_reservante = $temp3->full_name;
            $llegadas[$key]->id_estado = $temp2->id;
            $llegadas[$key]->estado = $temp2->nombre;
            $llegadas[$key]->libre = $temp2->libre;
            $llegadas[$key]->libreValor = $temp2->libreValor;
            $llegadas[$key]->cliente = $temp2->cliente;
            $llegadas[$key]->cliente_id = $temp2->cliente_id;
            $llegadas[$key]->descripcion = $temp2->descripcion;
            $llegadas[$key]->color = $temp2->color;
            $llegadas[$key]->id_estado_reserva = $temp->id_reservas_estado;
            $llegadas[$key]->grupos = $this->detallesGruposFecha($temp->id, $request->fecha, $temp2->cliente_id);
            $llegadas[$key]->id_reserva = $temp->id_reservas;
            $llegadas[$key]->detalles = $this->detallesHabitacion($request->fecha, $temp->id);
        }

        // dd($llegadas);
        $salidas = TblReservasDetalle::select(

        'tbl_habitaciones.id', 'tbl_reservas_detalle.check_out_fecha', 'tbl_reservas_detalle.deleted_at', 'tbl_habitaciones.numero', 'tbl_habitaciones_tipo.nombre AS tipo_habitacion', 'tbl_reservas_detalle.id_reservas_grupo', 'tbl_reservas_grupo.id_reservas_estado', 'tbl_reservas_grupo.id_reservas'
)->join('tbl_habitaciones', 'tbl_reservas_detalle.id_habitacion', '=', 'tbl_habitaciones.id')
            ->join('tbl_habitaciones_tipo', 'tbl_habitaciones.id_habitacion_tipo', '=', 'tbl_habitaciones_tipo.id')
            ->join('tbl_reservas_grupo', 'tbl_reservas_detalle.id_reservas_grupo', '=', 'tbl_reservas_grupo.id')

            ->where('tbl_reservas_detalle.check_out_fecha', '=', $request->fecha)
            ->where('tbl_reservas_detalle.salida', '=', true)
            ->where('tbl_reservas_grupo.id_reservas_estado', '=', 4)

            ->join('tbl_reservas', 'tbl_reservas_grupo.id_reservas', '=', 'tbl_reservas.id')
            ->join('tbl_clientes', 'tbl_reservas.id_cliente', '=', 'tbl_clientes.id')

            ->whereNull('tbl_habitaciones.deleted_at')

            ->whereNull('tbl_reservas_detalle.deleted_at')

            ->groupBy('tbl_habitaciones.id', 'tbl_reservas_detalle.check_out_fecha', 'tbl_reservas_detalle.deleted_at',  'tbl_habitaciones.numero', 'tbl_habitaciones_tipo.nombre', 'tbl_reservas_detalle.id_reservas_grupo', 'tbl_reservas_grupo.id_reservas_estado', 'tbl_reservas_grupo.id_reservas'
)
            ->get();

        foreach ($salidas as $key => $temp)
        {
            $temp2 = $this->estadoHabitacion2($temp->id, $estado->nombre, $estado->id, $request->fecha);
            // dd($temp2);
            $temp3 = $this->reservanteHabitacion($temp->id_reservas);
            $temp4 = $this->pagadoresHabitacion($temp->id_reservas_grupo);
            $salidas[$key]->pagadores = $temp4->pagadores;
            $salidas[$key]->nombre_reservante = $temp3->full_name;
            $salidas[$key]->id_estado = $temp2->id;
            $salidas[$key]->estado = $temp2->nombre;
            $salidas[$key]->estado2 = $temp2->variable;
            $salidas[$key]->libre = $temp2->libre;
            $salidas[$key]->cliente = $temp2->cliente;
            $salidas[$key]->cliente_id = $temp2->cliente_id;
            $salidas[$key]->descripcion = $temp2->descripcion;
            $salidas[$key]->color = $temp2->color;
            $salidas[$key]->id_estado_reserva = $temp->id_reservas_estado;
            $salidas[$key]->grupos = $this->detallesGruposFecha($temp->id, $request->fecha, $temp2->cliente_id);
            $salidas[$key]->id_reserva = $temp->id_reservas;
            $salidas[$key]->detalles = $this->detallesHabitacion($request->fecha, $temp->id);
        }

        // dd($salidas);
        $alojados = TblReservasDetalle::select(

        'tbl_habitaciones.id', 'tbl_reservas_detalle.check_in_fecha', 'tbl_reservas_detalle.check_out_fecha', 'tbl_reservas_detalle.deleted_at',  'tbl_habitaciones.numero', 'tbl_habitaciones_tipo.nombre AS tipo_habitacion', 'tbl_reservas_detalle.id_reservas_grupo', 'tbl_reservas_grupo.id_reservas_estado', 'tbl_reservas_detalle.id as identificador_detalle', 'tbl_reservas_grupo.id_reservas'
)->join('tbl_habitaciones', 'tbl_reservas_detalle.id_habitacion', '=', 'tbl_habitaciones.id')
            ->join('tbl_habitaciones_tipo', 'tbl_habitaciones.id_habitacion_tipo', '=', 'tbl_habitaciones_tipo.id')
            ->join('tbl_reservas_grupo', 'tbl_reservas_detalle.id_reservas_grupo', '=', 'tbl_reservas_grupo.id')

            ->join('tbl_reservas', 'tbl_reservas_grupo.id_reservas', '=', 'tbl_reservas.id')
            ->join('tbl_clientes', 'tbl_reservas.id_cliente', '=', 'tbl_clientes.id')

            ->where('tbl_reservas_detalle.check_in_fecha', '>=', $request->fecha)
            ->where('tbl_reservas_detalle.check_in_fecha', '<=', $request->fecha)

            ->whereNull('tbl_habitaciones.deleted_at')

            ->whereNull('tbl_reservas_detalle.deleted_at')
            ->where('tbl_reservas_grupo.id_reservas_estado', '=', 2)

            ->groupBy('tbl_habitaciones.id', 'tbl_reservas_detalle.check_in_fecha', 'tbl_reservas_detalle.check_out_fecha', 'tbl_reservas_detalle.deleted_at',  'tbl_habitaciones.numero', 'tbl_habitaciones_tipo.nombre', 'tbl_reservas_detalle.id_reservas_grupo', 'tbl_reservas_grupo.id_reservas_estado', 'tbl_reservas_detalle.id', 'tbl_reservas_grupo.id_reservas'
)
            ->get();

        foreach ($alojados as $key => $temp)
        {
            $temp2 = $this->estadoHabitacion($temp->id, $estado->nombre, $estado->id, $request->fecha);
            $temp3 = $this->reservanteHabitacion($temp->id_reservas);
            $temp4 = $this->pagadoresHabitacion($temp->id_reservas_grupo);
            $alojados[$key]->pagadores = $temp4->pagadores;
            $alojados[$key]->nombre_reservante = $temp3->full_name;
            $alojados[$key]->id_estado = $temp2->id;
            $alojados[$key]->estado = $temp2->nombre;
            $alojados[$key]->libre = $temp2->libre;
            $alojados[$key]->libreValor = $temp2->libreValor;
            $alojados[$key]->cliente = $temp2->cliente;
            $alojados[$key]->cliente_id = $temp2->cliente_id;
            $alojados[$key]->descripcion = $temp2->descripcion;
            $alojados[$key]->color = $temp2->color;
            $alojados[$key]->id_estado_reserva = $temp->id_reservas_estado;
            $alojados[$key]->grupos = $this->detallesGruposFecha($temp->id, $request->fecha, $temp2->cliente_id);
            $alojados[$key]->id_reserva = $temp->id_reservas;
            $alojados[$key]->detalles = $this->detallesHabitacion($request->fecha, $temp->id);
        }

        $disponibles = TblHabitacione::select('tbl_habitaciones.id',  'tbl_habitaciones_tipo.nombre AS tipo_habitacion', 'tbl_habitaciones.numero')->orderBy('id_habitacion_tipo')
            ->orderBy('numero')
            ->join('tbl_habitaciones_tipo', 'tbl_habitaciones.id_habitacion_tipo', '=', 'tbl_habitaciones_tipo.id')
            ->get();

        foreach ($disponibles as $key => $temp)
        {
            $temp2 = $this->estadoHabitacion($temp->id, $estado->nombre, $estado->id, $request->fecha);

            $disponibles[$key]->id_estado = $temp2->id;
            $disponibles[$key]->estado = $temp2->nombre;
            $disponibles[$key]->libre = $temp2->libre;
            $disponibles[$key]->libreValor = $temp2->libreValor;
            $disponibles[$key]->cliente = $temp2->cliente;
            $disponibles[$key]->cliente_id = $temp2->cliente_id;
            $disponibles[$key]->check_in_fecha = $temp2->check_in_fecha;
            $disponibles[$key]->check_out_fecha = $temp2->check_out_fecha;
            $disponibles[$key]->descripcion = $temp2->descripcion;
            $disponibles[$key]->color = $temp2->color;
            $disponibles[$key]->id_estado_reserva = $this->EstadoReserva($temp->id, $request->fecha);
            $disponibles[$key]->grupos = $this->detallesGruposFecha($temp->id, $request->fecha, $temp2->cliente_id);
            $disponibles[$key]->id_reserva = $this->detallesGruposReserva($temp->id, $request->fecha);
            $disponibles[$key]->detalles = $this->detallesHabitacion($request->fecha, $temp->id);
        }
        // dd($disponibles);
        return (['validate' => true, 'llegadas' => $llegadas, 'salidas' => $salidas, 'alojados' => $alojados, 'disponibles' => $disponibles]);
    }

    public function estadoHabitacion($id_habitacion, $estadoNull, $estadoNullid, $fecha)

    {

        $res = TblReservasDetalle::where('tbl_reservas_detalle.id_habitacion', '=', (int)$id_habitacion)->where('tbl_reservas_detalle.check_in_fecha', '=', $fecha)->select('tbl_reservas_detalle.salida', 'tbl_reservas_detalle.id_habitacion', 'tbl_reservas.check_in_fecha as check_in_fecha', 'tbl_reservas.check_out_fecha as check_out_fecha', DB::raw('date(now())-date(tbl_reservas_detalle.check_in_fecha) AS dias') , 'tbl_habitaciones_estado.id', 'tbl_habitaciones_estado.color', 'tbl_habitaciones_estado.nombre', 'tbl_habitaciones_detalle_estado.descripcion',

        DB::raw('concat_ws(\' \',tbl_clientes.nombre,tbl_clientes.apellido) as cliente') , 'tbl_clientes.id as cliente_id')

            ->leftJoin('tbl_reservas_detalle_huespedes', 'tbl_reservas_detalle.id', '=', 'tbl_reservas_detalle_huespedes.id_reservas_detalle')

            ->leftJoin('tbl_clientes', 'tbl_reservas_detalle_huespedes.id_cliente_huesped', '=', 'tbl_clientes.id')

            ->leftJoin('tbl_habitaciones_detalle_estado', 'tbl_reservas_detalle.id_habitacion', '=', 'tbl_habitaciones_detalle_estado.id_habitacion')

            ->leftJoin('tbl_habitaciones_estado', 'tbl_habitaciones_detalle_estado.id_habitacion_estado', '=', 'tbl_habitaciones_estado.id')

            ->leftJoin('tbl_reservas_grupo', 'tbl_reservas_detalle.id_reservas_grupo', '=', 'tbl_reservas_grupo.id')

            ->leftJoin('tbl_reservas', 'tbl_reservas_grupo.id_reservas', '=', 'tbl_reservas.id')

            ->orderBy('tbl_reservas_detalle.check_in_fecha')

            ->first();

        $idiomas = idioma::find(1);
        $idioma_libre = $idiomas->libre;
        $idioma_ocupado = $idiomas->ocupado;

        $estadosValor = $this->EstadosValor($id_habitacion, $fecha);
        $descripcion = !isset($res->descripcion) ? '' : $res->descripcion;
        $salida = !isset($res->salida) ? '' : $res->salida;
        $id = !isset($res->id) ? $estadoNullid : $res->id;
        $nombre = $estadosValor->nombre;
        $cliente = !isset($res->cliente) ? '' : $res->cliente;
        $cliente_id = !isset($res->cliente_id) ? '' : $res->cliente_id;
        $color = $estadosValor->color;
        $libreValor = is_null($res) ? 1 : 2;
        $libre = is_null($res) ? $idioma_libre : $idioma_ocupado;
        $check_in_fecha = !isset($res->check_in_fecha) ? '' : $res->check_in_fecha;
        $check_out_fecha = !isset($res->check_out_fecha) ? '' : $res->check_out_fecha;

        return (object)['nombre' => $nombre, 'id' => $id, 'libre' => $libre, 'libreValor' => $libreValor, 'cliente' => $cliente, 'cliente_id' => $cliente_id, 'check_in_fecha' => $check_in_fecha, 'check_out_fecha' => $check_out_fecha, 'color' => $color, 'descripcion' => $descripcion, 'salida' => $salida

        ];
    }

    public function estadoHabitacion2($id_habitacion, $estadoNull, $estadoNullid, $fecha)

    {
        $res = TblReservasDetalle::where('tbl_reservas_detalle.id_habitacion', '=', $id_habitacion)->where('tbl_reservas_detalle.check_in_fecha', '=', $fecha)->select('tbl_reservas_detalle.salida', 'tbl_reservas_detalle.id_habitacion', 'tbl_reservas.check_in_fecha as check_in_fecha', 'tbl_reservas.check_out_fecha as check_out_fecha', DB::raw('date(now())-date(tbl_reservas_detalle.check_in_fecha) AS dias') , 'tbl_habitaciones_estado.id', 'tbl_habitaciones_estado.color', 'tbl_habitaciones_estado.nombre', 'tbl_habitaciones_detalle_estado.descripcion', 'tbl_habitaciones_detalle_estado.fecha as variable',

        DB::raw('concat_ws(\' \',tbl_clientes.nombre,tbl_clientes.apellido) as cliente') , 'tbl_clientes.id as cliente_id')

            ->leftJoin('tbl_reservas_detalle_huespedes', 'tbl_reservas_detalle.id', '=', 'tbl_reservas_detalle_huespedes.id_reservas_detalle')

            ->leftJoin('tbl_clientes', 'tbl_reservas_detalle_huespedes.id_cliente_huesped', '=', 'tbl_clientes.id')

            ->leftJoin('tbl_habitaciones_detalle_estado', 'tbl_reservas_detalle.id_habitacion', '=', 'tbl_habitaciones_detalle_estado.id_habitacion')

            ->leftJoin('tbl_habitaciones_estado', 'tbl_habitaciones_detalle_estado.id_habitacion_estado', '=', 'tbl_habitaciones_estado.id')

            ->leftJoin('tbl_reservas_grupo', 'tbl_reservas_detalle.id_reservas_grupo', '=', 'tbl_reservas_grupo.id')

            ->leftJoin('tbl_reservas', 'tbl_reservas_grupo.id_reservas', '=', 'tbl_reservas.id')

            ->orderBy('tbl_reservas_detalle.check_in_fecha')

            ->first();

        $estadosValor = $this->EstadosValor($id_habitacion, $fecha);
        $descripcion = !isset($res->descripcion) ? '' : $res->descripcion;
        $salida = !isset($res->salida) ? '' : $res->salida;
        $id = !isset($res->id) ? $estadoNullid : $res->id;
        $nombre = $estadosValor->nombre;
        $cliente = !isset($res->cliente) ? '' : $res->cliente;
        $cliente_id = !isset($res->cliente_id) ? '' : $res->cliente_id;
        $color = $estadosValor->color;
        $libre = is_null($res) ? 'Libre' : 'Libre';
        $check_in_fecha = !isset($res->check_in_fecha) ? '' : $res->check_in_fecha;
        $check_out_fecha = !isset($res->check_out_fecha) ? '' : $res->check_out_fecha;
        $variable = $estadosValor->id;
        // $check_out_fecha = $res->check_out_fecha;
        return (object)['nombre' => $nombre, 'id' => $id, 'libre' => $libre, 'cliente' => $cliente, 'cliente_id' => $cliente_id, 'check_in_fecha' => $check_in_fecha, 'check_out_fecha' => $check_out_fecha, 'color' => $color, 'descripcion' => $descripcion, 'salida' => $salida, 'variable' => $variable

        ];
    }

    private function EstadoReserva($id_habitacion, $fecha)
    {
        $res = TblReservasGrupo::select('id_reservas_estado')->where('tbl_reservas_detalle.id_habitacion', '=', $id_habitacion)->where('tbl_reservas_detalle.check_in_fecha', '=', $fecha)->join('tbl_reservas_detalle', 'tbl_reservas_grupo.id', '=', 'tbl_reservas_detalle.id_reservas_grupo')
            ->first();
        $id_reservas_estado = (is_null($res)) ? 5 : $res->id_reservas_estado;
        return $id_reservas_estado;
    }

    // public function VerEstadosReservas_salidas(Request $request)
    // {
    //     $data=TblReservasDetalle
    //     ::select(
    //         'tbl_habitaciones.id',
    //         'tbl_reservas_detalle.check_out_fecha',
    //         'tbl_reservas_detalle.deleted_at',
    //         'tbl_habitaciones.piso',
    //         'tbl_habitaciones.numero',
    //         'tbl_habitaciones.personas_minimo',
    //         'tbl_habitaciones.personas_maximo',
    //         'tbl_habitaciones_tipo.nombre AS tipo_habitacion',
    //         'tbl_reservas_detalle.id_reservas_grupo'
    //     )
    //     ->join('tbl_habitaciones','tbl_reservas_detalle.id_habitacion', '=', 'tbl_habitaciones.id')
    //     ->join('tbl_habitaciones_tipo','tbl_habitaciones.id_habitacion_tipo', '=', 'tbl_habitaciones_tipo.id')
    //     ->where('tbl_reservas_detalle.check_out_fecha', '=', $request->fecha)
    //     ->whereNull('tbl_habitaciones.deleted_at')
    //     ->whereNull('tbl_reservas_detalle.deleted_at')
    //     ->groupBy(
    //         'tbl_habitaciones.id',
    //         'tbl_reservas_detalle.check_out_fecha',
    //         'tbl_reservas_detalle.deleted_at',
    //         'tbl_habitaciones.piso',
    //         'tbl_habitaciones.numero',
    //         'tbl_habitaciones.personas_minimo',
    //         'tbl_habitaciones.personas_maximo',
    //         'tbl_habitaciones_tipo.nombre',
    //         'tbl_reservas_detalle.id_reservas_grupo'
    //     )
    //     ->get();


    //     // dd($data);
    //     $estado=TblHabitacionesEstado::find(1);
    //     foreach($data as $key=>$temp)
    //     {
    //         $temp2=$this->estadoHabitacionSalida($temp->id,$estado->nombre,$estado->id,$request->fecha);
    //         $data[$key]->id_estado        = $temp2->id;
    //         $data[$key]->estado           = $temp2->nombre;
    //         $data[$key]->libre            = $temp2->libre;
    //         $data[$key]->cliente          = $temp2->cliente;
    //         $data[$key]->cliente_id       = $temp2->cliente_id;
    //         $data[$key]->check_in_fecha   = $temp2->check_out_fecha;
    //         $data[$key]->check_out_fecha  = $temp2->check_out_fecha;
    //         $data[$key]->descripcion      = $temp2->descripcion;
    //         $data[$key]->color            = $temp2->color;
    //         $data[$key]->id_estado_reserva= $this->EstadoReserva($temp->id,$request->fecha);
    //         $data[$key]->grupos           = $this->detallesGruposFecha($temp->id,$request->fecha, $temp2->cliente_id);
    //         $data[$key]->id_reserva       = $this->detallesGruposReserva($temp->id,$request->fecha);
    //         $data[$key]->detalles         = $this->detallesHabitacion($request->fecha,$temp->id);


    //     }
    //     return (['validate'=>true,'data'=>$data ,'data2'=>$data2]);
    // }


    private function detallesGruposReserva($id_habitacion, $fecha)
    {
        $res = TblReservasGrupo::select('id_reservas')->where('tbl_reservas_detalle.id_habitacion', '=', $id_habitacion)->where('tbl_reservas_detalle.check_in_fecha', '=', $fecha)->join('tbl_reservas_detalle', 'tbl_reservas_grupo.id', '=', 'tbl_reservas_detalle.id_reservas_grupo')
            ->first();
        $id_reservas = (is_null($res)) ? 1 : $res->id_reservas;
        return $id_reservas;
    }

    private function detallesGruposReservaSalida($id_habitacion, $fecha)
    {
        $res = TblReservasGrupo::select('id_reservas')->where('tbl_reservas_detalle.id_habitacion', '=', $id_habitacion)->where('tbl_reservas_detalle.check_out_fecha', '=', $fecha)->join('tbl_reservas_detalle', 'tbl_reservas_grupo.id', '=', 'tbl_reservas_detalle.id_reservas_grupo')
            ->first();
        $id_reservas = (is_null($res)) ? 1 : $res->id_reservas;
        return $id_reservas;
    }
    private function EstadoReservaSalida($id_habitacion, $fecha)
    {
        $res = TblReservasGrupo::select('id_reservas_estado')->where('tbl_reservas_detalle.id_habitacion', '=', $id_habitacion)->where('tbl_reservas_detalle.check_out_fecha', '=', $fecha)->join('tbl_reservas_detalle', 'tbl_reservas_grupo.id', '=', 'tbl_reservas_detalle.id_reservas_grupo')
            ->first();
        $id_reservas_estado = (is_null($res)) ? 1 : $res->id_reservas_estado;
        return $id_reservas_estado;
    }
    private function detallesGruposFecha($id_habitacion, $fecha, $cliente_id)
    {
        $res = TblReservasGrupo::select('id_reservas')->where('tbl_reservas_detalle.id_habitacion', '=', $id_habitacion)->where('tbl_reservas_detalle.check_in_fecha', '=', $fecha)->join('tbl_reservas_detalle', 'tbl_reservas_grupo.id', '=', 'tbl_reservas_detalle.id_reservas_grupo')
            ->first();
        $id_reservas = (is_null($res)) ? 0 : $res->id_reservas;
        $data = $this->detallesGrupos($id_reservas, $cliente_id);
        return $data;
    }
    private function detallesGruposFechaSalida($id_habitacion, $fecha, $cliente_id)
    {
        $res = TblReservasGrupo::select('id_reservas')->where('tbl_reservas_detalle.id_habitacion', '=', $id_habitacion)->where('tbl_reservas_detalle.check_out_fecha', '=', $fecha)->join('tbl_reservas_detalle', 'tbl_reservas_grupo.id', '=', 'tbl_reservas_detalle.id_reservas_grupo')
            ->first();
        $id_reservas = (is_null($res)) ? 0 : $res->id_reservas;
        $data = $this->detallesGrupos($id_reservas, $cliente_id);
        return $data;
    }
    public function detallesHabitacion($fecha, $id_habitacion)
    {
        $pagador = TblReservasDetalle::select(DB::raw('concat_ws(\' \', tbl_clientes.nombre, tbl_clientes.apellido) AS cliente_principal'))->join('tbl_reservas_grupo', 'tbl_reservas_detalle.id_reservas_grupo', '=', 'tbl_reservas_grupo.id')
            ->join('tbl_reservas', 'tbl_reservas_grupo.id_reservas', '=', 'tbl_reservas.id')
            ->join('tbl_clientes', 'tbl_reservas.id_cliente', '=', 'tbl_clientes.id')
            ->where('tbl_reservas_detalle.id_habitacion', '=', $id_habitacion)->where('tbl_reservas_detalle.check_in_fecha', '=', $fecha)->first();
        $huespedes = TblReservasDetalle::select(DB::raw('concat_ws(\' \', tbl_clientes.nombre, tbl_clientes.apellido) AS huespedes'))->join('tbl_reservas_detalle_huespedes', 'tbl_reservas_detalle.id', '=', 'tbl_reservas_detalle_huespedes.id_reservas_detalle')
            ->join('tbl_clientes', 'tbl_reservas_detalle_huespedes.id_cliente_huesped', '=', 'tbl_clientes.id')
            ->where('tbl_reservas_detalle.id_habitacion', '=', $id_habitacion)->where('tbl_reservas_detalle.check_in_fecha', '=', $fecha)->get();

        $pagador = is_null($pagador) ? '' : $pagador->cliente_principal;

        $historico = TblHabitacionesDetalleEstado::where('id_habitacion', '=', $id_habitacion)->join('tbl_habitaciones_estado', 'tbl_habitaciones_detalle_estado.id_habitacion_estado', '=', 'tbl_habitaciones_estado.id')
            ->orderBy('tbl_habitaciones_detalle_estado.fecha')
            ->limit(10)
            ->get();
        return ['pagador' => $pagador, 'huespedes' => $huespedes, 'historico' => $historico];
    }
    public function detallesHabitacionSalida($fecha, $id_habitacion)
    {
        $pagador = TblReservasDetalle::select(DB::raw('concat_ws(\' \', tbl_clientes.nombre, tbl_clientes.apellido) AS cliente_principal'))->join('tbl_reservas_grupo', 'tbl_reservas_detalle.id_reservas_grupo', '=', 'tbl_reservas_grupo.id')
            ->join('tbl_reservas', 'tbl_reservas_grupo.id_reservas', '=', 'tbl_reservas.id')
            ->join('tbl_clientes', 'tbl_reservas.id_cliente', '=', 'tbl_clientes.id')
            ->where('tbl_reservas_detalle.id_habitacion', '=', $id_habitacion)->where('tbl_reservas_detalle.check_out_fecha', '=', $fecha)->first();
        $huespedes = TblReservasDetalle::select(DB::raw('concat_ws(\' \', tbl_clientes.nombre, tbl_clientes.apellido) AS huespedes'))->join('tbl_reservas_detalle_huespedes', 'tbl_reservas_detalle.id', '=', 'tbl_reservas_detalle_huespedes.id_reservas_detalle')
            ->join('tbl_clientes', 'tbl_reservas_detalle_huespedes.id_cliente_huesped', '=', 'tbl_clientes.id')
            ->where('tbl_reservas_detalle.id_habitacion', '=', $id_habitacion)->where('tbl_reservas_detalle.check_out_fecha', '=', $fecha)->get();

        $pagador = is_null($pagador) ? '' : $pagador->cliente_principal;

        $historico = TblHabitacionesDetalleEstado::where('id_habitacion', '=', $id_habitacion)->join('tbl_habitaciones_estado', 'tbl_habitaciones_detalle_estado.id_habitacion_estado', '=', 'tbl_habitaciones_estado.id')
            ->orderBy('tbl_habitaciones_detalle_estado.fecha')
            ->limit(10)
            ->get();
        return ['pagador' => $pagador, 'huespedes' => $huespedes, 'historico' => $historico];
    }

    public function agregar_cliente_hospedado(Request $request)
    {

        $huesped = TblReservasDetalleHuespedes::firstOrNew(['id_cliente_huesped' => $request->id_cliente, 'reserva_grupo_id' => $request->id_grupo, ]);
        $huesped->id_cliente_huesped = $request->id_cliente;
        $huesped->id_reservas_detalle = $request->reserva_detalle_id;
        $huesped->es_cliente_principal = false;
        $huesped->save();

        return ['huesped' => $huesped];
    }
    public function agregar_cliente_detalle_hospedado(Request $request)
    {

        $huesped = TblReservasDetalleHuespedes::firstOrNew(['id_cliente_huesped' => $request->id_cliente, 'reserva_grupo_id' => $request->id_grupo, ]);

        $huesped->id_cliente_huesped = $request->id_cliente;
        $huesped->reserva_grupo_id = $request->id_grupo;
        $huesped->es_cliente_principal = false;
        $huesped->save();

        $pagador = TblReservaPagador::firstOrNew(['cliente_id' => $request->id_cliente, 'reserva_grupo_id' => $request->id_grupo, ]);

        $pagador->cliente_id = $request->id_cliente;
        $pagador->reserva_grupo_id = $request->id_grupo;
        $pagador->save();

        return ['huesped' => $huesped, 'pagador' => $pagador];
    }

    public function clientes_hospedados(Request $request)
    {

        $clientes_hospedados = TblReservasDetalleHuespedes::select('tbl_reservas_detalle_huespedes.id_cliente_huesped', 'tbl_clientes.nombre', 'tbl_clientes.apellido'
)
->where('tbl_reservas_detalle_huespedes.reserva_grupo_id', '=', $request->id_grupo)
            ->join('tbl_clientes', 'tbl_reservas_detalle_huespedes.id_cliente_huesped', '=', 'tbl_clientes.id')

            ->groupBy('tbl_reservas_detalle_huespedes.id_cliente_huesped', 'tbl_clientes.nombre', 'tbl_clientes.apellido')
            ->get();

        return ['clientes_hospedados' => $clientes_hospedados];
    }

    public function eliminar_cliente_hospedado(Request $request)
    {

        $clientes_hospedados = TblReservasDetalleHuespedes::select('tbl_reservas_detalle_huespedes.id_cliente_huesped', 'tbl_reservas_detalle_huespedes.id'
)->where('tbl_reservas_detalle_huespedes.id_cliente_huesped', '=', $request->id_cliente)
            ->where('tbl_reservas_detalle_huespedes.reserva_grupo_id', '=', $request->id_grupo)
            ->get();

        foreach ($clientes_hospedados as $key)
        {

            $hospedado_eliminar = TblReservasDetalleHuespedes::find($key['id']);
            $hospedado_eliminar->delete();
        }
        return ['clientes_hospedados' => 'eliminado'];
    }

    public function obtener_tipo(Request $request)
    {

        $items = TblHabitacionesTipo::find($request->id);
        return ['items' => $items];
    }

    public function obtener_habitaciones()
    {
        $habitaciones = TblHabitacionesTipo::all();
        return ['habitaciones' => $habitaciones];
    }

    public function nombre_reservante(Request $request)
    {

        $nombre_reserva = TblReserva::select(DB::raw("concat_ws(' ', tbl_clientes.nombre, tbl_clientes.apellido) AS cliente_nombre"))->where('tbl_reservas.id', '=', $request->reserva_id)
            ->join('tbl_clientes', 'tbl_reservas.id_cliente', '=', 'tbl_clientes.id')
            ->firstOrfail();
        return ['nombre_reserva' => $nombre_reserva];
    }

    public function crear_comentarios(Request $request)
    {

        $comentario = new Comentario();
        $comentario->grupo_id = $request->grupo_id;
        $comentario->comentario = $request->comentario;
        $comentario->save();
        return ['validate' => 1];
    }

    public function listar_comentarios(Request $request)
    {

        $comentarios = Comentario::where('grupo_id', '=', $request->grupo_id)
            ->get();
        return ['comentarios' => $comentarios];
    }

    public function checkOut(Request $request)
    {
        $checkOut = TblReservasGrupo::find($request->grupo_id);
        $checkOut->id_reservas_estado = 7;
        $checkOut->save();

        $estado = TblReservasDetalle::where('id_reservas_grupo', '=', $request->grupo_id)
            ->firstOrfail();
        $estado->id_reserva_detalle_estado_habitacion = 2;
        $estado->save();

        $estado_habitacion = TblHabitacionesDetalleEstado::where('fecha', '=', $request->check_out_fecha)
            ->where('id_habitacion', '=', $request->habitacion)
            ->get();

        foreach ($estado_habitacion as $valor)
        {
            $cambio_estado = TblHabitacionesDetalleEstado::find($valor['id']);
            $cambio_estado->id_habitacion_estado = 2;
            $cambio_estado->save();
        }

        return ['validate' => 1];
    }

    public function eliminar_pagador(Request $request)
    {

        //////
        $TblReservaPagador = TblReservaPagador::where('cliente_id', '=', $request->cliente_id)
            ->where('reserva_grupo_id', '=', $request->grupo_id)
            ->delete();

        return ['eliminado' => 1];
    }

    public function eliminar_mensaje(Request $request)
    {

        $datos = MensajesFactura::find($request->mensaje_id);
        $datos->delete();
        return ['guardado' => true];
    }

    public function actualizar_numeroimpuesto(Request $request)
    {

        $datos = TblReservasDetalle::find($request->id);
        $datos->numero_impuesto = $request->valorImpuesto;
        $datos->save();
        return ['asignado' => 1];
    }

    public function actualizar_numeroimpuesto2(Request $request)
    {

        $datos = TblReservasDetalle::find($request->id);
        $datos->numero_impuesto = 0;
        $datos->save();
        return ['asignado' => 1];
    }

    public function aplicar_servicio_reserva(Request $request)
    {

        foreach ($request->reservas as $value)
        {
            $reserva_detalle = TblReservasDetalle::find($value['id']);
            $reserva_detalle->servicio = $value['servicio'];
            $reserva_detalle->save();
        }
    }
    public function quitar_servicio_reserva(Request $request)
    {

        foreach ($request->reservas as $value)
        {
            $reserva_detalle = TblReservasDetalle::find($value['id']);
            $reserva_detalle->servicio = null;
            $reserva_detalle->save();
        }
    }

    public function quitar_servicio_individual(Request $request)
    {
        $reserva_detalle = TblReservasDetalle::find($request->reserva);
        $reserva_detalle->servicio = null;
        $reserva_detalle->save();

    }
    public function agregar_servicio_individual(Request $request)
    {
        $reserva_detalle = TblReservasDetalle::find($request->reserva);
        $reserva_detalle->servicio = $request->servicio;
        $reserva_detalle->save();
    }

    public function listarall_reservas(Request $request)
    {
        $Filtro = array();
        $where = array();
        $where2 = array();
        $wheres = '';
        $wheres2 = '';
        $fecha_reserva = ($request->fecha_reserva == 'NaN-aN-aN') ? null : $request->fecha_reserva;
        $fecha_reserva_hasta = ($request->fecha_reserva_hasta == 'NaN-aN-aN') ? null : $request->fecha_reserva_hasta;
        $fecha_llegada = ($request->fecha_llegada == 'NaN-aN-aN') ? null : $request->fecha_llegada;
        $fecha_llegada_hasta = ($request->fecha_llegada_hasta == 'NaN-aN-aN') ? null : $request->fecha_llegada_hasta;
        $fecha_salida = ($request->fecha_salida == 'NaN-aN-aN') ? null : $request->fecha_salida;
        $fecha_salida_hasta = ($request->fecha_salida_hasta == 'NaN-aN-aN') ? null : $request->fecha_salida_hasta;
        $data_estado = json_decode($request->estado);
        $estado = ($data_estado) ? $data_estado : null;
        $data_tipoHabitacion = json_decode($request->tipoHabitacion);
        $tipoHabitacion = ($data_tipoHabitacion) ? $data_tipoHabitacion : null;
        $data_fuente = json_decode($request->fuente);
        $fuente = ($data_fuente) ? $data_fuente : null;
        $data_reserva = json_decode($request->no_reserva);
        $no_reserva = ($data_reserva) ? $data_reserva : null;
        

        $query = DB::table('tbl_reservas')->select('tbl_reservas.precio_total','tbl_reservas.created_at', 'tbl_reservas.id as id_reservas', 'tbl_habitaciones.numero as numero', 'tbl_habitaciones_tipo.nombre as nombre_tipo', 'tbl_reservas.id_cliente', 'tbl_clientes.nombre', 'tbl_clientes.apellido', 'tbl_reservas.check_in_fecha', 'tbl_reservas_detalle.precio_neto', 'tbl_reservas_detalle.check_out_fecha', 'tbl_reservas_detalle.adultos_cantidad', 'tbl_reservas_detalle.ninos_cantidad', 'tbl_reservas_detalle.infantes_cantidad', 'tbl_fuentes_reservas.nombre as nombre_fuente', DB::raw('(tbl_reservas_detalle.adultos_cantidad+tbl_reservas_detalle.ninos_cantidad+tbl_reservas_detalle.infantes_cantidad) AS huespedes_cantidad') , 'tbl_reservas_detalle.id_habitacion_tipo', 'tbl_reservas_detalle.id_habitacion as resource', DB::raw('TO_CHAR(date(tbl_reservas_detalle.check_out_fecha), \'yyyy-mm-dd\') as end') , DB::raw('concat_ws(\' \', tbl_clientes.nombre,tbl_clientes.apellido) as text') , DB::raw('sum(adultos_cantidad+ninos_cantidad+infantes_cantidad) as cantidad_huespedes') , 'tbl_reservas_estado.descripcion as estado_reserva_descripcion', 'tbl_reservas_estado.id as id_estado_reserva', 'tbl_reservas_detalle.id_reservas_grupo as id_grupo', DB::raw('COALESCE(tbl_reservas_estado.color,\'#000\') as barColor') , 'tbl_reservas_grupo.facturado')
            ->groupBy('tbl_reservas.precio_total','tbl_reservas.id', 'tbl_habitaciones.numero', 'tbl_habitaciones_tipo.nombre', 'tbl_reservas_estado.descripcion',  'tbl_reservas_detalle.precio_neto', 'tbl_reservas_detalle.id_habitacion_tipo', 'tbl_reservas_detalle.id_habitacion', 'tbl_reservas_detalle.adultos_cantidad', 'tbl_reservas_detalle.ninos_cantidad', 'tbl_reservas_detalle.infantes_cantidad', 'tbl_fuentes_reservas.nombre', 'tbl_reservas_detalle.id_reservas_grupo', 'tbl_reservas_estado.id', 'tbl_reservas.id', 'tbl_reservas.id_cliente', 'tbl_clientes.nombre', 'tbl_clientes.apellido', 'tbl_reservas_grupo.check_in_fecha', 'tbl_reservas_grupo.check_out_fecha', 'tbl_reservas_grupo.id', 'tbl_reservas_detalle.check_out_fecha', 'tbl_reservas_grupo.facturado')
            ->join('tbl_clientes', 'tbl_reservas.id_cliente', 'tbl_clientes.id')
            ->leftJoin('tbl_reservas_grupo', 'tbl_reservas.id', 'tbl_reservas_grupo.id_reservas')
            ->leftJoin('tbl_reservas_detalle', 'tbl_reservas_grupo.id', 'tbl_reservas_detalle.id_reservas_grupo')
            ->join('tbl_reservas_estado', 'tbl_reservas_grupo.id_reservas_estado', 'tbl_reservas_estado.id')
            ->join('tbl_habitaciones', 'tbl_reservas_detalle.id_habitacion', 'tbl_habitaciones.id')
            ->join('tbl_habitaciones_tipo', 'tbl_habitaciones.id_habitacion_tipo', 'tbl_habitaciones_tipo.id')
            ->join('tbl_fuentes_reservas', 'tbl_reservas.fuente_reserva_id', 'tbl_fuentes_reservas.id')
            ->whereNotNull('tbl_reservas_detalle.id_habitacion')
            ->whereNull('tbl_reservas_detalle.deleted_at');

        $FechasReservas = [$fecha_reserva, $fecha_reserva_hasta];
        if($fecha_reserva != null && $fecha_reserva_hasta != null)
        {
            $query->when($FechasReservas, function ($q, $FechasReservas)
            {
                return $q->where("tbl_reservas.created_at",">=",$FechasReservas[0])->where("tbl_reservas.created_at","<=",$FechasReservas[1]);
            });
         }
        
        $FechasLlegadas = [$fecha_llegada, $fecha_llegada_hasta];
       
        if($fecha_llegada != null && $fecha_llegada_hasta != null)
        {
            
            $query->when($FechasLlegadas, function ($q, $FechasLlegadas)
            {
                return $q->where("tbl_reservas.check_in_fecha",">=",$FechasLlegadas[0])->where("tbl_reservas.check_in_fecha","<=",$FechasLlegadas[1]);
            });
        }
        
        $FechasHasta = [$fecha_salida, $fecha_salida_hasta];
        if($fecha_salida != null && $fecha_salida_hasta != null)
        {
           
            $query->when($FechasHasta, function ($q, $FechasHasta)
            {
                return $q->where("tbl_reservas.check_out_fecha",">=",$FechasHasta[0])->where("tbl_reservas.check_out_fecha","<=",$FechasHasta[1]);
            });
        }
        $query->when($estado, function ($q, $estado)
        {
          
            return $q->where('tbl_reservas_estado.id', $estado);
        });

        $query->when($tipoHabitacion, function ($q, $tipoHabitacion)
        {
            return $q->where('tbl_reservas_detalle.id_habitacion_tipo', $tipoHabitacion);
        });

        $query->when($fuente, function ($q, $fuente)
        {
            return $q->where('tbl_reservas.fuente_reserva_id', $fuente);
        });

        $query->when($no_reserva, function ($q, $no_reserva)
        {
            return $q->where('tbl_reservas.id', $no_reserva);
        });

        $tasks = $query->paginate(50);

        foreach ($tasks as $key => $temp)
        {
            $temp->barColor = $temp->barcolor;
            unset($temp->barcolor);
            $date1 = new \DateTime($temp->check_in_fecha);
            $date2 = new \DateTime($temp->check_out_fecha);
            $diff = $date1->diff($date2);
            $dias = $diff->days;
            $tasks[$key]->id = ($key + 1);
            $temp->zipcode = '';
            $temp->ciudad = '';
            $temp->pais = '';
            $temp->dias = $dias;
            $temp->calle_residencia = '';
            $temp->codigo_postal_residencia = '';
            $temp->nombre_departamento = '';
            $temp->habitacion_tipo = $temp->id_habitacion_tipo;
            $temp->nombre_pais = '';
            $fecha_chec_in = TblReservasDetalle::where('id_reservas_grupo', '=', $temp->id_grupo)
                ->orderBy('check_in_fecha')
                ->first();
            $tasks[$key]->check_in_fecha = $fecha_chec_in->check_in_fecha;
            $tasks[$key]->start = date('Y-m-d', strtotime($fecha_chec_in->check_in_fecha));
            $tasks[$key]->grupos = $this->detallesGrupos($temp->id_reservas);
            $tasks[$key]->detalles = $this->detalles($temp, $temp->id_reservas);
            $temp->checkOut = $this->InfoPagos($temp->id_reservas, $temp->numero);
        }

        return ['pagination' => ['total' => $tasks->total() , 'current_page' => $tasks->currentPage() , 'per_page' => $tasks->perPage() , 'last_page' => $tasks->lastPage() , 'from' => $tasks->firstItem() , 'to' => $tasks->lastItem() , ], 'datos' => $tasks, 'cantidad' => $tasks->count() ];

    }

    public function importar(Request $request)
    {

        $request->validate(['file' => 'required|mimes:csv,xlsx,xls', ]);
        $data = $this->fetchCsv($request);
        $headerRow = $data->first()
            ->keys()
            ->toArray();
        $validate = $this->validateHeaderRow($headerRow);

        if ($validate == true)
        {
            // Load $data into array if > 0
            if ($data->count())
            {
                $arr = $this->loadCsvIntoArray($data, $request);
                $validator = Validator::make($arr, ["*.fecha_ingreso" => "required|date", "*.fecha_salida" => "required|date", "*.tipo_habitacion" => "required|string", "*.cantidad_adultos" => "integer", "*.cantidad_ninos" => "integer", "*.tarifa" => "required", "*.nombres_cliente" => "required|string", "*.apellidos_cliente" => "required|string", "*.telefono" => "required|integer", "*.correo" => "required|email", "*.fuente" => "required|string",
                ]);
                if ($validator->fails())
                {
                    return ['data' => $validator->errors()
                        ->all() ];
                }
                else
                {
                    $this->crearReservacionesExcel($arr);
                }
                // Write to the database
                if (!empty($arr))
                {
                    return ['data' => true];
                }
            }
        }
        // Return our import finished message
        $message = $this->returnMessage($validate, $request);
        return $message;

    }

    public function crearReservacionesExcel($data)
    {
        $id_reserva = null;
        for ($i = 0;$i < count($data);$i++)
        {
            $cliente = TblCliente::firstOrNew(['email' => $data[$i]['correo']]);
            if (is_null($cliente->nombre));
            {
                $cliente->nombre = $data[$i]['nombres_cliente'];
                $cliente->apellido = $data[$i]['apellidos_cliente'];
                $cliente->id_clientes_tipo = 1;
                $cliente->save();
            }
            $id_reserva = $this->saveReservaDetailExcel($data[$i], $cliente->id, $data, $id_reserva, $data[$i]['fuente'], $data[$i]['fuente']);
        }
    }

    private function saveReservaDetailExcel($data_detail, $cliente_id, $data, $id_reserva, $BookingChannel, $RequestorID)
    {
        foreach ($data as $key => $temp)
        {
            $fuente = FuenteReserva::select('id')->where('nombre', '=', $RequestorID)->first();
            $reserva = new TblReserva();
            $reserva->id_cliente = $cliente_id;
            $reserva->check_in_fecha = $this->formatDate($data_detail['fecha_ingreso']);
            $reserva->check_out_fecha = $this->formatDate($data_detail['fecha_salida']);
            $reserva->fuente_reserva_id = $fuente->id;
            $reserva->huespedes_cantidad = (int)$temp['cantidad_adultos'] + (int)$temp['cantidad_ninos'];
            $reserva->save();
            $id_reserva = $reserva->id;
            //====================CREAR RESERVACION DETALLE////====================//
            $this->nuevos_gruposExcel($data_detail, $id_reserva, $cliente_id);
            //====================CREAR RESERVACION DETALLE====================//
            return $reserva;
        }
    }

    private function nuevos_gruposExcel($data, $id_reserva, $cliente)
    {

        $id_grupo = $this->newGrupoExcel($id_reserva, $data);
        $this->CrearReservaDetallesExcel($id_grupo, $data, $id_reserva, $cliente);

        PGSchema::switchTo(Auth::user()->schema);
        $datos = [];
        $datos = TblConfig::select('value')->where('name', 'data_api')
            ->first();
        $datos = (is_null($datos)) ? ['validate' => false, 'value' => false] : ['validate' => true, 'value' => $datos->value];
        $res = (json_decode($datos['value']));
        $user = $res;

        $url = 'https://reservations.orbebooking.com/admin/OAF/AOBA-XML';
        $xmlRequest = '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
        $xmlRequest .= '<soap:Header>
            <HTNGHeader xmlns="http://htng.org/1.1/Header/"></HTNGHeader>
            <soap:Username>' . $user->user . '</soap:Username>
            <soap:Password>' . $user->pass . '</soap:Password>
            </soap:Header>
            <soap:Body>
            <InventoryUpdateRequest TimeStamp="2017-8-22T18:12:16" Version="1.00">
            <INVENTORY HotelCode="' . $user->code . '" HotelName="DIAMOND DEMO">' . "\n			";

        $datoTipoHabitacion = TblHabitacionesTipo::select('room_type')->where('nombre', '=', $data['tipo_habitacion'])->first();
        for ($i = $this->formatDate($data['fecha_ingreso']);$i < $this->formatDate($data['fecha_salida']);$i = date("Y-m-d", strtotime($i . "+ 1 days")))
        {

            $xmlRequest .= '<Update Inv_Date="' . $i . '" Quantity="-1" Room_Type="' . (string)$datoTipoHabitacion['room_type'] . '"  Task="Add"/>';

        }

        $xmlRequest .= '</INVENTORY></InventoryUpdateRequest></soap:Body></soap:Envelope>';
        $res = $this->con($xmlRequest, $url);
        $datas = $res->getBody();
        $datas = simplexml_load_string($datas);

    }

    private function newGrupoExcel($id_reserva, $data)
    {

        $reserva = new TblReservasGrupo();
        $reserva->id_reservas = $id_reserva;
        $reserva->id_reservas_estado = 1;

        $reserva->check_in_fecha = $this->formatDate($data['fecha_ingreso']);
        $reserva->check_out_fecha = $this->formatDate($data['fecha_salida']);
        $reserva->huespedes_cantidad = (int)$data['cantidad_adultos'] + (int)$data['cantidad_ninos'];
        $reserva->save();

        return $reserva->id;
    }

    private function CrearReservaDetallesExcel($id_grupos, $ROOM_TYPE, $id_reserva, $cliente)
    {

        $reserva = TblReserva::find($id_reserva);
        $precio_total = (double)$ROOM_TYPE['tarifa'];
        $date1 = new \DateTime($reserva->check_in_fecha);
        $date2 = new \DateTime($reserva->check_out_fecha);
        $diff = $date1->diff($date2);
        $dias = $diff->days;
        $precio = $precio_total;

        while (strtotime($reserva->check_in_fecha) < strtotime($reserva->check_out_fecha))
        {
            $this->CreaarReservaDetalleExcel($ROOM_TYPE, $reserva, $precio, $cliente, $id_grupos);
            $reserva->check_in_fecha = date('Y-m-d', strtotime($reserva->check_in_fecha . "+ 1 days"));
        }
    }

    public function CreaarReservaDetalleExcel($ROOM_TYPE, $reserva, $precio, $cliente, $id_grupo)
    {

        $impuesto = TblImpuesto::where('principal', '=', true)->firstOrfail();
        $valor_impuesto = ($impuesto->valor / 100) + 1;
        $id = TblHabitacionesTipo::select('id')->where('nombre', '=', $ROOM_TYPE['tipo_habitacion'])->first();
        $id_reservas = TblReservasGrupo::where('id_reservas', '=', $reserva->id)
            ->first();
        $ReservasDetalle = new TblReservasDetalle();
        $ReservasDetalle->id_reservas_grupo = $id_grupo;
        $ReservasDetalle->id_habitacion_tipo = $id->id;
        $ReservasDetalle->adultos_cantidad = $ROOM_TYPE['cantidad_adultos'];
        $ReservasDetalle->ninos_cantidad = $ROOM_TYPE['cantidad_ninos'];
        $ReservasDetalle->precio_total = $precio;
        $ReservasDetalle->precio_neto = $precio;
        $ReservasDetalle->check_in_fecha = $reserva->check_in_fecha;
        $ReservasDetalle->check_out_fecha = $reserva->check_out_fecha;
        $ReservasDetalle->id_reserva_detalle_estado_habitacion = 1;
        $ReservasDetalle->numero_impuesto = 0;
        $ReservasDetalle->numero_impuesto2 = 0;
        $ReservasDetalle->servicio = 0;
        $ReservasDetalle->id_reserva_detalle_estado_habitacion = 1;
        $ReservasDetalle->save();

    }

    public function fetchCsv($request)
    {
        $path = $request->file('file')
            ->getRealPath();
        $data = Excel::load($path)->get();

        return $data;
    }

    public function validateHeaderRow($headerRow)
    {

        $validate = false;
        if ($headerRow[0] == 'fecha_ingreso' && $headerRow[1] == 'fecha_salida' && $headerRow[2] == 'tipo_habitacion' && $headerRow[3] == 'cantidad_adultos' && $headerRow[4] == 'cantidad_ninos' && $headerRow[5] == 'tarifa' && $headerRow[6] == 'nombres_cliente' && $headerRow[7] == 'apellidos_cliente' && $headerRow[8] == 'telefono' && $headerRow[9] == 'correo' && $headerRow[10] == 'fuente')
        {
            $validate = true;
        }
        return $validate;
    }

    public function loadCsvIntoArray($data, $request)
    {

        foreach ($data as $key => $value)
        {

            $arr[] = ['fecha_ingreso' => $value->fecha_ingreso, 'fecha_salida' => $value->fecha_salida, 'tipo_habitacion' => $value->tipo_habitacion, 'cantidad_adultos' => $value->cantidad_adultos, 'cantidad_ninos' => $value->cantidad_ninos, 'tarifa' => $value->tarifa, 'nombres_cliente' => $value->nombres_cliente, 'apellidos_cliente' => $value->apellidos_cliente, 'telefono' => $value->telefono, 'correo' => $value->correo, 'fuente' => $value->fuente, ];

        }
        return $arr;
    }

    public function returnMessage($validate, $request)
    {
        if ($validate == true)
        {
            return back()->with('success', 'Schedule uploaded successfully!');
        }
        else
        {
            return back()
                ->with('danger', 'Your .CSV headers do not meet the requirements. Must be: `user_id`, `customer_name`, `date`');
        }
    }

    public function agregar_prepago_nota(Request $request)
    {

        $datos = new PrepagoNota();
        $datos->tipo_pago_id = $request->tipo_pago;
        $datos->valor_pagado = $request->valor_a_pagar;
        $datos->reserva_id = $request->reserva_id;
        $datos->save();
        return ['datos' => $datos];

    }
    public function actualizar_prepago_nota(Request $request)
    {
        $datos = PrepagoNota::find($request->idPago);
        $datos->tipo_pago_id = $request->tipo_pago;
        $datos->valor_pagado = $request->valor_a_pagar;
        $datos->save();
        return ['datos' => $datos];

    }
    public function obtener_prepagos_notas(Request $request)
    {
        $datos = PrepagoNota::select('tbl_tipo_pagos.created_at as fecha_creacion', 'tbl_tipo_pagos.nombre', 'tbl_prepagos_notas.id', 'tbl_prepagos_notas.valor_pagado', 'tbl_prepagos_notas.tipo_pago_id', 'tbl_prepagos_notas.reserva_id', DB::raw("concat_ws(' ', tbl_clientes.nombre, tbl_clientes.apellido) AS cliente_nombre"))->join('tbl_tipo_pagos', 'tbl_prepagos_notas.tipo_pago_id', '=', 'tbl_tipo_pagos.id')
            ->leftjoin('tbl_clientes', 'tbl_prepagos_notas.cliente_id', '=', 'tbl_clientes.id')
            ->where('tbl_prepagos_notas.reserva_id', $request->reserva_id)
            ->get();
        return ['datos' => $datos];
    }
    public function obtener_prepagos_notas2(Request $request)
    {

        $datos = PrepagoNota::select('tbl_tipo_pagos.nombre', 'tbl_prepagos_notas.id', 'tbl_prepagos_notas.valor_pagado', 'tbl_prepagos_notas.tipo_pago_id', 'tbl_prepagos_notas.reserva_id', DB::raw("concat_ws(' ', tbl_clientes.nombre, tbl_clientes.apellido) AS cliente_nombre"))->join('tbl_tipo_pagos', 'tbl_prepagos_notas.tipo_pago_id', '=', 'tbl_tipo_pagos.id')
            ->leftjoin('tbl_clientes', 'tbl_prepagos_notas.cliente_id', '=', 'tbl_clientes.id')
            ->where('tbl_prepagos_notas.reserva_id', $request->reserva_id)
            ->get();

        return ['datos' => $datos, "cantidad" => count($datos) ];

    }

    public function eliminar_nota_prepago(Request $request)
    {
        $eliminar = PrepagoNota::find($request->idPago);
        $eliminar->delete();
        return ['datos' => true];

    }

    public function asignar_pagos_notas(Request $request)
    {
        $prepagoNota = PrepagoNota::find($request->pagosNotas['id']);
        $prepagoNota->cliente_id = $request->pagador_cliente['cliente_id'];
        $prepagoNota->save();

        $datos = TipoPagoTotal::where('cliente_id', '=', (int)$request->pagador_cliente['cliente_id'])
            ->where('reserva_id', '=', (int)$request->pagosNotas['reserva_id'])
            ->get();
        foreach ($datos as $dato)
        {

            $pago = TipoPagoTotalDetalle::firstOrNew(['tipo_pago_total_id' => $dato['id'], 'tipo_pago_id' => (int)$request->pagosNotas['tipo_pago_id']

            ]);
            $pago->tipo_pago_total_id = $dato['id'];
            $pago->tipo_pago_id = (int)$request->pagosNotas['tipo_pago_id'];
            $pago->valor_pagado = (float)$pago->valor_pagado + (float)$request->pagosNotas['valor_pagado'];
            $pago->save();
        }
        $PagoTotal = PagoTotal::where('factura_id', $request->pagador_cliente['factura_id'])
            ->where('cliente_id', (int)$request->pagador_cliente['cliente_id'])
            ->firstOrfail();
        $PagoTotal->valor_pagado = (float)$request->pagosNotas['valor_pagado'] + (float)$PagoTotal->valor_pagado;
        $PagoTotal->save();

    }

    public function insertar_pms_waldo(Request $request)
    {

        $resultadoConsulta = DB::connection('test_sqlsrv')->table('DmdCargos')
            ->where('IDReserva', (int)$request->reserva_id)
            ->where('NumHabita', $request->numero)
            ->where('Fecha', $request->fecha_ingreso)
            ->where('CodHotel', Auth::user()
            ->schema)
            ->get();

        foreach ($resultadoConsulta as $value)
        {

            $impuesto = ($value->PorImpu > 0) ? ImpuestoProducto::where('iva', true)
                ->firstOrfail() : null;
            $servicio = ($value->PorServi > 0) ? ImpuestoProducto::where('servicio', true)
                ->firstOrfail() : null;
            $sumaImpuestos = (float)$value->PorImpu + (float)$value->PorServi;
            $total_consumo1 = ($value->MtoTotal * (float)$sumaImpuestos) / 100;
            $total_extras = ($value->MtoTotal + $total_consumo1);

            $datos = new ConsumoExtra();
            $datos->producto_id = 2000;
            $datos->impuesto_id = ($impuesto != null) ? $impuesto['id'] : null;
            $datos->servicio_id = ($servicio != null) ? $servicio['id'] : null;
            $datos->cliente_id = $request->cliente;
            $datos->reserva_detalle_id = $request->reserva_detalle_id;
            $datos->total_consumo = $total_extras;
            $datos->numero_habitacion = $request->numero;
            $datos->fecha = $request->fecha_ingreso;
            $datos->punto_de_venta_id = 2000;
            $datos->categoria_id = 2000;
            $datos->unidad = (int)$value->Cantidad;
            $datos->total_extras_neto = (float)$value->MtoTotal;
            $datos->CodProdu = $value->CodProdu;
            $datos->DesProdu = $value->DesProdu;
            $datos->UnmProdu = $value->UnmProdu;
            $datos->PUnitario = $value->PUnitario;
            $datos->MtoTotal = $value->MtoTotal;
            $datos->PorImpu = $value->PorImpu;
            $datos->PorServi = $value->PorServi;
            $datos->save();
        }
    }
    public function cargar_consumos(Request $request)
    {
        foreach ($request->reservas as $data)
        {

            $resultadoConsulta = DB::connection('test_sqlsrv')->table('DmdCargos')
                ->where('IDReserva', (int)$data['reserva_id'])->where('NumHabita', $data['numero'])->where('Fecha', $data['check_in_fecha'])->where('CodHotel', Auth::user()
                ->schema)
                ->get();

            foreach ($resultadoConsulta as $value)
            {

                $impuesto = ($value->PorImpu > 0) ? ImpuestoProducto::where('iva', true)
                    ->firstOrfail() : null;
                $servicio = ($value->PorServi > 0) ? ImpuestoProducto::where('servicio', true)
                    ->firstOrfail() : null;
                $sumaImpuestos = (float)$value->PorImpu + (float)$value->PorServi;
                $total_consumo1 = ($value->MtoTotal * (float)$sumaImpuestos) / 100;
                $total_extras = ($value->MtoTotal + $total_consumo1);
                $datos = new ConsumoExtra();
                $datos->producto_id = 2000;
                $datos->impuesto_id = ($impuesto != null) ? $impuesto['id'] : null;
                $datos->servicio_id = ($servicio != null) ? $servicio['id'] : null;
                $datos->cliente_id = $request->cliente;
                $datos->reserva_detalle_id = $request->reserva_detalle_id;
                $datos->total_consumo = $total_extras;
                $datos->numero_habitacion = $request->numero;
                $datos->fecha = $request->fecha_ingreso;
                $datos->punto_de_venta_id = 2000;
                $datos->categoria_id = 2000;
                $datos->unidad = (int)$value->Cantidad;
                $datos->total_extras_neto = (float)$value->MtoTotal;
                $datos->CodProdu = $value->CodProdu;
                $datos->DesProdu = $value->DesProdu;
                $datos->UnmProdu = $value->UnmProdu;
                $datos->PUnitario = $value->PUnitario;
                $datos->MtoTotal = $value->MtoTotal;
                $datos->PorImpu = $value->PorImpu;
                $datos->PorServi = $value->PorServi;
                $datos->save();
            }
        }
    }
    public function modificar_inventario_expandir(Request $request)
    {
      $tipoHabitacion = TblHabitacione::where('numero', $request->numero_habitacion_anterior)->firstOrfail();
      $fecha_llegada_anterior = $this->formatDate($request->fecha_llegada_anterior);
      $fecha_salida_anterior = $this->formatDate($request->fecha_salida_anterior);
      for($i=$fecha_llegada_anterior;$i<=$fecha_salida_anterior;$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
      $data = Inventario::where('tipo_habitacion_id', $tipoHabitacion->id_habitacion_tipo)->where('start', $i)->firstOrfail();
      $data->disponibilidad = (int)$data->disponibilidad + 1;
      $data->save();
      $dato = InventarioHabitacion::where('tipo_habitacion_id', $tipoHabitacion->id_habitacion_tipo)
      ->where('start', $i)
      ->where('habitacion_id', $tipoHabitacion->id)
      ->firstOrfail();
      $dato->disponibilidad = true;
      $dato->estado = 'Disponible';
      $dato->save();
      }
      $fecha_llegada_nueva = $this->formatDate($request->fecha_llegada_nueva);
      $fecha_salida_nueva = $this->formatDate($request->fecha_salida_nueva);
      for($i=$fecha_llegada_nueva;$i<=$fecha_salida_nueva;$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
      $data_save = Inventario::where('tipo_habitacion_id', $tipoHabitacion->id_habitacion_tipo)->where('start', $i)->firstOrfail();
      $data_save->disponibilidad = (int)$data_save->disponibilidad - 1;
      $data_save->save();
      $dato_save = InventarioHabitacion::where('tipo_habitacion_id', $tipoHabitacion->id_habitacion_tipo)
      ->where('start', $i)
      ->where('habitacion_id', $tipoHabitacion->id)
      ->firstOrfail();
      $dato_save->disponibilidad = false;
      $dato_save->estado = 'No Disponible';
      $dato_save->save();
      }
      $this->ModificarInventarioOrbeExpandir($request, $tipoHabitacion);
      $this->DescontarInventarioOrbeExpandir($request, $tipoHabitacion);

    }
    public function modificar_inventario(Request $request)
    {
      $tipoHabitacion = TblHabitacione::where('numero', $request->numero_habitacion_anterior)->firstOrfail();
      $fecha_llegada_anterior = $this->formatDate($request->fecha_llegada_anterior);
      $fecha_salida_anterior = $this->formatDate($request->fecha_salida_anterior);
      for($i=$fecha_llegada_anterior;$i<$fecha_salida_anterior;$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
      $data = Inventario::where('tipo_habitacion_id', $request->tipo_habitacion_anterior)->where('start', $i)->firstOrfail();
      $data->disponibilidad = (int)$data->disponibilidad + 1;
      $data->save();
      $dato = InventarioHabitacion::where('tipo_habitacion_id', $request->tipo_habitacion_anterior)
      ->where('start', $i)
      ->where('habitacion_id', $tipoHabitacion->id)
      ->firstOrfail();
      $dato->disponibilidad = true;
      $dato->estado = 'Disponible';
      $dato->save();
      }
      $fecha_llegada_nueva = $this->formatDate($request->fecha_llegada_nueva);
      $fecha_salida_nueva = $this->formatDate($request->fecha_salida_nueva);
      for($i=$fecha_llegada_nueva;$i<$fecha_salida_nueva;$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
      $data_save = Inventario::where('tipo_habitacion_id', $request->tipo_habitacion_nueva)->where('start', $i)->firstOrfail();
      $data_save->disponibilidad = (int)$data_save->disponibilidad - 1;
      $data_save->save();
      $dato_save = InventarioHabitacion::where('tipo_habitacion_id', $request->tipo_habitacion_nueva)
      ->where('start', $i)
      ->where('habitacion_id', $request->id_habitacion_nueva)
      ->firstOrfail();
      $dato_save->disponibilidad = false;
      $dato_save->estado = 'No Disponible';
      $dato_save->save();
      }
      $this->ModificarInventarioOrbe($request);
      $this->DescontarInventarioOrbe($request);

    }
    public function modificar_inventario_manual(Request $request)
    {
      foreach ($request->data as $key => $value) {
        $this->DescontarInventarioOrbeManual($request);
      }
    }
    public function DescontarInventarioOrbeManual($request)
    {
      $b = new ApiController($request);
      $number = -1;
      $result = $b->CrearInventarioManual($request, $number);
      return $result;
    }
    public function ModificarInventarioOrbe($request)
    {
      $b = new ApiController($request);
      $number = 1;
      $result = $b->CrearInventario($request->fecha_llegada_anterior, $request->fecha_salida_anterior, $request->tipo_habitacion_anterior, $number);
      return $result;
    }
    public function DescontarInventarioOrbe($request)
    {
      $b = new ApiController($request);
      $number = -1;
      $result = $b->CrearInventario($request->fecha_llegada_nueva, $request->fecha_salida_nueva, $request->tipo_habitacion_nueva, $number);
      return $result;
    }
    public function ModificarInventarioOrbeExpandir($request,$tipoHabitacion)
    {
      $b = new ApiController($request);
      $number = 1;
      $result = $b->CrearInventario($request->fecha_llegada_anterior, $request->fecha_salida_anterior, $tipoHabitacion->id_habitacion_tipo, $number);
      return $result;
    }
    public function DescontarInventarioOrbeExpandir($request,$tipoHabitacion)
    {
      $b = new ApiController($request);
      $number = -1;
      $result = $b->CrearInventario($request->fecha_llegada_nueva, $request->fecha_salida_nueva, $tipoHabitacion->id_habitacion_tipo, $number);
      return $result;
    }
    public function tasas_impuestos_detalles(Request $request)
    {
      $data = TasaImpuestoDetalle::select(
        'tbl_tasas_impuestos_detalles.id',
        'tbl_tasas_impuestos_detalles.reserva_detalle_id',
        'tbl_tasas_impuestos_detalles.impuesto_tasa_id',
        'tbl_impuestos_tasas.nombre as nombre_tasa',
        'tbl_impuestos_tasas.valor as valor_tasa',
        'tbl_reservas_detalle.check_in_fecha',
        'tbl_reservas_detalle.check_out_fecha',
        'tbl_reservas_detalle.total_diario',
        'tbl_habitaciones.numero'
        )->join('tbl_reservas_detalle', 'tbl_tasas_impuestos_detalles.reserva_detalle_id', '=', 'tbl_reservas_detalle.id')
      ->join('tbl_reservas_grupo', 'tbl_reservas_detalle.id_reservas_grupo', '=', 'tbl_reservas_grupo.id')
      ->join('tbl_impuestos_tasas', 'tbl_tasas_impuestos_detalles.impuesto_tasa_id', '=', 'tbl_impuestos_tasas.id')
      ->join('tbl_habitaciones', 'tbl_reservas_detalle.id_habitacion', '=', 'tbl_habitaciones.id')
      ->where('tbl_reservas_grupo.id_reservas', $request->reserva_id)->get();
      return ["datos" => $data];
    }
    public function tipo_habitaciones(Request $request)
    {

      $fecha_salida = date($request->fecha_salida);
      $nuevafecha = strtotime ( '-1 day' , strtotime ( $fecha_salida ) ) ;
      $nuevafecha = date ( 'Y-m-j' , $nuevafecha );
      $tipo = TblHabitacionesTipo::where('persona_maximo', '>=', ((int)$request->cantidad_adultos + (int)$request->cantidad_ninos ))->get();
      $arreglo = array();
      $datetime1 = new \DateTime($request->fecha_ingreso);
      $datetime2 = new \DateTime($request->fecha_salida);
      $dias = $datetime1->diff($datetime2);
      foreach ($tipo as $value) {
        $data = Inventario::select('tipo_habitacion_id')->whereBetween('start', [$request->fecha_ingreso, $nuevafecha])
        ->where('tipo_habitacion_id', (int)$value['id'])->where('disponibilidad', '>', 0)->count();
        if ($data === (int)$dias->format('%a')) {
          $arreglo[] = (int)$value['id'];
        }
      }
      $tipos = TblHabitacionesTipo::whereIn('id', $arreglo)->get();
      return ["datos" => $tipos];
    }
    public function tipo_habitaciones_modal(Request $request)
    {

      $arreglo = array();
      $datetime1 = new \DateTime($request->fecha_ingreso);
      $datetime2 = new \DateTime($request->fecha_salida);
      $dias = $datetime1->diff($datetime2)->days;
      $habitaciones = TblHabitacione::where('id_habitacion_tipo', (int)$request->tipo_habitacion)->get();
      foreach ($habitaciones as $value) {
        for($i=$request->fecha_ingreso;$i<$request->fecha_salida;$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
          $data = InventarioHabitacion::whereDate('start', $i)->where('habitacion_id', (int)$value['id'])->where('disponibilidad', true)->first();
          if ($data !=null) {
            $arreglo[] = $data->habitacion_id;
          }
        }
      }
      $array_habitaciones = [];
      $array = array_count_values($arreglo);
      foreach ($array as $key => $value) {
        if ($value == $dias) {
          $array_habitaciones[] = $key;
        }
      }
      $data_habitaciones = TblHabitacione::whereIn('id', $array_habitaciones)->get();
      return ["datos" => $data_habitaciones];
    }
    public function obtener_abonos(Request $request)
    {
      $suma_cuenta_reserva = CuentaReserva::where('reserva_id', (int)$request->reserva_id)
      ->where('cuenta_id', (int)$request->cuenta_id)->sum('cargo');

      $suma_cuenta_abono = CuentaAbono::where('reserva_id', (int)$request->reserva_id)
      ->where('cuenta_id', (int)$request->cuenta_id)->sum('abono');

      return ['suma_cuenta_reserva' => round((float)$suma_cuenta_reserva, 2), 'suma_cuenta_abono' => round((float)$suma_cuenta_abono, 2)];

    }
    public function actualizar_cuenta_reserva(Request $request)
    {

      if ($request->cuenta_tipo_movimiento == 'H') {
        $cuenta_reserva = CuentaReserva::where('habitacion_id', (int)$request->cuenta_habitacion)
          ->whereDate('fecha_hora', $request->cuenta_fecha)
          ->where('tipo_movimiento', 'H')
          ->where('reserva_id', (int)$request->reserva_id)
          ->get();
        foreach ($cuenta_reserva as $value) {
          $data = CuentaReserva::find((int)$value['id']);
          $data->cuenta_id = (int)$request->cuenta_cambio;
          $data->save();
        }
      }
      else {
        $cuenta_reserva = CuentaReserva::where('tipo_movimiento', $request->cuenta_tipo_movimiento)
          ->where('reserva_id', (int)$request->reserva_id)
          ->get();
        foreach ($cuenta_reserva as $value) {
          $data = CuentaReserva::find((int)$value['id']);
          $data->cuenta_id = (int)$request->cuenta_cambio;
          $data->save();
        }

      }
      $validacion = CuentaAbono::where('cuenta_reserva_id', (int)$request->cuenta_reserva_id)->exists();
      if ($validacion == true) {
      $cuenta_abono = CuentaAbono::where('cuenta_reserva_id', (int)$request->cuenta_reserva_id)->firstOrfail();
      $cuenta_abono->cuenta_id = (int)$request->cuenta_cambio;
      $cuenta_abono->save();
      }
    }
    public function eliminar_abono(Request $request)
    {
      CuentaReserva::find((int)$request->id)->delete();
      CuentaAbono::where('cuenta_reserva_id', (int)$request->id)->delete();
      return 'eliminado';
    }
    public function monto_pagado(Request $request)
    {
      $datos = CuentaAbono::where('reserva_id', (int)$request->reserva_id)->sum('abono');
      return ['datos' => $datos];

    }
    public function crear_cuentas(Request $request)
    {
      if (!$request->ajax()) return redirect('/');
        try {
          DB::beginTransaction();
          $data = new Cuenta();
          $data->nombre = $request->cuenta.'-'.$request->reserva_id;
          $data->reserva_id = (int)$request->reserva_id;
          $data->save();
          DB::commit();

        } catch(\Illuminate\Database\QueryException $ex){
          return false;
        }

    }
    public function miscuentas(Request $request)
    {
      $data = Cuenta::where('reserva_id', (int)$request->reserva_id)->get();
      return ['datos' => $data];
    }
    public function crear_consumos(Request $request)
    {

      $fechaActual = date('Y-m-d');
      $Porcentaje_monto_productos= DB::select('SELECT "porcentaje_monto_productos"()');
      $decimal = (float)($Porcentaje_monto_productos[0]->porcentaje_monto_productos/100);
      $valor_neto_producto = (float)$request->total_extras_neto / (1 + (float)$decimal);
      $valor_iva_producto = (float)$valor_neto_producto * (float)$decimal;

      $cuenta_consumo = new CuentaReserva();
      $cuenta_consumo->reserva_id = (int)$request->reserva_id;
      $cuenta_consumo->cuenta_id = (int)$request->cuenta_consumo;
      $cuenta_consumo->fecha_hora = $fechaActual;
      $cuenta_consumo->concepto = 'Consumo Producto-'.$request->unidad.'/'.$request->producto['nombre_producto'];
      $cuenta_consumo->cargo = $valor_neto_producto;
      $cuenta_consumo->condicion = 3;
      $cuenta_consumo->estado = true;
      $cuenta_consumo->save();
      $tmp = CuentaReserva::find((int)$cuenta_consumo->id);
      $tmp->tipo_movimiento = (int)$cuenta_consumo->id;
      $tmp->save();
      $cuenta_monto = new CuentaReserva();
      $cuenta_monto->reserva_id = (int)$request->reserva_id;
      $cuenta_monto->cuenta_id = (int)$request->cuenta_consumo;
      $cuenta_monto->fecha_hora = $fechaActual;
      $cuenta_monto->concepto = 'Porcentaje Monto Producto/'.$Porcentaje_monto_productos[0]->porcentaje_monto_productos;
      $cuenta_monto->cargo = $valor_iva_producto;
      $cuenta_monto->condicion = 3;
      $cuenta_monto->tipo_movimiento = $cuenta_consumo->id;
      $cuenta_monto->estado = true;
      $cuenta_monto->save();
      $consumo_extra = new ConsumoExtra();
      $consumo_extra->producto_id = (int)$request->producto['id'];
      $consumo_extra->producto = $request->producto['nombre_producto'];
      $consumo_extra->unidad = (int)$request->unidad;
      $consumo_extra->cuenta_id = (int)$request->cuenta_consumo;
      $consumo_extra->total = (float)$request->total_extras_neto;
      $consumo_extra->cuenta_reserva_id = (int)$cuenta_monto->id;
      $consumo_extra->save();
      $reserva = TblReserva::find((int)$request->reserva_id);
      $reserva->precio_total = (float)$reserva->precio_total + (float)$request->total_extras_neto;
      $reserva->save();
      return ["datos" => $reserva->precio_total];
    }

    public function eliminar_consumo(Request $request)
    {
      $suma = CuentaReserva::where('tipo_movimiento', $request->id)->sum('cargo');
      $descontar_reserva = TblReserva::find((int)$request->reserva_id);
      $descontar_reserva->precio_total = round((float)$descontar_reserva->precio_total - (float)$suma, 2);
      $descontar_reserva->save();
      CuentaReserva::where('tipo_movimiento', $request->id)->delete();
      ConsumoExtra::where('cuenta_reserva_id', (int)$request->id)->delete();

      return ["data" => $descontar_reserva->precio_total];
    }

}
