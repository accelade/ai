{{-- AI Package Styles --}}
@php
    // Load the built CSS
    $cssPath = __DIR__ . '/../../../dist/accelade-ai.css';
    $inlineCss = file_exists($cssPath) ? file_get_contents($cssPath) : '';
@endphp
@if($inlineCss)
<style>
{!! $inlineCss !!}
</style>
@else
{{-- Fallback styles if built assets not available --}}
<style>
    /* AI Component Base Variables */
    :root {
        --ai-primary: #8b5cf6;
        --ai-primary-hover: #7c3aed;
    }

    /* Global Search Styles */
    [data-accelade-component="global-search"] {
        --ai-primary: #8b5cf6;
        --ai-primary-hover: #7c3aed;
    }

    /* Copilot Animation */
    @keyframes ai-pulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.05); opacity: 0.9; }
    }

    [data-accelade-component="copilot"] button:first-of-type:hover {
        animation: ai-pulse 1.5s ease-in-out infinite;
    }

    /* Chat Message Animations */
    @keyframes ai-slide-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    [data-accelade-component="ai-chat"] .message-enter,
    [data-accelade-component="chat"] .message-enter {
        animation: ai-slide-in 0.3s ease-out forwards;
    }

    /* Typing Indicator */
    @keyframes ai-bounce {
        0%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-6px); }
    }

    .ai-typing-dot {
        animation: ai-bounce 1.4s infinite ease-in-out both;
    }
    .ai-typing-dot:nth-child(1) { animation-delay: -0.32s; }
    .ai-typing-dot:nth-child(2) { animation-delay: -0.16s; }

    /* Scrollbar Styling */
    [data-accelade-component="ai-chat"] ::-webkit-scrollbar,
    [data-accelade-component="chat"] ::-webkit-scrollbar,
    [data-accelade-component="copilot"] ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    [data-accelade-component="ai-chat"] ::-webkit-scrollbar-track,
    [data-accelade-component="chat"] ::-webkit-scrollbar-track,
    [data-accelade-component="copilot"] ::-webkit-scrollbar-track {
        background: transparent;
    }

    [data-accelade-component="ai-chat"] ::-webkit-scrollbar-thumb,
    [data-accelade-component="chat"] ::-webkit-scrollbar-thumb,
    [data-accelade-component="copilot"] ::-webkit-scrollbar-thumb {
        background-color: rgba(156, 163, 175, 0.5);
        border-radius: 3px;
    }

    /* Code Block Styling */
    [data-accelade-component="ai-chat"] pre,
    [data-accelade-component="chat"] pre,
    [data-accelade-component="copilot"] pre {
        margin: 0.5rem 0;
        padding: 0.75rem;
        border-radius: 0.5rem;
        overflow-x: auto;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    [data-accelade-component="ai-chat"] code,
    [data-accelade-component="chat"] code,
    [data-accelade-component="copilot"] code {
        font-family: ui-monospace, SFMono-Regular, 'SF Mono', Menlo, Consolas, 'Liberation Mono', monospace;
    }

    /* Gradient Text Effect */
    .ai-gradient-text {
        background: linear-gradient(135deg, #8b5cf6 0%, #a855f7 50%, #d946ef 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Shimmer Loading Effect */
    @keyframes ai-shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    .ai-shimmer {
        background: linear-gradient(90deg, rgba(156, 163, 175, 0.1) 25%, rgba(156, 163, 175, 0.3) 50%, rgba(156, 163, 175, 0.1) 75%);
        background-size: 200% 100%;
        animation: ai-shimmer 1.5s infinite;
    }
</style>
@endif
