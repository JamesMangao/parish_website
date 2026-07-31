<?php

namespace App\Jobs;

use App\Models\GalleryAlbum;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadGalleryMediaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public GalleryAlbum $album,
        public string $tempPath,
        public string $originalName,
        public string $mimeType
    ) {}

    public function handle(): void
    {
        $extension = pathinfo($this->originalName, PATHINFO_EXTENSION) ?: 'bin';
        $filename = Str::uuid() . '.' . strtolower($extension);

        if (Storage::disk('local')->exists($this->tempPath)) {
            $contents = Storage::disk('local')->get($this->tempPath);
            $path = Storage::put('gallery/' . $filename, $contents);
            Storage::disk('local')->delete($this->tempPath);

            if ($path) {
                $isVideo = Str::startsWith($this->mimeType, 'video/') ||
                    in_array(strtolower($extension), ['mp4', 'mov', 'ogv', 'avi', 'wmv', 'flv', 'mkv', 'webm']);

                $this->album->images()->create([
                    'title' => $this->originalName,
                    'storage_path' => $filename,
                    'type' => $isVideo ? 'video' : 'image',
                    'is_published' => true,
                ]);
            }
        }
    }
}
