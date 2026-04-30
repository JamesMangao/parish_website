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
            display: flex; align-items: center; gap: 12px;
        }
        .divider-ornament::after {
            content: ''; height: 1px; flex: 1;
            background: linear-gradient(90deg, rgba(245,197,24,0.35), transparent);
        }

        .gold-btn {
            background: linear-gradient(135deg, #FFD740 0%, #F5C518 55%, #E0A800 100%);
            color: var(--blue-deep);
            box-shadow: 0 4px 20px rgba(245,197,24,0.35), 0 1px 0 rgba(255,255,255,0.35) inset;
            transition: all 0.25s ease; font-weight: 700;
        }
        .gold-btn:hover {
            box-shadow: 0 8px 32px rgba(245,197,24,0.55), 0 1px 0 rgba(255,255,255,0.35) inset;
            transform: translateY(-2px);
        }

        .reveal {
            opacity: 0; transform: translateY(40px);
            transition: all 1.2s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .reveal.active { opacity: 1; transform: translateY(0); }

        /* Photo grid */
        .photo-card {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            aspect-ratio: 4/3;
            border: 1px solid rgba(26,64,128,0.1);
            cursor: zoom-in;
            box-shadow: 0 4px 20px rgba(13,42,82,0.07);
            transition: all 0.4s cubic-bezier(0.16,1,0.3,1);
        }
        .photo-card:hover {
            border-color: rgba(245,197,24,0.5);
            box-shadow: 0 16px 48px rgba(13,42,82,0.14);
            transform: translateY(-4px);
        }
        .photo-card img {
            width: 100%; height: 100%; object-fit: cover;
            transition: transform 0.9s ease;
        }
        .photo-card:hover img { transform: scale(1.08); }
        .photo-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(to top,
                rgba(8,20,45,0.88) 0%,
                rgba(8,20,45,0.1) 50%,
                transparent 100%);
            opacity: 0; transition: opacity 0.35s ease;
            display: flex; flex-direction: column;
            justify-content: flex-end; padding: 1.25rem;
        }
        .photo-card:hover .photo-overlay { opacity: 1; }

        /* Lightbox */
        .lightbox {
            position: fixed; inset: 0; z-index: 200;
            background: rgba(5,12,30,0.97);
            backdrop-filter: blur(20px);
            display: flex; align-items: center; justify-content: center;
            padding: 1.5rem;
            animation: lbFadeIn 0.25s ease;
        }
        @keyframes lbFadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        .lightbox-img {
            max-width: 100%; max-height: 82vh;
            object-fit: contain;
            border-radius: 12px;
            box-shadow: 0 24px 80px rgba(0,0,0,0.6);
            animation: lbScaleIn 0.3s cubic-bezier(0.22,1,0.36,1);
        }
        @keyframes lbScaleIn {
            from { opacity: 0; transform: scale(0.94); }
            to   { opacity: 1; transform: scale(1); }
        }
        .lb-close {
            position: absolute; top: 1.25rem; right: 1.25rem;
            width: 44px; height: 44px; border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.15);
            background: rgba(255,255,255,0.07);
            color: rgba(255,255,255,0.8);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s ease;
            backdrop-filter: blur(8px);
        }
        .lb-close:hover {
            background: rgba(245,197,24,0.15);
            border-color: rgba(245,197,24,0.5);
            color: var(--gold-light);
        }
        .lb-nav {
            position: absolute; top: 50%; transform: translateY(-50%);
            width: 48px; height: 48px; border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.12);
            background: rgba(255,255,255,0.05);
            color: rgba(255,255,255,0.7);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s ease;
            backdrop-filter: blur(8px);
        }
        .lb-nav:hover {
            background: rgba(245,197,24,0.12);
            border-color: rgba(245,197,24,0.45);
            color: var(--gold-light);
        }
        .lb-prev { left: 1.25rem; }
        .lb-next { right: 1.25rem; }
    </style>
</x-slot>

