<button @click="fetchPreview()"
    class="inline-flex items-center gap-2 bg-muted border border-border px-4 py-2 rounded-xl font-bold text-xs hover:bg-muted/80 transition-all">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0z" />
        <circle cx="12" cy="12" r="3" />
    </svg>
    Preview Content
</button>
<form action="{{ route('admin.generate-ppt') }}" method="POST" class="inline-flex">
    @csrf
    <button type="submit"
        class="inline-flex items-center gap-2 bg-accent text-accent-foreground px-4 py-2 rounded-xl font-bold text-xs shadow-md hover:opacity-90 transition-opacity">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M2 3h20" />
            <path d="M21 3v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V3" />
            <path d="m7 21 5-5 5 5" />
        </svg>
        Generate PPT
    </button>
</form>
