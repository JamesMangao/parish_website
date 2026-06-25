<x-public-layout>
    @php
        $currentDate = \Carbon\Carbon::create($year, $month, 1);
        $prevMonth = $currentDate->copy()->subMonth();
        $nextMonth = $currentDate->copy()->addMonth();
        $daysInMonth = $currentDate->daysInMonth;
        $firstDayOfMonth = $currentDate->dayOfWeek; // 0 (Sun) to 6 (Sat)
        $monthName = $currentDate->format('F Y');
    @endphp

    <div class="container py-12 mx-auto px-4 max-w-6xl" x-data="{ selectedEvent: null }">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-8">
            <div class="animate-in fade-in slide-in-from-left-4 duration-500">
                <p class="text-[12px] font-black uppercase tracking-[0.3em] text-accent mb-4">Parish Life</p>
                <h1 class="font-heading text-4xl md:text-6xl font-black text-primary italic uppercase leading-tight">Event Calendar</h1>
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
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-card border rounded-[2rem] md:rounded-[3rem] shadow-2xl overflow-x-auto md:overflow-hidden animate-in zoom-in-95 duration-700">
            <div class="min-w-[700px] md:min-w-full font-serif">
                <!-- Calendar Header -->
                <div class="grid grid-cols-7 border-b bg-primary">
                    @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                        <div class="py-6 text-center text-[10px] font-black uppercase tracking-[0.3em] text-white/60">
                            <span class="hidden sm:inline">{{ $day }}</span>
                            <span class="sm:hidden">{{ substr($day, 0, 1) }}</span>
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
                                    <div class="px-2 py-1 rounded-md bg-primary/5 border-l-2 border-primary text-[10px] font-bold text-primary truncate hover:whitespace-normal hover:bg-primary hover:text-white transition-all cursor-pointer"
                                         @click="selectedEvent = {
                                             title: {{ json_encode($event->title) }},
                                             description: {{ json_encode($event->description ?? 'No description available.') }},
                                             date: {{ json_encode(\Carbon\Carbon::parse($event->event_date)->format('l, F j, Y')) }},
                                             time: {{ json_encode($event->event_time ? \Carbon\Carbon::parse($event->event_time)->format('g:i A') : 'All day') }},
                                             url: {{ json_encode($event->url ?? $event->link ?? null) }}
                                         }">
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

        <!-- Event Details Modal -->
        <template x-teleport="body">
            <div x-show="selectedEvent" x-cloak
                 class="fixed inset-0 z-50 flex items-center justify-center p-4"
                 x-transition:enter="transition duration-300 ease-out"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition duration-200 ease-in"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
                <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="selectedEvent = null"></div>
                <div class="relative bg-white rounded-[2rem] shadow-2xl max-w-lg w-full p-8"
                     x-transition:enter="transition duration-300 ease-out"
                     x-transition:enter-start="scale-95 opacity-0 translate-y-4"
                     x-transition:enter-end="scale-100 opacity-100 translate-y-0"
                     x-transition:leave="transition duration-200 ease-in"
                     x-transition:leave-start="scale-100 opacity-100 translate-y-0"
                     x-transition:leave-end="scale-95 opacity-0 translate-y-4">
                    <button @click="selectedEvent = null" class="absolute top-4 right-4 p-2 rounded-xl hover:bg-muted/30 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                    <div class="space-y-4">
                        <div>
                            <h3 class="font-heading text-2xl font-black text-primary" x-text="selectedEvent?.title"></h3>
                            <div class="h-1 w-16 bg-accent mt-3 rounded-full"></div>
                        </div>
                        <div class="flex items-center gap-4 text-sm text-primary/60">
                            <span class="flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                <span x-text="selectedEvent?.date"></span>
                            </span>
                            <span class="flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <span x-text="selectedEvent?.time"></span>
                            </span>
                        </div>
                        <p class="text-primary/70 leading-relaxed" x-text="selectedEvent?.description"></p>
                        <template x-if="selectedEvent?.url">
                            <a :href="selectedEvent.url" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-primary/90 transition-all">
                                View Details
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M7 7h10v10"/><path d="M7 17 21 3"/></svg>
                            </a>
                        </template>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-public-layout>
