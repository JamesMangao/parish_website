<x-admin-layout>
    <div x-data="{ 
        selected: [], 
        showRejectionModal: false, 
        rejectionReason: '', 
        singleRejectId: null,
        toggleAll() {
            if (this.selected.length > 0 && this.selected.length === {{ count($intentions) }}) {
                this.selected = [];
            } else {
                this.selected = @json($intentions->pluck('id')->toArray());
            }
        },
        openReject(id = null) {
            this.singleRejectId = id;
            this.showRejectionModal = true;
        },
        submitReject() {
            if (this.singleRejectId) {
                // Submit single rejection
                const form = document.getElementById('reject-form-' + this.singleRejectId);
                const reasonInput = document.createElement('input');
                reasonInput.type = 'hidden';
                reasonInput.name = 'rejection_reason';
                reasonInput.value = this.rejectionReason;
                form.appendChild(reasonInput);
                form.submit();
            } else if (this.selected.length > 0) {
                // Submit batch rejection
                document.getElementById('batch-action-type').value = 'rejected';
                document.getElementById('batch-rejection-reason').value = this.rejectionReason;
                document.getElementById('batch-form').submit();
            }
        }
    }">
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

    <!-- Batch Actions Bar -->
    <div x-show="selected.length > 0" x-transition 
         class="mb-4 p-4 bg-primary/5 border border-primary/20 rounded-xl flex items-center justify-between animate-in slide-in-from-top-4 duration-300">
        <div class="flex items-center gap-3">
            <span class="text-sm font-bold text-primary italic">
                <span x-text="selected.length"></span> intentions selected
            </span>
        </div>
        <div class="flex items-center gap-2">
            <form id="batch-form" action="{{ route('admin.intentions.batch') }}" method="POST" class="hidden">
                @csrf
                <template x-for="id in selected" :key="id">
                    <input type="hidden" name="ids[]" :value="id">
                </template>
                <input type="hidden" name="status" id="batch-action-type">
                <input type="hidden" name="rejection_reason" id="batch-rejection-reason">
            </form>
            <button @click="document.getElementById('batch-action-type').value = 'approved'; document.getElementById('batch-form').submit()" 
                    class="px-4 py-2 bg-green-600 text-white rounded-lg text-xs font-bold shadow-md hover:bg-green-700 transition-all flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                Approve Selected
            </button>
            <button @click="openReject()" 
                    class="px-4 py-2 bg-destructive text-white rounded-lg text-xs font-bold shadow-md hover:bg-destructive/90 transition-all flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                Reject Selected
            </button>
            <button @click="selected = []" class="text-xs text-muted-foreground hover:text-primary font-bold px-2">Cancel</button>
        </div>
    </div>

    <div class="bg-card rounded-xl border shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-muted/50 border-b">
                    <tr>
                        <th class="px-6 py-4 w-10">
                            <input type="checkbox" @click="toggleAll()" :checked="selected.length > 0 && selected.length === {{ count($intentions) }}" class="rounded border-gray-300 text-primary focus:ring-primary">
                        </th>
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
                        <tr class="hover:bg-muted/20 transition-colors" :class="selected.includes('{{ $i->id }}') ? 'bg-primary/5' : ''">
                            <td class="px-6 py-4">
                                <input type="checkbox" x-model="selected" value="{{ $i->id }}" class="rounded border-gray-300 text-primary focus:ring-primary">
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-primary">{{ $i->full_name ?? '—' }}</div>
                                <div class="text-[10px] text-muted-foreground font-mono uppercase">{{ (string) $i->reference_number ?? substr($i->id, 0, 8) }}</div>
                            </td>
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
                                        <form id="reject-form-{{ $i->id }}" action="/admin/intentions/{{ $i->id }}/status" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="button" @click="openReject('{{ $i->id }}')" class="p-1.5 rounded-md border border-destructive/20 text-destructive hover:bg-destructive hover:text-white transition-all" title="Reject">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-circle"><circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div class="flex flex-col items-end">
                                        <span class="text-[10px] text-muted-foreground">{{ isset($i->updated_at) ? $i->updated_at->format('M d, H:i') : '—' }}</span>
                                        @if($i->rejection_reason)
                                            <span class="text-[9px] text-destructive italic font-medium mt-1 truncate max-w-[150px]" title="{{ $i->rejection_reason }}">Reason: {{ $i->rejection_reason }}</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    @if(count($intentions) === 0)
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-muted-foreground italic">
                                No intentions found for this filter.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Rejection Reason Modal -->
    <div x-show="showRejectionModal" x-cloak 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-primary/20 backdrop-blur-sm animate-in fade-in duration-200">
        <div @click.away="showRejectionModal = false" 
             class="bg-white max-w-md w-full rounded-2xl shadow-2xl border p-6 animate-in zoom-in-95 duration-200">
            <h3 class="text-xl font-bold text-primary italic font-heading mb-2">Rejection Reason</h3>
            <p class="text-sm text-muted-foreground mb-4">Please provide a reason for rejecting the selected intention(s). This will be recorded for reference.</p>
            
            <textarea x-model="rejectionReason" 
                      class="w-full min-h-[100px] rounded-lg border border-input bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring mb-6"
                      placeholder="e.g. Offensive language, duplicated entry, invalid date..."></textarea>
            
            <div class="flex items-center justify-end gap-3">
                <button @click="showRejectionModal = false; rejectionReason = ''; singleRejectId = null" 
                        class="px-5 py-2 rounded-md text-sm font-bold text-muted-foreground hover:bg-muted transition-colors">
                    Cancel
                </button>
                <button @click="submitReject()" 
                        class="px-5 py-2 bg-destructive text-white rounded-md text-sm font-bold shadow-md hover:bg-destructive/90 transition-all">
                    Reject Now
                </button>
            </div>
        </div>
    </div>
    </div>
</x-admin-layout>
