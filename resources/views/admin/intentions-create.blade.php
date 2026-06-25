<x-admin-form 
    title="Create Mass Intention" 
    description="Manually add a mass intention to the parish record."
    backRoute="{{ route('admin.intentions') }}"
    action="{{ route('admin.intentions.store') }}"
    submitLabel="Create Intention"
>
    <div class="space-y-4">
        @if(session('duplicate_warning'))
        <div class="p-4 rounded-xl border-2 border-amber-200 bg-amber-50/80 flex items-start gap-3">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2" class="shrink-0 mt-0.5">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
            <div class="flex-1">
                <p class="text-sm font-bold text-amber-800">Duplicate intention detected</p>
                <p class="text-xs text-amber-600/70 mt-1">
                    A pending <strong>{{ session('duplicate_type') }}</strong> intention already exists for this name (Ref: {{ session('duplicate_ref') }}).
                    You may create it anyway, but consider reviewing the existing one first.
                </p>
                <div class="flex gap-2 mt-3">
                    <a href="{{ route('admin.intentions', ['status' => 'pending']) }}" class="px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest border border-amber-300 text-amber-700 hover:bg-amber-100 transition-all" style="text-decoration:none;">View Pending</a>
                </div>
            </div>
        </div>
        <input type="hidden" name="force_submit" value="1">
        @endif

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
