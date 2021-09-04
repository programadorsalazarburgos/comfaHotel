<?php

use Illuminate\Database\Seeder;

class TipoHabitacionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         \DB::table('tbl_habitaciones_tipo')->delete();
        
        \DB::table('tbl_habitaciones_tipo')->insert(array (
            0 => 
            array (
                'id' => 1,
                'codigo' => 'DouR',
                'nombre' => 'Double Room',
                'persona_minimo' => 1,
                'persona_maximo' => 10,
                'id_camas' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            1 => 
            array (
                'id' => 2,
                'codigo' => 'DR',
                'nombre' => 'Deluxe Room',
                'persona_minimo' => 1,
                'persona_maximo' => 11,
                'id_camas' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
      
        ));
        
    }
}
