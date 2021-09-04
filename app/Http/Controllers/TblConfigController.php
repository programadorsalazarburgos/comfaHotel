<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TblConfig;
use PGSchema;
use Auth;


class TblConfigController extends Controller
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
    public function Get($name)
    {
        $res=[];
        try {
            $res = TblConfig::select('value')->where('name',$name)->first();
            $res = (is_null($res))?['validate'=>false,'value'=>FALSE]:['validate'=>TRUE,'value'=>$res->value];
        }
        catch (\Exception $e)
        {
            $res = ['validate'=>false,'value'=>FALSE];
        }
        return ($res);
    }
    public static function Geter($name)//Para llamar desde otro controllador
    {
        $res=[];
        try {
            $res = TblConfig::select('value')->where('name',$name)->first();
            $res = (is_null($res))?['validate'=>false,'value'=>FALSE]:['validate'=>TRUE,'value'=>$res->value];
        }
        catch (\Exception $e)
        {
            $res = ['validate'=>false,'value'=>FALSE];
        }
        return (object)($res);
    }
    public static function Setter($name,$value)
    {
        $data= new TblConfig();
        $data->name=$name;
        $data->value=$value;
        $data->save();
        return $data->name;
    }
}
