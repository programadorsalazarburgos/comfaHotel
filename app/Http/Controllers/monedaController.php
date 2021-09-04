<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TblMoneda;
class monedaController extends Controller
{
    public function VerMoneda(Request $request)
    {

        if(!is_null($request->input('page')))
        {
            $limit=20;
            $page=$request->input('page')-1;    
            if(!is_null($request->input('filtro')))
            {
                
                $data = TblMoneda::skip($page)->take($limit)
                ->where($request->input('criterio'),'ILIKE','%'.$request->input('filtro').'%')
                ->get();
                $total = TblMoneda
                ::where($request->input('criterio'),'ILIKE','%'.$request->input('filtro').'%')
                ->count();
            }
            else
            {
                $data = TblMoneda::skip($page)->take($limit)->get();
                $total= TblMoneda::count();
            }
            return (['moneda'  => $data,'pages'=>ceil($total/$limit)]);
        }
        else{//Cuando no tiene filtros ni nada
           return response()->json(TblMoneda::all());
        }
    }
    public function SaveEditMoneda(Request $request)
    {
        try
        {
            $data = TblMoneda::find($request->input('id'));
            $data->valor_usd=$request->input('valor_usd');
            $data->Save();
            return response()->json(['validate'=>true,'msj'=>NULL,'id'=>$data->id]);
        }
        catch(\Exception $e)
        {
            return response()->json(['validate'=>false,'msj'=>$e->getMessage(),'id'=>NULL]);
        }
    }
}
