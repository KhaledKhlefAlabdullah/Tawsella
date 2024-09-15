<?php

namespace Database\Seeders;

use App\Models\UserProfile;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
                'user_id' => 'Asds-52664d66-aasd5566-C2',
                'name' => 'customer 2',
                'phoneNumber' => '+96563335648',
            ],
            [
                'id' => Str::uuid(),
                'user_id' => 'Asds-52664d66-aasd5566-C1',
                'name' => 'customer 1',
                'phoneNumber' => '+96563335648',
            ],
            [
                'user_id' => 'Asds-52664d66-aasd5566-D1',
                'name' => 'driver 1',
                'phoneNumber' => '+965634535648',
            ]
        ];
        foreach ($profiles as $profile) {
            UserProfile::create($profile);
        }
    }
}
