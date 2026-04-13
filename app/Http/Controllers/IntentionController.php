<?php

namespace App\Http\Controllers;

use App\Models\MassIntention;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

        $intention = MassIntention::create([
            'full_name' => $validated['fullName'],
            'email' => $validated['email'],
            'intention_type' => $validated['intentionType'],
            'raw_message' => $validated['description'],
            'preferred_date' => $validated['preferredDate'],
            'mass_time' => $validated['massTime'],
            'status' => 'pending',
            'payment_method' => $validated['paymentMethod'],
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Your mass intention has been submitted.',
                'refId' => \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($intention->id, 0, 8))
            ]);
        }

        return back()->with('success', 'Your mass intention has been submitted. Reference ID: ' . \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($intention->id, 0, 8)));
    }
}
