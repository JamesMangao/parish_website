<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Support\VideoEmbed;
use Illuminate\Support\Facades\Cache;

class AboutController extends Controller
{
    private const GAP_START = 1991;
    private const GAP_END = 2008;

    public function __invoke()
    {
        $keys = [
            'about_video_url',
            'about_video_title',
            'about_video_description',
            'former_priests',
            'priest_years',
            'priest_contrib_short',
            'priest_contrib_full',
            'priest_contrib_confirmed',
        ];

        $aboutSettings = Setting::whereIn('key', $keys)->pluck('value', 'key');

        $aboutVideoUrl = trim($aboutSettings['about_video_url'] ?? '');
        $formerPriestsRaw = $aboutSettings['former_priests'] ?? '[]';
        $formerPriests = is_string($formerPriestsRaw)
            ? (json_decode($formerPriestsRaw, true) ?: [])
            : (is_array($formerPriestsRaw) ? $formerPriestsRaw : []);

        $currentPriestYears = $aboutSettings['priest_years'] ?? null;
        $currentPriestConfirmed = !empty($aboutSettings['priest_contrib_confirmed']);
        $currentContribShort = trim($aboutSettings['priest_contrib_short'] ?? '');
        $currentContribFull = trim($aboutSettings['priest_contrib_full'] ?? '');
        $currentShowContrib = $this->shouldShowContrib($currentPriestYears, $currentPriestConfirmed);

        $currentPriestContrib = [
            'short' => $currentContribShort,
            'full' => $currentContribFull,
            'show' => $currentShowContrib && ($currentContribShort !== '' || $currentContribFull !== ''),
        ];

        $formerPriests = collect($formerPriests)->map(function ($fp) {
            $years = $fp['years'] ?? null;
            $confirmed = !empty($fp['contrib_confirmed']);
            $contribShort = trim($fp['contrib_short'] ?? '');
            $contribFull = trim($fp['contrib_full'] ?? '');
            $showContrib = $this->shouldShowContrib($years, $confirmed);

            return array_merge($fp, [
                'contrib_short' => $contribShort,
                'contrib_full' => $contribFull,
                'show_contrib' => $showContrib && ($contribShort !== '' || $contribFull !== ''),
            ]);
        })->all();

        return view('about', [
            'aboutVideoUrl' => $aboutVideoUrl,
            'aboutVideoEmbed' => $aboutVideoUrl ? VideoEmbed::embedUrl($aboutVideoUrl) : null,
            'aboutVideoTitle' => $aboutSettings['about_video_title'] ?? null,
            'aboutVideoDescription' => $aboutSettings['about_video_description'] ?? null,
            'formerPriests' => $formerPriests,
            'currentPriestContrib' => $currentPriestContrib,
        ]);
    }

    private function parseYearRange(?string $years): ?array
    {
        if (empty($years)) {
            return null;
        }

        preg_match_all('/(\d{4})/', $years, $matches);

        if (empty($matches[1])) {
            return null;
        }

        $start = (int) $matches[1][0];
        $end = $matches[1][1] ?? null;

        if ($end === null) {
            if (stripos($years, 'present') !== false) {
                $end = (int) date('Y');
            } else {
                $end = $start;
            }
        } else {
            $end = (int) $end;
        }

        if ($start > $end) {
            [$start, $end] = [$end, $start];
        }

        return [$start, $end];
    }

    private function overlapsGap(array $range): bool
    {
        return $range[0] <= self::GAP_END && $range[1] >= self::GAP_START;
    }

    private function shouldShowContrib(?string $years, bool $confirmed): bool
    {
        if ($confirmed) {
            return true;
        }

        $range = $this->parseYearRange($years);

        if ($range === null) {
            return false;
        }

        return ! $this->overlapsGap($range);
    }
}
