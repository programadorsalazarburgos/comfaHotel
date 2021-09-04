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

class CancelReservasController extends Controller
{
    //
    public function cancelacionReserva($data_detail, $cliente_id, $data, $id_reserva, $BookingChannel, $RequestorID, $collection)
    {

        $reserva_exists = TblReserva::where('res_code', $data->Res_Code)->exists();
        if ($reserva_exists == true) {
       
            $this->existeReservaCancelada($data_detail, $cliente_id, $data, $id_reserva, $BookingChannel, $RequestorID, $collection);

        }
        if ($reserva_exists == false) {

            $this->NoexisteReservaCancelada($data_detail, $cliente_id, $data, $id_reserva, $BookingChannel, $RequestorID, $collection);
        }

    }

    public function existeReservaCancelada($data_detail, $cliente_id, $data, $id_reserva, $BookingChannel, $RequestorID, $collection)
    {

        $reserva = TblReserva::where('res_code', $data->Res_Code)->firstOrfail();
        $reserva->estado_reserva = 2;
        $reserva->save();

        $updateReservaDetalle = TblReservasDetalle::join('tbl_reservas_grupo', 'tbl_reservas_detalle.id_reservas_grupo', '=', 'tbl_reservas_grupo.id')
            ->leftJoin('tbl_reservas', 'tbl_reservas_grupo.id_reservas', '=', 'tbl_reservas.id')
            ->where('tbl_reservas_grupo.id_reservas', $reserva->id)->update(['id_reserva_detalle_estado_habitacion' => 6]);
          
        $updateReservaGrupo = TblReservasGrupo::join('tbl_reservas', 'tbl_reservas_grupo.id_reservas', '=', 'tbl_reservas.id')->where('tbl_reservas_grupo.id_reservas', $reserva->id)
        ->update(['id_reservas_estado' => 6]);


        $dataHabitaciones = TblReservasDetalle::select('tbl_habitaciones.id')->join('tbl_reservas_grupo', 'tbl_reservas_detalle.id_reservas_grupo', '=', 'tbl_reservas_grupo.id')->
        join('tbl_reservas', 'tbl_reservas_grupo.id_reservas', '=', 'tbl_reservas.id')->
        join('tbl_habitaciones', 'tbl_reservas_detalle.id_habitacion', '=', 'tbl_habitaciones.id')
        ->where('tbl_reservas.res_code', $data->Res_Code)->groupBy('tbl_habitaciones.id')->get();

        $dataHabitacionesTipo = TblReservasDetalle::select('tbl_habitaciones.id_habitacion_tipo')->join('tbl_reservas_grupo', 'tbl_reservas_detalle.id_reservas_grupo', '=', 'tbl_reservas_grupo.id')->
        join('tbl_reservas', 'tbl_reservas_grupo.id_reservas', '=', 'tbl_reservas.id')->
        join('tbl_habitaciones', 'tbl_reservas_detalle.id_habitacion', '=', 'tbl_habitaciones.id')
        ->where('tbl_reservas.res_code', $data->Res_Code)->groupBy('tbl_habitaciones.id_habitacion_tipo')->get();

         if (count($dataHabitaciones) > 0) {
             for($i=$this->formatDate($data_detail->Arrival);$i<$this->formatDate($data_detail->Departure);$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
                 foreach ($dataHabitaciones as  $value) {
                   $habitacion_false = InventarioHabitacion::
                   whereDate('start', $i)
                   ->where('habitacion_id', (int)$value['id'])
                   ->update(['disponibilidad' => true ]);
                 }
             }
         }
         if (count($dataHabitacionesTipo) > 0) {
             for($i=$this->formatDate($data_detail->Arrival);$i<$this->formatDate($data_detail->Departure);$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
                 foreach ($dataHabitacionesTipo as  $value) {
                   $descontar_inventario = Inventario::where('tipo_habitacion_id', '=', $value['id_habitacion_tipo'])
                   ->whereDate('start',$i)
                   ->firstOrfail();
                   $descontar_inventario->disponibilidad = (int)$descontar_inventario->disponibilidad + 1;
                   $descontar_inventario->save();

                 }
             }
         }
         $room_type = TblHabitacionesTipo::where('codigo', $data->ROOM_TYPES->ROOM_TYPE->Type_Code)->first();
         $reservaController = new ReservasController();
         $reservaController->aumentar_disponibilidad_orbe_c($this->formatDate($data_detail->Arrival), $this->formatDate($data_detail->Departure), $room_type->room_type);        

    }

    public function NoexisteReservaCancelada($data_detail, $cliente_id, $data, $id_reserva, $BookingChannel, $RequestorID, $collection)
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
        $reserva->estado_reserva = 2;
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

    public function cancelacionReservaTemp($data_detail, $cliente_id, $data, $id_reserva, $BookingChannel, $RequestorID, $collection, $temp)
    {

        $reserva_exists = TblReserva::where('res_code', $data->Res_Code)->exists();
        if ($reserva_exists == true) {
       
            $this->existeReservaCancelada($data_detail, $cliente_id, $data, $id_reserva, $BookingChannel, $RequestorID, $collection);

        }
        if ($reserva_exists == false) {

            $this->NoexisteReservaCanceladaTemp($data_detail, $cliente_id, $data, $id_reserva, $BookingChannel, $RequestorID, $collection, $temp);
        }

    }

    public function NoexisteReservaCanceladaTemp($data_detail, $cliente_id, $data, $id_reserva, $BookingChannel, $RequestorID, $collection, $temp)
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
        $reserva->estado_reserva = 2;
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
