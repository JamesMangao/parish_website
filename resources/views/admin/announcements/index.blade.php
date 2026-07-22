<x-admin-index 
    title="Announcements" 
    description="Manage latest news and updates for the parish."
    createRoute="{{ route('admin.announcements.create') }}"
    :headers="['Title', 'Content Preview', 'Status', 'Posted']"
>
    @forelse($announcements as $a)
        <tr class="hover:bg-muted/20 transition-colors">
            <td class="px-6 py-4">
                <div class="flex flex-col gap-1.5">
                    <span class="font-bold text-primary">{{ $a->title }}</span>
                    @if($a->is_recruitment)
                        <span class="flex items-center gap-1 text-[9px] font-black uppercase tracking-widest text-accent">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            Recruitment
                        </span>
                    @endif
                </div>
            </td>
            <td class="px-6 py-4 max-w-[300px] truncate text-muted-foreground">{{ $a->content }}</td>
            <td class="px-6 py-4">
                <x-admin-badge :status="$a->is_published ? 'published' : 'draft'" />
            </td>
            <td class="px-6 py-4 text-muted-foreground text-xs">{{ $a->created_at->format('M d, Y') }}</td>
            <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2" x-data>
                    <a href="{{ route('admin.announcements.edit', $a->id) }}" class="p-1.5 rounded-md border border-border text-muted-foreground hover:border-primary hover:text-primary transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                    </a>
                    <form :id="'delete-announcement-{{ $a->id }}'" action="{{ route('admin.announcements.destroy', $a->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                            @click="$store.confirm.open('Delete Announcement', 'Are you sure you want to permanently remove this announcement? This action cannot be undone.', () => document.getElementById('delete-announcement-{{ $a->id }}').submit())"
                            class="p-1.5 rounded-md border border-border text-muted-foreground hover:border-destructive hover:text-destructive transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    @empty
        <x-admin-empty
            title="No announcements yet"
            description="Create your first announcement to share news and updates with your parish community."
            icon="empty"
            :colSpan="5"
        />
    @endforelse
</x-admin-index>
