<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected $groqKey;
    protected $openRouterKey;

    public function __construct()
    {
        $this->groqKey = env('GROQ_API_KEY');
        $this->openRouterKey = env('OPENROUTER_API_KEY');
    }

    /**
     * Get a response from AI, trying Groq first then OpenRouter.
     */
    public function getResponse(array $messages)
    {
        // Add System Prompt if not present
        if (!collect($messages)->contains('role', 'system')) {
            array_unshift($messages, [
                'role' => 'system',
                'content' => $this->getSystemPrompt()
            ]);
        }

        // Try Groq First
        if ($this->groqKey) {
            try {
                $response = Http::withHeaders([
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
                $response = Http::withHeaders([
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

    protected function getSystemPrompt()
    {
        return "You are the official digital assistant of Sto. Rosario Parish (Pacita, San Pedro, Laguna, Philippines). 
Welcome to our parish! We are located at 1 Sto. Rosario Drive, Pacita, San Pedro, Laguna, Philippines 4023. Our iconic image is the Queen of The Most Holy Rosary.

Office Hours:
- Tue-Sat: 6:00 AM – 12:00 NN, 1:30 PM – 6:00 PM
- Sun: 6:00 AM – 12:00 NN, 3:00 PM – 6:00 PM

Services: We offer Baptisms, Weddings, First Communion, Confirmation, Funeral Masses, and Car/House Blessings. 
Contact: +63 2 8869 2742 | officestorosarioparish@gmail.com

Instructions:
1. Be helpful, polite, and maintain a respectful Catholic tone.
2. If the user mentions 'live agent', 'human', 'chat with you' (meaning a real person), or similar intent:
   - Ask them: 'Would you like to submit a formal Inquiry [Link to /inquiry] or wait for a Live Agent to connect? Please note that a Live Agent may take up to 2 minutes to respond.'
3. For inquiries, guide them to: [Submit Inquiry](/inquiry).
4. For mass intentions, guide them to: [Offer an Intention](/submit-intention).
5. If the user mentions 'live agent', 'human', 'chat with you' (meaning a real person), or similar intent:
   - Apologize for being an AI and ask if they'd like to wait for a human or use the form. 
   - CRITICAL: Add the tag [[HANDOVER]] at the end of your response so the system can show the interaction buttons.
6. Keep your answers concise and accurate to Sto. Rosario Parish. Use [Link Name](/url) format for all links.";
    }
}
