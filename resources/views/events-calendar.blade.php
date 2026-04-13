<x-public-layout>
    @php
        $currentDate = \Carbon\Carbon::create($year, $month, 1);
        $prevMonth = $currentDate->copy()->subMonth();
        $nextMonth = $currentDate->copy()->addMonth();
        $daysInMonth = $currentDate->daysInMonth;
        $firstDayOfMonth = $currentDate->dayOfWeek; // 0 (Sun) to 6 (Sat)
        $monthName = $currentDate->format('F Y');
    @php

    <div class="container py-12 mx-auto px-4 max-w-6xl">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-8">
            <div class="animate-in fade-in slide-in-from-left-4 duration-500">
                <p class="text-[12px] font-black uppercase tracking-[0.3em] text-accent mb-4">Parish Life</p>
                <h1 class="font-heading text-6xl font-black text-primary italic uppercase leading-none">Event Calendar</h1>
                <div class="h-1.5 w-24 bg-accent mt-6 rounded-full"></div>
            </div>
            
            <div class="flex items-center gap-4 animate-in fade-in slide-in-from-right-4 duration-500">
                <a href="{{ route('events', ['view' => 'list']) }}" class="px-6 py-3 rounded-2xl border-2 border-primary/10 font-black uppercase text-xs tracking-widest hover:bg-primary hover:text-white transition-all">List View</a>
                <div class="flex items-center bg-muted/30 rounded-2xl p-1 border">
                    <a href="{{ route('events', ['view' => 'calendar', 'month' => $prevMonth->month, 'year' => $prevMonth->year]) }}" class="p-3 hover:bg-white rounded-xl transition-all text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    </a>
                    <span class="px-6 font-black uppercase text-sm tracking-widest text-primary">{{ $monthName }}</span>
                    <a href="{{ route('events', ['view' => 'calendar', 'month' => $nextMonth->month, 'year' => $nextMonth->year]) }}" class="p-3 hover:bg-white rounded-xl transition-all text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6 6-6"/></svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-card border rounded-[3rem] shadow-2xl overflow-hidden animate-in zoom-in-95 duration-700">
            <!-- Calendar Header -->
            <div class="grid grid-cols-7 border-b bg-primary">
                @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                    <div class="py-6 text-center text-[10px] font-black uppercase tracking-[0.3em] text-white/60">
                        {{ $day }}
                    </div>
                @endforeach
            </div>

            <!-- Calendar Grid -->
            <div class="grid grid-cols-7 bg-muted/10">
                @for($i = 0; $i < $firstDayOfMonth; $i++)
                    <div class="aspect-square border-r border-b bg-muted/5"></div>
                @endfor

                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
                        $dayEvents = $events->filter(fn($e) => \Carbon\Carbon::parse($e->event_date)->format('Y-m-d') === $dateStr);
                        $isToday = \Carbon\Carbon::today()->format('Y-m-d') === $dateStr;
                    @endphp
                    <div class="aspect-square border-r border-b p-4 relative group hover:bg-white transition-colors">
                        <span class="text-sm font-black {{ $isToday ? 'bg-accent text-white h-7 w-7 rounded-full flex items-center justify-center shadow-lg shadow-accent/20' : 'text-primary' }}">
                            {{ $day }}
                        </span>
                        
                        <div class="mt-2 space-y-1">
                            @foreach($dayEvents as $event)
                                <div class="px-2 py-1 rounded-md bg-primary/5 border-l-2 border-primary text-[10px] font-bold text-primary truncate hover:whitespace-normal hover:bg-primary hover:text-white transition-all cursor-default" title="{{ $event->title }}">
                                    {{ $event->title }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endfor

                @php
                    $remainingCells = (7 - (($firstDayOfMonth + $daysInMonth) % 7)) % 7;
                @endphp
                @for($i = 0; $i < $remainingCells; $i++)
                    <div class="aspect-square border-r border-b bg-muted/5"></div>
                @endfor
            </div>
        </div>
        
        <div class="mt-12 p-8 bg-accent/5 rounded-[2.5rem] border border-accent/10 flex items-center gap-6 italic">
            <div class="h-12 w-12 rounded-2xl bg-accent flex items-center justify-center text-white shrink-0 shadow-lg shadow-accent/20">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            </div>
            <p class="text-sm text-primary font-medium leading-relaxed">
                <span class="font-black uppercase tracking-widest mr-2">Note:</span> 
                All events are subject to change. Please follow our social media pages for real-time updates and live stream announcements.
            </p>
        </div>
    </div>
</x-public-layout>
