<?php

it('renders the global search component', function () {
    $view = $this->blade('<x-ai-global-search />');

    $view->assertSee('data-accelade');
    $view->assertSee('global-search');
});

it('renders with custom placeholder', function () {
    $view = $this->blade('<x-ai-global-search placeholder="Find something..." />');

    $view->assertSee('Find something...');
});

it('renders with AI toggle button when useAI is true', function () {
    $view = $this->blade('<x-ai-global-search :use-ai="true" />');

    $view->assertSee('Toggle AI');
});

it('renders the search input', function () {
    $view = $this->blade('<x-ai-global-search />');

    // Check for input element attributes
    $view->assertSee('a-model');
});

it('renders keyboard shortcut indicator', function () {
    $view = $this->blade('<x-ai-global-search />');

    // Default shortcut is cmd+k shown as ⌘K
    $view->assertSee('⌘');
});

it('renders with accelade class', function () {
    $view = $this->blade('<x-ai-global-search />');

    $view->assertSee('accelade-global-search');
});
