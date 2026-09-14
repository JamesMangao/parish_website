<?php

namespace App\Console\Commands;

use App\Models\ChatSession;
use Illuminate\Console\Command;

class PruneChatSessions extends Command
{
    protected $signature = 'chat:prune-stale {--days=2 : Resolve chat sessions idle for N days}';

    protected $description = 'Mark stale/abandoned chat sessions as resolved';

    public function handle(): int
    {
        $sweep = ChatSession::whereIn('status', ['active', 'handover'])
            ->where('updated_at', '<', now()->subDays((int) $this->option('days')))
            ->update(['status' => 'resolved']);

        $this->info("Resolved {$sweep} stale chat session(s).");

        return self::SUCCESS;
    }
}
