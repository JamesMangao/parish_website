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

        return "You are Sto. Rosario Parish's digital assistant (Pacita, San Pedro, Laguna). Warm, helpful Catholic parish concierge.

### PARISH KNOWLEDGE BASE:
{$context}

### INFO:
- Address: 1 Sto. Rosario Drive, Pacita, San Pedro, Laguna 4023
- Contact: {$contactLine} | {$email}
- Website: https://storosario.ph

### RULES:
- Speak naturally — never say 'according to the knowledge base'. You ARE the concierge.
- Reply in the SAME language the user uses (English/Tagalog/Taglish).
- For sacramental inquiries (Baptism, Wedding, etc.), give ALL options: [Submit an Inquiry](/inquiry), phone number ({$contactLine}), and office address with hours.
- Use ONLY these links: [/], [/mass-schedule], [/submit-intention], [/inquiry], [/events], [/gallery], [/bulletins], [/track], [/about], [/donate].
- Never use 'chapel' — say 'church' or 'Sto. Rosario Parish'.
- No mass reservations. For handover, only if user explicitly asks.
- You may answer basic Catholic faith questions.
- Keep replies concise (2-4 sentences unless detail is needed).";
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
            'greeting' => "Peace be with you! 🙏 I am the digital concierge of {$name}. How may I assist you today? You can ask me about:\n\n- ⛪ Mass Schedules\n- 🕯️ Mass Intentions\n- 📝 Sacramental Inquiries\n- 📅 Events & Activities\n- 💰 Donations & GCash\n\nHow can I help?",

            'mass_schedule' => $this->buildMassScheduleResponse(),

            'intention' => "You can offer a Mass Intention through our online form:\n👉 [Submit Mass Intention](/submit-intention)\n\nAfter submission, you'll receive a reference number to [track your intention status](/track).\n\nMass offerings are ₱500.00 per intention. Thank you for your support! 🕯️",

            'inquiry' => "For sacramental inquiries like Baptism, Wedding, Confirmation, Funeral Mass, House Blessing, or Car Blessing, you have a few options:\n\n📝 **Submit an Inquiry Online:** [Click here to fill out our inquiry form](/inquiry) — you'll receive a reference ID to [track your status](/track).\n\n📞 **Call us directly:** {$contact}\n\n📍 **Visit the parish office:** 1 Sto. Rosario Drive, Pacita, San Pedro, Laguna\n🕐 Office Hours: Tue–Sat 6AM–12NN & 1:30–6PM | Sun 6AM–12NN & 3–6PM\n\nOur team will review your inquiry and get back to you soon. God bless! 🙏",

            'track' => "You can track the status of your Mass Intention or Inquiry here:\n👉 [Track Your Request](/track)\n\nYou'll need your Reference ID (e.g., SRP-2026-001 or INQ-2026-001).",

            'donation' => "Thank you for your generosity! 🙏\n\n**GCash:** {$gcashNum}\n**Account Name:** ".(Cache::remember('chatbot_settings_gcash_name', 300, fn () => Setting::where('key', 'gcash_name')->value('value')) ?? $name)."\n\nYou can also donate via Bank Transfer — check our [Donation Page](/donate) for details.\n\n*Donations are voluntary and support our parish operations and outreach programs.*",

            'gallery' => "View our parish photo gallery and videos:\n👉 [Gallery](/gallery)\n\nWe have collections from parish events, feasts, and daily life at {$name}.",

            'events' => $this->buildEventsResponse(),

            'bulletins' => "Stay updated with our latest parish announcements:\n👉 [Bulletins](/bulletins)",

            'office_hours' => "🕐 **Office Hours:**\n- Tuesday to Saturday: 6:00 AM – 12:00 NN, 1:30 PM – 6:00 PM\n- Sunday: 6:00 AM – 12:00 NN, 3:00 PM – 6:00 PM\n- Monday: **Closed**\n\n📍 1 Sto. Rosario Drive, Pacita, San Pedro, Laguna",

            'location' => "📍 **Address:**\n1 Sto. Rosario Drive, Pacita, San Pedro, Laguna, Philippines 4023\n\nWe are located in Pacita Complex 1. You can view our location on [Google Maps](https://maps.google.com/?q=Sto.+Rosario+Parish+Pacita+San+Pedro+Laguna).",

            'contact' => "📞 **Contact Information:**\n- Phone: {$contact}\n- Email: {$email}\n- Facebook: [Sto. Rosario Parish Pacita](https://facebook.com/storosarioparish)\n- Messenger: [m.me/storosarioparishpacita1](https://m.me/storosarioparishpacita1)\n\n**Office Hours:**\n- Tue–Sat: 6:00 AM – 12:00 NN, 1:30 PM – 6:00 PM\n- Sun: 6:00 AM – 12:00 NN, 3:00 PM – 6:00 PM\n- Mon: Closed",

            'about' => "{$name} is a Catholic parish located at 1 Sto. Rosario Drive, Pacita, San Pedro, Laguna. Our patroness is the **Queen of the Most Holy Rosary of Pacita**, whose image was carved in Paete, Laguna in 1982.\n\n⛪ **Key Milestones:**\n- 1983 (Oct 16): Canonical erection of the parish\n- 1986 (Dec 6): Church dedication\n- 2024: Image declared Important Cultural Property of San Pedro\n- 2025: Our Lady accorded the title 'Queen of the City of San Pedro'\n\nOur Parish Priest is {$priest}".($assistantPriest ? " and our Assistant Parish Priest is {$assistantPriest}" : '').".\n\nLearn more: [About Us](/about)",

            'faith' => "That's a beautiful question about our Catholic faith! 🙏\n\nI'd be happy to help with basic questions about prayers, sacraments, and Catholic traditions. For more complex spiritual guidance, I recommend speaking with {$priest} after Mass or scheduling a pastoral appointment.\n\nIs there something specific about our faith you'd like to know?",

            'thank_you' => "You're most welcome! 🙏 It is my joy to assist you. If you ever need anything else, feel free to ask. God bless you and your family! ✝️",

            'unknown' => "I'm not sure I understand. Could you please rephrase your question? You can ask me about:\n\n- ⛪ Mass Schedules\n- 🕯️ Mass Intentions\n- 📝 Sacramental Inquiries\n- 📅 Events & Activities\n- 💰 Donations & GCash\n- 📍 Location & Contact Info\n- 📜 Parish History",
        ];

        return $responses[$topIntent] ?? $responses['unknown'];
    }

    protected function buildMassScheduleResponse(): string
    {
        $schedules = MassSchedule::where('is_active', true)->get();
        if ($schedules->isEmpty()) {
            return 'There are no active mass schedules available at the moment. Please check back later or contact the parish office for more information.';
        }

        $response = "⛪ **Mass Schedules:**\n\n";
        foreach ($schedules as $s) {
            $days = is_array($s->day_of_week) ? implode(', ', $s->day_of_week) : $s->day_of_week;
            $times = is_array($s->time) ? implode(', ', $s->time) : $s->time;
            $response .= "- **{$s->title}** ({$s->mass_type}): {$days} at {$times}";
            if ($s->location && $s->location !== 'Main Church') {
                $response .= " [{$s->location}]";
            }
            $response .= "\n";
        }
        $response .= "\n*Schedule may change on special occasions. Visit our [Mass Schedule page](/mass-schedule) for updates.*";

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
            return 'There are no upcoming events scheduled at the moment. Check back soon or visit our [Events page](/events) for updates!';
        }

        $response = "📅 **Upcoming Events:**\n\n";
        foreach ($events as $e) {
            $date = $e->event_date ? $e->event_date->format('M d, Y') : 'TBA';
            $response .= "- **{$e->title}** — {$date}\n";
            if ($e->description) {
                $response .= '  '.strip_tags(mb_strimwidth($e->description, 0, 120, '...'))."\n";
            }
        }
        $response .= "\n👉 [View All Events](/events)";

        return $response;
    }
}
