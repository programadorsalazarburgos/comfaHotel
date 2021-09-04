<?php

use Illuminate\Database\Seeder;

class TblDepartamentosTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tbl_departamentos')->delete();
        
        \DB::table('tbl_departamentos')->insert(array (
            0 => 
            array (
                'id' => 5,
                'nombre_departamento' => 'ANTIOQUIA',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            1 => 
            array (
                'id' => 8,
                'nombre_departamento' => 'ATLANTICO',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            2 => 
            array (
                'id' => 9,
                'nombre_departamento' => 'BARRANQUILLA D.E',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            3 => 
            array (
                'id' => 11,
                'nombre_departamento' => 'BOGOTA',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            4 => 
            array (
                'id' => 13,
                'nombre_departamento' => 'BOLIVAR',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            5 => 
            array (
                'id' => 15,
                'nombre_departamento' => 'BOYACA',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            6 => 
            array (
                'id' => 17,
                'nombre_departamento' => 'CALDAS',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            7 => 
            array (
                'id' => 18,
                'nombre_departamento' => 'CAQUETA',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            8 => 
            array (
                'id' => 19,
                'nombre_departamento' => 'CAUCA',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            9 => 
            array (
                'id' => 20,
                'nombre_departamento' => 'CESAR',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            10 => 
            array (
                'id' => 23,
                'nombre_departamento' => 'CORDOBA',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            11 => 
            array (
                'id' => 25,
                'nombre_departamento' => 'CUNDINAMARCA',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            12 => 
            array (
                'id' => 27,
                'nombre_departamento' => 'CHOCO',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            13 => 
            array (
                'id' => 41,
                'nombre_departamento' => 'HUILA',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            14 => 
            array (
                'id' => 44,
                'nombre_departamento' => 'LA GUAJIRA',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            15 => 
            array (
                'id' => 48,
                'nombre_departamento' => 'SANTA MARTA D.E',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            16 => 
            array (
                'id' => 50,
                'nombre_departamento' => 'META',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            17 => 
            array (
                'id' => 52,
                'nombre_departamento' => 'NARINO',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            18 => 
            array (
                'id' => 54,
                'nombre_departamento' => 'NORTE DE SANTANDER',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            19 => 
            array (
                'id' => 63,
                'nombre_departamento' => 'QUINDIO',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            20 => 
            array (
                'id' => 66,
                'nombre_departamento' => 'RISARALDA',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            21 => 
            array (
                'id' => 68,
                'nombre_departamento' => 'SANTANDER',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            22 => 
            array (
                'id' => 70,
                'nombre_departamento' => 'SUCRE',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            23 => 
            array (
                'id' => 73,
                'nombre_departamento' => 'TOLIMA',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            24 => 
            array (
                'id' => 76,
                'nombre_departamento' => 'VALLE',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            25 => 
            array (
                'id' => 81,
                'nombre_departamento' => 'ARAUCA',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            26 => 
            array (
                'id' => 85,
                'nombre_departamento' => 'CASANARE',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            27 => 
            array (
                'id' => 86,
                'nombre_departamento' => 'PUTUMAYO',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            28 => 
            array (
                'id' => 88,
                'nombre_departamento' => 'SAN ANDRES',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            29 => 
            array (
                'id' => 91,
                'nombre_departamento' => 'AMAZONAS',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            30 => 
            array (
                'id' => 94,
                'nombre_departamento' => 'GUANIA',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            31 => 
            array (
                'id' => 95,
                'nombre_departamento' => 'GUAVIARE',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            32 => 
            array (
                'id' => 97,
                'nombre_departamento' => 'VAUPES',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
            33 => 
            array (
                'id' => 99,
                'nombre_departamento' => 'VICHADA',
                'id_pais' => 1,
                'created_at' => '2018-11-07 11:22:18',
                'updated_at' => '2018-11-07 11:22:18',
            ),
        ));
        
        
    }
}