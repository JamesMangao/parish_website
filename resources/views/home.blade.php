<x-public-layout>
    <x-slot name="meta">
        <meta name="description" content="Welcome to Sto. Rosario Parish – Pacita, San Pedro, Laguna. Mass schedules, intentions, events, and community news.">
    </x-slot>

    {{-- ═══════════════════════════════════════════
         HERO
    ═══════════════════════════════════════════ --}}
    <section class="relative min-h-[92vh] flex flex-col items-center justify-center overflow-hidden">

        {{-- Background --}}
        <div class="absolute inset-0 z-0">
            <img src="/bg.png" alt="Sto. Rosario Parish" class="w-full h-full object-cover">
            <div class="absolute inset-0" style="background: linear-gradient(to bottom, rgba(20,6,12,0.70) 0%, rgba(20,6,12,0.82) 50%, rgba(20,6,12,0.97) 100%);"></div>
        </div>

        {{-- Content --}}
        <div class="container relative z-10 mx-auto px-4 text-center flex flex-col items-center">

            {{-- Pre-heading --}}
            <div class="flex items-center gap-3 mb-8">
                <div class="h-px w-12 bg-accent/60"></div>
                <p class="text-accent text-[11px] font-black uppercase tracking-[0.45em]">Pacita, San Pedro, Laguna</p>
                <div class="h-px w-12 bg-accent/60"></div>
            </div>

            {{-- Cross icon --}}
            <div class="mb-6 text-accent text-4xl font-serif">✝</div>

            {{-- Main heading --}}
            <h1 class="font-heading text-5xl md:text-7xl lg:text-8xl font-bold text-white mb-6 leading-[0.95] tracking-tight">
                Sto. Rosario<br><em class="text-accent not-italic">Parish</em>
            </h1>

            <p class="max-w-xl mx-auto text-base md:text-lg text-white/80 mb-12 leading-relaxed font-light tracking-wide">
                Home to the Queen of the Most Holy Rosary — a beacon of faith, community, and service for over four decades.
            </p>

            {{-- CTA buttons --}}
            <div class="flex flex-col sm:flex-row items-center gap-3">
                <a href="/mass-schedule" class="px-8 py-3.5 rounded-full bg-accent text-[#1a0a0e] font-black text-sm uppercase tracking-widest hover:brightness-110 transition-all shadow-xl">
                    Mass Schedule
                </a>
                <a href="/submit-intention" class="px-8 py-3.5 rounded-full border-2 border-white/50 text-white font-bold text-sm uppercase tracking-widest hover:bg-white/15 hover:border-white/70 transition-all">
                    Offer an Intention
                </a>
                <a href="/inquiry" class="px-8 py-3.5 rounded-full border-2 border-white/50 text-white font-bold text-sm uppercase tracking-widest hover:bg-white/15 hover:border-white/70 transition-all">
                    Parish Inquiry
                </a>
            </div>
        </div>

        {{-- Scroll cue — pinned to very bottom, below content --}}
        <div class="relative z-10 mt-16 flex flex-col items-center gap-2 opacity-50">
            <p class="text-white text-[9px] uppercase tracking-[0.4em]">Scroll</p>
            <div class="h-8 w-px bg-white/60"></div>
        </div>

        {{-- Bottom fade --}}
        <div class="absolute bottom-0 left-0 right-0 h-28 z-10" style="background: linear-gradient(to top, oklch(0.96 0.01 40), transparent);"></div>
    </section>

    {{-- ═══════════════════════════════════════════
         NEXT MASS BANNER
    ═══════════════════════════════════════════ --}}
    <section class="relative z-20 -mt-1 py-0">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto rounded-2xl border border-border bg-card shadow-xl shadow-primary/5 overflow-hidden">
                <div class="grid md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-border">
                    {{-- Next mass --}}
                    <div class="p-6 flex items-center gap-4">
                        <div class="h-11 w-11 rounded-xl bg-accent/10 flex items-center justify-center text-accent shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 7 4 2v11a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9l4-2"/><path d="M14 22v-4a2 2 0 0 0-2-2v0a2 2 0 0 0-2 2v4"/><path d="M18 5 12 2 6 5"/><path d="M12 7V2"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground mb-0.5">Next Mass</p>
                            @if($nextMass)
                                @if(($nextMass->calculated_day ?? '') === 'Ongoing')
                                    <p class="font-heading font-black text-accent text-lg animate-pulse">Ongoing Now</p>
                                    <p class="text-xs text-muted-foreground">Started at {{ $nextMass->calculated_time }}</p>
                                @else
                                    <p class="font-heading font-black text-primary text-lg">{{ $nextMass->calculated_day }} · {{ $nextMass->calculated_time }}</p>
                                    @if(isset($nextMass->mass_type))
                                        <p class="text-xs text-muted-foreground capitalize">{{ $nextMass->mass_type }} Mass</p>
                                    @endif
                                @endif
                            @else
                                <p class="font-heading font-black text-primary text-lg">See Schedule</p>
                            @endif
                        </div>
                    </div>
                    {{-- Office hours --}}
                    <div class="p-6 flex items-center gap-4">
                        <div class="h-11 w-11 rounded-xl bg-accent/10 flex items-center justify-center text-accent shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground mb-0.5">Office Hours</p>
                            <p class="font-bold text-primary text-sm">Tue–Sat · 6AM–12NN, 1:30–6PM</p>
                            <p class="text-xs text-muted-foreground">Sunday · 6AM–12NN, 3–6PM</p>
                        </div>
                    </div>
                    {{-- Full schedule link --}}
                    <div class="p-6 flex items-center justify-center md:justify-end">
                        <a href="/mass-schedule" class="group inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-primary text-primary-foreground font-bold text-sm hover:bg-primary/90 transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">
                            Full Schedule
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:translate-x-1 transition-transform"><path d="m9 18 6-6-6-6"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
         QUICK ACTIONS
    ═══════════════════════════════════════════ --}}
    <section class="py-20 container mx-auto px-4">
        <div class="max-w-5xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
            $actions = [
                ['href'=>'/mass-schedule','icon'=>'<path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/>','label'=>'Mass Schedule','sub'=>'Times & days'],
                ['href'=>'/submit-intention','icon'=>'<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/><path d="M12 15a3.2 3.2 0 0 0 4.5-4.5l-4.5-4.5-4.5 4.5a3.2 3.2 0 0 0 4.5 4.5Z"/>','label'=>'Offer Intention','sub'=>'Submit online'],
                ['href'=>'/inquiry','icon'=>'<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>','label'=>'Inquiry','sub'=>'Sacraments & docs'],
                ['href'=>'/donate','icon'=>'<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.505 4.04 3 5.5L12 21l7-7Z"/>','label'=>'Donate','sub'=>'Support the parish'],
            ];
            @endphp
            @foreach($actions as $a)
            <a href="{{ $a['href'] }}" class="group flex flex-col items-center gap-3 p-6 rounded-2xl bg-card border border-border hover:border-accent/40 hover:bg-accent/5 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg text-center">
                <div class="h-12 w-12 rounded-xl bg-accent/10 flex items-center justify-center text-accent group-hover:bg-accent group-hover:text-accent-foreground transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $a['icon'] !!}</svg>
                </div>
                <div>
                    <p class="font-bold text-primary text-sm group-hover:text-accent transition-colors">{{ $a['label'] }}</p>
                    <p class="text-xs text-muted-foreground mt-0.5">{{ $a['sub'] }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
         UPCOMING EVENTS
    ═══════════════════════════════════════════ --}}
    @if(isset($upcomingEvents) && $upcomingEvents->count() > 0)
    <section class="py-20" style="background: linear-gradient(180deg, var(--color-background) 0%, oklch(0.93 0.015 40) 100%);">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 max-w-5xl mx-auto mb-12">
                <div>
                    <p class="text-[11px] font-black uppercase tracking-[0.35em] text-accent mb-2">What's Coming</p>
                    <h2 class="font-heading text-4xl font-bold text-primary italic">Upcoming Events</h2>
                </div>
                <a href="/events" class="text-sm font-bold text-accent hover:text-primary transition-colors flex items-center gap-1 group">
                    View all events
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:translate-x-1 transition-transform"><path d="m9 18 6-6-6-6"/></svg>
                </a>
            </div>

            <div class="grid md:grid-cols-2 gap-6 max-w-5xl mx-auto">
                @foreach($upcomingEvents as $event)
                <a href="{{ route('events.show', $event) }}" class="group flex items-start gap-5 p-6 rounded-2xl bg-card border border-border hover:border-accent/30 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    {{-- Date badge --}}
                    <div class="flex flex-col items-center justify-center shrink-0 w-16 h-16 rounded-xl bg-primary text-primary-foreground group-hover:bg-accent group-hover:text-accent-foreground transition-colors">
                        <span class="text-[9px] font-black uppercase tracking-wider opacity-70">{{ $event->event_date->format('M') }}</span>
                        <span class="font-heading text-2xl font-black leading-none">{{ $event->event_date->format('d') }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-heading font-bold text-primary text-lg leading-tight mb-1.5 group-hover:text-accent transition-colors line-clamp-1">{{ $event->title }}</h3>
                        <p class="text-sm text-muted-foreground line-clamp-2 leading-relaxed">{{ $event->description }}</p>
                        @if(!empty($event->event_time) && count($event->event_time) > 0)
                            <p class="text-xs font-bold text-accent mt-2">{{ \Carbon\Carbon::parse($event->event_time[0]['time'] ?? '')->format('g:i A') }}</p>
                        @endif
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground/30 group-hover:text-accent shrink-0 mt-1 group-hover:translate-x-0.5 transition-all"><path d="m9 18 6-6-6-6"/></svg>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ═══════════════════════════════════════════
         ANNOUNCEMENTS
    ═══════════════════════════════════════════ --}}
    <section class="py-24 bg-background">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-12">
                    <div>
                        <p class="text-[11px] font-black uppercase tracking-[0.35em] text-accent mb-2">From the Parish</p>
                        <h2 class="font-heading text-4xl font-bold text-primary italic">Latest Announcements</h2>
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-6">
                    @forelse($announcements as $item)
                    <div class="group flex flex-col bg-card rounded-2xl border border-border hover:border-accent/30 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                        {{-- Top accent bar --}}
                        <div class="h-1 bg-gradient-to-r from-primary to-accent"></div>
                        <div class="p-6 flex flex-col flex-1">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="h-7 w-7 rounded-lg bg-accent/10 flex items-center justify-center text-accent">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
                                </div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Announcement</span>
                            </div>
                            <h3 class="font-heading text-lg font-bold text-primary mb-3 leading-tight group-hover:text-accent transition-colors line-clamp-2">{{ $item->title }}</h3>
                            <p class="text-sm text-muted-foreground leading-relaxed line-clamp-3 flex-1">{{ $item->content }}</p>
                            <div class="flex items-center gap-1.5 mt-5 pt-4 border-t border-border text-xs text-muted-foreground">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                                {{ $item->created_at->format('M d, Y') }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-3 py-16 text-center text-muted-foreground italic">
                        No recent announcements at this time.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
         SERVICES STRIP
    ═══════════════════════════════════════════ --}}
    <section style="background:#1a0a0e;" class="py-20 px-4">
        <div class="container mx-auto max-w-5xl">
            <div class="text-center mb-14">
                <p class="text-[11px] font-black uppercase tracking-[0.35em] text-accent mb-3">Sacramental Services</p>
                <h2 class="font-heading text-4xl font-bold text-white italic">How We Serve</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @php
                $services = [
                    ['label'=>'Baptism','href'=>'/inquiry','svg'=>'<path d="M12 22a10 10 0 0 1-10-10c0-5.52 4.48-10 10-10s10 4.48 10 10"/><path d="M12 8v8"/><path d="M8 12h8"/>'],
                    ['label'=>'Wedding','href'=>'/inquiry','svg'=>'<circle cx="12" cy="6" r="3"/><path d="M8.5 9.5 5 15h14l-3.5-5.5"/><path d="M12 9v12"/><path d="M9 21h6"/>'],
                    ['label'=>'Confirmation','href'=>'/inquiry','svg'=>'<path d="M18 11V6a2 2 0 0 0-2-2v0a2 2 0 0 0-2 2v0"/><path d="M14 10V4a2 2 0 0 0-2-2v0a2 2 0 0 0-2 2v2"/><path d="M10 10.5V6a2 2 0 0 0-2-2v0a2 2 0 0 0-2 2v8"/><path d="M18 8a2 2 0 1 1 4 0v6a8 8 0 0 1-8 8h-2c-2.8 0-4.5-.86-5.99-2.34l-3.6-3.6a2 2 0 0 1 2.83-2.82L7 15"/>'],
                    ['label'=>'Funeral Mass','href'=>'/inquiry','svg'=>'<path d="M17 14V2"/><path d="M9 18.12 10 14H4.17a2 2 0 0 1-1.92-2.56l2.33-8A2 2 0 0 1 6.5 2H20a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-2.76a2 2 0 0 0-1.79 1.11L12 22h0a3.13 3.13 0 0 1-3-3.88Z"/>'],
                    ['label'=>'House Blessing','href'=>'/inquiry','svg'=>'<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>'],
                    ['label'=>'Car Blessing','href'=>'/inquiry','svg'=>'<path d="M19 17H5v-5l2-6h10l2 6v5Z"/><circle cx="7.5" cy="17.5" r="1.5"/><circle cx="16.5" cy="17.5" r="1.5"/><path d="M5 12h14"/>'],
                ];
                @endphp
                @foreach($services as $s)
                <a href="{{ $s['href'] }}" class="group flex flex-col items-center gap-3 p-6 rounded-2xl border border-white/10 hover:border-accent/50 hover:bg-white/5 transition-all text-center">
                    <div class="h-12 w-12 rounded-xl border border-white/15 flex items-center justify-center text-white/50 group-hover:border-accent/50 group-hover:text-accent transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">{!! $s['svg'] !!}</svg>
                    </div>
                    <span class="text-white/60 group-hover:text-white transition-colors text-xs font-bold uppercase tracking-wider">{{ $s['label'] }}</span>
                </a>
                @endforeach
            </div>
            <p class="text-center mt-8 text-white/30 text-xs">Inquire about any sacramental service at our parish office.</p>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
         INTENTION CTA
    ═══════════════════════════════════════════ --}}
    <section class="py-24 px-4 bg-background">
        <div class="container mx-auto max-w-3xl text-center">
            <div class="text-5xl mb-6">🙏</div>
            <h2 class="font-heading text-4xl md:text-5xl font-bold text-primary italic mb-5 leading-tight">
                Offer a Mass Intention
            </h2>
            <p class="text-muted-foreground text-lg leading-relaxed mb-10 max-w-xl mx-auto">
                Unite your prayers with the Holy Sacrifice of the Mass. Submit your intention online and our staff will include it in the upcoming liturgy.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="/submit-intention" class="px-10 py-4 rounded-full bg-accent text-[#1a0a0e] font-black text-sm uppercase tracking-widest hover:bg-accent/90 transition-all shadow-xl shadow-accent/20 hover:-translate-y-0.5">
                    Submit Intention
                </a>
                <a href="/track" class="px-10 py-4 rounded-full border border-border text-primary font-bold text-sm uppercase tracking-widest hover:border-accent/50 hover:text-accent transition-all">
                    Track Status
                </a>
            </div>
        </div>
    </section>

</x-public-layout>