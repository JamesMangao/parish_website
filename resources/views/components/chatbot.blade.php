<div x-data="chatbot()" x-init="init()" class="fixed bottom-6 right-6 z-[100]">
    <!-- Trigger Button -->
    <button 
        @click="toggle()" 
        class="h-14 w-14 rounded-full bg-accent text-accent-foreground shadow-2xl flex items-center justify-center hover:scale-110 transition-transform active:scale-95 border-2 border-white/20 relative"
        aria-label="Toggle Chatbot"
    >
        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle-heart"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/><path d="M12 15a3.2 3.2 0 0 0 4.5-4.5l-4.5-4.5-4.5 4.5a3.2 3.2 0 0 0 4.5 4.5Z"/></svg>
        <svg x-show="open" x-cloak xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6L6 18"/><path d="M6 6l12 12"/></svg>
        <!-- Unread badge -->
        <span x-show="!open && unreadCount > 0" x-cloak
            class="absolute -top-1 -right-1 h-5 w-5 rounded-full bg-red-500 text-white text-[10px] font-bold flex items-center justify-center animate-pulse"
            x-text="unreadCount"></span>
    </button>

    <!-- Chat Window -->
    <div 
        x-show="open"
        x-cloak 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-10 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-10 scale-95"
        class="absolute bottom-20 right-0 bg-white rounded-2xl shadow-2xl border flex flex-col overflow-hidden"
        style="width: 410px; height: 600px;"
    >
        <!-- Header -->
        <div class="p-4 bg-primary text-primary-foreground flex items-center gap-3">
            <div class="h-10 w-10 rounded-full bg-white/20 flex items-center justify-center text-xl font-bold relative">
                ✝
                <!-- Status dot -->
                <span class="absolute -bottom-0.5 -right-0.5 h-3 w-3 rounded-full border-2 border-primary"
                    :class="{
                        'bg-emerald-400': liveAgentStatus === 'none' || liveAgentStatus === 'suggesting',
                        'bg-amber-400 animate-pulse': liveAgentStatus === 'waiting',
                        'bg-blue-400': liveAgentStatus === 'connected'
                    }"></span>
            </div>
            <div>
                <h3 class="font-bold text-sm leading-none">SRP AI Assistant</h3>
                <p class="text-[10px] mt-1 uppercase tracking-widest font-bold"
                   :class="{
                       'opacity-70': liveAgentStatus === 'none' || liveAgentStatus === 'suggesting',
                       'text-amber-200': liveAgentStatus === 'waiting',
                       'text-blue-200': liveAgentStatus === 'connected'
                   }"
                   x-text="statusLabel"></p>
            </div>
        </div>

        <!-- Messages -->
        <div x-ref="msgContainer" class="flex-1 overflow-y-auto p-4 space-y-4 bg-muted/10">
            <template x-for="(msg, index) in messages" :key="msg._id">
                <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                    <div class="max-w-[85%] space-y-1">
                        <div 
                            :class="msg.role === 'user' ? 'bg-primary text-primary-foreground rounded-tr-none ml-auto' : (msg.type === 'system' ? 'bg-muted text-muted-foreground text-[10px] text-center italic border-none shadow-none mx-auto py-1' : 'bg-white border text-primary rounded-tl-none font-medium')"
                            class="p-3 rounded-2xl text-sm shadow-sm leading-relaxed"
                            x-html="formatMessage(msg.content, msg.role)"
                        ></div>

                        <!-- Timestamp -->
                        <div class="text-[9px] px-1 opacity-40 font-medium"
                             :class="msg.role === 'user' ? 'text-right' : 'text-left'"
                             x-text="formatTime(msg.time)"
                             x-show="msg.type !== 'system'"></div>

                        <!-- Special Interaction: Handover Prompt -->
                        <template x-if="msg.type === 'handover_prompt' && !msg.handoverActioned">
                            <div class="flex flex-col gap-2 mt-2">
                                <a href="/inquiry" class="text-center px-4 py-2 border border-primary text-primary rounded-xl text-xs font-bold hover:bg-primary hover:text-white transition-all">
                                    Submit Inquiry Form
                                </a>
                                <button @click="requestAgent(msg)" class="px-4 py-2 bg-accent text-accent-foreground rounded-xl text-xs font-bold shadow-md hover:opacity-90 transition-all">
                                    Wait for Live Agent
                                </button>
                            </div>
                        </template>

                        <!-- Special Interaction: Error with Retry -->
                        <template x-if="msg.type === 'error'">
                            <div class="mt-2">
                                <button @click="retryLastMessage(msg)" class="px-4 py-2 border border-red-300 text-red-600 rounded-xl text-xs font-bold hover:bg-red-50 transition-all flex items-center gap-1.5 mx-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 16h5v5"/></svg>
                                    Retry
                                </button>
                            </div>
                        </template>

                        <!-- Special Interaction: Timeout -->
                        <template x-if="msg.type === 'timeout'">
                            <div class="mt-2">
                                <a href="/inquiry" class="block text-center px-4 py-2 bg-primary text-primary-foreground rounded-xl text-xs font-bold shadow-md">
                                    Go to Inquiry Page
                                </a>
                            </div>
                        </template>
                    </div>
                </div>
            </template>

            <!-- AI/Agent Typing Indicator -->
            <div x-show="loading || agentTyping" x-cloak class="flex justify-start">
                <div class="bg-white border p-3 rounded-2xl rounded-tl-none flex items-center gap-2">
                    <span class="h-1.5 w-1.5 rounded-full bg-accent animate-bounce"></span>
                    <span class="h-1.5 w-1.5 rounded-full bg-accent animate-bounce" style="animation-delay: 0.1s"></span>
                    <span class="h-1.5 w-1.5 rounded-full bg-accent animate-bounce" style="animation-delay: 0.2s"></span>
                    <span x-show="agentTyping" x-cloak class="text-[10px] text-muted-foreground ml-1 italic">Agent is typing</span>
                </div>
            </div>
        </div>

        <!-- Input -->
        <div class="p-3 bg-white border-t">
            <!-- Waiting for agent banner -->
            <div x-show="liveAgentStatus === 'waiting'" x-cloak
                 class="mb-2 px-3 py-2 bg-amber-50 border border-amber-200 rounded-xl flex items-center gap-2">
                <span class="h-2 w-2 rounded-full bg-amber-400 animate-pulse shrink-0"></span>
                <span class="text-[11px] text-amber-700 font-medium">Waiting for a parish representative to connect…</span>
            </div>

            <form @submit.prevent="sendMessage" class="flex gap-2">
                <div class="flex-1 relative">
                    <input 
                        x-model="userInput" 
                        x-ref="chatInput"
                        type="text"
                        maxlength="1000"
                        :placeholder="inputPlaceholder"
                        :disabled="inputDisabled"
                        class="w-full bg-muted/30 border-none focus:ring-2 focus:ring-accent rounded-xl px-4 py-2 text-sm text-primary font-medium disabled:opacity-50 disabled:cursor-not-allowed pr-14"
                    />
                    <!-- Character counter -->
                    <span x-show="userInput.length > 0" x-cloak
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-[9px] font-mono tabular-nums"
                        :class="userInput.length > 900 ? (userInput.length > 980 ? 'text-red-500 font-bold' : 'text-amber-500') : 'text-muted-foreground/50'"
                        x-text="userInput.length + '/1000'"></span>
                </div>
                <button type="submit" 
                    :disabled="!canSend"
                    class="p-2 bg-accent text-accent-foreground rounded-xl shadow-lg transition-all"
                    :class="canSend ? 'hover:opacity-90 hover:scale-105' : 'opacity-40 cursor-not-allowed'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-send-horizontal"><path d="m3 3 3 9-3 9 19-9Z"/><path d="M6 12h16"/></svg>
                </button>
            </form>
        </div>
    </div>

    <script>
        function chatbot() {
            let _msgId = 0;

            return {
                open: false,
                messages: [],
                userInput: '',
                loading: false,
                liveAgentStatus: 'none',
                agentName: '',
                agentTyping: false,
                lastMessageId: 0,
                pollInterval: null,
                waitTimer: null,
                waitCounter: 0,
                unreadCount: 0,
                _lastFailedMessage: null,
                _sessionKey: 'srp_chatbot_state',

                init() {
                    this.loadState();
                    // If no saved messages, show welcome
                    if (this.messages.length === 0) {
                        this.messages.push(this._makeMsg('assistant', 'Peace be with you! I am SRP Assistant, your AI assistant for Sto. Rosario Parish. How can I help you today?'));
                    }
                    // Resume polling if we were in a live agent state
                    if (this.liveAgentStatus === 'waiting' || this.liveAgentStatus === 'connected') {
                        this.startPolling();
                    }
                    if (this.liveAgentStatus === 'waiting') {
                        this.startTimeout();
                    }
                    // Watch for state changes to persist
                    this.$watch('messages', () => this.saveState(), { deep: true });
                    this.$watch('liveAgentStatus', () => this.saveState());
                    this.$watch('lastMessageId', () => this.saveState());
                },

                toggle() {
                    this.open = !this.open;
                    if (this.open) {
                        this.unreadCount = 0;
                        this.$nextTick(() => this.scrollToBottom());
                    }
                },

                get statusLabel() {
                    switch (this.liveAgentStatus) {
                        case 'waiting': return 'Connecting to agent…';
                        case 'connected': return 'Live Agent Connected';
                        default: return 'Sto. Rosario Parish';
                    }
                },

                get inputPlaceholder() {
                    if (this.loading) return 'Processing…';
                    if (this.liveAgentStatus === 'waiting') return 'Waiting for agent to connect…';
                    return 'Ask something…';
                },

                get inputDisabled() {
                    return this.loading || this.liveAgentStatus === 'waiting';
                },

                get canSend() {
                    return this.userInput.trim().length > 0 && !this.loading && this.liveAgentStatus !== 'waiting';
                },

                _makeMsg(role, content, type = null) {
                    return { _id: ++_msgId, role, content, type, time: Date.now(), handoverActioned: false };
                },

                async sendMessage() {
                    if (!this.canSend) return;
                    const text = this.userInput.trim();
                    this.messages.push(this._makeMsg('user', text));
                    this.userInput = '';
                    this._lastFailedMessage = text;
                    this.loading = true;
                    this.scrollToBottom();
                    try {
                        const response = await fetch('/api/chatbot', {
                            method: 'POST',
                            headers: { 
                                'Content-Type': 'application/json', 
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content 
                            },
                            body: JSON.stringify({ message: text })
                        });
                        if (!response.ok) throw new Error('HTTP ' + response.status);
                        const data = await response.json();
                        if (data.status === 'suggest_handover') {
                            this.liveAgentStatus = 'suggesting';
                            this.messages.push(this._makeMsg('assistant', data.message, 'handover_prompt'));
                        } else if (data.status === 'waiting_for_agent') {
                            this.messages.push(this._makeMsg('assistant', data.message));
                        } else {
                            this.messages.push(this._makeMsg('assistant', data.message));
                        }
                        this._lastFailedMessage = null;
                    } catch (e) {
                        this.messages.push(this._makeMsg('assistant', 'I am sorry, I am having trouble connecting to the parish servers right now.', 'error'));
                    } finally {
                        this.loading = false;
                        this.scrollToBottom();
                    }
                },

                async retryLastMessage(errorMsg) {
                    // Remove the error message
                    this.messages = this.messages.filter(m => m._id !== errorMsg._id);
                    if (this._lastFailedMessage) {
                        this.userInput = this._lastFailedMessage;
                        await this.sendMessage();
                    }
                },

                async requestAgent(handoverMsg) {
                    // Mark the handover prompt as actioned so buttons disappear
                    if (handoverMsg) handoverMsg.handoverActioned = true;
                    this.loading = true;
                    try {
                        await fetch('/api/chatbot/request-agent', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
                        });
                        this.liveAgentStatus = 'waiting';
                        this.messages.push(this._makeMsg('assistant', 'Connecting you to a parish representative… Please stay on the line.', 'system'));
                        this.startPolling();
                        this.startTimeout();
                    } catch (e) {
                        this.messages.push(this._makeMsg('assistant', 'Could not request a live agent. Please try again.', 'error'));
                    } finally {
                        this.loading = false;
                        this.scrollToBottom();
                    }
                },

                startPolling() {
                    if (this.pollInterval) clearInterval(this.pollInterval);
                    this.pollInterval = setInterval(async () => {
                        try {
                            const response = await fetch('/api/chatbot/poll?last_id=' + this.lastMessageId);
                            const data = await response.json();
                            if (data.messages && data.messages.length > 0) {
                                data.messages.forEach(msg => {
                                    this.messages.push(this._makeMsg('assistant', msg.message));
                                    this.lastMessageId = Math.max(this.lastMessageId, msg.id);
                                });
                                if (!this.open) this.unreadCount += data.messages.length;
                                this.scrollToBottom();
                            }
                            if (data.agent_connected && this.liveAgentStatus !== 'connected') {
                                this.liveAgentStatus = 'connected';
                                this.messages.push(this._makeMsg('assistant', 'A parish representative has connected.', 'system'));
                                clearInterval(this.waitTimer);
                                this.scrollToBottom();
                            }
                            // Agent typing indicator from backend
                            this.agentTyping = !!data.agent_typing;
                        } catch (e) {}
                    }, 3000);
                },

                startTimeout() {
                    this.waitCounter = 0;
                    if (this.waitTimer) clearInterval(this.waitTimer);
                    this.waitTimer = setInterval(() => {
                        this.waitCounter += 5;
                        if (this.waitCounter >= 120 && this.liveAgentStatus === 'waiting') {
                            this.messages.push(this._makeMsg('assistant', 'No agents are available at the moment. Please use the inquiry page to leave a message.', 'timeout'));
                            this.liveAgentStatus = 'none';
                            clearInterval(this.waitTimer);
                            clearInterval(this.pollInterval);
                            this.scrollToBottom();
                        }
                    }, 5000);
                },

                scrollToBottom() {
                    this.$nextTick(() => {
                        const container = this.$refs.msgContainer;
                        if (container) container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' });
                    });
                },

                formatTime(timestamp) {
                    if (!timestamp) return '';
                    const d = new Date(timestamp);
                    const now = new Date();
                    const time = d.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
                    // Show date if not today
                    if (d.toDateString() !== now.toDateString()) {
                        return d.toLocaleDateString([], { month: 'short', day: 'numeric' }) + ', ' + time;
                    }
                    return time;
                },

                formatMessage(content, role) {
                    if (!content) return '';
                    let text = content.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                    if (role === 'assistant' && text.includes('[[HANDOVER]]')) {
                        this.liveAgentStatus = 'suggesting';
                        text = text.replace('[[HANDOVER]]', '');
                    }
                    // Format [Link Name](/url) -> <a>
                    text = text.replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" class="text-accent underline font-bold hover:text-primary transition-colors">$1</a>');
                    // Handle [Link name to /url]
                    text = text.replace(/\[([^\]]*?)(?:to\s+)?(\/[a-z\-/]+)\]/gi, '<a href="$2" class="text-accent underline font-bold hover:text-primary transition-colors">$1 $2</a>');
                    return text.replace(/\n/g, '<br>');
                },

                saveState() {
                    try {
                        const state = {
                            messages: this.messages,
                            liveAgentStatus: this.liveAgentStatus,
                            lastMessageId: this.lastMessageId,
                            _msgId: _msgId
                        };
                        sessionStorage.setItem(this._sessionKey, JSON.stringify(state));
                    } catch (e) {}
                },

                loadState() {
                    try {
                        const raw = sessionStorage.getItem(this._sessionKey);
                        if (raw) {
                            const state = JSON.parse(raw);
                            this.messages = state.messages || [];
                            this.liveAgentStatus = state.liveAgentStatus || 'none';
                            this.lastMessageId = state.lastMessageId || 0;
                            _msgId = state._msgId || 0;
                        }
                    } catch (e) {}
                }
            }
        }
    </script>
</div>
