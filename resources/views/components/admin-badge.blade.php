@props(['status'])

@php
    $map = [
        'pending'    => 'bg-amber-50 text-amber-700 border-amber-200/80',
        'approved'   => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
        'rejected'   => 'bg-red-50 text-red-600 border-red-200/80',
        'active'     => 'bg-blue-50 text-blue-600 border-blue-200/80',
        'inactive'   => 'bg-gray-50 text-gray-500 border-gray-200/80',
        'draft'      => 'bg-gray-50 text-gray-500 border-gray-200/80',
        'published'  => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
        'accepted'   => 'bg-emerald-50 text-emerald-700 border-emerald-200/80',
        'declined'   => 'bg-red-50 text-red-600 border-red-200/80',
        'handover'   => 'bg-orange-50 text-orange-600 border-orange-200/80',
        'resolved'   => 'bg-emerald-50 text-emerald-600 border-emerald-200/80',
        'paused'     => 'bg-amber-50 text-amber-600 border-amber-200/80',
    ];
    $classes = $map[$status] ?? 'bg-gray-50 text-gray-500 border-gray-200/80';
@endphp

<span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-[.1em] border {{ $classes }}">
    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-60 mr-1.5"></span>
    {{ $slot ?? str_replace('_', ' ', $status) }}
</span>
