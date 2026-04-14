<?php

namespace App\Http\Controllers;

use App\Models\MassIntention;
use App\Mail\IntentionReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class IntentionController extends Controller
{
    public function create()
    {
        return view('submit-intention');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'intentionType' => 'required|string',
            'preferredDate' => 'nullable|date|after_or_equal:today',
            'massTime' => 'nullable|string',
            'description' => 'required|string',
            'paymentMethod' => 'nullable|string',
        ]);

        $year = date('Y');
        $count = MassIntention::whereYear('created_at', $year)->count() + 1;
        $refNumber = 'SRP-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        $intention = MassIntention::create([
            'reference_number' => $refNumber,
            'full_name' => $validated['fullName'],
            'email' => $validated['email'],
            'intention_type' => $validated['intentionType'],
            'raw_message' => $validated['description'],
            'preferred_date' => $validated['preferredDate'],
            'mass_time' => $validated['massTime'],
            'status' => 'pending',
            'payment_method' => $validated['paymentMethod'],
        ]);

        // Send confirmation email
        try {
            Mail::to($validated['email'])->send(new IntentionReceived($intention));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send intention email: ' . $e->getMessage());
        }

        $refId = $refNumber;

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Your mass intention has been submitted.',
                'refId' => $refId
            ]);
        }

        return back()->with('success', 'Your mass intention has been submitted. Reference ID: ' . $refId);
    }
}
