<?php

use Accelade\AI\Http\Controllers\AIController;
use Accelade\AI\Http\Controllers\GlobalSearchController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| AI Package Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the AIServiceProvider within a group which
| is assigned the "accelade-ai" prefix and the configured middleware.
|
*/

// AI Chat Endpoints
Route::post('/chat', [AIController::class, 'chat'])->name('accelade-ai.chat');
Route::post('/stream', [AIController::class, 'stream'])->name('accelade-ai.stream');
Route::get('/config', [AIController::class, 'config'])->name('accelade-ai.config');

// Global Search
Route::post('/search', [GlobalSearchController::class, 'search'])->name('accelade-ai.search');

// Demo routes (only in non-production)
if (config('accelade-ai.routes.demo', false) || app()->environment('local', 'development')) {
    Route::get('/demo/global-search', function () {
        return view('accelade-ai::demo.global-search');
    })->name('accelade-ai.demo.global-search');

    Route::get('/demo/chat', function () {
        return view('accelade-ai::demo.chat');
    })->name('accelade-ai.demo.chat');

    Route::get('/demo/copilot', function () {
        return view('accelade-ai::demo.copilot');
    })->name('accelade-ai.demo.copilot');
}
