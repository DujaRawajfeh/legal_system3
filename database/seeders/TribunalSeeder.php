<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TribunalSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tribunal')->insert([
            [
                'id' => 11,
                'name' => 'محكمة بداية عمان',
                'number' => '2/1',
                'created_at' => '2025-10-22 13:53:58',
                'updated_at' => '2025-10-22 13:53:58',
            ],
            [
                'id' => 12,
                'name' => 'محكمة بداية الزرقاء',
                'number' => '2/2',
                'created_at' => '2025-10-22 13:53:58',
                'updated_at' => '2025-10-22 13:53:58',
            ],
            [
                'id' => 13,
                'name' => 'محكمة بداية إربد',
                'number' => '2/3',
                'created_at' => '2025-10-22 13:53:58',
                'updated_at' => '2025-10-22 13:53:58',
            ],
            [
                'id' => 14,
                'name' => 'محكمة بداية الكرك',
                'number' => '2/4',
                'created_at' => '2025-10-22 13:53:58',
                'updated_at' => '2025-10-22 13:53:58',
            ],
            [
                'id' => 15,
                'name' => 'محكمة بداية المفرق',
                'number' => '2/5',
                'created_at' => '2025-10-22 13:53:58',
                'updated_at' => '2025-10-22 13:53:58',
            ],
            [
                'id' => 16,
                'name' => 'محكمة صلح عمان',
                'number' => '3/1',
                'created_at' => '2025-10-22 13:53:58',
                'updated_at' => '2025-10-22 13:53:58',
            ],
            [
                'id' => 17,
                'name' => 'محكمة صلح الزرقاء',
                'number' => '3/2',
                'created_at' => '2025-10-22 13:53:58',
                'updated_at' => '2025-10-22 13:53:58',
            ],
        ]);
    }
}