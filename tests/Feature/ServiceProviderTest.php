<?php

use Accelade\AI\AIManager;
use Accelade\AI\Facades\AI;

it('registers the AI manager singleton', function () {
    $manager = app('accelade.ai');

    expect($manager)->toBeInstanceOf(AIManager::class);
});

it('registers the AI facade', function () {
    expect(AI::getFacadeRoot())->toBeInstanceOf(AIManager::class);
});

it('registers built-in providers', function () {
    $manager = app('accelade.ai');

    expect($manager->hasProvider('openai'))->toBeTrue();
    expect($manager->hasProvider('anthropic'))->toBeTrue();
    expect($manager->hasProvider('gemini'))->toBeTrue();
});

it('loads configuration', function () {
    expect(config('accelade-ai.default'))->toBe('openai');
    expect(config('accelade-ai.providers.openai'))->toBeArray();
    expect(config('accelade-ai.providers.anthropic'))->toBeArray();
    expect(config('accelade-ai.providers.gemini'))->toBeArray();
});

it('registers routes', function () {
    $routes = app('router')->getRoutes();

    expect($routes->hasNamedRoute('accelade-ai.chat'))->toBeTrue();
    expect($routes->hasNamedRoute('accelade-ai.stream'))->toBeTrue();
    expect($routes->hasNamedRoute('accelade-ai.config'))->toBeTrue();
    expect($routes->hasNamedRoute('accelade-ai.search'))->toBeTrue();
});

it('registers blade components', function () {
    $blade = app('blade.compiler');

    // Check that components are registered
    expect(view()->exists('accelade-ai::components.global-search'))->toBeTrue();
    expect(view()->exists('accelade-ai::components.chat'))->toBeTrue();
    expect(view()->exists('accelade-ai::components.copilot'))->toBeTrue();
});

it('registers blade directives', function () {
    $blade = app('blade.compiler');
    $directives = $blade->getCustomDirectives();

    expect($directives)->toHaveKey('aiScripts');
    expect($directives)->toHaveKey('aiStyles');
    expect($directives)->toHaveKey('aiGlobalSearch');
    expect($directives)->toHaveKey('aiChat');
    expect($directives)->toHaveKey('aiCopilot');
});
