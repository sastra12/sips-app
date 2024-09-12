<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("customers")->insert([
            'customer_name' => 'Gugus Prasetyo',
            'customer_address' => 'Dusun Krajan',
            'customer_neighborhood' => 01,
            'customer_community_association' => 01,
            'rubbish_fee' => 15000,
            'customer_status' => 'Rumah Tangga',
            'waste_id' => 1
        ]);
        DB::table("customers")->insert([
            'customer_name' => 'Sandi Pranata',
            'customer_address' => 'Dusun Krajan',
            'customer_neighborhood' => 01,
            'customer_community_association' => 01,
            'rubbish_fee' => 15000,
            'customer_status' => 'Rumah Tangga',
            'waste_id' => 1
        ]);
        DB::table("customers")->insert([
            'customer_name' => 'Viki Syahrosi',
            'customer_address' => 'Dusun Krajan',
            'customer_neighborhood' => 01,
            'customer_community_association' => 01,
            'rubbish_fee' => 15000,
            'customer_status' => 'Rumah Tangga',
            'waste_id' => 1
        ]);
        DB::table("customers")->insert([
            'customer_name' => 'Agung Dewantara',
            'customer_address' => 'Dusun Krajan',
            'customer_neighborhood' => 01,
            'customer_community_association' => 01,
            'rubbish_fee' => 15000,
            'customer_status' => 'Rumah Tangga',
            'waste_id' => 2
        ]);
        DB::table("customers")->insert([
            'customer_name' => 'Doni Kurniawan',
            'customer_address' => 'Dusun Krajan',
            'customer_neighborhood' => 01,
            'customer_community_association' => 01,
            'rubbish_fee' => 15000,
            'customer_status' => 'Rumah Tangga',
            'waste_id' => 2
        ]);
        DB::table("customers")->insert([
            'customer_name' => 'Dani Kusuma',
            'customer_address' => 'Dusun Krajan',
            'customer_neighborhood' => 01,
            'customer_community_association' => 01,
            'rubbish_fee' => 15000,
            'customer_status' => 'Rumah Tangga',
            'waste_id' => 2
        ]);
        DB::table("customers")->insert([
            'customer_name' => 'Pradiar Ikhsanu',
            'customer_address' => 'Dusun Krajan',
            'customer_neighborhood' => 01,
            'customer_community_association' => 01,
            'rubbish_fee' => 15000,
            'customer_status' => 'Rumah Tangga',
            'waste_id' => 5
        ]);
        DB::table("customers")->insert([
            'customer_name' => 'Affan Kurniawan',
            'customer_address' => 'Dusun Krajan',
            'customer_neighborhood' => 01,
            'customer_community_association' => 01,
            'rubbish_fee' => 15000,
            'customer_status' => 'Rumah Tangga',
            'waste_id' => 5
        ]);
    }
}
