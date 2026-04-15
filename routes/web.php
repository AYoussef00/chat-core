<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Channels\FacebookMessengerAuthController;
use App\Http\Controllers\Channels\MessengerConnectPageController;
use App\Http\Controllers\Channels\MessengerMessageController;
use App\Http\Controllers\Channels\MessengerWebhookController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');
Route::inertia('/pricing', 'Pricing', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('pricing');
Route::inertia('/data-deletion-instructions', 'DataDeletionInstructions')
    ->name('data-deletion.instructions');

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
Route::get('/webhook', [MessengerWebhookController::class, 'verify'])->name('messenger.webhook.verify');
Route::post('/webhook', [MessengerWebhookController::class, 'handle'])->name('messenger.webhook.handle');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::inertia('channels/connect', 'ConnectChannel')->name('channels.connect');
    Route::get('channels/connect/messenger', MessengerConnectPageController::class)->name('channels.connect.messenger');
    Route::get('channels/connect/messenger/facebook', [FacebookMessengerAuthController::class, 'redirect'])
        ->name('channels.connect.messenger.facebook.redirect');
    Route::get('channels/connect/messenger/facebook/callback', [FacebookMessengerAuthController::class, 'callback'])
        ->name('channels.connect.messenger.facebook.callback');
    Route::post('channels/connect/messenger/facebook/page', [FacebookMessengerAuthController::class, 'selectPage'])
        ->name('channels.connect.messenger.facebook.page.select');
    Route::post('channels/connect/messenger/send-message', [MessengerMessageController::class, 'store'])
        ->name('channels.connect.messenger.send-message');
});

require __DIR__.'/settings.php';
