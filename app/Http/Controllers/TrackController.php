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

    public function showStatus($query)
    {
        $queryStr = trim($query);

        // Check if query is an email address
        if (filter_var($queryStr, FILTER_VALIDATE_EMAIL)) {
            $intentions = MassIntention::where('email', $queryStr)->latest()->get();
            $inquiries = Inquiry::where('email', $queryStr)->latest()->get();

            if ($intentions->isEmpty() && $inquiries->isEmpty()) {
                return redirect()->route('track')->withErrors(['reference_id' => 'No records found for email: ' . $queryStr]);
            }

            return view('track-status', [
                'type' => 'Multiple Records',
                'intentions' => $intentions,
                'inquiries' => $inquiries,
                'searchQuery' => $queryStr,
            ]);
        }

        // Search by reference ID in Mass Intentions
        $intention = MassIntention::where('reference_number', $queryStr)->first();
        
        if ($intention) {
            return view('track-status', [
                'type' => 'Mass Intention',
                'item' => $intention,
                'status' => $intention->status,
                'date' => $intention->preferred_date,
                'refId' => $queryStr
            ]);
        }

        // Search by reference ID in Inquiries
        $inquiry = Inquiry::where('reference_id', $queryStr)->first();

        if ($inquiry) {
            return view('track-status', [
                'type' => 'Sacramental Inquiry',
                'item' => $inquiry,
                'status' => $inquiry->status,
                'date' => $inquiry->preferred_date,
                'refId' => $queryStr
            ]);
        }

        return redirect()->route('track')->withErrors(['reference_id' => 'No record matching Reference ID or Email found. Please check and try again.']);
    }
}
