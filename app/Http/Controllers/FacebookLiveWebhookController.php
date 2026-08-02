<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookLiveWebhookController extends Controller
{
    /** Cache key read by the live-mass-slot blade component. */
    public const CACHE_KEY = 'facebook_live_permalink';

    /**
     * Facebook's one-time verification handshake when you save the
     * Callback URL in the Webhooks product settings.
     */
    public function verify(Request $request)
    {
        $verifyToken = config('services.facebook.webhook_verify_token');

        if (
            $request->query('hub_mode') === 'subscribe' &&
            hash_equals((string) $verifyToken, (string) $request->query('hub_verify_token'))
        ) {
            return response((string) $request->query('hub_challenge'), 200);
        }

        return response('Forbidden', 403);
    }

    /**
     * Real-time notification every time the Page's live video status changes
     * (goes live, ends, becomes a VOD, etc).
     */
    public function handle(Request $request)
    {
        $payload = $request->getContent();

        if (! $this->hasValidSignature($request, $payload)) {
            Log::warning('Facebook webhook: signature mismatch, ignoring payload.');
            return response('Invalid signature', 403);
        }

        $data = json_decode($payload, true) ?? [];

        foreach ($data['entry'] ?? [] as $entry) {
            foreach ($entry['changes'] ?? [] as $change) {
                if (($change['field'] ?? null) !== 'live_videos') {
                    continue;
                }

                $liveVideoId = $change['value']['id'] ?? null;
                $status = strtolower((string) ($change['value']['status'] ?? ''));

                if (! $liveVideoId) {
                    continue;
                }

                if ($status === 'live') {
                    $this->cacheLivePermalink((string) $liveVideoId);
                } else {
                    Cache::forget(self::CACHE_KEY);
                }
            }
        }

        return response('OK', 200);
    }

    protected function hasValidSignature(Request $request, string $payload): bool
    {
        $appSecret = config('services.facebook.app_secret');
        $signature = $request->header('X-Hub-Signature-256');

        if (! $appSecret || ! $signature) {
            return false;
        }

        $expected = 'sha256=' . hash_hmac('sha256', $payload, $appSecret);

        return hash_equals($expected, $signature);
    }

    protected function cacheLivePermalink(string $liveVideoId): void
    {
        $token = config('services.facebook.page_access_token');

        $response = Http::get("https://graph.facebook.com/v21.0/{$liveVideoId}", [
            'fields' => 'permalink_url,status',
            'access_token' => $token,
        ]);

        if (! $response->ok()) {
            Log::warning('Facebook webhook: failed to fetch live video details.', [
                'body' => $response->body(),
            ]);
            return;
        }

        $permalink = $response->json('permalink_url');

        if (! $permalink) {
            return;
        }

        if (! str_starts_with($permalink, 'http')) {
            $permalink = 'https://www.facebook.com' . $permalink;
        }

        Cache::put(self::CACHE_KEY, $permalink, now()->addHours(4));
    }
}
