<x-admin-layout>
    <div class="mb-8">
        <a href="{{ $backRoute }}"
            class="text-sm font-bold text-muted-foreground hover:text-primary transition-colors flex items-center gap-1 mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="lucide lucide-chevron-left">
                <path d="m15 18-6-6 6-6" />
            </svg>
            Back to List
        </a>
        <h1 class="font-heading text-3xl font-bold text-primary italic italic text-primary">{{ $title }}</h1>
        <p class="text-sm text-muted-foreground mt-1">{{ $description }}</p>
    </div>

    <div class="max-w-2xl bg-card rounded-xl border shadow-sm p-8">
        <form action="{{ $action }}" method="POST" class="space-y-6">
            @csrf
            @if($method ?? false)
                @method($method)
            @endif

            {{ $slot }}

            <div class="pt-4 flex items-center gap-4">
                <button type="submit"
                    class="bg-primary text-primary-foreground px-8 py-2.5 rounded-md font-bold text-sm shadow-md hover:bg-primary/90 transition-all">
                    {{ $submitLabel ?? 'Save Changes' }}
                </button>
                <a href="{{ $backRoute }}"
                    class="text-sm font-bold text-muted-foreground hover:text-primary transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</x-admin-layout>