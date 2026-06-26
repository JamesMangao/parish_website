<x-public-layout>
<x-slot name="meta">
    <meta name="description" content="Welcome to Sto. Rosario Parish – Pacita, San Pedro, Laguna. Mass schedules, intentions, events, and community news.">
    <link rel="preload" href="{{ asset('fonts/Canterbury.ttf') }}" as="font" type="font/ttf" crossorigin>
    <style>
        @font-face{font-family:'Canterbury';src:url('{{ asset('fonts/Canterbury.ttf') }}') format('truetype');font-weight:normal;font-style:normal;font-display:swap;}
    </style>
</x-slot>

<section class="hero-section" style="position:relative;height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;overflow:hidden;">
    <div class="hero-bg-wrap">
        <img src="{{ \Illuminate\Support\Facades\Storage::disk('supabase')->url('assets/bg.webp') }}" alt="Sto. Rosario Parish" fetchpriority="high" decoding="async" width="1920" height="1080" class="hero-bg-img">
        <div class="hero-overlay" style="position:absolute;inset:0;"></div>
    </div>
    <div class="hero-radial-overlay"></div>
    <div class="hero-noise-overlay"></div>

    <div class="hero-content-wrap">
        <div class="hero-badge animate-fade-in-down" style="margin-bottom:24px;animation-delay:.1s;">
            <span class="hero-dot"></span>
            <span class="hero-est-text">Est. · Diocese of San Pablo · Pacita</span>
        </div>

        <h1 class="hero-title animate-fade-in-up" style="animation-delay:.2s;">
            <span class="hero-title-accent">Sto. Rosario Parish</span>
        </h1>

        <p class="hero-subtitle font-heading animate-fade-in-up" style="animation-delay:.3s;">Pacita Complex 1, San Pedro, Laguna</p>
        <div class="hero-divider"></div>
        <p class="hero-desc animate-fade-in-up" style="animation-delay:.4s;">Home to the Queen of the Most Holy Rosary — a beacon of faith, community, and service for over four decades.</p>

        <div class="hero-cta-wrap animate-fade-in-up" style="animation-delay:.5s;">
            <a href="{{ route('mass-schedule') }}" class="ghost-btn inline-flex items-center gap-2 rounded-full font-bold uppercase" style="padding:13px 30px;font-size:12.5px;letter-spacing:.18em;text-decoration:none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                Mass Schedule
            </a>
            <a href="{{ route('submit-intention') }}" class="gold-btn inline-flex items-center gap-2 rounded-full" style="padding:13px 30px;font-size:12.5px;letter-spacing:.18em;text-transform:uppercase;text-decoration:none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/></svg>
                Offer an Intention
            </a>
            <a href="{{ route('inquiry') }}" class="ghost-btn inline-flex items-center gap-2 rounded-full font-bold uppercase" style="padding:13px 30px;font-size:12.5px;letter-spacing:.18em;text-decoration:none;">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
                Parish Inquiry
            </a>
        </div>

        <div class="hero-stats-strip animate-fade-in-up" style="animation-delay:.6s;">
            @foreach([['40+','Years of Service'],['7','Weekly Masses'],['1','Community']] as $stat)
            <div style="text-align:center;">
                <div class="font-heading hero-stat-val">{{ $stat[0] }}</div>
                <div class="hero-stat-label">{{ $stat[1] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="max-w-5xl mx-auto px-6 mt-48 reveal reveal-up section-px-mobile"><br><br>
    <div class="next-mass-card">
        <div class="next-mass-banner">
            <div class="next-mass-banner-bg">
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('supabase')->url('assets/img/mass.webp') }}" alt="Mass" loading="lazy" width="1200" height="800">
            </div>

            <div class="next-mass-inner">
                <div class="next-mass-icon-wrap">
                    <svg width="82" height="82" viewBox="0 0 82 82" style="position:absolute;inset:0;" fill="none" aria-hidden="true">
                        <line x1="41" y1="3" x2="41" y2="13" stroke="rgba(245,197,24,.4)" stroke-width="1.5" stroke-linecap="round"/><line x1="41" y1="69" x2="41" y2="79" stroke="rgba(245,197,24,.4)" stroke-width="1.5" stroke-linecap="round"/><line x1="3" y1="41" x2="13" y2="41" stroke="rgba(245,197,24,.4)" stroke-width="1.5" stroke-linecap="round"/><line x1="69" y1="41" x2="79" y2="41" stroke="rgba(245,197,24,.4)" stroke-width="1.5" stroke-linecap="round"/><line x1="11" y1="11" x2="18" y2="18" stroke="rgba(245,197,24,.4)" stroke-width="1.5" stroke-linecap="round"/><line x1="64" y1="64" x2="71" y2="71" stroke="rgba(245,197,24,.4)" stroke-width="1.5" stroke-linecap="round"/><line x1="71" y1="11" x2="64" y2="18" stroke="rgba(245,197,24,.4)" stroke-width="1.5" stroke-linecap="round"/><line x1="11" y1="71" x2="18" y2="64" stroke="rgba(245,197,24,.4)" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <div class="next-mass-icon-ring">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#F5C518" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 22h8"/><path d="M12 11v11"/><path d="M5 3h14L18 9a6 6 0 0 1-12 0L5 3z"/><path d="M3 3h18"/></svg>
                    </div>
                </div>

                <div>
                    <div class="eyebrow-row flex items-center gap-3 mb-2">
                        <span style="display:inline-block;height:1px;width:32px;background:linear-gradient(90deg,transparent,rgba(245,197,24,.6));"></span>
                        <span class="eyebrow" style="color:rgba(235,242,255,.7);">NEXT MASS</span>
                        <span style="display:inline-block;height:1px;width:32px;background:linear-gradient(90deg,rgba(245,197,24,.6),transparent);"></span>
                    </div>
                    @if($nextMass)
                    <h2 class="font-heading font-bold italic leading-none next-mass-title">{{ $nextMass->calculated_day }}</h2>
                    <p class="font-heading font-bold italic next-mass-time">{{ $nextMass->calculated_time }}</p>
                    <p class="next-mass-type">{{ strtoupper($nextMass->title ?? ($nextMass->mass_type === 'sunday' ? 'Sunday Mass' : 'Weekday Mass')) }}</p>
                    @else
                    <h2 class="font-heading font-bold italic leading-none next-mass-title">Sunday</h2>
                    <p class="font-heading font-bold italic next-mass-time">6:00 AM</p>
                    <p class="next-mass-type">SUNDAY MASS</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="next-mass-divider"></div>

        <div class="px-8 pt-7 pb-0 section-px-mobile"><br>
            <div class="office-hours-header mb-2">
                <span style="flex:1;height:1px;background:linear-gradient(90deg,transparent,rgba(201,162,0,.3));"></span>
                <div class="flex items-center gap-2">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span class="font-cinzel office-hours-label">OFFICE HOURS</span>
                </div>
                <span style="flex:1;height:1px;background:linear-gradient(90deg,rgba(201,162,0,.3),transparent);"></span>
            </div>

            <div class="office-cols grid grid-cols-1 md:grid-cols-3 gap-5">
                @foreach([
                    ['icon'=>'<rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>','day'=>'· TUE – SAT ·','hours'=>['6:00 AM – 12:00 NN','1:30 PM – 6:00 PM'],'closed'=>false],
                    ['icon'=>'<circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/>','day'=>'· SUNDAY ·','hours'=>['6:00 AM – 12:00 NN','3:00 PM – 6:00 PM'],'closed'=>false],
                    ['icon'=>'<rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>','day'=>'· MONDAY ·','hours'=>[],'closed'=>true],
                ] as $ohCol)
                <div class="office-col"><br>
                    <div class="office-col-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $ohCol['icon'] !!}</svg>
                    </div>
                    <p class="office-col-day">{{ $ohCol['day'] }}</p>
                    @if($ohCol['closed'])
                    <p class="font-cinzel font-semibold office-col-closed">CLOSED</p>
                    @else
                    @foreach($ohCol['hours'] as $ohHour)
                    <p class="office-col-hours">{{ $ohHour }}</p>
                    @endforeach
                    @endif
                </div>
                @endforeach<br>
            </div>
        </div>

        <a href="{{ route('mass-schedule') }}" class="group relative flex flex-col items-center justify-center overflow-hidden mt-6 office-hours-cta" aria-label="View Full Mass Schedule">
            <div class="absolute left-0 top-[70%] -translate-y-1/2 pointer-events-none transition-transform duration-700 group-hover:scale-110" style="opacity:.5;height:150%;width:auto;" aria-hidden="true">
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('supabase')->url('assets/img/parish-illustration.svg') }}" alt="Parish Illustration" width="285" height="135" style="height:90%;width:auto;object-fit:contain;filter:brightness(0) invert(1);">
            </div>
            <div class="flex items-center gap-2.5 mb-1.5">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/><path d="M11 2v3M9 3h4"/></svg>
                <span class="font-cinzel office-hours-cta-label">FULL SCHEDULE</span>
            </div>
            <span class="office-hours-cta-arrow block" aria-hidden="true">→</span>
        </a>
    </div>
