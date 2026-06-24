<?php

namespace App\Http\Controllers;

use App\Models\MassIntention;
use App\Models\Inquiry;
use App\Models\ChatSession;
use App\Models\ActivityLog;
use App\Services\LogService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        return app(DashboardController::class)->dashboard();
    }

    public function getNotifications()
    {
        return app(DashboardController::class)->getNotifications();
    }

    public function logs()
    {
        return app(DashboardController::class)->logs();
    }

    public function intentions(Request $request)
    {
        return app(AdminIntentionController::class)->index($request);
    }

    public function showIntention($id)
    {
        return app(AdminIntentionController::class)->show($id);
    }

    public function createIntention()
    {
        return app(AdminIntentionController::class)->create();
    }

    public function storeIntention(Request $request)
    {
        return app(AdminIntentionController::class)->store($request);
    }

    public function updateStatus(Request $request, $id)
    {
        return app(AdminIntentionController::class)->updateStatus($request, $id);
    }

    public function batchUpdateStatus(Request $request)
    {
        return app(AdminIntentionController::class)->batchUpdateStatus($request);
    }

    public function generatePPT(Request $request)
    {
        return app(PptController::class)->generate($request);
    }

    public function previewPPT()
    {
        return app(PptController::class)->preview();
    }

    public function createGoogleSlides(Request $request)
    {
        return app(GoogleSlidesController::class)->create($request);
    }
}
