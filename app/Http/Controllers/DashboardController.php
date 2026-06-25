<?php

namespace App\Http\Controllers;

use App\Models\MassIntention;
use App\Models\MassSchedule;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\Inquiry;
use App\Models\ChatSession;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        try {
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

            return response(view('admin.dashboard', compact('stats', 'intentionsTrend', 'inquiryTypes'))->render());
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('DashboardController::dashboard CRASHED: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'error' => true,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ], 500);
        }
    }

    public function getNotifications()
    {
        return response()->json([
            'intentions' => MassIntention::where('status', 'pending')->count(),
            'inquiries' => Inquiry::where('status', 'pending')->count(),
            'chats' => ChatSession::where('status', 'handover')->count(),
        ]);
    }

    public function dashTest()
    {
        return response('dashTest OK: ' . Auth::user()->name . ' role=' . Auth::user()->role);
    }

    public function logs()
    {
        $logs = ActivityLog::with('user')->latest()->paginate(50);
        return view('admin.logs', compact('logs'));
    }
}
