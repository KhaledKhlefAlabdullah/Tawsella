<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdvertisementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {
            DB::table('advertisements')->insert([
                'id' => Str::uuid(),
                'admin_id' => getAdminId(), // تأكد من وجود مستخدم بهذا المعرف في جدول users
                'title' => 'إعلان ' . ($i + 1),
                'image' => '/images/services/images/service.jpg',
                'logo' => '/images/services/logos/logo.jpg',
                'description' => 'هذا وصف للإعلان رقم ' . ($i + 1),
                'validity_date' => Carbon::now()->addDays(rand(10, 30)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
