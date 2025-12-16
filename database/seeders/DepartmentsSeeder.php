<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('department')->insert([
            [
                'id' => 1,
                'name' => 'قلم الجنايات',
                'number' => 5,
                'created_at' => '2025-10-22 13:59:07',
                'updated_at' => '2025-10-22 13:59:07',
            ],
            [
                'id' => 2,
                'name' => 'قلم صلح الجزاء',
                'number' => 9,
                'created_at' => '2025-10-22 13:59:07',
                'updated_at' => '2025-10-22 13:59:07',
            ],
           
           
        ]);
    }
}
