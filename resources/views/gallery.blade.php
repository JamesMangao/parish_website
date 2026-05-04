<x-public-layout>
<x-slot name="meta">
    <style>
        :root {
            --gold:       #F5C518;
            --gold-light: #FFD740;
            --blue-deep:  #0D2A52;
            --blue-mid:   #1A4080;
            --blue-pale:  #EBF2FF;
            --cream:      #F7F9FF;
            --cream-deep: #EDF2FC;
        }
        body { background: var(--cream); font-family: 'Jost', sans-serif; }
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

        .reveal {
            opacity: 0; transform: translateY(40px);
            transition: all 1.2s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .reveal.active { opacity: 1; transform: translateY(0); }

        @keyframes shimmer {
            0%   { background-position: -200% center; }
            100% { background-position:  200% center; }
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
            font-size: 0.925rem;
            color: rgba(13,42,82,0.7);
            line-height: 1.8;
            margin-bottom: 2rem;
            white-space: pre-wrap;
            font-weight: 400;
            letter-spacing: 0.01em;
        }

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
    </style>
</x-slot>

{{-- ═══════════════ HERO ═══════════════ --}}
<section style="position:relative; min-height:38svh; display:flex; flex-direction:column;
                align-items:center; justify-content:center; overflow:hidden;
                background:var(--blue-deep);">

    {{-- Radial glow --}}
    <div style="position:absolute; inset:0; pointer-events:none;
                background:radial-gradient(ellipse 80% 60% at 50% 30%, rgba(26,64,128,0.55) 0%, transparent 70%);"></div>

    {{-- Gold top rule --}}
    <div style="position:absolute; top:0; left:0; right:0; height:1px;
                background:linear-gradient(90deg,transparent,rgba(245,197,24,0.5),transparent);"></div>

    {{-- Watermark cross --}}
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

    {{-- Gold bottom rule --}}
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

{{-- ═══════════════ PARISH HIGHLIGHTS (CINEMATIC) ═══════════════ --}}
@if($highlights->isNotEmpty())
<section class="reveal" style="padding:4rem 1.5rem 0; max-width:1100px; margin:0 auto 4rem;">
    <div class="divider-ornament mb-10" style="justify-content:flex-start;">
        <span class="eyebrow">Parish Highlights</span>
        <div style="flex:1; height:1px; background:linear-gradient(90deg,rgba(245,197,24,0.35),transparent);"></div>
    </div>

    <div style="display:flex; flex-direction:column; gap:4rem;">
        @foreach($highlights as $v)
        <div id="card-{{ $loop->index }}" class="card-sacred overflow-hidden flex flex-col md:grid md:grid-cols-2"
             style="min-height:340px; background:#fff; border:1px solid rgba(245,197,24,0.18);
                    box-shadow:0 24px 72px rgba(13,42,82,0.14);">

            {{-- LEFT: Video Pane --}}
            <div style="position:relative; background:#0a0a0a; overflow:hidden;">

                {{-- HIGHLIGHT VIDEO badge --}}
                <div style="position:absolute; top:16px; left:16px; z-index:20;
                            display:flex; align-items:center; gap:8px;">
                    <span style="width:8px; height:8px; border-radius:50%;
                                 background:var(--gold); display:inline-block;
                                 box-shadow:0 0 0 3px rgba(245,197,24,0.25);"></span>
                    <span class="font-cinzel"
                          style="font-size:9px; letter-spacing:0.28em; color:#fff;
                                 font-weight:700; text-transform:uppercase;">Highlight Video</span>
                </div>

                {{-- Action icons --}}
                <div style="position:absolute; top:14px; right:14px; z-index:20;
                            display:flex; align-items:center; gap:10px;">
                    <button class="video-btn" style="width:34px; height:34px; border-radius:50%; backdrop-filter:blur(6px);"
                            title="Picture in Picture"
                            onclick="togglePIP('hv-{{ $loop->index }}')">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M8 5h11a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-11a2 2 0 0 1-2-2v-10a2 2 0 0 1 2-2z"/>
                            <rect x="13" y="11" width="7" height="7" rx="1"/>
                        </svg>
                    </button>
                    <button class="video-btn" style="width:34px; height:34px; border-radius:50%; backdrop-filter:blur(6px);"
                            title="Favorite"
                            onclick="this.querySelector('svg').style.fill=(this.querySelector('svg').style.fill==='#F5C518'?'none':'#F5C518')">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </button>
                    <button class="video-btn" style="width:34px; height:34px; border-radius:50%; backdrop-filter:blur(6px);"
                            title="Share"
                            onclick="shareVideo('{{ $v->title }}', '{{ $v->video_url }}')">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/>
                            <circle cx="18" cy="19" r="3"/>
                            <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/>
                            <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
                        </svg>
                    </button>
                </div>

                {{-- Video / iframe --}}
                <div style="position:relative; width:100%; height:100%; min-height:300px;">
                    @if(Str::contains($v->video_url, ['youtube.com', 'youtu.be']))
                        @php
                            $videoId = Str::contains($v->video_url, 'youtu.be')
                                ? Str::afterLast($v->video_url, '/')
                                : Str::after(Str::after($v->video_url, 'v='), '&');
                            if (Str::contains($videoId, '&')) $videoId = Str::before($videoId, '&');
                        @endphp
                        <iframe src="https://www.youtube.com/embed/{{ $videoId }}?rel=0&modestbranding=1"
                                style="position:absolute; inset:0; width:100%; height:100%; border:0;"
                                frameborder="0" allowfullscreen></iframe>
                    @else
                        {{-- Custom video with big gold play btn --}}
                        <video id="hv-{{ $loop->index }}"
                               style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover; cursor:pointer;"
                               preload="metadata"
                               onclick="toggleVideoPlay('{{ $loop->index }}')"
                               onplay="syncVideoUI('{{ $loop->index }}', true)"
                               onpause="syncVideoUI('{{ $loop->index }}', false)"
                               ontimeupdate="updateVideoProgress('{{ $loop->index }}')"
                               onended="onVideoEnded('{{ $loop->index }}')">
                            <source src="{{ $v->video_url }}" type="video/mp4">
                        </video>
                        {{-- Play overlay --}}
                        <div id="play-overlay-{{ $loop->index }}"
                             style="position:absolute; inset:0; display:flex; align-items:center;
                                    justify-content:center; z-index:10; cursor:pointer;
                                    background:rgba(0,0,0,0.25);"
                             onclick="
                                 const vid=document.getElementById('hv-{{ $loop->index }}');
                                 const ov=document.getElementById('play-overlay-{{ $loop->index }}');
                                 if(vid.paused){vid.play();ov.style.opacity='0';ov.style.pointerEvents='none';}
                                 else{vid.pause();ov.style.opacity='1';ov.style.pointerEvents='auto';}
                             ">
                            <div style="width:72px; height:72px; border-radius:50%;
                                        background:linear-gradient(135deg,#FFD740,#F5C518);
                                        display:flex; align-items:center; justify-content:center;
                                        box-shadow:0 8px 40px rgba(245,197,24,0.55), 0 0 0 12px rgba(245,197,24,0.15);
                                        transition:transform 0.2s ease;">
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="#0D2A52">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Faux progress bar --}}
                <div style="position:absolute; bottom:0; left:0; right:0; z-index:15;
                            padding:0 12px 10px; background:linear-gradient(to top, rgba(0,0,0,0.75), transparent);">
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:8px;">
                        <span id="time-curr-{{ $loop->index }}" style="color:#fff; font-size:10px; font-weight:600; letter-spacing:0.05em; white-space:nowrap; min-width:30px;">0:00</span>
                        <div class="progress-container" style="flex:1;" onclick="seekVideo('{{ $loop->index }}', event)">
                            <div id="prog-fill-{{ $loop->index }}" class="progress-fill">
                                <div class="progress-handle"></div>
                            </div>
                        </div>
                        <span style="color:rgba(255,255,255,0.55); font-size:10px;">{{ $v->duration ?? '4:00' }}</span>
                    </div>
                    <div style="display:flex; align-items:center; justify-content:space-between;">
                        {{-- Left controls --}}
                        <div style="display:flex; align-items:center; gap:14px;">
                            <button class="video-btn" style="background:none; border:none; padding:0; width:auto; height:auto;" onclick="toggleVideoPlay('{{ $loop->index }}')">
                                <svg id="play-icon-{{ $loop->index }}" width="14" height="14" viewBox="0 0 24 24" fill="#fff"><path d="M8 5v14l11-7z"/></svg>
                                <svg id="pause-icon-{{ $loop->index }}" width="14" height="14" viewBox="0 0 24 24" fill="#fff" style="display:none;"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
                            </button>
                            {{-- Skip Buttons --}}
                            <button class="video-btn" style="background:none; border:none; padding:0; width:auto; height:auto;" title="Back 5s" onclick="skipVideo('{{ $loop->index }}', -5)">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8">
                                    <path d="M11 17l-5-5 5-5M18 17l-5-5 5-5"/>
                                </svg>
                            </button>
                            <button class="video-btn" style="background:none; border:none; padding:0; width:auto; height:auto;" title="Forward 5s" onclick="skipVideo('{{ $loop->index }}', 5)">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8">
                                    <path d="M13 17l5-5-5-5M6 17l5-5-5-5"/>
                                </svg>
                            </button>
                            <button class="video-btn" style="background:none; border:none; padding:0; width:auto; height:auto;" onclick="toggleVideoMute('{{ $loop->index }}')">
                                <svg id="vol-icon-{{ $loop->index }}" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8">
                                    <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
                                    <path d="M15.54 8.46a5 5 0 0 1 0 7.07"/>
                                </svg>
                                <svg id="mute-icon-{{ $loop->index }}" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8" style="display:none;">
                                    <line x1="1" y1="1" x2="23" y2="23"/><path d="M9 9v6h4l5 5V5l-5 5H9z"/>
                                </svg>
                            </button>
                        </div>
                        {{-- Right controls --}}
                        <div style="display:flex; align-items:center; gap:14px;">
                            <button class="video-btn" style="background:none; border:none; padding:0; width:auto; height:auto;" title="Fullscreen" onclick="toggleFullscreen('hv-{{ $loop->index }}')">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8">
                                    <polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/>
                                    <line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/>
                                </svg>
                            </button>
                            <div style="position:relative;">
                                <button class="video-btn" style="background:none; border:none; padding:0; width:auto; height:auto;" title="More Options" onclick="toggleSettingsMenu('{{ $loop->index }}')">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.8">
                                        <circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/>
                                    </svg>
                                </button>
                                {{-- Settings Menu --}}
                                <div id="settings-{{ $loop->index }}"
                                     style="position:absolute; bottom:30px; right:0; background:rgba(13,42,82,0.95);
                                            backdrop-filter:blur(10px); border-radius:8px; border:1px solid rgba(255,255,255,0.1);
                                            padding:8px 0; display:none; flex-direction:column; min-width:140px; z-index:100;
                                            box-shadow:0 10px 30px rgba(0,0,0,0.5);">
                                    <div style="padding:6px 16px; font-size:9px; color:rgba(255,255,255,0.4); text-transform:uppercase; letter-spacing:0.1em; border-bottom:1px solid rgba(255,255,255,0.05);">Playback Speed</div>
                                    @foreach([0.5, 1, 1.25, 1.5, 2] as $speed)
                                    <button onclick="setVideoSpeed('{{ $loop->parent->index }}', {{ $speed }}, this)"
                                            style="background:none; border:none; color:{{ $speed == 1 ? 'var(--gold)' : '#fff' }};
                                                   padding:8px 16px; text-align:left; font-size:11px; cursor:pointer;
                                                   transition:background 0.2s;"
                                            onmouseover="this.style.background='rgba(255,255,255,0.05)'"
                                            onmouseout="this.style.background='none'">
                                        {{ $speed == 1 ? 'Normal' : $speed.'x' }}
                                    </button>
                                    @endforeach
                                    <div style="padding:6px 16px; font-size:9px; color:rgba(255,255,255,0.4); text-transform:uppercase; letter-spacing:0.1em; border-top:1px solid rgba(255,255,255,0.05); border-bottom:1px solid rgba(255,255,255,0.05);">Quality</div>
                                    <div style="padding:8px 16px; color:#fff; font-size:11px; opacity:0.8;">Auto (HD)</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Info Pane --}}
            <div style="padding:2.5rem 2.25rem; background:#fff; display:flex; flex-direction:column; justify-content:center; text-align:left !important;">

                {{-- Eyebrow --}}
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:1.25rem;">
                    <div style="width:28px; height:2px; background:var(--gold);"></div>
                    <span class="eyebrow" style="color:var(--gold);">Featured Highlight</span>
                </div>

                {{-- Title --}}
                <h3 class="font-heading"
                    style="font-size:clamp(1.6rem,2.8vw,2.25rem); font-weight:700; font-style:italic;
                           color:var(--blue-deep); line-height:1.15; margin-bottom:1rem; letter-spacing:-0.01em;">
                    {{ $v->title }}
                </h3>

                {{-- Gold rule --}}
                <div style="width:40px; height:2px; background:var(--gold); margin-bottom:1rem; border-radius:1px;"></div>

                {{-- Description --}}
                @if($v->description)
                <p class="video-description">{{ trim($v->description) }}</p>
                @endif

                {{-- Meta: Date, Location, Duration, Tags --}}
                <div style="display:flex; flex-direction:column; gap:0.6rem; margin-bottom:2rem;">
                    @if($v->event_date ?? null)
                    <div style="display:flex; align-items:center; gap:10px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(13,42,82,0.4)" stroke-width="1.6">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <span style="font-size:0.8rem; color:rgba(13,42,82,0.6);">{{ $v->event_date }}</span>
                    </div>
                    @endif
                    @if($v->location ?? null)
                    <div style="display:flex; align-items:center; gap:10px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(13,42,82,0.4)" stroke-width="1.6">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        <span style="font-size:0.8rem; color:rgba(13,42,82,0.6);">{{ $v->location }}</span>
                    </div>
                    @endif
                    @if($v->duration ?? null)
                    <div style="display:flex; align-items:center; gap:10px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(13,42,82,0.4)" stroke-width="1.6">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        <span style="font-size:0.8rem; color:rgba(13,42,82,0.6);">{{ $v->duration }} Minutes</span>
                    </div>
                    @endif
                    @if($v->tags ?? null)
                    <div style="display:flex; align-items:center; gap:10px;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="rgba(13,42,82,0.4)" stroke-width="1.6">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                            <line x1="7" y1="7" x2="7.01" y2="7"/>
                        </svg>
                        <span style="font-size:0.8rem; color:rgba(13,42,82,0.6);">{{ $v->tags }}</span>
                    </div>
                    @endif
                </div>

                {{-- CTA Buttons --}}
                <div style="display:flex; flex-wrap:wrap; gap:0.75rem;">
                    @if($v->download_url ?? $v->video_url)
                    <button onclick="forceDownloadVideo('{{ $v->download_url ?? $v->video_url }}', '{{ addslashes($v->title) }}')"
                            style="display:inline-flex; align-items:center; gap:8px;
                                   padding:13px 22px; border-radius:8px;
                                   font-size:9.5px; letter-spacing:0.2em; text-transform:uppercase;
                                   font-weight:700; background:transparent; cursor:pointer;
                                   border:1.5px solid rgba(26,64,128,0.25); color:var(--blue-deep);
                                   transition:all 0.25s;"
                            onmouseover="this.style.borderColor='var(--gold)'; this.style.color='var(--gold)';"
                            onmouseout="this.style.borderColor='rgba(26,64,128,0.25)'; this.style.color='var(--blue-deep)';">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/>
                            <line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        Download
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
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
                          style="font-size:9px; font-weight:700; letter-spacing:0.25em;
                                 text-transform:uppercase; padding:4px 14px; border-radius:100px;">
                        Featured
                    </span>
                    <span style="font-size:10px; font-weight:700; letter-spacing:0.2em;
                                 text-transform:uppercase; color:rgba(235,242,255,0.55);">
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
                    {{ $featured->description }}
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
                        <img src="{{ $thumb->type === 'video' ? 'https://images.pexels.com/photos/1117132/pexels-photo-1117132.jpeg' : $thumb->url }}" alt="{{ $album->title }}"
                             style="width:100%; height:100%; object-fit:cover;
                                    transition:transform 0.7s ease;"
                             class="group-hover:scale-110" loading="lazy">
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
                               color:var(--blue-deep); margin-bottom:0.35rem;
                               transition:color 0.25s;">
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

        {{-- Gold rules --}}
        <div style="position:absolute; top:0; left:0; right:0; height:1px;
                    background:linear-gradient(90deg,transparent,rgba(245,197,24,0.35),transparent);"></div>
        <div style="position:absolute; bottom:0; left:0; right:0; height:1px;
                    background:linear-gradient(90deg,transparent,rgba(245,197,24,0.2),transparent);"></div>

        {{-- Watermark --}}
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
                   style="position:relative; aspect-ratio:1; border-radius:12px; overflow:hidden;
                          display:block; background:rgba(255,255,255,0.05);
                          border:1px solid rgba(255,255,255,0.06);">
                    
                    @if($item->type === 'video')
                        <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; z-index:5;">
                            <div style="width:32px; height:32px; border-radius:50%; background:rgba(245,197,24,0.8); display:flex; align-items:center; justify-content:center; box-shadow:0 4px 12px rgba(0,0,0,0.3);">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="#0D2A52"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                        </div>
                    @endif

                    <img src="{{ $item->type === 'video' ? 'https://images.pexels.com/photos/1117132/pexels-photo-1117132.jpeg' : $item->url }}" alt="Recent highlight"
                         class="recent-photo"
                         style="width:100%; height:100%; object-fit:cover;" loading="lazy">
                    <div style="position:absolute; inset:0; opacity:0; background:rgba(245,197,24,0.08);
                                transition:opacity 0.5s;" class="group-hover:opacity-100"></div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    @endif

    {{-- ═══════════════ STAY CONNECTED ═══════════════ --}}
    <section class="reveal" style="padding:4rem 1.5rem; max-width:860px; margin:0 auto;">

        <div style="text-align:center; margin-bottom:3rem;">
            <div class="font-cinzel" style="font-size:2rem; margin-bottom:1.5rem;
                        opacity:0.55; color:var(--gold);">✝</div>
            <div class="divider-ornament mb-5">
                <span class="eyebrow">Social Feed</span>
            </div>
            <h2 class="font-heading"
                style="font-size:clamp(2rem,4vw,3.5rem); font-weight:700; font-style:italic;
                       color:var(--blue-deep); letter-spacing:-0.01em; margin-bottom:1rem;">
                Stay Connected
            </h2>
            <div style="width:48px; height:1px; margin:0 auto 1.25rem;
                        background:linear-gradient(90deg,transparent,rgba(245,197,24,0.6),transparent);"></div>
            <p style="font-size:0.875rem; font-style:italic; color:rgba(13,42,82,0.45);">
                Follow our latest updates and community announcements live from Facebook.
            </p>
        </div>

        {{-- STAT ROW --}}
        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:2.5rem;">
            @foreach([
                ['val'=>'4K+',   'label'=>'Followers'],
                ['val'=>'Daily', 'label'=>'Mass Intentions'],
                ['val'=>'Live',  'label'=>'Streamed Masses'],
            ] as $stat)
            <div class="card-sacred" style="text-align:center; padding:1.25rem 0.5rem;">
                <p class="font-heading" style="font-size:1.5rem; font-weight:700;
                           color:var(--blue-deep);">{{ $stat['val'] }}</p>
                <p class="font-cinzel" style="font-size:9px; letter-spacing:0.2em;
                           color:rgba(13,42,82,0.35); margin-top:4px; text-transform:uppercase;">
                    {{ $stat['label'] }}
                </p>
            </div>
            @endforeach
        </div>

        {{-- FB EMBED --}}
        <div class="card-sacred" style="overflow:hidden; margin-bottom:2rem; padding:0;">
            <div id="fb-root"></div>
            <script async defer crossorigin="anonymous"
                    src="https://connect.facebook.net/en_PH/sdk.js#xfbml=1&version=v19.0"></script>
            <div class="fb-page"
                 data-href="https://www.facebook.com/storosarioparishpacita1"
                 data-tabs="timeline"
                 data-width="800"
                 data-height="500"
                 data-small-header="true"
                 data-adapt-container-width="true"
                 data-hide-cover="false"
                 data-show-facepile="false">
                <blockquote cite="https://www.facebook.com/storosarioparishpacita1"
                            class="fb-xfbml-parse-ignore">
                    <a href="https://www.facebook.com/storosarioparishpacita1">Sto. Rosario Parish - Pacita</a>
                </blockquote>
            </div>
        </div>

        {{-- BUTTONS --}}
        <div style="display:flex; flex-wrap:wrap; gap:1rem; justify-content:center;">
            <a href="https://www.facebook.com/storosarioparishpacita1" target="_blank"
               class="gold-btn"
               style="display:inline-flex; align-items:center; gap:0.5rem;
                      padding:14px 28px; border-radius:100px;
                      font-size:10px; letter-spacing:0.2em; text-transform:uppercase;
                      text-decoration:none;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
                Follow on Facebook
            </a>
            <a href="/about"
               style="display:inline-flex; align-items:center; padding:14px 28px;
                      border-radius:100px; font-size:10px; letter-spacing:0.2em;
                      text-transform:uppercase; font-weight:700; text-decoration:none;
                      border:1.5px solid rgba(26,64,128,0.25); color:var(--blue-deep);
                      transition:all 0.25s ease;"
               onmouseover="this.style.borderColor='var(--blue-mid)'; this.style.background='rgba(26,64,128,0.05)';"
               onmouseout="this.style.borderColor='rgba(26,64,128,0.25)'; this.style.background='transparent';">
                Contact the Office
            </a>
        </div>

        <p style="text-align:center; margin-top:4rem; font-size:9px; letter-spacing:0.4em;
                  text-transform:uppercase; color:rgba(13,42,82,0.2); font-family:'Cinzel',serif;">
            Sto. Rosario Parish · Pacita, San Pedro, Laguna
        </p>
    </section>

