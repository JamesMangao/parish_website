<?php

namespace App\Console\Commands;

use App\Data\DefaultTimeline;
use App\Models\Setting;
use Illuminate\Console\Command;

class SeedTimeline extends Command
{
    protected $signature = 'parish:seed-timeline';
    protected $description = 'Seed the parish_timeline setting with default Sacred History entries (idempotent)';

    public function handle()
    {
        $key = 'parish_timeline';

        if (Setting::where('key', $key)->exists()) {
            $this->info(" '{$key}' already exists in settings — skipping.");
            return Command::SUCCESS;
        }

        $entries = DefaultTimeline::entries();
        Setting::create(['key' => $key, 'value' => json_encode($entries)]);

        $this->info(" Seeded {$key} with " . count($entries) . " default entries.");
        return Command::SUCCESS;
    }
}
