<x-admin-form 
    title="Create Mass Schedule" 
    description="Add a new service time to the parish schedule."
    backRoute="/admin/schedules"
    action="/admin/schedules"
    submitLabel="Create Schedule"
>
    <div class="space-y-4">
        <div>
            <label class="block text-xs font-black uppercase tracking-widest text-muted-foreground mb-2">Service Title</label>
            <input type="text" name="title" value="{{ old('title') }}" 
                class="w-full bg-background border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-bold text-primary"
                placeholder="e.g., Sunday Morning Mass">
            @error('title') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-muted-foreground mb-2">Mass Type</label>
                <select name="mass_type" required 
                    class="w-full bg-background border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-bold text-primary">
                    <option value="sunday" {{ old('mass_type') == 'sunday' ? 'selected' : '' }}>Sunday</option>
                    <option value="weekday" {{ old('mass_type') == 'weekday' ? 'selected' : '' }}>Weekday</option>
                    <option value="saturday" {{ old('mass_type') == 'saturday' ? 'selected' : '' }}>Saturday</option>
                    <option value="anticipated" {{ old('mass_type') == 'anticipated' ? 'selected' : '' }}>Anticipated</option>
                    <option value="special" {{ old('mass_type') == 'special' ? 'selected' : '' }}>Special</option>
                </select>
            </div>
            <div x-data="{ times: ['{{ old('time.0', '') }}'] }">
                <label class="block text-xs font-black uppercase tracking-widest text-muted-foreground mb-2">Times</label>
                <template x-for="(time, index) in times" :key="index">
                    <div class="flex gap-2 mb-2">
                        <input type="time" :name="'time['+index+']'" x-model="times[index]" required 
                            class="flex-1 bg-background border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-bold text-primary">
                        <button type="button" @click="times.splice(index, 1)" x-show="times.length > 1" class="px-3 py-2 bg-destructive/10 text-destructive rounded-md hover:bg-destructive hover:text-white transition-colors" title="Remove Time">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                        </button>
                    </div>
                </template>
                <button type="button" @click="times.push('')" class="text-xs font-bold text-primary hover:underline flex items-center gap-1 mt-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    Add Another Time
                </button>
                @error('time') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-xs font-black uppercase tracking-widest text-muted-foreground mb-2">Days of Week (for Weekly Masses)</label>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 border rounded-md p-4 bg-background">
                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                    <label class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="day_of_week[]" value="{{ $day }}" 
                            {{ (is_array(old('day_of_week')) && in_array($day, old('day_of_week'))) ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary transition-all">
                        <span class="text-sm font-bold text-muted-foreground group-hover:text-primary transition-colors">{{ $day }}</span>
                    </label>
                @endforeach
            </div>
            @error('day_of_week') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
        </div>


    </div>
</x-admin-form>
