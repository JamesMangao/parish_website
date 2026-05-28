<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule daily readings preloading to run twice a day (1:00 AM and 1:00 PM Manila time)
Schedule::command('readings:preload --days=7')->twiceDaily(1, 13)->timezone('Asia/Manila');

