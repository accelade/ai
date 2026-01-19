<?php

declare(strict_types=1);

namespace Accelade\AI\Facades;

use Accelade\AI\AIManager;
use Accelade\AI\Contracts\AIProvider;
use Illuminate\Support\Facades\Facade;

/**
 * @method static AIProvider provider(?string $name = null)
 * @method static bool hasProvider(?string $name = null)
 * @method static array<string, AIProvider> getProviders()
 * @method static array<string, class-string<AIProvider>> getAvailableProviders()
 * @method static AIManager addProvider(AIProvider|string $provider, ?AIProvider $instance = null)
 * @method static AIManager registerProvider(string $name, string $class)
 * @method static AIManager setDefault(string $name)
 * @method static ?string getDefault()
 * @method static bool isConfigured()
 * @method static array toArray()
 * @method static array chat(array $messages, array $options = [])
 * @method static void stream(array $messages, callable $callback, array $options = [])
 *
 * @see \Accelade\AI\AIManager
 */
class AI extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'accelade.ai';
    }
}