{{-- ═══════════════ HERO / HEADER ═══════════════ --}}
<section style="position:relative; background:var(--blue-deep); overflow:hidden;
                padding:5rem 1.5rem 3.5rem;">

    {{-- Gold rules --}}
    <div style="position:absolute; top:0; left:0; right:0; height:1px;
                background:linear-gradient(90deg,transparent,rgba(245,197,24,0.45),transparent);"></div>
    <div style="position:absolute; bottom:0; left:0; right:0; height:1px;
                background:linear-gradient(90deg,transparent,rgba(245,197,24,0.2),transparent);"></div>

    {{-- Radial glow --}}
    <div style="position:absolute; inset:0; pointer-events:none;
                background:radial-gradient(ellipse 70% 70% at 50% 20%, rgba(26,64,128,0.5) 0%, transparent 70%);"></div>

    {{-- Watermark --}}
    <div class="font-cinzel" style="position:absolute; font-size:320px; color:rgba(255,255,255,0.018);
                top:50%; left:50%; transform:translate(-50%,-50%);
                pointer-events:none; user-select:none; line-height:1;">✝</div>

    <div style="max-width:1100px; margin:0 auto; position:relative; z-index:5;">

        {{-- Back link --}}
        <a href="{{ route('gallery') }}"
           style="display:inline-flex; align-items:center; gap:8px; margin-bottom:2rem;
                  font-size:9.5px; font-weight:600; letter-spacing:0.28em; text-transform:uppercase;
                  color:rgba(235,242,255,0.45); text-decoration:none; transition:color 0.2s;"
           onmouseover="this.style.color='rgba(245,197,24,0.85)';"
           onmouseout="this.style.color='rgba(235,242,255,0.45)';">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            All Albums
        </a>

        <div style="display:flex; flex-wrap:wrap; align-items:flex-end;
                    justify-content:space-between; gap:1.5rem;">
            <div style="flex:1; min-width:0;">
                <div class="divider-ornament mb-4" style="display:flex; align-items:center; gap:12px;">
                    <span class="eyebrow" style="color:rgba(245,197,24,0.75); white-space:nowrap;">
                        Parish Gallery
                    </span>
                </div>
                <h1 class="font-heading"
                    style="font-size:clamp(2.4rem,5vw,5rem); font-weight:700; font-style:italic;
                           color:#EBF2FF; letter-spacing:-0.02em; line-height:1.05; margin-bottom:1rem;">
                    {{ $album->title }}
                </h1>
                @if($album->description)
                <p style="font-size:1rem; font-style:italic; color:rgba(235,242,255,0.45);
                          line-height:1.7; max-width:600px;">
                    {!! nl2br(e($album->description)) !!}
                </p>
                @endif
            </div>

            <div style="text-align:right; flex-shrink:0;">
                <div class="gold-btn"
                     style="display:inline-block; font-size:9px; letter-spacing:0.25em;
                            text-transform:uppercase; padding:6px 18px; border-radius:100px;
                            margin-bottom:8px;">
                    {{ $album->images->count() }} Photos
                </div>
                <p class="font-cinzel"
                   style="font-size:9px; letter-spacing:0.2em; color:rgba(235,242,255,0.3);
                          text-transform:uppercase;">
                    Added {{ $album->created_at->format('M d, Y') }}
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════ PHOTO GRID ═══════════════ --}}
<section class="reveal" style="padding:3.5rem 1.5rem 5rem; max-width:1100px; margin:0 auto;">

    @if($album->images->isEmpty())
        <div style="text-align:center; padding:6rem 2rem;
                    border:1px dashed rgba(26,64,128,0.15); border-radius:20px;
                    background:var(--cream-deep);">
            <div style="width:64px; height:64px; border-radius:50%; margin:0 auto 1.25rem;
                        background:#fff; border:1px solid rgba(26,64,128,0.1);
                        display:flex; align-items:center; justify-content:center;">
                <svg width="28" height="28" fill="none" viewBox="0 0 24 24">
                    <rect width="18" height="18" x="3" y="3" rx="2" stroke="#F5C518" stroke-width="1.5"/>
                    <circle cx="9" cy="9" r="2" stroke="#F5C518" stroke-width="1.5"/>
                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" stroke="#F5C518" stroke-width="1.5"/>
                </svg>
            </div>
            <p style="font-size:0.875rem; font-style:italic; color:rgba(13,42,82,0.4);">
                No photos in this album yet. Check back soon.
            </p>
        </div>

    @else
        <div id="photo-grid"
             style="display:grid; grid-template-columns:repeat(auto-fill,minmax(260px,1fr)); gap:1.25rem;">
            @foreach($album->images as $idx => $img)
            <div class="photo-card"
                 data-idx="{{ $idx }}"
                 onclick="openLightbox({{ $idx }})"
                 x-data="{ loaded: false }">
                <div style="position:absolute; inset:0; background:var(--cream-deep);"
                     x-show="!loaded"></div>
                <img src="{{ $img->url }}"
                     alt="{{ $img->title }}"
                     @load="loaded = true"
                     :class="loaded ? '' : 'opacity-0'"
                     loading="lazy">
                <div class="photo-overlay">
                    <p style="font-size:0.8125rem; font-weight:600; color:#EBF2FF;
                              white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $img->caption ?: $img->title }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</section>

