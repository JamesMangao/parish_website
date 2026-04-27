<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Event;

foreach (Event::all() as $e) {
    echo "ID: " . $e->id . " - " . $e->title . "\n";
    echo "Type: " . gettype($e->event_time) . "\n";
    echo "Data: " . json_encode($e->event_time) . "\n\n";
}
