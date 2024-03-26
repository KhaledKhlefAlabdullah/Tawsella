<?php

namespace Database\Seeders;

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
        DB::table('user_profiles')->insert([
            'id' => Str::uuid(),
            'user_id' => 'Asds-52664d66-aasd5566-C1',
            'name' => 'customer 1',
            'avatar' => 'avatar1',
            'phoneNumber' => '+96563335648',
        ]);

        DB::table('user_profiles')->insert([
            'id' => Str::uuid(),
            'user_id' => 'Asds-52664d66-aasd5566-C2',
            'name' => 'customer 2',
            'avatar' => 'avatar2',
            'phoneNumber' => '+96563335648',
        ]);
    }
}
