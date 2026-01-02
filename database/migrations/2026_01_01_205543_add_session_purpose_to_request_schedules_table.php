<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('request_schedules', function (Blueprint $table) {
            $table->string('session_purpose')->nullable()->after('session_status');
        });
    }

    public function down(): void
    {
        Schema::table('request_schedules', function (Blueprint $table) {
            $table->dropColumn('session_purpose');
        });
    }
};