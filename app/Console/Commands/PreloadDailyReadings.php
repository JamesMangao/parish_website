<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DailyReading;
use App\Http\Controllers\DailyReadingController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PreloadDailyReadings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'readings:preload {--days=7 : The number of days to preload}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pre-fetches Catholic daily readings for both English and Tagalog languages';

    /**
     * Execute the console command.
     */
    public function handle(DailyReadingController $controller)
    {
        $days = (int) $this->option('days');
        $this->info("Preloading daily readings for the next {$days} days...");

        $now = Carbon::now('Asia/Manila');

        for ($i = 0; $i < $days; $i++) {
            $targetDate = $now->copy()->addDays($i);
            $dateStr = $targetDate->format('Ymd');

            foreach (['EN', 'TG'] as $lang) {
                $this->info("Checking {$dateStr} ({$lang})...");

                // Check if already in DB
                $exists = DailyReading::where('date', $dateStr)
                    ->where('language', $lang)
                    ->exists();

                if ($exists) {
                    $this->line("-> Already exists in database.");
                    continue;
                }

                $this->info("-> Fetching readings for {$dateStr} ({$lang})...");
                try {
                    $controller->getOrFetchReadings($dateStr, $lang);
                    $this->info("-> Successfully preloaded!");
                } catch (\Exception $e) {
                    $this->error("-> Failed to preload: " . $e->getMessage());
                    Log::warning("Artisan command readings:preload failed for date {$dateStr} ({$lang}): " . $e->getMessage());
                }

                // Add a small sleep between requests to avoid rate limits
                usleep(500000); // 0.5s
            }
        }

        $this->info('Preloading completed!');
        return self::SUCCESS;
    }
}
