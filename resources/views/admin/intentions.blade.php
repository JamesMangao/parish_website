<x-admin-layout>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-heading text-3xl font-bold text-primary italic text-primary">Mass Intentions</h1>
            <p class="text-sm text-muted-foreground mt-1">Review and manage mass intention submissions.</p>
        </div>
        
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.intentions.create') }}" class="inline-flex items-center gap-2 bg-primary text-primary-foreground px-4 py-2 rounded-lg font-bold text-xs shadow-lg shadow-primary/20 hover:scale-105 active:scale-95 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-circle"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>
                Create New Intention
            </a>
            @php $currentFilter = request('status', 'all'); @endphp
            @foreach(['all', 'pending', 'approved', 'rejected'] as $f)
                <a 
                    href="/admin/intentions?status={{ $f }}" 
                    class="px-4 py-1.5 rounded-md text-xs font-bold transition-all capitalize border {{ $currentFilter === $f ? 'bg-primary text-primary-foreground border-primary' : 'bg-white text-muted-foreground border-border hover:border-primary hover:text-primary' }}"
                >
                    {{ $f }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="bg-card rounded-xl border shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-muted/50 border-b">
                    <tr>
                        <th class="px-6 py-4 font-bold text-primary">Name</th>
                        <th class="px-6 py-4 font-bold text-primary">Type</th>
                        <th class="px-6 py-4 font-bold text-primary">Donation</th>
                        <th class="px-6 py-4 font-bold text-primary">Preferred Date</th>
                        <th class="px-6 py-4 font-bold text-primary">Status</th>
                        <th class="px-6 py-4 font-bold text-primary text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach($intentions as $i)
                        <tr class="hover:bg-muted/20 transition-colors">
                            <td class="px-6 py-4 font-bold text-primary">{{ $i->full_name ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <span class="block font-medium">{{ $i->intention_type ?? '—' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($i->payment_method)
                                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md border text-[10px] font-bold uppercase tracking-wider {{ $i->payment_method === 'GCash' ? 'bg-blue-50 text-blue-600 border-blue-200' : 'bg-primary/5 text-primary border-primary/20' }}">
                                        {{ $i->payment_method }}
                                    </span>
                                @else
                                    <span class="text-muted-foreground italic text-xs">Cash/None</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-muted-foreground whitespace-nowrap">
                                <span class="block font-bold text-primary">{{ (isset($i->preferred_date) && $i->preferred_date) ? $i->preferred_date->format('M d, Y') : '—' }}</span>
                                <span class="text-[10px] font-medium uppercase tracking-widest">{{ $i->mass_time ?? 'No time set' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-accent/10 text-accent border-accent/20',
                                        'approved' => 'bg-green-100 text-green-700 border-green-200',
                                        'rejected' => 'bg-destructive/10 text-destructive border-destructive/20',
                                    ];
                                @endphp
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $statusClasses[$i->status ?? 'pending'] ?? '' }}">
                                    {{ $i->status ?? 'unknown' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if(($i->status ?? '') === 'pending')
                                    <div class="flex items-center justify-end gap-2">
                                        <form action="/admin/intentions/{{ $i->id }}/status" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="approved">
                                            <button class="p-1.5 rounded-md border border-green-200 text-green-600 hover:bg-green-600 hover:text-white transition-all" title="Approve">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                            </button>
                                        </form>
                                        <form action="/admin/intentions/{{ $i->id }}/status" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="rejected">
                                            <button class="p-1.5 rounded-md border border-destructive/20 text-destructive hover:bg-destructive hover:text-white transition-all" title="Reject">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-muted-foreground">{{ isset($i->updated_at) ? $i->updated_at->format('M d, H:i') : '—' }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @if(count($intentions) === 0)
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-muted-foreground italic">
                                No intentions found for this filter.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
