<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\MassIntention;
use Illuminate\Http\Request;

class TrackController extends Controller
{
    public function index()
    {
        return view('track-status');
    }

    public function track(Request $request)
    {
        $validated = $request->validate([
            'reference_id' => 'required|string',
        ]);

        return $this->showStatus($validated['reference_id']);
    }

    public function showStatus($refId)
    {
        // Try searching in Mass Intentions
        $intention = MassIntention::where('reference_number', $refId)->first();
        
        if ($intention) {
            return view('track-status', [
                'type' => 'Mass Intention',
                'item' => $intention,
                'status' => $intention->status,
                'date' => $intention->preferred_date,
                'refId' => $refId
            ]);
        }

        // Try searching in Inquiries
        $inquiry = Inquiry::where('reference_id', $refId)->first();

        if ($inquiry) {
            return view('track-status', [
                'type' => 'Sacramental Inquiry',
                'item' => $inquiry,
                'status' => $inquiry->status,
                'date' => $inquiry->preferred_date,
                'refId' => $refId
            ]);
        }

        return redirect()->route('track')->withErrors(['reference_id' => 'Invalid Reference ID. Please check and try again.']);
    }
}
