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
        // Update Announcements
        Schema::table('announcements', function (Blueprint $table) {
            $table->timestamp('expires_at')->nullable()->after('published_at');
        });

        // Update Inquiries
        Schema::table('inquiries', function (Blueprint $table) {
            $table->uuid('reference_id')->unique()->nullable()->after('id');
        });

        // Create Bulletins
        Schema::create('bulletins', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('file_path');
            $table->date('published_date');
            $table->timestamps();
        });

        // Update Users for 2FA
        Schema::table('users', function (Blueprint $table) {
            $table->text('two_factor_secret')->nullable()->after('password');
            $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_recovery_codes');
        });
        
        // Update Mass Intentions for Ref ID (it already has UUID id, but let's make sure it's clear)
        // It use HasUuids, so ID is already a reference.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn('expires_at');
        });

        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropColumn('reference_id');
        });

        Schema::dropIfExists('bulletins');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['two_factor_secret', 'two_factor_recovery_codes', 'two_factor_confirmed_at']);
        });
    }
};
