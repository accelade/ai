<?php

declare(strict_types=1);

namespace Accelade\AI\Providers;

use Generator;
use RuntimeException;

class GeminiProvider extends BaseProvider
{
    public function getName(): string
    {
        return 'gemini';
    }

    public function getLabel(): string
    {
        return 'Google Gemini';
    }

    public function getModels(): array
    {
        return [
            'gemini-2.0-flash-exp' => 'Gemini 2.0 Flash',
            'gemini-1.5-pro' => 'Gemini 1.5 Pro',
            'gemini-1.5-flash' => 'Gemini 1.5 Flash',
            'gemini-1.0-pro' => 'Gemini 1.0 Pro',
        ];
    }

    public function getDefaultModel(): string
    {
        return 'gemini-2.0-flash-exp';
    }

    protected function getBaseUrl(): string
    {
        return 'https://generativelanguage.googleapis.com/v1beta';
    }

    protected function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }

    public function chat(array $messages, array $options = []): array
    {
        $model = $options['model'] ?? $this->getModel();

        // Convert messages to Gemini format
        $contents = [];
        $systemInstruction = null;

        foreach ($messages as $message) {
            if ($message['role'] === 'system') {
                $systemInstruction = $message['content'];
            } else {
                $contents[] = [
                    'role' => $message['role'] === 'assistant' ? 'model' : 'user',
                    'parts' => [['text' => $message['content']]],
                ];
            }
        }

        $payload = [
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => $options['temperature'] ?? $this->getTemperature(),
                'maxOutputTokens' => $options['max_tokens'] ?? $this->getMaxTokens(),
            ],
        ];

        if ($systemInstruction) {
            $payload['systemInstruction'] = ['parts' => [['text' => $systemInstruction]]];
        }

        $url = "/models/{$model}:generateContent?key=".$this->getApiKey();
        $response = $this->http()->post($url, $payload);
        $data = $response->json();

        $content = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

        return [
            'content' => $content,
            'usage' => [
                'prompt_tokens' => $data['usageMetadata']['promptTokenCount'] ?? 0,
                'completion_tokens' => $data['usageMetadata']['candidatesTokenCount'] ?? 0,
                'total_tokens' => $data['usageMetadata']['totalTokenCount'] ?? 0,
            ],
        ];
    }

    public function streamChatRealtime(array $messages, callable $callback, array $options = []): void
    {
        $model = $options['model'] ?? $this->getModel();
        $url = ($this->baseUrl ?? $this->getBaseUrl())."/models/{$model}:streamGenerateContent?key=".$this->getApiKey().'&alt=sse';

        $contents = [];
        $systemInstruction = null;

        foreach ($messages as $message) {
            if ($message['role'] === 'system') {
                $systemInstruction = $message['content'];
            } else {
                $contents[] = [
                    'role' => $message['role'] === 'assistant' ? 'model' : 'user',
                    'parts' => [['text' => $message['content']]],
                ];
            }
        }

        $payload = [
            'contents' => $contents,
            'generationConfig' => [
                'temperature' => $options['temperature'] ?? $this->getTemperature(),
                'maxOutputTokens' => $options['max_tokens'] ?? $this->getMaxTokens(),
            ],
        ];

        if ($systemInstruction) {
            $payload['systemInstruction'] = ['parts' => [['text' => $systemInstruction]]];
        }

        $buffer = '';
        $httpCode = 0;

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: text/event-stream',
            ],
            CURLOPT_RETURNTRANSFER => false,
            CURLOPT_HEADER => false,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_WRITEFUNCTION => function ($ch, $data) use (&$buffer, &$httpCode, $callback) {
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
                        if (isset($json['candidates'][0]['content']['parts'][0]['text'])) {
                            $callback($json['candidates'][0]['content']['parts'][0]['text']);
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
            throw new RuntimeException("Gemini API Error: {$errorMessage}");
        }
    }

    public function streamChat(array $messages, array $options = []): Generator
    {
        $chunks = [];
        $this->streamChatRealtime($messages, function ($chunk) use (&$chunks) {
            $chunks[] = $chunk;
        }, $options);

        foreach ($chunks as $chunk) {
            yield $chunk;
        }
    }
}
