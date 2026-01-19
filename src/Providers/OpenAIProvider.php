<?php

declare(strict_types=1);

namespace Accelade\AI\Providers;

use Generator;
use RuntimeException;

class OpenAIProvider extends BaseProvider
{
    public function getName(): string
    {
        return 'openai';
    }

    public function getLabel(): string
    {
        return 'OpenAI';
    }

    public function getModels(): array
    {
        return [
            'gpt-4o' => 'GPT-4o',
            'gpt-4o-mini' => 'GPT-4o Mini',
            'gpt-4-turbo' => 'GPT-4 Turbo',
            'gpt-4' => 'GPT-4',
            'gpt-3.5-turbo' => 'GPT-3.5 Turbo',
            'o1' => 'O1',
            'o1-mini' => 'O1 Mini',
            'o1-preview' => 'O1 Preview',
        ];
    }

    public function getDefaultModel(): string
    {
        return 'gpt-4o-mini';
    }

    protected function getBaseUrl(): string
    {
        return 'https://api.openai.com/v1';
    }

    protected function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer '.$this->getApiKey(),
            'Content-Type' => 'application/json',
        ];
    }

    public function chat(array $messages, array $options = []): array
    {
        $response = $this->http()->post('/chat/completions', [
            'model' => $options['model'] ?? $this->getModel(),
            'messages' => $messages,
            'temperature' => $options['temperature'] ?? $this->getTemperature(),
            'max_tokens' => $options['max_tokens'] ?? $this->getMaxTokens(),
        ]);

        $data = $response->json();

        return [
            'content' => $data['choices'][0]['message']['content'] ?? '',
            'usage' => [
                'prompt_tokens' => $data['usage']['prompt_tokens'] ?? 0,
                'completion_tokens' => $data['usage']['completion_tokens'] ?? 0,
                'total_tokens' => $data['usage']['total_tokens'] ?? 0,
            ],
        ];
    }

    public function chatWithTools(array $messages, array $tools, array $options = []): array
    {
        $formattedTools = array_map(static fn ($tool) => [
            'type' => 'function',
            'function' => [
                'name' => $tool['name'],
                'description' => $tool['description'] ?? '',
                'parameters' => $tool['parameters'] ?? ['type' => 'object', 'properties' => []],
            ],
        ], $tools);

        $response = $this->http()->post('/chat/completions', [
            'model' => $options['model'] ?? $this->getModel(),
            'messages' => $messages,
            'tools' => $formattedTools,
            'tool_choice' => $options['tool_choice'] ?? 'auto',
            'temperature' => $options['temperature'] ?? $this->getTemperature(),
            'max_tokens' => $options['max_tokens'] ?? $this->getMaxTokens(),
        ]);

        $data = $response->json();
        $message = $data['choices'][0]['message'] ?? [];

        $toolCalls = null;
        if (isset($message['tool_calls'])) {
            $toolCalls = array_map(static fn ($call) => [
                'id' => $call['id'],
                'name' => $call['function']['name'],
                'arguments' => json_decode($call['function']['arguments'], true) ?? [],
            ], $message['tool_calls']);
        }

        return [
            'content' => $message['content'] ?? null,
            'tool_calls' => $toolCalls,
            'usage' => [
                'prompt_tokens' => $data['usage']['prompt_tokens'] ?? 0,
                'completion_tokens' => $data['usage']['completion_tokens'] ?? 0,
                'total_tokens' => $data['usage']['total_tokens'] ?? 0,
            ],
        ];
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

    public function streamChatRealtime(array $messages, callable $callback, array $options = []): void
    {
        $url = ($this->baseUrl ?? $this->getBaseUrl()).'/chat/completions';

        $payload = [
            'model' => $options['model'] ?? $this->getModel(),
            'messages' => $messages,
            'temperature' => $options['temperature'] ?? $this->getTemperature(),
            'max_tokens' => $options['max_tokens'] ?? $this->getMaxTokens(),
            'stream' => true,
        ];

        $buffer = '';
        $httpCode = 0;

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer '.$this->getApiKey(),
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
                        if ($jsonData === '[DONE]') {
                            continue;
                        }
                        $json = json_decode($jsonData, true);
                        if (isset($json['choices'][0]['delta']['content'])) {
                            $callback($json['choices'][0]['delta']['content']);
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
            throw new RuntimeException("OpenAI API Error: {$errorMessage}");
        }
    }
}
