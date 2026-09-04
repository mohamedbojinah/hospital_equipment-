<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->nullable()->constrained('tickets')->onDelete('cascade');
            $table->foreignId('maintenance_record_id')->nullable()->constrained('maintenance_records')->onDelete('cascade');
            $table->foreignId('equipment_id')->constrained('equipment')->onDelete('cascade');
            $table->foreignId('submitted_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->string('title');
            $table->text('description');
            $table->decimal('total_amount', 15, 2);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('file_path')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
