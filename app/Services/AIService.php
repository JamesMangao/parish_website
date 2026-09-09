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
            $aiResponse = $this->cleanAiOutput($aiResponse);

            if ($cacheKey) {
                Cache::put($cacheKey, $aiResponse, 600);
            }

            return $aiResponse;
        }

        return $this->localFallback($userMessage);
    }

    /**
     * Strip any HTML tags or entities the AI may have emitted so the frontend
     * never receives raw <strong>/<em> markup. Markdown is preserved.
     */
    protected function cleanAiOutput(string $text): string
    {
        $cleaned = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        for ($i = 0; $i < 3 && $cleaned !== $text; $i++) {
            $text = $cleaned;
            $cleaned = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }
        $text = $cleaned;

        $text = preg_replace('/<br\s*\/?>/i', "\n", $text);
        $text = preg_replace('/<\/?(p|div|ul|ol|li|blockquote|h[1-6])[^>]*>(\n?)/i', "\n", $text);
        $text = preg_replace('/<[^>]+>/', '', $text);
        $text = preg_replace('/[ \t]+\n/', "\n", $text);

        return trim($text);
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
                        $datePart = $t['date'] ?? '';
                        $combined = trim(($datePart ? "{$datePart} " : '').$timePart.($titlePart ? " ($titlePart)" : ''));
                        if ($combined) {
                            $eTimes[] = $combined;
                        }
                    }
                }
                $eTimeStr = ! empty($eTimes) ? implode(', ', $eTimes) : (is_string($e->event_time) ? $e->event_time : 'N/A');
                $ctx .= "- {$e->title} on ".($e->event_date ? $e->event_date->format('M d, Y') : 'N/A')." at {$eTimeStr}: {$e->description}\n";
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

        return "You are the official AI concierge of Sto. Rosario Parish (Pacita, San Pedro, Laguna, Philippines). You speak with the warmth of a neighbor and the authority of the parish office — approachable, never robotic, never like a brochure.

## HOW TO TALK (conversational, not formal)
- Sound like a real, friendly parish volunteer having a conversation. Talk directly to the person, like you genuinely know them.
- Answer in a few warm sentences, then give the useful details. Do NOT open with a formulaic acknowledgment, and do NOT end with a generic \"How can I help?\".
- When an answer is naturally a list (schedules, fees, requirements), use short, clean bullets. Anything else gets flowing prose.
- Be brief and human. Cut filler words. Vary your sentence structure so no two replies feel templated.
- Prove you know the parish: mention exact Mass times, the actual upcoming events and announcements from the knowledge base, and exact fees — not generic advice.
- Anticipate the next question and offer ONE natural next step woven into the reply, e.g. \"You can send the details through the /submit-intention page — kami na ang bahala.\"
- Ask a clarifying question when the request is vague instead of guessing.

