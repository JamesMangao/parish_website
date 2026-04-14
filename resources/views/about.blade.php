<x-public-layout>
    <x-slot name="meta">
        <meta name="description" content="Learn about Sto. Rosario Parish in Pacita, San Pedro, Laguna. Discover our mission, history, office hours, and contact details.">
    </x-slot>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <div class="container mx-auto px-4 py-12 max-w-5xl">
        <div class="mb-12 text-center">
            <h1 class="font-heading text-4xl md:text-5xl font-bold text-primary mb-6">About Our Parish</h1>
            <div class="section-divider mx-auto mb-6"></div>

            <p class="text-lg md:text-xl text-muted-foreground leading-relaxed max-w-3xl mx-auto">
                Welcome to Sto. Rosario Parish located in the heart of Pacita, City of San Pedro, Laguna, Philippines 4023.
            </p>

            <p class="text-lg md:text-xl text-muted-foreground leading-relaxed max-w-3xl mx-auto mt-3">
                It is home to the iconic image, Queen of The Most Holy Rosary. The Queen of the City of San Pedro.
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-16 items-center mb-24">
            <div class="relative group">
                <div class="absolute -inset-4 bg-primary/5 rounded-[3rem] blur-2xl group-hover:bg-primary/10 transition-all duration-700"></div>
                <img src="{{ asset($global_settings['hero_image'] ?? 'bg.png') }}" alt="Sto. Rosario Parish Church"
                    class="relative rounded-[2.5rem] shadow-2xl w-full aspect-[4/5] object-cover border-4 border-white transform group-hover:-rotate-1 transition-transform duration-700">
                <div class="absolute -bottom-6 -right-6 h-32 w-32 bg-accent rounded-3xl flex flex-col items-center justify-center text-accent-foreground shadow-2xl transform group-hover:rotate-6 transition-transform duration-700">
                    <span class="text-4xl font-black font-heading">40+</span>
                    <span class="text-[10px] font-black uppercase tracking-widest opacity-80">Years</span>
                </div>
            </div>
            <div class="space-y-8">
                <div>
                    <h2 class="text-[10px] font-black uppercase tracking-[0.5em] text-accent mb-4">Our Calling</h2>
                    <h3 class="font-heading text-4xl font-black text-primary leading-tight mb-6">Building a Sanctuary of Faith & Service</h3>
                    <p class="text-muted-foreground leading-relaxed text-lg">
                        Sto. Rosario Parish is more than just a building; it is a vibrant community of believers dedicated to the Queen of the Most Holy Rosary. For over four decades, we have been a beacon of hope in Pacita, San Pedro.
                    </p>
                </div>
                
                <div class="grid gap-6">
                    <div class="flex gap-4 p-6 bg-primary/5 rounded-3xl border border-primary/10 hover:bg-primary/10 transition-colors">
                        <div class="h-12 w-12 shrink-0 bg-white rounded-2xl shadow-sm flex items-center justify-center text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-primary mb-1">Our Mission</h4>
                            <p class="text-sm text-muted-foreground leading-relaxed">To build a deeply prayerful and actively serving community reaching out to the margins.</p>
                        </div>
                    </div>
                    <div class="flex gap-4 p-6 bg-accent/5 rounded-3xl border border-accent/10 hover:bg-accent/10 transition-colors">
                        <div class="h-12 w-12 shrink-0 bg-white rounded-2xl shadow-sm flex items-center justify-center text-accent">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-primary mb-1">Our Vision</h4>
                            <p class="text-sm text-muted-foreground leading-relaxed">A Christ-centered parish family, transformed by the Holy Spirit, living the Gospel values.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Timeline -->
        <div class="mb-24">
            <h2 class="text-[10px] font-black uppercase tracking-[0.5em] text-accent text-center mb-4">The Journey</h2>
            <h3 class="font-heading text-4xl font-black text-primary text-center mb-16 italic">Our Sacred History</h3>
            
            <div class="relative space-y-12">
                <div class="absolute left-1/2 -translate-x-1/2 top-4 bottom-4 w-px bg-gradient-to-b from-transparent via-primary/20 to-transparent hidden md:block"></div>
                
                <!-- 1983 -->
                <div class="flex flex-col md:flex-row gap-8 items-center md:items-start group">
                    <div class="md:w-1/2 md:text-right">
                        <span class="text-3xl font-black text-primary/20 group-hover:text-accent transition-colors">1983</span>
                        <h4 class="text-xl font-black text-primary mt-2">The Foundation</h4>
                        <p class="text-muted-foreground text-sm mt-3 leading-relaxed md:ml-auto max-w-sm">Sto. Rosario Parish was canonically established on November 7, 1983, to serve the growing spiritual needs of Pacita Complex.</p>
                    </div>
                    <div class="h-4 w-4 rounded-full border-4 border-accent bg-white z-10 hidden md:block mt-8"></div>
                    <div class="md:w-1/2"></div>
                </div>

                <!-- 1990s -->
                <div class="flex flex-col md:flex-row-reverse gap-8 items-center md:items-start group">
                    <div class="md:w-1/2">
                        <span class="text-3xl font-black text-primary/20 group-hover:text-accent transition-colors">1990s</span>
                        <h4 class="text-xl font-black text-primary mt-2">Expansion</h4>
                        <p class="text-muted-foreground text-sm mt-3 leading-relaxed max-w-sm">Development of the main parish temple and establishment of various ministries and organizations.</p>
                    </div>
                    <div class="h-4 w-4 rounded-full border-4 border-accent bg-white z-10 hidden md:block mt-8"></div>
                    <div class="md:w-1/2"></div>
                </div>

                <!-- Today -->
                <div class="flex flex-col md:flex-row gap-8 items-center md:items-start group">
                    <div class="md:w-1/2 md:text-right">
                        <span class="text-3xl font-black text-primary/20 group-hover:text-accent transition-colors">Today</span>
                        <h4 class="text-xl font-black text-primary mt-2">A Digital Horizon</h4>
                        <p class="text-muted-foreground text-sm mt-3 leading-relaxed md:ml-auto max-w-sm">Embracing technology with Parish Pal to connect more parishioners and modernize our services while staying true to our core mission.</p>
                    </div>
                    <div class="h-4 w-4 rounded-full border-4 border-accent bg-white z-10 hidden md:block mt-8"></div>
                    <div class="md:w-1/2"></div>
                </div>
            </div>
        </div>

        <!-- Our Leadership -->
        <div class="mb-24">
            <h2 class="text-[10px] font-black uppercase tracking-[0.2em] text-accent text-center mb-4">Our Leadership</h2>
            <h3 class="font-heading text-4xl font-black text-primary text-center mb-16 italic">Shepherds of the Flock</h3>

            <div class="max-w-md mx-auto">
                <div class="group relative">
                    <div class="absolute -inset-2 bg-gradient-to-r from-primary to-accent rounded-[2.5rem] blur opacity-20 group-hover:opacity-40 transition duration-700"></div>
                    <div class="relative bg-white rounded-[2rem] border p-8 text-center shadow-xl">
                        <div class="h-40 w-40 rounded-full bg-muted mx-auto mb-6 border-4 border-primary/10 overflow-hidden">
                            <img src="{{ asset('storage/priest.png') }}" onerror="this.src='https://ui-avatars.com/api/?name=FR+Vicar&background=0D1B2A&color=fff&size=512'" alt="Parish Priest" class="w-full h-full object-cover">
                        </div>
                        <h4 class="font-heading text-2xl font-black text-primary italic">Rev. Fr. Parish Priest</h4>
                        <p class="text-[10px] font-black uppercase tracking-widest text-accent mt-1">Parish Priest</p>
                        <p class="text-sm text-muted-foreground mt-4 leading-relaxed italic">"Feeding the sheep and tending the flock of the Lord with love and devotion."</p>
                        
                        <div class="mt-8 pt-8 border-t flex justify-center gap-6">
                            <div class="text-center">
                                <p class="text-[10px] font-black uppercase tracking-tighter text-muted-foreground mb-1">Assigned</p>
                                <p class="text-xs font-bold text-primary">2021 - Present</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Location Section -->
        <h2 class="font-heading text-3xl font-bold text-primary mb-6 text-center">Find Us Here</h2>

        <div class="glass-card p-2 md:p-4 rounded-xl shadow-sm border">
            <!-- Leaflet Map Container -->
            <div id="map" class="z-0"
                style="width: 100%; height: 450px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);"></div>
        </div>

        <div class="mt-8 grid md:grid-cols-3 gap-6 text-center">
            <div class="bg-card p-6 rounded-xl border shadow-sm">
                <div class="mb-3 text-accent flex justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-map-pin">
                        <path
                            d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 15 4 10a8 8 0 0 1 16 0" />
                        <circle cx="12" cy="10" r="3" />
                    </svg>
                </div>
                <h3 class="font-bold text-lg mb-2">Address</h3>
                <p class="text-muted-foreground text-sm">1 Sto. Rosario Drive,<br>Pacita, San Pedro, Laguna</p>
            </div>
            <div class="bg-card p-6 rounded-xl border shadow-sm">
                <div class="mb-3 text-accent flex justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-phone">
                        <path
                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                    </svg>
                </div>
                <h3 class="font-bold text-lg mb-3">Contact</h3>
                <div class="text-left space-y-2 text-sm">
                    <a href="tel:+6328869 2742"
                        class="flex items-center gap-2 text-muted-foreground hover:text-accent transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                        </svg>
                        +63 2 8869 2742
                    </a>
                    <a href="mailto:officestorosarioparish@gmail.com"
                        class="flex items-center gap-2 text-muted-foreground hover:text-accent transition-colors break-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="20" height="16" x="2" y="4" rx="2" />
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                        </svg>
                        {{ config('services.parish.office_email') }}
                    </a>
                </div>
            </div>
            <div class="bg-card p-6 rounded-xl border shadow-sm">
                <div class="mb-3 text-accent flex justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-clock">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                </div>
                <h3 class="font-bold text-lg mb-4">Office Hours</h3>
                <div class="text-left space-y-3 text-sm">
                    <div>
                        <p class="font-semibold text-primary mb-1.5">Tuesday – Saturday</p>
                        <div class="flex flex-wrap gap-1.5">
                            <span
                                class="inline-block bg-accent/10 text-accent border border-accent/20 rounded-full px-3 py-0.5 text-xs font-medium">6:00
                                AM – 12:00 NN</span>
                            <span
                                class="inline-block bg-accent/10 text-accent border border-accent/20 rounded-full px-3 py-0.5 text-xs font-medium">1:30
                                PM – 6:00 PM</span>
                        </div>
                    </div>
                    <div class="border-t border-muted pt-3">
                        <p class="font-semibold text-primary mb-1.5">Sunday</p>
                        <div class="flex flex-wrap gap-1.5">
                            <span
                                class="inline-block bg-accent/10 text-accent border border-accent/20 rounded-full px-3 py-0.5 text-xs font-medium">6:00
                                AM – 12:00 NN</span>
                            <span
                                class="inline-block bg-accent/10 text-accent border border-accent/20 rounded-full px-3 py-0.5 text-xs font-medium">3:00
                                PM – 6:00 PM</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact CTA -->
        <div class="bg-primary rounded-[3rem] p-12 text-center text-primary-foreground relative overflow-hidden group">
            <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/2 w-96 h-96 bg-accent/20 rounded-full blur-3xl"></div>
            <div class="relative z-10">
                <h3 class="font-heading text-3xl font-black italic mb-4">Visit Us Today</h3>
                <p class="max-w-xl mx-auto opacity-80 mb-8 font-medium">We are located at 1 Sto. Rosario Drive, Pacita, San Pedro, Laguna. Our doors and hearts are always open to you.</p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="/mass-schedule" class="px-8 py-3 bg-accent text-accent-foreground rounded-xl font-black uppercase tracking-widest text-xs hover:scale-105 transition-all shadow-xl shadow-accent/20">View Schedule</a>
                    <a href="/inquiry" class="px-8 py-3 bg-white text-primary rounded-xl font-black uppercase tracking-widest text-xs hover:bg-muted transition-all">Contact Office</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Initialize the map with exact coordinates, zoom level 17, and disable scroll zoom
            const map = L.map('map', { scrollWheelZoom: false }).setView([14.345435, 121.061630], 17);

            // OpenStreetMap tile layer (free, no API key)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Google Maps directions URL
            const directionsUrl = "https://www.google.com/maps/dir/?api=1&destination=14.345435,121.061630";

            // Marker with popup
            const marker = L.marker([14.345435, 121.061630]).addTo(map);

            marker.bindPopup(`
                <div style="font-family: sans-serif; text-align: left;">
                    <b style="font-size: 1.1em; color: var(--primary);">Sto. Rosario Parish</b><br>
                    1 Sto. Rosario Drive,<br>
                    Pacita, San Pedro, Laguna<br>
                    📞 +63 2 8869 2742<br>
                    <div style="margin-top: 8px;">
                        <a href="${directionsUrl}" target="_blank" style="color: #2563eb; text-decoration: none; font-weight: bold; border-bottom: 1px solid #2563eb;">Get Directions ↗</a>
                    </div>
                </div>
            `).openPopup();

            // Fix map rendering issues if it loads initially hidden or scaled incorrectly
            setTimeout(function () {
                map.invalidateSize();
            }, 500);
        });
    </script>
</x-public-layout>