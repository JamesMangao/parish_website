<nav
    x-data="nav()"
    x-init="init()"
    @keydown.escape.window="closeAll()"
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-500">

    {{-- Scrolled background --}}
    <div class="absolute inset-0 transition-all duration-500 pointer-events-none shadow-sm"
         :class="scrolled ? 'opacity-100' : 'opacity-0'"
         style="background:rgba(255,255,255,0.96); backdrop-filter:blur(20px);
                -webkit-backdrop-filter:blur(20px);
                border-bottom:1px solid rgba(0,0,0,0.05);"></div>

    {{-- ── Top bar ── --}}
    <div class="relative z-10 max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-10
                h-[64px] flex items-center justify-between gap-2">

        {{-- Logo --}}
        <a href="/" class="flex items-center gap-2.5 group flex-shrink-0">
            <div class="relative w-8 h-8 flex items-center justify-center flex-shrink-0">
                <div class="absolute inset-0 rounded-full border transition-colors duration-500"
                     :style="scrolled ? 'border-color:rgba(8,20,45,0.2);' : 'border-color:rgba(245,197,24,0.5);'"></div>
                <span class="text-base leading-none transition-colors duration-300"
                      :class="scrolled ? 'text-[#08142D]' : 'text-white'"
                      style="font-family:'Cinzel',Georgia,serif;">✝</span>
            </div>
            <div>
                <div class="text-[13px] font-bold tracking-[0.1em] leading-none transition-colors duration-300"
                     :class="scrolled ? 'text-[#08142D]' : 'text-white'"
                     style="font-family:'Cormorant Garamond',Georgia,serif; font-style:italic;">
                    Sto. Rosario Parish
                </div>
                <div class="text-[7.5px] tracking-[0.3em] font-medium uppercase mt-0.5 transition-colors duration-300"
                     :class="scrolled ? 'text-[#08142D]/60' : 'text-white/60'">
                    Pacita 1
                </div>
            </div>
        </a>

        {{-- Desktop links --}}
        <div class="hidden md:flex items-center gap-1">

            {{-- Parish Services --}}
            <div class="relative"
                 x-data="dropdown('parish-services')"
                 @click.outside="close()"
                 @keydown.arrow-down.prevent="openAndFocus()"
                 @keydown.enter.prevent="toggle()">

                <button x-ref="trigger" @click="toggle()"
                        :aria-expanded="isOpen" aria-haspopup="menu"
                        class="h-10 px-4 rounded-full flex items-center gap-1.5
                               text-[11px] font-semibold uppercase tracking-widest
                               transition-all duration-150 outline-none"
                        :class="scrolled ? 'text-[#08142D]/80 hover:text-[#08142D] hover:bg-[#08142D]/5' : 'text-white/80 hover:text-white hover:bg-white/10'">
                    Parish Services
                    <svg class="w-3 h-3 transition-transform duration-150"
                         :class="isOpen ? 'rotate-180' : ''"
                         viewBox="0 0 20 20" fill="none" stroke="currentColor"
                         stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m5 7.5 5 5 5-5"/>
                    </svg>
                </button>

                <div x-show="isOpen" x-cloak x-ref="panel"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                     class="absolute top-full pt-2 z-50"
                     :class="edgeFlip ? 'right-0' : 'left-0'">
                    <div class="w-56 rounded-2xl border transition-colors duration-300
                                backdrop-blur-xl p-1.5 shadow-[0_16px_48px_rgba(0,0,0,0.2)]"
                         :class="scrolled ? 'bg-white border-black/5 shadow-xl' : 'border-white/10 bg-[rgba(8,20,45,0.97)]'">
                        @foreach([
                            ['Mass Schedule',      '/mass-schedule',    'View all Mass times'],
                            ['Submit Intention',   '/submit-intention', 'Offer a prayer intention'],
                            ['Sacramental Inquiry','/inquiry',          'Baptism, wedding & more'],
                        ] as [$label,$url,$sub])
                        <a href="{{ $url }}" role="menuitem" tabindex="-1"
                           @keydown.arrow-down.prevent="focusNext($event)"
                           @keydown.arrow-up.prevent="focusPrev($event)"
                           @keydown.escape.prevent="closeAndReturn()"
                           class="flex items-start gap-3 px-4 py-3 rounded-xl
                                  transition-colors duration-150 group/item outline-none"
                           :class="scrolled ? 'hover:bg-gray-50 focus:bg-gray-50' : 'hover:bg-white/8 focus:bg-white/8'">
                            <span class="w-1 h-1 rounded-full mt-2 shrink-0 transition-colors"
                                  :class="scrolled ? 'bg-[#F5C518]' : 'bg-[#F5C518]/40 group-hover/item:bg-[#F5C518]'"></span>
                            <div>
                                <div class="text-[11px] font-bold uppercase tracking-wider transition-colors duration-150"
                                     :class="scrolled ? 'text-[#08142D] group-hover/item:text-black' : 'text-white/90 group-hover/item:text-white'">{{ $label }}</div>
                                <div class="text-[10px] mt-0.5 transition-colors duration-150"
                                     :class="scrolled ? 'text-[#08142D]/60' : 'text-white/80'">{{ $sub }}</div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Community --}}
            <div class="relative"
                 x-data="dropdown('community')"
                 @click.outside="close()"
                 @keydown.arrow-down.prevent="openAndFocus()"
                 @keydown.enter.prevent="toggle()">

                <button x-ref="trigger" @click="toggle()"
                        :aria-expanded="isOpen" aria-haspopup="menu"
                        class="h-10 px-4 rounded-full flex items-center gap-1.5
                               text-[11px] font-semibold uppercase tracking-widest
                               transition-all duration-150 outline-none"
                        :class="scrolled ? 'text-[#08142D]/80 hover:text-[#08142D] hover:bg-[#08142D]/5' : 'text-white/80 hover:text-white hover:bg-white/10'">
                    Community
                    <svg class="w-3 h-3 transition-transform duration-150"
                         :class="isOpen ? 'rotate-180' : ''"
                         viewBox="0 0 20 20" fill="none" stroke="currentColor"
                         stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m5 7.5 5 5 5-5"/>
                    </svg>
                </button>

                <div x-show="isOpen" x-cloak x-ref="panel"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                     x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                     class="absolute top-full pt-2 z-50"
                     :class="edgeFlip ? 'right-0' : 'left-0'">
                    <div class="w-56 rounded-2xl border transition-colors duration-300
                                backdrop-blur-xl p-1.5 shadow-[0_16px_48px_rgba(0,0,0,0.2)]"
                         :class="scrolled ? 'bg-white border-black/5 shadow-xl' : 'border-white/10 bg-[rgba(8,20,45,0.97)]'">
                        @foreach([
                            ['Parish Events',   '/events',  'Liturgical & community events'],
                            ['Photo Gallery',   '/gallery', 'Memories & celebrations'],
                            ['About Our Parish','/about',   'History & our mission'],
                        ] as [$label,$url,$sub])
                        <a href="{{ $url }}" role="menuitem" tabindex="-1"
                           @keydown.arrow-down.prevent="focusNext($event)"
                           @keydown.arrow-up.prevent="focusPrev($event)"
                           @keydown.escape.prevent="closeAndReturn()"
                           class="flex items-start gap-3 px-4 py-3 rounded-xl
                                  transition-colors duration-150 group/item outline-none"
                           :class="scrolled ? 'hover:bg-gray-50 focus:bg-gray-50' : 'hover:bg-white/8 focus:bg-white/8'">
                            <span class="w-1 h-1 rounded-full mt-2 shrink-0 transition-colors"
                                  :class="scrolled ? 'bg-[#F5C518]' : 'bg-[#F5C518]/40 group-hover/item:bg-[#F5C518]'"></span>
                            <div>
                                <div class="text-[11px] font-bold uppercase tracking-wider transition-colors duration-150"
                                     :class="scrolled ? 'text-[#08142D] group-hover/item:text-black' : 'text-white/90 group-hover/item:text-white'">{{ $label }}</div>
                                <div class="text-[10px] mt-0.5 transition-colors duration-150"
                                     :class="scrolled ? 'text-[#08142D]/60' : 'text-white/80'">{{ $sub }}</div>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <a href="/track"
               class="h-10 px-4 rounded-full flex items-center text-[11px] font-semibold uppercase tracking-widest
                      transition-all duration-150"
               :class="scrolled ? 'text-[#08142D]/80 hover:text-[#08142D] hover:bg-[#08142D]/5' : 'text-white/80 hover:text-white hover:bg-white/10'">
                Track
            </a>

            <div class="w-px h-5 mx-1 transition-colors duration-300"
                 :class="scrolled ? 'bg-[#08142D]/15' : 'bg-white/15'"></div>

            <a href="/donate"
               class="relative overflow-hidden h-10 px-5 rounded-full text-[10px] font-bold
                      uppercase tracking-widest text-[#1A0E00] hover:scale-[1.03]
                      active:scale-95 transition-all duration-150 flex items-center"
               style="background:linear-gradient(135deg,#FFD740 0%,#F5C518 55%,#E0A800 100%);
                      box-shadow:0 2px 16px rgba(245,197,24,0.4);">
                Donate
            </a>
        </div>

        {{-- Hamburger --}}
        <button class="md:hidden w-10 h-10 flex items-center justify-center
                       rounded-full transition-all duration-200 flex-shrink-0"
                :class="scrolled ? 'text-[#08142D] hover:bg-[#08142D]/5' : 'text-white hover:bg-white/10'"
                @click="open = !open"
                :aria-expanded="open"
                aria-label="Toggle menu">
            <svg x-show="!open" width="20" height="20" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round">
                <line x1="3" y1="6"  x2="21" y2="6"/>
                <line x1="3" y1="12" x2="16" y2="12"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
            <svg x-show="open" x-cloak width="20" height="20" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round">
                <path d="M18 6 6 18M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- ══════════════════════════════════════
         MOBILE FULL-SCREEN DRAWER
         Slides down from top, covers full viewport
    ══════════════════════════════════════ --}}
    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-3"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-3"
         class="md:hidden fixed inset-0 z-40 flex flex-col"
         style="top:64px; background:rgba(6,16,38,0.98); backdrop-filter:blur(24px);
                -webkit-backdrop-filter:blur(24px);">

        {{-- Gold top rule --}}
        <div style="height:1px; background:linear-gradient(90deg,transparent,rgba(245,197,24,0.4),transparent);"></div>

        {{-- Scrollable content --}}
        <div class="flex-1 overflow-y-auto px-5 py-6 flex flex-col gap-2">

            {{-- Section: Parish Services --}}
            <p style="font-size:9px; font-weight:700; letter-spacing:0.35em;
                      text-transform:uppercase; color:rgba(245,197,24,0.6);
                      padding:0 4px; margin-bottom:4px;">Parish Services</p>

            @foreach([
                [
                    'label' => 'Mass Schedule',
                    'url'   => '/mass-schedule',
                    'sub'   => 'View all Mass times',
                    'icon'  => '<rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
                ],
                [
                    'label' => 'Submit Intention',
                    'url'   => '/submit-intention',
                    'sub'   => 'Offer a prayer intention',
                    'icon'  => '<path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/>',
                ],
                [
                    'label' => 'Sacramental Inquiry',
                    'url'   => '/inquiry',
                    'sub'   => 'Baptism, wedding & more',
                    'icon'  => '<path d="M8 22h8"/><path d="M12 11v11"/><path d="M5 3h14L18 9a6 6 0 0 1-12 0L5 3z"/><path d="M3 3h18"/>',
                ],
            ] as $item)
            <a href="{{ $item['url'] }}"
               @click="open = false"
               class="flex items-center gap-4 px-4 py-3.5 rounded-2xl
                      transition-all duration-200 group"
               style="border:1px solid rgba(255,255,255,0.06);"
               onmouseover="this.style.background='rgba(245,197,24,0.07)'; this.style.borderColor='rgba(245,197,24,0.25)';"
               onmouseout="this.style.background=''; this.style.borderColor='rgba(255,255,255,0.06)';">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background:rgba(245,197,24,0.08); border:1px solid rgba(245,197,24,0.2);">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                         stroke="rgba(245,197,24,0.8)" stroke-width="1.75"
                         stroke-linecap="round" stroke-linejoin="round">
                        {!! $item['icon'] !!}
                    </svg>
                </div>
                <div class="min-w-0">
                    <div style="font-size:13px; font-weight:700; color:#EBF2FF; line-height:1.2;">
                        {{ $item['label'] }}
                    </div>
                    <div style="font-size:10px; color:rgba(235,242,255,0.38); margin-top:2px;">
                        {{ $item['sub'] }}
                    </div>
                </div>
                <svg class="ml-auto flex-shrink-0" width="14" height="14" viewBox="0 0 24 24"
                     fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6"/>
                </svg>
            </a>
            @endforeach

            {{-- Divider --}}
            <div style="height:1px; margin:8px 0;
                        background:linear-gradient(90deg,transparent,rgba(255,255,255,0.07),transparent);"></div>

            {{-- Section: Community --}}
            <p style="font-size:9px; font-weight:700; letter-spacing:0.35em;
                      text-transform:uppercase; color:rgba(245,197,24,0.6);
                      padding:0 4px; margin-bottom:4px;">Community</p>

            @foreach([
                [
                    'label' => 'Parish Events',
                    'url'   => '/events',
                    'sub'   => 'Liturgical & community events',
                    'icon'  => '<rect width="18" height="18" x="3" y="4" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/><path d="M8 14h.01M12 14h.01M16 14h.01"/>',
                ],
                [
                    'label' => 'Photo Gallery',
                    'url'   => '/gallery',
                    'sub'   => 'Memories & celebrations',
                    'icon'  => '<rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>',
                ],
                [
                    'label' => 'About Our Parish',
                    'url'   => '/about',
                    'sub'   => 'History & our mission',
                    'icon'  => '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/>',
                ],
            ] as $item)
            <a href="{{ $item['url'] }}"
               @click="open = false"
               class="flex items-center gap-4 px-4 py-3.5 rounded-2xl
                      transition-all duration-200 group"
               style="border:1px solid rgba(255,255,255,0.06);"
               onmouseover="this.style.background='rgba(245,197,24,0.07)'; this.style.borderColor='rgba(245,197,24,0.25)';"
               onmouseout="this.style.background=''; this.style.borderColor='rgba(255,255,255,0.06)';">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background:rgba(245,197,24,0.08); border:1px solid rgba(245,197,24,0.2);">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                         stroke="rgba(245,197,24,0.8)" stroke-width="1.75"
                         stroke-linecap="round" stroke-linejoin="round">
                        {!! $item['icon'] !!}
                    </svg>
                </div>
                <div class="min-w-0">
                    <div style="font-size:13px; font-weight:700; color:#EBF2FF; line-height:1.2;">
                        {{ $item['label'] }}
                    </div>
                    <div style="font-size:10px; color:rgba(235,242,255,0.38); margin-top:2px;">
                        {{ $item['sub'] }}
                    </div>
                </div>
                <svg class="ml-auto flex-shrink-0" width="14" height="14" viewBox="0 0 24 24"
                     fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6"/>
                </svg>
            </a>
            @endforeach

            {{-- Divider --}}
            <div style="height:1px; margin:8px 0;
                        background:linear-gradient(90deg,transparent,rgba(255,255,255,0.07),transparent);"></div>

            {{-- Track row --}}
            <a href="/track" @click="open = false"
               class="flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all duration-200"
               style="border:1px solid rgba(255,255,255,0.06);"
               onmouseover="this.style.background='rgba(245,197,24,0.07)'; this.style.borderColor='rgba(245,197,24,0.25)';"
               onmouseout="this.style.background=''; this.style.borderColor='rgba(255,255,255,0.06)';">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background:rgba(245,197,24,0.08); border:1px solid rgba(245,197,24,0.2);">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                         stroke="rgba(245,197,24,0.8)" stroke-width="1.75"
                         stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/>
                        <path d="m21 21-4.35-4.35"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <div style="font-size:13px; font-weight:700; color:#EBF2FF;">Track Submission</div>
                    <div style="font-size:10px; color:rgba(235,242,255,0.38); margin-top:2px;">Check your intention status</div>
                </div>
                <svg class="ml-auto flex-shrink-0" width="14" height="14" viewBox="0 0 24 24"
                     fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6"/>
                </svg>
            </a>
        </div>

        {{-- Sticky donate button at bottom --}}
        <div class="px-5 py-4"
             style="border-top:1px solid rgba(255,255,255,0.06);">
            <a href="/donate" @click="open = false"
               class="w-full flex items-center justify-center gap-2.5 py-4 rounded-2xl
                      font-bold uppercase tracking-widest active:scale-95
                      transition-transform duration-150"
               style="font-size:11px; color:#1A0E00;
                      background:linear-gradient(135deg,#FFD740 0%,#F5C518 55%,#E0A800 100%);
                      box-shadow:0 4px 24px rgba(245,197,24,0.35);">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.04 3 5.5L12 21l7-7Z"/>
                </svg>
                Support Our Parish
            </a>
        </div>
    </div>
</nav>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('nav', () => ({
        open: false,
        scrolled: false,
        init() {
            window.addEventListener('scroll', () => {
                this.scrolled = window.scrollY > 80
            }, { passive: true })
            // Close drawer on resize to desktop
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) this.open = false
            }, { passive: true })
        },
        closeAll() {
            window.dispatchEvent(new CustomEvent('dropdown-close-all'))
        }
    }))

    Alpine.data('dropdown', id => ({
        id, isOpen: false, edgeFlip: false,
        init() {
            window.addEventListener('dropdown-close-all', () => this.close())
        },
        toggle() { this.isOpen ? this.close() : this.open() },
        open() {
            window.dispatchEvent(new CustomEvent('dropdown-close-all'))
            this.isOpen = true
            this.$nextTick(() => this.setEdge())
        },
        close()        { this.isOpen = false },
        openAndFocus() {
            if (!this.isOpen) this.open()
            this.$nextTick(() => this.items()[0]?.focus())
        },
        closeAndReturn() { this.close(); this.$refs.trigger?.focus() },
        items()   { return [...this.$refs.panel.querySelectorAll('[role="menuitem"]')] },
        focusNext(e) {
            const items = this.items(), i = items.indexOf(e.target)
            items[(i + 1) % items.length]?.focus()
        },
        focusPrev(e) {
            const items = this.items(), i = items.indexOf(e.target)
            items[(i - 1 + items.length) % items.length]?.focus()
        },
        setEdge() {
            const r = this.$refs.panel?.getBoundingClientRect()
            this.edgeFlip = r && r.right > window.innerWidth - 16
        }
    }))
})
</script>