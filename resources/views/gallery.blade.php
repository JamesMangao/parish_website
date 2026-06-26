<x-public-layout>
<x-slot name="meta">
    <meta name="description" content="Explore photo and video highlights from the Sto. Rosario Parish community in our official gallery.">
    <style>
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



        @keyframes shimmer {
            0%   { background-position: -200% center; }
            100% { background-position:  200% center; }
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.4; transform: scale(0.75); }
        }

        /* Gallery-specific */
        .album-card-overlay {
            background: linear-gradient(to top,
                rgba(8,20,45,0.92) 0%,
                rgba(8,20,45,0.25) 60%,
                transparent 100%);
        }
        .recent-photo { filter: grayscale(0.6); transition: all 0.7s ease; }
        .recent-photo:hover { filter: grayscale(0); transform: scale(1.08); }

        .video-description {
            font-size: 1.1rem;
            color: rgba(13,42,82,0.7);
            line-height: 1.8;
            margin-bottom: 2rem;
            white-space: pre-wrap;
            font-weight: 400;
            letter-spacing: 0.01em;
        }

        /* Highlight card — no hover lift on the outer wrapper */
        .highlight-card-outer { transition: none !important; transform: none !important; }
        .highlight-card-outer:hover { transform: none !important; box-shadow: none !important; border-color: rgba(245,197,24,0.18) !important; }

        /* Video Controls */
        .video-btn {
            background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.18);
            color: #fff; display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s ease;
        }
        .video-btn:hover { background: rgba(255,255,255,0.2); transform: translateY(-1px); }
        .video-btn:active { transform: translateY(0); }

        .progress-container {
            height: 4px; background: rgba(255,255,255,0.2); border-radius: 2px;
            position: relative; cursor: pointer; transition: height 0.2s;
        }
        .progress-container:hover { height: 6px; }
        .progress-fill {
            height: 100%; width: 0%; background: linear-gradient(90deg, #FFD740, #F5C518);
            border-radius: 2px; position: relative;
        }
        .progress-handle {
            position: absolute; right: -6px; top: 50%; transform: translateY(-50%);
            width: 12px; height: 12px; background: #F5C518; border-radius: 50%;
            box-shadow: 0 2px 6px rgba(0,0,0,0.4); opacity: 0; transition: opacity 0.2s;
        }
        .progress-container:hover .progress-handle { opacity: 1; }

        /* Lightbox */
        .lightbox-backdrop {
            position: fixed; inset: 0; background: rgba(8,20,45,0.98);
            z-index: 9999; display: none; align-items: center; justify-content: center;
            backdrop-filter: blur(12px); opacity: 0; transition: opacity 0.4s ease;
        }
        .lightbox-backdrop.active { display: flex; opacity: 1; }
        .lightbox-content {
            width: 90%; max-width: 1200px; position: relative;
            transform: scale(0.9); transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .lightbox-backdrop.active .lightbox-content { transform: scale(1); }
    </style>
</x-slot>

{{-- ═══════════════ HERO ═══════════════ --}}
<section style="position:relative; min-height:38svh; display:flex; flex-direction:column;
                align-items:center; justify-content:center; overflow:hidden;
                background:var(--blue-deep);">

    <div style="position:absolute; inset:0; pointer-events:none;
                background:radial-gradient(ellipse 80% 60% at 50% 30%, rgba(26,64,128,0.55) 0%, transparent 70%);"></div>

    <div style="position:absolute; top:0; left:0; right:0; height:1px;
                background:linear-gradient(90deg,transparent,rgba(245,197,24,0.5),transparent);"></div>

    <div class="font-cinzel" style="position:absolute; font-size:360px; color:rgba(255,255,255,0.018);
                line-height:1; pointer-events:none; user-select:none;">✝</div>

    <div style="position:relative; z-index:10; text-align:center; padding:80px 24px 60px;">
        <div class="divider-ornament mb-5">
            <span class="eyebrow" style="color:rgba(245,197,24,0.75);">Capturing Moments</span>
        </div>
        <h1 class="font-heading"
            style="font-size:clamp(2.8rem,7vw,6rem); font-weight:700; font-style:italic;
                   letter-spacing:-0.02em; color:#EBF2FF; line-height:1; margin-bottom:20px;
                   text-shadow:0 4px 48px rgba(0,0,0,0.4);">
            Gallery
        </h1>
        <div style="width:48px; height:1px; margin:0 auto 20px;
                    background:linear-gradient(90deg,transparent,rgba(245,197,24,0.7),transparent);"></div>
        <p style="max-width:500px; margin:0 auto; font-size:0.95rem; font-style:italic;
                  color:rgba(235,242,255,0.5); line-height:1.7;">
            A visual chronicle of our community's journey in faith, service, and celebration.
        </p>
    </div>

    <div style="position:absolute; bottom:0; left:0; right:0; height:1px;
                background:linear-gradient(90deg,transparent,rgba(245,197,24,0.25),transparent);"></div>
</section>

<div class="pb-24">

    @if($albums->isEmpty())
    {{-- ═══════════════ EMPTY STATE ═══════════════ --}}
    <div class="reveal" style="padding:8rem 1rem; text-align:center;">
        <div style="width:80px; height:80px; border-radius:50%; margin:0 auto 1.5rem;
                    background:var(--cream-deep); border:1px solid rgba(26,64,128,0.1);
                    display:flex; align-items:center; justify-content:center;">
            <svg width="32" height="32" fill="none" viewBox="0 0 24 24">
                <rect width="18" height="18" x="3" y="3" rx="2" stroke="#F5C518" stroke-width="1.5"/>
                <circle cx="9" cy="9" r="2" stroke="#F5C518" stroke-width="1.5"/>
                <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" stroke="#F5C518" stroke-width="1.5"/>
            </svg>
        </div>
        <h3 class="font-heading" style="font-size:1.8rem; font-weight:700; font-style:italic;
                   color:var(--blue-deep); margin-bottom:0.75rem;">No Albums Yet</h3>
        <p style="font-size:0.875rem; font-style:italic; color:rgba(13,42,82,0.45);">
            We are currently curating our latest community photos. Please check back soon.
        </p>
    </div>

    @else
    @php
        $featured    = $albums->first();
        $otherAlbums = $albums->slice(1);
        $highlightsVideo = $global_settings['gallery_highlights_video'] ?? null;
    @endphp

    {{-- ═══════════════ PARISH HIGHLIGHTS ═══════════════ --}}
    @if($highlights->isNotEmpty())
    @php $v = $highlights->first(); @endphp
    @php
        $isYT = Str::contains($v->video_url, ['youtube.com', 'youtu.be']);
        if ($isYT) {
            $videoId = Str::contains($v->video_url, 'youtu.be')
                ? Str::afterLast($v->video_url, '/')
                : Str::after(Str::after($v->video_url, 'v='), '&');
            if (Str::contains($videoId, '&')) $videoId = Str::before($videoId, '&');
            if (Str::contains($videoId, '?')) $videoId = Str::before($videoId, '?');
        }
    @endphp

    <section class="parish-highlights reveal" style="padding:5rem 0 0; background:var(--cream, #F7F9FF);">

        {{-- Section eyebrow header --}}
        <div style="text-align:center; margin-bottom:32px; padding:0 1.5rem;">
            <div class="divider-ornament" style="justify-content:center; display:flex; align-items:center; gap:12px; margin-bottom:0;">
                <span style="height:1px; width:48px; display:block; background:linear-gradient(90deg,transparent,rgba(245,197,24,0.5));"></span>
                <span style="font-size:10px; font-weight:700; letter-spacing:0.32em; text-transform:uppercase; color:#F5C518; font-family:'Cinzel',serif;">
                    Parish Highlights
                </span>
                <span style="height:1px; width:48px; display:block; background:linear-gradient(90deg,rgba(245,197,24,0.5),transparent);"></span>
            </div>
        </div>

        {{-- Cinematic video container --}}
        <div class="highlight-cinema" id="highlight-cinema"
             style="position:relative; width:100%; max-width:1200px; margin:0 auto; border-radius:28px; overflow:hidden;
                    box-shadow:0 24px 80px rgba(13,42,82,0.22); background:#000; aspect-ratio:16/9;">

            {{-- THE VIDEO --}}
            @if($isYT)
                <iframe class="highlight-video"
                        src="https://www.youtube.com/embed/{{ $videoId }}?rel=0&modestbranding=1&color=white&enablejsapi=1"
                        style="width:100%; height:100%; border:0; display:block; position:relative; z-index:1;"
                        frameborder="0" allowfullscreen allow="autoplay; encrypted-media"></iframe>
            @else
                <video id="highlight-vid" class="highlight-video"
                       src="{{ $v->video_url }}"
                       playsinline preload="metadata"
                       onclick="hlTogglePlay()"
                       style="width:100%; height:100%; object-fit:cover; display:block; position:relative; z-index:1; cursor:pointer;">
                </video>
            @endif

            {{-- BOTTOM GRADIENT OVERLAY --}}
            <div style="position:absolute; inset:0; z-index:2; pointer-events:none;
                        background:linear-gradient(to bottom, transparent 0%, transparent 30%, rgba(13,42,82,0.40) 55%, rgba(13,42,82,0.85) 78%, rgba(13,42,82,0.96) 100%);">
            </div>

            {{-- TOP-LEFT BADGE --}}
            <div style="position:absolute; top:24px; left:28px; z-index:4; display:flex; align-items:center; gap:8px;">
                <span style="display:flex; align-items:center; gap:6px;
                             background:rgba(13,42,82,0.60); backdrop-filter:blur(12px);
                             border:1px solid rgba(255,255,255,0.15); border-radius:999px; padding:6px 14px;">
                    <span style="width:7px; height:7px; border-radius:50%; background:#F5C518;
                                 box-shadow:0 0 8px rgba(245,197,24,0.80); display:inline-block;"></span>
                    <span style="font-family:'Jost',sans-serif; font-size:9px; font-weight:700;
                                 letter-spacing:0.28em; text-transform:uppercase; color:#FFFFFF;">
                        Highlight Video
                    </span>
                </span>
            </div>

            {{-- CENTER PLAY BUTTON --}}
            @if(!$isYT)
            <button class="cinema-play-btn" id="hl-center-play" onclick="hlTogglePlay()"
                    style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%);
                           z-index:6; width:72px; height:72px; border-radius:50%; border:none; cursor:pointer;
                           background:rgba(245,197,24,0.92);
                           box-shadow:0 0 0 12px rgba(245,197,24,0.18), 0 8px 32px rgba(245,197,24,0.40);
                           display:flex; align-items:center; justify-content:center;
                           transition:all 0.25s ease;"
                    aria-label="Play highlight video">
                <svg id="hl-center-play-icon" width="22" height="22" viewBox="0 0 24 24" fill="#0D2A52" aria-hidden="true">
                    <polygon points="5,3 19,12 5,21"/>
                </svg>
                <svg id="hl-center-pause-icon" width="22" height="22" viewBox="0 0 24 24" fill="#0D2A52" aria-hidden="true" style="display:none;">
                    <rect x="5" y="3" width="4" height="18"/><rect x="15" y="3" width="4" height="18"/>
                </svg>
            </button>
            @endif

            {{-- BOTTOM OVERLAY TEXT CONTENT --}}
            <div id="hl-info-bar" class="cinema-overlay-text"
                 style="position:absolute; bottom:0; left:0; right:0; z-index:3; padding:40px 44px 36px;
                        transition:transform 0.3s cubic-bezier(0.22,1,0.36,1), padding 0.3s ease;
                        will-change:transform;">
                <div style="max-width:720px;">
                    <p style="font-family:'Jost',sans-serif; font-size:10px; font-weight:700;
                              letter-spacing:0.32em; text-transform:uppercase;
                              color:rgba(245,197,24,0.85); margin-bottom:10px;">
                        Featured Highlight
                    </p>
                    <h2 style="font-family:'Cormorant Garamond',Georgia,serif; font-style:italic; font-weight:700;
                               font-size:clamp(1.8rem, 3.5vw, 3rem); color:#FFFFFF; line-height:1.05;
                               letter-spacing:-0.01em; text-shadow:0 2px 20px rgba(0,0,0,0.40); margin-bottom:10px;">
                        @php
                            $cleanVideoTitle = preg_replace('/[\x{1D400}-\x{1D7FF}]/u', '', $v->title ?? 'Parish Highlight');
                        @endphp
                        {{ trim($cleanVideoTitle) }}
                    </h2>
                    <span style="display:block; width:56px; height:3px; background:linear-gradient(90deg,#FFD740,#F5C518);
                                 border-radius:999px; margin-bottom:14px;"></span>
                    @if($v->event_date ?? null)
                    <p style="font-family:'Jost',sans-serif; font-size:11px; font-weight:600;
                              letter-spacing:0.18em; text-transform:uppercase;
                              color:rgba(255,255,255,0.55); margin-bottom:10px;">
                        {{ $v->event_date }}
                    </p>
                    @endif
                    @if($v->description ?? null)
                    @php
                        $videoDesc = preg_replace('/^(NASA LARAWAN:|IN PHOTOS:|TINGNAN:|IN VIDEO:|WATCH:)\s*/iu', '', $v->description);
                        $videoDesc = preg_replace('/#\w+/u', '', $videoDesc);
                        $videoDesc = trim(preg_replace('/\s+/', ' ', $videoDesc));
                    @endphp
                    <p style="font-family:'Cormorant Garamond',Georgia,serif; font-style:italic;
                              font-size:clamp(0.95rem, 1.5vw, 1.1rem); color:rgba(255,255,255,0.68);
                              line-height:1.7; max-width:580px; margin-top:6px;
                              display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                        {{ $videoDesc }}
                    </p>
                    @endif
                </div>
            </div>

            {{-- ═══ VIDEO CONTROL BAR (local videos only) ═══ --}}
            @if(!$isYT)
            <div id="hl-controls" class="hl-controls"
                 style="position:absolute; bottom:0; left:0; right:0; z-index:8;
                        padding:0 20px 14px; pointer-events:none;
                        transition:opacity 0.35s ease, transform 0.35s ease;
                        opacity:1; transform:translateY(0);">

                {{-- Progress / seek bar --}}
                <div class="hl-progress-wrap" onclick="hlSeek(event)" style="pointer-events:auto; cursor:pointer;
                     margin-bottom:10px; padding:6px 0;">
                    <div class="hl-progress-track">
                        <div id="hl-prog-buffered" class="hl-progress-buffered"></div>
                        <div id="hl-prog-fill" class="hl-progress-fill">
                            <div id="hl-prog-handle" class="hl-progress-handle"></div>
                        </div>
                    </div>
                </div>

                {{-- Controls row --}}
                <div style="display:flex; align-items:center; justify-content:space-between; pointer-events:auto;">

                    {{-- Left controls --}}
                    <div style="display:flex; align-items:center; gap:6px;">

                        {{-- Play / Pause --}}
                        <button id="hl-play-btn" class="hl-ctrl-btn" onclick="hlTogglePlay()" aria-label="Play">
                            <svg id="hl-play-icon" width="16" height="16" viewBox="0 0 24 24" fill="#fff"><polygon points="6,3 20,12 6,21"/></svg>
                            <svg id="hl-pause-icon" width="16" height="16" viewBox="0 0 24 24" fill="#fff" style="display:none;"><rect x="5" y="3" width="4" height="18"/><rect x="15" y="3" width="4" height="18"/></svg>
                        </button>

                        {{-- Skip back 10s --}}
                        <button class="hl-ctrl-btn" onclick="hlSkip(-10)" aria-label="Skip back 10 seconds" title="Back 10s">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round">
                                <path d="M12.5 8.5l-4 3.5 4 3.5"/><path d="M2 12a10 10 0 1 1 2.5 6.6"/>
                                <text x="8" y="13.5" fill="#fff" stroke="none" font-size="7" font-family="Jost,sans-serif" font-weight="700">10</text>
                            </svg>
                        </button>

                        {{-- Skip forward 10s --}}
                        <button class="hl-ctrl-btn" onclick="hlSkip(10)" aria-label="Skip forward 10 seconds" title="Forward 10s">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round">
                                <path d="M11.5 8.5l4 3.5-4 3.5"/><path d="M22 12a10 10 0 1 0-2.5 6.6"/>
                                <text x="8" y="13.5" fill="#fff" stroke="none" font-size="7" font-family="Jost,sans-serif" font-weight="700">10</text>
                            </svg>
                        </button>

                        {{-- Volume --}}
                        <div class="hl-volume-group" style="display:flex; align-items:center; gap:4px;">
                            <button id="hl-mute-btn" class="hl-ctrl-btn" onclick="hlToggleMute()" aria-label="Toggle mute">
                                <svg id="hl-vol-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round">
                                    <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" fill="none"/>
                                    <path id="hl-vol-wave1" d="M15.54 8.46a5 5 0 0 1 0 7.07"/>
                                    <path id="hl-vol-wave2" d="M19.07 4.93a10 10 0 0 1 0 14.14" style="display:none;"/>
                                </svg>
                                <svg id="hl-mute-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" style="display:none;">
                                    <line x1="1" y1="1" x2="23" y2="23"/>
                                    <path d="M9 9v6h4l5 5V5l-5 5H9z" fill="none"/>
                                </svg>
                            </button>
                            <input id="hl-volume-slider" type="range" min="0" max="1" step="0.05" value="1"
                                   class="hl-volume-slider" oninput="hlSetVolume(this.value)" aria-label="Volume">
                        </div>

                        {{-- Time --}}
                        <span id="hl-time" style="font-family:'Jost',sans-serif; font-size:11px; font-weight:600;
                             color:rgba(255,255,255,0.75); letter-spacing:0.04em; margin-left:6px; white-space:nowrap; user-select:none;">
                            0:00 / 0:00
                        </span>
                    </div>

                    {{-- Right controls --}}
                    <div style="display:flex; align-items:center; gap:6px;">

                        {{-- Playback speed --}}
                        <div class="hl-speed-group" style="position:relative;">
                            <button id="hl-speed-btn" class="hl-ctrl-btn hl-ctrl-text" onclick="hlToggleSpeedMenu()" aria-label="Playback speed" title="Speed">
                                1x
                            </button>
                            <div id="hl-speed-menu" class="hl-speed-menu" style="display:none;">
                                @foreach([0.5, 0.75, 1, 1.25, 1.5, 2] as $speed)
                                <button class="hl-speed-opt {{ $speed === 1 ? 'active' : '' }}" onclick="hlSetSpeed({{ $speed }}, this)">
                                    {{ $speed === 1 ? 'Normal' : $speed . 'x' }}
                                </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- Captions (placeholder) --}}
                        <button class="hl-ctrl-btn" onclick="hlToggleCaptions()" aria-label="Toggle captions" title="Captions" style="opacity:0.45; pointer-events:none;">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round">
                                <rect x="1" y="4" width="22" height="16" rx="2"/>
                                <text x="12" y="15" text-anchor="middle" fill="#fff" stroke="none" font-size="8" font-family="Jost,sans-serif" font-weight="700">CC</text>
                            </svg>
                        </button>

                        {{-- Picture-in-picture --}}
                        <button class="hl-ctrl-btn" onclick="hlTogglePiP()" aria-label="Picture in picture" title="Picture-in-Picture">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="3" width="20" height="14" rx="2"/>
                                <rect x="11" y="9" width="9" height="7" rx="1" fill="rgba(255,255,255,0.15)"/>
                            </svg>
                        </button>

                        {{-- Fullscreen --}}
                        <button class="hl-ctrl-btn" onclick="hlToggleFullscreen()" aria-label="Toggle fullscreen" title="Fullscreen">
                            <svg id="hl-fs-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/>
                                <line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/>
                            </svg>
                            <svg id="hl-fs-exit-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                                <polyline points="4 14 10 14 10 20"/><polyline points="20 10 14 10 14 4"/>
                                <line x1="14" y1="10" x2="21" y2="3"/><line x1="3" y1="21" x2="10" y2="14"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- Responsive styles --}}
        <style>
            .cinema-play-btn {
                transition: opacity 0.3s ease, transform 0.25s ease, box-shadow 0.25s ease !important;
            }

            /* ── Controls-visible: push overlay text UP ── */
            .highlight-cinema.controls-visible .cinema-overlay-text {
                transform: translateY(-56px);
            }

            /* ── Control bar ── */
            .hl-controls {
                background: linear-gradient(to top, rgba(0,0,0,0.82) 0%, rgba(0,0,0,0.45) 70%, transparent 100%);
            }
            .highlight-cinema.hl-controls-hidden .hl-controls {
                opacity: 0 !important;
                transform: translateY(8px) !important;
                pointer-events: none !important;
            }

            .hl-ctrl-btn {
                width: 34px; height: 34px; border-radius: 8px; border: none; cursor: pointer;
                background: rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: center;
                transition: background 0.2s, transform 0.15s;
            }
            .hl-ctrl-btn:hover { background: rgba(255,255,255,0.18); transform: scale(1.06); }
            .hl-ctrl-btn:active { transform: scale(0.95); }
            .hl-ctrl-text {
                width: auto; padding: 0 10px; font-family: 'Jost', sans-serif;
                font-size: 11px; font-weight: 700; color: #fff; letter-spacing: 0.04em;
            }

            /* ── Progress bar ── */
            .hl-progress-track {
                height: 4px; background: rgba(255,255,255,0.2); border-radius: 2px;
                position: relative; transition: height 0.2s;
            }
            .hl-progress-wrap:hover .hl-progress-track { height: 6px; }
            .hl-progress-buffered {
                position: absolute; top: 0; left: 0; height: 100%; width: 0%;
                background: rgba(255,255,255,0.25); border-radius: 2px;
            }
            .hl-progress-fill {
                position: absolute; top: 0; left: 0; height: 100%; width: 0%;
                background: linear-gradient(90deg, #FFD740, #F5C518); border-radius: 2px;
            }
            .hl-progress-handle {
                position: absolute; right: -6px; top: 50%; transform: translateY(-50%);
                width: 12px; height: 12px; background: #F5C518; border-radius: 50%;
                box-shadow: 0 2px 6px rgba(0,0,0,0.4); opacity: 0; transition: opacity 0.2s;
            }
            .hl-progress-wrap:hover .hl-progress-handle { opacity: 1; }

            /* ── Volume slider ── */
            .hl-volume-slider {
                width: 0; max-width: 0; opacity: 0; overflow: hidden;
                transition: width 0.25s ease, max-width 0.25s ease, opacity 0.25s ease;
                -webkit-appearance: none; appearance: none; height: 4px; border-radius: 2px;
                background: rgba(255,255,255,0.3); cursor: pointer;
            }
            .hl-volume-group:hover .hl-volume-slider {
                width: 70px; max-width: 70px; opacity: 1;
            }
            .hl-volume-slider::-webkit-slider-thumb {
                -webkit-appearance: none; width: 12px; height: 12px; border-radius: 50%;
                background: #F5C518; cursor: pointer; box-shadow: 0 1px 4px rgba(0,0,0,0.3);
            }
            .hl-volume-slider::-moz-range-thumb {
                width: 12px; height: 12px; border-radius: 50%; border: none;
                background: #F5C518; cursor: pointer; box-shadow: 0 1px 4px rgba(0,0,0,0.3);
            }

            /* ── Speed menu ── */
            .hl-speed-menu {
                position: absolute; bottom: 42px; right: 0; min-width: 100px;
                background: rgba(13,42,82,0.95); backdrop-filter: blur(12px);
                border: 1px solid rgba(255,255,255,0.1); border-radius: 10px;
                padding: 6px 0; display: flex; flex-direction: column;
                box-shadow: 0 10px 30px rgba(0,0,0,0.5); z-index: 20;
            }
            .hl-speed-opt {
                background: none; border: none; color: rgba(255,255,255,0.7);
                padding: 7px 16px; text-align: left; font-size: 12px; font-family: 'Jost', sans-serif;
                font-weight: 500; cursor: pointer; transition: background 0.15s, color 0.15s;
            }
            .hl-speed-opt:hover { background: rgba(255,255,255,0.08); color: #fff; }
            .hl-speed-opt.active { color: #F5C518; font-weight: 700; }

            /* ── Mobile ── */
            @media (max-width: 768px) {
                .highlight-cinema {
                    border-radius: 20px !important;
                    margin-left: 16px !important;
                    margin-right: 16px !important;
                }
                .highlight-cinema.controls-visible .cinema-overlay-text {
                    transform: translateY(-48px);
                }
                #hl-info-bar { padding: 24px 20px 20px !important; }
                .hl-ctrl-btn { width: 30px; height: 30px; }
                #hl-time { font-size: 10px; display: none; }
                .hl-volume-group { display: none; }
            }
            @media (max-width: 480px) {
                #hl-controls { padding: 0 10px 10px !important; }
            }
        </style>

        {{-- JavaScript: full video controls with overlay push --}}
        @if(!$isYT)
        <script>
        (function () {
            var vid    = document.getElementById('highlight-vid');
            var cinema = document.getElementById('highlight-cinema');
            if (!vid || !cinema) return;

            var overlay    = cinema.querySelector('.cinema-overlay-text');
            var controls   = document.getElementById('hl-controls');
            var playBtn    = document.getElementById('hl-play-btn');
            var playIcon   = document.getElementById('hl-play-icon');
            var pauseIcon  = document.getElementById('hl-pause-icon');
            var cPlayBtn   = document.getElementById('hl-center-play');
            var cPlayIcon  = document.getElementById('hl-center-play-icon');
            var cPauseIcon = document.getElementById('hl-center-pause-icon');
            var progFill   = document.getElementById('hl-prog-fill');
            var progBuf    = document.getElementById('hl-prog-buffered');
            var timeEl     = document.getElementById('hl-time');
            var volIcon    = document.getElementById('hl-vol-icon');
            var volWave1   = document.getElementById('hl-vol-wave1');
            var volWave2   = document.getElementById('hl-vol-wave2');
            var muteIcon   = document.getElementById('hl-mute-icon');
            var volSlider  = document.getElementById('hl-volume-slider');
            var speedBtn   = document.getElementById('hl-speed-btn');
            var speedMenu  = document.getElementById('hl-speed-menu');
            var fsIcon     = document.getElementById('hl-fs-icon');
            var fsExitIcon = document.getElementById('hl-fs-exit-icon');

            var hideTimer    = null;
            var controlsTimer = null;

            function fmt(s) {
                if (!s || isNaN(s)) return '0:00';
                var m = Math.floor(s / 60);
                var sec = Math.floor(s % 60);
                return m + ':' + (sec < 10 ? '0' : '') + sec;
            }

            // ── Controls visibility: push overlay text UP ──
            var mouseOverCinema = false;

            function showControls() {
                cinema.classList.add('controls-visible');
                clearTimeout(controlsTimer);
                if (mouseOverCinema) {
                    controlsTimer = setTimeout(hideControls, 3000);
                }
            }
            function hideControls() {
                clearTimeout(controlsTimer);
                if (!vid.paused) {
                    cinema.classList.remove('controls-visible');
                }
            }

            cinema.addEventListener('mousemove',  showControls);
            cinema.addEventListener('touchstart', showControls, { passive: true });
            cinema.addEventListener('mouseenter', function () {
                mouseOverCinema = true;
                if (!vid.paused) showControls();
            });
            cinema.addEventListener('mouseleave', function () {
                mouseOverCinema = false;
                hideControls();
            });

            /* ── Play / Pause ── */
            window.hlTogglePlay = function () {
                if (vid.paused) vid.play(); else vid.pause();
            };

            vid.addEventListener('play', function () {
                playIcon.style.display  = 'none';
                pauseIcon.style.display = 'block';
                if (cPlayBtn) { cPlayBtn.style.opacity = '0'; cPlayBtn.style.pointerEvents = 'none'; }
                if (cPlayIcon) cPlayIcon.style.display = 'none';
                if (cPauseIcon) cPauseIcon.style.display = 'block';
                cinema.classList.add('hl-playing');
                showControls();
            });

            vid.addEventListener('pause', function () {
                playIcon.style.display  = 'block';
                pauseIcon.style.display = 'none';
                if (cPlayBtn) { cPlayBtn.style.opacity = '1'; cPlayBtn.style.pointerEvents = 'auto'; }
                if (cPlayIcon) cPlayIcon.style.display = 'block';
                if (cPauseIcon) cPauseIcon.style.display = 'none';
                cinema.classList.remove('hl-playing');
                cinema.classList.add('controls-visible');
                clearTimeout(controlsTimer);
            });

            vid.addEventListener('ended', function () {
                playIcon.style.display  = 'block';
                pauseIcon.style.display = 'none';
                if (cPlayBtn) { cPlayBtn.style.opacity = '1'; cPlayBtn.style.pointerEvents = 'auto'; }
                if (cPlayIcon) cPlayIcon.style.display = 'block';
                if (cPauseIcon) cPauseIcon.style.display = 'none';
                cinema.classList.remove('hl-playing');
                cinema.classList.add('controls-visible');
                vid.currentTime = 0;
            });

            /* ── Time / Progress ── */
            vid.addEventListener('timeupdate', function () {
                if (!vid.duration) return;
                var pct = (vid.currentTime / vid.duration) * 100;
                progFill.style.width = pct + '%';
                timeEl.textContent = fmt(vid.currentTime) + ' / ' + fmt(vid.duration);
            });

            vid.addEventListener('progress', function () {
                if (!vid.duration || !vid.buffered.length) return;
                var buf = (vid.buffered.end(vid.buffered.length - 1) / vid.duration) * 100;
                progBuf.style.width = buf + '%';
            });

            vid.addEventListener('loadedmetadata', function () {
                timeEl.textContent = fmt(0) + ' / ' + fmt(vid.duration);
            });

            /* ── Seek ── */
            window.hlSeek = function (e) {
                var rect = e.currentTarget.getBoundingClientRect();
                vid.currentTime = ((e.clientX - rect.left) / rect.width) * vid.duration;
            };

            /* ── Skip ±10s ── */
            window.hlSkip = function (sec) {
                vid.currentTime = Math.max(0, Math.min(vid.duration || 0, vid.currentTime + sec));
            };

            /* ── Volume ── */
            window.hlToggleMute = function () {
                vid.muted = !vid.muted;
                updateVolUI();
            };
            window.hlSetVolume = function (val) {
                vid.volume = parseFloat(val);
                vid.muted = (vid.volume === 0);
                updateVolUI();
            };
            function updateVolUI() {
                if (vid.muted || vid.volume === 0) {
                    volIcon.style.display = 'none'; muteIcon.style.display = 'block';
                    volWave1.style.display = 'none'; volWave2.style.display = 'none';
                } else {
                    volIcon.style.display = 'block'; muteIcon.style.display = 'none';
                    volWave1.style.display = '';
                    volWave2.style.display = vid.volume > 0.5 ? '' : 'none';
                }
                volSlider.value = vid.muted ? 0 : vid.volume;
            }

            /* ── Speed ── */
            window.hlToggleSpeedMenu = function () {
                speedMenu.style.display = speedMenu.style.display === 'flex' ? 'none' : 'flex';
            };
            window.hlSetSpeed = function (s, el) {
                vid.playbackRate = s;
                speedBtn.textContent = s === 1 ? '1x' : s + 'x';
                speedMenu.querySelectorAll('.hl-speed-opt').forEach(function (b) { b.classList.remove('active'); });
                el.classList.add('active');
                speedMenu.style.display = 'none';
            };
            document.addEventListener('click', function (e) {
                if (!speedBtn.contains(e.target) && !speedMenu.contains(e.target)) {
                    speedMenu.style.display = 'none';
                }
            });

            /* ── Fullscreen ── */
            window.hlToggleFullscreen = function () {
                if (document.fullscreenElement) {
                    document.exitFullscreen();
                } else {
                    cinema.requestFullscreen();
                }
            };
            document.addEventListener('fullscreenchange', function () {
                var isFs = !!document.fullscreenElement;
                fsIcon.style.display     = isFs ? 'none' : '';
                fsExitIcon.style.display = isFs ? '' : 'none';
                if (!isFs) {
                    if (vid.paused) {
                        cinema.classList.add('controls-visible');
                    } else {
                        showControls();
                    }
                }
            });

            /* ── Picture-in-Picture ── */
            window.hlTogglePiP = async function () {
                try {
                    if (document.pictureInPictureElement) {
                        await document.exitPictureInPicture();
                    } else if (document.pictureInPictureEnabled) {
                        await vid.requestPictureInPicture();
                    }
                } catch (err) {}
            };

            /* ── Captions placeholder ── */
            window.hlToggleCaptions = function () {};

            /* ── Keyboard shortcuts ── */
            document.addEventListener('keydown', function (e) {
                if (['INPUT','TEXTAREA','SELECT'].includes(document.activeElement.tagName)) return;
                if (!document.contains(vid)) return;
                switch (e.key) {
                    case ' ': case 'k': e.preventDefault(); hlTogglePlay(); break;
                    case 'ArrowLeft':  e.preventDefault(); hlSkip(-5); showControls(); break;
                    case 'ArrowRight': e.preventDefault(); hlSkip(5);  showControls(); break;
                    case 'f': e.preventDefault(); hlToggleFullscreen(); break;
                    case 'm': e.preventDefault(); hlToggleMute(); break;
                    case ',': e.preventDefault(); hlSkip(-10); showControls(); break;
                    case '.': e.preventDefault(); hlSkip(10);  showControls(); break;
                }
            });

            /* ── Double-click fullscreen ── */
            vid.addEventListener('dblclick', function (e) {
                e.preventDefault();
                hlToggleFullscreen();
            });

            /* ── Initial state: show controls (video is paused) ── */
            cinema.classList.add('controls-visible');
        })();
        </script>
        @endif

    </section>
    @endif

    {{-- ═══════════════ FEATURED ALBUM ═══════════════ --}}
    <section class="reveal" style="padding:4rem 1.5rem 0; max-width:1100px; margin:0 auto 5rem;">
        <div class="divider-ornament mb-8" style="justify-content:flex-start;">
            <span class="eyebrow">Featured Album</span>
            <div style="flex:1; height:1px; background:linear-gradient(90deg,rgba(245,197,24,0.3),transparent);"></div>
        </div>

        <a href="{{ route('gallery.album', $featured) }}"
           class="group"
           style="display:block; position:relative; border-radius:20px; overflow:hidden;
                  height:480px; border:1px solid rgba(26,64,128,0.12);
                  box-shadow:0 12px 50px rgba(13,42,82,0.12); text-decoration:none;">

            @if($featured->images->count() > 0)
                @php $firstItem = $featured->images->first(); @endphp
                <img src="{{ $firstItem->type === 'video' ? 'https://images.pexels.com/photos/1117132/pexels-photo-1117132.jpeg' : $firstItem->url }}" alt="{{ $featured->title }}"
                     style="width:100%; height:100%; object-fit:cover; transition:transform 3s ease;"
                     class="group-hover:scale-105">
                @if($firstItem->type === 'video' || $featured->images->where('type', 'video')->count() > 0)
                    <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; z-index:5;">
                        <div style="width:72px; height:72px; border-radius:50%; background:rgba(245,197,24,0.9); display:flex; align-items:center; justify-content:center; box-shadow:0 12px 32px rgba(0,0,0,0.4);">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="#0D2A52"><path d="M8 5v14l11-7z"/></svg>
                        </div>
                    </div>
                @endif
            @else
                <div style="width:100%; height:100%; background:var(--cream-deep);"></div>
            @endif

            <div class="album-card-overlay"
                 style="position:absolute; inset:0; display:flex; flex-direction:column;
                        justify-content:flex-end; padding:2.5rem;">
                <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1rem;">
                    <span class="gold-btn"
                          style="font-size:10px; font-weight:700; letter-spacing:0.25em;
                                 text-transform:uppercase; padding:8px 18px; border-radius:100px;">
                        Featured
                    </span>
                    <span style="font-size:10px; font-weight:700; letter-spacing:0.25em;
                                 text-transform:uppercase; padding:8px 18px; border-radius:100px;
                                 color:rgba(235,242,255,0.55); border:1px solid rgba(235,242,255,0.2);">
                        {{ $featured->images_count }} Items
                    </span>
                </div>
                <h2 class="font-heading group-hover:text-[#F5C518]"
                    style="font-size:clamp(2rem,4vw,3.5rem); font-weight:700; font-style:italic;
                           color:#EBF2FF; letter-spacing:-0.01em; margin-bottom:0.75rem;
                           transition:color 0.3s; line-height:1.1;">
                    {{ $featured->title }}
                </h2>
                <p style="font-size:0.875rem; font-style:italic; max-width:600px;
                          color:rgba(235,242,255,0.5); line-height:1.6;
                          display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                    @php
                        $cleanDesc = preg_replace(
                            '/^(NASA LARAWAN:|IN PHOTOS:|TINGNAN:|IN VIDEO:|WATCH:)\s*/i',
                            '',
                            $featured->description ?? ''
                        );
                        $cleanDesc = preg_replace('/#\w+/u', '', $cleanDesc);
                        $cleanDesc = trim($cleanDesc);
                    @endphp
                    {{ Str::limit($cleanDesc, 160) }}
                </p>
            </div>
        </a>
    </section>

    {{-- ═══════════════ MORE GALLERIES ═══════════════ --}}
    @if($otherAlbums->isNotEmpty())
    <section class="reveal" style="padding:0 1.5rem; max-width:1100px; margin:0 auto 5rem;">
        <div class="divider-ornament mb-12" style="justify-content:flex-start; gap:16px;">
            <h2 class="font-heading"
                style="font-size:2rem; font-weight:700; font-style:italic;
                       color:var(--blue-deep); white-space:nowrap;">More Galleries</h2>
            <div style="flex:1; height:1px; background:linear-gradient(90deg,rgba(26,64,128,0.12),transparent);"></div>
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(300px,1fr)); gap:2rem;">
            @foreach($otherAlbums as $album)
            <a href="{{ route('gallery.album', $album) }}"
               class="group card-sacred"
               style="display:flex; flex-direction:column; text-decoration:none; overflow:hidden;">

                <div style="position:relative; aspect-ratio:4/3; overflow:hidden; background:var(--cream-deep);">
                    @if($album->images->count() > 0)
                        @php $thumb = $album->images->first(); @endphp
                        <div x-data="{ loaded: false }" class="relative w-full h-full">
                            <div x-show="!loaded" class="absolute inset-0 skeleton z-10"></div>
                            <img src="{{ $thumb->type === 'video' ? 'https://images.pexels.com/photos/1117132/pexels-photo-1117132.jpeg' : $thumb->url }}"
                                 alt="{{ $album->title }}"
                                 width="400" height="300"
                                 style="width:100%; height:100%; object-fit:cover; transition:transform 0.7s ease;"
                                 class="group-hover:scale-110" loading="lazy"
                                 @load="loaded = true">
                        </div>
                        @if($thumb->type === 'video' || $album->images->where('type', 'video')->count() > 0)
                            <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; z-index:5;">
                                <div style="width:40px; height:40px; border-radius:50%; background:rgba(245,197,24,0.85); display:flex; align-items:center; justify-content:center; box-shadow:0 8px 20px rgba(0,0,0,0.3);">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="#0D2A52"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                        @endif
                    @endif
                    <div style="position:absolute; inset:0; background:rgba(13,42,82,0.15); transition:background 0.3s;"
                         class="group-hover:bg-[rgba(13,42,82,0.05)]"></div>
                    <div style="position:absolute; top:12px; right:12px;
                                font-size:9px; font-weight:700; letter-spacing:0.2em;
                                text-transform:uppercase; padding:4px 10px; border-radius:100px;
                                background:rgba(255,255,255,0.92); color:var(--blue-deep);">
                        {{ $album->images_count }} Items
                    </div>
                </div>

                <div style="padding:1.25rem 1.25rem 1.5rem;">
                    <h3 class="font-heading group-hover:text-[#F5C518]"
                        style="font-size:1.25rem; font-weight:700; font-style:italic;
                               color:var(--blue-deep); margin-bottom:0.35rem; transition:color 0.25s;">
                        {{ $album->title }}
                    </h3>
                    <p style="font-size:0.8125rem; font-style:italic; color:rgba(13,42,82,0.45);
                              display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;
                              overflow:hidden; line-height:1.6;">
                        {{ $album->description }}
                    </p>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- ═══════════════ RECENT HIGHLIGHTS ═══════════════ --}}
    @if($latestItems->isNotEmpty())
    <section class="reveal" style="background:var(--blue-deep); padding:5rem 1.5rem;
                                     margin-bottom:5rem; position:relative; overflow:hidden;">

        <div style="position:absolute; top:0; left:0; right:0; height:1px;
                    background:linear-gradient(90deg,transparent,rgba(245,197,24,0.35),transparent);"></div>
        <div style="position:absolute; bottom:0; left:0; right:0; height:1px;
                    background:linear-gradient(90deg,transparent,rgba(245,197,24,0.2),transparent);"></div>

        <div class="font-cinzel" style="position:absolute; font-size:320px; color:rgba(255,255,255,0.018);
                    top:50%; left:50%; transform:translate(-50%,-50%);
                    pointer-events:none; user-select:none; line-height:1;">✝</div>

        <div style="max-width:1100px; margin:0 auto; position:relative; z-index:1;">
            <div class="text-center mb-12">
                <div class="divider-ornament mb-3">
                    <span class="eyebrow" style="color:rgba(245,197,24,0.75);">Latest</span>
                </div>
                <h2 class="font-heading"
                    style="font-size:2.25rem; font-weight:700; font-style:italic;
                           color:var(--blue-pale); letter-spacing:-0.01em;">
                    Recent Highlights
                </h2>
            </div>

            <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(150px,1fr)); gap:0.75rem;">
                @foreach($latestItems->take(6) as $item)
                <a href="{{ route('gallery.album', $item->album_id) }}"
                   class="highlight-thumb"
                   style="position:relative; aspect-ratio:4/3; border-radius:12px; overflow:hidden;
                          display:block; background:rgba(255,255,255,0.05);
                          border:1px solid rgba(255,255,255,0.06); cursor:pointer;">

                    @if($item->type === 'video')
                        <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; z-index:5;">
                            <div style="width:32px; height:32px; border-radius:50%; background:rgba(245,197,24,0.8); display:flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(0,0,0,0.3);">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="#0D2A52"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </div>
                    @endif

                    <div x-data="{ loaded: false }" class="relative w-full h-full">
                        <div x-show="!loaded" class="absolute inset-0 skeleton-dark z-10"></div>
                        <img src="{{ $item->type === 'video' ? 'https://images.pexels.com/photos/1117132/pexels-photo-1117132.jpeg' : $item->url }}"
                             alt="{{ $item->album->title ?? 'Recent highlight' }}"
                             style="width:100%; height:100%; object-fit:cover; object-position:center;
                                    transition:transform 0.4s cubic-bezier(0.22,1,0.36,1);"
                             loading="lazy"
                             @load="loaded = true">
                    </div>

                    {{-- Hover overlay --}}
                    <div style="position:absolute; inset:0;
                                background:rgba(13,42,82,0.25); opacity:0;
                                transition:opacity 0.3s ease; border-radius:inherit; pointer-events:none;"></div>

                    {{-- Caption overlay --}}
                    <div class="thumb-caption"
                         style="position:absolute; bottom:0; left:0; right:0;
                                padding:28px 12px 10px;
                                background:linear-gradient(to top, rgba(13,42,82,0.75), transparent);
                                color:#FFFFFF; font-family:'Jost',sans-serif;
                                font-size:10px; font-weight:600; letter-spacing:0.15em;
                                text-transform:uppercase; border-radius:0 0 12px 12px;
                                opacity:0; transition:opacity 0.3s ease; z-index:3;">
                        <span>{{ Str::limit(strip_tags($item->album->title ?? ''), 40) }}</span>
                    </div>
                </a>
                @endforeach
            </div>

            {{-- View All link --}}
            <div style="text-align:center; margin-top:28px;">
                <a href="{{ route('gallery.index') }}"
                   style="display:inline-flex; align-items:center; gap:8px;
                          height:44px; padding:0 28px; border-radius:999px;
                          border:1.5px solid rgba(255,255,255,0.25);
                          color:rgba(255,255,255,0.70);
                          font-family:'Jost',sans-serif;
                          font-size:10px; font-weight:700;
                          letter-spacing:0.25em; text-transform:uppercase;
                          text-decoration:none; transition:all 0.2s ease;"
                   onmouseover="this.style.background='rgba(255,255,255,0.10)'; this.style.borderColor='rgba(255,255,255,0.45)'; this.style.color='#FFFFFF';"
                   onmouseout="this.style.background='transparent'; this.style.borderColor='rgba(255,255,255,0.25)'; this.style.color='rgba(255,255,255,0.70)';">
                    View All Galleries
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2.5"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>
    @endif
    @endif

    {{-- ═══════════════ STAY CONNECTED / SOCIAL FEED ═══════════════ --}}
    <section class="social-feed-section reveal" style="background:var(--cream, #F7F9FF); position:relative; padding-top:64px; padding-bottom:80px;">
        {{-- Background cross motif --}}
        <div class="social-feed-bg-cross" aria-hidden="true">✝</div>
        {{-- Gold radial glow --}}
        <div class="social-feed-glow"></div>

        <div style="max-width:1100px; margin:0 auto; position:relative; z-index:1;">

            {{-- Section Header --}}
            <div style="text-align:center; margin-bottom:3rem;">
                <div class="divider-ornament mb-5">
                    <span class="eyebrow">Social Feed</span>
                </div>
                <h2 class="font-heading"
                    style="font-size:clamp(2.2rem,4vw,3.6rem); font-weight:700; font-style:italic;
                           color:#0D2A52; letter-spacing:-0.01em; margin-bottom:1rem;">
                    Stay Connected
                </h2>
                <p class="font-heading" style="font-size:1.1rem; font-style:italic;
                   color:rgba(13,42,82,0.48); max-width:520px; margin:0 auto; line-height:1.7;">
                    Follow our latest updates and community announcements live from Facebook.
                </p>
            </div>

            {{-- Facebook Embed Wrapper --}}
            <div class="fb-embed-wrapper" style="margin-bottom:2.5rem;">
                <div class="fb-embed-card" style="display:flex; justify-content:center; align-items:flex-start;">
                    {{-- Shimmer loader --}}
                    <div class="fb-shimmer"></div>

                    {{-- Facebook Page Plugin --}}
                    <div id="fb-root"></div>
                    <!-- 
                        TODO: Once Meta rate limit clears, register domain at 
                        developers.facebook.com → Settings → Basic → App Domains
                        and add appId parameter to the SDK script src for 
                        better reliability.
                    -->
                    <script async defer crossorigin="anonymous"
                            src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v19.0"></script>

                    <div class="fb-page"
                         data-href="https://www.facebook.com/storosarioparishpacita1"
                         data-tabs="timeline"
                         data-width="500"
                         data-height="700"
                         data-small-header="false"
                         data-adapt-container-width="true"
                         data-hide-cover="false"
                         data-show-facepile="true"
                         style="position:relative; z-index:1; margin:0 auto !important;">
                    </div>
                </div>
            </div>

            {{-- CTA Buttons --}}
            <div class="social-feed-cta-row" style="gap:0.75rem;">
                <a href="https://www.facebook.com/storosarioparishpacita1" target="_blank" rel="noopener noreferrer"
                   class="social-feed-cta-gold">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    Follow on Facebook
                </a>
                <a href="{{ route('inquiry') }}" class="social-feed-cta-ghost"
                   style="border:1.5px solid rgba(13,42,82,0.30); color:rgba(13,42,82,0.65);"
                   onmouseover="this.style.background='rgba(13,42,82,0.06)'; this.style.borderColor='rgba(13,42,82,0.50)'; this.style.color='rgba(13,42,82,0.85)';"
                   onmouseout="this.style.background='transparent'; this.style.borderColor='rgba(13,42,82,0.30)'; this.style.color='rgba(13,42,82,0.65)';">
                    Contact the Office
                </a>
            </div>

        </div>

        {{-- Responsive width fix + shimmer dismiss --}}
        <script>
        function resizeFbPlugin() {
            var wrapper = document.querySelector('.fb-embed-wrapper');
            var fbEl = document.querySelector('.fb-page');
            if (!wrapper || !fbEl) return;
            var w = Math.min(wrapper.offsetWidth - 48, 500);
            if (w < 280) w = 280;
            fbEl.setAttribute('data-width', w);
            if (window.FB) FB.XFBML.parse();
        }
        window.addEventListener('resize', resizeFbPlugin);
        window.fbAsyncInit = function() {
            FB.init({ version: 'v19.0', xfbml: true });
            FB.Event.subscribe('xfbml.render', function() {
                var shimmer = document.querySelector('.fb-shimmer');
                if (shimmer) shimmer.style.display = 'none';
            });
            resizeFbPlugin();
        };
        </script>
    </section>

