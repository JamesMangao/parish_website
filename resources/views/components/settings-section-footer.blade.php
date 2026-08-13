<div class="flex items-center justify-between mt-6 pt-4 border-t border-border">
    <span x-show="isDirty" x-cloak class="text-[10px] font-black uppercase tracking-widest text-amber-600 animate-pulse">Unsaved changes</span>
    <span x-show="!isDirty" class="text-[10px] text-muted-foreground italic">No changes</span>
    <button type="submit"
        :disabled="!isDirty"
        :class="!isDirty && 'opacity-40 cursor-not-allowed hover:scale-100'"
        class="px-6 py-2.5 bg-accent text-accent-foreground rounded-xl font-black text-xs shadow-lg hover:scale-[1.02] transition-all flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
        {{ $label ?? 'Save' }}
    </button>
</div>
