<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DonationController extends Controller
{
    /**
     * Show the donation page.
     */
    public function create()
    {
        $paymongoEnabled = !empty(config('services.paymongo.secret_key'));
        return view('donate', compact('paymongoEnabled'));
    }

    /**
     * Create a PayMongo checkout session and redirect.
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|integer|min:10000', // min ₱100 in centavos
            'donor_name' => 'nullable|string|max:255',
            'donor_email' => 'nullable|email|max:255',
            'purpose' => 'required|string|in:General Donation,Church Maintenance,Outreach,Youth Ministry',
            'message' => 'nullable|string|max:500',
        ]);

        $donation = Donation::create([
            'donor_name' => $validated['donor_name'] ?? null,
            'donor_email' => $validated['donor_email'] ?? null,
            'amount' => $validated['amount'],
            'currency' => 'PHP',
            'purpose' => $validated['purpose'],
            'message' => $validated['message'] ?? null,
            'status' => 'pending',
            'checkout_session_id' => '',
        ]);

        try {
            $response = Http::withBasicAuth(config('services.paymongo.secret_key'), '')
                ->post('https://api.paymongo.com/v2/checkout_sessions', [
                    'data' => [
                        'attributes' => [
                            'line_items' => [
                                [
                                    'name' => 'Parish Donation',
                                    'amount' => $validated['amount'],
                                    'currency' => 'PHP',
                                    'quantity' => 1,
                                ],
                            ],
                            'payment_method_types' => ['gcash', 'paymaya', 'card', 'qrph'],
                            'success_url' => route('donate.success') . '?donation_id=' . $donation->id,
                            'cancel_url' => route('donate.cancel') . '?donation_id=' . $donation->id,
                            'reference_number' => 'DON-' . strtoupper(substr($donation->id, 0, 8)),
                            'description' => 'Sto. Rosario Parish — ' . $validated['purpose'],
                        ],
                    ],
                ]);

            if ($response->failed()) {
                Log::error('PayMongo checkout creation failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                $donation->update(['status' => 'failed']);
                return back()->with('error', 'Unable to create checkout session. Please try again.');
            }

            $data = $response->json();
            $checkoutUrl = $data['data']['attributes']['checkout_url'];
            $checkoutId = $data['data']['id'];

            $donation->update(['checkout_session_id' => $checkoutId]);

            return redirect($checkoutUrl);
        } catch (\Exception $e) {
            Log::error('PayMongo checkout exception', ['error' => $e->getMessage()]);
            $donation->update(['status' => 'failed']);
            return back()->with('error', 'Payment service unavailable. Please try again later.');
        }
    }

    /**
     * Success return URL after payment.
     */
    public function success(Request $request)
    {
        $donation = null;

        if ($request->has('donation_id')) {
            $donation = Donation::find($request->donation_id);

            // Poll PayMongo to confirm payment if still pending
            if ($donation && $donation->status === 'pending' && $donation->checkout_session_id) {
                try {
                    $response = Http::withBasicAuth(config('services.paymongo.secret_key'), '')
                        ->get('https://api.paymongo.com/v2/checkout_sessions/' . $donation->checkout_session_id);

                    if ($response->successful()) {
                        $sessionData = $response->json();
                        $paymentStatus = $sessionData['data']['attributes']['payment_intent']['attributes']['status'] ?? null;

                        if ($paymentStatus === 'succeeded') {
                            $paymentId = $sessionData['data']['attributes']['payment_intent']['id'] ?? null;
                            $paymentMethod = $sessionData['data']['attributes']['payment_method_used'] ?? null;
                            $donation->update([
                                'status' => 'paid',
                                'paid_at' => now(),
                                'paymongo_payment_id' => $paymentId,
                                'payment_method' => $paymentMethod,
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning('Failed to poll PayMongo on success page', ['error' => $e->getMessage()]);
                }
            }
        }

        return view('donate-success', compact('donation'));
    }

    /**
     * Cancel return URL.
     */
    public function cancel(Request $request)
    {
        if ($request->has('donation_id')) {
            $donation = Donation::find($request->donation_id);
            if ($donation && $donation->status === 'pending') {
                $donation->update(['status' => 'expired']);
            }
        }

        return view('donate-cancel');
    }

    /**
     * Handle PayMongo webhook for payment confirmation.
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Paymongo-Signature');

        // Verify webhook signature
        $webhookSecret = config('services.paymongo.webhook_secret');
        if ($webhookSecret && $sigHeader) {
            $parts = collect(explode(',', $sigHeader))
                ->mapWithKeys(function ($part) {
                    [$key, $value] = explode('=', $part, 2);
                    return [$key => $value];
                });

            $timestamp = $parts->get('t');
            $testSignature = $parts->get('te');
            $liveSignature = $parts->get('li');

            $expectedPayload = $timestamp . '.' . $payload;
            $computedSignature = hash_hmac('sha256', $expectedPayload, $webhookSecret);

            $signature = $liveSignature ?: $testSignature;
            if (!hash_equals($computedSignature, $signature ?? '')) {
                Log::warning('PayMongo webhook signature mismatch');
                return response()->json(['error' => 'Invalid signature'], 403);
            }
        }

        $event = json_decode($payload, true);
        $eventType = $event['data']['attributes']['type'] ?? null;

        if ($eventType === 'checkout_session.payment.paid') {
            $checkoutSessionId = $event['data']['attributes']['data']['id'] ?? null;

            if ($checkoutSessionId) {
                $donation = Donation::where('checkout_session_id', $checkoutSessionId)->first();

                if ($donation && $donation->status !== 'paid') {
                    $paymentMethod = $event['data']['attributes']['data']['attributes']['payment_method_used'] ?? null;
                    $payments = $event['data']['attributes']['data']['attributes']['payments'] ?? [];
                    $paymentId = !empty($payments) ? ($payments[0]['id'] ?? null) : null;

                    $donation->update([
                        'status' => 'paid',
                        'paid_at' => now(),
                        'payment_method' => $paymentMethod,
                        'paymongo_payment_id' => $paymentId,
                    ]);

                    Log::info('Donation marked as paid via webhook', ['donation_id' => $donation->id]);
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Admin: list all donations.
     */
    public function adminIndex()
    {
        $donations = Donation::latest()->paginate(25);
        $totalPaid = Donation::where('status', 'paid')->sum('amount');
        $todayPaid = Donation::where('status', 'paid')->whereDate('paid_at', today())->sum('amount');
        $totalCount = Donation::where('status', 'paid')->count();
        return view('admin.donations', compact('donations', 'totalPaid', 'todayPaid', 'totalCount'));
    }
}
