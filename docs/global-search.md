# Global Search Component

A macOS Spotlight-like search component with AI-enhanced search capabilities.

## Basic Usage

```blade
<x-ai-global-search />
```

The search modal opens with `Cmd+K` (Mac) or `Ctrl+K` (Windows/Linux).

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `provider` | string | `config default` | AI provider to use |
| `model` | string | `null` | Specific model to use |
| `placeholder` | string | `'Search or ask anything...'` | Input placeholder text |
| `keyboardShortcut` | string | `'cmd+k'` | Keyboard shortcut to open |
| `useAi` | bool | `true` | Enable AI-enhanced search |
| `limit` | int | `5` | Maximum number of results |
| `debounce` | int | `300` | Debounce delay in ms |
| `searchables` | array | `[]` | Custom searchable items |

## Custom Keyboard Shortcut

```blade
<x-ai-global-search keyboard-shortcut="cmd+/" />
```

## Custom Placeholder

```blade
<x-ai-global-search placeholder="What would you like to find?" />
```

## Without AI Enhancement

```blade
<x-ai-global-search :use-ai="false" />
```

## Custom Searchable Items

Provide your own searchable items:

```blade
<x-ai-global-search :searchables="[
    ['title' => 'Dashboard', 'url' => '/dashboard', 'icon' => 'home'],
    ['title' => 'Settings', 'url' => '/settings', 'icon' => 'cog'],
    ['title' => 'Profile', 'url' => '/profile', 'icon' => 'user'],
]" />
```

## Styling

The component uses Tailwind CSS classes. You can customize the appearance by publishing and editing the views:

```bash
php artisan vendor:publish --tag=accelade-ai-views
```

Then edit `resources/views/vendor/accelade-ai/components/global-search.blade.php`.

## Events

The component emits events you can listen to:

```javascript
// When search is opened
document.addEventListener('accelade-ai:search-opened', (e) => {
    console.log('Search opened');
});

// When search is closed
document.addEventListener('accelade-ai:search-closed', (e) => {
    console.log('Search closed');
});

// When a result is selected
document.addEventListener('accelade-ai:search-selected', (e) => {
    console.log('Selected:', e.detail);
});
```

## Backend Search

To implement backend search, create a route that handles the search:

```php
// routes/web.php
Route::post('/api/search', function (Request $request) {
    $query = $request->input('query');

    $results = [
        'users' => User::search($query)->take(3)->get(),
        'posts' => Post::search($query)->take(3)->get(),
    ];

    return response()->json($results);
});
```

Then configure the component to use your endpoint:

```blade
<x-ai-global-search search-endpoint="/api/search" />
```

## Categories

Results are organized by categories. Each result can have:

```php
[
    'title' => 'Result Title',
    'description' => 'Optional description',
    'url' => '/path/to/resource',
    'icon' => 'icon-name',
    'category' => 'Category Name',
    'metadata' => [
        'type' => 'user',
        'id' => 123,
    ],
]
```

## Keyboard Navigation

- `Cmd/Ctrl + K`: Open search
- `Escape`: Close search
- `Arrow Up/Down`: Navigate results
- `Enter`: Select result
- `Tab`: Toggle AI mode (when enabled)

## Dark Mode

The component automatically supports dark mode when using Tailwind's dark mode utilities.

## Accessibility

The component includes:
- ARIA labels and roles
- Keyboard navigation
- Focus management
- Screen reader announcements
