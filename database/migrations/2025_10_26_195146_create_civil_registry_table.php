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
        Schema::create('civil_registry', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->string('national_id')->unique();
        $table->string('full_name')->nullable();
        $table->string('first_name')->nullable();
        $table->string('father_name')->nullable();
        $table->string('mother_name')->nullable();
        $table->string('grandfather_name')->nullable();
        $table->string('family_name')->nullable();
        $table->date('birth_date')->nullable();
        $table->integer('age')->nullable();
        $table->string('gender')->nullable();
        $table->string('marital_status')->nullable();
        $table->string('religion')->nullable();
        $table->string('nationality')->nullable();
        $table->string('place_of_birth')->nullable();
        $table->string('current_address')->nullable();
        $table->string('phone_number')->nullable();
        $table->string('email')->nullable();
        $table->string('occupation')->nullable();
        $table->string('education_level')->nullable();
        $table->string('civil_record_number')->nullable();
        $table->string('record_location')->nullable();
        $table->timestamps(); // created_at + updated_at
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('civil_registry');
    }
};
