<?php

declare(strict_types=1);

namespace Accelade\AI\Providers;

use Generator;
use RuntimeException;

class AnthropicProvider extends BaseProvider
{
    public function getName(): string
    {
        return 'anthropic';
    }

    public function getLabel(): string
    {
        return 'Anthropic';
    }

    public function getModels(): array
    {
        return [
            'claude-sonnet-4-20250514' => 'Claude Sonnet 4',
            'claude-opus-4-20250514' => 'Claude Opus 4',
            'claude-3-5-sonnet-20241022' => 'Claude 3.5 Sonnet',
            'claude-3-5-haiku-20241022' => 'Claude 3.5 Haiku',
            'claude-3-opus-20240229' => 'Claude 3 Opus',
        ];
    }

    public function getDefaultModel(): string
    {
        return 'claude-sonnet-4-20250514';
    }

    protected function getBaseUrl(): string
    {
        return 'https://api.anthropic.com/v1';
    }

    protected function getHeaders(): array
    {
        return [
            'x-api-key' => $this->getApiKey(),
            'anthropic-version' => '2023-06-01',
            'Content-Type' => 'application/json',
        ];
    }

    public function chat(array $messages, array $options = []): array
    {
        // Convert messages to Anthropic format
        $systemMessage = '';
        $anthropicMessages = [];

        foreach ($messages as $message) {
            if ($message['role'] === 'system') {
                $systemMessage = $message['content'];
            } else {
                $anthropicMessages[] = [
                    'role' => $message['role'],
                    'content' => $message['content'],
                ];
            }
        }

        $payload = [
            'model' => $options['model'] ?? $this->getModel(),
            'messages' => $anthropicMessages,
            'max_tokens' => $options['max_tokens'] ?? $this->getMaxTokens(),
        ];

        if ($systemMessage) {
            $payload['system'] = $systemMessage;
        }

        $response = $this->http()->post('/messages', $payload);
        $data = $response->json();

        return [
            'content' => $data['content'][0]['text'] ?? '',
            'usage' => [
                'prompt_tokens' => $data['usage']['input_tokens'] ?? 0,
                'completion_tokens' => $data['usage']['output_tokens'] ?? 0,
                'total_tokens' => ($data['usage']['input_tokens'] ?? 0) + ($data['usage']['output_tokens'] ?? 0),
            ],
        ];
    }

    public function chatWithTools(array $messages, array $tools, array $options = []): array
    {
        $systemMessage = '';
        $anthropicMessages = [];

        foreach ($messages as $message) {
            if ($message['role'] === 'system') {
                $systemMessage = $message['content'];
            } else {
                $anthropicMessages[] = [
                    'role' => $message['role'],
                    'content' => $message['content'],
                ];
            }
        }

        $formattedTools = array_map(static fn ($tool) => [
            'name' => $tool['name'],
            'description' => $tool['description'] ?? '',
            'input_schema' => $tool['parameters'] ?? ['type' => 'object', 'properties' => []],
        ], $tools);

        $payload = [
            'model' => $options['model'] ?? $this->getModel(),
            'messages' => $anthropicMessages,
            'tools' => $formattedTools,
            'max_tokens' => $options['max_tokens'] ?? $this->getMaxTokens(),
        ];

        if ($systemMessage) {
            $payload['system'] = $systemMessage;
        }

        $response = $this->http()->post('/messages', $payload);
        $data = $response->json();

        $content = null;
        $toolCalls = null;

        foreach ($data['content'] ?? [] as $block) {
            if ($block['type'] === 'text') {
                $content = $block['text'];
            } elseif ($block['type'] === 'tool_use') {
                $toolCalls ??= [];
                $toolCalls[] = [
                    'id' => $block['id'],
                    'name' => $block['name'],
                    'arguments' => $block['input'] ?? [],
                ];
            }
        }

        return [
            'content' => $content,
            'tool_calls' => $toolCalls,
            'usage' => [
                'prompt_tokens' => $data['usage']['input_tokens'] ?? 0,
                'completion_tokens' => $data['usage']['output_tokens'] ?? 0,
                'total_tokens' => ($data['usage']['input_tokens'] ?? 0) + ($data['usage']['output_tokens'] ?? 0),
            ],
        ];
    }

    public function streamChatRealtime(array $messages, callable $callback, array $options = []): void
    {
        $url = ($this->baseUrl ?? $this->getBaseUrl()).'/messages';

        $systemMessage = '';
        $anthropicMessages = [];

        foreach ($messages as $message) {
            if ($message['role'] === 'system') {
                $systemMessage = $message['content'];
            } else {
                $anthropicMessages[] = [
                    'role' => $message['role'],
                    'content' => $message['content'],
                ];
            }
        }

        $payload = [
            'model' => $options['model'] ?? $this->getModel(),
            'messages' => $anthropicMessages,
            'max_tokens' => $options['max_tokens'] ?? $this->getMaxTokens(),
            'stream' => true,
        ];

        if ($systemMessage) {
            $payload['system'] = $systemMessage;
        }

        $buffer = '';
        $httpCode = 0;

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'x-api-key: '.$this->getApiKey(),
                'anthropic-version: 2023-06-01',
                'Content-Type: application/json',
                'Accept: text/event-stream',
            ],
            CURLOPT_RETURNTRANSFER => false,
            CURLOPT_HEADER => false,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_WRITEFUNCTION => static function ($ch, $data) use (&$buffer, &$httpCode, $callback) {
                if ($httpCode === 0) {
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                }

                if ($httpCode !== 200) {
                    $buffer .= $data;

                    return strlen($data);
                }

                $buffer .= $data;

                while (($pos = strpos($buffer, "\n")) !== false) {
                    $line = trim(substr($buffer, 0, $pos));
                    $buffer = substr($buffer, $pos + 1);

                    if (str_starts_with($line, 'data: ')) {
                        $jsonData = substr($line, 6);
                        $json = json_decode($jsonData, true);
                        if (isset($json['type']) && $json['type'] === 'content_block_delta') {
                            if (isset($json['delta']['text'])) {
                                $callback($json['delta']['text']);
                            }
                        }
                    }
                }

                return strlen($data);
            },
        ]);

        curl_exec($ch);

        $finalHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new RuntimeException("cURL Error: {$error}");
        }

        if ($finalHttpCode !== 200) {
            $errorData = json_decode($buffer, true);
            $errorMessage = $errorData['error']['message'] ?? "HTTP {$finalHttpCode}: {$buffer}";
            throw new RuntimeException("Anthropic API Error: {$errorMessage}");
        }
    }

    public function streamChat(array $messages, array $options = []): Generator
    {
        $chunks = [];
        $this->streamChatRealtime($messages, static function ($chunk) use (&$chunks) {
            $chunks[] = $chunk;
        }, $options);

        foreach ($chunks as $chunk) {
            yield $chunk;
        }
    }
}
