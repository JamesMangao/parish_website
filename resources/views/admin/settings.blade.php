<x-admin-layout>
    @php
        $qrUrl = isset($settings['qr_code']) ? \Illuminate\Support\Facades\Storage::disk('supabase')->url($settings['qr_code']) : null;
        $priestUrl = isset($settings['priest_image']) ? \Illuminate\Support\Facades\Storage::disk('supabase')->url($settings['priest_image']) : null;
        $assistantPriestUrl = isset($settings['assistant_priest_image']) ? \Illuminate\Support\Facades\Storage::disk('supabase')->url($settings['assistant_priest_image']) : null;
    @endphp
    <script type="application/json" id="settings-previews-data">{!! json_encode(['qrUrl' => $qrUrl, 'priestUrl' => $priestUrl, 'assistantPriestUrl' => $assistantPriestUrl]) !!}</script>
    <div class="max-w-4xl" x-data="settingsForm()">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="font-heading text-3xl font-bold text-primary italic">General Settings</h1>
                <p class="text-sm text-muted-foreground mt-1">Configure parish information and donation details.</p>
            </div>
        </div>

        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6" @submit="revokePreviews()">
            @csrf

            {{-- Parish Information --}}
            <x-admin-card>
                <h3 class="text-xs font-black uppercase tracking-widest text-primary italic mb-6">Parish Information</h3>
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Parish Name</label>
                        <input type="text" name="parish_name" value="{{ $settings['parish_name'] ?? '' }}" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                        @error('parish_name') <p class="text-xs text-destructive mt-1 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg> {{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        @php
                            $contactRaw = $settings['parish_contact'] ?? '';
                            $contactNumbers = is_string($contactRaw) && $contactRaw !== ''
                                ? (json_decode($contactRaw, true) ?: [$contactRaw])
                                : (is_array($contactRaw) ? $contactRaw : ['']);
                        @endphp
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Contact Numbers</label>
                        <script type="application/json" id="contact-numbers-data">{!! json_encode($contactNumbers) !!}</script>
                        <div x-data="contactNumbers()">
                            <template x-for="(number, index) in numbers" :key="index">
                                <div class="flex gap-2 mb-2">
                                    <input type="text" :name="'parish_contact['+index+']'" x-model="numbers[index]" placeholder="+63 2 8869 2742"
                                        class="flex-1 bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                                    <button type="button" @click="removeNumber(index)" x-show="numbers.length > 1"
                                        class="px-3 py-2 bg-destructive/10 text-destructive rounded-md hover:bg-destructive hover:text-white transition-colors" title="Remove Number">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                    </button>
                                </div>
                            </template>
                            <button type="button" @click="addNumber(10)" class="text-xs font-bold text-primary hover:underline flex items-center gap-1 mt-1" :class="numbers.length >= 10 && 'opacity-40 pointer-events-none'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                <span x-text="numbers.length >= 10 ? 'Max limit reached' : 'Add Another Number'"></span>
                            </button>
                        </div>
                        @error('parish_contact') <p class="text-xs text-destructive mt-1 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg> {{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Full Address</label>
                        <textarea name="parish_address" rows="2" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">{{ $settings['parish_address'] ?? '' }}</textarea>
                        @error('parish_address') <p class="text-xs text-destructive mt-1 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg> {{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Official Email</label>
                        <input type="email" name="parish_email" value="{{ $settings['parish_email'] ?? '' }}" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                        @error('parish_email') <p class="text-xs text-destructive mt-1 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg> {{ $message }}</p> @enderror
                    </div>
                </div>
            </x-admin-card>

            {{-- Donation Settings --}}
            <x-admin-card>
                <h3 class="text-xs font-black uppercase tracking-widest text-primary italic mb-6">Donation & Payments (GCash)</h3>
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">GCash Number</label>
                        <input type="text" name="gcash_number" value="{{ $settings['gcash_number'] ?? '' }}" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                        @error('gcash_number') <p class="text-xs text-destructive mt-1 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg> {{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">GCash Account Name</label>
                        <input type="text" name="gcash_name" value="{{ $settings['gcash_name'] ?? '' }}" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                        @error('gcash_name') <p class="text-xs text-destructive mt-1 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg> {{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Payment QR Code</label>
                        <div class="flex items-center gap-6 p-4 bg-muted/10 rounded-xl border border-dashed">
                            <div class="h-32 w-32 shrink-0 rounded bg-white border shadow-sm flex items-center justify-center overflow-hidden">
                                <template x-if="qrPreview">
                                    <img :src="qrPreview" class="h-full w-full object-contain" />
                                </template>
                                <template x-if="!qrPreview">
                                    <span class="text-muted-foreground text-[10px] uppercase font-black text-center px-4">No QR Uploaded</span>
                                </template>
                            </div>
                            <div class="flex-1">
                                <input type="file" name="qr_code" accept="image/*" @change="handleFileUpload($event, 'qrPreview', 'QR Code image')"
                                    class="w-full text-xs text-muted-foreground file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:opacity-90">
                                <p class="mt-2 text-[10px] text-muted-foreground">Upload your GCash QR code. Max 1.8MB (JPG/PNG).</p>
                            </div>
                        </div>
                        @error('qr_code') <p class="text-xs text-destructive mt-1 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg> {{ $message }}</p> @enderror
                    </div>
                </div>
            </x-admin-card>

            {{-- Leadership Settings --}}
            <x-admin-card>
                <h3 class="text-xs font-black uppercase tracking-widest text-primary italic mb-6">Leadership Information</h3>

                {{-- Parish Priest --}}
                <div class="grid gap-6 md:grid-cols-2 mb-8">
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Parish Priest Name</label>
                        <input type="text" name="priest_name" value="{{ $settings['priest_name'] ?? '' }}" placeholder="Rev. Fr. John Doe" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                        @error('priest_name') <p class="text-xs text-destructive mt-1 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg> {{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Role / Title</label>
                        <input type="text" name="priest_role" value="{{ $settings['priest_role'] ?? '' }}" placeholder="Parish Priest · 2019–Present" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                        @error('priest_role') <p class="text-xs text-destructive mt-1 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg> {{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Quote</label>
                        <input type="text" name="priest_quote" value="{{ $settings['priest_quote'] ?? '' }}" placeholder="Feeding the sheep and tending the flock..." class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                        @error('priest_quote') <p class="text-xs text-destructive mt-1 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg> {{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Parish Priest Image</label>
                        <div class="flex items-center gap-6 p-4 bg-muted/10 rounded-xl border border-dashed">
                            <div class="h-32 w-32 shrink-0 relative rounded-full overflow-hidden border bg-white shadow-sm flex items-center justify-center">
                                <template x-if="priestPreview">
                                    <img :src="priestPreview" class="h-full w-full object-cover" />
                                </template>
                                <template x-if="!priestPreview">
                                    <span class="text-muted-foreground text-[10px] font-black text-center px-4">No Image</span>
                                </template>
                            </div>
                            <div class="flex-1">
                                <input type="file" name="priest_image" accept="image/*" @change="handleFileUpload($event, 'priestPreview', 'Priest image')"
                                    class="w-full text-xs text-muted-foreground file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:opacity-90">
                                <p class="mt-2 text-[10px] text-muted-foreground">Upload an image of the Parish Priest for the About section. Max 1.8MB (JPG/PNG).</p>
                            </div>
                        </div>
                        @error('priest_image') <p class="text-xs text-destructive mt-1 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg> {{ $message }}</p> @enderror
                    </div>
                </div>

                <div style="height:1px; background:linear-gradient(90deg,rgba(var(--primary-rgb),0.15),transparent); margin-bottom:24px;"></div>

                {{-- Assistant Parish Priest --}}
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Assistant Parish Priest Name</label>
                        <input type="text" name="assistant_priest_name" value="{{ $settings['assistant_priest_name'] ?? '' }}" placeholder="Rev. Fr. Jane Smith" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                        @error('assistant_priest_name') <p class="text-xs text-destructive mt-1 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg> {{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Role / Title</label>
                        <input type="text" name="assistant_priest_role" value="{{ $settings['assistant_priest_role'] ?? '' }}" placeholder="Assistant Parish Priest" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                        @error('assistant_priest_role') <p class="text-xs text-destructive mt-1 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg> {{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Quote</label>
                        <input type="text" name="assistant_priest_quote" value="{{ $settings['assistant_priest_quote'] ?? '' }}" placeholder="Serving the community with faith and dedication..." class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                        @error('assistant_priest_quote') <p class="text-xs text-destructive mt-1 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg> {{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Assistant Parish Priest Image</label>
                        <div class="flex items-center gap-6 p-4 bg-muted/10 rounded-xl border border-dashed">
                            <div class="h-32 w-32 shrink-0 relative rounded-full overflow-hidden border bg-white shadow-sm flex items-center justify-center">
                                <template x-if="assistantPriestPreview">
                                    <img :src="assistantPriestPreview" class="h-full w-full object-cover" />
                                </template>
                                <template x-if="!assistantPriestPreview">
                                    <span class="text-muted-foreground text-[10px] font-black text-center px-4">No Image</span>
                                </template>
                            </div>
                            <div class="flex-1">
                                <input type="file" name="assistant_priest_image" accept="image/*" @change="handleFileUpload($event, 'assistantPriestPreview', 'Assistant priest image')"
                                    class="w-full text-xs text-muted-foreground file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white hover:file:opacity-90">
                                <p class="mt-2 text-[10px] text-muted-foreground">Upload an image of the Assistant Parish Priest for the About section. Max 1.8MB (JPG/PNG).</p>
                            </div>
                        </div>
                        @error('assistant_priest_image') <p class="text-xs text-destructive mt-1 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg> {{ $message }}</p> @enderror
                    </div>
                </div>
            </x-admin-card>

            {{-- Sacred History Timeline --}}
            <x-admin-card>
                <h3 class="text-xs font-black uppercase tracking-widest text-primary italic mb-6">Sacred History Timeline</h3>
                @php
                    $timelineRaw = $settings['parish_timeline'] ?? '[]';
                    $timelineEntries = is_string($timelineRaw) ? (json_decode($timelineRaw, true) ?: []) : (is_array($timelineRaw) ? $timelineRaw : []);
                    if (empty($timelineEntries)) {
                        $timelineEntries = \App\Data\DefaultTimeline::entries();
                    }
                @endphp
                <script type="application/json" id="timeline-entries-data">{!! json_encode($timelineEntries) !!}</script>
                <div x-data="timelineManager()">
                    <template x-for="(entry, index) in entries" :key="index">
                        <div class="border border-border rounded-lg p-4 mb-4 bg-background/50">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-bold text-muted-foreground" x-text="'Entry ' + (index + 1)"></span>
                                <div class="flex items-center gap-1">
                                    <button type="button" @click="moveUp(index)" :disabled="index === 0"
                                        class="p-1 rounded hover:bg-muted disabled:opacity-30 disabled:cursor-not-allowed transition-colors" title="Move Up">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
                                    </button>
                                    <button type="button" @click="moveDown(index)" :disabled="index >= entries.length - 1"
                                        class="p-1 rounded hover:bg-muted disabled:opacity-30 disabled:cursor-not-allowed transition-colors" title="Move Down">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                                    </button>
                                    <button type="button" @click="removeEntry(index)"
                                        class="p-1 rounded bg-destructive/10 text-destructive hover:bg-destructive hover:text-white transition-colors" title="Remove Entry">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                    </button>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3 mb-3">
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Year</label>
                                    <input type="text" :name="'parish_timeline['+index+'][year]'" x-model="entries[index].year" placeholder="1986"
                                        class="w-full bg-muted/20 border-border rounded-lg px-3 py-1.5 text-sm focus:ring-accent focus:border-accent">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Badge (optional)</label>
                                    <input type="text" :name="'parish_timeline['+index+'][badge]'" x-model="entries[index].badge" placeholder="Cultural Heritage"
                                        class="w-full bg-muted/20 border-border rounded-lg px-3 py-1.5 text-sm focus:ring-accent focus:border-accent">
                                </div>
                            </div>
                            <div class="space-y-1 mb-3">
                                <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Title</label>
                                <input type="text" :name="'parish_timeline['+index+'][title]'" x-model="entries[index].title" placeholder="Church dedication"
                                    class="w-full bg-muted/20 border-border rounded-lg px-3 py-1.5 text-sm focus:ring-accent focus:border-accent">
                            </div>
                            <div class="space-y-1 mb-3">
                                <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Short Description</label>
                                <textarea :name="'parish_timeline['+index+'][short]'" x-model="entries[index].short" rows="2" placeholder="Always-visible text..."
                                    class="w-full bg-muted/20 border-border rounded-lg px-3 py-1.5 text-sm focus:ring-accent focus:border-accent"></textarea>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Full Description (Read more)</label>
                                <textarea :name="'parish_timeline['+index+'][full]'" x-model="entries[index].full" rows="2" placeholder="Hidden until 'Read more' is clicked..."
                                    class="w-full bg-muted/20 border-border rounded-lg px-3 py-1.5 text-sm focus:ring-accent focus:border-accent"></textarea>
                            </div>
                        </div>
                    </template>
                    <button type="button" @click="addEntry()" class="text-xs font-bold text-primary hover:underline flex items-center gap-1 mt-2" :class="entries.length >= 30 && 'opacity-40 pointer-events-none'">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                        <span x-text="entries.length >= 30 ? 'Max limit reached' : 'Add Timeline Entry'"></span>
                    </button>
                </div>
            </x-admin-card>

            {{-- Gallery Settings --}}
            <x-admin-card>
                <h3 class="text-xs font-black uppercase tracking-widest text-primary italic mb-6">Gallery Settings</h3>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Main Gallery Highlights Video (YouTube URL or Cloud Path)</label>
                    <input type="text" name="gallery_highlights_video" value="{{ $settings['gallery_highlights_video'] ?? '' }}" placeholder="https://www.youtube.com/watch?v=..." class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                    <p class="text-[10px] text-muted-foreground italic">This video will be featured at the top of the main Gallery index page.</p>
                    @error('gallery_highlights_video') <p class="text-xs text-destructive mt-1 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg> {{ $message }}</p> @enderror
                </div>
            </x-admin-card>

            {{-- Email Template Settings --}}
            <x-admin-card>
                <h3 class="text-xs font-black uppercase tracking-widest text-primary italic mb-6">Email Templates</h3>
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Email Greeting</label>
                        <input type="text" name="email_greeting" value="{{ $settings['email_greeting'] ?? 'Peace be with you!' }}" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                        <p class="text-[10px] text-muted-foreground italic">The greeting phrase used in email notifications.</p>
                        @error('email_greeting') <p class="text-xs text-destructive mt-1 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg> {{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Email Closing Message</label>
                        <input type="text" name="email_closing" value="{{ $settings['email_closing'] ?? 'Thank you for your faith and patience.' }}" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                        <p class="text-[10px] text-muted-foreground italic">The closing line before the sign-off.</p>
                        @error('email_closing') <p class="text-xs text-destructive mt-1 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg> {{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Email Sign-off Name</label>
                        <input type="text" name="email_signoff" value="{{ $settings['email_signoff'] ?? 'Sto. Rosario Parish' }}" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                        <p class="text-[10px] text-muted-foreground italic">The name used at the end of emails.</p>
                        @error('email_signoff') <p class="text-xs text-destructive mt-1 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg> {{ $message }}</p> @enderror
                    </div>
                </div>
            </x-admin-card>

            <div class="flex justify-end">
                <button type="submit" class="px-8 py-3 bg-accent text-accent-foreground rounded-xl font-black text-sm shadow-xl hover:scale-[1.02] transition-all flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
