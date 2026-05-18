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
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function __invoke(Request $request)
    {
        $language = Str::upper($request->query('language', 'EN'));
        $language = in_array($language, ['EN', 'TG'], true) ? $language : 'EN';

        $date = now('Asia/Manila')->format('Ymd');
        $displayDate = now('Asia/Manila')->format('l, F j, Y');

        try {
            $data = Cache::remember(
                "daily-readings:{$date}:{$language}",
                now('Asia/Manila')->endOfDay(),
                function () use ($date, $displayDate, $language) {
                    // Fetch English raw readings from the official Evangelizo feed
                    $response = Http::timeout(15)
                        ->get('http://feed.evangelizo.org/v2/reader.php', [
                            'date' => $date,
                            'lang' => 'AM',
                            'type' => 'all',
                        ]);

                    if (!$response->successful()) {
                        throw new \Exception('Failed to fetch daily readings from source. Status code: ' . $response->status());
                    }

                    $rawText = $response->body();

                    // Define the parsing/translation system prompt for AI
                    if ($language === 'TG') {
                        $systemPrompt = "You are an expert Catholic liturgical assistant and translator. "
                            . "Parse and translate the following raw English daily readings to formal, beautiful Tagalog (Filipino) "
                            . "as used in the Philippine Catholic Mass (Aklat ng Pagmimisa sa Roma / Awit at Papuri).\n"
                            . "Format the output as a strict JSON object. Do not include markdown code block formatting (like ```json), explanations, or any text other than the JSON.\n\n"
                            . "Required JSON Structure:\n"
                            . "{\n"
                            . "  \"title\": \"Liturgical title in Tagalog (e.g. Martes ng Ikapitong Linggo ng Pasko ng Pagkabuhay)\",\n"
                            . "  \"date_displayed\": \"Date in Tagalog (e.g. Martes, Mayo 19, 2026)\",\n"
                            . "  \"readings\": [\n"
                            . "    {\n"
                            . "      \"type\": \"Reading type in Tagalog (e.g. Unang Pagbasa, Salmong Tugunan, Ikalawang Pagbasa, Mabuting Balita)\",\n"
                            . "      \"reference\": \"Scripture reference (e.g. Gawa 20:17-27)\",\n"
                            . "      \"text\": \"Full text of the reading in Tagalog\"\n"
                            . "    }\n"
                            . "  ]\n"
                            . "}";
                    } else {
                        $systemPrompt = "You are an expert Catholic liturgical assistant. "
                            . "Parse the following raw English daily readings into a clean, structured JSON object. "
                            . "Clean up HTML tags, decode entities, and structure the readings beautifully.\n"
                            . "Format the output as a strict JSON object. Do not include markdown code block formatting (like ```json), explanations, or any text other than the JSON.\n\n"
                            . "Required JSON Structure:\n"
                            . "{\n"
                            . "  \"title\": \"Liturgy title (e.g. Tuesday of the Seventh week of Easter)\",\n"
                            . "  \"date_displayed\": \"Date (e.g. Tuesday, May 19, 2026)\",\n"
                            . "  \"readings\": [\n"
                            . "    {\n"
                            . "      \"type\": \"Reading type (e.g. First Reading, Responsorial Psalm, Second Reading, Gospel)\",\n"
                            . "      \"reference\": \"Scripture reference (e.g. Acts 20:17-27)\",\n"
                            . "      \"text\": \"Full text of the reading\"\n"
                            . "    }\n"
                            . "  ]\n"
                            . "}";
                    }

                    $messages = [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => "Raw Readings Feed for Date {$displayDate}:\n\n" . $rawText]
                    ];

                    $aiResponse = $this->aiService->getResponse($messages);

                    // Clean and extract the JSON object using boundary matching
                    $jsonText = trim($aiResponse);
                    
                    $jsonStart = strpos($jsonText, '{');
                    $jsonEnd = strrpos($jsonText, '}');
                    
                    if ($jsonStart !== false && $jsonEnd !== false) {
                        $jsonText = substr($jsonText, $jsonStart, $jsonEnd - $jsonStart + 1);
                    }

                    $parsedData = json_decode($jsonText, true);

                    if (json_last_error() !== JSON_ERROR_NONE || !isset($parsedData['readings'])) {
                        Log::error('Failed to parse daily readings JSON from AI response: ' . json_last_error_msg());
                        Log::error('Raw AI response was: ' . $aiResponse);
                        throw new \Exception('Failed to parse daily readings JSON from AI response: ' . json_last_error_msg());
                    }

                    return $parsedData;
                }
            );

            return response()->json($data);

        } catch (\Exception $e) {
            Log::error('Daily Readings Controller error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Readings unavailable. Please try again later.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