</div>

{{-- ═══════════════ CINEMATIC LIGHTBOX ═══════════════ --}}
<div id="video-lightbox" class="lightbox-backdrop" onclick="closeCinematicLightbox(event)">
    <div class="lightbox-content" onclick="event.stopPropagation()">
        <button class="video-btn"
                style="position:absolute; top:-50px; right:0; width:40px; height:40px; border-radius:50%; background:rgba(255,255,255,0.15);"
                onclick="closeCinematicLightbox()">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
            </svg>
        </button>
        <div class="card-sacred overflow-hidden" style="background:#0a0a0a; border:1px solid rgba(255,255,255,0.1);">
            <div style="position:relative; aspect-ratio:16/9; background:#000;">
                <video id="lightbox-video" style="width:100%; height:100%;" controls preload="auto"></video>
            </div>
            <div style="padding:2rem; background:var(--blue-deep);">
                <h3 id="lightbox-title" class="font-heading" style="font-size:1.75rem; color:#fff; margin-bottom:0.5rem; font-style:italic;">Video Title</h3>
                <p id="lightbox-desc" style="color:rgba(255,255,255,0.5); font-size:0.9rem; line-height:1.6; font-style:italic;">Video Description</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const observer = new IntersectionObserver(
        entries => entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('active'); observer.unobserve(e.target); } }),
        { threshold: 0.12, rootMargin: '0px 0px -50px 0px' }
    );
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
});

