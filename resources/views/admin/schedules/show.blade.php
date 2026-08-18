<x-admin-layout>
    <div class="max-w-5xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('admin.schedules.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-muted-foreground/50 hover:text-primary transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6" /></svg>
                Back to Schedules
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-black/[.04] shadow-sm shadow-black/[.02] overflow-hidden">
            <div class="px-8 py-8 border-b border-black/[.04] bg-gradient-to-r from-[#F5F7FA] to-white">
                <p class="text-[10px] font-black uppercase tracking-[.2em] text-primary/30 mb-1">Mass Schedule</p>
                <h1 class="font-heading text-3xl font-black text-primary italic">{{ $schedule->title ?: 'Untitled Schedule' }}</h1>
            </div>

            <div class="p-8 space-y-8">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/40 mb-2">Mass Type</h3>
                        <p class="font-bold text-primary capitalize text-sm">{{ $schedule->mass_type }}</p>
                    </div>
                    <div>
                        <h3 class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/40 mb-2">Status</h3>
                        <x-admin-badge :status="$schedule->is_active ? 'active' : 'inactive'" />
                    </div>
                </div>

                <div>
                    <h3 class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/40 mb-2">Days</h3>
                    <p class="font-bold text-primary text-sm">
                        @if(is_array($schedule->day_of_week))
                            {{ implode(', ', $schedule->day_of_week) }}
                        @else
                            {{ $schedule->day_of_week ?? '—' }}
                        @endif
                    </p>
                </div>

                <div>
                    <h3 class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/40 mb-2">Times</h3>
                    @if(is_array($schedule->time))
                        <div class="space-y-1">
                            @foreach($schedule->time as $t)
                                @if($t)
                                    <p class="font-bold text-primary text-sm">{{ \Carbon\Carbon::parse($t)->format('g:i A') }}</p>
                                @endif
                            @endforeach
                        </div>
                    @elseif($schedule->time)
                        <p class="font-bold text-primary text-sm">{{ \Carbon\Carbon::parse($schedule->time)->format('g:i A') }}</p>
                    @else
                        <p class="text-muted-foreground/50 italic text-sm">No times set</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
