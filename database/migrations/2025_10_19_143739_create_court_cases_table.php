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
        Schema::create('court_cases', function (Blueprint $table) {
    $table->id(); // رقم تلقائي
    $table->string('type'); // نوع الدعوى (حقوقي، جزائي...)
    $table->string('number'); // رقم الدعوى
    $table->string('year'); // السنة
    $table->foreignId('tribunal_id')->constrained('tribunal'); // المحكمة المرتبطة
    $table->foreignId('department_id')->constrained('department'); // القلم المرتبط
    $table->foreignId('created_by')->constrained('users'); // المستخدم اللي سجل الدعوى
    $table->timestamps(); // created_at و updated_at
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('court_cases');
    }
};
