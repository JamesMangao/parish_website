<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MassScheduleController;
use App\Http\Controllers\IntentionController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminIntentionController;
use App\Http\Controllers\PptController;
use App\Http\Controllers\GoogleSlidesController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\BulletinController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\DailyReadingController;
use App\Http\Controllers\GoogleAuthController;


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('/about', 'about')->name('about');
Route::get('/mass-schedule', [MassScheduleController::class, 'index'])->name('mass-schedule');
Route::get('/mass-schedule/{id}/ical', [MassScheduleController::class, 'generateICal'])->name('mass-schedule.ical');
Route::view('/donate', 'donate')->name('donate');

// Tracking Status
Route::get('/track', [TrackController::class, 'index'])->name('track');
Route::post('/track', [TrackController::class, 'track'])->name('track.post');
Route::get('/track-intention/{refId}', [TrackController::class, 'showStatus'])->name('track.status');

// Bulletins
Route::get('/bulletins', [BulletinController::class, 'index'])->name('bulletins.index');
Route::get('/bulletins/{bulletin}/download', [BulletinController::class, 'download'])->name('bulletins.download');

Route::get('/submit-intention', [IntentionController::class, 'create'])->name('submit-intention');
Route::post('/submit-intention', [IntentionController::class, 'store'])->middleware('throttle:submissions');

Route::get('/inquiry', [InquiryController::class, 'create'])->name('inquiry');
Route::post('/inquiry', [InquiryController::class, 'store'])->middleware('throttle:submissions')->name('inquiry.store');

Route::get('/events', [EventsController::class, 'publicIndex'])->name('events');
Route::get('/events/{event}', [EventsController::class, 'publicShow'])->name('events.show');
Route::get('/gallery', [GalleryController::class, 'publicIndex'])->name('gallery');
Route::get('/gallery/{album}', [GalleryController::class, 'publicAlbum'])->name('gallery.album');

// API Proxies
Route::middleware('throttle:chat')->group(function () {
    Route::post('/api/chatbot', [ChatbotController::class, 'chat'])->name('chatbot.chat');
    Route::get('/api/chatbot/poll', [ChatbotController::class, 'poll'])->name('chatbot.poll');
    Route::post('/api/chatbot/request-agent', [ChatbotController::class, 'requestAgent'])->name('chatbot.request-agent');
});

Route::get('/api/readings/today', DailyReadingController::class);

Route::get('/admin-portal/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/admin-portal/login', [LoginController::class, 'login'])->middleware('throttle:auth')->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');



