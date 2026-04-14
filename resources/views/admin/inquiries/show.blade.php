<x-admin-layout>
    <div class="px-6 py-12 max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('admin.inquiries.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-muted-foreground hover:text-primary transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Back to Inquiries
            </a>
        </div>

        <div class="bg-card rounded-2xl border shadow-sm overflow-hidden">
            <div class="p-8 border-b bg-muted/20 flex items-center justify-between">
                <div>
                    <h1 class="font-heading text-2xl font-bold text-primary">{{ $inquiry->inquiry_type }} Inquiry</h1>
                    <p class="text-xs text-muted-foreground mt-1">Submitted on {{ $inquiry->created_at->format('F d, Y h:i A') }}</p>
                </div>
                <div>
                    <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-widest border {{ $inquiry->status === 'accepted' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-amber-100 text-amber-700 border-amber-200' }}">
                        {{ $inquiry->status }}
                    </span>
                </div>
            </div>

            <div class="p-8 space-y-8">
                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1">Full Name</h3>
                        <p class="font-bold text-primary">{{ $inquiry->full_name }}</p>
                    </div>
                    <div>
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1">Email Address</h3>
                        <p class="font-bold text-primary">{{ $inquiry->email }}</p>
                    </div>
                    <div>
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1">Phone Number</h3>
                        <p class="font-bold text-primary">{{ $inquiry->phone ?? 'Not provided' }}</p>
                    </div>
                    <div>
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1">Inquiry Type</h3>
                        <p class="font-bold text-primary capitalize">{{ $inquiry->inquiry_type }}</p>
                    </div>
                </div>

                <div class="pt-8 border-t">
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-3">Message / Details</h3>
                    <div class="bg-muted/30 p-6 rounded-xl text-sm leading-relaxed text-primary/80 whitespace-pre-wrap">
                        {{ $inquiry->message }}
                    </div>
                </div>

                @if($inquiry->status === 'pending')
                    <div class="pt-8 border-t flex justify-end gap-4">
                        <form action="{{ route('admin.inquiries.accept', $inquiry->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 rounded-md bg-primary px-6 py-3 text-sm font-bold text-primary-foreground hover:bg-primary/90 transition-all shadow-md">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                Validate & Forward to Office
                            </button>
                        </form>
                    </div>
                @else
                    <div class="pt-8 border-t">
                        <div class="bg-green-50 border border-green-100 rounded-xl p-4 flex items-center gap-3 text-green-700 text-xs italic">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            This inquiry was validated and emailed to the parish office on {{ $inquiry->accepted_at->format('M d, Y h:i A') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
