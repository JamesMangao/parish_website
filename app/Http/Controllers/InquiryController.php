<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Mail\InquiryAccepted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

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
        // Types that require a preferred date
        $dateRequiredTypes = ['Baptism', 'Wedding', 'Funeral Mass'];

        $validated = $request->validate([
            'fullName'      => 'required|string|max:255',
            'email'         => 'required|email|max:255',
            'phone'         => 'nullable|string|max:20',
            'inquiryType'   => 'required|string',
            'preferredDate' => [
                in_array($request->input('inquiryType'), $dateRequiredTypes)
                    ? 'required'
                    : 'nullable',
                'nullable',
                'date',
                'after_or_equal:today',
            ],
            // Accept empty string here — we validate length manually below
            // so that Alpine-driven hidden inputs don't cause silent failures.
            'message'       => 'required|string|min:3',
        ], [
            'message.required' => 'Please fill in the required details for your inquiry.',
            'message.min'      => 'Please provide more detail about your inquiry.',
            'preferredDate.required' => 'A preferred date is required for this type of inquiry.',
        ]);

        // Check for duplicate inquiries (same email + same type + pending status)
        $duplicate = Inquiry::where('email', $validated['email'])
            ->where('inquiry_type', $validated['inquiryType'])
            ->where('status', 'pending')
            ->first();

        if ($duplicate && !$request->input('force_submit')) {
            return back()->with([
                'duplicate_warning' => true,
                'duplicate_ref'     => $duplicate->reference_id,
                'duplicate_type'    => $validated['inquiryType'],
                'old_input'         => $validated,
            ])->withInput();
        }

        // Append document-request flag when present
        $message = trim($validated['message']);
        if ($request->filled('wants_document')) {
            $message .= "\n\n[Wants to request a document: " . strtoupper($request->wants_document) . "]";
        }

        $inquiry = Inquiry::create([
            'full_name'      => $validated['fullName'],
            'email'          => $validated['email'],
            'phone'          => $validated['phone'] ?? null,
            'inquiry_type'   => $validated['inquiryType'],
            'preferred_date' => $validated['preferredDate'] ?? null,
            'message'        => $message,
            'status'         => 'pending',
        ]);

        // Send submission confirmation (queued)
        try {
            \App\Jobs\SendInquirySubmittedNotification::dispatch($inquiry);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to queue InquirySubmitted notification: ' . $e->getMessage());
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Your inquiry has been submitted. Our team will review it soon.',
                'refId'   => $inquiry->reference_id,
            ]);
        }

        return back()->with([
            'success'      => 'Your inquiry has been submitted. Our team will review it soon.',
            'reference_id' => $inquiry->reference_id,
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
                'status'      => 'accepted',
                'accepted_at' => now(),
            ]);

            // Email to the parish office
            Mail::to(config('services.parish.office_email'))->send(new InquiryAccepted($inquiry));

            // Notify the parishioner
            try {
                \Illuminate\Support\Facades\Notification::route('mail', $inquiry->email)
                    ->notify(new \App\Notifications\InquiryStatusUpdated($inquiry));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('InquiryAccepted notify error: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Inquiry accepted and forwarded to the parish office.');
    }

    public function decline(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:1000']);

        $inquiry = Inquiry::findOrFail($id);
        $inquiry->update([
            'status'           => 'declined',
            'rejection_reason' => $request->reason,
        ]);

        // Notify the parishioner
        try {
            if ($inquiry->email) {
                \Illuminate\Support\Facades\Notification::route('mail', $inquiry->email)
                    ->notify(new \App\Notifications\InquiryStatusUpdated($inquiry));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('InquiryDeclined notify error: ' . $e->getMessage());
        }

        return back()->with('success', 'Inquiry has been declined with a reason.');
    }
}