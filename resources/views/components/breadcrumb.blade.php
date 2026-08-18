@props(['items' => []])

@if(!empty($items))
<nav aria-label="Breadcrumb" class="max-w-[1200px] mx-auto px-6 py-4 section-px-mobile">
    <ol class="flex items-center gap-2 text-xs" itemscope itemtype="https://schema.org/BreadcrumbList">
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a href="{{ route('home') }}" itemprop="item"
               class="text-muted-foreground hover:text-primary transition-colors font-medium uppercase tracking-wider"
               style="font-size:10px; letter-spacing:0.12em;">
                <span itemprop="name">Home</span>
            </a>
            <meta itemprop="position" content="1">
        </li>
        @foreach($items as $index => $item)
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"
                class="flex items-center gap-2">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                     stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                     class="text-muted-foreground/40 flex-shrink-0" aria-hidden="true">
                    <path d="m9 18 6-6-6-6"/>
                </svg>
                @if(!empty($item['url']) && !$loop->last)
                    <a href="{{ $item['url'] }}" itemprop="item"
                       class="text-muted-foreground hover:text-primary transition-colors font-medium uppercase tracking-wider"
                       style="font-size:10px; letter-spacing:0.12em;">
                        <span itemprop="name">{{ $item['label'] }}</span>
                    </a>
                @else
                    <span itemprop="name" class="font-bold uppercase tracking-wider"
                          style="font-size:10px; letter-spacing:0.12em; color:var(--blue-deep);">
                        {{ $item['label'] }}
                    </span>
                @endif
                <meta itemprop="position" content="{{ $index + 2 }}">
            </li>
        @endforeach
    </ol>
</nav>
@endif
