<?php

namespace App\Jobs;

use App\Models\ActivityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LogActivity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public ?string $userId,
        public string $action,
        public ?string $modelType,
        public ?string $modelId,
        public mixed $payload,
        public ?string $ipAddress
    ) {}

    public function handle(): void
    {
        ActivityLog::create([
            'user_id' => $this->userId,
            'action' => $this->action,
            'model_type' => $this->modelType,
            'model_id' => $this->modelId,
            'payload' => $this->payload,
            'ip_address' => $this->ipAddress,
        ]);
    }
}