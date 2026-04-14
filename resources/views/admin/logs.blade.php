<x-admin-layout>
    <div class="max-w-6xl">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="font-heading text-3xl font-bold text-primary italic">System Activity Logs</h1>
                <p class="text-sm text-muted-foreground mt-1">Audit trail of all administrative actions performed on the platform.</p>
            </div>
        </div>

        <div class="bg-card rounded-2xl border shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-muted/30">
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-muted-foreground border-b">Timestamp</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-muted-foreground border-b">User</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-muted-foreground border-b">Action</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-muted-foreground border-b">Details</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-muted-foreground border-b">IP Address</th>
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
                                <span class="text-[10px] font-black uppercase tracking-widest px-2 py-0.5 rounded {{ 
                                    str_contains($log->action, 'create') ? 'bg-green-100 text-green-700' : 
                                    (str_contains($log->action, 'delete') ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') 
                                }}">
                                    {{ $log->action }}
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
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-muted-foreground italic">No activity logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-6 border-t">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
