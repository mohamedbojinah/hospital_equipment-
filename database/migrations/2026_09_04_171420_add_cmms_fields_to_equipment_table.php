<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->string('manufacturer')->nullable();
            $table->string('model_number')->nullable();
            $table->string('supplier')->nullable();
            $table->decimal('purchase_price', 15, 2)->nullable();
            $table->string('invoice_number')->nullable();
            
            $table->integer('expected_life_span')->nullable()->comment('in years');
            $table->integer('operating_hours')->nullable();
            $table->enum('risk_level', ['low', 'medium', 'high'])->nullable();
            $table->date('operating_date')->nullable();
            
            $table->string('manual_file_path')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->dropColumn([
                'manufacturer', 'model_number', 'supplier', 'purchase_price', 'invoice_number',
                'expected_life_span', 'operating_hours', 'risk_level', 'operating_date', 'manual_file_path'
            ]);
        });
    }
};
