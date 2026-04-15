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
use App\Http\Controllers\BulletinController;
use App\Http\Controllers\TrackController;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', function () { return view('about'); })->name('about');
Route::get('/mass-schedule', [MassScheduleController::class, 'index'])->name('mass-schedule');
Route::get('/mass-schedule/{id}/ical', [MassScheduleController::class, 'generateICal'])->name('mass-schedule.ical');
Route::get('/donate', function () { return view('donate'); })->name('donate');

// Tracking Status
Route::get('/track', [TrackController::class, 'index'])->name('track');
Route::post('/track', [TrackController::class, 'track'])->name('track.post');
Route::get('/track-intention/{refId}', [TrackController::class, 'showStatus'])->name('track.status');

// Bulletins
// Route::get('/bulletins', [BulletinController::class, 'index'])->name('bulletins.index');
// Route::get('/bulletins/{bulletin}/download', [BulletinController::class, 'download'])->name('bulletins.download');

Route::get('/submit-intention', [IntentionController::class, 'create'])->name('submit-intention');
Route::post('/submit-intention', [IntentionController::class, 'store'])->middleware('throttle:5,1');

Route::get('/inquiry', [InquiryController::class, 'create'])->name('inquiry');
Route::post('/inquiry', [InquiryController::class, 'store'])->middleware('throttle:5,1')->name('inquiry.store');

Route::get('/events', [EventsController::class, 'publicIndex'])->name('events');
Route::get('/events/{event}', [EventsController::class, 'publicShow'])->name('events.show');
Route::get('/gallery', [GalleryController::class, 'publicIndex'])->name('gallery');
Route::get('/gallery/{album}', [GalleryController::class, 'publicAlbum'])->name('gallery.album');

// API Proxies
Route::post('/api/chatbot', [ChatbotController::class, 'chat'])->middleware('throttle:10,1');
Route::get('/api/chatbot/poll', [ChatbotController::class, 'poll'])->name('chatbot.poll');
Route::post('/api/chatbot/request-agent', [ChatbotController::class, 'requestAgent'])->name('chatbot.request-agent');

Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [LoginController::class, 'login'])->middleware('throttle:5,1');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');



Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/notifications/count', [AdminController::class, 'getNotifications'])->name('admin.notifications.count');

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
        Route::post('/admin/intentions/batch', [AdminController::class, 'batchUpdateStatus'])->name('admin.intentions.batch');
        Route::post('/admin/intentions/{id}/status', [AdminController::class, 'updateStatus']);
    });

    // Role: super_admin, staff, or soccom
    Route::middleware('role:super_admin,staff,soccom')->group(function () {
        // Inquiries
        Route::get('/admin/inquiries', [InquiryController::class, 'index'])->name('admin.inquiries.index');
        Route::get('/admin/inquiries/{id}', [InquiryController::class, 'show'])->name('admin.inquiries.show');
        Route::post('/admin/inquiries/{id}/accept', [InquiryController::class, 'accept'])->name('admin.inquiries.accept');
        Route::post('/admin/inquiries/{id}/decline', [InquiryController::class, 'decline'])->name('admin.inquiries.decline');
    });

    // Role: super_admin or soccom
    Route::middleware('role:super_admin,soccom')->group(function () {
        Route::resource('/admin/schedules', ScheduleController::class)->names('admin.schedules');
        Route::resource('/admin/announcements', AnnouncementController::class)->names('admin.announcements');
        Route::resource('/admin/events', EventsController::class)->names('admin.events');
        Route::resource('/admin/gallery', App\Http\Controllers\GalleryAlbumController::class)->names('admin.gallery');
        Route::post('/admin/gallery/{album}/add-images', [App\Http\Controllers\GalleryAlbumController::class, 'addImages'])->name('admin.gallery.add-images');
        Route::delete('/admin/gallery/image/{image}', [App\Http\Controllers\GalleryAlbumController::class, 'removeImage'])->name('admin.gallery.remove-image');

        // Route::get('/admin/bulletins', [BulletinController::class, 'adminIndex'])->name('admin.bulletins.index');
        // Route::post('/admin/bulletins', [BulletinController::class, 'store'])->name('admin.bulletins.store');
        // Route::delete('/admin/bulletins/{bulletin}', [BulletinController::class, 'destroy'])->name('admin.bulletins.destroy');

        // Live Chat Admin
        Route::get('/admin/chats', [ChatbotController::class, 'adminIndex'])->name('admin.chats.index');
        Route::get('/admin/chats/{id}', [ChatbotController::class, 'adminShow'])->name('admin.chats.show');
        Route::post('/admin/chats/{id}/reply', [ChatbotController::class, 'adminReply'])->name('admin.chats.reply');
        Route::post('/admin/chats/{id}/resolve', [ChatbotController::class, 'resolve'])->name('admin.chats.resolve');
        Route::post('/admin/chats/{id}/typing', [ChatbotController::class, 'adminTyping'])->name('admin.chats.typing');
    });


    // Role: super_admin only
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/admin/users', [\App\Http\Controllers\UserController::class, 'index'])->name('admin.users');
        Route::post('/admin/users', [\App\Http\Controllers\UserController::class, 'store'])->name('admin.users.store');
        Route::put('/admin/users/{user}', [\App\Http\Controllers\UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('admin.users.destroy');
        Route::get('/admin/logs', [AdminController::class, 'logs'])->name('admin.logs');
        Route::get('/admin/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('admin.settings');
        Route::post('/admin/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('admin.settings.update');
    });
});
