<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParticipantsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('participants')->insert([

            // court_case_id = 1
            [
                'court_case_id' => 1,
                'type' => 'مدعى عليه',
                'charge' => 'القتل',
                'name' => 'محمد علي',
                'national_id' => '2000599876',
                'residence' => 'الزرقاء/شارع الامير محمد',
                'job' => 'مهندس/شركة الكهرباء',
                'phone' => '0799798408',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // court_case_id = 4
            [
                'court_case_id' => 4,
                'type' => 'مشتكى عليه',
                'charge' => null,
                'name' => 'صهيب محمد',
                'national_id' => '2003455631',
                'residence' => 'عمان/شارع مكه',
                'job' => 'متقاعد',
                'phone' => '0799798408',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // court_case_id = 5
            [
                'court_case_id' => 5,
                'type' => 'شاهد',
                'charge' => null,
                'name' => 'سوار خالد',
                'national_id' => '2009677635',
                'residence' => 'مرج الحمام',
                'job' => 'مهندس/شركة الكهرباء',
                'phone' => '0774578086',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // court_case_id = 35 (3 أطراف)
            [
                'court_case_id' => 35,
                'type' => 'مدعية',
                'charge' => null,
                'name' => 'ليلى الحباشنة',
                'national_id' => '4455667788',
                'residence' => 'جبل التاج',
                'job' => 'محامية',
                'phone' => '0799988776',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'court_case_id' => 35,
                'type' => 'مدعى عليه',
                'charge' => null,
                'name' => 'رامي الشوابكة',
                'national_id' => '3344556677',
                'residence' => 'الدوار السابع',
                'job' => 'شرطي',
                'phone' => '0782233445',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'court_case_id' => 35,
                'type' => 'شاهد',
                'charge' => null,
                'name' => 'نادر الطراونة',
                'national_id' => '2233445566',
                'residence' => 'العبدلي',
                'job' => 'طبيب شرعي',
                'phone' => '0776655443',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // court_case_id = 95 (فيه شاهد created_at = null)
            [
                'court_case_id' => 95,
                'type' => 'شاهد',
                'charge' => null,
                'name' => 'مريم محمد',
                'national_id' => '4000677894',
                'residence' => 'الزرقاء',
                'job' => 'غير موظف',
                'phone' => '07775649876',
                'created_at' => null,
                'updated_at' => null,
            ],
        ]);
    }
}