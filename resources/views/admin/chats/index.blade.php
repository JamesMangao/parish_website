<x-admin-layout>
    <div class="px-8 py-10" x-data="chatList"
         data-status="{{ $status }}"
         data-search="{{ request('search') }}"
         data-url="{{ route('admin.chats.sessions-html') }}">
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
                    @include('admin.chats._sessions_rows')
                </table>
            </div>
            
            <div id="sessions-pagination-wrap">
                @if($sessions->hasPages())
                    <div class="px-6 py-4 bg-muted/20 border-t">
                        {{ $sessions->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>