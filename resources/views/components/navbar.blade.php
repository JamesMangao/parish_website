<nav x-data="{ open: false }" class="sticky top-0 z-50 border-b border-amber-200/60 bg-amber-50/80 backdrop-blur-md" style="border-bottom: 1px solid #e8dfc4;">
    {{-- gold accent line --}}
    <div class="absolute bottom-0 left-0 right-0 h-px" style="background: linear-gradient(90deg, transparent, #c9a84c, transparent);"></div>

    <div class="container flex h-16 items-center justify-between mx-auto px-4">

        {{-- Brand --}}
        <a href="/" class="flex items-center gap-2.5 group">
            <span class="text-2xl transition-transform group-hover:scale-110" style="color: #c9a84c;">✝</span>
            <div class="flex flex-col leading-none">
                <span class="font-bold text-[15px] tracking-wide text-stone-800 transition-colors group-hover:text-amber-700" style="font-family: 'Cinzel', 'Georgia', serif;">Sto. Rosario</span>
                <span class="text-[8.5px] tracking-[0.28em] font-black uppercase text-stone-500 mt-0.5">Parish</span>
            </div>
        </a>

        {{-- Desktop Nav --}}
        <div class="hidden md:flex items-center gap-1">

            {{-- Worship --}}
            <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                <button class="px-3 py-1.5 rounded-md text-[10.5px] font-bold uppercase tracking-widest inline-flex items-center gap-1 transition-all
                    {{ request()->is('mass-schedule*','submit-intention*','inquiry*')
                        ? 'text-amber-700'
                        : 'text-stone-500 hover:text-stone-800 hover:bg-amber-100/60' }}">
                    Worship
                    <svg :class="open ? 'rotate-180' : ''" class="transition-transform duration-200 opacity-60" xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                     class="absolute top-full left-0 pt-3 w-52"
                     x-cloak>
                    <div class="rounded-2xl border p-1.5 flex flex-col gap-0.5 shadow-lg" style="background:#fff9ef; border-color:#e8dfc4; box-shadow: 0 8px 32px rgba(100,80,20,0.12);">
                        @foreach([['Mass Schedule','/mass-schedule'],['Submit Intention','/submit-intention'],['Sacramental Inquiry','/inquiry']] as [$label,$url])
                        <a href="{{ $url }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-[10.5px] font-bold uppercase tracking-wider text-stone-500 hover:text-stone-800 hover:bg-amber-50 transition-colors">
                            <span class="w-1.5 h-1.5 rounded-full flex-shrink-0" style="background:#c9a84c;"></span>
                            {{ $label }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Community --}}
            <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                <button class="px-3 py-1.5 rounded-md text-[10.5px] font-bold uppercase tracking-widest inline-flex items-center gap-1 transition-all
                    {{ request()->is('events*','gallery*','about*')
                        ? 'text-amber-700'
                        : 'text-stone-500 hover:text-stone-800 hover:bg-amber-100/60' }}">
                    Community
                    <svg :class="open ? 'rotate-180' : ''" class="transition-transform duration-200 opacity-60" xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                     class="absolute top-full left-0 pt-3 w-52"
                     x-cloak>
                    <div class="rounded-2xl border p-1.5 flex flex-col gap-0.5 shadow-lg" style="background:#fff9ef; border-color:#e8dfc4; box-shadow: 0 8px 32px rgba(100,80,20,0.12);">
                        @foreach([['Parish Events','/events'],['Photo Gallery','/gallery'],['About Our Parish','/about']] as [$label,$url])
                        <a href="{{ $url }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-[10.5px] font-bold uppercase tracking-wider text-stone-500 hover:text-stone-800 hover:bg-amber-50 transition-colors">
                            <span class="w-1.5 h-1.5 rounded-full flex-shrink-0" style="background:#c9a84c;"></span>
                            {{ $label }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <a href="/track" class="px-3 py-1.5 rounded-md text-[10.5px] font-bold uppercase tracking-widest transition-all
                {{ request()->is('track*') ? 'text-amber-700' : 'text-stone-500 hover:text-stone-800 hover:bg-amber-100/60' }}">
                Track Intention
            </a>

            <div class="h-5 w-px mx-2" style="background:#e8dfc4;"></div>

            <a href="/donate" class="px-5 py-2 rounded-full text-[9.5px] font-black uppercase tracking-widest transition-all hover:scale-105 active:scale-95" style="background:#c9a84c; color:#1a1510;">
                Donate
            </a>
        </div>

        {{-- Mobile Toggle --}}
        <button class="md:hidden p-2 rounded-lg hover:bg-amber-100/60 transition-colors" @click="open = !open">
            <template x-if="!open">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x2="21" y1="6" y2="6"/><line x1="3" x2="21" y1="12" y2="12"/><line x1="3" x2="21" y1="18" y2="18"/></svg>
            </template>
            <template x-if="open">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </template>
        </button>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-3"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-3"
         class="md:hidden border-t overflow-y-auto max-h-[80vh]"
         style="background:#faf7f0; border-color:#e8dfc4;"
         x-cloak>
        <div class="container py-8 flex flex-col gap-6 mx-auto px-6">
            <div>
                <p class="text-[9px] font-black uppercase tracking-[0.25em] mb-4" style="color:#a0896a;">Worship</p>
                <div class="flex flex-col gap-2">
                    @foreach([['Mass Schedule','/mass-schedule'],['Submit Intention','/submit-intention'],['Sacramental Inquiry','/inquiry']] as [$label,$url])
                    <a href="{{ $url }}" class="px-4 py-2.5 rounded-xl text-sm font-bold text-stone-700 transition-colors hover:bg-amber-100/60" style="background: rgba(201,168,76,0.08); border: 1px solid rgba(201,168,76,0.2);">{{ $label }}</a>
                    @endforeach
                </div>
            </div>
            <div>
                <p class="text-[9px] font-black uppercase tracking-[0.25em] mb-4" style="color:#a0896a;">Community</p>
                <div class="flex flex-col gap-2">
                    @foreach([['Parish Events','/events'],['Photo Gallery','/gallery'],['About Our Parish','/about']] as [$label,$url])
                    <a href="{{ $url }}" class="px-4 py-2.5 rounded-xl text-sm font-bold text-stone-700 hover:bg-amber-100/60 transition-colors" style="background:rgba(0,0,0,0.03); border: 1px solid #e8dfc4;">{{ $label }}</a>
                    @endforeach
                </div>
            </div>
            <div class="pt-4 border-t flex flex-col gap-3" style="border-color:#e8dfc4;">
                <a href="/track" class="text-center px-6 py-3 rounded-xl font-bold text-sm text-stone-800 border" style="border-color:#c9a84c; background: rgba(201,168,76,0.08);">Track Intention</a>
                <a href="/donate" class="text-center px-6 py-3 rounded-full font-black text-sm uppercase tracking-widest" style="background:#c9a84c; color:#1a1510;">Donate Now</a>
            </div>
        </div>
    </div>
</nav>
