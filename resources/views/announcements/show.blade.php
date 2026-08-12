<x-public-layout>
    <x-slot name="meta">
        <meta name="description" content="{{ Str::limit(strip_tags($announcement->content), 160) }}">
        <meta property="og:title" content="{{ $announcement->title }} | Sto. Rosario Parish">
        <meta property="og:description" content="{{ Str::limit(strip_tags($announcement->content), 160) }}">
    </x-slot>

    <div class="container py-12 mx-auto px-4 max-w-4xl">
        <nav class="flex mb-8 text-sm font-medium text-muted-foreground" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li><a href="{{ route('home') }}" class="hover:text-primary transition-colors">Home</a></li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 mx-1" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg>
                        <a href="{{ route('announcements.index') }}" class="hover:text-primary transition-colors">Announcements</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 mx-1" fill="currentColor" viewBox="0 0 20 20"><path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path></svg>
                        <span class="text-primary truncate max-w-[200px]">{{ $announcement->title }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        @php
            $category = $announcement->category ?? ($announcement->is_recruitment ? 'Recruitment' : 'Parish Life');
            $categoryStyles = match(strtolower($category)) {
                'liturgical' => 'bg-purple-50 text-purple-800 border-purple-200/80',
                'parish event', 'event' => 'bg-indigo-50 text-indigo-800 border-indigo-200/80',
                'recruitment' => 'bg-emerald-50 text-emerald-800 border-emerald-200/80',
                default => 'bg-amber-50 text-amber-900 border-amber-200/80',
            };
        @endphp

        <article class="bg-card rounded-[2.5rem] border shadow-2xl overflow-hidden">
            <div class="relative h-64 md:h-80 bg-[#0D2A52] overflow-hidden">
                <img src="{{ \Illuminate\Support\Facades\Storage::disk('supabase')->url('assets/img/church1.webp') }}" alt="{{ $announcement->title }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/20"></div>
            </div>

            <div class="p-8 md:p-12">
                <div class="mb-6">
                    <span class="inline-block text-[9.5px] font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full border {{ $categoryStyles }}">
                        {{ strtoupper($category) }}
                    </span>
                </div>

                <h1 class="font-heading text-3xl md:text-5xl font-black text-primary mb-6 italic leading-tight">
                    {{ $announcement->title }}
                </h1>

                <div class="prose prose-lg max-w-none text-muted-foreground leading-relaxed mb-8">
                    {!! $announcement->content !!}
                </div>

                @if($announcement->is_recruitment && $announcement->registration_link)
                    <div class="mt-10 p-8 bg-accent/5 rounded-[2.5rem] border border-accent/10 text-center">
                        <p class="text-xs font-black uppercase text-accent tracking-widest mb-4">Registration</p>
                        <a href="{{ $announcement->registration_link }}" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 px-8 py-4 rounded-xl gold-btn text-[11px] font-black uppercase tracking-widest">
                            Register Now
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M7 7h10v10"/><path d="M7 17 21 3"/></svg>
                        </a>
                    </div>
                @endif

                @if(isset($recentAnnouncements) && $recentAnnouncements->isNotEmpty())
                    <div class="mt-12 pt-12 border-t border-muted">
                        <h2 class="text-xs font-black uppercase tracking-widest text-accent mb-6">Recent Announcements</h2>
                        <div class="space-y-4">
                            @foreach($recentAnnouncements as $ann)
                                <div class="flex gap-3 items-center">
                                    <span class="flex-shrink-0 w-2 h-2 rounded-full bg-accent/50"></span>
                                    <a href="{{ route('announcements.show', $ann) }}" class="text-primary hover:text-accent transition-colors font-medium">
                                        {{ $ann->title }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </article>

        <div class="mt-12 text-center">
            <a href="{{ route('announcements.index') }}" class="inline-flex items-center gap-2 text-primary font-bold hover:text-accent transition-colors group">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="group-hover:-translate-x-1 transition-transform"><path d="m15 18-6-6 6-6"/></svg>
                Back to All Announcements
            </a>
        </div>
    </div>
</x-public-layout>
