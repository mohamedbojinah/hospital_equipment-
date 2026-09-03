<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('serial_number')->unique();
            $table->foreignId('equipment_type_id')->constrained()->onDelete('cascade');
            $table->string('department');
            $table->string('location');
            $table->enum('status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->date('purchase_date')->nullable();
            $table->date('warranty_expiry')->nullable();
            $table->string('qr_code')->unique()->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
