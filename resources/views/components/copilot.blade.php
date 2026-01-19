@props([
    'position' => config('accelade-ai.copilot.position', 'bottom-right'),
    'provider' => null,
    'model' => null,
    'suggestions' => null,
    'readContext' => config('accelade-ai.copilot.read_page_context', true),
    'keyboardShortcut' => config('accelade-ai.copilot.keyboard_shortcut', 'cmd+shift+k'),
])

@php
    $positionClasses = match($position) {
        'bottom-left' => 'bottom-4 left-4',
        'top-right' => 'top-4 right-4',
        'top-left' => 'top-4 left-4',
        default => 'bottom-4 right-4',
    };

    $suggestions = $suggestions ?? config('accelade-ai.copilot.suggestions', [
        'Explain this page',
        'Summarize the data',
        'Help me understand',
    ]);
@endphp

<div
    data-accelade-copilot
    data-provider="{{ $provider ?? config('accelade-ai.default', 'openai') }}"
    data-model="{{ $model }}"
    data-read-context="{{ $readContext ? 'true' : 'false' }}"
    data-keyboard-shortcut="{{ $keyboardShortcut }}"
    {{ $attributes->merge(['class' => 'fixed z-50 ' . $positionClasses]) }}
>
    {{-- Floating Action Button --}}
    <button
        type="button"
        a-on:click="toggleOpen"
        a-show="!isOpen"
        class="group flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-violet-500 to-purple-600 text-white shadow-lg transition-all duration-300 hover:scale-110 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900"
        title="AI Copilot ({{ $keyboardShortcut }})"
    >
        <svg class="h-7 w-7 transition-transform duration-300 group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
        </svg>
        <span class="absolute -right-1 -top-1 flex h-3 w-3">
            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-green-400 opacity-75"></span>
            <span class="relative inline-flex h-3 w-3 rounded-full bg-green-500"></span>
        </span>
    </button>

    {{-- Copilot Panel --}}
    <div
        a-show="isOpen"
        a-transition:enter="transition ease-out duration-200"
        a-transition:enter-start="opacity-0 scale-95 translate-y-4"
        a-transition:enter-end="opacity-100 scale-100 translate-y-0"
        a-transition:leave="transition ease-in duration-150"
        a-transition:leave-start="opacity-100 scale-100 translate-y-0"
        a-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="absolute {{ $position === 'bottom-right' || $position === 'bottom-left' ? 'bottom-16' : 'top-16' }} {{ str_contains($position, 'right') ? 'right-0' : 'left-0' }} flex w-96 flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl dark:border-gray-700 dark:bg-gray-800"
        style="max-height: 32rem;"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-gray-200 bg-gradient-to-r from-violet-500 to-purple-600 px-4 py-3 dark:border-gray-700">
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
                <span class="text-sm font-semibold text-white">AI Copilot</span>
                <span a-show="pageContext" class="rounded-full bg-white/20 px-2 py-0.5 text-xs text-white">Context loaded</span>
            </div>
            <div class="flex items-center gap-1">
                <button
                    type="button"
                    a-on:click="clearChat"
                    class="rounded-lg p-1.5 text-white/80 transition-colors hover:bg-white/20 hover:text-white"
                    title="Clear chat"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
                <button
                    type="button"
                    a-on:click="toggleOpen"
                    class="rounded-lg p-1.5 text-white/80 transition-colors hover:bg-white/20 hover:text-white"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Messages Area --}}
        <div
            a-ref="messagesContainer"
            class="flex-1 space-y-3 overflow-y-auto p-4"
            style="min-height: 200px; max-height: 300px;"
        >
            {{-- Welcome Message --}}
            <div a-show="messages.length === 0" class="text-center">
                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-violet-100 to-purple-100 dark:from-violet-900/50 dark:to-purple-900/50">
                    <svg class="h-6 w-6 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">How can I help you?</p>
                <p a-show="pageContext" class="mt-1 text-xs text-gray-500 dark:text-gray-400">I can see this page's content</p>

                {{-- Quick Suggestions --}}
                <div class="mt-4 flex flex-wrap justify-center gap-2">
                    @foreach($suggestions as $suggestion)
                        <button
                            type="button"
                            a-on:click="sendSuggestion('{{ addslashes($suggestion) }}')"
                            class="rounded-full border border-gray-200 bg-gray-50 px-3 py-1.5 text-xs text-gray-600 transition-colors hover:border-violet-300 hover:bg-violet-50 hover:text-violet-700 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:hover:border-violet-500 dark:hover:bg-violet-900/30 dark:hover:text-violet-400"
                        >
                            {{ $suggestion }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Message List --}}
            <template a-for="(message, index) in messages" a-key="index">
                <div a-bind:class="message.role === 'user' ? 'justify-end' : 'justify-start'" class="flex">
                    <div
                        a-bind:class="message.role === 'user'
                            ? 'bg-violet-600 text-white rounded-2xl rounded-br-md'
                            : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-2xl rounded-bl-md'"
                        class="max-w-[85%] px-3 py-2 text-sm"
                    >
                        <div a-html="formatMessage(message.content)"></div>
                    </div>
                </div>
            </template>

            {{-- Typing Indicator --}}
            <div a-show="isLoading" class="flex justify-start">
                <div class="rounded-2xl rounded-bl-md bg-gray-100 px-4 py-3 dark:bg-gray-700">
                    <div class="flex items-center gap-1">
                        <span class="h-2 w-2 animate-bounce rounded-full bg-gray-400 dark:bg-gray-500" style="animation-delay: 0ms;"></span>
                        <span class="h-2 w-2 animate-bounce rounded-full bg-gray-400 dark:bg-gray-500" style="animation-delay: 150ms;"></span>
                        <span class="h-2 w-2 animate-bounce rounded-full bg-gray-400 dark:bg-gray-500" style="animation-delay: 300ms;"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Input Area --}}
        <div class="border-t border-gray-200 p-3 dark:border-gray-700">
            <form a-on:submit.prevent="sendMessage" class="flex items-end gap-2">
                <div class="relative flex-1">
                    <textarea
                        a-model="inputMessage"
                        a-on:keydown.enter.prevent="handleEnter"
                        rows="1"
                        placeholder="Ask anything..."
                        class="w-full resize-none rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-900 placeholder-gray-500 focus:border-violet-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-violet-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-violet-500 dark:focus:bg-gray-600"
                        style="max-height: 100px;"
                    ></textarea>
                </div>
                <button
                    type="submit"
                    a-bind:disabled="!inputMessage.trim() || isLoading"
                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-violet-600 text-white transition-all hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 dark:focus:ring-offset-gray-800"
                >
                    <svg a-show="!isLoading" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                    </svg>
                    <svg a-show="isLoading" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script a-script>
    return {
        isOpen: false,
        messages: [],
        inputMessage: '',
        isLoading: false,
        pageContext: null,
        provider: this.$el.dataset.provider || 'openai',
        model: this.$el.dataset.model || null,
        readContext: this.$el.dataset.readContext === 'true',
        keyboardShortcut: this.$el.dataset.keyboardShortcut || 'cmd+shift+k',

        init() {
            // Parse keyboard shortcut
            this.setupKeyboardShortcut();

            // Read page context if enabled
            if (this.readContext) {
                this.extractPageContext();
            }

            // Watch for DOM changes to update context
            if (this.readContext) {
                const observer = new MutationObserver(this.debounce(() => {
                    this.extractPageContext();
                }, 1000));

                observer.observe(document.body, {
                    childList: true,
                    subtree: true,
                    characterData: true
                });
            }
        },

        setupKeyboardShortcut() {
            document.addEventListener('keydown', (e) => {
                const shortcut = this.keyboardShortcut.toLowerCase();
                const isCmd = shortcut.includes('cmd') || shortcut.includes('meta');
                const isCtrl = shortcut.includes('ctrl');
                const isShift = shortcut.includes('shift');
                const isAlt = shortcut.includes('alt');

                // Extract the key
                const key = shortcut.replace(/cmd|meta|ctrl|shift|alt|\+/gi, '').trim();

                const matchesModifiers = (
                    (!isCmd || e.metaKey) &&
                    (!isCtrl || e.ctrlKey) &&
                    (!isShift || e.shiftKey) &&
                    (!isAlt || e.altKey)
                );

                if (matchesModifiers && e.key.toLowerCase() === key) {
                    e.preventDefault();
                    this.toggleOpen();
                }
            });
        },

        extractPageContext() {
            // Extract meaningful content from the page
            const content = {
                title: document.title,
                url: window.location.href,
                headings: [],
                mainContent: '',
                forms: [],
                tables: [],
                lists: []
            };

            // Get headings
            document.querySelectorAll('h1, h2, h3').forEach(h => {
                content.headings.push({
                    level: h.tagName.toLowerCase(),
                    text: h.textContent.trim()
                });
            });

            // Get main content (prioritize main, article, or [role="main"])
            const mainElement = document.querySelector('main, article, [role="main"]') || document.body;
            const copilotElement = this.$el;

            // Clone and clean the content
            const clone = mainElement.cloneNode(true);

            // Remove scripts, styles, and the copilot itself
            clone.querySelectorAll('script, style, noscript, [data-accelade-copilot]').forEach(el => el.remove());

            content.mainContent = clone.textContent.replace(/\s+/g, ' ').trim().substring(0, 3000);

            // Get form fields
            document.querySelectorAll('form').forEach(form => {
                const fields = [];
                form.querySelectorAll('input, select, textarea').forEach(field => {
                    if (field.type !== 'hidden' && field.name) {
                        fields.push({
                            name: field.name,
                            type: field.type || field.tagName.toLowerCase(),
                            label: this.findLabel(field)
                        });
                    }
                });
                if (fields.length > 0) {
                    content.forms.push({ fields });
                }
            });

            // Get table headers
            document.querySelectorAll('table').forEach(table => {
                const headers = [];
                table.querySelectorAll('th').forEach(th => {
                    headers.push(th.textContent.trim());
                });
                if (headers.length > 0) {
                    content.tables.push({ headers });
                }
            });

            this.pageContext = content;
        },

        findLabel(field) {
            // Try to find associated label
            if (field.id) {
                const label = document.querySelector(`label[for="${field.id}"]`);
                if (label) {
                    return label.textContent.trim();
                }
            }
            // Check parent label
            const parentLabel = field.closest('label');
            if (parentLabel) {
                return parentLabel.textContent.replace(field.value || '', '').trim();
            }
            return field.placeholder || field.name;
        },

        toggleOpen() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.$nextTick(() => {
                    this.$el.querySelector('textarea')?.focus();
                });
            }
        },

        clearChat() {
            this.messages = [];
        },

        sendSuggestion(suggestion) {
            this.inputMessage = suggestion;
            this.sendMessage();
        },

        handleEnter(e) {
            if (!e.shiftKey) {
                this.sendMessage();
            }
        },

        async sendMessage() {
            const message = this.inputMessage.trim();
            if (!message || this.isLoading) return;

            // Add user message
            this.messages.push({
                role: 'user',
                content: message
            });

            this.inputMessage = '';
            this.isLoading = true;
            this.scrollToBottom();

            try {
                // Build messages array with context
                const systemMessage = this.buildSystemMessage();
                const apiMessages = [
                    { role: 'system', content: systemMessage },
                    ...this.messages.map(m => ({ role: m.role, content: m.content }))
                ];

                // Stream response
                const response = await this.streamChat(apiMessages);

                this.messages.push({
                    role: 'assistant',
                    content: response
                });

            } catch (error) {
                console.error('Copilot error:', error);
                this.messages.push({
                    role: 'assistant',
                    content: 'Sorry, I encountered an error. Please try again.'
                });
            } finally {
                this.isLoading = false;
                this.scrollToBottom();
            }
        },

        buildSystemMessage() {
            let systemMessage = 'You are a helpful AI assistant embedded in a web application. Be concise and helpful.';

            if (this.pageContext) {
                systemMessage += '\n\nCurrent page context:';
                systemMessage += `\nPage title: ${this.pageContext.title}`;
                systemMessage += `\nURL: ${this.pageContext.url}`;

                if (this.pageContext.headings.length > 0) {
                    systemMessage += '\n\nPage structure:';
                    this.pageContext.headings.forEach(h => {
                        systemMessage += `\n- ${h.level}: ${h.text}`;
                    });
                }

                if (this.pageContext.mainContent) {
                    systemMessage += `\n\nPage content summary:\n${this.pageContext.mainContent.substring(0, 1500)}`;
                }

                if (this.pageContext.forms.length > 0) {
                    systemMessage += '\n\nForms on this page:';
                    this.pageContext.forms.forEach((form, i) => {
                        systemMessage += `\nForm ${i + 1} fields: ${form.fields.map(f => f.label || f.name).join(', ')}`;
                    });
                }

                if (this.pageContext.tables.length > 0) {
                    systemMessage += '\n\nTables on this page:';
                    this.pageContext.tables.forEach((table, i) => {
                        systemMessage += `\nTable ${i + 1} columns: ${table.headers.join(', ')}`;
                    });
                }
            }

            return systemMessage;
        },

        async streamChat(messages) {
            const url = new URL('/accelade-ai/stream', window.location.origin);
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'text/event-stream',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    messages,
                    provider: this.provider,
                    model: this.model
                })
            });

            if (!response.ok) {
                throw new Error('Failed to get response');
            }

            const reader = response.body.getReader();
            const decoder = new TextDecoder();
            let fullContent = '';

            // Add placeholder message
            const assistantIndex = this.messages.length;
            this.messages.push({
                role: 'assistant',
                content: ''
            });

            while (true) {
                const { done, value } = await reader.read();
                if (done) break;

                const chunk = decoder.decode(value);
                const lines = chunk.split('\n');

                for (const line of lines) {
                    if (line.startsWith('data: ')) {
                        const data = line.slice(6);
                        if (data === '[DONE]') continue;

                        try {
                            const parsed = JSON.parse(data);
                            if (parsed.content) {
                                fullContent += parsed.content;
                                this.messages[assistantIndex].content = fullContent;
                                this.scrollToBottom();
                            }
                        } catch (e) {
                            // Skip invalid JSON
                        }
                    }
                }
            }

            // Remove the placeholder and return content
            this.messages.pop();
            return fullContent;
        },

        formatMessage(content) {
            if (!content) return '';

            // Basic markdown-like formatting
            return content
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.*?)\*/g, '<em>$1</em>')
                .replace(/`(.*?)`/g, '<code class="rounded bg-gray-200 px-1 py-0.5 text-xs dark:bg-gray-600">$1</code>')
                .replace(/\n/g, '<br>');
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const container = this.$refs.messagesContainer;
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        },

        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    };
</script>
