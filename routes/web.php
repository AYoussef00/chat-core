<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Channels\FacebookMessengerAuthController;
use App\Http\Controllers\Channels\MessengerConnectPageController;
use App\Http\Controllers\Channels\MessengerMessageController;
use App\Http\Controllers\Channels\MessengerWebhookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Onboarding\OnboardingAccountAboutController;
use App\Http\Controllers\Onboarding\OnboardingPersonRoleController;
use App\Http\Controllers\Onboarding\OnboardingStrategyController;
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
    Route::get('dashboard', DashboardController::class)->name('dashboard');
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

    Route::get('onboarding/strategy', [OnboardingStrategyController::class, 'show'])
        ->name('onboarding.strategy');
    Route::post('onboarding/strategy', [OnboardingStrategyController::class, 'store'])
        ->name('onboarding.strategy.store');

    Route::get('onboarding/account-about', [OnboardingAccountAboutController::class, 'show'])
        ->name('onboarding.account.about');
    Route::post('onboarding/account-about', [OnboardingAccountAboutController::class, 'store'])
        ->name('onboarding.account.about.store');

    Route::get('onboarding/person-role', [OnboardingPersonRoleController::class, 'show'])
        ->name('onboarding.person-role');
    Route::post('onboarding/person-role', [OnboardingPersonRoleController::class, 'store'])
        ->name('onboarding.person-role.store');
});

require __DIR__.'/settings.php';
