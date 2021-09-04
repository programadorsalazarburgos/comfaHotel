<?php

use Illuminate\Database\Seeder;

class TblHabitacionEsestadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \DB::table('tbl_habitaciones_estado')->insert(
            array 
            (
                ['nombre' =>'Limpia',                       'color'=>'#65CC00'],
                ['nombre' =>'Sucia',                        'color'=>'#FF668A'],
                ['nombre' =>'Sucia con prioridad',          'color'=>'#FF6347'],
                ['nombre' =>'Fuera de orden',               'color'=>'#9999CC'],
                ['nombre' =>'Fuera de orden con prioridad', 'color'=>'#F09609'],
                ['nombre' =>'Uso casa',                     'color'=>'#87CEEB'],
                ['nombre' =>'Bloqueado',                    'color'=>'#696969'],
            )
        );
    }
}
