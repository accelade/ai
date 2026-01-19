<?php

declare(strict_types=1);

namespace Accelade\AI\Http\Controllers;

use Accelade\AI\AIManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AIController extends Controller
{
    public function __construct(
        protected AIManager $ai
    ) {}

    /**
     * Get AI configuration for frontend.
     */
    public function config(): JsonResponse
    {
        return response()->json([
            'providers' => $this->ai->toArray()['providers'],
            'default' => $this->ai->getDefault(),
            'global_search' => config('accelade-ai.global_search', []),
            'chat' => config('accelade-ai.chat', []),
            'copilot' => config('accelade-ai.copilot', []),
        ]);
    }

    /**
     * Send a chat message.
     */
    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'messages' => 'required|array',
            'messages.*.role' => 'required|string|in:system,user,assistant',
            'messages.*.content' => 'required|string',
            'provider' => 'nullable|string',
            'model' => 'nullable|string',
        ]);

        $provider = $this->ai->provider($validated['provider'] ?? null);

        $options = [];
        if (! empty($validated['model'])) {
            $options['model'] = $validated['model'];
        }

        $result = $provider->chat($validated['messages'], $options);

        return response()->json([
            'content' => $result['content'],
            'usage' => $result['usage'],
        ]);
    }

    /**
     * Stream a chat response.
     */
    public function stream(Request $request): StreamedResponse
    {
        $validated = $request->validate([
            'messages' => 'required|array',
            'messages.*.role' => 'required|string|in:system,user,assistant',
            'messages.*.content' => 'required|string',
            'provider' => 'nullable|string',
            'model' => 'nullable|string',
        ]);

        $provider = $this->ai->provider($validated['provider'] ?? null);

        $options = [];
        if (! empty($validated['model'])) {
            $options['model'] = $validated['model'];
        }

        return response()->stream(function () use ($provider, $validated, $options) {
            try {
                $provider->streamChatRealtime(
                    $validated['messages'],
                    function ($chunk) {
                        echo 'data: '.json_encode(['content' => $chunk])."\n\n";
                        ob_flush();
                        flush();
                    },
                    $options
                );
                echo "data: [DONE]\n\n";
                ob_flush();
                flush();
            } catch (\Exception $e) {
                echo 'data: '.json_encode(['error' => $e->getMessage()])."\n\n";
                ob_flush();
                flush();
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}
