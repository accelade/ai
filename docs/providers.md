# AI Providers

The AI package uses a factory pattern to support multiple AI providers. Each provider implements the `AIProvider` contract.

## Built-in Providers

### OpenAI

The default provider, supporting GPT-4 and GPT-3.5 models.

```php
use Accelade\AI\Facades\AI;

$response = AI::provider('openai')
    ->model('gpt-4o')
    ->temperature(0.7)
    ->chat([
        ['role' => 'user', 'content' => 'Hello!']
    ]);
```

**Available Models:**
- `gpt-4o` (recommended)
- `gpt-4o-mini`
- `gpt-4-turbo`
- `gpt-4`
- `gpt-3.5-turbo`
- `o1`
- `o1-mini`
- `o1-preview`

### Anthropic (Claude)

Claude models from Anthropic.

```php
$response = AI::provider('anthropic')
    ->model('claude-sonnet-4-20250514')
    ->chat($messages);
```

**Available Models:**
- `claude-sonnet-4-20250514`
- `claude-opus-4-20250514`
- `claude-3-5-sonnet-latest`
- `claude-3-5-haiku-latest`
- `claude-3-opus-latest`

### Google Gemini

Google's Gemini AI models.

```php
$response = AI::provider('gemini')
    ->model('gemini-2.0-flash-exp')
    ->chat($messages);
```

**Available Models:**
- `gemini-2.0-flash-exp`
- `gemini-1.5-pro`
- `gemini-1.5-flash`
- `gemini-1.0-pro`

## Using Providers

### Basic Chat

```php
use Accelade\AI\Facades\AI;

// Use default provider
$response = AI::chat([
    ['role' => 'system', 'content' => 'You are a helpful assistant.'],
    ['role' => 'user', 'content' => 'What is Laravel?']
]);

echo $response['content'];
```

### Streaming Responses

```php
foreach (AI::stream($messages) as $chunk) {
    echo $chunk;
    ob_flush();
    flush();
}
```

### With Options

```php
$response = AI::provider('openai')
    ->model('gpt-4o')
    ->temperature(0.9)
    ->maxTokens(1000)
    ->chat($messages);
```

## Creating Custom Providers

You can create your own AI provider by implementing the `AIProvider` contract:

```php
namespace App\AI\Providers;

use Accelade\AI\Contracts\AIProvider;
use Accelade\AI\Providers\BaseProvider;

class CustomProvider extends BaseProvider implements AIProvider
{
    protected string $name = 'custom';
    protected string $label = 'Custom AI';

    public function getModels(): array
    {
        return [
            'custom-model-1' => 'Custom Model 1',
            'custom-model-2' => 'Custom Model 2',
        ];
    }

    public function chat(array $messages, array $options = []): array
    {
        // Implement your chat logic
        $response = $this->makeRequest($messages, $options);

        return [
            'content' => $response['text'],
            'model' => $this->model,
            'usage' => $response['usage'] ?? null,
        ];
    }

    public function streamChat(array $messages, array $options = []): \Generator
    {
        // Implement streaming logic
        yield 'chunk 1';
        yield 'chunk 2';
    }

    public function streamChatRealtime(array $messages, callable $callback, array $options = []): void
    {
        foreach ($this->streamChat($messages, $options) as $chunk) {
            $callback($chunk);
        }
    }

    public function chatWithTools(array $messages, array $tools, array $options = []): array
    {
        // Implement tool calling if supported
        return $this->chat($messages, $options);
    }
}
```

### Registering Custom Providers

Register your provider in a service provider:

```php
use Accelade\AI\Facades\AI;
use App\AI\Providers\CustomProvider;

public function boot(): void
{
    AI::registerProvider('custom', CustomProvider::class);
}
```

Or add to configuration:

```php
// config/accelade-ai.php
'providers' => [
    // ... built-in providers

    'custom' => [
        'class' => \App\AI\Providers\CustomProvider::class,
        'api_key' => env('CUSTOM_AI_API_KEY'),
        'model' => 'custom-model-1',
    ],
],
```

## Provider Contract

All providers must implement:

```php
interface AIProvider
{
    public function getName(): string;
    public function getLabel(): string;
    public function getModels(): array;
    public function chat(array $messages, array $options = []): array;
    public function streamChat(array $messages, array $options = []): Generator;
    public function streamChatRealtime(array $messages, callable $callback, array $options = []): void;
    public function chatWithTools(array $messages, array $tools, array $options = []): array;
}
```

## Error Handling

```php
use Accelade\AI\Facades\AI;
use Accelade\AI\Exceptions\AIException;

try {
    $response = AI::chat($messages);
} catch (AIException $e) {
    // Handle AI-specific errors
    logger()->error('AI Error: ' . $e->getMessage());
} catch (\Exception $e) {
    // Handle general errors
}
```
