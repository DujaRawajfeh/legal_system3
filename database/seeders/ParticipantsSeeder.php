<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Participant;
use App\Models\CourtCase;
use Carbon\Carbon;

class ParticipantsSeeder extends Seeder
{
    public function run(): void
    {
        // جلب كل القضايا الموجودة
        $cases = CourtCase::pluck('id')->toArray();

        // إذا ما في قضايا، نوقف
        if (count($cases) === 0) {
            return;
        }

        $now = Carbon::now();

        $participants = [
            [
                'name' => 'محمد علي',
                'national_id' => '2000599876',
                'phone' => '0799798408',
                'residence' => 'الزرقاء/شارع الامير محمد',
                'job' => 'مهندس/شركة الكهرباء',
                'type' => 'مدعى عليه',
                'charge' => 'القتل',
            ],
            [
                'name' => 'صهيب محمد',
                'national_id' => '2003455631',
                'phone' => '0799798408',
                'residence' => 'عمان/شارع مكه',
                'job' => 'متقاعد',
                'type' => 'مشتكى عليه',
                'charge' => null,
            ],
            [
                'name' => 'سوار خالد',
                'national_id' => '2009677635',
                'phone' => '0774578086',
                'residence' => 'مرج الحمام',
                'job' => 'مهندس/شركة الكهرباء',
                'type' => 'شاهد',
                'charge' => null,
            ],
            [
                'name' => 'ليلى الحباشنة',
                'national_id' => '4455667788',
                'phone' => '0799988776',
                'residence' => 'جبل التاج',
                'job' => 'محامية',
                'type' => 'مدعية',
                'charge' => null,
            ],
            [
                'name' => 'رامي الشوابكة',
                'national_id' => '3344556677',
                'phone' => '0782233445',
                'residence' => 'الدوار السابع',
                'job' => 'شرطي',
                'type' => 'مدعى عليه',
                'charge' => null,
            ],
            [
                'name' => 'نادر الطراونة',
                'national_id' => '2233445566',
                'phone' => '0776655443',
                'residence' => 'العبدلي',
                'job' => 'طبيب شرعي',
                'type' => 'شاهد',
                'charge' => null,
            ],
            [
                'name' => 'مريم محمد',
                'national_id' => '4000677894',
                'phone' => '0777564987',
                'residence' => 'الزرقاء',
                'job' => 'غير موظف',
                'type' => 'شاهد',
                'charge' => null,
            ],
        ];

        foreach ($participants as $participant) {
            Participant::create([
                'court_case_id' => $cases[array_rand($cases)], // 👈 ID موجود فعليًا
                'name' => $participant['name'],
                'national_id' => $participant['national_id'],
                'phone' => $participant['phone'],
                'residence' => $participant['residence'],
                'job' => $participant['job'],
                'type' => $participant['type'],
                'charge' => $participant['charge'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
