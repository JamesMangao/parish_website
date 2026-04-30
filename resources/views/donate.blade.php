<x-public-layout>
    <x-slot name="meta">
        <meta name="description" content="Support Sto. Rosario Parish. Give your tithes, digital offerings, and donations to help our church maintenance and community programs.">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600;1,700&family=Cinzel:wght@400;500;600&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
        <style>
            :root {
                --gold: #F5C518; --gold-light: #FFD740;
                --blue-deep: #0D2A52; --blue-mid: #1A4080;
                --blue-pale: #EBF2FF; --cream: #F7F9FF;
            }
            body { background: var(--cream); font-family: 'Jost', sans-serif; color: var(--blue-deep); }
            .font-heading { font-family: 'Cormorant Garamond', Georgia, serif; }
            .font-cinzel  { font-family: 'Cinzel', Georgia, serif; }

            @keyframes shimmer { 0%{background-position:-200% center} 100%{background-position:200% center} }
            @keyframes spin-slow { from{transform:rotate(0deg)} to{transform:rotate(360deg)} }
            .hero-accent {
                background: linear-gradient(90deg,#FFD740 0%,#FFFFFF 40%,#FFD740 60%,#F5C518 100%);
                background-size: 200% auto; -webkit-background-clip: text; background-clip: text;
                -webkit-text-fill-color: transparent; animation: shimmer 4s linear infinite;
            }
            .reveal { opacity:0; transform:translateY(40px); transition: all 1.2s cubic-bezier(0.22,1,0.36,1); }
            .reveal.active { opacity:1; transform:translateY(0); }
        </style>
    </x-slot>

    {{-- ═══════════ HERO ═══════════ --}}
    <section style="background:var(--blue-deep);position:relative;overflow:hidden;padding:100px 24px 80px;text-align:center;">
        <div style="position:absolute;inset:0;background:radial-gradient(ellipse 70% 60% at 50% 110%,rgba(245,197,24,0.10) 0%,transparent 70%);pointer-events:none;"></div>
        <div style="position:absolute;width:520px;height:520px;border-radius:50%;border:1px solid rgba(245,197,24,0.06);top:50%;left:50%;transform:translate(-50%,-50%);animation:spin-slow 60s linear infinite;pointer-events:none;"></div>
        <div style="position:absolute;top:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,rgba(245,197,24,0.4),transparent);"></div>
        <div style="position:relative;z-index:10;max-width:600px;margin:0 auto;">
            <div style="font-size:10px;font-weight:600;letter-spacing:.32em;text-transform:uppercase;color:rgba(245,197,24,0.8);margin-bottom:18px;">Stewardship &amp; Giving</div>
            <div class="font-cinzel" style="color:var(--gold);font-size:1.35rem;opacity:.65;margin-bottom:16px;text-shadow:0 0 20px rgba(245,197,24,0.5);">✝</div>
            <h1 class="font-heading" style="font-weight:700;font-style:italic;font-size:clamp(2.4rem,6vw,4.5rem);line-height:1.05;color:#fff;letter-spacing:-0.02em;margin-bottom:16px;">
                Support <em class="hero-accent">Our Parish</em>
            </h1>
            <div style="width:56px;height:1px;margin:0 auto 20px;background:linear-gradient(90deg,transparent,rgba(245,197,24,0.65),transparent);"></div>
            <p style="color:rgba(235,242,255,0.6);font-size:clamp(0.85rem,1.4vw,1rem);line-height:1.78;font-weight:300;">
                Your tithes and donations help us maintain our sacred space, support our<br class="hidden md:block"> community outreach programs, and continue our mission of spiritual service.
            </p>
        </div>
        <div style="position:absolute;bottom:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,rgba(245,197,24,0.2),transparent);"></div>
    </section>

    {{-- ═══════════ MAIN ═══════════ --}}
    <div class="container py-16 mx-auto px-4 max-w-4xl" x-data="{ copiedG: false, copiedM: false }">
        <div class="grid md:grid-cols-2 gap-8 items-start reveal">

            {{-- ── QR Card ── --}}
            <div style="background:#fff;border-radius:2.5rem;border:1px solid rgba(26,64,128,0.12);box-shadow:0 20px 60px rgba(13,42,82,0.10);padding:2rem;position:sticky;top:6rem;overflow:hidden;" class="group">
                <div style="position:absolute;inset:0;background:rgba(13,42,82,0.02);opacity:0;transition:opacity .3s;" class="group-hover:opacity-100"></div>
                <div style="position:relative;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;">
                        <div>
                            <p style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.25em;color:rgba(13,42,82,0.35);">Digital Offerings</p>
                            <h2 class="font-heading" style="font-size:1.5rem;font-weight:700;color:var(--blue-deep);text-transform:uppercase;margin-top:2px;">Maya &amp; GCash</h2>
                        </div>
                        <div style="height:48px;width:48px;border-radius:14px;background:var(--gold);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(245,197,24,0.35);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--blue-deep)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3"/><path d="M21 21v.01"/><path d="M12 7v3"/><path d="M7 12h3"/><path d="M12 20v-5"/><path d="M17 12h.01"/><path d="M7 12v.01"/><path d="M12 12v.01"/><path d="M16 7h5"/><path d="M21 12h-3a2 2 0 0 0-2 2v3"/><path d="M21 16v.01"/><path d="M16 21h.01"/><path d="M12 17v.01"/><path d="M17 17v.01"/><path d="M16 12v5"/><path d="M7 7h.01"/></svg>
                        </div>
                    </div>

                    <div style="padding:1.5rem;background:#F7F9FF;border-radius:1.5rem;border:1px solid rgba(26,64,128,0.08);margin-bottom:2rem;transition:transform .3s ease;" class="group-hover:scale-[1.02]">
                        @if(isset($global_settings['qr_code']))
                            <img src="{{ asset('storage/' . $global_settings['qr_code']) }}"
                                 alt="Parish Donation QR"
                                 style="width:100%;height:auto;border-radius:12px;">
                        @else
                            <div style="aspect-ratio:1;display:flex;flex-direction:column;align-items:center;justify-content:center;border:2px dashed rgba(26,64,128,0.15);border-radius:12px;color:rgba(13,42,82,0.3);padding:2rem;text-align:center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:1rem;opacity:.2;"><rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3"/><path d="M21 21v.01"/><path d="M12 7v3"/><path d="M7 12h3"/><path d="M12 20v-5"/><path d="M17 12h.01"/><path d="M7 12v.01"/><path d="M12 12v.01"/><path d="M16 7h5"/><path d="M21 12h-3a2 2 0 0 0-2 2v3"/><path d="M21 16v.01"/><path d="M16 21h.01"/><path d="M12 17v.01"/><path d="M17 17v.01"/><path d="M16 12v5"/><path d="M7 7h.01"/></svg>
                                <p style="font-size:.875rem;font-style:italic;">Parish QR code not yet available. Please use the account details below.</p>
                            </div>
                        @endif
                    </div>

                    <div style="display:flex;flex-direction:column;gap:12px;">
                        {{-- GCash number --}}
                        <div style="display:flex;align-items:center;justify-content:space-between;padding:1rem;background:rgba(13,42,82,0.03);border-radius:16px;border:1.5px dashed rgba(26,64,128,0.12);cursor:pointer;transition:all .2s;"
                            @click="navigator.clipboard.writeText('{{ $global_settings['gcash_number'] ?? 'Not set' }}'); copiedG = true; setTimeout(() => copiedG = false, 2000)"
                            onmouseenter="this.style.borderColor='rgba(245,197,24,0.5)';this.style.background='rgba(245,197,24,0.04)'"
                            onmouseleave="this.style.borderColor='rgba(26,64,128,0.12)';this.style.background='rgba(13,42,82,0.03)'">
                            <div style="display:flex;align-items:center;gap:12px;">
                                <div style="width:32px;height:32px;border-radius:50%;background:rgba(13,42,82,0.08);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:var(--blue-deep);">G</div>
                                <div>
                                    <p style="font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.2em;color:rgba(13,42,82,0.35);">GCash Number</p>
                                    <p style="font-weight:700;font-size:.875rem;color:var(--blue-deep);">{{ $global_settings['gcash_number'] ?? 'Not set' }}</p>
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <span x-show="copiedG" x-cloak style="font-size:9px;font-weight:800;color:var(--gold);">COPIED!</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="rgba(245,197,24,0.7)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                            </div>
                        </div>
                        {{-- Account name --}}
                        <div style="display:flex;align-items:center;justify-content:space-between;padding:1rem;background:rgba(13,42,82,0.03);border-radius:16px;border:1.5px dashed rgba(26,64,128,0.12);cursor:pointer;transition:all .2s;"
                            @click="navigator.clipboard.writeText('{{ $global_settings['gcash_name'] ?? 'Not set' }}'); copiedM = true; setTimeout(() => copiedM = false, 2000)"
                            onmouseenter="this.style.borderColor='rgba(245,197,24,0.5)';this.style.background='rgba(245,197,24,0.04)'"
                            onmouseleave="this.style.borderColor='rgba(26,64,128,0.12)';this.style.background='rgba(13,42,82,0.03)'">
                            <div style="display:flex;align-items:center;gap:12px;">
                                <div style="width:32px;height:32px;border-radius:50%;background:var(--blue-pale);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:var(--blue-mid);">A</div>
                                <div>
                                    <p style="font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.2em;color:rgba(13,42,82,0.35);">Account Name</p>
                                    <p style="font-weight:700;font-size:.875rem;color:var(--blue-deep);">{{ $global_settings['gcash_name'] ?? 'Not set' }}</p>
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <span x-show="copiedM" x-cloak style="font-size:9px;font-weight:800;color:var(--gold);">COPIED!</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="rgba(245,197,24,0.7)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Right Column ── --}}
            <div style="display:flex;flex-direction:column;gap:24px;">

                {{-- Where it goes --}}
                <div style="padding:2rem;background:#fff;border-radius:1.5rem;border:1px solid rgba(26,64,128,0.1);box-shadow:0 4px 24px rgba(13,42,82,0.06);">
                    <div style="font-size:10px;font-weight:700;letter-spacing:.2em;text-transform:uppercase;color:rgba(13,42,82,0.35);margin-bottom:6px;">Transparency</div>
                    <h3 class="font-heading" style="font-size:1.4rem;font-weight:700;color:var(--blue-deep);margin-bottom:1.5rem;">Where your donation goes</h3>
                    <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:1.5rem;">
                        <li style="display:flex;align-items:flex-start;gap:1rem;">
                            <div style="width:40px;height:40px;flex-shrink:0;background:rgba(13,42,82,0.06);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--blue-deep)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/><path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/><path d="M12 3v6"/></svg>
                            </div>
                            <div>
                                <p style="font-weight:700;font-size:.9rem;color:var(--blue-deep);">Church Maintenance</p>
                                <p style="font-size:.8rem;color:rgba(13,42,82,0.5);line-height:1.6;margin-top:3px;">Preserving the beauty and structural integrity of our 100-year-old parish temple.</p>
                            </div>
                        </li>
                        <li style="display:flex;align-items:flex-start;gap:1rem;">
                            <div style="width:40px;height:40px;flex-shrink:0;background:rgba(245,197,24,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.505 4.04 3 5.5L12 21l7-7Z"/></svg>
                            </div>
                            <div>
                                <p style="font-weight:700;font-size:.9rem;color:var(--blue-deep);">Pantry Community Outreach</p>
                                <p style="font-size:.8rem;color:rgba(13,42,82,0.5);line-height:1.6;margin-top:3px;">Providing weekly food supplies and scholarships to under-privileged families in the parish.</p>
                            </div>
                        </li>
                        <li style="display:flex;align-items:flex-start;gap:1rem;">
                            <div style="width:40px;height:40px;flex-shrink:0;background:rgba(26,64,128,0.08);border-radius:12px;display:flex;align-items:center;justify-content:center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--blue-mid)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22v-4"/><path d="M12 13V2"/><path d="M5.4 6a3.6 3.6 0 1 0 13.2 0"/><path d="M12 8a3.6 3.6 0 1 0 0-7.2A3.6 3.6 0 0 0 12 8Z"/></svg>
                            </div>
                            <div>
                                <p style="font-weight:700;font-size:.9rem;color:var(--blue-deep);">Youth Ministry</p>
                                <p style="font-size:.8rem;color:rgba(13,42,82,0.5);line-height:1.6;margin-top:3px;">Empowering the next generation of leaders through spiritual retreats and skill-building workshops.</p>
                            </div>
                        </li>
                    </ul>
                </div>

                {{-- Scripture quote --}}
                <div style="padding:2rem;background:var(--blue-deep);border-radius:1.5rem;position:relative;overflow:hidden;">
                    <div style="position:absolute;top:1/2;left:1/2;transform:translate(-50%,-50%);font-family:'Cinzel',serif;font-size:180px;color:rgba(255,255,255,0.03);pointer-events:none;line-height:1;">✝</div>
                    <div style="position:absolute;top:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,rgba(245,197,24,0.4),transparent);"></div>
                    <div style="position:relative;z-index:10;">
                        <div style="color:rgba(245,197,24,0.5);font-size:2rem;margin-bottom:12px;font-family:'Cinzel',serif;">"</div>
                        <p style="font-size:.9rem;font-style:italic;line-height:1.8;color:rgba(235,242,255,0.8);margin-bottom:1rem;">
                            Each of you should give what you have decided in your heart to give, not reluctantly or under compulsion, for God loves a cheerful giver.
                        </p>
                        <p style="font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:.25em;color:var(--gold);">– 2 Corinthians 9:7</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const obs = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('active'); obs.unobserve(e.target); } });
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
    });
    </script>
</x-public-layout>