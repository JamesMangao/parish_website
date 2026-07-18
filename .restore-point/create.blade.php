<x-admin-layout>
    <div class="p-6 max-w-4xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('admin.highlights.index') }}" class="text-sm font-bold text-muted-foreground hover:text-primary flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Back to Highlights
            </a>
            <h1 class="text-3xl font-heading font-bold text-primary italic">Create Highlight</h1>
            <p class="text-muted-foreground mt-1 italic text-sm">Add a standalone cinematic video reel.</p>
        </div>

        <form action="{{ route('admin.highlights.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <div class="bg-card border rounded-2xl p-8 shadow-sm space-y-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Highlight Title</label>
                    <input type="text" name="title" required placeholder="e.g. Easter Sunday Recap 2026" class="w-full bg-muted/20 border-border rounded-xl px-4 py-3 text-sm focus:ring-accent focus:border-accent font-bold">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Short Description</label>
                    <textarea name="description" rows="3" placeholder="A brief caption for this highlight..." class="w-full bg-muted/20 border-border rounded-xl px-4 py-3 text-sm focus:ring-accent focus:border-accent italic"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                    <div class="space-y-4">
                        <div class="p-4 bg-accent/5 border border-accent/20 rounded-2xl">
                            <label class="text-[10px] font-black uppercase tracking-widest text-accent mb-4 block">Option A: Direct Upload</label>
                            <input type="file" name="video_file" accept="video/mp4,video/x-m4v,video/*" class="text-xs text-muted-foreground file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:opacity-90">
                            <p class="mt-3 text-[10px] text-muted-foreground italic">Best for short, high-quality clips (Max 100MB).</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="p-4 bg-muted/20 border border-border rounded-2xl">
                            <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-4 block">Option B: Video URL</label>
                            <input type="text" name="video_url" placeholder="YouTube or Cloud Link..." class="w-full bg-background border-border rounded-lg px-4 py-2 text-xs focus:ring-accent focus:border-accent">
                            <p class="mt-3 text-[10px] text-muted-foreground italic">Paste a link to YouTube, Vimeo, or a cloud file.</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-4">
                    <input type="checkbox" name="is_published" id="is_published" value="1" checked class="w-4 h-4 rounded border-gray-300 text-accent focus:ring-accent">
                    <label for="is_published" class="text-sm font-bold text-primary italic">Publish Immediately</label>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="px-10 py-4 bg-primary text-primary-foreground rounded-2xl font-black text-sm shadow-2xl hover:scale-[1.02] transition-all flex items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Create Highlight
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
