<x-admin-layout>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="font-heading text-3xl font-bold text-primary italic capitalize italic text-primary">{{ $title }}
            </h1>
            <p class="text-sm text-muted-foreground mt-1">{{ $description }}</p>
        </div>

        @isset($createRoute)
            <a href="{{ $createRoute }}"
                class="inline-flex items-center gap-2 bg-primary text-primary-foreground px-4 py-2 rounded-md font-bold text-sm shadow-md hover:bg-primary/90 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-plus">
                    <path d="M5 12h14" />
                    <path d="M12 5v14" />
                </svg>
                Create New
            </a>
        @endisset
    </div>

    <div class="bg-card rounded-xl border shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-muted/50 border-b">
                    <tr>
                        @foreach($headers as $h)
                            <th class="px-6 py-4 font-bold text-primary">{{ $h }}</th>
                        @endforeach
                        <th class="px-6 py-4 font-bold text-primary text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    {{ $slot }}
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>