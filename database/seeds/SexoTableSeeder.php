<?php

use Illuminate\Database\Seeder;
use App\Models\TblGenero;


class SexoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TblGenero::create( [
		'id' => 1,
		'nombre'=>'Masculino'
		] );
					

		
		TblGenero::create( [
		'id' => 2,
		'nombre'=>'Femenino'

		] );

    }
}
