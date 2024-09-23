<?php

namespace Database\Seeders;

use App\Enums\UserEnums\UserType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'id' => 'Asds-52664d66-aasd5566-C2',
                'email'=>'customer2@gmail.com',
                'password'=>Hash::make('12345678'),
                'mail_code_verified_at' => now(),
            ],
            [
                'id' => 'Asds-52664d66-aasd5566-C1',
                'email'=>'customer1@gmail.com',
                'password'=>Hash::make('12345678'),
                'mail_code_verified_at' => now(),
            ]
        ];
        foreach ($users as $user) {
            $user = User::create($user);
            $user->assignRole(UserType::Customer()->key);
        }

        $driver = User::create([
            'id' => 'Asds-52664d66-aasd5566-D1',
            'email'=>'driver1@gmail.com',
            'password'=>Hash::make('12345678'),
            'mail_code_verified_at' => now(),
        ]);

        $user->assignrole(UserType::TaxiDriver()->key);
    }
}
