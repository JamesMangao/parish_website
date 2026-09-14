<?php

namespace Tests\Feature;

use App\Models\ChatSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ChatSessionPruningTest extends TestCase
{
    use RefreshDatabase;

    private function makeSession(string $status, int $daysAgo): ChatSession
    {
        $session = ChatSession::create([
            'session_id' => 'sess_'.uniqid(),
            'user_ip' => '127.0.0.1',
            'status' => $status,
        ]);

        $session->forceFill(['updated_at' => now()->subDays($daysAgo)])->save();

        return $session;
    }

    public function test_prune_command_resolves_stale_sessions_only(): void
    {
        $stale = $this->makeSession('active', 3);
        $handover = $this->makeSession('handover', 3);
        $fresh = $this->makeSession('active', 0);
        $resolved = $this->makeSession('resolved', 10);

        Artisan::call('chat:prune-stale');

        $this->assertDatabaseHas('chat_sessions', ['id' => $stale->id, 'status' => 'resolved']);
        $this->assertDatabaseHas('chat_sessions', ['id' => $handover->id, 'status' => 'resolved']);
        $this->assertDatabaseHas('chat_sessions', ['id' => $fresh->id, 'status' => 'active']);
        $this->assertDatabaseHas('chat_sessions', ['id' => $resolved->id, 'status' => 'resolved']);
    }

    public function test_inline_sweep_resolves_stale_sessions(): void
    {
        Cache::forget('chat_prune_last_run');

        $stale = $this->makeSession('active', 3);

        $this->get(route('chatbot.session-status'))->assertOk();

        $this->assertDatabaseHas('chat_sessions', ['id' => $stale->id, 'status' => 'resolved']);
    }

    public function test_prune_command_honors_custom_age(): void
    {
        $older = $this->makeSession('active', 3);
        $mid = $this->makeSession('active', 1);

        Artisan::call('chat:prune-stale', ['--days' => 7]);

        $this->assertDatabaseHas('chat_sessions', ['id' => $older->id, 'status' => 'active']);
        $this->assertDatabaseHas('chat_sessions', ['id' => $mid->id, 'status' => 'active']);
    }
}
