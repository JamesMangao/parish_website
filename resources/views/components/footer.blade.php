<style>
    :root {
        --primary:            #0d2a52;
        --primary-foreground: #EBF2FF;
        --accent:             #F5C518;
    }
</style>

<footer class="border-t bg-primary text-primary-foreground" style="background-color:#0d2a52;">
    <div class="container py-12 mx-auto px-4">
        <div class="grid md:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-2xl text-accent">✝</span>
                    <span class="font-heading text-xl font-bold">Sto. Rosario Parish</span>
                </div>
                <p class="text-primary-foreground/70 text-sm leading-relaxed">
                    Serving the faithful community with love and devotion. Join us in prayer and worship.
                </p>
            </div>
            <div>
                <h4 class="font-heading font-semibold mb-4 text-accent">Quick Links</h4>
                <div class="grid grid-cols-2 gap-x-4 gap-y-2">
                    <a href="/mass-schedule" class="text-xs text-primary-foreground/70 hover:text-primary-foreground transition-colors">Mass Schedule</a>
                    <a href="/submit-intention" class="text-xs text-primary-foreground/70 hover:text-primary-foreground transition-colors">Submit Intention</a>
                    <a href="/inquiry" class="text-xs text-primary-foreground/70 hover:text-primary-foreground transition-colors">Sacramental Inquiry</a>
                    <a href="/events" class="text-xs text-primary-foreground/70 hover:text-primary-foreground transition-colors">Parish Events</a>
                    <a href="/gallery" class="text-xs text-primary-foreground/70 hover:text-primary-foreground transition-colors">Photo Gallery</a>

                    <a href="/track" class="text-xs text-primary-foreground/70 hover:text-primary-foreground transition-colors">Track Submission</a>
                    <a href="/about" class="text-xs text-primary-foreground/70 hover:text-primary-foreground transition-colors">About Us</a>
                    <a href="/donate" class="text-xs text-primary-foreground/70 hover:text-primary-foreground transition-colors">Donate</a>
                    <a href="/admin/login" class="text-xs text-primary-foreground/40 hover:text-accent transition-colors">Admin Portal</a>
                </div>
            </div>
            <div>
                <h4 class="font-heading font-semibold mb-4 text-accent">Contact</h4>
                <p class="text-sm text-primary-foreground/70 leading-relaxed">
                    <a href="https://www.facebook.com/storosarioparishpacita/">Sto. Rosario Parish Pacita 1</a><br />
                    (02) 8869 2742 | 0906 099 2324<br />
                    Sunday Mass: 6:30 AM, 8:30 AM, 10:00 AM, 3:00 PM, 4:30 PM, 6:00 PM
                </p>
            </div>
        </div>
        <div class="mt-8 pt-8 border-t border-primary-foreground/10 flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left text-[10px] text-primary-foreground/50">
            <p>© {{ date('Y') }} Sto. Rosario Parish. Developed for the Greater Glory of God.</p>
            <p class="uppercase tracking-widest font-black opacity-30 italic">Pacita Complex, San Pedro, Laguna</p>
        </div>
    </div>
</footer>