/* Video Controls */
let lastActiveVideoIndex = 0;

function toggleSettingsMenu(index) {
    const menu = document.getElementById(`settings-${index}`);
    menu.style.display = menu.style.display === 'flex' ? 'none' : 'flex';
    const closeMenu = (e) => {
        if (!menu.contains(e.target) && !e.target.closest('button[title="More Options"]')) {
            menu.style.display = 'none';
            document.removeEventListener('click', closeMenu);
        }
    };
    if (menu.style.display === 'flex') setTimeout(() => document.addEventListener('click', closeMenu), 1);
}

function setVideoSpeed(index, speed, btn) {
    const vid = document.getElementById(`hv-${index}`);
    const menu = document.getElementById(`settings-${index}`);
    vid.playbackRate = speed;
    menu.querySelectorAll('button').forEach(b => b.style.color = '#fff');
    btn.style.color = 'var(--gold)';
    menu.style.display = 'none';
}

function skipVideo(index, seconds) {
    const vid = document.getElementById(`hv-${index}`);
    if (!vid) return;
    vid.currentTime = Math.max(0, Math.min(vid.duration, vid.currentTime + seconds));
    updateVideoProgress(index);
}

function toggleVideoPlay(index) {
    lastActiveVideoIndex = index;
    const vid = document.getElementById(`hv-${index}`);
    if (vid.paused) vid.play();
    else vid.pause();
}

