<x-public-layout>
    <x-slot name="meta">
        <meta name="description" content="Stay updated with the latest announcements and parish news from Sto. Rosario Parish.">
        <meta property="og:title" content="Parish Announcements | Sto. Rosario Parish">
        <meta property="og:description" content="Latest news, novenas, community updates, and event announcements from Sto. Rosario Parish.">
    </x-slot>

    <section class="max-w-6xl mx-auto px-6 pt-28 pb-16" x-data="announcementTabs()" x-init="init()">
        {{-- Header --}}
        <div class="text-center mb-12 animate-in fade-in slide-in-from-bottom-4 duration-500">
            <p class="text-[12px] font-black uppercase tracking-[0.3em] text-accent mb-4">Stay Informed</p>
            <h1 class="font-heading text-4xl md:text-6xl font-black mb-6 text-primary italic uppercase leading-tight">
                Parish Announcements
            </h1>
            <div class="h-1.5 w-32 bg-accent mx-auto rounded-full mb-8"></div>
            <p class="text-xl text-muted-foreground max-w-2xl mx-auto italic">
                Latest news, novenas, and community updates from Sto. Rosario Parish.
            </p>
        </div>

        {{-- Tab Bar --}}
        <div class="relative mb-10">
            <div class="flex items-center justify-center gap-0 border-b-2 border-muted/50 overflow-x-auto [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]" x-ref="tabBar">
                <button @click="switchTab('all')"
                    :class="activeTab === 'all' ? 'text-primary border-primary' : 'text-muted-foreground border-transparent hover:text-primary/60'"
                    class="relative px-5 py-3 text-xs font-bold uppercase tracking-widest whitespace-nowrap transition-colors duration-200 border-b-2 -mb-[2px]">
                    All
                    <span class="ml-1.5 inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full text-[10px] font-black transition-colors duration-200"
                        :class="activeTab === 'all' ? 'bg-primary/10 text-primary' : 'bg-muted text-muted-foreground'">
                        <span x-text="counts['all']"></span>
                    </span>
                </button>
                @foreach($categories as $cat)
                    <button @click="switchTab('{{ $cat }}')"
                        :class="activeTab === '{{ $cat }}' ? 'text-primary border-primary' : 'text-muted-foreground border-transparent hover:text-primary/60'"
                        class="relative px-5 py-3 text-xs font-bold uppercase tracking-widest whitespace-nowrap transition-colors duration-200 border-b-2 -mb-[2px]">
                        {{ $cat }}
                        <span class="ml-1.5 inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full text-[10px] font-black transition-colors duration-200"
                            :class="activeTab === '{{ $cat }}' ? 'bg-primary/10 text-primary' : 'bg-muted text-muted-foreground'">
                            <span x-text="counts['{{ $cat }}']"></span>
                        </span>
                    </button>
                @endforeach
            </div>
            {{-- Sliding underline indicator --}}
            <div class="absolute bottom-0 h-[2px] bg-primary rounded-full transition-all duration-300 ease-out"
                x-ref="underline"
                :style="underlineStyle"></div>
        </div>

        {{-- Announcements Grid (fades during tab switch) --}}
        <div class="transition-all duration-300 ease-out" :class="loading ? 'opacity-30 pointer-events-none scale-[0.99]' : 'opacity-100 scale-100'">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="ann in announcements" :key="ann.id">
                    <article class="bg-card border border-muted rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden group relative h-[268px]"
                        :style="'border-top-width:4px;border-top-color:' + getCategoryColor(ann.category)">
                        <div class="p-5 flex flex-col h-full">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <template x-if="ann.is_recruitment">
                                        <span class="inline-flex items-center gap-1 text-[8px] font-black uppercase tracking-widest text-accent">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                            <span>Recruitment</span>
                                        </span>
                                    </template>
                                </div>
                                <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center" :style="'background:' + getCategoryTint(ann.category)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" :stroke="getCategoryColor(ann.category)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 19V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v13"/><path d="M4 19l8-8 8 8"/><path d="M8 7v4"/><path d="M16 7v2"/>
                                    </svg>
                                </div>
                            </div>
                            <h3 class="font-heading font-bold italic text-base text-primary leading-tight mb-2 line-clamp-2 min-h-[2.7em] flex-1 group-hover:text-accent transition-colors">
                                <a :href="ann.url" class="focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary rounded" x-text="ann.title"></a>
                            </h3>
                            <p class="text-sm text-muted-foreground leading-relaxed line-clamp-2 min-h-[2.6em] mb-4" x-text="ann.content"></p>
                            <div class="mt-auto pt-4 border-t border-muted flex items-center justify-between">
                                <a :href="ann.url" class="inline-flex items-center gap-1 text-[11px] font-bold uppercase tracking-wider text-primary hover:text-accent transition-colors">
                                    Read more <span aria-hidden="true">&rarr;</span>
                                </a>
                                <template x-if="ann.is_recruitment && ann.registration_link">
                                    <a :href="ann.registration_link" target="_blank" rel="noopener" class="gold-btn text-[9.5px] px-2.5 py-1 rounded-lg font-bold uppercase tracking-wider">Register Now</a>
                                </template>
                            </div>
                        </div>
                    </article>
                </template>
            </div>

            {{-- Empty state --}}
            <div x-show="announcements.length === 0" class="text-center py-20 bg-muted/30 rounded-[3rem] border-2 border-dashed">
                <div class="font-cinzel text-5xl mb-5 opacity-20 text-primary">✝</div>
                <h3 class="font-heading font-bold italic text-2xl mb-2 text-primary">No Announcements</h3>
                <p class="text-muted-foreground italic">There are currently no announcements in this category. Please check back soon.</p>
            </div>
        </div>
    </section>

    <script>
        function announcementTabs() {
            return {
                activeTab: @js($category ?? 'all'),
                announcements: @js($initialAnnouncements),
                counts: @js($counts),
                loading: false,
                underlineWidth: 0,
                underlineLeft: 0,

                init() {
                    this.$nextTick(() => this.updateUnderline());
                    window.addEventListener('resize', () => this.updateUnderline());
                    window.addEventListener('popstate', () => {
                        const params = new URLSearchParams(window.location.search);
                        const cat = params.get('category') || 'all';
                        if (cat !== this.activeTab) {
                            this.fetchTab(cat, false);
                        }
                    });
                },

                get underlineStyle() {
                    return `width: ${this.underlineWidth}px; left: ${this.underlineLeft}px;`;
                },

                updateUnderline() {
                    this.$nextTick(() => {
                        const bar = this.$refs.tabBar;
                        if (!bar) return;
                        const activeBtn = bar.querySelector('button.text-primary') || bar.querySelector('button');
                        if (!activeBtn) return;
                        const barRect = bar.getBoundingClientRect();
                        const btnRect = activeBtn.getBoundingClientRect();
                        this.underlineWidth = btnRect.width;
                        this.underlineLeft = btnRect.left - barRect.left + bar.scrollLeft;
                    });
                },

                async switchTab(category) {
                    if (this.activeTab === category) return;
                    await this.fetchTab(category, true);
                },

                async fetchTab(category, pushState = true) {
                    this.loading = true;

                    if (pushState) {
                        const url = new URL(window.location);
                        if (category === 'all') {
                            url.searchParams.delete('category');
                        } else {
                            url.searchParams.set('category', category);
                        }
                        history.pushState({}, '', url);
                    }

                    try {
                        const res = await fetch(`{{ route('announcements.filter') }}?category=${encodeURIComponent(category)}`);
                        const data = await res.json();
                        this.announcements = data.announcements;
                        this.activeTab = category;
                        this.updateUnderline();
                    } catch (e) {
                        console.error('Failed to load announcements:', e);
                    } finally {
                        this.loading = false;
                    }
                },

                getCategoryColor(cat) {
                    const colors = {
                        'Parish Life': '#B5562F',
                        'Liturgical': '#6B3FA0',
                        'Sacraments': '#A87F22',
                        'Formation': '#1B2A4A',
                    };
                    return colors[cat] || '#2D6A4F';
                },

                getCategoryTint(cat) {
                    const tints = {
                        'Parish Life': '#FBEEE7',
                        'Liturgical': '#F3ECFA',
                        'Sacraments': '#FBF3DC',
                        'Formation': '#EEF1F6',
                    };
                    return tints[cat] || '#E8F4F0';
                },
            }
        }
    </script>
</x-public-layout>
