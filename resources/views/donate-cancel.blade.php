<x-public-layout>
    <x-slot name="meta">
        <meta name="description" content="Donation cancelled. You can try again anytime.">
    </x-slot>

    {{-- Hero --}}
    <section style="background:var(--color-blue-deep);position:relative;overflow:hidden;padding:100px 24px 80px;text-align:center;">
        <div style="position:absolute;inset:0;background:radial-gradient(ellipse 70% 60% at 50% 110%,rgba(245,197,24,0.06) 0%,transparent 70%);pointer-events:none;"></div>
        <div style="position:relative;z-index:10;max-width:600px;margin:0 auto;">
            <div style="font-size:10px;font-weight:600;letter-spacing:.32em;text-transform:uppercase;color:rgba(245,197,24,0.6);margin-bottom:18px;">Donation Cancelled</div>
            <h1 class="font-heading" style="font-weight:700;font-style:italic;font-size:clamp(2rem,5vw,3.5rem);line-height:1.1;color:#fff;margin-bottom:16px;">
                No Worries
            </h1>
            <div style="width:56px;height:1px;margin:0 auto 20px;background:linear-gradient(90deg,transparent,rgba(245,197,24,0.4),transparent);"></div>
            <p style="color:rgba(235,242,255,0.6);font-size:clamp(0.85rem,1.4vw,1rem);line-height:1.78;font-weight:300;">
                Your donation was not processed. You can try again whenever you're ready.
            </p>
        </div>
    </section>

    {{-- Cancel Card --}}
    <div style="max-width:520px;margin:-40px auto 80px;padding:0 24px;position:relative;z-index:20;">
        <div style="background:#fff;border-radius:24px;border:1px solid rgba(26,64,128,0.1);box-shadow:0 8px 40px rgba(13,42,82,0.1);padding:48px 36px;text-align:center;">

            {{-- Icon --}}
            <div style="width:80px;height:80px;border-radius:50%;background:rgba(245,197,24,0.08);display:flex;align-items:center;justify-content:center;margin:0 auto 24px;">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="rgba(245,197,24,0.7)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="m15 9-6 6"/>
                    <path d="m9 9 6 6"/>
                </svg>
            </div>

            <h2 class="font-heading" style="font-size:1.5rem;font-weight:700;color:var(--color-blue-deep,#0D2A52);margin-bottom:8px;">Donation Cancelled</h2>
            <p style="font-size:.9rem;color:rgba(13,42,82,0.5);line-height:1.7;margin-bottom:32px;">
                The payment process was interrupted or cancelled. No amount was charged to your account.
            </p>

            {{-- Actions --}}
            <div style="display:flex;flex-direction:column;gap:12px;">
                <a href="{{ route('donate') }}" style="display:block;padding:14px 24px;border-radius:14px;background:linear-gradient(135deg,#F5C518 0%,#FFD740 100%);color:#0D2A52;font-size:14px;font-weight:800;text-decoration:none;text-align:center;transition:all .3s;box-shadow:0 4px 20px rgba(245,197,24,0.25);"
                   onmouseenter="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 32px rgba(245,197,24,0.4)'"
                   onmouseleave="this.style.transform='none';this.style.boxShadow='0 4px 20px rgba(245,197,24,0.25)'">
                    Try Again
                </a>
                <a href="{{ route('home') }}" style="display:block;padding:12px 24px;border-radius:12px;background:transparent;border:1.5px solid rgba(26,64,128,0.12);color:rgba(13,42,82,0.5);font-size:13px;font-weight:600;text-decoration:none;text-align:center;transition:all .2s;"
                   onmouseenter="this.style.borderColor='rgba(26,64,128,0.25)'"
                   onmouseleave="this.style.borderColor='rgba(26,64,128,0.12)'">
                    Return to Home
                </a>
            </div>
        </div>
    </div>
</x-public-layout>
