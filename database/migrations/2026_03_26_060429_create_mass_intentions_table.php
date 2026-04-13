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
        Schema::create('mass_intentions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('full_name');
            $table->string('intention_type');
            $table->string('ai_suggested_type')->nullable();
            $table->text('raw_message');
            $table->text('formatted_message')->nullable();
            $table->date('preferred_date')->nullable();
            $table->uuid('mass_schedule_id')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->uuid('reviewed_by')->nullable();
            $table->timestamps();
            
            $table->foreign('mass_schedule_id')->references('id')->on('mass_schedules')->onDelete('set null');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mass_intentions');
    }
};
