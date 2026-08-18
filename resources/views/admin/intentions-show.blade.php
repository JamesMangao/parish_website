<x-admin-layout>
    <div class="max-w-5xl mx-auto" x-data="{ showRejection: false }">
        <div class="mb-8 flex items-center justify-between">
            <a href="{{ route('admin.intentions') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-muted-foreground/50 hover:text-primary transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6" /></svg>
                Back to Intentions
            </a>

            @if($intention->status === 'pending')
                <div class="flex items-center gap-3">
                    <button @click="showRejection = true" class="px-5 py-2.5 rounded-xl border border-red-200 text-red-500 text-[10px] font-bold uppercase tracking-[.1em] hover:bg-red-50 transition-all">
                        Reject
                    </button>
                    <form action="{{ route('admin.intentions.status', $intention->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-accent to-gold-dark text-primary text-[10px] font-black uppercase tracking-[.1em] shadow-lg shadow-accent/20 hover:shadow-xl hover:shadow-accent/30 transition-all active:scale-[0.97] flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" /><polyline points="22 4 12 14.01 9 11.01" /></svg>
                            Approve
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-2xl border border-black/[.04] shadow-sm shadow-black/[.02] overflow-hidden relative">
            <div class="absolute top-6 right-6">
                <x-admin-badge :status="$intention->status" />
            </div>

            <div class="px-8 py-8 border-b border-black/[.04] bg-gradient-to-r from-[#F5F7FA] to-white">
                <p class="text-[10px] font-black uppercase tracking-[.2em] text-primary/30 mb-1">Intention Details</p>
                <h1 class="font-heading text-3xl font-black text-primary italic">{{ $intention->intention_type }}</h1>
                <p class="text-xs font-medium text-muted-foreground/50 mt-2">Ref: <span class="font-mono font-bold">{{ $intention->reference_number ?? substr($intention->id, 0, 8) }}</span> · Submitted {{ $intention->created_at->diffForHumans() }}</p>
            </div>

            <div class="p-8 space-y-8">
                <div class="grid md:grid-cols-12 gap-8">
                    <div class="space-y-1 md:col-span-4">
                        <h3 class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/40">Full Name</h3>
                        <p class="font-bold text-primary">{{ $intention->full_name }}</p>
                    </div>
                    <div class="space-y-1 md:col-span-5">
                        <h3 class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/40">Email Address</h3>
                        <p class="font-bold text-primary">{{ $intention->email ?? 'Not provided' }}</p>
                    </div>
                    <div class="space-y-1 md:col-span-3">
                        <h3 class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/40">Donation Method</h3>
                        <div class="pt-1">
                            @if($intention->payment_method)
                                <span class="px-2.5 py-0.5 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $intention->payment_method === 'GCash' ? 'bg-blue-50 text-blue-600' : 'bg-primary/5 text-primary' }}">
                                    {{ $intention->payment_method }}
                                </span>
                            @else
                                <span class="text-muted-foreground/50 italic text-xs">Cash/None</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="p-5 bg-accent/5 rounded-2xl border border-accent/10 flex flex-col md:flex-row md:items-center gap-5 text-sm font-medium text-primary">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-accent/10 flex items-center justify-center text-accent">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2" /><line x1="16" x2="16" y1="2" y2="6" /><line x1="8" x2="8" y1="2" y2="6" /><line x1="3" x2="21" y1="10" y2="10" /></svg>
                        </div>
                        {{ $intention->preferred_date ? $intention->preferred_date->format('F d, Y') : 'Any Date' }}
                    </div>
                    <div class="hidden md:block h-6 w-px bg-accent/15"></div>
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-accent/10 flex items-center justify-center text-accent">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" /><polyline points="12 6 12 12 16 14" /></svg>
                        </div>
                        {{ $intention->mass_time ?? 'Standard Mass' }}
                    </div>
                </div>

                <div class="space-y-3">
                    <h3 class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/40">The Intention Message</h3>
                    <div class="bg-[#F5F7FA] p-6 rounded-2xl text-sm leading-relaxed text-primary/80 whitespace-pre-wrap border border-black/[.04]">
                        {{ $intention->raw_message ?? 'No specific message provided.' }}
                    </div>
                </div>

                @if($intention->status === 'approved')
                    <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-5 flex items-start gap-4 text-emerald-800">
                        <div class="h-9 w-9 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5" /></svg>
                        </div>
                        <div class="text-sm">
                            <p class="font-bold mb-0.5">Intention Approved</p>
                            <p class="opacity-70 text-xs">Reviewed and scheduled on <strong>{{ $intention->updated_at->format('M d, Y h:i A') }}</strong>.</p>
                        </div>
                    </div>
                @elseif($intention->status === 'rejected')
                    <div class="bg-red-50 border border-red-100 rounded-2xl p-5 flex items-start gap-4 text-red-800">
                        <div class="h-9 w-9 rounded-xl bg-red-100 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10" /><line x1="15" x2="9" y1="9" y2="15" /><line x1="9" x2="15" y1="9" y2="15" /></svg>
                        </div>
                        <div class="text-sm">
                            <p class="font-bold mb-1">Intention Rejected</p>
                            <div class="bg-white/60 p-3 rounded-xl text-xs">{{ $intention->rejection_reason }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Rejection Modal --}}
        <div x-show="showRejection" x-cloak
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/30 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="bg-white max-w-lg w-full rounded-2xl shadow-2xl border border-black/[.06] p-8">
                <h3 class="text-xl font-bold text-primary font-heading mb-2">Confirm Rejection</h3>
                <p class="text-sm text-muted-foreground mb-6 leading-relaxed">Provide a reason why this intention cannot be processed.</p>
                <form action="{{ route('admin.intentions.status', $intention->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="rejected">
                    <textarea name="rejection_reason" required rows="4" placeholder="e.g. Duplicated submission, incorrect timing..."
                        class="w-full rounded-xl border border-black/[.06] bg-[#F5F7FA] p-4 text-sm focus:ring-2 focus:ring-primary/20 mb-6"></textarea>
                    @error('rejection_reason')
                        <p class="text-xs text-red-500 -mt-4 mb-4">{{ $message }}</p>
                    @enderror
                    <div class="flex items-center justify-end gap-3">
                        <button type="button" @click="showRejection = false" class="px-5 py-2.5 rounded-xl text-sm font-bold text-muted-foreground hover:bg-[#F5F7FA] transition-colors">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-red-500 text-white rounded-xl text-xs font-bold shadow-lg shadow-red-500/20 hover:bg-red-600 transition-all active:scale-[0.97]">Submit & Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