</div>

{{-- ═══════════════ CINEMATIC LIGHTBOX ═══════════════ --}}
<div id="video-lightbox" class="lightbox-backdrop" onclick="closeCinematicLightbox(event)">
    <div class="lightbox-content" onclick="event.stopPropagation()">
        <button class="video-btn"
                style="position:absolute; -top:50px; right:0; width:40px; height:40px; border-radius:50%; background:rgba(255,255,255,0.15);"
                onclick="closeCinematicLightbox()">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
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
        if(overlay) { overlay.style.opacity = '0'; overlay.style.pointerEvents = 'none'; }
        playIcon.style.display = 'none';
        pauseIcon.style.display = 'block';
    } else {
        if(overlay) { overlay.style.opacity = '1'; overlay.style.pointerEvents = 'auto'; }
        playIcon.style.display = 'block';
        pauseIcon.style.display = 'none';
    }
}

function toggleVideoMute(index) {
    const vid = document.getElementById(`hv-${index}`);
    const volIcon = document.getElementById(`vol-icon-${index}`);
    const muteIcon = document.getElementById(`mute-icon-${index}`);

    vid.muted = !vid.muted;
    if (vid.muted) {
        volIcon.style.display = 'none';
        muteIcon.style.display = 'block';
    } else {
        volIcon.style.display = 'block';
        muteIcon.style.display = 'none';
    }
}

