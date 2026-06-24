<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mass_intentions', function (Blueprint $table) {
            $table->index('status');
            $table->index('preferred_date');
            $table->index('created_at');
        });

        Schema::table('inquiries', function (Blueprint $table) {
            $table->index('status');
            $table->index('email');
        });

        Schema::table('chat_sessions', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->index(['event_date', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::table('mass_intentions', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['preferred_date']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['email']);
        });

        Schema::table('chat_sessions', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex(['event_date', 'is_published']);
        });
    }
};
