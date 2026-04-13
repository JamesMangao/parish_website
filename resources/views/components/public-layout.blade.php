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
</body>
</html>
