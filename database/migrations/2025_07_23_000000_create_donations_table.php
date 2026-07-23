<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('donor_name')->nullable();
            $table->string('donor_email')->nullable();
            $table->integer('amount'); // in centavos
            $table->string('currency', 10)->default('PHP');
            $table->string('purpose')->nullable();
            $table->text('message')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('checkout_session_id')->unique();
            $table->string('paymongo_payment_id')->nullable();
            $table->string('status')->default('pending'); // pending, paid, failed, expired
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
