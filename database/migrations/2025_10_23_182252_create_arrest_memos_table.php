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
        

    Schema::create('arrest_memos', function (Blueprint $table) {
        $table->id();
        $table->string('case_id', 50);
        $table->string('judge_name', 100)->nullable();
        $table->integer('detention_duration')->nullable();
        $table->text('detention_reason')->nullable();
        $table->string('detention_center', 100)->nullable();
        $table->unsignedBigInteger('created_by');
        $table->timestamps();

        $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arrest_memos');
    }
};
