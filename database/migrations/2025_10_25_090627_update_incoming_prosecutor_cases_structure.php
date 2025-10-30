<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('incoming_prosecutor_cases', function (Blueprint $table) {
        // حذف الحقول القديمة
        $table->dropColumn(['plaintiff_name', 'defendant_name']);

        // إضافة الحقول الجديدة
        $table->string('participant_type'); // مدعي أو مدعى عليه
        $table->string('name');
        $table->string('national_id');
        $table->string('residence');
        $table->string('job')->nullable();
        $table->string('phone')->nullable();
        $table->unsignedBigInteger('tribunal_id')->nullable();
    });
}

public function down()
{
    Schema::table('incoming_prosecutor_cases', function (Blueprint $table) {
        // استرجاع الحقول القديمة
        $table->string('plaintiff_name');
        $table->string('defendant_name');

        // حذف الحقول الجديدة
        $table->dropColumn([
            'participant_type',
            'name',
            'national_id',
            'residence',
            'job',
            'phone',
            'tribunal_id',
        ]);
    });
}




};
