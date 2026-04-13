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
        <div class="hidden md:flex items-center gap-1">
            @php
                $navLinks = [
                    ['url' => '/', 'label' => 'Home', 'active' => request()->is('/')],
                    ['url' => '/mass-schedule', 'label' => 'Mass', 'active' => request()->is('mass-schedule*')],
                    ['url' => '/submit-intention', 'label' => 'Intentions', 'active' => request()->is('submit-intention*')],
                    ['url' => '/inquiry', 'label' => 'Inquiry', 'active' => request()->is('inquiry*')],
                    ['url' => '/events', 'label' => 'Events', 'active' => request()->is('events*')],
                    ['url' => '/gallery', 'label' => 'Gallery', 'active' => request()->is('gallery*')],
                    ['url' => '/about', 'label' => 'About', 'active' => request()->is('about*')],
                ];
            @endphp

            @foreach($navLinks as $link)
                <a href="{{ $link['url'] }}" 
                   class="px-3 py-1.5 rounded-md text-sm font-bold uppercase tracking-wider transition-all {{ $link['active'] ? 'text-accent border-b-2 border-accent' : 'text-primary/70 hover:text-primary hover:bg-muted' }}">
                    {{ $link['label'] }}
                </a>
            @endforeach

            <div class="h-4 w-px bg-border mx-2"></div>

            <a href="/donate" class="px-4 py-1.5 rounded-full bg-accent text-accent-foreground text-xs font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-sm">
                Donate
            </a>


            <a href="/admin/login" class="ml-2 p-2 rounded-lg text-muted-foreground hover:bg-muted hover:text-primary transition-all" title="Admin Portal">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shield-lock"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><circle cx="12" cy="13" r="1.5"/><path d="M12 14.5v1.5"/></svg>
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
        <div class="container py-6 flex flex-col gap-1 mx-auto px-4">
            @foreach($navLinks as $link)
                <a href="{{ $link['url'] }}" 
                   class="px-4 py-3 rounded-xl text-md font-bold transition-all {{ $link['active'] ? 'bg-primary text-primary-foreground shadow-lg shadow-primary/20' : 'text-primary/70 hover:bg-muted' }}"
                   @click="open = false">
                    {{ $link['label'] }}
                </a>
            @endforeach
            
            <div class="mt-4 pt-4 border-t flex items-center justify-end">
                <a href="/donate" class="px-6 py-2 rounded-full bg-accent text-accent-foreground text-xs font-black uppercase tracking-widest active:scale-95 transition-all">
                    Donate
                </a>
            </div>

            <a href="/admin/login" class="mt-4 flex items-center justify-center p-4 border rounded-xl text-primary font-bold hover:bg-muted transition-all">
                Admin Access
            </a>
        </div>
    </div>
</nav>