{{-- ═══════════════ LIGHTBOX ═══════════════ --}}
<div id="lightbox" class="lightbox" style="display:none;"
     onclick="if(event.target===this) closeLightbox()">

    <button class="lb-close" onclick="closeLightbox()" aria-label="Close">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 6 6 18M6 6l12 12"/>
        </svg>
    </button>

    <button class="lb-nav lb-prev" onclick="lbNav(-1)" aria-label="Previous">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M12 5l-7 7 7 7"/>
        </svg>
    </button>

    <div style="display:flex; flex-direction:column; align-items:center;
                max-width:1000px; width:100%;">
        <img id="lb-img" class="lightbox-img" src="" alt="">
        <div id="lb-caption" style="margin-top:1.5rem; text-align:center;">
            <h3 id="lb-title" class="font-heading"
                style="font-size:1.75rem; font-weight:700; font-style:italic;
                       color:#EBF2FF; margin-bottom:0.25rem;"></h3>
            <p id="lb-sub"
               style="font-size:0.875rem; font-style:italic; color:rgba(235,242,255,0.4);"></p>
            <p id="lb-counter" class="font-cinzel"
               style="font-size:9px; letter-spacing:0.3em; color:rgba(235,242,255,0.25);
                      text-transform:uppercase; margin-top:0.75rem;"></p>
        </div>
    </div>

    <button class="lb-nav lb-next" onclick="lbNav(1)" aria-label="Next">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14M12 5l7 7-7 7"/>
        </svg>
    </button>
</div>

<script>
const images = @json($album->images->map(fn($i) => ['url' => $i->url, 'title' => $i->title, 'caption' => $i->caption]));
let lbIdx = 0;

const lb     = document.getElementById('lightbox');
const lbImg  = document.getElementById('lb-img');
const lbTitle = document.getElementById('lb-title');
const lbSub   = document.getElementById('lb-sub');
const lbCount = document.getElementById('lb-counter');

function openLightbox(idx) {
    lbIdx = idx;
    renderLb();
    lb.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    lb.style.display = 'none';
    document.body.style.overflow = '';
}
function lbNav(dir) {
    lbIdx = (lbIdx + dir + images.length) % images.length;
    renderLb();
}
function renderLb() {
    const img = images[lbIdx];
    lbImg.src = img.url;
    lbImg.alt = img.title;
    lbTitle.textContent = img.title || '';
    lbSub.textContent   = img.caption || '';
    lbCount.textContent = `${lbIdx + 1} / ${images.length}`;
}

document.addEventListener('keydown', e => {
    if (lb.style.display === 'none') return;
    if (e.key === 'Escape')     closeLightbox();
    if (e.key === 'ArrowLeft')  lbNav(-1);
    if (e.key === 'ArrowRight') lbNav(1);
});

// Reveal observer
document.addEventListener('DOMContentLoaded', () => {
    const obs = new IntersectionObserver(
        entries => entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('active'); obs.unobserve(e.target); } }),
        { threshold: 0.1 }
    );
    document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
});
</script>

</x-public-layout>