<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
         Schema::table('request_schedules', function (Blueprint $table) {
            $table->dropColumn([
                'session_purpose',
                'session_type',
                'original_date',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('request_schedules', function (Blueprint $table) {
            $table->dropForeign(['tribunal_id']);
            $table->dropColumn('tribunal_id');
        });
    }
};