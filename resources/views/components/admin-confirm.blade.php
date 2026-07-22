<div
    x-data
    x-show="$store.confirm.show"
    class="fixed inset-0 z-[9998] flex items-center justify-center p-4 bg-background/60 backdrop-blur-sm"
    x-cloak
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
>
    <div
        @click.away="$store.confirm.cancel()"
        class="bg-white max-w-md w-full rounded-2xl shadow-2xl border p-6 animate-in zoom-in-95 duration-200"
    >
        <div class="flex items-start gap-4 mb-6">
            <div :class="$store.confirm.type === 'danger' ? 'bg-red-100 text-red-600' : 'bg-primary/10 text-primary'" class="h-12 w-12 flex-shrink-0 flex items-center justify-center rounded-full">
                <template x-if="$store.confirm.type === 'danger'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </template>
                <template x-if="$store.confirm.type !== 'danger'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/></svg>
                </template>
            </div>
            <div>
                <h3 class="text-xl font-bold text-primary italic font-heading" x-text="$store.confirm.title"></h3>
                <p class="text-sm text-muted-foreground mt-2 leading-relaxed" x-text="$store.confirm.message"></p>
            </div>
        </div>
        <div class="flex items-center justify-end gap-3">
            <button @click="$store.confirm.cancel()" class="px-5 py-2 rounded-md text-sm font-bold text-muted-foreground hover:bg-muted transition-colors">
                Cancel
            </button>
            <button @click="$store.confirm.execute()"
                    :class="$store.confirm.type === 'danger' ? 'bg-red-600 hover:bg-red-700' : 'bg-primary hover:bg-primary/90'"
                    class="px-5 py-2 text-white rounded-md text-sm font-bold shadow-md transition-all">
                <span x-text="$store.confirm.confirmText"></span>
            </button>
        </div>
    </div>
</div>
