<x-admin-layout>
    <div class="px-6 py-12 max-w-5xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('admin.events.index') }}" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-muted-foreground hover:text-primary transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Back to Events
            </a>
        </div>

        <div class="bg-card rounded-[2.5rem] border shadow-2xl overflow-hidden">
            <div class="p-10 border-b bg-muted/20">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/40 mb-1">Event Details</p>
                <h1 class="font-heading text-4xl font-black text-primary italic">{{ $event->title }}</h1>
                <p class="text-xs font-medium text-muted-foreground mt-2">{{ $event->event_date->format('F d, Y') }}</p>
            </div>

            <div class="p-10 space-y-8">
                <div>
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60 mb-2">Description</h3>
                    <p class="text-primary leading-relaxed">{{ $event->description ?? 'No description provided.' }}</p>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60 mb-2">Date</h3>
                        <p class="font-bold text-primary">{{ $event->event_date->format('l, F d, Y') }}</p>
                    </div>
                    <div>
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60 mb-2">Location</h3>
                        <p class="font-bold text-primary">{{ $event->location ?? 'Not specified' }}</p>
                    </div>
                </div>

                @if(!empty($event->event_time))
                    <div>
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60 mb-2">Service Times</h3>
                        <div class="space-y-1">
                            @foreach($event->event_time as $slot)
                                <div class="flex items-center gap-2">
                                    @if(!empty($slot['title']))
                                        <span class="font-bold text-primary/70">{{ $slot['title'] }}:</span>
                                    @endif
                                    @if(!empty($slot['time']))
                                        <span class="text-primary">{{ \Carbon\Carbon::parse($slot['time'])->format('g:i A') }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div>
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60 mb-2">Status</h3>
                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $event->is_published ? 'bg-green-100 text-green-700 border-green-200' : 'bg-muted text-muted-foreground border-border' }}">
                        {{ $event->is_published ? 'Published' : 'Draft' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