Route::middleware(['auth', 'throttle:admin'])->group(function () {
    Route::get('/admin-portal/dashboard', [DashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin-portal/notifications/count', [DashboardController::class, 'getNotifications'])->name('admin.notifications.count');
    Route::get('/admin-portal/dash-test', [DashboardController::class, 'dashTest']);

    // Role: super_admin, staff, or soccom
    Route::middleware('role:super_admin,staff,soccom')->group(function () {
        Route::post('/admin-portal/generate-ppt', [PptController::class, 'generate'])->name('admin.generate-ppt');
        Route::get('/admin-portal/preview-ppt', [PptController::class, 'preview'])->name('admin.preview-ppt');
        Route::post('/admin-portal/create-google-slides', [GoogleSlidesController::class, 'create'])->name('admin.create-google-slides');

        Route::get('/google/auth', [GoogleAuthController::class, 'auth']);
        Route::get('/google/callback', [GoogleAuthController::class, 'callback']);
    });

    // Role: super_admin or staff
    Route::middleware('role:super_admin,staff')->group(function () {
        Route::get('/admin-portal/intentions', [AdminIntentionController::class, 'index'])->name('admin.intentions');
        Route::get('/admin-portal/intentions/create', [AdminIntentionController::class, 'create'])->name('admin.intentions.create');
        Route::get('/admin-portal/intentions/{id}', [AdminIntentionController::class, 'show'])->name('admin.intentions.show');
        Route::post('/admin-portal/intentions', [AdminIntentionController::class, 'store'])->name('admin.intentions.store');
        Route::post('/admin-portal/intentions/batch', [AdminIntentionController::class, 'batchUpdateStatus'])->name('admin.intentions.batch');
        Route::post('/admin-portal/intentions/{id}/status', [AdminIntentionController::class, 'updateStatus'])->name('admin.intentions.status');
    });

    // Role: super_admin, staff, or soccom
    Route::middleware('role:super_admin,staff,soccom')->group(function () {
        // Inquiries
        Route::get('/admin-portal/inquiries', [InquiryController::class, 'index'])->name('admin.inquiries.index');
        Route::get('/admin-portal/inquiries/{id}', [InquiryController::class, 'show'])->name('admin.inquiries.show');
        Route::post('/admin-portal/inquiries/{id}/accept', [InquiryController::class, 'accept'])->name('admin.inquiries.accept');
        Route::post('/admin-portal/inquiries/{id}/decline', [InquiryController::class, 'decline'])->name('admin.inquiries.decline');
    });

    // Role: super_admin or soccom
    Route::middleware('role:super_admin,soccom')->group(function () {
        Route::resource('/admin-portal/schedules', ScheduleController::class)->names('admin.schedules');
        Route::resource('/admin-portal/announcements', AnnouncementController::class)->names('admin.announcements');
        Route::resource('/admin-portal/events', EventsController::class)->names('admin.events');
        Route::resource('/admin-portal/gallery', App\Http\Controllers\GalleryAlbumController::class)->names('admin.gallery');
        Route::post('/admin-portal/gallery/{album}/add-images', [App\Http\Controllers\GalleryAlbumController::class, 'addImages'])->name('admin.gallery.add-images');
        Route::delete('/admin-portal/gallery/image/{image}', [App\Http\Controllers\GalleryAlbumController::class, 'removeImage'])->name('admin.gallery.remove-image');

        // Video Highlights (Standalone)
        Route::resource('/admin-portal/highlights', App\Http\Controllers\VideoHighlightController::class)->names('admin.highlights');
        Route::post('/admin-portal/highlights/reorder', [App\Http\Controllers\VideoHighlightController::class, 'reorder'])->name('admin.highlights.reorder');

        // Route::get('/admin-portal/bulletins', [BulletinController::class, 'adminIndex'])->name('admin.bulletins.index');
        // Route::post('/admin-portal/bulletins', [BulletinController::class, 'store'])->name('admin.bulletins.store');
        // Route::delete('/admin-portal/bulletins/{bulletin}', [BulletinController::class, 'destroy'])->name('admin.bulletins.destroy');

        // Live Chat Admin
        Route::get('/admin-portal/chats', [ChatbotController::class, 'adminIndex'])->name('admin.chats.index');
        Route::get('/admin-portal/chats/{id}', [ChatbotController::class, 'adminShow'])->name('admin.chats.show');
        Route::post('/admin-portal/chats/{id}/reply', [ChatbotController::class, 'adminReply'])->name('admin.chats.reply');
        Route::post('/admin-portal/chats/{id}/resolve', [ChatbotController::class, 'resolve'])->name('admin.chats.resolve');
        Route::post('/admin-portal/chats/{id}/pause', [ChatbotController::class, 'pause'])->name('admin.chats.pause');
        Route::post('/admin-portal/chats/{id}/resume', [ChatbotController::class, 'resume'])->name('admin.chats.resume');
        Route::get('/admin-portal/chats/{id}/poll', [ChatbotController::class, 'adminPoll'])->name('admin.chats.poll');
        Route::post('/admin-portal/chats/{id}/typing', [ChatbotController::class, 'adminTyping'])->name('admin.chats.typing');
    });


    // Role: super_admin only
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/admin-portal/users', [\App\Http\Controllers\UserController::class, 'index'])->name('admin.users');
        Route::post('/admin-portal/users', [\App\Http\Controllers\UserController::class, 'store'])->name('admin.users.store');
        Route::put('/admin-portal/users/{user}', [\App\Http\Controllers\UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin-portal/users/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('admin.users.destroy');
        Route::get('/admin-portal/logs', [DashboardController::class, 'logs'])->name('admin.logs');
        Route::get('/admin-portal/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('admin.settings');
        Route::post('/admin-portal/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('admin.settings.update');
    });
});