<x-admin-layout>
    <div class="px-6 py-12 max-w-5xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('admin.schedules.index') }}" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-muted-foreground hover:text-primary transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Back to Schedules
            </a>
        </div>

        <div class="bg-card rounded-[2.5rem] border shadow-2xl overflow-hidden">
            <div class="p-10 border-b bg-muted/20">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/40 mb-1">Mass Schedule</p>
                <h1 class="font-heading text-4xl font-black text-primary italic">{{ $schedule->title ?: 'Untitled Schedule' }}</h1>
            </div>

            <div class="p-10 space-y-8">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60 mb-2">Mass Type</h3>
                        <p class="font-bold text-primary capitalize">{{ $schedule->mass_type }}</p>
                    </div>
                    <div>
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60 mb-2">Status</h3>
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $schedule->is_active ? 'bg-green-100 text-green-700 border-green-200' : 'bg-muted text-muted-foreground border-border' }}">
                            {{ $schedule->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                <div>
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60 mb-2">Days</h3>
                    <p class="font-bold text-primary">
                        @if(is_array($schedule->day_of_week))
                            {{ implode(', ', $schedule->day_of_week) }}
                        @else
                            {{ $schedule->day_of_week ?? '—' }}
                        @endif
                    </p>
                </div>

                <div>
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60 mb-2">Times</h3>
                    @if(is_array($schedule->time))
                        <div class="space-y-1">
                            @foreach($schedule->time as $t)
                                @if($t)
                                    <p class="font-bold text-primary">{{ \Carbon\Carbon::parse($t)->format('g:i A') }}</p>
                                @endif
                            @endforeach
                        </div>
                    @elseif($schedule->time)
                        <p class="font-bold text-primary">{{ \Carbon\Carbon::parse($schedule->time)->format('g:i A') }}</p>
                    @else
                        <p class="text-muted-foreground italic">No times set</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
