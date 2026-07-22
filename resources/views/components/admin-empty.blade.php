@props(['title' => null, 'description' => null, 'actionUrl' => null, 'actionLabel' => null, 'icon' => 'empty', 'colSpan' => 5])

<tr>
    <td colspan="{{ $colSpan }}" class="px-6 py-16 text-center">
        <div class="flex flex-col items-center justify-center">
            @if($icon === 'empty')
                <div class="h-16 w-16 rounded-full bg-muted/50 flex items-center justify-center text-muted-foreground/40 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
            @elseif($icon === 'search')
                <div class="h-16 w-16 rounded-full bg-muted/50 flex items-center justify-center text-muted-foreground/40 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>
            @elseif($icon === 'inbox')
                <div class="h-16 w-16 rounded-full bg-muted/50 flex items-center justify-center text-muted-foreground/40 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
                </div>
            @elseif($icon === 'chat')
                <div class="h-16 w-16 rounded-full bg-muted/50 flex items-center justify-center text-muted-foreground/40 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/></svg>
                </div>
            @endif
            @if($title)
                <p class="text-sm font-bold text-primary">{{ $title }}</p>
            @endif
            @if($description)
                <p class="text-xs text-muted-foreground mt-1 max-w-sm">{{ $description }}</p>
            @endif
            @if($actionUrl && $actionLabel)
                <a href="{{ $actionUrl }}" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-primary text-primary-foreground rounded-md text-xs font-bold shadow hover:bg-primary/90 transition-all">
                    {{ $actionLabel }}
                </a>
            @endif
        </div>
    </td>
</tr>
