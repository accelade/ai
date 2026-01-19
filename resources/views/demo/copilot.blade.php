<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Copilot Demo - Accelade AI</title>

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
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">AI Copilot Demo</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Click the floating button or press <kbd class="rounded bg-gray-200 px-2 py-1 text-sm dark:bg-gray-700">Cmd+Shift+K</kbd>
            </p>
        </div>

        <div class="space-y-6">
            {{-- Sample content for the copilot to read --}}
            <article class="rounded-xl border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h2 class="mb-4 text-2xl font-bold text-gray-900 dark:text-white">Understanding Laravel Eloquent</h2>
                <p class="mb-4 text-gray-600 dark:text-gray-400">
                    Eloquent is Laravel's built-in ORM (Object-Relational Mapping) that provides a beautiful, simple ActiveRecord implementation for working with your database. Each database table has a corresponding "Model" which is used to interact with that table.
                </p>

                <h3 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">Key Features</h3>
                <ul class="mb-4 list-inside list-disc space-y-2 text-gray-600 dark:text-gray-400">
                    <li>Fluent query builder for database operations</li>
                    <li>Relationship definitions (hasOne, hasMany, belongsTo, etc.)</li>
                    <li>Mutators and accessors for data transformation</li>
                    <li>Soft deletes for recoverable data</li>
                    <li>Eager loading to prevent N+1 queries</li>
                </ul>

                <h3 class="mb-3 text-lg font-semibold text-gray-900 dark:text-white">Example Code</h3>
                <pre class="rounded-lg bg-gray-100 p-4 text-sm dark:bg-gray-700"><code>// Find a user by ID
$user = User::find(1);

// Get all users with posts
$users = User::with('posts')->get();

// Create a new user
$user = User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com'
]);</code></pre>
            </article>

            {{-- Sample form --}}
            <div class="rounded-xl border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h2 class="mb-4 text-xl font-semibold text-gray-900 dark:text-white">Sample Form</h2>
                <form class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
                        <input type="text" id="name" name="name" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 dark:border-gray-600 dark:bg-gray-700">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
                        <input type="email" id="email" name="email" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 dark:border-gray-600 dark:bg-gray-700">
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Message</label>
                        <textarea id="message" name="message" rows="3" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 dark:border-gray-600 dark:bg-gray-700"></textarea>
                    </div>
                    <button type="submit" class="rounded-lg bg-violet-600 px-4 py-2 text-white hover:bg-violet-700">Submit</button>
                </form>
            </div>

            {{-- Sample table --}}
            <div class="rounded-xl border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <h2 class="mb-4 text-xl font-semibold text-gray-900 dark:text-white">Sample Data Table</h2>
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-gray-700">
                            <th class="py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Name</th>
                            <th class="py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Role</th>
                            <th class="py-2 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600 dark:text-gray-400">
                        <tr class="border-b border-gray-100 dark:border-gray-700">
                            <td class="py-3">Alice Johnson</td>
                            <td class="py-3">Developer</td>
                            <td class="py-3"><span class="rounded-full bg-green-100 px-2 py-1 text-xs text-green-700">Active</span></td>
                        </tr>
                        <tr class="border-b border-gray-100 dark:border-gray-700">
                            <td class="py-3">Bob Smith</td>
                            <td class="py-3">Designer</td>
                            <td class="py-3"><span class="rounded-full bg-green-100 px-2 py-1 text-xs text-green-700">Active</span></td>
                        </tr>
                        <tr>
                            <td class="py-3">Carol White</td>
                            <td class="py-3">Manager</td>
                            <td class="py-3"><span class="rounded-full bg-yellow-100 px-2 py-1 text-xs text-yellow-700">Away</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-ai-copilot />

    @acceladeScripts
</body>
</html>
