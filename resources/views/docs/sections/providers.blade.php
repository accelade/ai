@props(['framework' => 'vanilla', 'prefix' => 'a', 'documentation' => null, 'hasDemo' => true])

@php
    app('accelade')->setFramework($framework);
@endphp

<x-accelade::layouts.docs :framework="$framework" section="ai-providers" :documentation="$documentation" :hasDemo="$hasDemo">
    <div class="space-y-6">
        <div class="prose dark:prose-invert max-w-none">
            <p class="text-gray-600 dark:text-gray-400">
                Accelade AI uses a factory pattern to support multiple AI providers. Each provider implements
                the same interface, making it easy to switch between providers or add custom ones.
            </p>
        </div>

        {{-- OpenAI Provider --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <div class="mb-4 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-100 dark:bg-green-900/30">
                    <span class="text-xl">🤖</span>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">OpenAI</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">GPT-4, GPT-4o, GPT-3.5 Turbo</p>
                </div>
            </div>

            <div class="mb-4 space-y-2">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    OpenAI's GPT models provide excellent general-purpose AI capabilities with strong reasoning
                    and code generation abilities.
                </p>
            </div>

            <div class="mb-4">
                <h4 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">Available Models</h4>
                <div class="flex flex-wrap gap-2">
                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">gpt-4o</span>
                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">gpt-4o-mini</span>
                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">gpt-4-turbo</span>
                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">gpt-3.5-turbo</span>
                </div>
            </div>

            <x-accelade::code-block language="php" title="Configuration">
'openai' => [
    'api_key' => env('OPENAI_API_KEY'),
    'model' => 'gpt-4o-mini',
    'temperature' => 0.7,
    'max_tokens' => 2048,
],
            </x-accelade::code-block>
        </div>

        {{-- Anthropic Provider --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <div class="mb-4 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-orange-100 dark:bg-orange-900/30">
                    <span class="text-xl">🧠</span>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Anthropic</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Claude 3 Opus, Sonnet, Haiku</p>
                </div>
            </div>

            <div class="mb-4 space-y-2">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Anthropic's Claude models excel at nuanced understanding, long-form content, and
                    maintaining helpful, harmless, and honest conversations.
                </p>
            </div>

            <div class="mb-4">
                <h4 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">Available Models</h4>
                <div class="flex flex-wrap gap-2">
                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">claude-3-opus-20240229</span>
                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">claude-3-sonnet-20240229</span>
                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">claude-3-haiku-20240307</span>
                </div>
            </div>

            <x-accelade::code-block language="php" title="Configuration">
'anthropic' => [
    'api_key' => env('ANTHROPIC_API_KEY'),
    'model' => 'claude-3-sonnet-20240229',
    'temperature' => 0.7,
    'max_tokens' => 2048,
],
            </x-accelade::code-block>
        </div>

        {{-- Gemini Provider --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <div class="mb-4 flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30">
                    <span class="text-xl">✨</span>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Google Gemini</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Gemini Pro, Gemini Ultra</p>
                </div>
            </div>

            <div class="mb-4 space-y-2">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Google's Gemini models offer multimodal capabilities and strong performance across
                    a wide range of tasks with competitive pricing.
                </p>
            </div>

            <div class="mb-4">
                <h4 class="mb-2 text-sm font-medium text-gray-900 dark:text-white">Available Models</h4>
                <div class="flex flex-wrap gap-2">
                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">gemini-pro</span>
                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">gemini-pro-vision</span>
                    <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-300">gemini-ultra</span>
                </div>
            </div>

            <x-accelade::code-block language="php" title="Configuration">
'gemini' => [
    'api_key' => env('GOOGLE_AI_API_KEY'),
    'model' => 'gemini-pro',
    'temperature' => 0.7,
    'max_tokens' => 2048,
],
            </x-accelade::code-block>
        </div>

        {{-- Using Providers Programmatically --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Using Providers</h3>

            <x-accelade::code-block language="php" title="Programmatic Usage">
use Accelade\AI\Facades\AI;

// Use the default provider
$response = AI::chat([
    ['role' => 'user', 'content' => 'Hello!']
]);

// Use a specific provider
$response = AI::provider('anthropic')->chat([
    ['role' => 'user', 'content' => 'Hello!']
]);

// Stream responses
AI::provider('openai')->streamChat(
    messages: [['role' => 'user', 'content' => 'Write a story']],
    callback: function ($chunk) {
        echo $chunk;
    }
);

// Check available providers
$providers = AI::getAvailableProviders();

// Check if a provider is configured
if (AI::hasProvider('anthropic')) {
    // Use Anthropic
}
            </x-accelade::code-block>
        </div>

        {{-- Creating Custom Providers --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Custom Providers</h3>
            <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                You can create custom providers by implementing the <code class="rounded bg-gray-100 px-1.5 py-0.5 text-sm dark:bg-gray-700">AIProvider</code> interface:
            </p>

            <x-accelade::code-block language="php" title="Custom Provider">
use Accelade\AI\Contracts\AIProvider;
use Accelade\AI\Providers\BaseProvider;

class MyCustomProvider extends BaseProvider implements AIProvider
{
    protected string $name = 'custom';
    protected string $label = 'My Custom AI';

    public function chat(array $messages, array $options = []): array
    {
        // Implement your custom chat logic
    }

    public function streamChat(array $messages, array $options = []): Generator
    {
        // Implement streaming
    }
}

// Register your provider
AI::registerProvider('custom', MyCustomProvider::class);
            </x-accelade::code-block>
        </div>
    </div>
</x-accelade::layouts.docs>
