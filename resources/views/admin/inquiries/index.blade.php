<x-admin-index 
    title="Service Inquiries" 
    description="Review and validate sacramental inquiries from parishioners."
    :headers="['Name', 'Type', 'Status', 'Date']"
>
    @foreach($inquiries as $i)
        <tr class="hover:bg-muted/20 transition-colors">
            <td class="px-6 py-4 font-bold text-primary">{{ $i->full_name }}</td>
            <td class="px-6 py-4 capitalize">{{ $i->inquiry_type }}</td>
            <td class="px-6 py-4">
                <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest border 
                    {{ $i->status === 'accepted' ? 'bg-green-100 text-green-700 border-green-200' : '' }}
                    {{ $i->status === 'declined' ? 'bg-red-100 text-red-700 border-red-200' : '' }}
                    {{ $i->status === 'pending' ? 'bg-amber-100 text-amber-700 border-amber-200' : '' }}">
                    {{ $i->status }}
                </span>
            </td>
            <td class="px-6 py-4 text-xs text-muted-foreground">{{ $i->created_at->format('M d, Y h:i A') }}</td>
            <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('admin.inquiries.show', $i->id) }}" class="p-1.5 rounded-md border border-border text-muted-foreground hover:border-primary hover:text-primary transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0z"/><circle cx="12" cy="12" r="3"/></svg>
                    </a>
                </div>
            </td>
        </tr>
    @endforeach
</x-admin-index>