function syncVideoUI(index, isPlaying) {
    const overlay = document.getElementById(`play-overlay-${index}`);
    const playIcon = document.getElementById(`play-icon-${index}`);
    const pauseIcon = document.getElementById(`pause-icon-${index}`);
    if (isPlaying) {
        if (overlay) { overlay.style.opacity = '0'; overlay.style.pointerEvents = 'none'; }
        playIcon.style.display = 'none';
        pauseIcon.style.display = 'block';
    } else {
        if (overlay) { overlay.style.opacity = '1'; overlay.style.pointerEvents = 'auto'; }
        playIcon.style.display = 'block';
        pauseIcon.style.display = 'none';
    }
}

function toggleVideoMute(index) {
    const vid = document.getElementById(`hv-${index}`);
    const volIcon = document.getElementById(`vol-icon-${index}`);
    const muteIcon = document.getElementById(`mute-icon-${index}`);
    vid.muted = !vid.muted;
    if (vid.muted) { volIcon.style.display = 'none'; muteIcon.style.display = 'block'; }
    else { volIcon.style.display = 'block'; muteIcon.style.display = 'none'; }
}

function updateVideoProgress(index) {
    const vid = document.getElementById(`hv-${index}`);
    const fill = document.getElementById(`prog-fill-${index}`);
    const curr = document.getElementById(`time-curr-${index}`);
    fill.style.width = `${(vid.currentTime / vid.duration) * 100}%`;
    const mins = Math.floor(vid.currentTime / 60);
    const secs = Math.floor(vid.currentTime % 60).toString().padStart(2, '0');
    curr.innerText = `${mins}:${secs}`;
}

