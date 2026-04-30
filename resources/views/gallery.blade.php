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
            Parish Gallery
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
    @endphp

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
                <img src="{{ $featured->images->first()->url }}" alt="{{ $featured->title }}"
                     style="width:100%; height:100%; object-fit:cover; transition:transform 3s ease;"
                     class="group-hover:scale-105">
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
                        {{ $featured->images_count }} Photos
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
                        <img src="{{ $album->images->first()->url }}" alt="{{ $album->title }}"
                             style="width:100%; height:100%; object-fit:cover;
                                    transition:transform 0.7s ease;"
                             class="group-hover:scale-110" loading="lazy">
                    @endif
                    <div style="position:absolute; inset:0; background:rgba(13,42,82,0.15); transition:background 0.3s;"
                         class="group-hover:bg-[rgba(13,42,82,0.05)]"></div>
                    <div style="position:absolute; top:12px; right:12px;
                                font-size:9px; font-weight:700; letter-spacing:0.2em;
                                text-transform:uppercase; padding:4px 10px; border-radius:100px;
                                background:rgba(255,255,255,0.92); color:var(--blue-deep);">
                        {{ $album->images_count }} Pics
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

    {{-- ═══════════════ RECENT CAPTURES ═══════════════ --}}
    @if($latestPhotos->isNotEmpty())
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
                    Recent Captures
                </h2>
            </div>

            <div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(150px,1fr)); gap:0.75rem;">
                @foreach($latestPhotos->take(6) as $photo)
                <a href="{{ route('gallery.album', $photo->album_id) }}"
                   style="position:relative; aspect-ratio:1; border-radius:12px; overflow:hidden;
                          display:block; background:rgba(255,255,255,0.05);
                          border:1px solid rgba(255,255,255,0.06);">
                    <img src="{{ $photo->url }}" alt="Recent photo"
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

<script>
document.addEventListener('DOMContentLoaded', () => {
    const observer = new IntersectionObserver(
        entries => entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('active'); observer.unobserve(e.target); } }),
        { threshold: 0.12, rootMargin: '0px 0px -50px 0px' }
    );
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
});
</script>

</x-public-layout>