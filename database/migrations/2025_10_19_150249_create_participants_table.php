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
        Schema::create('participants', function (Blueprint $table) {
    $table->id(); // رقم تلقائي
    $table->foreignId('court_case_id')->constrained('court_cases')->onDelete('cascade'); // القضية المرتبطة
    $table->string('type'); // نوع الطرف (مدعي، مدعى عليه...)
    $table->string('name');
    $table->string('national_id');
    $table->string('residence');
    $table->string('job');
    $table->string('phone');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
