<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Sto. Rosario Parish') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&display=swap" rel="stylesheet">

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- SEO and Meta Tags -->
    @if(isset($meta))
        {{ $meta }}
    @else
        <meta name="description" content="Official website of Sto. Rosario Parish - Pacita. Providing spiritual guidance, sacramental services, and community outreach in San Pedro, Laguna.">
    @endif
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ config('app.name', 'Sto. Rosario Parish') }}">
    <meta property="og:description" content="{{ $meta_description ?? 'Official website of Sto. Rosario Parish - Pacita. Providing spiritual guidance, sacramental services, and community outreach in San Pedro, Laguna.' }}">
    <meta property="og:image" content="{{ asset($global_settings['hero_image'] ?? 'bg.png') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ config('app.name', 'Sto. Rosario Parish') }}">
    <meta property="twitter:description" content="{{ $meta_description ?? 'Official website of Sto. Rosario Parish - Pacita. Providing spiritual guidance, sacramental services, and community outreach in San Pedro, Laguna.' }}">
    <meta property="twitter:image" content="{{ asset($global_settings['hero_image'] ?? 'bg.png') }}">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        
        @keyframes pageFadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .page-animate {
            animation: pageFadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        ::view-transition-group(root) {
            animation-duration: 0.5s;
        }

        /* ════════════════════════════════════════
           GLOBAL SCROLL REVEAL
        ════════════════════════════════════════ */
        .reveal-global,
        [data-reveal] {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        [data-reveal="left"]  { transform: translateX(-32px) translateY(0); }
        [data-reveal="right"] { transform: translateX(32px) translateY(0); }
        [data-reveal="scale"] { transform: scale(.93) translateY(0); }
        
        .reveal-global.revealed,
        [data-reveal].revealed {
            opacity: 1;
            transform: none;
        }

        @media (prefers-reduced-motion: reduce) {
            .reveal-global, [data-reveal] {
                transition: none !important;
                opacity: 1 !important;
                transform: none !important;
            }
        }

        /* ════════════════════════════════════════
           MOBILE + TABLET (max-width: 1023px)
        ════════════════════════════════════════ */
        @media (max-width: 1023px) {


            /* Section spacing */
            .py-28, .py-32 { padding-top: 3.5rem !important; padding-bottom: 3.5rem !important; }
            .py-24 { padding-top: 3rem !important; padding-bottom: 3rem !important; }
            .mb-16 { margin-bottom: 2rem !important; }
            .mb-14 { margin-bottom: 2rem !important; }

            /* Typography scaling */
            .text-4xl { font-size: 1.75rem !important; }
            .text-5xl, .text-6xl { font-size: 2rem !important; }

            /* Quick Actions — 2x2 */
            .grid.grid-cols-2.md\\:grid-cols-4 { grid-template-columns: repeat(2, 1fr) !important; gap: 12px !important; }
            .grid.grid-cols-2.md\\:grid-cols-4 .card-sacred { padding: 1rem !important; }
            .card-sacred br { display: none; }

            /* Events & Announcements — 2 col on tablet, 1 on phone */
            .grid.md\\:grid-cols-3 { grid-template-columns: repeat(2, 1fr) !important; }
            #ann-grid { grid-template-columns: repeat(2, 1fr) !important; }

            /* Sacramental Services — 3 per row */
            .grid.grid-cols-2.md\\:grid-cols-3.lg\\:grid-cols-6 {
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 8px !important;
            }
            .grid.grid-cols-2.md\\:grid-cols-3.lg\\:grid-cols-6 > a {
                padding: 0.75rem 0.5rem !important;
                gap: 0.5rem !important;
            }

            /* Footer — stack */
            footer .grid.md\\:grid-cols-3 { grid-template-columns: repeat(2, 1fr) !important; }

            /* Next Mass — vertical on small */
            .flex.items-center.gap-8.px-10.py-8 {
                padding: 1.5rem !important;
                gap: 1rem !important;
            }
        }

        /* ════════════════════════════════════════
           PHONE ONLY (max-width: 639px)
        ════════════════════════════════════════ */
        @media (max-width: 639px) {
            /* Tighter section side padding */
            section { padding-left: 14px !important; padding-right: 14px !important; }

            /* Stack grids to single col */
            .grid.md\\:grid-cols-3 { grid-template-columns: 1fr !important; }
            #ann-grid { grid-template-columns: 1fr !important; }

            /* Office hours — stack */
            .grid.grid-cols-3 { grid-template-columns: 1fr !important; }
            .grid.grid-cols-3 > div {
                border-left: none !important;
                border-bottom: 1px solid rgba(26,64,128,0.07);
                padding: 1rem 0.75rem !important;
            }
            .grid.grid-cols-3 > div:last-child { border-bottom: none; }

            /* Next Mass — vertical layout */
            .flex.items-center.gap-8.px-10.py-8 {
                flex-direction: column !important;
                align-items: flex-start !important;
            }

            /* Footer — single col */
            footer .grid.md\\:grid-cols-3 { grid-template-columns: 1fr !important; }

            /* Hero badge */
            .hero-badge { padding: 5px 12px !important; }
            .hero-badge span:last-child { font-size: 8px !important; letter-spacing: 0.25em !important; }

            /* Hero stats */
            .animate-fade-up.delay-5 .font-heading[style*="font-size:1.75rem"] {
                font-size: 1.35rem !important;
            }
        }
    </style>
</head>
<body class="antialiased" x-data="{
    initGlobalReveal() {
        // Auto-apply reveal class to common elements if they don't have it
        const autoTargets = document.querySelectorAll('main section > h1, main section > h2, main section > p:not(.no-reveal), .card, .bg-card, .bg-white');
        autoTargets.forEach(el => {
            if (!el.hasAttribute('data-reveal') && !el.classList.contains('reveal-global')) {
                el.classList.add('reveal-global');
            }
        });

        const revealEls = document.querySelectorAll('.reveal-global, [data-reveal]');
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        revealEls.forEach(el => revealObserver.observe(el));
    }
}" x-init="initGlobalReveal()">
    <div class="flex min-h-screen flex-col bg-background text-foreground">
        <x-navbar />

        <main class="flex-1 page-animate">
            {{ $slot }}
        </main>

        <x-footer />
        
        <x-chatbot />



        <!-- Back to Top Button -->
        <button 
            x-data="{ show: false }" 
            x-init="window.addEventListener('scroll', () => { show = window.scrollY > 500 })"
            x-show="show"
            x-transition 
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            style="position: fixed; bottom: 5rem; right: 2rem; z-index: 100;"
            class="flex h-12 w-12 rounded-2xl bg-primary text-primary-foreground shadow-2xl items-center justify-center hover:-translate-y-2 transition-all active:scale-95"
            title="Back to Top"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
        </button>
    </div>

    <!-- Global Public Notification Toast -->
    <div x-data="{
            notification: { show: false, message: '', type: 'success' },
            init() {
                window.showToast = (message, type = 'success') => {
                    this.notification.message = message;
                    this.notification.type = type;
                    this.notification.show = true;
                    setTimeout(() => this.notification.show = false, 5000);
                };
            }
        }">
        <template x-teleport="body">
            <div 
                x-show="notification.show" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                class="fixed bottom-6 right-6 z-[9999] max-w-sm w-full bg-white border-l-4 shadow-2xl rounded-xl p-5 flex items-start gap-4 animate-in slide-in-from-right-10"
                :class="notification.type === 'success' ? 'border-green-500' : 'border-red-500'"
                x-cloak
            >
                <div :class="notification.type === 'success' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'" 
                     class="h-10 w-10 flex-shrink-0 flex items-center justify-center rounded-full shadow-sm">
                    <template x-if="notification.type === 'success'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </template>
                    <template x-if="notification.type === 'error'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                    </template>
                </div>
                <div class="flex-1 pt-0.5">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground mb-1" x-text="notification.type === 'success' ? 'Success' : 'Notice'"></p>
                    <p class="text-sm font-bold text-primary leading-tight" x-text="notification.message"></p>
                </div>
                <button @click="notification.show = false" class="p-1 hover:bg-muted rounded-md transition-colors text-muted-foreground mt-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
        </template>
    </div>
</body>
</html>
