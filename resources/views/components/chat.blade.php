@props([
    'showSidebar' => true,
    'endpoint' => null,
    'initialProvider' => null,
    'initialModel' => null,
])

@php
    $componentId = 'ai-chat-' . uniqid();
    $endpoint = $endpoint ?? url(config('accelade-ai.routes.prefix', 'accelade-ai'));
    $aiManager = app('accelade.ai');
    $config = $aiManager->toArray();
    $initialProvider = $initialProvider ?? $aiManager->getDefault();
    $defaultModel = $initialProvider && isset($config['providers'][$initialProvider])
        ? $config['providers'][$initialProvider]['defaultModel']
        : null;
@endphp

@php
    $stateData = [
        'messages' => [],
        'input' => '',
        'loading' => false,
        'streaming' => false,
        'sidebarOpen' => $showSidebar,
        'sessions' => [],
        'currentSession' => null,
        'selectedProvider' => $initialProvider,
        'selectedModel' => $initialModel ?? $defaultModel,
    ];
    $configData = [
        'endpoint' => $endpoint,
        'showSidebar' => $showSidebar,
        'providers' => $config['providers'] ?? [],
        'configured' => $config['configured'] ?? false,
    ];
@endphp

<div
    id="{{ $componentId }}"
    data-accelade
    data-accelade-component="ai-chat"
    data-accelade-state='@json($stateData)'
    data-accelade-config='@json($configData)'
    {{ $attributes->merge(['class' => 'flex h-full overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm']) }}
