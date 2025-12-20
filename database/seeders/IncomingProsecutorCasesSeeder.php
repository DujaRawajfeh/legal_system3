<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IncomingProsecutorCasesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('incoming_prosecutor_cases')->insert([

            //  القضية 1 – قتل عمد (جنوب عمان)
            [
                'case_number' => '2146',
                'title' => 'قضية قتل عمد',
                'department_id' => 1,
                'records' => 'السجل العام/جنوب عمان',
                'tribunal_id' => 11,

                // المدعي (الحق العام – جهة رسمية)
                'plaintiff_name' => 'الحق العام',
                'plaintiff_national_id' => '200877965',
                'plaintiff_residence' => 'جنوب عمان',
                'plaintiff_job' => 'نيابة عامة',
                'plaintiff_phone' => '0000000000',
                'plaintiff_type' => 'جهة رسمية',

                // المدعى عليه
                'defendant_name' => 'محمد أحمد حسن',
                'defendant_national_id' => '1002003001',
                'defendant_residence' => 'جنوب عمان',
                'defendant_job' => 'عامل',
                'defendant_phone' => '0791111111',
                'defendant_type' => 'شخص طبيعي',

                // الطرف الثالث
                'third_party_name' => null,
                'third_party_national_id' => null,
                'third_party_residence' => null,
                'third_party_job' => null,
                'third_party_phone' => null,
                'third_party_type' => null,

                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            //  القضية 2 – شروع بالقتل (شرق عمان)
            [
                'case_number' => '9854',
                'title' => 'قضية شروع بالقتل',
                'department_id' => 1,
                'records' => 'السجل العام/شرق عمان',
                'tribunal_id' => 11,

                'plaintiff_name' => 'الحق العام',
                'plaintiff_national_id' => '4000766543',
                'plaintiff_residence' => 'شرق عمان',
                'plaintiff_job' => 'نيابة عامة',
                'plaintiff_phone' => '0000000000',
                'plaintiff_type' => 'جهة رسمية',

                'defendant_name' => 'سامي خالد يوسف',
                'defendant_national_id' => '1002003002',
                'defendant_residence' => 'شرق عمان',
                'defendant_job' => 'سائق',
                'defendant_phone' => '0792222222',
                'defendant_type' => 'شخص طبيعي',

                'third_party_name' => null,
                'third_party_national_id' => null,
                'third_party_residence' => null,
                'third_party_job' => null,
                'third_party_phone' => null,
                'third_party_type' => null,

                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            //  القضية 3 – قتل خطأ (غرب عمان)
            [
                'case_number' => '6789',
                'title' => 'قضية قتل خطأ',
                'department_id' => 1,
                'records' => 'السجل العام/غرب عمان',
                'tribunal_id' => 11,

                'plaintiff_name' => 'الحق العام',
                'plaintiff_national_id' => '4000988765',
                'plaintiff_residence' => 'غرب عمان',
                'plaintiff_job' => 'نيابة عامة',
                'plaintiff_phone' => '0000000000',
                'plaintiff_type' => 'جهة رسمية',

                'defendant_name' => 'أحمد محمود سليمان',
                'defendant_national_id' => '1002003003',
                'defendant_residence' => 'غرب عمان',
                'defendant_job' => 'مقاول',
                'defendant_phone' => '0793333333',
                'defendant_type' => 'شخص طبيعي',

                'third_party_name' => null,
                'third_party_national_id' => null,
                'third_party_residence' => null,
                'third_party_job' => null,
                'third_party_phone' => null,
                'third_party_type' => null,

                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            //  القضية 4 – قتل مع سبق الإصرار (شمال عمان)
            [
                'case_number' => '5632',
                'title' => 'قضية قتل مع سبق الإصرار',
                'department_id' => 1,
                'records' => 'السجل العام/شمال عمان',
                'tribunal_id' => 11,

                'plaintiff_name' => 'الحق العام',
                'plaintiff_national_id' => '0000000000',
                'plaintiff_residence' => 'شمال عمان',
                'plaintiff_job' => 'نيابة عامة',
                'plaintiff_phone' => '0000000000',
                'plaintiff_type' => 'جهة رسمية',

                'defendant_name' => 'خالد فواز العبدالله',
                'defendant_national_id' => '1002003004',
                'defendant_residence' => 'شمال عمان',
                'defendant_job' => 'تاجر',
                'defendant_phone' => '0794444444',
                'defendant_type' => 'شخص طبيعي',

                'third_party_name' => null,
                'third_party_national_id' => null,
                'third_party_residence' => null,
                'third_party_job' => null,
                'third_party_phone' => null,
                'third_party_type' => null,

                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}