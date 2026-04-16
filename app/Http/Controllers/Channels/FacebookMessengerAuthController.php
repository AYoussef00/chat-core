<?php

namespace App\Http\Controllers\Channels;

use App\Models\FacebookPageConnection;
use App\Services\Facebook\FacebookService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
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

        $isFresh = $request->boolean('fresh');

        $request->session()->forget(array_filter([
            'messenger.facebook_pages',
            'messenger.selected_facebook_page_id',
            'messenger.selected_facebook_page',
            $isFresh ? 'messenger.facebook_account' : null,
            $isFresh ? 'messenger.facebook_user_access_token' : null,
        ]));

        if ($isFresh && $request->user() && Schema::hasColumn('users', 'facebook_user_access_token')) {
            $request->user()?->forceFill(['facebook_user_access_token' => null])->save();
        }

        /** @var AbstractProvider $provider */
        $provider = Socialite::driver('facebook');

        $response = $provider
            ->scopes([
                'public_profile',
                'pages_show_list',
                'pages_manage_metadata',
                'pages_messaging',
            ])
            ->with($isFresh ? [
                // Force Facebook to re-auth (avoid "Reconnect/Continue").
                'auth_type' => 'reauthenticate',
                'auth_nonce' => Str::random(16),
            ] : [])
            ->redirect();

        parse_str((string) parse_url($response->getTargetUrl(), PHP_URL_QUERY), $query);

        logger()->info('Facebook OAuth redirect URL generated.', [
            'configured_redirect_uri' => config('services.facebook.redirect'),
            'configured_client_id' => config('services.facebook.client_id'),
            'configured_graph_version' => config('services.facebook.graph_version'),
            'configured_scopes' => [
                'public_profile',
                'pages_show_list',
                'pages_manage_metadata',
                'pages_messaging',
            ],
            'sent_redirect_uri' => $query['redirect_uri'] ?? null,
            'sent_client_id' => $query['client_id'] ?? null,
            'sent_scope' => $query['scope'] ?? null,
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

            logger()->info('Facebook user fetched', (array) $facebookUser);
        } catch (InvalidStateException $e) {
            Inertia::flash('toast', [
                'type' => 'error',
                'message' => 'Facebook session expired. Please sign in again.',
            ]);

            return redirect()->route('channels.connect.messenger');
        } catch (\Throwable $e) {
            logger()->error('Facebook error: '.$e->getMessage());
            report($e);

            Inertia::flash('toast', [
                'type' => 'error',
                'message' => 'Could not connect to Facebook. Please try again.',
            ]);

            return redirect()->route('channels.connect.messenger');
        }

        $userAccessToken = (string) data_get($facebookUser, 'token', '');
        if ($userAccessToken === '') {
            Inertia::flash('toast', [
                'type' => 'error',
                'message' => 'Facebook did not return a user access token. Please try again.',
            ]);

            return redirect()->route('channels.connect.messenger');
        }

        try {
            $facebookPages = $this->facebookService->getPagesForUserToken($userAccessToken);

            logger()->info('Facebook pages fetched', [
                'count' => count($facebookPages),
                'pages' => $facebookPages,
            ]);
        } catch (RequestException $e) {
            logger()->error('Facebook pages fetch failed', [
                'message' => $e->getMessage(),
                'response_body' => $e->response?->body(),
            ]);
            report($e);

            Inertia::flash('toast', [
                'type' => 'error',
                'message' => 'Facebook login worked, but we could not load your pages. Please make sure you granted page access.',
            ]);

            return redirect()->route('channels.connect.messenger');
        }

        $request->session()->put('messenger.facebook_account', [
            'id' => $facebookUser->getId(),
            'name' => $facebookUser->getName(),
            'email' => $facebookUser->getEmail(),
            'connected_at' => now()->toIso8601String(),
        ]);
        $request->session()->put('messenger.facebook_user_access_token', $userAccessToken);
        $request->session()->put('messenger.facebook_pages', $facebookPages);

        $user = $request->user();
        if ($user && Schema::hasColumn('users', 'facebook_user_access_token')) {
            $user->forceFill([
                'facebook_user_access_token' => $userAccessToken,
            ])->save();
        }

        if ($facebookPages === []) {
            Inertia::flash('toast', [
                'type' => 'warning',
                'message' => 'Facebook login completed, but no manageable pages were returned for this account.',
            ]);

            return redirect()->route('channels.connect.messenger');
        }

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Facebook login completed successfully. Choose a page to connect.',
        ]);

        return redirect()->route('channels.connect.messenger');
    }

    public function selectPage(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'page_id' => ['required', 'string'],
        ]);

        $pageId = $validated['page_id'];
        logger()->info('Facebook page selection requested.', [
            'page_id' => $pageId,
            'user_id' => $request->user()?->id,
        ]);

        $pages = collect($request->session()->get('messenger.facebook_pages', []));
        $selectedPage = $pages->firstWhere('id', $pageId);

        if (! is_array($selectedPage)) {
            logger()->warning('Facebook page selection failed: page missing from session.', [
                'page_id' => $pageId,
                'session_pages_count' => $pages->count(),
                'user_id' => $request->user()?->id,
            ]);
            Inertia::flash('toast', [
                'type' => 'error',
                'message' => 'Selected page is invalid. Please choose a page from the list.',
            ]);

            return redirect()->route('channels.connect.messenger');
        }

        $pageAccessToken = (string) ($selectedPage['access_token'] ?? '');
        if ($pageAccessToken === '') {
            logger()->warning('Facebook page selection failed: missing page access token.', [
                'page_id' => $pageId,
                'user_id' => $request->user()?->id,
            ]);
            Inertia::flash('toast', [
                'type' => 'error',
                'message' => 'The selected page does not have a valid access token.',
            ]);

            return redirect()->route('channels.connect.messenger');
        }

        try {
            $this->facebookService->subscribePage($pageId, $pageAccessToken);
            logger()->info('Facebook page subscription succeeded.', [
                'page_id' => $pageId,
                'page_name' => (string) ($selectedPage['name'] ?? 'Facebook Page'),
                'user_id' => $request->user()?->id,
            ]);
        } catch (RequestException $e) {
            logger()->error('Facebook page subscription failed.', [
                'page_id' => $pageId,
                'page_name' => (string) ($selectedPage['name'] ?? 'Facebook Page'),
                'user_id' => $request->user()?->id,
                'message' => $e->getMessage(),
                'response_body' => $e->response?->body(),
            ]);
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

        logger()->info('Facebook page connection saved.', [
            'connection_id' => $connection->id,
            'page_id' => $connection->page_id,
            'page_name' => $connection->page_name,
            'status' => $connection->status,
            'user_id' => $user->id,
        ]);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => "Page \"{$connection->page_name}\" connected and subscribed successfully.",
        ]);

        return redirect()->route('onboarding.strategy');
    }
}
