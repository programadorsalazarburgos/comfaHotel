<?php

use Illuminate\Database\Seeder;

class TblCamasTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_camas')->delete();
        
        \DB::table('tbl_camas')->insert(array (
            0 => 
            array (
                'id' => 1,
                'descripcion' => 'No definido',
            ),
            1 => 
            array (
                'id' => 2,
                'descripcion' => 'Individual',
            ),
            2 => 
            array (
                'id' => 3,
                'descripcion' => 'Twin',
            ),
            3 => 
            array (
                'id' => 4,
                'descripcion' => 'Doble',
            ),
            4 => 
            array (
                'id' => 5,
                'descripcion' => 'Queen',
            ),
            5 => 
            array (
                'id' => 6,
                'descripcion' => 'King',
            ),
            6 => 
            array (
                'id' => 7,
                'descripcion' => 'puede variar',
            ),
        ));
        
        
    }
}