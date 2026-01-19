<?php

it('returns AI configuration', function () {
    $response = $this->getJson(route('accelade-ai.config'));

    $response->assertOk();
    $response->assertJsonStructure([
        'providers',
        'default',
        'global_search',
        'chat',
        'copilot',
    ]);
});

it('accepts chat request with valid messages format', function () {
    $response = $this->postJson(route('accelade-ai.chat'), [
        'messages' => [
            ['role' => 'user', 'content' => 'Hello'],
        ],
    ]);

    // The request should be accepted (not a validation error)
    // It may fail during API call since we have test keys, but not due to validation
    // Status could be 200 (if mocked) or 500 (if API call fails with test key)
    expect($response->status())->toBeIn([200, 500]);
});

it('validates chat request has messages', function () {
    $response = $this->postJson(route('accelade-ai.chat'), []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['messages']);
});

it('validates search request has query', function () {
    $response = $this->postJson(route('accelade-ai.search'), []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['query']);
});
