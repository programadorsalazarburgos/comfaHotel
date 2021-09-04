<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Almacen;
use App\Models\TblConfig;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'id' => 1,
            'usuario'=>'Admin',
            'nombres'=>'Admin',
            'tipo_documento'=>'cedula',
            'documento'=>'10728626',
            'telefono'=>'324152552',
            'email'=>'admin1@gmail.com',
            'password'=>bcrypt('admin'),
            'activo'=>true,
            'remember_token'=>NULL,
            'schema'=>'master'
            ]);          

            User::create([
                'id' => 4,
                'usuario'=>'supervisor',
                'nombres'=>'supervisor',
                'tipo_documento'=>'cedula',
                'documento'=>'11728626',
                'telefono'=>'324152552',
                'email'=>'admin2@gmail.com',
                'password'=>bcrypt('supervisor'),
                'activo'=>true,
                'schema'=>'master',
                'remember_token'=>NULL
            ]);
    }
}