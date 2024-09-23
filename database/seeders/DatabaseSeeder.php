<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            AdminSeeder::class,
            TaxiMovementTypesSeeder::class,
            UsersSeeder::class,
            TaxiMovementsSeeder::class,
            OffersSeeder::class,
            AboutUsSeeder::class,
        ]);
    }
}
