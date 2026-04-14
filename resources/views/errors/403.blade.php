<x-public-layout>
    <div class="container py-24 mx-auto px-4 text-center">
        <div class="max-w-md mx-auto">
            <div class="mb-8 flex justify-center">
                <div class="h-24 w-24 rounded-[2rem] bg-destructive/10 flex items-center justify-center text-destructive">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/><path d="m14.5 9.5-5 5"/><path d="m9.5 9.5 5 5"/></svg>
                </div>
            </div>
            <h1 class="text-6xl font-black text-primary font-heading italic mb-4">403</h1>
            <h2 class="text-2xl font-bold text-primary uppercase tracking-tight mb-4">Access Denied</h2>
            <p class="text-muted-foreground italic mb-10 leading-relaxed">
                You do not have permission to access this area. If you believe this is an error, please contact your administrator.
            </p>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 bg-primary text-white font-bold py-3 px-8 rounded-full shadow-lg shadow-primary/20 hover:scale-105 transition-transform active:scale-95 uppercase tracking-widest text-xs">
                Back to Home
            </a>
        </div>
    </div>
</x-public-layout>
