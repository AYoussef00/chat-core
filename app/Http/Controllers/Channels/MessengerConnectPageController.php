<?php

namespace App\Http\Controllers\Channels;

use App\Models\FacebookPageConnection;
use App\Services\Facebook\FacebookService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;

class MessengerConnectPageController extends Controller
{
    public function __construct(
        private readonly FacebookService $facebookService,
    ) {}

    public function __invoke(Request $request): Response
    {
        $user = $request->user();
        $connectedPages = [];

        if ($user) {
            $connectedPages = FacebookPageConnection::query()
                ->where('user_id', $user->id)
                ->orderBy('page_name')
                ->get(['id', 'page_id', 'page_name', 'status', 'updated_at'])
                ->toArray();

            $sessionPages = $request->session()->get('messenger.facebook_pages', []);
            if (($sessionPages === [] || ! is_array($sessionPages)) && (string) ($user->facebook_user_access_token ?? '') !== '') {
                try {
                    $facebookPages = $this->facebookService->getPagesForUserToken((string) $user->facebook_user_access_token);
                    $request->session()->put('messenger.facebook_pages', $facebookPages);
                } catch (RequestException $e) {
                    report($e);
                    $request->session()->forget([
                        'messenger.facebook_pages',
                        'messenger.selected_facebook_page_id',
                        'messenger.selected_facebook_page',
                        'messenger.facebook_account',
                        'messenger.facebook_user_access_token',
                    ]);

                    $user->forceFill(['facebook_user_access_token' => null])->save();

                    Inertia::flash('toast', [
                        'type' => 'error',
                        'message' => 'Your Facebook connection expired. Please sign in again.',
                    ]);
                }
            }
        }

        return Inertia::render('ConnectMessenger', [
            'facebookAccount' => $request->session()->get('messenger.facebook_account'),
            'facebookPages' => $request->session()->get('messenger.facebook_pages', []),
            'selectedFacebookPageId' => $request->session()->get('messenger.selected_facebook_page_id'),
            'connectedPages' => $connectedPages,
        ]);
    }
}
