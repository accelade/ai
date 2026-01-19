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

    // Load the built JavaScript
    $jsPath = __DIR__ . '/../../../dist/accelade-ai.js';
    $inlineJs = file_exists($jsPath) ? file_get_contents($jsPath) : '';
@endphp
{{-- Configuration must be set before the module loads --}}
<script>
    window.AcceladeAI = window.AcceladeAI || {};
    window.AcceladeAI.config = @json($aiConfig);
</script>
@if($inlineJs)
<script>
{!! $inlineJs !!}
</script>
@else
{{-- Fallback: inline implementation if built assets not available --}}
<script>
    window.AcceladeAI = window.AcceladeAI || {};

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

    AcceladeAI.formatMarkdown = function(content) {
        if (!content) return '';

        return content
            .replace(/```(\w*)\n([\s\S]*?)```/g, '<pre class="bg-gray-100 dark:bg-gray-800 p-3 rounded-lg overflow-x-auto my-2"><code class="text-sm">$2</code></pre>')
            .replace(/`([^`]+)`/g, '<code class="bg-gray-200 dark:bg-gray-700 px-1 py-0.5 rounded text-sm">$1</code>')
            .replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>')
            .replace(/\*([^*]+)\*/g, '<em>$1</em>')
            .replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">$1</a>')
            .replace(/\n/g, '<br>');
    };

    console.log('[AcceladeAI] Scripts loaded (fallback)');
</script>
@endif
