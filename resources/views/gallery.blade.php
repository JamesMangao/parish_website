<x-public-layout>
    <div class="container py-12 mx-auto px-4">
        <div class="text-center mb-16">
            <h1 class="font-heading text-5xl font-bold mb-4 text-primary">Parish Gallery</h1>
            <div class="section-divider mx-auto"></div>
            <p class="text-muted-foreground mt-4 italic text-lg opacity-80">Moments that weave our community together
            </p>
        </div>

        <div>
            @if($albums->isEmpty())
                <div class="text-center py-20 bg-muted/20 border border-dashed rounded-3xl">
                    <p class="text-muted-foreground italic">No albums available yet. Check back soon for updates.</p>
                </div>
            @else
                <!-- Featured Album Row -->
                @php 
                    $featured = $albums->first();
                    $otherAlbums = $albums->slice(1);
                @endphp

                <div class="mb-4 pl-2">
                    <h2 class="font-heading text-xl font-bold text-primary/60 uppercase tracking-[0.2em]">Featured Album</h2>
                </div>
                <div class="mb-20">
                    <a href="{{ route('gallery.album', $featured) }}"
                        class="group block relative h-[450px] rounded-[2.5rem] overflow-hidden shadow-2xl shadow-primary/20 border border-white/10">
                        @if($featured->images->count() > 0)
                            <img src="{{ $featured->images->first()->url }}" alt="{{ $featured->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-[3000ms]">
                        @else
                            <div class="w-full h-full bg-muted"></div>
                        @endif
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent p-10 flex flex-col justify-end">
                            <div class="flex items-center gap-3 mb-4">
                                <span
                                    class="bg-accent text-accent-foreground text-xs font-black uppercase px-4 py-1.5 rounded-full shadow-xl">Featured</span>
                                <span
                                    class="text-white/95 text-xs font-bold uppercase tracking-[0.3em] drop-shadow-md">{{ $featured->images_count }}
                                    Photos</span>
                            </div>
                            <h2
                                class="text-4xl md:text-6xl font-heading font-black text-white leading-tight group-hover:text-accent transition-colors drop-shadow-2xl max-w-3xl">
                                {{ $featured->title }}
                            </h2>
                        </div>
                    </a>
                </div>

                @if($otherAlbums->isNotEmpty())
                    <div class="mb-8 pl-2 mt-8">
                        <h2 class="font-heading text-3xl font-black text-primary tracking-tight">More Galleries</h2>
                    </div>
                @endif

                <!-- Albums Grid -->
                <div class="grid gap-8"
                    style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));">
                    @foreach($albums as $album)
                        @if(!$loop->first)
                        <div>
                            <a href="{{ route('gallery.album', $album) }}" class="group block h-full">
                                <div
                                    class="bg-card rounded-[2rem] overflow-hidden shadow-lg border border-muted/40 h-full flex flex-col group-hover:shadow-2xl group-hover:border-accent/30 transition-all duration-500">
                                    <!-- Image Container -->
                                    <div class="relative aspect-[4/3] overflow-hidden bg-muted">
                                        @if($album->images->count() > 0)
                                            <img src="{{ $album->images->first()->url }}" alt="{{ $album->title }}"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000"
                                                loading="lazy" />
                                        @else
                                            <div class="w-full h-full bg-muted flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" class="text-muted-foreground/30">
                                                    <rect width="18" height="18" x="3" y="3" rx="2" ry="2" />
                                                    <circle cx="9" cy="9" r="2" />
                                                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div
                                            class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/20 to-transparent opacity-60 group-hover:opacity-90 transition-opacity">
                                        </div>
                                        <div class="absolute bottom-6 left-6 right-6 text-white">
                                            <p class="text-[10px] font-bold text-accent uppercase tracking-[0.2em] mb-1">
                                                {{ $album->images_count }} PHOTOS
                                            </p>
                                            <h3
                                                class="font-heading font-black text-xl leading-tight group-hover:text-accent transition-colors line-clamp-2">
                                                {{ $album->title }}
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endif
                    @endforeach
                </div>

                <!-- Latest Photos Grid -->
                @if($latestPhotos->isNotEmpty())
                    <div class="mt-24">
                        <div class="mb-8 pl-2">
                            <h2 class="font-heading text-3xl font-black text-primary tracking-tight">Latest Photos</h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                            @foreach($latestPhotos as $photo)
                                <a href="{{ route('gallery.album', $photo->album_id) }}"
                                    class="group block relative aspect-square rounded-lg overflow-hidden bg-muted shadow-sm hover:shadow-md transition-all">
                                    <img src="{{ $photo->url }}" alt="Latest photo"
                                        class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                                        loading="lazy" />
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-end p-4">
                                        <p class="text-white text-xs font-bold line-clamp-2">{{ $photo->album->title }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif

            {{-- ===================== --}}
            {{-- Facebook Feed Section --}}
            {{-- ===================== --}}
            <div class="mt-24">
                <div class="section-divider mb-10"></div>

                <div class="text-center mb-10">
                    <h2 class="font-heading text-3xl font-black text-primary tracking-tight mb-2">More on Facebook</h2>
                    <p class="text-muted-foreground text-sm italic">Follow our latest updates and photo albums on our
                        official Facebook page.</p>
                </div>

                <!-- Facebook Page Card -->
                <div class="max-w-lg mx-auto">
                    <div class="rounded-2xl overflow-hidden border border-muted/40 shadow-lg bg-card">

                        <!-- Card Header (Facebook blue) -->
                        <div style="background: #1877F2;" class="px-5 py-4 flex items-center gap-3">
                            <div
                                class="w-11 h-11 rounded-full bg-white flex items-center justify-center flex-shrink-0 overflow-hidden">
                                <svg viewBox="0 0 40 40" width="40" height="40" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect width="40" height="40" rx="20" fill="#7c1f2e" />
                                    <text x="20" y="27" text-anchor="middle" font-size="20" fill="white"
                                        font-family="serif">✝</text>
                                </svg>
                            </div>
                            <div>
                                <p class="text-white font-bold text-sm leading-tight"
                                    style="font-family: Arial, sans-serif;">Sto. Rosario Parish · Pacita</p>
                                <p class="text-white/70 text-xs" style="font-family: Arial, sans-serif;">
                                    @storosarioparishpacita1 · Facebook Page</p>
                            </div>
                        </div>

                        <!-- Latest Post Preview -->
                        <div class="p-5 border-b border-muted/40">
                            <!-- Post author row -->
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0"
                                    style="background: #1877F2;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="white">
                                        <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-primary leading-tight"
                                        style="font-family: Arial, sans-serif;">Sto. Rosario Parish - Pacita</p>
                                    <p class="text-xs text-muted-foreground" style="font-family: Arial, sans-serif;">
                                        Latest post · Facebook</p>
                                </div>
                            </div>

                            <!-- Facebook SDK Embedded Post (live) -->
                            <div id="fb-root"></div>
                            <script async defer crossorigin="anonymous"
                                src="https://connect.facebook.net/en_PH/sdk.js#xfbml=1&version=v19.0">
                                </script>
                            <div class="rounded-xl overflow-hidden">
                                <div class="fb-page" data-href="https://www.facebook.com/storosarioparishpacita1"
                                    data-tabs="timeline" data-width="480" data-height="400" data-small-header="true"
                                    data-adapt-container-width="true" data-hide-cover="true" data-show-facepile="false">
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="px-5 py-4 flex items-center justify-between bg-muted/10">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="#1877F2">
                                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                                </svg>
                                <span class="text-xs text-muted-foreground"
                                    style="font-family: Arial, sans-serif;">facebook.com/storosarioparishpacita1</span>
                            </div>
                            <a href="https://www.facebook.com/storosarioparishpacita1" target="_blank"
                                class="text-xs font-semibold text-white px-4 py-1.5 rounded-full transition-colors"
                                style="background: #1877F2; font-family: Arial, sans-serif;"
                                onmouseover="this.style.background='#1565d8'"
                                onmouseout="this.style.background='#1877F2'">
                                Follow Page
                            </a>
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <div class="text-center mt-6">
                        <a href="https://www.facebook.com/storosarioparishpacita1" target="_blank"
                            class="inline-flex items-center gap-2 text-white font-semibold px-6 py-3 rounded-full transition-colors shadow-md"
                            style="background: #1877F2;" onmouseover="this.style.background='#1565d8'"
                            onmouseout="this.style.background='#1877F2'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                fill="white">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                            </svg>
                            Visit Our Facebook Page
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-public-layout>