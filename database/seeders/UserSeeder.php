<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Bahtiar Eka',
            'username' => 'bahtiareka123',
            'password' => bcrypt('rahasia123'),
            'role_id' => 1
        ]);
        DB::table('users')->insert([
            'name' => 'Ahmad Abul A',
            'username' => 'ahmadabul123',
            'password' => bcrypt('rahasia123'),
            'role_id' => 1
        ]);
        DB::table('users')->insert([
            'name' => 'Miantoko Gundo',
            'username' => 'miantokogundo123',
            'password' => bcrypt('rahasia123'),
            'role_id' => 2
        ]);
        DB::table('users')->insert([
            'name' => 'Dingga Senol',
            'username' => 'dinggasenol123',
            'password' => bcrypt('rahasia123'),
            'role_id' => 3
        ]);
    }
}
