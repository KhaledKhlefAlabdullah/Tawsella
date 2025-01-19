<?php

namespace Database\Seeders;

use App\Enums\UserEnums\UserType;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // List of roles to create based on UserType enum
        $roles = UserType::getKeys();

        foreach ($roles as $roleName) {
            Role::create(['name' => $roleName, 'is_default' => 1, 'guard_name' => 'web']);
        }
    }
}
