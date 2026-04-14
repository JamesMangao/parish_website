<x-public-layout>
    <x-slot name="meta">
        <meta name="description" content="{{ Str::limit($event->description, 160) }}">
        <meta property="og:title" content="{{ $event->title }} | Sto. Rosario Parish">
        <meta property="og:description" content="{{ Str::limit($event->description, 160) }}">
    </x-slot>

    <div class="container py-12 mx-auto px-4 max-w-4xl" x-data="{
        downloadICS(title, desc, start, end, loc, rrule = '') {
            let ics = [
                'BEGIN:VCALENDAR',
                'VERSION:2.0',
                'PRODID:-//Parish Pal//EN',
                'BEGIN:VEVENT',
                'UID:' + Math.random().toString(36).substr(2, 9) + '@parish-pal',
                'DTSTAMP:' + new Date().toISOString().replace(/[-:]/g, '').split('.')[0] + 'Z',
                'DTSTART:' + start,
                'DTEND:' + end,
                'SUMMARY:' + title,
                'DESCRIPTION:' + desc,
                'LOCATION:' + loc
            ];
            
            if (rrule) ics.push('RRULE:' + rrule);
            
            ics.push('END:VEVENT');
            ics.push('END:VCALENDAR');
            
            const blob = new Blob([ics.join('\\r\\n')], { type: 'text/calendar;charset=utf-8' });
            const link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.setAttribute('download', title.replace(/\\s+/g, '_') + '.ics');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }">
        <nav class="flex mb-8 text-sm font-medium text-muted-foreground" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li><a href="/" class="hover:text-primary transition-colors">Home</a></li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 mx-1" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg>
                        <a href="/events" class="hover:text-primary transition-colors">Events</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 mx-1" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg>
                        <span class="text-primary truncate max-w-[200px]">{{ $event->title }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <article class="bg-card rounded-[2.5rem] border shadow-2xl overflow-hidden animate-in fade-in slide-in-from-bottom-8 duration-700">
            <!-- Header Section -->
            <div class="relative h-64 md:h-80 bg-primary/5 flex items-center justify-center overflow-hidden">
                <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-primary via-transparent to-transparent"></div>
                
                <div class="relative z-10 flex flex-col items-center text-center px-6">
                    <div class="flex flex-col items-center justify-center w-24 h-24 rounded-2xl bg-white border border-primary/10 shadow-xl mb-6 scale-110">
                        <span class="text-[10px] font-black uppercase tracking-widest text-primary/60">{{ $event->event_date->format('M') }}</span>
                        <span class="text-4xl font-heading font-black text-primary my-0.5">{{ $event->event_date->format('d') }}</span>
                        <span class="text-[10px] font-bold text-primary/40">{{ $event->event_date->format('Y') }}</span>
                    </div>
                    <h1 class="font-heading text-3xl md:text-5xl font-black text-primary mb-2 italic">{{ $event->title }}</h1>
                </div>
            </div>

            <div class="p-8 md:p-12">
                <div class="grid md:grid-cols-3 gap-12">
                    <!-- Main Content -->
                    <div class="md:col-span-2 space-y-8">
                        <div>
                            <h2 class="text-xs font-black uppercase tracking-widest text-accent mb-4">About this Event</h2>
                            <div class="prose prose-lg max-w-none text-muted-foreground leading-relaxed whitespace-pre-line">
                                {{ $event->description }}
                            </div>
                        </div>

                        @if($event->location)
                        <div class="pt-8 border-t">
                             <h2 class="text-xs font-black uppercase tracking-widest text-accent mb-4">Venue & Location</h2>
                             <div class="flex items-start gap-3 text-primary">
                                <div class="p-2 rounded-lg bg-primary/5 border border-primary/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-lg">{{ $event->location }}</p>
                                    <p class="text-sm text-muted-foreground">Sto. Rosario Parish Community</p>
                                </div>
                             </div>
                        </div>
                        @endif
                    </div>

                    <!-- Sidebar Info -->
                    <div class="space-y-8">
                        <div class="bg-muted/30 rounded-3xl p-6 border border-muted">
                            <h2 class="text-xs font-black uppercase tracking-widest text-primary mb-6">Schedule Details</h2>
                            
                            @if(!empty($event->event_time))
                                <div class="space-y-4">
                                    @foreach($event->event_time as $slot)
                                        <div class="flex items-center justify-between group">
                                            <div class="flex flex-col">
                                                <span class="text-[10px] uppercase font-black text-muted-foreground group-hover:text-accent transition-colors">{{ $slot['title'] ?? 'Main Session' }}</span>
                                                <span class="font-bold text-primary">{{ \Carbon\Carbon::parse($slot['time'])->format('g:i A') }}</span>
                                            </div>
                                            <div class="h-8 w-8 rounded-full bg-white border flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-8 pt-8 border-t space-y-3">
                                @php
                                    $startTime = !empty($event->event_time[0]['time']) ? \Carbon\Carbon::parse($event->event_time[0]['time'])->format('Hi00') : '080000';
                                    $endTime = !empty($event->event_time[0]['time']) ? \Carbon\Carbon::parse($event->event_time[0]['time'])->addHour()->format('Hi00') : '090000';
                                    $dates = $event->event_date->format('Ymd') . 'T' . $startTime . '/' . $event->event_date->format('Ymd') . 'T' . $endTime;
                                    $googleUrl = "https://calendar.google.com/calendar/render?action=TEMPLATE&text=" . urlencode($event->title) . "&dates=" . $dates . "&details=" . urlencode($event->description) . "&location=" . urlencode($event->location ?? 'Sto. Rosario Parish');
                                @endphp
                                
                                <a href="{{ $googleUrl }}" target="_blank" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-primary text-primary-foreground font-bold text-sm hover:opacity-90 transition-opacity">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                                    Add to Google Calendar
                                </a>
                                
                                <button @click="downloadICS('{{ addslashes($event->title) }}', '{{ addslashes(Str::limit($event->description, 200)) }}', '{{ $event->event_date->format('Ymd') }}T{{ $startTime }}', '{{ $event->event_date->format('Ymd') }}T{{ $endTime }}', '{{ addslashes($event->location ?? 'Sto. Rosario Parish') }}')" 
                                    class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl border-2 border-primary/10 bg-white text-primary font-bold text-sm hover:bg-muted/50 transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                                    Download iCal File
                                </button>
                            </div>
                        </div>

                        <div class="text-center p-6 bg-accent/5 rounded-3xl border border-accent/10">
                            <p class="text-xs font-bold text-accent uppercase tracking-widest mb-2">Need Help?</p>
                            <p class="text-sm text-primary/80 mb-4">Contact the parish office for more inquiries about this event.</p>
                            <a href="/inquiry" class="text-xs font-black uppercase underline hover:text-accent transition-colors">Submit an Inquiry</a>
                        </div>
                    </div>
                </div>
            </div>
        </article>
        
        <div class="mt-12 text-center">
            <a href="/events" class="inline-flex items-center gap-2 text-primary font-bold hover:text-accent transition-colors group">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="group-hover:-translate-x-1 transition-transform"><path d="m15 18-6-6 6-6"/></svg>
                Back to All Events
            </a>
        </div>
    </div>
</x-public-layout>
