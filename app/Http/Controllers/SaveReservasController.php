<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\ReservasController;
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

class SaveReservasController extends Controller
{
    //
    public function crearReserva($data_detail, $cliente_id, $data, $id_reserva, $BookingChannel, $RequestorID, $collection)
    {
       
        $dataReserva = $data->ROOM_TYPES->ROOM_TYPE;
        $total_reserva = 0;
        $total_huespedes = 0;
        $fuente = FuenteReserva::select('id')->where('id_ota', '=', $RequestorID)->first();
        $sum = 0.0;
        $date1 = new \DateTime($data_detail->Arrival);
        $date2 = new \DateTime($data_detail->Departure);
        $diff = $date1->diff($date2);
        $dias = $diff->days;
        $fuente = FuenteReserva::select('id')->where('id_ota', '=', $RequestorID)->first();

        $reserva = new TblReserva();
        $reserva->id_cliente = $cliente_id;
        $reserva->check_in_fecha = $this->formatDate($data_detail->Arrival);
        $reserva->check_out_fecha = $this->formatDate($data_detail->Departure);
        $reserva->fuente_reserva_id = $fuente->id;
        $reserva->huespedes_cantidad = (int)$dataReserva->Adults + (int)$dataReserva->Children + (int)$dataReserva->Infants;
        $reserva->precio_total = $dataReserva->Average_Rate * $dias;
        $reserva->res_code = $data->Res_Code;
        $reserva->estado_reserva = 1;
        $reserva->save();
        $id_reserva = $reserva->id;
        $cuenta = new Cuenta();
        $cuenta->nombre = 'Hospedaje'.'-'.$reserva->id;
        $cuenta->reserva_id = (int)$id_reserva;
        $cuenta->save();
         //====================CREAR RESERVACION DETALLE////====================//
         $reservaController = new ReservasController();
         $reservaController->nuevos_grupos($data_detail, $id_reserva, $cliente_id, $collection, $data->Action);         
         //====================CREAR RESERVACION DETALLE====================//
         return $id_reserva;

    }
    public function crearReservaTmp($data_detail, $cliente_id, $data, $id_reserva, $BookingChannel, $RequestorID, $collection, $temp)
    {

        $dataReserva = $data->ROOM_TYPES->ROOM_TYPE;
        $total_reserva = 0;
        $total_huespedes = 0;
        $fuente = FuenteReserva::select('id')->where('id_ota', '=', $RequestorID)->first();
        $sum = 0.0;
        $date1 = new \DateTime($data_detail->Arrival);
        $date2 = new \DateTime($data_detail->Departure);
        $diff = $date1->diff($date2);
        $dias = $diff->days;
        $fuente = FuenteReserva::select('id')->where('id_ota', '=', $RequestorID)->first();

        $reserva = new TblReserva();
        $reserva->id_cliente = $cliente_id;
        $reserva->check_in_fecha = $this->formatDate($data_detail->Arrival);
        $reserva->check_out_fecha = $this->formatDate($data_detail->Departure);
        $reserva->fuente_reserva_id = $fuente->id;
        $reserva->huespedes_cantidad = (int)$temp->Adults + (int)$temp->Children + (int)$temp->Infants;
        $reserva->precio_total = $sum;
        $reserva->res_code = $data->Res_Code;
        $reserva->estado_reserva = 1;
        $reserva->save();
        $id_reserva = $reserva->id;
        $cuenta = new Cuenta();
        $cuenta->nombre = 'Hospedaje'.'-'.$reserva->id;
        $cuenta->reserva_id = (int)$id_reserva;
        $cuenta->save();

         //====================CREAR RESERVACION DETALLE////====================//
         $reservaController = new ReservasController();
         $reservaController->nuevos_grupos($data_detail, $id_reserva, $cliente_id, $collection, $data->Action);         
         //====================CREAR RESERVACION DETALLE====================//
         return $id_reserva;

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
}
