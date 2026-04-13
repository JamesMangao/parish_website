<x-admin-layout>
    <div class="p-6">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-heading font-bold text-primary">Edit Album</h1>
                <p class="text-muted-foreground mt-1">Manage photos and details for "{{ $album->title }}"</p>
            </div>
            <a href="{{ route('admin.gallery.index') }}" class="px-4 py-2 text-sm font-medium text-muted-foreground hover:text-primary flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                Back to Albums
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Details Form -->
            <div class="lg:col-span-1">
                <form action="{{ route('admin.gallery.update', $album) }}" method="POST" class="space-y-6">
                    @csrf @method('PUT')
                    <div class="bg-card rounded-xl border p-6 shadow-sm space-y-4">
                        <div class="space-y-2">
                            <label for="title" class="text-sm font-bold text-primary uppercase tracking-wider">Album Title</label>
                            <input 
                                type="text" 
                                name="title" 
                                id="title" 
                                value="{{ $album->title }}"
                                required
                                class="w-full px-4 py-3 rounded-lg border bg-background focus:ring-2 focus:ring-accent/20 outline-none transition-all"
                            >
                        </div>

                        <div class="space-y-2">
                            <label for="description" class="text-sm font-bold text-primary uppercase tracking-wider">Description</label>
                            <textarea 
                                name="description" 
                                id="description" 
                                rows="4" 
                                class="w-full px-4 py-3 rounded-lg border bg-background focus:ring-2 focus:ring-accent/20 outline-none transition-all resize-none"
                            >{{ $album->description }}</textarea>
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="is_published" id="is_published" value="1" {{ $album->is_published ? 'checked' : '' }} class="rounded border-gray-300 text-accent focus:ring-accent">
                            <label for="is_published" class="text-sm font-semibold text-primary">Published</label>
                        </div>

                        <button type="submit" class="w-full py-3 bg-primary text-primary-foreground rounded-lg font-bold shadow-lg shadow-primary/20 hover:scale-105 active:scale-95 transition-all">
                            Save Changes
                        </button>
                    </div>
                </form>

                <!-- Add More Images -->
                <div class="mt-8 bg-card rounded-xl border p-6 shadow-sm border-accent/20 bg-accent/5">
                    <h3 class="text-lg font-heading font-bold text-primary mb-4 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-image-plus"><path d="M16 5h6"/><path d="M19 2v6"/><rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                        Add More Photos
                    </h3>
                    <form action="{{ route('admin.gallery.add-images', $album) }}" method="POST" enctype="multipart/form-data" x-data="{ count: 0, loading: false }" @submit="loading = true">
                        @csrf
                        <input type="file" name="images[]" multiple class="hidden" x-ref="addInput" @change="count = $refs.addInput.files.length">
                        <div 
                            class="border-2 border-dashed border-accent/30 rounded-lg p-6 flex flex-col items-center justify-center cursor-pointer hover:bg-accent/10 transition-all mb-4"
                            :class="loading ? 'opacity-50 cursor-not-allowed' : ''"
                            @click="if(!loading) $refs.addInput.click()"
                        >
                            <span class="text-xs font-bold text-accent" x-text="count === 0 ? 'Click to select files' : `${count} files ready` "></span>
                        </div>
                        <button 
                            type="submit" 
                            x-show="count > 0" 
                            :disabled="loading"
                            class="w-full py-2 bg-accent text-accent-foreground rounded-lg font-bold text-sm transition-all flex items-center justify-center gap-2"
                            :class="loading ? 'opacity-70 cursor-wait' : 'hover:scale-[1.02]'"
                        >
                            <template x-if="loading">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </template>
                            <span x-text="loading ? 'Uploading...' : 'Upload Selected'"></span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Photos Grid -->
            <div class="lg:col-span-2">
                <div class="bg-card rounded-xl border p-6 shadow-sm">
                    <h3 class="text-xl font-heading font-bold text-primary mb-6">Album Photos ({{ $album->images->count() }})</h3>
                    
                    @if($album->images->isEmpty())
                        <p class="text-center py-20 text-muted-foreground italic">No photos in this album yet.</p>
                    @else
                        <div class="grid grid-cols-3 sm:grid-cols-5 md:grid-cols-6 lg:grid-cols-8 gap-2">
                            @foreach($album->images as $img)
                                <div class="relative group aspect-square rounded-md overflow-hidden border bg-muted">
                                    <img src="{{ $img->url }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                        <form action="{{ route('admin.gallery.remove-image', $img) }}" method="POST" onsubmit="return confirm('Delete this image?')">
                                            @csrf @method('DELETE')
                                            <button class="p-1.5 bg-destructive text-white rounded-full hover:scale-110 active:scale-95 transition-all shadow-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
