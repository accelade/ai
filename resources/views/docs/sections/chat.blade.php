@php
    $framework = request()->query('framework', 'vanilla');
@endphp

<x-accelade::layouts.docs section="ai-chat" :framework="$framework" :hasDemo="true">
    <div class="space-y-8">
        <div class="rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
            <div class="border-b border-gray-200 p-4 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">AI Chat Demo</h3>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    A full-featured ChatGPT-like chat interface with streaming responses.
                </p>
            </div>

            {{-- Embedded chat component --}}
            <div style="height: 500px;">
                <x-ai-chat :show-sidebar="false" />
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Features</h3>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                    <div class="mb-2 flex items-center gap-2">
                        <svg class="h-5 w-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span class="font-medium text-gray-900 dark:text-white">Streaming Responses</span>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        See AI responses as they're generated in real-time.
                    </p>
                </div>

                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                    <div class="mb-2 flex items-center gap-2">
                        <svg class="h-5 w-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        <span class="font-medium text-gray-900 dark:text-white">Session Management</span>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Create, switch, and delete conversation sessions.
                    </p>
                </div>

                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                    <div class="mb-2 flex items-center gap-2">
                        <svg class="h-5 w-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                        <span class="font-medium text-gray-900 dark:text-white">Markdown Support</span>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Messages support markdown formatting and code highlighting.
                    </p>
                </div>

                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                    <div class="mb-2 flex items-center gap-2">
                        <svg class="h-5 w-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                        <span class="font-medium text-gray-900 dark:text-white">Multi-Provider</span>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Switch between OpenAI, Anthropic, and Gemini models.
                    </p>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Usage</h3>
            <x-accelade::code-block language="blade">@verbatim
{{-- Basic usage --}}
<x-ai-chat />

{{-- Without sidebar --}}
<x-ai-chat :show-sidebar="false" />

{{-- Specific provider --}}
<x-ai-chat provider="anthropic" model="claude-sonnet-4-20250514" />

{{-- Custom suggestions --}}
<x-ai-chat :suggestions="['Help me code', 'Explain this', 'Debug my issue']" />
@endverbatim</x-accelade::code-block>
        </div>
    </div>
</x-accelade::layouts.docs>
