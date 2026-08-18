<x-admin-layout>
    <div class="max-w-5xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('admin.gallery.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-muted-foreground/50 hover:text-primary transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6" /></svg>
                Back to Gallery
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-black/[.04] shadow-sm shadow-black/[.02] overflow-hidden">
            <div class="px-8 py-8 border-b border-black/[.04] bg-gradient-to-r from-[#F5F7FA] to-white">
                <p class="text-[10px] font-black uppercase tracking-[.2em] text-primary/30 mb-1">Album</p>
                <h1 class="font-heading text-3xl font-black text-primary italic">{{ $album->title }}</h1>
                <p class="text-xs font-medium text-muted-foreground/50 mt-2">{{ $album->images->count() }} images</p>
            </div>

            <div class="p-8 space-y-8">
                @if($album->description)
                    <div>
                        <h3 class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/40 mb-2">Description</h3>
                        <p class="text-primary leading-relaxed text-sm">{{ $album->description }}</p>
                    </div>
                @endif

                <div>
                    <h3 class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/40 mb-2">Status</h3>
                    <x-admin-badge :status="$album->is_published ? 'published' : 'draft'" />
                </div>

                @if($album->images->count() > 0)
                    <div>
                        <h3 class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/40 mb-4">Images</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach($album->images as $image)
                                <div class="aspect-square rounded-xl overflow-hidden border border-black/[.04] bg-[#F5F7FA]">
                                    @if($image->type === 'video')
                                        <div class="w-full h-full flex items-center justify-center text-muted-foreground/30">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="5 3 19 12 5 21 5 3" /></svg>
                                        </div>
                                    @else
                                        <img src="{{ \Illuminate\Support\Facades\Storage::disk(config('filesystems.default'))->url('gallery/' . $image->storage_path) }}" alt="{{ $image->title }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
