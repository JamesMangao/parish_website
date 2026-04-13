<div x-data="chatbot()" class="fixed bottom-6 right-6 z-[100]">
    <!-- Trigger Button -->
    <button 
        @click="open = !open" 
        class="h-14 w-14 rounded-full bg-accent text-accent-foreground shadow-2xl flex items-center justify-center hover:scale-110 transition-transform active:scale-95 border-2 border-white/20"
        aria-label="Toggle Chatbot"
    >
        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle-heart"><path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/><path d="M12 15a3.2 3.2 0 0 0 4.5-4.5l-4.5-4.5-4.5 4.5a3.2 3.2 0 0 0 4.5 4.5Z"/></svg>
        <svg x-show="open" x-cloak xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x"><path d="M18 6L6 18"/><path d="M6 6l12 12"/></svg>
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
            <div class="h-10 w-10 rounded-full bg-white/20 flex items-center justify-center text-xl font-bold">✝</div>
            <div>
                <h3 class="font-bold text-sm leading-none">SRP AI Assistant</h3>
                <p class="text-[10px] opacity-70 mt-1 uppercase tracking-widest font-bold">Sto. Rosario Parish</p>
            </div>
        </div>

        <!-- Messages -->
        <div x-ref="msgContainer" class="flex-1 overflow-y-auto p-4 space-y-4 bg-muted/10">
            <template x-for="(msg, index) in messages" :key="index">
                <div :class="msg.role === 'user' ? 'flex justify-end' : 'flex justify-start'">
                    <div class="max-w-[85%] space-y-2">
                        <div 
                            :class="msg.role === 'user' ? 'bg-primary text-primary-foreground rounded-tr-none ml-auto' : (msg.type === 'system' ? 'bg-muted text-muted-foreground text-[10px] text-center italic border-none shadow-none mx-auto py-1' : 'bg-white border text-primary rounded-tl-none font-medium')"
                            class="p-3 rounded-2xl text-sm shadow-sm leading-relaxed"
                            x-html="formatMessage(msg.content, msg.role)"
                        ></div>

                        <!-- Special Interaction: Handover Prompt -->
                        <template x-if="msg.type === 'handover_prompt' || (msg.role === 'assistant' && liveAgentStatus === 'suggesting' && index === messages.length - 1)">
                            <div class="flex flex-col gap-2 mt-2">
                                <a href="/inquiry" class="text-center px-4 py-2 border border-primary text-primary rounded-xl text-xs font-bold hover:bg-primary hover:text-white transition-all">
                                    Submit Inquiry Form
                                </a>
                                <button @click="requestAgent" class="px-4 py-2 bg-accent text-accent-foreground rounded-xl text-xs font-bold shadow-md hover:opacity-90 transition-all">
                                    Wait for Live Agent
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
            <div x-show="loading" class="flex justify-start">
                <div class="bg-white border p-3 rounded-2xl rounded-tl-none flex gap-1">
                    <span class="h-1.5 w-1.5 rounded-full bg-accent animate-bounce"></span>
                    <span class="h-1.5 w-1.5 rounded-full bg-accent animate-bounce" style="animation-delay: 0.1s"></span>
                    <span class="h-1.5 w-1.5 rounded-full bg-accent animate-bounce" style="animation-delay: 0.2s"></span>
                </div>
            </div>
        </div>

        <!-- Input -->
        <div class="p-4 bg-white border-t">
            <form @submit.prevent="sendMessage" class="flex gap-2">
                <input 
                    x-model="userInput" 
                    type="text" 
                    placeholder="Ask something..." 
                    class="flex-1 bg-muted/30 border-none focus:ring-2 focus:ring-accent rounded-xl px-4 py-2 text-sm text-primary font-medium"
                />
                <button type="submit" class="p-2 bg-accent text-accent-foreground rounded-xl shadow-lg hover:opacity-90 transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-send-horizontal"><path d="m3 3 3 9-3 9 19-9Z"/><path d="M6 12h16"/></svg>
                </button>
            </form>
        </div>
    </div>

    <script>
        function chatbot() {
            return {
                open: false,
                messages: [
                    { role: 'assistant', content: 'Peace be with you! I am SRP Assistant, your AI assistant for Sto. Rosario Parish. How can I help you today?' }
                ],
                userInput: '',
                loading: false,
                liveAgentStatus: 'none',
                agentName: '',
                lastMessageId: 0,
                pollInterval: null,
                waitTimer: null,
                waitCounter: 0,

                async sendMessage() {
                    if (!this.userInput.trim() || this.loading) return;
                    const text = this.userInput;
                    this.messages.push({ role: 'user', content: text });
                    this.userInput = '';
                    this.loading = true;
                    try {
                        const response = await fetch('/api/chatbot', {
                            method: 'POST',
                            headers: { 
                                'Content-Type': 'application/json', 
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content 
                            },
                            body: JSON.stringify({ message: text })
                        });
                        const data = await response.json();
                        if (data.status === 'suggest_handover') {
                            this.liveAgentStatus = 'suggesting';
                            this.messages.push({ role: 'assistant', content: data.message, type: 'handover_prompt' });
                        } else if (data.status === 'waiting_for_agent') {
                            this.messages.push({ role: 'assistant', content: data.message });
                        } else {
                            this.messages.push({ role: 'assistant', content: data.message });
                        }
                    } catch (e) {
                        this.messages.push({ role: 'assistant', content: 'I am sorry, I am having trouble connecting to the parish servers right now.' });
                    } finally {
                        this.loading = false;
                        this.scrollToBottom();
                    }
                },

                async requestAgent() {
                    this.loading = true;
                    try {
                        await fetch('/api/chatbot/request-agent', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
                        });
                        this.liveAgentStatus = 'waiting';
                        this.messages.push({ role: 'assistant', content: 'Connecting you to a parish representative... Please stay on the line.' });
                        this.startPolling();
                        this.startTimeout();
                    } catch (e) {
                        this.messages.push({ role: 'assistant', content: 'Could not request a live agent.' });
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
                                    this.messages.push({ role: 'assistant', content: msg.message, sender_name: msg.sender });
                                    this.lastMessageId = Math.max(this.lastMessageId, msg.id);
                                });
                                this.scrollToBottom();
                            }
                            if (data.agent_connected && this.liveAgentStatus !== 'connected') {
                                this.liveAgentStatus = 'connected';
                                this.messages.push({ role: 'assistant', content: data.admin_name + ' has connected.', type: 'system' });
                                clearInterval(this.waitTimer);
                            }
                        } catch (e) {}
                    }, 5000);
                },

                startTimeout() {
                    this.waitCounter = 0;
                    this.waitTimer = setInterval(() => {
                        this.waitCounter += 5;
                        if (this.waitCounter >= 120 && this.liveAgentStatus === 'waiting') {
                            this.messages.push({ role: 'assistant', content: 'No agents available. Please use the inquiry page.', type: 'timeout' });
                            this.liveAgentStatus = 'none';
                            clearInterval(this.waitTimer);
                            clearInterval(this.pollInterval);
                            this.scrollToBottom();
                        }
                    }, 5000);
                },

                scrollToBottom() {
                    setTimeout(() => {
                        const container = this.$refs.msgContainer;
                        if (container) container.scrollTop = container.scrollHeight;
                    }, 50);
                },

                formatMessage(content, role) {
                    if (!content) return '';
                    let text = content.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                    if (role === 'assistant' && text.includes('[[HANDOVER]]')) {
                        this.liveAgentStatus = 'suggesting';
                        text = text.replace('[[HANDOVER]]', '');
                    }
                    // 2a. Format [Link Name](/url) -> <a href="/url" ...>Link Name</a>
                    text = text.replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" class="text-accent underline font-bold hover:text-primary transition-colors">$1</a>');
                    
                    // 2b. Handle [Link name to /url] or [Link to /url] 
                    text = text.replace(/\[([^\]]*?)(?:to\s+)?(\/[a-z\-/]+)\]/gi, '<a href="$2" class="text-accent underline font-bold hover:text-primary transition-colors">$1 $2</a>');
                    
                    return text.replace(/\n/g, '<br>');
                }
            }
        }
    </script>
</div>
