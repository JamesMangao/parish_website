<x-public-layout>
    <x-slot name="meta">
        <meta name="description" content="Stay updated with the latest announcements and parish news from Sto. Rosario Parish.">
        <meta property="og:title" content="Parish Announcements | Sto. Rosario Parish">
        <meta property="og:description" content="Latest news, novenas, community updates, and event announcements from Sto. Rosario Parish.">
    </x-slot>

    <section class="max-w-6xl mx-auto px-6 pt-20 pb-16">
        <div class="text-center mb-16 animate-in fade-in slide-in-from-bottom-4 duration-500">
            <p class="text-[12px] font-black uppercase tracking-[0.3em] text-accent mb-4">Stay Informed</p>
            <h1 class="font-heading text-4xl md:text-6xl font-black mb-6 text-primary italic uppercase leading-tight">
                Parish Announcements
            </h1>
            <div class="h-1.5 w-32 bg-accent mx-auto rounded-full mb-8"></div>
            <p class="text-xl text-muted-foreground max-w-2xl mx-auto italic">
                Latest news, novenas, and community updates from Sto. Rosario Parish.
            </p>
        </div>

        <div class="text-center mb-12">
            <div class="flex items-center justify-center gap-4 mb-5">
                <span style="display:block;flex:1;max-width:60px;height:1px;background:linear-gradient(90deg,transparent,rgba(201,162,0,.4));"></span>
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/><line x1="12" y1="2" x2="12" y2="0"/><line x1="10" y1="1" x2="14" y2="1"/></svg>
                <span style="display:block;flex:1;max-width:60px;height:1px;background:linear-gradient(90deg,rgba(201,162,0,.4),transparent);"></span>
            </div>
            <h2 class="font-cinzel font-semibold" style="font-size:clamp(1.25rem,3vw,2.15rem);color:var(--blue-deep);letter-spacing:.16em;margin-bottom:8px;">LATEST ANNOUNCEMENTS</h2>
            <p style="color:rgba(13,42,82,.4);font-size:15.5px;max-width:480px;margin:0 auto 14px;">Stay informed. Be involved. Grow in faith together.</p>
            <div class="flex justify-center"><div style="width:6px;height:6px;background:rgba(201,162,0,.42);transform:rotate(45deg);"></div></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
            @forelse($latestAnnouncements as $ann)
                <x-announcement-card :ann="$ann" />
            @empty
                <div class="col-span-full text-center py-20 bg-muted/30 rounded-[3rem] border-2 border-dashed">
                    <div class="font-cinzel text-5xl mb-5 opacity-20 text-primary">✝</div>
                    <h3 class="font-heading font-bold italic text-2xl mb-2 text-primary">
                        No Announcements
                    </h3>
                    <p class="text-muted-foreground italic">
                        There are currently no announcements published. Please check back soon.
                    </p>
                </div>
            @endforelse
        </div>

        @if($latestAnnouncements->hasPages())
            <div class="mb-20">
                {{ $latestAnnouncements->links() }}
            </div>
        @endif

        @if($featuredAnnouncement)
            @php
                $categoryConfigs = [
                    'Parish Life'  => ['tint' => '#FBEEE7', 'color' => '#B5562F'],
                    'Liturgical'   => ['tint' => '#F3ECFA', 'color' => '#6B3FA0'],
                    'Sacraments'   => ['tint' => '#FBF3DC', 'color' => '#A87F22'],
                    'Formation'    => ['tint' => '#EEF1F6', 'color' => '#1B2A4A'],
                    '_default'     => ['tint' => '#E8F4F0', 'color' => '#2D6A4F'],
                ];
                $categoryIcons = [
                    'Parish Life' => '<path d="M16 21v-2a4 4 0 0 0-3.7-3.97"/><path d="M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z"/><path d="M22 21v-2a4 4 0 0 0-4-4h-2"/><circle cx="6" cy="9" r="4"/>',
                    'Liturgical'  => '<path d="M12 2L2 21h10l2-4 2 4z"/><path d="M12 2L22 21H12z"/><path d="M6 17V9"/>',
                    'Sacraments'  => '<path d="M20 14.69 12 23l-8-8.31A6 6 0 0 1 12 7a6 6 0 0 1 10 7.69Z"/>',
                    'Formation'   => '<path d="M4 19V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v13"/><path d="M4 19l8-8 8 8"/><path d="M8 7v4"/><path d="M16 7v2"/>',
                    '_default'    => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>',
                ];
                $heroCategory = $featuredAnnouncement->category ?? 'Parish Life';
                $heroCfg = $categoryConfigs[$heroCategory] ?? $categoryConfigs['_default'];
                $heroIconPath = $categoryIcons[$heroCategory] ?? $categoryIcons['_default'];
            @endphp

            <div class="text-center mb-10">
                <p class="text-[12px] font-black uppercase tracking-[0.3em] text-accent mb-3">Featured</p>
                <div class="h-1 w-16 bg-accent mx-auto rounded-full"></div>
            </div>

            <article class="bg-card border border-muted rounded-2xl shadow-xl overflow-hidden group relative" style="border-top-width:4px;border-top-color:{{ $heroCfg['tint'] }};">
                <div class="p-6 md:p-10">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-2">
                            @if($featuredAnnouncement->is_recruitment)
                                <span class="inline-flex items-center gap-1 text-[8px] font-black uppercase tracking-widest text-accent">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    <span>Recruitment</span>
                                </span>
                            @endif
                        </div>
                        <div class="w-12 h-12 rounded-full flex-shrink-0 flex items-center justify-center" style="background:{{ $heroCfg['tint'] }};">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="{{ $heroCfg['color'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                {!! $heroIconPath !!}
                            </svg>
                        </div>
                    </div>

                    <h2 class="font-heading font-bold italic text-2xl md:text-3xl text-primary leading-tight mb-4 group-hover:text-accent transition-colors">
                        <a href="{{ route('announcements.show', $featuredAnnouncement) }}" class="focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded">
                            {{ $featuredAnnouncement->title }}
                        </a>
                    </h2>

                    @if($featuredAnnouncement->content)
                        <p class="text-base text-muted-foreground leading-relaxed line-clamp-3 mb-6 max-w-4xl">
                            {{ strip_tags($featuredAnnouncement->content) }}
                        </p>
                    @endif

                    <div class="flex flex-wrap items-center gap-4">
                        <a href="{{ route('announcements.show', $featuredAnnouncement) }}" class="inline-flex items-center gap-1 text-[11px] font-bold uppercase tracking-wider text-primary hover:text-accent transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded">
                            Read more <span aria-hidden="true">&rarr;</span>
                        </a>
                        @if($featuredAnnouncement->is_recruitment)
                            @if($featuredAnnouncement->registration_link)
                                <a href="{{ $featuredAnnouncement->registration_link }}" target="_blank" rel="noopener" class="gold-btn text-[9.5px] px-3 py-1 rounded-lg font-bold uppercase tracking-wider">Register Now</a>
                            @else
                                <a href="{{ route('about') }}#visit-map" class="gold-btn text-[9.5px] px-3 py-1 rounded-lg font-bold uppercase tracking-wider">Visit Parish Office</a>
                            @endif
                        @endif
                    </div>
                </div>
            </article>
        @endif
    </section>
</x-public-layout>
