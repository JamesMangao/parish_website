<?php

namespace App\Http\Controllers;

use App\Models\MassIntention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class IntentionController extends Controller
{
    public function create()
    {
        return view('submit-intention');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullName' => 'required|string|max:255',
            'intentionType' => 'required|string',
            'preferredDate' => 'nullable|date',
            'massTime' => 'nullable|string',
            'description' => 'nullable|string',
            'paymentMethod' => 'nullable|string',
        ]);

        MassIntention::create([
            'full_name' => $validated['fullName'],
            'intention_type' => $validated['intentionType'],
            'raw_message' => $validated['description'] ?? null,
            'formatted_message' => null,
            'ai_suggested_type' => null,
            'preferred_date' => $validated['preferredDate'],
            'mass_time' => $validated['massTime'],
            'status' => 'pending',
            'payment_method' => $validated['paymentMethod'],
        ]);

        return back()->with('success', 'Your mass intention has been submitted.');
    }

    public function aiFormat(Request $request)
    {
        $message = $request->input('message');
        $intentionType = $request->input('intentionType');

        if (!$message) {
            return response()->json(['error' => 'No message provided'], 400);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.supabase.anon_key'),
                'Content-Type' => 'application/json',
            ])->post(config('services.supabase.url') . '/functions/v1/ai-intention', [
                'message' => $message,
                'intentionType' => $intentionType,
            ]);

            if ($response->failed()) {
                throw new \Exception('Supabase Edge Function failed');
            }

            return $response->json();
        } catch (\Exception $e) {
            return response()->json(['error' => 'AI Unavailable'], 500);
        }
    }

    public function chatbot(Request $request)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.supabase.anon_key'),
                'Content-Type' => 'application/json',
            ])->post(config('services.supabase.url') . '/functions/v1/ai-intention', [
                'message' => "Answer this as a Catholic Parish AI: " . $request->input('message'),
            ]);

            if ($response->failed()) {
                return response()->json(['reply' => 'I am currently in contemplative silence. Please try again later.']);
            }

            $data = $response->json();
            return response()->json([
                'reply' => $data['formatted'] ?? 'Thank you for your message. How else can I assist you with parish matters?'
            ]);
        } catch (\Exception $e) {
            return response()->json(['reply' => 'I am sorry, I am having trouble connecting to the parish servers.']);
        }
    }
}
