<tbody id="sessions-tbody" class="divide-y divide-border">
    @forelse($sessions as $s)
        <tr class="hover:bg-muted/10 transition-colors group">
            <td class="px-6 py-5">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-black text-xs relative">
                        {{ substr($s->user_ip, -2) }}
                        @if(($s->last_message_sender ?? '') === 'user')
                            <span class="absolute -top-1 -right-1 h-3 w-3 bg-red-500 rounded-full border-2 border-card animate-pulse"></span>
                        @endif
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
