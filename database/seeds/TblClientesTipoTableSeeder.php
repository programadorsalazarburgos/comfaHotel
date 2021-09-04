<?php

use Illuminate\Database\Seeder;

class TblClientesTipoTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_clientes_tipo')->delete();
        
        \DB::table('tbl_clientes_tipo')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nombre' => 'Persona',
            ),
            1 => 
            array (
                'id' => 2,
                'nombre' => 'Empresa',
            ),
            2 => 
            array (
                'id' => 3,
                'nombre' => 'Agencia',
            ),
        ));
        
        
    }
}