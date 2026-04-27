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

        .animate-fade-up { animation: fadeUp 0.9s ease both; }
        .animate-fade-in { animation: fadeIn 1.2s ease both; }
        .delay-1 { animation-delay: 0.15s; }
        .delay-2 { animation-delay: 0.30s; }
        .delay-3 { animation-delay: 0.45s; }
        .delay-4 { animation-delay: 0.60s; }
        .delay-5 { animation-delay: 0.80s; }
        .scroll-line { animation: scrollLine 2s ease infinite; }

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

        {{-- Cross --}}
        <div class="font-cinzel animate-fade-in delay-1"
             style="color:var(--gold); font-size:1.35rem; opacity:0.75; margin-bottom:18px;
                    text-shadow:0 0 20px rgba(245,197,24,0.5);">✝</div>

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
            <span style="color:#FFFFFF;">Sto. Rosario</span>
            <em class="hero-title-accent" style="font-style:italic;"> Parish</em>
        </h1>

        {{-- Location --}}
        <p class="font-heading animate-fade-up delay-2"
           style="font-style:italic; color:rgba(255,215,64,0.82); margin-bottom:14px;
                  font-size:clamp(0.9rem, 1.8vw, 1.15rem); font-weight:300;
                  text-shadow:0 2px 12px rgba(0,0,0,0.4);">
            Pacita, San Pedro, Laguna
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
               class="gold-btn inline-flex items-center gap-2 rounded-full"
               style="padding:13px 30px; font-size:10.5px; letter-spacing:0.18em; text-transform:uppercase; text-decoration:none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                Mass Schedule
            </a>
            <a href="/submit-intention"
               class="ghost-btn inline-flex items-center gap-2 rounded-full font-bold uppercase"
               style="padding:13px 30px; font-size:10.5px; letter-spacing:0.18em; text-decoration:none;">
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
{{-- NEXT MASS BANNER — Premium Layout (Image 1)        --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="relative z-20 bg-[var(--cream)]" style="padding-top:0;">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="max-w-4xl mx-auto -mt-12 rounded-[32px] overflow-hidden"
             style="background:#FFFFFF;
                    border:1px solid rgba(26,64,128,0.1);
                    box-shadow:0 32px 80px rgba(13,42,82,0.12);">

            {{-- ── ROW 1: Next Mass Display ── --}}
            <div class="relative grid grid-cols-1 md:grid-cols-2 min-h-[220px]">
                
                {{-- Left: Status & Chalice --}}
                <div class="flex items-center justify-center p-10 border-b md:border-b-0 md:border-r border-muted/40 bg-white">
                    <div class="relative">
                        {{-- Chalice Frame --}}
                        <div class="w-32 h-32 rounded-full flex items-center justify-center relative z-10"
                             style="background:radial-gradient(circle, #FFFBF0 0%, #FFFFFF 100%);
                                    border:1.5px solid rgba(245,197,24,0.3);
                                    box-shadow: 0 0 40px rgba(245,197,24,0.1);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                                 fill="none" stroke="#C9A200" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M8 2h8l2 7H6L8 2z"/><path d="M6 9c0 3.314 2.686 6 6 6s6-2.686 6-6"/><line x1="12" y1="15" x2="12" y2="20"/><line x1="8" y1="20" x2="16" y2="20"/><path d="M12 6v3"/>
                            </svg>
                        </div>
                        {{-- Soft Rays Watermark --}}
                        <div class="absolute inset-0 scale-150 opacity-20 pointer-events-none" style="background: radial-gradient(circle, var(--gold) 0%, transparent 70%);"></div>
                    </div>
                </div>

                {{-- Right: Time & Details --}}
                <div class="relative flex flex-col justify-center px-12 py-10 overflow-hidden">
                    {{-- Church Interior Watermark BG --}}
                    <div class="absolute inset-0 z-0 opacity-10 pointer-events-none">
                        <img src="/bg-interior.png" alt="" class="w-full h-full object-cover">
                        <div class="absolute inset-0" style="background:linear-gradient(90deg, #FFFFFF 0%, transparent 100%);"></div>
                    </div>

                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="h-px w-8 bg-muted"></div>
                            <span class="text-[10px] font-bold uppercase tracking-[0.4em] text-muted-foreground">Next Mass</span>
                            <div class="h-px w-8 bg-muted"></div>
                        </div>

                        @if($nextMass)
                            <h3 class="font-heading font-bold text-6xl italic leading-none mb-2" style="color:var(--blue-deep);">
                                {{ $nextMass->calculated_day }}
                            </h3>
                            <p class="font-heading font-bold text-5xl italic leading-none mb-6" style="color:var(--gold);">
                                {{ $nextMass->calculated_time }}
                            </p>
                            @if(isset($nextMass->mass_type))
                            <span class="text-[11px] font-black uppercase tracking-[0.3em] text-muted-foreground/60">
                                {{ $nextMass->mass_type }} Mass
                            </span>
                            @endif
                        @else
                            <h3 class="font-heading font-bold text-5xl italic leading-none" style="color:var(--blue-deep);">See Schedule</h3>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ── ROW 2: Office Hours ── --}}
            <div class="bg-[#FCFDFF] border-t border-muted/50">
                {{-- Eyebrow --}}
                <div class="flex flex-col items-center pt-8 pb-4">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="h-px w-12 bg-gradient-to-r from-transparent to-muted"></div>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-accent/10 flex items-center justify-center text-accent">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                            <span class="text-[11px] font-bold uppercase tracking-[0.35em] text-primary">Office Hours</span>
                        </div>
                        <div class="h-px w-12 bg-gradient-to-l from-transparent to-muted"></div>
                    </div>
                    <span class="text-accent text-[8px]">◆</span>
                </div>

                {{-- Hours Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-muted/30 pb-12 px-6">
                    {{-- Weekdays --}}
                    <div class="py-8 md:py-4 flex flex-col items-center text-center">
                        <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M8 2v4"/><path d="M16 2v4"/></svg>
                        </div>
                        <span class="text-[10px] font-bold text-primary uppercase tracking-[0.2em] mb-3">· Tue – Sat ·</span>
                        <p class="text-sm font-medium text-muted-foreground leading-relaxed">
                            6:00 AM – 12:00 NN<br>1:30 PM – 6:00 PM
                        </p>
                    </div>
                    {{-- Sunday --}}
                    <div class="py-8 md:py-4 flex flex-col items-center text-center">
                        <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
                        </div>
                        <span class="text-[10px] font-bold text-primary uppercase tracking-[0.2em] mb-3">· Sunday ·</span>
                        <p class="text-sm font-medium text-muted-foreground leading-relaxed">
                            6:00 AM – 12:00 NN<br>3:00 PM – 6:00 PM
                        </p>
                    </div>
                    {{-- Monday --}}
                    <div class="py-8 md:py-4 flex flex-col items-center text-center">
                        <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="m15 11-6 6"/><path d="m9 11 6 6"/></svg>
                        </div>
                        <span class="text-[10px] font-bold text-primary uppercase tracking-[0.2em] mb-3">· Monday ·</span>
                        <p class="text-lg font-black text-accent uppercase tracking-widest">Closed</p>
                    </div>
                </div>

                {{-- Full Schedule Bar --}}
                <a href="/mass-schedule"
                   class="group block relative py-8 bg-primary text-center overflow-hidden transition-all duration-500 hover:bg-[#0a2044]"
                   style="text-decoration:none;">
                    {{-- Watermark --}}
                    <div class="absolute inset-0 opacity-10 flex items-center justify-start pl-8 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="0.5"><path d="M3 21h18M3 10l9-8 9 8v11H3V10z"/><path d="M9 21v-8a3 3 0 0 1 6 0v8"/></svg>
                    </div>
                    
                    <div class="relative z-10 flex flex-col items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mb-1"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M8 2v4"/><path d="M16 2v4"/><rect x="8" y="14" width="2" height="2"/><rect x="14" y="14" width="2" height="2"/></svg>
                        <span class="text-[11px] font-bold uppercase tracking-[0.4em] text-white">Full Schedule</span>
                        <span class="text-accent text-sm group-hover:translate-y-1 transition-transform">↓</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════ --}}
{{-- QUICK ACTIONS                                      --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="py-32 bg-[var(--cream)]">
    <div class="max-w-[1200px] mx-auto px-6">
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
               style="text-decoration:none;">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center transition-all duration-300 group-hover:scale-110"
                     style="background:linear-gradient(135deg,rgba(245,197,24,0.14),rgba(245,197,24,0.04));
                            border:1px solid rgba(245,197,24,0.32);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">{!! $a['icon'] !!}</svg>
                </div>
                <div>
                    <p class="font-heading font-bold text-lg italic transition-colors duration-200 group-hover:text-[#C9A200]"
                       style="color:var(--blue-deep);">{{ $a['label'] }}</p>
                    <p class="text-[11px] mt-1 tracking-wide" style="color:rgba(13,42,82,0.4);">{{ $a['sub'] }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════ --}}
{{-- UPCOMING EVENTS — Premium Layout (Image 2)         --}}
{{-- ═══════════════════════════════════════════════════ --}}
@if(isset($upcomingEvents) && $upcomingEvents->count() > 0)
<section class="py-32 section-ruled relative overflow-hidden" style="background:#FAFBFF;">
    
    {{-- Church Interior Watermark BG --}}
    <div class="absolute inset-0 z-0 opacity-[0.04] pointer-events-none">
        <img src="/bg-interior.png" alt="" class="w-full h-full object-cover">
    </div>

    <div class="max-w-[1200px] mx-auto px-6 relative z-10">

        {{-- Section Header --}}
        <div class="text-center mb-16">
            <div class="flex flex-col items-center mb-6">
                <div class="cross-container">
                    <div class="cross-ray ray-1"></div>
                    <div class="cross-ray ray-2"></div>
                    <div class="cross-ray ray-3"></div>
                    <span class="font-cinzel text-3xl" style="color:var(--gold); opacity:0.8;">✝</span>
                </div>
            </div>
            <div class="eyebrow mb-4" style="color:var(--gold);">Upcoming Events</div>
            <h2 class="font-heading text-6xl italic font-bold mb-6" style="color:var(--blue-deep);">
                What's Happening
            </h2>
            <div class="divider-ornament opacity-40 mb-6"></div>
            <p class="text-muted-foreground max-w-lg mx-auto leading-relaxed">
                Stay connected. Join us in our liturgical celebrations and events.
            </p>
        </div>

        {{-- Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            @foreach($upcomingEvents->take(3) as $i => $event)
                @php 
                    $isFeatured = $i === 0; 
                    $titleLower = strtolower($event->title);
                    $icon = '<path d="M8 2h8l2 7H6L8 2z"/><path d="M6 9c0 3.314 2.686 6 6 6s6-2.686 6-6"/><line x1="12" y1="15" x2="12" y2="20"/><line x1="8" y1="20" x2="16" y2="20"/>'; // Default: Chalice

                    if(str_contains($titleLower, 'adoration') || str_contains($titleLower, 'eucharist')) {
                        $icon = '<circle cx="12" cy="12" r="4"/><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>';
                    } elseif(str_contains($titleLower, 'pentecost') || str_contains($titleLower, 'spirit')) {
                        $icon = '<path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="M15 9.354a4 4 0 1 0-3 6.569"/><path d="M9 12h.01"/>';
                    } elseif(str_contains($titleLower, 'mass')) {
                        $icon = '<path d="M8 2h8l2 7H6L8 2z"/><path d="M6 9c0 3.314 2.686 6 6 6s6-2.686 6-6"/><line x1="12" y1="15" x2="12" y2="20"/><line x1="8" y1="20" x2="16" y2="20"/>';
                    }
                @endphp

                <a href="{{ route('events.show', $event) }}" 
                   class="card-event {{ $isFeatured ? 'card-event-featured' : '' }} group"
                   style="text-decoration:none;">
                    
                    {{-- Today Badge for Featured --}}
                    @if($isFeatured)
                    <div class="event-badge-today">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M8 2v4"/><path d="M16 2v4"/></svg>
                        <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-primary">Today</span>
                    </div>
                    @else
                    <div class="pt-8 px-6 text-left">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M8 2v4"/><path d="M16 2v4"/></svg>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground/60">{{ $event->event_date->format('F j, Y') }}</span>
                        </div>
                    </div>
                    @endif

                    <div class="flex-1 px-8 py-10 flex flex-col items-center justify-center">
                        {{-- Icon --}}
                        <div class="w-20 h-20 rounded-full flex items-center justify-center mb-6"
                             style="background:radial-gradient(circle, #FFFBF0 0%, #FFFFFF 100%);
                                    border:1px solid rgba(245,197,24,0.3);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                {!! $icon !!}
                            </svg>
                        </div>

                        <h3 class="font-cinzel font-bold text-sm tracking-[0.2em] uppercase mb-4 group-hover:text-accent transition-colors" style="color:var(--blue-deep);">
                            {{ $event->title }}
                        </h3>

                        <div class="h-px w-8 bg-muted/40 mb-6"></div>

                        @if(!empty($event->event_time) && count($event->event_time) > 0)
                            <div class="flex flex-col gap-1">
                                @foreach($event->event_time as $t)
                                    <p class="font-heading font-bold text-3xl italic" style="color:var(--blue-deep);">
                                        {{ \Carbon\Carbon::parse($t['time'] ?? '')->format('g:i A') }}
                                    </p>
                                @endforeach
                            </div>
                        @else
                            <p class="font-heading font-bold text-2xl italic" style="color:var(--blue-deep);">Schedule TBD</p>
                        @endif
                    </div>

                    {{-- Location Bar --}}
                    <div class="event-location-bar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span class="text-xs font-medium text-muted-foreground">{{ $event->location ?? 'Main Church' }}</span>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- View Full Schedule Bar --}}
        <div class="max-w-6xl mx-auto mt-16">
            <a href="/events"
               class="group block relative py-10 bg-primary rounded-[24px] text-center overflow-hidden transition-all duration-500 hover:bg-[#0a2044]"
               style="text-decoration:none;">
                {{-- Watermark --}}
                <div class="absolute inset-0 opacity-10 flex items-center justify-start pl-12 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="0.4"><path d="M3 21h18M3 10l9-8 9 8v11H3V10z"/><path d="M9 21v-8a3 3 0 0 1 6 0v8"/></svg>
                </div>
                
                <div class="relative z-10 flex flex-col items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mb-1"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M8 2v4"/><path d="M16 2v4"/><circle cx="12" cy="16" r="1"/></svg>
                    <span class="text-xs font-bold uppercase tracking-[0.4em] text-white">View Full Schedule</span>
                    <span class="text-accent text-lg group-hover:translate-x-2 transition-transform">→</span>
                </div>
            </a>
        </div>
    </div>
</section>
@endif


{{-- ═══════════════════════════════════════════════════ --}}
{{-- ANNOUNCEMENTS                                      --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="py-36 bg-[var(--cream)]">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 max-w-5xl mx-auto mb-16">
            <div>
                <div class="divider-ornament justify-start mb-4">
                    <span class="eyebrow">From the Parish</span>
                </div>
                <h2 class="font-heading text-4xl md:text-5xl font-bold italic" style="color:var(--blue-deep);">Latest Announcements</h2>
            </div>
        </div>
        <div class="grid gap-7 max-w-5xl mx-auto" style="grid-template-columns:repeat(auto-fill,minmax(300px,1fr));">
            @forelse($announcements as $item)
            <div class="card-sacred flex flex-col group">
                <div class="h-[2px] mx-7 mt-8 rounded-full mb-7"
                     style="background:linear-gradient(90deg,var(--gold),rgba(245,197,24,0.25));"></div>
                <div class="px-8 pb-8 flex flex-col flex-1">
                    <div class="flex items-center gap-2.5 mb-6">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center"
                             style="background:rgba(245,197,24,0.10); border:1px solid rgba(245,197,24,0.25);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
                        </div>
                        <span class="text-[9px] font-bold uppercase tracking-[0.3em]" style="color:rgba(13,42,82,0.4);">Announcement</span>
                    </div>
                    <h3 class="font-heading font-bold text-xl italic leading-tight mb-4 line-clamp-2" style="color:var(--blue-deep);">{{ $item->title }}</h3>
                    <p class="text-sm leading-relaxed line-clamp-3 flex-1" style="color:rgba(13,42,82,0.55);">{{ $item->content }}</p>
                    <div class="flex items-center gap-1.5 mt-8 pt-6 text-[11px]"
                         style="border-top:1px solid rgba(26,64,128,0.08); color:rgba(13,42,82,0.35);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                        {{ $item->created_at->format('M d, Y') }}
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 py-20 text-center">
                <p class="font-heading italic text-lg" style="color:rgba(13,42,82,0.3);">No recent announcements at this time.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════ --}}
{{-- SACRAMENTAL SERVICES                               --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="py-28 relative overflow-hidden" style="background:var(--blue-deep);">

    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none select-none font-cinzel leading-none"
         style="font-size:420px; color:rgba(255,255,255,0.018);">✝</div>

    <div class="absolute top-0 left-1/2 -translate-x-1/2 pointer-events-none"
         style="width:600px; height:300px; background:radial-gradient(ellipse,rgba(245,197,24,0.08) 0%,transparent 70%);"></div>

    <div class="absolute top-0 left-0 right-0 h-px"
         style="background:linear-gradient(90deg,transparent,rgba(245,197,24,0.4),transparent);"></div>

    <div class="max-w-[1200px] mx-auto px-6 relative z-10">
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
    </div>

    <div class="absolute bottom-0 left-0 right-0 h-px"
         style="background:linear-gradient(90deg,transparent,rgba(245,197,24,0.25),transparent);"></div>
</section>


{{-- ═══════════════════════════════════════════════════ --}}
{{-- INTENTION CTA                                      --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="py-28 relative overflow-hidden bg-[var(--cream)]">

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

</x-public-layout>