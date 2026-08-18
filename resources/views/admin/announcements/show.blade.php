<x-admin-layout>
    <div class="max-w-5xl mx-auto">
        <div class="mb-8">
            <a href="{{ route('admin.announcements.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-muted-foreground/50 hover:text-primary transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6" /></svg>
                Back to Announcements
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-black/[.04] shadow-sm shadow-black/[.02] overflow-hidden">
            <div class="px-8 py-8 border-b border-black/[.04] bg-gradient-to-r from-[#F5F7FA] to-white">
                <p class="text-[10px] font-black uppercase tracking-[.2em] text-primary/30 mb-1">Announcement</p>
                <h1 class="font-heading text-3xl font-black text-primary italic">{{ $announcement->title }}</h1>
                <p class="text-xs font-medium text-muted-foreground/50 mt-2">Posted {{ $announcement->created_at->format('F d, Y') }}</p>
            </div>

            <div class="p-8 space-y-8">
                <div>
                    <h3 class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/40 mb-3">Content</h3>
                    <div class="text-primary leading-relaxed whitespace-pre-wrap">{{ $announcement->content }}</div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/40 mb-2">Status</h3>
                        <x-admin-badge :status="$announcement->is_published ? 'published' : 'draft'" />
                    </div>
                    @if($announcement->expires_at)
                        <div>
                            <h3 class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/40 mb-2">Expires</h3>
                            <p class="font-bold text-primary text-sm">{{ $announcement->expires_at->format('F d, Y g:i A') }}</p>
                        </div>
                    @endif
                </div>

                @if($announcement->is_recruitment)
                    <div class="bg-accent/5 p-6 rounded-2xl border border-accent/10">
                        <h3 class="text-[10px] font-black uppercase tracking-[.15em] text-accent mb-2">Recruitment</h3>
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
