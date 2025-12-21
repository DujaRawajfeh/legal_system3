<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IncomingPoliceCasesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('incoming_police_cases')->insert([

            // 🟦 القضية 1 — شرطة شمال عمان
            [
                'center_name' => 'شرطة شمال عمان',
                'police_case_number' => 'P-2025-001',
                'police_registration_date' => '2025-01-10',
                'crime_date' => '2025-01-08',
                'status' => 'قيد التحقيق',
                'case_type' => 'قتل عمد',

                'plaintiff_name' => 'الحق العام',
                'plaintiff_national_id' => '0000000000',
                'plaintiff_residence' => 'شمال عمان',
                'plaintiff_job' => 'جهة رسمية',
                'plaintiff_phone' => '0000000000',
                'plaintiff_type' => 'جهة رسمية',

                'defendant_name' => 'محمود علي سالم',
                'defendant_national_id' => '2003004001',
                'defendant_residence' => 'شمال عمان',
                'defendant_job' => 'عامل',
                'defendant_phone' => '0795551111',
                'defendant_type' => 'شخص طبيعي',

                'third_party_name' => null,
                'third_party_national_id' => null,
                'third_party_residence' => null,
                'third_party_job' => null,
                'third_party_phone' => null,
                'third_party_type' => null,

                'tribunal_id' => 11,
                'department_id' => 1,

                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 🟦 القضية 2 — شرطة جنوب عمان
            [
                'center_name' => 'شرطة جنوب عمان',
                'police_case_number' => 'P-2025-002',
                'police_registration_date' => '2025-02-05',
                'crime_date' => '2025-02-04',
                'status' => 'محولة للنيابة',
                'case_type' => 'شروع بالقتل',

                'plaintiff_name' => 'الحق العام',
                'plaintiff_national_id' => '0000000000',
                'plaintiff_residence' => 'جنوب عمان',
                'plaintiff_job' => 'جهة رسمية',
                'plaintiff_phone' => '0000000000',
                'plaintiff_type' => 'جهة رسمية',

                'defendant_name' => 'سامي فهد حسين',
                'defendant_national_id' => '2003004002',
                'defendant_residence' => 'جنوب عمان',
                'defendant_job' => 'سائق',
                'defendant_phone' => '0795552222',
                'defendant_type' => 'شخص طبيعي',

                'third_party_name' => null,
                'third_party_national_id' => null,
                'third_party_residence' => null,
                'third_party_job' => null,
                'third_party_phone' => null,
                'third_party_type' => null,

                'tribunal_id' => 11,
                'department_id' => 1,

                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 🟦 القضية 3 — شرطة شمال عمان (مرة ثانية)
            [
                'center_name' => 'شرطة شمال عمان',
                'police_case_number' => 'P-2025-003',
                'police_registration_date' => '2025-03-12',
                'crime_date' => '2025-03-11',
                'status' => 'قيد التحقيق',
                'case_type' => 'قتل خطأ',

                'plaintiff_name' => 'الحق العام',
                'plaintiff_national_id' => '0000000000',
                'plaintiff_residence' => 'شمال عمان',
                'plaintiff_job' => 'جهة رسمية',
                'plaintiff_phone' => '0000000000',
                'plaintiff_type' => 'جهة رسمية',

                'defendant_name' => 'أحمد محمود خليل',
                'defendant_national_id' => '2003004003',
                'defendant_residence' => 'شمال عمان',
                'defendant_job' => 'مقاول',
                'defendant_phone' => '0795553333',
                'defendant_type' => 'شخص طبيعي',

                'third_party_name' => null,
                'third_party_national_id' => null,
                'third_party_residence' => null,
                'third_party_job' => null,
                'third_party_phone' => null,
                'third_party_type' => null,

                'tribunal_id' => 11,
                'department_id' => 1,

                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 🟦 القضية 4 — شرطة شمال عمان (مرة ثالثة)
            [
                'center_name' => 'شرطة شمال عمان',
                'police_case_number' => 'P-2025-004',
                'police_registration_date' => '2025-04-01',
                'crime_date' => '2025-03-30',
                'status' => 'محولة للنيابة',
                'case_type' => 'قتل مع سبق الإصرار',

                'plaintiff_name' => 'الحق العام',
                'plaintiff_national_id' => '0000000000',
                'plaintiff_residence' => 'شمال عمان',
                'plaintiff_job' => 'جهة رسمية',
                'plaintiff_phone' => '0000000000',
                'plaintiff_type' => 'جهة رسمية',

                'defendant_name' => 'خالد يوسف عبدالله',
                'defendant_national_id' => '2003004004',
                'defendant_residence' => 'شمال عمان',
                'defendant_job' => 'تاجر',
                'defendant_phone' => '0795554444',
                'defendant_type' => 'شخص طبيعي',

                'third_party_name' => null,
                'third_party_national_id' => null,
                'third_party_residence' => null,
                'third_party_job' => null,
                'third_party_phone' => null,
                'third_party_type' => null,

                'tribunal_id' => 11,
                'department_id' => 1,

                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // 🟦 القضية 5 — شرطة غرب عمان
            [
                'center_name' => 'شرطة غرب عمان',
                'police_case_number' => 'P-2025-005',
                'police_registration_date' => '2025-05-20',
                'crime_date' => '2025-05-19',
                'status' => 'قيد التحقيق',
                'case_type' => 'إيذاء بليغ',

                'plaintiff_name' => 'الحق العام',
                'plaintiff_national_id' => '0000000000',
                'plaintiff_residence' => 'غرب عمان',
                'plaintiff_job' => 'جهة رسمية',
                'plaintiff_phone' => '0000000000',
                'plaintiff_type' => 'جهة رسمية',

                'defendant_name' => 'فراس ناصر حمد',
                'defendant_national_id' => '2003004005',
                'defendant_residence' => 'غرب عمان',
                'defendant_job' => 'موظف',
                'defendant_phone' => '0795555555',
                'defendant_type' => 'شخص طبيعي',

                'third_party_name' => null,
                'third_party_national_id' => null,
                'third_party_residence' => null,
                'third_party_job' => null,
                'third_party_phone' => null,
                'third_party_type' => null,

                'tribunal_id' => 11,
                'department_id' => 1,

                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}