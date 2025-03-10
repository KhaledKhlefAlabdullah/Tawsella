<?php

namespace Database\Seeders;

use App\Models\TaxiMovementType;
use Illuminate\Database\Seeder;


class TaxiMovementTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        $movementTypes = [
//            ['type' => 'اعزاز', 'price1' => 50],
//            ['type' => 'سجو', 'price1' => 75],
//            ['type' => 'كفر كلبين', 'price1' => 80],
//            ['type' => 'ندة', 'price1' => 80],
//            ['type' => 'يحمور', 'price1' => 100],
//            ['type' => 'كفرة', 'price1' => 150],
//            ['type' => 'صوران', 'price1' => 200],
//            ['type' => 'احتيملات', 'price1' => 250],
//            ['type' => 'دابق ارشاف', 'price1' => 300],
//            ['type' => 'اخترين', 'price1' => 350],
//            ['type' => 'الباب', 'price1' => 600],
//            ['type' => 'الراعي', 'price1' => 600],
//            ['type' => 'جرابلس', 'price1' => 1200],
//            ['type' => 'قطمة', 'price1' => 100],
//            ['type' => 'كفر جنة', 'price1' => 150],
//            ['type' => 'عفرين', 'price1' => 300],
//            ['type' => 'شران', 'price1' => 250],
//            ['type' => 'ميدانكي', 'price1' => 350],
//            ['type' => 'بلبلة', 'price1' => 650],
//            ['type' => 'شمارين', 'price1' => 200],
//            ['type' => 'النبي هوري', 'price1' => 500],
//            ['type' => 'دير صوان', 'price1' => 400],
//            ['type' => 'جنديرس', 'price1' => 500],
//            ['type' => 'اطمة ادلب', 'price1' => 600],
//            ['type' => 'سرمدا', 'price1' => 800],
//            ['type' => 'ادلب', 'price1' => 1200],
//            ['type' => 'سلقين', 'price1' => 1500],
//            ['type' => 'حارم', 'price1' => 1300],
//            ['type' => 'جسر الشغور', 'price1' => 1600],
//            ['type' => 'جبرين', 'price1' => 150],
//            ['type' => 'مارع', 'price1' => 250],
//            ['type' => 'قصطل', 'price1' => 100],
//            ['type' => 'معرين', 'price1' => 100],
//            ['type' => 'يازي باغ', 'price1' => 150],
//            ['type' => 'مريمين', 'price1' => 250],
//            ['type' => 'الغندورة', 'price1' => 1000],
//            ['type' => 'راجو', 'price1' => 500],
//            ['type' => 'حزانو', 'price1' => 1000],
//            ['type' => 'الحمران', 'price1' => 1400],
//        ];
//
//        foreach ($movementTypes as $movementType) {
//            TaxiMovementType::create($movementType);
//        }

        $GeneralMovementsTypes = [
            ['id' => 't-m-t-1', 'type' => 'طلب داخلي', 'price1' => 50, 'payment1' => 0, 'is_general' => true],
//            ['id' => 't-m-t-2', 'type' => 'طلب خارجي', 'is_onKM' => true, 'price1' => 0.5, 'payment1' => 1, 'is_general' => true],
//            ['id' => 't-m-t-3', 'type' => 'استأجار سيارة لمدة زمنية', 'description' => 'تواصل معنا على الرقم', 'price1' => 1000, 'payment1' => 1, 'is_general' => true],
        ];

        foreach ($GeneralMovementsTypes as $GeneralMovementsType) {
            $movementType = TaxiMovementType::create($GeneralMovementsType);
//            $movementType->offers()->createMany([
//                [
//                    'admin_id' => getAdminId(),
//                    'offer' => 'العيد',
//                    'value_of_discount' => 0.5,
//                    'valid_date' => '2025-8-8',
//                    'description' => 'test offer 1'
//                ],
//                [
//                    'admin_id' => getAdminId(),
//                    'offer' => 'اختبار',
//                    'value_of_discount' => 0.1,
//                    'valid_date' => '2025-11-8',
//                    'description' => 'test offer 2'
//                ]
//            ]);
        }


    }
}
