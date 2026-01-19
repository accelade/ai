<?php

declare(strict_types=1);

namespace Accelade\AI;

use Accelade\AI\Providers\AnthropicProvider;
use Accelade\AI\Providers\GeminiProvider;
use Accelade\AI\Providers\OpenAIProvider;
use Accelade\Docs\DocsRegistry;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AIServiceProvider extends ServiceProvider
{
    /**
     * Documentation sections configuration.
     *
     * @var array<int, array<string, mixed>>
     */
    private const DOCUMENTATION_SECTIONS = [
        ['id' => 'ai-overview', 'label' => 'Overview', 'icon' => '🤖', 'markdown' => 'overview.md', 'description' => 'AI components for intelligent interactions', 'keywords' => ['ai', 'artificial intelligence', 'chat', 'copilot', 'search'], 'view' => 'accelade-ai::docs.sections.overview'],
        ['id' => 'ai-configuration', 'label' => 'Configuration', 'icon' => '⚙️', 'markdown' => 'configuration.md', 'description' => 'Configure AI providers and settings', 'keywords' => ['config', 'settings', 'provider', 'api key'], 'subgroup' => 'setup', 'view' => 'accelade-ai::docs.sections.configuration'],
        ['id' => 'ai-providers', 'label' => 'Providers', 'icon' => '🔌', 'markdown' => 'providers.md', 'description' => 'AI provider implementations', 'keywords' => ['openai', 'anthropic', 'gemini', 'claude', 'gpt'], 'subgroup' => 'setup', 'view' => 'accelade-ai::docs.sections.providers'],
    ];

    /**
     * Component documentation sections configuration.
     *
     * @var array<int, array<string, mixed>>
     */
    private const COMPONENT_SECTIONS = [
        ['id' => 'ai-global-search', 'label' => 'Global Search', 'icon' => '🔍', 'markdown' => 'global-search.md', 'description' => 'Spotlight-like search with AI', 'keywords' => ['search', 'spotlight', 'cmd+k', 'fuzzy'], 'view' => 'accelade-ai::docs.sections.global-search'],
        ['id' => 'ai-chat', 'label' => 'AI Chat', 'icon' => '💬', 'markdown' => 'chat.md', 'description' => 'ChatGPT-like chat interface', 'keywords' => ['chat', 'conversation', 'messages', 'streaming'], 'view' => 'accelade-ai::docs.sections.chat'],
        ['id' => 'ai-copilot', 'label' => 'Copilot', 'icon' => '🚀', 'markdown' => 'copilot.md', 'description' => 'AI assistant widget with page context', 'keywords' => ['copilot', 'assistant', 'widget', 'context'], 'view' => 'accelade-ai::docs.sections.copilot'],
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/accelade-ai.php',
            'accelade-ai'
        );

        // Register the AI Manager
        $this->app->singleton('accelade.ai', function () {
            $manager = new AIManager;

            // Register built-in providers
            $manager->registerProvider('openai', OpenAIProvider::class);
            $manager->registerProvider('anthropic', AnthropicProvider::class);
            $manager->registerProvider('gemini', GeminiProvider::class);

            return $manager;
        });

        // Alias for dependency injection
        $this->app->alias('accelade.ai', AIManager::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'accelade-ai');

        // Register routes
        $this->registerRoutes();

        // Register Blade directives
        $this->registerBladeDirectives();

        // Register Blade components
        $this->registerComponents();

        // Inject assets into Accelade
        $this->injectAcceladeAssets();

        // Register documentation
        $this->registerDocumentation();

        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
        }
    }

    /**
     * Register AI routes.
     */
    protected function registerRoutes(): void
    {
        Route::group([
            'prefix' => config('accelade-ai.routes.prefix', 'accelade-ai'),
            'middleware' => config('accelade-ai.routes.middleware', ['web']),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    /**
     * Register Blade directives.
     */
    protected function registerBladeDirectives(): void
    {
        // @aiScripts - Include AI JavaScript
        Blade::directive('aiScripts', function () {
            return "<?php echo view('accelade-ai::scripts')->render(); ?>";
        });

        // @aiStyles - Include AI CSS
        Blade::directive('aiStyles', function () {
            return "<?php echo view('accelade-ai::styles')->render(); ?>";
        });

        // @aiGlobalSearch - Render the global search component
        Blade::directive('aiGlobalSearch', function ($expression) {
            if (empty($expression)) {
                return "<?php echo view('accelade-ai::components.global-search')->render(); ?>";
            }

            return "<?php echo view('accelade-ai::components.global-search', {$expression})->render(); ?>";
        });

        // @aiChat - Render the chat component
        Blade::directive('aiChat', function ($expression) {
            if (empty($expression)) {
                return "<?php echo view('accelade-ai::components.chat')->render(); ?>";
            }

            return "<?php echo view('accelade-ai::components.chat', {$expression})->render(); ?>";
        });

        // @aiCopilot - Render the copilot widget
        Blade::directive('aiCopilot', function ($expression) {
            if (empty($expression)) {
                return "<?php echo view('accelade-ai::components.copilot')->render(); ?>";
            }

            return "<?php echo view('accelade-ai::components.copilot', {$expression})->render(); ?>";
        });
    }

    /**
     * Register Blade components.
     */
    protected function registerComponents(): void
    {
        Blade::componentNamespace('Accelade\\AI\\Components', 'ai');

        // Anonymous components
        Blade::component('accelade-ai::components.global-search', 'ai-global-search');
        Blade::component('accelade-ai::components.chat', 'ai-chat');
        Blade::component('accelade-ai::components.copilot', 'ai-copilot');
    }

    /**
     * Inject AI scripts and styles into Accelade.
     */
    protected function injectAcceladeAssets(): void
    {
        if (! $this->app->bound('accelade')) {
            return;
        }

        /** @var \Accelade\Accelade $accelade */
        $accelade = $this->app->make('accelade');

        $accelade->registerScript('ai', function () {
            return view('accelade-ai::scripts')->render();
        });

        $accelade->registerStyle('ai', function () {
            return view('accelade-ai::styles')->render();
        });
    }

    /**
     * Register documentation sections.
     */
    protected function registerDocumentation(): void
    {
        if (! $this->app->bound('accelade.docs')) {
            return;
        }

        /** @var DocsRegistry $registry */
        $registry = $this->app->make('accelade.docs');

        $registry->registerPackage('ai', __DIR__.'/../docs');
        $registry->registerGroup('ai', 'AI', '🤖', 60);

        // Register sub-groups
        $registry->registerSubgroup('ai', 'setup', '⚙️ Setup', '', 10);
        $registry->registerSubgroup('ai', 'components', '🧩 Components', '', 20);

        // Register main sections
        foreach (self::DOCUMENTATION_SECTIONS as $section) {
            $this->registerSection($registry, $section);
        }

        // Register component sections
        foreach (self::COMPONENT_SECTIONS as $section) {
            $this->registerComponentSection($registry, $section);
        }
    }

    /**
     * Register a documentation section.
     *
     * @param  array<string, mixed>  $section
     */
    protected function registerSection(DocsRegistry $registry, array $section): void
    {
        $builder = $registry->section($section['id'])
            ->label($section['label'])
            ->icon($section['icon'])
            ->markdown($section['markdown'])
            ->description($section['description'])
            ->keywords($section['keywords'])
            ->demo()
            ->view($section['view'])
            ->package('ai')
            ->inGroup('ai');

        if (isset($section['subgroup'])) {
            $builder->inSubgroup($section['subgroup']);
        }

        $builder->register();
    }

    /**
     * Register a component documentation section.
     *
     * @param  array<string, mixed>  $section
     */
    protected function registerComponentSection(DocsRegistry $registry, array $section): void
    {
        $registry->section($section['id'])
            ->label($section['label'])
            ->icon($section['icon'])
            ->markdown($section['markdown'])
            ->description($section['description'])
            ->keywords($section['keywords'])
            ->demo()
            ->view($section['view'])
            ->package('ai')
            ->inGroup('ai')
            ->inSubgroup('components')
            ->register();
    }

    /**
     * Register publishing.
     */
    protected function registerPublishing(): void
    {
        // Publish config
        $this->publishes([
            __DIR__.'/../config/accelade-ai.php' => config_path('accelade-ai.php'),
        ], 'accelade-ai-config');

        // Publish views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/accelade-ai'),
        ], 'accelade-ai-views');
    }
}
