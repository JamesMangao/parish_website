@props(['status'])

@php
    $map = [
        'pending'    => 'bg-accent/10 text-accent border-accent/20',
        'approved'   => 'bg-green-100 text-green-700 border-green-200',
        'rejected'   => 'bg-destructive/10 text-destructive border-destructive/20',
        'active'     => 'bg-blue-100 text-blue-600 border-blue-200',
        'inactive'   => 'bg-muted text-muted-foreground border-border',
        'draft'      => 'bg-muted text-muted-foreground border-border',
        'published'  => 'bg-green-100 text-green-700 border-green-200',
        'accepted'   => 'bg-green-100 text-green-700 border-green-200',
        'declined'   => 'bg-destructive/10 text-destructive border-destructive/20',
        'handover'   => 'bg-red-50 text-red-600 border-red-100',
        'resolved'   => 'bg-green-50 text-green-600 border-green-100',
        'paused'     => 'bg-amber-50 text-amber-600 border-amber-100',
    ];
    $classes = $map[$status] ?? 'bg-muted text-muted-foreground border-border';
@endphp

<span class="inline-flex px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $classes }}">
    {{ $slot ?? str_replace('_', ' ', $status) }}
</span>
