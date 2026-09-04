<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maintenance_records', function (Blueprint $table) {
            $table->text('engineer_report')->nullable();
            $table->text('spare_parts_changed')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('maintenance_records', function (Blueprint $table) {
            $table->dropColumn(['engineer_report', 'spare_parts_changed']);
        });
    }
};
