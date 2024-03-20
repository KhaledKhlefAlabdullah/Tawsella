<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
            'id' => 'Asds-52664d66-aasd5566-C1',
            'email'=>'customer1@gmail.com',
            'password'=>Hash::make('12345678'),
            'user_type' => 'customer',
            'created_at' => now(),
        ]);
        
        DB::table('users')->insert([
            'id' => 'Asds-52664d66-aasd5566-C2',
            'email'=>'customer2@gmail.com',
            'password'=>Hash::make('12345678'),
            'user_type' => 'customer',
            'created_at' => now(),
        ]);

        DB::table('users')->insert([
            'id' => 'Asds-52664d66-aasd5566-D1',
            'email'=>'driver1@gmail.com',
            'password'=>Hash::make('12345678'),
            'user_type' => 'driver',
            'created_at' => now(),
        ]);
    }
}
