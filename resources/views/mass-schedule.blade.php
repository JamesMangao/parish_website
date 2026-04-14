<x-public-layout>
    <x-slot name="meta">
        <meta name="description" content="Check the latest mass schedules for Sto. Rosario Parish. Sunday Masses, Antidipated Masses, and weekday schedules.">
    </x-slot>
    <div class="container py-12 mx-auto px-4" x-data="{
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
        <div class="text-center mb-10">
            <h1 class="font-heading text-4xl font-bold mb-2 text-primary">Mass Schedule</h1>
            <div class="section-divider"></div>
            <p class="text-muted-foreground mt-4 italic italic">Join us for the Holy Eucharist</p>
        </div>

        <div class="max-w-5xl mx-auto space-y-20">
            @php
                $order = ['sunday', 'anticipated', 'weekday', 'saturday', 'special'];
                $labels = [
                    'sunday' => 'Sunday Masses',
                    'anticipated' => 'Anticipated Sunday Masses',
                    'weekday' => 'Weekday Masses',
                    'saturday' => 'Saturday Masses',
                    'special' => 'Special Masses'
                ];
                $colors = [
                    'sunday' => 'bg-accent/10 text-accent border border-accent/20',
                    'anticipated' => 'bg-amber-100 text-amber-800 border border-amber-200',
                    'weekday' => 'bg-primary/5 text-primary border border-primary/20',
                    'saturday' => 'bg-blue-100 text-blue-800 border border-blue-200',
                    'special' => 'bg-destructive/10 text-destructive border border-destructive/20'
                ];

                $dayMap = [
                    'Sunday' => 'SU', 'Monday' => 'MO', 'Tuesday' => 'TU', 'Wednesday' => 'WE', 
                    'Thursday' => 'TH', 'Friday' => 'FR', 'Saturday' => 'SA'
                ];
            @endphp

            @foreach($order as $type)
                @if(isset($schedules[$type]) && $schedules[$type]->count() > 0)
                    <div class="relative">
                        <!-- Elegant Header -->
                        <div class="flex flex-col md:flex-row md:items-end justify-between border-b-2 border-muted pb-4 mb-8">
                            <div>
                                <h2 class="font-heading text-3xl font-black text-primary tracking-tight">{{ $labels[$type] }}</h2>
                            </div>
                            <div class="mt-3 md:mt-0">
                                <span class="text-[10px] font-black uppercase tracking-[0.2em] px-4 py-1.5 rounded-full {{ $colors[$type] }} shadow-sm">
                                    {{ $type }}
                                </span>
                            </div>
                        </div>

                        <!-- Modern Schedule List -->
                        <div class="space-y-6">
                            @foreach($schedules[$type] as $s)
                                <div class="group bg-card hover:bg-accent/5 rounded-3xl border border-muted/60 p-6 transition-all duration-300 hover:shadow-2xl hover:shadow-blue-900/5 hover:-translate-y-1 flex flex-col justify-between relative overflow-hidden">
                                    <!-- Decorative gradient bg -->
                                    <div class="absolute -right-10 -top-10 w-32 h-32 bg-accent/5 rounded-full blur-3xl group-hover:bg-accent/10 transition-colors pointer-events-none"></div>

                                    <div class="relative z-10">
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-2xl font-heading font-black text-primary group-hover:text-accent transition-colors leading-tight pr-4">
                                                {{ $s->title ?? 'Mass Schedule' }}
                                            </h3>
                                            <div class="flex items-center gap-2">
                                                @php
                                                    $times = is_array($s->time) ? array_filter($s->time) : [$s->time];
                                                    $firstTime = !empty($times[0]) ? \Carbon\Carbon::parse($times[0])->format('Hi00') : '080000';
                                                    $endTime = !empty($times[0]) ? \Carbon\Carbon::parse($times[0])->addHour()->format('Hi00') : '090000';
                                                    
                                                    $rrule = '';
                                                    $dtStart = now()->format('Ymd') . 'T' . $firstTime;
                                                    $dtEnd = now()->format('Ymd') . 'T' . $endTime;

                                                    if ($s->day_of_week) {
                                                        $days = is_array($s->day_of_week) ? $s->day_of_week : [$s->day_of_week];
                                                        $rrule = 'FREQ=WEEKLY;BYDAY=' . implode(',', array_map(fn($d) => $dayMap[$d] ?? 'SU', $days));
                                                        // Adjust DTSTART to the first occurrence
                                                        $nextDate = now();
                                                        while(!in_array($nextDate->format('l'), $days)) {
                                                            $nextDate->addDay();
                                                        }
                                                        $dtStart = $nextDate->format('Ymd') . 'T' . $firstTime;
                                                        $dtEnd = $nextDate->format('Ymd') . 'T' . $endTime;
                                                    } elseif ($s->specific_date) {
                                                        $dtStart = $s->specific_date->format('Ymd') . 'T' . $firstTime;
                                                        $dtEnd = $s->specific_date->format('Ymd') . 'T' . $endTime;
                                                    }
                                                    
                                                    $googleUrl = "https://calendar.google.com/calendar/render?action=TEMPLATE&text=" . urlencode('Mass: ' . ($s->title ?? 'Sto Rosario')) . "&dates=" . $dtStart . "/" . $dtEnd . "&location=" . urlencode('Sto. Rosario Parish');
                                                    if ($rrule) $googleUrl .= "&recur=RRULE:" . $rrule;
                                                @endphp
                                                
                                                <a href="{{ $googleUrl }}" target="_blank" class="flex items-center gap-1 text-[9px] font-black uppercase tracking-widest text-accent/60 hover:text-accent transition-colors" title="Add to Google Calendar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="M12 14v4"/><path d="M10 16h4"/></svg>
                                                    Google
                                                </a>

                                                <button @click="downloadICS('Mass: {{ addslashes($s->title ?? 'Sto Rosario') }}', 'Regular mass schedule', '{{ $dtStart }}', '{{ $dtEnd }}', 'Sto. Rosario Parish', '{{ $rrule }}')" 
                                                        class="flex items-center gap-1 text-[9px] font-black uppercase tracking-widest text-accent/60 hover:text-accent transition-colors" title="Download iCal">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                                                    iCal
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="space-y-3 mb-8">
                                            @if($s->day_of_week || $s->specific_date)
                                                <div class="flex items-center gap-2 text-sm text-primary/70 font-bold">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                                    @if(is_array($s->day_of_week))
                                                        {{ implode(', ', $s->day_of_week) }}
                                                    @else
                                                        {{ $s->day_of_week ?: ($s->specific_date ? $s->specific_date->format('M d, Y') : '') }}
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Time Tags -->
                                    <div class="flex flex-wrap gap-2 mt-auto pt-5 border-t border-muted/50 relative z-10 w-full">
                                        @if(is_array($s->time))
                                            @foreach(array_filter($s->time) as $t)
                                                <div class="bg-background border border-muted/60 px-4 py-2 rounded-xl text-primary font-black font-heading text-lg shadow-sm group-hover:border-accent/30 group-hover:text-accent transition-colors">
                                                    {{ \Carbon\Carbon::parse($t)->format('g:i A') }}
                                                </div>
                                            @endforeach
                                        @elseif($s->time)
                                            <div class="bg-background border border-muted/60 px-4 py-2 rounded-xl text-primary font-black font-heading text-lg shadow-sm group-hover:border-accent/30 group-hover:text-accent transition-colors">
                                                {{ \Carbon\Carbon::parse($s->time)->format('g:i A') }}
                                            </div>
                                        @else
                                            <span class="text-muted-foreground italic text-sm w-full text-center py-2">Time TBD</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

            @if(count($schedules) === 0)
                <div class="flex flex-col items-center justify-center py-24 text-center border-2 border-dashed border-muted rounded-3xl bg-muted/10">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground/40 mb-4"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                    <h3 class="font-heading text-2xl font-bold text-primary mb-2">No Schedules Available</h3>
                    <p class="text-muted-foreground max-w-md">There are currently no mass schedules posted. Please check back later or contact the parish office for inquiries.</p>
                </div>
            @endif
        </div>        
        <div class="mt-20 max-w-2xl mx-auto p-6 rounded-xl bg-accent/5 border border-accent/10 flex items-start gap-4">
            <div class="p-2 bg-accent/10 rounded text-accent shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-info"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
            </div>
            <div>
                <h4 class="font-bold text-primary text-sm mb-1 uppercase tracking-wider">Note for Special Masses</h4>
                <p class="text-xs text-muted-foreground leading-relaxed">
                    Mass times for holy days of obligation, feast days, and other special occasions may vary. 
                    Please follow our announcements or social media pages for the most up-to-date information.
                </p>
            </div>
        </div>
    </div>
</x-public-layout>
