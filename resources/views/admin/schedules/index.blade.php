<x-admin-index 
    title="Mass Schedules" 
    description="Manage weekly and special mass service times."
    createRoute="{{ route('admin.schedules.create') }}"
    :headers="['Title', 'Type', 'Day', 'Time', 'Status']"
>
    @forelse($schedules as $s)
        <tr class="hover:bg-[#F5F7FA]/60 transition-colors group">
            <td class="px-6 py-4 font-bold text-primary text-[13px]">{{ $s->title ?? 'Untitled Schedule' }}</td>
            <td class="px-6 py-4 text-xs capitalize font-medium text-muted-foreground/60">{{ $s->mass_type }}</td>
            <td class="px-6 py-4 text-xs">
                @if(is_array($s->day_of_week))
                    {{ implode(', ', $s->day_of_week) }}
                @else
                    {{ $s->day_of_week ?? '—' }}
                @endif
            </td>
            <td class="px-6 py-4 text-xs font-medium">
                @if(is_array($s->time))
                    {{ implode(', ', array_map(function($t) { return $t ? \Carbon\Carbon::parse($t)->format('g:i A') : ''; }, array_filter($s->time))) }}
                @elseif($s->time)
                    {{ \Carbon\Carbon::parse($s->time)->format('g:i A') }}
                @else
                    —
                @endif
            </td>
            <td class="px-6 py-4">
                <x-admin-badge :status="$s->is_active ? 'active' : 'inactive'" />
            </td>
            <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity" x-data>
                    <a href="{{ route('admin.schedules.edit', $s->id) }}" class="p-2 rounded-xl hover:bg-[#F5F7FA] text-muted-foreground/40 hover:text-primary transition-all" title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" /><path d="m15 5 4 4" /></svg>
                    </a>
                    <form :id="'delete-schedule-{{ $s->id }}'" action="{{ route('admin.schedules.destroy', $s->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                            @click="$store.confirm.open({ title: 'Delete Schedule', message: 'Are you sure you want to permanently remove this mass schedule? This action cannot be undone.', onConfirm: () => document.getElementById('delete-schedule-{{ $s->id }}').submit() })"
                            class="p-2 rounded-xl hover:bg-red-50 text-muted-foreground/40 hover:text-red-500 transition-all" title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18" /><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" /><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" /><line x1="10" x2="10" y1="11" y2="17" /><line x1="14" x2="14" y1="11" y2="17" /></svg>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    @empty
        <x-admin-empty
            title="No schedules yet"
            description="Create your first mass schedule to help parishioners plan their attendance."
            icon="empty"
            :colSpan="6"
        />
    @endforelse
</x-admin-index>
