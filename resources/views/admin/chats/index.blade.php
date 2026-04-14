<x-admin-layout>
    <div class="px-8 py-10" x-data="{ status: '{{ $status }}' }">
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div>
                <h1 class="font-heading text-4xl font-black text-primary italic">Live Chat Management</h1>
                <p class="text-sm text-muted-foreground mt-1">Manage and respond to real-time conversations with parishioners.</p>
            </div>
            
            <div class="flex items-center gap-4">
                <form action="{{ route('admin.chats.index') }}" method="GET" class="relative group hidden md:block">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Search IP..." 
                        value="{{ request('search') }}"
                        class="pl-10 pr-4 py-2 rounded-xl border bg-muted/20 text-xs focus:ring-accent focus:border-accent italic font-medium w-48 transition-all group-hover:w-64"
                    >
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-muted-foreground">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    </div>
                </form>

                <div class="flex items-center gap-2 p-1 bg-muted rounded-xl border">
                    @foreach(['handover' => 'Waiting', 'active' => 'Active', 'resolved' => 'Resolved'] as $val => $label)
                        <a href="{{ route('admin.chats.index', ['status' => $val, 'search' => request('search')]) }}" 
                           class="px-4 py-2 rounded-lg text-xs font-black uppercase tracking-widest transition-all {{ $status === $val ? 'bg-white text-primary shadow-sm' : 'text-muted-foreground hover:text-primary' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        </header>

        <div class="bg-card rounded-[2rem] border shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-muted/30 border-b">
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground">User / IP</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground">Status</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground">Messages</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground">Requested At</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground">Admin</th>
                            <th class="px-6 py-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($sessions as $s)
                            <tr class="hover:bg-muted/10 transition-colors group">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-black text-xs">
                                            {{ substr($s->user_ip, -2) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-primary text-sm">{{ $s->user_ip }}</p>
                                            <p class="text-[10px] text-muted-foreground uppercase font-black tracking-widest">Guest User</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border 
                                        {{ $s->status === 'handover' ? 'bg-red-50 text-red-600 border-red-100 animate-pulse' : '' }}
                                        {{ $s->status === 'active' ? 'bg-blue-50 text-blue-600 border-blue-100' : '' }}
                                        {{ $s->status === 'resolved' ? 'bg-green-50 text-green-600 border-green-100' : '' }}">
                                        {{ $s->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-primary">{{ $s->messages_count }}</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-muted-foreground/40"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-xs text-muted-foreground font-medium">
                                    {{ $s->live_agent_requested_at ? $s->live_agent_requested_at->diffForHumans() : 'N/A' }}
                                </td>
                                <td class="px-6 py-5">
                                    @if($s->admin)
                                        <div class="flex items-center gap-2">
                                            <div class="h-6 w-6 rounded-full bg-accent text-[10px] flex items-center justify-center font-bold text-accent-foreground">{{ substr($s->admin->name, 0, 1) }}</div>
                                            <span class="text-xs font-bold text-primary/80">{{ $s->admin->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/40 italic">Unassigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                        @if($s->status !== 'resolved')
                                            <form action="{{ route('admin.chats.resolve', $s->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="p-2 rounded-lg hover:bg-green-50 text-green-600 transition-all" title="Mark as Resolved">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('admin.chats.show', $s->id) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-primary text-white text-[10px] font-black uppercase tracking-widest hover:shadow-lg transition-all active:scale-95">
                                            {{ $s->admin_id ? 'Resume' : 'Join' }}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center text-muted-foreground italic">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mb-4 opacity-10"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
                                        <p>No chat sessions found for this status.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($sessions->hasPages())
                <div class="px-6 py-4 bg-muted/20 border-t">
                    {{ $sessions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>