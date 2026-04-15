<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin - Sto. Rosario Parish</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        @keyframes pageFadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .page-animate {
            animation: pageFadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</head>

<body class="bg-muted/30 font-sans antialiased text-foreground" 
    x-data="{ 
        sidebarOpen: true,
        notification: { show: false, message: '', type: 'success' },
        confirmModal: { show: false, title: '', message: '', onConfirm: null, confirmText: 'Confirm', type: 'danger' },
        showNotification(msg, type = 'success') {
            this.notification.message = msg;
            this.notification.type = type;
            this.notification.show = true;
            setTimeout(() => this.notification.show = false, 5000);
        },
        triggerConfirm(title, message, action, confirmText = 'Delete Permanently', type = 'danger') {
            this.confirmModal.title = title;
            this.confirmModal.message = message;
            this.confirmModal.onConfirm = action;
            this.confirmModal.confirmText = confirmText;
            this.confirmModal.type = type;
            this.confirmModal.show = true;
        }
    }">
    
    <!-- Global Notification System -->
    @if(session('success'))
        <div x-init="showNotification('{{ session('success') }}', 'success')"></div>
    @endif
    @if(session('error'))
        <div x-init="showNotification('{{ session('error') }}', 'error')"></div>
    @endif

    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'"
            class="bg-primary text-primary-foreground transition-all duration-300 flex flex-col fixed inset-y-0 z-50 shadow-xl">
            <div class="h-16 flex items-center px-6 border-b border-primary-foreground/10 shrink-0">
                <span x-show="sidebarOpen" class="font-heading font-bold text-xl tracking-tight transition-all">Sto Rosario Parish Admin</span>
                <span x-show="!sidebarOpen" class="font-heading font-bold text-xl mx-auto">SRP</span>
            </div>

            <nav class="flex-1 py-6 space-y-1 overflow-y-auto">
                @php $role = Auth::user()->role ?? 'super_admin'; @endphp

                <x-admin-nav-link href="/admin/dashboard" icon="layout-dashboard" label="Dashboard"
                    :active="request()->is('admin/dashboard')" />

                @if($role === 'super_admin' || $role === 'staff')
                    <x-admin-nav-link href="/admin/intentions" icon="heart" label="Mass Intentions"
                        :active="request()->is('admin/intentions*')" />
                @endif

                @if($role === 'super_admin' || $role === 'soccom' || $role === 'staff')
                    <x-admin-nav-link href="/admin/inquiries" icon="message-square-quote" label="Inquiries"
                        :active="request()->is('admin/inquiries*')" />
                @endif

                @if($role === 'super_admin' || $role === 'soccom')
                    <x-admin-nav-link href="/admin/schedules" icon="calendar" label="Schedules"
                        :active="request()->is('admin/schedules*')" />
                    <x-admin-nav-link href="/admin/bulletins" icon="book-open" label="Weekly Bulletins"
                        :active="request()->is('admin/bulletins*')" />
                    <x-admin-nav-link href="/admin/announcements" icon="megaphone" label="Announcements"
                        :active="request()->is('admin/announcements*')" />
                    <x-admin-nav-link href="/admin/events" icon="sparkles" label="Events"
                        :active="request()->is('admin/events*')" />
                    <x-admin-nav-link href="/admin/gallery" icon="image" label="Gallery"
                        :active="request()->is('admin/gallery*')" />
                    <x-admin-nav-link href="/admin/chats" icon="messages-square" label="Live Chat"
                        :active="request()->is('admin/chats*')" />
                @endif

                @if($role === 'super_admin')
                    <div class="pt-4 pb-2 px-6">
                        <p class="text-[10px] font-black uppercase tracking-widest text-primary-foreground/40">System</p>
                    </div>

                    <x-admin-nav-link href="/admin/users" icon="users" label="Users"
                        :active="request()->is('admin/users*')" />
                    <x-admin-nav-link href="/admin/logs" icon="scroll-text" label="Logs"
                        :active="request()->is('admin/logs*')" />
                    <x-admin-nav-link href="/admin/settings" icon="settings" label="Settings"
                        :active="request()->is('admin/settings*')" />
                @endif
            </nav>

            <div class="p-4 border-t border-primary-foreground/10">
                <form method="POST" action="/logout" id="logout-form">
                    @csrf
                    <button type="button" @click="triggerConfirm('Confirm Logout', 'Are you sure you want to end your session?', () => document.getElementById('logout-form').submit(), 'Sign Out', 'primary')"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-md hover:bg-primary-foreground/10 text-sm font-medium transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-log-out">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <polyline points="16 17 21 12 16 7" />
                            <line x1="21" x2="9" y1="12" y2="12" />
                        </svg>
                        <span x-show="sidebarOpen">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main :class="sidebarOpen ? 'ml-64' : 'ml-20'"
            class="flex-1 flex flex-col transition-all duration-300 min-h-screen">
            <header class="h-16 bg-white border-b flex items-center justify-between px-8 sticky top-0 z-40">
                <button @click="sidebarOpen = !sidebarOpen"
                    class="p-2 -ml-2 rounded-md hover:bg-muted text-muted-foreground transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-menu">
                        <line x1="4" x2="20" y1="12" y2="12" />
                        <line x1="4" x2="20" y1="6" y2="6" />
                        <line x1="4" x2="20" y1="18" y2="18" />
                    </svg>
                </button>

                <div class="flex items-center gap-6">
                    <!-- Notification Bell -->
                    <div x-data="{ 
                        open: false,
                        counts: { intentions: 0, inquiries: 0, chats: 0 },
                        get total() { return this.counts.intentions + this.counts.inquiries + this.counts.chats },
                        async fetchCounts() {
                            try {
                                const response = await fetch('{{ route('admin.notifications.count') }}');
                                this.counts = await response.json();
                            } catch (e) {}
                        }
                    }" x-init="fetchCounts(); setInterval(() => fetchCounts(), 30000)" class="relative">
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
                                    <a href="/admin/intentions?status=pending" class="flex items-center gap-3 p-3 rounded-xl hover:bg-muted/50 transition-colors">
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
                                    <a href="/admin/inquiries" class="flex items-center gap-3 p-3 rounded-xl hover:bg-muted/50 transition-colors">
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
                                    <a href="/admin/chats" class="flex items-center gap-3 p-3 rounded-xl hover:bg-muted/50 transition-colors">
                                        <div class="h-8 w-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m5 8 6 6 6-6"/><path d="m5 12 6 6 6-6"/></svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-xs font-bold text-primary"><span x-text="counts.chats"></span> Active Chats</p>
                                            <p class="text-[10px] text-muted-foreground">Waiting for response</p>
                                        </div>
                                    </a>
                                </template>
                            </div>
                            <div class="p-2 border-t bg-muted/10">
                                <a href="/admin/dashboard" class="block w-full text-center py-2 text-[10px] font-black uppercase tracking-widest text-muted-foreground hover:text-primary transition-colors">View All Activities</a>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 border-l pl-6">
                        <div class="text-right">
                            <p class="text-sm font-bold text-primary">{{ Auth::user()->name ?? 'Admin User' }}</p>
                            <p class="text-xs text-muted-foreground">{{ Auth::user()->email ?? 'admin@storosario.ph' }}</p>
                        </div>
                        <div
                            class="h-10 w-10 rounded-full bg-accent flex items-center justify-center text-accent-foreground font-black shadow-inner border-2 border-white">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-8 flex-1 page-animate">
                {{ $slot }}
            </div>
        </main>
    </div>

    <!-- Global Professional Notification Toast -->
    <template x-teleport="body">
        <div 
            x-show="notification.show" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-8 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-8 scale-95"
            class="fixed bottom-6 right-6 z-[9999] max-w-sm w-full bg-white border-l-4 shadow-2xl rounded-xl p-5 flex items-start gap-4 animate-in slide-in-from-right-10"
            :class="notification.type === 'success' ? 'border-green-500' : 'border-red-500'"
            x-cloak
        >
            <div :class="notification.type === 'success' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'" 
                 class="h-10 w-10 flex-shrink-0 flex items-center justify-center rounded-full shadow-sm">
                <template x-if="notification.type === 'success'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                </template>
                <template x-if="notification.type === 'error'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                </template>
            </div>
            <div class="flex-1 pt-0.5">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground mb-1" x-text="notification.type === 'success' ? 'Successful Action' : 'System Notice'"></p>
                <p class="text-sm font-bold text-primary leading-tight" x-text="notification.message"></p>
            </div>
            <button @click="notification.show = false" class="p-1 hover:bg-muted rounded-md transition-colors text-muted-foreground mt-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
    </template>

    <!-- Global Professional Confirmation Modal -->
    <template x-teleport="body">
        <div 
            x-show="confirmModal.show" 
            class="fixed inset-0 z-[9998] flex items-center justify-center p-4 bg-background/60 backdrop-blur-sm"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            <div 
                @click.away="confirmModal.show = false"
                class="bg-white max-w-md w-full rounded-2xl shadow-2xl border p-6 animate-in zoom-in-95 duration-200"
            >
                <div class="flex items-start gap-4 mb-6">
                    <div :class="confirmModal.type === 'danger' ? 'bg-red-100 text-red-600' : 'bg-primary/10 text-primary'" class="h-12 w-12 flex-shrink-0 flex items-center justify-center rounded-full">
                        <template x-if="confirmModal.type === 'danger'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        </template>
                        <template x-if="confirmModal.type !== 'danger'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                        </template>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-primary italic font-heading" x-text="confirmModal.title"></h3>
                        <p class="text-sm text-muted-foreground mt-2 leading-relaxed" x-text="confirmModal.message"></p>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <button @click="confirmModal.show = false" class="px-5 py-2 rounded-md text-sm font-bold text-muted-foreground hover:bg-muted transition-colors">
                        Cancel
                    </button>
                    <button @click="confirmModal.show = false; if(confirmModal.onConfirm) confirmModal.onConfirm()" 
                            :class="confirmModal.type === 'danger' ? 'bg-red-600 hover:bg-red-700' : 'bg-primary hover:bg-primary/90'"
                            class="px-5 py-2 text-white rounded-md text-sm font-bold shadow-md transition-all">
                        <span x-text="confirmModal.confirmText"></span>
                    </button>
                </div>
            </div>
        </div>
    </template>
</body>

</html>