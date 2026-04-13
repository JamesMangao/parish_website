<x-admin-form 
    title="Create Mass Intention" 
    description="Manually add a mass intention to the parish record."
    backRoute="/admin/intentions"
    action="/admin/intentions"
    submitLabel="Create Intention"
>
    <div class="space-y-4">
        <div>
            <label class="block text-xs font-black uppercase tracking-widest text-muted-foreground mb-2">Full Name</label>
            <input type="text" name="fullName" value="{{ old('fullName') }}" required
                class="w-full bg-background border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-bold text-primary"
                placeholder="e.g., Juan Dela Cruz">
            @error('fullName') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-black uppercase tracking-widest text-muted-foreground mb-2">Intention Type</label>
            <select name="intentionType" required 
                class="w-full bg-background border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-bold text-primary">
                <option value="Thanksgiving" {{ old('intentionType') == 'Thanksgiving' ? 'selected' : '' }}>Thanksgiving</option>
                <option value="Petition" {{ old('intentionType') == 'Petition' ? 'selected' : '' }}>Petition</option>
                <option value="Healing" {{ old('intentionType') == 'Healing' ? 'selected' : '' }}>Healing</option>
                <option value="Souls" {{ old('intentionType') == 'Souls' ? 'selected' : '' }}>For the Souls</option>
                <option value="Special Intention" {{ old('intentionType') == 'Special Intention' ? 'selected' : '' }}>Special Intention</option>
            </select>
            @error('intentionType') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-muted-foreground mb-2">Preferred Date</label>
                <input type="date" name="preferredDate" value="{{ old('preferredDate') }}" 
                    class="w-full bg-background border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-bold text-primary">
                @error('preferredDate') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-muted-foreground mb-2">Mass Time</label>
                <select name="massTime" 
                    class="w-full bg-background border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-bold text-primary">
                    <option value="" disabled selected>Select time...</option>
                    @foreach(["6:00 AM", "8:30 AM", "10:00 AM", "3:00 PM", "4:30 PM", "6:00 PM"] as $time)
                        <option value="{{ $time }}" {{ old('massTime') == $time ? 'selected' : '' }}>{{ $time }}</option>
                    @endforeach
                </select>
                @error('massTime') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-xs font-black uppercase tracking-widest text-muted-foreground mb-2">Description / Specific Intentions</label>
            <textarea name="description" rows="3" 
                class="w-full bg-background border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-bold text-primary placeholder:text-muted-foreground/50"
                placeholder="Enter names or specific intentions...">{{ old('description') }}</textarea>
            @error('description') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-black uppercase tracking-widest text-muted-foreground mb-2">Donation Method</label>
            <select name="paymentMethod" 
                class="w-full bg-background border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-bold text-primary">
                <option value="" selected>None / Cash</option>
                <option value="GCash" {{ old('paymentMethod') == 'GCash' ? 'selected' : '' }}>GCash</option>
                <option value="Bank" {{ old('paymentMethod') == 'Bank' ? 'selected' : '' }}>Bank Transfer</option>
            </select>
            @error('paymentMethod') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
        </div>
    </div>
</x-admin-form>
