<x-admin-index 
    title="Announcements" 
    description="Manage latest news and updates for the parish."
    createRoute="{{ route('admin.announcements.create') }}"
    :headers="['Title', 'Content Preview', 'Category', 'Status', 'Posted']"
>
    @forelse($announcements as $a)
        <tr class="hover:bg-[#F5F7FA]/60 transition-colors group">
            <td class="px-6 py-4">
                <div class="flex flex-col gap-1">
                    <span class="font-bold text-primary text-[13px]">{{ $a->title }}</span>
                    @if($a->is_recruitment)
                        <span class="inline-flex items-center gap-1 text-[9px] font-black uppercase tracking-[.15em] text-accent">
                            <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                            Recruitment
                        </span>
                    @endif
                    @if($a->is_featured)
                        <span class="inline-flex items-center gap-1 text-[9px] font-black uppercase tracking-[.15em] text-primary/60">
                            <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            Featured
                        </span>
                    @endif
                </div>
            </td>
            <td class="px-6 py-4 max-w-[300px] truncate text-muted-foreground/60 text-xs">{{ $a->content }}</td>
            <td class="px-6 py-4">
                <span class="text-xs font-medium text-muted-foreground/60">{{ $a->category ?? 'Parish Life' }}</span>
            </td>
            <td class="px-6 py-4">
                <x-admin-badge :status="$a->is_published ? 'published' : 'draft'" />
            </td>
            <td class="px-6 py-4 text-muted-foreground/50 text-xs">{{ $a->created_at->format('M d, Y') }}</td>
            <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity" x-data>
                    <a href="{{ route('admin.announcements.edit', $a->id) }}" class="p-2 rounded-xl hover:bg-[#F5F7FA] text-muted-foreground/40 hover:text-primary transition-all" title="Edit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" /><path d="m15 5 4 4" /></svg>
                    </a>
                    <form id="delete-announcement-{{ $a->id }}" action="{{ route('admin.announcements.destroy', $a) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                            @click="$store.confirm.open({
                                title: 'Delete Announcement',
                                message: 'Are you sure you want to permanently remove this announcement? This action cannot be undone.',
                                onConfirm: () => document.getElementById('delete-announcement-{{ $a->id }}').submit()
                            })"
                            class="p-2 rounded-xl hover:bg-red-50 text-muted-foreground/40 hover:text-red-500 transition-all"
                            title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18" /><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" /><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" /><line x1="10" x2="10" y1="11" y2="17" /><line x1="14" x2="14" y1="11" y2="17" /></svg>
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
            :colSpan="6"
        />
    @endforelse
</x-admin-index>
