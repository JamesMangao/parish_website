<x-public-layout>
    <div x-data="intentionForm()" class="container py-12 mx-auto px-4">
        <div class="text-center mb-10">
            <h1 class="font-heading text-4xl font-bold mb-2 text-primary">Submit Mass Intention</h1>
            <div class="section-divider"></div>
            <p class="text-muted-foreground mt-4 max-w-lg mx-auto italic italic">
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
            >
                <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-accent/20 text-accent mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </div>
                <h2 class="font-heading text-3xl font-bold mb-2 text-primary">Thank You!</h2>
                <p class="text-muted-foreground mb-8">Your mass intention has been submitted and is pending review.</p>
                <button @click="reset()" class="px-6 py-2 rounded-md border border-primary text-primary font-bold hover:bg-primary hover:text-white transition-colors">
                    Submit Another
                </button>
            </div>

            <!-- Form State -->
            <div x-show="!submitted" class="bg-card rounded-xl border shadow-sm overflow-hidden">
                <div class="p-6 border-b bg-muted/30">
                    <h3 class="font-heading text-xl font-bold text-primary">Intention Details</h3>
                </div>
                <div class="p-6">
                    <form @submit.prevent="submitForm()" class="space-y-6">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-primary" for="fullName">Full Name</label>
                            <input 
                                x-model="formData.fullName"
                                id="fullName" 
                                name="fullName" 
                                placeholder="Juan Dela Cruz" 
                                required 
                                class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                            />
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-primary" for="intentionType">Intention Type</label>
                            <div class="relative">
                                <select 
                                    x-model="formData.intentionType"
                                    id="intentionType" 
                                    name="intentionType" 
                                    required
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 appearance-none font-medium"
                                >
                                    <option value="" disabled selected>Select type...</option>
                                    <template x-for="type in intentionTypes" :key="type">
                                        <option :value="type" x-text="type"></option>
                                    </template>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-muted-foreground">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-down"><path d="m6 9 6 6 6-6"/></svg>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-primary" for="preferredDate">Preferred Mass Date</label>
                                <input 
                                    x-model="formData.preferredDate"
                                    id="preferredDate" 
                                    name="preferredDate" 
                                    type="date" 
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 font-medium"
                                />
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-bold text-primary" for="massTime">Mass Time</label>
                                <div class="relative">
                                    <select 
                                        x-model="formData.massTime"
                                        id="massTime" 
                                        name="massTime" 
                                        required
                                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 appearance-none font-medium"
                                    >
                                        <option value="" disabled selected>Select time...</option>
                                        <template x-for="time in massTimes" :key="time">
                                            <option :value="time" x-text="time"></option>
                                        </template>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-muted-foreground">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2 pt-2">
                             <label class="text-sm font-bold text-primary" for="description">Description / Specific Intentions</label>
                             <textarea 
                                 x-model="formData.description"
                                 id="description" 
                                 name="description" 
                                 rows="3"
                                 placeholder="Enter names or specific intentions (e.g. For the healing of..., In memory of...)"
                                 class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground/50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 font-medium"
                             ></textarea>
                         </div>

                         <div class="space-y-4 pt-4 border-t">
                            <label class="text-sm font-bold text-primary">Donation Method (Optional)</label>
                            <div class="grid grid-cols-2 gap-3">
                                <button 
                                    type="button"
                                    @click="formData.paymentMethod = 'GCash'"
                                    :class="formData.paymentMethod === 'GCash' ? 'border-primary bg-primary/5 ring-1 ring-primary' : 'border-input hover:border-primary/50'"
                                    class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all gap-2"
                                >
                                    <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-black text-xs">G</div>
                                    <span class="text-xs font-bold">GCash</span>
                                </button>
                                <button 
                                    type="button"
                                    @click="formData.paymentMethod = 'Bank'"
                                    :class="formData.paymentMethod === 'Bank' ? 'border-primary bg-primary/5 ring-1 ring-primary' : 'border-input hover:border-primary/50'"
                                    class="flex flex-col items-center justify-center p-4 rounded-xl border transition-all gap-2"
                                >
                                    <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-landmark"><line x1="3" x2="21" y1="22" y2="22"/><line x1="6" x2="6" y1="18" y2="11"/><line x1="10" x2="10" y1="18" y2="11"/><line x1="14" x2="14" y1="18" y2="11"/><line x1="18" x2="18" y1="18" y2="11"/><polygon points="12 2 20 7 4 7 12 2"/></svg>
                                    </div>
                                    <span class="text-xs font-bold">Bank Transfer</span>
                                </button>
                            </div>
                            
                            <div x-show="formData.paymentMethod" x-transition class="p-4 rounded-lg bg-muted/50 border border-dashed text-[11px] text-muted-foreground leading-relaxed">
                                <template x-if="formData.paymentMethod === 'GCash'">
                                    <p>Please send your donation to: <br> <strong class="text-primary">Juan Dela Cruz - 0912 345 6789</strong></p>
                                </template>
                                <template x-if="formData.paymentMethod === 'Bank'">
                                    <p>Sto. Rosario Parish <br> BPI: <strong class="text-primary">1234-5678-90</strong></p>
                                </template>
                                <p class="mt-2 italic">* Please keep a screenshot of your transaction for verification if necessary.</p>
                            </div>
                        </div>

                        <button 
                            type="submit" 
                            :disabled="loading"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-md bg-primary px-4 py-3 text-sm font-bold text-primary-foreground transition-all hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-70 shadow-lg"
                        >
                            <template x-if="!loading">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-send"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                                    Submit Intention
                                </div>
                            </template>
                            <template x-if="loading">
                                <div class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Submitting...
                                </div>
                            </template>
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
            intentionTypes: [
                "Thanksgiving", "Birthday", "Wedding Anniversary", "Healing", 
                "Repose of the Soul", "Special Intention", "Other"
            ],
            massTimes: [
                "6:00 AM", "8:30 AM", "10:00 AM", "3:00 PM", "4:30 PM", "6:00 PM"
            ],
            formData: {
                fullName: '',
                intentionType: '',
                preferredDate: '',
                massTime: '',
                description: '',
                paymentMethod: ''
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
                    
                    if (!response.ok) throw new Error('Submission failed');
                    
                    this.submitted = true;
                } catch (error) {
                    alert('Something went wrong. Please try again.');
                } finally {
                    this.loading = false;
                }
            },
            
            reset() {
                this.submitted = false;
                this.formData = {
                    fullName: '',
                    intentionType: '',
                    preferredDate: '',
                    massTime: '',
                    description: '',
                    paymentMethod: ''
                };
            }
        }
    }
    </script>
</x-public-layout>
