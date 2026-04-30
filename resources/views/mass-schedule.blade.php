<x-public-layout>
<x-slot name="meta">
    <meta name="description" content="Mass schedule at Sto. Rosario Parish – Pacita, San Pedro, Laguna. View weekly and special Mass times.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600;1,700&family=Cinzel:wght@400;500;600&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --gold:       #F5C518;
            --gold-light: #FFD740;
            --gold-pale:  #FFF8DC;
            --blue-deep:  #0D2A52;
            --blue-mid:   #1A4080;
            --blue-soft:  #2255A4;
            --blue-pale:  #EBF2FF;
            --cream:      #F5F5DC;
            --cream-deep: #EDF2FC;
            --stone-text: #1E3254;
            --yellowish:  #fdf8acff; 
        }
        body, .bg-background { background-color: #FFFFFF !important; }
        body { font-family: 'Jost', sans-serif; }
        .font-heading { font-family: 'Cormorant Garamond', Georgia, serif; }
        .font-cinzel  { font-family: 'Cinzel', Georgia, serif; }

        .eyebrow {
            font-size: 10px; font-weight: 600;
            letter-spacing: 0.32em; text-transform: uppercase;
            color: var(--gold);
        }
        .divider-ornament {
            display: flex; align-items: center; gap: 12px; justify-content: center;
        }
        .divider-ornament::before,
        .divider-ornament::after {
            content: ''; height: 1px; width: 48px;
            background: linear-gradient(90deg, transparent, rgba(245,197,24,0.5));
        }
        .divider-ornament::after {
            background: linear-gradient(90deg, rgba(245,197,24,0.5), transparent);
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        .animate-fade-up { animation: fadeUp 0.9s ease both; }
        .animate-fade-in { animation: fadeIn 1.2s ease both; }
        .delay-1 { animation-delay: 0.15s; }
        .delay-2 { animation-delay: 0.30s; }
        .delay-3 { animation-delay: 0.45s; }

        .reveal {
            opacity: 0; transform: translateY(40px);
            transition: all 1.2s cubic-bezier(0.22, 1, 0.36, 1);
            will-change: transform, opacity;
        }
        .reveal.active { opacity: 1; transform: translateY(0); }

        /* ── Page hero banner ── */
        .page-hero {
            background: linear-gradient(160deg, var(--blue-deep) 0%, var(--blue-mid) 60%, #0f3060 100%);
            position: relative; overflow: hidden;
        }
        .page-hero::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(ellipse 70% 60% at 50% 0%, rgba(245,197,24,0.10) 0%, transparent 70%);
            pointer-events: none;
        }

        /* ── Schedule cards ── */
        .card-sacred {
            background: #FFFFFF;
            border: 1px solid rgba(26,64,128,0.12);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            transition: all 0.3s ease;
        }
        .card-sacred:hover {
            border-color: rgba(245, 197, 24, 0.55);
            box-shadow: 0 10px 40px rgba(13,42,82,0.12);
            transform: translateY(-3px);
        }

        /* ── Time pill ── */
        .time-pill {
            display: inline-flex; align-items: center; gap: 6px;
            background: #FFFFFF;
            border: 1px solid rgba(245,197,24,0.5);
            border-radius: 100px;
            padding: 7px 18px;
            font-family: 'Cormorant Garamond', Georgia, serif;
            font-size: 1.15rem; font-weight: 700; font-style: italic;
            color: var(--blue-deep);
            transition: all 0.2s ease;
        }
        .card-sacred:hover .time-pill {
            background: var(--blue-pale);
            border-color: rgba(34,85,164,0.3);
            color: var(--blue-deep);
        }

        /* ── Type badge ── */
        .type-badge {
            display: inline-block;
            font-size: 8.5px; font-weight: 700;
            letter-spacing: 0.28em; text-transform: uppercase;
            padding: 4px 14px; border-radius: 100px;
        }
        .badge-sunday      { background: #FFFFFF; color: #8a6800;  border: 1px solid rgba(245,197,24,0.6); }
        .badge-anticipated { background: #FFFFFF; color: #7a5e00;  border: 1px solid rgba(245,197,24,0.4); }
        .badge-weekday     { background: #FFFFFF; color: var(--blue-mid); border: 1px solid rgba(26,64,128,0.3); }
        .badge-saturday    { background: #FFFFFF; color: var(--blue-soft); border: 1px solid rgba(34,85,164,0.3); }
        .badge-special     { background: #FFFFFF; color: var(--blue-deep); border: 1px solid rgba(13,42,82,0.3); }

        /* ── Section rule ── */
        .section-title-rule {
            display: flex; align-items: center; gap: 16px; margin-bottom: 28px;
        }
        .section-title-rule::after {
            content: ''; flex: 1; height: 1px;
            background: linear-gradient(90deg, rgba(26,64,128,0.15), transparent);
        }

        /* ── Cal buttons ── */
        .cal-link {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 9px; font-weight: 700; letter-spacing: 0.18em; text-transform: uppercase;
            color: rgba(13,42,82,0.65); text-decoration: none;
            padding: 5px 12px; border-radius: 100px;
            border: 1px solid rgba(13,42,82,0.25);
            background: #FFFFFF;
            transition: all 0.2s ease; cursor: pointer;
        }
        .cal-link:hover {
            border-color: rgba(34,85,164,0.3);
            color: var(--blue-deep);
            background: var(--blue-pale);
        }

        /* ── Gold btn (reused from home) ── */
        .gold-btn {
            background: linear-gradient(135deg, #FFD740 0%, #F5C518 55%, #E0A800 100%);
            color: var(--blue-deep);
            box-shadow: 0 4px 20px rgba(245,197,24,0.40), 0 1px 0 rgba(255,255,255,0.35) inset;
            transition: all 0.25s ease; font-weight: 700;
        }
        .gold-btn:hover {
            box-shadow: 0 8px 32px rgba(245,197,24,0.55), 0 1px 0 rgba(255,255,255,0.35) inset;
            transform: translateY(-2px);
        }

        html { scroll-behavior: smooth; }
    </style>
</x-slot>

{{-- ═══════════════════════════════════════════════════ --}}
{{-- PAGE HERO                                          --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="page-hero py-24 md:py-32">

    {{-- Watermark cross --}}
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none select-none font-cinzel leading-none"
         style="font-size:340px; color:rgba(255,255,255,0.018);" aria-hidden="true">✝</div>

    {{-- Top gold rule --}}
    <div class="absolute top-0 left-0 right-0 h-px"
         style="background:linear-gradient(90deg,transparent,rgba(245,197,24,0.35),transparent);"></div>

    <div class="max-w-[1200px] mx-auto px-6 relative z-10 text-center">

        {{-- Cross icon with rays --}}
        <div class="flex justify-center mb-6 animate-fade-in">
            <div class="relative flex items-center justify-center" style="width:52px; height:52px;">
                <svg width="52" height="52" viewBox="0 0 52 52" style="position:absolute;inset:0;" fill="none" aria-hidden="true">
                    <line x1="26" y1="2"  x2="26" y2="10" stroke="rgba(253, 248, 172, 1)" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="26" y1="42" x2="26" y2="50" stroke="rgba(253, 248, 172, 1)" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="2"  y1="26" x2="10" y2="26" stroke="rgba(253, 248, 172, 1)" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="42" y1="26" x2="50" y2="26" stroke="rgba(253, 248, 172, 1)" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="8"  y1="8"  x2="13" y2="13" stroke="rgba(253, 248, 172, 1)" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="39" y1="39" x2="44" y2="44" stroke="rgba(253, 248, 172, 1)" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="44" y1="8"  x2="39" y2="13" stroke="rgba(253, 248, 172, 1)" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="8"  y1="44" x2="13" y2="39" stroke="rgba(253, 248, 172, 1)" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <span class="font-cinzel relative z-10" style="color:var(--yellowish); font-size:1.5rem;" aria-hidden="true">✝</span>
            </div>
        </div>

        <div class="divider-ornament mb-4 animate-fade-in delay-1">
            <span class="eyebrow" style="color:rgba(253, 248, 172, 1);">Holy Eucharist</span>
        </div>

        <h1 class="font-heading animate-fade-up delay-1"
            style="font-size:clamp(2.8rem,6vw,5.5rem); font-weight:700; font-style:italic;
                   color:#FFFFFF; line-height:1.05; letter-spacing:-0.01em; margin-bottom:16px;
                   text-shadow:0 4px 32px rgba(0,0,0,0.45);">
            Mass Schedule
        </h1>

        <p class="animate-fade-up delay-2 font-heading"
           style="font-style:italic; color:rgba(253, 248, 172, 1); font-size:clamp(0.95rem,1.8vw,1.15rem);
                  font-weight:300; margin-bottom:0;">
            Pacita Complex 1, San Pedro, Laguna
        </p>
    </div>

    {{-- Bottom gold rule --}}
    <div class="absolute bottom-0 left-0 right-0 h-px"
         style="background:linear-gradient(90deg,transparent,rgba(245,197,24,0.25),transparent);"></div>
</section>


{{-- ═══════════════════════════════════════════════════ --}}
{{-- SCHEDULE CONTENT                                   --}}
{{-- ═══════════════════════════════════════════════════ --}}
<div x-data="{
    downloadICS(title, desc, start, end, loc, rrule = '') {
        let ics = [
            'BEGIN:VCALENDAR','VERSION:2.0','PRODID:-//Sto. Rosario Parish//EN',
            'BEGIN:VEVENT',
            'UID:' + Math.random().toString(36).substr(2,9) + '@storosario',
            'DTSTAMP:' + new Date().toISOString().replace(/[-:]/g,'').split('.')[0] + 'Z',
            'DTSTART:' + start, 'DTEND:' + end,
            'SUMMARY:' + title, 'DESCRIPTION:' + desc, 'LOCATION:' + loc
        ];
        if (rrule) ics.push('RRULE:' + rrule);
        ics.push('END:VEVENT','END:VCALENDAR');
        const blob = new Blob([ics.join('\r\n')], { type: 'text/calendar;charset=utf-8' });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = title.replace(/\s+/g,'_') + '.ics';
        document.body.appendChild(a); a.click(); document.body.removeChild(a);
    }
}">

@php
    $order  = ['sunday','anticipated','weekday','saturday','special'];
    $labels = [
        'sunday'      => 'Sunday Masses',
        'anticipated' => 'Anticipated Sunday Masses',
        'weekday'     => 'Weekday Masses',
        'saturday'    => 'Saturday Masses',
        'special'     => 'Special Masses',
    ];
    $dayMap = [
        'Sunday'=>'SU','Monday'=>'MO','Tuesday'=>'TU','Wednesday'=>'WE',
        'Thursday'=>'TH','Friday'=>'FR','Saturday'=>'SA',
    ];
@endphp

<section class="py-20 max-w-[1100px] mx-auto px-6">

    @if(count($schedules) === 0)
    {{-- Empty state --}}
    <div class="reveal flex flex-col items-center justify-center py-28 text-center rounded-3xl"
         style="border:1.5px dashed rgba(26,64,128,0.14); background:rgba(235,242,255,0.35);">
        <div class="font-cinzel text-3xl mb-4 opacity-30" style="color:var(--blue-deep);">✝</div>
        <h3 class="font-heading font-bold italic text-2xl mb-2" style="color:var(--blue-deep);">No Schedules Available</h3>
        <p style="color:rgba(13,42,82,0.42); font-size:14px; max-width:380px; line-height:1.75;">
            No mass schedules posted at this time. Contact the parish office for inquiries.
        </p>
    </div>
    @endif

    @foreach($order as $type)
    @if(isset($schedules[$type]) && $schedules[$type]->count() > 0)

    <div class="reveal mb-20">

        {{-- Section heading --}}
        <div class="section-title-rule">
            <div>
                <p class="eyebrow mb-1">{{ $type === 'special' ? 'Liturgical Calendar' : 'Regular Schedule' }}</p>
                <h2 class="font-heading font-bold italic"
                    style="font-size:clamp(1.8rem,3.5vw,2.8rem); color:var(--blue-deep); line-height:1.1;">
                    {{ $labels[$type] }}
                </h2>
            </div>
        </div>

        {{-- Cards --}}
        <div class="space-y-5 p-6 md:p-8 rounded-2xl" style="background: #FDFBF5; border: 1px solid rgba(245, 197, 24, 0.25);">
        @foreach($schedules[$type] as $s)
        @php
            $times      = is_array($s->time) ? array_filter($s->time) : array_filter([$s->time]);
            $firstTime  = !empty($times[0]) ? \Carbon\Carbon::parse(is_array($times[0]) ? ($times[0]['time'] ?? '08:00') : $times[0])->format('Hi00') : '080000';
            $endTime    = !empty($times[0]) ? \Carbon\Carbon::parse(is_array($times[0]) ? ($times[0]['time'] ?? '08:00') : $times[0])->addHour()->format('Hi00') : '090000';
            $rrule      = '';
            $dtStart    = now()->format('Ymd').'T'.$firstTime;
            $dtEnd      = now()->format('Ymd').'T'.$endTime;

            if ($s->day_of_week) {
                $days    = is_array($s->day_of_week) ? $s->day_of_week : [$s->day_of_week];
                $rrule   = 'FREQ=WEEKLY;BYDAY='.implode(',', array_map(fn($d) => $dayMap[$d] ?? 'SU', $days));
                $next    = now();
                while (!in_array($next->format('l'), $days)) { $next->addDay(); }
                $dtStart = $next->format('Ymd').'T'.$firstTime;
                $dtEnd   = $next->format('Ymd').'T'.$endTime;
            } elseif ($s->specific_date) {
                $dtStart = $s->specific_date->format('Ymd').'T'.$firstTime;
                $dtEnd   = $s->specific_date->format('Ymd').'T'.$endTime;
            }

            $googleUrl = 'https://calendar.google.com/calendar/render?action=TEMPLATE'
                .'&text='.urlencode('Mass: '.($s->title ?? 'Sto Rosario'))
                .'&dates='.$dtStart.'/'.$dtEnd
                .'&location='.urlencode('Sto. Rosario Parish, Pacita Complex 1, San Pedro, Laguna');
            if ($rrule) $googleUrl .= '&recur=RRULE:'.$rrule;
        @endphp

        <article class="card-sacred p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">

                {{-- Left: info --}}
                <div class="flex-1 min-w-0">
                    {{-- Title row --}}
                    @if($s->title)
                    <div class="flex flex-wrap items-center gap-3 mb-3">
                        <h3 class="font-heading font-bold italic"
                            style="font-size:1.45rem; color:var(--blue-deep); line-height:1.2;">
                            {{ $s->title }}
                        </h3>
                    </div>
                    @endif

                    {{-- Day / date --}}
                    @if($s->day_of_week || $s->specific_date)
                    <div class="flex items-center gap-2 mb-5">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                             stroke="rgba(13,42,82,0.38)" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect width="18" height="18" x="3" y="4" rx="2"/>
                            <path d="M16 2v4M8 2v4M3 10h18"/>
                        </svg>
                        <span style="font-size:12px; font-weight:600; color:rgba(13,42,82,0.48); letter-spacing:0.06em;">
                            @if(is_array($s->day_of_week))
                                {{ implode(', ', $s->day_of_week) }}
                            @else
                                {{ $s->day_of_week ?: ($s->specific_date ? $s->specific_date->format('F d, Y') : '') }}
                            @endif
                        </span>
                    </div>
                    @endif

                    {{-- Time pills --}}
                    <div class="flex flex-wrap gap-2">
                        @forelse($times as $t)
                        @php
                            $label = is_array($t) ? \Carbon\Carbon::parse($t['time'] ?? '00:00')->format('g:i A') : \Carbon\Carbon::parse($t)->format('g:i A');
                            $sub   = is_array($t) && !empty($t['title']) ? $t['title'] : null;
                        @endphp
                        <div class="time-pill">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                            </svg>
                            {{ $label }}{{ $sub ? ' · '.$sub : '' }}
                        </div>
                        @empty
                        <span style="font-size:12px; color:rgba(13,42,82,0.32); font-style:italic;">Time TBD</span>
                        @endforelse
                    </div>
                </div>

                {{-- Right: cal buttons --}}
                <div class="flex items-center gap-2 md:flex-col md:items-end shrink-0">
                    <a href="{{ $googleUrl }}" target="_blank" rel="noopener" class="cal-link" title="Add to Google Calendar">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18M12 14v4M10 16h4"/></svg>
                        Google Cal
                    </a>
                    <button @click="downloadICS(
                        'Mass: {{ addslashes($s->title ?? 'Sto Rosario') }}',
                        'Regular mass schedule at Sto. Rosario Parish',
                        '{{ $dtStart }}','{{ $dtEnd }}',
                        'Sto. Rosario Parish, Pacita Complex 1, San Pedro, Laguna',
                        '{{ $rrule }}')"
                        class="cal-link" title="Download iCal">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                        iCal
                    </button>
                </div>

            </div>
        </article>
        @endforeach
        </div>
    </div>

    @endif
    @endforeach

    {{-- ── Special Masses Note ── --}}
    <div class="reveal mt-4 rounded-2xl flex items-start gap-5 p-6 md:p-8"
         style="background:#FFFFFF; border:1px solid rgba(245,197,24,0.28);
                box-shadow:0 4px 24px rgba(13,42,82,0.06);">
        <div class="shrink-0 w-10 h-10 rounded-xl flex items-center justify-center"
             style="background:rgba(245,197,24,0.10); border:1px solid rgba(245,197,24,0.28);">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#C9A200"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>
            </svg>
        </div>
        <div>
            <p class="eyebrow mb-2">Note for Special Masses</p>
            <p style="font-size:13px; color:rgba(13,42,82,0.52); line-height:1.8;">
                Mass times for holy days of obligation, feast days, and other special occasions may vary.
                Follow our announcements or social media pages for the most up-to-date information.
            </p>
        </div>
    </div>

</section>

{{-- /x-data --}}

<script>
document.addEventListener('DOMContentLoaded', () => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('active'); observer.unobserve(e.target); } });
    }, { threshold: 0.12, rootMargin: '0px 0px -50px 0px' });
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
});
</script>

</x-public-layout>