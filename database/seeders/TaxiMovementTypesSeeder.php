<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxiMovementTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('taxi_movement_types')->insert([
            'id' => 't-m-t-1',
            'type' => 'داخل المدينة',
            'price' => 50,
            'description' => 'this is',
            'is_onKM' => false
        ]);

        DB::table('taxi_movement_types')->insert([
            'id' => 't-m-t-2',
            'type' => 'خارج المدينة',
            'price' => 50,
            'description' => 'this is',
            'is_onKM' => true
        ]);
    }
}
