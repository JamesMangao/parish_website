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
        Schema::table('mass_intentions', function (Blueprint $table) {
            $table->string('reference_number')->unique()->nullable()->after('id');
            $table->text('rejection_reason')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mass_intentions', function (Blueprint $table) {
            $table->dropColumn(['reference_number', 'rejection_reason']);
        });
    }
};
