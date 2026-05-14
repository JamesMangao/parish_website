<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->isProduction()) {
            URL::forceScheme('https');
        }

        $this->configureRateLimiting();

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $settings = \Illuminate\Support\Facades\Cache::remember('global_settings', now()->addHours(24), function() {
                    return \App\Models\Setting::all()->pluck('value', 'key');
                });
                view()->share('global_settings', $settings);
            }
        } catch (\Exception $e) {
            // Silently fail if table doesn't exist yet
        }
    }

    protected function configureRateLimiting(): void
    {
        // ─── AUTH ROUTES — strictest ───────────────────────
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->input('email') . '|' . $request->ip())
                ->response(fn() => response()->json([
                    'message' => 'Too many attempts. Try again in 60 seconds.'
                ], 429));
        });

        // ─── PASSWORD RESET ────────────────────────────────
        RateLimiter::for('password-reset', function (Request $request) {
            return Limit::perMinute(3)
                ->by($request->input('email') . '|' . $request->ip())
                ->response(fn() => response()->json([
                    'message' => 'Too many reset attempts. Try again later.'
                ], 429));
        });

        // ─── PUBLIC FORM SUBMISSIONS ───────────────────────
        RateLimiter::for('submissions', function (Request $request) {
            return Limit::perMinute(10)
                ->by($request->ip())
                ->response(fn() => response()->json([
                    'message' => 'Submission limit reached. Please wait.'
                ], 429));
        });

        // ─── GENERAL API ───────────────────────────────────
        RateLimiter::for('api', function (Request $request) {
            return $request->user()
                ? Limit::perMinute(120)->by($request->user()->id)
                : Limit::perMinute(30)->by($request->ip());
        });

        // ─── CHAT / AI ENDPOINTS — expensive, strict ───────
        RateLimiter::for('chat', function (Request $request) {
            return Limit::perMinute(15)
                ->by($request->ip())
                ->response(fn() => response()->json([
                    'message' => 'Chat limit reached. Please wait a moment.'
                ], 429));
        });

        // ─── ADMIN PANEL ───────────────────────────────────
        RateLimiter::for('admin', function (Request $request) {
            return Limit::perMinute(60)
                ->by($request->user()?->id ?? $request->ip());
        });
    }
}
