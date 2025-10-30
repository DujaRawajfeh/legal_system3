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
        Schema::create('incoming_prosecutor_cases', function (Blueprint $table) {
            $table->bigIncrements('id'); // رقم تسلسلي
            $table->string('case_number')->unique(); // رقم الدعوى
            $table->string('title'); // عنوان القضية
            $table->unsignedBigInteger('department_id'); // القلم المرتبط
            $table->string('plaintiff_name'); // اسم المدعي
            $table->string('defendant_name'); // اسم المدعى عليه
            $table->text('records')->nullable(); // السجلات
            $table->timestamps(); // created_at و updated_at

            // ✅ الربط مع جدول department بدون s
            $table->foreign('department_id')->references('id')->on('department')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_prosecutor_cases');
    }
};
