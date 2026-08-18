<x-admin-layout>
    <div class="mb-8">
        <a href="{{ $backRoute }}"
            class="inline-flex items-center gap-1.5 text-xs font-bold text-muted-foreground/60 hover:text-primary transition-colors mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m15 18-6-6 6-6" />
            </svg>
            Back to List
        </a>
        <h1 class="font-heading text-3xl font-bold text-primary italic">{{ $title }}</h1>
        <p class="text-sm text-muted-foreground mt-1.5">{{ $description }}</p>
    </div>

    <div class="max-w-4xl bg-white rounded-2xl border border-black/[.04] shadow-sm shadow-black/[.02] p-8">
        <form action="{{ $action }}" method="POST" class="space-y-6">
            @csrf
            @if($method ?? false)
                @method($method)
            @endif

            {{ $slot }}

            <div class="pt-4 flex items-center gap-4 border-t border-black/[.04]">
                <button type="submit"
                    class="bg-primary text-primary-foreground px-8 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-primary/15 hover:shadow-xl hover:shadow-primary/20 hover:scale-[1.01] active:scale-[0.98] transition-all duration-200">
                    {{ $submitLabel ?? 'Save Changes' }}
                </button>
                <a href="{{ $backRoute }}"
                    class="text-sm font-bold text-muted-foreground/60 hover:text-primary transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>
