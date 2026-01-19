{{-- AI Package Scripts --}}
@php
    $aiConfig = [
        'endpoints' => [
            'chat' => route('accelade-ai.chat'),
            'stream' => route('accelade-ai.stream'),
            'search' => route('accelade-ai.search'),
            'config' => route('accelade-ai.config'),
        ],
        'default_provider' => config('accelade-ai.default', 'openai'),
        'global_search' => config('accelade-ai.global_search'),
        'chat' => config('accelade-ai.chat'),
        'copilot' => config('accelade-ai.copilot'),
    ];
@endphp
<script>
    window.AcceladeAI = window.AcceladeAI || {};

    /**
     * AI Provider Manager for client-side operations
     */
    AcceladeAI.config = @json($aiConfig);

    /**
     * Utility function to stream chat responses
     */
    AcceladeAI.streamChat = async function(messages, options = {}) {
        const url = AcceladeAI.config.endpoints.stream;
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'text/event-stream',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({
                messages,
                provider: options.provider || AcceladeAI.config.default_provider,
                model: options.model || null,
                ...options
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.body;
    };

    /**
     * Utility function for non-streaming chat
     */
    AcceladeAI.chat = async function(messages, options = {}) {
        const url = AcceladeAI.config.endpoints.chat;
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({
                messages,
                provider: options.provider || AcceladeAI.config.default_provider,
                model: options.model || null,
                ...options
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.json();
    };

    /**
     * Global search function
     */
    AcceladeAI.search = async function(query, options = {}) {
        const url = AcceladeAI.config.endpoints.search;
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            body: JSON.stringify({
                query,
                use_ai: options.useAI ?? AcceladeAI.config.global_search.use_ai,
                limit: options.limit ?? AcceladeAI.config.global_search.limit,
                ...options
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.json();
    };

    /**
     * Parse SSE stream and yield content
     */
    AcceladeAI.parseStream = async function*(readableStream) {
        const reader = readableStream.getReader();
        const decoder = new TextDecoder();
        let buffer = '';

        try {
            while (true) {
                const { done, value } = await reader.read();
                if (done) break;

                buffer += decoder.decode(value, { stream: true });
                const lines = buffer.split('\n');
                buffer = lines.pop() || '';

                for (const line of lines) {
                    if (line.startsWith('data: ')) {
                        const data = line.slice(6);
                        if (data === '[DONE]') continue;

                        try {
                            const parsed = JSON.parse(data);
                            if (parsed.content) {
                                yield parsed.content;
                            }
                        } catch (e) {
                            // Skip invalid JSON
                        }
                    }
                }
            }
        } finally {
            reader.releaseLock();
        }
    };

    /**
     * Format markdown-like content to HTML
     */
    AcceladeAI.formatMarkdown = function(content) {
        if (!content) return '';

        return content
            // Code blocks
            .replace(/```(\w*)\n([\s\S]*?)```/g, '<pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded-lg overflow-x-auto my-2"><code class="text-sm">$2</code></pre>')
            // Inline code
            .replace(/`([^`]+)`/g, '<code class="bg-gray-200 dark:bg-gray-700 px-1 py-0.5 rounded text-sm">$1</code>')
            // Bold
            .replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>')
            // Italic
            .replace(/\*([^*]+)\*/g, '<em>$1</em>')
            // Links
            .replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">$1</a>')
            // Line breaks
            .replace(/\n/g, '<br>');
    };

    console.log('[AcceladeAI] Scripts loaded');
</script>
