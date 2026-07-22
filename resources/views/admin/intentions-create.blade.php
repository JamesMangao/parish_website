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
            <label class="block text-xs font-black uppercase tracking-widest text-muted-foreground mb-2">Full Name <span class="text-destructive">*</span></label>
            <input type="text" name="fullName" value="{{ old('fullName') }}" required
                class="w-full bg-background border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-bold text-primary"
                placeholder="e.g., Juan Dela Cruz">
            @error('fullName')
                <p class="text-xs text-destructive mt-1.5 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-muted-foreground mb-2">Intention Type <span class="text-destructive">*</span></label>
                <select name="intentionType" required
                    class="w-full bg-background border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-bold text-primary">
                    <option value="Thanksgiving" {{ old('intentionType') == 'Thanksgiving' ? 'selected' : '' }}>Thanksgiving</option>
                    <option value="Birthday" {{ old('intentionType') == 'Birthday' ? 'selected' : '' }}>Birthday</option>
                    <option value="Wedding Anniversary" {{ old('intentionType') == 'Wedding Anniversary' ? 'selected' : '' }}>Wedding Anniversary</option>
                    <option value="Healing" {{ old('intentionType') == 'Healing' ? 'selected' : '' }}>Healing / Recovery</option>
                    <option value="Special Intention" {{ old('intentionType') == 'Special Intention' ? 'selected' : '' }}>Special Intention</option>
                    <option value="Repose of Soul" {{ old('intentionType') == 'Repose of Soul' ? 'selected' : '' }}>Repose of Soul</option>
                </select>
                @error('intentionType')
                    <p class="text-xs text-destructive mt-1.5 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>
            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-muted-foreground mb-2">Preferred Date</label>
                <input type="date" name="preferredDate" value="{{ old('preferredDate') }}"
                    class="w-full bg-background border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-bold text-primary">
                @error('preferredDate')
                    <p class="text-xs text-destructive mt-1.5 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        <div>
            <label class="block text-xs font-black uppercase tracking-widest text-muted-foreground mb-2">Mass Time</label>
            <select name="massTime"
                class="w-full bg-background border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-bold text-primary">
                <option value="">No preference</option>
                <option value="6:00 AM" {{ old('massTime') == '6:00 AM' ? 'selected' : '' }}>6:00 AM</option>
                <option value="7:00 AM" {{ old('massTime') == '7:00 AM' ? 'selected' : '' }}>7:00 AM</option>
                <option value="8:00 AM" {{ old('massTime') == '8:00 AM' ? 'selected' : '' }}>8:00 AM</option>
                <option value="9:00 AM" {{ old('massTime') == '9:00 AM' ? 'selected' : '' }}>9:00 AM</option>
                <option value="10:00 AM" {{ old('massTime') == '10:00 AM' ? 'selected' : '' }}>10:00 AM</option>
                <option value="11:00 AM" {{ old('massTime') == '11:00 AM' ? 'selected' : '' }}>11:00 AM</option>
                <option value="12:00 NN" {{ old('massTime') == '12:00 NN' ? 'selected' : '' }}>12:00 NN</option>
                <option value="5:00 PM" {{ old('massTime') == '5:00 PM' ? 'selected' : '' }}>5:00 PM</option>
                <option value="6:00 PM" {{ old('massTime') == '6:00 PM' ? 'selected' : '' }}>6:00 PM</option>
            </select>
            @error('massTime')
                <p class="text-xs text-destructive mt-1.5 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-black uppercase tracking-widest text-muted-foreground mb-2">Description / Special Request <span class="text-destructive">*</span></label>
            <textarea name="description" rows="3" required
                class="w-full bg-background border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-medium text-primary"
                placeholder="e.g., For the speedy recovery of...">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-xs text-destructive mt-1.5 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div>
            <label class="block text-xs font-black uppercase tracking-widest text-muted-foreground mb-2">Payment Method</label>
            <select name="paymentMethod"
                class="w-full bg-background border rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all font-bold text-primary">
                <option value="" {{ old('paymentMethod') == '' ? 'selected' : '' }}>Select payment method</option>
                <option value="GCash" {{ old('paymentMethod') == 'GCash' ? 'selected' : '' }}>GCash</option>
                <option value="Cash" {{ old('paymentMethod') == 'Cash' ? 'selected' : '' }}>Cash</option>
            </select>
            @error('paymentMethod')
                <p class="text-xs text-destructive mt-1.5 flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>
    </div>
</x-admin-form>
