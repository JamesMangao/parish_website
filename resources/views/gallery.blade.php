<x-public-layout>
    {{-- Hero/Header Section --}}
    <section class="relative py-20 bg-background overflow-hidden">
        <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: radial-gradient(#c9a84c 1px, transparent 1px); background-size: 32px 32px;"></div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center max-w-3xl mx-auto">
                <span class="inline-block px-4 py-1 rounded-full bg-accent/10 text-accent text-[11px] font-black uppercase tracking-[0.4em] mb-6">Capturing Moments</span>
                <h1 class="font-heading text-5xl md:text-7xl font-bold text-primary mb-6 italic">Parish Gallery</h1>
                <div class="h-1.5 w-24 bg-accent mx-auto rounded-full mb-8"></div>
                <p class="text-xl text-stone-500 italic leading-relaxed">
                    A visual chronicle of our community's journey in faith, service, and celebration.
                </p>
            </div>
        </div>
    </section>

    <div class="container pb-24 mx-auto px-4">
        @if($albums->isEmpty())
            <section class="py-24 text-center">
                <div class="max-w-md mx-auto p-12 rounded-[3rem] bg-stone-50 border border-amber-100 shadow-inner">
                    <div class="w-20 h-20 bg-amber-100/50 rounded-full flex items-center justify-center mx-auto mb-6 text-amber-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                    </div>
                    <h3 class="font-heading text-2xl font-bold text-primary mb-3">No Albums Yet</h3>
                    <p class="text-stone-500 italic">We are currently curating our latest community photos. Please check back soon.</p>
                </div>
            </section>
        @else
            {{-- Featured Album --}}
            @php 
                $featured = $albums->first();
                $otherAlbums = $albums->slice(1);
            @endphp

            <section class="mb-24">
                <div class="flex items-center gap-4 mb-8">
                    <h2 class="font-heading text-sm font-black text-accent uppercase tracking-[0.3em]">Featured Album</h2>
                    <div class="h-px flex-1 bg-amber-100"></div>
                </div>
                
                <a href="{{ route('gallery.album', $featured) }}" class="group block relative h-[500px] rounded-[3rem] overflow-hidden shadow-2xl transition-all duration-700 hover:shadow-amber-900/10">
                    @if($featured->images->count() > 0)
                        <img src="{{ $featured->images->first()->url }}" alt="{{ $featured->title }}" class="w-full h-full object-cover transition-transform duration-[3s] group-hover:scale-105">
                    @else
                        <div class="w-full h-full bg-stone-200"></div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent p-12 flex flex-col justify-end">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="bg-accent text-accent-foreground text-[10px] font-black uppercase px-4 py-1.5 rounded-full">Primary Gallery</span>
                            <span class="text-white/80 text-xs font-bold uppercase tracking-widest">{{ $featured->images_count }} Photos</span>
                        </div>
                        <h2 class="text-4xl md:text-6xl font-heading font-bold text-white mb-4 group-hover:text-accent transition-colors">
                            {{ $featured->title }}
                        </h2>
                        <p class="text-white/70 max-w-2xl line-clamp-2 italic text-lg">{{ $featured->description }}</p>
                    </div>
                </a>
            </section>

            {{-- More Galleries Grid --}}
            @if($otherAlbums->isNotEmpty())
                <section class="mb-24">
                    <div class="flex items-center justify-between mb-12">
                        <h2 class="font-heading text-4xl font-bold text-primary italic">More Galleries</h2>
                        <div class="hidden md:block h-px w-1/3 bg-amber-100"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                        @foreach($otherAlbums as $album)
                            <a href="{{ route('gallery.album', $album) }}" class="group flex flex-col h-full">
                                <div class="relative aspect-[4/3] rounded-[2rem] overflow-hidden bg-stone-100 border border-stone-200 mb-6 transition-all duration-500 group-hover:shadow-xl group-hover:border-accent/30">
                                    @if($album->images->count() > 0)
                                        <img src="{{ $album->images->first()->url }}" alt="{{ $album->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                                    @endif
                                    <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors"></div>
                                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest text-primary shadow-sm">
                                        {{ $album->images_count }} Pics
                                    </div>
                                </div>
                                <h3 class="font-heading text-2xl font-bold text-primary group-hover:text-accent transition-colors mb-2">{{ $album->title }}</h3>
                                <p class="text-stone-500 text-sm italic line-clamp-2">{{ $album->description }}</p>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Latest Photos Strip --}}
            @if($latestPhotos->isNotEmpty())
                <section class="py-20 border-y border-amber-100 bg-stone-50/50 -mx-4 px-4 mb-24">
                    <div class="container mx-auto">
                        <h2 class="font-heading text-3xl font-bold text-primary mb-12 text-center italic">Recent Captures</h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                            @foreach($latestPhotos->take(6) as $photo)
                                <a href="{{ route('gallery.album', $photo->album_id) }}" class="group relative aspect-square rounded-2xl overflow-hidden shadow-sm">
                                    <img src="{{ $photo->url }}" alt="Latest photo" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700 group-hover:scale-110" loading="lazy">
                                    <div class="absolute inset-0 bg-accent/0 group-hover:bg-accent/10 transition-colors"></div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif
        @endif

        {{-- Social/Facebook Integration Section --}}
        <section class="max-w-4xl mx-auto pt-20">
            <div class="text-center mb-12">
                <span class="inline-block px-4 py-1 rounded-full bg-accent/10 text-accent text-[10px] font-black uppercase tracking-[0.4em] mb-4">Social Feed</span>
                <h2 class="font-heading text-4xl font-bold text-primary italic mb-4">Stay Connected</h2>
                <div class="h-1 w-16 bg-accent mx-auto rounded-full mb-6"></div>
                <p class="text-stone-500 italic">Follow our latest updates and community announcements live from Facebook.</p>
            </div>

            <div class="bg-stone-50 p-6 md:p-10 rounded-[3rem] border border-amber-100 shadow-xl overflow-hidden">
                <div class="flex flex-col md:flex-row gap-10 items-center">
                    {{-- Facebook Feed Card --}}
                    <div class="w-full md:w-auto flex-shrink-0">
                        <div class="rounded-2xl overflow-hidden border border-amber-200/50 shadow-md bg-white">
                            <div id="fb-root"></div>
                            <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_PH/sdk.js#xfbml=1&version=v19.0"></script>
                            <div class="fb-page" 
                                data-href="https://www.facebook.com/storosarioparishpacita1" 
                                data-tabs="timeline" 
                                data-width="340" 
                                data-height="500" 
                                data-small-header="false" 
                                data-adapt-container-width="true" 
                                data-hide-cover="false" 
                                data-show-facepile="true">
                                <blockquote cite="https://www.facebook.com/storosarioparishpacita1" class="fb-xfbml-parse-ignore">
                                    <a href="https://www.facebook.com/storosarioparishpacita1">Sto. Rosario Parish - Pacita</a>
                                </blockquote>
                            </div>
                        </div>
                    </div>

                    {{-- CTA Details --}}
                    <div class="flex-1 text-center md:text-left">
                        <h3 class="font-heading text-2xl font-bold text-primary mb-4 italic">Community Updates</h3>
                        <p class="text-stone-600 mb-8 leading-relaxed">
                            Join over 5,000 parishioners on our digital community. We share daily mass intentions, announcements, and live-streamed services regularly.
                        </p>
                        
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-4">
                            <a href="https://www.facebook.com/storosarioparishpacita1" target="_blank" 
                               class="inline-flex items-center gap-2 px-8 py-4 rounded-full font-bold shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all"
                               style="background-color: #1877F2; color: #ffffff;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                Follow us on Facebook
                            </a>
                            <a href="/about" class="text-primary font-bold hover:text-accent transition-colors underline underline-offset-8 decoration-accent/30 hover:decoration-accent">
                                Contact the Office
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-public-layout>