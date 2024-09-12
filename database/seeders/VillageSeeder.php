<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VillageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('villages')->insert([
            'village_name' => 'GENTENGKULON',
            'village_code' => '68465'
        ]);
        DB::table('villages')->insert([
            'village_name' => 'SETAIL',
            'village_code' => '68465'
        ]);
        DB::table('villages')->insert([
            'village_name' => 'PURWODADI',
            'village_code' => '68486'
        ]);
        DB::table('villages')->insert([
            'village_name' => 'TEMBOKREJO',
            'village_code' => '68472'
        ]);
        DB::table('villages')->insert([
            'village_name' => 'SUMBERBERAS',
            'village_code' => '68472'
        ]);
    }
}
