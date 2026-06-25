<x-admin-layout>
    <div class="max-w-4xl">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="font-heading text-3xl font-bold text-primary italic">General Settings</h1>
                <p class="text-sm text-muted-foreground mt-1">Configure parish information and donation details.</p>
            </div>
        </div>


        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            @if ($errors->any())
                <div class="p-4 bg-red-100 text-red-700 border border-red-200 rounded-xl text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
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
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Payment QR Code</label>
                        <div class="flex items-center gap-6 p-4 bg-muted/10 rounded-xl border border-dashed">
                            @if(isset($settings['qr_code']))
                                <img src="{{ \Illuminate\Support\Facades\Storage::disk('supabase')->url($settings['qr_code']) }}" class="h-32 w-32 object-contain border rounded bg-white shadow-sm" />
                            @else
                                <div class="h-32 w-32 flex items-center justify-center bg-white border rounded text-muted-foreground text-[10px] uppercase font-black text-center px-4">No QR Uploaded</div>
                            @endif
                            <div class="flex-1">
                                <input type="file" name="qr_code" class="text-xs text-muted-foreground file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:opacity-90">
                                <p class="mt-2 text-[10px] text-muted-foreground">Upload your GCash QR code for easier donations. Max 2MB (JPG/PNG).</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leadership Settings -->
            <div class="bg-card rounded-2xl border shadow-sm overflow-hidden">
                <div class="p-6 border-b bg-muted/30">
                    <h3 class="text-xs font-black uppercase tracking-widest text-primary italic">Leadership Information</h3>
                </div>
                <div class="p-6 grid gap-6 md:grid-cols-2">
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Parish Priest Name</label>
                        <input type="text" name="priest_name" value="{{ $settings['priest_name'] ?? '' }}" placeholder="Rev. Fr. John Doe" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Parish Priest Image</label>
                        <div class="flex items-center gap-6 p-4 bg-muted/10 rounded-xl border border-dashed">
                            <div id="priest_preview_container" class="h-32 w-32 relative rounded-full overflow-hidden border bg-white shadow-sm flex items-center justify-center">
                                @if(isset($settings['priest_image']))
                                    <img id="priest_preview_image" src="{{ \Illuminate\Support\Facades\Storage::disk('supabase')->url($settings['priest_image']) }}" class="h-full w-full object-cover" />
                                    <span id="priest_no_image_text" class="hidden text-muted-foreground text-[10px] font-black text-center px-4">No Image</span>
                                @else
                                    <img id="priest_preview_image" src="" class="hidden h-full w-full object-cover" />
                                    <span id="priest_no_image_text" class="text-muted-foreground text-[10px] font-black text-center px-4">No Image</span>
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" name="priest_image" id="priest_image_input" accept="image/*" class="text-xs text-muted-foreground file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:opacity-90">
                                <p class="mt-2 text-[10px] text-muted-foreground">Upload an image of the current Parish Priest to be shown on the About section. Max 2MB (JPG/PNG).</p>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gallery Settings -->
            <div class="bg-card rounded-2xl border shadow-sm overflow-hidden mb-6">
                <div class="p-6 border-b bg-muted/30">
                    <h3 class="text-xs font-black uppercase tracking-widest text-primary italic">Gallery Settings</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Main Gallery Highlights Video (YouTube URL or Cloud Path)</label>
                        <input type="text" name="gallery_highlights_video" value="{{ $settings['gallery_highlights_video'] ?? '' }}" placeholder="https://www.youtube.com/watch?v=..." class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                        <p class="text-[10px] text-muted-foreground italic">This video will be featured at the top of the main Gallery index page.</p>
                    </div>
                </div>
            </div>

            <!-- Email Template Settings -->
            <div class="bg-card rounded-2xl border shadow-sm overflow-hidden mb-6">
                <div class="p-6 border-b bg-muted/30">
                    <h3 class="text-xs font-black uppercase tracking-widest text-primary italic">Email Templates</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Email Greeting</label>
                        <input type="text" name="email_greeting" value="{{ $settings['email_greeting'] ?? 'Peace be with you!' }}" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                        <p class="text-[10px] text-muted-foreground italic">The greeting phrase used in email notifications (e.g., "Peace be with you!").</p>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Email Closing Message</label>
                        <input type="text" name="email_closing" value="{{ $settings['email_closing'] ?? 'Thank you for your faith and patience.' }}" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                        <p class="text-[10px] text-muted-foreground italic">The closing line before the sign-off.</p>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Email Sign-off Name</label>
                        <input type="text" name="email_signoff" value="{{ $settings['email_signoff'] ?? 'Sto. Rosario Parish' }}" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                        <p class="text-[10px] text-muted-foreground italic">The name used at the end of emails (e.g., "Sto. Rosario Parish" or "Parish Office").</p>
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
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const qrInput = document.querySelector('input[name="qr_code"]');
            const priestInput = document.querySelector('input[name="priest_image"]');
            const maxMB = 1.8; // Safer margin for 2M PHP limit

            if (qrInput && qrInput.files.length > 0 && qrInput.files[0].size > maxMB * 1024 * 1024) {
                e.preventDefault();
                alert(`The QR Code image is too large. Maximum size is ${maxMB}MB.`);
                return;
            }

            if (priestInput && priestInput.files.length > 0 && priestInput.files[0].size > maxMB * 1024 * 1024) {
                e.preventDefault();
                alert(`The Parish Priest Image is too large. Maximum size is ${maxMB}MB.`);
                return;
            }
        });

        const priestImageInput = document.getElementById('priest_image_input');
        if(priestImageInput) {
            priestImageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const img = document.getElementById('priest_preview_image');
                        const placeholder = document.getElementById('priest_no_image_text');
                        img.src = event.target.result;
                        img.classList.remove('hidden');
                        placeholder.classList.add('hidden');
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    </script>
</x-admin-layout>
