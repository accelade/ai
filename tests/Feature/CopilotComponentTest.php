<?php

it('renders the copilot component', function () {
    $view = $this->blade('<x-ai-copilot />');

    $view->assertSee('data-accelade-copilot');
});

it('renders in bottom-right position by default', function () {
    $view = $this->blade('<x-ai-copilot />');

    $view->assertSee('bottom-4 right-4');
});

it('renders in bottom-left position', function () {
    $view = $this->blade('<x-ai-copilot position="bottom-left" />');

    $view->assertSee('bottom-4 left-4');
});

it('renders in top-right position', function () {
    $view = $this->blade('<x-ai-copilot position="top-right" />');

    $view->assertSee('top-4 right-4');
});

it('renders in top-left position', function () {
    $view = $this->blade('<x-ai-copilot position="top-left" />');

    $view->assertSee('top-4 left-4');
});

it('renders with custom keyboard shortcut', function () {
    $view = $this->blade('<x-ai-copilot keyboard-shortcut="cmd+j" />');

    $view->assertSee('data-keyboard-shortcut', escape: false);
    $view->assertSee('cmd+j');
});

it('renders with read-context enabled by default', function () {
    $view = $this->blade('<x-ai-copilot />');

    $view->assertSee('data-read-context', escape: false);
});

it('renders with read-context disabled', function () {
    $view = $this->blade('<x-ai-copilot :read-context="false" />');

    $view->assertSee('data-read-context', escape: false);
});

it('renders with custom provider', function () {
    $view = $this->blade('<x-ai-copilot provider="anthropic" />');

    $view->assertSee('data-provider', escape: false);
    $view->assertSee('anthropic');
});

it('renders floating action button', function () {
    $view = $this->blade('<x-ai-copilot />');

    $view->assertSee('AI Copilot');
});

it('renders default suggestions', function () {
    $view = $this->blade('<x-ai-copilot />');

    $view->assertSee('Explain this page');
    $view->assertSee('Summarize the data');
    $view->assertSee('Help me understand');
});

it('renders custom suggestions', function () {
    $view = $this->blade('<x-ai-copilot :suggestions="[\'Custom suggestion 1\', \'Custom suggestion 2\']" />');

    $view->assertSee('Custom suggestion 1');
    $view->assertSee('Custom suggestion 2');
});
