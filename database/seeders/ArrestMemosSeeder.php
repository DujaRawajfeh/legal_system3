<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArrestMemosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('arrest_memos')->insert([
            [
                'case_id' => 3,
                'participant_name' => 'محمود علي',
                'released' => '1',
                'judge_name' => 'سارة احمد المجالي',
                'detention_duration' => 9,
                'detention_reason' => 'منع المشتكى عليه من الفرار',
                'detention_center' => 'مركز إصلاح و تأهيل ماركا',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'case_id' => 1,
                'participant_name' => 'محمد علي',
                'released' => 'تم الإفراج',
                'judge_name' => 'علي محمد المجالي',
                'detention_duration' => 4,
                'detention_reason' => 'منع المشتكى عليه من الفرار',
                'detention_center' => 'مركز إصلاح و تأهيل إربد',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'case_id' => 4,
                'participant_name' => 'صهيب محمد',
                'released' => null,
                'judge_name' => 'علي محمد المجالي',
                'detention_duration' => 14,
                'detention_reason' => 'خشية الفرار',
                'detention_center' => 'مركز إصلاح وتأهيل ماركا',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'case_id' => 95,
                'participant_name' => 'علاءالدين',
                'released' => 'تم الإفراج',
                'judge_name' => 'علي محمد المجالي',
                'detention_duration' => 12,
                'detention_reason' => 'خطر العبث بالأدلة',
                'detention_center' => 'مركز الإصلاح المركزي',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}