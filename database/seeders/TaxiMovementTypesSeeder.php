<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class TaxiMovementTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $movementTypes = [
            ['type' => 'اعزاز', 'price' => 50],
            ['type' => 'سجو', 'price' => 75],
            ['type' => 'كفر كلبين', 'price' => 80],
            ['type' => 'ندة', 'price' => 80],
            ['type' => 'يحمور', 'price' => 100],
            ['type' => 'كفرة', 'price' => 150],
            ['type' => 'صوران', 'price' => 200],
            ['type' => 'احتيملات', 'price' => 250],
            ['type' => 'دابق ارشاف', 'price' => 300],
            ['type' => 'اخترين', 'price' => 350],
            ['type' => 'الباب', 'price' => 600],
            ['type' => 'الراعي', 'price' => 600],
            ['type' => 'جرابلس', 'price' => 1200],
            ['type' => 'قطمة', 'price' => 100],
            ['type' => 'كفر جنة', 'price' => 150],
            ['type' => 'عفرين', 'price' => 300],
            ['type' => 'شران', 'price' => 250],
            ['type' => 'ميدانكي', 'price' => 350],
            ['type' => 'بلبلة', 'price' => 650],
            ['type' => 'شمارين', 'price' => 200],
            ['type' => 'النبي هوري', 'price' => 500],
            ['type' => 'دير صوان', 'price' => 400],
            ['type' => 'جنديرس', 'price' => 500],
            ['type' => 'اطمة ادلب', 'price' => 600],
            ['type' => 'سرمدا', 'price' => 800],
            ['type' => 'ادلب', 'price' => 1200],
            ['type' => 'سلقين', 'price' => 1500],
            ['type' => 'حارم', 'price' => 1300],
            ['type' => 'جسر الشغور', 'price' => 1600],
            ['type' => 'جبرين', 'price' => 150],
            ['type' => 'مارع', 'price' => 250],
            ['type' => 'قصطل', 'price' => 100],
            ['type' => 'معرين', 'price' => 100],
            ['type' => 'يازي باغ', 'price' => 150],
            ['type' => 'مريمين', 'price' => 250],
            ['type' => 'الغندورة', 'price' => 1000],
            ['type' => 'راجو', 'price' => 500],
            ['type' => 'حزانو', 'price' => 1000],
            ['type' => 'الحمران', 'price' => 1400],
        ];

        foreach ($movementTypes as $movementType) {
            DB::table('taxi_movement_types')->insert([
                'id' => Str::uuid(),
                'type' => $movementType['type'],
                'price' => $movementType['price']
            ]);
        }   
        
        DB::table('taxi_movement_types')->insert([
            'id' => 't-m-t-1',
            'type' => 'طلب داخلي',
            'price' => 50
        ]);

        DB::table('taxi_movement_types')->insert([
            'id' => 't-m-t-2',
            'type' => 'طلب خارجي',
            'is_onKM' => true,
            'price' => 0.5
        ]);
    }
}
