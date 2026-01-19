<?php

use Accelade\AI\AIManager;
use Accelade\AI\Contracts\AIProvider;
use Accelade\AI\Providers\OpenAIProvider;

it('can be instantiated', function () {
    $manager = app('accelade.ai');

    expect($manager)->toBeInstanceOf(AIManager::class);
});

it('has registered providers from config', function () {
    $manager = app('accelade.ai');

    // Config sets API keys for all three providers
    expect($manager->hasProvider('openai'))->toBeTrue();
    expect($manager->hasProvider('anthropic'))->toBeTrue();
    expect($manager->hasProvider('gemini'))->toBeTrue();
});

it('can get a provider instance', function () {
    $manager = app('accelade.ai');

    $provider = $manager->provider('openai');

    expect($provider)->toBeInstanceOf(AIProvider::class);
    expect($provider)->toBeInstanceOf(OpenAIProvider::class);
});

it('caches provider instances', function () {
    $manager = app('accelade.ai');

    $provider1 = $manager->provider('openai');
    $provider2 = $manager->provider('openai');

    expect($provider1)->toBe($provider2);
});

it('can get all available provider classes', function () {
    $manager = app('accelade.ai');

    $providers = $manager->getAvailableProviders();

    expect($providers)->toHaveKey('openai');
    expect($providers)->toHaveKey('anthropic');
    expect($providers)->toHaveKey('gemini');
});

it('throws exception for unconfigured provider', function () {
    $manager = app('accelade.ai');

    $manager->provider('nonexistent');
})->throws(InvalidArgumentException::class);

it('can set and get default provider', function () {
    $manager = app('accelade.ai');

    $manager->setDefaultProvider('anthropic');

    expect($manager->getDefaultProvider())->toBe('anthropic');
});

it('returns default provider when none specified', function () {
    $manager = app('accelade.ai');
    $manager->setDefaultProvider('openai');

    $provider = $manager->provider();

    expect($provider)->toBeInstanceOf(OpenAIProvider::class);
});

it('can add a provider instance', function () {
    $manager = app('accelade.ai');
    $provider = new OpenAIProvider;
    $provider->apiKey('custom-key');

    $manager->addProvider('custom', $provider);

    expect($manager->hasProvider('custom'))->toBeTrue();
});

it('can register a new provider class', function () {
    $manager = app('accelade.ai');

    $manager->registerProvider('custom', OpenAIProvider::class);

    expect($manager->getAvailableProviders())->toHaveKey('custom');
});

it('returns configuration array', function () {
    $manager = app('accelade.ai');

    $config = $manager->toArray();

    expect($config)->toHaveKey('configured');
    expect($config)->toHaveKey('default');
    expect($config)->toHaveKey('providers');
});

it('checks if any provider is configured', function () {
    $manager = app('accelade.ai');

    // All providers have API keys in test config
    expect($manager->isConfigured())->toBeTrue();
});
