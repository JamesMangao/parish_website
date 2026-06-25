<?php

namespace App\Http\Controllers;

use App\Models\MassIntention;
use App\Models\MassSchedule;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\Inquiry;
use App\Models\ChatSession;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $stats = [];
        $intentionsTrend = collect();
        $inquiryTypes = collect();

        try {
            $stats = [
                'total_intentions' => MassIntention::count(),
                'pending_intentions' => MassIntention::where('status', 'pending')->count(),
                'total_inquiries' => Inquiry::count(),
                'pending_inquiries' => Inquiry::where('status', 'pending')->count(),
                'upcoming_events' => Event::where('event_date', '>=', now())->count(),
                'total_announcements' => Announcement::count(),
                'active_schedules' => MassSchedule::where('is_active', true)->count(),
            ];
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Dashboard stats query failed: ' . $e->getMessage());
        }

        try {
            $intentionsTrend = MassIntention::selectRaw("TO_CHAR(created_at, 'IYYYIW') as week, count(*) as total")
                ->where('created_at', '>=', now()->subWeeks(8))
                ->groupBy('week')
                ->orderBy('week')
                ->get();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Dashboard intentions trend query failed: ' . $e->getMessage());
        }

        try {
            $inquiryTypes = Inquiry::selectRaw('inquiry_type as type, count(*) as total')
                ->groupBy('type')
                ->get();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Dashboard inquiry types query failed: ' . $e->getMessage());
        }

        return view('admin.dashboard', compact('stats', 'intentionsTrend', 'inquiryTypes'));
    }

    public function getNotifications()
    {
        $handoverChats = ChatSession::where('status', 'handover')->count();
        $unreadActiveChats = ChatSession::where('status', 'active')
            ->whereRaw('(select sender from chat_messages where chat_session_id = chat_sessions.id order by id desc limit 1) = ?', ['user'])
            ->count();

        $latestUserMsg = ChatMessage::whereIn('chat_session_id', function ($q) {
            $q->select('id')->from('chat_sessions')->whereIn('status', ['handover', 'active']);
        })
            ->where('sender', 'user')
            ->latest('id')
            ->first();

        return response()->json([
            'intentions' => MassIntention::where('status', 'pending')->count(),
            'inquiries' => Inquiry::where('status', 'pending')->count(),
            'chats' => $handoverChats + $unreadActiveChats,
            'last_user_message_id' => $latestUserMsg?->id ?? 0,
        ]);
    }

    public function logs()
    {
        $logs = ActivityLog::with('user')->latest()->paginate(50);
        return view('admin.logs', compact('logs'));
    }
}
