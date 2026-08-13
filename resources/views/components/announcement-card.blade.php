@props(['ann'])

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
    $category = $ann->category ?? 'Parish Life';
    $cfg = $categoryConfigs[$category] ?? $categoryConfigs['_default'];
    $iconPath = $categoryIcons[$category] ?? $categoryIcons['_default'];
@endphp

<article class="bg-card border border-muted rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden group relative h-[268px]" style="border-top-width:4px;border-top-color:{{ $cfg['tint'] }};">
    <div class="p-5 flex flex-col h-full">
        <div class="flex items-start justify-between mb-3">
            <div class="flex items-center gap-2">
                @if($ann->is_recruitment)
                    <span class="inline-flex items-center gap-1 text-[8px] font-black uppercase tracking-widest text-accent">
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <span>Recruitment</span>
                    </span>
                @endif
            </div>
            <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center" style="background:{{ $cfg['tint'] }};">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="{{ $cfg['color'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    {!! $iconPath !!}
                </svg>
            </div>
        </div>

        <h3 class="font-heading font-bold italic text-base text-primary leading-tight mb-2 line-clamp-2 min-h-[2.7em] flex-1 group-hover:text-accent transition-colors">
            <a href="{{ route('announcements.show', $ann) }}" class="focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded">
                {{ $ann->title }}
            </a>
        </h3>

        @if($ann->content)
            <p class="text-sm text-muted-foreground leading-relaxed line-clamp-2 min-h-[2.6em] mb-4">
                {{ strip_tags($ann->content) }}
            </p>
        @endif

        <div class="mt-auto pt-4 border-t border-muted flex items-center justify-between">
            <a href="{{ route('announcements.show', $ann) }}" class="inline-flex items-center gap-1 text-[11px] font-bold uppercase tracking-wider text-primary hover:text-accent transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded">
                Read more <span aria-hidden="true">&rarr;</span>
            </a>
            @if($ann->is_recruitment)
                @if($ann->registration_link)
                    <a href="{{ $ann->registration_link }}" target="_blank" rel="noopener" class="gold-btn text-[9.5px] px-2.5 py-1 rounded-lg font-bold uppercase tracking-wider">Register Now</a>
                @else
                    <a href="{{ route('about') }}#visit-map" class="gold-btn text-[9.5px] px-2.5 py-1 rounded-lg font-bold uppercase tracking-wider">Visit Parish Office</a>
                @endif
            @endif
        </div>
    </div>
</article>
