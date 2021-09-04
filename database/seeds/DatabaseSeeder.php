<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $this->call(UsersTableSeeder::class);
        $this->call(TblCamasTableSeeder::class);
        $this->call(TipoHabitacionTableSeeder::class);
        $this->call(TblPaisesTableSeeder::class);
        $this->call(TblMonedasTableSeeder::class);
        $this->call(TblHabitacionEsestadoSeeder::class);
        $this->call(TblDocumentoTipoTableSeeder::class);
        $this->call(TblConfigSeeder::class);
        $this->call(TblClientesTipoTableSeeder::class);
        $this->call(SexoTableSeeder::class);
        $this->call(ReservaEstadoTableSeeder::class);



        

     }
}
