/**
 * AI Manager - Core functionality for Accelade AI components
 */

export interface ChatMessage {
    role: 'user' | 'assistant' | 'system';
    content: string;
}

export interface AIConfig {
    endpoints: {
        chat: string;
        stream: string;
        search: string;
        config: string;
    };
    default_provider: string;
    global_search: {
        use_ai: boolean;
        limit: number;
    };
    chat: Record<string, unknown>;
    copilot: Record<string, unknown>;
}

export interface SearchOptions {
    useAI?: boolean;
    limit?: number;
    [key: string]: unknown;
}

export interface ChatOptions {
    provider?: string;
    model?: string | null;
    [key: string]: unknown;
}

export interface SearchResult {
    id: string;
    title: string;
    description?: string;
    url?: string;
    type?: string;
    icon?: string;
}

export interface ChatResponse {
    content: string;
    model?: string;
    usage?: {
        prompt_tokens: number;
        completion_tokens: number;
        total_tokens: number;
    };
}

export class AIManager {
    private _config: AIConfig | null = null;

    /**
     * Get the AI configuration
     */
    get config(): AIConfig {
        if (!this._config) {
            // Try to get from window if set by PHP
            const windowConfig = (window as unknown as { AcceladeAI?: { config?: AIConfig } }).AcceladeAI?.config;
            if (windowConfig) {
                this._config = windowConfig;
            } else {
                throw new Error('[AcceladeAI] Configuration not initialized. Make sure @acceladeScripts is included.');
            }
        }
        return this._config;
    }

    /**
     * Set the AI configuration (called from PHP-rendered script)
     */
    set config(value: AIConfig) {
        this._config = value;
    }

    /**
     * Get CSRF token from meta tag
     */
    private getCsrfToken(): string {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    /**
     * Stream chat responses
     */
    async streamChat(messages: ChatMessage[], options: ChatOptions = {}): Promise<ReadableStream<Uint8Array> | null> {
        const url = this.config.endpoints.stream;
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'text/event-stream',
                'X-CSRF-TOKEN': this.getCsrfToken(),
            },
            body: JSON.stringify({
                messages,
                provider: options.provider || this.config.default_provider,
                model: options.model || null,
                ...options,
            }),
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.body;
    }

    /**
     * Non-streaming chat
     */
    async chat(messages: ChatMessage[], options: ChatOptions = {}): Promise<ChatResponse> {
        const url = this.config.endpoints.chat;
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': this.getCsrfToken(),
            },
            body: JSON.stringify({
                messages,
                provider: options.provider || this.config.default_provider,
                model: options.model || null,
                ...options,
            }),
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.json();
    }

    /**
     * Global search function
     */
    async search(query: string, options: SearchOptions = {}): Promise<SearchResult[]> {
        const url = this.config.endpoints.search;
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': this.getCsrfToken(),
            },
            body: JSON.stringify({
                query,
                use_ai: options.useAI ?? this.config.global_search.use_ai,
                limit: options.limit ?? this.config.global_search.limit,
                ...options,
            }),
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return response.json();
    }

    /**
     * Parse SSE stream and yield content
     */
    async *parseStream(readableStream: ReadableStream<Uint8Array>): AsyncGenerator<string, void, unknown> {
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
                            const parsed = JSON.parse(data) as { content?: string };
                            if (parsed.content) {
                                yield parsed.content;
                            }
                        } catch {
                            // Skip invalid JSON
                        }
                    }
                }
            }
        } finally {
            reader.releaseLock();
        }
    }

    /**
     * Format markdown-like content to HTML
     */
    formatMarkdown(content: string): string {
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
    }
}

export default AIManager;
