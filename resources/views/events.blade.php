<x-public-layout>
    <x-slot name="meta">
        <meta name="description" content="Stay updated with the latest events and celebrations at Sto. Rosario Parish. Join our community activities and spiritual gatherings.">
    </x-slot>
    <div class="container py-12 mx-auto px-4">
        <div class="text-center mb-10">
            <h1 class="font-heading text-4xl font-bold mb-2 text-primary">Parish Events</h1>
            <div class="section-divider"></div>
            <p class="text-muted-foreground mt-4 italic italic">Join our community activities</p>
        </div>

        <div class="max-w-4xl mx-auto space-y-6">
            @forelse($events as $event)
                <div class="group relative bg-card hover:bg-accent/5 rounded-[2rem] border border-muted/60 p-8 transition-all duration-300 hover:shadow-2xl hover:shadow-blue-900/5 hover:-translate-y-1">
                    <div class="flex flex-col md:flex-row gap-8 items-start">
                        
                        <!-- Elegant Date Badge -->
                        <div class="flex flex-col items-center justify-center shrink-0 w-28 h-28 rounded-2xl bg-primary/5 border border-primary/10 group-hover:bg-accent/10 group-hover:border-accent/20 transition-colors">
                            <span class="text-[10px] font-black uppercase tracking-widest text-primary/60 group-hover:text-accent/80 transition-colors">{{ $event->event_date->format('M') }}</span>
                            <span class="text-4xl font-heading font-black text-primary group-hover:text-accent transition-colors my-0.5">{{ $event->event_date->format('d') }}</span>
                            <span class="text-[10px] font-bold text-primary/40">{{ $event->event_date->format('Y') }}</span>
                        </div>

                        <!-- Content Section -->
                        <div class="flex-1 flex flex-col h-full justify-between w-full">
                            <div>
                                <h3 class="font-heading text-3xl font-black text-primary mb-3 leading-tight group-hover:text-accent transition-colors">{{ $event->title }}</h3>
                                
                                <div class="flex flex-wrap items-center gap-4 mb-4">
                                    @php
                                        $startTime = !empty($event->event_time[0]['time']) ? \Carbon\Carbon::parse($event->event_time[0]['time'])->format('Hi00') : '080000';
                                        $endTime = !empty($event->event_time[0]['time']) ? \Carbon\Carbon::parse($event->event_time[0]['time'])->addHour()->format('Hi00') : '090000';
                                        $dates = $event->event_date->format('Ymd') . 'T' . $startTime . '/' . $event->event_date->format('Ymd') . 'T' . $endTime;
                                        $googleUrl = "https://calendar.google.com/calendar/render?action=TEMPLATE&text=" . urlencode($event->title) . "&dates=" . $dates . "&details=" . urlencode($event->description) . "&location=" . urlencode($event->location ?? 'Sto. Rosario Parish');
                                    @endphp
                                    <a href="{{ $googleUrl }}" target="_blank" class="flex items-center gap-1.5 text-[10px] font-black uppercase tracking-widest text-accent hover:text-primary transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="M12 14v4"/><path d="M10 16h4"/></svg>
                                        Add to Google Calendar
                                    </a>
                                </div>

                                <p class="text-muted-foreground leading-relaxed text-base mb-6">
                                    {{ $event->description }}
                                </p>
                            </div>
                            
                            @if(!empty($event->event_time))
                                <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6 pt-6 border-t border-muted/40">
                                    <div class="flex items-start gap-2.5 text-sm font-medium text-primary/80">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-accent opacity-80 shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($event->event_time as $slot)
                                                <div class="flex items-center gap-1.5 bg-background border border-muted/60 px-2.5 py-1 rounded-md shadow-sm group-hover:border-accent/30 transition-colors">
                                                    @if(!empty($slot['title']))
                                                        <span class="text-[10px] uppercase tracking-wider font-bold text-muted-foreground">{{ $slot['title'] }}</span>
                                                    @endif
                                                    @if(!empty($slot['time']))
                                                        <span class="font-bold text-xs">{{ \Carbon\Carbon::parse($slot['time'])->format('g:i A') }}</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-20 bg-muted/20 rounded-xl border border-dashed">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-x mx-auto text-muted mb-4"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="m14 14-4 4"/><path d="m10 14 4 4"/></svg>
                    <p class="text-muted-foreground italic italic">No upcoming events scheduled right now. Check back soon!</p>
                </div>
            @endforelse
        </div>
    </div>
</x-public-layout>
