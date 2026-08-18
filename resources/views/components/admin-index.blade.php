<x-admin-layout>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-heading text-3xl font-bold text-primary italic">{{ $title }}</h1>
            <p class="text-sm text-muted-foreground mt-1.5">{{ $description }}</p>
        </div>

        @isset($createRoute)
            <a href="{{ $createRoute }}"
                class="inline-flex items-center gap-2 bg-primary text-primary-foreground px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-primary/15 hover:shadow-xl hover:shadow-primary/20 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14" />
                    <path d="M12 5v14" />
                </svg>
                Create New
            </a>
        @endisset
    </div>

    <div class="bg-white rounded-2xl border border-black/[.04] shadow-sm shadow-black/[.02] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-black/[.04]">
                        @foreach($headers as $h)
                            <th class="px-6 py-4 font-bold text-primary/50 text-[10px] uppercase tracking-[.15em]">{{ $h }}</th>
                        @endforeach
                        <th class="px-6 py-4 font-bold text-primary/50 text-[10px] uppercase tracking-[.15em] text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/[.03]">
                    {{ $slot }}
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
