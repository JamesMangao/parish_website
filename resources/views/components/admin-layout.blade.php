<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - Sto. Rosario Parish</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-muted/30 font-sans antialiased text-foreground">

    {{-- Session flash → Alpine store --}}
    <div x-data x-init="
        @if(session('success')) $store.toast.trigger('{{ session('success') }}', 'success'); @endif
        @if(session('error')) $store.toast.trigger('{{ session('error') }}', 'error'); @endif
        $store.ui._notifUrl = '{{ route('admin.notifications.count') }}';
        $store.ui.init();
    "></div>

    <div class="min-h-screen flex">
        {{-- Mobile overlay --}}
        <div
            x-data
            x-show="$store.ui.sidebarOpen && $store.ui.isMobile"
            @click="$store.ui.sidebarOpen = false"
            class="fixed inset-0 bg-black/50 z-40 lg:hidden"
            x-transition.opacity
            x-cloak
        ></div>

        {{-- Sidebar --}}
        <aside
            x-data
            :class="$store.ui.isMobile && $store.ui.sidebarOpen ? 'w-64' : (!$store.ui.isMobile ? ($store.ui.sidebarOpen ? 'w-64' : 'w-20') : 'w-0 overflow-hidden')"
            class="bg-primary text-primary-foreground transition-all duration-300 flex flex-col fixed inset-y-0 z-50 shadow-xl"
            :style="$store.ui.isMobile && !$store.ui.sidebarOpen ? 'width:0;overflow:hidden' : ''"
        >
            <div class="h-16 flex items-center px-6 border-b border-primary-foreground/10 shrink-0">
                <span x-show="$store.ui.sidebarOpen" class="font-heading font-bold text-xl tracking-tight transition-all">Sto Rosario Parish Admin</span>
                <span x-show="!$store.ui.sidebarOpen" class="font-heading font-bold text-xl mx-auto">SRP</span>
            </div>

            <nav class="flex-1 py-6 space-y-1 overflow-y-auto">
                @php $role = Auth::user()->role ?? 'super_admin'; @endphp

                <x-admin-nav-link href="{{ route('admin.dashboard') }}" icon="layout-dashboard" label="Dashboard"
                    :active="request()->is('admin-portal/dashboard')" />

                @if($role === 'super_admin' || $role === 'staff')
                    <x-admin-nav-link href="{{ route('admin.intentions') }}" icon="heart" label="Mass Intentions"
                        :active="request()->is('admin-portal/intentions*')" />
                @endif

                @if($role === 'super_admin' || $role === 'soccom' || $role === 'staff')
                    <x-admin-nav-link href="{{ route('admin.inquiries.index') }}" icon="message-square-quote" label="Inquiries"
                        :active="request()->is('admin-portal/inquiries*')" />
                @endif

                @if($role === 'super_admin' || $role === 'soccom')
                    <x-admin-nav-link href="{{ route('admin.schedules.index') }}" icon="calendar" label="Schedules"
                        :active="request()->is('admin-portal/schedules*')" />

                    <x-admin-nav-link href="{{ route('admin.announcements.index') }}" icon="megaphone" label="Announcements"
                        :active="request()->is('admin-portal/announcements*')" />
                    <x-admin-nav-link href="{{ route('admin.events.index') }}" icon="sparkles" label="Events"
                        :active="request()->is('admin-portal/events*')" />
                    <x-admin-nav-link href="{{ route('admin.gallery.index') }}" icon="image" label="Gallery"
                        :active="request()->is('admin-portal/gallery*')" />
                    <x-admin-nav-link href="{{ route('admin.highlights.index') }}" icon="clapperboard" label="Video Highlights"
                        :active="request()->is('admin-portal/highlights*')" />

                    {{-- Live Chat with badge --}}
                    <div class="relative">
                        <x-admin-nav-link href="{{ route('admin.chats.index') }}" icon="messages-square" label="Live Chat"
                            :active="request()->is('admin-portal/chats*')" />
                        <template x-if="$store.ui.notifCounts.chats > 0">
                            <span class="absolute top-1 right-2 h-5 w-5 bg-red-600 text-white text-[9px] font-black rounded-full flex items-center justify-center border-2 border-primary animate-pulse" x-text="$store.ui.notifCounts.chats"></span>
                        </template>
                    </div>
                @endif

                @if($role === 'super_admin')
                    <div class="pt-4 pb-2 px-6">
                        <p class="text-[10px] font-black uppercase tracking-widest text-primary-foreground/40">System</p>
                    </div>

                    <x-admin-nav-link href="{{ route('admin.users') }}" icon="users" label="Users"
                        :active="request()->is('admin-portal/users*')" />
                    <x-admin-nav-link href="{{ route('admin.logs') }}" icon="scroll-text" label="Logs"
                        :active="request()->is('admin-portal/logs*')" />
                    <x-admin-nav-link href="{{ route('admin.settings') }}" icon="settings" label="Settings"
                        :active="request()->is('admin-portal/settings*')" />
                @endif
            </nav>

            <div class="p-4 border-t border-primary-foreground/10">
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <button type="button" @click="$store.confirm.open({
                        title: 'Confirm Logout',
                        message: 'Are you sure you want to end your session?',
                        onConfirm: () => document.getElementById('logout-form').submit(),
                        confirmText: 'Sign Out',
                        type: 'primary'
                    })"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-md hover:bg-primary-foreground/10 text-sm font-medium transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <polyline points="16 17 21 12 16 7" />
                            <line x1="21" x2="9" y1="12" y2="12" />
                        </svg>
                        <span x-show="$store.ui.sidebarOpen">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content --}}
        <main
            x-data
            :class="($store.ui.isMobile ? 'ml-0' : ($store.ui.sidebarOpen ? 'ml-64' : 'ml-20'))"
            class="flex-1 flex flex-col transition-all duration-300 min-h-screen"
        >
            <header class="h-16 bg-white border-b flex items-center justify-between px-8 sticky top-0 z-40">
                <button @click="$store.ui.sidebarOpen = !$store.ui.sidebarOpen"
                    class="p-2 -ml-2 rounded-md hover:bg-muted text-muted-foreground transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="4" x2="20" y1="12" y2="12" />
                        <line x1="4" x2="20" y1="6" y2="6" />
                        <line x1="4" x2="20" y1="18" y2="18" />
                    </svg>
                </button>

                <div class="flex items-center gap-6">
                    {{-- Notification Bell --}}
                    <div x-data="{
                        open: false,
                        get counts() { return $store.ui.notifCounts },
                        get total() { return this.counts.intentions + this.counts.inquiries + this.counts.chats }
                    }" class="relative">
                        <button @click="open = !open" class="p-2 rounded-lg hover:bg-muted text-muted-foreground transition-all relative group">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:scale-110 transition-transform"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                            <template x-if="total > 0">
                                <span class="absolute -top-1 -right-1 h-5 w-5 bg-red-600 text-white text-[10px] font-black rounded-full flex items-center justify-center border-2 border-white animate-pulse" x-text="total"></span>
                            </template>
                        </button>

                        <div x-show="open" @click.away="open = false" x-cloak
                             class="absolute right-0 mt-3 w-72 bg-white border border-border shadow-2xl rounded-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200 z-50">
                            <div class="p-4 border-b bg-muted/30">
                                <h4 class="text-xs font-black uppercase tracking-widest text-primary italic">Notifications</h4>
                            </div>
                            <div class="p-2">
                                <template x-if="total === 0">
                                    <div class="p-4 text-center text-xs text-muted-foreground italic">No new notifications</div>
                                </template>
                                <template x-if="counts.intentions > 0">
                                    <a href="{{ route('admin.intentions', ['status' => 'pending']) }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-muted/50 transition-colors">
                                        <div class="h-8 w-8 rounded-full bg-accent/10 text-accent flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.505 4.04 3 5.5L12 21l7-7Z"/></svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs font-bold text-primary"><span x-text="counts.intentions"></span> New Intentions</p>
                                            <p class="text-[10px] text-muted-foreground">Pending review</p>
                                        </div>
                                    </a>
                                </template>
                                <template x-if="counts.inquiries > 0">
                                    <a href="{{ route('admin.inquiries.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-muted/50 transition-colors">
                                        <div class="h-8 w-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs font-bold text-primary"><span x-text="counts.inquiries"></span> New Inquiries</p>
                                            <p class="text-[10px] text-muted-foreground">Recent submissions</p>
                                        </div>
                                    </a>
                                </template>
                                <template x-if="counts.chats > 0">
                                    <a href="{{ route('admin.chats.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-muted/50 transition-colors">
                                        <div class="h-8 w-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m5 8 6 6 6-6"/><path d="m5 12 6 6 6-6"/></svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs font-bold text-primary"><span x-text="counts.chats"></span> Chats Waiting</p>
                                            <p class="text-[10px] text-muted-foreground">Awaiting reply</p>
                                        </div>
                                    </a>
                                </template>
                            </div>
                            <div class="p-2 border-t bg-muted/10">
                                <a href="{{ route('admin.dashboard') }}" class="block w-full text-center py-2 text-[10px] font-black uppercase tracking-widest text-muted-foreground hover:text-primary transition-colors">View All Activities</a>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 border-l pl-6">
                        <div class="text-right">
                            <p class="text-sm font-bold text-primary">{{ Auth::user()->name ?? 'Admin User' }}</p>
                            <p class="text-xs text-muted-foreground">{{ Auth::user()->email ?? 'admin@storosario.ph' }}</p>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-accent flex items-center justify-center text-accent-foreground font-black shadow-inner border-2 border-white">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-4 sm:p-6 lg:p-8 flex-1 page-animate">
                {{ $slot }}
            </div>
        </main>
    </div>

    {{-- Global Toast + Confirm (via components) --}}
    <x-admin-toast />
    <x-admin-confirm />
</body>
</html>
