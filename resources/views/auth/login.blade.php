<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Sto. Rosario Parish</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-background font-sans antialiased text-foreground flex items-center justify-center min-h-screen relative overflow-hidden">
    <!-- Background Decoration -->
    <div class="absolute inset-0 z-0">
        <img src="/bg.png" alt="Background" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-primary/80 backdrop-blur-sm"></div>
    </div>

    <div class="container relative z-10 max-w-md mx-auto px-4">
        <div class="text-center mb-8">
            <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-accent/20 text-accent mb-4 backdrop-blur-md border border-accent/20">
                <span class="text-3xl font-bold">✝</span>
            </div>
            <h1 class="font-heading text-3xl font-bold text-white mb-2 shadow-sm uppercase tracking-widest">Sto Rosario Parish Admin</h1>
            <p class="text-white/60 text-sm italic italic">Welcome back, servant of God.</p>
        </div>

        <div class="bg-card/95 backdrop-blur-lg rounded-2xl border p-8 shadow-2xl">
            <h2 class="font-heading text-xl font-bold text-primary mb-6">Staff Login</h2>
            
            <form method="POST" action="/admin/login" class="space-y-5">
                @csrf
                <div class="space-y-2">
                    <label class="text-sm font-bold text-primary" for="email">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        required 
                        autofocus
                        class="flex h-11 w-full rounded-lg border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        placeholder="admin@storosario.ph"
                    />
                    @error('email')
                        <p class="text-xs text-destructive font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-bold text-primary" for="password">Password</label>
                    </div>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        class="flex h-11 w-full rounded-lg border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        placeholder="••••••••"
                    />
                    @error('password')
                        <p class="text-xs text-destructive font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <button 
                    type="submit" 
                    class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-4 py-3 text-sm font-bold text-primary-foreground transition-all hover:bg-primary/90 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 shadow-lg hover:shadow-primary/20"
                >
                    Sign In to Dashboard
                </button>
            </form>
        </div>
        
        <div class="text-center mt-8">
            <a href="/" class="text-white/60 hover:text-white text-xs font-bold transition-colors uppercase tracking-widest flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-left"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                Back to Public Site
            </a>
        </div>
    </div>
</body>
</html>
