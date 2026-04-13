<x-admin-layout>
    <div class="p-8">
        <div class="flex items-center justify-between mb-12">
            <div>
                <h1 class="text-4xl font-black text-primary uppercase font-heading">Weekly Bulletins</h1>
                <p class="text-muted-foreground italic">Manage and upload weekly parish bulletins.</p>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Upload Form -->
            <div class="bg-card border rounded-[2.5rem] p-8 shadow-sm h-fit">
                <h2 class="text-xl font-black text-primary uppercase mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-accent"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                    Upload New
                </h2>
                <form action="{{ route('admin.bulletins.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground mb-2">Bulletin Title</label>
                        <input type="text" name="title" required 
                            class="w-full px-4 py-3 rounded-xl border-muted bg-muted/20 focus:border-accent focus:ring-0 transition-all text-sm font-bold"
                            placeholder="e.g. Easter Sunday 2026">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground mb-2">Publish Date</label>
                        <input type="date" name="published_date" required 
                            class="w-full px-4 py-3 rounded-xl border-muted bg-muted/20 focus:border-accent focus:ring-0 transition-all text-sm font-bold"
                            value="{{ date('Y-m-d') }}">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground mb-2">PDF File</label>
                        <input type="file" name="file" accept="application/pdf" required 
                            class="w-full text-xs text-muted-foreground file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-primary file:text-white hover:file:bg-primary/90 cursor-pointer">
                    </div>
                    <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white font-black py-4 rounded-xl shadow-lg shadow-primary/20 transition-all active:scale-95 uppercase text-xs tracking-widest">
                        Upload Bulletin
                    </button>
                </form>
            </div>

            <!-- List -->
            <div class="lg:col-span-2 space-y-4">
                @foreach($bulletins as $bulletin)
                    <div class="bg-card border rounded-3xl p-6 shadow-sm flex items-center justify-between group hover:border-accent transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-12 rounded-2xl bg-primary/5 flex items-center justify-center text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                            </div>
                            <div>
                                <h3 class="font-black text-primary uppercase text-sm">{{ $bulletin->title }}</h3>
                                <p class="text-xs text-muted-foreground font-bold tracking-widest">{{ $bulletin->published_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                             <a href="{{ Storage::url($bulletin->file_path) }}" target="_blank" class="p-2 text-muted-foreground hover:text-primary transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12c-1.8 0-4.5 2.7-4.5 4.5V20"/><path d="M3 12c1.8 0 4.5-2.7 4.5-4.5V4"/><path d="M12 3v18"/><path d="M3 12h18"/></svg>
                            </a>
                            <form action="{{ route('admin.bulletins.destroy', $bulletin) }}" method="POST" onsubmit="return confirm('Delete this bulletin?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-muted-foreground hover:text-destructive transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
                {{ $bulletins->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
