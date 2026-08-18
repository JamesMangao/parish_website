@props(['show' => 'false', 'title' => '', 'maxWidth' => 'md', 'onClose' => ''])

@php
    $maxClass = match($maxWidth) {
        'sm' => 'max-w-sm',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        '5xl' => 'max-w-5xl',
        default => 'max-w-md',
    };
@endphp

<div
    x-show="{{ $show }}"
    x-cloak
    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/30 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
>
    <div
        @if($onClose) @click.away="{{ $onClose }}" @endif
        class="bg-white {{ $maxClass }} w-full rounded-2xl shadow-2xl shadow-black/10 border border-black/[.06] overflow-hidden"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
    >
        @if($title)
            <div class="px-6 py-5 border-b border-black/[.04] bg-gradient-to-r from-[#F5F7FA] to-white flex items-center justify-between">
                <h3 class="text-lg font-bold text-primary font-heading">{{ $title }}</h3>
                @if($onClose)
                    <button @click="{{ $onClose }}" class="p-1.5 rounded-lg hover:bg-black/[.04] text-muted-foreground transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                @endif
            </div>
        @endif
        <div class="p-6">
            {{ $slot }}
        </div>
    </div>
</div>
