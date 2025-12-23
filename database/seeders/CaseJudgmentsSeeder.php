<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CaseJudgmentsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('case_judgments')->insert([
            [
                'court_case_id' => 1,
                'participant_id' => 1,
                'judgment_date' => null,
                'closure_date' => null,
                'judgment_type' => null,
                'charge_decision' => null,
                'judgment_mode' => null,
                'termination_type' => null,
                'execution_details' => null,
                'judgment_summary' => null,
                'personal_drop_text' => null,
                'charge_text' => 'دخول السجن ثلاث سنوات',
                'charge_split_type' => 'إحالة',
                'created_by' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'court_case_id' => 56,
                'participant_id' => 75,
                'judgment_date' => '2025-11-23',
                'closure_date' => '2025-11-23',
                'judgment_type' => 'وجاهي',
                'charge_decision' => null,
                'judgment_mode' => null,
                'termination_type' => 'إسقاط بالعفو',
                'execution_details' => null,
                'judgment_summary' => 'سجن اربع شهور',
                'personal_drop_text' => null,
                'charge_text' => 'سجن اربع شهور',
                'charge_split_type' => 'إحالة',
                'created_by' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'court_case_id' => 56,
                'participant_id' => 76,
                'judgment_date' => '2025-11-23',
                'closure_date' => '2025-11-23',
                'judgment_type' => 'وجاهي',
                'charge_decision' => null,
                'judgment_mode' => null,
                'termination_type' => 'إسقاط بالعفو',
                'execution_details' => null,
                'judgment_summary' => 'سجن اربع شهور',
                'personal_drop_text' => null,
                'charge_text' => 'سجن خمس شهور',
                'charge_split_type' => 'إدانة',
                'created_by' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'court_case_id' => 95,
                'participant_id' => 105,
                'judgment_date' => '2025-12-21',
                'closure_date' => '2025-12-21',
                'judgment_type' => 'تدقيقيا',
                'charge_decision' => null,
                'judgment_mode' => null,
                'termination_type' => 'عدم اختصاص',
                'execution_details' => null,
                'judgment_summary' => 'دخول السجن',
                'personal_drop_text' => null,
                'charge_text' => 'دخول السجن خمس شهور',
                'charge_split_type' => 'إدانة',
                'created_by' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}