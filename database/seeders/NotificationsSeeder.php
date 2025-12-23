<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('notifications')->insert([

            [
                'case_id' => 1,
                'participant_name' => 'محمد علي',
                'method' => 'قسم التباليغ',
                'notified_at' => '2025-10-23 13:37:39',
                'created_at' => '2025-10-23 13:37:39',
                'updated_at' => '2025-10-23 13:37:39',
            ],

            [
                'case_id' => 5,
                'participant_name' => 'سوار خالد',
                'method' => 'email',
                'notified_at' => '2025-11-19 17:29:57',
                'created_at' => '2025-11-19 17:29:57',
                'updated_at' => '2025-11-19 17:29:57',
            ],

            [
                'case_id' => 56,
                'participant_name' => 'سعيد محمود',
                'method' => 'sms',
                'notified_at' => '2025-12-19 18:26:22',
                'created_at' => '2025-12-19 18:26:22',
                'updated_at' => '2025-12-19 18:26:22',
            ],

            [
                'case_id' => 4,
                'participant_name' => 'صهيب محمد',
                'method' => 'sms',
                'notified_at' => '2025-12-19 18:28:11',
                'created_at' => '2025-12-19 18:28:11',
                'updated_at' => '2025-12-19 18:28:11',
            ],

            [
                'case_id' => 48,
                'participant_name' => 'محمد علي',
                'method' => 'قسم التباليغ',
                'notified_at' => '2025-12-19 18:29:47',
                'created_at' => '2025-12-19 18:29:47',
                'updated_at' => '2025-12-19 18:29:47',
            ],

            [
                'case_id' => 56,
                'participant_name' => 'ليلى حسن',
                'method' => 'sms',
                'notified_at' => '2025-12-20 18:13:17',
                'created_at' => '2025-12-20 18:13:17',
                'updated_at' => '2025-12-20 18:13:17',
            ],

            [
                'case_id' => 56,
                'participant_name' => 'سعيد محمود',
                'method' => 'sms',
                'notified_at' => '2025-12-20 18:17:10',
                'created_at' => '2025-12-20 18:17:10',
                'updated_at' => '2025-12-20 18:17:10',
            ],

            [
                'case_id' => 95,
                'participant_name' => 'رياض محمد',
                'method' => 'email',
                'notified_at' => '2025-12-21 13:17:10',
                'created_at' => '2025-12-21 13:17:10',
                'updated_at' => '2025-12-21 13:17:10',
            ],

            [
                'case_id' => 95,
                'participant_name' => 'علاءالدين',
                'method' => 'قسم التباليغ',
                'notified_at' => '2025-12-21 13:23:56',
                'created_at' => '2025-12-21 13:23:56',
                'updated_at' => '2025-12-21 13:23:56',
            ],

            [
                'case_id' => 95,
                'participant_name' => 'مريم محمد',
                'method' => 'sms',
                'notified_at' => '2025-12-21 13:37:25',
                'created_at' => '2025-12-21 13:37:25',
                'updated_at' => '2025-12-21 13:37:25',
            ],

        ]);
    }
}