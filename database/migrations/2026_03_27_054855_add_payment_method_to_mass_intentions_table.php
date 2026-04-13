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
            $table->string('raw_message')->nullable()->change();
            $table->string('payment_method')->nullable()->after('status'); // GCash, Bank, etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mass_intentions', function (Blueprint $table) {
            $table->string('raw_message')->nullable(false)->change();
            $table->dropColumn('payment_method');
        });
    }
};
