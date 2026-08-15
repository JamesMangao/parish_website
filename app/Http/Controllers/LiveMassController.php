<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LiveMassController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate(['state' => 'required|in:on,off']);

        $request->state === 'on'
            ? Cache::put('manual_live_override', true, now()->addHours(3)) // auto-expires
            : Cache::forget('manual_live_override');

        return back()->with('success', 'Live Mass override updated.');
    }
}
