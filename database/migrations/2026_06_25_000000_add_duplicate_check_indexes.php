<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mass_intentions', function (Blueprint $table) {
            $table->index(['email', 'intention_type', 'status'], 'idx_mass_intentions_dup_check');
            $table->index(['full_name', 'intention_type', 'status'], 'idx_mass_intentions_admin_dup_check');
        });
    }

    public function down(): void
    {
        Schema::table('mass_intentions', function (Blueprint $table) {
            $table->dropIndex('idx_mass_intentions_dup_check');
            $table->dropIndex('idx_mass_intentions_admin_dup_check');
        });
    }
};
