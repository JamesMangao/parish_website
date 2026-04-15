<x-public-layout>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <div x-data="intentionForm()" class="container py-12 mx-auto px-4">
        <div class="text-center mb-10">
            <h1 class="font-heading text-4xl font-bold mb-2 text-primary">Submit Mass Intention</h1>
            <div class="section-divider"></div>
            <p class="text-muted-foreground mt-4 max-w-lg mx-auto italic">
                Offer your prayers through the Holy Mass. Fill out the form below and our parish staff will review your intention.
            </p>
        </div>

        <div class="max-w-lg mx-auto">
            <!-- Success State -->
            <div 
                x-show="submitted" 
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="text-center py-12"
                x-cloak
            >
                <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-accent text-accent-foreground mb-6 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check"><path d="M20 6 9 17l-5-5"/></svg>
                </div>
                <h2 class="font-heading text-3xl font-bold mb-2 text-primary">Submission Received</h2>
                <p class="text-muted-foreground mb-6">Your mass intention has been submitted and is awaiting review.</p>
                
                <div x-show="refId" class="mb-8 p-6 bg-card border-2 border-dashed rounded-2xl relative group">
                    <span class="text-[10px] uppercase font-black tracking-widest text-muted-foreground/60 block mb-2">Reference ID</span>
                    <div class="flex items-center justify-center gap-3">
                        <span class="font-mono text-2xl font-black text-primary tracking-tighter" x-text="refId"></span>
                        <button @click="copyRefId()" type="button" class="p-2 hover:bg-muted rounded-xl transition-all text-accent" title="Copy ID">
                            <span x-show="!refCopied">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                            </span>
                            <span x-show="refCopied" x-cloak class="text-[10px] font-black uppercase tracking-widest">Done!</span>
                        </button>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <a :href="'/track-intention/' + refId" class="w-full py-4 rounded-xl bg-primary text-primary-foreground font-black uppercase tracking-widest shadow-xl hover:-translate-y-1 transition-all">
                        Track My Request
                    </a>
                    <button @click="reset()" class="w-full py-3 rounded-xl border-2 border-primary/10 text-primary/60 font-bold hover:bg-muted transition-colors">
                        Submit Another
                    </button>
                </div>
            </div>

            <!-- Form State -->
            <div x-show="!submitted" class="bg-card rounded-3xl border shadow-xl overflow-hidden animate-in fade-in slide-in-from-bottom-4">
                <div class="p-8 border-b bg-muted/20">
                    <h3 class="font-heading text-xl font-bold text-primary">Intention Details</h3>
                </div>
                <div class="p-8">
                    <form @submit.prevent="submitForm()" class="space-y-6">
                        @csrf
                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-black uppercase tracking-widest text-muted-foreground/70 ml-1" for="fullName">Requester Full Name</label>
                                    <input 
                                        x-model="formData.fullName"
                                        id="fullName" 
                                        name="fullName" 
                                        placeholder="Juan Dela Cruz" 
                                        required 
                                        class="flex h-12 w-full rounded-xl border bg-background px-4 py-2 text-sm focus:ring-2 focus:ring-accent transition-all font-medium"
                                    />
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-black uppercase tracking-widest text-muted-foreground/70 ml-1" for="email">Email Address</label>
                                    <input 
                                        x-model="formData.email"
                                        id="email" 
                                        name="email" 
                                        type="email"
                                        placeholder="juan@example.com" 
                                        required 
                                        class="flex h-12 w-full rounded-xl border bg-background px-4 py-2 text-sm focus:ring-2 focus:ring-accent transition-all font-medium"
                                    />
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-[11px] font-black uppercase tracking-widest text-muted-foreground/70 ml-1" for="intentionType">Intention Category</label>
                                <div class="relative">
                                    <select 
                                        x-model="formData.intentionType"
                                        id="intentionType" 
                                        name="intentionType" 
                                        required
                                        class="flex h-12 w-full rounded-xl border bg-background px-4 py-2 text-sm focus:ring-2 focus:ring-accent transition-all font-medium appearance-none"
                                    >
                                        <option value="" disabled selected>Select category...</option>
                                        <template x-for="type in intentionTypes" :key="type">
                                            <option :value="type" x-text="type"></option>
                                        </template>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-muted-foreground">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down"><path d="m6 9 6 6 6-6"/></svg>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-black uppercase tracking-widest text-muted-foreground/70 ml-1" for="preferredDate">Mass Date</label>
                                    <div class="relative">
                                        <input 
                                            x-ref="datePicker"
                                            x-model="formData.preferredDate"
                                            id="preferredDate" 
                                            name="preferredDate" 
                                            placeholder="Select Date"
                                            required
                                            readonly
                                            class="flex h-12 w-full rounded-xl border bg-background px-4 py-2 text-sm focus:ring-2 focus:ring-accent transition-all font-medium cursor-pointer"
                                        />
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-muted-foreground">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10"/></svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-black uppercase tracking-widest text-muted-foreground/70 ml-1" for="massTime">Service Time</label>
                                    <div class="relative">
                                        <select 
                                            x-model="formData.massTime"
                                            id="massTime" 
                                            name="massTime" 
                                            required
                                            class="flex h-12 w-full rounded-xl border bg-background px-4 py-2 text-sm focus:ring-2 focus:ring-accent transition-all font-medium appearance-none"
                                        >
                                            <option value="" disabled selected>Select time...</option>
                                            <template x-for="time in massTimes" :key="time">
                                                <option :value="time" x-text="time"></option>
                                            </template>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-muted-foreground">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                 <label class="text-[11px] font-black uppercase tracking-widest text-muted-foreground/70 ml-1" for="description">Intention Detail / Names</label>
                                 <textarea 
                                     x-model="formData.description"
                                     id="description" 
                                     name="description" 
                                     rows="3"
                                     required
                                     placeholder="Enter names or specific prayer requests..."
                                     class="flex min-h-[100px] w-full rounded-xl border bg-background px-4 py-2 text-sm focus:ring-2 focus:ring-accent transition-all font-medium resize-none"
                                 ></textarea>
                            </div>
                        </div>

                        <div x-data="{ open: false }" class="space-y-2">
                             <button type="button" @click="open = !open" class="flex items-center justify-between w-full p-4 rounded-xl border border-dashed border-accent/30 bg-accent/5 hover:bg-accent/10 transition-colors">
                                 <div class="flex items-center gap-3">
                                     <div class="h-8 w-8 rounded-full bg-accent flex items-center justify-center text-accent-foreground">
                                         <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 15h2m-2-4h2m-2-4h2M9 22h6a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2Z"/></svg>
                                     </div>
                                     <span class="text-xs font-black uppercase tracking-widest text-primary">Donation Details</span>
                                 </div>
                                 <svg :class="open ? 'rotate-180' : ''" class="transition-transform" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                             </button>
                             
                             <div x-show="open" x-cloak x-transition class="p-4 rounded-xl bg-muted/30 border border-dashed space-y-4">
                                <div class="grid grid-cols-2 gap-2">
                                    <button 
                                        type="button"
                                        @click="formData.paymentMethod = 'GCash'"
                                        :class="formData.paymentMethod === 'GCash' ? 'bg-primary text-primary-foreground shadow-lg' : 'bg-white hover:bg-muted'"
                                        class="p-3 rounded-xl border font-bold text-[10px] uppercase tracking-widest transition-all"
                                    >GCash</button>
                                    <button 
                                        type="button"
                                        @click="formData.paymentMethod = 'Bank'"
                                        :class="formData.paymentMethod === 'Bank' ? 'bg-primary text-primary-foreground shadow-lg' : 'bg-white hover:bg-muted'"
                                        class="p-3 rounded-xl border font-bold text-[10px] uppercase tracking-widest transition-all"
                                    >Bank</button>
                                </div>

                                <div x-show="formData.paymentMethod" class="space-y-3">
                                    <template x-if="formData.paymentMethod === 'GCash'">
                                        <div class="p-4 rounded-xl bg-white border border-dashed text-center">
                                            <p class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60 mb-2">Send to GCash</p>
                                            <p class="font-mono text-lg font-black text-primary mb-3">{{ $global_settings['gcash_number'] ?? '09123456789' }}</p>
                                            <button @click="copyGCash()" type="button" class="px-4 py-2 rounded-lg bg-accent/10 text-accent text-[10px] font-black uppercase tracking-widest hover:bg-accent/20 transition-colors">
                                                <span x-show="!gcashCopied">Copy Number</span>
                                                <span x-show="gcashCopied" x-cloak>Copied!</span>
                                            </button>
                                        </div>
                                    </template>
                                    <template x-if="formData.paymentMethod === 'Bank'">
                                        <div class="p-4 rounded-xl bg-white border border-dashed text-center">
                                            <p class="text-[10px] font-black uppercase tracking-widest text-muted-foreground/60 mb-2">Bank Details</p>
                                            <p class="text-xs font-bold leading-relaxed">{{ $global_settings['bank_details'] ?? 'Sto. Rosario Parish' }}</p>
                                        </div>
                                    </template>
                                    <p class="text-[9px] text-muted-foreground text-center italic">* Donation is voluntary. Thank you for your support.</p>
                                </div>
                             </div>
                        </div>

                        <button 
                            type="submit" 
                            :disabled="loading"
                            class="w-full relative h-14 rounded-2xl bg-primary text-primary-foreground font-black uppercase tracking-[0.2em] shadow-2xl hover:bg-primary/95 transition-all overflow-hidden"
                        >
                            <span :class="loading ? 'opacity-0' : 'opacity-100'" class="transition-opacity">Submit Request</span>
                            <div x-show="loading" class="absolute inset-0 flex items-center justify-center" x-cloak>
                                <svg class="animate-spin h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function intentionForm() {
        return {
            loading: false,
            submitted: false,
            gcashCopied: false,
            refCopied: false,
            refId: '',
            intentionTypes: [
                "Thanksgiving", "Birthday", "Wedding Anniversary", "Healing", 
                "Repose of the Soul", "Special Intention", "Other"
            ],
            massTimes: [
                "6:00 AM", "8:30 AM", "10:00 AM", "3:00 PM", "4:30 PM", "6:00 PM"
            ],
            formData: {
                fullName: '',
                email: '',
                intentionType: '',
                preferredDate: '',
                massTime: '',
                description: '',
                paymentMethod: 'GCash'
            },
            
            init() {
                flatpickr(this.$refs.datePicker, {
                    minDate: "today",
                    dateFormat: "Y-m-d",
                    disableMobile: "true",
                    onChange: (selectedDates, dateStr) => {
                        this.formData.preferredDate = dateStr;
                        this.updateMassTimes(selectedDates[0]);
                    }
                });
            },

            updateMassTimes(date) {
                if (!date) return;
                
                const day = date.getDay(); // 0: Sunday, 6: Saturday
                
                if (day === 0) {
                    // Sunday
                    this.massTimes = ["6:30 AM", "8:30 AM", "10:00 AM", "3:00 PM", "4:30 PM", "6:00 PM"];
                } else if (day === 6) {
                    // Saturday
                    this.massTimes = ["6:30 AM", "6:00 PM"];
                } else {
                    // Mon-Fri
                    this.massTimes = ["6:00 PM"];
                }

                // Automatically select the time if only one option exists, 
                // or if current selection is not in the new list
                if (this.massTimes.length === 1) {
                    this.formData.massTime = this.massTimes[0];
                } else if (!this.massTimes.includes(this.formData.massTime)) {
                    this.formData.massTime = "";
                }
            },

            async submitForm() {
                if (this.loading) return;
                this.loading = true;
                try {
                    const response = await fetch('/submit-intention', {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(this.formData)
                    });
                    
                    const data = await response.json();
                    
                    if (!response.ok) throw new Error('Submission failed');
                    
                    this.refId = data.refId || '';
                    this.submitted = true;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } catch (error) {
                    if (window.showToast) {
                        window.showToast('Submission failed. Please try again.', 'error');
                    }
                } finally {
                    this.loading = false;
                }
            },

            copyGCash() {
                navigator.clipboard.writeText('{{ $global_settings['gcash_number'] ?? '09123456789' }}');
                this.gcashCopied = true;
                setTimeout(() => this.gcashCopied = false, 2000);
            },

            copyRefId() {
                navigator.clipboard.writeText(this.refId);
                this.refCopied = true;
                setTimeout(() => this.refCopied = false, 2000);
            },
            
            reset() {
                this.submitted = false;
                this.formData = {
                    fullName: '',
                    email: '',
                    intentionType: '',
                    preferredDate: '',
                    massTime: '',
                    description: '',
                    paymentMethod: 'GCash'
                };
            }
        }
    }
    </script>
</x-public-layout>
