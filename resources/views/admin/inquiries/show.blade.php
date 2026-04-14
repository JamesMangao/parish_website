<x-admin-layout>
    <div class="px-6 py-12 max-w-4xl mx-auto" x-data="{ showRejection: false }">
        <div class="mb-8 flex items-center justify-between">
            <a href="{{ route('admin.inquiries.index') }}" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-muted-foreground hover:text-primary transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Back to Inquiries
            </a>

            @if($inquiry->status === 'pending')
                <div class="flex items-center gap-3">
                    <button @click="showRejection = true" class="px-4 py-2 rounded-xl border-2 border-red-200 text-red-600 text-[10px] font-black uppercase tracking-widest hover:bg-red-50 transition-all">
                        Decline Inquiry
                    </button>
                    <form action="{{ route('admin.inquiries.accept', $inquiry->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-6 py-2 rounded-xl bg-primary text-white text-[10px] font-black uppercase tracking-widest hover:shadow-lg transition-all active:scale-95 shadow-md flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            Validate & Forward
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <div class="bg-card rounded-[2.5rem] border shadow-2xl overflow-hidden relative">
            <div class="absolute top-0 right-0 p-8">
                <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border shadow-sm
                    {{ $inquiry->status === 'accepted' ? 'bg-green-50 text-green-700 border-green-200' : '' }}
                    {{ $inquiry->status === 'declined' ? 'bg-red-50 text-red-700 border-red-200' : '' }}
                    {{ $inquiry->status === 'pending' ? 'bg-amber-50 text-amber-700 border-amber-200' : '' }}">
                    {{ $inquiry->status }}
                </span>
            </div>

            <div class="p-10 border-b bg-muted/20">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/40 mb-1">Inquiry Details</p>
                <h1 class="font-heading text-4xl font-black text-primary italic">{{ $inquiry->inquiry_type }}</h1>
                <p class="text-xs font-medium text-muted-foreground mt-2">Ref: <span class="font-mono font-bold">{{ $inquiry->reference_id }}</span> • Submitted {{ $inquiry->created_at->diffForHumans() }}</p>
            </div>

            <div class="p-10 space-y-12">
                <div class="grid md:grid-cols-3 gap-10">
                    <div class="space-y-1">
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60">Full Name</h3>
                        <p class="font-bold text-primary text-lg">{{ $inquiry->full_name }}</p>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60">Email Address</h3>
                        <p class="font-bold text-primary text-lg">{{ $inquiry->email }}</p>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60">Contact Number</h3>
                        <p class="font-bold text-primary text-lg">{{ $inquiry->phone ?? 'None' }}</p>
                    </div>
                </div>

                @if($inquiry->preferred_date)
                    <div class="p-6 bg-accent/5 rounded-2xl border border-accent/10 flex items-center gap-4 italic font-medium text-primary">
                        <div class="h-10 w-10 rounded-xl bg-accent/10 flex items-center justify-center text-accent">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                        </div>
                        Preferred Ceremony Date: {{ \Carbon\Carbon::parse($inquiry->preferred_date)->format('F d, Y') }}
                    </div>
                @endif

                <div class="space-y-4">
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60">Message / Request</h3>
                    <div class="bg-muted/30 p-8 rounded-3xl text-sm leading-relaxed text-primary/80 whitespace-pre-wrap border italic">
                        {{ $inquiry->message }}
                    </div>
                </div>

                @if($inquiry->status === 'accepted')
                    <div class="bg-green-50 border border-green-100 rounded-3xl p-6 flex items-start gap-4 text-green-800 italic">
                        <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center shrink-0 border border-green-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        </div>
                        <div class="text-sm">
                            <p class="font-bold mb-1">Validation Successful</p>
                            <p class="opacity-80">This inquiry was officially reviewed and forwarded to the parish office on <strong>{{ $inquiry->accepted_at->format('M d, Y h:i A') }}</strong>.</p>
                        </div>
                    </div>
                @elseif($inquiry->status === 'declined')
                    <div class="bg-red-50 border border-red-100 rounded-3xl p-6 flex items-start gap-4 text-red-800 italic">
                        <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center shrink-0 border border-red-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>
                        </div>
                        <div class="text-sm">
                            <p class="font-bold mb-1">Inquiry Declined</p>
                            <p class="opacity-80 font-bold mb-2">Reason for rejection:</p>
                            <div class="bg-white/50 p-4 rounded-xl border border-red-100/50">
                                {{ $inquiry->rejection_reason }}
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
                <p class="text-sm text-muted-foreground mb-8 leading-relaxed italic">Please provide a standard reason why this inquiry cannot be processed at this time. This will be emailed to the parishioner.</p>
                
                <form action="{{ route('admin.inquiries.decline', $inquiry->id) }}" method="POST">
                    @csrf
                    <textarea 
                        name="reason" 
                        required 
                        rows="4"
                        placeholder="e.g. Preferred date is already fully booked for weddings. Please suggest another weekend."
                        class="w-full rounded-2xl border-border bg-muted/20 p-4 text-sm focus:ring-accent focus:border-accent italic font-medium mb-8"
                    ></textarea>

                    <div class="flex items-center justify-end gap-3">
                        <button type="button" @click="showRejection = false" class="px-6 py-2 rounded-xl text-sm font-bold text-muted-foreground hover:bg-muted transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-8 py-2 bg-red-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-red-200 hover:bg-red-700 transition-all active:scale-95">
                            Submit & Notify
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
