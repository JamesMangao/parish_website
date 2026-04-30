<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\MassSchedule;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\Setting;

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
     * Get a response from AI, trying Groq first then OpenRouter.
     */
    public function getResponse(array $messages)
    {
        // Add System Prompt if not present
        if (!collect($messages)->contains('role', 'system')) {
            $context = $this->getParishContext();
            array_unshift($messages, [
                'role' => 'system',
                'content' => $this->getSystemPrompt($context)
            ]);
        }

        // Try Groq First
        if ($this->groqKey) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->groqKey,
                    'Content-Type' => 'application/json',
                ])->withOptions(['verify' => false])->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => 'llama-3.1-8b-instant',
                    'messages' => $messages,
                    'temperature' => 0.7,
                ]);

                if ($response->successful()) {
                    return $response->json()['choices'][0]['message']['content'];
                }
                
                Log::warning('Groq API failed: ' . $response->body());
            } catch (\Exception $e) {
                Log::error('Groq connection error: ' . $e->getMessage());
            }
        }

        // Fallback to OpenRouter
        if ($this->openRouterKey) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->openRouterKey,
                    'HTTP-Referer' => config('app.url'),
                    'X-Title' => config('app.name'),
                    'Content-Type' => 'application/json',
                ])->withOptions(['verify' => false])->post('https://openrouter.ai/api/v1/chat/completions', [
                    'model' => 'google/gemma-2-9b-it:free',
                    'messages' => $messages,
                ]);

                if ($response->successful()) {
                    return $response->json()['choices'][0]['message']['content'];
                }
                
                Log::warning('OpenRouter API failed: ' . $response->body());
            } catch (\Exception $e) {
                Log::error('OpenRouter connection error: ' . $e->getMessage());
            }
        }

        return "I am sorry, my contemplative silence is being interrupted by technical connectivity. Please try again or contact the parish office directly.";
    }

/**
 * Fetch dynamic knowledge from the database.
 */
protected function getParishContext()
{
    $schedules = MassSchedule::where('is_active', true)->get();
    $announcements = Announcement::where('is_published', true)->orderBy('published_at', 'desc')->take(3)->get();
    $events = Event::where('is_published', true)->where('event_date', '>=', now()->toDateString())->orderBy('event_date', 'asc')->get();

    $ctx = "CURRENT DATE: " . now()->toFormattedDateString() . "\n\n";

    $ctx .= "### ACTIVE MASS SCHEDULES:\n";
    if ($schedules->isEmpty()) $ctx .= "No active schedules found.\n";
    foreach ($schedules as $s) {
        $days = is_array($s->day_of_week) ? implode(', ', $s->day_of_week) : $s->day_of_week;
        $times = is_array($s->time) ? implode(', ', $s->time) : $s->time;
        $ctx .= "- {$s->title} ({$s->mass_type}): {$days} at {$times} [{$s->location}]\n";
    }

    $ctx .= "\n### RECENT ANNOUNCEMENTS:\n";
    if ($announcements->isEmpty()) $ctx .= "No recent announcements.\n";
    foreach ($announcements as $a) {
        $ctx .= "- {$a->title}: " . strip_tags($a->content) . " (Published: " . ($a->published_at ? $a->published_at->format('M d, Y') : 'N/A') . ")\n";
    }

    $ctx .= "\n### UPCOMING EVENTS & PARISH ACTIVITIES:\n";
    if ($events->isEmpty()) $ctx .= "No upcoming events.\n";
    foreach ($events as $e) {
        $eTimes = [];
        if (is_array($e->event_time)) {
            foreach ($e->event_time as $t) {
                $timePart = $t['time'] ?? '';
                $titlePart = $t['title'] ?? '';
                $combined = trim($timePart . ($titlePart ? " ($titlePart)" : ""));
                if ($combined) $eTimes[] = $combined;
            }
        }
        $eTimeStr = !empty($eTimes) ? implode(', ', $eTimes) : (is_string($e->event_time) ? $e->event_time : 'N/A');
        
        $ctx .= "- {$e->title} on " . ($e->event_date ? $e->event_date->format('M d, Y') : 'N/A') . " at {$eTimeStr} [{$e->location}]: {$e->description}\n";
    }

    // Add Parish Details & History
    $ctx .= "\n### PARISH HISTORY & IDENTITY:\n";
    $ctx .= "- 1982: The image of the Queen of the Most Holy Rosary of Pacita was carved in Paete, Laguna.\n";
    $ctx .= "- 1983 (Oct 16): Canonical erection of the parish. The image was declared patroness.\n";
    $ctx .= "- 1986 (Dec 6): Church dedication.\n";
    $ctx .= "- 2009: Rev. Fr. Mario P. Rivera began promoting the endearing title 'Our Lady of Pacita'.\n";
    $ctx .= "- 2021: Hermandad del Santo Rosario (Rosary Confraternity of Pacita) was established.\n";
    $ctx .= "- 2024: The image was declared an Important Cultural Property of San Pedro City.\n";
    $ctx .= "- 2025: Our Lady was accorded the honorific title 'Queen of the City of San Pedro'.\n";
    
    // Add dynamically loaded global settings if needed
    $priestName = Setting::where('key', 'priest_name')->value('value') ?? 'Rev. Fr. Parish Priest';
    $ctx .= "- Leadership: {$priestName} (Parish Priest, serving since 2019 to Present).\n";

    return $ctx;
}

