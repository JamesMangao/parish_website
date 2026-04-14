<x-admin-layout>
    <div class="max-w-4xl">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="font-heading text-3xl font-bold text-primary italic">General Settings</h1>
                <p class="text-sm text-muted-foreground mt-1">Configure parish information and donation details.</p>
            </div>
        </div>


        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Parish Information -->
            <div class="bg-card rounded-2xl border shadow-sm overflow-hidden">
                <div class="p-6 border-b bg-muted/30">
                    <h3 class="text-xs font-black uppercase tracking-widest text-primary italic">Parish Information</h3>
                </div>
                <div class="p-6 grid gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Parish Name</label>
                        <input type="text" name="parish_name" value="{{ $settings['parish_name'] ?? '' }}" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Contact Number</label>
                        <input type="text" name="parish_contact" value="{{ $settings['parish_contact'] ?? '' }}" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Full Address</label>
                        <textarea name="parish_address" rows="2" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">{{ $settings['parish_address'] ?? '' }}</textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Official Email</label>
                        <input type="email" name="parish_email" value="{{ $settings['parish_email'] ?? '' }}" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                    </div>
                </div>
            </div>

            <!-- Donation Settings -->
            <div class="bg-card rounded-2xl border shadow-sm overflow-hidden">
                <div class="p-6 border-b bg-muted/30">
                    <h3 class="text-xs font-black uppercase tracking-widest text-primary italic">Donation & Payments (GCash)</h3>
                </div>
                <div class="p-6 grid gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">GCash Number</label>
                        <input type="text" name="gcash_number" value="{{ $settings['gcash_number'] ?? '' }}" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">GCash Account Name</label>
                        <input type="text" name="gcash_name" value="{{ $settings['gcash_name'] ?? '' }}" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="px-8 py-3 bg-accent text-accent-foreground rounded-xl font-black text-sm shadow-xl hover:scale-[1.02] transition-all flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
