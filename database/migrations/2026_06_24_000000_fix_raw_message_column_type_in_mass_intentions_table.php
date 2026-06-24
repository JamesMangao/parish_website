<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mass_intentions', function (Blueprint $table) {
            $table->text('raw_message')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('mass_intentions', function (Blueprint $table) {
            $table->string('raw_message')->nullable()->change();
        });
    }
};