</section>

<section class="py-24 bg-[var(--cream)] reveal reveal-up section-pad-mobile section-pad-tablet">
    <div class="max-w-5xl mx-auto px-6 section-px-mobile">
        <div id="daily-readings-widget" class="card-sacred overflow-hidden" data-readings-url="/api/readings/today" style="box-shadow:0 12px 50px rgba(13,42,82,.08);">
            <div class="px-6 md:px-8 py-7" style="background:linear-gradient(135deg,#fff 0%,#F0F5FF 100%);border-bottom:1px solid rgba(26,64,128,.08);">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                    <div>
                        <div class="flex items-center gap-3 mb-3">
                            <span style="display:inline-block;width:34px;height:1px;background:linear-gradient(90deg,transparent,rgba(245,197,24,.75));"></span>
                            <span class="eyebrow" style="color:#C9A200;">READINGS OF THE DAY</span>
                        </div>
                        <h2 class="font-heading font-bold italic" data-reading-title style="font-size:clamp(2rem,4vw,3.2rem);line-height:1.05;color:var(--blue-deep);">Daily Mass Readings</h2>
                        <p data-reading-date style="font-size:13px;color:rgba(13,42,82,.48);margin-top:8px;">Loading today&rsquo;s readings...</p>
                    </div>
                    <div class="flex-shrink-0" style="display:flex!important;flex-direction:row!important;align-items:center;background:rgba(26,64,128,.05);border:1px solid rgba(26,64,128,.08);border-radius:999px;padding:4px;width:140px;height:40px;box-shadow:0 6px 20px rgba(13,42,82,.04);">
                        <button type="button" data-reading-tab="EN" class="reading-tab active" style="flex:1;border:0;border-radius:999px;height:100%;font-size:11px;font-weight:800;letter-spacing:.12em;color:var(--blue-deep);background:var(--gold);transition:all .3s ease;cursor:pointer;outline:none;display:flex;align-items:center;justify-content:center;">EN</button>
                        <button type="button" data-reading-tab="TG" class="reading-tab" style="flex:1;border:0;border-radius:999px;height:100%;font-size:11px;font-weight:800;letter-spacing:.12em;color:rgba(13,42,82,.55);background:transparent;transition:all .3s ease;cursor:pointer;outline:none;display:flex;align-items:center;justify-content:center;">FIL</button>
                    </div>
                </div>
            </div>
            <div class="px-6 md:px-8 py-8">
                <div data-reading-status style="font-size:14px;color:rgba(13,42,82,.55);">Fetching readings...</div>
                <div data-reading-content class="grid gap-5" style="display:none;"></div>
            </div>
        </div>
    </div>
