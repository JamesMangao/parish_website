<div
    x-data
    x-show="$store.toast.show"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 translate-y-6 scale-[0.96]"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
    x-transition:leave-end="opacity-0 translate-y-6 scale-[0.96]"
    class="fixed bottom-6 right-6 z-[9999] max-w-sm w-full bg-white border border-black/[.06] shadow-2xl shadow-black/10 rounded-2xl p-5 flex items-start gap-4"
    :class="$store.toast.type === 'success' ? 'border-l-[3px] border-l-emerald-500' : 'border-l-[3px] border-l-red-500'"
    x-cloak
>
    <div :class="$store.toast.type === 'success' ? 'bg-emerald-50 text-emerald-500' : 'bg-red-50 text-red-500'"
         class="h-10 w-10 flex-shrink-0 flex items-center justify-center rounded-xl">
        <template x-if="$store.toast.type === 'success'">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
        </template>
        <template x-if="$store.toast.type === 'error'">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
        </template>
    </div>
    <div class="flex-1 pt-0.5 min-w-0">
        <p class="text-[10px] font-black uppercase tracking-[.15em] text-muted-foreground/50 mb-0.5" x-text="$store.toast.type === 'success' ? 'Success' : 'Notice'"></p>
        <p class="text-sm font-bold text-primary leading-snug" x-text="$store.toast.message"></p>
    </div>
    <button @click="$store.toast.show = false" class="p-1 rounded-lg hover:bg-[#F5F7FA] transition-colors text-muted-foreground/40 hover:text-muted-foreground mt-0.5">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
    </button>
</div>
