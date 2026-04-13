<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\MassSchedule;
use App\Models\Announcement;
use App\Models\Event;

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
                $response = Http::withoutVerifying()
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $this->groqKey,
                        'Content-Type' => 'application/json',
                    ])->post('https://api.groq.com/openai/v1/chat/completions', [
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
                $response = Http::withoutVerifying()
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $this->openRouterKey,
                        'HTTP-Referer' => config('app.url'),
                        'X-Title' => config('app.name'),
                        'Content-Type' => 'application/json',
                    ])->post('https://openrouter.ai/api/v1/chat/completions', [
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

        $ctx .= "\n### UPCOMING EVENTS:\n";
        if ($events->isEmpty()) $ctx .= "No upcoming events.\n";
        foreach ($events as $e) {
            $eTime = is_array($e->event_time) ? implode(', ', $e->event_time) : $e->event_time;
            $ctx .= "- {$e->title} on " . ($e->event_date ? $e->event_date->format('M d, Y') : 'N/A') . " at {$eTime} [{$e->location}]: {$e->description}\n";
        }

        return $ctx;
    }

    protected function getSystemPrompt($context = '')
    {
        return "You are the official digital assistant of Sto. Rosario Parish (Pacita, San Pedro, Laguna, Philippines). 
You are a warm, helpful, and knowledgeable 'Parish Concierge'. Your goal is to assist parishioners and visitors with accurate information about parish life.

### PARISH KNOWLEDGE BASE:
{$context}

### GENERAL INFO:
- Location: 1 Sto. Rosario Drive, Pacita, San Pedro, Laguna, Philippines 4023.
- Iconic Image: Queen of The Most Holy Rosary.
- Office Hours: Tue-Sat (6:00 AM – 12:00 NN, 1:30 PM – 6:00 PM), Sun (6:00 AM – 12:00 NN, 3:00 PM – 6:00 PM). Mon is Closed.
- Services: Baptisms, Weddings, First Communion, Confirmation, Funeral Masses, blessings.
- Contact: +63 2 8869 2742 | officestorosarioparish@gmail.com

### CONVERSATION SCOPE & LIMITS:
CRITICAL: You are ONLY authorized to discuss the following 8 areas of Sto. Rosario Parish. Do NOT provide information or advice on any other topics:
1. **Home**: General welcome and basic parish statistics.
2. **Mass**: Schedules and types of mass (from your knowledge base).
3. **Intentions**: Direct users to [Offer a Mass Intention](/submit-intention).
4. **Inquiries**: Direct users to [Submit an Inquiry](/inquiry) for all sacramental requests (Baptism, Wedding, etc.).
5. **Events**: Upcoming events from your database.
6. **Gallery**: Direct users to view photos at the [Gallery](/gallery).
7. **About**: Parish history, location (1 Sto. Rosario Drive), and office hours.
8. **Donate**: Direct users to the [Donation Page](/donate).

### CONVERSATION GUIDELINES:
1. **Stick to the List**: If a user asks about something NOT on the list above (e.g., specific theology, outside news, or mass reservations), politely inform them that you are only programmed to assist with the 8 core areas mentioned above.
2. **No Reservations**: Sto. Rosario Parish DOES NOT have a mass reservation system.
3. **Handover**: Only suggest a live representative if the user explicitly asks for a person or if you cannot answer a question within the 8 authorized areas.
4. **Links**: Use only the following paths: [/], [/mass-schedule], [/submit-intention], [/inquiry], [/events], [/gallery], [/about], [/donate].

### TONE:
Vibrant, polite, and faith-filled. Maintain a respectful Catholic tone. Use [Link Name](/url) for links.";
    }
}
