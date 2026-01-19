# AI Chat Component

A full-featured ChatGPT-like chat interface with streaming responses and session management.

## Basic Usage

```blade
<x-ai-chat />
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `provider` | string | `config default` | AI provider to use |
| `model` | string | `null` | Specific model to use |
| `streaming` | bool | `true` | Enable streaming responses |
| `showSidebar` | bool | `true` | Show session sidebar |
| `showModelSelector` | bool | `true` | Show model selector |
| `systemPrompt` | string | `null` | Custom system prompt |
| `suggestions` | array | `[...]` | Quick suggestion buttons |
| `persistSessions` | bool | `true` | Persist chat sessions |

## Specific Provider and Model

```blade
<x-ai-chat
    provider="anthropic"
    model="claude-sonnet-4-20250514"
/>
```

## Without Sidebar

```blade
<x-ai-chat :show-sidebar="false" />
```

## Custom System Prompt

```blade
<x-ai-chat system-prompt="You are a helpful coding assistant specializing in Laravel." />
```

## Custom Suggestions

```blade
<x-ai-chat :suggestions="[
    'Explain how routing works',
    'Help me write a migration',
    'Debug this error',
    'Optimize this query',
]" />
```

## Non-Streaming Mode

```blade
<x-ai-chat :streaming="false" />
```

## Features

### Session Management

The chat component includes a sidebar for managing multiple conversation sessions:
- Create new sessions
- Switch between sessions
- Delete sessions
- Session history is persisted in localStorage

### Model Selection

Users can switch between available models during the conversation:
- OpenAI models (GPT-4o, GPT-4, etc.)
- Anthropic models (Claude)
- Gemini models

### Message Features

- **Markdown rendering**: Messages support markdown formatting
- **Code highlighting**: Code blocks are syntax highlighted
- **Copy button**: Each message has a copy button
- **Timestamps**: Messages show when they were sent

### Streaming

Streaming responses show the AI's reply as it's being generated, providing a more interactive experience.

## Styling

The chat interface uses a modern, responsive design with:
- Collapsible sidebar on mobile
- Dark mode support
- Customizable accent colors

Publish views to customize:

```bash
php artisan vendor:publish --tag=accelade-ai-views
```

## Events

```javascript
// Message sent
document.addEventListener('accelade-ai:message-sent', (e) => {
    console.log('Message:', e.detail.message);
});

// Response received
document.addEventListener('accelade-ai:response-received', (e) => {
    console.log('Response:', e.detail.response);
});

// Session changed
document.addEventListener('accelade-ai:session-changed', (e) => {
    console.log('Session:', e.detail.sessionId);
});
```

## Programmatic Control

Access the chat component via JavaScript:

```javascript
// Get chat instance
const chat = document.querySelector('[data-accelade-chat]').__accelade;

// Send a message programmatically
chat.sendMessage('Hello!');

// Clear the current session
chat.clearMessages();

// Switch provider
chat.setProvider('anthropic');

// Set model
chat.setModel('claude-sonnet-4-20250514');
```

## Full-Page Chat

For a full-page chat experience:

```blade
<!DOCTYPE html>
<html>
<head>
    <title>AI Chat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @acceladeStyles
</head>
<body class="h-screen">
    <x-ai-chat class="h-full" />

    @acceladeScripts
</body>
</html>
```

## Embedded Chat

For embedding in a card or section:

```blade
<div class="max-w-4xl mx-auto p-4">
    <div class="rounded-xl shadow-lg overflow-hidden" style="height: 600px;">
        <x-ai-chat :show-sidebar="false" />
    </div>
</div>
```

## Keyboard Shortcuts

- `Enter`: Send message (without shift)
- `Shift + Enter`: New line
- `Cmd/Ctrl + N`: New session
- `Escape`: Close model selector

## Accessibility

The chat component includes:
- ARIA labels for all interactive elements
- Keyboard navigation support
- Screen reader announcements for new messages
- Focus management
