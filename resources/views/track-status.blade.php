<x-public-layout>
    <div class="container py-12 mx-auto px-4 max-w-2xl">
        <div class="text-center mb-12">
            <h1 class="font-heading text-4xl font-black mb-4 text-primary italic uppercase">Track Your Status</h1>
            <div class="h-1 w-20 bg-accent mx-auto rounded-full mb-6"></div>
            <p class="text-muted-foreground">Enter your reference ID to check the status of your sacramental inquiry or mass intention.</p>
        </div>

        <div class="bg-card border rounded-[2rem] shadow-xl p-8 mb-12">
            <form action="{{ route('track.post') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="reference_id" class="block text-sm font-bold text-primary uppercase tracking-widest mb-2">Reference ID</label>
                    <input type="text" name="reference_id" id="reference_id" required 
                        class="w-full px-6 py-4 rounded-2xl border-2 border-muted bg-muted/30 focus:border-accent focus:ring-0 transition-all font-mono text-lg"
                        placeholder="e.g. 550E8400..."
                        value="{{ old('reference_id') }}">
                    @error('reference_id')
                        <p class="text-destructive text-xs mt-2 font-bold">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-primary-foreground font-black py-4 rounded-2xl shadow-lg shadow-primary/20 transition-all active:scale-95 uppercase tracking-widest">
                    Check Status
                </button>
            </form>
        </div>

        @if(isset($status))
            <div class="bg-card border rounded-[2rem] shadow-xl overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div class="bg-primary p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest opacity-60">Tracking Result</p>
                            <h2 class="text-2xl font-black uppercase font-heading">{{ $type }}</h2>
                        </div>
                        <div class="px-4 py-2 rounded-full bg-white/20 backdrop-blur-md text-xs font-black uppercase tracking-tighter">
                            {{ $status }}
                        </div>
                    </div>
                </div>
                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1">Name</p>
                            <p class="font-bold break-words">{{ $item->full_name }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1">Submitted On</p>
                            <p class="font-bold">{{ $item->created_at->format('M d, Y') }}</p>
                        </div>
                        @if($date)
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1">Preferred Date</p>
                            <p class="font-bold">{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</p>
                        </div>
                        @endif
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-muted-foreground mb-1">Current Status</p>
                            <div class="flex items-center gap-2">
                                <span class="h-2 w-2 rounded-full {{ $status == 'pending' ? 'bg-orange-500' : ($status == 'accepted' ? 'bg-green-500' : 'bg-primary') }} animate-pulse"></span>
                                <p class="font-black uppercase text-sm {{ $status == 'pending' ? 'text-orange-500' : ($status == 'accepted' ? 'text-green-500' : 'text-primary') }}">
                                    {{ $status }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t font-italic text-muted-foreground text-sm">
                        @if($status == 'pending')
                            Our team is currently reviewing your request. Please check back later or wait for our email/SMS notification.
                        @elseif($status == 'accepted')
                            Your request has been accepted! Please proceed as instructed in our latest communication.
                        @else
                            Status updated. Check your email for more details.
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-public-layout>
