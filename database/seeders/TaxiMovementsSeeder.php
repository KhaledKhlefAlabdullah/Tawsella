<?php

namespace Database\Seeders;

use App\Models\TaxiMovement;
use Illuminate\Database\Seeder;

class TaxiMovementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TaxiMovement::create([
            'customer_id' => 'Asds-52664d66-aasd5566-C1',
            'movement_type_id' => 't-m-t-1',
            'my_address' => 'azaz moole',
            'destnation_address' => 'afrin',
            'gender' => 'male',
            'start_latitude' => 35.6266,
            'start_longitude' => 24.8895
        ]);

        TaxiMovement::create([
            'customer_id' => 'Asds-52664d66-aasd5566-C2',
            'movement_type_id' => 't-m-t-3',
            'my_address' => 'azaz city',
            'destnation_address' => 'sarmada',
            'gender' => 'female',
            'start_latitude' => 35.6266,
            'start_longitude' => 24.8895
        ]);
    }
}