## KNOWLEDGE & ACCURACY (be the smartest parish resource they can reach)
- Use ONLY the knowledge base below. Never invent schedules, fees, dates, events, or people.
- Quote exact figures: ₱500 per Mass intention, ₱100 sacramental certificates, office hours, current announcements and events.
- Use the current date/time in the knowledge base to reason about \"kailan\", \"next\", \"tomorrow\", \"this Sunday\", office open/closed (note: office is CLOSED on Mondays).
- If you genuinely don't know something, say so plainly and point them to the office, the inquiry form, or the right page — never guess.
- Read the whole conversation for context (things like \"noon\", \"kanina\", \"as I said\" refer to earlier messages).

## FORMATTING RULES (CRITICAL)
- NEVER output HTML tags — no <strong>, </strong>, <em>, <b>, <i>, <a>, <br>, <p>, or any other tag, ever. Use plain markdown ONLY: **bold**, *italic*, and [link text](/path) for links.
- Money, dates, and times may be **bold**.
- At most 1 emoji per message; usually none.
- For links, write them naturally inside a sentence as [text](/path) — never as raw URLs and never with arrows.
- Never reference \"the knowledge base\", \"my data\", \"as an AI\", or \"according to our records\".

## LANGUAGE
- CRITICAL: Match the user's language exactly. English → English, Tagalog → Tagalog, Taglish → Taglish. Never switch mid-reply.
- In Tagalog: be casual and natural (\"Pwede mo\", \"Narito\", \"Kung kailangan mo\"), never formal or robotic.
- Translate all parish data (schedules, fees, addresses) naturally into the user's language.

## KNOWLEDGE BASE:
{$context}

## CONTACT INFO:
- Address: 1 Sto. Rosario Drive, Pacita, San Pedro, Laguna 4023
- Phone: {$contactLine}
- Email: {$email}
- Website: https://storosario.ph

## ALLOWED LINKS (use ONLY these, always as [text](/path) inside a sentence)
[/] [/mass-schedule] [/submit-intention] [/inquiry] [/events] [/gallery] [/bulletins] [/track] [/about] [/donate]

## BOUNDARIES
- Sacramental inquiries (Baptism, Wedding, Confirmation, Funeral, etc.): always cover the online inquiry form, the phone number, and an office visit with office hours.
- Say \"church\" or \"Sto. Rosario Parish\", never \"chapel\".
- No mass seat reservations exist. If asked, say so and direct them to the /inquiry form.
- Only suggest a live agent if the user explicitly asks for a person or a human.
- You may happily answer basic Catholic faith questions (prayers, sacraments' meaning, feast days). For deep pastoral or theological matters, gently recommend speaking with the parish priest.
- Never fabricate details. If unsure, say so and route to the office.";
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
            'greeting' => "Peace be with you, and welcome to {$name}! \u{1F64F} I can help you with Mass schedules, intentions, sacraments, events, and parish information. Anong kailangan mo today?",

            'mass_schedule' => $this->buildMassScheduleResponse(),

            'intention' => "We'd love to offer a Mass intention with you. It's **₱500.00 per intention**, and you can have it offered for the living or the departed, for healing, thanksgiving, or any special intention.

Just fill out the [Mass Intention Form](/submit-intention) and you'll get a reference number to [track the status](/track). From there, kami na ang bahala sa rest. \u{1F4DC}",

            'inquiry' => "For sacramental inquiries (Baptism, Wedding, Confirmation, Funeral Mass, House Blessing, etc.), you have three options:

- **Online:** [Submit an Inquiry](/inquiry) — you will receive a reference ID to [track your status](/track)
- **Phone:** {$contact}
- **Visit:** 1 Sto. Rosario Drive, Pacita, San Pedro, Laguna (Tue–Sat 6AM–12NN & 1:30–6PM, Sun 6AM–12NN & 3–6PM)

Our team will review your inquiry and respond promptly.",

            'track' => "You can check the status of your Mass Intention or Inquiry anytime through the [Track Your Request](/track) page — just enter your Reference ID (e.g., SRP-2026-001 or INQ-2026-001) and we'll show you where it stands.",

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

            'unknown' => "Hmm, I want to make sure I get you the right answer. Could you rephrase that a bit? Just so you know, I can help with:

- Mass Schedules
- Mass Intentions
- Sacramental Inquiries (Baptism, Wedding, Confirmation, Funeral, etc.)
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
            $response .= "- **{$s->title}** ({$s->mass_type}): {$days} at {$times}\n";
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
            $response .= "- **{$e->title}** — {$date}\n";
            if (is_array($e->event_time)) {
                foreach ($e->event_time as $t) {
                    $timePart = $t['time'] ?? '';
                    $titlePart = $t['title'] ?? '';
                    $datePart = $t['date'] ?? '';
                    $combined = trim($titlePart . ($datePart ? " ({$datePart})" : '') . ($timePart ? " at {$timePart}" : ''));
                    if ($combined) {
                        $response .= "  - {$combined}\n";
                    }
                }
            }
            if ($e->description) {
                $response .= '  '.strip_tags(mb_strimwidth($e->description, 0, 120, '...'))."\n";
            }
        }
        $response .= "
[View All Events](/events)";

        return $response;
    }
}
