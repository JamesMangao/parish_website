@props(['headers' => [], 'colSpan' => null])

@php
    $colCount = count($headers) + 1;
    $span = $colSpan ?? $colCount;
@endphp

<x-admin-card padding="p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-muted/50 border-b">
                <tr>
                    @foreach($headers as $h)
                        <th class="px-6 py-4 font-bold text-primary text-[11px] uppercase tracking-wider">{{ $h }}</th>
                    @endforeach
                    <th class="px-6 py-4 font-bold text-primary text-[11px] uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</x-admin-card>
