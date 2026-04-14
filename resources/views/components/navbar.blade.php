<nav 
    x-data="{ open: false }" 
    class="sticky top-0 z-50 border-b bg-card/90 backdrop-blur-md"
>
    <div class="container flex h-16 items-center justify-between mx-auto px-4">
        <a href="/" class="flex items-center gap-2 group">
            <span class="text-2xl text-accent group-hover:scale-110 transition-transform">✝</span>
            <div class="flex flex-col leading-none">
                <span class="font-heading text-lg font-bold text-primary group-hover:text-accent transition-colors">Sto. Rosario</span>
                <span class="text-[10px] text-muted-foreground tracking-[0.2em] font-black uppercase">Parish</span>
            </div>
        </a>

        <!-- Desktop Navigation -->
        <div class="hidden md:flex items-center gap-2">
            <!-- Worship Dropdown -->
            <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                <button class="px-3 py-1.5 rounded-md text-xs font-black uppercase tracking-widest transition-all inline-flex items-center gap-1 {{ request()->is('mass-schedule*', 'submit-intention*', 'inquiry*', 'bulletins*') ? 'text-accent border-b-2 border-accent' : 'text-primary/70 hover:text-primary' }}">
                    Worship
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                     class="absolute top-full left-0 w-56 pt-2"
                     x-cloak>
                    <div class="bg-card border rounded-2xl shadow-2xl p-2 flex flex-col gap-1">
                        <a href="/mass-schedule" class="px-4 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-wider hover:bg-muted transition-colors flex items-center gap-3">
                            <span class="h-1.5 w-1.5 rounded-full bg-accent"></span>
                            Mass Schedule
                        </a>
                        <a href="/submit-intention" class="px-4 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-wider hover:bg-muted transition-colors flex items-center gap-3">
                            <span class="h-1.5 w-1.5 rounded-full bg-accent"></span>
                            Submit Intention
                        </a>
                        <a href="/inquiry" class="px-4 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-wider hover:bg-muted transition-colors flex items-center gap-3">
                            <span class="h-1.5 w-1.5 rounded-full bg-accent"></span>
                            Sacramental Inquiry
                        </a>
                        <a href="/bulletins" class="px-4 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-wider hover:bg-muted transition-colors flex items-center gap-3">
                            <span class="h-1.5 w-1.5 rounded-full bg-accent"></span>
                            Parish Bulletins
                        </a>
                    </div>
                </div>
            </div>

            <!-- Community Dropdown -->
            <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                <button class="px-3 py-1.5 rounded-md text-xs font-black uppercase tracking-widest transition-all inline-flex items-center gap-1 {{ request()->is('events*', 'gallery*', 'about*') ? 'text-accent border-b-2 border-accent' : 'text-primary/70 hover:text-primary' }}">
                    Community
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                </button>
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute top-full left-0 w-56 pt-2"
                     x-cloak>
                    <div class="bg-card border rounded-2xl shadow-2xl p-2 flex flex-col gap-1">
                        <a href="/events" class="px-4 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-wider hover:bg-muted transition-colors flex items-center gap-3">
                            <span class="h-1.5 w-1.5 rounded-full bg-accent"></span>
                            Parish Events
                        </a>
                        <a href="/gallery" class="px-4 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-wider hover:bg-muted transition-colors flex items-center gap-3">
                            <span class="h-1.5 w-1.5 rounded-full bg-accent"></span>
                            Photo Gallery
                        </a>
                        <a href="/about" class="px-4 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-wider hover:bg-muted transition-colors flex items-center gap-3">
                            <span class="h-1.5 w-1.5 rounded-full bg-accent"></span>
                            About Our Parish
                        </a>
                    </div>
                </div>
            </div>

            <a href="/track" 
               class="px-3 py-1.5 rounded-md text-xs font-black uppercase tracking-widest transition-all {{ request()->is('track*') ? 'text-accent border-b-2 border-accent' : 'text-primary/70 hover:text-primary hover:bg-muted' }}">
                Track Intention
            </a>

            <div class="h-4 w-px bg-border mx-2"></div>

            <a href="/donate" class="px-4 py-1.5 rounded-full bg-accent text-accent-foreground text-[10px] font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-lg shadow-accent/20">
                Donate
            </a>
        </div>

        <!-- Mobile Menu Toggle -->
        <button class="md:hidden p-2 rounded-lg hover:bg-muted" @click="open = !open">
            <template x-if="!open">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x2="21" y1="6" y2="6"/><line x1="3" x2="21" y1="12" y2="12"/><line x1="3" x2="21" y1="18" y2="18"/></svg>
            </template>
            <template x-if="open">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </template>
        </button>
    </div>

    <!-- Mobile Menu Overlay -->
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4"
        class="md:hidden border-t bg-card animate-in slide-in-from-top-2"
        x-cloak
    >
        <div class="container py-8 flex flex-col gap-6 mx-auto px-6 overflow-y-auto max-h-[80vh]">
            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground/60 mb-4">Worship</p>
                <div class="grid grid-cols-1 gap-2">
                    <a href="/mass-schedule" class="px-4 py-2 rounded-xl bg-accent/5 border border-accent/10 font-bold text-sm">Mass Schedule</a>
                    <a href="/submit-intention" class="px-4 py-2 rounded-xl bg-accent/5 border border-accent/10 font-bold text-sm">Submit Intention</a>
                    <a href="/inquiry" class="px-4 py-2 rounded-xl bg-accent/5 border border-accent/10 font-bold text-sm">Sacramental Inquiry</a>
                    <a href="/bulletins" class="px-4 py-2 rounded-xl bg-accent/5 border border-accent/10 font-bold text-sm">Parish Bulletins</a>
                </div>
            </div>

            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground/60 mb-4">Community</p>
                <div class="grid grid-cols-1 gap-2">
                    <a href="/events" class="px-4 py-2 rounded-xl bg-muted/50 font-bold text-sm">Parish Events</a>
                    <a href="/gallery" class="px-4 py-2 rounded-xl bg-muted/50 font-bold text-sm">Photo Gallery</a>
                    <a href="/about" class="px-4 py-2 rounded-xl bg-muted/50 font-bold text-sm">About Our Parish</a>
                </div>
            </div>

            <div class="pt-4 border-t flex flex-col gap-4">
                <a href="/track" class="text-center px-6 py-3 rounded-xl bg-primary text-primary-foreground font-bold shadow-lg">Track Intention</a>
                <a href="/donate" class="text-center px-6 py-3 rounded-xl bg-accent text-accent-foreground font-black uppercase tracking-widest">Donate Now</a>
            </div>
        </div>
    </div>
</nav>
