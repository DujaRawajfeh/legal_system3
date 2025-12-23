<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CaseSessionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('case_sessions')->insert([
            [
                'court_case_id' => 5,
                'judge_id' => 2,
                'session_date' => '2025-10-25 15:42:13',
                'created_by' => 1,
                'session_time' => '13:00:00',
                'session_type' => 'جلسة بدون قرار',
                'status' => 'مكتملة',
                'final_decision' => null,
                'postponed_reason' => null,
                'action_done' => 0,
                'session_goal' => null,
                'judgment_type' => 'تمهيدي',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'court_case_id' => 6,
                'judge_id' => 3,
                'session_date' => '2025-10-31 15:42:13',
                'created_by' => 1,
                'session_time' => '08:45:00',
                'session_type' => 'جلسة قادمة',
                'status' => 'محددة',
                'final_decision' => null,
                'postponed_reason' => null,
                'action_done' => 0,
                'session_goal' => null,
                'judgment_type' => 'تمهيدي',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'court_case_id' => 7,
                'judge_id' => 1,
                'session_date' => '2025-11-02 15:42:13',
                'created_by' => 1,
                'session_time' => '15:00:00',
                'session_type' => 'جلسة استماع',
                'status' => 'مكتملة',
                'final_decision' => null,
                'postponed_reason' => null,
                'action_done' => 0,
                'session_goal' => null,
                'judgment_type' => 'تمهيدي',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🔹 مثال على جلسة فيها قيم null (نفس قاعدة البيانات)
            [
                'court_case_id' => 35,
                'judge_id' => 2,
                'session_date' => '2025-10-31 13:16:25',
                'created_by' => null,
                'session_time' => null,
                'session_type' => null,
                'status' => 'محددة',
                'final_decision' => null,
                'postponed_reason' => null,
                'action_done' => null,
                'session_goal' => null,
                'judgment_type' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // 🔹 جلسة مستمرة
            [
                'court_case_id' => 95,
                'judge_id' => 2,
                'session_date' => '2025-12-30 11:20:00',
                'created_by' => 6,
                'session_time' => '11:20:00',
                'session_type' => null,
                'status' => 'مستمرة',
                'final_decision' => null,
                'postponed_reason' => null,
                'action_done' => null,
                'session_goal' => 'غياب أحد الأطراف',
                'judgment_type' => 'وجاهي',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}