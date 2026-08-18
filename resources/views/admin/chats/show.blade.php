<x-admin-layout>
    <div class="py-6">
        <div class="mb-6">
            @php
                $backStatus = match($chat->status) {
                    'handover' => 'handover',
                    'active', 'paused' => 'active',
                    'resolved' => 'resolved',
                    default => 'active'
                };
            @endphp
            <a href="{{ route('admin.chats.index', ['status' => $backStatus]) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-muted-foreground/50 hover:text-primary transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6" /></svg>
                Back to Active Chats
            </a>
        </div>

        <div class="grid md:grid-cols-4 gap-5 h-[700px]">
            {{-- User Panel --}}
            <div class="md:col-span-1 bg-white border border-black/[.04] rounded-2xl p-5 flex flex-col items-center text-center shadow-sm shadow-black/[.02]">
                <div class="w-16 h-16 rounded-2xl bg-[#F5F7FA] flex items-center justify-center text-2xl mb-3">
                    👤
                </div>
                <h3 class="font-bold text-primary break-all text-xs mb-0.5 uppercase tracking-tight">{{ $chat->user_ip }}</h3>
                <p class="text-[10px] text-muted-foreground/40 font-mono mb-4">ID: {{ substr($chat->session_id, 0, 8) }}...</p>
                
                <div class="w-full space-y-2.5 mt-auto pt-4 border-t border-black/[.04] text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-muted-foreground/50 font-medium">Status</span>
                        <span class="capitalize text-primary font-bold">{{ $chat->status }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-muted-foreground/50 font-medium">Messages</span>
                        <span class="text-primary font-bold">{{ $chat->messages->count() }}</span>
                    </div>
                </div>

                <div class="w-full mt-4 space-y-2 pt-4 border-t border-black/[.04]">
                    @if($chat->status === 'paused')
                        <form action="{{ route('admin.chats.resume', $chat->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-2.5 rounded-xl font-bold text-[10px] uppercase tracking-[.1em] bg-emerald-500 text-white shadow-lg shadow-emerald-500/20 hover:shadow-xl transition-all">
                                Resume Chat
                            </button>
                        </form>
                    @elseif($chat->status !== 'resolved')
                        <form action="{{ route('admin.chats.pause', $chat->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-2.5 rounded-xl font-bold text-[10px] uppercase tracking-[.1em] bg-amber-500 text-white shadow-lg shadow-amber-500/20 hover:shadow-xl transition-all">
                                Pause (AI Takeover)
                            </button>
                        </form>
                    @endif
                    
                    @if($chat->status !== 'resolved')
                        <form action="{{ route('admin.chats.resolve', $chat->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-2.5 rounded-xl font-bold text-[10px] uppercase tracking-[.1em] bg-red-500 text-white shadow-lg shadow-red-500/20 hover:shadow-xl transition-all">
                                Resolve & Close
                            </button>
                        </form>
                    @else
                        <div class="p-3 bg-[#F5F7FA] rounded-xl text-[10px] font-bold uppercase tracking-wider text-muted-foreground/40 text-center border border-black/[.04]">
                            Resolved
                        </div>
                    @endif
                </div>
            </div>

            {{-- Chat Area --}}
            <div class="md:col-span-3 bg-white border border-black/[.04] rounded-2xl flex flex-col overflow-hidden shadow-sm shadow-black/[.02]">
                <div class="flex-1 overflow-y-auto p-6 bg-[#FAFBFC] flex flex-col-reverse" id="chatContainer">
                    <div class="space-y-4" id="chatMessages">
                    @php $lastId = 0; @endphp
                    @foreach($chat->messages as $m)
                        @php $lastId = max($lastId, $m->id); @endphp
                        <div class="flex {{ $m->sender === 'admin' ? 'justify-end' : 'justify-start' }}" data-id="{{ $m->id }}">
                            <div class="max-w-[80%]">
                                <div class="text-[9px] font-black uppercase tracking-[.15em] text-muted-foreground/40 mb-1 {{ $m->sender === 'admin' ? 'text-right' : '' }}">
                                    {{ $m->sender }} · {{ $m->created_at->format('h:i A') }}
                                </div>
                                <div class="px-4 py-3 rounded-2xl text-sm leading-relaxed {{ $m->sender === 'admin' ? 'bg-primary text-primary-foreground rounded-tr-md shadow-md shadow-primary/10' : ($m->sender === 'ai' ? 'bg-white border border-black/[.06] italic rounded-tl-md' : 'bg-white border border-black/[.06] rounded-tl-md shadow-sm') }}">
                                    {{ $m->message }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
                </div>

                <div class="p-5 bg-white border-t border-black/[.04]">
                    @if($chat->status === 'paused')
                        <div class="px-4 py-3 bg-amber-50 border border-amber-200/60 rounded-xl text-xs text-amber-700 font-medium flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" /><line x1="12" y1="8" x2="12" y2="12" /><line x1="12" y1="16" x2="12.01" y2="16" /></svg>
                            Chat paused — resume to reply
                        </div>
                    @else
                        <form action="{{ route('admin.chats.reply', $chat->id) }}" method="POST" onsubmit="this.querySelector('button[type=submit]').disabled = true; this.querySelector('button[type=submit]').innerHTML = 'Sending...';">
                            @csrf
                            <div class="flex gap-3">
                                <input name="message" required placeholder="Type your reply..." 
                                       id="adminReplyInput" autocomplete="off"
                                       class="flex-1 bg-[#F5F7FA] border border-black/[.06] rounded-xl px-5 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary/30 font-medium">
                                <button type="submit" class="bg-primary text-primary-foreground px-5 py-3 rounded-xl font-bold text-sm shadow-lg shadow-primary/15 hover:shadow-xl transition-all flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 3 3 9-3 9 19-9Z" /><path d="M6 12h16" /></svg>
                                    Send
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            let typingTimeout = null;
            const input = document.getElementById('adminReplyInput');
            const messagesContainer = document.getElementById('chatMessages');
            const chatContainer = document.getElementById('chatContainer');
            const chatId = @json($chat->id);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            let lastId = @json($lastId);
            let lastMessageCount = messagesContainer?.children.length || 0;
            let isAtBottom = true;

            if (chatContainer) {
                chatContainer.addEventListener('scroll', function() {
                    isAtBottom = chatContainer.scrollHeight - chatContainer.scrollTop - chatContainer.clientHeight < 100;
                });
            }

            if (input && csrfToken) {
                input.addEventListener('input', function() {
                    if (typingTimeout) clearTimeout(typingTimeout);
                    typingTimeout = setTimeout(() => {
                        fetch('{{ route('admin.chats.typing', ':id') }}'.replace(':id', chatId), {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' }
                        }).catch(() => {});
                    }, 300);
                });
            }

            async function pollMessages() {
                try {
                    const response = await fetch('{{ route('admin.chats.poll', ':id') }}'.replace(':id', chatId) + '?last_id=' + lastId);
                    const data = await response.json();
                    if (data.messages && data.messages.length > 0) {
                        data.messages.forEach(msg => {
                            if (document.querySelector(`[data-id="${msg.id}"]`)) return;
                            const isSelf = msg.sender === 'admin';
                            const timeStr = new Date(msg.created_at).toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
                            const msgHtml = `
                                <div class="flex ${isSelf ? 'justify-end' : 'justify-start'}" data-id="${msg.id}">
                                    <div class="max-w-[80%]">
                                        <div class="text-[9px] font-black uppercase tracking-[.15em] text-muted-foreground/40 mb-1 ${isSelf ? 'text-right' : ''}">${msg.sender} · ${timeStr}</div>
                                        <div class="px-4 py-3 rounded-2xl text-sm leading-relaxed ${isSelf ? 'bg-primary text-primary-foreground rounded-tr-md shadow-md shadow-primary/10' : (msg.sender === 'ai' ? 'bg-white border border-black/[.06] italic rounded-tl-md' : 'bg-white border border-black/[.06] rounded-tl-md shadow-sm')}">${msg.message}</div>
                                    </div>
                                </div>
                            `;
                            messagesContainer.insertAdjacentHTML('beforeend', msgHtml);
                            lastId = Math.max(lastId, msg.id);
                        });
                        if (isAtBottom || data.messages.some(m => m.sender !== 'admin')) {
                            chatContainer.scrollTo({ top: chatContainer.scrollHeight, behavior: 'smooth' });
                        }
                    }
                } catch (e) { console.error('Polling error:', e); }
            }
            setInterval(pollMessages, 3000);
        })();
    </script>
</x-admin-layout>
