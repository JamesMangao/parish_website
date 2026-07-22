<x-admin-layout>
    <div class="max-w-6xl" x-data="{
        search: '{{ request('search') }}',
        action: '{{ request('action', 'all') }}',
        from: '{{ request('from') }}',
        to: '{{ request('to') }}',
        applyFilters() {
            const params = new URLSearchParams();
            if (this.search) params.set('search', this.search);
            if (this.action && this.action !== 'all') params.set('action', this.action);
            if (this.from) params.set('from', this.from);
            if (this.to) params.set('to', this.to);
            window.location.href = '{{ route('admin.logs') }}?' + params.toString();
        },
        clearFilters() {
            window.location.href = '{{ route('admin.logs') }}';
        }
    }">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="font-heading text-3xl font-bold text-primary italic">System Activity Logs</h1>
                <p class="text-sm text-muted-foreground mt-1">Audit trail of all administrative actions performed on the platform.</p>
            </div>
        </div>

        {{-- Filter Bar --}}
        <x-admin-card padding="p-4" class="mb-6">
            <div class="flex flex-wrap items-end gap-3">
                <div class="flex-1 min-w-[200px]">
                    <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1 block">Search</label>
                    <input type="text" x-model="search" @keydown.enter="applyFilters()"
                        placeholder="Search action, user, or IP..."
                        class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                </div>
                <div class="min-w-[160px]">
                    <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1 block">Action Type</label>
                    <select x-model="action" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                        <option value="all">All Actions</option>
                        <option value="create">Create</option>
                        <option value="update">Update</option>
                        <option value="delete">Delete</option>
                        <option value="status_update">Status Update</option>
                        <option value="batch">Batch</option>
                        <option value="role_change">Role Change</option>
                    </select>
                </div>
                <div class="min-w-[140px]">
                    <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1 block">From</label>
                    <input type="date" x-model="from" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                </div>
                <div class="min-w-[140px]">
                    <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1 block">To</label>
                    <input type="date" x-model="to" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                </div>
                <div class="flex gap-2">
                    <button @click="applyFilters()" class="px-4 py-2 bg-primary text-primary-foreground rounded-lg text-xs font-bold shadow hover:bg-primary/90 transition-all">Apply</button>
                    @if(request()->hasAny(['search', 'action', 'from', 'to']))
                        <button @click="clearFilters()" class="px-4 py-2 bg-muted text-muted-foreground rounded-lg text-xs font-bold hover:bg-muted/80 transition-all">Clear</button>
                    @endif
                </div>
            </div>
        </x-admin-card>

        <div class="bg-card rounded-xl border shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-muted/50 border-b">
                        <tr>
                            <th class="px-6 py-4 font-bold text-primary text-[11px] uppercase tracking-wider">Timestamp</th>
                            <th class="px-6 py-4 font-bold text-primary text-[11px] uppercase tracking-wider">User</th>
                            <th class="px-6 py-4 font-bold text-primary text-[11px] uppercase tracking-wider">Action</th>
                            <th class="px-6 py-4 font-bold text-primary text-[11px] uppercase tracking-wider">Details</th>
                            <th class="px-6 py-4 font-bold text-primary text-[11px] uppercase tracking-wider">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($logs as $log)
                            <tr class="hover:bg-muted/20 transition-colors">
                                <td class="px-6 py-4 text-xs font-medium text-muted-foreground whitespace-nowrap">
                                    {{ $log->created_at->format('M d, Y H:i:s') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="h-6 w-6 rounded-full bg-primary/10 text-primary flex items-center justify-center text-[10px] font-black border border-primary/20">
                                            {{ substr($log->user->name ?? 'S', 0, 1) }}
                                        </div>
                                        <span class="text-xs font-bold text-primary">{{ $log->user->name ?? 'System' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $actionColor = str_contains($log->action, 'create') ? 'bg-green-100 text-green-700 border-green-200' :
                                            (str_contains($log->action, 'delete') ? 'bg-red-100 text-red-700 border-red-200' :
                                            (str_contains($log->action, 'role') ? 'bg-purple-100 text-purple-700 border-purple-200' :
                                            'bg-blue-100 text-blue-700 border-blue-200'));
                                    @endphp
                                    <span class="inline-flex text-[10px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full border {{ $actionColor }}">
                                        {{ str_replace('_', ' ', $log->action) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs text-primary font-medium">{{ $log->model_type ? class_basename($log->model_type) : '' }} {{ $log->model_id ? '#' . substr($log->model_id, 0, 8) : '' }}</p>
                                    @if($log->payload)
                                        <div class="mt-1" x-data="{ open: false }">
                                            <button @click="open = !open" class="text-[10px] font-black uppercase tracking-widest text-accent hover:underline">View Payload</button>
                                            <div x-show="open" x-cloak class="mt-2 p-2 bg-muted/50 rounded text-[10px] font-mono break-all max-w-xs">
                                                {{ json_encode($log->payload) }}
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs font-mono text-muted-foreground">
                                    {{ $log->ip_address }}
                                </td>
                            </tr>
                        @empty
                            <x-admin-empty
                                title="No activity logs found"
                                description="{{ request()->hasAny(['search', 'action', 'from', 'to']) ? 'No logs match your current filters. Try adjusting your search criteria.' : 'No administrative actions have been recorded yet.' }}"
                                icon="{{ request()->hasAny(['search', 'action', 'from', 'to']) ? 'search' : 'inbox' }}"
                                :colSpan="5"
                            />
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($logs->hasPages())
                <div class="p-6 border-t">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
