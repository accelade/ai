# Accelade AI

[![Tests](https://github.com/accelade/ai/actions/workflows/tests.yml/badge.svg)](https://github.com/accelade/ai/actions/workflows/tests.yml)
[![Latest Version](https://img.shields.io/packagist/v/accelade/ai.svg?style=flat-square)](https://packagist.org/packages/accelade/ai)
[![License](https://img.shields.io/packagist/l/accelade/ai.svg?style=flat-square)](https://packagist.org/packages/accelade/ai)

AI-powered components for Laravel applications built with Accelade. Add intelligent search, chat interfaces, and contextual AI assistants to your application with minimal setup.

## Features

- **Global Search** - macOS Spotlight-like search with AI enhancement (Cmd+K / Ctrl+K)
- **AI Chat** - Full-featured ChatGPT-like chat interface with streaming responses
- **Copilot Widget** - Floating AI assistant that understands page context
- **Multi-Provider Support** - OpenAI, Anthropic (Claude), and Google Gemini
- **Framework Agnostic** - Works with vanilla JS, Vue, React, Svelte, and Angular
- **Streaming Responses** - Real-time streaming for better UX
- **Context Awareness** - Copilot can read and understand page content

## Requirements

- PHP 8.2+
- Laravel 11.0+ or 12.0+
- Accelade ^1.0

## Installation

```bash
composer require accelade/ai
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=accelade-ai-config
```

Add your API keys to `.env`:

```env
# OpenAI (GPT-4, GPT-3.5)
OPENAI_API_KEY=your-openai-key

# Anthropic (Claude)
ANTHROPIC_API_KEY=your-anthropic-key

# Google AI (Gemini)
GOOGLE_AI_API_KEY=your-google-key
```

## Quick Start

Add AI components to your Blade layout:

```blade
{{-- Global Search - Opens with Cmd+K (Mac) or Ctrl+K (Windows/Linux) --}}
<x-ai-global-search />

{{-- Full Chat Interface --}}
<x-ai-chat />

{{-- Floating Copilot Widget --}}
<x-ai-copilot />
```

Or use Blade directives:

```blade
@aiGlobalSearch
@aiChat
@aiCopilot
```

## Documentation

For detailed documentation, see the [docs](docs/) folder:

- [Overview](docs/overview.md) - Getting started and basic concepts
- [Configuration](docs/configuration.md) - All configuration options
- [Global Search](docs/global-search.md) - Spotlight-like search component
- [AI Chat](docs/chat.md) - ChatGPT-style chat interface
- [Copilot](docs/copilot.md) - Contextual AI assistant widget
- [Providers](docs/providers.md) - AI provider configuration and usage

## Components

### Global Search

A spotlight-like search modal combining traditional search with AI-powered queries:

```blade
<x-ai-global-search
    :keyboard-shortcut="'cmd+k'"
    :placeholder="'Search or ask anything...'"
    :use-ai="true"
/>
```

### AI Chat

A full-featured chat interface with streaming responses:

```blade
<x-ai-chat
    provider="openai"
    model="gpt-4o"
    :streaming="true"
    :show-sidebar="true"
/>
```

### Copilot Widget

A floating assistant that can read and understand page context:

```blade
<x-ai-copilot
    position="bottom-right"
    :read-context="true"
    :keyboard-shortcut="'cmd+shift+k'"
/>
```

## PHP API

Use the AI facade for programmatic access:

```php
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
foreach (AI::stream($messages) as $chunk) {
    echo $chunk;
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security Vulnerabilities

Please review our [security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Fady Mondy](https://github.com/fadymondy)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
