<x-admin-form title="Edit Parish Event" description="Update the details of your parish activity or celebration."
    backRoute="/admin/events" action="/admin/events/{{ $event->id }}" method="PUT" submitLabel="Update Event">
    <div class="space-y-4">
        <div>
            <label class="block text-xs font-black uppercase tracking-widest text-muted-foreground mb-2">Event
                Title</label>
            <input type="text" name="title" required value="{{ old('title', $event->title) }}"
                class="w-full bg-background border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-bold text-primary"
                placeholder="e.g., Annual Parish Fiesta">
            @error('title') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label
                class="block text-xs font-black uppercase tracking-widest text-muted-foreground mb-2">Description</label>
            <textarea name="description" rows="4"
                class="w-full bg-background border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-primary"
                placeholder="Describe what will happen during the event...">{{ old('description', $event->description) }}</textarea>
            @error('description') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-muted-foreground mb-2">Event
                    Date</label>
                <input type="date" name="event_date" required
                    value="{{ old('event_date', \Carbon\Carbon::parse($event->event_date)->format('Y-m-d')) }}"
                    class="w-full bg-background border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-bold text-primary">
            </div>
            <div>
                <label
                    class="block text-xs font-black uppercase tracking-widest text-muted-foreground mb-2">Location</label>
                <input type="text" name="location" value="{{ old('location', $event->location) }}"
                    class="w-full bg-background border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-bold text-primary"
                    placeholder="e.g., Parish Hall / Online">
            </div>
        </div>

        <div x-data="{ times: {{ json_encode(old('event_time', $event->event_time ?: [['time' => '', 'title' => '']])) }} }">
            <label class="block text-xs font-black uppercase tracking-widest text-muted-foreground mb-2">Service
                Times</label>
            <template x-for="(time, index) in times" :key="index">
                <div class="flex gap-2 mb-2">
                    <input type="text" :name="'event_time['+index+'][title]'" x-model="times[index].title"
                        placeholder="Title (e.g. Mass)"
                        class="flex-1 bg-background border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-bold text-primary">
                    <input type="time" :name="'event_time['+index+'][time]'" x-model="times[index].time"
                        class="w-32 bg-background border rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-bold text-primary">
                    <button type="button" @click="times.splice(index, 1)" x-show="times.length > 1"
                        class="px-3 py-2 bg-destructive/10 text-destructive rounded-md hover:bg-destructive hover:text-white transition-colors"
                        title="Remove Time">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path d="M18 6 6 18" />
                            <path d="m6 6 12 12" />
                        </svg>
                    </button>
                </div>
            </template>
            <button type="button" @click="times.push({time: '', title: ''})"
                class="text-xs font-bold text-primary hover:underline flex items-center gap-1 mt-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14" />
                    <path d="M12 5v14" />
                </svg>
                Add Another Time
            </button>
            @error('event_time') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-3 py-2">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $event->is_published) ? 'checked' : '' }}
                class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary">
            <label for="is_published" class="text-sm font-bold text-primary cursor-pointer">Published and
                Visible</label>
        </div>
    </div>
</x-admin-form>