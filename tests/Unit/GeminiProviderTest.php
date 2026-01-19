<?php

use Accelade\AI\Contracts\AIProvider;
use Accelade\AI\Providers\GeminiProvider;

it('implements AIProvider contract', function () {
    $provider = new GeminiProvider;

    expect($provider)->toBeInstanceOf(AIProvider::class);
});

it('has correct name and label', function () {
    $provider = new GeminiProvider;

    expect($provider->getName())->toBe('gemini');
    expect($provider->getLabel())->toBe('Google Gemini');
});

it('returns available models', function () {
    $provider = new GeminiProvider;

    $models = $provider->getModels();

    expect($models)->toBeArray();
    expect($models)->toHaveKey('gemini-2.0-flash-exp');
    expect($models)->toHaveKey('gemini-1.5-pro');
    expect($models)->toHaveKey('gemini-1.5-flash');
    expect($models)->toHaveKey('gemini-1.0-pro');
});

it('can be configured fluently', function () {
    $provider = new GeminiProvider;

    $result = $provider
        ->apiKey('test-key')
        ->model('gemini-1.5-pro')
        ->temperature(0.7)
        ->maxTokens(2048);

    expect($result)->toBe($provider);
});
