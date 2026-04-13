<x-public-layout>
    <div class="container py-12 mx-auto px-4 max-w-md">
        <div class="bg-card border rounded-[2.5rem] shadow-2xl p-10 animate-in zoom-in-95 duration-500">
            <div class="text-center mb-8">
                <div class="h-16 w-16 rounded-2xl bg-primary/10 flex items-center justify-center text-primary mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <h1 class="text-3xl font-black text-primary uppercase font-heading">Verify Login</h1>
                <p class="text-sm text-muted-foreground mt-2 italic">We've sent a 6-digit verification code to your email. Please enter it below to continue.</p>
            </div>

            <form action="{{ route('admin.2fa.verify') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground mb-4 text-center">Verification Code</label>
                    <div class="flex justify-center">
                        <input type="text" name="code" maxlength="6" required autofocus
                            class="w-full text-center tracking-[1em] text-3xl font-black py-4 rounded-2xl border-2 border-muted bg-muted/30 focus:border-primary focus:ring-0 transition-all font-mono">
                    </div>
                    @error('code')
                        <p class="text-destructive text-xs mt-4 text-center font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white font-black py-4 rounded-2xl shadow-lg shadow-primary/20 transition-all active:scale-95 uppercase tracking-widest">
                    Verify & Access Dashboard
                </button>
            </form>

            <div class="mt-8 pt-8 border-t text-center">
                <form action="{{ route('admin.2fa.resend') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-xs font-bold text-muted-foreground hover:text-primary transition-colors uppercase tracking-widest">
                        Didn't receive code? <span class="text-accent underline">Resend now</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-public-layout>
