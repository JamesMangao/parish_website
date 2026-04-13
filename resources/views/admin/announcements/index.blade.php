<x-admin-index 
    title="Announcements" 
    description="Manage latest news and updates for the parish."
    createRoute="/admin/announcements/create"
    :headers="['Title', 'Content Preview', 'Status', 'Posted']"
>
    @forelse($announcements as $a)
        <tr class="hover:bg-muted/20 transition-colors">
            <td class="px-6 py-4 font-bold text-primary">{{ $a->title }}</td>
            <td class="px-6 py-4 max-w-[300px] truncate text-muted-foreground">{{ $a->content }}</td>
            <td class="px-6 py-4">
                <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $a->is_published ? 'bg-green-100 text-green-700 border-green-200' : 'bg-muted text-muted-foreground border-border' }}">
                    {{ $a->is_published ? 'Published' : 'Draft' }}
                </span>
            </td>
            <td class="px-6 py-4 text-muted-foreground text-xs">{{ $a->created_at->format('M d, Y') }}</td>
            <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                    <a href="/admin/announcements/{{ $a->id }}/edit" class="p-1.5 rounded-md border border-border text-muted-foreground hover:border-primary hover:text-primary transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                    </a>
                    <form action="/admin/announcements/{{ $a->id }}" method="POST" onsubmit="return confirm('Delete this announcement?')">
                        @csrf
                        @method('DELETE')
                        <button class="p-1.5 rounded-md border border-border text-muted-foreground hover:border-destructive hover:text-destructive transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="px-6 py-12 text-center text-muted-foreground italic">No announcements found.</td>
        </tr>
    @endforelse
</x-admin-index>
