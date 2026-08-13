<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Support\VideoEmbed;
use Illuminate\Support\Facades\Cache;

class AboutController extends Controller
{
    public function __invoke()
    {
        $keys = [
            'about_video_url',
            'about_video_title',
            'about_video_description',
            'former_priests',
        ];

        $aboutSettings = Setting::whereIn('key', $keys)->pluck('value', 'key');

        $aboutVideoUrl = trim($aboutSettings['about_video_url'] ?? '');
        $formerPriestsRaw = $aboutSettings['former_priests'] ?? '[]';
        $formerPriests = is_string($formerPriestsRaw)
            ? (json_decode($formerPriestsRaw, true) ?: [])
            : (is_array($formerPriestsRaw) ? $formerPriestsRaw : []);

        return view('about', [
            'aboutVideoUrl' => $aboutVideoUrl,
            'aboutVideoEmbed' => $aboutVideoUrl ? VideoEmbed::embedUrl($aboutVideoUrl) : null,
            'aboutVideoTitle' => $aboutSettings['about_video_title'] ?? null,
            'aboutVideoDescription' => $aboutSettings['about_video_description'] ?? null,
            'formerPriests' => $formerPriests,
        ]);
    }
}
