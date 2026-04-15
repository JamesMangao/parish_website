<x-public-layout>

    {{-- HERO --}}
    <section class="py-24 text-center" style="background:#F5F0E8;border-bottom:0.5px solid #E0D9CE;">
        <div class="px-4">
            <p class="text-xs font-black uppercase tracking-[0.35em] mb-4" style="color:#C9A96E;">Capturing Moments</p>
            <h1 class="font-heading text-5xl md:text-7xl font-black mb-6 italic" style="color:#3B1A22;letter-spacing:-0.02em;">Parish Gallery</h1>
            <div class="w-16 h-px mx-auto mb-6" style="background:#C9A96E;"></div>
            <p class="max-w-xl mx-auto leading-relaxed text-base italic" style="color:#888;">
                A visual chronicle of our community's journey in faith, service, and celebration.
            </p>
        </div>
    </section>

    <div class="pb-24">

        @if($albums->isEmpty())
            {{-- EMPTY STATE --}}
            <div class="py-32 text-center px-4">
                <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6" style="background:#F5F0E8;">
                    <svg width="32" height="32" fill="none" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="3" rx="2" stroke="#C9A96E" stroke-width="1.5"/><circle cx="9" cy="9" r="2" stroke="#C9A96E" stroke-width="1.5"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" stroke="#C9A96E" stroke-width="1.5"/></svg>
                </div>
                <h3 class="font-heading text-2xl font-black mb-3" style="color:#3B1A22;">No Albums Yet</h3>
                <p class="text-sm italic" style="color:#888;">We are currently curating our latest community photos. Please check back soon.</p>
            </div>

        @else
            @php
                $featured = $albums->first();
                $otherAlbums = $albums->slice(1);
            @endphp

            {{-- FEATURED ALBUM --}}
            <section class="px-4 pt-16 pb-0 max-w-5xl mx-auto mb-20">
                <div class="flex items-center gap-4 mb-8">
                    <p class="text-xs font-black uppercase tracking-[0.3em] flex-shrink-0" style="color:#C9A96E;">Featured Album</p>
                    <div class="h-px flex-1" style="background:#E0D9CE;"></div>
                </div>

                <a href="{{ route('gallery.album', $featured) }}" class="group block relative rounded-2xl overflow-hidden" style="height:480px;">
                    @if($featured->images->count() > 0)
                        <img src="{{ $featured->images->first()->url }}" alt="{{ $featured->title }}"
                             class="w-full h-full object-cover transition-transform duration-[3s] group-hover:scale-105">
                    @else
                        <div class="w-full h-full" style="background:#D4BFA0;"></div>
                    @endif
                    <div class="absolute inset-0 flex flex-col justify-end p-10" style="background:linear-gradient(to top,rgba(30,10,15,0.92) 0%,rgba(30,10,15,0.2) 60%,transparent 100%);">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="text-xs font-black uppercase tracking-widest px-3 py-1 rounded" style="background:#C9A96E;color:#3B1A22;">Featured</span>
                            <span class="text-xs font-bold uppercase tracking-widest" style="color:rgba(255,255,255,0.6);">{{ $featured->images_count }} Photos</span>
                        </div>
                        <h2 class="font-heading text-4xl md:text-5xl font-black text-white italic mb-3 group-hover:text-[#C9A96E] transition-colors" style="letter-spacing:-0.01em;">{{ $featured->title }}</h2>
                        <p class="text-sm leading-relaxed line-clamp-2 italic" style="color:rgba(255,255,255,0.6);max-width:600px;">{{ $featured->description }}</p>
                    </div>
                </a>
            </section>

            {{-- MORE GALLERIES --}}
            @if($otherAlbums->isNotEmpty())
                <section class="px-4 max-w-5xl mx-auto mb-20">
                    <div class="flex items-center gap-4 mb-12">
                        <h2 class="font-heading text-3xl font-black italic flex-shrink-0" style="color:#3B1A22;">More Galleries</h2>
                        <div class="h-px flex-1" style="background:#E0D9CE;"></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($otherAlbums as $album)
                            <a href="{{ route('gallery.album', $album) }}" class="group flex flex-col">
                                <div class="relative aspect-[4/3] rounded-xl overflow-hidden mb-4" style="background:#D4BFA0;border:0.5px solid #E0D9CE;">
                                    @if($album->images->count() > 0)
                                        <img src="{{ $album->images->first()->url }}" alt="{{ $album->title }}"
                                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                                    @endif
                                    <div class="absolute inset-0 transition-colors" style="background:rgba(0,0,0,0.15);"></div>
                                    <div class="absolute top-3 right-3 text-xs font-black uppercase tracking-widest px-2.5 py-1 rounded" style="background:rgba(255,255,255,0.92);color:#3B1A22;">
                                        {{ $album->images_count }} Pics
                                    </div>
                                </div>
                                <h3 class="font-heading text-xl font-black group-hover:text-[#C9A96E] transition-colors mb-1" style="color:#3B1A22;">{{ $album->title }}</h3>
                                <p class="text-sm italic line-clamp-2" style="color:#888;">{{ $album->description }}</p>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- RECENT CAPTURES --}}
            @if($latestPhotos->isNotEmpty())
                <section class="py-20 px-4 mb-20" style="background:#F5F0E8;border-top:0.5px solid #E0D9CE;border-bottom:0.5px solid #E0D9CE;">
                    <div class="max-w-5xl mx-auto">
                        <p class="text-xs font-black uppercase tracking-[0.3em] text-center mb-3" style="color:#C9A96E;">Latest</p>
                        <h2 class="font-heading text-3xl font-black text-center italic mb-12" style="color:#3B1A22;">Recent Captures</h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                            @foreach($latestPhotos->take(6) as $photo)
                                <a href="{{ route('gallery.album', $photo->album_id) }}" class="group relative aspect-square rounded-xl overflow-hidden" style="background:#D4BFA0;">
                                    <img src="{{ $photo->url }}" alt="Recent photo"
                                         class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700 group-hover:scale-110" loading="lazy">
                                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity" style="background:rgba(201,169,110,0.1);"></div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif
        @endif

        {{-- STAY CONNECTED --}}
        <section class="px-4 py-20 max-w-3xl mx-auto">
            <div class="text-center mb-12">
                <p class="text-xs font-black uppercase tracking-[0.35em] mb-3" style="color:#C9A96E;">Social Feed</p>
                <h2 class="font-heading text-4xl font-black italic mb-4" style="color:#3B1A22;">Stay Connected</h2>
                <div class="w-12 h-px mx-auto mb-5" style="background:#C9A96E;"></div>
                <p class="text-sm italic" style="color:#888;">Follow our latest updates and community announcements live from Facebook.</p>
            </div>

            {{-- STAT ROW --}}
            <div class="grid grid-cols-3 gap-4 mb-10">
                <div class="text-center py-5 rounded-xl" style="background:#F5F0E8;border:0.5px solid #E0D9CE;">
                    <p class="font-heading text-2xl font-black" style="color:#3B1A22;">5K+</p>
                    <p class="text-xs font-black uppercase tracking-widest mt-1" style="color:#aaa;">Followers</p>
                </div>
                <div class="text-center py-5 rounded-xl" style="background:#F5F0E8;border:0.5px solid #E0D9CE;">
                    <p class="font-heading text-2xl font-black" style="color:#3B1A22;">Daily</p>
                    <p class="text-xs font-black uppercase tracking-widest mt-1" style="color:#aaa;">Mass Intentions</p>
                </div>
                <div class="text-center py-5 rounded-xl" style="background:#F5F0E8;border:0.5px solid #E0D9CE;">
                    <p class="font-heading text-2xl font-black" style="color:#3B1A22;">Live</p>
                    <p class="text-xs font-black uppercase tracking-widest mt-1" style="color:#aaa;">Streamed Masses</p>
                </div>
            </div>

            {{-- FB EMBED — centered, full container width --}}
            <div class="rounded-2xl overflow-hidden mb-8" style="background:white;border:0.5px solid #E0D9CE;">
                <div id="fb-root"></div>
                <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_PH/sdk.js#xfbml=1&version=v19.0"></script>
                <div class="fb-page"
                    data-href="https://www.facebook.com/storosarioparishpacita1"
                    data-tabs="timeline"
                    data-width="800"
                    data-height="500"
                    data-small-header="true"
                    data-adapt-container-width="true"
                    data-hide-cover="false"
                    data-show-facepile="false">
                    <blockquote cite="https://www.facebook.com/storosarioparishpacita1" class="fb-xfbml-parse-ignore">
                        <a href="https://www.facebook.com/storosarioparishpacita1">Sto. Rosario Parish - Pacita</a>
                    </blockquote>
                </div>
            </div>

            {{-- BUTTONS --}}
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="https://www.facebook.com/storosarioparishpacita1" target="_blank"
                   class="inline-flex items-center gap-2 px-7 py-3 rounded-lg text-sm font-bold transition-all hover:opacity-90"
                   style="background:#1877F2;color:white;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    Follow on Facebook
                </a>
                <a href="/about"
                   class="inline-flex items-center px-7 py-3 rounded-lg text-sm font-bold transition-all"
                   style="background:#C9A96E;color:#3B1A22;">
                    Contact the Office
                </a>
            </div>
        </section>

    </div>

</x-public-layout>