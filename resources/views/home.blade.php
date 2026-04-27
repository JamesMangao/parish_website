<x-public-layout>
<x-slot name="meta">
    <meta name="description" content="Welcome to Sto. Rosario Parish – Pacita, San Pedro, Laguna. Mass schedules, intentions, events, and community news.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600;1,700&family=Cinzel:wght@400;500;600&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --gold:        #F5C518;
            --gold-light:  #FFD740;
            --gold-pale:   #FFF8DC;
            --blue-deep:   #0D2A52;
            --blue-mid:    #1A4080;
            --blue-soft:   #2255A4;
            --blue-pale:   #EBF2FF;
            --cream:       #F7F9FF;
            --cream-deep:  #EDF2FC;
            --stone-text:  #1E3254;
        }
        body { background: var(--cream); font-family: 'Jost', sans-serif; }
        .font-heading { font-family: 'Cormorant Garamond', Georgia, serif; }
        .font-cinzel  { font-family: 'Cinzel', Georgia, serif; }

        .eyebrow {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.32em;
            text-transform: uppercase;
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
        @keyframes fadeIn  { from { opacity: 0; } to { opacity: 1; } }
        @keyframes shimmer {
            0%   { background-position: -200% center; }
            100% { background-position:  200% center; }
        }
        @keyframes scrollLine {
            0%   { transform: scaleY(0); transform-origin: top; }
            50%  { transform: scaleY(1); transform-origin: top; }
            51%  { transform: scaleY(1); transform-origin: bottom; }
            100% { transform: scaleY(0); transform-origin: bottom; }
        }

        html { scroll-behavior: smooth; }
        .animate-fade-up { animation: fadeUp 0.9s ease both; }
        .animate-fade-in { animation: fadeIn 1.2s ease both; }
        .delay-1 { animation-delay: 0.15s; }
        .delay-2 { animation-delay: 0.30s; }
        .delay-3 { animation-delay: 0.45s; }
        .delay-4 { animation-delay: 0.60s; }
        .delay-5 { animation-delay: 0.80s; }
        .scroll-line { animation: scrollLine 2s ease infinite; }

        /* ── Scroll Reveal ── */
        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: all 1.2s cubic-bezier(0.22, 1, 0.36, 1);
            will-change: transform, opacity;
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }
        .reveal-left {
            opacity: 0;
            transform: translateX(-40px);
            transition: all 1.2s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .reveal-left.active {
            opacity: 1;
            transform: translateX(0);
        }
        .reveal-right {
            opacity: 0;
            transform: translateX(40px);
            transition: all 1.2s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .reveal-right.active {
            opacity: 1;
            transform: translateX(0);
        }

        /* ── Buttons ── */
        .gold-btn {
            background: linear-gradient(135deg, #FFD740 0%, #F5C518 55%, #E0A800 100%);
            color: var(--blue-deep);
            box-shadow: 0 4px 20px rgba(245,197,24,0.40), 0 1px 0 rgba(255,255,255,0.35) inset;
            transition: all 0.25s ease;
            font-weight: 700;
        }
        .gold-btn:hover {
            box-shadow: 0 8px 32px rgba(245,197,24,0.55), 0 1px 0 rgba(255,255,255,0.35) inset;
            transform: translateY(-2px);
        }
        .ghost-btn {
            border: 1.5px solid rgba(245,197,24,0.5);
            color: rgba(255,255,255,0.9);
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(6px);
            transition: all 0.25s ease;
        }
        .ghost-btn:hover {
            border-color: var(--gold-light);
            color: var(--gold-light);
            background: rgba(245,197,24,0.10);
            transform: translateY(-2px);
        }

        /* ── Cards ── */
        .card-sacred {
            background: #FFFFFF;
            border: 1px solid rgba(26,64,128,0.12);
            border-radius: 20px;
            transition: all 0.3s ease;
        }
        .card-sacred:hover {
            border-color: rgba(245,197,24,0.55);
            box-shadow: 0 10px 40px rgba(13,42,82,0.12);
            transform: translateY(-3px);
        }

        .section-ruled { border-top: 1px solid rgba(26,64,128,0.08); }

        /* ── Hero ── */
        .hero-overlay {
            background: linear-gradient(
                180deg,
                rgba(8,20,45,0.68)   0%,
                rgba(10,25,55,0.58)  40%,
                rgba(8,20,45,0.80)   72%,
                rgba(247,249,255,1)  100%
            );
        }

        /* ── Hero title: "Sto. Rosario Parish" shimmer on the word Parish ── */
        .hero-title-accent {
            background: linear-gradient(
                90deg,
                #FFD740 0%, #FFFFFF 40%, #FFD740 60%, #F5C518 100%
            );
            background-size: 200% auto;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmer 4s linear infinite;
        }

        /* ── Hero badge pill ── */
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 6px 18px;
            border-radius: 100px;
            border: 1px solid rgba(245,197,24,0.35);
            background: rgba(245,197,24,0.08);
            backdrop-filter: blur(8px);
        }

        /* ── Mass card ── */
        .mass-card { background: linear-gradient(135deg, #FFFFFF 0%, #F0F5FF 100%); }

        /* ── Cross & Rays ── */
        .cross-container {
            position: relative;
            width: 40px;
            height: 52px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }
        .cross-ray {
            position: absolute;
            background: var(--gold);
            width: 1px;
            opacity: 0.4;
            transform-origin: bottom center;
        }
        .ray-1 { height: 12px; top: 0; left: 50%; transform: translateX(-50%); }
        .ray-2 { height: 10px; top: 4px; left: calc(50% - 10px); transform: rotate(-35deg); }
        .ray-3 { height: 10px; top: 4px; left: calc(50% + 10px); transform: rotate(35deg); }

        /* ── Events ── */
        .card-event {
            background: #FFFFFF;
            border: 1px solid rgba(26,64,128,0.1);
            border-radius: 20px;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
            text-align: center;
            position: relative;
        }
        .card-event:hover {
            border-color: var(--gold);
            box-shadow: 0 20px 50px rgba(13,42,82,0.12);
            transform: translateY(-5px);
        }
        .card-event-featured {
            border: 2px solid var(--gold);
            box-shadow: 0 10px 30px rgba(245,197,24,0.1);
        }
        .event-badge-today {
            position: absolute;
            top: -1px;
            left: -1px;
            background: #FFF9E6;
            border: 1px solid var(--gold);
            border-top-left-radius: 20px;
            border-bottom-right-radius: 20px;
            padding: 8px 18px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .event-location-bar {
            background: #F9FBFF;
            border-top: 1px solid rgba(26,64,128,0.06);
            padding: 14px;
            border-radius: 0 0 20px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
    </style>
</x-slot>


{{-- ═══════════════════════════════════════════════════ --}}
{{-- HERO                                               --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section style="position:relative; min-height:100svh; display:flex; flex-direction:column; align-items:center; justify-content:center; overflow:hidden;">

    {{-- Background photo --}}
    <div style="position:absolute; inset:0; z-index:0;">
        <img src="/bg.png" alt="Sto. Rosario Parish"
             style="width:100%; height:100%; object-fit:cover; filter:saturate(0.75) brightness(0.85); transform:scale(1.04);">
        <div class="hero-overlay" style="position:absolute; inset:0;"></div>
    </div>

    {{-- Blue atmospheric radial --}}
    <div style="position:absolute; inset:0; z-index:1; pointer-events:none;
                background: radial-gradient(ellipse 80% 60% at 50% 30%, rgba(26,64,128,0.22) 0%, transparent 70%);"></div>

    {{-- Grain texture --}}
    <div style="position:absolute; inset:0; z-index:2; pointer-events:none; opacity:0.03;
                background-image:url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22><filter id=%22n%22><feTurbulence type=%22fractalNoise%22 baseFrequency=%220.75%22 stitchTiles=%22stitch%22/></filter><rect width=%22200%22 height=%22200%22 filter=%22url(%23n)%22/></svg>');"></div>


    {{-- Content wrapper — pushed down by nav height, centered vertically --}}
    <div style="position:relative; z-index:10; text-align:center; display:flex; flex-direction:column;
                align-items:center; padding: 0 24px; width:100%; max-width:960px;
                margin-top: 72px;">

        {{-- Badge --}}
        <div class="hero-badge animate-fade-in" style="margin-bottom:24px;">
            <span style="width:6px; height:6px; border-radius:50%; background:var(--gold); display:block; box-shadow:0 0 8px rgba(245,197,24,0.8);"></span>
            <span style="font-size:9.5px; font-weight:600; letter-spacing:0.38em; text-transform:uppercase; color:rgba(255,248,180,0.85);">
                Est. · Diocese of San Pablo · Pacita
            </span>
        </div>

        {{-- ══ SINGLE-LINE TITLE ══
             "Sto. Rosario" in white, " Parish" shimmering gold — forced nowrap,
             clamp keeps it one line from ~360px upward.
        --}}
        <h1 class="font-heading animate-fade-up delay-1"
            style="white-space: nowrap;
                   font-weight: 700;
                   line-height: 1;
                   letter-spacing: -0.02em;
                   margin-bottom: 20px;
                   text-shadow: 0 4px 48px rgba(0,0,0,0.55);
                   font-size: clamp(2.4rem, 6.5vw, 6.4rem);">
            
            <em class="hero-title-accent" style="font-style:italic;">Sto. Rosario</em>
            <span style="color:#FFFFFF;">Parish</span>
        </h1>

        {{-- Location --}}
        <p class="font-heading animate-fade-up delay-2"
           style="font-style:italic; color:rgba(255,215,64,0.82); margin-bottom:14px;
                  font-size:clamp(0.9rem, 1.8vw, 1.15rem); font-weight:300;
                  text-shadow:0 2px 12px rgba(0,0,0,0.4);">
            Pacita Complex 1, San Pedro, Laguna
        </p>

        {{-- Thin gold rule --}}
        <div class="animate-fade-in delay-2"
             style="width:56px; height:1px; margin-bottom:22px;
                    background:linear-gradient(90deg, transparent, rgba(245,197,24,0.65), transparent);"></div>

        {{-- Description --}}
        <p class="animate-fade-up delay-3"
           style="color:rgba(220,232,255,0.78); font-size:clamp(0.85rem,1.4vw,1rem);
                  line-height:1.78; max-width:430px; font-weight:300; letter-spacing:0.01em;
                  margin-bottom:36px;">
            Home to the Queen of the Most Holy Rosary — a beacon of faith,
            community, and service for over four decades.
        </p>

        {{-- CTA buttons --}}
        <div class="animate-fade-up delay-4"
             style="display:flex; flex-wrap:wrap; align-items:center; justify-content:center; gap:12px; margin-bottom:40px;">
            <a href="/mass-schedule"
               class="ghost-btn inline-flex items-center gap-2 rounded-full font-bold uppercase"
               style="padding:13px 30px; font-size:10.5px; letter-spacing:0.18em; text-decoration:none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                Mass Schedule
            </a>
            <a href="/submit-intention"
               class="gold-btn inline-flex items-center gap-2 rounded-full"
               style="padding:13px 30px; font-size:10.5px; letter-spacing:0.18em; text-transform:uppercase; text-decoration:none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/></svg>
                Offer an Intention
            </a>
            <a href="/inquiry"
               class="ghost-btn inline-flex items-center gap-2 rounded-full font-bold uppercase"
               style="padding:13px 30px; font-size:10.5px; letter-spacing:0.18em; text-decoration:none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
                Parish Inquiry
            </a>
        </div>

        {{-- Stats strip --}}
        <div class="animate-fade-up delay-5"
             style="display:flex; align-items:center; justify-content:center; gap:40px;
                    padding-top:28px; width:100%; max-width:400px;
                    border-top:1px solid rgba(245,197,24,0.15);">
            @foreach([['40+','Years of Service'],['7','Weekly Masses'],['1','Community']] as $stat)
            <div style="text-align:center;">
                <div class="font-heading"
                     style="font-size:1.75rem; font-weight:700; font-style:italic;
                            color:var(--gold-light); line-height:1;">{{ $stat[0] }}</div>
                <div style="font-size:9px; text-transform:uppercase; letter-spacing:0.3em;
                             color:rgba(200,215,255,0.45); margin-top:5px;">{{ $stat[1] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Scroll cue --}}
    <div class="animate-fade-in delay-5"
         style="position:absolute; bottom:2.5rem; left:50%; transform:translateX(-50%);
                z-index:10; display:flex; flex-direction:column; align-items:center; gap:8px;">
        <span style="color:rgba(245,197,24,0.45); font-size:9px; text-transform:uppercase; letter-spacing:0.4em;">Scroll</span>
        <div class="scroll-line"
             style="height:36px; width:1.5px; background:linear-gradient(to bottom, rgba(245,197,24,0.6), transparent);"></div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════ --}}
{{-- NEXT MASS + OFFICE HOURS                           --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="max-w-5xl mx-auto px-6 mt-10 reveal">
    <div class="rounded-3xl overflow-hidden"
         style="background:#FFFFFF; border:1px solid rgba(201,162,0,0.22); box-shadow:0 12px 50px rgba(13,42,82,0.09);">

        {{-- ── Next Mass ── --}}
        <div class="relative flex items-center overflow-hidden" style="min-height:215px; background:#0d2a52;">
            {{-- Background Image --}}
            <div class="absolute inset-0">
                <img src="{{ asset('assets/img/mass.jpg') }}" 
                     alt="Mass" 
                     style="width:100%; height:100%; object-fit:cover; opacity:0.35;">
                <div class="absolute inset-0" 
                     style="background:linear-gradient(90deg, #0d2a52 0%, rgba(13,42,82,0.4) 50%, #0d2a52 100%);"></div>
            </div>

            {{-- Left content --}}
            <div class="relative z-10 flex items-center gap-8 px-10 py-8 flex-1">
                {{-- Chalice icon with SVG rays --}}
                <div class="relative shrink-0 flex items-center justify-center" style="width:82px; height:82px;">
                    <svg width="82" height="82" viewBox="0 0 82 82" style="position:absolute;inset:0;" fill="none" aria-hidden="true">
                        <line x1="41" y1="3"  x2="41" y2="13" stroke="rgba(245,197,24,0.4)" stroke-width="1.5" stroke-linecap="round"/>
                        <line x1="41" y1="69" x2="41" y2="79" stroke="rgba(245,197,24,0.4)" stroke-width="1.5" stroke-linecap="round"/>
                        <line x1="3"  y1="41" x2="13" y2="41" stroke="rgba(245,197,24,0.4)" stroke-width="1.5" stroke-linecap="round"/>
                        <line x1="69" y1="41" x2="79" y2="41" stroke="rgba(245,197,24,0.4)" stroke-width="1.5" stroke-linecap="round"/>
                        <line x1="11" y1="11" x2="18" y2="18" stroke="rgba(245,197,24,0.4)" stroke-width="1.5" stroke-linecap="round"/>
                        <line x1="64" y1="64" x2="71" y2="71" stroke="rgba(245,197,24,0.4)" stroke-width="1.5" stroke-linecap="round"/>
                        <line x1="71" y1="11" x2="64" y2="18" stroke="rgba(245,197,24,0.4)" stroke-width="1.5" stroke-linecap="round"/>
                        <line x1="11" y1="71" x2="18" y2="64" stroke="rgba(245,197,24,0.4)" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <div class="rounded-full flex items-center justify-center"
                         style="width:62px; height:62px; background:rgba(245,197,24,0.1); border:1.5px solid rgba(245,197,24,0.4);">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#F5C518" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M8 22h8"/><path d="M12 11v11"/>
                            <path d="M5 3h14L18 9a6 6 0 0 1-12 0L5 3z"/><path d="M3 3h18"/>
                        </svg>
                    </div>
                </div>

                {{-- Text --}}
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span style="display:inline-block; height:1px; width:32px; background:linear-gradient(90deg,transparent,rgba(245,197,24,0.6));"></span>
                        <span class="eyebrow" style="color:rgba(235,242,255,0.7);">NEXT MASS</span>
                        <span style="display:inline-block; height:1px; width:32px; background:linear-gradient(90deg,rgba(245,197,24,0.6),transparent);"></span>
                    </div>
                    @if($nextMass)
                    <h2 class="font-heading font-bold italic leading-none"
                        style="font-size:clamp(2.2rem,4vw,3.6rem); color:#FFFFFF; letter-spacing:-0.01em;">{{ $nextMass->calculated_day }}</h2>
                    <p class="font-heading font-bold italic"
                       style="font-size:clamp(1.8rem,3.5vw,3rem); color:#F5C518; line-height:1.1;">{{ $nextMass->calculated_time }}</p>
                    <p style="font-size:10px; letter-spacing:0.22em; text-transform:uppercase; color:rgba(235,242,255,0.5); margin-top:6px;">
                        {{ strtoupper($nextMass->title ?? ($nextMass->mass_type === 'sunday' ? 'Sunday Mass' : 'Weekday Mass')) }}
                    </p>
                    @else
                    <h2 class="font-heading font-bold italic leading-none" style="font-size:3.6rem; color:#FFFFFF;">Sunday</h2>
                    <p class="font-heading font-bold italic" style="font-size:3rem; color:#F5C518; line-height:1.1;">6:00 AM</p>
                    <p style="font-size:10px; letter-spacing:0.22em; text-transform:uppercase; color:rgba(235,242,255,0.5); margin-top:6px;">SUNDAY MASS</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Thin gold divider --}}
        <div style="height:1px; background:linear-gradient(90deg,transparent,rgba(201,162,0,0.2),transparent);"></div>

        {{-- ── Office Hours ── --}}
        <div class="px-8 pt-7 pb-0"><br>
            {{-- Header --}}
            <div class="flex items-center gap-3 mb-2">
                <span style="flex:1; height:1px; background:linear-gradient(90deg,transparent,rgba(201,162,0,0.3));"></span>
                <div class="flex items-center gap-2">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span class="font-cinzel" style="font-size:10.5px; letter-spacing:0.3em; color:var(--blue-deep); font-weight:600;">OFFICE HOURS</span>
                </div>
                <span style="flex:1; height:1px; background:linear-gradient(90deg,rgba(201,162,0,0.3),transparent);"></span>
            </div>
            <div class="flex justify-center mb-5">
                <div style="width:6px; height:6px; background:rgba(201,162,0,0.4); transform:rotate(45deg);"></div>
            </div>

            {{-- Three columns --}}
            <div class="grid grid-cols-3 overflow-hidden"
                 style="border:1px solid rgba(26,64,128,0.07); border-radius:16px;">
                @foreach([
                    ['icon'=>'<rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>', 'day'=>'· TUE – SAT ·', 'hours'=>['6:00 AM – 12:00 NN','1:30 PM – 6:00 PM'], 'closed'=>false],
                    ['icon'=>'<circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/>', 'day'=>'· SUNDAY ·', 'hours'=>['6:00 AM – 12:00 NN','3:00 PM – 6:00 PM'], 'closed'=>false],
                    ['icon'=>'<rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>', 'day'=>'· MONDAY ·', 'hours'=>[], 'closed'=>true],
                ] as $ohIdx => $ohCol)
                <div class="flex flex-col items-center py-7 px-4 text-center"
                     style="{{ $ohIdx > 0 ? 'border-left:1px solid rgba(26,64,128,0.07);' : '' }}">
                    <div class="w-11 h-11 rounded-full flex items-center justify-center mb-4"
                         style="background:var(--blue-deep);">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $ohCol['icon'] !!}</svg>
                    </div>
                    <p style="font-size:9.5px; letter-spacing:0.2em; color:rgba(13,42,82,0.45); font-weight:500; margin-bottom:10px;">{{ $ohCol['day'] }}</p>
                    @if($ohCol['closed'])
                    <p class="font-cinzel font-semibold" style="color:#C9A200; font-size:0.8rem; letter-spacing:0.08em;">CLOSED</p>
                    @else
                    @foreach($ohCol['hours'] as $ohHour)
                    <p style="font-size:12.5px; color:var(--blue-deep); line-height:1.9;">{{ $ohHour }}</p>
                    @endforeach
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        {{-- Full Schedule CTA --}}
        <a href="/mass-schedule"
           class="group relative flex flex-col items-center justify-center overflow-hidden mt-6"
           style="background:var(--blue-deep); text-decoration:none; padding:22px 24px; border-radius:0 0 24px 24px; display:flex; min-height:82px;"
           aria-label="View Full Mass Schedule">
            <div class="absolute left-0 top-[70%] -translate-y-1/2 pointer-events-none transition-transform duration-700 group-hover:scale-110" style="opacity:0.50; height:180%; width:auto;" aria-hidden="true">
                <img src="{{ asset('assets/img/parish-illustration.svg') }}" 
                     alt="Parish Illustration" 
                     style="height:90%; width:auto; object-fit:contain; filter:brightness(0) invert(1);">
            </div>
            <div class="flex items-center gap-2.5 mb-1.5">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect width="18" height="18" x="3" y="4" rx="2"/>
                    <path d="M16 2v4M8 2v4M3 10h18"/>
                    <path d="M11 2v3M9 3h4"/>
                </svg>
                <span class="font-cinzel" style="font-size:10.5px; letter-spacing:0.32em; color:#FFFFFF; font-weight:600;">FULL SCHEDULE</span>
            </div>
            <span class="transition-transform duration-300 group-hover:translate-x-1 block" style="color:#C9A200; font-size:16px; line-height:1;" aria-hidden="true">→</span>
        </a>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════ --}}
{{-- QUICK ACTIONS                                      --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="py-32 bg-[var(--cream)] reveal">
    <div class="max-w-[1200px] mx-auto px-6"><br><br><br>
        <div class="text-center mb-16">
            <div class="divider-ornament mb-4"><span class="eyebrow">Quick Access</span></div>
            <h2 class="font-heading text-4xl md:text-5xl font-bold italic" style="color:var(--blue-deep);">How Can We Serve You?</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
            @php
            $actions = [
                ['href'=>'/mass-schedule','icon'=>'<path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/>','label'=>'Mass Schedule','sub'=>'Times & days'],
                ['href'=>'/submit-intention','icon'=>'<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/><path d="M12 8v8"/><path d="M8 12h8"/>','label'=>'Offer Intention','sub'=>'Submit online'],
                ['href'=>'/inquiry','icon'=>'<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.19 12 19.79 19.79 0 0 1 1.12 3.33A2 2 0 0 1 3.09 1h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>','label'=>'Inquiry','sub'=>'Sacraments & docs'],
                ['href'=>'/donate','icon'=>'<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.505 4.04 3 5.5L12 21l7-7Z"/>','label'=>'Donate','sub'=>'Support the parish'],
            ];
            @endphp
            @foreach($actions as $a)
            <a href="{{ $a['href'] }}"
               class="card-sacred group flex flex-col items-center gap-5 p-9 text-center"
               style="text-decoration:none;"><br><br>
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center transition-all duration-300 group-hover:scale-110"
                     style="background:linear-gradient(135deg,rgba(245,197,24,0.14),rgba(245,197,24,0.04));
                            border:1px solid rgba(245,197,24,0.32);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">{!! $a['icon'] !!}</svg>
                </div>
                <div>
                    <p class="font-heading font-bold text-lg italic transition-colors duration-200 group-hover:text-[#C9A200]"
                       style="color:var(--blue-deep);">{{ $a['label'] }}</p>
                    <p class="text-[11px] mt-1 tracking-wide" style="color:rgba(13,42,82,0.4);">{{ $a['sub'] }}</p>
                </div><br><br>
            </a>
            @endforeach
        </div>
    </div><br><br>
</section>


{{-- ═══════════════════════════════════════════════════ --}}
{{-- UPCOMING EVENTS                                    --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="relative py-24 overflow-hidden reveal">
    {{-- Faded background --}}
    <div class="absolute inset-0 pointer-events-none select-none" aria-hidden="true">
        <img src="https://images.pexels.com/photos/17702529/pexels-photo-17702529.jpeg?auto=compress&w=1400"
             alt=""
             class="w-full h-full object-cover"
             style="filter:saturate(0.2) brightness(1.2) blur(4px); transform:scale(1.06);">
        <div style="position:absolute; inset:0; background:rgba(247,249,255,0.89);"></div>
    </div>

    <div class="relative z-10 max-w-5xl mx-auto px-6">
        {{-- Section header --}}
        <div class="text-center mb-14">
            {{-- Gold cross with radiating rays --}}
            <div class="flex justify-center mb-5">
                <div class="relative flex items-center justify-center" style="width:50px; height:50px;">
                    <svg width="50" height="50" viewBox="0 0 50 50" style="position:absolute;inset:0;" fill="none" aria-hidden="true">
                        <line x1="25" y1="2"  x2="25" y2="9"  stroke="rgba(201,162,0,0.5)" stroke-width="1.5" stroke-linecap="round"/>
                        <line x1="25" y1="41" x2="25" y2="48" stroke="rgba(201,162,0,0.5)" stroke-width="1.5" stroke-linecap="round"/>
                        <line x1="2"  y1="25" x2="9"  y2="25" stroke="rgba(201,162,0,0.5)" stroke-width="1.5" stroke-linecap="round"/>
                        <line x1="41" y1="25" x2="48" y2="25" stroke="rgba(201,162,0,0.5)" stroke-width="1.5" stroke-linecap="round"/>
                        <line x1="7"  y1="7"  x2="12" y2="12" stroke="rgba(201,162,0,0.5)" stroke-width="1.5" stroke-linecap="round"/>
                        <line x1="38" y1="38" x2="43" y2="43" stroke="rgba(201,162,0,0.5)" stroke-width="1.5" stroke-linecap="round"/>
                        <line x1="43" y1="7"  x2="38" y2="12" stroke="rgba(201,162,0,0.5)" stroke-width="1.5" stroke-linecap="round"/>
                        <line x1="7"  y1="43" x2="12" y2="38" stroke="rgba(201,162,0,0.5)" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <span class="font-cinzel relative z-10" style="color:#C9A200; font-size:1.35rem; line-height:1;" aria-hidden="true">✝</span>
                </div>
            </div>
            <div class="eyebrow mb-3">UPCOMING EVENTS</div>
            <h2 class="font-heading font-bold italic"
                style="font-size:clamp(2.4rem,5vw,4rem); color:var(--blue-deep); line-height:1.1; margin-bottom:12px;">What's Happening</h2>
            <div class="flex justify-center mb-4">
                <div style="width:7px; height:7px; background:rgba(201,162,0,0.42); transform:rotate(45deg);"></div>
            </div>
            <p style="color:rgba(13,42,82,0.45); font-size:14px; max-width:480px; margin:0 auto; line-height:1.7;">
                Stay connected. Join us in our liturgical celebrations and events.
            </p>
        </div>

        {{-- Event cards grid --}}
        <div class="grid md:grid-cols-3 gap-5 mb-8">

            {{-- Card 1: Today's Mass (always shown if available) --}}
            @if($nextMass)
            <div class="card-event card-event-featured relative">
                <div class="event-badge-today">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    <span style="font-size:9px; font-weight:700; letter-spacing:0.18em; color:#C9A200; text-transform:uppercase;">TODAY</span>
                </div>
                <div class="flex flex-col items-center text-center pt-14 pb-5 px-6" style="flex:1;">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mb-4"
                         style="background:rgba(245,197,24,0.07); border:1.5px solid rgba(201,162,0,0.28);">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M8 22h8"/><path d="M12 11v11"/><path d="M5 3h14L18 9a6 6 0 0 1-12 0L5 3z"/><path d="M3 3h18"/>
                        </svg>
                    </div>
                    <p style="font-size:10px; letter-spacing:0.22em; color:rgba(13,42,82,0.5); text-transform:uppercase; font-weight:600; margin-bottom:10px;">
                        {{ strtoupper($nextMass->title ?? ($nextMass->mass_type === 'sunday' ? 'Sunday Mass' : 'Weekday Mass')) }}
                    </p>
                    <div class="flex justify-center mb-3">
                        <div style="width:5px; height:5px; background:rgba(201,162,0,0.45); transform:rotate(45deg);"></div>
                    </div>
                    <p class="font-heading font-bold"
                       style="font-size:clamp(1.8rem,3vw,2.5rem); color:var(--blue-deep); line-height:1.05;">{{ $nextMass->calculated_time }}</p>
                </div>

            </div>
            @endif

            {{-- Upcoming events from DB --}}
            @php
            $evtIcons = [
                '<circle cx="12" cy="12" r="4"/><path d="M12 2v3M12 19v3M4.93 4.93l2.12 2.12M16.95 16.95l2.12 2.12M2 12h3M19 12h3M4.93 19.07l2.12-2.12M16.95 7.05l2.12-2.12"/>',
                '<path d="M12 22V12"/><path d="M12 12C9 12 4 10 4 6a4 4 0 0 1 8 0"/><path d="M12 12c3 0 8-2 8-6a4 4 0 0 0-8 0"/><path d="M8 22h8"/>',
            ];
            @endphp
            @foreach($upcomingEvents as $evt)
            <div class="card-event">
                <div class="flex flex-col items-center text-center pt-5 pb-5 px-6" style="flex:1;">
                    <div class="flex items-center gap-1.5 mb-4" style="align-self:flex-start;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="rgba(13,42,82,0.4)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                        <span style="font-size:10px; letter-spacing:0.14em; color:rgba(13,42,82,0.4); text-transform:uppercase; font-weight:500;">
                            {{ $evt->event_date->format('M d, Y') }}
                        </span>
                    </div>
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mb-4"
                         style="background:rgba(245,197,24,0.07); border:1.5px solid rgba(201,162,0,0.28);">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $evtIcons[$loop->index % 2] !!}</svg>
                    </div>
                    <p style="font-size:10px; letter-spacing:0.22em; color:rgba(13,42,82,0.5); text-transform:uppercase; font-weight:600; margin-bottom:10px;">
                        {{ strtoupper($evt->title) }}
                    </p>
                    <div class="flex justify-center mb-3">
                        <div style="width:5px; height:5px; background:rgba(201,162,0,0.45); transform:rotate(45deg);"></div>
                    </div>
                    <p style="font-size:13px; color:var(--blue-deep); line-height:1.8;">
                        @php
                            $times = is_array($evt->event_time) ? $evt->event_time : [$evt->event_time];
                            $formattedTimes = array_map(function($t) {
                                if (is_array($t)) {
                                    $timeStr = $t['time'] ?? '';
                                    if (!empty($t['title'])) {
                                        $timeStr .= " ({$t['title']})";
                                    }
                                    return $timeStr;
                                }
                                return (string) $t;
                            }, $times);
                        @endphp
                        @if(!empty($formattedTimes))
                            {{ implode(' · ', array_slice($formattedTimes, 0, 3)) }}
                            @if(count($formattedTimes) > 3)<br>{{ implode(' · ', array_slice($formattedTimes, 3)) }}@endif
                        @endif
                    </p>
                </div>

            </div>
            @endforeach

            {{-- Placeholder cards if fewer than 2 upcoming events --}}
            @for($evtFill = 0; $evtFill < (2 - $upcomingEvents->count()); $evtFill++)
            <div class="card-event" style="opacity:0.38;">
                <div class="flex flex-col items-center text-center px-6 py-10" style="flex:1; justify-content:center;">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center mb-4"
                         style="background:rgba(26,64,128,0.04); border:1px solid rgba(26,64,128,0.08);">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="rgba(13,42,82,0.25)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    </div>
                    <p style="font-size:11px; color:rgba(13,42,82,0.3);">No upcoming event</p>
                </div>
                <div class="event-location-bar"></div>
            </div>
            @endfor
        </div>

        {{-- CTA banner --}}
<a href="/events"
   class="group relative flex flex-col items-center justify-center overflow-hidden rounded-2xl"
   style="background:#0A2342; text-decoration:none; padding:24px; min-height:100px; transition:all 0.35s ease;"
   aria-label="View full events schedule">

    <div class="absolute left-0 top-[70%] -translate-y-1/2 pointer-events-none transition-transform duration-700 group-hover:scale-110"
         style="opacity:0.50; height:150%; width:auto;"
         aria-hidden="true">
        <img src="{{ asset('assets/img/parish-illustration.svg') }}" 
             alt="Parish Illustration" 
             style="height:90%; width:auto; object-fit:contain; filter:brightness(0) invert(1);">
    </div>

    <div class="relative z-10 flex flex-col items-center gap-2">
        <div class="flex items-center gap-3">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect width="18" height="18" x="3" y="4" rx="2"/>
                <path d="M16 2v4M8 2v4M3 10h18"/>
            </svg>

            <span class="font-cinzel"
                  style="font-size:11px; letter-spacing:0.35em; color:#FFFFFF; font-weight:700; text-transform:uppercase;">
                View Full Schedule
            </span>
        </div>

        <span class="transition-all duration-300 group-hover:translate-x-2"
              style="color:#C9A200; font-size:18px; line-height:1;"
              aria-hidden="true">
            →
        </span>
    </div>
</a>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════ --}}
{{-- LATEST ANNOUNCEMENTS                               --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="relative py-24 overflow-hidden reveal">
    {{-- Faded background --}}
    <div class="absolute inset-0 pointer-events-none select-none" aria-hidden="true">
        <img src="{{ asset('assets/img/church1.jpg') }}"
             alt=""
             class="w-full h-full object-cover"
             style="filter:saturate(0.18) brightness(1.15) blur(4px); transform:scale(1.06);">
        <div style="position:absolute; inset:0; background:rgba(246,243,238,0.91);"></div>
    </div>

    @php
    $annImgs = [
        asset('assets/img/church1.jpg'),
        'https://images.pexels.com/photos/6756378/pexels-photo-6756378.jpeg?auto=compress&w=800',
        'https://images.pexels.com/photos/5735003/pexels-photo-5735003.jpeg?auto=compress&w=800',
    ];
    $annCats = ['Liturgical', 'Parish Life', 'Parish Event'];
    @endphp

    <div class="relative z-10 max-w-6xl mx-auto px-6">
        {{-- Header row --}}
        <div class="relative flex items-start justify-between mb-14">
            <div class="w-24 shrink-0 hidden sm:block"></div>
            {{-- Center heading --}}
            <div class="flex-1 text-center">
                <div class="flex items-center justify-center gap-4 mb-5">
                    <span style="display:block; flex:1; max-width:60px; height:1px; background:linear-gradient(90deg,transparent,rgba(201,162,0,0.4));"></span>
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                        <line x1="12" y1="2" x2="12" y2="0"/><line x1="10" y1="1" x2="14" y2="1"/>
                    </svg>
                    <span style="display:block; flex:1; max-width:60px; height:1px; background:linear-gradient(90deg,rgba(201,162,0,0.4),transparent);"></span>
                </div>
                <h2 class="font-cinzel font-semibold"
                    style="font-size:clamp(1.25rem,3vw,2.15rem); color:var(--blue-deep); letter-spacing:0.16em; margin-bottom:8px;">LATEST ANNOUNCEMENTS</h2>
                <p style="color:rgba(13,42,82,0.4); font-size:13.5px; max-width:480px; margin:0 auto 14px;">Stay informed. Be involved. Grow in faith together.</p>
                <div class="flex justify-center">
                    <div style="width:6px; height:6px; background:rgba(201,162,0,0.42); transform:rotate(45deg);"></div>
                </div>
            </div>
            {{-- Prev / Next buttons --}}
            <div class="hidden sm:flex items-center gap-2 shrink-0 pt-1">
                <button onclick="document.getElementById('ann-grid').scrollBy({left:-340,behavior:'smooth'})"
                        class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200"
                        style="border:1.5px solid rgba(13,42,82,0.16); color:rgba(13,42,82,0.4); background:#FFFFFF;"
                        aria-label="Previous announcements">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                </button>
                <button onclick="document.getElementById('ann-grid').scrollBy({left:340,behavior:'smooth'})"
                        class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200"
                        style="border:1.5px solid rgba(13,42,82,0.16); color:rgba(13,42,82,0.4); background:#FFFFFF;"
                        aria-label="Next announcements">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                </button>
            </div>
        </div>

        {{-- Announcement cards --}}
        @if($announcements->isEmpty())
        <div class="text-center py-16">
            <p style="color:rgba(13,42,82,0.3); font-size:14px;">No announcements at this time.</p>
        </div>
        @else
        <div id="ann-grid" class="grid md:grid-cols-3 gap-5 mb-10">
            @foreach($announcements as $ann)
            @php $aIdx = $loop->index; $aIsFirst = $loop->first; @endphp
            <article class="card-sacred overflow-hidden flex flex-col group/ann">
                {{-- Photo --}}
                <div class="relative overflow-hidden shrink-0" style="height:196px;">
                    <img src="{{ $annImgs[$aIdx % 3] }}"
                         alt="{{ $ann->title }}"
                         class="w-full h-full object-cover transition-transform duration-700 group-hover/ann:scale-105">
                    @if($aIsFirst)
                    <div class="absolute top-0 left-0 flex items-center gap-1.5 px-3 py-2"
                         style="background:rgba(162,118,0,0.92); border-bottom-right-radius:14px;">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="#FFFFFF" stroke="none" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        <span style="font-size:8.5px; font-weight:700; letter-spacing:0.18em; color:#FFFFFF; text-transform:uppercase;">FEATURED</span>
                    </div>
                    @endif
                </div>
                {{-- Card body --}}
                <div class="p-5 flex-1 flex flex-col">
                    <div class="flex items-start gap-3 mb-3">
                        {{-- Date badge --}}
                        <div class="shrink-0 text-center rounded-lg overflow-hidden" style="border:1px solid rgba(201,162,0,0.3); min-width:44px;">
                            <div style="background:rgba(201,162,0,0.09); padding:2px 6px; font-size:8px; font-weight:700; letter-spacing:0.1em; color:#C9A200; text-transform:uppercase;">
                                {{ ($ann->published_at ?? $ann->created_at)->format('M') }}
                            </div>
                            <div class="font-heading font-bold"
                                 style="font-size:1.1rem; color:var(--blue-deep); padding:2px 6px; line-height:1.15;">
                                {{ ($ann->published_at ?? $ann->created_at)->format('d') }}
                            </div>
                        </div>
                        {{-- Category + Title --}}
                        <div class="flex-1 min-w-0">
                            <p class="eyebrow mb-1" style="font-size:8.5px;">{{ strtoupper($annCats[$aIdx % 3]) }}</p>
                            <h3 class="font-heading font-bold italic leading-snug"
                                style="font-size:1.08rem; color:var(--blue-deep);">{{ $ann->title }}</h3>
                        </div>
                    </div>
                    @if($ann->content)
                    <p class="text-sm leading-relaxed flex-1"
                       style="color:rgba(13,42,82,0.48); overflow:hidden; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical;">
                        {{ mb_substr(strip_tags($ann->content), 0, 130) }}
                    </p>
                    @endif
                </div>
                {{-- Card footer --}}
                <div class="flex items-center justify-between px-5 py-3.5"
                     style="border-top:1px solid rgba(26,64,128,0.06);">

                    <a href="#"
                       style="font-size:9.5px; font-weight:700; letter-spacing:0.15em; color:#C9A200; text-decoration:none; text-transform:uppercase;"
                       class="hover:opacity-60 transition-opacity"
                       aria-label="Read more about {{ $ann->title }}">READ MORE →</a>
                </div>
            </article>
            @endforeach
        </div>
        @endif
    </div>
</section>

{{-- ═══════════════════════════════════════════════════ --}}
{{-- SACRAMENTAL SERVICES                               --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="py-28 relative overflow-hidden reveal" style="background:var(--blue-deep);">

    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none select-none font-cinzel leading-none"
         style="font-size:420px; color:rgba(255,255,255,0.018);">✝</div>

    <div class="absolute top-0 left-1/2 -translate-x-1/2 pointer-events-none"
         style="width:600px; height:300px; background:radial-gradient(ellipse,rgba(245,197,24,0.08) 0%,transparent 70%);"></div>

    <div class="absolute top-0 left-0 right-0 h-px"
         style="background:linear-gradient(90deg,transparent,rgba(245,197,24,0.4),transparent);"></div>

    <div class="max-w-[1200px] mx-auto px-6 relative z-10"><br><br>
        <div class="text-center mb-16">
            <div class="divider-ornament mb-4">
                <span style="font-size:10px; font-weight:600; letter-spacing:0.35em; text-transform:uppercase; color:rgba(245,197,24,0.65);">Sacramental Services</span>
            </div>
            <h2 class="font-heading text-4xl md:text-5xl font-bold italic" style="color:#EBF2FF;">How We Serve</h2>
            <p class="mt-4 text-sm font-light" style="color:rgba(235,242,255,0.40); letter-spacing:0.05em;">Inquire about any sacramental service at our parish office.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 max-w-5xl mx-auto">
            @php
            $services = [
                ['label'=>'Baptism',       'href'=>'/inquiry','svg'=>'<circle cx="12" cy="6" r="3"/><path d="M5 12a7 7 0 0 1 14 0"/><line x1="12" y1="12" x2="12" y2="20"/><path d="M9 20h6"/>'],
                ['label'=>'Wedding',       'href'=>'/inquiry','svg'=>'<path d="M8.5 9.5 5 15h14l-3.5-5.5"/><circle cx="12" cy="5" r="2.5"/><path d="M12 8v10"/><path d="M9.5 18h5"/>'],
                ['label'=>'Confirmation',  'href'=>'/inquiry','svg'=>'<path d="M12 2v8"/><path d="M8 5l4 5 4-5"/><circle cx="12" cy="17" r="4"/><path d="M12 14v6"/>'],
                ['label'=>'Funeral Mass',  'href'=>'/inquiry','svg'=>'<path d="M12 2v12"/><path d="M8 6h8"/><path d="M5 14h14l-1.5 6H6.5L5 14z"/>'],
                ['label'=>'House Blessing','href'=>'/inquiry','svg'=>'<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>'],
                ['label'=>'Car Blessing',  'href'=>'/inquiry','svg'=>'<path d="M19 17H5v-5l2-6h10l2 6v5Z"/><circle cx="7.5" cy="17.5" r="1.5"/><circle cx="16.5" cy="17.5" r="1.5"/><path d="M5 12h14"/>'],
            ];
            @endphp
            @foreach($services as $s)
            <a href="{{ $s['href'] }}"
               class="group flex flex-col items-center gap-4 p-6 rounded-2xl text-center transition-all duration-300 hover:-translate-y-1"
               style="border:1px solid rgba(255,255,255,0.08); background:rgba(255,255,255,0.03); text-decoration:none;"
               onmouseover="this.style.borderColor='rgba(245,197,24,0.50)'; this.style.background='rgba(245,197,24,0.06)';"
               onmouseout="this.style.borderColor='rgba(255,255,255,0.08)'; this.style.background='rgba(255,255,255,0.03)';">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300 group-hover:scale-110"
                     style="border:1px solid rgba(255,255,255,0.12); background:rgba(255,255,255,0.04);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                         fill="none" stroke="rgba(235,242,255,0.55)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                         class="transition-all duration-300">{!! $s['svg'] !!}</svg>
                </div>
                <span class="text-[10.5px] font-semibold uppercase tracking-wider transition-colors duration-200"
                      style="color:rgba(235,242,255,0.55);">{{ $s['label'] }}</span>
            </a>
            @endforeach
        </div>
    </div><br><br>

    <div class="absolute bottom-0 left-0 right-0 h-px"
         style="background:linear-gradient(90deg,transparent,rgba(245,197,24,0.25),transparent);"></div>
</section>


{{-- ═══════════════════════════════════════════════════ --}}
{{-- INTENTION CTA                                      --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="py-28 relative overflow-hidden bg-[var(--cream)] reveal">

    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full pointer-events-none"
         style="background:radial-gradient(circle,rgba(26,64,128,0.05) 0%,transparent 70%);"></div>

    <div class="max-w-[1200px] mx-auto px-6 relative z-10">
        <div class="max-w-2xl mx-auto text-center">

            <div class="font-cinzel text-4xl mb-8 opacity-60" style="color:var(--gold);">✝</div>

            <div class="divider-ornament mb-6"><span class="eyebrow">Unite Your Prayers</span></div>

            <h2 class="font-heading text-4xl md:text-6xl font-bold italic leading-[1.05] mb-6" style="color:var(--blue-deep);">
                Offer a Mass<br>Intention
            </h2>

            <p class="text-lg leading-relaxed mb-12 font-light" style="color:rgba(13,42,82,0.55);">
                Unite your prayers with the Holy Sacrifice of the Mass. Submit your intention online and our staff will include it in the upcoming liturgy.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="/submit-intention"
                   class="gold-btn inline-flex items-center gap-2.5 px-10 py-4 rounded-full text-[11px] font-bold uppercase tracking-widest"
                   style="text-decoration:none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/></svg>
                    Submit Intention
                </a>
                <a href="/track"
                   class="inline-flex items-center gap-2.5 px-10 py-4 rounded-full text-[11px] font-bold uppercase tracking-widest border-2 transition-all duration-300 hover:-translate-y-0.5"
                   style="border-color:rgba(26,64,128,0.25); color:var(--blue-deep); background:transparent; text-decoration:none;"
                   onmouseover="this.style.borderColor='var(--blue-mid)'; this.style.background='rgba(26,64,128,0.05)';"
                   onmouseout="this.style.borderColor='rgba(26,64,128,0.25)'; this.style.background='transparent';">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Track Status
                </a>
            </div>

            <p class="mt-16 text-[10px] font-medium uppercase tracking-[0.4em]" style="color:rgba(13,42,82,0.25);">
                Sto. Rosario Parish · Pacita, San Pedro, Laguna
            </p>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const observerOptions = {
            threshold: 0.12,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    // Once animated, no need to observe anymore
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.reveal, .reveal-left, .reveal-right').forEach(el => {
            observer.observe(el);
        });
    });
</script>

</x-public-layout>