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
            100% { background-position: 200% center; }
        }

        .animate-fade-up { animation: fadeUp 0.9s ease both; }
        .animate-fade-in { animation: fadeIn 1.2s ease both; }
        .delay-1 { animation-delay: 0.15s; }
        .delay-2 { animation-delay: 0.30s; }
        .delay-3 { animation-delay: 0.45s; }
        .delay-4 { animation-delay: 0.60s; }
        .delay-5 { animation-delay: 0.80s; }

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
                rgba(8, 20, 45, 0.70)  0%,
                rgba(10, 25, 55, 0.62) 40%,
                rgba(8, 20, 45, 0.82)  72%,
                rgba(247, 249, 255, 1) 100%
            );
        }

        /* Hero title shimmer effect */
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
            padding: 6px 16px;
            border-radius: 100px;
            border: 1px solid rgba(245,197,24,0.35);
            background: rgba(245,197,24,0.08);
            backdrop-filter: blur(8px);
        }

        /* ── Mass card ── */
        .mass-card {
            background: linear-gradient(135deg, #FFFFFF 0%, #F0F5FF 100%);
        }

        /* ── Scroll cue line ── */
        @keyframes scrollLine {
            0%   { transform: scaleY(0); transform-origin: top; }
            50%  { transform: scaleY(1); transform-origin: top; }
            51%  { transform: scaleY(1); transform-origin: bottom; }
            100% { transform: scaleY(0); transform-origin: bottom; }
        }
        .scroll-line { animation: scrollLine 2s ease infinite; }
    </style>
</x-slot>


{{-- ═══════════════════════════════════════════════════ --}}
{{-- HERO (FIXED + REDESIGNED) --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="relative overflow-hidden" style="min-height: 100svh; display: flex; flex-direction: column; align-items: center; justify-content: center;">

    {{-- Background --}}
    <div class="absolute inset-0 z-0">
        <img src="/bg.png" alt="Sto. Rosario Parish"
             class="w-full h-full object-cover"
             style="filter: saturate(0.75) brightness(0.85); transform: scale(1.04);">
        <div class="hero-overlay absolute inset-0"></div>
    </div>

    {{-- Blue tint atmospheric layer --}}
    <div class="absolute inset-0 z-[1] pointer-events-none"
         style="background: radial-gradient(ellipse 80% 60% at 50% 30%, rgba(26,64,128,0.25) 0%, transparent 70%);"></div>

    {{-- Noise grain --}}
    <div class="absolute inset-0 z-[2] pointer-events-none" style="opacity: 0.03;
         background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22><filter id=%22n%22><feTurbulence type=%22fractalNoise%22 baseFrequency=%220.75%22 stitchTiles=%22stitch%22/></filter><rect width=%22200%22 height=%22200%22 filter=%22url(%23n)%22 opacity=%221%22/></svg>');"></div>

    {{-- Gold horizontal accent lines --}}
    <div class="absolute z-[3] pointer-events-none w-full" style="top: 50%; transform: translateY(-50%); opacity: 0.06;">
        <div style="height: 1px; background: linear-gradient(90deg, transparent, #F5C518 30%, #F5C518 70%, transparent);"></div>
    </div>

    {{-- Content --}}
    <div class="relative z-10 text-center flex flex-col items-center px-6 w-full max-w-4xl mx-auto"
         style="margin-top: 52px;">

        {{-- Badge pill --}}
        <div class="hero-badge animate-fade-in mb-6">
            <span style="width: 6px; height: 6px; border-radius: 50%; background: var(--gold); display: block; box-shadow: 0 0 8px rgba(245,197,24,0.8);"></span>
            <span style="font-size: 9.5px; font-weight: 600; letter-spacing: 0.38em; text-transform: uppercase; color: rgba(255,248,180,0.85);">
                Est. · Diocese of San Pablo · Pacita
            </span>
        </div>

        {{-- Cross ornament --}}
        <div class="animate-fade-in font-cinzel mb-5 delay-1"
             style="color: var(--gold); font-size: 1.4rem; opacity: 0.75; text-shadow: 0 0 20px rgba(245,197,24,0.5);">✝</div>

        {{-- Main title --}}
        <h1 class="font-heading animate-fade-up delay-1 font-bold mb-4"
            style="line-height: 0.88;
                   letter-spacing: -0.01em;
                   text-shadow: 0 4px 48px rgba(0,0,0,0.6);
                   font-size: clamp(3.4rem, 10vw, 8rem);">
            <span style="color: #FFFFFF; display: block; font-weight: 700;">Sto. Rosario</span>
            <em class="hero-title-accent" style="display: block; font-size: 0.82em;">Parish</em>
        </h1>

        {{-- Location --}}
        <p class="animate-fade-up delay-2 font-heading italic mb-4"
           style="color: rgba(255,215,64,0.80); font-size: clamp(0.95rem, 2vw, 1.2rem); font-weight: 300;
                  text-shadow: 0 2px 12px rgba(0,0,0,0.4);">
            Pacita, San Pedro, Laguna
        </p>

        {{-- Divider rule --}}
        <div class="animate-fade-in delay-2 mb-7"
             style="width: 60px; height: 1px; background: linear-gradient(90deg, transparent, rgba(245,197,24,0.6), transparent);"></div>

        {{-- Description --}}
        <p class="animate-fade-up delay-3 mb-10 font-light"
           style="color: rgba(220,232,255,0.75);
                  font-size: clamp(0.88rem, 1.5vw, 1.05rem);
                  line-height: 1.75;
                  max-width: 440px;
                  letter-spacing: 0.01em;">
            Home to the Queen of the Most Holy Rosary — a beacon of faith,
            community, and service for over four decades.
        </p>

        {{-- CTAs --}}
        <div class="animate-fade-up delay-4 flex flex-wrap items-center justify-center gap-3">
            <a href="/mass-schedule"
               class="gold-btn inline-flex items-center gap-2 rounded-full"
               style="padding: 13px 30px; font-size: 10.5px; letter-spacing: 0.18em; text-transform: uppercase;">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                Mass Schedule
            </a>
            <a href="/submit-intention"
               class="ghost-btn inline-flex items-center gap-2 rounded-full font-bold uppercase"
               style="padding: 13px 30px; font-size: 10.5px; letter-spacing: 0.18em;">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/></svg>
                Offer an Intention
            </a>
            <a href="/inquiry"
               class="ghost-btn inline-flex items-center gap-2 rounded-full font-bold uppercase"
               style="padding: 13px 30px; font-size: 10.5px; letter-spacing: 0.18em;">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
                Parish Inquiry
            </a>
        </div>

        {{-- Stats strip --}}
        <div class="animate-fade-up delay-5 flex items-center justify-center gap-8 mt-12 pt-10"
             style="border-top: 1px solid rgba(245,197,24,0.15); width: 100%; max-width: 420px;">
            @foreach([['40+','Years of Service'],['7','Weekly Masses'],['1','Community']] as $stat)
            <div class="text-center">
                <div class="font-heading font-bold italic" style="font-size: 1.75rem; color: var(--gold-light); line-height: 1;">{{ $stat[0] }}</div>
                <div style="font-size: 9px; text-transform: uppercase; letter-spacing: 0.3em; color: rgba(200,215,255,0.5); margin-top: 4px;">{{ $stat[1] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Scroll cue --}}
    <div class="absolute z-10 flex flex-col items-center gap-2 animate-fade-in delay-5"
         style="bottom: 2.5rem; left: 50%; transform: translateX(-50%);">
        <span style="color: rgba(245,197,24,0.4); font-size: 9px; text-transform: uppercase; letter-spacing: 0.4em;"></span>
        <div class="scroll-line"
             style="height: 36px; width: 1.5px; background: linear-gradient(to bottom, rgba(245,197,24,0.6), transparent);"></div>
    </div>
</section>


<br>
{{-- ═══════════════════════════════════════════════════ --}}
{{-- NEXT MASS BANNER --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="relative z-20 bg-[var(--cream)]" style="padding-top: 0;">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="max-w-5xl mx-auto -mt-10 rounded-[28px] overflow-hidden"
             style="background: #FFFFFF;
                    border: 1px solid rgba(26,64,128,0.14);
                    box-shadow: 0 20px 64px rgba(13,42,82,0.14), 0 1px 0 rgba(255,255,255,0.9) inset;">

            {{-- Gold top accent --}}
            <div class="h-[3px]"
                 style="background: linear-gradient(90deg, transparent, #F5C518 20%, #FFD740 50%, #F5C518 80%, transparent);"></div>

            <div class="grid md:grid-cols-[1fr_1.4fr_auto]">

                {{-- ── Next Mass ── --}}
                <div class="p-8 flex items-center gap-5"
                     style="border-right: 1px solid rgba(26,64,128,0.10);">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0"
                         style="background: linear-gradient(135deg, rgba(245,197,24,0.14), rgba(245,197,24,0.04));
                                border: 1px solid rgba(245,197,24,0.32);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                             fill="none" stroke="#E0A800" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m18 7 4 2v11a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9l4-2"/>
                            <path d="M14 22v-4a2 2 0 0 0-2-2v0a2 2 0 0 0-2 2v4"/>
                            <path d="M18 5 12 2 6 5"/><path d="M12 7V2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="eyebrow mb-2">Next Mass</p>
                        @if($nextMass)
                            @if(($nextMass->calculated_day ?? '') === 'Ongoing')
                                <p class="font-heading font-bold text-2xl italic leading-tight" style="color: var(--blue-soft);">
                                    Ongoing Now
                                </p>
                                <p class="text-xs mt-1" style="color: rgba(13,42,82,0.45);">Started at {{ $nextMass->calculated_time }}</p>
                            @else
                                <p class="font-heading font-bold text-2xl italic leading-tight" style="color: var(--blue-deep);">
                                    {{ $nextMass->calculated_day }}
                                </p>
                                <p class="font-heading text-xl italic leading-tight" style="color: var(--gold);">
                                    {{ $nextMass->calculated_time }}
                                </p>
                                @if(isset($nextMass->mass_type))
                                    <p class="text-[11px] mt-1.5 uppercase tracking-wider font-medium capitalize" style="color: rgba(13,42,82,0.4);">
                                        {{ $nextMass->mass_type }} Mass
                                    </p>
                                @endif
                            @endif
                        @else
                            <p class="font-heading font-bold text-2xl italic" style="color: var(--blue-deep);">See Schedule</p>
                        @endif
                    </div>
                </div>

                {{-- ── Office Hours ── --}}
                <div class="p-8 flex items-start gap-5"
                     style="border-right: 1px solid rgba(26,64,128,0.10);">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 mt-0.5"
                         style="background: linear-gradient(135deg, rgba(245,197,24,0.14), rgba(245,197,24,0.04));
                                border: 1px solid rgba(245,197,24,0.32);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                             fill="none" stroke="#E0A800" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="eyebrow mb-4">Office Hours</p>
                        <div class="space-y-3">
                            <div class="flex items-start justify-between gap-4">
                                <span class="text-[11px] font-bold shrink-0 pt-0.5 w-14" style="color: var(--blue-mid);">Tue – Sat</span>
                                <div class="text-right space-y-0.5">
                                    <div class="text-[11.5px] leading-5" style="color: rgba(13,42,82,0.65);">6:00 AM – 12:00 NN</div>
                                    <div class="text-[11.5px] leading-5" style="color: rgba(13,42,82,0.65);">1:30 PM – 6:00 PM</div>
                                </div>
                            </div>
                            <div style="height:1px; background: rgba(26,64,128,0.10);"></div>
                            <div class="flex items-start justify-between gap-4">
                                <span class="text-[11px] font-bold shrink-0 pt-0.5 w-14" style="color: var(--blue-mid);">Sunday</span>
                                <div class="text-right space-y-0.5">
                                    <div class="text-[11.5px] leading-5" style="color: rgba(13,42,82,0.65);">6:00 AM – 12:00 NN</div>
                                    <div class="text-[11.5px] leading-5" style="color: rgba(13,42,82,0.65);">3:00 PM – 6:00 PM</div>
                                </div>
                            </div>
                            <div style="height:1px; background: rgba(26,64,128,0.10);"></div>
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-[11px] font-bold w-14" style="color: rgba(13,42,82,0.35);">Monday</span>
                                <span class="text-[10px] font-semibold uppercase tracking-widest px-3 py-1 rounded-full"
                                      style="background: rgba(13,42,82,0.05); border: 1px solid rgba(13,42,82,0.10); color: rgba(13,42,82,0.4);">
                                    Closed
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── CTA ── --}}
                <div class="p-8 flex items-center justify-center" style="min-width: 180px;">
                    <a href="/mass-schedule"
                       class="group flex flex-col items-center justify-center gap-3 w-full h-full rounded-2xl
                              text-center transition-all duration-300 hover:-translate-y-1 px-6 py-6"
                       style="background: var(--blue-deep);
                              color: #FFFFFF;
                              box-shadow: 0 8px 28px rgba(13,42,82,0.35);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                             fill="none" stroke="#FFD740" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="4" rx="2"/>
                            <path d="M16 2v4M8 2v4M3 10h18"/>
                        </svg>
                        <span class="text-[10px] font-bold uppercase tracking-[0.22em] text-white">
                            Full Schedule
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                             fill="none" stroke="rgba(255,215,64,0.7)" stroke-width="2.5"
                             stroke-linecap="round" stroke-linejoin="round"
                             class="group-hover:translate-y-0.5 transition-transform">
                            <path d="m6 9 6 6 6-6"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ═══════════════════════════════════════════════════ --}}
{{-- QUICK ACTIONS --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="py-32 bg-[var(--cream)]">
    <br><br><div class="max-w-[1200px] mx-auto px-6">
        <div class="text-center mb-16">
            <div class="divider-ornament mb-4"><span class="eyebrow">Quick Access</span></div>
            <h2 class="font-heading text-4xl md:text-5xl font-bold italic" style="color: var(--blue-deep);">How Can We Serve You?</h2>
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
               class="card-sacred group flex flex-col items-center gap-5 p-9 text-center">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center transition-all duration-300 group-hover:scale-110"
                     style="background: linear-gradient(135deg, rgba(245,197,24,0.14), rgba(245,197,24,0.04)); border: 1px solid rgba(245,197,24,0.32);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">{!! $a['icon'] !!}</svg>
                </div>
                <div>
                    <p class="font-heading font-bold text-lg italic transition-colors duration-200"
                       style="color: var(--blue-deep);"
                       onmouseover="" class="group-hover:text-[#C9A200]">{{ $a['label'] }}</p>
                    <p class="text-[11px] mt-1 tracking-wide" style="color: rgba(13,42,82,0.4);">{{ $a['sub'] }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div><br>
</section>


{{-- ═══════════════════════════════════════════════════ --}}
{{-- UPCOMING EVENTS --}}
{{-- ═══════════════════════════════════════════════════ --}}
@if(isset($upcomingEvents) && $upcomingEvents->count() > 0)
<section class="py-28 section-ruled" style="background: linear-gradient(180deg, #EDF2FC 0%, #E3ECFF 100%);">
    <div class="max-w-[1200px] mx-auto px-6">

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 max-w-5xl mx-auto mb-14">
            <div><br>
                <div class="divider-ornament justify-start mb-4">
                    <span class="eyebrow">What's Coming</span>
                </div>
                <h2 class="font-heading text-4xl md:text-5xl font-bold italic leading-tight" style="color: var(--blue-deep);">
                    Upcoming Events
                </h2>
            </div>
            <a href="/events"
               class="inline-flex items-center gap-2 text-[11px] font-semibold uppercase tracking-widest transition-colors group shrink-0"
               style="color: var(--gold);"
               onmouseover="this.style.color='var(--blue-deep)'"
               onmouseout="this.style.color='var(--gold)'">
                View all
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                     class="group-hover:translate-x-1 transition-transform">
                    <path d="m9 18 6-6-6-6"/>
                </svg>
            </a>
        </div>

        @php $eventCount = $upcomingEvents->count(); @endphp
        <div class="max-w-5xl mx-auto">
            @if($eventCount === 1)
            @foreach($upcomingEvents as $event)
            <a href="{{ route('events.show', $event) }}"
               class="card-sacred group flex flex-col md:flex-row items-stretch gap-0 overflow-hidden"
               style="background: #FFFFFF;">
                <div class="md:w-[140px] shrink-0 flex flex-col items-center justify-center gap-1 py-10 md:py-0"
                     style="background: linear-gradient(135deg, rgba(245,197,24,0.12), rgba(245,197,24,0.03));
                            border-right: 1px solid rgba(245,197,24,0.25);">
                    <span class="text-[10px] font-bold uppercase tracking-[0.3em]" style="color: var(--gold);">
                        {{ $event->event_date->format('M') }}
                    </span>
                    <span class="font-heading font-bold italic leading-none" style="font-size: 4rem; color: var(--blue-deep);">
                        {{ $event->event_date->format('d') }}
                    </span>
                    <span class="text-[10px] font-medium uppercase tracking-widest" style="color: rgba(13,42,82,0.4);">
                        {{ $event->event_date->format('l') }}
                    </span>
                </div>
                <div class="flex-1 p-10 md:p-14 flex flex-col justify-center">
                    <span class="eyebrow mb-3" style="color: rgba(201,162,0,0.8);">Featured Event</span>
                    <h3 class="font-heading font-bold text-3xl md:text-4xl italic leading-tight mb-3 transition-colors"
                        style="color: var(--blue-deep);"
                        onmouseover="this.style.color='var(--gold)'"
                        onmouseout="this.style.color='var(--blue-deep)'">
                        {{ $event->title }}
                    </h3>
                    <p class="leading-relaxed mb-5 max-w-lg" style="color: rgba(13,42,82,0.55);">{{ $event->description }}</p>
                    @if(!empty($event->event_time) && count($event->event_time) > 0)
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                             fill="none" stroke="#C9A200" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                        <span class="text-[12px] font-semibold uppercase tracking-wider" style="color: var(--gold);">
                            {{ \Carbon\Carbon::parse($event->event_time[0]['time'] ?? '')->format('g:i A') }}
                        </span>
                    </div>
                    @endif
                </div>
                <div class="hidden md:flex items-center pr-8">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="transition-all" style="color: rgba(26,64,128,0.2);">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                </div>
            </a>
            @endforeach

            @else
            @foreach($upcomingEvents as $i => $event)
                @if($i === 0)
                <a href="{{ route('events.show', $event) }}"
                   class="card-sacred group flex items-stretch gap-0 overflow-hidden mb-5"
                   style="background: #FFFFFF;">
                    <div class="w-[110px] shrink-0 flex flex-col items-center justify-center gap-1"
                         style="background: linear-gradient(135deg, rgba(245,197,24,0.12), rgba(245,197,24,0.03));
                                border-right: 1px solid rgba(245,197,24,0.22);">
                        <span class="text-[10px] font-bold uppercase tracking-[0.3em]" style="color: var(--gold);">
                            {{ $event->event_date->format('M') }}
                        </span>
                        <span class="font-heading font-bold italic leading-none" style="font-size: 3rem; color: var(--blue-deep);">
                            {{ $event->event_date->format('d') }}
                        </span>
                        <span class="text-[9px] font-medium uppercase tracking-widest" style="color: rgba(13,42,82,0.4);">
                            {{ $event->event_date->format('D') }}
                        </span>
                    </div>
                    <div class="flex-1 px-8 py-7 flex flex-col justify-center">
                        <span class="eyebrow mb-2" style="color: rgba(201,162,0,0.75);">Featured</span>
                        <h3 class="font-heading font-bold text-2xl md:text-3xl italic leading-tight mb-2 transition-colors"
                            style="color: var(--blue-deep);">
                            {{ $event->title }}
                        </h3>
                        <p class="text-sm leading-relaxed line-clamp-2 max-w-xl" style="color: rgba(13,42,82,0.5);">
                            {{ $event->description }}
                        </p>
                        @if(!empty($event->event_time) && count($event->event_time) > 0)
                        <div class="flex items-center gap-1.5 mt-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24"
                                 fill="none" stroke="#C9A200" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                            </svg>
                            <span class="text-[11px] font-semibold" style="color: var(--gold);">
                                {{ \Carbon\Carbon::parse($event->event_time[0]['time'] ?? '')->format('g:i A') }}
                            </span>
                        </div>
                        @endif
                    </div>
                    <div class="flex items-center pr-7">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             style="color: rgba(26,64,128,0.2);">
                            <path d="m9 18 6-6-6-6"/>
                        </svg>
                    </div>
                </a>

                <div class="grid gap-4" style="grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));">
                @elseif($i === $eventCount - 1)
                <a href="{{ route('events.show', $event) }}"
                   class="card-sacred group flex items-center gap-5 p-6"
                   style="background: #FFFFFF;">
                    <div class="shrink-0 w-[56px] text-center">
                        <div class="rounded-xl overflow-hidden" style="border: 1px solid rgba(245,197,24,0.30);">
                            <div class="py-1 text-[9px] font-bold uppercase tracking-widest"
                                 style="background: linear-gradient(135deg, rgba(245,197,24,0.14), rgba(245,197,24,0.04)); color: var(--gold);">
                                {{ $event->event_date->format('M') }}
                            </div>
                            <div class="py-2 bg-white">
                                <span class="font-heading font-bold text-2xl italic" style="color: var(--blue-deep);">
                                    {{ $event->event_date->format('d') }}
                                </span>
                            </div>
                        </div>
                        <span class="text-[9px] font-medium mt-1.5 block tracking-wide" style="color: rgba(13,42,82,0.4);">
                            {{ $event->event_date->format('D') }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-heading font-bold text-lg italic leading-tight mb-1 line-clamp-1 transition-colors"
                            style="color: var(--blue-deep);">
                            {{ $event->title }}
                        </h3>
                        <p class="text-xs line-clamp-2 leading-relaxed" style="color: rgba(13,42,82,0.45);">{{ $event->description }}</p>
                        @if(!empty($event->event_time) && count($event->event_time) > 0)
                        <div class="flex items-center gap-1 mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24"
                                 fill="none" stroke="#C9A200" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                            </svg>
                            <span class="text-[10px] font-semibold" style="color: var(--gold);">
                                {{ \Carbon\Carbon::parse($event->event_time[0]['time'] ?? '')->format('g:i A') }}
                            </span>
                        </div>
                        @endif
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="shrink-0 transition-all" style="color: rgba(26,64,128,0.2);">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                </a>
                </div>

                @else
                <a href="{{ route('events.show', $event) }}"
                   class="card-sacred group flex items-center gap-5 p-6"
                   style="background: #FFFFFF;">
                    <div class="shrink-0 w-[56px] text-center">
                        <div class="rounded-xl overflow-hidden" style="border: 1px solid rgba(245,197,24,0.30);">
                            <div class="py-1 text-[9px] font-bold uppercase tracking-widest"
                                 style="background: linear-gradient(135deg, rgba(245,197,24,0.14), rgba(245,197,24,0.04)); color: var(--gold);">
                                {{ $event->event_date->format('M') }}
                            </div>
                            <div class="py-2 bg-white">
                                <span class="font-heading font-bold text-2xl italic" style="color: var(--blue-deep);">
                                    {{ $event->event_date->format('d') }}
                                </span>
                            </div>
                        </div>
                        <span class="text-[9px] font-medium mt-1.5 block tracking-wide" style="color: rgba(13,42,82,0.4);">
                            {{ $event->event_date->format('D') }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-heading font-bold text-lg italic leading-tight mb-1 line-clamp-1 transition-colors"
                            style="color: var(--blue-deep);">
                            {{ $event->title }}
                        </h3>
                        <p class="text-xs line-clamp-2 leading-relaxed" style="color: rgba(13,42,82,0.45);">{{ $event->description }}</p>
                        @if(!empty($event->event_time) && count($event->event_time) > 0)
                        <div class="flex items-center gap-1 mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24"
                                 fill="none" stroke="#C9A200" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                            </svg>
                            <span class="text-[10px] font-semibold" style="color: var(--gold);">
                                {{ \Carbon\Carbon::parse($event->event_time[0]['time'] ?? '')->format('g:i A') }}
                            </span>
                        </div>
                        @endif
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="shrink-0 transition-all" style="color: rgba(26,64,128,0.2);">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                </a>
                @endif
            @endforeach
            @endif
        </div>
    </div><br>
</section>
@endif

<br>

{{-- ═══════════════════════════════════════════════════ --}}
{{-- ANNOUNCEMENTS --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="py-36 bg-[var(--cream)]">
    <div class="max-w-[1200px] mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 max-w-5xl mx-auto mb-16">
            <div>
                <div class="divider-ornament justify-start mb-4">
                    <span class="eyebrow">From the Parish</span>
                </div>
                <h2 class="font-heading text-4xl md:text-5xl font-bold italic" style="color: var(--blue-deep);">Latest Announcements</h2>
            </div>
        </div>
        <div class="grid gap-7 max-w-5xl mx-auto" style="grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));">
            @forelse($announcements as $item)
            <div class="card-sacred flex flex-col group" style="background: #FFFFFF;">
                <div class="h-[2px] mx-7 mt-8 rounded-full mb-7"
                     style="background: linear-gradient(90deg, var(--gold), rgba(245,197,24,0.25));"></div>
                <div class="px-8 pb-8 flex flex-col flex-1">
                    <div class="flex items-center gap-2.5 mb-6">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center"
                             style="background: rgba(245,197,24,0.10); border: 1px solid rgba(245,197,24,0.25);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
                        </div>
                        <span class="text-[9px] font-bold uppercase tracking-[0.3em]" style="color: rgba(13,42,82,0.4);">Announcement</span>
                    </div>
                    <h3 class="font-heading font-bold text-xl italic leading-tight mb-4 transition-colors line-clamp-2"
                        style="color: var(--blue-deep);">{{ $item->title }}</h3>
                    <p class="text-sm leading-relaxed line-clamp-3 flex-1" style="color: rgba(13,42,82,0.55);">{{ $item->content }}</p>
                    <div class="flex items-center gap-1.5 mt-8 pt-6 text-[11px]"
                         style="border-top: 1px solid rgba(26,64,128,0.08); color: rgba(13,42,82,0.35);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                        {{ $item->created_at->format('M d, Y') }}
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 py-20 text-center">
                <p class="font-heading italic text-lg" style="color: rgba(13,42,82,0.3);">No recent announcements at this time.</p>
            </div>
            @endforelse
        </div>
    </div><br>
</section>


{{-- ═══════════════════════════════════════════════════ --}}
{{-- SACRAMENTAL SERVICES --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="py-28 relative overflow-hidden" style="background: var(--blue-deep);">

    {{-- Decorative cross watermark --}}
    <br><div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none select-none font-cinzel leading-none"
         style="font-size: 420px; color: rgba(255,255,255,0.018);">✝</div>

    {{-- Gold radial glow --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 pointer-events-none"
         style="width: 600px; height: 300px; background: radial-gradient(ellipse, rgba(245,197,24,0.08) 0%, transparent 70%);"></div>

    <div class="absolute top-0 left-0 right-0 h-px"
         style="background: linear-gradient(90deg, transparent, rgba(245,197,24,0.4), transparent);"></div>

    <div class="max-w-[1200px] mx-auto px-6 relative z-10">
        <div class="text-center mb-16">
            <div class="divider-ornament mb-4">
                <span style="font-size: 10px; font-weight: 600; letter-spacing: 0.35em; text-transform: uppercase; color: rgba(245,197,24,0.65);">Sacramental Services</span>
            </div>
            <h2 class="font-heading text-4xl md:text-5xl font-bold italic" style="color: #EBF2FF;">How We Serve</h2>
            <p class="mt-4 text-sm font-light" style="color: rgba(235,242,255,0.40); letter-spacing: 0.05em;">Inquire about any sacramental service at our parish office.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 max-w-5xl mx-auto">
            @php
            $services = [
                ['label'=>'Baptism','href'=>'/inquiry','svg'=>'<circle cx="12" cy="6" r="3"/><path d="M5 12a7 7 0 0 1 14 0"/><line x1="12" y1="12" x2="12" y2="20"/><path d="M9 20h6"/>'],
                ['label'=>'Wedding','href'=>'/inquiry','svg'=>'<path d="M8.5 9.5 5 15h14l-3.5-5.5"/><circle cx="12" cy="5" r="2.5"/><path d="M12 8v10"/><path d="M9.5 18h5"/>'],
                ['label'=>'Confirmation','href'=>'/inquiry','svg'=>'<path d="M12 2v8"/><path d="M8 5l4 5 4-5"/><circle cx="12" cy="17" r="4"/><path d="M12 14v6"/>'],
                ['label'=>'Funeral Mass','href'=>'/inquiry','svg'=>'<path d="M12 2v12"/><path d="M8 6h8"/><path d="M5 14h14l-1.5 6H6.5L5 14z"/>'],
                ['label'=>'House Blessing','href'=>'/inquiry','svg'=>'<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>'],
                ['label'=>'Car Blessing','href'=>'/inquiry','svg'=>'<path d="M19 17H5v-5l2-6h10l2 6v5Z"/><circle cx="7.5" cy="17.5" r="1.5"/><circle cx="16.5" cy="17.5" r="1.5"/><path d="M5 12h14"/>'],
            ];
            @endphp
            @foreach($services as $s)
            <a href="{{ $s['href'] }}"
               class="group flex flex-col items-center gap-4 p-6 rounded-2xl text-center transition-all duration-300 hover:-translate-y-1"
               style="border: 1px solid rgba(255,255,255,0.08); background: rgba(255,255,255,0.03);"
               onmouseover="this.style.borderColor='rgba(245,197,24,0.50)'; this.style.background='rgba(245,197,24,0.06)';"
               onmouseout="this.style.borderColor='rgba(255,255,255,0.08)'; this.style.background='rgba(255,255,255,0.03)';">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-300 group-hover:scale-110"
                     style="border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.04);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgba(235,242,255,0.55)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
                         class="transition-all duration-300" style="">{!! $s['svg'] !!}</svg>
                </div>
                <span class="text-[10.5px] font-semibold uppercase tracking-wider transition-colors duration-200"
                      style="color: rgba(235,242,255,0.55);">{{ $s['label'] }}</span>
            </a>
            @endforeach
        </div>
    </div><br>

    <div class="absolute bottom-0 left-0 right-0 h-px"
         style="background: linear-gradient(90deg, transparent, rgba(245,197,24,0.25), transparent);"></div>
</section>


{{-- ═══════════════════════════════════════════════════ --}}
{{-- INTENTION CTA --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="py-28 relative overflow-hidden bg-[var(--cream)]">

    <br><div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] rounded-full pointer-events-none"
         style="background: radial-gradient(circle, rgba(26,64,128,0.05) 0%, transparent 70%);"></div>

    <div class="max-w-[1200px] mx-auto px-6 relative z-10">
        <div class="max-w-2xl mx-auto text-center">

            <div class="font-cinzel text-4xl mb-8 opacity-60" style="color: var(--gold);">✝</div>

            <div class="divider-ornament mb-6"><span class="eyebrow">Unite Your Prayers</span></div>

            <h2 class="font-heading text-4xl md:text-6xl font-bold italic leading-[1.05] mb-6" style="color: var(--blue-deep);">
                Offer a Mass<br>Intention
            </h2>

            <p class="text-lg leading-relaxed mb-12 font-light" style="color: rgba(13,42,82,0.55); font-weight: 300;">
                Unite your prayers with the Holy Sacrifice of the Mass. Submit your intention online and our staff will include it in the upcoming liturgy.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="/submit-intention"
                   class="gold-btn inline-flex items-center gap-2.5 px-10 py-4 rounded-full text-[11px] font-bold uppercase tracking-widest">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/></svg>
                    Submit Intention
                </a>
                <a href="/track"
                   class="inline-flex items-center gap-2.5 px-10 py-4 rounded-full text-[11px] font-bold uppercase tracking-widest border-2 transition-all duration-300 hover:-translate-y-0.5"
                   style="border-color: rgba(26,64,128,0.25); color: var(--blue-deep); background: transparent;"
                   onmouseover="this.style.borderColor='var(--blue-mid)'; this.style.background='rgba(26,64,128,0.05)';"
                   onmouseout="this.style.borderColor='rgba(26,64,128,0.25)'; this.style.background='transparent';">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Track Status
                </a>
            </div>

            <p class="mt-16 text-[10px] font-medium uppercase tracking-[0.4em]" style="color: rgba(13,42,82,0.25);">
                Sto. Rosario Parish · Pacita, San Pedro, Laguna
            </p>
        </div>
    </div>
</section>

</x-public-layout>