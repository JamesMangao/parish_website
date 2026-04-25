<nav x-data="{ open: false, scrolled: false }"
     x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 80 }, { passive: true })"
     class="fixed top-0 left-0 right-0 z-50 transition-all duration-500">

    {{-- Background layer — transparent on hero, cream when scrolled --}}
    <div class="absolute inset-0 transition-all duration-500 pointer-events-none"
         :class="scrolled ? 'opacity-100' : 'opacity-0'"
         style="background: rgba(253,250,244,0.95); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border-bottom: 1px solid rgba(180,150,80,0.15);"></div>

    <div class="relative z-10 max-w-[1200px] mx-auto px-6 lg:px-10 h-[72px] flex items-center justify-between">

        {{-- Brand --}}
        <a href="/" class="flex items-center gap-3 group">
            <div class="relative w-9 h-9 flex items-center justify-center">
                <div class="absolute inset-0 rounded-full transition-all duration-500 border"
                     :class="scrolled ? 'border-[rgba(201,168,76,0.5)]' : 'border-[rgba(201,168,76,0.6)]'"></div>
                <span class="text-[#C9A84C] text-lg leading-none" style="font-family: 'Cinzel', Georgia, serif;">✝</span>
            </div>
            <div>
                <div class="text-[14px] font-bold tracking-[0.12em] leading-none transition-colors duration-500"
                     :class="scrolled ? 'text-stone-800' : 'text-white'"
                     style="font-family: 'Cormorant Garamond', Georgia, serif; font-style: italic;">Sto. Rosario</div>
                <div class="text-[8.5px] tracking-[0.35em] font-medium uppercase mt-0.5 transition-colors duration-500"
                     :class="scrolled ? 'text-stone-400' : 'text-white/50'">Parish · Pacita</div>
            </div>
        </a>

        {{-- Desktop Nav --}}
        <div class="hidden md:flex items-center gap-1">

            {{-- Worship Dropdown --}}
            <div class="relative" x-data="{ sub: false }" @mouseenter="sub = true" @mouseleave="sub = false">
                <button class="flex items-center gap-1.5 px-4 py-2 rounded-full text-[11px] font-semibold uppercase tracking-widest transition-all duration-300"
                        :class="scrolled
                            ? 'text-stone-500 hover:text-stone-800 hover:bg-[rgba(245,237,216,0.6)]'
                            : 'text-white/75 hover:text-white hover:bg-[rgba(255,255,255,0.1)]'">
                    Worship
                    <svg :class="sub ? 'rotate-180' : ''" class="transition-transform duration-200 opacity-60" xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div x-show="sub"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                     class="absolute top-full left-0 pt-3 w-56" x-cloak>
                    <div class="rounded-2xl border border-[#E8DFC4] bg-[#FEFCF6] p-1.5"
                         style="box-shadow: 0 12px 40px rgba(120,90,20,0.14), 0 1px 0 rgba(255,255,255,0.9) inset;">
                        @foreach([
                            ['Mass Schedule',     '/mass-schedule',     'View all Mass times'],
                            ['Submit Intention',   '/submit-intention',  'Offer a prayer intention'],
                            ['Sacramental Inquiry','/inquiry',           'Baptism, wedding & more'],
                        ] as [$label, $url, $sub])
                        <a href="{{ $url }}" class="flex items-start gap-3 px-4 py-3 rounded-xl hover:bg-[#FDF6E8] transition-colors group/item">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#C9A84C] mt-1.5 shrink-0 opacity-50 group-hover/item:opacity-100 transition-opacity"></span>
                            <div>
                                <div class="text-[11px] font-bold uppercase tracking-wider text-stone-700 group-hover/item:text-stone-900 transition-colors">{{ $label }}</div>
                                <div class="text-[10px] text-stone-400 mt-0.5">{{ $sub }}</div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Community Dropdown --}}
            <div class="relative" x-data="{ sub: false }" @mouseenter="sub = true" @mouseleave="sub = false">
                <button class="flex items-center gap-1.5 px-4 py-2 rounded-full text-[11px] font-semibold uppercase tracking-widest transition-all duration-300"
                        :class="scrolled
                            ? 'text-stone-500 hover:text-stone-800 hover:bg-[rgba(245,237,216,0.6)]'
                            : 'text-white/75 hover:text-white hover:bg-[rgba(255,255,255,0.1)]'">
                    Community
                    <svg :class="sub ? 'rotate-180' : ''" class="transition-transform duration-200 opacity-60" xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div x-show="sub"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                     class="absolute top-full left-0 pt-3 w-56" x-cloak>
                    <div class="rounded-2xl border border-[#E8DFC4] bg-[#FEFCF6] p-1.5"
                         style="box-shadow: 0 12px 40px rgba(120,90,20,0.14), 0 1px 0 rgba(255,255,255,0.9) inset;">
                        @foreach([
                            ['Parish Events',    '/events',  'Liturgical & community events'],
                            ['Photo Gallery',    '/gallery', 'Memories & celebrations'],
                            ['About Our Parish', '/about',   'History & our mission'],
                        ] as [$label, $url, $sub])
                        <a href="{{ $url }}" class="flex items-start gap-3 px-4 py-3 rounded-xl hover:bg-[#FDF6E8] transition-colors group/item">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#C9A84C] mt-1.5 shrink-0 opacity-50 group-hover/item:opacity-100 transition-opacity"></span>
                            <div>
                                <div class="text-[11px] font-bold uppercase tracking-wider text-stone-700 group-hover/item:text-stone-900 transition-colors">{{ $label }}</div>
                                <div class="text-[10px] text-stone-400 mt-0.5">{{ $sub }}</div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <a href="/track"
               class="px-4 py-2 rounded-full text-[11px] font-semibold uppercase tracking-widest transition-all duration-300"
               :class="scrolled
                   ? 'text-stone-500 hover:text-stone-800 hover:bg-[rgba(245,237,216,0.6)]'
                   : 'text-white/75 hover:text-white hover:bg-[rgba(255,255,255,0.1)]'">
                Track Intention
            </a>

            <div class="w-px h-5 mx-2 transition-colors duration-500"
                 :class="scrolled ? 'bg-stone-200' : 'bg-white/20'"></div>

            <a href="/donate"
               class="relative overflow-hidden px-6 py-2.5 rounded-full text-[10.5px] font-bold uppercase tracking-widest transition-all duration-300 hover:scale-[1.03] active:scale-95 group"
               style="background: linear-gradient(135deg, #D4A843 0%, #C9913A 60%, #BF8532 100%); color: #2C1A06; box-shadow: 0 2px 14px rgba(201,168,76,0.40);">
                Donate
                <div class="absolute inset-0 bg-white/0 group-hover:bg-white/10 transition-colors duration-200 rounded-full"></div>
            </a>
        </div>

        {{-- Mobile Toggle --}}
        <button class="md:hidden w-10 h-10 flex items-center justify-center rounded-full transition-all duration-300"
                :class="scrolled ? 'text-stone-700 hover:bg-[rgba(245,237,216,0.6)]' : 'text-white hover:bg-[rgba(255,255,255,0.12)]'"
                @click="open = !open">
            <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x2="21" y1="6" y2="6"/><line x1="3" x2="15" y1="12" y2="12"/><line x1="3" x2="21" y1="18" y2="18"/></svg>
            <svg x-show="open" x-cloak xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="md:hidden overflow-y-auto max-h-[80vh] relative z-10"
         style="background: #FEFCF6; border-top: 1px solid rgba(210,190,140,0.4);"
         x-cloak>
        <div class="max-w-[1200px] mx-auto px-6 py-8 flex flex-col gap-8">
            <div>
                <p class="text-[9px] font-black uppercase tracking-[0.3em] text-[#C9A84C] mb-4">Worship</p>
                <div class="flex flex-col gap-1.5">
                    @foreach([['Mass Schedule','/mass-schedule'],['Submit Intention','/submit-intention'],['Sacramental Inquiry','/inquiry']] as [$label,$url])
                    <a href="{{ $url }}" class="px-5 py-3.5 rounded-2xl text-sm font-semibold text-stone-700 hover:bg-[#FDF6E8] hover:text-stone-900 border border-[rgba(210,190,140,0.35)] transition-colors">{{ $label }}</a>
                    @endforeach
                </div>
            </div>
            <div>
                <p class="text-[9px] font-black uppercase tracking-[0.3em] text-[#C9A84C] mb-4">Community</p>
                <div class="flex flex-col gap-1.5">
                    @foreach([['Parish Events','/events'],['Photo Gallery','/gallery'],['About Our Parish','/about']] as [$label,$url])
                    <a href="{{ $url }}" class="px-5 py-3.5 rounded-2xl text-sm font-semibold text-stone-700 hover:bg-[#FDF6E8] hover:text-stone-900 border border-[rgba(210,190,140,0.35)] transition-colors">{{ $label }}</a>
                    @endforeach
                </div>
            </div>
            <div class="flex flex-col gap-3 pt-2 border-t border-[rgba(210,190,140,0.3)]">
                <a href="/track" class="text-center px-6 py-3.5 rounded-2xl font-semibold text-sm text-stone-800 border border-[rgba(201,168,76,0.4)] bg-[rgba(201,168,76,0.06)] transition-colors">Track Intention</a>
                <a href="/donate" class="text-center px-6 py-3.5 rounded-full font-bold text-sm uppercase tracking-widest" style="background: linear-gradient(135deg, #D4A843, #C9913A); color: #2C1A06;">Donate Now</a>
            </div>
        </div>
    </div>
</nav>