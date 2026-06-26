<footer class="border-t bg-primary text-primary-foreground" style="background-color:#0d2a52;">
    <div class="container py-12 mx-auto px-4">
        <div class="grid md:grid-cols-3 gap-8">
            <div>
                <div class="footer-brand">
                    <span class="footer-brand-cross">✝</span>
                    <span class="font-heading text-xl font-bold">Sto. Rosario Parish</span>
                </div>
                <p class="text-primary-foreground/70 text-base leading-relaxed">
                    Serving the faithful community with love and devotion. Join us in prayer and worship.
                </p>
            </div>
            <div>
                <h4 class="footer-heading font-heading font-semibold">Quick Links</h4>
                <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                    <a href="{{ route('mass-schedule') }}" class="footer-link text-sm">Mass Schedule</a>
                    <a href="{{ route('submit-intention') }}" class="footer-link text-sm">Submit Intention</a>
                    <a href="{{ route('inquiry') }}" class="footer-link text-sm">Sacramental Inquiry</a>
                    <a href="{{ route('events') }}" class="footer-link text-sm">Parish Events</a>
                    <a href="{{ route('gallery') }}" class="footer-link text-sm">Photo Gallery</a>

                    <a href="{{ route('track') }}" class="footer-link text-sm">Track Submission</a>
                    <a href="{{ route('about') }}" class="footer-link text-sm">About Us</a>
                    <a href="{{ route('donate') }}" class="footer-link text-sm">Donate</a>
                </div>
            </div>
            <div>
                <h4 class="footer-heading font-heading font-semibold">Contact</h4>
                <p class="footer-contact text-base">
                    <a href="https://www.facebook.com/storosarioparishpacita/">Sto. Rosario Parish Pacita 1</a><br />
                    (02) 8869 2742 | 0906 099 2324<br />
                    Sunday Mass: 6:30 AM, 8:30 AM, 10:00 AM, 3:00 PM, 4:30 PM, 6:00 PM
                </p>
            </div>
        </div>
        <div class="footer-bottom flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left">
            <p>© {{ date('Y') }} Sto. Rosario Parish. Developed for the Greater Glory of God.</p>
            <p class="footer-location">Pacita Complex, San Pedro, Laguna</p>
        </div>
    </div>
</footer>
