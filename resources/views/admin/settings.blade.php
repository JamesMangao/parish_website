<x-admin-layout>
    @php
        $disk = config('filesystems.default');
        $qrUrl = isset($settings['qr_code']) ? \Illuminate\Support\Facades\Storage::disk($disk)->url($settings['qr_code']) : null;
        $priestUrl = isset($settings['priest_image']) ? \Illuminate\Support\Facades\Storage::disk($disk)->url($settings['priest_image']) : null;
        $assistantPriestUrl = isset($settings['assistant_priest_image']) ? \Illuminate\Support\Facades\Storage::disk($disk)->url($settings['assistant_priest_image']) : null;

        $contactRaw = $settings['parish_contact'] ?? '';
        $contactNumbers = is_string($contactRaw) && $contactRaw !== ''
            ? (json_decode($contactRaw, true) ?: [$contactRaw])
            : (is_array($contactRaw) ? $contactRaw : ['']);

        $timelineRaw = $settings['parish_timeline'] ?? '[]';
        $timelineEntries = is_string($timelineRaw) ? (json_decode($timelineRaw, true) ?: []) : (is_array($timelineRaw) ? $timelineRaw : []);
        if (empty($timelineEntries)) {
            $timelineEntries = \App\Data\DefaultTimeline::entries();
        }

        $formerPriestsRaw = $settings['former_priests'] ?? '[]';
        $formerPriestsDb = is_string($formerPriestsRaw) ? (json_decode($formerPriestsRaw, true) ?: []) : (is_array($formerPriestsRaw) ? $formerPriestsRaw : []);
        $formerPriestsForJs = collect($formerPriestsDb)->map(fn ($e) => [
            'name' => $e['name'] ?? '',
            'role' => $e['role'] ?? 'Parish Priest',
            'years' => $e['years'] ?? '',
            'quote' => $e['quote'] ?? '',
            'image' => $e['image'] ?? '',
            'imageUrl' => ! empty($e['image']) ? \Illuminate\Support\Facades\Storage::disk($disk)->url($e['image']) : null,
        ])->values()->all();
    @endphp

    <script type="application/json" id="settings-previews-data">{!! json_encode(['qrUrl' => $qrUrl, 'priestUrl' => $priestUrl, 'assistantPriestUrl' => $assistantPriestUrl]) !!}</script>
    <script type="application/json" id="contact-numbers-data">{!! json_encode($contactNumbers) !!}</script>
    <script type="application/json" id="timeline-entries-data">{!! json_encode($timelineEntries) !!}</script>
    <script type="application/json" id="former-priests-data">{!! json_encode($formerPriestsForJs) !!}</script>

    <div class="max-w-4xl space-y-6" x-data="settingsForm()">
        <div class="flex items-center justify-between mb-2">
            <div>
                <h1 class="font-heading text-3xl font-bold text-primary italic">General Settings</h1>
                <p class="text-sm text-muted-foreground mt-1">Each section saves independently when you make changes.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-sm text-green-800 font-medium">
                {{ session('success') }}
            </div>
        @endif

        {{-- Parish Information --}}
        <form action="{{ route('admin.settings.section.update', 'parish') }}" method="POST" x-data="settingsSection()">
            @csrf
            <x-admin-card>
                <h3 class="text-xs font-black uppercase tracking-widest text-primary italic mb-6">Parish Information</h3>
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Parish Name</label>
                        <input type="text" name="parish_name" value="{{ $settings['parish_name'] ?? '' }}" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                        @error('parish_name') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Contact Numbers</label>
                        <div x-data="contactNumbers()">
                            <template x-for="(number, index) in numbers" :key="index">
                                <div class="flex gap-2 mb-2">
                                    <input type="text" :name="'parish_contact['+index+']'" x-model="numbers[index]" placeholder="+63 2 8869 2742"
                                        class="flex-1 bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                                    <button type="button" @click="removeNumber(index)" x-show="numbers.length > 1"
                                        class="px-3 py-2 bg-destructive/10 text-destructive rounded-md hover:bg-destructive hover:text-white transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                    </button>
                                </div>
                            </template>
                            <button type="button" @click="addNumber()" class="text-xs font-bold text-primary hover:underline flex items-center gap-1 mt-1" :class="numbers.length >= 10 && 'opacity-40 pointer-events-none'">
                                + Add Another Number
                            </button>
                        </div>
                        @error('parish_contact') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Full Address</label>
                        <textarea name="parish_address" rows="2" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">{{ $settings['parish_address'] ?? '' }}</textarea>
                        @error('parish_address') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Official Email</label>
                        <input type="email" name="parish_email" value="{{ $settings['parish_email'] ?? '' }}" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                        @error('parish_email') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <x-settings-section-footer label="Save Parish Information" />
            </x-admin-card>
        </form>

        {{-- Donations --}}
        <form action="{{ route('admin.settings.section.update', 'donations') }}" method="POST" enctype="multipart/form-data" x-data="settingsSection()" @submit="$root.revokePreviews()">
            @csrf
            <x-admin-card>
                <h3 class="text-xs font-black uppercase tracking-widest text-primary italic mb-6">Donation & Payments (GCash)</h3>
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">GCash Number</label>
                        <input type="text" name="gcash_number" value="{{ $settings['gcash_number'] ?? '' }}" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                        @error('gcash_number') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">GCash Account Name</label>
                        <input type="text" name="gcash_name" value="{{ $settings['gcash_name'] ?? '' }}" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                        @error('gcash_name') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Payment QR Code</label>
                        <div class="flex items-center gap-6 p-4 bg-muted/10 rounded-xl border border-dashed">
                            <div class="h-32 w-32 shrink-0 rounded bg-white border shadow-sm flex items-center justify-center overflow-hidden">
                                <template x-if="$root.qrPreview"><img :src="$root.qrPreview" class="h-full w-full object-contain" /></template>
                                <template x-if="!$root.qrPreview"><span class="text-muted-foreground text-[10px] uppercase font-black text-center px-4">No QR</span></template>
                            </div>
                            <div class="flex-1">
                                <input type="file" name="qr_code" accept="image/*" @change="$root.handleFileUpload($event, 'qrPreview', 'QR Code image')"
                                    class="w-full text-xs text-muted-foreground file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white">
                                <p class="mt-2 text-[10px] text-muted-foreground">Max 1.8MB (JPG/PNG).</p>
                            </div>
                        </div>
                        @error('qr_code') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <x-settings-section-footer label="Save Donation Settings" />
            </x-admin-card>
        </form>

        {{-- Leadership --}}
        <form action="{{ route('admin.settings.section.update', 'leadership') }}" method="POST" enctype="multipart/form-data" x-data="settingsSection()" @submit="$root.revokePreviews()">
            @csrf
            <x-admin-card>
                <h3 class="text-xs font-black uppercase tracking-widest text-primary italic mb-6">Leadership Information</h3>
                <div class="grid gap-6 md:grid-cols-2 mb-8">
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Parish Priest Name</label>
                        <input type="text" name="priest_name" value="{{ $settings['priest_name'] ?? '' }}" placeholder="Rev. Fr. John Doe" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                        @error('priest_name') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Role / Title</label>
                        <input type="text" name="priest_role" value="{{ $settings['priest_role'] ?? '' }}" placeholder="Parish Priest · 2019–Present" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Quote</label>
                        <input type="text" name="priest_quote" value="{{ $settings['priest_quote'] ?? '' }}" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Parish Priest Image</label>
                        <div class="flex items-center gap-6 p-4 bg-muted/10 rounded-xl border border-dashed">
                            <div class="h-32 w-32 shrink-0 rounded-full overflow-hidden border bg-white shadow-sm flex items-center justify-center">
                                <template x-if="$root.priestPreview"><img :src="$root.priestPreview" class="h-full w-full object-cover" /></template>
                                <template x-if="!$root.priestPreview"><span class="text-muted-foreground text-[10px] font-black">No Image</span></template>
                            </div>
                            <input type="file" name="priest_image" accept="image/*" @change="$root.handleFileUpload($event, 'priestPreview', 'Priest image')"
                                class="flex-1 text-xs file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white">
                        </div>
                    </div>
                </div>
                <div style="height:1px; background:linear-gradient(90deg,rgba(var(--primary-rgb),0.15),transparent); margin-bottom:24px;"></div>
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Assistant Parish Priest Name</label>
                        <input type="text" name="assistant_priest_name" value="{{ $settings['assistant_priest_name'] ?? '' }}" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Role / Title</label>
                        <input type="text" name="assistant_priest_role" value="{{ $settings['assistant_priest_role'] ?? '' }}" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Quote</label>
                        <input type="text" name="assistant_priest_quote" value="{{ $settings['assistant_priest_quote'] ?? '' }}" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Assistant Parish Priest Image</label>
                        <div class="flex items-center gap-6 p-4 bg-muted/10 rounded-xl border border-dashed">
                            <div class="h-32 w-32 shrink-0 rounded-full overflow-hidden border bg-white shadow-sm flex items-center justify-center">
                                <template x-if="$root.assistantPriestPreview"><img :src="$root.assistantPriestPreview" class="h-full w-full object-cover" /></template>
                                <template x-if="!$root.assistantPriestPreview"><span class="text-muted-foreground text-[10px] font-black">No Image</span></template>
                            </div>
                            <input type="file" name="assistant_priest_image" accept="image/*" @change="$root.handleFileUpload($event, 'assistantPriestPreview', 'Assistant priest image')"
                                class="flex-1 text-xs file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white">
                        </div>
                    </div>
                </div>
                <x-settings-section-footer label="Save Leadership" />
            </x-admin-card>
        </form>

        {{-- Former Parish Priests --}}
        <form action="{{ route('admin.settings.section.update', 'former_priests') }}" method="POST" enctype="multipart/form-data" x-data="settingsSection()">
            @csrf
            <x-admin-card>
                <h3 class="text-xs font-black uppercase tracking-widest text-primary italic mb-2">Former Parish Priests</h3>
                <p class="text-[10px] text-muted-foreground mb-6">Displayed on the About page below the current leadership.</p>
                <div x-data="formerPriestsManager()">
                    <template x-for="(entry, index) in entries" :key="index">
                        <div class="border border-border rounded-lg p-4 mb-4 bg-background/50">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-bold text-muted-foreground" x-text="'Priest ' + (index + 1)"></span>
                                <div class="flex items-center gap-1">
                                    <button type="button" @click="moveUp(index)" :disabled="index === 0" class="p-1 rounded hover:bg-muted disabled:opacity-30"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m18 15-6-6-6 6"/></svg></button>
                                    <button type="button" @click="moveDown(index)" :disabled="index >= entries.length - 1" class="p-1 rounded hover:bg-muted disabled:opacity-30"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg></button>
                                    <button type="button" @click="removeEntry(index)" class="p-1 rounded bg-destructive/10 text-destructive hover:bg-destructive hover:text-white"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg></button>
                                </div>
                            </div>
                            <input type="hidden" :name="'former_priests['+index+'][existing_image]'" x-model="entries[index].existing_image">
                            <div class="grid gap-3 md:grid-cols-2 mb-3">
                                <div class="space-y-1 md:col-span-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Name</label>
                                    <input type="text" :name="'former_priests['+index+'][name]'" x-model="entries[index].name" placeholder="Rev. Fr. John Doe"
                                        class="w-full bg-muted/20 border-border rounded-lg px-3 py-1.5 text-sm">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Role</label>
                                    <input type="text" :name="'former_priests['+index+'][role]'" x-model="entries[index].role" placeholder="Parish Priest"
                                        class="w-full bg-muted/20 border-border rounded-lg px-3 py-1.5 text-sm">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Years Served</label>
                                    <input type="text" :name="'former_priests['+index+'][years]'" x-model="entries[index].years" placeholder="1990–2005"
                                        class="w-full bg-muted/20 border-border rounded-lg px-3 py-1.5 text-sm">
                                </div>
                                <div class="space-y-1 md:col-span-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Quote (optional)</label>
                                    <input type="text" :name="'former_priests['+index+'][quote]'" x-model="entries[index].quote"
                                        class="w-full bg-muted/20 border-border rounded-lg px-3 py-1.5 text-sm">
                                </div>
                                <div class="space-y-1 md:col-span-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Photo (optional)</label>
                                    <div class="flex items-center gap-4">
                                        <div class="h-16 w-16 rounded-full overflow-hidden border bg-muted/20 flex items-center justify-center shrink-0">
                                            <template x-if="entries[index].imagePreview">
                                                <img :src="entries[index].imagePreview" class="h-full w-full object-cover">
                                            </template>
                                            <template x-if="!entries[index].imagePreview">
                                                <span class="text-xs font-black text-primary" x-text="initials(entries[index].name)"></span>
                                            </template>
                                        </div>
                                        <input type="file" :name="'former_priests['+index+'][image]'" accept="image/*" @change="handleImageUpload($event, index)"
                                            class="flex-1 text-xs file:mr-4 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-white">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <button type="button" @click="addEntry()" class="text-xs font-bold text-primary hover:underline flex items-center gap-1" :class="entries.length >= 20 && 'opacity-40 pointer-events-none'">
                        + Add Former Priest
                    </button>
                </div>
                @error('former_priests') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
                <x-settings-section-footer label="Save Former Priests" />
            </x-admin-card>
        </form>

        {{-- About Page Video --}}
        <form action="{{ route('admin.settings.section.update', 'about_video') }}" method="POST" x-data="settingsSection()">
            @csrf
            <x-admin-card>
                <h3 class="text-xs font-black uppercase tracking-widest text-primary italic mb-2">About Page Video</h3>
                <p class="text-[10px] text-muted-foreground mb-6">YouTube video displayed on the About page between Sacred History and Leadership.</p>
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">YouTube URL</label>
                        <input type="url" name="about_video_url" value="{{ $settings['about_video_url'] ?? '' }}" placeholder="https://youtu.be/WvypjBETp-o"
                            class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                        @error('about_video_url') <p class="text-xs text-destructive mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Section Title (optional)</label>
                        <input type="text" name="about_video_title" value="{{ $settings['about_video_title'] ?? '' }}" placeholder="Parish Story"
                            class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Description (optional)</label>
                        <textarea name="about_video_description" rows="2" placeholder="A brief introduction to the video..."
                            class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">{{ $settings['about_video_description'] ?? '' }}</textarea>
                    </div>
                </div>
                <x-settings-section-footer label="Save About Video" />
            </x-admin-card>
        </form>

        {{-- Timeline --}}
        <form action="{{ route('admin.settings.section.update', 'timeline') }}" method="POST" x-data="settingsSection()">
            @csrf
            <x-admin-card>
                <h3 class="text-xs font-black uppercase tracking-widest text-primary italic mb-6">Sacred History Timeline</h3>
                <div x-data="timelineManager()">
                    <template x-for="(entry, index) in entries" :key="index">
                        <div class="border border-border rounded-lg p-4 mb-4 bg-background/50">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-bold text-muted-foreground" x-text="'Entry ' + (index + 1)"></span>
                                <div class="flex items-center gap-1">
                                    <button type="button" @click="moveUp(index)" :disabled="index === 0" class="p-1 rounded hover:bg-muted disabled:opacity-30"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m18 15-6-6-6 6"/></svg></button>
                                    <button type="button" @click="moveDown(index)" :disabled="index >= entries.length - 1" class="p-1 rounded hover:bg-muted disabled:opacity-30"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg></button>
                                    <button type="button" @click="removeEntry(index)" class="p-1 rounded bg-destructive/10 text-destructive hover:bg-destructive hover:text-white"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg></button>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3 mb-3">
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Year</label>
                                    <input type="text" :name="'parish_timeline['+index+'][year]'" x-model="entries[index].year" class="w-full bg-muted/20 border-border rounded-lg px-3 py-1.5 text-sm">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Badge</label>
                                    <input type="text" :name="'parish_timeline['+index+'][badge]'" x-model="entries[index].badge" class="w-full bg-muted/20 border-border rounded-lg px-3 py-1.5 text-sm">
                                </div>
                            </div>
                            <div class="space-y-1 mb-3">
                                <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Title</label>
                                <input type="text" :name="'parish_timeline['+index+'][title]'" x-model="entries[index].title" class="w-full bg-muted/20 border-border rounded-lg px-3 py-1.5 text-sm">
                            </div>
                            <div class="space-y-1 mb-3">
                                <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Short Description</label>
                                <textarea :name="'parish_timeline['+index+'][short]'" x-model="entries[index].short" rows="2" class="w-full bg-muted/20 border-border rounded-lg px-3 py-1.5 text-sm"></textarea>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Full Description</label>
                                <textarea :name="'parish_timeline['+index+'][full]'" x-model="entries[index].full" rows="2" class="w-full bg-muted/20 border-border rounded-lg px-3 py-1.5 text-sm"></textarea>
                            </div>
                        </div>
                    </template>
                    <button type="button" @click="addEntry()" class="text-xs font-bold text-primary hover:underline flex items-center gap-1">+ Add Timeline Entry</button>
                </div>
                <x-settings-section-footer label="Save Timeline" />
            </x-admin-card>
        </form>

        {{-- Gallery --}}
        <form action="{{ route('admin.settings.section.update', 'gallery') }}" method="POST" x-data="settingsSection()">
            @csrf
            <x-admin-card>
                <h3 class="text-xs font-black uppercase tracking-widest text-primary italic mb-6">Gallery Settings</h3>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Main Gallery Highlights Video</label>
                    <input type="text" name="gallery_highlights_video" value="{{ $settings['gallery_highlights_video'] ?? '' }}" placeholder="https://www.youtube.com/watch?v=..."
                        class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm focus:ring-accent focus:border-accent">
                    <p class="text-[10px] text-muted-foreground italic">Legacy setting — gallery hero uses Video Highlights admin instead.</p>
                </div>
                <x-settings-section-footer label="Save Gallery Settings" />
            </x-admin-card>
        </form>

        {{-- Email --}}
        <form action="{{ route('admin.settings.section.update', 'email') }}" method="POST" x-data="settingsSection()">
            @csrf
            <x-admin-card>
                <h3 class="text-xs font-black uppercase tracking-widest text-primary italic mb-6">Email Templates</h3>
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Email Greeting</label>
                        <input type="text" name="email_greeting" value="{{ $settings['email_greeting'] ?? 'Peace be with you!' }}" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Email Closing Message</label>
                        <input type="text" name="email_closing" value="{{ $settings['email_closing'] ?? 'Thank you for your faith and patience.' }}" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase tracking-widest text-muted-foreground">Email Sign-off Name</label>
                        <input type="text" name="email_signoff" value="{{ $settings['email_signoff'] ?? 'Sto. Rosario Parish' }}" class="w-full bg-muted/20 border-border rounded-lg px-4 py-2 text-sm">
                    </div>
                </div>
                <x-settings-section-footer label="Save Email Templates" />
            </x-admin-card>
        </form>
    </div>
</x-admin-layout>
