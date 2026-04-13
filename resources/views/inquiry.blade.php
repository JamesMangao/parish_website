<x-public-layout>
    <div class="container py-12 mx-auto px-4 max-w-4xl">
        <div class="text-center mb-10">
            <h1 class="font-heading text-4xl font-bold mb-2 text-primary">Parish Inquiries</h1>
            <div class="section-divider mx-auto"></div>
            <p class="text-muted-foreground mt-4 max-w-lg mx-auto italic italic">
                Looking for a sacrament or a blessing? Send us your inquiry and our team will get back to you soon.
            </p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-700 px-6 py-4 rounded-xl mb-10 flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-card rounded-2xl border shadow-sm overflow-hidden">
            <div class="grid md:grid-cols-5">
                <!-- Info Sidebar -->
                <div class="md:col-span-2 bg-primary p-8 text-white">
                    <h2 class="font-heading text-2xl font-bold mb-6">Sacramental Services</h2>
                    <ul class="space-y-4 text-sm opacity-90">
                        <li class="flex items-start gap-3">
                            <div class="mt-1 flex-shrink-0 w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-[10px]">1</div>
                            <p>Fill out the inquiry form with your contact details and specific needs.</p>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="mt-1 flex-shrink-0 w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-[10px]">2</div>
                            <p>Our SocCom or Admin team will validate your request.</p>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="mt-1 flex-shrink-0 w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-[10px]">3</div>
                            <p>Once accepted, the inquiry is forwarded to the Parish Office for processing.</p>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="mt-1 flex-shrink-0 w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-[10px]">4</div>
                            <p>For Document Requests (Baptismal, etc.), please allow 3-5 working days for verification.</p>
                        </li>
                    </ul>

                    <div class="mt-12 pt-8 border-t border-white/10 space-y-4">
                        <div class="flex items-center gap-3 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <span>+63 2 8869 2742</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 13V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12c0 1.1.9 2 2 2h9"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/><path d="M19 16v6"/><path d="M16 19h6"/></svg>
                            <span class="break-all">officestorosarioparish@gmail.com</span>
                        </div>
                    </div>
                </div>

                <!-- Form Area -->
                <div class="md:col-span-3 p-8 bg-background">
                    <form action="{{ route('inquiry.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-primary" for="fullName">Full Name</label>
                            <input name="fullName" id="fullName" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2" placeholder="Juan Dela Cruz">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-primary" for="email">Email</label>
                                <input type="email" name="email" id="email" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2" placeholder="juan@example.com">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-primary" for="phone">Phone (Optional)</label>
                                <input name="phone" id="phone" class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2" placeholder="0912 345 6789">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-primary" for="inquiryType">Inquiry Type</label>
                            <select name="inquiryType" id="inquiryType" required class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 appearance-none font-medium">
                                <option value="" disabled selected>Select a service...</option>
                                <optgroup label="Sacramental Rites">
                                    <option value="Baptism">Baptism</option>
                                    <option value="First Communion">First Communion</option>
                                    <option value="Confirmation">Confirmation</option>
                                    <option value="Wedding">Wedding</option>
                                    <option value="Funeral Mass">Funeral Mass</option>
                                </optgroup>
                                <optgroup label="Document Requests">
                                    <option value="Baptismal Certificate">Baptismal Certificate</option>
                                    <option value="Confirmation Certificate">Confirmation Certificate</option>
                                    <option value="Marriage Certificate">Marriage Certificate</option>
                                </optgroup>
                                <optgroup label="Blessings & Others">
                                    <option value="Car Blessing">Car Blessing</option>
                                    <option value="House Blessing">House Blessing</option>
                                    <option value="Other">Others</option>
                                </optgroup>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-primary" for="message">Message / Details</label>
                            <textarea name="message" id="message" rows="4" required class="flex min-h-[100px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2" placeholder="Please provide details about your request (e.g. preferred dates, occasion details)"></textarea>
                        </div>

                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-md bg-primary px-4 py-3 text-sm font-bold text-primary-foreground hover:bg-primary/90 transition-all shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                            Submit Inquiry
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>
