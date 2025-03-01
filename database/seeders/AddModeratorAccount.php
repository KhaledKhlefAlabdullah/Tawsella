<?php

namespace Database\Seeders;

use App\Enums\UserEnums\UserGender;
use App\Enums\UserEnums\UserType;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AddModeratorAccount extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password= Hash::make('12345678');
        $user = User::create([
            'email'=>'moderator@email.com',
            'password'=>$password,
            'mail_code_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user->profile()->create([
            'name' => 'Moderator',
            'gender' => UserGender::male,
            'phone_number' => '+352000000',
        ]);

        Role::create(['name' => UserType::Moderator()->key, 'guard_name' => 'web']);

        $user->assignRole(UserType::Moderator()->key);
    }
}
