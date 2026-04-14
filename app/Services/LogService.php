<?php

namespace App\Services;

use App\Models\ActivityLog;

class LogService
{
    public static function log($action, $model = null, $payload = null)
    {
        return ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'payload' => $payload,
            'ip_address' => request()->ip()
        ]);
    }
}
