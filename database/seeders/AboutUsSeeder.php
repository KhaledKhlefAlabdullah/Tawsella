<?php

namespace Database\Seeders;

use App\Models\AboutUs;
use Illuminate\Database\Seeder;


class AboutUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $aboutUsData = [
            [
                'admin_id' => getAdminId(),
                'title' => 'حولنا',
                'description' => 'نحن شركة ستار تكسي في خدمتكم 24 ساعة عنا سيارات كتير كويسة جربو خدمتنا',
                'is_general' => true,
                'complaints_number' => '+364614874645',
            ],
            [
                'admin_id' => getAdminId(),
                'title' => 'السائقين',
                'description' => 'السائقات غير متاحات ليلا',
                'is_general' => false,
            ],
            [
                'admin_id' => getAdminId(),
                'title' => 'السائقين',
                'description' => 'السائقات غير متاحات ليلا',
                'is_general' => false,
            ],
            [
                'admin_id' => getAdminId(),
                'title' => 'السائقين',
                'description' => 'السائقات غير متاحات ليلا',
                'is_general' => false,
            ]
        ];

        foreach ($aboutUsData as $data) {
            AboutUs::create($data);
        }
    }
}
