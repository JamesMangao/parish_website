<x-admin-layout>
    <div class="px-6 py-12 max-w-5xl mx-auto" x-data="{ showRejection: false }">
        <div class="mb-8 flex items-center justify-between">
            <a href="{{ route('admin.intentions') }}" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-muted-foreground hover:text-primary transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Back to Intentions
            </a>

            @if($intention->status === 'pending')
                <div class="flex items-center gap-3">
                    <button @click="showRejection = true" class="px-4 py-2 rounded-xl border-2 border-destructive/20 text-destructive text-[10px] font-black uppercase tracking-widest hover:bg-destructive/5 transition-all">
                        Reject Intention
                    </button>
                    <form action="{{ route('admin.intentions.status', $intention->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="px-6 py-2 rounded-xl bg-accent text-accent-foreground text-[10px] font-black uppercase tracking-widest hover:shadow-lg hover:brightness-110 transition-all active:scale-95 shadow-md flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            Approve Intention
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <div class="bg-card rounded-[2.5rem] border shadow-2xl overflow-hidden relative">
            <div class="absolute top-0 right-0 p-8">
                @php
                    $statusClasses = [
                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'approved' => 'bg-green-50 text-green-700 border-green-200',
                        'rejected' => 'bg-red-50 text-red-700 border-red-200',
                    ];
                @endphp
                <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm {{ $statusClasses[$intention->status] ?? '' }}">
                    {{ $intention->status }}
                </span>
            </div>

            <div class="p-10 border-b bg-muted/20">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/40 mb-1">Intention Details</p>
                <h1 class="font-heading text-4xl font-black text-primary italic">{{ $intention->intention_type }}</h1>
                <p class="text-xs font-medium text-muted-foreground mt-2">Ref: <span class="font-mono font-bold">{{ $intention->reference_number ?? substr($intention->id, 0, 8) }}</span> • Submitted {{ $intention->created_at->diffForHumans() }}</p>
            </div>

            <div class="p-10 space-y-12">
                <div class="grid md:grid-cols-12 gap-10">
                    <div class="space-y-1 md:col-span-4">
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60">Full Name</h3>
                        <p class="font-bold text-primary text-lg">{{ $intention->full_name }}</p>
                    </div>
                    <div class="space-y-1 md:col-span-5">
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60">Email Address</h3>
                        <p class="font-bold text-primary text-lg">{{ $intention->email ?? 'Not provided' }}</p>
                    </div>
                    <div class="space-y-1 md:col-span-3">
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60">Donation Method</h3>
                        <div class="pt-1">
                            @if($intention->payment_method)
                                <span class="px-3 py-1 rounded-lg border text-[11px] font-black uppercase tracking-widest {{ $intention->payment_method === 'GCash' ? 'bg-blue-50 text-blue-600 border-blue-200' : 'bg-primary/5 text-primary border-primary/20' }}">
                                    {{ $intention->payment_method }}
                                </span>
                            @else
                                <span class="text-muted-foreground italic text-sm">Cash/None</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-accent/5 rounded-2xl border border-accent/10 flex flex-col md:flex-row md:items-center gap-6 italic font-medium text-primary">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 rounded-xl bg-accent/10 flex items-center justify-center text-accent">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                        </div>
                        Preferred Date: {{ $intention->preferred_date ? $intention->preferred_date->format('F d, Y') : 'Any Date' }}
                    </div>
                    <div class="hidden md:block h-8 w-px bg-accent/20"></div>
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 rounded-xl bg-accent/10 flex items-center justify-center text-accent">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        Mass Time: {{ $intention->mass_time ?? 'Standard Mass' }}
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60">The Intention Message</h3>
                    <div class="bg-muted/30 p-8 rounded-3xl text-lg leading-relaxed text-primary/90 whitespace-pre-wrap border italic shadow-inner">
                        {{ $intention->raw_message ?? 'No specific message provided.' }}
                    </div>
                </div>

                @if($intention->status === 'approved')
                    <div class="bg-green-50 border border-green-100 rounded-3xl p-6 flex items-start gap-4 text-green-800 italic">
                        <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center shrink-0 border border-green-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        </div>
                        <div class="text-sm">
                            <p class="font-bold mb-1">Intention Approved</p>
                            <p class="opacity-80">This intention was officially reviewed and scheduled for inclusion in the mass liturgy on <strong>{{ $intention->updated_at->format('M d, Y h:i A') }}</strong>.</p>
                        </div>
                    </div>
                @elseif($intention->status === 'rejected')
                    <div class="bg-red-50 border border-red-100 rounded-3xl p-6 flex items-start gap-4 text-red-800 italic">
                        <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center shrink-0 border border-red-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>
                        </div>
                        <div class="text-sm">
                            <p class="font-bold mb-1">Intention Rejected</p>
                            <p class="opacity-80 font-bold mb-2">Reason for rejection:</p>
                            <div class="bg-white/50 p-4 rounded-xl border border-red-100/50">
                                {{ $intention->rejection_reason }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Rejection Modal -->
        <div x-show="showRejection" 
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-background/60 backdrop-blur-sm"
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
        >
            <div class="bg-white max-w-lg w-full rounded-[2.5rem] shadow-2xl border p-10 animate-in zoom-in-95 duration-200">
                <h3 class="text-2xl font-black text-primary italic font-heading mb-2">Confirm Rejection</h3>
                <p class="text-sm text-muted-foreground mb-8 leading-relaxed italic">Please provide a reason why this intention cannot be processed. This will be recorded in the system logs and the user will be notified if an email was provided.</p>
                
                <form action="{{ route('admin.intentions.status', $intention->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="rejected">
                    <textarea 
                        name="rejection_reason" 
                        required 
                        rows="4"
                        placeholder="e.g. Duplicated submission, incorrect timing, or offensive content."
                        class="w-full rounded-2xl border-border bg-muted/20 p-4 text-sm focus:ring-accent focus:border-accent italic font-medium mb-8"
                    ></textarea>

                    <div class="flex items-center justify-end gap-3">
                        <button type="button" @click="showRejection = false" class="px-6 py-2 rounded-xl text-sm font-bold text-muted-foreground hover:bg-muted transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-8 py-2 bg-destructive text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-destructive/20 hover:brightness-110 transition-all active:scale-95">
                            Submit & Reject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
