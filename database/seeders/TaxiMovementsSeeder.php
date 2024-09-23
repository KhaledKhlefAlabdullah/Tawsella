<?php

namespace Database\Seeders;

use App\Enums\UserEnums\UserGender;
use App\Models\TaxiMovement;
use Illuminate\Database\Seeder;
use App\Models\TaxiMovementType;

class TaxiMovementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TaxiMovement::create([
            'customer_id' => 'Asds-52664d66-aasd5566-C1',
            'movement_type_id' => TaxiMovementType::where('is_general', true)->first()->id,
            'start_address' => 'azaz moole',
            'destination_address' => 'afrin',
            'gender' => UserGender::male,
            'start_latitude' => 35.6266,
            'start_longitude' => 24.8895
        ]);

        TaxiMovement::create([
            'customer_id' => 'Asds-52664d66-aasd5566-C2',
            'movement_type_id' => TaxiMovementType::where('is_general', true)->first()->id,
            'start_address' => 'azaz city',
            'destination_address' => 'sarmada',
            'gender' => UserGender::female,
            'start_latitude' => 35.6266,
            'start_longitude' => 24.8895
        ]);
    }
}
