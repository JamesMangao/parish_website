<x-public-layout>
<x-slot name="meta">
    <meta name="description" content="Parish inquiries – sacraments, blessings, and document requests at Sto. Rosario Parish, Pacita, San Pedro, Laguna.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600;1,700&family=Cinzel:wght@400;500;600&family=Jost:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --gold:       #F5C518;
            --gold-light: #FFD740;
            --blue-deep:  #0D2A52;
            --blue-mid:   #1A4080;
            --blue-soft:  #2255A4;
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

        @keyframes fadeUp { from { opacity:0; transform:translateY(24px); } to { opacity:1; transform:translateY(0); } }
        @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
        .animate-fade-up { animation: fadeUp 0.9s ease both; }
        .animate-fade-in { animation: fadeIn 1.2s ease both; }
        .delay-1 { animation-delay: 0.15s; }
        .delay-2 { animation-delay: 0.30s; }

        .reveal { opacity:0; transform:translateY(40px); transition: all 1.2s cubic-bezier(0.22,1,0.36,1); }
        .reveal.active { opacity:1; transform:translateY(0); }

        /* ── Hero ── */
        .page-hero {
            background: linear-gradient(160deg, var(--blue-deep) 0%, var(--blue-mid) 60%, #0f3060 100%);
            position: relative; overflow: hidden;
        }
        .page-hero::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(ellipse 70% 60% at 50% 0%, rgba(245,197,24,0.10) 0%, transparent 70%);
            pointer-events: none;
        }

        /* ── Main card ── */
        .inquiry-card {
            background: #FFF;
            border: 1px solid rgba(26,64,128,0.12);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 12px 50px rgba(13,42,82,0.09);
        }

        /* ── Sidebar ── */
        .sidebar {
            background: linear-gradient(175deg, var(--blue-deep) 0%, var(--blue-mid) 100%);
            position: relative; overflow: hidden;
            padding: 40px 36px;
        }
        .sidebar::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(ellipse 80% 50% at 50% 0%, rgba(245,197,24,0.08) 0%, transparent 70%);
            pointer-events: none;
        }
        .sidebar-step {
            display: flex; align-items: flex-start; gap: 14px;
        }
        .step-num {
            flex-shrink: 0; width: 22px; height: 22px;
            border-radius: 50%;
            border: 1px solid rgba(245,197,24,0.40);
            background: rgba(245,197,24,0.08);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Cinzel', Georgia, serif;
            font-size: 9px; font-weight: 600;
            color: rgba(245,197,24,0.80);
            margin-top: 2px; flex-shrink: 0;
        }
        .sidebar-contact {
            display: flex; align-items: center; gap: 12px;
            font-size: 13px; color: rgba(235,242,255,0.60);
        }
        .sidebar-contact svg { flex-shrink: 0; opacity: 0.55; }

        /* ── Inputs ── */
        .sacred-input {
            width: 100%; height: 48px;
            border: 1px solid rgba(26,64,128,0.16);
            border-radius: 12px;
            background: var(--cream);
            padding: 0 16px;
            font-family: 'Jost', sans-serif;
            font-size: 14px; font-weight: 500;
            color: var(--blue-deep);
            transition: all 0.2s ease;
            outline: none; appearance: none;
        }
        .sacred-input:focus {
            border-color: rgba(245,197,24,0.60);
            background: #FFF;
            box-shadow: 0 0 0 3px rgba(245,197,24,0.10);
        }
        .sacred-input::placeholder { color: rgba(13,42,82,0.28); }
        textarea.sacred-input { height: auto; padding: 14px 16px; resize: none; }

        .field-label {
            display: block;
            font-size: 9.5px; font-weight: 700;
            letter-spacing: 0.28em; text-transform: uppercase;
            color: rgba(13,42,82,0.45);
            margin-bottom: 7px; margin-left: 2px;
        }

        /* ── Gold btn ── */
        .gold-btn {
            background: linear-gradient(135deg, #FFD740 0%, #F5C518 55%, #E0A800 100%);
            color: var(--blue-deep);
            box-shadow: 0 4px 20px rgba(245,197,24,0.40), 0 1px 0 rgba(255,255,255,0.35) inset;
            transition: all 0.25s ease; border: none; cursor: pointer;
            font-weight: 700;
        }
        .gold-btn:hover:not(:disabled) {
            box-shadow: 0 8px 32px rgba(245,197,24,0.55), 0 1px 0 rgba(255,255,255,0.35) inset;
            transform: translateY(-2px);
        }
        .gold-btn:disabled { opacity: 0.65; cursor: not-allowed; }

        /* ── Success alert ── */
        .success-alert {
            display: flex; align-items: flex-start; gap: 14px;
            padding: 18px 22px; border-radius: 16px;
            background: rgba(16,185,129,0.06);
            border: 1px solid rgba(16,185,129,0.25);
            margin-bottom: 28px;
        }
        .success-icon {
            width: 34px; height: 34px; flex-shrink: 0;
            border-radius: 50%;
            background: rgba(16,185,129,0.12);
            border: 1px solid rgba(16,185,129,0.30);
            display: flex; align-items: center; justify-content: center;
            margin-top: 1px;
        }
    </style>
</x-slot>

{{-- ═══════════════════════════════════════════════════ --}}
{{-- PAGE HERO                                          --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="page-hero py-24 md:py-32">
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none select-none font-cinzel leading-none"
         style="font-size:340px; color:rgba(255,255,255,0.018);" aria-hidden="true">✝</div>
    <div class="absolute top-0 left-0 right-0 h-px"
         style="background:linear-gradient(90deg,transparent,rgba(245,197,24,0.35),transparent);"></div>

    <div class="max-w-[1200px] mx-auto px-6 relative z-10 text-center">
        {{-- Cross icon --}}
        <div class="flex justify-center mb-6 animate-fade-in">
            <div class="relative flex items-center justify-center" style="width:52px;height:52px;">
                <svg width="52" height="52" viewBox="0 0 52 52" style="position:absolute;inset:0;" fill="none" aria-hidden="true">
                    <line x1="26" y1="2"  x2="26" y2="10" stroke="rgba(245,197,24,0.55)" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="26" y1="42" x2="26" y2="50" stroke="rgba(245,197,24,0.55)" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="2"  y1="26" x2="10" y2="26" stroke="rgba(245,197,24,0.55)" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="42" y1="26" x2="50" y2="26" stroke="rgba(245,197,24,0.55)" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="8"  y1="8"  x2="13" y2="13" stroke="rgba(245,197,24,0.55)" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="39" y1="39" x2="44" y2="44" stroke="rgba(245,197,24,0.55)" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="44" y1="8"  x2="39" y2="13" stroke="rgba(245,197,24,0.55)" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="8"  y1="44" x2="13" y2="39" stroke="rgba(245,197,24,0.55)" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                <span class="font-cinzel relative z-10" style="color:var(--gold);font-size:1.5rem;" aria-hidden="true">✝</span>
            </div>
        </div>

        <div class="divider-ornament mb-4 animate-fade-in delay-1">
            <span class="eyebrow" style="color:rgba(245,197,24,0.70);">Sacraments & Services</span>
        </div>

        <h1 class="font-heading animate-fade-up delay-1"
            style="font-size:clamp(2.8rem,6vw,5.5rem); font-weight:700; font-style:italic;
                   color:#FFF; line-height:1.05; letter-spacing:-0.01em; margin-bottom:16px;
                   text-shadow:0 4px 32px rgba(0,0,0,0.45);">
            Parish Inquiries
        </h1>

        <p class="font-heading animate-fade-up delay-2"
           style="font-style:italic; color:rgba(245,197,24,0.75);
                  font-size:clamp(0.9rem,1.8vw,1.1rem); font-weight:300;">
            Pacita Complex 1, San Pedro, Laguna
        </p>
    </div>

    <div class="absolute bottom-0 left-0 right-0 h-px"
         style="background:linear-gradient(90deg,transparent,rgba(245,197,24,0.25),transparent);"></div>
</section>


{{-- ═══════════════════════════════════════════════════ --}}
{{-- CONTENT                                            --}}
{{-- ═══════════════════════════════════════════════════ --}}
<section class="py-20 max-w-[960px] mx-auto px-6">

    {{-- Success alert --}}
    @if(session('success'))
    <div class="success-alert reveal active">
        <div class="success-icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                 stroke="rgb(16,185,129)" stroke-width="2.5"
                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M20 6 9 17l-5-5"/>
            </svg>
        </div>
        <div>
            <p class="eyebrow mb-1" style="color:rgb(6,120,83);">Submitted</p>
            <p style="font-size:13.5px; color:rgb(6,95,70); font-weight:500; line-height:1.7;">
                {{ session('success') }}
            </p>
        </div>
    </div>
    @endif

    {{-- Card --}}
    <div class="inquiry-card reveal">
        <div class="grid md:grid-cols-5">

            {{-- ── SIDEBAR ── --}}
            <aside class="sidebar md:col-span-2">
                <div class="relative z-10">
                    <p class="eyebrow mb-2" style="color:rgba(245,197,24,0.65);">How It Works</p>
                    <h2 class="font-heading font-bold italic mb-8"
                        style="font-size:1.85rem; color:#FFF; line-height:1.15;">
                        Sacramental Services
                    </h2>

                    <div class="space-y-6 mb-12">
                        @foreach([
                            'Fill out the inquiry form with your contact details and specific needs.',
                            'Our SocCom or Admin team will validate your request.',
                            'Once accepted, forwarded to the Parish Office for processing.',
                            'Document Requests (Baptismal, etc.) — allow 3–5 working days for verification.',
                        ] as $i => $step)
                        <div class="sidebar-step">
                            <div class="step-num">{{ $i + 1 }}</div>
                            <p style="font-size:13px; color:rgba(235,242,255,0.65); line-height:1.75;">{{ $step }}</p>
                        </div>
                        @endforeach
                    </div>

                    {{-- Gold rule --}}
                    <div style="height:1px; background:linear-gradient(90deg,rgba(245,197,24,0.25),transparent); margin-bottom:28px;"></div>

                    <div class="space-y-4">
                        <div class="sidebar-contact">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            +63 2 8869 2742
                        </div>
                        <div class="sidebar-contact">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 13V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2h9"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/><path d="M19 16v6"/><path d="M16 19h6"/></svg>
                            <span style="word-break:break-all;">{{ config('services.parish.office_email') }}</span>
                        </div>
                    </div>
                </div>
            </aside>

            {{-- ── FORM ── --}}
            <div class="md:col-span-3 p-8 md:p-10" style="background:#FFF;"
                 x-data="{ loading: false, inquiryType: '' }">

                <p class="eyebrow mb-1">Contact Form</p>
                <h3 class="font-heading font-bold italic mb-8"
                    style="font-size:1.6rem; color:var(--blue-deep); line-height:1.2;">
                    Send Your Inquiry
                </h3>

                <form action="{{ route('inquiry.store') }}" method="POST"
                      class="space-y-5" @submit="loading = true">
                    @csrf

                    {{-- Full Name --}}
                    <div>
                        <label class="field-label" for="fullName">Full Name</label>
                        <input name="fullName" id="fullName" required
                               placeholder="Juan Dela Cruz"
                               class="sacred-input">
                    </div>

                    {{-- Email + Phone --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="field-label" for="email">Email</label>
                            <input type="email" name="email" id="email" required
                                   placeholder="juan@example.com"
                                   class="sacred-input">
                        </div>
                        <div>
                            <label class="field-label" for="phone">Phone <span style="opacity:0.45;">(Optional)</span></label>
                            <input name="phone" id="phone"
                                   placeholder="0912 345 6789"
                                   class="sacred-input">
                        </div>
                    </div>

                    {{-- Inquiry Type + Preferred Date --}}
                    <div :class="['Baptism','Wedding','Funeral Mass'].includes(inquiryType)
                                 ? 'grid grid-cols-1 md:grid-cols-2 gap-4'
                                 : ''">
                        <div>
                            <label class="field-label" for="inquiryType">Inquiry Type</label>
                            <div class="relative">
                                <select name="inquiryType" id="inquiryType" required
                                        x-model="inquiryType"
                                        class="sacred-input" style="padding-right:42px;">
                                    <option value="" disabled selected>Select a service…</option>
                                    <optgroup label="Sacramental Rites">
                                        <option value="Baptism">Baptism</option>
                                        <option value="First Communion">First Communion</option>
                                        <option value="Confirmation">Confirmation</option>
                                        <option value="Wedding">Wedding</option>
                                        <option value="Funeral Mass">Funeral Mass</option>
                                    </optgroup>
                                    <optgroup label="Document Requests">
                                        <option value="Baptismal Certificate">Baptismal Certificate</option>
                                        <option value="Confirmation Certificate">Confirmation Certificate</option>
                                        <option value="Marriage Certificate">Marriage Certificate</option>
                                    </optgroup>
                                    <optgroup label="Blessings & Others">
                                        <option value="Car Blessing">Car Blessing</option>
                                        <option value="House Blessing">House Blessing</option>
                                        <option value="Other">Others</option>
                                    </optgroup>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                         stroke="rgba(13,42,82,0.38)" stroke-width="2.5"
                                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="m6 9 6 6 6-6"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div x-show="['Baptism','Wedding','Funeral Mass'].includes(inquiryType)"
                             x-transition>
                            <label class="field-label" for="preferredDate">Preferred Date</label>
                            <div class="relative">
                                <input type="date" name="preferredDate" id="preferredDate"
                                       :required="['Baptism','Wedding','Funeral Mass'].includes(inquiryType)"
                                       min="{{ date('Y-m-d') }}"
                                       class="sacred-input" style="padding-right:42px;">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                         stroke="rgba(13,42,82,0.38)" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <rect width="18" height="18" x="3" y="4" rx="2"/>
                                        <path d="M16 2v4M8 2v4M3 10h18"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Message --}}
                    <div>
                        <label class="field-label" for="message">Message / Details</label>
                        <textarea name="message" id="message" rows="4" required
                                  placeholder="Provide details about your request (e.g. occasion, names)…"
                                  class="sacred-input"></textarea>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" :disabled="loading"
                            class="gold-btn w-full h-14 rounded-2xl relative overflow-hidden
                                   inline-flex items-center justify-center gap-2.5
                                   text-[11px] font-bold uppercase tracking-widest">
                        <span :class="loading ? 'opacity-0' : 'opacity-100'"
                              class="transition-opacity flex items-center gap-2.5">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="2.5"
                                 stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/>
                            </svg>
                            Submit Inquiry
                        </span>
                        <div x-show="loading" x-cloak
                             class="absolute inset-0 flex items-center justify-center">
                            <svg class="animate-spin w-6 h-6" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                            </svg>
                        </div>
                    </button>
                </form>
            </div>

        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const obs = new IntersectionObserver(
        entries => entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('active'); obs.unobserve(e.target); } }),
        { threshold: 0.10, rootMargin: '0px 0px -40px 0px' }
    );
    document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
});
</script>

</x-public-layout>