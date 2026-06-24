<?php

namespace App\Http\Controllers;

use App\Services\AIService;
use App\Models\DailyReading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DailyReadingController extends Controller
{
    protected AIService $aiService;

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
                "daily-readings:db:{$date}:{$language}",
                now('Asia/Manila')->endOfDay(),
                fn () => $this->getOrFetchReadings($date, $language)
            );

            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Daily Readings Controller error: ' . $e->getMessage());

            $fallback = DailyReading::where('language', $language)
                ->orderBy('date', 'desc')
                ->first();

            if ($fallback) {
                return response()->json([
                    'title'          => $fallback->title,
                    'date_displayed' => $fallback->date_displayed . ' (Offline Fallback)',
                    'readings'       => $fallback->readings,
                    'is_fallback'    => true,
                ]);
            }

            return response()->json([
                'error'   => 'Readings unavailable. Please try refreshing the page or try again later.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getOrFetchReadings(string $date, string $language): array
    {
        $record = DailyReading::where('date', $date)
            ->where('language', $language)
            ->first();

        if ($record) {
            $isStale = false;
            if ($language === 'TG') {
                $readingsText = json_encode($record->readings);
                if (str_contains($readingsText, "Tanang mga kaharian") || str_contains($readingsText, "Hihilingin ko sa Ama")) {
                    $isStale = true;
                }
            }

            if (!$isStale) {
                return [
                    'title'          => $record->title,
                    'date_displayed' => $record->date_displayed,
                    'readings'       => $record->readings,
                ];
            }

            $record->delete();
        }

        $dateObj    = Carbon::createFromFormat('Ymd', $date, 'Asia/Manila');
        $scraperLang = $language === 'TG' ? 'tl' : 'en';

        $data = $this->fetchAwitAtPapuriReadings($dateObj, $scraperLang);

        if (empty($data) || empty($data['readings'])) {
            $data = $this->fetchWithAi($dateObj, $language);
        }

        if (empty($data) || empty($data['readings'])) {
            throw new \Exception("Could not retrieve valid readings for {$date} ({$language})");
        }

        DailyReading::updateOrCreate(
            ['date' => $date, 'language' => $language],
            [
                'title'          => $data['title'] ?? 'Daily Mass Readings',
                'date_displayed' => $data['date_displayed'] ?? $dateObj->format('l, F j, Y'),
                'readings'       => $data['readings'],
            ]
        );

        return $data;
    }

    private function fetchAwitAtPapuriReadings(Carbon $targetDate, string $lang = 'tl'): array
    {
        try {
            $year  = $targetDate->format('Y');
            $month = $targetDate->format('m');
            $day   = $targetDate->format('d');

            $archiveUrl = "https://www.awitatpapuri.com/{$year}/{$month}/{$day}/";
            $archiveRes = Http::timeout(15)->withoutVerifying()->withHeaders($this->browserHeaders())->get($archiveUrl);
            if (!$archiveRes->successful()) return [];

            $readingsUrl = $archiveUrl;
            $pattern = '~href=["\']([^"\']*/' . $year . '/' . $month . '/' . $day . '/[^"\']+/)["\']~i';
            if (preg_match($pattern, $archiveRes->body(), $match)) {
                $readingsUrl = $match[1];
                if (str_starts_with($readingsUrl, '/')) {
                    $readingsUrl = 'https://www.awitatpapuri.com' . $readingsUrl;
                }
            }

            $readingsRes = Http::timeout(15)->withoutVerifying()->withHeaders($this->browserHeaders())->get($readingsUrl);
            if (!$readingsRes->successful()) return [];

            $parsedPage = $this->parseAwitAtPapuriPage($readingsRes->body(), $targetDate, $lang);

            return $lang === 'en'
                ? $this->normalizeEnglishReadings($parsedPage)
                : $this->normalizeTagalogReadings($parsedPage);

        } catch (\Exception $e) {
            Log::warning('Failed to fetch from Awit at Papuri: ' . $e->getMessage());
            return [];
        }
    }

private function parseAwitAtPapuriPage(string $html, Carbon $targetDate, string $lang): array
{
    $dom = new \DOMDocument();
    @$dom->loadHTML($html);
    $xpath = new \DOMXPath($dom);

    $candidates = $lang === 'tl'
        ? ['tl', 'tagalog', 'filipino', 'fil']
        : ['en', 'english'];

    $contentNode = null;
    foreach ($candidates as $cls) {
        $contentNode = $xpath->query("//div[contains(@class, 'entry-content')]//div[contains(@class, '{$cls}')]")->item(0);
        if ($contentNode) break;
    }
    if (!$contentNode) {
        $contentNode = $xpath->query("//div[contains(@class, 'entry-content')]")->item(0);
    }
    if (!$contentNode) return [];

    $text = $this->getNodeText($contentNode);
    $text = preg_replace('/[ \t]+/', ' ', $text);
    $text = preg_replace('/(\s*\n\s*){2,}/', "\n\n", $text);

    // Normalize section headers to uppercase on their own line
    $allHeaders = $lang === 'tl'
        ? ['UNANG PAGBASA', 'IKALAWANG PAGBASA', 'SALMONG TUGUNAN', 'ALELUYA', 'MABUTING BALITA']
        : ['FIRST READING', 'SECOND READING', 'RESPONSORIAL PSALM', 'ALLELUIA', 'GOSPEL ACCLAMATION', 'GOSPEL'];

    foreach ($allHeaders as $header) {
        // Match header anywhere on its own line (case-insensitive), ensure it's on its own line
        $text = preg_replace(
            '/(?:^|\n)\s*(' . preg_quote($header, '/') . ')\s*(?:\n|$)/iu',
            "\n\n" . strtoupper($header) . "\n\n",
            $text
        );
    }

    // Ensure double newlines between all sections
    $text = preg_replace('/\n{3,}/', "\n\n", $text);

    $title = $this->firstMatch($text, '/(Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday).+?(?=\n)/u') ?: 'Daily Readings';
    $dateDisplayed = $this->firstMatch($text, '/(Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday),\s+[A-Za-zñÑ]+ \d{1,2}, \d{4}/u')
        ?: $targetDate->translatedFormat('l, F j, Y');

    // Trim everything before the first section header
    $firstHeader = $lang === 'tl' ? 'UNANG PAGBASA' : 'FIRST READING';
    $start = strpos($text, "\n\n" . $firstHeader . "\n");
    if ($start !== false) {
        $text = substr($text, $start);
    }

    $readings = [];

    if ($lang === 'en') {
        if ($v = $this->sectionBetweenExact($text, 'FIRST READING', 'RESPONSORIAL PSALM'))
            $readings[] = $this->makeReading('First Reading', $v, 'en');

        if ($v = $this->sectionBetweenExact($text, 'RESPONSORIAL PSALM', 'ALLELUIA'))
            $readings[] = $this->makeReading('Responsorial Psalm', $v, 'en');

        if ($v = $this->sectionBetweenMultiEnd($text, 'ALLELUIA', ['GOSPEL ACCLAMATION', 'GOSPEL']))
            $readings[] = $this->makeReading('Alleluia', $v, 'en');

        if ($v = $this->sectionAfterExact($text, 'GOSPEL', ['PRAYER OF THE FAITHFUL', 'PANALANGIN', 'Pages:']))
            $readings[] = $this->makeReading('Gospel', $v, 'en');

    } else {
        if ($v = $this->sectionBetweenExact($text, 'UNANG PAGBASA', 'SALMONG TUGUNAN'))
            $readings[] = $this->makeReading('Unang Pagbasa', $v, 'tl');

        if ($v = $this->sectionBetweenExact($text, 'SALMONG TUGUNAN', 'ALELUYA'))
            $readings[] = $this->makeReading('Salmong Tugunan', $v, 'tl');

        if ($v = $this->sectionBetweenExact($text, 'ALELUYA', 'MABUTING BALITA'))
            $readings[] = $this->makeReading('Aleluya', $v, 'tl');

        if ($v = $this->sectionAfterExact($text, 'MABUTING BALITA', ['PANALANGIN NG BAYAN', 'Pages:', 'Mga Pagbasa']))
            $readings[] = $this->makeReading('Mabuting Balita', $v, 'tl');
    }

    return [
        'title'          => $title,
        'date_displayed' => $dateDisplayed,
        'readings'       => $readings,
    ];
}

/**
 * Match headers that are on their OWN LINE (double-newline delimited).
 * Falls back to case-insensitive search if strict match fails.
 */
private function sectionBetweenExact(string $text, string $start, string $end): string
{
    $startPattern = "\n\n" . strtoupper($start) . "\n";
    $endPattern   = "\n\n" . strtoupper($end) . "\n";

    $startPos = strpos($text, $startPattern);
    if ($startPos === false) {
        // Fallback: case-insensitive search for header on its own line
        $startPos = preg_match('/(?:^|\n)\s*' . preg_quote(strtoupper($start), '/') . '\s*\n/im', $text, $m, PREG_OFFSET_CAPTURE);
        if (!$startPos) return '';
        $startPos = $m[0][1] + strlen($m[0][0]);
    } else {
        $startPos += strlen($startPattern);
    }

    $endPos = strpos($text, $endPattern, $startPos);
    if ($endPos === false) {
        // Fallback: case-insensitive search for end header
        $endMatch = preg_match('/(?:^|\n)\s*' . preg_quote(strtoupper($end), '/') . '\s*\n/im', $text, $m, PREG_OFFSET_CAPTURE, $startPos);
        $endPos = $endMatch ? $m[0][1] : false;
    }

    return trim($endPos === false ? substr($text, $startPos) : substr($text, $startPos, $endPos - $startPos));
}

private function sectionAfterExact(string $text, string $start, array $stops = []): string
{
    $startPattern = "\n\n" . strtoupper($start) . "\n";
    $startPos = strpos($text, $startPattern);
    if ($startPos === false) {
        // Fallback: case-insensitive search
        $found = preg_match('/(?:^|\n)\s*' . preg_quote(strtoupper($start), '/') . '\s*\n/im', $text, $m, PREG_OFFSET_CAPTURE);
        if (!$found) return '';
        $startPos = $m[0][1] + strlen($m[0][0]);
    } else {
        $startPos += strlen($startPattern);
    }

    $section = substr($text, $startPos);

    $cutAt = null;
    foreach ($stops as $stop) {
        $pos = stripos($section, $stop);
        if ($pos !== false && ($cutAt === null || $pos < $cutAt)) {
            $cutAt = $pos;
        }
    }

    return trim($cutAt !== null ? substr($section, 0, $cutAt) : $section);
}
    private function getNodeText(\DOMNode $node): string
    {
        $text = '';
        foreach ($node->childNodes as $child) {
            if ($child->nodeType === XML_TEXT_NODE) {
                $text .= $child->nodeValue;
            } elseif ($child->nodeType === XML_ELEMENT_NODE) {
                $tag = strtolower($child->nodeName);
                if ($tag === 'br' || $tag === 'p') $text .= "\n";
                $text .= $this->getNodeText($child);
                if ($tag === 'p') $text .= "\n";
            }
        }
        return trim(preg_replace('/(\s*\n\s*){2,}/', "\n\n", $text));
    }

    private function normalizeEnglishReadings(array $data): array
    {
        if (empty($data['readings'])) return [];

        $types     = array_map(fn ($r) => Str::lower($r['type'] ?? ''), $data['readings']);
        $hasFirst  = collect($types)->contains(fn ($t) => str_contains($t, 'first') || str_contains($t, 'reading'));
        $hasGospel = collect($types)->contains(fn ($t) => str_contains($t, 'gospel'));

        if (!$hasFirst || !$hasGospel) return [];

        $data['readings'] = array_values(array_filter(
            $data['readings'],
            fn ($r) => !empty(trim($r['text'] ?? ''))
        ));

        return $data;
    }

    private function normalizeTagalogReadings(array $data): array
    {
        if (empty($data['readings'])) return [];

        $types     = array_map(fn ($r) => Str::lower($r['type'] ?? ''), $data['readings']);
        $hasFirst  = collect($types)->contains(fn ($t) => str_contains($t, 'unang') || str_contains($t, 'pagbasa'));
        $hasGospel = collect($types)->contains(fn ($t) => str_contains($t, 'mabuting') || str_contains($t, 'balita'));

        if (!$hasFirst || !$hasGospel) return [];

        $data['readings'] = array_values(array_filter(
            $data['readings'],
            fn ($r) => !empty(trim($r['text'] ?? ''))
        ));

        if (empty($data['readings'])) return [];

        return $data;
    }

    private function makeReading(string $type, string $section, string $lang = 'en'): array
    {
        $lines     = array_values(array_filter(array_map('trim', preg_split("/\n+/", trim($section)))));
        $reference = '';

        if (!empty($lines[0])) {
            $enPattern = '/\d|Gen|Ex|Lev|Num|Deut|Josh|Judg|Ruth|Sam|Kgs|Chr|Ezra|Neh|Tob|Jdt|Esth|Macc|Job|Ps|Prov|Eccl|Song|Wis|Sir|Isa|Jer|Lam|Bar|Ezek|Dan|Hos|Joel|Amos|Obad|Jon|Mic|Nah|Hab|Zeph|Hag|Zech|Mal|Matt|Mark|Luke|John|Acts|Rom|Cor|Gal|Eph|Phil|Col|Thess|Tim|Tit|Phlm|Heb|Jas|Pet|Jn|Jude|Rev/';
            $tlPattern = '/\d|Salmo|Juan|Lucas|Marcos|Mateo|Gawa|Roma|Corinto|Pedro|Santiago|Hebreo|Galata|Efeso|Filipos|Colosas|Tesaloni|Timoteo|Tito|Filemon|Pahayag/u';
            $pattern   = $lang === 'tl' ? $tlPattern : $enPattern;

            if (preg_match($pattern, $lines[0])) {
                $reference = array_shift($lines);
            }
        }

        return [
            'type'      => $type,
            'reference' => $reference,
            'text'      => trim(implode("\n", $lines)),
        ];
    }

    private function fetchWithAi(Carbon $targetDate, string $language): array
    {
        $displayDate = $targetDate->format('l, F j, Y');
        $dateStr     = $targetDate->format('Ymd');
        $rawText     = '';
        $sourceUsed  = '';

        if ($language === 'TG') {
            foreach (['TG', 'FI'] as $evLang) {
                try {
                    $res = Http::timeout(15)->withoutVerifying()->get('https://feed.evangelizo.org/v2/reader.php', [
                        'date' => $dateStr,
                        'lang' => $evLang,
                        'type' => 'all',
                    ]);
                    if ($res->successful() && !empty(trim($res->body()))) {
                        $rawText    = $res->body();
                        $sourceUsed = "Evangelizo ({$evLang})";
                        break;
                    }
                } catch (\Exception $e) {
                    Log::warning("Evangelizo {$evLang} fetch failed: " . $e->getMessage());
                }
            }
        }

        if (empty($rawText) && $language === 'EN') {
            try {
                $usccbDate = $targetDate->format('mdy');
                $response  = Http::timeout(10)->withoutVerifying()->withHeaders($this->browserHeaders())
                    ->get("https://bible.usccb.org/bible/readings/{$usccbDate}.cfm");

                if ($response->successful() && strpos($response->body(), 'Verify you are human') === false) {
                    $rawText    = $response->body();
                    $sourceUsed = 'USCCB';
                }
            } catch (\Exception $e) {
                Log::warning('Failed to fetch from USCCB: ' . $e->getMessage());
            }
        }

        if (empty($rawText)) {
            try {
                $response = Http::timeout(15)->withoutVerifying()->get('https://feed.evangelizo.org/v2/reader.php', [
                    'date' => $dateStr,
                    'lang' => 'AM',
                    'type' => 'all',
                ]);
                if ($response->successful()) {
                    $rawText    = $response->body();
                    $sourceUsed = 'Evangelizo (EN)';
                }
            } catch (\Exception $e) {
                Log::warning('Evangelizo EN fallback failed: ' . $e->getMessage());
            }
        }

        if (empty($rawText)) throw new \Exception('Daily readings could not be retrieved from any source.');

        $cleanRawText = str_ireplace(['<br>', '<br/>', '<br />', '</p>', '</div>'], "\n", $rawText);
        $cleanRawText = strip_tags($cleanRawText);
        $cleanRawText = html_entity_decode($cleanRawText, ENT_QUOTES, 'UTF-8');
        $cleanRawText = preg_replace('/[ \t]+/', ' ', $cleanRawText);
        $cleanRawText = preg_replace("/\n{3,}/", "\n\n", $cleanRawText);
        $cleanRawText = substr($cleanRawText, 0, 12000);

        $langInstruction = $language === 'TG'
            ? 'Translate ALL reading texts and labels to Filipino/Tagalog. Use these exact type labels: "Unang Pagbasa", "Salmong Tugunan", "Aleluya", "Mabuting Balita". Preserve all R. and A. response markers.'
            : 'Keep all texts in English. Use these exact type labels: "First Reading", "Responsorial Psalm", "Alleluia", "Gospel".';

        $systemPrompt = "You are a Catholic liturgical assistant. Parse raw Catholic daily Mass readings into strict JSON only. No markdown, no explanation — only the JSON object. Preserve line breaks using \\n. {$langInstruction} Required JSON format: {\"title\":\"\",\"date_displayed\":\"\",\"readings\":[{\"type\":\"\",\"reference\":\"\",\"text\":\"\"}]}";

        $aiResponse = $this->aiService->getResponse([
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => "Raw Readings for {$displayDate} (Language: {$language}, Source: {$sourceUsed}):\n\n" . $cleanRawText],
        ]);

        $jsonText  = trim($aiResponse);
        $jsonStart = strpos($jsonText, '{');
        $jsonEnd   = strrpos($jsonText, '}');

        if ($jsonStart !== false && $jsonEnd !== false) {
            $jsonText = substr($jsonText, $jsonStart, $jsonEnd - $jsonStart + 1);
        }

        $parsed = json_decode($jsonText, true);
        if (json_last_error() !== JSON_ERROR_NONE || !isset($parsed['readings'])) {
            throw new \Exception('Failed to parse daily readings JSON from AI response.');
        }

        foreach ($parsed['readings'] as &$reading) {
            if (isset($reading['text'])) {
                $reading['text'] = str_replace(['\n', '\"', "\'"], ["\n", '"', "'"], $reading['text']);
            }
        }
        unset($reading);

        return $language === 'TG'
            ? $this->normalizeTagalogReadings($parsed)
            : $this->normalizeEnglishReadings($parsed);
    }

    // ─── Section Helpers ────────────────────────────────────────────────────────

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

    /**
     * sectionBetween but tries multiple end markers — uses earliest match.
     */
    private function sectionBetweenMultiEnd(string $text, string $start, array $ends): string
    {
        $startPos = stripos($text, $start);
        if ($startPos === false) return '';
        $startPos += strlen($start);

        $endPos = null;
        foreach ($ends as $end) {
            $pos = stripos($text, $end, $startPos);
            if ($pos !== false && ($endPos === null || $pos < $endPos)) {
                $endPos = $pos;
            }
        }

        return trim($endPos !== null
            ? substr($text, $startPos, $endPos - $startPos)
            : substr($text, $startPos));
    }

    /**
     * sectionAfter but hard-stops at first match of any stop string.
     * Prevents footer/nav content bleeding into Gospel/Mabuting Balita.
     */
    private function sectionAfterWithStop(string $text, string $start, array $stops): string
    {
        $startPos = stripos($text, $start);
        if ($startPos === false) return '';
        $startPos += strlen($start);

        $section = substr($text, $startPos);

        $cutAt = null;
        foreach ($stops as $stop) {
            $pos = stripos($section, $stop);
            if ($pos !== false && ($cutAt === null || $pos < $cutAt)) {
                $cutAt = $pos;
            }
        }

        return trim($cutAt !== null ? substr($section, 0, $cutAt) : $section);
    }

    private function firstMatch(string $text, string $pattern): string
    {
        return preg_match($pattern, $text, $match) ? trim($match[0]) : '';
    }

    private function browserHeaders(): array
    {
        return [
            'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
            'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Language' => 'en-US,en;q=0.5',
        ];
    }
}