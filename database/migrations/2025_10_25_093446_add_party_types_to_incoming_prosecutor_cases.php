<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('incoming_prosecutor_cases', function (Blueprint $table) {
        $table->string('plaintiff_type')->nullable()->after('plaintiff_phone');
        $table->string('defendant_type')->nullable()->after('defendant_phone');
        $table->string('third_party_type')->nullable()->after('third_party_phone');
    });
}

public function down()
{
    Schema::table('incoming_prosecutor_cases', function (Blueprint $table) {
        $table->dropColumn(['plaintiff_type', 'defendant_type', 'third_party_type']);
    });
}
};
