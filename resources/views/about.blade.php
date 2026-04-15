<x-public-layout>
    <x-slot name="meta">
        <meta name="description" content="Learn about Sto. Rosario Parish in Pacita, San Pedro, Laguna. Discover our history, the Queen of the Most Holy Rosary, office hours, and contact details.">
    </x-slot>

    {{-- ───────────── STYLES + ANIMATIONS ───────────── --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&display=swap');

        /* ── Reset ── */
        *, *::before, *::after { box-sizing: border-box; }

        /* ── Tokens ── */
        :root {
            --maroon: #3B1A22;
            --gold:   #C9A96E;
            --cream:  #F5F0E8;
            --border: #E0D9CE;
            --text:   #555;
            --muted:  #888;
        }

        .font-heading { font-family: 'Playfair Display', Georgia, serif; font-style: italic; }

        /* ════════════════════════════════════════
           KEYFRAMES
        ════════════════════════════════════════ */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-32px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(32px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.92); }
            to   { opacity: 1; transform: scale(1); }
        }
        @keyframes shimmer {
            0%   { background-position: -200% center; }
            100% { background-position:  200% center; }
        }
        @keyframes pulse-dot {
            0%, 100% { box-shadow: 0 0 0 0 rgba(201,169,110,.6); }
            50%       { box-shadow: 0 0 0 7px rgba(201,169,110,0); }
        }
        @keyframes lineGrow {
            from { transform: scaleY(0); transform-origin: top center; }
            to   { transform: scaleY(1); transform-origin: top center; }
        }
        @keyframes countUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-8px); }
        }

        /* Respect reduced-motion */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                transition-duration: .01ms !important;
            }
        }

        /* ════════════════════════════════════════
           SCROLL-REVEAL BASE
        ════════════════════════════════════════ */
        [data-reveal] {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity .65s ease, transform .65s ease;
        }
        [data-reveal="left"]  { transform: translateX(-32px); }
        [data-reveal="right"] { transform: translateX(32px); }
        [data-reveal="scale"] { transform: scale(.93); }
        [data-reveal].revealed {
            opacity: 1;
            transform: none;
        }

        /* ════════════════════════════════════════
           HERO
        ════════════════════════════════════════ */
        .about-hero {
            background: var(--maroon);
            color: #fff;
            text-align: center;
            padding: 96px 24px 80px;
            position: relative;
            overflow: hidden;
        }
        /* decorative radial glow */
        .about-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 80% 60% at 50% 110%, rgba(201,169,110,.18) 0%, transparent 70%);
            pointer-events: none;
        }
        /* animated faint rose window ring */
        .about-hero::after {
            content: '';
            position: absolute;
            width: 520px; height: 520px;
            border-radius: 50%;
            border: 1px solid rgba(201,169,110,.07);
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            animation: spin-slow 60s linear infinite;
        }
        .hero-eyebrow {
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .35em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 18px;
            animation: fadeIn .6s ease both;
        }
        .hero-title {
            font-family: 'Playfair Display', Georgia, serif;
            font-style: italic;
            font-size: clamp(2.6rem, 7vw, 5rem);
            line-height: 1.05;
            margin-bottom: 20px;
            animation: fadeUp .8s .1s ease both;
        }
        .hero-divider {
            width: 48px; height: 1px;
            background: var(--gold);
            margin: 0 auto 20px;
            animation: scaleIn .6s .3s ease both;
        }
        .hero-sub {
            color: rgba(255,255,255,.65);
            font-size: .97rem;
            line-height: 1.75;
            max-width: 500px;
            margin: 0 auto;
            animation: fadeUp .8s .25s ease both;
        }

        /* ════════════════════════════════════════
           STATS BAR
        ════════════════════════════════════════ */
        .stats-bar {
            background: var(--cream);
            border-bottom: 1px solid var(--border);
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
        .stat-cell {
            padding: 40px 0;
            text-align: center;
            border-right: 1px solid var(--border);
        }
        .stat-cell:last-child { border-right: none; }
        .stat-number {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 3.4rem;
            color: var(--maroon);
            line-height: 1;
            /* JS adds .counted class to trigger */
        }
        .stat-number.counted { animation: countUp .7s ease both; }
        .stat-label {
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .22em;
            text-transform: uppercase;
            color: var(--muted);
            margin-top: 8px;
        }

        /* ════════════════════════════════════════
           OUR CALLING
        ════════════════════════════════════════ */
        .calling-section {
            padding: 88px 24px;
            max-width: 960px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 48px;
            align-items: center;
        }
        @media (min-width: 700px) {
            .calling-section { flex-direction: row; gap: 72px; }
        }
        .calling-img-wrap {
            width: 100%;
            max-width: 380px;
            flex-shrink: 0;
            aspect-ratio: 4/3;
            border-radius: 18px;
            overflow: hidden;
            position: relative;
            background: var(--border);
            /* hover lift */
            transition: transform .4s ease, box-shadow .4s ease;
        }
        .calling-img-wrap:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 48px rgba(59,26,34,.18);
        }
        .calling-img-wrap img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform .6s ease;
        }
        .calling-img-wrap:hover img { transform: scale(1.04); }
        .calling-badge {
            position: absolute;
            bottom: 14px; left: 14px;
            background: var(--maroon);
            color: var(--gold);
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .15em;
            text-transform: uppercase;
            padding: 5px 12px;
            border-radius: 4px;
        }
        .calling-eyebrow {
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .25em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 14px;
        }
        .calling-title {
            font-size: clamp(1.6rem, 4vw, 2.4rem);
            color: var(--maroon);
            line-height: 1.2;
            margin-bottom: 18px;
        }
        .calling-rule { width: 36px; height: 2px; background: var(--gold); margin-bottom: 22px; }
        .calling-body { color: var(--text); line-height: 1.8; font-size: .95rem; margin-bottom: 14px; }

        /* ════════════════════════════════════════
           TIMELINE
        ════════════════════════════════════════ */
        .timeline-section {
            background: var(--cream);
            padding: 88px 24px;
        }
        .section-eyebrow {
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .25em;
            text-transform: uppercase;
            color: var(--gold);
            text-align: center;
            margin-bottom: 10px;
        }
        .section-title {
            font-family: 'Playfair Display', Georgia, serif;
            font-style: italic;
            font-size: clamp(2rem, 5vw, 3.2rem);
            color: var(--maroon);
            text-align: center;
            margin-bottom: 0;
        }

        /* vertical track */
        .tl-wrapper {
            max-width: 720px;
            margin: 56px auto 0;
            position: relative;
            padding-left: 36px;
        }
        /* the golden spine */
        .tl-spine {
            position: absolute;
            left: 7px; top: 8px; bottom: 8px;
            width: 2px;
            background: linear-gradient(to bottom, transparent, var(--gold) 6%, var(--gold) 94%, transparent);
            transform-origin: top center;
            /* animated when section scrolls in */
            transform: scaleY(0);
            transition: transform 1.4s cubic-bezier(.16,1,.3,1);
        }
        .tl-spine.revealed { transform: scaleY(1); }

        /* individual items — staggered via JS */
        .tl-item {
            position: relative;
            margin-bottom: 52px;
            opacity: 0;
            transform: translateX(-20px);
            transition: opacity .55s ease, transform .55s ease;
        }
        .tl-item:last-child { margin-bottom: 0; }
        .tl-item.visible { opacity: 1; transform: none; }

        /* dot */
        .tl-dot {
            position: absolute;
            left: -43px; top: 6px;
            width: 16px; height: 16px;
            border-radius: 50%;
            background: var(--gold);
            border: 3px solid var(--cream);
            box-shadow: 0 0 0 2px var(--gold);
            transition: transform .3s ease;
        }
        .tl-item.visible .tl-dot { animation: pulse-dot 2.4s 1s ease infinite; }
        .tl-item:hover .tl-dot { transform: scale(1.3); }

        /* content */
        .tl-badge {
            display: inline-block;
            font-size: 8px;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
            background: var(--maroon);
            color: var(--gold);
            padding: 3px 10px;
            border-radius: 4px;
            margin-bottom: 8px;
        }
        .tl-year {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 2.4rem;
            color: var(--gold);
            line-height: 1;
            margin-bottom: 4px;
        }
        .tl-title {
            font-weight: 700;
            font-size: .97rem;
            color: var(--maroon);
            margin-bottom: 8px;
        }
        .tl-body {
            font-size: .875rem;
            color: var(--muted);
            line-height: 1.75;
            max-width: 580px;
        }

        /* expand / collapse on click */
        .tl-body-full { display: none; }
        .tl-item.expanded .tl-body-full { display: block; }
        .tl-toggle {
            display: inline-block;
            margin-top: 8px;
            font-size: .78rem;
            font-weight: 700;
            color: var(--gold);
            cursor: pointer;
            letter-spacing: .05em;
            user-select: none;
            transition: opacity .2s;
        }
        .tl-toggle:hover { opacity: .7; }

        /* ════════════════════════════════════════
           LEADERSHIP
        ════════════════════════════════════════ */
        .leadership-section {
            background: var(--maroon);
            padding: 88px 24px;
            text-align: center;
        }
        .leadership-section .section-eyebrow { color: var(--gold); }
        .leadership-title {
            font-family: 'Playfair Display', Georgia, serif;
            font-style: italic;
            font-size: clamp(2rem, 5vw, 3.2rem);
            color: #fff;
            margin-bottom: 44px;
        }
        .leader-card {
            background: #fff;
            border-radius: 18px;
            padding: 36px 32px;
            max-width: 380px;
            margin: 0 auto;
            transition: transform .4s ease, box-shadow .4s ease;
        }
        .leader-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 24px 56px rgba(0,0,0,.22);
        }
        .leader-avatar {
            width: 180px; height: 180px;
            border-radius: 50%;
            background: var(--maroon);
            color: var(--gold);
            font-size: 3.5rem;
            font-weight: 800;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px;
            animation: float 4s ease-in-out infinite;
        }
        .leader-name {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 1.2rem;
            color: var(--maroon);
        }
        .leader-role {
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: var(--gold);
            margin: 6px 0 18px;
        }
        .leader-rule { width: 28px; height: 1px; background: var(--border); margin: 0 auto 18px; }
        .leader-quote { font-size: .87rem; font-style: italic; color: var(--muted); line-height: 1.7; }

        /* ════════════════════════════════════════
           FIND US
        ════════════════════════════════════════ */
        .findus-section { background: var(--cream); padding: 88px 24px; }
        .findus-inner { max-width: 960px; margin: 0 auto; }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
            margin: 48px 0 28px;
        }
        @media (min-width: 600px) { .info-grid { grid-template-columns: repeat(3,1fr); } }

        .info-card {
            background: #fff;
            border: 0.5px solid var(--border);
            border-radius: 14px;
            padding: 28px 22px;
            text-align: center;
            transition: transform .35s ease, box-shadow .35s ease;
        }
        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 14px 36px rgba(59,26,34,.1);
        }
        .info-icon {
            width: 40px; height: 40px;
            border-radius: 50%;
            background: var(--cream);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px;
        }
        .info-card-label {
            font-size: 8px;
            font-weight: 800;
            letter-spacing: .15em;
            text-transform: uppercase;
            color: #bbb;
            margin-bottom: 10px;
        }
        .info-card p  { font-size: .88rem; font-weight: 600; color: var(--maroon); line-height: 1.6; }
        .info-card small { font-size: .78rem; color: var(--muted); }

        .map-wrap {
            border-radius: 18px;
            overflow: hidden;
            border: 0.5px solid var(--border);
            box-shadow: 0 4px 28px rgba(59,26,34,.09);
        }
        .map-credit { text-align: center; font-size: .72rem; color: #bbb; margin-top: 10px; }
        .map-credit a { color: var(--gold); }

        /* ════════════════════════════════════════
           CTA
        ════════════════════════════════════════ */
        .cta-section {
            background: var(--maroon);
            padding: 88px 24px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .cta-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 70% 50% at 50% 100%, rgba(201,169,110,.14) 0%, transparent 70%);
            pointer-events: none;
        }
        .cta-title {
            font-family: 'Playfair Display', Georgia, serif;
            font-style: italic;
            font-size: clamp(2.2rem, 6vw, 4rem);
            color: #fff;
            margin-bottom: 14px;
        }
        .cta-rule { width: 40px; height: 1px; background: var(--gold); margin: 0 auto 20px; }
        .cta-sub { font-size: .87rem; color: rgba(255,255,255,.45); margin-bottom: 40px; }
        .cta-btns { display: flex; flex-wrap: wrap; gap: 14px; justify-content: center; }

        .btn-gold {
            display: inline-block;
            background: var(--gold);
            color: var(--maroon);
            font-size: .87rem;
            font-weight: 700;
            padding: 13px 30px;
            border-radius: 8px;
            text-decoration: none;
            transition: opacity .2s, transform .2s;
        }
        .btn-gold:hover { opacity: .88; transform: translateY(-2px); }

        .btn-ghost {
            display: inline-block;
            border: 1px solid rgba(255,255,255,.28);
            color: #fff;
            font-size: .87rem;
            font-weight: 700;
            padding: 13px 30px;
            border-radius: 8px;
            text-decoration: none;
            transition: background .2s, transform .2s;
        }
        .btn-ghost:hover { background: rgba(255,255,255,.08); transform: translateY(-2px); }

        /* ── shimmer on gold rule hover ── */
        .hero-divider:hover {
            background: linear-gradient(90deg, var(--gold), #fff, var(--gold));
            background-size: 200% auto;
            animation: shimmer 1.2s linear infinite;
        }
    </style>

    {{-- ═══════════════ HERO ═══════════════ --}}
    <section class="about-hero">
        <p class="hero-eyebrow">Pacita, San Pedro, Laguna</p>
        <h1 class="hero-title font-heading">About Our Parish</h1>
        <div class="hero-divider"></div>
        <p class="hero-sub">
            Home to the Queen of the Most Holy Rosary — a beacon of faith,<br class="hidden md:block">
            community, and service for over four decades.
        </p>
    </section>

    {{-- ═══════════════ STATS ═══════════════ --}}
    <section class="stats-bar">
        <div class="stat-cell">
            <p class="stat-number" data-target="40" data-suffix="+">40+</p>
            <p class="stat-label">Years of Service</p>
        </div>
        <div class="stat-cell">
            <p class="stat-number" data-target="1983" data-suffix="">1983</p>
            <p class="stat-label">Year Founded</p>
        </div>
    </section>

    {{-- ═══════════════ OUR CALLING ═══════════════ --}}
    <section style="background:#fff;">
        <div class="calling-section">
            <div class="calling-img-wrap" data-reveal="scale">
                <img
                    src="{{ asset($global_settings['hero_image'] ?? 'bg.png') }}"
                    alt="Sto. Rosario Parish Church"
                >
                <div class="calling-badge">Est. 1983</div>
            </div>
            <div data-reveal="right">
                <p class="calling-eyebrow">Our Calling</p>
                <h2 class="font-heading calling-title">Building a sanctuary<br>of faith &amp; service</h2>
                <div class="calling-rule"></div>
                <p class="calling-body">
                    Sto. Rosario Parish is home to the venerable image of the Queen of the Most Holy Rosary of Pacita — a European-inspired wooden sculpture enshrined at the retablo mayor of our church. Carved in Paete, Laguna in 1982, she is the titular patroness of Brgy. Pacita 1 and the beloved protectress of the faithful of San Pedro.
                </p>
                <p class="calling-body">
                    In 2024, the image was declared an <strong style="color:var(--maroon)">Important Cultural Property</strong> of the City of San Pedro. In 2025, Our Lady was accorded the honorific title <strong style="color:var(--maroon)">"Queen of the City of San Pedro."</strong>
                </p>
            </div>
        </div>
    </section>

    {{-- ═══════════════ TIMELINE ═══════════════ --}}
    <section class="timeline-section">
        <p class="section-eyebrow" data-reveal>The Journey</p>
        <h2 class="section-title" data-reveal>Our sacred history</h2>

        <div class="tl-wrapper">
            <div class="tl-spine" id="tl-spine"></div>

            @php
            $timeline = [
                [
                    'year'  => '1982',
                    'badge' => null,
                    'title' => 'The image is carved',
                    'short' => 'Carved in Paete, Laguna through funds gathered by Mrs. Delia Sanchez and Mrs. Fely Canta.',
                    'full'  => 'Blessed by Rev. Fr. Rey Amante, the image was first housed at the Canta residence, then transferred in procession to the make-shift chapel.',
                ],
                [
                    'year'  => '1983',
                    'badge' => null,
                    'title' => 'Canonical erection',
                    'short' => 'The parish was canonically erected on October 16, 1983.',
                    'full'  => 'On the same day, the Queen of the Most Holy Rosary of Pacita was officially declared patroness of the parish community.',
                ],
                [
                    'year'  => '1986',
                    'badge' => null,
                    'title' => 'Church dedication',
                    'short' => 'The Sto. Rosario Parish Church was blessed and dedicated on December 6.',
                    'full'  => 'Jointly officiated by Msgr. Bruno Torpigliani (Papal Nuncio), Bishop Pedro Bantigue, and Auxiliary Bishop Gabriel Reyes.',
                ],
                [
                    'year'  => '2009',
                    'badge' => null,
                    'title' => 'Our Lady of Pacita',
                    'short' => "Rev. Fr. Mario P. Rivera began promoting the endearing title 'Our Lady of Pacita.'",
                    'full'  => "This title integrated the community's deep sense of belonging with the Blessed Mother.",
                ],
                [
                    'year'  => '2021',
                    'badge' => null,
                    'title' => 'Hermandad established',
                    'short' => 'The Hermandad del Santo Rosario — the Rosary Confraternity of Pacita — was formally established.',
                    'full'  => 'Established to propagate devotion to Our Lady. The Perpetual Novena is held every Saturday.',
                ],
                [
                    'year'  => '2024',
                    'badge' => 'Cultural Heritage',
                    'title' => 'Important Cultural Property',
                    'short' => 'The image was declared an Important Cultural Property of the City of San Pedro.',
                    'full'  => 'Via Sangguniang Panlungsod Resolution No. 2024-198, adopted October 1, 2024.',
                ],
                [
                    'year'  => '2025',
                    'badge' => 'Royal Honor',
                    'title' => 'Queen of the City',
                    'short' => "Our Lady was accorded the honorific title 'Queen of the City of San Pedro.'",
                    'full'  => 'Via Sangguniang Panlungsod Resolution No. 2025-93, adopted June 10, 2025.',
                ],
            ];
            @endphp

            @foreach($timeline as $i => $e)
            <div class="tl-item" data-index="{{ $i }}">
                <div class="tl-dot"></div>
                @if($e['badge'])
                    <span class="tl-badge">{{ $e['badge'] }}</span>
                @endif
                <div class="tl-year">{{ $e['year'] }}</div>
                <div class="tl-title">{{ $e['title'] }}</div>
                <p class="tl-body">
                    {{ $e['short'] }}
                    <span class="tl-body-full"> {{ $e['full'] }}</span>
                </p>
                <span class="tl-toggle" onclick="toggleItem(this)">Read more ↓</span>
            </div>
            @endforeach
        </div>
    </section>

    {{-- ═══════════════ LEADERSHIP ═══════════════ --}}
    <section class="leadership-section">
        <p class="section-eyebrow" data-reveal>Our Leadership</p>
        <h2 class="leadership-title" data-reveal>Shepherds of<br>the flock</h2>
        <div class="leader-card" data-reveal="scale">
            @if(isset($global_settings['priest_image']))
                <div class="leader-avatar" style="background-image: url('{{ asset('storage/' . $global_settings['priest_image']) }}'); background-size: cover; background-position: center;"></div>
            @else
                <div class="leader-avatar">FV</div>
            @endif
            <h3 class="leader-name">{{ $global_settings['priest_name'] ?? 'Rev. Fr. Parish Priest' }}</h3>
            <p class="leader-role">Parish Priest · 2021–Present</p>
            <div class="leader-rule"></div>
            <p class="leader-quote">"Feeding the sheep and tending the flock of the Lord with love and devotion."</p>
        </div>
    </section>

    {{-- ═══════════════ FIND US ═══════════════ --}}
    <section class="findus-section">
        <div class="findus-inner">
            <p class="section-eyebrow" data-reveal>Find Us</p>
            <h2 class="section-title" data-reveal>We're here for you</h2>

            <div class="info-grid">
                <div class="info-card" data-reveal>
                    <div class="info-icon">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5z" fill="#C9A96E"/></svg>
                    </div>
                    <p class="info-card-label">Address</p>
                    <p>1 Sto. Rosario Drive, Pacita,<br>San Pedro, Laguna</p>
                </div>

                <div class="info-card" data-reveal style="transition-delay:.1s">
                    <div class="info-icon">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path d="M6.62 10.79a15.05 15.05 0 006.59 6.59l2.2-2.2a1 1 0 011.01-.24c1.12.37 2.33.57 3.58.57a1 1 0 011 1V20a1 1 0 01-1 1C9.39 21 3 14.61 3 7a1 1 0 011-1h3.5a1 1 0 011 1c0 1.25.2 2.45.57 3.57a1 1 0 01-.25 1.01l-2.2 2.21z" fill="#C9A96E"/></svg>
                    </div>
                    <p class="info-card-label">Contact</p>
                    <p>(02) 8869 2742</p>
                    <p>0906 099 2324</p>
                    <small>{{ config('services.parish.office_email', 'officestorosarioparish@gmail.com') }}</small>
                </div>

                <div class="info-card" data-reveal style="transition-delay:.2s">
                    <div class="info-icon">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke="#C9A96E" stroke-width="2"/><path d="M12 7v5l3 3" stroke="#C9A96E" stroke-width="2" stroke-linecap="round"/></svg>
                    </div>
                    <p class="info-card-label">Office Hours</p>
                    <p>Tue–Sat</p>
                    <small>6:00 AM – 12:00 NN · 1:30 PM – 6:00 PM</small>
                    <p style="margin-top:8px">Sunday</p>
                    <small>6:00 AM – 12:00 NN · 3:00 PM – 6:00 PM</small>
                </div>
            </div>

            {{-- MAP --}}
            <div class="map-wrap" data-reveal>
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                <div id="parish-map" style="width:100%;height:400px;"></div>
            </div>
            <p class="map-credit">
                Map data © <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a> contributors
            </p>
        </div>
    </section>

    {{-- ═══════════════ CTA ═══════════════ --}}
    <section class="cta-section">
        <h2 class="cta-title" data-reveal>Visit us today</h2>
        <div class="cta-rule" data-reveal></div>
        <p class="cta-sub" data-reveal>Our doors and hearts are always open to you.</p>
        <div class="cta-btns" data-reveal>
            <a href="/mass-schedule" class="btn-gold">View schedule</a>
            <a href="/inquiry"       class="btn-ghost">Contact office</a>
        </div>
    </section>

    {{-- ═══════════════ SCRIPTS ═══════════════ --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    /* ── Leaflet map ── */
    document.addEventListener('DOMContentLoaded', function () {
        const lat = 14.345435, lng = 121.061630;

        const map = L.map('parish-map', {
            scrollWheelZoom: false,
            zoomControl: true
        }).setView([lat, lng], 17);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: false
        }).addTo(map);

        const icon = L.divIcon({
            className: '',
            html: `<div style="
                width:36px;height:36px;
                background:#3B1A22;
                border:3px solid #C9A96E;
                border-radius:50% 50% 50% 0;
                transform:rotate(-45deg);
                box-shadow:0 2px 8px rgba(0,0,0,.3);
            "></div>`,
            iconSize:    [36, 36],
            iconAnchor:  [18, 36],
            popupAnchor: [0, -36]
        });

        const directionsUrl = `https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}`;

        L.marker([lat, lng], { icon }).addTo(map)
            .bindPopup(`
                <div style="font-family:sans-serif;padding:4px 2px;min-width:200px;">
                    <p style="font-weight:700;font-size:14px;color:#3B1A22;margin:0 0 4px;">Sto. Rosario Parish</p>
                    <p style="font-size:12px;color:#777;margin:0 0 10px;line-height:1.5;">
                        1 Sto. Rosario Drive,<br>Pacita, San Pedro, Laguna
                    </p>
                    <a href="${directionsUrl}" target="_blank"
                       style="display:inline-block;background:#C9A96E;color:#3B1A22;font-size:12px;font-weight:700;padding:6px 14px;border-radius:6px;text-decoration:none;">
                        Get Directions ↗
                    </a>
                </div>
            `, { maxWidth: 260 }).openPopup();

        setTimeout(() => map.invalidateSize(), 300);
    });

    /* ── Scroll-reveal (IntersectionObserver) ── */
    const revealEls = document.querySelectorAll('[data-reveal]');
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });
    revealEls.forEach(el => revealObserver.observe(el));

    /* ── Timeline: spine + staggered items ── */
    const spine  = document.getElementById('tl-spine');
    const tlItems = document.querySelectorAll('.tl-item');

    const spineObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                spine.classList.add('revealed');
                spineObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    spineObserver.observe(spine);

    const itemObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const idx = parseInt(entry.target.dataset.index, 10);
                setTimeout(() => entry.target.classList.add('visible'), idx * 90);
                itemObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    tlItems.forEach(el => itemObserver.observe(el));

    /* ── Timeline expand/collapse ── */
    function toggleItem(toggle) {
        const item = toggle.closest('.tl-item');
        const expanded = item.classList.toggle('expanded');
        toggle.textContent = expanded ? 'Read less ↑' : 'Read more ↓';
    }

    /* ── Stat counter animation ── */
    const statObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const el     = entry.target;
            const target = parseInt(el.dataset.target, 10);
            const suffix = el.dataset.suffix || '';
            const start  = Date.now();
            const dur    = 1200;

            el.classList.add('counted');

            (function tick() {
                const elapsed = Date.now() - start;
                const progress = Math.min(elapsed / dur, 1);
                const eased = 1 - Math.pow(1 - progress, 3); // ease-out-cubic
                const current = Math.round(eased * target);
                el.textContent = current + suffix;
                if (progress < 1) requestAnimationFrame(tick);
            })();

            statObserver.unobserve(el);
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('.stat-number[data-target]').forEach(el => {
        statObserver.observe(el);
    });
    </script>

</x-public-layout>