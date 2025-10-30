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
       Schema::create('case_transfers', function (Blueprint $table) {
        $table->id();

        // مصدر القضية (شرطة، محكمة أخرى، إلخ)
        $table->string('source');

        // معرف القضية الأصلية (من جدول الشرطة أو غيره)
        $table->unsignedBigInteger('source_case_id');

        // معرف القضية الجديدة (من جدول court_cases)
        $table->unsignedBigInteger('target_case_id');

        // المستخدم الذي نفذ التحويل
        $table->unsignedBigInteger('transferred_by');

        // تاريخ التحويل
        $table->timestamp('transferred_at');

        $table->timestamps();

        // علاقات اختيارية (لو بدك تربطيهم فعليًا)
        $table->foreign('source_case_id')->references('id')->on('incoming_police_cases')->onDelete('cascade');
        $table->foreign('target_case_id')->references('id')->on('court_cases')->onDelete('cascade');
    
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_transfers');
    }
};
