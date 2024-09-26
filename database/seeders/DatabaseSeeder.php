<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            UsersProfilesSeeder::class,
            OffersSeeder::class,
            AboutUsSeeder::class
        ]);
    }
}
