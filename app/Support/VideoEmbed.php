<?php

namespace App\Support;

class VideoEmbed
{
    public static function parseYouTubeId(string $url): ?string
    {
        $url = trim($url);

        if (preg_match('#(?:youtube\.com/(?:watch\?.*v=|embed/|shorts/)|youtu\.be/)([a-zA-Z0-9_-]{11})#', $url, $m)) {
            return $m[1];
        }

        return null;
    }

    public static function embedUrl(string $url): ?string
    {
        $id = self::parseYouTubeId($url);

        return $id ? 'https://www.youtube.com/embed/' . $id : null;
    }

    public static function thumbnailUrl(string $url): ?string
    {
        $id = self::parseYouTubeId($url);

        return $id ? 'https://img.youtube.com/vi/' . $id . '/hqdefault.jpg' : null;
    }
}
