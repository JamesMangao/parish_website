<?php

namespace App\Http\Controllers;

use App\Models\MassIntention;
use App\Models\MassSchedule;
use App\Models\Announcement;
use App\Models\Event;
use App\Models\Inquiry;
use App\Models\ChatSession;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function dashboard()
    {
        \Illuminate\Support\Facades\Log::info('Dashboard::dashboard() started');
        
        $stats = [];
        $intentionsTrend = collect();
        $inquiryTypes = collect();

        try {
            \Illuminate\Support\Facades\Log::info('Dashboard::stats query start');
            $stats = [
                'total_intentions' => MassIntention::count(),
                'pending_intentions' => MassIntention::where('status', 'pending')->count(),
                'total_inquiries' => Inquiry::count(),
                'pending_inquiries' => Inquiry::where('status', 'pending')->count(),
                'upcoming_events' => Event::where('event_date', '>=', now())->count(),
                'total_announcements' => Announcement::count(),
                'active_schedules' => MassSchedule::where('is_active', true)->count(),
            ];
            \Illuminate\Support\Facades\Log::info('Dashboard::stats query done', $stats);
        } catch (\Throwable $e) {
            Log::error('Dashboard stats query failed: ' . $e->getMessage());
        }

        try {
            \Illuminate\Support\Facades\Log::info('Dashboard::trend query start');
            $intentionsTrend = MassIntention::selectRaw("TO_CHAR(created_at, 'IYYYIW') as week, count(*) as total")
                ->where('created_at', '>=', now()->subWeeks(8))
                ->groupBy('week')
                ->orderBy('week')
                ->get();
            \Illuminate\Support\Facades\Log::info('Dashboard::trend query done', ['count' => $intentionsTrend->count()]);
        } catch (\Throwable $e) {
            Log::error('Dashboard intentions trend query failed: ' . $e->getMessage());
        }

        try {
            \Illuminate\Support\Facades\Log::info('Dashboard::inquiry types query start');
            $inquiryTypes = Inquiry::selectRaw('inquiry_type as type, count(*) as total')
                ->groupBy('type')
                ->get();
            \Illuminate\Support\Facades\Log::info('Dashboard::inquiry types query done', ['count' => $inquiryTypes->count()]);
        } catch (\Throwable $e) {
            Log::error('Dashboard inquiry types query failed: ' . $e->getMessage());
        }

        \Illuminate\Support\Facades\Log::info('Dashboard::about to render view');
        try {
            $result = view('admin.dashboard', compact('stats', 'intentionsTrend', 'inquiryTypes'));
            \Illuminate\Support\Facades\Log::info('Dashboard::view rendered');
            return $result;
        } catch (\Throwable $e) {
            Log::error('Dashboard view rendering failed: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response('Dashboard view error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine(), 500);
        }
    }

    public function dashboardSimple()
    {
        return response('Dashboard simple OK');
    }

    public function dashboardTestLayout()
    {
        $data = ['stats' => ['total_intentions' => 0, 'pending_intentions' => 0, 'total_inquiries' => 0, 'pending_inquiries' => 0, 'upcoming_events' => 0, 'total_announcements' => 0, 'active_schedules' => 0], 'intentionsTrend' => collect(), 'inquiryTypes' => collect()];
        try {
            return view('admin.test-dashboard', $data);
        } catch (\Throwable $e) {
            return response('Test dashboard error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine(), 500);
        }
    }
    }

    public function dashboardSimple()
    {
        return response('Dashboard simple OK');
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

    public function logs()
    {
        $logs = ActivityLog::with('user')->latest()->paginate(50);
        return view('admin.logs', compact('logs'));
    }
}
