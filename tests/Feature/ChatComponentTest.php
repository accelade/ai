<?php

it('renders the chat component', function () {
    $view = $this->blade('<x-ai-chat />');

    $view->assertSee('data-accelade');
    $view->assertSee('ai-chat');
});

it('renders with sidebar by default', function () {
    $view = $this->blade('<x-ai-chat />');

    $view->assertSee('New Chat');
});

it('renders without sidebar when disabled', function () {
    $view = $this->blade('<x-ai-chat :show-sidebar="false" />');

    $view->assertSee('data-accelade');
    $view->assertDontSee('New Chat');
});

it('renders the message input area', function () {
    $view = $this->blade('<x-ai-chat />');

    // Check for textarea
    $view->assertSee('Type your message...');
    $view->assertSee('$sendMessage');
});

it('renders with custom endpoint', function () {
    $view = $this->blade('<x-ai-chat endpoint="/custom-ai-endpoint" />');

    $view->assertSee('custom-ai-endpoint');
});

it('renders input instructions', function () {
    $view = $this->blade('<x-ai-chat />');

    // Input help text
    $view->assertSee('Enter');
    $view->assertSee('to send');
});

it('renders with configured state', function () {
    $view = $this->blade('<x-ai-chat />');

    $view->assertSee('data-accelade-state', escape: false);
    $view->assertSee('data-accelade-config', escape: false);
});
