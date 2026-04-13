<x-admin-index title="Parish Events" description="Manage upcoming parish activities and celebrations."
    createRoute="/admin/events/create" :headers="['Event Name', 'Date & Time', 'Status']">
    @foreach($events as $e)
        <tr class="hover:bg-muted/20 transition-colors">
            <td class="px-6 py-4">
                <div class="font-bold text-primary">{{ $e->title }}</div>
                <div class="text-xs text-muted-foreground line-clamp-1">{{ $e->description }}</div>
            </td>
            <td class="px-6 py-4">
                <div class="text-primary font-medium">{{ \Carbon\Carbon::parse($e->event_date)->format('M d, Y') }}</div>
                <div class="text-xs text-muted-foreground space-y-1 mt-1">
                    @foreach($e->event_time ?: [] as $slot)
                        <div>
                            @if(!empty($slot['title']))
                                <span class="font-bold text-primary/70">{{ $slot['title'] }}:</span>
                            @endif
                            @if(!empty($slot['time']))
                                <span>{{ \Carbon\Carbon::parse($slot['time'])->format('h:i A') }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </td>

            <td class="px-6 py-4">
                <span
                    class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $e->is_published ? 'bg-green-100 text-green-700 border-green-200' : 'bg-muted text-muted-foreground border-border' }}">
                    {{ $e->is_published ? 'Published' : 'Draft' }}
                </span>
            </td>
            <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                    <a href="/admin/events/{{ $e->id }}/edit"
                        class="p-1.5 rounded-md border border-border text-muted-foreground hover:border-primary hover:text-primary transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-pencil">
                            <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                            <path d="m15 5 4 4" />
                        </svg>
                    </a>
                    <form :id="'delete-event-{{ $e->id }}'" action="/admin/events/{{ $e->id }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" 
                            @click="triggerConfirm('Confirm Deletion', 'Are you sure you want to permanently remove this event? This action cannot be undone.', () => document.getElementById('delete-event-{{ $e->id }}').submit())"
                            class="p-1.5 rounded-md border border-border text-muted-foreground hover:border-destructive hover:text-destructive transition-all" title="Delete Event">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-trash-2">
                                <path d="M3 6h18" />
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                <line x1="10" x2="10" y1="11" y2="17" />
                                <line x1="14" x2="14" y1="11" y2="17" />
                            </svg>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    @endforeach
</x-admin-index>