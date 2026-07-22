<div
    x-data
    x-show="$store.toast.show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-8 scale-95"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
    x-transition:leave-end="opacity-0 translate-y-8 scale-95"
    class="fixed bottom-6 right-6 z-[9999] max-w-sm w-full bg-white border-l-4 shadow-2xl rounded-xl p-5 flex items-start gap-4"
    :class="$store.toast.type === 'success' ? 'border-green-500' : 'border-red-500'"
    x-cloak
>
    <div :class="$store.toast.type === 'success' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'"
         class="h-10 w-10 flex-shrink-0 flex items-center justify-center rounded-full shadow-sm">
        <template x-if="$store.toast.type === 'success'">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
        </template>
        <template x-if="$store.toast.type === 'error'">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
        </template>
    </div>
    <div class="flex-1 pt-0.5">
        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground mb-1" x-text="$store.toast.type === 'success' ? 'Successful Action' : 'System Notice'"></p>
        <p class="text-sm font-bold text-primary leading-tight" x-text="$store.toast.message"></p>
    </div>
    <button @click="$store.toast.show = false" class="p-1 hover:bg-muted rounded-md transition-colors text-muted-foreground mt-0.5">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
    </button>
</div>
