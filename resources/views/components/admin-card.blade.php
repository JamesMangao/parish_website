@props(['padding' => 'p-6', 'class' => ''])

<div {{ $attributes->merge(['class' => "bg-card rounded-xl border shadow-sm {$padding} {$class}"]) }}>
    {{ $slot }}
</div>
