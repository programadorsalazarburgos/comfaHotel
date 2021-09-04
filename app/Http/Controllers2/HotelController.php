<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

Use DB;
use PGSchema;
use App\User;
use App\Models\TblHotel;
class HotelController extends Controller
{
    private function SorucesConfig($request)
    {
        try
        {
            $schemaName=trim($request->input('hotel_nombre'));
            $schemaName='hotel_'.str_replace(' ','',strtolower($schemaName));
            DB::select('CREATE SCHEMA '.$schemaName);
            PGSchema::switchTo($schemaName);
            PGSchema::migrate($schemaName);
            PGSchema::switchTo('master');
            $res = $this->GuardarDatos($request,$schemaName);
            return (object)['validate'=>$res->validate,'schema'=>$schemaName,'msj'=>$res,'res'=>$res];
        }
        catch (\Exception $e)
        {
            return (object)['validate'=>false,'schema'=>$schemaName,'msj'=>$e->getMessage()];
        }
    }
    private function Validar($nit)
    {
        $data=TblHotel::where('nit','=',$nit)->first();
        $res=[];
        if(is_null($data))
        {
            $res=[
                'validate'=>true,
                'data'=>NULL,
            ];
        }
        else
        {
            $res=[
                'validate'=>false,
                'data'=>$data,
            ];
        }
        return (object)$res;
    }
    private function NewHotel($Data,$schema)
    {
        $timezone = "UTC";
        date_default_timezone_set($Data->input('zona_horaria'));
        $utc = gmdate("M d Y h:i:s A");
        $date = gmdate('H:i', strtotime($Data->hora_cuenta));
        $hotel                          = new TblHotel();
        $hotel->nombre                  = $Data->input('hotel_nombre');
        $hotel->fecha_inicio            = $Data->input('hotel_fecha_inicio');
        $hotel->fecha_fin               = $Data->input('hotel_fecha_fin');
        $hotel->nit                     = $Data->input('hotel_nit');
        $hotel->direccion               = $Data->input('hotel_direccion');
        $hotel->responsable_nombre      = $Data->input('hotel_responsable_nombre');
        $hotel->responsable_telefono    = $Data->input('hotel_responsable_telefono');
        $hotel->api_user                = $Data->input('api_user');
        $hotel->api_password            = $Data->input('api_password');
        $hotel->api_code                = $Data->input('api_code');
        $hotel->zona_horaria            = $Data->input('zona_horaria');
        $hotel->hora_cuenta             = $Data->input('hora_cuenta');
        $hotel->hora_utc                = $date;
        $hotel->schema                  = $schema;
        $hotel->Save();
        return $hotel->id;
    }
    public function Editar(Request $Data)
    {
        $datosHotel = TblHotel::find(1);
        $timezone = "UTC";
        date_default_timezone_set($datosHotel->zona_horaria);
        $utc = gmdate("M d Y h:i:s A");
        $date = gmdate('H:i', strtotime($Data->hora_cuenta));
        $hotel                          = TblHotel::find($Data->input('id'));
        $nombre                         = $hotel->nombre;
        $hotel->nombre                  = $Data->input('hotel_nombre');
        $hotel->nit                     = $Data->input('hotel_nit');
        $hotel->direccion               = $Data->input('hotel_direccion');
        $hotel->responsable_nombre      = $Data->input('hotel_responsable_nombre');
        $hotel->responsable_telefono    = $Data->input('hotel_responsable_telefono');
        $hotel->api_user                = $Data->input('api_user');
        $hotel->api_password            = $Data->input('api_password');
        $hotel->api_code                = $Data->input('api_code');
        $hotel->hora_cuenta             = $Data->input('hora_cuenta');
        $hotel->hora_utc                = $date;
        $hotel->Save();
        return ['validate'=>true,'data'=>$hotel];
    }
    public function GuardarDatos($Data,$schema)
    {
        $hotel_nombre       = trim($Data->input('hotel_nombre'));
        $usuario            = str_replace(' ','',strtolower($hotel_nombre));
        $User               = new User();
        $User->usuario      = $usuario;
        $User->schema       = $schema;
        $User->password     = bcrypt($Data->input('hotel_nit'));
        $User->activo    = true;
        $User->Save();
        return (object)['validate'=>true,'schema'=>$schema,'data'=>$User];
    }
    public function Nueva(Request $request)
    {
        $resultado=[];
        $validar=$this->Validar($request->input('hotel_nit'));
        if($validar->validate)
        {
            $res=$this->SorucesConfig($request);
            $resultado=$request->all();
            $request->request->add(['validate' => true]);
            $request->request->add(['respuesta' => $res]);
        }
        else
        {
            $request->request->add(['validate' => false]);
            $request->request->add(['respuesta' => $validar]);
        }
        return $request;
    }


    //======================================================================================================//
    public function index(Request $request)
    {
        if(!is_null($request->input('page')))
        {
            $limit=10;
            $page=$request->input('page')-1;
            if(!is_null($request->input('filtro')))
            {
                $data = TblHotel::skip($page)->take($limit)
                ->where($request->input('criterio'),'ILIKE','%'.$request->input('filtro').'%')
                ->get();
                $total = TblHotel
                ::where($request->input('criterio'),'ILIKE','%'.$request->input('filtro').'%')
                ->count();
                foreach($data as $key => $temp)
                {
                    $data[$key]->schema=str_replace('hotel_','',$temp->schema);
                }
            }
            else
            {
                $total= TblHotel::count();
                $data = TblHotel::skip($page)->take($limit)
                ->get();
                foreach($data as $key => $temp)
                {
                    $data[$key]->schema=str_replace('hotel_','',$temp->schema);
                }
            }
            return (['hoteles'  => $data,'pages'=>ceil($total/$limit)]);
        }
        else{
            $data=TblHotel::all();
            foreach($data as $key => $temp)
            {
                $data[$key]->schema=str_replace('hotel_','',$temp->schema);
            }
            return response()->json([
                'hoteles'=>$data
            ]);
        }
    }

}
