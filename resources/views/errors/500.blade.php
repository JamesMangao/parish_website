<x-public-layout>
    <div class="container mx-auto px-4 min-h-[60vh] flex flex-col items-center justify-center text-center">
        <div class="mb-8 p-6 rounded-full bg-red-50 text-red-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-triangle"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
        </div>
        <h1 class="font-heading text-6xl font-bold text-primary mb-4">500</h1>
        <h2 class="text-2xl font-bold mb-6">Internal Server Error</h2>
        <p class="text-muted-foreground max-w-md mx-auto mb-10">
            Something went wrong on our servers. We're working to fix it. Please try again later.
        </p>
        <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-2 rounded-md bg-primary px-6 py-3 text-sm font-bold text-primary-foreground hover:bg-primary/90 transition-all shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
            Refresh Page
        </a>
    </div>
</x-public-layout>
