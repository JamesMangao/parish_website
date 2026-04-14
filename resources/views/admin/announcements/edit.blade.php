<x-admin-form 
    title="Edit Announcement" 
    description="Update the details of your parish announcement."
    backRoute="/admin/announcements"
    action="/admin/announcements/{{ $announcement->id }}"
    method="PUT"
    submitLabel="Update Announcement"
>
    <div class="space-y-4">
        <div>
            <label class="block text-xs font-black uppercase tracking-widest text-muted-foreground mb-2">Headline</label>
            <input type="text" name="title" required value="{{ old('title', $announcement->title) }}" 
                class="w-full bg-background border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-bold text-primary"
                placeholder="e.g., Upcoming Lenten Recollection">
            @error('title') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-black uppercase tracking-widest text-muted-foreground mb-2">Notice Content</label>
            <textarea name="content" required rows="6"
                class="w-full bg-background border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-primary"
                placeholder="Enter the full details of the announcement...">{{ old('content', $announcement->content) }}</textarea>
            @error('content') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-black uppercase tracking-widest text-muted-foreground mb-2">Expiry Date (Optional)</label>
            <input type="datetime-local" name="expires_at" value="{{ old('expires_at', $announcement->expires_at ? $announcement->expires_at->format('Y-m-d\TH:i') : '') }}" 
                class="w-full bg-background border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-primary">
            <p class="text-xs text-muted-foreground mt-1">If set, the announcement will automatically be hidden after this date.</p>
            @error('expires_at') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-3 py-2">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $announcement->is_published) ? 'checked' : '' }}
                class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
            <label for="is_published" class="text-sm font-bold text-primary cursor-pointer">Published and Visible</label>
        </div>
    </div>
</x-admin-form>
