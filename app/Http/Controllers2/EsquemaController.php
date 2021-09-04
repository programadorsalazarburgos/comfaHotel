<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PGSchema;

class EsquemaController extends Controller
{

    public static function changeConection()
    {

      $schema = Auth::user()->schema;
      PGSchema::switchTo($schema);
    }
}
