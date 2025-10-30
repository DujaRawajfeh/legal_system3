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
    {   Schema::create('tribunal', function (Blueprint $table) {
              $table->id();
              $table->string('name');         // اسم المحكمة
              $table->string('number');       // رقم المحكمة بالنمط ٢/٢
              $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tribunal');
    }
};