protected function getSystemPrompt($context = '')
{
        return "You are the official digital assistant of Sto. Rosario Parish (Pacita, San Pedro, Laguna, Philippines). 
You are a warm, helpful, and knowledgeable 'Parish Concierge'. Your goal is to assist parishioners and visitors with accurate information about parish life and basic Catholic teachings.

### PARISH KNOWLEDGE BASE:
{$context}

### GENERAL INFO:
- Location: 1 Sto. Rosario Drive, Pacita, San Pedro, Laguna, Philippines 4023.
- Iconic Image: Queen of The Most Holy Rosary.
- Office Hours: Tue-Sat (6:00 AM – 12:00 NN, 1:30 PM – 6:00 PM), Sun (6:00 AM – 12:00 NN, 3:00 PM – 6:00 PM). Mon is Closed.
- Services: Baptisms, Weddings, First Communion, Confirmation, Funeral Masses, blessings.
- Contact: +63 2 8869 2742 | officestorosarioparish@gmail.com

### SYSTEM INSTRUCTIONS:
- NEVER mention phrases like 'According to the Parish Knowledge Base', 'Based on the provided information', or 'The system says'. You are a living concierge. Speak naturally and confidently as if you inherently know these facts.
- Language: You seamlessly understand and speak English, Tagalog (Filipino), and Taglish. 
- MANDATORY LANGUAGE RULE: Always reply in the EXACT SAME LANGUAGE as the user's most recent message. If they ask in English, reply ONLY in English. If they ask in Tagalog, reply ONLY in Tagalog. Do not mix languages unless the user does (Taglish). Stay consistent.
- If a user asks a follow-up question that you don't know (e.g. 'until when will he be the priest?'), just answer naturally and conversationally, e.g., 'He is our current serving Parish Priest, and there is no end date specified!' rather than saying 'the provided information does not specify.'
- Your primary focus is Sto. Rosario Parish. You have REAL-TIME access to our database. When asked about activities, events, or mass schedules, refer strictly to the 'PARISH KNOWLEDGE BASE' provided above. Do NOT make up schedules.
- If a user asks 'What are the upcoming events?', list them from the knowledge base above naturally.
- You are ALSO authorized to answer common Catholic faith questions (e.g., 'What is Lent?', 'How to pray the Rosary?'). 
- Ensure your faith-based answers are consistent with standard Catholic teachings.
- For complex theological debates or personal pastoral advice, kindly suggest they speak with a priest.

### CORE PARISH AREAS:
1. **Home**: General welcome and basic parish statistics.
2. **Mass**: Schedules and types of mass (from your knowledge base).
3. **Intentions**: Direct users to [Offer a Mass Intention](/submit-intention).
4. **Inquiries**: Direct users to [Submit an Inquiry](/inquiry) for all sacramental requests (Baptism, Wedding, etc.).
5. **Events**: Upcoming events from your database.
6. **Gallery**: Direct users to view photos at the [Gallery](/gallery).
7. **Bulletins**: Direct users to view weekly announcements at [Bulletins](/bulletins).
8. **Tracker**: Direct users to check their status at [Tracker](/track).
9. **About**: Parish history, location (1 Sto. Rosario Drive), and office hours.
10. **Donate**: Direct users to the [Donation Page](/donate).

### CONVERSATION GUIDELINES:
1. **Stay Relevant**: While you can answer faith questions, always try to bring the conversation back to how it relates to parish life if possible.
2. **No Reservations**: Sto. Rosario Parish DOES NOT have a mass reservation system.
3. **Handover**: Only suggest a live representative if the user explicitly asks for a person or if you cannot answer a question.
4. **Links**: Use only the following paths: [/], [/mass-schedule], [/submit-intention], [/inquiry], [/events], [/gallery], [/bulletins], [/track], [/about], [/donate].

### TONE:
Vibrant, polite, and faith-filled. Maintain a respectful Catholic tone. Use [Link Name](/url) for links.";
    }
}
