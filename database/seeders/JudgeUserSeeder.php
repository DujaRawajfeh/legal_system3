<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JudgeUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('judge_user')->insert([

            [
                'user_id' => 1,
                'judge_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'user_id' => 6,
                'judge_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
} 