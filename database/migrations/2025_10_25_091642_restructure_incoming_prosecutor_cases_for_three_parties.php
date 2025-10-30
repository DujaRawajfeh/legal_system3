<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('incoming_prosecutor_cases', function (Blueprint $table) {
        // حذف الحقول الموحدة
        $table->dropColumn([
            'participant_type',
            'name',
            'national_id',
            'residence',
            'job',
            'phone'
        ]);

        // إضافة حقول الطرف الأول (مدعي)
        $table->string('plaintiff_name');
        $table->string('plaintiff_national_id');
        $table->string('plaintiff_residence');
        $table->string('plaintiff_job')->nullable();
        $table->string('plaintiff_phone')->nullable();

        // إضافة حقول الطرف الثاني (مدعى عليه)
        $table->string('defendant_name');
        $table->string('defendant_national_id');
        $table->string('defendant_residence');
        $table->string('defendant_job')->nullable();
        $table->string('defendant_phone')->nullable();

        // إضافة حقول الطرف الثالث
        $table->string('third_party_name')->nullable();
        $table->string('third_party_national_id')->nullable();
        $table->string('third_party_residence')->nullable();
        $table->string('third_party_job')->nullable();
        $table->string('third_party_phone')->nullable();
    });
}

public function down()
{
    Schema::table('incoming_prosecutor_cases', function (Blueprint $table) {
        // استرجاع الحقول الموحدة
        $table->string('participant_type')->nullable();
        $table->string('name')->nullable();
        $table->string('national_id')->nullable();
        $table->string('residence')->nullable();
        $table->string('job')->nullable();
        $table->string('phone')->nullable();

        // حذف الحقول الجديدة
        $table->dropColumn([
            'plaintiff_name', 'plaintiff_national_id', 'plaintiff_residence', 'plaintiff_job', 'plaintiff_phone',
            'defendant_name', 'defendant_national_id', 'defendant_residence', 'defendant_job', 'defendant_phone',
            'third_party_name', 'third_party_national_id', 'third_party_residence', 'third_party_job', 'third_party_phone',
        ]);
    });
}
};
