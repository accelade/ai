<?php

use Accelade\AI\Contracts\AIProvider;
use Accelade\AI\Providers\AnthropicProvider;

it('implements AIProvider contract', function () {
    $provider = new AnthropicProvider;

    expect($provider)->toBeInstanceOf(AIProvider::class);
});

it('has correct name and label', function () {
    $provider = new AnthropicProvider;

    expect($provider->getName())->toBe('anthropic');
    expect($provider->getLabel())->toBe('Anthropic');
});

it('returns available models', function () {
    $provider = new AnthropicProvider;

    $models = $provider->getModels();

    expect($models)->toBeArray();
    expect($models)->toHaveKey('claude-sonnet-4-20250514');
    expect($models)->toHaveKey('claude-opus-4-20250514');
    expect($models)->toHaveKey('claude-3-5-sonnet-20241022');
    expect($models)->toHaveKey('claude-3-5-haiku-20241022');
});

it('can be configured fluently', function () {
    $provider = new AnthropicProvider;

    $result = $provider
        ->apiKey('test-key')
        ->model('claude-sonnet-4-20250514')
        ->temperature(0.7)
        ->maxTokens(2048);

    expect($result)->toBe($provider);
});
