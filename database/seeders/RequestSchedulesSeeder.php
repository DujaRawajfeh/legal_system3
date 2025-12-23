<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RequestSchedulesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('request_schedules')->insert([
            [
                'request_number' => '6789',
                'court_year' => '2025',
                'session_date' => '2025-12-01',
                'session_time' => '09:00:00',
                'session_type' => 'جلسة تحقيق',
                'session_purpose' => 'استجواب الشهود',
                'session_status' => 'مؤجلة',
                'session_reason' => 'غياب أحد الأطراف',
                'original_date' => '2025-11-15',

                'tribunal_id' => 11,
                'judge_id' => 2,
                'department_id' => 1,

                'title' => 'طلب تنفيذ',

                'judgment_text_plaintiff' =>
                    'يطلب المدعي تنفيذ الحكم الصادر بحقه حسب الأصول القانونية.',
                'judgment_text_defendant' => null,
                'judgment_text_third_party' => null,
                'judgment_text_lawyer' => null,
                'judgment_text_final' => null,
                'judgment_text_waiver' => null,

                'judgment_date' => null,
                'closure_date' => null,

                'plaintiff_name' => 'أحمد محمد علي',
                'plaintiff_national_id' => '9876543210',
                'plaintiff_residence' => 'عمّان – جبل الحسين',
                'plaintiff_job' => 'موظف',
                'plaintiff_phone' => '0791234567',

                'defendant_name' => null,
                'defendant_national_id' => null,
                'defendant_residence' => null,
                'defendant_job' => null,
                'defendant_phone' => null,

                'third_party_name' => null,
                'third_party_national_id' => null,
                'third_party_residence' => null,
                'third_party_job' => null,
                'third_party_phone' => null,

                'lawyer_name' => null,
                'lawyer_national_id' => null,
                'lawyer_residence' => null,
                'lawyer_job' => null,
                'lawyer_phone' => null,

                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}