</section>

<script>
(() => {
    const widget = document.getElementById('daily-readings-widget');
    if (!widget) return;

    const tabs    = [...widget.querySelectorAll('[data-reading-tab]')];
    const title   = widget.querySelector('[data-reading-title]');
    const date    = widget.querySelector('[data-reading-date]');
    const status  = widget.querySelector('[data-reading-status]');
    const content = widget.querySelector('[data-reading-content]');
    const cache   = {};
    let active    = 'EN';

    const esc = v => String(v ?? '').replace(/[&<>"']/g, c => ({
        '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'
    }[c]));

    const slug = v => String(v ?? '')
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-|-$/g, '');

const renderReadingText = text => {
    if (!text) return '';

    let normalized = String(text);
    normalized = normalized
        .replace(/\\u2019/g, '\u2019')
        .replace(/\\u2018/g, '\u2018')
        .replace(/\\u201c/g, '\u201c')
        .replace(/\\u201d/g, '\u201d')
        .replace(/\\u2013/g, '\u2013')
        .replace(/\\u2014/g, '\u2014');

    const lines = normalized.split(/\\n|\n|\r\n|\r/);

    let inResponse = false;

    return lines.map(rawLine => {
        const line = rawLine.trim();
        if (!line) {
            inResponse = false;
            return '<div class="reading-line--blank"></div>';
        }
        if (/^(or|o kaya):?$/i.test(line)) {
            inResponse = false;
            return `<div class="reading-line reading-line--or">${esc(line)}</div>`;
        }
        if (/^(Ang Mabuting Balita ng Panginoon|Ang Salita ng Diyos|Pagbasa mula sa|A reading from|The holy Gospel|The word of the Lord|Aleluya!)/i.test(line)) {
            inResponse = false;
            return `<div class="reading-line" style="font-weight:700;color:var(--blue-deep);margin-top:8px;">${esc(line)}</div>`;
        }
        const responseMatch = line.match(/^((?:R\.|A\.|S\.|Response:|Refrain:|Tugon:|Tugon))\s*(.*)$/i);
        if (responseMatch) {
            inResponse = true;
            const raw = responseMatch[1];
            const marker = /^(Response|Refrain|Tugon)/i.test(raw) ? 'R.' : raw;
            return `<div class="reading-line--response"><span class="reading-marker">${esc(marker)}</span><strong class="reading-response">${esc(responseMatch[2])}</strong></div>`;
        }
        if (inResponse) {
            return `<div class="reading-line--response"><span class="reading-marker">&nbsp;</span><strong class="reading-response">${esc(line)}</strong></div>`;
        }
        return `<div class="reading-line">${esc(line)}</div>`;
    }).join('');
};

    const setTabs = lang => tabs.forEach(tab => {
        const isActive = tab.dataset.readingTab === lang;
        tab.classList.toggle('active', isActive);
        tab.style.background = isActive ? 'var(--gold)' : 'transparent';
        tab.style.color      = isActive ? 'var(--blue-deep)' : 'rgba(13,42,82,0.55)';
    });

    const normalize = data => {
        const readings    = data?.readings || data?.data?.readings || [];
        let liturgy       = data?.liturgic_title || data?.data?.liturgic_title || data?.title || 'Daily Mass Readings';
        if (liturgy && typeof liturgy === 'string') liturgy = liturgy.split(' • ')[0];
        const displayDate = data?.date_displayed || data?.data?.date_displayed || data?.date
            || new Date().toLocaleDateString('en-PH', { weekday:'long', year:'numeric', month:'long', day:'numeric' });

        return {
            title: liturgy,
            date:  displayDate,
            readings: readings
                .map(item => ({
                    type:      item.type || item.kind || item.title || 'Reading',
                    reference: item.reference || item.ref || '',
                    text:      item.text || item.content || item.reading || '',
                    kind:      slug(item.type || item.kind || item.title || '')
                }))
                .filter(item => item.text || item.reference)
        };
    };

    const render = payload => {
        const data = normalize(payload);
        title.textContent = data.title;

        if (payload?.is_fallback) {
            date.innerHTML = `${esc(data.date)} <span style="display:inline-block;padding:2px 8px;font-size:10px;font-weight:700;color:#c27803;background:#fef3c7;border:1px solid #fcd34d;border-radius:999px;margin-left:8px;vertical-align:middle;text-transform:uppercase;letter-spacing:.05em;">Offline Fallback</span>`;
        } else {
            date.textContent = data.date;
        }

        if (!data.readings.length) {
            status.style.display  = 'block';
            status.textContent    = 'No readings available today.';
            content.style.display = 'none';
            return;
        }

        status.style.display  = 'none';
        content.style.display = 'grid';
        content.innerHTML     = data.readings.map(item => `
            <article class="reading-card rounded-2xl p-5"
                     data-reading-kind="${esc(item.kind)}"
                     style="background:#fff;border:1px solid rgba(26,64,128,.09);">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-3">
                    <h3 class="font-cinzel" style="font-size:12px;letter-spacing:.22em;color:#C9A200;font-weight:700;text-transform:uppercase;">${esc(item.type)}</h3>
                    ${item.reference ? `<span style="font-size:12px;color:rgba(13,42,82,.45);">${esc(item.reference)}</span>` : ''}
                </div>
                <div class="reading-text">${renderReadingText(item.text)}</div>
            </article>
        `).join('');
    };

    const getSkeletonHtml = () => `
        <div class="grid gap-5 skeleton-pulse" style="width:100%;">
            ${[['120px','100px',['100%','96%','90%','45%']],['140px','80px',['100%','92%','75%']]].map(([w1,w2,lines]) => `
            <div class="rounded-2xl p-5" style="background:#fff;border:1px solid rgba(26,64,128,.06);display:flex;flex-direction:column;gap:12px;">
                <div class="flex justify-between items-center mb-2">
                    <div class="skeleton-block" style="width:${w1};height:14px;"></div>
                    <div class="skeleton-block" style="width:${w2};height:12px;"></div>
                </div>
                ${lines.map(w => `<div class="skeleton-block" style="width:${w};height:14px;"></div>`).join('')}
            </div>`).join('')}
        </div>`;

    const getErrorHtml = () => `
        <div style="display:flex;flex-direction:column;align-items:center;text-align:center;padding:36px 16px;gap:18px;max-width:400px;margin:0 auto;">
            <div class="rounded-full flex items-center justify-center" style="width:52px;height:52px;background:rgba(239,68,68,.08);color:#ef4444;border:1.5px solid rgba(239,68,68,.18);">
                <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <p class="font-heading font-bold italic" style="font-size:1.4rem;color:var(--blue-deep);margin-bottom:6px;">Readings Unavailable</p>
                <p style="font-size:13.5px;color:rgba(13,42,82,.55);line-height:1.5;">We encountered an issue fetching today's readings. Please try again.</p>
            </div>
            <button type="button" data-reading-retry style="background:var(--blue-deep);color:#fff;border:0;border-radius:999px;padding:9px 28px;font-size:11.5px;font-weight:800;letter-spacing:.08em;cursor:pointer;transition:all .25s ease;box-shadow:0 6px 20px rgba(13,42,82,.15);">RETRY FETCH</button>
        </div>`;

    const load = async lang => {
        active = lang;
        setTabs(lang);
        status.style.display  = 'block';
        status.innerHTML      = getSkeletonHtml();
        content.style.display = 'none';

        try {
            if (!cache[lang]) {
                const res = await fetch(`${widget.dataset.readingsUrl}?language=${lang}`, {
                    headers: { Accept: 'application/json' }
                });
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                cache[lang] = await res.json();
            }
            if (active === lang) render(cache[lang]);
        } catch (err) {
            if (active === lang) {
                status.innerHTML = getErrorHtml();
                status.querySelector('[data-reading-retry]')?.addEventListener('click', () => {
                    delete cache[lang];
                    load(lang);
                });
            }
        }
    };

    tabs.forEach(tab => tab.addEventListener('click', () => load(tab.dataset.readingTab)));
    load(active);
})();
</script>

