<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //['customer','driver','admin']
        DB::table('users')->insert([
            'id' => Str::uuid(),
            'email'=>'customer1@gmail.com',
            'password'=>password_hash('12345678',PASSWORD_DEFAULT),
            'user_type' => 'customer',
            'created_at' => now(),
        ]);
        
        DB::table('users')->insert([
            'id' => Str::uuid(),
            'email'=>'customer2@gmail.com',
            'password'=>password_hash('12345678',PASSWORD_DEFAULT),
            'user_type' => 'customer',
            'created_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => Str::uuid(),
            'email'=>'driver1@gmail.com',
            'password'=>password_hash('12345678',PASSWORD_DEFAULT),
            'user_type' => 'driver',
            'created_at' => now(),
        ]);
    }
}
