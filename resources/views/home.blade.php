<x-public-layout>
    <!-- Hero Section -->
    <section class="relative h-[600px] flex items-center justify-center overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img src="/bg.png" alt="Sto. Rosario Parish" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-primary/70"></div>
        </div>

        <div class="container relative z-10 mx-auto px-4 text-center">
            <div
                class="mb-6 inline-flex h-12 w-12 items-center justify-center rounded-full bg-accent/20 text-accent backdrop-blur-sm">
                <span class="text-2xl font-bold">✝</span>
            </div>
            <h1 class="font-heading text-5xl md:text-7xl font-bold text-white mb-6 drop-shadow-lg">
                Sto. Rosario Parish
            </h1>
            <p class="max-w-2xl mx-auto text-lg md:text-xl text-white/90 mb-10 leading-relaxed drop-shadow-md">
                A place of faith, hope, and community in the heart of the parish.
                Join us in our journey of spiritual growth and service.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="/mass-schedule"
                    class="w-full sm:w-auto px-8 py-3 rounded-lg bg-accent text-accent-foreground font-semibold hover:opacity-90 transition-opacity shadow-lg">
                    Mass Schedule
                </a>
                <a href="/submit-intention"
                    class="w-full sm:w-auto px-8 py-3 rounded-lg bg-white/10 text-white font-semibold backdrop-blur-md border border-white/20 hover:bg-white/20 transition-all">
                    Offer an Intention
                </a>
            </div>
        </div>

        <!-- Decoration -->
        <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-background to-transparent z-10"></div>
    </section>

    <!-- Next Mass Card -->
    <section class="container -mt-12 relative z-20 mx-auto px-4 mb-20">
        <div class="max-w-4xl mx-auto glass-card p-8 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-full bg-accent/10 flex items-center justify-center text-accent">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-church">
                        <path d="m18 7 4 2v11a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9l4-2" />
                        <path d="M14 22v-4a2 2 0 0 0-2-2v0a2 2 0 0 0-2 2v4" />
                        <path d="M18 5 12 2 6 5" />
                        <path d="M12 7V2" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-heading text-lg font-bold text-primary">Next Mass Service</h3>
                    <p class="text-sm text-muted-foreground">Join our community in prayer</p>
                </div>
            </div>

            @if($nextMass)
                <div class="flex flex-col md:items-end text-right md:text-right">
                    @if(($nextMass->calculated_day ?? '') === 'Ongoing')
                        <p class="text-xl md:text-2xl font-bold text-accent animate-pulse">Ongoing Mass Service</p>
                        <p class="text-xs md:text-sm text-primary/60 italic font-medium">Started at
                            {{ $nextMass->calculated_time }}</p>
                    @else
                        <p class="text-2xl font-bold text-accent">
                            {{ $nextMass->calculated_day ?? 'Next' }} At {{ $nextMass->calculated_time ?? '00:00' }}
                        </p>
                        @if(isset($nextMass->mass_type))
                            <span
                                class="text-[10px] font-black uppercase tracking-[0.2em] bg-accent/10 px-2 py-0.5 rounded-full border border-accent/20">{{ $nextMass->mass_type }}
                                Mass</span>
                        @endif
                    @endif
                </div>
            @else
                <div class="flex flex-col md:items-end">
                    <p class="text-2xl font-bold text-accent">Checking Service...</p>
                    <p class="text-sm font-medium text-primary">Please check full schedule</p>
                </div>
            @endif

            <a href="/mass-schedule" class="text-primary font-semibold hover:underline flex items-center gap-1 group">
                Full Schedule
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-chevron-right group-hover:translate-x-1 transition-transform">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </a>
        </div>
    </section>

    @if(isset($upcomingEvents) && $upcomingEvents->count() > 0)
        <!-- Upcoming Events Preview -->
        <section class="container mx-auto px-4 mb-20">
            <div class="text-center mb-12">
                <br>
                <h2 class="font-heading text-3xl md:text-4xl font-bold text-primary mb-4 italic">
                    Upcoming Events</h2>
                <div class="section-divider mx-auto"></div>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                @foreach($upcomingEvents as $event)
                    <div
                        class="group relative bg-card hover:bg-accent/5 rounded-[2rem] border border-muted p-6 transition-all duration-300 shadow-sm hover:shadow-xl hover:-translate-y-1 flex items-start gap-6">
                        <!-- Elegant Date Badge -->
                        <div
                            class="flex flex-col items-center justify-center shrink-0 w-24 h-24 rounded-2xl bg-primary/5 border border-primary/10 group-hover:bg-accent/10 group-hover:border-accent/20 transition-colors">
                            <span
                                class="text-[10px] font-black uppercase tracking-widest text-primary/60">{{ $event->event_date->format('M') }}</span>
                            <span
                                class="text-3xl font-heading font-black text-primary my-0.5 group-hover:text-accent transition-colors">{{ $event->event_date->format('d') }}</span>
                            <span class="text-[10px] font-bold text-primary/40">{{ $event->event_date->format('Y') }}</span>
                        </div>

                        <div class="flex flex-col justify-center">
                            <h3
                                class="font-heading text-xl font-bold text-primary mb-2 leading-tight group-hover:text-accent transition-colors">
                                {{ $event->title }}</h3>
                            <p class="text-sm text-muted-foreground line-clamp-2 mb-3">
                                {{ $event->description }}
                            </p>
                            <a href="/events"
                                class="text-xs font-bold uppercase tracking-widest text-accent hover:text-primary transition-colors flex items-center gap-1 group/btn">
                                View Details
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    class="group-hover/btn:translate-x-1 transition-transform">
                                    <path d="m9 18 6-6-6-6" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-8">
                <a href="/events" class="inline-flex items-center gap-2 group text-primary font-bold">
                    View All
                    <span
                        class="underline group-hover:text-accent transition-colors uppercase tracking-widest text-xs">Click
                        here</span>
                </a>
            </div>
        </section>
    @endif

    <!-- Announcements -->
    <section class="py-20 bg-background">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="font-heading text-3xl md:text-4xl font-bold text-primary mb-4 italic italic">Latest
                    Announcements</h2>
                <div class="section-divider"></div>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                @forelse($announcements as $item)
                    <div
                        class="group bg-card rounded-xl border p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="mb-4 text-accent">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="lucide lucide-megaphone">
                                <path d="m3 11 18-5v12L3 14v-3z" />
                                <path d="M11.6 16.8a3 3 0 1 1-5.8-1.6" />
                            </svg>
                        </div>
                        <h3
                            class="font-heading text-xl font-bold mb-3 text-primary group-hover:text-accent transition-colors">
                            {{ $item->title }}</h3>
                        <p class="text-muted-foreground text-sm line-clamp-3 mb-6 leading-relaxed">
                            {{ $item->content }}
                        </p>
                        <div
                            class="flex items-center justify-between text-xs text-muted-foreground border-t pt-4 border-muted">
                            <span class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-calendar">
                                    <path d="M8 2v4" />
                                    <path d="M16 2v4" />
                                    <rect width="18" height="18" x="3" y="4" rx="2" />
                                    <path d="M3 10h18" />
                                </svg>
                                {{ $item->created_at->format('M d, Y') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-12 text-muted-foreground italic italic">
                        No recent announcements at this time.
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-12">
                <a href="/submit-intention" class="inline-flex items-center gap-2 group text-primary font-bold">
                    Need to request a Mass Intention?
                    <span
                        class="underline group-hover:text-accent transition-colors uppercase tracking-widest text-xs">Click
                        here</span>
                </a>
            </div>
        </div>
    </section>
</x-public-layout>