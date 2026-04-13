<x-public-layout>
    <div class="container py-12 mx-auto px-4 max-w-5xl">
        <div class="text-center mb-16 animate-in fade-in slide-in-from-bottom-4 duration-500">
            <p class="text-[12px] font-black uppercase tracking-[0.3em] text-accent mb-4">Stay Informed</p>
            <h1 class="font-heading text-6xl font-black mb-6 text-primary italic uppercase leading-tight">Weekly Bulletins</h1>
            <div class="h-1.5 w-32 bg-accent mx-auto rounded-full mb-8"></div>
            <p class="text-xl text-muted-foreground max-w-2xl mx-auto italic">
                Download our weekly parish bulletins to keep up with the latest news, mass schedules, and community announcements.
            </p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($bulletins as $bulletin)
                <div class="group bg-card border rounded-[2.5rem] p-8 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                    </div>
                    
                    <div class="mb-6">
                        <div class="h-12 w-12 rounded-2xl bg-accent/10 flex items-center justify-center text-accent mb-4 group-hover:bg-accent group-hover:text-white transition-colors shadow-lg shadow-accent/5">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                        </div>
                        <h2 class="text-xl font-black text-primary uppercase leading-tight mb-2">{{ $bulletin->title }}</h2>
                        <p class="text-xs font-bold text-muted-foreground uppercase tracking-widest">{{ $bulletin->published_date->format('F d, Y') }}</p>
                    </div>

                    <a href="{{ route('bulletins.download', $bulletin) }}" class="inline-flex items-center gap-2 text-sm font-black text-primary hover:text-accent transition-colors uppercase tracking-widest group/link">
                        Download PDF
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="translate-x-0 group-hover/link:translate-x-1 transition-transform"><path d="m9 18 6-6-6-6"/></svg>
                    </a>
                </div>
            @empty
                <div class="col-span-full text-center py-20 bg-muted/30 rounded-[3rem] border-2 border-dashed">
                    <p class="text-muted-foreground font-medium italic">No bulletins available at this time.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-16">
            {{ $bulletins->links() }}
        </div>
    </div>
</x-public-layout>