>
    @if($showSidebar)
    {{-- Sidebar --}}
    <aside
        class="flex flex-col border-e border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 transition-all duration-300"
        :class="sidebarOpen ? 'w-72' : 'w-0 overflow-hidden'"
    >
        {{-- Sidebar Header --}}
        <div class="flex h-14 items-center justify-between border-b border-gray-200 dark:border-gray-700 px-4">
            <button
                type="button"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 py-1.5 text-sm font-medium text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 transition"
                @click="$newChat()"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>New Chat</span>
            </button>
        </div>

        {{-- Sessions List --}}
        <div class="flex-1 overflow-y-auto px-3 py-3">
            <div class="space-y-1">
                <template a-for="session in sessions" :key="session.id">
                    <button
                        type="button"
                        class="group flex w-full items-center gap-3 rounded-md px-3 py-2 text-sm font-medium transition-colors"
                        :class="currentSession?.id === session.id
                            ? 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100'
                            : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-gray-200'"
                        @click="$loadSession(session)"
                    >
                        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <span class="flex-1 truncate text-start" a-text="session.title"></span>
                        <button
                            type="button"
                            class="h-6 w-6 shrink-0 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded hover:bg-gray-300 dark:hover:bg-gray-600"
                            @click.stop="$deleteSession(session.id)"
                        >
                            <svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </button>
                </template>
            </div>
        </div>
    </aside>
    @endif

    {{-- Main Chat Area --}}
    <div class="flex flex-1 flex-col bg-white dark:bg-gray-900">
        {{-- Header --}}
        <header class="flex h-14 items-center justify-between border-b border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 px-4 backdrop-blur-sm">
            <div class="flex items-center gap-3">
                @if($showSidebar)
                <button
                    type="button"
                    class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-200"
                    @click="sidebarOpen = !sidebarOpen"
                >
                    <svg a-show="sidebarOpen" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                    <svg a-show="!sidebarOpen" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                @endif
                <div class="flex items-center gap-2.5">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-primary-500 to-primary-600 shadow-sm">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </div>
                    <span class="text-base font-semibold text-gray-900 dark:text-gray-100">AI Assistant</span>
                </div>
            </div>

            {{-- Model Selector --}}
            <div class="flex items-center gap-2">
                <select
                    a-model="selectedProvider"
                    class="h-9 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 text-sm text-gray-700 dark:text-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                >
                    @foreach($config['providers'] ?? [] as $key => $provider)
                        @if($provider['configured'] ?? false)
                        <option value="{{ $key }}">{{ $provider['label'] }}</option>
                        @endif
                    @endforeach
                </select>

                <select
                    a-model="selectedModel"
                    class="h-9 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 px-3 text-sm text-gray-700 dark:text-gray-200 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                >
                    <template a-for="(label, key) in config.providers[selectedProvider]?.models || {}" :key="key">
                        <option :value="key" a-text="label"></option>
                    </template>
                </select>
            </div>
        </header>

        {{-- Messages Area --}}
        <div class="flex-1 overflow-y-auto bg-gray-50/30 dark:bg-gray-900/50" a-ref="messagesContainer">
            {{-- Not Configured State --}}
            <div a-show="!config.configured" class="flex h-full flex-col items-center justify-center gap-4 p-8 text-center">
                <div class="rounded-full bg-gray-100 dark:bg-gray-800 p-5 shadow-sm">
                    <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">AI Not Configured</h3>
                    <p class="mt-2 max-w-sm text-sm text-gray-500 dark:text-gray-400">
                        Please configure AI providers in your configuration to enable the AI assistant.
                    </p>
                </div>
            </div>

            {{-- Empty State --}}
            <div a-show="config.configured && messages.length === 0" class="flex h-full flex-col items-center justify-center gap-8 p-8">
                <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-br from-primary-500 to-primary-600 shadow-lg">
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                </div>
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">AI Assistant</h2>
                    <p class="mt-3 max-w-lg text-gray-500 dark:text-gray-400">
                        Start a conversation with the AI assistant. Ask questions, get help, or explore ideas.
                    </p>
                </div>
                <div class="flex flex-wrap items-center justify-center gap-2">
                    <button type="button" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-1.5 text-xs font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition" @click="input = 'Help me understand this codebase'">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                        Help me understand
                    </button>
                    <button type="button" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-1.5 text-xs font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition" @click="input = 'Generate a report'">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                        Generate a report
                    </button>
                    <button type="button" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 px-3 py-1.5 text-xs font-medium text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition" @click="input = 'Debug this issue'">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                        Debug an issue
                    </button>
                </div>
            </div>

            {{-- Messages --}}
            <div a-show="messages.length > 0" class="mx-auto max-w-3xl space-y-6 px-4 py-6">
                <template a-for="(message, index) in messages" :key="index">
                    <div
                        class="group flex gap-3"
                        :class="message.role === 'user' ? 'justify-end' : 'justify-start'"
                    >
                        {{-- Assistant Avatar --}}
                        <div
                            a-show="message.role === 'assistant'"
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-primary-500 to-primary-600 shadow-sm"
                        >
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>

                        {{-- Message Content --}}
                        <div
                            class="relative max-w-[80%] rounded-xl px-4 py-3 shadow-sm"
                            :class="message.role === 'user'
                                ? 'bg-primary-500 text-white'
                                : 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700'"
                        >
                            <div
                                class="whitespace-pre-wrap text-sm leading-relaxed"
                                :class="message.role === 'user' ? '' : 'prose prose-sm dark:prose-invert max-w-none'"
                                a-html="message.role === 'assistant' ? $renderMarkdown(message.content) : message.content"
                            ></div>
                            <div
                                class="mt-2 flex items-center justify-between gap-3 text-xs"
                                :class="message.role === 'user' ? 'text-white/60' : 'text-gray-400'"
                            >
                                <span a-text="$formatTime(message.timestamp)"></span>
                                <button
                                    type="button"
                                    class="flex h-6 w-6 items-center justify-center rounded-md opacity-0 transition-all group-hover:opacity-100"
                                    :class="message.role === 'user' ? 'hover:bg-white/10' : 'hover:bg-gray-100 dark:hover:bg-gray-700'"
                                    @click="$copyMessage(message.content)"
                                >
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- User Avatar --}}
                        <div
                            a-show="message.role === 'user'"
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-700 shadow-sm"
                        >
                            <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                </template>

                {{-- Typing Indicator --}}
                <div a-show="loading && !streaming" class="flex gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-primary-500 to-primary-600 shadow-sm">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-3 shadow-sm">
                        <div class="flex items-center gap-1.5">
                            <span class="h-2 w-2 animate-bounce rounded-full bg-primary-500/60" style="animation-delay: 0ms"></span>
                            <span class="h-2 w-2 animate-bounce rounded-full bg-primary-500/60" style="animation-delay: 150ms"></span>
                            <span class="h-2 w-2 animate-bounce rounded-full bg-primary-500/60" style="animation-delay: 300ms"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Input Area --}}
        <div class="border-t border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 p-4 backdrop-blur-sm">
            <div class="mx-auto max-w-3xl">
                <div class="flex items-end gap-3 rounded-2xl border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-800 p-3 shadow-sm transition-all duration-200 focus-within:shadow-md focus-within:ring-2 focus-within:ring-primary-500/20 focus-within:border-primary-500/50">
                    <textarea
                        a-model="input"
                        rows="1"
                        class="min-h-[44px] flex-1 resize-none border-0 bg-transparent px-3 py-2.5 text-sm text-gray-900 dark:text-gray-100 placeholder:text-gray-500 focus:ring-0"
                        placeholder="Type your message..."
                        :disabled="loading || !config.configured"
                        @keydown.enter.prevent="!$event.shiftKey && $sendMessage()"
                        @input="$autoResize($event.target)"
                        a-ref="inputTextarea"
                    ></textarea>
                    <button
                        type="button"
                        class="h-10 w-10 shrink-0 rounded-xl bg-primary-500 text-white shadow-sm transition-all duration-200 hover:bg-primary-600 hover:scale-105 hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                        :disabled="!input.trim() || loading || !config.configured"
                        @click="$sendMessage()"
                    >
                        <svg a-show="loading" class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <svg a-show="!loading" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </div>
                <p class="mt-3 text-center text-xs text-gray-500 dark:text-gray-400">
                    Press <kbd class="rounded bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 font-mono text-[10px]">Enter</kbd> to send
                    <span class="mx-1">•</span>
                    <kbd class="rounded bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 font-mono text-[10px]">Shift+Enter</kbd> for new line
                </p>
            </div>
        </div>
    </div>

    <script type="text/accelade" a-script>
    // Simple markdown renderer
    function renderMarkdown(text) {
        if (!text) return '';
        return text
            .replace(/```(\w+)?\n([\s\S]*?)```/g, '<pre class="bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg p-3 overflow-x-auto"><code>$2</code></pre>')
            .replace(/`([^`]+)`/g, '<code class="bg-gray-100 dark:bg-gray-800 px-1.5 py-0.5 rounded text-primary-600 dark:text-primary-400">$1</code>')
            .replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>')
            .replace(/\*([^*]+)\*/g, '<em>$1</em>')
            .replace(/\n/g, '<br>');
    }

    function scrollToBottom() {
        const container = $el.querySelector('[a-ref="messagesContainer"]');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }

    return {
        $renderMarkdown: renderMarkdown,

        $formatTime(timestamp) {
            if (!timestamp) return '';
            return new Date(timestamp).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        },

        $autoResize(textarea) {
            textarea.style.height = 'auto';
            textarea.style.height = Math.min(textarea.scrollHeight, 200) + 'px';
        },

        async $copyMessage(content) {
            try {
                await navigator.clipboard.writeText(content);
            } catch (error) {
                console.error('Failed to copy:', error);
            }
        },

        $newChat() {
            state.currentSession = null;
            state.messages = [];
            state.input = '';
        },

        async $loadSession(session) {
            state.currentSession = session;
            state.messages = session.messages || [];
            setTimeout(scrollToBottom, 100);
        },

        async $deleteSession(sessionId) {
            try {
                await fetch(`${config.endpoint}/sessions/${sessionId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                });
                state.sessions = state.sessions.filter(s => s.id !== sessionId);
                if (state.currentSession?.id === sessionId) {
                    state.currentSession = null;
                    state.messages = [];
                }
            } catch (error) {
                console.error('Failed to delete session:', error);
            }
        },

        async $sendMessage() {
            if (!state.input.trim() || state.loading) return;

            const userMessage = {
                role: 'user',
                content: state.input.trim(),
                timestamp: Date.now(),
            };

            state.messages.push(userMessage);
            const userInput = state.input;
            state.input = '';
            state.loading = true;
            state.streaming = true;

            // Reset textarea
            const textarea = $el.querySelector('[a-ref="inputTextarea"]');
            if (textarea) textarea.style.height = 'auto';

            setTimeout(scrollToBottom, 100);

            const assistantMessage = {
                role: 'assistant',
                content: '',
                timestamp: Date.now(),
            };
            state.messages.push(assistantMessage);

            try {
                const response = await fetch(`${config.endpoint}/stream`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'text/event-stream',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify({
                        messages: state.messages.slice(0, -1).map(m => ({ role: m.role, content: m.content })),
                        provider: state.selectedProvider,
                        model: state.selectedModel,
                    }),
                });

                const reader = response.body?.getReader();
                const decoder = new TextDecoder();

                if (reader) {
                    let buffer = '';
                    while (true) {
                        const { done, value } = await reader.read();
                        if (done) break;

                        buffer += decoder.decode(value, { stream: true });
                        const lines = buffer.split('\n');
                        buffer = lines.pop() || '';

                        for (const line of lines) {
                            const trimmed = line.trim();
                            if (trimmed.startsWith('data: ')) {
                                const data = trimmed.slice(6);
                                if (data === '[DONE]') continue;

                                try {
                                    const json = JSON.parse(data);
                                    if (json.error) {
                                        assistantMessage.content = `Error: ${json.error}`;
                                    } else if (json.content) {
                                        assistantMessage.content += json.content;
                                        scrollToBottom();
                                    }
                                } catch (e) {
                                    // Ignore parse errors
                                }
                            }
                        }
                    }
                }
            } catch (error) {
                console.error('Stream error:', error);
                assistantMessage.content = 'Sorry, an error occurred. Please try again.';
            } finally {
                state.loading = false;
                state.streaming = false;
                scrollToBottom();
            }
        }
    };
    </script>
</div>
