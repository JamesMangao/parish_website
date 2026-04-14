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

        <!-- Back to Top Button -->
        <button 
            x-data="{ show: false }" 
            x-init="window.addEventListener('scroll', () => { show = window.scrollY > 500 })"
            x-show="show"
            x-transition 
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            style="position: fixed; bottom: 2rem; left: 2rem; z-index: 100;"
            class="h-12 w-12 rounded-2xl bg-primary text-primary-foreground shadow-2xl flex items-center justify-center hover:-translate-y-2 transition-all active:scale-95"
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
