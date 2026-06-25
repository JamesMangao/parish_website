<?php

namespace App\Http\Controllers;

use App\Models\DailyReading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DailyReadingController extends Controller
{
    public function __invoke(Request $request)
    {
        $language = Str::upper($request->query('language', 'EN'));
        $language = in_array($language, ['EN', 'TG'], true) ? $language : 'EN';
        $date = now('Asia/Manila')->format('Ymd');
        $force = $request->boolean('refresh');

        try {
            if ($force) {
                Cache::forget("daily-readings:db:{$date}:{$language}");
                DailyReading::withTrashed()
                    ->where('date', $date)
                    ->where('language', $language)
                    ->forceDelete();
            }

            $data = Cache::remember(
                "daily-readings:db:{$date}:{$language}",
                now('Asia/Manila')->endOfDay(),
                fn () => $this->getOrFetchReadings($date, $language)
            );

            if ($this->isStaleReadingData($data['readings'] ?? [], $language)) {
                Cache::forget("daily-readings:db:{$date}:{$language}");
                $data = Cache::remember(
                    "daily-readings:db:{$date}:{$language}",
                    now('Asia/Manila')->endOfDay(),
                    fn () => $this->getOrFetchReadings($date, $language)
                );
            }

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
            $readingsArr = $record->readings ?? [];
            $readingCount = is_array($readingsArr) ? count($readingsArr) : 0;

            if ($readingCount >= 3 && !$this->isStaleReadingData($readingsArr, $language)) {
                return [
                    'title'          => $record->title,
                    'date_displayed' => $record->date_displayed,
                    'readings'       => $record->readings,
                ];
            }

            $record->forceDelete();
        }

        $dateObj = Carbon::createFromFormat('Ymd', $date, 'Asia/Manila');
        $data = [];

        if ($language === 'TG') {
            $data = $this->fetchAwitAtPapuriReadings($dateObj);
        } else {
            $data = $this->fetchUsccbReadings($dateObj);
            if (empty($data['readings'])) {
                $data = $this->fetchEvangelizoReadings($dateObj, 'EN');
            }
        }

        if (empty($data) || empty($data['readings'])) {
            throw new \Exception("Could not retrieve valid readings for {$date} ({$language})");
        }

        DailyReading::withTrashed()
            ->where('date', $date)
            ->where('language', $language)
            ->forceDelete();

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

    // ─── USCCB (English) ────────────────────────────────────────────────

    private function fetchUsccbReadings(Carbon $targetDate): array
    {
        try {
            $mdy = $targetDate->format('mdy');
            $agent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Safari/605.1.15';

            $mdUrl = "https://bible.usccb.org/bible/readings/{$mdy}.cfm.md";
            $md = $this->httpGet($mdUrl, $agent);
            if ($md && stripos($md, '### Reading') !== false && preg_match('/^##\s/m', $md)) {
                return $this->parseUsccbMarkdown($md, $targetDate);
            }

            $html = $this->fetchWithObolus("https://bible.usccb.org/bible/readings/{$mdy}.cfm", $agent);
            if (!$html || stripos($html, 'Checking connection') !== false) {
                return [];
            }

            return $this->parseUsccbPage($html, $targetDate);
        } catch (\Exception $e) {
            Log::warning('USCCB fetch failed: ' . $e->getMessage());
            return [];
        }
    }

    private function fetchWithObolus(string $url, string $agent): string
    {
        $html = $this->httpGet($url, $agent);
        if (!$html || stripos($html, 'Checking connection') === false) {
            return $html ?: '';
        }

        $nonce = $this->extractObolusParam($html, '/nonce\s*:\s*[\'"]([a-f0-9]+)[\'"]/');
        $token = $this->extractObolusParam($html, '/challengeToken\s*:\s*[\'"]([a-f0-9]+)[\'"]/');
        $timestamp = $this->extractObolusParam($html, '/challengeTimestamp\s*:\s*[\'"](\d+)[\'"]/');
        $difficulty = (int) $this->extractObolusParam($html, '/difficulty\s*:\s*[\'"](\d+)[\'"]/');

        if (!$nonce || !$token || !$timestamp || $difficulty < 10) {
            return '';
        }

        $solution = $this->solveObolusPow($nonce, $difficulty);
        if (!$solution) {
            return '';
        }

        $proof = "{$timestamp}:{$nonce}:{$token}:{$solution['elapsed']}:{$solution['miningNonce']}";
        return $this->httpGet($url, $agent, $proof);
    }

    private function solveObolusPow(string $nonce, int $difficulty): ?array
    {
        $miningNonce = 0;
        $maxAttempts = 1 << ($difficulty + 2);
        $startTime = microtime(true);

        while ($miningNonce < $maxAttempts) {
            $hash = hash('sha256', $nonce . ':mine:' . $miningNonce);
            if ($this->countLeadingZeroBits($hash) >= $difficulty) {
                return [
                    'miningNonce' => $miningNonce,
                    'hash'        => $hash,
                    'elapsed'     => (int) round((microtime(true) - $startTime) * 1000),
                ];
            }
            $miningNonce++;
        }

        return null;
    }

    private function countLeadingZeroBits(string $hex): int
    {
        $count = 0;
        for ($i = 0; $i < strlen($hex); $i++) {
            $digit = hexdec($hex[$i]);
            if ($digit === 0) {
                $count += 4;
            } else {
                $count += (4 - (int) ceil(log($digit + 1, 2)));
                break;
            }
        }
        return $count;
    }

    private function extractObolusParam(string $html, string $pattern): ?string
    {
        return preg_match($pattern, $html, $m) ? $m[1] : null;
    }

    private function parseUsccbPage(string $html, Carbon $targetDate): array
    {
        $html = preg_replace('/<br\s*\/?>/i', "\n", $html);
        $html = preg_replace('/<\/(p|div|h[1-6])>/i', "\n", $html);

        $text = strip_tags($html);
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        $text = preg_replace('/[ \t]+/', ' ', $text);
        $text = preg_replace('/\s*\n\s*/', "\n", $text);

        $text = $this->truncateAtFooters($text, [
            'Lectionary for Mass', 'Get the Daily Readings',
            'About USCCB', 'Dive into God',
        ]);

        $title = $this->firstMatch($text, '/(Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday)[^\n]+/u')
            ?: 'Daily Mass Readings';
        $dateDisplayed = $targetDate->format('l, F j, Y');

        $headingMap = [
            'Reading 1'          => 'First Reading',
            'Reading I'          => 'First Reading',
            'Reading 2'          => 'Second Reading',
            'Reading II'         => 'Second Reading',
            'Responsorial Psalm' => 'Responsorial Psalm',
            'Alleluia'           => 'Alleluia',
            'Gospel'             => 'Gospel',
        ];

        $sections = $this->splitByHeadings($text, $headingMap);

        $readings = [];
        foreach ($sections as $type => $content) {
            $readings[] = $this->makeReading($type, $content, 'en');
        }

        if (empty($readings)) {
            return [];
        }

        return [
            'title'          => $title,
            'date_displayed' => $dateDisplayed,
            'readings'       => $readings,
        ];
    }

    private function parseUsccbMarkdown(string $md, Carbon $targetDate): array
    {
        $md = html_entity_decode($md, ENT_QUOTES, 'UTF-8');
        $md = str_replace("\r\n", "\n", $md);
        $md = preg_replace('/\[([^\]]+)\]\s*\([^)]*\)/', '$1', $md);
        $md = preg_replace('/\*\*([^*]+)\*\*/', '$1', $md);

        $title = $this->firstMatch($md, '/^##\s+((?:Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday)[^\n]+)/mu')
            ?: 'Daily Mass Readings';
        $title = trim($title);
        $dateDisplayed = $targetDate->format('l, F j, Y');

        $headingMap = [
            'Reading 1'          => 'First Reading',
            'Reading I'          => 'First Reading',
            'Reading 2'          => 'Second Reading',
            'Reading II'         => 'Second Reading',
            'Responsorial Psalm' => 'Responsorial Psalm',
            'Alleluia'           => 'Alleluia',
            'Gospel'             => 'Gospel',
        ];

        $pattern = '/^###\s+(' . implode('|', array_map('preg_quote', array_keys($headingMap))) . ')\s*$/mu';
        preg_match_all($pattern, $md, $matches, PREG_OFFSET_CAPTURE);

        if (empty($matches[1])) {
            return [];
        }

        $readings = [];
        for ($i = 0; $i < count($matches[1]); $i++) {
            $heading = $matches[1][$i][0];
            $start = $matches[1][$i][1] + strlen($matches[0][$i][0]);
            $end = ($i + 1 < count($matches[1]))
                ? $matches[1][$i + 1][1]
                : strlen($md);

            $block = trim(substr($md, $start, $end - $start));
            $block = preg_replace('/^\s*\n/m', '', $block);

            $readings[] = $this->makeReading($headingMap[$heading], $block, 'en');
        }

        return empty($readings) ? [] : [
            'title'          => $title,
            'date_displayed' => $dateDisplayed,
            'readings'       => $readings,
        ];
    }

    // ─── Evangelizo (English fallback) ───────────────────────────────────

    private function fetchEvangelizoReadings(Carbon $targetDate, string $language): array
    {
        try {
            $dateStr = $targetDate->format('Ymd');
            $langCode = $language === 'TG' ? 'TL' : 'AM';

            $res = Http::timeout(15)->withoutVerifying()->get('https://feed.evangelizo.org/v2/reader.php', [
                'date' => $dateStr,
                'lang' => $langCode,
                'type' => 'all',
            ]);

            if (!$res->successful() || empty(trim($res->body()))) {
                return [];
            }

            return $this->parseEvangelizoPage($res->body(), $targetDate, $language);
        } catch (\Exception $e) {
            Log::warning("Evangelizo {$language} fetch failed: " . $e->getMessage());
            return [];
        }
    }

    private function parseEvangelizoPage(string $html, Carbon $targetDate, string $language): array
    {
        $html = html_entity_decode($html, ENT_QUOTES, 'UTF-8');
        $html = preg_replace('/<br\s*\/?>/i', "\n", $html);
        $html = strip_tags($html);
        $html = preg_replace('/[ \t]+/', ' ', $html);
        $html = preg_replace('/\n{3,}/', "\n\n", $html);
        $html = trim($html);

        $lines = array_values(array_filter(
            preg_split("/\n+/", $html),
            fn ($l) => trim($l) !== ''
        ));

        if (empty($lines)) return [];

        $title = $lines[0];
        $dateDisplayed = $targetDate->format('l, F j, Y');

        $enRefPattern = '/^(?:\d|Book of |First book of |2nd book of |Acts of the Apostles |Psalms |Holy Gospel |Alleluia\b|A reading from )/u';
        $tlRefPattern = '/^(?:\d|Salmo |Mabuting Balita |Aleluya\b|Unang Pagbasa |Ikalawang Pagbasa |Pagbasa mula sa )/u';
        $refPattern = $language === 'TG' ? $tlRefPattern : $enRefPattern;

        $refIndices = [];
        foreach ($lines as $idx => $line) {
            if ($idx === 0) continue;
            if (preg_match($refPattern, $line)) {
                $refIndices[] = $idx;
            }
        }

        if (empty($refIndices)) return [];

        $readings = [];

        foreach ($refIndices as $i => $refIdx) {
            $reference = $lines[$refIdx];
            $nextRefIdx = ($i + 1 < count($refIndices)) ? $refIndices[$i + 1] : count($lines);
            $textLines = array_slice($lines, $refIdx + 1, $nextRefIdx - $refIdx - 1);
            $text = trim(implode("\n", $textLines));

            $type = $this->detectEvangelizoType($reference, $text, $readings, $language);

            $readings[] = compact('type', 'reference', 'text');
        }

        return empty($readings) ? [] : compact('title', 'dateDisplayed', 'readings');
    }

    private function detectEvangelizoType(string $reference, string $text, array $existingReadings, string $language): string
    {
        if ($language === 'TG') {
            if (preg_match('/Mabuting Balita|Ebanghelyo/i', $reference)) {
                return 'Mabuting Balita';
            }
            if (preg_match('/Salmo/i', $reference)) {
                return 'Salmong Tugunan';
            }
            if (preg_match('/^Aleluya/i', $text)) {
                return 'Aleluya';
            }
            $hasFirst = false;
            foreach ($existingReadings as $r) {
                if ($r['type'] === 'Unang Pagbasa') $hasFirst = true;
            }
            return $hasFirst ? 'Ikalawang Pagbasa' : 'Unang Pagbasa';
        }

        if (preg_match('/Holy Gospel|Gospel according to/i', $reference)) {
            return 'Gospel';
        }
        if (preg_match('/Psalm/i', $reference)) {
            return 'Responsorial Psalm';
        }
        if (preg_match('/^Alleluia/i', $text)) {
            return 'Alleluia';
        }

        $hasFirst = false;
        foreach ($existingReadings as $r) {
            if ($r['type'] === 'First Reading') $hasFirst = true;
        }

        return $hasFirst ? 'Second Reading' : 'First Reading';
    }

    // ─── Awit at Papuri (Filipino) ───────────────────────────────────────

    private function fetchAwitAtPapuriReadings(Carbon $targetDate): array
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

            $parsedPage = $this->parseAwitAtPapuriPage($readingsRes->body(), $targetDate);

            return $this->normalizeReadings($parsedPage);

        } catch (\Exception $e) {
            Log::warning('Failed to fetch from Awit at Papuri: ' . $e->getMessage());
            return [];
        }
    }

    private function parseAwitAtPapuriPage(string $html, Carbon $targetDate): array
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new \DOMXPath($dom);

        $contentNode = null;
        $candidates = ['tl', 'tagalog', 'filipino', 'fil'];
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

        $allHeaders = ['UNANG PAGBASA', 'PANGALAWANG PAGBASA', 'IKALAWANG PAGBASA', 'SALMONG TUGUNAN', 'ALELUYA', 'MABUTING BALITA'];

        foreach ($allHeaders as $header) {
            $text = preg_replace(
                '/(?:^|\n)\s*(' . preg_quote($header, '/') . ')\s*(?:\n|$)/iu',
                "\n\n" . strtoupper($header) . "\n\n",
                $text
            );
        }

        $text = preg_replace('/\n{3,}/', "\n\n", $text);

        $title = $this->firstMatch($text, '/(Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday).+?(?=\n)/u') ?: 'Daily Mass Readings';
        $dateDisplayed = $this->firstMatch($text, '/(Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday),\s+[A-Za-zñÑ]+ \d{1,2}, \d{4}/u')
            ?: $targetDate->translatedFormat('l, F j, Y');

        $firstHeader = 'UNANG PAGBASA';
        $start = strpos($text, "\n\n" . $firstHeader . "\n");
        if ($start !== false) {
            $text = substr($text, $start);
        }

        $text = $this->truncateAtFooters($text, [
            'PANALANGIN NG BAYAN', 'Pages:', 'Mga Pagbasa Kahapon',
            'Mga Pagbasa Bukas',
        ]);

        $headingMap = [
            'UNANG PAGBASA'     => 'Unang Pagbasa',
            'SALMONG TUGUNAN'   => 'Salmong Tugunan',
            'IKALAWANG PAGBASA' => 'Ikalawang Pagbasa',
            'PANGALAWANG PAGBASA' => 'Ikalawang Pagbasa',
            'ALELUYA'           => 'Aleluya',
            'MABUTING BALITA'   => 'Mabuting Balita',
        ];

        $sections = $this->splitByHeadings($text, $headingMap);

        $readings = [];
        foreach ($sections as $type => $content) {
            $readings[] = $this->makeReading($type, $content, 'tl');
        }

        return [
            'title'          => $title,
            'date_displayed' => $dateDisplayed,
            'readings'       => $readings,
        ];
    }

    // ─── Shared helpers ──────────────────────────────────────────────────

    private function isStaleReadingData(array $readings, string $language): bool
    {
        $enFooters = ['Lectionary for Mass', 'Get the Daily Readings', 'About USCCB'];
        $tgFooters = ['PANALANGIN NG BAYAN', 'Mga Pagbasa Kahapon', 'Mga Pagbasa Bukas'];
        $markers = $language === 'TG' ? $tgFooters : $enFooters;

        foreach ($readings as $reading) {
            $text = $reading['text'] ?? '';

            if ($language === 'EN' && !empty($readings[0]['text'] ?? '')) {
                $firstText = $readings[0]['text'];
                $dayPattern = '/^(Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday)\b/i';
                if (preg_match($dayPattern, $firstText)) {
                    return true;
                }
            }

            foreach ($markers as $marker) {
                if (stripos($text, $marker) !== false) {
                    return true;
                }
            }
        }
        return false;
    }

    private function truncateAtFooters(string $text, array $markers): string
    {
        $earliest = false;
        foreach ($markers as $marker) {
            $pos = stripos($text, $marker);
            if ($pos !== false && ($earliest === false || $pos < $earliest)) {
                $earliest = $pos;
            }
        }
        return $earliest !== false ? trim(substr($text, 0, $earliest)) : $text;
    }

    private function splitByHeadings(string $text, array $headingMap): array
    {
        $found = [];
        foreach (array_keys($headingMap) as $heading) {
            $pos = stripos($text, $heading);
            if ($pos !== false) {
                $found[$heading] = $pos;
            }
        }

        if (empty($found)) return [];

        uasort($found, fn($a, $b) => $a - $b);

        $result = [];
        $headings = array_keys($found);

        for ($i = 0; $i < count($headings); $i++) {
            $type = $headingMap[$headings[$i]];
            if (isset($result[$type])) continue;

            $startPos = $found[$headings[$i]] + strlen($headings[$i]);
            $endPos = ($i + 1 < count($headings))
                ? $found[$headings[$i + 1]]
                : strlen($text);

            $content = trim(substr($text, $startPos, $endPos - $startPos));
            if (!empty($content)) {
                $result[$type] = $content;
            }
        }

        return $result;
    }

    private function httpGet(string $url, string $agent, ?string $proofCookie = null): string
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_USERAGENT      => $agent,
            CURLOPT_FOLLOWLOCATION => true,
        ]);

        if ($proofCookie) {
            curl_setopt($ch, CURLOPT_COOKIE, "X_Obolus_Proof={$proofCookie}");
        }

        $result = curl_exec($ch);
        curl_close($ch);

        return $result ?: '';
    }

    private function normalizeReadings(array $data): array
    {
        if (empty($data['readings'])) return [];

        $data['readings'] = array_values(array_filter(
            $data['readings'],
            fn ($r) => !empty(trim($r['text'] ?? ''))
        ));

        return !empty($data['readings']) ? $data : [];
    }

    private function makeReading(string $type, string $section, string $lang = 'en'): array
    {
        $lines     = array_values(array_filter(array_map('trim', preg_split("/\n+/", trim($section)))));
        $reference = '';

        if (!empty($lines[0])) {
            $enPattern = '/^\d|Gen|Ex|Lev|Num|Deut|Josh|Judg|Ruth|Sam|Kgs|Chr|Ezra|Neh|Tob|Jdt|Esth|Macc|Job|Ps|Prov|Eccl|Song|Wis|Sir|Isa|Jer|Lam|Bar|Ezek|Dan|Hos|Joel|Amos|Obad|Jon|Mic|Nah|Hab|Zeph|Hag|Zech|Mal|Matt|Mark|Luke|John|Acts|Rom|Cor|Gal|Eph|Phil|Col|Thess|Tim|Tit|Phlm|Heb|Jas|Pet|Jn|Jude|Rev|Book of|Psalms|Holy Gospel/u';
            $tlPattern = '/^\d|Salmo|Juan|Lucas|Marcos|Mateo|Gawa|Roma|Corinto|Pedro|Santiago|Hebreo|Galata|Efeso|Filipos|Colosas|Tesaloni|Timoteo|Tito|Filemon|Pahayag/u';
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

    private function sectionBetween(string $text, string $start, string $end): string
    {
        $startPos = stripos($text, $start);
        if ($startPos === false) return '';
        $startPos += strlen($start);
        $endPos = stripos($text, $end, $startPos);
        return trim($endPos === false ? substr($text, $startPos) : substr($text, $startPos, $endPos - $startPos));
    }

    private function sectionAfter(string $text, string $start, array $stops = []): string
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

    private function sectionBetweenExact(string $text, string $start, string $end): string
    {
        $startPattern = "\n\n" . strtoupper($start) . "\n";
        $endPattern   = "\n\n" . strtoupper($end) . "\n";

        $startPos = strpos($text, $startPattern);
        if ($startPos === false) {
            $startPos = preg_match('/(?:^|\n)\s*' . preg_quote(strtoupper($start), '/') . '\s*\n/im', $text, $m, PREG_OFFSET_CAPTURE);
            if (!$startPos) return '';
            $startPos = $m[0][1] + strlen($m[0][0]);
        } else {
            $startPos += strlen($startPattern);
        }

        $endPos = strpos($text, $endPattern, $startPos);
        if ($endPos === false) {
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
