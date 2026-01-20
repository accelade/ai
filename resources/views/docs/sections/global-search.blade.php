@php
    $framework = request()->query('framework', 'vanilla');
@endphp

<x-accelade::layouts.docs section="ai-global-search" :framework="$framework" :hasDemo="true">
    <div class="space-y-8">
        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Global Search Demo</h3>
            <p class="mb-6 text-gray-600 dark:text-gray-400">
                Press <kbd class="rounded bg-gray-100 px-2 py-1 text-sm dark:bg-gray-700">Cmd+K</kbd> (Mac) or
                <kbd class="rounded bg-gray-100 px-2 py-1 text-sm dark:bg-gray-700">Ctrl+K</kbd> (Windows/Linux)
                to open the global search modal.
            </p>

            <div class="flex flex-wrap gap-4">
                <button
                    type="button"
                    onclick="window.Accelade?.emit('open-global-search')"
                    class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-violet-700"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Open Search
                </button>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Features</h3>
            <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                <li class="flex items-start gap-2">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Spotlight-like modal with keyboard navigation</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>AI-enhanced search for natural language queries</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Debounced input for optimized performance</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Results grouped by category</span>
                </li>
                <li class="flex items-start gap-2">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Dark mode support</span>
                </li>
            </ul>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Keyboard Shortcuts</h3>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-700">
                    <span class="text-gray-600 dark:text-gray-300">Open search</span>
                    <kbd class="rounded bg-gray-200 px-2 py-1 text-sm dark:bg-gray-600">Cmd/Ctrl + K</kbd>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-700">
                    <span class="text-gray-600 dark:text-gray-300">Close search</span>
                    <kbd class="rounded bg-gray-200 px-2 py-1 text-sm dark:bg-gray-600">Escape</kbd>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-700">
                    <span class="text-gray-600 dark:text-gray-300">Navigate results</span>
                    <kbd class="rounded bg-gray-200 px-2 py-1 text-sm dark:bg-gray-600">↑ / ↓</kbd>
                </div>
                <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3 dark:bg-gray-700">
                    <span class="text-gray-600 dark:text-gray-300">Select result</span>
                    <kbd class="rounded bg-gray-200 px-2 py-1 text-sm dark:bg-gray-600">Enter</kbd>
                </div>
            </div>
        </div>
    </div>

    {{-- Include the global search component --}}
    <x-ai-global-search />
</x-accelade::layouts.docs>
