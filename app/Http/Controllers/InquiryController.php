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
            'message' => 'required|string',
        ]);

        Inquiry::create([
            'full_name' => $validated['fullName'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'inquiry_type' => $validated['inquiryType'],
            'message' => $validated['message'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Your inquiry has been submitted. Our team will review it soon.');
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
        }

        return back()->with('success', 'Inquiry accepted and forwarded to the parish office.');
    }
}
