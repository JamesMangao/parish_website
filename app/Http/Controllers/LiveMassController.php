<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LiveMassController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate(['state' => 'required|in:on,off']);

        if ($request->state === 'on') {
            Cache::put('manual_live_override', true, now()->addHours(3));
        } else {
            Cache::forget('manual_live_override');
            Cache::forget(FacebookLiveWebhookController::CACHE_KEY);
        }

        return back()->with('success', 'Live Mass override updated.');
    }

    public function updateFacebookLink(Request $request)
    {
        $request->validate([
            'facebook_live_url' => ['nullable', 'url', 'regex:/^https?:\/\/(www\.)?(facebook\.com|fb\.watch|m\.facebook\.com)\//i'],
        ]);

        $url = $request->input('facebook_live_url');

        if ($url) {
            Cache::put(FacebookLiveWebhookController::CACHE_KEY, $url, now()->addHours(4));
            return back()->with('success', 'Facebook Live link updated.');
        }

        Cache::forget(FacebookLiveWebhookController::CACHE_KEY);
        return back()->with('success', 'Facebook Live link cleared.');
    }
}
