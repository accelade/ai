@php
    $framework = request()->query('framework', 'vanilla');
@endphp

<x-accelade::layouts.docs section="ai-copilot" :framework="$framework" :hasDemo="true">
    <div class="space-y-8">
        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Copilot Widget Demo</h3>
            <p class="mb-6 text-gray-600 dark:text-gray-400">
                Look for the floating button in the bottom-right corner, or press
                <kbd class="rounded bg-gray-100 px-2 py-1 text-sm dark:bg-gray-700">Cmd+Shift+K</kbd>
                to toggle the copilot.
            </p>

            <div class="rounded-lg bg-gradient-to-r from-violet-50 to-purple-50 p-6 dark:from-violet-900/20 dark:to-purple-900/20">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-violet-500 to-purple-600 text-white shadow-lg">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Context-Aware AI Assistant</h4>
                        <p class="mt-1 text-gray-600 dark:text-gray-400">
                            The copilot can read the current page content and answer questions about it.
                            Try asking "What is this page about?" or "Explain the features listed here."
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Page Context Extraction</h3>
            <p class="mb-4 text-gray-600 dark:text-gray-400">
                The copilot automatically extracts context from the current page, including:
            </p>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="flex items-center gap-3 rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                    <svg class="h-8 w-8 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <div>
                        <div class="font-medium text-gray-900 dark:text-white">Page Title & URL</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Basic page info</div>
                    </div>
                </div>

                <div class="flex items-center gap-3 rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                    <svg class="h-8 w-8 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    <div>
                        <div class="font-medium text-gray-900 dark:text-white">Headings</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">H1, H2, H3 structure</div>
                    </div>
                </div>

                <div class="flex items-center gap-3 rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                    <svg class="h-8 w-8 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <div>
                        <div class="font-medium text-gray-900 dark:text-white">Main Content</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Text summary</div>
                    </div>
                </div>

                <div class="flex items-center gap-3 rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                    <svg class="h-8 w-8 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <div>
                        <div class="font-medium text-gray-900 dark:text-white">Forms</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Field names & labels</div>
                    </div>
                </div>

                <div class="flex items-center gap-3 rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                    <svg class="h-8 w-8 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <div>
                        <div class="font-medium text-gray-900 dark:text-white">Tables</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Column headers</div>
                    </div>
                </div>

                <div class="flex items-center gap-3 rounded-lg bg-gray-50 p-4 dark:bg-gray-700">
                    <svg class="h-8 w-8 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <div>
                        <div class="font-medium text-gray-900 dark:text-white">Live Updates</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">DOM mutation watching</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Position Options</h3>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-600">
                    <code class="text-sm text-violet-600 dark:text-violet-400">position="bottom-right"</code>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Default position</p>
                </div>
                <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-600">
                    <code class="text-sm text-violet-600 dark:text-violet-400">position="bottom-left"</code>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Bottom left corner</p>
                </div>
                <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-600">
                    <code class="text-sm text-violet-600 dark:text-violet-400">position="top-right"</code>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Top right corner</p>
                </div>
                <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-600">
                    <code class="text-sm text-violet-600 dark:text-violet-400">position="top-left"</code>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Top left corner</p>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Usage</h3>
            <x-accelade::code-block language="blade">@verbatim
{{-- Basic usage --}}
<x-ai-copilot />

{{-- Custom position --}}
<x-ai-copilot position="bottom-left" />

{{-- Without page context reading --}}
<x-ai-copilot :read-context="false" />

{{-- Custom suggestions --}}
<x-ai-copilot :suggestions="[
    'What is this page about?',
    'Help me fill this form',
    'Explain the data shown',
]" />

{{-- Specific provider --}}
<x-ai-copilot provider="anthropic" model="claude-sonnet-4-20250514" />
@endverbatim</x-accelade::code-block>
        </div>
    </div>

    {{-- Include the copilot widget --}}
    <x-ai-copilot />
</x-accelade::layouts.docs>
