<?php

namespace App\Http\Controllers\Channels;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirect;

class FacebookMessengerAuthController extends Controller
{
    public function redirect(): RedirectResponse|SymfonyRedirect
    {
        if (! config('services.facebook.client_id') || ! config('services.facebook.client_secret')) {
            Inertia::flash('toast', [
                'type' => 'error',
                'message' => 'Facebook credentials are missing. Please configure FACEBOOK_CLIENT_ID and FACEBOOK_CLIENT_SECRET.',
            ]);

            return redirect()->route('channels.connect.messenger');
        }

        return Socialite::driver('facebook')->redirect();
    }

    public function callback(Request $request): RedirectResponse
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        try {
            $facebookUser = Socialite::driver('facebook')->user();
        } catch (InvalidStateException $e) {
            Inertia::flash('toast', [
                'type' => 'error',
                'message' => 'Facebook session expired. Please sign in again.',
            ]);

            return redirect()->route('channels.connect.messenger');
        } catch (\Throwable $e) {
            report($e);

            Inertia::flash('toast', [
                'type' => 'error',
                'message' => 'Could not connect to Facebook. Please try again.',
            ]);

            return redirect()->route('channels.connect.messenger');
        }

        $request->session()->put('messenger.facebook_account', [
            'id' => $facebookUser->getId(),
            'name' => $facebookUser->getName(),
            'email' => $facebookUser->getEmail(),
            'connected_at' => now()->toIso8601String(),
        ]);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Facebook account connected successfully.',
        ]);

        return redirect()->route('channels.connect.messenger');
    }
}
