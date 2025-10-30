<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomingPoliceCasesTable extends Migration
{
    public function up()
    {
        Schema::create('incoming_police_cases', function (Blueprint $table) {
            $table->id();

            // معلومات القضية الأمنية
            $table->string('center_name');
            $table->string('police_case_number');
            $table->date('police_registration_date');
            $table->date('crime_date');
            $table->string('status');
            $table->string('case_type');

            // الطرف الأول: المدعي
            $table->string('plaintiff_name');
            $table->string('plaintiff_national_id');
            $table->string('plaintiff_residence');
            $table->string('plaintiff_job');
            $table->string('plaintiff_phone');
            $table->string('plaintiff_type');

            // الطرف الثاني: المدعى عليه
            $table->string('defendant_name');
            $table->string('defendant_national_id');
            $table->string('defendant_residence');
            $table->string('defendant_job');
            $table->string('defendant_phone');
            $table->string('defendant_type');

            // الطرف الثالث: الشاهد (اختياري)
            $table->string('third_party_name')->nullable();
            $table->string('third_party_national_id')->nullable();
            $table->string('third_party_residence')->nullable();
            $table->string('third_party_job')->nullable();
            $table->string('third_party_phone')->nullable();
            $table->string('third_party_type')->nullable();

            // الربط القضائي
            $table->unsignedBigInteger('tribunal_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();

            $table->timestamps();
        });

        // الربط بعد إنشاء الجدول
        Schema::table('incoming_police_cases', function (Blueprint $table) {
            $table->foreign('tribunal_id')
                  ->references('id')
                  ->on('tribunal')
                  ->onDelete('set null');

            $table->foreign('department_id')
                  ->references('id')
                  ->on('department')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('incoming_police_cases');
    }
}