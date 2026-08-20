<?php

namespace App\Services;

use App\Models\Announcement;
use App\Models\Event;
use App\Models\MassSchedule;
use App\Models\Setting;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected $groqKey;

    protected $openRouterKey;

    public function __construct()
    {
        $this->groqKey = config('services.groq.key');
        $this->openRouterKey = config('services.openrouter.key');
    }

    /**
     * Get a response from AI, racing OpenRouter + Groq concurrently,
     * then falling back to OpenRouter model 2, then local keyword engine.
     */
    public function getResponse(array $messages)
    {
        $messages = $this->sanitizeMessages($messages);

        if (! collect($messages)->contains('role', 'system')) {
            $context = $this->getParishContext();
            array_unshift($messages, [
                'role' => 'system',
                'content' => $this->getSystemPrompt($context),
            ]);
        }

        $userMessage = '';
        foreach (array_reverse($messages) as $m) {
            if ($m['role'] === 'user') {
                $userMessage = $m['content'];
                break;
            }
        }

        // --- Response caching: only for self-contained first messages (2b) ---
        // Skip caching for short follow-ups like "yes", "how much", "tomorrow"
        // which are context-dependent and could cause wrong cached replies.
        $userCount = collect($messages)->filter(fn ($m) => ($m['role'] ?? '') === 'user')->count();
        $cacheKey = ($userCount <= 1 && strlen($userMessage) >= 15)
            ? 'chatbot_response_'.md5($userMessage)
            : null;

        if ($cacheKey && $cached = Cache::get($cacheKey)) {
            return $cached;
        }

        $aiResponse = null;

        // --- Concurrent pool: race OpenRouter model 1 vs Groq (2a) ---
        if ($this->openRouterKey && $this->groqKey) {
            $pool = Http::pool(function (Pool $pool) use ($messages) {
                $pool->withoutVerifying()->withHeaders([
                    'Authorization' => 'Bearer '.$this->openRouterKey,
                    'HTTP-Referer' => config('app.url'),
                    'X-Title' => config('app.name'),
                    'Content-Type' => 'application/json',
                ])->timeout(8)->post('https://openrouter.ai/api/v1/chat/completions', [
                    'model' => 'google/gemini-2.5-flash',
                    'messages' => $messages,
                    'max_tokens' => 800,
                ]);

                $pool->withoutVerifying()->withHeaders([
                    'Authorization' => 'Bearer '.$this->groqKey,
                    'Content-Type' => 'application/json',
                ])->timeout(8)->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'llama-3.1-8b-instant',
                    'messages' => $messages,
                    'temperature' => 0.7,
                    'max_tokens' => 2000,
                ]);
            });

            if ($pool[0]->successful()) {
                $aiResponse = $pool[0]->json()['choices'][0]['message']['content'];
            } elseif ($pool[1]->successful()) {
                $aiResponse = $pool[1]->json()['choices'][0]['message']['content'];
            } else {
                Log::warning('Concurrent pool failed — OR: '.$pool[0]->status().', Groq: '.$pool[1]->status());
            }
        }

        // --- Sequential fallback for remaining/unpooled providers ---
        if ($aiResponse === null) {
            $providers = [];

            if ($this->openRouterKey) {
                $orModels = ($this->openRouterKey && $this->groqKey)
                    ? ['meta-llama/llama-4-scout']
                    : ['google/gemini-2.5-flash', 'meta-llama/llama-4-scout'];

                foreach ($orModels as $model) {
                    $providers[] = [
                        'label' => 'OpenRouter ('.$model.')',
                        'url' => 'https://openrouter.ai/api/v1/chat/completions',
                        'headers' => [
                            'Authorization' => 'Bearer '.$this->openRouterKey,
                            'HTTP-Referer' => config('app.url'),
                            'X-Title' => config('app.name'),
                            'Content-Type' => 'application/json',
                        ],
                        'payload' => [
                            'model' => $model,
                            'messages' => $messages,
                            'max_tokens' => 800,
                        ],
                    ];
                }
            }

            // Only try Groq sequentially if it wasn't already in the pool
            if ($this->groqKey && ! $this->openRouterKey) {
                $providers[] = [
                    'label' => 'Groq',
                    'url' => 'https://api.groq.com/openai/v1/chat/completions',
                    'headers' => [
                        'Authorization' => 'Bearer '.$this->groqKey,
                        'Content-Type' => 'application/json',
                    ],
                    'payload' => [
                        'model' => 'llama-3.1-8b-instant',
                        'messages' => $messages,
                        'temperature' => 0.7,
                        'max_tokens' => 2000,
                    ],
                ];
            }

            foreach ($providers as $provider) {
                try {
                    $response = Http::withoutVerifying()->withHeaders($provider['headers'])
                        ->timeout(8)->post($provider['url'], $provider['payload']);
                    if ($response->successful()) {
                        $aiResponse = $response->json()['choices'][0]['message']['content'];
                        break;
                    }
                    Log::warning($provider['label'].' failed: '.$response->body());
                } catch (\Exception $e) {
                    Log::error($provider['label'].' error: '.$e->getMessage());
                }
            }
        }

        if ($aiResponse !== null) {
            if ($cacheKey) {
                Cache::put($cacheKey, $aiResponse, 600);
            }

            return $aiResponse;
        }

        return $this->localFallback($userMessage);
    }

    protected function sanitizeMessages(array $messages): array
    {
        $patterns = [
            '/ignore\s+(all\s+)?(previous|prior|above)\s+(instructions|prompts|rules)/i',
            '/you\s+are\s+now\s+(a|an|the)\s+/i',
            '/new\s+instructions?:/i',
            '/system\s*:\s*/i',
            '/override\s+(previous|prior|all)\s+/i',
            '/disregard\s+(previous|prior|all)\s+/i',
            '/forget\s+(previous|prior|all)\s+/i',
            '/act\s+as\s+if\s+you\s+have\s+no\s+(rules|restrictions|guidelines)/i',
            '/pretend\s+you\s+are\s+(a|an|the)\s+/i',
            '/role\s*play\s+as\s+/i',
            '/jailbreak/i',
            '/DAN\s+mode/i',
            '/developer\s+mode/i',
        ];

        return array_map(function ($message) use ($patterns) {
            if (($message['role'] ?? '') !== 'user') {
                return $message;
            }
            $content = $message['content'] ?? '';
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $content)) {
                    $content = '[Message filtered for security]';
                    break;
                }
            }

            return ['role' => 'user', 'content' => trim($content)];
        }, $messages);
    }

    protected function getParishContext(): string
    {
        return Cache::remember('chatbot_parish_context', 300, function () {
            $schedules = MassSchedule::where('is_active', true)->get();
            $announcements = Announcement::where('is_published', true)->orderBy('published_at', 'desc')->take(5)->get();
            $events = Event::where('is_published', true)->where('event_date', '>=', now()->toDateString())->orderBy('event_date', 'asc')->get();

            $ctx = 'CURRENT DATE & TIME: '.now('Asia/Manila')->format('l, F j, Y h:i A')." (Philippine Time)\n\n";

            $ctx .= "### OFFICE HOURS:\n";
            $ctx .= "- Tuesday to Saturday: 6:00 AM – 12:00 NN, 1:30 PM – 6:00 PM\n";
            $ctx .= "- Sunday: 6:00 AM – 12:00 NN, 3:00 PM – 6:00 PM\n";
            $ctx .= "- Monday: Closed\n\n";

            $gcashNumber = Setting::where('key', 'gcash_number')->value('value') ?? '09123456789';
            $gcashName = Setting::where('key', 'gcash_name')->value('value') ?? 'Sto. Rosario Parish';
            $priestName = Setting::where('key', 'priest_name')->value('value') ?? 'Rev. Fr. Parish Priest';
            $assistantPriestName = Setting::where('key', 'assistant_priest_name')->value('value');
            $ctx .= "### DONATION INFO:\n";
            $ctx .= "- GCash Number: {$gcashNumber} (Account Name: {$gcashName})\n";
            $ctx .= "- Donations are voluntary; used for parish operations and outreach.\n\n";

            $ctx .= "### ACTIVE MASS SCHEDULES:\n";
            if ($schedules->isEmpty()) {
                $ctx .= "No active schedules found.\n";
            }
            foreach ($schedules as $s) {
                $days = is_array($s->day_of_week) ? implode(', ', $s->day_of_week) : $s->day_of_week;
                $times = is_array($s->time) ? implode(', ', $s->time) : $s->time;
                $ctx .= "- {$s->title} ({$s->mass_type}): {$days} at {$times} [{$s->location}]\n";
            }

            $ctx .= "\n### RECENT ANNOUNCEMENTS:\n";
            if ($announcements->isEmpty()) {
                $ctx .= "No recent announcements.\n";
            }
            foreach ($announcements as $a) {
                $ctx .= "- {$a->title}: ".strip_tags($a->content).' (Published: '.($a->published_at ? $a->published_at->format('M d, Y') : 'N/A').")\n";
            }

            $ctx .= "\n### UPCOMING EVENTS & PARISH ACTIVITIES:\n";
            if ($events->isEmpty()) {
                $ctx .= "No upcoming events.\n";
            }
            foreach ($events as $e) {
                $eTimes = [];
                if (is_array($e->event_time)) {
                    foreach ($e->event_time as $t) {
                        $timePart = $t['time'] ?? '';
                        $titlePart = $t['title'] ?? '';
                        $combined = trim($timePart.($titlePart ? " ($titlePart)" : ''));
                        if ($combined) {
                            $eTimes[] = $combined;
                        }
                    }
                }
                $eTimeStr = ! empty($eTimes) ? implode(', ', $eTimes) : (is_string($e->event_time) ? $e->event_time : 'N/A');
                $ctx .= "- {$e->title} on ".($e->event_date ? $e->event_date->format('M d, Y') : 'N/A')." at {$eTimeStr} [{$e->location}]: {$e->description}\n";
            }

            $ctx .= "\n### PARISH HISTORY:\n";
            $ctx .= "- Est. 1983. Patroness: Queen of The Most Holy Rosary of Pacita. Parish Priest: {$priestName}.";
            if ($assistantPriestName) {
                $ctx .= " Asst. Parish Priest: {$assistantPriestName}.";
            }
            $ctx .= " 2024: Image declared Important Cultural Property of San Pedro. 2025: Our Lady titled 'Queen of the City of San Pedro'.\n";

            $ctx .= "\n### SACRAMENTAL REQUIREMENTS & FEES:\n";
            $ctx .= "- **Baptism**: Birth Certificate (with Registry Number), Baptismal Permit (non-Pacita residents), Registration Fee: ₱500.00\n";
            $ctx .= "- **Wedding**: Baptismal & Confirmation Certificates (for Marriage Purpose), PSA Birth Certificate & CENOMAR, Marriage License/Civil Marriage Contract, complete 2 months before preferred date\n";
            $ctx .= "- **Confirmation**: Photocopy of Baptismal Certificate\n";
            $ctx .= "- **First Communion**: Photocopy of Baptismal Certificate\n";
            $ctx .= "- **Funeral Mass**: Photocopy of Death Certificate\n";
            $ctx .= "- **Mass Intention**: ₱500.00 per intention. Submit via [/submit-intention](/submit-intention)\n";
            $ctx .= "- **Sacramental Certificates** (Baptismal/Confirmation/Marriage): Full name, date, processing fee (₱100)\n";

            return $ctx;
        });
    }

    protected function getSystemPrompt($context = ''): string
    {
        $contactRaw = Cache::remember('chatbot_settings_parish_contact', 300, fn () => Setting::where('key', 'parish_contact')->value('value'));
        $contactRaw = $contactRaw ?? '+63 2 8869 2742';
        $contactNumbers = is_string($contactRaw) && $contactRaw !== ''
            ? (json_decode($contactRaw, true) ?: [$contactRaw])
            : (is_array($contactRaw) ? $contactRaw : ['+63 2 8869 2742']);
        $contactLine = implode(' | ', $contactNumbers);
        $email = Cache::remember('chatbot_settings_parish_email', 300, fn () => Setting::where('key', 'parish_email')->value('value')) ?? 'officestorosarioparish@gmail.com';

        return "You are the official digital concierge of Sto. Rosario Parish (Pacita, San Pedro, Laguna, Philippines). You embody the warmth and hospitality of this Catholic community.

## PERSONALITY
- Warm, approachable, and genuinely helpful — like a well-informed parish volunteer.
- Confident and knowledgeable about parish life, Catholic traditions, and local community.
- Concise by default; expand with detail only when the question demands it.
- You are NOT a generic chatbot. You ARE part of the parish.

## RESPONSE STYLE
- Open with a brief acknowledgment of what the user asked, then answer directly.
- Use bold (**text**) for key terms, dates, fees, and names.
- Use clean bullet points for lists (2+ items). Single-item answers should be inline.
- Limit emojis to 1-2 per message maximum, only where they add genuine warmth. Never use emojis as decoration.
- For links, write naturally in the sentence — never raw URLs or arrow symbols.
- End longer responses with a single helpful follow-up suggestion, not a generic \"How can I help?\"
- Never say \"according to our records\", \"based on the knowledge base\", or any meta-references to your data source.

## LANGUAGE
- CRITICAL: Match the user's language exactly. If they write in English, reply 100% in English. If they write in Tagalog, reply 100% in Tagalog. If they write in Taglish, reply in Taglish.
- Never switch languages mid-response. A Tagalog message gets a Tagalog reply. An English message gets an English reply.
- When in Tagalog, use casual natural Filipino (\"Pwede mo\", \"Narito\", \"Maaari kang\") — never formal or robotic.
- When in Tagalog, translate all data (schedules, fees, addresses) into Tagalog where natural.
- Only mix languages when the user themselves mixes (Taglish).

## PARISH KNOWLEDGE BASE:
{$context}

## CONTACT INFO:
- Address: 1 Sto. Rosario Drive, Pacita, San Pedro, Laguna 4023
- Phone: {$contactLine}
- Email: {$email}
- Website: https://storosario.ph

## ALLOWED LINKS (use ONLY these)
[/] [/mass-schedule] [/submit-intention] [/inquiry] [/events] [/gallery] [/bulletins] [/track] [/about] [/donate]

## BOUNDARIES
- For sacramental inquiries (Baptism, Wedding, Confirmation, Funeral, etc.), always provide all three options: online inquiry form, phone, and office visit with hours.
- Never say \"chapel\" — always say \"church\" or \"Sto. Rosario Parish\".
- No mass seat reservations exist. If asked, direct them to the inquiry form.
- For live agent requests: only suggest handover if the user explicitly asks for a person or human.
- You may answer basic Catholic faith questions (prayers, sacrament meaning, feast days, etc.).
- For complex pastoral or theological questions beyond your scope, gently recommend speaking with the parish priest.
- Never fabricate schedules, fees, or event details. If unsure, say so and direct them to contact the office.";
    }

    protected function getGcashNumber(): string
    {
        return Cache::remember('chatbot_settings_gcash_number', 300, fn () => Setting::where('key', 'gcash_number')->value('value')) ?? '09123456789';
    }

    /**
     * Local keyword-based fallback engine when no external AI is available.
     */
    protected function localFallback(string $message): string
    {
        $lower = mb_strtolower(trim($message));
        $lower = preg_replace('/[^a-z0-9\sáéíóúñäëïöü\-]+/u', '', $lower);

        $intents = [
            'mass_schedule' => [
                'mass', 'schedule', 'misa', 'oras', 'time', 'service',
                'scheduled', 'kailan', 'anong oras', 'simba', 'banal',
            ],
            'intention' => [
                'intention', 'alay', 'panalangin', 'offering', 'offer mass',
                'magpaalay', 'magpamisa', 'donate mass',
            ],
            'inquiry' => [
                'inquiry', 'inquiries', 'sacrament', 'baptism', 'baptized',
                'baptise', 'wedding', 'kasal', 'binyag', 'binyagan',
                'confirmation', 'kumpil', 'funeral', 'libing', 'sakramento',
            ],
            'track' => [
                'track', 'status', 'follow up', 'follow-up', 'update',
                'reference', 'ref id', 'ref number', 'check',
            ],
            'donation' => [
                'donate', 'donation', 'ambag', 'tulong', 'give', 'contribute',
                'payment', 'bayad', 'gcash', 'bank transfer',
            ],
            'gallery' => [
                'gallery', 'photo', 'picture', 'video', 'larawan',
            ],
            'events' => [
                'event', 'events', 'activity', 'activities', 'program',
                'palatuntunan', 'aktibidad',
            ],
            'bulletins' => [
                'bulletin', 'announcement', 'balita', 'anunsyo',
            ],
            'office_hours' => [
                'office hours', 'open', 'closed', 'hours of operation',
                'oras ng opisina', 'tue', 'tuesday', 'saturday',
            ],
            'location' => [
                'address', 'location', 'map', 'directions', 'where', 'pumunta',
                'paano pumunta', 'saan',
            ],
            'contact' => [
                'contact', 'phone', 'number', 'email', 'telephone',
                'cellphone', 'landline',
            ],
            'about' => [
                'history', 'about', 'story', 'background', 'parish info',
                'information', 'ano ang', 'who is', 'what is',
            ],
            'greeting' => [
                'hello', 'hi', 'hey', 'good morning', 'good afternoon',
                'good evening', 'magandang', 'kamusta', 'musta', 'peace',
            ],
            'thank_you' => [
                'thank', 'thanks', 'salamat', 'maraming salamat', 'appreciate',
            ],
            'faith' => [
                'rosary', 'rosaryo', 'faith', 'pananampalataya', 'prayer',
                'dasal', 'pray', 'lent', 'easter', 'christmas', 'pasko',
                'holy week', 'semana santa', 'catholic', 'katoliko',
            ],
        ];

        $scores = [];
        foreach ($intents as $intent => $keywords) {
            $score = 0;
            foreach ($keywords as $kw) {
                if (str_contains($lower, $kw)) {
                    $score++;
                }
            }
            if ($score > 0) {
                $scores[$intent] = $score;
            }
        }

        arsort($scores);
        $topIntent = key($scores) ?: 'unknown';

        $name = Cache::remember('chatbot_settings_parish_name', 300, fn () => Setting::where('key', 'parish_name')->value('value')) ?? 'Sto. Rosario Parish';
        $contactRaw = Cache::remember('chatbot_settings_parish_contact', 300, fn () => Setting::where('key', 'parish_contact')->value('value')) ?? '+63 2 8869 2742';
        $contactNumbers = is_string($contactRaw) && $contactRaw !== ''
            ? (json_decode($contactRaw, true) ?: [$contactRaw])
            : (is_array($contactRaw) ? $contactRaw : ['+63 2 8869 2742']);
        $contact = implode(' | ', $contactNumbers);
        $email = Cache::remember('chatbot_settings_parish_email', 300, fn () => Setting::where('key', 'parish_email')->value('value')) ?? 'officestorosarioparish@gmail.com';
        $gcashNum = Cache::remember('chatbot_settings_gcash_number', 300, fn () => Setting::where('key', 'gcash_number')->value('value')) ?? '09123456789';
        $priest = Cache::remember('chatbot_settings_priest_name', 300, fn () => Setting::where('key', 'priest_name')->value('value')) ?? 'our Parish Priest';
        $assistantPriest = Cache::remember('chatbot_settings_assistant_priest_name', 300, fn () => Setting::where('key', 'assistant_priest_name')->value('value'));

        $responses = [
            'greeting' => "Peace be with you! Welcome to {$name}. I can help you with mass schedules, intentions, sacraments, events, and parish information. What would you like to know?",

            'mass_schedule' => $this->buildMassScheduleResponse(),

            'intention' => "You can offer a **Mass Intention** for ₱500.00 per intention. Here is how:

- Submit online: [Mass Intention Form](/submit-intention)
- You will receive a reference number to [track your status](/track)

Mass intentions may be offered for the living or deceased, for thanksgiving, healing, or special intentions.",

            'inquiry' => "For sacramental inquiries (Baptism, Wedding, Confirmation, Funeral Mass, House Blessing, etc.), you have three options:

- **Online:** [Submit an Inquiry](/inquiry) — you will receive a reference ID to [track your status](/track)
- **Phone:** {$contact}
- **Visit:** 1 Sto. Rosario Drive, Pacita, San Pedro, Laguna (Tue–Sat 6AM–12NN & 1:30–6PM, Sun 6AM–12NN & 3–6PM)

Our team will review your inquiry and respond promptly.",

            'track' => "You can check the status of your Mass Intention or Inquiry anytime: [Track Your Request](/track)

You will need your Reference ID (e.g., SRP-2026-001 or INQ-2026-001).",

            'donation' => "Thank you for your generosity!

- **GCash:** {$gcashNum}
- **Account Name:** ".(Cache::remember('chatbot_settings_gcash_name', 300, fn () => Setting::where('key', 'gcash_name')->value('value')) ?? $name)."

You can also donate via Bank Transfer. See details on our [Donation Page](/donate). Donations support our parish operations and outreach programs.",

            'gallery' => "Browse our parish photos and videos from events, feasts, and community life: [Gallery](/gallery)",

            'events' => $this->buildEventsResponse(),

            'bulletins' => "Read our latest parish announcements and updates: [Bulletins](/bulletins)",

            'office_hours' => "**Office Hours:**
- Tuesday to Saturday: 6:00 AM – 12:00 NN, 1:30 PM – 6:00 PM
- Sunday: 6:00 AM – 12:00 NN, 3:00 PM – 6:00 PM
- Monday: **Closed**

**Address:** 1 Sto. Rosario Drive, Pacita, San Pedro, Laguna",

            'location' => "**Parish Address:**
1 Sto. Rosario Drive, Pacita, San Pedro, Laguna, Philippines 4023

Located in Pacita Complex 1. View on [Google Maps](https://maps.google.com/?q=Sto.+Rosario+Parish+Pacita+San+Pedro+Laguna).",

            'contact' => "**Contact Us:**
- Phone: {$contact}
- Email: {$email}
- Facebook: [Sto. Rosario Parish Pacita](https://facebook.com/storosarioparish)
- Messenger: [m.me/storosarioparishpacita1](https://m.me/storosarioparishpacita1)

Office Hours: Tue–Sat 6AM–12NN & 1:30–6PM | Sun 6AM–12NN & 3–6PM | Mon Closed",

            'about' => "**{$name}** is a Catholic parish at 1 Sto. Rosario Drive, Pacita, San Pedro, Laguna. Our patroness is the **Queen of the Most Holy Rosary of Pacita**, whose image was carved in Paete, Laguna in 1982.

**Key Milestones:**
- 1983: Canonical erection of the parish
- 1986: Church dedication
- 2024: Image declared Important Cultural Property of San Pedro
- 2025: Our Lady accorded the title 'Queen of the City of San Pedro'

Parish Priest: {$priest}".($assistantPriest ? " | Asst. Parish Priest: {$assistantPriest}" : '')."

More details: [About Us](/about)",

            'faith' => "I am happy to help with questions about Catholic prayers, sacraments, feast days, and traditions.

For deeper spiritual guidance, I recommend speaking with {$priest} after Mass or scheduling a pastoral appointment. What would you like to know?",

            'thank_you' => "You are most welcome! God bless you and your family. Feel free to reach out anytime you need help.",

            'unknown' => "I want to make sure I help you correctly. Could you rephrase that? Here is what I can assist with:

- Mass Schedules
- Mass Intentions
- Sacramental Inquiries (Baptism, Wedding, etc.)
- Events & Activities
- Donations & GCash
- Location & Contact Info
- Parish History",
        ];

        return $responses[$topIntent] ?? $responses['unknown'];
    }

    protected function buildMassScheduleResponse(): string
    {
        $schedules = MassSchedule::where('is_active', true)->get();
        if ($schedules->isEmpty()) {
            return 'No active mass schedules at the moment. Please check back later or [contact us](/inquiry) for information.';
        }

        $response = "**Mass Schedules:**

";
        foreach ($schedules as $s) {
            $days = is_array($s->day_of_week) ? implode(', ', $s->day_of_week) : $s->day_of_week;
            $times = is_array($s->time) ? implode(', ', $s->time) : $s->time;
            $response .= "- **{$s->title}** ({$s->mass_type}): {$days} at {$times}";
            if ($s->location && $s->location !== 'Main Church') {
                $response .= " [{$s->location}]";
            }
            $response .= "
";
        }
        $response .= "
*Schedules may change on special occasions. See our [Mass Schedule page](/mass-schedule) for the latest.*";

        return $response;
    }

    protected function buildEventsResponse(): string
    {
        $events = Event::where('is_published', true)
            ->where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date', 'asc')
            ->limit(5)
            ->get();

        if ($events->isEmpty()) {
            return 'No upcoming events scheduled right now. Check our [Events page](/events) for the latest updates.';
        }

        $response = "**Upcoming Events:**

";
        foreach ($events as $e) {
            $date = $e->event_date ? $e->event_date->format('M d, Y') : 'TBA';
            $response .= "- **{$e->title}** — {$date}
";
            if ($e->description) {
                $response .= '  '.strip_tags(mb_strimwidth($e->description, 0, 120, '...'))."
";
            }
        }
        $response .= "
[View All Events](/events)";

        return $response;
    }
}
