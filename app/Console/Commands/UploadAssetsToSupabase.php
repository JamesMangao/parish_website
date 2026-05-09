<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class UploadAssetsToSupabase extends Command
{
    protected $signature = 'assets:upload-supabase';
    protected $description = 'Upload local public image assets to Supabase storage bucket';

    public function handle()
    {
        $disk = Storage::disk('supabase');

        // Files from public/assets/img/ → assets/img/ in bucket
        $imgDir = public_path('assets/img');
        $imgFiles = glob($imgDir . '/*.*');

        // Files from public/ root → assets/ in bucket
        $rootFiles = [
            public_path('bg.webp') => 'assets/bg.webp',
            public_path('assets/img/olp.webp') => 'assets/olp.webp',
            public_path('assets/img/svf.webp') => 'assets/svf.webp',
        ];

        $this->info('Uploading image assets to Supabase...');
        $this->newLine();

        // Upload assets/img files
        foreach ($imgFiles as $file) {
            $filename = basename($file);
            $remotePath = 'assets/img/' . $filename;

            $this->line("  Uploading: {$remotePath}");
            try {
                $disk->put($remotePath, file_get_contents($file), 'public');
                $this->info("  ✓ Uploaded: {$remotePath}");
            } catch (\Exception $e) {
                $this->error("  ✗ Failed: {$remotePath} — " . $e->getMessage());
            }
        }

        // Upload root-level files
        foreach ($rootFiles as $localPath => $remotePath) {
            if (!file_exists($localPath)) {
                $this->warn("  ⊘ Skipped (not found): {$remotePath}");
                continue;
            }

            $this->line("  Uploading: {$remotePath}");
            try {
                $disk->put($remotePath, file_get_contents($localPath), 'public');
                $this->info("  ✓ Uploaded: {$remotePath}");
            } catch (\Exception $e) {
                $this->error("  ✗ Failed: {$remotePath} — " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info('Done! All assets uploaded to Supabase.');

        return Command::SUCCESS;
    }
}
