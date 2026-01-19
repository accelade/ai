# Configuration

The AI package configuration is stored in `config/accelade-ai.php`.

## Default Provider

Set the default AI provider:

```php
'default' => env('ACCELADE_AI_PROVIDER', 'openai'),
```

## Provider Configuration

Configure each AI provider with their credentials and settings:

```php
'providers' => [
    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
        'base_url' => env('OPENAI_BASE_URL'),
        'temperature' => env('OPENAI_TEMPERATURE', 0.7),
        'max_tokens' => env('OPENAI_MAX_TOKENS', 2048),
    ],

    'anthropic' => [
        'api_key' => env('ANTHROPIC_API_KEY'),
        'model' => env('ANTHROPIC_MODEL', 'claude-sonnet-4-20250514'),
        'base_url' => env('ANTHROPIC_BASE_URL'),
        'temperature' => env('ANTHROPIC_TEMPERATURE', 0.7),
        'max_tokens' => env('ANTHROPIC_MAX_TOKENS', 2048),
    ],

    'gemini' => [
        'api_key' => env('GOOGLE_AI_API_KEY'),
        'model' => env('GOOGLE_AI_MODEL', 'gemini-2.0-flash-exp'),
        'base_url' => env('GOOGLE_AI_BASE_URL'),
        'temperature' => env('GOOGLE_AI_TEMPERATURE', 0.7),
        'max_tokens' => env('GOOGLE_AI_MAX_TOKENS', 2048),
    ],
],
```

## Global Search Configuration

```php
'global_search' => [
    'enabled' => true,
    'limit' => 5,
    'debounce' => 300,
    'use_ai' => true,
    'keyboard_shortcut' => 'cmd+k',
],
```

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `enabled` | bool | `true` | Enable/disable global search |
| `limit` | int | `5` | Maximum search results |
| `debounce` | int | `300` | Debounce delay in milliseconds |
| `use_ai` | bool | `true` | Enable AI-enhanced search |
| `keyboard_shortcut` | string | `'cmd+k'` | Keyboard shortcut to open |

## Chat Configuration

```php
'chat' => [
    'enabled' => true,
    'streaming' => true,
    'max_history' => 100,
    'show_token_usage' => false,
    'persist_sessions' => true,
],
```

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `enabled` | bool | `true` | Enable/disable chat component |
| `streaming` | bool | `true` | Use streaming responses |
| `max_history` | int | `100` | Maximum messages to keep in history |
| `show_token_usage` | bool | `false` | Display token usage info |
| `persist_sessions` | bool | `true` | Persist chat sessions |

## Copilot Configuration

```php
'copilot' => [
    'enabled' => true,
    'position' => 'bottom-right',
    'read_page_context' => true,
    'keyboard_shortcut' => 'cmd+shift+k',
    'suggestions' => [
        'Explain this page',
        'Summarize the data',
        'Help me understand',
    ],
],
```

| Option | Type | Default | Description |
|--------|------|---------|-------------|
| `enabled` | bool | `true` | Enable/disable copilot widget |
| `position` | string | `'bottom-right'` | Widget position: `bottom-right`, `bottom-left`, `top-right`, `top-left` |
| `read_page_context` | bool | `true` | Allow copilot to read page content |
| `keyboard_shortcut` | string | `'cmd+shift+k'` | Keyboard shortcut to toggle |
| `suggestions` | array | `[...]` | Quick suggestion buttons |

## Routes Configuration

```php
'routes' => [
    'prefix' => 'accelade-ai',
    'middleware' => ['web'],
],
```

## Environment Variables

Add these to your `.env` file:

```env
# Default provider
ACCELADE_AI_PROVIDER=openai

# OpenAI
OPENAI_API_KEY=sk-...
OPENAI_MODEL=gpt-4o-mini
OPENAI_TEMPERATURE=0.7
OPENAI_MAX_TOKENS=2048

# Anthropic
ANTHROPIC_API_KEY=sk-ant-...
ANTHROPIC_MODEL=claude-sonnet-4-20250514
ANTHROPIC_TEMPERATURE=0.7
ANTHROPIC_MAX_TOKENS=2048

# Google Gemini
GOOGLE_AI_API_KEY=...
GOOGLE_AI_MODEL=gemini-2.0-flash-exp
GOOGLE_AI_TEMPERATURE=0.7
GOOGLE_AI_MAX_TOKENS=2048
```
