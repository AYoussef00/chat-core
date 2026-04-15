<?php

namespace App\Http\Controllers\Channels;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\AbstractProvider;
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

        $redirectUri = (string) config('services.facebook.redirect');

        /** @var AbstractProvider $provider */
        $provider = Socialite::driver('facebook');

        $response = $provider
            ->redirectUrl($redirectUri)
            ->redirect();

        parse_str((string) parse_url($response->getTargetUrl(), PHP_URL_QUERY), $query);

        logger()->info('Facebook OAuth redirect URL generated.', [
            'configured_redirect_uri' => $redirectUri,
            'sent_redirect_uri' => $query['redirect_uri'] ?? null,
            'target_url' => $response->getTargetUrl(),
        ]);

        return $response;
    }

    public function callback(Request $request): RedirectResponse
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        try {
            $redirectUri = (string) config('services.facebook.redirect');
            /** @var AbstractProvider $provider */
            $provider = Socialite::driver('facebook');

            logger()->info('Facebook OAuth callback received.', [
                'configured_redirect_uri' => $redirectUri,
                'callback_url' => $request->fullUrl(),
            ]);

            $facebookUser = $provider
                ->redirectUrl($redirectUri)
                ->user();
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
