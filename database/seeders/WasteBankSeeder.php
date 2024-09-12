<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WasteBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('waste_banks')->insert([
            'waste_name' => "TPS3R DESA GENTENG KULON",
            'village_id' => 1
        ]);
        DB::table('waste_banks')->insert([
            'waste_name' => "TPS3R KSM DESA SETAIL",
            'village_id' => 2
        ]);
        DB::table('waste_banks')->insert([
            'waste_name' => "TPS3R KSM RIJIG DESA PURWODADI",
            'village_id' => 3
        ]);
        DB::table('waste_banks')->insert([
            'waste_name' => "TPS3R DESA TEMBOKREJO",
            'village_id' => 4
        ]);
        DB::table('waste_banks')->insert([
            'waste_name' => "TPS3R DESA SUMBERBERAS",
            'village_id' => 5
        ]);
    }
}
