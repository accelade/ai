/**
 * Accelade AI - AI components for intelligent interactions
 *
 * This module provides:
 * - Global search with AI-powered results
 * - Chat interface with streaming support
 * - Copilot widget with page context awareness
 */

import './styles/ai.css';
import { AIManager, type AIConfig, type ChatMessage, type SearchOptions, type ChatOptions } from './core/AIManager';

// Create global instance
const manager = new AIManager();

// Export for module usage
export { AIManager, type AIConfig, type ChatMessage, type SearchOptions, type ChatOptions };
export default manager;

// Expose to window for script usage
declare global {
    interface Window {
        AcceladeAI: AIManager;
    }
}

window.AcceladeAI = manager;

console.log('[AcceladeAI] Scripts loaded');
