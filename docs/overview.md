# AI Package Overview

The Accelade AI package provides intelligent AI-powered components for your Laravel application. It includes a spotlight-like global search, a ChatGPT-style chat interface, and a contextual copilot widget.

## Features

- **Global Search**: macOS Spotlight-like search with AI enhancement
- **AI Chat**: Full-featured ChatGPT-like chat interface with streaming
- **Copilot Widget**: Floating AI assistant that understands page context
- **Multi-Provider Support**: OpenAI, Anthropic (Claude), and Google Gemini
- **Framework Agnostic**: Works with vanilla JS, Vue, React, Svelte, and Angular

## Quick Start

### Installation

```bash
composer require accelade/ai
```

### Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=accelade-ai-config
```

Add your API keys to `.env`:

```env
OPENAI_API_KEY=your-openai-key
ANTHROPIC_API_KEY=your-anthropic-key
GOOGLE_AI_API_KEY=your-google-key
```

### Basic Usage

Add AI components to your layout:

```blade
{{-- Global Search (Cmd+K to open) --}}
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

## Components

### Global Search

A spotlight-like search modal that combines traditional search with AI-powered queries:

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

### Copilot

A floating assistant widget that can read and understand page context:

```blade
<x-ai-copilot
    position="bottom-right"
    :read-context="true"
    :keyboard-shortcut="'cmd+shift+k'"
/>
```

## Provider Pattern

The package uses a factory pattern for AI providers, making it easy to add new providers:

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

## Configuration Options

See the [Configuration](configuration.md) guide for all available options.
