@props([
    'placeholder' => 'Search...',
    'shortcut' => 'cmd+k',
    'useAI' => true,
    'debounce' => 300,
    'endpoint' => null,
])

@php
    $componentId = 'ai-global-search-' . uniqid();
    $endpoint = $endpoint ?? route('accelade-ai.search');
    $config = [
        'placeholder' => $placeholder,
        'shortcut' => $shortcut,
        'useAI' => $useAI,
        'debounce' => $debounce,
        'endpoint' => $endpoint,
        'hasAI' => app('accelade.ai')->isConfigured(),
    ];
    $stateData = [
        'isOpen' => false,
        'query' => '',
        'results' => [],
        'loading' => false,
        'selectedIndex' => 0,
        'useAI' => $useAI,
    ];
@endphp

<div
    id="{{ $componentId }}"
    data-accelade
    data-accelade-component="global-search"
    data-accelade-state='@json($stateData)'
    data-accelade-config='@json($config)'
    {{ $attributes->merge(['class' => 'accelade-global-search']) }}
>
    {{-- Trigger Button --}}
    <button
        type="button"
        class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-1.5 text-sm text-gray-500 dark:text-gray-400 shadow-sm transition hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-200"
        title="{{ $placeholder }}"
        @click="isOpen = true"
        a-ref="trigger"
    >
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <kbd class="pointer-events-none hidden h-5 select-none items-center gap-0.5 rounded border border-gray-200 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 px-1.5 font-mono text-[10px] font-medium sm:flex">
            <span class="text-xs">⌘</span>K
        </kbd>
    </button>

    {{-- Modal Backdrop --}}
    <div
        a-show="isOpen"
        a-transition:enter="duration-200 ease-out"
        a-transition:enter-start="opacity-0"
        a-transition:enter-end="opacity-100"
        a-transition:leave="duration-150 ease-in"
        a-transition:leave-start="opacity-100"
        a-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto p-4 pt-[25vh] sm:p-6 sm:pt-[20vh]"
        @click.self="isOpen = false"
        @keydown.escape.window="isOpen = false"
    >
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="isOpen = false"></div>

        {{-- Search Dialog --}}
        <div
            a-show="isOpen"
            a-transition:enter="duration-200 ease-out"
            a-transition:enter-start="opacity-0 scale-95 translate-y-4"
            a-transition:enter-end="opacity-100 scale-100 translate-y-0"
            a-transition:leave="duration-150 ease-in"
            a-transition:leave-start="opacity-100 scale-100 translate-y-0"
            a-transition:leave-end="opacity-0 scale-95 translate-y-4"
            class="relative mx-auto max-w-2xl transform overflow-hidden rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-2xl ring-1 ring-gray-200 dark:ring-gray-700 transition-all"
        >
            {{-- Search Input --}}
            <div class="relative flex items-center px-4 py-3">
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-700">
                    <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input
                    type="text"
                    a-model="query"
                    class="h-12 flex-1 border-0 bg-transparent px-4 text-lg text-gray-900 dark:text-gray-100 placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:outline-none focus:ring-0"
                    placeholder="{{ $placeholder }}"
                    a-ref="searchInput"
                    @keydown.arrow-down.prevent="selectedIndex = Math.min(selectedIndex + 1, results.length - 1)"
                    @keydown.arrow-up.prevent="selectedIndex = Math.max(selectedIndex - 1, 0)"
                    @keydown.enter.prevent="$selectResult()"
                />
                <div class="flex items-center gap-2">
                    @if($useAI)
                    {{-- AI Toggle --}}
                    <button
                        type="button"
                        class="flex h-9 w-9 items-center justify-center rounded-lg transition-all duration-200"
                        :class="useAI ? 'bg-primary-500 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 hover:bg-gray-200 dark:hover:bg-gray-600'"
                        @click="useAI = !useAI"
                        title="Toggle AI-powered search"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </button>
                    @endif
                    {{-- Loading Spinner --}}
                    <div a-show="loading" class="h-5 w-5">
                        <svg class="animate-spin text-gray-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    {{-- Clear Button --}}
                    <button
                        a-show="query && !loading"
                        type="button"
                        class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-500 transition hover:bg-gray-200 dark:hover:bg-gray-600"
                        @click="query = ''; results = []"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- AI Badge --}}
            <div a-show="useAI && {{ $config['hasAI'] ? 'true' : 'false' }}" class="mx-4 mb-3 inline-flex items-center gap-1.5 rounded-full bg-primary-100 dark:bg-primary-900/30 px-3 py-1 text-xs font-medium text-primary-600 dark:text-primary-400">
                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
                <span>AI-powered</span>
            </div>

            {{-- Divider --}}
            <div a-show="results.length > 0 || query" class="mx-4 h-px bg-gray-200 dark:bg-gray-700"></div>

            {{-- Results --}}
            <div a-show="results.length > 0" class="max-h-[50vh] overflow-y-auto overscroll-contain px-3 py-3">
                <template a-for="(group, groupIndex) in results" :key="group.resource">
                    <div class="mb-4 last:mb-0">
                        {{-- Group Header --}}
                        <h3 class="mb-2 flex items-center gap-2 px-2 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            <span a-text="group.label"></span>
                        </h3>

                        {{-- Results List --}}
                        <ul class="space-y-1">
                            <template a-for="(result, idx) in group.results" :key="result.id">
                                <li>
                                    <button
                                        type="button"
                                        class="group flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-left transition-all duration-150"
                                        :class="selectedIndex === idx ? 'bg-primary-500 text-white' : 'text-gray-900 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700'"
                                        @click="$navigateTo(result.url)"
                                        @mouseenter="selectedIndex = idx"
                                    >
                                        <div class="flex-1 min-w-0">
                                            <div class="truncate font-medium" a-text="result.title"></div>
                                            <div
                                                a-show="result.subtitle"
                                                class="truncate text-sm"
                                                :class="selectedIndex === idx ? 'text-white/70' : 'text-gray-500 dark:text-gray-400'"
                                                a-text="result.subtitle"
                                            ></div>
                                        </div>
                                        <div
                                            class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-md transition-all"
                                            :class="selectedIndex === idx ? 'bg-white/20 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 opacity-0 group-hover:opacity-100'"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                    </button>
                                </li>
                            </template>
                        </ul>
                    </div>
                </template>
            </div>

            {{-- Empty State --}}
            <div a-show="query && !loading && results.length === 0" class="px-6 py-14 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-700">
                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <p class="text-lg font-medium text-gray-900 dark:text-gray-100">No results found</p>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    No results for "<span a-text="query"></span>"
                </p>
            </div>

            {{-- Initial State --}}
            <div a-show="!query" class="px-6 py-14 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-700">
                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <p class="text-lg font-medium text-gray-900 dark:text-gray-100">Start typing to search</p>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Search across your application</p>
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-between border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 px-4 py-2.5">
                <span class="text-xs text-gray-500 dark:text-gray-400">
                    <span a-show="results.length > 0">
                        <span a-text="results.reduce((acc, g) => acc + g.results.length, 0)"></span> results
                    </span>
                    <span a-show="results.length === 0">Type to search</span>
                </span>
                <div class="hidden items-center gap-3 text-xs text-gray-500 dark:text-gray-400 sm:flex">
                    <div class="flex items-center gap-1">
                        <kbd class="flex h-5 items-center justify-center rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-1.5 font-mono text-[10px] font-medium">↑</kbd>
                        <kbd class="flex h-5 items-center justify-center rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-1.5 font-mono text-[10px] font-medium">↓</kbd>
                        <span class="ms-1">navigate</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <kbd class="flex h-5 items-center justify-center rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-1.5 font-mono text-[10px] font-medium">↵</kbd>
                        <span class="ms-1">select</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <kbd class="flex h-5 items-center justify-center rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-2 font-mono text-[10px] font-medium">esc</kbd>
                        <span class="ms-1">close</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script a-script>
    // Keyboard shortcut listener
    document.addEventListener('keydown', (e) => {
        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
            e.preventDefault();
            state.isOpen = !state.isOpen;
            if (state.isOpen) {
                setTimeout(() => {
                    const input = $el.querySelector('[a-ref="searchInput"]');
                    if (input) input.focus();
                }, 100);
            }
        }
    });

    // Debounced search
    let searchTimeout = null;
    $watch('query', (newQuery) => {
        if (searchTimeout) clearTimeout(searchTimeout);

        if (!newQuery.trim()) {
            state.results = [];
            return;
        }

        state.loading = true;
        searchTimeout = setTimeout(async () => {
            try {
                const response = await fetch(
                    `${config.endpoint}?query=${encodeURIComponent(newQuery)}&useAI=${state.useAI}`
                );
                const data = await response.json();
                state.results = data.results || [];
                state.selectedIndex = 0;
            } catch (error) {
                console.error('Search error:', error);
                state.results = [];
            } finally {
                state.loading = false;
            }
        }, config.debounce);
    });

    return {
        $selectResult() {
            const flatResults = state.results.flatMap(g => g.results);
            const selected = flatResults[state.selectedIndex];
            if (selected?.url) {
                window.location.href = selected.url;
            }
        },
        $navigateTo(url) {
            if (url) window.location.href = url;
        }
    };
</script>
