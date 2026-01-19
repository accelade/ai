<?php

declare(strict_types=1);

namespace Accelade\AI\Tests;

use Accelade\AcceladeServiceProvider;
use Accelade\AI\AIServiceProvider;
use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            BladeIconsServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            AcceladeServiceProvider::class,
            AIServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'AI' => \Accelade\AI\Facades\AI::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
        $app['config']->set('accelade.framework', 'vanilla');

        // Set up AI configuration for testing
        $app['config']->set('accelade-ai.default', 'openai');
        $app['config']->set('accelade-ai.providers.openai', [
            'api_key' => 'test-api-key',
            'model' => 'gpt-4o-mini',
            'temperature' => 0.7,
            'max_tokens' => 2048,
        ]);
        $app['config']->set('accelade-ai.providers.anthropic', [
            'api_key' => 'test-anthropic-key',
            'model' => 'claude-sonnet-4-20250514',
            'temperature' => 0.7,
            'max_tokens' => 2048,
        ]);
        $app['config']->set('accelade-ai.providers.gemini', [
            'api_key' => 'test-gemini-key',
            'model' => 'gemini-2.0-flash-exp',
            'temperature' => 0.7,
            'max_tokens' => 2048,
        ]);
    }
}
