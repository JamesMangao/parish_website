<x-public-layout>
    <div class="container py-12 mx-auto px-4" x-data="{ selected: null }">
        <!-- Header -->
        <div class="mb-12">
            <a href="{{ route('gallery') }}"
                class="inline-flex items-center gap-2 text-muted-foreground hover:text-primary font-bold mb-6 group transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-arrow-left-circle group-hover:scale-110 transition-transform">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M16 12H8" />
                    <path d="m12 8-4 4 4 4" />
                </svg>
                Back to All Albums
            </a>

            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <h1 class="font-heading text-5xl font-black text-primary mb-6 tracking-tight">{{ $album->title }}
                    </h1>
                    <div class="max-w-4xl py-2">
                        <div
                            class="text-lg md:text-xl text-muted-foreground/80 leading-relaxed font-medium whitespace-pre-wrap">
                            {!! nl2br(e($album->description ?: 'Explore these moments from our parish community.')) !!}
                        </div>
                    </div>
                </div>
                <div class="shrink-0 text-right">
                    <span
                        class="text-[10px] font-black uppercase tracking-[0.3em] bg-accent/10 px-4 py-1.5 rounded-full border border-accent/20 text-accent">
                        {{ $album->images->count() }} Photos in Collection
                    </span>
                    <p class="text-[10px] font-bold text-muted-foreground uppercase mt-2 tracking-widest">Added
                        {{ $album->created_at->format('M d, Y') }}
                    </p>
                </div>
            </div>
            <div class="section-divider mt-10"></div>
        </div>

        @if($album->images->isEmpty())
            <div class="text-center py-20 bg-muted/20 border border-dashed rounded-3xl">
                <p class="text-muted-foreground italic">No photos in this album yet. Check back soon.</p>
            </div>
        @else
            <!-- Photo Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($album->images as $img)
                    <div class="aspect-[4/3] relative group rounded-2xl overflow-hidden shadow-lg border border-muted/30 hover:shadow-2xl transition-all duration-500 cursor-zoom-in"
                        @click="selected = { url: '{{ $img->url }}', title: '{{ addslashes($img->title) }}', caption: '{{ addslashes($img->caption) }}' }">
                        <img src="{{ $img->url }}" alt="{{ $img->title }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000"
                            loading="lazy" />
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity p-6 flex flex-col justify-end">
                            <p class="text-white font-bold text-sm drop-shadow-md truncate">{{ $img->caption ?: $img->title }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Lightbox Modal -->
        <div x-show="selected" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/95 backdrop-blur-xl"
            @keydown.escape.window="selected = null" style="display: none;">
            <div class="relative max-w-6xl w-full h-[90vh] bg-transparent overflow-hidden"
                @click.away="selected = null">
                <!-- Close Button -->
                <button @click="selected = null"
                    class="absolute top-4 right-4 z-50 p-3 rounded-full bg-black/50 text-white hover:bg-white hover:text-black transition-all shadow-xl border border-white/20">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-x">
                        <path d="M18 6 6 18" />
                        <path d="m6 6 12 12" />
                    </svg>
                </button>

                <div class="flex flex-col h-full">
                    <!-- Image Area -->
                    <div class="flex-1 flex items-center justify-center overflow-hidden">
                        <img :src="selected?.url" class="max-w-full max-h-full object-contain shadow-2xl rounded-lg"
                            :alt="selected?.title">
                    </div>

                    <!-- Caption Area -->
                    <div class="p-8 text-center" x-show="selected?.title || selected?.caption">
                        <h3 class="text-2xl font-heading font-black text-white mb-2" x-text="selected?.title"></h3>
                        <p class="text-white/80 text-lg max-w-2xl mx-auto italic" x-text="selected?.caption"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>