<section class="py-32 bg-[var(--cream)] reveal reveal-up section-pad-mobile section-pad-tablet">
    <div class="max-w-[1200px] mx-auto px-6 section-px-mobile">
        <div class="text-center mb-16">
            <div class="divider-ornament mb-4"><span class="eyebrow">Quick Access</span></div>
            <h2 class="font-heading text-4xl md:text-5xl font-bold italic" style="color:var(--blue-deep);">How Can We Serve You?</h2>
        </div>
        <div class="quick-actions-grid grid grid-cols-2 md:grid-cols-4 gap-8 max-w-5xl mx-auto reveal reveal-stagger">
            @php
            $actions = [
                ['href'=>'/mass-schedule','icon'=>'<path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/>','label'=>'Mass Schedule','sub'=>'Times & days'],
                ['href'=>'/submit-intention','icon'=>'<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/><path d="M12 8v8"/><path d="M8 12h8"/>','label'=>'Offer Intention','sub'=>'Submit online'],
                ['href'=>'/inquiry','icon'=>'<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.19 12 19.79 19.79 0 0 1 1.12 3.33A2 2 0 0 1 3.09 1h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>','label'=>'Inquiry','sub'=>'Sacraments & docs'],
                ['href'=>'/donate','icon'=>'<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.505 4.04 3 5.5L12 21l7-7Z"/>','label'=>'Donate','sub'=>'Support the parish'],
            ];
            @endphp
            @foreach($actions as $a)
            <a href="{{ $a['href'] }}" class="card-sacred group quick-action-card"><br><br>
                <div class="quick-action-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">{!! $a['icon'] !!}</svg>
                </div>
                <div>
                    <p class="font-heading font-bold text-lg italic quick-action-label">{{ $a['label'] }}</p>
                    <p class="text-sm mt-1 tracking-wide quick-action-sub">{{ $a['sub'] }}</p>
                </div><br><br>
            </a>
            @endforeach
        </div>
    </div><br>