function seekVideo(index, event) {
    const vid = document.getElementById(`hv-${index}`);
    const rect = event.currentTarget.getBoundingClientRect();
    vid.currentTime = ((event.clientX - rect.left) / rect.width) * vid.duration;
}

function onVideoEnded(index) {
    const overlay = document.getElementById(`play-overlay-${index}`);
    if (overlay) { overlay.style.opacity = '1'; overlay.style.pointerEvents = 'auto'; }
    document.getElementById(`play-icon-${index}`).style.display = 'block';
    document.getElementById(`pause-icon-${index}`).style.display = 'none';
}


function toggleFullscreen(id) {
    const vid = document.getElementById(id);
    if (vid.requestFullscreen) vid.requestFullscreen();
    else if (vid.webkitRequestFullscreen) vid.webkitRequestFullscreen();
    else if (vid.msRequestFullscreen) vid.msRequestFullscreen();
}

function openCinematicLightbox(url, title, desc) {
    const modal = document.getElementById('video-lightbox');
    const vid = document.getElementById('lightbox-video');
    document.getElementById('lightbox-title').innerText = title;
    document.getElementById('lightbox-desc').innerText = desc || '';
    vid.src = url;
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    vid.play();
}

function closeCinematicLightbox(event) {
    const modal = document.getElementById('video-lightbox');
    const vid = document.getElementById('lightbox-video');
    modal.classList.remove('active');
    document.body.style.overflow = '';
    vid.pause();
    vid.src = '';
}

async function shareVideo(title, url) {
    if (navigator.share) {
        try { await navigator.share({ title, url }); } catch (err) {}
    } else {
        navigator.clipboard.writeText(url);
        alert('Video link copied to clipboard!');
    }
}

document.addEventListener('keydown', (e) => {
    if (['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) return;
    if (e.key === 'ArrowRight') { e.preventDefault(); skipVideo(lastActiveVideoIndex, 5); }
    else if (e.key === 'ArrowLeft') { e.preventDefault(); skipVideo(lastActiveVideoIndex, -5); }
    else if (e.key === ' ' || e.key === 'k') { e.preventDefault(); toggleVideoPlay(lastActiveVideoIndex); }
    else if (e.key === 'f') { e.preventDefault(); toggleFullscreen(`hv-${lastActiveVideoIndex}`); }
    else if (e.key === 'm') { e.preventDefault(); toggleVideoMute(lastActiveVideoIndex); }
});
</script>

</x-public-layout>