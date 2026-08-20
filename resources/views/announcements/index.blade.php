<x-public-layout>
    <x-slot name="meta">
        <meta name="description" content="Stay updated with the latest announcements and parish news from Sto. Rosario Parish.">
        <meta property="og:title" content="Parish Announcements | Sto. Rosario Parish">
        <meta property="og:description" content="Latest news, novenas, community updates, and event announcements from Sto. Rosario Parish.">
    </x-slot>

    <section class="max-w-6xl mx-auto px-6 pt-20 pb-16">
        <div class="text-center mb-16 animate-in fade-in slide-in-from-bottom-4 duration-500">
            <p class="text-[12px] font-black uppercase tracking-[0.3em] text-accent mb-4">Stay Informed</p>
            <h1 class="font-heading text-4xl md:text-6xl font-black mb-6 text-primary italic uppercase leading-tight">
                Parish Announcements
            </h1>
            <div class="h-1.5 w-32 bg-accent mx-auto rounded-full mb-8"></div>
            <p class="text-xl text-muted-foreground max-w-2xl mx-auto italic">
                Latest news, novenas, and community updates from Sto. Rosario Parish.
            </p>
        </div>


        {{-- Category Filters --}}
        <div class="flex flex-wrap items-center justify-center gap-2 mb-10">
            @php $activeAll = !$category; @endphp
            <a href="{{ route('announcements.index') }}"
               class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-200 {{ $activeAll ? 'bg-primary text-white shadow-md' : 'bg-muted text-muted-foreground hover:bg-primary/10 hover:text-primary' }}">
                All
                <span class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full text-[9px] font-black {{ $activeAll ? 'bg-white/25 text-white' : 'bg-primary/10 text-primary' }}">{{ $counts['all'] }}</span>
            </a>
            @foreach($categories as $cat)
                @php $isActive = $category === $cat; @endphp
                <a href="{{ route('announcements.index', ['category' => $cat]) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider transition-all duration-200 {{ $isActive ? 'bg-primary text-white shadow-md' : 'bg-muted text-muted-foreground hover:bg-primary/10 hover:text-primary' }}">
                    {{ $cat }}
                    <span class="inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full text-[9px] font-black {{ $isActive ? 'bg-white/25 text-white' : 'bg-primary/10 text-primary' }}">{{ $counts[$cat] }}</span>
                </a>
            @endforeach
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($announcements as $ann)
                <x-announcement-card :ann="$ann" />
            @empty
                <div class="col-span-full text-center py-20 bg-muted/30 rounded-[3rem] border-2 border-dashed">
                    <div class="font-cinzel text-5xl mb-5 opacity-20 text-primary">✝</div>
                    <h3 class="font-heading font-bold italic text-2xl mb-2 text-primary">
                        No Announcements
                    </h3>
                    <p class="text-muted-foreground italic">
                        There are currently no announcements published. Please check back soon.
                    </p>
                </div>
            @endforelse
        </div>

        @if($announcements->hasPages())
            <div class="mt-12">
                {{ $announcements->links() }}
            </div>
        @endif
    </section>
</x-public-layout>
