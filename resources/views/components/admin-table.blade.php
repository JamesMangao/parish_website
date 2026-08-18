@props(['headers' => [], 'colSpan' => null])

@php
    $colCount = count($headers) + 1;
    $span = $colSpan ?? $colCount;
@endphp

<x-admin-card padding="p-0">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-black/[.04]">
                    @foreach($headers as $h)
                        <th class="px-6 py-4 font-bold text-primary/50 text-[10px] uppercase tracking-[.15em]">{{ $h }}</th>
                    @endforeach
                    <th class="px-6 py-4 font-bold text-primary/50 text-[10px] uppercase tracking-[.15em] text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-black/[.03]">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</x-admin-card>
