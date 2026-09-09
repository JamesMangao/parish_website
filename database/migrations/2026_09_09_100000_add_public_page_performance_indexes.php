<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gallery_albums', function (Blueprint $table) {
            $table->index(['is_published', 'created_at']);
        });

        Schema::table('gallery_images', function (Blueprint $table) {
            $table->index(['album_id', 'created_at']);
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->index(['is_published', 'expires_at']);
        });

        Schema::table('mass_schedules', function (Blueprint $table) {
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('gallery_albums', function (Blueprint $table) {
            $table->dropIndex(['is_published', 'created_at']);
        });

        Schema::table('gallery_images', function (Blueprint $table) {
            $table->dropIndex(['album_id', 'created_at']);
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->dropIndex(['is_published', 'expires_at']);
        });

        Schema::table('mass_schedules', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
        });
    }
};