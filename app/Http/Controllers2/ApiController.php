<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SoapClient;
use SoapHeader;
use GuzzleHttp\Client;
use App\Http\Controllers\ReservasController;
use DB;
use PGSchema;
use Auth;
use App\Models\TblConfig;
use App\Models\TblReservasDetalle;
use App\Models\TblReservasGrupo;
use App\Models\TblHabitacionesTipo;
use App\Bitacora;

class ApiController extends Controller
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
	private function datosuser()
	{
		$datos=TblConfigController::Geter('data_api');
		$res=(json_decode($datos->value));
		return $res;
	}
    public function __construct(Request $request)
    {
			$this->middleware(function ($request, $next) {
					 $this->projects = Auth::user()->schema;
					 PGSchema::switchTo($this->projects);
					 return $next($request);
			 });

			$datos=[];
			$datos = TblConfig::select('value')->where('name','data_api')->first();
			$datos = (is_null($datos))?['validate'=>false,'value'=>FALSE]:['validate'=>TRUE,'value'=>$datos->value];
			$res=(json_decode($datos['value']));
			$user=$res;

			$this->request = $request;
			$this->openSoapEnvelope   =   '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"
			xmlns:xsd="http://www.w3.org/2001/XMLSchema"
			xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
			$this->soapHeader         =   '<soap:Header>
			<HTNGHeader xmlns="http://htng.org/1.1/Header/"></HTNGHeader>
			<soap:Username>'.$user->user.'</soap:Username>
			<soap:Password>'.$user->pass.'</soap:Password>
			</soap:Header>';
			$this->closeSoapEnvelope   =   '</soap:Envelope>';
	}
	public function generateSoapRequest($soapBody)
	{
		return $this->openSoapEnvelope . $this->soapHeader . $soapBody . $this->closeSoapEnvelope;
	}
	private function con($xmlRequest,$url,$type='POST')
	{
		$client = new Client();
        $options = [
            'body'    => $xmlRequest,
            'headers' => [
                "Content-Type" => "text/xml",
                "accept" => "*/*",
                "accept-encoding" => "gzip, deflate"
            ]
        ];
        $res = $client->request(
            $type,
            $url,
            $options
        );
        return $res;
	}
	private function ApiVerReservas($shema_id)
	{
		PGSchema::switchTo($shema_id);
		$datos=[];
		$datos = TblConfig::select('value')->where('name','data_api')->first();
		$datos = (is_null($datos))?['validate'=>false,'value'=>FALSE]:['validate'=>TRUE,'value'=>$datos->value];
		$res=(json_decode($datos['value']));
		try {
			$user = $res;
			$url  = 'https://reservations.orbebooking.com/admin/OAF/BOOKRET/XML';
			$xmlRequest   =   '<ReservationsRequest>
			<Username>'.$user->user.'</Username>
			<Password>'.$user->pass.'</Password>
			<HotelCode>'.$user->code.'</HotelCode>
			</ReservationsRequest>';
			$res=$this->con($xmlRequest,$url);
			$data = $res->getBody();
			if(trim($data)!=='<RESULT>   <RESERVATIONS>   </RESERVATIONS></RESULT>')
			{
			$data_config= new TblConfig();
			$data_config->name='reserva'.date('YmdHis');
			$data_config->value=trim($data);
			$data_config->save();
			}
			return simplexml_load_string($data);
		} catch (\Throwable $th) {
			return simplexml_load_string('<data></data>');
		}
	}

	public function CrearInventario($fecha_actual, $fecha_salida, $id_habitacion_tipo, $number)
	{
		PGSchema::switchTo(Auth::user()->schema);
		$datos=[];
		$datos = TblConfig::select('value')->where('name','data_api')->first();
		$datos = (is_null($datos))?['validate'=>false,'value'=>FALSE]:['validate'=>TRUE,'value'=>$datos->value];
		$res=(json_decode($datos['value']));
		$user = $res;
		$url  = 'https://reservations.orbebooking.com/admin/OAF/AOBA-XML';
		$xmlRequest   =   '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
		$xmlRequest.='<soap:Header>
						<HTNGHeader xmlns="http://htng.org/1.1/Header/"></HTNGHeader>
						<soap:Username>'.$user->user.'</soap:Username>
						<soap:Password>'.$user->pass.'</soap:Password>
						</soap:Header>
						<soap:Body>
						<InventoryUpdateRequest TimeStamp="2017-8-22T18:12:16" Version="1.00">
						<INVENTORY HotelCode="'.$user->code.'" HotelName="DIAMOND DEMO">'."\n			";

		$tipoHabitacion = TblHabitacionesTipo::select('tbl_habitaciones_tipo.room_type')
		->where('tbl_habitaciones_tipo.id', $id_habitacion_tipo)
		->firstOrfail();
		$room_type = (string)$tipoHabitacion->room_type;
		for($i=$this->formatDate($fecha_actual);$i<$this->formatDate($fecha_salida);$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
				$xmlRequest.='<Update Inv_Date="'.$i.'" Quantity="'.$number.'" Room_Type="'.$room_type.'" Task="Add"/>';
	 }//
	 $xmlRequest   .=   '</INVENTORY></InventoryUpdateRequest></soap:Body></soap:Envelope>';
	 $res  = $this->con($xmlRequest,$url);
	 $data = $res->getBody();
	 $data = simplexml_load_string($data);
	 return (object)['validate'=>true,'res'=>$data];
	}
	public function CrearInventarioManual($request, $number)
	{
		PGSchema::switchTo(Auth::user()->schema);
		$datos=[];
		$datos = TblConfig::select('value')->where('name','data_api')->first();
		$datos = (is_null($datos))?['validate'=>false,'value'=>FALSE]:['validate'=>TRUE,'value'=>$datos->value];
		$res=(json_decode($datos['value']));
		$user = $res;
		$url  = 'https://reservations.orbebooking.com/admin/OAF/AOBA-XML';
		$xmlRequest   =   '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
		$xmlRequest.='<soap:Header>
						<HTNGHeader xmlns="http://htng.org/1.1/Header/"></HTNGHeader>
						<soap:Username>'.$user->user.'</soap:Username>
						<soap:Password>'.$user->pass.'</soap:Password>
						</soap:Header>
						<soap:Body>
						<InventoryUpdateRequest TimeStamp="2017-8-22T18:12:16" Version="1.00">
						<INVENTORY HotelCode="'.$user->code.'" HotelName="DIAMOND DEMO">'."\n			";
		foreach ($request->data as $value) {
			$tipoHabitacion = TblHabitacionesTipo::select('tbl_habitaciones_tipo.room_type')
			->where('tbl_habitaciones_tipo.id', $value['tipo_habitacion_id'])
			->firstOrfail();
			$room_type = (string)$tipoHabitacion->room_type;
			for($i=$this->formatDate($value['ingreso']);$i<$this->formatDate($value['salida']);$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
				$xmlRequest.='<Update Inv_Date="'.$i.'" Quantity="'.$number.'" Room_Type="'.$room_type.'" Task="Add"/>';
			}
			$xmlRequest   .=   '</INVENTORY></InventoryUpdateRequest></soap:Body></soap:Envelope>';
			$res  = $this->con($xmlRequest,$url);
			$data = $res->getBody();
			$data = simplexml_load_string($data);
			return (object)['validate'=>true,'res'=>$data];
		}
	}
	public function ActualizarInventario($data, $grupo, $llegada, $salida, $tipoHabitacion, $bitacora)//array(['habitacion_tipo'=>'SR','habitacion_tipo_id'=>1,'fecha'=>'2018-01-01','cantidad_disponible'=>1],['habitacion_tipo'=>'SR','habitacion_tipo_id'=>1,'fecha'=>'2018-01-02','cantidad_disponible'=>5])
	{
		PGSchema::switchTo(Auth::user()->schema);
		$datos=[];
		$datos = TblConfig::select('value')->where('name','data_api')->first();
		$datos = (is_null($datos))?['validate'=>false,'value'=>FALSE]:['validate'=>TRUE,'value'=>$datos->value];
		$res=(json_decode($datos['value']));
		$user = $res;
		$url  = 'https://reservations.orbebooking.com/admin/OAF/AOBA-XML';
		$xmlRequest   =   '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
		$xmlRequest.='<soap:Header>
						<HTNGHeader xmlns="http://htng.org/1.1/Header/"></HTNGHeader>
						<soap:Username>'.$user->user.'</soap:Username>
						<soap:Password>'.$user->pass.'</soap:Password>
						</soap:Header>
						<soap:Body>
						<InventoryUpdateRequest TimeStamp="2017-8-22T18:12:16" Version="1.00">
						<INVENTORY HotelCode="'.$user->code.'" HotelName="DIAMOND DEMO">'."\n			";

		$xmlRequest2   =   '<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">';
		$xmlRequest2.='<soap:Header>
						<HTNGHeader xmlns="http://htng.org/1.1/Header/"></HTNGHeader>
						<soap:Username>'.$user->user.'</soap:Username>
						<soap:Password>'.$user->pass.'</soap:Password>
						</soap:Header>
						<soap:Body>
						<InventoryUpdateRequest TimeStamp="2017-8-22T18:12:16" Version="1.00">
						<INVENTORY HotelCode="'.$user->code.'" HotelName="DIAMOND DEMO">'."\n			";

		$data_reserva_fecha = TblReservasGrupo::find((int)$grupo);
		$rommType1 = TblReservasGrupo::select('tbl_habitaciones_tipo.room_type')->where('tbl_reservas_grupo.id', (int)$grupo)
		->join('tbl_habitaciones_tipo', 'tbl_reservas_grupo.tipo_habitacion_id', '=', 'tbl_habitaciones_tipo.id')
		->firstOrfail();
		$uno = (string)$rommType1->room_type;
		for($i=$data_reserva_fecha->check_in_fecha;$i<$data_reserva_fecha->check_out_fecha;$i = date("Y-m-d", strtotime($i ."+ 1 days"))){
			 	$xmlRequest.='<Update Inv_Date="'.$i.'" Quantity="1" Room_Type="'.$uno.'" Task="Add"/>';
	 }
	 $xmlRequest   .=   '</INVENTORY></InventoryUpdateRequest></soap:Body></soap:Envelope>';
	 $res  = $this->con($xmlRequest,$url);
	 $data = $res->getBody();
	 $data = simplexml_load_string($data);
	 $salidas = TblReservasGrupo::where('id', $grupo)->where('id_reservas_estado', 4)->exists();
	 if ($salidas == true) {
		 $data_grupo = TblReservasGrupo::find($grupo);
		 $data_grupo->check_in_fecha  = date('Y-m-d', strtotime($llegada));
		 $data_grupo->check_out_fecha = date('Y-m-d', strtotime($salida));
		 $data_grupo->id_reservas_estado = 2;
		 $data_grupo->tipo_habitacion_id = $tipoHabitacion;
		 $data_grupo->save();
	 }
	 $llegadas = TblReservasGrupo::where('id', $grupo)->where('id_reservas_estado', 1)->exists();
	 if ($llegadas == true) {
		 $data_grupo = TblReservasGrupo::find($grupo);
		 $data_grupo->check_in_fecha  = date('Y-m-d', strtotime($llegada));
		 $data_grupo->check_out_fecha = date('Y-m-d', strtotime($salida));
		 $data_grupo->id_reservas_estado = 1;
		 $data_grupo->tipo_habitacion_id = $tipoHabitacion;
		 $data_grupo->save();
	 }
	 $checkin = TblReservasGrupo::where('id', $grupo)->where('id_reservas_estado', 2)->exists();
	 if ($checkin == true) {
		 $data_grupo = TblReservasGrupo::find($grupo);
		 $data_grupo->check_in_fecha  = date('Y-m-d', strtotime($llegada));
		 $data_grupo->check_out_fecha = date('Y-m-d', strtotime($salida));
		 $data_grupo->id_reservas_estado = 2;
		 $data_grupo->tipo_habitacion_id = $tipoHabitacion;
		 $data_grupo->save();
	 }
	 $data_reserva_fecha = TblReservasGrupo::find($grupo);
	 $rommType2 = TblReservasGrupo::select('tbl_habitaciones_tipo.room_type')->where('tbl_reservas_grupo.id', $grupo)
	 ->join('tbl_habitaciones_tipo', 'tbl_reservas_grupo.tipo_habitacion_id', '=', 'tbl_habitaciones_tipo.id')
	 ->firstOrfail();
	 $dos = (string)$rommType2->room_type;
	 for($i2=$data_reserva_fecha->check_in_fecha;$i2<$data_reserva_fecha->check_out_fecha;$i2 = date("Y-m-d", strtotime($i2 ."+ 1 days"))){
			 $xmlRequest2.='<Update Inv_Date="'.$i2.'" Quantity="-1" Room_Type="'.$dos.'" Task="Add"/>';
			 var_dump($i2);
	}
	$xmlRequest2   .=   '</INVENTORY></InventoryUpdateRequest></soap:Body></soap:Envelope>';
	$res2  = $this->con($xmlRequest2,$url);
	$data2 = $res2->getBody();
	$data2 = simplexml_load_string($data2);
	$bitacoras = new Bitacora();
	$bitacoras->user_id = Auth::user()->id;
	$bitacoras->reserva_id = (int)$bitacora['reserva_id'];
	$bitacoras->grupo_id = (int)$bitacora['grupo_id'];
	$bitacoras->habitacion_id = (int)$bitacora['habitacion_id'];
	$bitacoras->fecha_llegada_anterior = $bitacora['fecha_llegada_anterior'];
	$bitacoras->fecha_salida_anterior = $bitacora['fecha_salida_anterior'];
	$bitacoras->fecha_llegada_actual = $this->formatDate($bitacora['fecha_llegada_actual']);
	$bitacoras->fecha_salida_actual = $this->formatDate($bitacora['fecha_salida_actual']);
	$bitacoras->xml = $xmlRequest.$xmlRequest2;
	$bitacoras->respuesta = 'resp1:'. ' '.$res->getStatusCode(). ' '. 'resp2:'. ' '. $res2->getStatusCode();
	$bitacoras->tipo_movimiento = $bitacora['tipo_movimiento'];
	$bitacoras->save();
		return (object)['validate'=>true,'res'=>$data];
	}
	private function formatDate($date)
	{
			$date = (string)$date;
			$date = \date('Y-m-d', strtotime($date));
			return ($date);
	}
	public function CargarReservas($shema_id)
	{
		$data='';
		$n=1;
		switch($n)
		{
			case 1:
			$data = $this->ApiVerReservas($shema_id);//WEB SERVICES;
			break;
			case 2:
			$data=$this->pruebas('reserva20190518222837');//Este borra todo
			break;
			case 3:
			$data=TblConfigController::Geter('modifcacion');
			$data = simplexml_load_string($data->value);//LOCAL PRUEBAS
			break;
			case 4:
			$data = $this->ApiVerReservas($shema_id);//WEB SERVICES;
			break;

		}
		$data=json_decode(json_encode($data));

		if(key($data->RESERVATIONS)!==0)
		{
			if(isset($data->RESERVATIONS->RESERVATION))
			{
				switch(gettype($data->RESERVATIONS->RESERVATION))
				{
					case 'object':
						if(!is_null($data->RESERVATIONS->RESERVATION))
						{
							$data = $data->RESERVATIONS->RESERVATION;
							$reserva = new ReservasController();
							$reserva->CrearReservaciones($data);
						}
						else{

						}
					break;
					case 'array':
						foreach($data->RESERVATIONS->RESERVATION as $key=>$temp)
						{
				    	$reserva2 = new ReservasController();
							$reserva2->CrearReservaciones($temp);
						}
					break;
				}
			}
		}
	}
	public function VerReservas()
	{
		$reserva = new ReservasController();
		$this->CargarReservas(Auth::user()->schema);
		return $reserva->VerReservasChanel();
	}
    public function api()
    {
			PGSchema::switchTo(Auth::user()->schema);
			$datos=[];
			$datos = TblConfig::select('value')->where('name','data_api')->first();
			$datos = (is_null($datos))?['validate'=>false,'value'=>FALSE]:['validate'=>TRUE,'value'=>$datos->value];
			$res=(json_decode($datos['value']));
			$user = $res;
    }

}
