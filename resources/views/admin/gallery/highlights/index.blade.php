<x-admin-layout>
    <div class="p-6" x-data>
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-heading font-bold text-primary italic">Video Highlights</h1>
                <p class="text-muted-foreground mt-1 text-sm italic">Standalone cinematic clips and trailers.</p>
            </div>
            <a href="{{ route('admin.highlights.create') }}" class="px-6 py-3 bg-accent text-accent-foreground rounded-xl font-black text-sm shadow-xl hover:scale-[1.02] transition-all flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M23 7l-7 5 7 5V7z"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
                New Highlight
            </a>
        </div>

        @if($highlights->isEmpty())
            <x-admin-empty
                title="No highlights yet"
                description="Start by adding your first standalone cinematic video highlight."
                icon="empty"
            />
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="highlights-container">
                @foreach($highlights as $highlight)
                    <div class="bg-card border rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow group relative" data-id="{{ $highlight->id }}">
                        <div class="aspect-video bg-muted relative" x-data="{ playing: false }">
                            @if($highlight->is_external)
                                @if($highlight->thumbnail_url)
                                    <template x-if="!playing">
                                        <div class="relative w-full h-full cursor-pointer" @click="playing = true">
                                            <img src="{{ $highlight->thumbnail_url }}" alt="{{ $highlight->title }}" class="w-full h-full object-cover" loading="lazy">
                                            <div class="absolute inset-0 flex items-center justify-center bg-black/30 group-hover:bg-black/40 transition-colors">
                                                <div class="h-14 w-14 rounded-full bg-white/90 flex items-center justify-center shadow-xl group-hover:scale-110 transition-transform">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" class="text-primary ml-1"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="playing">
                                        <iframe src="{{ $highlight->embed_url }}?autoplay=1" class="absolute inset-0 w-full h-full" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen x-init="$nextTick(() => $el.focus())"></iframe>
                                    </template>
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-primary/10">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-accent"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/></svg>
                                    </div>
                                @endif
                            @else
                                <video class="w-full h-full object-cover" poster="{{ $highlight->thumbnail_url }}">
                                    <source src="{{ $highlight->video_url }}" type="video/mp4">
                                </video>
                                <div class="absolute inset-0 flex items-center justify-center bg-black/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polygon points="10 8 16 12 10 16 10 8"/></svg>
                                </div>
                            @endif
                            <div class="absolute top-4 left-4">
                                <x-admin-badge :status="$highlight->is_published ? 'published' : 'draft'" />
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="font-heading font-bold text-xl text-primary italic mb-2 line-clamp-1">{{ $highlight->title }}</h3>
                            <p class="text-sm text-muted-foreground italic line-clamp-2 mb-6">{{ $highlight->description }}</p>
                            
                            <div class="flex items-center justify-between pt-4 border-t">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.highlights.edit', $highlight) }}" class="p-2 bg-muted/50 hover:bg-accent hover:text-accent-foreground rounded-lg transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                    </a>
                                    <form :id="'delete-highlight-{{ $highlight->id }}" action="{{ route('admin.highlights.destroy', $highlight) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="button" aria-label="Delete highlight"
                                            @click="$store.confirm.open({
                                                title: 'Delete Highlight',
                                                message: 'Are you sure you want to permanently remove this video highlight? This action cannot be undone.',
                                                onConfirm: () => document.getElementById('delete-highlight-{{ $highlight->id }}').submit()
                                            })"
                                            class="p-2 bg-muted/50 hover:bg-destructive hover:text-white rounded-lg transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                        </button>
                                    </form>
                                </div>
                                <div class="cursor-move p-2 text-muted-foreground hover:text-primary drag-handle">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="16" y2="6"/><line x1="8" y1="12" x2="16" y2="12"/><line x1="8" y1="18" x2="16" y2="18"/></svg>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        const container = document.getElementById('highlights-container');
        if (container) {
            new Sortable(container, {
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'opacity-50',
                onEnd: function() {
                    const orders = {};
                    container.querySelectorAll('[data-id]').forEach((el, idx) => {
                        orders[el.dataset.id] = idx;
                    });

                    fetch("{{ route('admin.highlights.reorder') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ orders })
                    });
                }
            });
        }
    </script>
</x-admin-layout>
