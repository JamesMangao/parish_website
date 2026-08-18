@props(['padding' => 'p-6', 'class' => ''])

<div {{ $attributes->merge(['class' => "bg-white rounded-2xl border border-black/[.04] shadow-sm shadow-black/[.02] {$padding} {$class}"]) }}>
    {{ $slot }}
</div>
