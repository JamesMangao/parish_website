<x-admin-layout>
    <div class="p-6">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-heading font-bold text-primary">Create New Album</h1>
                <p class="text-muted-foreground mt-1">Group your photos into a beautiful album</p>
            </div>
            <a href="{{ route('admin.gallery.index') }}" class="px-4 py-2 text-sm font-medium text-muted-foreground hover:text-primary flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                Back to Albums
            </a>
        </div>

        @if($errors->any())
            <div class="max-w-2xl mb-6 bg-destructive/10 border border-destructive/20 text-destructive p-4 rounded-xl">
                <ul class="list-disc list-inside text-sm font-bold">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-2xl mb-6 bg-destructive/10 border border-destructive/20 text-destructive p-4 rounded-xl font-bold text-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="max-w-2xl">
            <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{ loading: false }" @submit="loading = true">
                @csrf
                <div class="bg-card rounded-xl border p-6 shadow-sm space-y-4">
                    <div class="space-y-2">
                        <label for="title" class="text-sm font-bold text-primary uppercase tracking-wider">Album Title</label>
                        <input 
                            type="text" 
                            name="title" 
                            id="title" 
                            required
                            placeholder="e.g. Parish Fiesta 2026"
                            class="w-full px-4 py-3 rounded-lg border bg-background focus:ring-2 focus:ring-accent/20 outline-none transition-all"
                        >
                    </div>

                    <div class="space-y-2">
                        <label for="description" class="text-sm font-bold text-primary uppercase tracking-wider">Description</label>
                        <textarea 
                            name="description" 
                            id="description" 
                            rows="4" 
                            placeholder="Tell the story behind these photos..."
                            class="w-full px-4 py-3 rounded-lg border bg-background focus:ring-2 focus:ring-accent/20 outline-none transition-all resize-none"
                        ></textarea>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_published" id="is_published" value="1" checked class="rounded border-gray-300 text-accent focus:ring-accent">
                        <label for="is_published" class="text-sm font-semibold text-primary">Publish immediately</label>
                    </div>
                </div>

                <div class="bg-card rounded-xl border p-6 shadow-sm space-y-4" x-data="{ images: [] }">
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-bold text-primary uppercase tracking-wider">Upload Photos</label>
                        <span class="text-xs text-muted-foreground" x-text="`${images.length} files selected`"></span>
                    </div>

                    <div 
                        class="relative border-2 border-dashed border-muted rounded-xl p-8 flex flex-col items-center justify-center hover:border-accent/40 hover:bg-accent/5 transition-all cursor-pointer group"
                        :class="loading ? 'opacity-50 cursor-wait' : ''"
                        @click="if(!loading) $refs.fileInput.click()"
                    >
                        <input 
                            type="file" 
                            name="images[]" 
                            multiple 
                            x-ref="fileInput" 
                            class="hidden"
                            @change="images = Array.from($event.target.files)"
                        >
                        <div class="h-12 w-12 rounded-full bg-accent/10 flex items-center justify-center text-accent mb-3 group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-upload-cloud"><path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"/><path d="M12 12v9"/><path d="m16 16-4-4-4 4"/></svg>
                        </div>
                        <p class="text-sm font-bold text-primary">Click to select multiple photos</p>
                        <p class="text-xs text-muted-foreground mt-1">Maximum 5MB per file</p>
                    </div>

                    <div class="grid grid-cols-4 gap-4 mt-4" x-show="images.length > 0">
                        <template x-for="(file, index) in images" :key="index">
                            <div class="aspect-square rounded-lg bg-muted relative group overflow-hidden border">
                                <img :src="URL.createObjectURL(file)" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.gallery.index') }}" class="px-6 py-3 rounded-lg font-bold text-primary hover:bg-muted transition-all">Cancel</a>
                    <button 
                        type="submit" 
                        :disabled="loading"
                        class="px-8 py-3 bg-accent text-accent-foreground rounded-lg font-bold shadow-lg shadow-accent/20 transition-all flex items-center justify-center gap-2"
                        :class="loading ? 'opacity-70 cursor-wait' : 'hover:scale-105 active:scale-95'"
                    >
                        <template x-if="loading">
                            <svg class="animate-spin h-5 w-5 text-accent-foreground" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </template>
                        <span x-text="loading ? 'Creating Album...' : 'Create Album'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
