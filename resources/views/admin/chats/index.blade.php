<x-admin-index title="Live Chat Conversations" description="Manage active chat sessions with parishioners."
    :headers="['User IP', 'Status', 'Messages', 'Requested At', 'Admin']">
    @foreach($sessions as $s)
        <tr class="hover:bg-muted/20 transition-colors">
            <td class="px-6 py-4 font-mono text-xs">{{ $s->user_ip }}</td>
            <td class="px-6 py-4 capitalize">
                <span
                    class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $s->status === 'handover' ? 'bg-red-100 text-red-700 border-red-200 animate-pulse' : 'bg-muted text-muted-foreground' }}">
                    {{ $s->status }}
                </span>
            </td>
            <td class="px-6 py-4 font-bold text-primary">{{ $s->messages_count }}</td>
            <td class="px-6 py-4 text-xs text-muted-foreground">
                {{ $s->live_agent_requested_at ? $s->live_agent_requested_at->diffForHumans() : 'N/A' }}
            </td>
            <td class="px-6 py-4 text-xs font-medium text-primary">
                {{ $s->user ? $s->user->name : 'Unassigned' }}
            </td>
            <td class="px-6 py-4 text-right">
                <a href="{{ route('admin.chats.show', $s->id) }}"
                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md bg-primary text-white text-[10px] font-bold hover:opacity-90 transition-all">
                    {{ $s->admin_id ? 'Resume Chat' : 'Join Chat' }}
                </a>
            </td>
        </tr>
    @endforeach
</x-admin-index>