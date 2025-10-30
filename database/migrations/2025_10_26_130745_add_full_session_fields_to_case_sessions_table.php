<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('case_sessions', function (Blueprint $table) {
            $table->time('session_time')->nullable();
            $table->string('session_type')->nullable();
            $table->string('status')->nullable();
            $table->text('final_decision')->nullable();
            $table->text('postponed_reason')->nullable();
            $table->boolean('action_done')->nullable();
            $table->string('judgment_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('case_sessions', function (Blueprint $table) {
            //
        });
    }
};
