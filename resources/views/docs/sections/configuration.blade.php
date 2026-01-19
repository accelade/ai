@props(['framework' => 'vanilla', 'prefix' => 'a', 'documentation' => null, 'hasDemo' => true])

@php
    app('accelade')->setFramework($framework);
@endphp

<x-accelade::layouts.docs :framework="$framework" section="ai-configuration" :documentation="$documentation" :hasDemo="$hasDemo">
    <div class="space-y-6">
        <div class="prose dark:prose-invert max-w-none">
            <p class="text-gray-600 dark:text-gray-400">
                Configure Accelade AI by publishing the configuration file and setting up your API keys.
                The package supports multiple AI providers which can be configured independently.
            </p>
        </div>

        {{-- Installation --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Installation</h3>
            <x-accelade::code-block language="bash">
composer require accelade/ai
            </x-accelade::code-block>
        </div>

        {{-- Publish Config --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Publish Configuration</h3>
            <x-accelade::code-block language="bash">
php artisan vendor:publish --tag=accelade-ai-config
            </x-accelade::code-block>
        </div>

        {{-- Environment Variables --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Environment Variables</h3>
            <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                Add your API keys to your <code class="rounded bg-gray-100 px-1.5 py-0.5 text-sm dark:bg-gray-700">.env</code> file:
            </p>
            <x-accelade::code-block language="bash" title=".env">
# OpenAI Configuration
OPENAI_API_KEY=your-openai-api-key
OPENAI_MODEL=gpt-4o-mini

# Anthropic Configuration
ANTHROPIC_API_KEY=your-anthropic-api-key
ANTHROPIC_MODEL=claude-3-sonnet-20240229

# Google Gemini Configuration
GOOGLE_AI_API_KEY=your-google-api-key
GOOGLE_AI_MODEL=gemini-pro

# Default Provider
AI_DEFAULT_PROVIDER=openai
            </x-accelade::code-block>
        </div>

        {{-- Configuration Options --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Configuration Options</h3>
            <x-accelade::code-block language="php" title="config/accelade-ai.php">
return [
    // Default AI provider
    'default' => env('AI_DEFAULT_PROVIDER', 'openai'),

    // Provider configurations
    'providers' => [
        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
            'temperature' => 0.7,
            'max_tokens' => 2048,
        ],

        'anthropic' => [
            'api_key' => env('ANTHROPIC_API_KEY'),
            'model' => env('ANTHROPIC_MODEL', 'claude-3-sonnet-20240229'),
            'temperature' => 0.7,
            'max_tokens' => 2048,
        ],

        'gemini' => [
            'api_key' => env('GOOGLE_AI_API_KEY'),
            'model' => env('GOOGLE_AI_MODEL', 'gemini-pro'),
            'temperature' => 0.7,
            'max_tokens' => 2048,
        ],
    ],

    // Route configuration
    'routes' => [
        'prefix' => 'accelade-ai',
        'middleware' => ['web'],
    ],

    // Global search configuration
    'global_search' => [
        'placeholder' => 'Search...',
        'debounce' => 300,
        'min_chars' => 2,
    ],

    // Chat configuration
    'chat' => [
        'max_messages' => 100,
        'streaming' => true,
    ],

    // Copilot configuration
    'copilot' => [
        'position' => 'bottom-right',
        'auto_context' => true,
    ],
];
            </x-accelade::code-block>
        </div>

        {{-- Provider Setup Summary --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Provider Setup</h3>
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-green-100 text-sm font-medium text-green-600 dark:bg-green-900/30 dark:text-green-400">1</div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Get API Keys</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Sign up at OpenAI, Anthropic, or Google AI to get your API keys.</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-green-100 text-sm font-medium text-green-600 dark:bg-green-900/30 dark:text-green-400">2</div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Add to .env</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Add your API keys to your environment file.</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-green-100 text-sm font-medium text-green-600 dark:bg-green-900/30 dark:text-green-400">3</div>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Use Components</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Add AI components to your Blade templates.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-accelade::layouts.docs>
