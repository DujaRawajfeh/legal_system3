<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
   public function run(): void
{
    $this->call([

        //  الأساسيات
        TribunalsSeeder::class,
        DepartmentsSeeder::class,

        //  المستخدمين
        UsersSeeder::class,

        //  الربط بين المستخدمين (قضاة / مستخدمين)
        JudgeUserSeeder::class,

        // القضايا
        CourtCasesSeeder::class,

        //  الأطراف
        ParticipantsSeeder::class,

        //  الجلسات
        CaseSessionsSeeder::class,

        //  تقارير الجلسات
        CourtSessionReportsSeeder::class,

        //  الأحكام
        CaseJudgmentsSeeder::class,

        //  المذكرات والتبليغات
        ArrestMemosSeeder::class,
        NotificationsSeeder::class,

        //  الطلبات
        RequestSchedulesSeeder::class,

        //  السجل المدني (مستقل)
        CivilRegistrySeeder::class,
    ]);
}
    }
