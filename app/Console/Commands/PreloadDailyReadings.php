<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DailyReading;
use App\Http\Controllers\DailyReadingController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PreloadDailyReadings extends Command
{
    protected $signature = 'readings:preload {--days=15 : The number of days to preload}';

    protected $description = 'Pre-fetches Catholic daily readings for both English and Filipino languages';

    public function handle(DailyReadingController $controller)
    {
        $days = (int) $this->option('days');
        $this->info("Preloading daily readings for the next {$days} days...");

        $now = Carbon::now('Asia/Manila');
        $successCount = 0;
        $skipCount = 0;
        $failCount = 0;

        for ($i = 0; $i < $days; $i++) {
            $targetDate = $now->copy()->addDays($i);
            $dateStr = $targetDate->format('Ymd');
            $dateLabel = $targetDate->format('D, M j');

            foreach (['TG', 'EN'] as $lang) {
                $exists = DailyReading::where('date', $dateStr)
                    ->where('language', $lang)
                    ->where('deleted_at', null)
                    ->exists();

                if ($exists) {
                    $this->line("  [SKIP] {$dateLabel} ({$lang}) - already in DB");
                    $skipCount++;
                    continue;
                }

                $this->line("  [FETCH] {$dateLabel} ({$lang})...");
                try {
                    $controller->getOrFetchReadings($dateStr, $lang);
                    $this->info("  [OK] {$dateLabel} ({$lang})");
                    $successCount++;
                } catch (\Exception $e) {
                    $this->error("  [FAIL] {$dateLabel} ({$lang}): " . $e->getMessage());
                    Log::warning("readings:preload failed for {$dateStr} ({$lang}): " . $e->getMessage());
                    $failCount++;
                }

                usleep(800000);
            }
        }

        $this->newLine();
        $this->info("Done! Success: {$successCount}, Skipped: {$skipCount}, Failed: {$failCount}");

        return self::SUCCESS;
    }
}
