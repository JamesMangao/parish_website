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

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{ $meta ?? '' }}
    @if(!isset($meta))
        <meta name="description" content="Official website of Sto. Rosario Parish - Pacita. Providing spiritual guidance, sacramental services, and community outreach in San Pedro, Laguna.">
    @endif
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ config('app.name', 'Sto. Rosario Parish') }}">
    <meta property="og:description" content="Official website of Sto. Rosario Parish - Pacita. Providing spiritual guidance, sacramental services, and community outreach in San Pedro, Laguna.">
    <meta property="og:image" content="{{ asset('bg.png') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ config('app.name', 'Sto. Rosario Parish') }}">
    <meta property="twitter:description" content="Official website of Sto. Rosario Parish - Pacita. Providing spiritual guidance, sacramental services, and community outreach in San Pedro, Laguna.">
    <meta property="twitter:image" content="{{ asset('bg.png') }}">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="antialiased">
    <div class="flex min-h-screen flex-col bg-background text-foreground">
        <x-navbar />

        <main class="flex-1">
            {{ $slot }}
        </main>

        <x-footer />
        
        <x-chatbot />

        <!-- Mobile Bottom Navigation -->
        <div class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-background/80 backdrop-blur-lg border-t pb-safe">
            <div class="flex items-center justify-around p-3">
                <a href="/" class="flex flex-col items-center gap-1 {{ request()->is('/') ? 'text-accent' : 'text-muted-foreground' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    <span class="text-[10px] font-black uppercase tracking-widest">Home</span>
                </a>
                <a href="/mass-schedule" class="flex flex-col items-center gap-1 {{ request()->is('mass-schedule*') ? 'text-accent' : 'text-muted-foreground' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 7 4 9v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9Z"/><path d="M14 2h-4v3"/><path d="M18 7V5c0-1.1-.9-2-2-2h-3"/><path d="M11 5H8c-1.1 0-2 .9-2 2v2"/><path d="M12 22v-4"/></svg>
                    <span class="text-[10px] font-black uppercase tracking-widest">Mass</span>
                </a>
                <a href="/submit-intention" class="flex flex-col items-center gap-1 {{ request()->is('submit-intention*') ? 'text-accent' : 'text-muted-foreground' }}">
                    <div class="h-10 w-10 -mt-6 bg-accent text-accent-foreground rounded-full flex items-center justify-center shadow-lg border-4 border-background">
                         <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.505 4.04 3 5.5L12 21l7-7Z"/></svg>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-widest -mt-1">Offer</span>
                </a>
                <a href="/track" class="flex flex-col items-center gap-1 {{ request()->is('track*') ? 'text-accent' : 'text-muted-foreground' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/><path d="M11 8v6"/><path d="M8 11h6"/></svg>
                    <span class="text-[10px] font-black uppercase tracking-widest">Track</span>
                </a>
                <button type="button" @click="window.scrollTo({top: 0, behavior: 'smooth'})" class="flex flex-col items-center gap-1 text-muted-foreground">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                    <span class="text-[10px] font-black uppercase tracking-widest">Top</span>
                </button>
            </div>
        </div>

        <!-- Back to Top Button (Hidden on Mobile due to Bottom Nav) -->
        <button 
            x-data="{ show: false }" 
            x-init="window.addEventListener('scroll', () => { show = window.scrollY > 500 })"
            x-show="show"
            x-transition 
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            style="position: fixed; bottom: 5rem; right: 2rem; z-index: 100;"
            class="hidden md:flex h-12 w-12 rounded-2xl bg-primary text-primary-foreground shadow-2xl items-center justify-center hover:-translate-y-2 transition-all active:scale-95"
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
