<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('daily_readings');
    }

    public function down(): void
    {
        // Recreated from the original feature migration if ever needed again.
    }
};
