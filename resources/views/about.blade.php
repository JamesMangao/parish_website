<x-public-layout>
    <x-slot name="meta">
        <meta name="description" content="Learn about Sto. Rosario Parish in Pacita, San Pedro, Laguna. Discover our history, the Queen of the Most Holy Rosary, office hours, and contact details.">
    </x-slot>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <div class="container mx-auto px-4 py-12 max-w-5xl">

        <!-- Hero Header -->
        <div class="mb-16 text-center">
            <h1 class="font-heading text-4xl md:text-5xl font-bold text-primary mb-6">About Our Parish</h1>
            <div class="section-divider mx-auto mb-6"></div>
            <p class="text-lg md:text-xl text-muted-foreground leading-relaxed max-w-3xl mx-auto">
                Welcome to Sto. Rosario Parish located in the heart of Pacita, City of San Pedro, Laguna, Philippines 4023.
            </p>
            <p class="text-lg md:text-xl text-muted-foreground leading-relaxed max-w-3xl mx-auto mt-3">
                It is home to the iconic image, Queen of The Most Holy Rosary &mdash; The Queen of the City of San Pedro.
            </p>
        </div>

        <!-- Parish Intro -->
        <div class="grid md:grid-cols-2 gap-12 items-start mb-24">
            <div>
                <img src="{{ asset($global_settings['hero_image'] ?? 'bg.png') }}" alt="Sto. Rosario Parish Church"
                    class="rounded-2xl shadow-xl w-full object-cover aspect-[4/3]">
                <div class="mt-4 inline-flex items-center gap-3 bg-accent text-accent-foreground px-6 py-3 rounded-xl shadow-lg">
                    <span class="text-3xl font-black font-heading">40+</span>
                    <span class="text-xs font-black uppercase tracking-widest opacity-80">Years of<br>Service</span>
                </div>
            </div>
            <div class="space-y-6">
                <h2 class="text-lg font-black uppercase tracking-widest text-accent">Our Calling</h2>
                <h3 class="font-heading text-3xl md:text-4xl font-black text-primary leading-tight">Building a Sanctuary of Faith &amp; Service</h3>
                <p class="text-muted-foreground leading-relaxed text-base md:text-lg">
                    Sto. Rosario Parish is more than just a building; it is a vibrant community of believers dedicated to the Queen of the Most Holy Rosary. For over four decades, we have been a beacon of hope in Pacita, San Pedro.
                </p>
            </div>
        </div>

        <!-- The Queen of Pacita -->
        <div class="mb-24">
            <div class="text-center mb-12">
                <h2 class="text-lg font-black uppercase tracking-widest text-accent mb-4">Our Titular Patroness</h2>
                <h3 class="font-heading text-3xl md:text-4xl font-black text-primary italic">The Queen of the Most Holy Rosary of Pacita</h3>
            </div>

            <div class="bg-card rounded-2xl border shadow-lg p-6 md:p-10">
                <div class="prose prose-lg max-w-none text-muted-foreground leading-relaxed space-y-5 text-sm md:text-base">
                    <p>The Queen of the Most Holy Rosary of Pacita is the titular patroness of Brgy. Pacita 1, City of San Pedro, Laguna.</p>

                    <p>The image of Our Lady is a European-inspired wooden sculpture of the Blessed Virgin Mary with the Infant Jesus in her left arm. Similar to the other depictions of Our Lady of the Most Holy Rosary, the Lady of Pacita is also garbed in beautiful regalia and is adorned with the aureola, rostrillo, cetro, baston and corona imperial. The infant Jesus holds an orb and the Virgin's Peaña dela Nubes (oval cloud base) has three cherubim. Her image is enshrined and venerated at the retablo mayor of the Church of Santo Rosario in Pacita Complex, San Pedro, Laguna.</p>

                    <p>The image was carved in Paete, Laguna in the year 1982. It was Mrs. Delia Sanchez and Mrs. Fely Canta &ndash; both workers in the academe, who took efforts in gathering funds (after they have approached the Interim Pastoral Council and have its approval) for the carving of the said image and the creation of its vestment and other embellishments.</p>

                    <p>A separate incident closely prior to the carving of the image was the sending of the request letter by the Campos Family &ndash; the Church prime land donor, addressing the council leaders to consider the Blessed Mother in the selection of the parish patron saint.</p>

                    <p>It is as if it was by divine providence that these two incidents took place at the time when the Interim-Pastoral Council headed by Bro. Pedro Guillen Jr. is in the process of discerning as to who will be the patron saint of the newly established and growing community. It was then that the people of Pacita started invoking the Queen of Most Holy Rosary as their patroness and protectress.</p>

                    <p>Upon its arrival in Pacita, the image was temporarily housed at the residence of the Cantas. It was later transferred through a procession to the make-shift chapel located at the site of the present church. The image was blessed by Rev. Fr. Rey Amante, the administrator of the would-be parish.</p>

                    <p>Coinciding with the canonical erection of the parish on October 16, 1983 is the official declaration of the Queen of the Most Holy Rosary of Pacita as the patroness of the parish community.</p>

                    <p>The Sto. Rosario Parish Church was then blessed and dedicated on December 6, 1986. It was jointly officiated by his Excellency, Msgr. Bruno Torpigliani, Papal Nuncio to the Philippines, Most Rev. Pedro Bantigue, Bishop of the Diocese of San Pablo and Most Rev. Gabriel Reyes, Auxiliary Bishop of Manila.</p>

                    <p>In the year 2009, the then Parish Priest, Rev. Fr. Mario P. Rivera started to promote the endearing title <strong>&lsquo;Our Lady of Pacita&rsquo;</strong>.</p>

                    <p>Our Lady of Pacita is an endearing title used by the local community to address Mary, the locality's titular patroness. Our Lady of Pacita is a sobriquet interchangeably used with Our Lady of the Most Holy Rosary to integrate a sense of belongingness of the community into the Blessed Mother and vice versa.</p>

                    <p>Over the years, devotion to the Queen of the Most Holy Rosary prospered. Devotees from nearby towns started to grow in numbers. Miracles of healing, answers to problems, provision of offspring to those with childbearing difficulties, successes in studies, professions and vocations are attributed to her by her devotees.</p>

                    <p>The Hermandad del Santo Rosario &ndash; the Rosary Confraternity of Pacita was formally established in 2021 to continuously propagate the devotion to Our Lady. The Perpetual Novena in her honor is being held in the parish every Saturday.</p>
                </div>

                <!-- Key Milestones -->
                <div class="mt-10 pt-8 border-t border-border">
                    <h4 class="text-sm font-black uppercase tracking-widest text-accent mb-6">Key Recognitions</h4>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="bg-muted/50 rounded-xl p-5 border">
                            <span class="text-2xl font-black text-primary/30 font-heading">2024</span>
                            <p class="text-sm text-muted-foreground mt-2 leading-relaxed">Declared as one of the <strong class="text-primary">Important Cultural Properties</strong> of the City of San Pedro by Sangguniang Panlungsod Resolution No. 2024-198.</p>
                        </div>
                        <div class="bg-muted/50 rounded-xl p-5 border">
                            <span class="text-2xl font-black text-primary/30 font-heading">2025</span>
                            <p class="text-sm text-muted-foreground mt-2 leading-relaxed">Accorded the honorific title <strong class="text-primary">&lsquo;Queen of the City of San Pedro&rsquo;</strong> by Sangguniang Panlungsod Resolution No. 2025-93.</p>
                        </div>
                    </div>
                    <p class="text-sm text-muted-foreground mt-6 leading-relaxed">The image of Our Lady also joins the roll of venerated Marian images in the famed Intramuros Grand Marian Procession every December during the pre-pandemic years.</p>
                    <p class="text-sm font-semibold text-primary mt-4">The feast of the Queen of the Most Holy Rosary of Pacita is celebrated every <span class="text-accent">Third Sunday of October</span>.</p>
                </div>
            </div>
        </div>

        <!-- History Timeline -->
        <div class="mb-24">
            <div class="text-center mb-12">
                <h2 class="text-lg font-black uppercase tracking-widest text-accent mb-4">The Journey</h2>
                <h3 class="font-heading text-3xl md:text-4xl font-black text-primary italic">Our Sacred History</h3>
            </div>

            <div class="relative space-y-12">
                <div class="absolute left-1/2 -translate-x-1/2 top-4 bottom-4 w-px bg-gradient-to-b from-transparent via-primary/20 to-transparent hidden md:block"></div>

                <!-- 1982 -->
                <div class="flex flex-col md:flex-row gap-8 items-center md:items-start group">
                    <div class="md:w-1/2 md:text-right">
                        <span class="text-3xl font-black text-primary/20 group-hover:text-accent transition-colors">1982</span>
                        <h4 class="text-xl font-black text-primary mt-2">The Image</h4>
                        <p class="text-muted-foreground text-sm mt-3 leading-relaxed md:ml-auto max-w-sm">The image of the Queen of the Most Holy Rosary was carved in Paete, Laguna through the efforts of Mrs. Delia Sanchez and Mrs. Fely Canta.</p>
                    </div>
                    <div class="h-4 w-4 rounded-full border-4 border-accent bg-white z-10 hidden md:block mt-8"></div>
                    <div class="md:w-1/2"></div>
                </div>

                <!-- 1983 -->
                <div class="flex flex-col md:flex-row-reverse gap-8 items-center md:items-start group">
                    <div class="md:w-1/2">
                        <span class="text-3xl font-black text-primary/20 group-hover:text-accent transition-colors">1983</span>
                        <h4 class="text-xl font-black text-primary mt-2">Canonical Erection</h4>
                        <p class="text-muted-foreground text-sm mt-3 leading-relaxed max-w-sm">Sto. Rosario Parish was canonically established on October 16, 1983. Our Lady was officially declared as patroness of the parish community.</p>
                    </div>
                    <div class="h-4 w-4 rounded-full border-4 border-accent bg-white z-10 hidden md:block mt-8"></div>
                    <div class="md:w-1/2"></div>
                </div>

                <!-- 1986 -->
                <div class="flex flex-col md:flex-row gap-8 items-center md:items-start group">
                    <div class="md:w-1/2 md:text-right">
                        <span class="text-3xl font-black text-primary/20 group-hover:text-accent transition-colors">1986</span>
                        <h4 class="text-xl font-black text-primary mt-2">Church Dedication</h4>
                        <p class="text-muted-foreground text-sm mt-3 leading-relaxed md:ml-auto max-w-sm">The parish church was blessed and dedicated on December 6, 1986, jointly officiated by Msgr. Bruno Torpigliani (Papal Nuncio), Bishop Pedro Bantigue, and Auxiliary Bishop Gabriel Reyes.</p>
                    </div>
                    <div class="h-4 w-4 rounded-full border-4 border-accent bg-white z-10 hidden md:block mt-8"></div>
                    <div class="md:w-1/2"></div>
                </div>

                <!-- 2009 -->
                <div class="flex flex-col md:flex-row-reverse gap-8 items-center md:items-start group">
                    <div class="md:w-1/2">
                        <span class="text-3xl font-black text-primary/20 group-hover:text-accent transition-colors">2009</span>
                        <h4 class="text-xl font-black text-primary mt-2">Our Lady of Pacita</h4>
                        <p class="text-muted-foreground text-sm mt-3 leading-relaxed max-w-sm">Rev. Fr. Mario P. Rivera promoted the endearing title &lsquo;Our Lady of Pacita&rsquo; to deepen the community's bond with the Blessed Mother.</p>
                    </div>
                    <div class="h-4 w-4 rounded-full border-4 border-accent bg-white z-10 hidden md:block mt-8"></div>
                    <div class="md:w-1/2"></div>
                </div>

                <!-- 2021 -->
                <div class="flex flex-col md:flex-row gap-8 items-center md:items-start group">
                    <div class="md:w-1/2 md:text-right">
                        <span class="text-3xl font-black text-primary/20 group-hover:text-accent transition-colors">2021</span>
                        <h4 class="text-xl font-black text-primary mt-2">Hermandad del Santo Rosario</h4>
                        <p class="text-muted-foreground text-sm mt-3 leading-relaxed md:ml-auto max-w-sm">The Rosary Confraternity of Pacita was formally established to propagate devotion to Our Lady. The Perpetual Novena is held every Saturday.</p>
                    </div>
                    <div class="h-4 w-4 rounded-full border-4 border-accent bg-white z-10 hidden md:block mt-8"></div>
                    <div class="md:w-1/2"></div>
                </div>

                <!-- Today -->
                <div class="flex flex-col md:flex-row-reverse gap-8 items-center md:items-start group">
                    <div class="md:w-1/2">
                        <span class="text-3xl font-black text-primary/20 group-hover:text-accent transition-colors">Today</span>
                        <h4 class="text-xl font-black text-primary mt-2">A Digital Horizon</h4>
                        <p class="text-muted-foreground text-sm mt-3 leading-relaxed max-w-sm">Embracing technology with Parish Pal to connect more parishioners and modernize our services while staying true to our core mission.</p>
                    </div>
                    <div class="h-4 w-4 rounded-full border-4 border-accent bg-white z-10 hidden md:block mt-8"></div>
                    <div class="md:w-1/2"></div>
                </div>
            </div>
        </div>

        <!-- Our Leadership -->
        <div class="mb-24">
            <div class="text-center mb-12">
                <h2 class="text-lg font-black uppercase tracking-widest text-accent mb-4">Our Leadership</h2>
                <h3 class="font-heading text-3xl md:text-4xl font-black text-primary italic">Shepherds of the Flock</h3>
            </div>

            <div class="max-w-md mx-auto">
                <div class="bg-white rounded-2xl border p-8 text-center shadow-xl">
                    <div class="h-40 w-40 rounded-full bg-muted mx-auto mb-6 border-4 border-primary/10 overflow-hidden">
                        <img src="{{ asset('storage/priest.png') }}" onerror="this.src='https://ui-avatars.com/api/?name=FR+Vicar&background=0D1B2A&color=fff&size=512'" alt="Parish Priest" class="w-full h-full object-cover">
                    </div>
                    <h4 class="font-heading text-2xl font-black text-primary">Rev. Fr. Parish Priest</h4>
                    <p class="text-xs font-black uppercase tracking-widest text-accent mt-1">Parish Priest</p>
                    <p class="text-sm text-muted-foreground mt-4 leading-relaxed italic">"Feeding the sheep and tending the flock of the Lord with love and devotion."</p>
                    <div class="mt-6 pt-6 border-t">
                        <p class="text-xs text-muted-foreground">Assigned: <span class="font-bold text-primary">2021 - Present</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Location Section -->
        <div class="mb-16">
            <div class="text-center mb-12">
                <h2 class="text-lg font-black uppercase tracking-widest text-accent mb-4">Our Location</h2>
                <h3 class="font-heading text-3xl md:text-4xl font-black text-primary">Find Us Here</h3>
            </div>

            <div class="rounded-2xl overflow-hidden shadow-lg border">
                <div id="map" class="z-0" style="width: 100%; height: 400px;"></div>
            </div>
        </div>

        <!-- Contact Info Cards -->
        <div class="grid md:grid-cols-3 gap-6 text-center mb-16">
            <div class="bg-card p-6 rounded-xl border shadow-sm">
                <div class="mb-3 text-accent flex justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 15 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/></svg>
                </div>
                <h3 class="font-bold text-lg mb-2">Address</h3>
                <p class="text-muted-foreground text-sm">1 Sto. Rosario Drive,<br>Pacita, San Pedro, Laguna</p>
            </div>
            <div class="bg-card p-6 rounded-xl border shadow-sm">
                <div class="mb-3 text-accent flex justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                </div>
                <h3 class="font-bold text-lg mb-3">Contact</h3>
                <div class="text-left space-y-2 text-sm">
                    <a href="tel:+6328869 2742" class="flex items-center gap-2 text-muted-foreground hover:text-accent transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        +63 2 8869 2742
                    </a>
                    <a href="mailto:officestorosarioparish@gmail.com" class="flex items-center gap-2 text-muted-foreground hover:text-accent transition-colors break-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        {{ config('services.parish.office_email') }}
                    </a>
                </div>
            </div>
            <div class="bg-card p-6 rounded-xl border shadow-sm">
                <div class="mb-3 text-accent flex justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <h3 class="font-bold text-lg mb-4">Office Hours</h3>
                <div class="text-left space-y-3 text-sm">
                    <div>
                        <p class="font-semibold text-primary mb-1.5">Tuesday &ndash; Saturday</p>
                        <div class="flex flex-wrap gap-1.5">
                            <span class="inline-block bg-accent/10 text-accent border border-accent/20 rounded-full px-3 py-0.5 text-xs font-medium">6:00 AM &ndash; 12:00 NN</span>
                            <span class="inline-block bg-accent/10 text-accent border border-accent/20 rounded-full px-3 py-0.5 text-xs font-medium">1:30 PM &ndash; 6:00 PM</span>
                        </div>
                    </div>
                    <div class="border-t border-muted pt-3">
                        <p class="font-semibold text-primary mb-1.5">Sunday</p>
                        <div class="flex flex-wrap gap-1.5">
                            <span class="inline-block bg-accent/10 text-accent border border-accent/20 rounded-full px-3 py-0.5 text-xs font-medium">6:00 AM &ndash; 12:00 NN</span>
                            <span class="inline-block bg-accent/10 text-accent border border-accent/20 rounded-full px-3 py-0.5 text-xs font-medium">3:00 PM &ndash; 6:00 PM</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact CTA -->
        <div class="bg-primary rounded-2xl p-12 text-center text-primary-foreground relative overflow-hidden">
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
            const map = L.map('map', { scrollWheelZoom: false }).setView([14.345435, 121.061630], 17);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            const directionsUrl = "https://www.google.com/maps/dir/?api=1&destination=14.345435,121.061630";

            const marker = L.marker([14.345435, 121.061630]).addTo(map);

            marker.bindPopup(`
                <div style="font-family: sans-serif; text-align: left;">
                    <b style="font-size: 1.1em;">Sto. Rosario Parish</b><br>
                    1 Sto. Rosario Drive,<br>
                    Pacita, San Pedro, Laguna<br>
                    📞 +63 2 8869 2742<br>
                    <div style="margin-top: 8px;">
                        <a href="${directionsUrl}" target="_blank" style="color: #2563eb; text-decoration: none; font-weight: bold; border-bottom: 1px solid #2563eb;">Get Directions ↗</a>
                    </div>
                </div>
            `).openPopup();

            setTimeout(function () {
                map.invalidateSize();
            }, 500);
        });
    </script>
</x-public-layout>