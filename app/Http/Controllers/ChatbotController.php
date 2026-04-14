<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use App\Models\ChatMessage;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * Handle incoming chat messages.
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $session = $this->getOrCreateSession();
        $userMessage = $request->input('message');

        // 1. Store User Message
        ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender' => 'user',
            'message' => $userMessage,
        ]);

        // 2. If in Live Agent mode, don't trigger AI
        if ($session->status === 'handover' || $session->admin_id) {
            return response()->json([
                'status' => 'waiting_for_agent',
                'message' => 'Your message has been sent to the live agent.',
            ]);
        }

        // 3. Simple Agent Intent Detection
        if (Str::contains(strtolower($userMessage), ['live agent', 'human', 'talk to person', 'speak with someone', 'admin'])) {
            return response()->json([
                'status' => 'suggest_handover',
                'message' => "I understand you'd like to speak with a person. Would you like to submit a formal Inquiry or wait for a Live Agent to connect? (Wait time is approx. 2 mins)",
            ]);
        }

        // 4. AIService Response
        $history = $session->messages()
            ->latest()
            ->take(10)
            ->get()
            ->reverse()
            ->map(fn($m) => [
                'role' => $m->sender === 'user' ? 'user' : 'assistant',
                'content' => $m->message
            ])
            ->toArray();

        $aiResponse = $this->aiService->getResponse($history);

        // 5. Store AI Response
        ChatMessage::create([
            'chat_session_id' => $session->id,
            'sender' => 'ai',
            'message' => $aiResponse,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => $aiResponse,
        ]);
    }

    /**
     * Request a live agent handover.
     */
    public function requestAgent()
    {
        $session = $this->getOrCreateSession();
        $session->update([
            'status' => 'handover',
            'live_agent_requested_at' => now(),
        ]);

        return response()->json([
            'status' => 'requested',
            'message' => 'Waiting for a parish representative to connect...',
        ]);
    }

    /**
     * Poll for new messages (especially from admin).
     */
    public function poll(Request $request)
    {
        $session = $this->getOrCreateSession();
        $lastId = $request->input('last_id', 0);

        $newMessages = $session->messages()
            ->where('id', '>', $lastId)
            ->whereIn('sender', ['admin', 'ai'])
            ->get();

        return response()->json([
            'messages' => $newMessages,
            'agent_connected' => $session->admin_id !== null,
            'agent_typing' => Cache::has('chat_typing_' . $session->id),
            'status' => $session->status,
        ]);
    }

    public function adminIndex(Request $request)
    {
        $status = $request->input('status', 'handover');
        $search = $request->input('search');
        
        $sessions = ChatSession::withCount('messages')
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($search, fn($q) => $q->where('user_ip', 'LIKE', "%{$search}%"))
            ->orderBy('live_agent_requested_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(15);
            
        return view('admin.chats.index', compact('sessions', 'status', 'search'));
    }

    public function resolve($id)
    {
        $chat = ChatSession::findOrFail($id);
        $chat->update(['status' => 'resolved']);
        
        return redirect()->route('admin.chats.index')->with('success', 'Conversation marked as resolved.');
    }

    public function adminShow($id)
    {
        $chat = ChatSession::with('messages', 'admin')->findOrFail($id);
        
        // Mark as connected if not already
        if (!$chat->admin_id && auth()->check()) {
            $chat->update([
                'admin_id' => auth()->id(),
                'status' => 'active'
            ]);
        }

        return view('admin.chats.show', compact('chat'));
    }

    public function adminReply(Request $request, $id)
    {
        $request->validate(['message' => 'required|string']);
        $chat = ChatSession::findOrFail($id);

        // Clear typing indicator on send
        Cache::forget('chat_typing_' . $chat->id);

        ChatMessage::create([
            'chat_session_id' => $chat->id,
            'sender' => 'admin',
            'message' => $request->message,
        ]);

        return back()->with('success', 'Reply sent!');
    }

    /**
     * Signal that admin is typing (called via AJAX from admin chat view).
     */
    public function adminTyping($id)
    {
        $chat = ChatSession::findOrFail($id);
        Cache::put('chat_typing_' . $chat->id, true, now()->addSeconds(5));

        return response()->json(['status' => 'ok']);
    }

    protected function getOrCreateSession()
    {
        $id = session()->getId();
        return ChatSession::firstOrCreate(
            ['session_id' => $id],
            [
                'user_ip' => request()->ip(),
                'status' => 'active'
            ]
        );
    }
}
