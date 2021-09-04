<?php

use Illuminate\Database\Seeder;
use App\TblReservaEstado;


class ReservaEstadoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TblReservaEstado::create( [
        'id' => 1,
        'descripcion'=>'LLegadas',
        'color' => '#DF102F'
        ] );
                    

        
        TblReservaEstado::create( [
        'id' => 2,
        'descripcion'=>'Alojado',
        'color' => '#1467EF'

        ] );



        TblReservaEstado::create( [
        'id' => 3,
        'descripcion'=>'Facturado',
        'color' => '#1155cc'

        ] );

        TblReservaEstado::create( [
        'id' => 4,
        'descripcion'=>'Salidas',
        'color' => '#14B319'

        ] );

        TblReservaEstado::create( [
        'id' => 5,
        'descripcion'=>'Disponibles',
        'color' => '#1155cc'

        ] );

        TblReservaEstado::create( [
        'id' => 6,
        'descripcion'=>'Anulacion',
        'color' => '#1155cc'

        ] );

        TblReservaEstado::create( [
        'id' => 7,
        'descripcion'=>'Checkout',
        'color' => '#1155cc'

        ] );



    }

}