function updateVideoProgress(index) {
    const vid = document.getElementById(`hv-${index}`);
    const fill = document.getElementById(`prog-fill-${index}`);
    const curr = document.getElementById(`time-curr-${index}`);

    const percent = (vid.currentTime / vid.duration) * 100;
    fill.style.width = `${percent}%`;

    const mins = Math.floor(vid.currentTime / 60);
    const secs = Math.floor(vid.currentTime % 60).toString().padStart(2, '0');
    curr.innerText = `${mins}:${secs}`;
}

function seekVideo(index, event) {
    const vid = document.getElementById(`hv-${index}`);
    const container = event.currentTarget;
    const rect = container.getBoundingClientRect();
    const x = event.clientX - rect.left;
    const percent = x / rect.width;
    vid.currentTime = percent * vid.duration;
}

function onVideoEnded(index) {
    const overlay = document.getElementById(`play-overlay-${index}`);
    const playIcon = document.getElementById(`play-icon-${index}`);
    const pauseIcon = document.getElementById(`pause-icon-${index}`);
    if(overlay) { overlay.style.opacity = '1'; overlay.style.pointerEvents = 'auto'; }
    playIcon.style.display = 'block';
    pauseIcon.style.display = 'none';
}

function togglePIP(id) {
    const vid = document.getElementById(id);
    if (document.pictureInPictureElement) {
        document.exitPictureInPicture();
    } else if (vid && vid.requestPictureInPicture) {
        vid.requestPictureInPicture();
    }
}

