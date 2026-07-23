<x-admin-layout>
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="font-heading text-3xl font-bold text-primary italic">Donations</h1>
            <p class="text-sm text-muted-foreground mt-1">Track and manage all parish donations.</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid gap-4 sm:grid-cols-3 mb-8">
        <div class="bg-card rounded-xl border p-5 flex flex-col justify-between shadow-sm border-l-4 border-l-green-500">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black text-muted-foreground uppercase tracking-widest">Total Received</span>
                <div class="p-2 bg-green-100 rounded text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
            </div>
            <p class="text-3xl font-black text-green-600 tracking-tighter">₱{{ number_format($totalPaid / 100, 2) }}</p>
        </div>
        <div class="bg-card rounded-xl border p-5 flex flex-col justify-between shadow-sm border-l-4 border-l-primary">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black text-muted-foreground uppercase tracking-widest">Today</span>
                <div class="p-2 bg-primary/10 rounded text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                </div>
            </div>
            <p class="text-3xl font-black text-primary tracking-tighter">₱{{ number_format($todayPaid / 100, 2) }}</p>
        </div>
        <div class="bg-card rounded-xl border p-5 flex flex-col justify-between shadow-sm border-l-4 border-l-accent">
            <div class="flex items-center justify-between mb-2">
                <span class="text-[10px] font-black text-muted-foreground uppercase tracking-widest">Total Donations</span>
                <div class="p-2 bg-accent/10 rounded text-accent">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.505 4.04 3 5.5L12 21l7-7Z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-black text-accent tracking-tighter">{{ $totalCount }}</p>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-card rounded-xl border shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-muted/50 border-b">
                    <tr>
                        <th class="px-6 py-4 font-bold text-primary text-[11px] uppercase tracking-wider">Donor</th>
                        <th class="px-6 py-4 font-bold text-primary text-[11px] uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 font-bold text-primary text-[11px] uppercase tracking-wider">Purpose</th>
                        <th class="px-6 py-4 font-bold text-primary text-[11px] uppercase tracking-wider">Method</th>
                        <th class="px-6 py-4 font-bold text-primary text-[11px] uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 font-bold text-primary text-[11px] uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 font-bold text-primary text-[11px] uppercase tracking-wider text-right">Ref</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse($donations as $donation)
                        <tr class="hover:bg-muted/20 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-primary">{{ $donation->donor_name ?? 'Anonymous' }}</div>
                                @if($donation->donor_email)
                                    <div class="text-[10px] text-muted-foreground">{{ $donation->donor_email }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-primary">{{ $donation->formatted_amount }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-muted-foreground">{{ $donation->purpose ?? '—' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($donation->payment_method)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md border text-[10px] font-bold uppercase tracking-wider
                                        {{ match($donation->payment_method) {
                                            'gcash' => 'bg-blue-50 text-blue-600 border-blue-200',
                                            'paymaya' => 'bg-green-50 text-green-600 border-green-200',
                                            'card' => 'bg-purple-50 text-purple-600 border-purple-200',
                                            'qrph' => 'bg-amber-50 text-amber-600 border-amber-200',
                                            default => 'bg-muted text-muted-foreground border-border',
                                        } }}">
                                        {{ $donation->payment_method }}
                                    </span>
                                @else
                                    <span class="text-muted-foreground italic text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <x-admin-badge :status="$donation->status" />
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-muted-foreground whitespace-nowrap">{{ $donation->created_at->format('M d, Y') }}</div>
                                <div class="text-[10px] text-muted-foreground">{{ $donation->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-[10px] font-mono text-muted-foreground uppercase">DON-{{ strtoupper(substr($donation->id, 0, 8)) }}</span>
                            </td>
                        </tr>
                    @empty
                        <x-admin-empty
                            title="No donations yet"
                            description="Donations made through the website will appear here."
                            icon="inbox"
                        />
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($donations->hasPages())
        <div class="mt-6">
            {{ $donations->links() }}
        </div>
    @endif
</x-admin-layout>
