<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Channels\FacebookMessengerAuthController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
    Route::inertia('channels/connect', 'ConnectChannel')->name('channels.connect');
    Route::inertia('channels/connect/messenger', 'ConnectMessenger')->name('channels.connect.messenger');
    Route::get('channels/connect/messenger/facebook', [FacebookMessengerAuthController::class, 'redirect'])
        ->name('channels.connect.messenger.facebook.redirect');
    Route::get('channels/connect/messenger/facebook/callback', [FacebookMessengerAuthController::class, 'callback'])
        ->name('channels.connect.messenger.facebook.callback');
});

require __DIR__.'/settings.php';
