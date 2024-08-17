<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Enums\UserType;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password= Hash::make('12345678');
        $user = User::create([
            'email'=>'admin@email.com',
            'password'=>$password,
            'user_type' => UserType::ADMIN(),
            'mail_code_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user->profile()->create([
            'name' => 'admin',
            'phone_number' => '+000000000',
        ]);

        $user->assignRole(UserType::ADMIN()->key);
    }
}
