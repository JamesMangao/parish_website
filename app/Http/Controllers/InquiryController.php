<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Mail\InquiryAccepted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InquiryController extends Controller
{
    public function index()
    {
        $inquiries = Inquiry::orderBy('created_at', 'desc')->get();
        return view('admin.inquiries.index', compact('inquiries'));
    }

    public function create()
    {
        return view('inquiry');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fullName' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'inquiryType' => 'required|string',
            'preferredDate' => 'nullable|date|after_or_equal:today',
            'message' => 'required|string',
        ]);

        $inquiry = Inquiry::create([
            'full_name' => $validated['fullName'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'inquiry_type' => $validated['inquiryType'],
            'preferred_date' => $validated['preferredDate'],
            'message' => $validated['message'],
            'status' => 'pending',
        ]);

        // Send confirmation email
        try {
            \Illuminate\Support\Facades\Notification::route('mail', $validated['email'])
                ->notify(new \App\Notifications\InquirySubmitted($inquiry));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Mail Error: " . $e->getMessage());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Your inquiry has been submitted. Our team will review it soon.',
                'refId' => $inquiry->reference_id
            ]);
        }

        return back()->with([
            'success' => 'Your inquiry has been submitted. Our team will review it soon.',
            'reference_id' => $inquiry->reference_id
        ]);
    }

    public function show($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        return view('admin.inquiries.show', compact('inquiry'));
    }

    public function accept($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        
        if ($inquiry->status !== 'accepted') {
            $inquiry->update([
                'status' => 'accepted',
                'accepted_at' => now(),
            ]);

            // Email to the parish office
            Mail::to(config('services.parish.office_email'))->send(new InquiryAccepted($inquiry));

            // Notify User
            \Illuminate\Support\Facades\Notification::route('mail', $inquiry->email)
                ->notify(new \App\Notifications\InquiryStatusUpdated($inquiry));
        }

        return back()->with('success', 'Inquiry accepted and forwarded to the parish office.');
    }

    public function decline(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:1000']);
        $inquiry = Inquiry::findOrFail($id);
        
        $inquiry->update([
            'status' => 'declined',
            'rejection_reason' => $request->reason,
        ]);

        // Notify User
        try {
            if ($inquiry->email) {
                \Illuminate\Support\Facades\Notification::route('mail', $inquiry->email)
                    ->notify(new \App\Notifications\InquiryStatusUpdated($inquiry));
            }
        } catch (\Exception $e) {
            // Log error but proceed
        }

        return back()->with('success', 'Inquiry has been declined with a reason.');
    }
}
