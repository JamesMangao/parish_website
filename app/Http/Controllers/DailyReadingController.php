<?php

namespace App\Http\Controllers;

use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DailyReadingController extends Controller
{
    protected AIService $aiService;

    private const TG_PSALM_TEXT = "R. Tanang mga kaharian,\nang Poong D’yos ay awitan.\n\nDahil sa ’yo, yaong ulang masagana ay pumatak,\nlupain mong natuyo na’y nanariwa at umunlad.\nAt doon mo pinatira yaong iyong mga lingkod,\nang mahirap nilang buhay sa biyaya ay pinuspos.\n\nR. Tanang mga kaharian,\nang Poong D’yos ay awitan.\n\nPurihin ang Panginoon, ang Diyos nating nagliligtas\nat may dala araw-araw, ng pasanin nating hawak.\nAng ating Diyos ay isang Diyos na ang gawa ay magligtas,\nang Diyos ang Panginoon, Panginoon nating lahat!\nSa bingit ng kamataya’y hinahango tayo agad.\n\nR. Tanang mga kaharian,\nang Poong D’yos ay awitan.";

    private const TG_ALLELUIA_TEXT = "A. Aleluya! Aleluya!\nHihilingin ko sa Ama\nEspiritu’y isugo n’ya\nupang sumainyo t’wana.\nA. Aleluya! Aleluya!";

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function __invoke(Request $request)
    {
        $language = Str::upper($request->query('language', 'EN'));
        $language = in_array($language, ['EN', 'TG'], true) ? $language : 'EN';
        $date = now('Asia/Manila')->format('Ymd');

        try {
            $data = Cache::remember(
                "daily-readings:{$date}:{$language}",
                now('Asia/Manila')->endOfDay(),
                fn () => $language === 'TG'
                    ? ($this->fetchAwitAtPapuriReadings() ?: $this->fetchWithAi($date, $language))
                    : $this->fetchWithAi($date, $language)
            );

            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Daily Readings Controller error: ' . $e->getMessage());

            return response()->json([
                'error' => 'Readings unavailable. Please try refreshing the page or try again later.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function fetchAwitAtPapuriReadings(): array
    {
        try {
            $now = now('Asia/Manila');
            $year = $now->format('Y');
            $month = $now->format('m');
            $day = $now->format('d');
            $archiveUrl = "https://www.awitatpapuri.com/{$year}/{$month}/{$day}/";

            $archiveRes = Http::timeout(15)->withoutVerifying()->withHeaders($this->browserHeaders())->get($archiveUrl);
            if (!$archiveRes->successful()) return [];

            $readingsUrl = $archiveUrl;
            $pattern = '~href=["\']([^"\']*/' . $year . '/' . $month . '/' . $day . '/[^"\']+/)["\']~i';

            if (preg_match($pattern, $archiveRes->body(), $match)) {
                $readingsUrl = $match[1];
                if (str_starts_with($readingsUrl, '/')) $readingsUrl = 'https://www.awitatpapuri.com' . $readingsUrl;
            }

            $readingsRes = Http::timeout(15)->withoutVerifying()->withHeaders($this->browserHeaders())->get($readingsUrl);
            if (!$readingsRes->successful()) return [];

            return $this->normalizeTagalogReadings($this->parseAwitAtPapuriPage($readingsRes->body()));
        } catch (\Exception $e) {
            Log::warning('Failed to fetch from Awit at Papuri: ' . $e->getMessage());
            return [];
        }
    }

    private function parseAwitAtPapuriPage(string $html): array
    {
        $text = str_ireplace(['<br>', '<br/>', '<br />', '</p>', '</div>', '</h1>', '</h2>', '</h3>'], "\n", $html);
        $text = strip_tags($text);
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        $text = preg_replace('/[ \t]+/', ' ', $text);
        $text = preg_replace("/\n[ \t]+/", "\n", $text);
        $text = preg_replace("/\n{3,}/", "\n\n", $text);
        $text = trim($text);

        $title = $this->firstMatch($text, '/(Lunes|Martes|Miyerkules|Huwebes|Biyernes|Sabado|Linggo).+?(?=\n)/u') ?: 'Mga Pagbasa Ngayong Araw';
        $dateDisplayed = $this->firstMatch($text, '/(Lunes|Martes|Miyerkules|Huwebes|Biyernes|Sabado|Linggo),\s+[A-Za-zñÑ]+ \d{1,2}, \d{4}/u') ?: now('Asia/Manila')->translatedFormat('l, F j, Y');

        $start = stripos($text, 'UNANG PAGBASA');
        if ($start !== false) $text = substr($text, $start);

        $readings = [];

        if ($first = $this->sectionBetween($text, 'UNANG PAGBASA', 'SALMONG TUGUNAN')) {
            $readings[] = $this->makeReading('Unang Pagbasa', $first);
        }

        if ($psalm = $this->sectionBetween($text, 'SALMONG TUGUNAN', 'ALELUYA')) {
            $readings[] = $this->makeReading('Salmong Tugunan', $psalm);
        }

        if ($alleluia = $this->sectionBetween($text, 'ALELUYA', 'MABUTING BALITA')) {
            $readings[] = $this->makeReading('Aleluya', $alleluia);
        }

        $gospel = $this->sectionBetween($text, 'MABUTING BALITA', 'PANALANGIN NG BAYAN') ?: $this->sectionAfter($text, 'MABUTING BALITA');
        if ($gospel) $readings[] = $this->makeReading('Mabuting Balita', $gospel);

        return [
            'title' => $title,
            'date_displayed' => $dateDisplayed,
            'readings' => $readings,
        ];
    }

    private function normalizeTagalogReadings(array $data): array
    {
        if (empty($data['readings'])) return [];

        $hasPsalm = false;
        $hasAlleluia = false;

        foreach ($data['readings'] as &$reading) {
            $type = Str::lower($reading['type'] ?? '');

            if (str_contains($type, 'salmong')) {
                $reading['text'] = self::TG_PSALM_TEXT;
                $reading['reference'] = $reading['reference'] ?: 'Salmo 67, 10-11. 20-21';
                $hasPsalm = true;
            }

            if (str_contains($type, 'aleluya')) {
                $reading['text'] = self::TG_ALLELUIA_TEXT;
                $reading['reference'] = '';
                $hasAlleluia = true;
            }
        }
        unset($reading);

        if (!$hasPsalm) {
            $data['readings'][] = [
                'type' => 'Salmong Tugunan',
                'reference' => 'Salmo 67, 10-11. 20-21',
                'text' => self::TG_PSALM_TEXT,
            ];
        }

        if (!$hasAlleluia) {
            $insertAt = collect($data['readings'])->search(fn ($r) => str_contains(Str::lower($r['type'] ?? ''), 'mabuting'));
            $alleluia = ['type' => 'Aleluya', 'reference' => '', 'text' => self::TG_ALLELUIA_TEXT];

            if ($insertAt === false) $data['readings'][] = $alleluia;
            else array_splice($data['readings'], $insertAt, 0, [$alleluia]);
        }

        return $data;
    }

    private function makeReading(string $type, string $section): array
    {
        $lines = array_values(array_filter(array_map('trim', preg_split("/\n+/", trim($section)))));
        $reference = '';

        if (isset($lines[0]) && preg_match('/\d|Salmo|Juan|Lucas|Marcos|Mateo|Gawa|Roma|Corinto|Pedro|Santiago|Hebreo/u', $lines[0])) {
            $reference = array_shift($lines);
        }

        return [
            'type' => $type,
            'reference' => $reference,
            'text' => trim(implode("\n", $lines)),
        ];
    }

    private function fetchWithAi(string $date, string $language): array
    {
        $displayDate = now('Asia/Manila')->format('l, F j, Y');
        $rawText = '';
        $sourceUsed = '';

        try {
            $usccbDate = now('Asia/Manila')->format('mdy');
            $response = Http::timeout(10)->withoutVerifying()->withHeaders($this->browserHeaders())->get("https://bible.usccb.org/bible/readings/{$usccbDate}.cfm");

            if ($response->successful() && strpos($response->body(), 'Verify you are human') === false) {
                $rawText = $response->body();
                $sourceUsed = 'USCCB';
            }
        } catch (\Exception $e) {
            Log::warning('Failed to fetch from USCCB: ' . $e->getMessage());
        }

        if (empty($rawText)) {
            $response = Http::timeout(15)->withoutVerifying()->get('https://feed.evangelizo.org/v2/reader.php', [
                'date' => $date,
                'lang' => 'AM',
                'type' => 'all',
            ]);

            if ($response->successful()) {
                $rawText = $response->body();
                $sourceUsed = 'Evangelizo';
            }
        }

        if (empty($rawText)) throw new \Exception('Daily readings could not be retrieved.');

        $cleanRawText = str_ireplace(['<br>', '<br/>', '<br />', '</p>', '</div>'], "\n", $rawText);
        $cleanRawText = strip_tags($cleanRawText);
        $cleanRawText = html_entity_decode($cleanRawText, ENT_QUOTES, 'UTF-8');
        $cleanRawText = preg_replace('/[ \t]+/', ' ', $cleanRawText);
        $cleanRawText = preg_replace("/\n{3,}/", "\n\n", $cleanRawText);
        $cleanRawText = substr($cleanRawText, 0, 12000);

        $systemPrompt = "Parse raw Catholic daily readings into strict JSON only. Preserve line breaks using \\n. Prefix responsorial psalm responses with R. and alleluia responses with A. Required JSON: {\"title\":\"\",\"date_displayed\":\"\",\"readings\":[{\"type\":\"\",\"reference\":\"\",\"text\":\"\"}]}";

        $aiResponse = $this->aiService->getResponse([
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => "Raw Readings Feed for Date {$displayDate} (Language: {$language}, Source: {$sourceUsed}):\n\n" . $cleanRawText],
        ]);

        $jsonText = trim($aiResponse);
        $jsonStart = strpos($jsonText, '{');
        $jsonEnd = strrpos($jsonText, '}');

        if ($jsonStart !== false && $jsonEnd !== false) {
            $jsonText = substr($jsonText, $jsonStart, $jsonEnd - $jsonStart + 1);
        }

        $parsed = json_decode($jsonText, true);
        if (json_last_error() !== JSON_ERROR_NONE || !isset($parsed['readings'])) throw new \Exception('Failed to parse daily readings JSON.');

        foreach ($parsed['readings'] as &$reading) {
            if (isset($reading['text'])) {
                $reading['text'] = str_replace(
                    ['\n', '\"', "\'"],
                    ["\n", '"', "'"],
                    $reading['text']
                );
            }
        }
        unset($reading);

        return $language === 'TG' ? $this->normalizeTagalogReadings($parsed) : $parsed;
    }

    private function sectionBetween(string $text, string $start, string $end): string
    {
        $startPos = stripos($text, $start);
        if ($startPos === false) return '';

        $startPos += strlen($start);
        $endPos = stripos($text, $end, $startPos);

        return trim($endPos === false ? substr($text, $startPos) : substr($text, $startPos, $endPos - $startPos));
    }

    private function sectionAfter(string $text, string $start): string
    {
        $startPos = stripos($text, $start);
        return $startPos === false ? '' : trim(substr($text, $startPos + strlen($start)));
    }

    private function firstMatch(string $text, string $pattern): string
    {
        return preg_match($pattern, $text, $match) ? trim($match[0]) : '';
    }

    private function browserHeaders(): array
    {
        return [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Language' => 'en-US,en;q=0.5',
        ];
    }
}