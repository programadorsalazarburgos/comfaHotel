<?php

use Illuminate\Database\Seeder;

class TblDocumentoTipoTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_documento_tipo')->delete();
        
        \DB::table('tbl_documento_tipo')->insert(array (
            0 => 
            array (
                'id' => 1,
                'descripcion' => 'Cedula',
            ),

            1 => 
            array (
                'id' => 2,
                'descripcion' => 'Pasaporte',

            ),

        ));
        
        
    }
}