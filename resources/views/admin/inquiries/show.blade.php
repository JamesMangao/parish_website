<x-admin-layout>
    <div class="max-w-5xl mx-auto" x-data="{ showRejection: false }">
        <div class="mb-8 flex items-center justify-between">
            <a href="{{ route('admin.inquiries.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-muted-foreground/50 hover:text-primary transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6" /></svg>
                Back to Inquiries
            </a>

            @if($inquiry->status === 'pending')
                <div class="flex items-center gap-3">
                    <button @click="showRejection = true" class="px-5 py-2.5 rounded-xl border border-red-200 text-red-500 text-[10px] font-bold uppercase tracking-[.1em] hover:bg-red-50 transition-all">
                        Decline
                    </button>
                    <form action="{{ route('admin.inquiries.accept', $inquiry->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-primary-foreground text-[10px] font-black uppercase tracking-[.1em] shadow-lg shadow-primary/15 hover:shadow-xl transition-all active:scale-[0.97] flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" /><polyline points="22 4 12 14.01 9 11.01" /></svg>
                            Validate & Forward
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-2xl border border-black/[.04] shadow-sm shadow-black/[.02] overflow-hidden relative">
            <div class="absolute top-6 right-6">
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-[.1em] border
                    {{ $inquiry->status === 'accepted' ? 'bg-emerald-50 text-emerald-600 border-emerald-200/80' : '' }}
                    {{ $inquiry->status === 'declined' ? 'bg-red-50 text-red-500 border-red-200/80' : '' }}
                    {{ $inquiry->status === 'pending' ? 'bg-amber-50 text-amber-600 border-amber-200/80' : '' }}">
                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-60 mr-1.5"></span>
                    {{ $inquiry->status }}
                </span>
            </div>

            <div class="px-8 py-8 border-b border-black/[.04] bg-gradient-to-r from-[#F5F7FA] to-white">
                <p class="text-[10px] font-black uppercase tracking-[.2em] text-primary/30 mb-1">Inquiry Details</p>
                <h1 class="font-heading text-3xl font-black text-primary italic">{{ $inquiry->inquiry_type }}</h1>
                <p class="text-xs font-medium text-muted-foreground/50 mt-2">Ref: <span class="font-mono font-bold">{{ $inquiry->reference_id }}</span> · Submitted {{ $inquiry->created_at->diffForHumans() }}</p>
            </div>

            <div class="p-8 space-y-8">
                <div class="grid md:grid-cols-12 gap-8">
                    <div class="space-y-1 md:col-span-3">
                        <h3 class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/40">Full Name</h3>
                        <p class="font-bold text-primary">{{ $inquiry->full_name }}</p>
                    </div>
                    <div class="space-y-1 md:col-span-6">
                        <h3 class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/40">Email Address</h3>
                        <p class="font-bold text-primary">{{ $inquiry->email }}</p>
                    </div>
                    <div class="space-y-1 md:col-span-3">
                        <h3 class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/40">Contact Number</h3>
                        <p class="font-bold text-primary">{{ $inquiry->phone ?? 'None' }}</p>
                    </div>
                </div>

                @if($inquiry->preferred_date)
                    <div class="p-5 bg-accent/5 rounded-2xl border border-accent/10 flex items-center gap-4 text-sm font-medium text-primary">
                        <div class="w-9 h-9 rounded-xl bg-accent/10 flex items-center justify-center text-accent">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v4" /><path d="M16 2v4" /><rect width="18" height="18" x="3" y="4" rx="2" /><path d="M3 10h18" /></svg>
                        </div>
                        Preferred Ceremony Date: {{ \Carbon\Carbon::parse($inquiry->preferred_date)->format('F d, Y') }}
                    </div>
                @endif

                <div class="space-y-3">
                    <h3 class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/40">Message / Request</h3>
                    <div class="bg-[#F5F7FA] p-6 rounded-2xl text-sm leading-relaxed text-primary/80 whitespace-pre-wrap border border-black/[.04]">
                        {{ $inquiry->message }}
                    </div>
                </div>

                @if($inquiry->status === 'accepted')
                    <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-5 flex items-start gap-4 text-emerald-800">
                        <div class="h-9 w-9 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5" /></svg>
                        </div>
                        <div class="text-sm">
                            <p class="font-bold mb-0.5">Validation Successful</p>
                            <p class="opacity-70 text-xs">Forwarded to the parish office on <strong>{{ $inquiry->accepted_at->format('M d, Y h:i A') }}</strong>.</p>
                        </div>
                    </div>
                @elseif($inquiry->status === 'declined')
                    <div class="bg-red-50 border border-red-100 rounded-2xl p-5 flex items-start gap-4 text-red-800">
                        <div class="h-9 w-9 rounded-xl bg-red-100 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10" /><line x1="15" x2="9" y1="9" y2="15" /><line x1="9" x2="15" y1="9" y2="15" /></svg>
                        </div>
                        <div class="text-sm">
                            <p class="font-bold mb-1">Inquiry Declined</p>
                            <div class="bg-white/60 p-3 rounded-xl text-xs">{{ $inquiry->rejection_reason }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Rejection Modal --}}
        <div x-show="showRejection" 
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/30 backdrop-blur-sm" x-cloak
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
            <div class="bg-white max-w-lg w-full rounded-2xl shadow-2xl border border-black/[.06] p-8">
                <h3 class="text-xl font-bold text-primary font-heading mb-2">Confirm Rejection</h3>
                <p class="text-sm text-muted-foreground mb-6 leading-relaxed">Provide a reason why this inquiry cannot be processed.</p>
                <form action="{{ route('admin.inquiries.decline', $inquiry->id) }}" method="POST">
                    @csrf
                    <textarea name="reason" required rows="4" placeholder="e.g. Preferred date is already fully booked..."
                        class="w-full rounded-xl border border-black/[.06] bg-[#F5F7FA] p-4 text-sm focus:ring-2 focus:ring-primary/20 mb-6"></textarea>
                    <div class="flex items-center justify-end gap-3">
                        <button type="button" @click="showRejection = false" class="px-5 py-2.5 rounded-xl text-sm font-bold text-muted-foreground hover:bg-[#F5F7FA] transition-colors">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 bg-red-500 text-white rounded-xl text-xs font-bold shadow-lg shadow-red-500/20 hover:bg-red-600 transition-all active:scale-[0.97]">Submit & Notify</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
