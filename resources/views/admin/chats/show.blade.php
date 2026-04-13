<x-admin-layout>
    <div class="py-8">
        <div class="mb-4">
            <a href="{{ route('admin.chats.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-muted-foreground hover:text-primary transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Back to Active Chats
            </a>
        </div>

        <div class="grid md:grid-cols-4 gap-6 h-[700px]">
            <!-- User Status Panel -->
            <div class="md:col-span-1 bg-card border rounded-2xl p-6 flex flex-col items-center text-center shadow-sm">
                <div class="h-20 w-20 rounded-full bg-muted flex items-center justify-center text-3xl font-bold mb-4 shadow-inner">
                    👤
                </div>
                <h3 class="font-bold text-primary break-all text-sm mb-1 uppercase tracking-tight">{{ $chat->user_ip }}</h3>
                <p class="text-[10px] text-muted-foreground mb-4 font-mono">Visitor Session ID: {{ substr($chat->session_id, 0, 8) }}...</p>
                
                <div class="w-full space-y-3 mt-auto pt-6 border-t font-medium text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-muted-foreground font-semibold">Status:</span>
                        <span class="capitalize text-primary font-bold">{{ $chat->status }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-muted-foreground font-semibold">Total Messages:</span>
                        <span class="text-primary font-bold">{{ $chat->messages->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="md:col-span-3 bg-card border rounded-2xl flex flex-col overflow-hidden shadow-sm">
                <!-- Chat Messages -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-muted/5 flex flex-col-reverse">
                    <div class="space-y-4">
                    @foreach($chat->messages->reverse() as $m)
                        <div class="flex {{ $m->sender === 'admin' ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[80%]">
                                <div class="text-[9px] font-black uppercase tracking-widest text-muted-foreground mb-1 {{ $m->sender === 'admin' ? 'text-right' : '' }}">
                                    {{ $m->sender }} • {{ $m->created_at->format('h:i A') }}
                                </div>
                                <div class="p-4 rounded-2xl text-sm leading-relaxed {{ $m->sender === 'admin' ? 'bg-primary text-primary-foreground rounded-tr-none shadow-md shadow-primary/10' : ($m->sender === 'ai' ? 'bg-muted/30 border italic rounded-tl-none' : 'bg-white border font-medium rounded-tl-none shadow-sm') }}">
                                    {{ $m->message }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
                </div>

                <!-- Admin Reply Input -->
                <div class="p-6 bg-white border-t">
                    <form action="{{ route('admin.chats.reply', $chat->id) }}" method="POST">
                        @csrf
                        <div class="flex gap-4">
                            <input name="message" required placeholder="Type your reply to the parishioner..." class="flex-1 bg-muted/20 border-none rounded-xl px-5 py-3 text-sm focus:ring-2 focus:ring-primary font-medium">
                            <button type="submit" class="bg-primary text-primary-foreground px-6 py-3 rounded-xl font-bold text-sm shadow-lg hover:opacity-90 transition-all flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-send-horizontal"><path d="m3 3 3 9-3 9 19-9Z"/><path d="M6 12h16"/></svg>
                                Send Reply
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
