<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Jobs\LogActivity;

class LogService
{
    public static function log($action, $model = null, $payload = null)
    {
        $ip = request()->ip();
        $userId = auth()->id() ? (string) auth()->id() : null;
        $modelType = $model ? get_class($model) : null;
        $modelId = $model ? (string) $model->id : null;

        try {
            LogActivity::dispatch($userId, $action, $modelType, $modelId, $payload, $ip);
        } catch (\Throwable $e) {
            // Queue unavailable (e.g. single-instance deploy) — fall back to a synchronous write.
            try {
                ActivityLog::create([
                    'user_id' => $userId,
                    'action' => $action,
                    'model_type' => $modelType,
                    'model_id' => $modelId,
                    'payload' => $payload,
                    'ip_address' => $ip,
                ]);
            } catch (\Throwable $e2) {
                \Illuminate\Support\Facades\Log::warning('Failed to log activity: ' . $e2->getMessage());
            }
        }
    }
}