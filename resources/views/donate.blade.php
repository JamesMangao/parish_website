<x-public-layout>
    <x-slot name="meta">
        <meta name="description" content="Support Sto. Rosario Parish. Give your tithes, digital offerings, and donations to help our church maintenance and community programs.">
    </x-slot>

    <div class="container py-12 mx-auto px-4 max-w-4xl" x-data="{ copiedG: false, copiedM: false }">
        <div class="text-center mb-12 animate-in fade-in slide-in-from-bottom-4 duration-500">
            <h1 class="font-heading text-5xl font-black mb-4 text-primary italic">Support Our Parish</h1>
            <div class="h-1 w-24 bg-accent mx-auto rounded-full mb-6 italic"></div>
            <p class="text-lg text-muted-foreground max-w-2xl mx-auto leading-relaxed italic">
                Your tithes and donations help us maintain our sacred space, support our community outreach programs, and continue our mission of spiritual service.
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-8 items-start">
            <!-- QR Code Card -->
            <div class="bg-card rounded-[2.5rem] border shadow-2xl p-8 sticky top-24 overflow-hidden group">
                <div class="absolute inset-0 bg-primary/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-primary/40">Digital Offerings</p>
                            <h2 class="text-2xl font-black text-primary uppercase font-heading">Maya & GCash</h2>
                        </div>
                        <div class="h-12 w-12 rounded-2xl bg-accent flex items-center justify-center text-accent-foreground shadow-lg shadow-accent/20">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-qr-code"><rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3"/><path d="M21 21v.01"/><path d="M12 7v3"/><path d="M7 12h3"/><path d="M12 20v-5"/><path d="M17 12h.01"/><path d="M7 12v.01"/><path d="M12 12v.01"/><path d="M16 7h5"/><path d="M21 12h-3a2 2 0 0 0-2 2v3"/><path d="M21 16v.01"/><path d="M16 21h.01"/><path d="M12 17v.01"/><path d="M17 17v.01"/><path d="M16 12v5"/><path d="M7 7h.01"/></svg>
                        </div>
                    </div>

                    <div class="p-6 bg-white rounded-3xl border shadow-inner mb-8 transform group-hover:scale-[1.02] transition-transform">
                        @if(isset($global_settings['qr_code']))
                            <img src="{{ asset('storage/' . $global_settings['qr_code']) }}" 
                                 alt="Parish Donation QR" 
                                 class="w-full h-auto rounded-xl">
                        @else
                            <div class="aspect-square flex flex-col items-center justify-center border-2 border-dashed rounded-xl text-muted-foreground p-8 text-center italic">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="mb-4 opacity-20"><rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3"/><path d="M21 21v.01"/><path d="M12 7v3"/><path d="M7 12h3"/><path d="M12 20v-5"/><path d="M17 12h.01"/><path d="M7 12v.01"/><path d="M12 12v.01"/><path d="M16 7h5"/><path d="M21 12h-3a2 2 0 0 0-2 2v3"/><path d="M21 16v.01"/><path d="M16 21h.01"/><path d="M12 17v.01"/><path d="M17 17v.01"/><path d="M16 12v5"/><path d="M7 7h.01"/></svg>
                                <p class="text-sm">Parish QR code not yet available. Please use the account details below.</p>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-muted/40 rounded-2xl border border-dashed hover:border-accent hover:bg-muted transition-all cursor-pointer" 
                            @click="navigator.clipboard.writeText('{{ $global_settings['gcash_number'] ?? 'Not set' }}'); copiedG = true; setTimeout(() => copiedG = false, 2000)">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-black text-xs">G</div>
                                <div>
                                    <p class="text-[10px] font-black uppercase text-muted-foreground/60 tracking-widest">GCash Number</p>
                                    <p class="font-bold text-sm">{{ $global_settings['gcash_number'] ?? 'Not set' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span x-show="copiedG" x-cloak class="text-[10px] font-bold text-accent">COPIED!</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-accent opacity-0 group-hover:opacity-100 transition-opacity"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-muted/40 rounded-2xl border border-dashed hover:border-accent hover:bg-muted transition-all cursor-pointer" 
                            @click="navigator.clipboard.writeText('{{ $global_settings['gcash_name'] ?? 'Not set' }}'); copiedM = true; setTimeout(() => copiedM = false, 2000)">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-black text-xs">A</div>
                                <div>
                                    <p class="text-[10px] font-black uppercase text-muted-foreground/60 tracking-widest">Account Name</p>
                                    <p class="font-bold text-sm">{{ $global_settings['gcash_name'] ?? 'Not set' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span x-show="copiedM" x-cloak class="text-[10px] font-bold text-accent">COPIED!</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-accent opacity-0 group-hover:opacity-100 transition-opacity"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transparency & Message Card -->
            <div class="space-y-6">
                <div class="p-8 bg-white rounded-3xl border shadow-sm">
                    <h3 class="text-lg font-black text-primary uppercase font-heading mb-4">Where your donation goes</h3>
                    <ul class="space-y-6">
                        <li class="flex items-start gap-4">
                            <div class="h-10 w-10 shrink-0 bg-primary/10 rounded-xl flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path d="M3 9h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9Z"/><path d="m3 9 2.45-4.9A2 2 0 0 1 7.24 3h9.52a2 2 0 0 1 1.8 1.1L21 9"/><path d="M12 3v6"/></svg>
                            </div>
                            <div>
                                <p class="font-bold text-sm">Church Maintenance</p>
                                <p class="text-xs text-muted-foreground leading-relaxed">Preserving the beauty and structural integrity of our 100-year-old parish temple.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="h-10 w-10 shrink-0 bg-accent/10 rounded-xl flex items-center justify-center text-accent">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.505 4.04 3 5.5L12 21l7-7Z"/></svg>
                            </div>
                            <div>
                                <p class="font-bold text-sm">Pantry Community Outreach</p>
                                <p class="text-xs text-muted-foreground leading-relaxed">Providing weekly food supplies and scholarships to under-privileged families in the parish.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="h-10 w-10 shrink-0 bg-purple-100 rounded-xl flex items-center justify-center text-purple-700">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22v-4"/><path d="M12 13V2"/><path d="M5.4 6a3.6 3.6 0 1 0 13.2 0"/><path d="M12 8a3.6 3.6 0 1 0 0-7.2A3.6 3.6 0 0 0 12 8Z"/></svg>
                            </div>
                            <div>
                                <p class="font-bold text-sm">Youth Ministry</p>
                                <p class="text-xs text-muted-foreground leading-relaxed">Empowering the next generation of leaders through spiritual retreats and skill-building workshops.</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="p-8 bg-primary rounded-3xl text-white shadow-xl italic">
                    <p class="text-sm font-medium leading-relaxed opacity-90 mb-4">
                        "Each of you should give what you have decided in your heart to give, not reluctantly or under compulsion, for God loves a cheerful giver."
                    </p>
                    <p class="text-xs font-black uppercase tracking-widest">– 2 Corinthians 9:7</p>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>
