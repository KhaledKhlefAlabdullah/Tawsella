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
                'id' => '3973d286-df6f-4eb8-be0e-283nde47cb1a',
                'email'=>'customer2@gmail.com',
                'password'=>Hash::make('12345678'),
                'mail_code_verified_at' => now(),
            ],
            [
                'id' => '3173d286-df6f-4eb8-be0e-283ade37cb1a',
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
            'id' => '3473d276-df6f-4eb8-be0e-283ade38cb1a',
            'email'=>'driver1@gmail.com',
            'password'=>Hash::make('12345678'),
            'mail_code_verified_at' => now(),
        ]);

        $driver->assignrole(UserType::TaxiDriver()->key);
    }
}