function toggleFullscreen(id) {
    const vid = document.getElementById(id);
    if (vid.requestFullscreen) vid.requestFullscreen();
    else if (vid.webkitRequestFullscreen) vid.webkitRequestFullscreen();
    else if (vid.msRequestFullscreen) vid.msRequestFullscreen();
}

/* Lightbox */
function openCinematicLightbox(url, title, desc) {
    const modal = document.getElementById('video-lightbox');
    const vid = document.getElementById('lightbox-video');
    const titleEl = document.getElementById('lightbox-title');
    const descEl = document.getElementById('lightbox-desc');

    vid.src = url;
    titleEl.innerText = title;
    descEl.innerText = desc || '';
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
    vid.src = "";
}

/* Share & Download */
async function shareVideo(title, url) {
    if (navigator.share) {
        try {
            await navigator.share({ title: title, url: url });
        } catch (err) {}
    } else {
        navigator.clipboard.writeText(url);
        alert('Video link copied to clipboard!');
    }
}

async function forceDownloadVideo(url, title) {
    // Slugify the title for a clean filename
    const slug = title.toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '') || 'parish-highlight';
    const filename = slug + '.mp4';

    try {
        const response = await fetch(url);
        const blob = await response.blob();
        const blobUrl = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = blobUrl;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(blobUrl);
        document.body.removeChild(a);
    } catch (err) {
        // Fallback
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        a.target = "_blank";
        a.click();
    }
}

/* Keyboard Support */
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