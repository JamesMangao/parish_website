<x-public-layout>
    <div class="container py-24 mx-auto px-4 text-center">
        <div class="max-w-md mx-auto">
            <div class="mb-8 flex justify-center">
                <div class="h-24 w-24 rounded-[2rem] bg-accent/10 flex items-center justify-center text-accent">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M16 16s-1.5-2-4-2-4 2-4 2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
                </div>
            </div>
            <h1 class="text-6xl font-black text-primary font-heading italic mb-4">404</h1>
            <h2 class="text-2xl font-bold text-primary uppercase tracking-tight mb-4">Page Not Found</h2>
            <p class="text-muted-foreground italic mb-10 leading-relaxed">
                We're sorry, but the page you are looking for doesn't exist or has been moved. May peace be with you.
            </p>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 bg-primary text-white font-bold py-3 px-8 rounded-full shadow-lg shadow-primary/20 hover:scale-105 transition-transform active:scale-95 uppercase tracking-widest text-xs">
                Back to Home
            </a>
        </div>
    </div>
</x-public-layout>
