<x-admin-layout>
    <div class="px-6 py-12 max-w-5xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('admin.highlights.index') }}" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-muted-foreground hover:text-primary transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Back to Video Highlights
            </a>
        </div>

        <div class="bg-card rounded-[2.5rem] border shadow-2xl overflow-hidden">
            <div class="p-10 border-b bg-muted/20">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/40 mb-1">Video Highlight</p>
                <h1 class="font-heading text-4xl font-black text-primary italic">{{ $highlight->title }}</h1>
            </div>

            <div class="p-10 space-y-8">
                @if($highlight->description)
                    <div>
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60 mb-2">Description</h3>
                        <p class="text-primary leading-relaxed">{{ $highlight->description }}</p>
                    </div>
                @endif

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60 mb-2">Status</h3>
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $highlight->is_published ? 'bg-green-100 text-green-700 border-green-200' : 'bg-muted text-muted-foreground border-border' }}">
                            {{ $highlight->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </div>
                    <div>
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60 mb-2">Sort Order</h3>
                        <p class="font-bold text-primary">{{ $highlight->sort_order }}</p>
                    </div>
                </div>

                <div>
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60 mb-2">Video</h3>
                    @if(Str::startsWith($highlight->video_path, ['http', 'https']))
                        <a href="{{ $highlight->video_path }}" target="_blank" class="text-primary hover:underline font-bold">{{ $highlight->video_path }}</a>
                    @else
                        <p class="font-bold text-primary">{{ $highlight->video_path }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
