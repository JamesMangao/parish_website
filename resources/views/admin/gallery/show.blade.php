<x-admin-layout>
    <div class="px-6 py-12 max-w-5xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('admin.gallery.index') }}" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-muted-foreground hover:text-primary transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Back to Gallery
            </a>
        </div>

        <div class="bg-card rounded-[2.5rem] border shadow-2xl overflow-hidden">
            <div class="p-10 border-b bg-muted/20">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/40 mb-1">Album</p>
                <h1 class="font-heading text-4xl font-black text-primary italic">{{ $album->title }}</h1>
                <p class="text-xs font-medium text-muted-foreground mt-2">{{ $album->images->count() }} images</p>
            </div>

            <div class="p-10 space-y-8">
                @if($album->description)
                    <div>
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60 mb-2">Description</h3>
                        <p class="text-primary leading-relaxed">{{ $album->description }}</p>
                    </div>
                @endif

                <div>
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60 mb-2">Status</h3>
                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $album->is_published ? 'bg-green-100 text-green-700 border-green-200' : 'bg-muted text-muted-foreground border-border' }}">
                        {{ $album->is_published ? 'Published' : 'Draft' }}
                    </span>
                </div>

                @if($album->images->count() > 0)
                    <div>
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60 mb-4">Images</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach($album->images as $image)
                                <div class="aspect-square rounded-xl overflow-hidden border bg-muted">
                                    @if($image->type === 'video')
                                        <div class="w-full h-full flex items-center justify-center text-muted-foreground">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
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
