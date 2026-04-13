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

        <div class="grid md:grid-cols-2 gap-12 items-center mb-16">
            <div>
                <img src="/bg.png" alt="Sto. Rosario Parish Church"
                    class="rounded-xl shadow-lg w-full h-auto object-cover" style="max-height: 400px;">
            </div>
            <div>
                <h2 class="font-heading text-2xl md:text-3xl font-bold text-primary mb-4">Our Mission & Vision</h2>
                <p class="text-muted-foreground mb-4 leading-relaxed">
                    Our mission is to build a deeply prayerful and actively serving community. We strive to be a welcoming home where everyone can encounter Christ through the sacraments, fellowship, and acts of charity.
                </p>
                <p class="text-muted-foreground leading-relaxed">
                    As a parish under the patronage of Our Lady of the Most Holy Rosary, we are inspired by her obedience and profound trust in God's plan.
                </p>
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

        <!-- Connect With Us -->
        <div class="mt-16">
            <h2 class="font-heading text-3xl font-bold text-primary mb-2 text-center">
                <br>Connect With Us
            </h2>
            <p class="text-center text-muted-foreground mb-8 text-sm">Follow us on our social media channels and stay updated.</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                <!-- Facebook -->
                <a href="https://www.facebook.com/storosarioparishpacita1" target="_blank"
                    class="group flex flex-col items-center gap-3 bg-card border rounded-xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background: #1877F2;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="white">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                        </svg>
                    </div>
                    <div class="text-center">
                        <p class="font-semibold text-sm">Facebook</p>
                        <p class="text-xs text-muted-foreground">@storosarioparishpacita1</p>
                    </div>
                </a>

                <!-- Instagram -->
                <a href="https://www.instagram.com/storosarioparish_pacita" target="_blank"
                    class="group flex flex-col items-center gap-3 bg-card border rounded-xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center"
                        style="background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                            stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="20" height="20" x="2" y="2" rx="5" ry="5" />
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                            <line x1="17.5" x2="17.51" y1="6.5" y2="6.5" />
                        </svg>
                    </div>
                    <div class="text-center">
                        <p class="font-semibold text-sm">Instagram</p>
                        <p class="text-xs text-muted-foreground">@storosarioparish_pacita</p>
                    </div>
                </a>

                <!-- YouTube -->
                <a href="https://www.youtube.com/@sto.rosarioparishpacita1983" target="_blank"
                    class="group flex flex-col items-center gap-3 bg-card border rounded-xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background: #FF0000;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="white">
                            <path
                                d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 0 0 1.46 6.42 29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z" />
                            <polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="#FF0000" />
                        </svg>
                    </div>
                    <div class="text-center">
                        <p class="font-semibold text-sm">YouTube</p>
                        <p class="text-xs text-muted-foreground">@sto.rosarioparishpacita</p>
                    </div>
                </a>

                <!-- Spotify -->
                <a href="https://open.spotify.com/user/Sto.%20Rosario%20Parish%20-%20Pacita" target="_blank"
                    class="group flex flex-col items-center gap-3 bg-card border rounded-xl p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background: #1DB954;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="white">
                            <circle cx="12" cy="12" r="10" fill="#1DB954" />
                            <path
                                d="M16.5 16.3c-.2.3-.6.4-.9.2-2.5-1.5-5.6-1.9-9.3-1-.4.1-.7-.2-.8-.5-.1-.4.2-.7.5-.8 4-1 7.5-.5 10.3 1.2.4.2.5.5.2.9zm1.1-2.6c-.2.4-.7.5-1 .3-2.9-1.8-7.2-2.3-10.6-1.2-.4.1-.9-.1-1-.5-.1-.4.1-.9.5-1 3.8-1.2 8.6-.6 11.8 1.4.4.2.5.6.3 1zm.1-2.6c-3.4-2-8.9-2.2-12.1-1.2-.5.1-1-.2-1.1-.7-.1-.5.2-1 .7-1.1 3.7-1.1 9.8-.9 13.6 1.4.4.3.6.8.3 1.2-.2.5-.9.7-1.4.4z" />
                        </svg>
                    </div>
                    <div class="text-center">
                        <p class="font-semibold text-sm">Spotify</p>
                        <p class="text-xs text-muted-foreground">Sto. Rosario Parish</p>
                    </div>
                </a>

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