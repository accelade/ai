{{-- AI Package Styles --}}
<style>
    /* Global Search Styles */
    [data-accelade-global-search] {
        --ai-primary: theme('colors.violet.500', #8b5cf6);
        --ai-primary-hover: theme('colors.violet.600', #7c3aed);
    }

    /* Copilot Animation */
    @keyframes ai-pulse {
        0%, 100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.05);
            opacity: 0.9;
        }
    }

    [data-accelade-copilot] button:first-of-type:hover {
        animation: ai-pulse 1.5s ease-in-out infinite;
    }

    /* Chat Message Animations */
    @keyframes ai-slide-in {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    [data-accelade-chat] .message-enter {
        animation: ai-slide-in 0.3s ease-out forwards;
    }

    /* Typing Indicator */
    @keyframes ai-bounce {
        0%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-6px);
        }
    }

    .ai-typing-dot {
        animation: ai-bounce 1.4s infinite ease-in-out both;
    }

    .ai-typing-dot:nth-child(1) {
        animation-delay: -0.32s;
    }

    .ai-typing-dot:nth-child(2) {
        animation-delay: -0.16s;
    }

    /* Scrollbar Styling for Chat */
    [data-accelade-chat] ::-webkit-scrollbar,
    [data-accelade-copilot] ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    [data-accelade-chat] ::-webkit-scrollbar-track,
    [data-accelade-copilot] ::-webkit-scrollbar-track {
        background: transparent;
    }

    [data-accelade-chat] ::-webkit-scrollbar-thumb,
    [data-accelade-copilot] ::-webkit-scrollbar-thumb {
        background-color: rgba(156, 163, 175, 0.5);
        border-radius: 3px;
    }

    [data-accelade-chat] ::-webkit-scrollbar-thumb:hover,
    [data-accelade-copilot] ::-webkit-scrollbar-thumb:hover {
        background-color: rgba(156, 163, 175, 0.7);
    }

    /* Dark mode scrollbar */
    .dark [data-accelade-chat] ::-webkit-scrollbar-thumb,
    .dark [data-accelade-copilot] ::-webkit-scrollbar-thumb {
        background-color: rgba(75, 85, 99, 0.5);
    }

    .dark [data-accelade-chat] ::-webkit-scrollbar-thumb:hover,
    .dark [data-accelade-copilot] ::-webkit-scrollbar-thumb:hover {
        background-color: rgba(75, 85, 99, 0.7);
    }

    /* Code Block Styling in Messages */
    [data-accelade-chat] pre,
    [data-accelade-copilot] pre {
        margin: 0.5rem 0;
        padding: 0.75rem;
        border-radius: 0.5rem;
        overflow-x: auto;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    [data-accelade-chat] code,
    [data-accelade-copilot] code {
        font-family: ui-monospace, SFMono-Regular, 'SF Mono', Menlo, Consolas, 'Liberation Mono', monospace;
    }

    /* Search Result Highlight */
    [data-accelade-global-search] .search-highlight {
        background-color: rgba(139, 92, 246, 0.2);
        border-radius: 2px;
        padding: 0 2px;
    }

    .dark [data-accelade-global-search] .search-highlight {
        background-color: rgba(139, 92, 246, 0.3);
    }

    /* Focus Visible for Accessibility */
    [data-accelade-global-search] *:focus-visible,
    [data-accelade-chat] *:focus-visible,
    [data-accelade-copilot] *:focus-visible {
        outline: 2px solid var(--ai-primary, #8b5cf6);
        outline-offset: 2px;
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
        0% {
            background-position: -200% 0;
        }
        100% {
            background-position: 200% 0;
        }
    }

    .ai-shimmer {
        background: linear-gradient(
            90deg,
            rgba(156, 163, 175, 0.1) 25%,
            rgba(156, 163, 175, 0.3) 50%,
            rgba(156, 163, 175, 0.1) 75%
        );
        background-size: 200% 100%;
        animation: ai-shimmer 1.5s infinite;
    }

    .dark .ai-shimmer {
        background: linear-gradient(
            90deg,
            rgba(75, 85, 99, 0.1) 25%,
            rgba(75, 85, 99, 0.3) 50%,
            rgba(75, 85, 99, 0.1) 75%
        );
        background-size: 200% 100%;
    }
</style>
