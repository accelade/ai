# Copilot Widget

A floating AI assistant widget that can read and understand the current page context.

## Basic Usage

```blade
<x-ai-copilot />
```

The copilot widget appears as a floating button and opens with `Cmd+Shift+K`.

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `position` | string | `'bottom-right'` | Widget position |
| `provider` | string | `config default` | AI provider to use |
| `model` | string | `null` | Specific model to use |
| `readContext` | bool | `true` | Read page content for context |
| `keyboardShortcut` | string | `'cmd+shift+k'` | Keyboard shortcut to toggle |
| `suggestions` | array | `[...]` | Quick suggestion buttons |

## Position Options

```blade
{{-- Bottom right (default) --}}
<x-ai-copilot position="bottom-right" />

{{-- Bottom left --}}
<x-ai-copilot position="bottom-left" />

{{-- Top right --}}
<x-ai-copilot position="top-right" />

{{-- Top left --}}
<x-ai-copilot position="top-left" />
```

## Page Context

When `read-context` is enabled (default), the copilot automatically extracts:
- Page title and URL
- Headings (h1, h2, h3)
- Main content text
- Form fields and labels
- Table headers

This allows the AI to understand and answer questions about the current page.

```blade
{{-- Enable context reading --}}
<x-ai-copilot :read-context="true" />

{{-- Disable context reading --}}
<x-ai-copilot :read-context="false" />
```

## Custom Suggestions

```blade
<x-ai-copilot :suggestions="[
    'What is this page about?',
    'Explain the form fields',
    'Help me fill this form',
    'What data is shown here?',
]" />
```

## Specific Provider

```blade
<x-ai-copilot
    provider="anthropic"
    model="claude-sonnet-4-20250514"
/>
```

## Custom Keyboard Shortcut

```blade
<x-ai-copilot keyboard-shortcut="cmd+j" />
```

## Features

### Page Context Understanding

The copilot can:
- Read and summarize page content
- Understand form structures
- Analyze table data
- Answer questions about the current view

### Quick Suggestions

Pre-defined suggestions help users get started:
- "Explain this page"
- "Summarize the data"
- "Help me understand"

### Streaming Responses

Responses stream in real-time for a better user experience.

### Conversation History

The copilot maintains conversation history within the session, allowing follow-up questions.

## Styling

The copilot uses a modern design with:
- Gradient accent colors
- Smooth animations
- Dark mode support
- Responsive layout

Publish views to customize:

```bash
php artisan vendor:publish --tag=accelade-ai-views
```

## Events

```javascript
// Copilot opened
document.addEventListener('accelade-ai:copilot-opened', () => {
    console.log('Copilot opened');
});

// Copilot closed
document.addEventListener('accelade-ai:copilot-closed', () => {
    console.log('Copilot closed');
});

// Context extracted
document.addEventListener('accelade-ai:context-extracted', (e) => {
    console.log('Context:', e.detail);
});
```

## Programmatic Control

```javascript
// Get copilot instance
const copilot = document.querySelector('[data-accelade-copilot]').__accelade;

// Open/close
copilot.toggleOpen();

// Send a message
copilot.sendMessage('What is this page about?');

// Clear chat
copilot.clearChat();

// Refresh context
copilot.extractPageContext();
```

## Adding to All Pages

Add the copilot to your main layout to have it available on every page:

```blade
{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <!-- ... -->
</head>
<body>
    {{ $slot }}

    {{-- Add copilot to all pages --}}
    <x-ai-copilot />

    @acceladeScripts
</body>
</html>
```

## Conditional Display

Show the copilot only for authenticated users:

```blade
@auth
    <x-ai-copilot />
@endauth
```

Or based on a feature flag:

```blade
@if(feature('ai-copilot'))
    <x-ai-copilot />
@endif
```

## Context Exclusions

To exclude certain elements from context extraction, add `data-copilot-ignore`:

```html
<div data-copilot-ignore>
    This content will not be read by the copilot.
</div>
```

## Keyboard Shortcuts

- `Cmd/Ctrl + Shift + K`: Toggle copilot
- `Enter`: Send message
- `Shift + Enter`: New line
- `Escape`: Close copilot

## Accessibility

The copilot includes:
- ARIA labels and roles
- Keyboard navigation
- Focus trap when open
- Screen reader announcements
