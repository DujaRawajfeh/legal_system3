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
                'judge_id' => 4,
                'type' => 'القتل العمد',
                'number' => '0382',
                'year' => '2025',
                'tribunal_id' => 11,
                'department_id' => 1,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'judge_id' => 3,
                'type' => 'القتل العمد مع سبق الإصرار',
                'number' => '0380',
                'year' => '2025',
                'tribunal_id' => 11,
                'department_id' => 1,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'judge_id' => 2,
                'type' => 'الخطف',
                'number' => '4822',
                'year' => '2025',
                'tribunal_id' => 11,
                'department_id' => 1,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'judge_id' => 2,
                'type' => 'القتل العمد مع سبق الإصرار',
                'number' => '8839',
                'year' => '2025',
                'tribunal_id' => 11,
                'department_id' => 1,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'judge_id' => 2,
                'type' => 'إساءة معاملة أسرى',
                'number' => '7607',
                'year' => '2025',
                'tribunal_id' => 11,
                'department_id' => 1,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'judge_id' => 2,
                'type' => 'المخدرات - اتجار',
                'number' => '8237',
                'year' => '2025',
                'tribunal_id' => 11,
                'department_id' => 1,
                'created_by' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}