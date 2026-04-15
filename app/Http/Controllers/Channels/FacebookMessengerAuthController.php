<?php

namespace App\Http\Controllers\Channels;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\InvalidStateException;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirect;

class FacebookMessengerAuthController extends Controller
{
    public function redirect(Request $request): RedirectResponse|SymfonyRedirect
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

        $request->session()->forget([
            'messenger.facebook_pages',
            'messenger.selected_facebook_page_id',
            'messenger.selected_facebook_page',
        ]);

        $response = $provider
            ->scopes(['public_profile', 'email', 'pages_show_list'])
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

        try {
            $userAccessToken = data_get($facebookUser, 'token');
            if (! is_string($userAccessToken) || $userAccessToken === '') {
                Inertia::flash('toast', [
                    'type' => 'error',
                    'message' => 'Could not read Facebook access token. Please sign in again.',
                ]);

                return redirect()->route('channels.connect.messenger');
            }

            $pagesResponse = Http::acceptJson()
                ->timeout(15)
                ->get('https://graph.facebook.com/v22.0/me/accounts', [
                    'fields' => 'id,name,access_token,category,picture{url}',
                    'access_token' => $userAccessToken,
                ])
                ->throw();
        } catch (RequestException $e) {
            report($e);

            Inertia::flash('toast', [
                'type' => 'error',
                'message' => 'Facebook login succeeded, but we could not fetch your pages. Confirm pages permissions and try again.',
            ]);

            return redirect()->route('channels.connect.messenger');
        }

        $pages = collect($pagesResponse->json('data', []))
            ->map(fn (array $page): array => [
                'id' => (string) ($page['id'] ?? ''),
                'name' => (string) ($page['name'] ?? ''),
                'category' => (string) ($page['category'] ?? ''),
                'picture' => $page['picture']['data']['url'] ?? null,
                'page_access_token' => $page['access_token'] ?? null,
            ])
            ->filter(fn (array $page): bool => $page['id'] !== '' && $page['name'] !== '')
            ->values()
            ->all();

        $request->session()->put('messenger.facebook_account', [
            'id' => $facebookUser->getId(),
            'name' => $facebookUser->getName(),
            'email' => $facebookUser->getEmail(),
            'connected_at' => now()->toIso8601String(),
        ]);
        $request->session()->put('messenger.facebook_pages', $pages);

        Inertia::flash('toast', [
            'type' => count($pages) > 0 ? 'success' : 'info',
            'message' => count($pages) > 0
                ? 'Facebook account connected. Choose the page you want to connect.'
                : 'Facebook account connected, but no pages were found on this account.',
        ]);

        return redirect()->route('channels.connect.messenger');
    }

    public function selectPage(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'page_id' => ['required', 'string'],
        ]);

        $pageId = $validated['page_id'];
        $pages = collect($request->session()->get('messenger.facebook_pages', []));
        $selectedPage = $pages->firstWhere('id', $pageId);

        if (! is_array($selectedPage)) {
            Inertia::flash('toast', [
                'type' => 'error',
                'message' => 'Selected page is invalid. Please choose a page from the list.',
            ]);

            return redirect()->route('channels.connect.messenger');
        }

        $request->session()->put('messenger.selected_facebook_page_id', $pageId);
        $request->session()->put('messenger.selected_facebook_page', $selectedPage);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => "Page \"{$selectedPage['name']}\" has been selected successfully.",
        ]);

        return redirect()->route('channels.connect.messenger');
    }
}
