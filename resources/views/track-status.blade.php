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
                        placeholder="e.g. SRP-2026-0001 or INQ-2026-0001"
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
            <div class="bg-card border rounded-[2rem] shadow-xl overflow-hidden animate-in fade-in slide-in-from-bottom-4 duration-500 mt-8">
                <div class="p-6 text-white {{ $status == 'pending' ? 'bg-amber-500' : ($status == 'approved' || $status == 'accepted' ? 'bg-emerald-600' : 'bg-red-600') }}">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest opacity-80">Tracking Result</p>
                            <h2 class="text-2xl font-black uppercase font-heading">{{ $type }}</h2>
                            <p class="text-[10px] font-mono opacity-70">Ref: {{ $refId ?? $item->reference_id ?? substr($item->id, 0, 8) }}</p>
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
                            <p class="font-bold break-words">{{ $item->full_name ?? $item->name ?? 'N/A' }}</p>
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
                                <span class="h-2 w-2 rounded-full {{ $status == 'pending' ? 'bg-amber-500 animate-pulse' : ($status == 'approved' || $status == 'accepted' ? 'bg-emerald-500' : 'bg-red-500') }}"></span>
                                <p class="font-black uppercase text-sm {{ $status == 'pending' ? 'text-amber-500' : ($status == 'approved' || $status == 'accepted' ? 'text-emerald-500' : 'text-red-500') }}">
                                    {{ $status }}
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($status == 'rejected' && !empty($item->rejection_reason))
                        <div class="p-4 bg-red-50 border border-red-100 rounded-xl">
                            <p class="text-[10px] font-black uppercase tracking-widest text-red-600 mb-1">Rejection Reason</p>
                            <p class="text-sm font-medium text-red-800 leading-relaxed italic">"{{ $item->rejection_reason }}"</p>
                        </div>
                    @endif

                    <div class="pt-6 border-t font-italic text-sm">
                        @if($status == 'pending')
                            <p class="text-muted-foreground">Our team is currently reviewing your request. Please check back later or wait for our email/SMS notification.</p>
                        @elseif($status == 'approved' || $status == 'accepted')
                            <p class="text-emerald-700 font-bold">Your request has been approved! We look forward to seeing you. Please check your email for any final instructions.</p>
                        @elseif($status == 'rejected')
                            <p class="text-red-700 font-bold">Unfortunately, your request could not be approved at this time. Please see the reason above or contact the parish office for clarification.</p>
                        @else
                            <p class="text-muted-foreground">Status updated. Check your email for more details.</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-public-layout>
