<x-admin-index title="Parish Events" description="Manage upcoming parish activities and celebrations."
    createRoute="{{ route('admin.events.create') }}" :headers="['Event Name', 'Date & Time', 'Status']">
    @forelse($events as $e)
        <tr class="hover:bg-[#F5F7FA]/60 transition-colors group">
            <td class="px-6 py-4">
                <div class="font-bold text-primary text-[13px]">{{ $e->title }}</div>
                <div class="text-xs text-muted-foreground/50 line-clamp-1 mt-0.5">{{ $e->description }}</div>
            </td>
            <td class="px-6 py-4">
                <div class="text-primary font-bold text-[13px]">{{ \Carbon\Carbon::parse($e->event_date)->format('M d, Y') }}</div>
                <div class="text-xs text-muted-foreground/50 space-y-0.5 mt-1">
                    @foreach($e->event_time ?: [] as $slot)
                        <div>
                            @if(!empty($slot['title']))
                                <span class="font-bold text-primary/50">{{ $slot['title'] }}:</span>
                            @endif
                            @if(!empty($slot['date']))
                                <span class="text-muted-foreground/70">{{ \Carbon\Carbon::parse($slot['date'])->format('M d') }}</span>
                            @endif
                            @if(!empty($slot['time']))
                                <span>{{ \Carbon\Carbon::parse($slot['time'])->format('h:i A') }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </td>
            <td class="px-6 py-4">
                <x-admin-badge :status="$e->is_published ? 'published' : 'draft'" />
            </td>
            <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity" x-data>
                    <a href="{{ route('admin.events.edit', $e->id) }}"
                        class="p-2 rounded-xl hover:bg-[#F5F7FA] text-muted-foreground/40 hover:text-primary transition-all" title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" /><path d="m15 5 4 4" /></svg>
                    </a>
                    <form :id="'delete-event-{{ $e->id }}'" action="{{ route('admin.events.destroy', $e->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" 
                            @click="$store.confirm.open({ title: 'Delete Event', message: 'Are you sure you want to permanently remove this event? This action cannot be undone.', onConfirm: () => document.getElementById('delete-event-{{ $e->id }}').submit() })"
                            class="p-2 rounded-xl hover:bg-red-50 text-muted-foreground/40 hover:text-red-500 transition-all" title="Delete Event">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18" /><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" /><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" /><line x1="10" x2="10" y1="11" y2="17" /><line x1="14" x2="14" y1="11" y2="17" /></svg>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    @empty
        <x-admin-empty
            title="No events yet"
            description="Create your first parish event to keep your community informed."
            icon="empty"
            :colSpan="4"
        />
    @endforelse
</x-admin-index>
