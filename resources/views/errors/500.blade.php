<x-public-layout>
    <div class="container py-24 mx-auto px-4 text-center">
        <div class="max-w-md mx-auto">
            <div class="mb-8 flex justify-center">
                <div class="h-24 w-24 rounded-[2rem] bg-destructive/10 flex items-center justify-center text-destructive">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                </div>
            </div>
            <h1 class="text-6xl font-black text-primary font-heading italic mb-4">500</h1>
            <h2 class="text-2xl font-bold text-primary uppercase tracking-tight mb-4">System Error</h2>
            <p class="text-muted-foreground italic mb-10 leading-relaxed">
                Something went wrong on our end. Please try again later or contact the parish office if the problem persists.
            </p>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 bg-primary text-white font-bold py-3 px-8 rounded-full shadow-lg shadow-primary/20 hover:scale-105 transition-transform active:scale-95 uppercase tracking-widest text-xs">
                Back to Home
            </a>
        </div>
    </div>
</x-public-layout>
