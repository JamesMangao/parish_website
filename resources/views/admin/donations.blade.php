<x-admin-layout>
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="font-heading text-3xl font-bold text-primary italic">Donations</h1>
            <p class="text-sm text-muted-foreground mt-1">Track and manage all parish donations.</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid gap-5 sm:grid-cols-3 mb-8">
        <div class="bg-white rounded-2xl border border-black/[.04] p-5 relative overflow-hidden group hover:shadow-md hover:shadow-black/[.04] transition-all duration-300">
            <div class="stat-shine"></div>
            <div class="flex items-center justify-between mb-3">
                <span class="text-[10px] font-black text-muted-foreground/50 uppercase tracking-[.15em]">Total Received</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
            </div>
            <p class="text-[2rem] font-black text-emerald-500 tracking-tighter leading-none">₱{{ number_format($totalPaid / 100, 2) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-black/[.04] p-5 relative overflow-hidden group hover:shadow-md hover:shadow-black/[.04] transition-all duration-300">
            <div class="stat-shine"></div>
            <div class="flex items-center justify-between mb-3">
                <span class="text-[10px] font-black text-muted-foreground/50 uppercase tracking-[.15em]">Today</span>
                <div class="w-9 h-9 rounded-xl bg-primary/5 flex items-center justify-center text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                </div>
            </div>
            <p class="text-[2rem] font-black text-primary tracking-tighter leading-none">₱{{ number_format($todayPaid / 100, 2) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-black/[.04] p-5 relative overflow-hidden group hover:shadow-md hover:shadow-black/[.04] transition-all duration-300">
            <div class="stat-shine"></div>
            <div class="flex items-center justify-between mb-3">
                <span class="text-[10px] font-black text-muted-foreground/50 uppercase tracking-[.15em]">Total Donations</span>
                <div class="w-9 h-9 rounded-xl bg-accent/10 flex items-center justify-center text-accent">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.505 4.04 3 5.5L12 21l7-7Z"/></svg>
                </div>
            </div>
            <p class="text-[2rem] font-black text-accent tracking-tighter leading-none">{{ $totalCount }}</p>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-black/[.04] shadow-sm shadow-black/[.02] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-black/[.04]">
                        <th class="px-6 py-4 font-bold text-primary/50 text-[10px] uppercase tracking-[.15em]">Donor</th>
                        <th class="px-6 py-4 font-bold text-primary/50 text-[10px] uppercase tracking-[.15em]">Amount</th>
                        <th class="px-6 py-4 font-bold text-primary/50 text-[10px] uppercase tracking-[.15em]">Purpose</th>
                        <th class="px-6 py-4 font-bold text-primary/50 text-[10px] uppercase tracking-[.15em]">Method</th>
                        <th class="px-6 py-4 font-bold text-primary/50 text-[10px] uppercase tracking-[.15em]">Status</th>
                        <th class="px-6 py-4 font-bold text-primary/50 text-[10px] uppercase tracking-[.15em]">Date</th>
                        <th class="px-6 py-4 font-bold text-primary/50 text-[10px] uppercase tracking-[.15em] text-right">Ref</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/[.03]">
                    @forelse($donations as $donation)
                        <tr class="hover:bg-[#F5F7FA]/60 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-primary text-[13px]">{{ $donation->donor_name ?? 'Anonymous' }}</div>
                                @if($donation->donor_email)
                                    <div class="text-[10px] text-muted-foreground/50 font-medium">{{ $donation->donor_email }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-primary">{{ $donation->formatted_amount }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-muted-foreground/60 text-xs">{{ $donation->purpose ?? '—' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($donation->payment_method)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-bold uppercase tracking-wider
                                        {{ match($donation->payment_method) {
                                            'gcash' => 'bg-blue-50 text-blue-600',
                                            'paymaya' => 'bg-emerald-50 text-emerald-600',
                                            'card' => 'bg-purple-50 text-purple-600',
                                            'qrph' => 'bg-amber-50 text-amber-600',
                                            default => 'bg-gray-50 text-gray-500',
                                        } }}">
                                        {{ $donation->payment_method }}
                                    </span>
                                @else
                                    <span class="text-muted-foreground/40 italic text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <x-admin-badge :status="$donation->status" />
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-muted-foreground/60 whitespace-nowrap text-xs">{{ $donation->created_at->format('M d, Y') }}</div>
                                <div class="text-[10px] text-muted-foreground/40 font-medium">{{ $donation->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-[10px] font-mono text-muted-foreground/40">DON-{{ strtoupper(substr($donation->id, 0, 8)) }}</span>
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

    @if($donations->hasPages())
        <div class="mt-6">
            {{ $donations->links() }}
        </div>
    @endif
</x-admin-layout>
