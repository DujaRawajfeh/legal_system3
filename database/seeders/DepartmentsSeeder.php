<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('departments')->insert([
            [
                'id' => 1,
                'name' => 'قلم الجنايات',
                'number' => 5,
                'created_at' => '2025-10-22 13:59:07',
                'updated_at' => '2025-10-22 13:59:07',
            ],
            [
                'id' => 2,
                'name' => 'قلم صلاح الجزاء',
                'number' => 9,
                'created_at' => '2025-10-22 13:59:07',
                'updated_at' => '2025-10-22 13:59:07',
            ],
            [
                'id' => 3,
                'name' => 'قلم صلاح الحقوق',
                'number' => 7,
                'created_at' => '2025-10-22 13:59:07',
                'updated_at' => '2025-10-22 13:59:07',
            ],
            [
                'id' => 4,
                'name' => 'قلم بداية الحقوق',
                'number' => 8,
                'created_at' => '2025-10-22 13:59:07',
                'updated_at' => '2025-10-22 13:59:07',
            ],
        ]);
    }
}
