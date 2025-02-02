<?php

namespace Database\Seeders;

use App\Enums\UserEnums\UserGender;
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

        foreach ($users as $index => $user) {
            $user = User::create($user);
            $user->assignRole(UserType::Customer()->key);
            $user->profile()->create(            [
                'name' => 'customer'.$index,
                'gender' => UserGender::male,
                'phone_number' => '+9656333564'.$index,
            ]);
        }

        $driver = User::create([
            'id' => '3473d276-df6f-4eb8-be0e-283ade38cb1a',
            'email'=>'driver1@gmail.com',
            'password'=>Hash::make('12345678'),
            'mail_code_verified_at' => now(),
        ]);

        $driver->assignrole(UserType::TaxiDriver()->key);

        $driver->taxi()->create([
            'car_name' => 'first',
            'lamp_number' => '1-F',
            'plate_number' => 'FCD-200',
        ]);

        $driver->profile()->create([
            'user_id' => '3473d276-df6f-4eb8-be0e-283ade38cb1a',
            'name' => 'driver 1',
            'gender' => UserGender::female,
            'phone_number' => '+965634535648',
        ]);
    }
}
