<x-admin-layout>
    <div class="px-6 py-12 max-w-5xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('admin.announcements.index') }}" class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-muted-foreground hover:text-primary transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Back to Announcements
            </a>
        </div>

        <div class="bg-card rounded-[2.5rem] border shadow-2xl overflow-hidden">
            <div class="p-10 border-b bg-muted/20">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-primary/40 mb-1">Announcement</p>
                <h1 class="font-heading text-4xl font-black text-primary italic">{{ $announcement->title }}</h1>
                <p class="text-xs font-medium text-muted-foreground mt-2">Posted {{ $announcement->created_at->format('F d, Y') }}</p>
            </div>

            <div class="p-10 space-y-8">
                <div>
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60 mb-2">Content</h3>
                    <div class="text-primary leading-relaxed whitespace-pre-wrap">{{ $announcement->content }}</div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60 mb-2">Status</h3>
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $announcement->is_published ? 'bg-green-100 text-green-700 border-green-200' : 'bg-muted text-muted-foreground border-border' }}">
                            {{ $announcement->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </div>
                    @if($announcement->expires_at)
                        <div>
                            <h3 class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60 mb-2">Expires</h3>
                            <p class="font-bold text-primary">{{ $announcement->expires_at->format('F d, Y g:i A') }}</p>
                        </div>
                    @endif
                </div>

                @if($announcement->is_recruitment)
                    <div class="bg-accent/5 p-6 rounded-2xl border border-accent/10">
                        <h3 class="text-[10px] font-black uppercase tracking-widest text-accent mb-2">Recruitment</h3>
                        <p class="text-sm text-primary">This announcement is for organization recruitment.</p>
                        @if($announcement->registration_link)
                            <a href="{{ $announcement->registration_link }}" target="_blank" class="inline-block mt-2 text-sm font-bold text-primary hover:underline">Registration Link →</a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