</section>

<section class="relative pt-12 pb-24 overflow-hidden reveal reveal-up section-pad-mobile section-pad-tablet">
    <div class="events-section-bg" aria-hidden="true">
        <img src="{{ \Illuminate\Support\Facades\Storage::disk('supabase')->url('assets/img/church1.webp') }}" alt="">
        <div class="events-section-bg-overlay"></div>
    </div>

    <div class="relative z-10 max-w-5xl mx-auto px-6 section-px-mobile">
        <div class="text-center mb-14"><br>
            <div class="flex justify-center mb-5">
                <div class="relative flex items-center justify-center" style="width:50px;height:50px;">
                    <svg width="50" height="50" viewBox="0 0 50 50" style="position:absolute;inset:0;" fill="none" aria-hidden="true">
                        <line x1="25" y1="2" x2="25" y2="9" stroke="rgba(201,162,0,.5)" stroke-width="1.5" stroke-linecap="round"/><line x1="25" y1="41" x2="25" y2="48" stroke="rgba(201,162,0,.5)" stroke-width="1.5" stroke-linecap="round"/><line x1="2" y1="25" x2="9" y2="25" stroke="rgba(201,162,0,.5)" stroke-width="1.5" stroke-linecap="round"/><line x1="41" y1="25" x2="48" y2="25" stroke="rgba(201,162,0,.5)" stroke-width="1.5" stroke-linecap="round"/><line x1="7" y1="7" x2="12" y2="12" stroke="rgba(201,162,0,.5)" stroke-width="1.5" stroke-linecap="round"/><line x1="38" y1="38" x2="43" y2="43" stroke="rgba(201,162,0,.5)" stroke-width="1.5" stroke-linecap="round"/><line x1="43" y1="7" x2="38" y2="12" stroke="rgba(201,162,0,.5)" stroke-width="1.5" stroke-linecap="round"/><line x1="7" y1="43" x2="12" y2="38" stroke="rgba(201,162,0,.5)" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <span class="font-cinzel relative z-10" style="color:#C9A200;font-size:1.35rem;line-height:1;" aria-hidden="true">✝</span>
                </div>
            </div>
            <div class="eyebrow mb-3">UPCOMING EVENTS</div>
            <h2 class="font-heading font-bold italic events-section-title">What's Happening</h2>
            <div class="flex justify-center mb-4"><div style="width:7px;height:7px;background:rgba(201,162,0,.42);transform:rotate(45deg);"></div></div>
            <p class="events-section-desc">Stay connected. Join us in our liturgical celebrations and events.</p>
        </div>

        <div class="events-grid grid md:grid-cols-3 gap-8 mb-8 reveal reveal-stagger">
            @if($nextMass)
            <div class="card-event card-event-featured relative">
                <div class="event-badge-today">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    <span style="font-size:11px;font-weight:700;letter-spacing:.18em;color:#C9A200;text-transform:uppercase;">TODAY</span>
                </div><br>
                <div class="flex flex-col items-center text-center pt-14 pb-5 px-6" style="flex:1;">
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mb-4" style="background:rgba(245,197,24,.07);border:1.5px solid rgba(201,162,0,.28);">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M8 22h8"/><path d="M12 11v11"/><path d="M5 3h14L18 9a6 6 0 0 1-12 0L5 3z"/><path d="M3 3h18"/></svg>
                    </div>
                    <p style="font-size:12px;letter-spacing:.22em;color:rgba(13,42,82,.5);text-transform:uppercase;font-weight:600;margin-bottom:10px;">{{ strtoupper($nextMass->title ?? ($nextMass->mass_type === 'sunday' ? 'Sunday Mass' : 'Weekday Mass')) }}</p>
                    <div class="flex justify-center mb-3"><div style="width:5px;height:5px;background:rgba(201,162,0,.45);transform:rotate(45deg);"></div></div>
                    <p class="font-heading font-bold" style="font-size:clamp(1.8rem,3vw,2.5rem);color:var(--blue-deep);line-height:1.05;">{{ $nextMass->calculated_time }}</p>
                </div>
            </div>
            @endif

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
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="rgba(13,42,82,.4)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                        <span style="font-size:12px;letter-spacing:.14em;color:rgba(13,42,82,.4);text-transform:uppercase;font-weight:500;">{{ $evt->event_date->format('M d, Y') }}</span>
                    </div>
                    <div class="w-16 h-16 rounded-full flex items-center justify-center mb-4" style="background:rgba(245,197,24,.07);border:1.5px solid rgba(201,162,0,.28);">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $evtIcons[$loop->index % 2] !!}</svg>
                    </div>
                    <p style="font-size:12px;letter-spacing:.22em;color:rgba(13,42,82,.5);text-transform:uppercase;font-weight:600;margin-bottom:10px;">{{ strtoupper($evt->title) }}</p>
                    <div class="flex justify-center mb-3"><div style="width:5px;height:5px;background:rgba(201,162,0,.45);transform:rotate(45deg);"></div></div>
                    <p style="font-size:15px;color:var(--blue-deep);line-height:1.8;">
                        @php
                            $times = is_array($evt->event_time) ? $evt->event_time : [$evt->event_time];
                            $formattedTimes = array_map(function($t) {
                                if (is_array($t)) {
                                    $timeStr = $t['time'] ?? '';
                                    if (!empty($t['title'])) $timeStr .= " ({$t['title']})";
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

            @for($evtFill = 0; $evtFill < (2 - $upcomingEvents->count()); $evtFill++)
            <div class="card-event" style="opacity:.38;">
                <div class="flex flex-col items-center text-center px-6 py-10" style="flex:1;justify-content:center;">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center mb-4" style="background:rgba(26,64,128,.04);border:1px solid rgba(26,64,128,.08);">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="rgba(13,42,82,.25)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    </div>
                    <p style="font-size:11px;color:rgba(13,42,82,.3);">No upcoming event</p>
                </div>
            </div>
            @endfor
        </div>

        <a href="{{ route('events') }}" class="group relative flex flex-col items-center justify-center overflow-hidden rounded-2xl events-cta-banner" aria-label="View full events schedule">
            <div class="absolute left-0 top-[70%] -translate-y-1/2 pointer-events-none transition-transform duration-700 group-hover:scale-110" style="opacity:.5;height:150%;width:auto;" aria-hidden="true">
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('supabase')->url('assets/img/parish-illustration.svg') }}" alt="Parish Illustration" width="285" height="135" style="height:90%;width:auto;object-fit:contain;filter:brightness(0) invert(1);">
            </div>
            <div class="relative z-10 flex flex-col items-center gap-2">
                <div class="flex items-center gap-3">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    <span class="font-cinzel events-cta-label">View Full Schedule</span>
                </div>
                <span class="events-cta-arrow block" aria-hidden="true">→</span>
            </div>
        </a>
    </div><br><br>
</section>

<section class="relative py-24 overflow-hidden reveal section-pad-mobile section-pad-tablet">
    <div class="ann-section-bg" aria-hidden="true">
        <img src="{{ \Illuminate\Support\Facades\Storage::disk('supabase')->url('assets/img/church1.webp') }}" alt="" loading="lazy" width="1200" height="800">
        <div class="ann-section-bg-overlay"></div>
    </div>

    @php
    $annImgs = [
        \Illuminate\Support\Facades\Storage::disk('supabase')->url('assets/img/church1.webp'),
        \Illuminate\Support\Facades\Storage::disk('supabase')->url('assets/img/mass.webp'),
        \Illuminate\Support\Facades\Storage::disk('supabase')->url('assets/img/church1.webp'),
    ];
    $annCats = ['Liturgical', 'Parish Life', 'Parish Event'];
    @endphp

    <div class="relative z-10 max-w-6xl mx-auto px-6 section-px-mobile">
        <div class="ann-header-row relative flex items-start justify-between mb-14">
            <div class="w-24 shrink-0 hidden sm:block ann-header-spacer"></div>
            <div class="flex-1 text-center">
                <div class="flex items-center justify-center gap-4 mb-5">
                    <span style="display:block;flex:1;max-width:60px;height:1px;background:linear-gradient(90deg,transparent,rgba(201,162,0,.4));"></span>
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#C9A200" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/><line x1="12" y1="2" x2="12" y2="0"/><line x1="10" y1="1" x2="14" y2="1"/></svg>
                    <span style="display:block;flex:1;max-width:60px;height:1px;background:linear-gradient(90deg,rgba(201,162,0,.4),transparent);"></span>
                </div>
                <h2 class="font-cinzel font-semibold ann-title">LATEST ANNOUNCEMENTS</h2>
                <p class="ann-desc">Stay informed. Be involved. Grow in faith together.</p>
                <div class="flex justify-center"><div style="width:6px;height:6px;background:rgba(201,162,0,.42);transform:rotate(45deg);"></div></div>
            </div>
            <div class="ann-nav-btns hidden sm:flex items-center gap-2 shrink-0 pt-1">
                <button onclick="document.getElementById('ann-grid').scrollBy({left:-340,behavior:'smooth'})" class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200" style="border:1.5px solid rgba(13,42,82,.16);color:rgba(13,42,82,.4);background:#fff;" aria-label="Previous announcements">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                </button>
                <button onclick="document.getElementById('ann-grid').scrollBy({left:340,behavior:'smooth'})" class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200" style="border:1.5px solid rgba(13,42,82,.16);color:rgba(13,42,82,.4);background:#fff;" aria-label="Next announcements">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                </button>
            </div>
        </div>

        @if($announcements->isEmpty())
        <div class="text-center py-16">
            <p style="color:rgba(13,42,82,.3);font-size:14px;">No announcements at this time.</p>
        </div>
        @else
        <div id="ann-grid" class="ann-grid grid md:grid-cols-3 gap-5 mb-10 reveal reveal-stagger">
            @foreach($announcements as $ann)
            @php $aIdx = $loop->index; $aIsFirst = $loop->first; @endphp
            <article class="card-sacred overflow-hidden flex flex-col group/ann">
                <div class="relative overflow-hidden shrink-0" style="height:196px;">
                    <img src="{{ $annImgs[$aIdx % 3] }}" alt="{{ $ann->title }}" class="ann-card-img">
                    @if($aIsFirst)
                    <div class="ann-featured-badge">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="#FFFFFF" stroke="none" aria-hidden="true"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        <span class="ann-featured-text">FEATURED</span>
                    </div>
                    @endif
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="shrink-0 text-center ann-date-badge">
                            <div class="ann-date-month">{{ ($ann->published_at ?? $ann->created_at)->format('M') }}</div>
                            <div class="font-heading font-bold ann-date-day">{{ ($ann->published_at ?? $ann->created_at)->format('d') }}</div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="eyebrow mb-1" style="font-size:8.5px;">{{ strtoupper($annCats[$aIdx % 3]) }}</p>
                            <h3 class="font-heading font-bold italic leading-snug ann-card-title">{{ $ann->title }}</h3>
                        </div>
                    </div>
                    @if($ann->content)
                    <p class="text-sm leading-relaxed flex-1 ann-card-excerpt">{{ mb_substr(strip_tags($ann->content), 0, 130) }}</p>
                    @endif
                </div>
                @if($ann->is_recruitment && $ann->registration_link)
                <div class="px-5 pb-5 mt-auto">
                    <a href="{{ $ann->registration_link }}" target="_blank" style="display:flex;align-items:center;justify-content:center;gap:8px;padding:10px 0;border-radius:12px;font-size:10px;font-weight:800;letter-spacing:.15em;text-transform:uppercase;text-decoration:none;" class="gold-btn group/reg transition-all hover:scale-[1.02] active:scale-95 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                        Register Now
                    </a>
                </div>
                @endif
            </article>
            @endforeach
        </div>
        @endif
    </div>
</section>

<section class="py-28 relative overflow-hidden reveal reveal-up section-pad-mobile section-pad-tablet services-section">
    <div class="services-section-bg-cross">✝</div>
    <div class="services-section-glow"></div>
    <div class="services-section-top-line"></div>

    <div class="max-w-[1200px] mx-auto px-6 section-px-mobile relative z-10"><br><br>
        <div class="text-center mb-16">
            <div class="divider-ornament mb-4"><span style="font-size:10px;font-weight:600;letter-spacing:.35em;text-transform:uppercase;color:rgba(245,197,24,.65);">Sacramental Services</span></div>
            <h2 class="font-heading text-4xl md:text-5xl font-bold italic" style="color:#EBF2FF;">How We Serve</h2>
            <p class="mt-4 text-sm font-light" style="color:rgba(235,242,255,.4);letter-spacing:.05em;">Inquire about any sacramental service at our parish office.</p>
        </div>

        <div class="services-grid grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 max-w-5xl mx-auto reveal reveal-stagger">
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
            <a href="{{ $s['href'] }}" class="group service-card">
                <div class="service-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgba(235,242,255,.55)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="transition-all duration-300">{!! $s['svg'] !!}</svg>
                </div>
                <span class="service-card-label">{{ $s['label'] }}</span>
            </a>
            @endforeach
        </div>
    </div><br><br>

    <div class="services-section-bottom-line"></div>
</section>

<section class="py-28 relative overflow-hidden bg-[var(--cream)] reveal reveal-up section-pad-mobile section-pad-tablet">
    <div class="intention-section-bg-glow"></div>

    <div class="max-w-[1200px] mx-auto px-6 section-px-mobile relative z-10">
        <div class="max-w-2xl mx-auto text-center">
            <div class="font-cinzel text-4xl mb-8 opacity-60" style="color:var(--gold);">✝</div>
            <div class="divider-ornament mb-6"><span class="eyebrow">Unite Your Prayers</span></div>
            <h2 class="font-heading text-4xl md:text-6xl font-bold italic leading-[1.05] mb-6 intention-section-title">Offer a Mass<br>Intention</h2>
            <p class="text-lg leading-relaxed mb-12 font-light intention-section-desc">Unite your prayers with the Holy Sacrifice of the Mass. Submit your intention online and our staff will include it in the upcoming liturgy.</p>

            <div class="intention-btns">
                <a href="{{ route('submit-intention') }}" class="gold-btn inline-flex items-center gap-2.5 px-10 py-4 rounded-full text-[11px] font-bold uppercase tracking-widest" style="text-decoration:none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/></svg>
                    Submit Intention
                </a>
                <a href="{{ route('track') }}" class="inline-flex items-center gap-2.5 px-10 py-4 rounded-full text-[11px] font-bold uppercase tracking-widest border-2 transition-all duration-300 hover:-translate-y-0.5" style="border-color:rgba(26,64,128,.25);color:var(--blue-deep);background:transparent;text-decoration:none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Track Status
                </a>
            </div>

            <p class="mt-16 text-[10px] font-medium uppercase tracking-[0.4em]" style="color:rgba(13,42,82,.25);">Sto. Rosario Parish · Pacita, San Pedro, Laguna</p>
        </div>
    </div>
</section>

</x-public-layout>
