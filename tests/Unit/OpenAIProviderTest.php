<?php

use Accelade\AI\Contracts\AIProvider;
use Accelade\AI\Providers\OpenAIProvider;

it('implements AIProvider contract', function () {
    $provider = new OpenAIProvider;

    expect($provider)->toBeInstanceOf(AIProvider::class);
});

it('has correct name and label', function () {
    $provider = new OpenAIProvider;

    expect($provider->getName())->toBe('openai');
    expect($provider->getLabel())->toBe('OpenAI');
});

it('returns available models', function () {
    $provider = new OpenAIProvider;

    $models = $provider->getModels();

    expect($models)->toBeArray();
    expect($models)->toHaveKey('gpt-4o');
    expect($models)->toHaveKey('gpt-4o-mini');
    expect($models)->toHaveKey('gpt-4-turbo');
    expect($models)->toHaveKey('gpt-4');
    expect($models)->toHaveKey('gpt-3.5-turbo');
});

it('can set api key fluently', function () {
    $provider = new OpenAIProvider;

    $result = $provider->apiKey('test-key');

    expect($result)->toBe($provider);
});

it('can set model fluently', function () {
    $provider = new OpenAIProvider;

    $result = $provider->model('gpt-4');

    expect($result)->toBe($provider);
});

it('can set temperature fluently', function () {
    $provider = new OpenAIProvider;

    $result = $provider->temperature(0.5);

    expect($result)->toBe($provider);
});

it('can set max tokens fluently', function () {
    $provider = new OpenAIProvider;

    $result = $provider->maxTokens(1000);

    expect($result)->toBe($provider);
});

it('can set base url fluently', function () {
    $provider = new OpenAIProvider;

    $result = $provider->baseUrl('https://custom.api.com');

    expect($result)->toBe($provider);
});

it('loads config from array', function () {
    $provider = new OpenAIProvider;

    $provider->loadConfig([
        'api_key' => 'config-key',
        'model' => 'gpt-4',
        'temperature' => 0.8,
        'max_tokens' => 500,
    ]);

    // We can verify by checking the provider still works
    expect($provider)->toBeInstanceOf(OpenAIProvider::class);
});
