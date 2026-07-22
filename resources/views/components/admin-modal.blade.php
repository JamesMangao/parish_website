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
    class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-background/60 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
>
    <div
        @if($onClose) @click.away="{{ $onClose }}" @endif
        class="bg-white {{ $maxClass }} w-full rounded-2xl shadow-2xl border overflow-hidden animate-in zoom-in-95 duration-200"
    >
        @if($title)
            <div class="p-6 border-b bg-muted/30 flex items-center justify-between">
                <h3 class="text-lg font-bold text-primary italic font-heading">{{ $title }}</h3>
                @if($onClose)
                    <button @click="{{ $onClose }}" class="text-muted-foreground hover:text-primary transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                @endif
            </div>
        @endif
        <div class="p-6">
            {{ $slot }}
        </div>
    </div>
</div>
