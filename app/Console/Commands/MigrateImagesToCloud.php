<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class MigrateImagesToCloud extends Command
{
    protected $signature = 'migrate:images';
    protected $description = 'Upload local storage images to Supabase cloud storage';

    public function handle()
    {
        $this->info('Starting migration to cloud storage...');

        $directories = ['gallery', 'settings'];
        $defaultDisk = config('filesystems.default');

        if ($defaultDisk !== 'supabase') {
            $this->error('Default disk is not set to supabase. Please check your .env file.');
            return 1;
        }

        foreach ($directories as $dir) {
            $localPath = storage_path('app/public/' . $dir);
            
            if (!File::exists($localPath)) {
                $this->warn("Local directory $dir does not exist, skipping.");
                continue;
            }

            $files = File::files($localPath);
            $this->info("Found " . count($files) . " files in $dir.");

            foreach ($files as $file) {
                $filename = $file->getFilename();
                $cloudPath = $dir . '/' . $filename;

                $this->line("Uploading $filename to cloud...");

                try {
                    $contents = File::get($file->getPathname());
                    $success = Storage::disk('supabase')->put($cloudPath, $contents);

                    if ($success) {
                        $this->info("Successfully uploaded $filename");
                    } else {
                        $this->error("Failed to upload $filename");
                    }
                } catch (\Exception $e) {
                    $this->error("Error uploading $filename: " . $e->getMessage());
                }
            }
        }

        $this->info('Migration completed!');
    }
}
