<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'role_name' => 'ADMIN YRPW'
        ]);
        DB::table('roles')->insert([
            'role_name' => 'ADMIN TPS3R'
        ]);
        DB::table('roles')->insert([
            'role_name' => 'ADMIN FASILITATOR'
        ]);
    }
}
