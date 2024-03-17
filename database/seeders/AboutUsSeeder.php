<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AboutUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('about_us')->insert([
            'id' => Str::uuid(),
            'admin_id' => getAdminId(),
            'title' => 'حولنا',
            'description' => 'نحن شركة ستار تكسي في خدمتكم 24 ساعة عنا سيارات كتير كويسة جربو خدمتنا',
            'complaints_number' => '+364614874645',
        ]);
    }
}
