<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Global Search Demo - Accelade AI</title>

    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    @acceladeStyles
</head>
<body class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto max-w-4xl px-4 py-12">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Global Search Demo</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Press <kbd class="rounded bg-gray-200 px-2 py-1 text-sm dark:bg-gray-700">Cmd+K</kbd> to open the search
            </p>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <h2 class="mb-4 text-xl font-semibold text-gray-900 dark:text-white">Features</h2>
            <ul class="space-y-3 text-gray-600 dark:text-gray-400">
                <li class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Spotlight-like modal interface
                </li>
                <li class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    AI-enhanced search capabilities
                </li>
                <li class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Keyboard navigation
                </li>
                <li class="flex items-center gap-2">
                    <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Dark mode support
                </li>
            </ul>

            <button
                onclick="window.Accelade?.emit('open-global-search')"
                class="mt-6 inline-flex items-center gap-2 rounded-lg bg-violet-600 px-6 py-3 text-white transition-colors hover:bg-violet-700"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Open Search
            </button>
        </div>
    </div>

    <x-ai-global-search />

    @acceladeScripts
</body>
</html>
