<?php

namespace App\Http\Controllers;

use App\Models\MassIntention;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\StoreIntentionRequest;

class AdminIntentionController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');
        $query = MassIntention::orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $intentions = $query->get();
        return view('admin.intentions', compact('intentions'));
    }

    public function show($id)
    {
        $intention = MassIntention::findOrFail($id);
        return view('admin.intentions-show', compact('intention'));
    }

    public function create()
    {
        return view('admin.intentions-create');
    }

    public function store(StoreIntentionRequest $request)
    {
        $validated = $request->validated();

        $duplicate = MassIntention::where('full_name', $validated['fullName'])
            ->where('intention_type', $validated['intentionType'])
            ->where('status', 'pending')
            ->first();

        if ($duplicate && !$request->input('force_submit')) {
            return back()->with([
                'duplicate_warning' => true,
                'duplicate_ref' => $duplicate->reference_number,
                'duplicate_type' => $validated['intentionType'],
            ])->withInput();
        }

        MassIntention::create([
            'full_name' => $validated['fullName'],
            'intention_type' => $validated['intentionType'],
            'raw_message' => $validated['description'] ?? null,
            'preferred_date' => $validated['preferredDate'],
            'mass_time' => $validated['massTime'],
            'status' => 'approved',
            'payment_method' => $validated['paymentMethod'],
            'reviewed_by' => auth()->id(),
            'reference_number' => $this->generateReferenceNumber(),
        ]);

        LogService::log('create_intention', null, [
            'full_name' => $validated['fullName'],
            'intention_type' => $validated['intentionType'],
            'preferred_date' => $validated['preferredDate'],
        ]);

        return redirect()->route('admin.intentions')->with('success', 'Mass intention created successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'rejection_reason' => 'nullable|string'
        ]);

        $intention = MassIntention::findOrFail($id);
        $oldStatus = $intention->status;
        $newStatus = $request->input('status');

        $intention->update([
            'status' => $newStatus,
            'rejection_reason' => $newStatus === 'rejected' ? $request->input('rejection_reason') : null,
            'reviewed_by' => auth()->id(),
        ]);

        try {
            if ($intention->email) {
                Notification::route('mail', $intention->email)
                    ->notify(new \App\Notifications\IntentionStatusUpdated($intention));
            }
        } catch (\Exception $e) {
            \Log::error('Status update notification failed: ' . $e->getMessage());
        }

        LogService::log("status_update_{$newStatus}", $intention, [
            'old_status' => $oldStatus,
            'reason' => $intention->rejection_reason
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status updated.',
                'intention' => $intention->fresh(['id', 'status', 'reference_number']),
            ]);
        }

        return back()->with('success', 'Status updated.');
    }

    public function batchUpdateStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:mass_intentions,id',
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'nullable|string'
        ]);

        $ids = $request->input('ids');
        $status = $request->input('status');
        $reason = $request->input('rejection_reason');

        MassIntention::whereIn('id', $ids)->update([
            'status' => $status,
            'rejection_reason' => $status === 'rejected' ? $reason : null,
            'reviewed_by' => auth()->id(),
            'updated_at' => now(),
        ]);

        if ($status === 'approved' || $status === 'rejected') {
            $intentions = MassIntention::whereIn('id', $ids)->whereNotNull('email')->get();
            foreach ($intentions as $intention) {
                try {
                    Notification::route('mail', $intention->email)
                        ->notify(new \App\Notifications\IntentionStatusUpdated($intention));
                } catch (\Exception $e) {
                    \Log::error('Batch notification failed for ' . $intention->id . ': ' . $e->getMessage());
                }
            }
        }

        LogService::log("batch_status_update_{$status}", null, [
            'ids' => $ids,
            'reason' => $reason
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => count($ids) . ' intentions updated.',
                'updated' => MassIntention::whereIn('id', $ids)->get(['id', 'status', 'reference_number']),
            ]);
        }

        return back()->with('success', count($ids) . ' intentions updated.');
    }

    private function generateReferenceNumber(): string
    {
        $year = date('Y');
        $count = MassIntention::whereYear('created_at', $year)->count() + 1;
        return 'SRP-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
