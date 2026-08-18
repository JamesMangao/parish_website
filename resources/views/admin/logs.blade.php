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
        <div class="bg-white rounded-2xl border border-black/[.04] p-4 mb-6">
            <div class="flex flex-wrap items-end gap-3">
                <div class="flex-1 min-w-[200px]">
                    <label class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/50 mb-1.5 block">Search</label>
                    <input type="text" x-model="search" @keydown.enter="applyFilters()" placeholder="Search action, user, or IP..."
                        class="w-full bg-[#F5F7FA] border border-black/[.06] rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all">
                </div>
                <div class="min-w-[160px]">
                    <label class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/50 mb-1.5 block">Action Type</label>
                    <select x-model="action" class="w-full bg-[#F5F7FA] border border-black/[.06] rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all">
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
                    <label class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/50 mb-1.5 block">From</label>
                    <input type="date" x-model="from" class="w-full bg-[#F5F7FA] border border-black/[.06] rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all">
                </div>
                <div class="min-w-[140px]">
                    <label class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/50 mb-1.5 block">To</label>
                    <input type="date" x-model="to" class="w-full bg-[#F5F7FA] border border-black/[.06] rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary/30 transition-all">
                </div>
                <div class="flex gap-2">
                    <button @click="applyFilters()" class="px-5 py-2.5 bg-primary text-primary-foreground rounded-xl text-xs font-bold shadow-lg shadow-primary/15 hover:shadow-xl transition-all">Apply</button>
                    @if(request()->hasAny(['search', 'action', 'from', 'to']))
                        <button @click="clearFilters()" class="px-5 py-2.5 bg-[#F5F7FA] text-muted-foreground/60 rounded-xl text-xs font-bold hover:bg-black/[.04] transition-all">Clear</button>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-black/[.04] shadow-sm shadow-black/[.02] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b border-black/[.04]">
                            <th class="px-6 py-4 font-bold text-primary/50 text-[10px] uppercase tracking-[.15em]">Timestamp</th>
                            <th class="px-6 py-4 font-bold text-primary/50 text-[10px] uppercase tracking-[.15em]">User</th>
                            <th class="px-6 py-4 font-bold text-primary/50 text-[10px] uppercase tracking-[.15em]">Action</th>
                            <th class="px-6 py-4 font-bold text-primary/50 text-[10px] uppercase tracking-[.15em]">Details</th>
                            <th class="px-6 py-4 font-bold text-primary/50 text-[10px] uppercase tracking-[.15em]">IP Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/[.03]">
                        @forelse($logs as $log)
                            <tr class="hover:bg-[#F5F7FA]/60 transition-colors">
                                <td class="px-6 py-4 text-xs font-medium text-muted-foreground/50 whitespace-nowrap">
                                    {{ $log->created_at->format('M d, Y H:i:s') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="h-7 w-7 rounded-lg bg-primary/5 text-primary flex items-center justify-center text-[10px] font-black">
                                            {{ substr($log->user->name ?? 'S', 0, 1) }}
                                        </div>
                                        <span class="text-xs font-bold text-primary">{{ $log->user->name ?? 'System' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $actionColor = str_contains($log->action, 'create') ? 'bg-emerald-50 text-emerald-600' :
                                            (str_contains($log->action, 'delete') ? 'bg-red-50 text-red-600' :
                                            (str_contains($log->action, 'role') ? 'bg-purple-50 text-purple-600' :
                                            'bg-blue-50 text-blue-600'));
                                    @endphp
                                    <span class="inline-flex text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-lg {{ $actionColor }}">
                                        {{ str_replace('_', ' ', $log->action) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs text-primary/70 font-medium">{{ $log->model_type ? class_basename($log->model_type) : '' }} {{ $log->model_id ? '#' . substr($log->model_id, 0, 8) : '' }}</p>
                                    @if($log->payload)
                                        <div class="mt-1" x-data="{ open: false }">
                                            <button @click="open = !open" class="text-[10px] font-bold text-accent hover:underline">View Payload</button>
                                            <div x-show="open" x-cloak class="mt-2 p-3 bg-[#F5F7FA] rounded-lg text-[10px] font-mono break-all max-w-xs border border-black/[.04]">
                                                {{ json_encode($log->payload) }}
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs font-mono text-muted-foreground/40">
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
                <div class="px-6 py-4 border-t border-black/[.04] bg-[#FAFBFC]">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
