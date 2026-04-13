<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique();
            $table->string('user_ip')->nullable();
            $table->string('status')->default('active'); // active, handover, closed
            $table->timestamp('live_agent_requested_at')->nullable();
            $table->foreignUuid('admin_id')->nullable()->constrained('users')->nullOnDelete(); // ✅ only this line
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_sessions');
    }
};
