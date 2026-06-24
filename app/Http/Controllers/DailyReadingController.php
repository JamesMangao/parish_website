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
            $readingsArr = $record->readings ?? [];
            $readingCount = is_array($readingsArr) ? count($readingsArr) : 0;

            if ($readingCount >= 3) {
                return [
                    'title'          => $record->title,
                    'date_displayed' => $record->date_displayed,
                    'readings'       => $record->readings,
                ];
            }

            $record->delete();
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
            $url = "https://bible.usccb.org/bible/readings/{$mdy}.cfm";
            $agent = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Safari/605.1.15';

            $html = $this->fetchWithObolus($url, $agent);
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
        $text = strip_tags($html);
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        $text = preg_replace('/[ \t]+/', ' ', $text);
        $text = preg_replace('/\s*\n\s*/', "\n", $text);

        $title = $this->firstMatch($text, '/(Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday)[^\n]+/u')
            ?: 'Daily Mass Readings';
        $dateDisplayed = $targetDate->format('l, F j, Y');

        $readings = [];

        if ($v = $this->sectionBetween($text, 'Reading I', 'Responsorial Psalm'))
            $readings[] = $this->makeReading('First Reading', $v, 'en');

        if ($v = $this->sectionBetween($text, 'Responsorial Psalm', 'Reading II'))
            $readings[] = $this->makeReading('Responsorial Psalm', $v, 'en');

        if ($v = $this->sectionBetween($text, 'Reading II', 'Alleluia'))
            $readings[] = $this->makeReading('Second Reading', $v, 'en');

        if ($v = $this->sectionBetween($text, 'Alleluia', 'Gospel'))
            $readings[] = $this->makeReading('Alleluia', $v, 'en');

        if ($v = $this->sectionAfter($text, 'Gospel', ['Prayer of the Faithful', 'Copyright', '©']))
            $readings[] = $this->makeReading('Gospel', $v, 'en');

        if (empty($readings)) {
            return [];
        }

        return [
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
        $html = strip_tags($html, '');
        $html = preg_replace('/[ \t]+/', ' ', $html);
        $html = preg_replace('/\n{3,}/', "\n\n", $html);

        $title = $this->firstMatch($html, '/^[^\n]+/u') ?: 'Daily Mass Readings';
        $dateDisplayed = $targetDate->format('l, F j, Y');

        $sections = array_filter(array_map('trim', preg_split("/\n\n+/", $html)));
        if (empty($sections)) {
            return [];
        }

        $readings = [];
        $enTypes = ['First Reading', 'Responsorial Psalm', 'Second Reading', 'Alleluia', 'Gospel'];
        $tlTypes = ['Unang Pagbasa', 'Salmong Tugunan', 'Ikalawang Pagbasa', 'Aleluya', 'Mabuting Balita'];
        $types = $language === 'TG' ? $tlTypes : $enTypes;

        $i = 0;
        foreach ($sections as $section) {
            if ($i >= count($types)) break;
            if (strlen($section) < 20) continue;

            $lines = array_values(array_filter(array_map('trim', preg_split("/\n+/", $section))));
            $reference = '';

            if (!empty($lines[0])) {
                $refPattern = '/^(?:(?:Book of |First book of |2nd book of |Acts of the Apostles |Psalms |Holy Gospel)[^\n]*|[A-Z][a-z]+ \d)/u';
                if (preg_match($refPattern, $lines[0])) {
                    $reference = array_shift($lines);
                }
            }

            $readings[] = [
                'type'      => $types[$i],
                'reference' => $reference,
                'text'      => trim(implode("\n", $lines)),
            ];
            $i++;
        }

        $readings = array_values(array_filter($readings, fn ($r) => !empty($r['text'])));

        return empty($readings) ? [] : [
            'title'          => $title,
            'date_displayed' => $dateDisplayed,
            'readings'       => $readings,
        ];
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

        $allHeaders = ['UNANG PAGBASA', 'IKALAWANG PAGBASA', 'SALMONG TUGUNAN', 'ALELUYA', 'MABUTING BALITA'];

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

        $readings = [];

        if ($v = $this->sectionBetweenExact($text, 'UNANG PAGBASA', 'SALMONG TUGUNAN'))
            $readings[] = $this->makeReading('Unang Pagbasa', $v, 'tl');

        if ($v = $this->sectionBetweenExact($text, 'SALMONG TUGUNAN', 'IKALAWANG PAGBASA'))
            $readings[] = $this->makeReading('Salmong Tugunan', $v, 'tl');

        if ($v = $this->sectionBetweenExact($text, 'IKALAWANG PAGBASA', 'ALELUYA'))
            $readings[] = $this->makeReading('Ikalawang Pagbasa', $v, 'tl');

        if ($v = $this->sectionBetweenExact($text, 'ALELUYA', 'MABUTING BALITA'))
            $readings[] = $this->makeReading('Aleluya', $v, 'tl');

        if ($v = $this->sectionAfterExact($text, 'MABUTING BALITA', ['PANALANGIN NG BAYAN', 'Pages:', 'Mga Pagbasa']))
            $readings[] = $this->makeReading('Mabuting Balita', $v, 'tl');

        return [
            'title'          => $title,
            'date_displayed' => $dateDisplayed,
            'readings'       => $readings,
        ];
    }

    // ─── Shared helpers ──────────────────────────────────────────────────

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
