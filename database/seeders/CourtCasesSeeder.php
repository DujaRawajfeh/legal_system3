<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourtCasesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('court_cases')->insert([
            [
                'id' => 1,
                'judge_id' => 2,
                'type' => 'القتل العمد',
                'number' => '0382',
                'year' => '2025',
                'tribunal_id' => 11,
                'department_id' => 1,
                'created_by' => 1,
                'created_at' => '2025-10-22 16:36:25',
                'updated_at' => '2025-10-22 16:36:25',
            ],
            [
                'id' => 2,
                'judge_id' => 3,
                'type' => 'القتل العمد مع سبق الإصرار',
                'number' => '0380',
                'year' => '2025',
                'tribunal_id' => 11,
                'department_id' => 1,
                'created_by' => 1,
                'created_at' => '2025-10-22 16:38:03',
                'updated_at' => '2025-10-22 16:38:03',
            ],
            [
                'id' => 4,
                'judge_id' => 2,
                'type' => 'الخطف',
                'number' => '4822',
                'year' => '2025',
                'tribunal_id' => 11,
                'department_id' => 1,
                'created_by' => 1,
                'created_at' => '2025-10-22 16:50:47',
                'updated_at' => '2025-10-22 16:50:47',
            ],
            [
                'id' => 5,
                'judge_id' => 3,
                'type' => 'الاعتداء الجسدي',
                'number' => '8836',
                'year' => '2025',
                'tribunal_id' => 11,
                'department_id' => 1,
                'created_by' => 1,
                'created_at' => '2025-10-22 16:53:32',
                'updated_at' => '2025-10-22 16:53:32',
            ],
            [
                'id' => 6,
                'judge_id' => 2,
                'type' => 'القتل العمد مع سبق الإصرار',
                'number' => '4296',
                'year' => '2025',
                'tribunal_id' => 11,
                'department_id' => 1,
                'created_by' => 1,
                'created_at' => '2025-10-22 18:15:16',
                'updated_at' => '2025-11-21 17:12:50',
            ],
            [
                'id' => 7,
                'judge_id' => 2,
                'type' => 'القتل العمد مع سبق الإصرار',
                'number' => '8839',
                'year' => '2025',
                'tribunal_id' => 11,
                'department_id' => 1,
                'created_by' => 1,
                'created_at' => '2025-10-22 18:15:17',
                'updated_at' => '2025-10-22 18:15:17',
            ],
            [
                'id' => 8,
                'judge_id' => 3,
                'type' => 'القتل العمد مع سبق الإصرار',
                'number' => '7373',
                'year' => '2025',
                'tribunal_id' => 11,
                'department_id' => 1,
                'created_by' => 1,
                'created_at' => '2025-10-23 14:32:40',
                'updated_at' => '2025-10-23 14:32:40',
            ],
            [
                'id' => 15,
                'judge_id' => 3,
                'type' => 'جنائية',
                'number' => '8840',
                'year' => '2025',
                'tribunal_id' => null,
                'department_id' => 1,
                'created_by' => 1,
                'created_at' => '2025-10-25 08:27:33',
                'updated_at' => '2025-10-25 08:27:33',
            ],
            [
                'id' => 20,
                'judge_id' => 3,
                'type' => 'جنائية',
                'number' => '8845',
                'year' => '2025',
                'tribunal_id' => null,
                'department_id' => 1,
                'created_by' => 1,
                'created_at' => '2025-10-25 09:24:23',
                'updated_at' => '2025-10-25 09:24:23',
            ],
            [
                'id' => 24,
                'judge_id' => 4,
                'type' => 'القتل العمد مع سبق الإصرار',
                'number' => '5723',
                'year' => '2025',
                'tribunal_id' => 11,
                'department_id' => 1,
                'created_by' => 1,
                'created_at' => '2025-10-25 18:48:13',
                'updated_at' => '2025-10-25 18:48:13',
            ],
            // 👇 بقية السجلات (35 → 94) بنفس النمط
        ]);
    }
}