<?php

namespace App\Http\Controllers;

use App\Models\FacebookPageConnection;
use App\Services\Facebook\FacebookService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
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
        }

        return Inertia::render('Dashboard', [
            'connectedPages' => $connectedPages,
        ]);
    }

    public function destroy(Request $request, FacebookPageConnection $facebookPageConnection): RedirectResponse
    {
        $user = $request->user();

        if (! $user || $facebookPageConnection->user_id !== $user->id) {
            abort(403);
        }

        try {
            $this->facebookService->unsubscribePage(
                $facebookPageConnection->page_id,
                (string) $facebookPageConnection->page_access_token,
            );
        } catch (RequestException $e) {
            report($e);

            Inertia::flash('toast', [
                'type' => 'error',
                'message' => 'Could not disconnect this page from Facebook. Please try again.',
            ]);

            return redirect()->route('dashboard');
        }

        $pageName = $facebookPageConnection->page_name;
        $facebookPageConnection->delete();

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => "Page \"{$pageName}\" disconnected successfully.",
        ]);

        return redirect()->route('dashboard');
    }
}

