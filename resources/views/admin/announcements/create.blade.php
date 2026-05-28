<x-admin-form 
    title="Create Announcement" 
    description="Post a new update or news item for the parish community."
    backRoute="/admin-portal/announcements"
    action="/admin-portal/announcements"
    submitLabel="Post Announcement"
>
    <div class="space-y-4">
        <div>
            <label class="block text-xs font-black uppercase tracking-widest text-muted-foreground mb-2">Headline</label>
            <input type="text" name="title" required value="{{ old('title') }}" 
                class="w-full bg-background border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-bold text-primary"
                placeholder="e.g., Upcoming Lenten Recollection">
            @error('title') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-black uppercase tracking-widest text-muted-foreground mb-2">Notice Content</label>
            <textarea name="content" required rows="6"
                class="w-full bg-background border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-primary"
                placeholder="Enter the full details of the announcement...">{{ old('content') }}</textarea>
            @error('content') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-black uppercase tracking-widest text-muted-foreground mb-2">Expiry Date (Optional)</label>
            <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}" 
                class="w-full bg-background border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-primary">
            <p class="text-xs text-muted-foreground mt-1">If set, the announcement will automatically be hidden after this date.</p>
            @error('expires_at') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="space-y-4 pt-4 border-t" x-data="{ isRecruitment: {{ old('is_recruitment', 0) }} }">
            <div class="flex items-center gap-3">
                <input type="hidden" name="is_recruitment" value="0">
                <input type="checkbox" name="is_recruitment" id="is_recruitment" value="1" 
                    x-model="isRecruitment"
                    class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
                <label for="is_recruitment" class="text-sm font-bold text-primary cursor-pointer flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-accent"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Mark as Organization Recruitment
                </label>
            </div>

            <div x-show="isRecruitment" x-transition class="bg-accent/5 p-4 rounded-xl border border-accent/10">
                <label class="block text-xs font-black uppercase tracking-widest text-primary mb-2">Online Registration Link</label>
                <input type="url" name="registration_link" value="{{ old('registration_link') }}" 
                    class="w-full bg-background border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-accent/20 transition-all font-medium text-primary"
                    placeholder="e.g., https://forms.gle/xyz...">
                <p class="text-[10px] text-muted-foreground mt-2">Parishioners will see a "Register Now" button redirecting to this link.</p>
                @error('registration_link') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center gap-3 pt-4">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }}
                class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
            <label for="is_published" class="text-sm font-bold text-primary cursor-pointer">Publish immediately</label>
        </div>
    </div>
</x-admin-form>
