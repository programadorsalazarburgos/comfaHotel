<?php

use Illuminate\Database\Seeder;

class TblConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_config')->insert([
            'name'=>'impuestos_incluidos',
            'value'=>'1'
        ]);
        DB::table('tbl_config')->insert([
            'name'=>'data_api',
            'value'=>'{"user":"DiamondPmS","pass":"Di4MoNdPmS495","code":"DIAMONDDEMO"}'
        ]);
        
    }
}
