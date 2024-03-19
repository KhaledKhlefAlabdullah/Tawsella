<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OffersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('offers')->insert([
            'id' => 'offer-1-Ied',
            'movement_type_id' => 't-m-t-1',
            'admin_id' => getAdminId(),
            'offer' => 'عرض العيد',
            'value_of_discount' => 10,
            'valide_date' => date('2024-8-5')
        ]);

        DB::table('offers')->insert([
            'id' => 'offer-2-Ied',
            'movement_type_id' => 't-m-t-1',
            'admin_id' => getAdminId(),
            'offer' => ' 555عرض العيد',
            'value_of_discount' => 10,
            'valide_date' => date('2024-8-5')
        ]);

        DB::table('offers')->insert([
            'id' => 'offer-3-Ied',
            'movement_type_id' => 't-m-t-2',
            'admin_id' => getAdminId(),
            'offer' => '6666عرض العيد',
            'value_of_discount' => 10,
            'valide_date' => date('2024-8-5')
        ]);

        DB::table('offers')->insert([
            'id' => 'offer-4-Ied',
            'movement_type_id' => 't-m-t-2',
            'admin_id' => getAdminId(),
            'offer' => '6776عرض العيد',
            'value_of_discount' => 10,
            'valide_date' => date('2024-8-5')
        ]);
    }
}
