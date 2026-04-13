<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MassScheduleController;
use App\Http\Controllers\IntentionController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\ChatbotController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', function () { return view('about'); })->name('about');
Route::get('/mass-schedule', [MassScheduleController::class, 'index'])->name('mass-schedule');
Route::get('/donate', function () { return view('donate'); })->name('donate');

Route::get('/submit-intention', [IntentionController::class, 'create'])->name('submit-intention');
Route::post('/submit-intention', [IntentionController::class, 'store'])->middleware('throttle:5,1');

Route::get('/inquiry', [InquiryController::class, 'create'])->name('inquiry');
Route::post('/inquiry', [InquiryController::class, 'store'])->name('inquiry.store');

Route::get('/events', [EventsController::class, 'publicIndex'])->name('events');
Route::get('/gallery', [GalleryController::class, 'publicIndex'])->name('gallery');
Route::get('/gallery/{album}', [GalleryController::class, 'publicAlbum'])->name('gallery.album');

// API Proxies
Route::post('/api/ai-intention', [IntentionController::class, 'aiFormat'])->middleware('throttle:10,1');
Route::post('/api/chatbot', [ChatbotController::class, 'chat'])->middleware('throttle:10,1');
Route::post('/api/chatbot/request-agent', [ChatbotController::class, 'requestAgent'])->name('chatbot.request-agent');
Route::get('/api/chatbot/poll', [ChatbotController::class, 'poll'])->name('chatbot.poll');

Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Role: super_admin, staff, or soccom
    Route::middleware('role:super_admin,staff,soccom')->group(function () {
        Route::post('/admin/generate-ppt', [AdminController::class, 'generatePPT']);
        Route::get('/admin/preview-ppt', [AdminController::class, 'previewPPT'])->name('admin.preview-ppt');
        Route::post('/admin/create-google-slides', [AdminController::class, 'createGoogleSlides'])->name('admin.create-google-slides');

        Route::get('/google/auth', function () {
            $client = new \Google\Client();
            $client->setAuthConfig(storage_path('app/google_oauth_client.json'));
            $client->addScope('https://www.googleapis.com/auth/presentations');
            $client->addScope('https://www.googleapis.com/auth/drive');
            $client->setRedirectUri(url('/google/callback'));
            $client->setAccessType('offline');
            $client->setPrompt('consent');

            $state = bin2hex(random_bytes(16));
            session(['google_oauth_state' => $state]);
            $client->setState($state);

            return redirect($client->createAuthUrl());
        });

        Route::get('/google/callback', function (\Illuminate\Http\Request $request) {
            if ($request->get('state') !== session('google_oauth_state')) {
                return 'Invalid state parameter.';
            }

            $client = new \Google\Client();
            $client->setAuthConfig(storage_path('app/google_oauth_client.json'));
            $client->setRedirectUri(url('/google/callback'));
            $token = $client->fetchAccessTokenWithAuthCode($request->get('code'));

            \App\Models\Setting::updateOrCreate(
                ['key' => 'google_token'],
                ['value' => json_encode($token)]
            );

            return 'Google connected! Token saved. You can close this tab.';
        });
    });

    // Role: super_admin or staff
    Route::middleware('role:super_admin,staff')->group(function () {
        Route::get('/admin/intentions', [AdminController::class, 'intentions'])->name('admin.intentions');
        Route::get('/admin/intentions/create', [AdminController::class, 'createIntention'])->name('admin.intentions.create');
        Route::post('/admin/intentions', [AdminController::class, 'storeIntention'])->name('admin.intentions.store');
        Route::post('/admin/intentions/{id}/status', [AdminController::class, 'updateStatus']);
    });

    // Role: super_admin or soccom
    Route::middleware('role:super_admin,soccom')->group(function () {
        Route::resource('/admin/schedules', ScheduleController::class)->names('admin.schedules');
        Route::resource('/admin/announcements', AnnouncementController::class)->names('admin.announcements');
        Route::resource('/admin/events', EventsController::class)->names('admin.events');
        Route::resource('/admin/gallery', App\Http\Controllers\GalleryAlbumController::class)->names('admin.gallery');
        Route::post('/admin/gallery/{album}/add-images', [App\Http\Controllers\GalleryAlbumController::class, 'addImages'])->name('admin.gallery.add-images');
        Route::delete('/admin/gallery/image/{image}', [App\Http\Controllers\GalleryAlbumController::class, 'removeImage'])->name('admin.gallery.remove-image');

        // Inquiries
        Route::get('/admin/inquiries', [InquiryController::class, 'index'])->name('admin.inquiries.index');
        Route::get('/admin/inquiries/{id}', [InquiryController::class, 'show'])->name('admin.inquiries.show');
        Route::post('/admin/inquiries/{id}/accept', [InquiryController::class, 'accept'])->name('admin.inquiries.accept');

        // Live Chat Admin
        Route::get('/admin/chats', [ChatbotController::class, 'adminIndex'])->name('admin.chats.index');
        Route::get('/admin/chats/{id}', [ChatbotController::class, 'adminShow'])->name('admin.chats.show');
        Route::post('/admin/chats/{id}/reply', [ChatbotController::class, 'adminReply'])->name('admin.chats.reply');
    });


    // Role: super_admin only
    Route::middleware('role:super_admin')->group(function () {
        // Placeholders for system management
        Route::get('/admin/users', function () {
            return view('admin.users');
        })->name('admin.users');
        Route::get('/admin/logs', function () {
            return view('admin.logs');
        })->name('admin.logs');
        Route::get('/admin/settings', function () {
            return view('admin.settings');
        })->name('admin.settings');
    });
});
