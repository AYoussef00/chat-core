<?php

namespace App\Http\Controllers\Channels;

use App\Models\FacebookPageConnection;
use App\Services\Facebook\FacebookService;
use Illuminate\Http\Client\RequestException;
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
    public function __construct(
        private readonly FacebookService $facebookService,
    ) {}

    public function redirect(Request $request): RedirectResponse|SymfonyRedirect
    {
        if (! config('services.facebook.client_id') || ! config('services.facebook.client_secret')) {
            Inertia::flash('toast', [
                'type' => 'error',
                'message' => 'Facebook credentials are missing. Please configure FACEBOOK_CLIENT_ID and FACEBOOK_CLIENT_SECRET.',
            ]);

            return redirect()->route('channels.connect.messenger');
        }

        $request->session()->forget([
            'messenger.facebook_pages',
            'messenger.selected_facebook_page_id',
            'messenger.selected_facebook_page',
        ]);

        /** @var AbstractProvider $provider */
        $provider = Socialite::driver('facebook');

        $response = $provider
            ->scopes([
                'public_profile',
                'email',
            ])
            ->redirect();

        parse_str((string) parse_url($response->getTargetUrl(), PHP_URL_QUERY), $query);

        logger()->info('Facebook OAuth redirect URL generated.', [
            'configured_redirect_uri' => config('services.facebook.redirect'),
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
            logger()->info('Facebook OAuth callback received.', [
                'configured_redirect_uri' => config('services.facebook.redirect'),
                'callback_url' => $request->fullUrl(),
            ]);

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
        $request->session()->put('messenger.facebook_user_access_token', data_get($facebookUser, 'token'));
        $request->session()->put('messenger.facebook_pages', []);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Facebook login completed successfully.',
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

        $pageAccessToken = (string) ($selectedPage['access_token'] ?? '');
        if ($pageAccessToken === '') {
            Inertia::flash('toast', [
                'type' => 'error',
                'message' => 'The selected page does not have a valid access token.',
            ]);

            return redirect()->route('channels.connect.messenger');
        }

        try {
            $this->facebookService->subscribePage($pageId, $pageAccessToken);
        } catch (RequestException $e) {
            report($e);

            Inertia::flash('toast', [
                'type' => 'error',
                'message' => 'Could not subscribe this page to the app. Please check Facebook app permissions.',
            ]);

            return redirect()->route('channels.connect.messenger');
        }

        $user = $request->user();
        if (! $user) {
            return redirect()->route('login');
        }

        $connection = FacebookPageConnection::query()->updateOrCreate(
            [
                'user_id' => $user->id,
                'page_id' => $pageId,
            ],
            [
                'page_name' => (string) ($selectedPage['name'] ?? 'Facebook Page'),
                'page_access_token' => $pageAccessToken,
                'status' => 'active',
                'bot_context' => [
                    'platform' => 'messenger',
                    'connected_at' => now()->toIso8601String(),
                ],
            ],
        );

        $request->session()->put('messenger.selected_facebook_page_id', $pageId);
        $request->session()->put('messenger.selected_facebook_page', [
            'id' => $connection->page_id,
            'name' => $connection->page_name,
        ]);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => "Page \"{$connection->page_name}\" connected and subscribed successfully.",
        ]);

        return redirect()->route('channels.connect.messenger');
    }
}
