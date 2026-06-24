<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule daily readings preloading: twice daily (1 AM & 1 PM Manila time), 15 days ahead
Schedule::command('readings:preload --days=15')->twiceDaily(1, 13)->timezone('Asia/Manila');

