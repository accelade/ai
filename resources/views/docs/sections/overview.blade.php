@props(['framework' => 'vanilla', 'prefix' => 'a', 'documentation' => null, 'hasDemo' => true])

@php
    app('accelade')->setFramework($framework);
@endphp

<x-accelade::layouts.docs :framework="$framework" section="ai-overview" :documentation="$documentation" :hasDemo="$hasDemo">
    <div class="space-y-6">
        <div class="prose dark:prose-invert max-w-none">
            <p class="text-gray-600 dark:text-gray-400">
                Accelade AI provides intelligent components for Laravel applications including global search,
                chat interfaces, and context-aware copilot widgets. All components support multiple AI providers
                and work seamlessly with any Accelade-supported framework.
            </p>
        </div>

        {{-- Feature Cards --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            {{-- Global Search --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800">
                <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-violet-100 dark:bg-violet-900/30">
                    <svg class="h-5 w-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="mb-2 text-base font-semibold text-gray-900 dark:text-white">Global Search</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Spotlight-like search with AI enhancement. Open with Cmd+K and search anything with natural language.
                </p>
            </div>

            {{-- AI Chat --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800">
                <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30">
                    <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                </div>
                <h3 class="mb-2 text-base font-semibold text-gray-900 dark:text-white">AI Chat</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Full ChatGPT-like interface with streaming responses, session management, and model selection.
                </p>
            </div>

            {{-- Copilot Widget --}}
            <div class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800">
                <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-900/30">
                    <svg class="h-5 w-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="mb-2 text-base font-semibold text-gray-900 dark:text-white">Copilot Widget</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Floating AI assistant that reads page context and answers questions about the current view.
                </p>
            </div>
        </div>

        {{-- Providers --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Supported Providers</h3>
            <div class="flex flex-wrap gap-4">
                <div class="flex items-center gap-2 rounded-lg bg-gray-50 px-4 py-2 dark:bg-gray-700">
                    <span class="text-lg">🤖</span>
                    <span class="font-medium text-gray-900 dark:text-white">OpenAI</span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">(GPT-4, GPT-4o)</span>
                </div>
                <div class="flex items-center gap-2 rounded-lg bg-gray-50 px-4 py-2 dark:bg-gray-700">
                    <span class="text-lg">🧠</span>
                    <span class="font-medium text-gray-900 dark:text-white">Anthropic</span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">(Claude 3)</span>
                </div>
                <div class="flex items-center gap-2 rounded-lg bg-gray-50 px-4 py-2 dark:bg-gray-700">
                    <span class="text-lg">✨</span>
                    <span class="font-medium text-gray-900 dark:text-white">Google</span>
                    <span class="text-sm text-gray-500 dark:text-gray-400">(Gemini Pro)</span>
                </div>
            </div>
        </div>

        {{-- Quick Start --}}
        <x-accelade::code-block language="blade" title="Quick Start">@verbatim
{{-- Global Search - Opens with Cmd+K --}}
<x-ai-global-search />

{{-- Full Chat Interface --}}
<x-ai-chat />

{{-- Floating Copilot Widget --}}
<x-ai-copilot position="bottom-right" />
@endverbatim</x-accelade::code-block>
    </div>
</x-accelade::layouts.docs>
