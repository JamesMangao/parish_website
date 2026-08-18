<x-admin-layout>
    <div class="max-w-5xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('admin.events.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-muted-foreground/50 hover:text-primary transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6" /></svg>
                Back to Events
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-black/[.04] shadow-sm shadow-black/[.02] overflow-hidden">
            <div class="px-8 py-8 border-b border-black/[.04] bg-gradient-to-r from-[#F5F7FA] to-white">
                <p class="text-[10px] font-black uppercase tracking-[.2em] text-primary/30 mb-1">Event Details</p>
                <h1 class="font-heading text-3xl font-black text-primary italic">{{ $event->title }}</h1>
                <p class="text-xs font-medium text-muted-foreground/50 mt-2">{{ $event->event_date->format('F d, Y') }}</p>
            </div>

            <div class="p-8 space-y-8">
                <div>
                    <h3 class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/40 mb-2">Description</h3>
                    <p class="text-primary leading-relaxed text-sm">{{ $event->description ?? 'No description provided.' }}</p>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/40 mb-2">Date</h3>
                        <p class="font-bold text-primary text-sm">{{ $event->event_date->format('l, F d, Y') }}</p>
                    </div>
                    <div>
                        <h3 class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/40 mb-2">Location</h3>
                        <p class="font-bold text-primary text-sm">{{ $event->location ?? 'Not specified' }}</p>
                    </div>
                </div>

                @if(!empty($event->event_time))
                    <div>
                        <h3 class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/40 mb-2">Service Times</h3>
                        <div class="space-y-1">
                            @foreach($event->event_time as $slot)
                                <div class="flex items-center gap-2 text-sm">
                                    @if(!empty($slot['title']))
                                        <span class="font-bold text-primary/60">{{ $slot['title'] }}:</span>
                                    @endif
                                    @if(!empty($slot['time']))
                                        <span class="text-primary font-medium">{{ \Carbon\Carbon::parse($slot['time'])->format('g:i A') }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div>
                    <h3 class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/40 mb-2">Status</h3>
                    <x-admin-badge :status="$event->is_published ? 'published' : 'draft'" />
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
