<?php

namespace Database\Seeders;

use App\Enums\UserEnums\UserGender;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class UsersProfilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $profiles = [
            [
                'user_id' => '3973d286-df6f-4eb8-be0e-283nde47cb1a',
                'name' => 'customer 2',
                'gender' => UserGender::male,
                'phone_number' => '+96563335648',
            ],
            [
                'id' => Str::uuid(),
                'user_id' => '3173d286-df6f-4eb8-be0e-283ade37cb1a',
                'name' => 'customer 1',
                'phone_number' => '+96563335648',
            ],
            [
                'user_id' => '3473d276-df6f-4eb8-be0e-283ade38cb1a',
                'name' => 'driver 1',
                'gender' => UserGender::female,
                'phone_number' => '+965634535648',
            ]
        ];
        foreach ($profiles as $profile) {
            UserProfile::create($profile);
        }
    }
}
