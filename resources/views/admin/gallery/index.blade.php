<x-admin-layout>
    <div class="p-6">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-heading font-bold text-primary">Photo Gallery</h1>
                <p class="text-muted-foreground mt-1">Manage your parish albums and memories</p>
            </div>
            <a href="{{ route('admin.gallery.create') }}" class="px-6 py-3 bg-primary text-primary-foreground rounded-lg font-bold shadow-lg shadow-primary/20 hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-circle"><circle cx="12" cy="12" r="10"/><path d="M8 12h8"/><path d="M12 8v8"/></svg>
                Create Album
            </a>
        </div>

        @if($albums->isEmpty())
            <div class="bg-card rounded-2xl border border-dashed p-20 text-center">
                <div class="h-20 w-20 rounded-full bg-accent/10 flex items-center justify-center text-accent mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-images"><path d="M18 22H4a2 2 0 0 1-2-2V6"/><path d="M22 18H8a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2z"/><circle cx="11" cy="7" r="2"/><path d="m24 13-4-4L9 18"/></svg>
                </div>
                <h2 class="text-2xl font-heading font-bold text-primary">No albums yet</h2>
                <p class="text-muted-foreground max-w-sm mx-auto mt-2">Start capturing your parish moments by creating your first photo album.</p>
                <a href="{{ route('admin.gallery.create') }}" class="mt-8 inline-flex items-center gap-2 text-accent font-bold hover:underline">
                    Create first album &rarr;
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($albums as $album)
                    <div class="bg-card rounded-2xl border shadow-sm group overflow-hidden hover:shadow-xl transition-all border-muted/60 flex flex-col">
                        <div class="aspect-video bg-muted relative overflow-hidden shrink-0">
                            @if($album->images->count() > 0)
                                <img src="{{ $album->images->first()->url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-muted-foreground/40 gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-image-off"><line x1="2" x2="22" y1="2" y2="22"/><path d="M10.41 10.41 2 18"/><path d="M8.13 8.13 11 5l9 9"/><path d="m16 16 4 4V8a2 2 0 0 0-2-2"/></svg>
                                    <span class="text-[10px] uppercase font-bold tracking-widest">Empty Album</span>
                                </div>
                            @endif
                            <div class="absolute top-4 right-4 flex gap-2">
                                <span class="px-2 py-1 rounded-md text-[10px] font-black uppercase tracking-wider {{ $album->is_published ? 'bg-green-500/90 text-white shadow-lg' : 'bg-amber-500/90 text-white shadow-lg' }}">
                                    {{ $album->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </div>
                            <div class="absolute bottom-4 left-4">
                                <span class="bg-black/60 backdrop-blur-md text-white text-[10px] font-bold px-2 py-1 rounded-full flex items-center gap-1.5 shadow-xl border border-white/10">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-camera"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                                    {{ $album->images_count }} Photos
                                </span>
                            </div>
                        </div>

                        <div class="p-6 flex flex-col flex-1">
                            <h3 class="text-xl font-heading font-bold text-primary truncate" title="{{ $album->title }}">{{ $album->title }}</h3>
                            <div class="mt-2 text-sm text-muted-foreground line-clamp-3 leading-relaxed flex-1">
                                {{ $album->description ?: 'No description provided.' }}
                            </div>

                            <div class="flex items-center justify-between pt-6 mt-6 border-t group-hover:border-primary/20 transition-colors">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.gallery.edit', $album) }}" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-primary/5 text-primary hover:bg-primary text-xs font-bold transition-all hover:text-white group/btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.gallery.destroy', $album) }}" method="POST" onsubmit="return confirm('Delete this album and all its images?')">
                                        @csrf @method('DELETE')
                                        <button class="p-2 rounded-lg bg-destructive/5 text-destructive hover:bg-destructive hover:text-white transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                        </button>
                                    </form>
                                </div>
                                <span class="text-[9px] text-muted-foreground font-black uppercase tracking-tighter opacity-50">{{ $album->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-admin-layout>
