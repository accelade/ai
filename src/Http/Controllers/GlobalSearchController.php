<?php

declare(strict_types=1);

namespace Accelade\AI\Http\Controllers;

use Accelade\AI\AIManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class GlobalSearchController extends Controller
{
    public function __construct(
        protected AIManager $ai
    ) {}

    /**
     * Perform a global search.
     */
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'query' => 'required|string|min:1',
            'use_ai' => 'nullable|boolean',
            'limit' => 'nullable|integer|min:1|max:50',
        ]);

        $query = $validated['query'];
        $useAI = $validated['use_ai'] ?? false;

        // Get registered search handlers from the app
        $results = $this->performSearch($query, $useAI);

        return response()->json(['results' => $results]);
    }

    /**
     * Perform the actual search.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function performSearch(string $query, bool $useAI): array
    {
        // This can be extended by registering search handlers
        // For now, return an empty structure that can be populated
        $handlers = app()->tagged('accelade.ai.search_handlers');

        $results = [];

        foreach ($handlers as $handler) {
            if (! (method_exists($handler, 'search'))) {
                continue;
            }

            $handlerResults = $handler->search($query, $useAI);
            if (! empty($handlerResults)) {
                $results[] = $handlerResults;
            }
        }

        // If AI is enabled and we have a configured provider, enhance results
        if ($useAI && $this->ai->isConfigured() && ! empty($query)) {
            $results = $this->enhanceWithAI($query, $results);
        }

        return $results;
    }

    /**
     * Enhance search results with AI understanding.
     *
     * @param  array<int, array<string, mixed>>  $results
     * @return array<int, array<string, mixed>>
     */
    protected function enhanceWithAI(string $query, array $results): array
    {
        // AI can be used to:
        // 1. Understand intent from natural language queries
        // 2. Rank results by relevance
        // 3. Provide suggestions when no results found

        return $results;
    }
}
