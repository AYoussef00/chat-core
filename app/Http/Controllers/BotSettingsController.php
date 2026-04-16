<?php

namespace App\Http\Controllers;

use App\Models\FacebookPageConnection;
use App\Services\Bots\BotSettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;

class BotSettingsController extends Controller
{
    public function __construct(
        private readonly BotSettingsService $botSettingsService,
    ) {}

    public function show(Request $request, FacebookPageConnection $facebookPageConnection): Response
    {
        $user = $request->user();

        if (! $user || $facebookPageConnection->user_id !== $user->id) {
            abort(403);
        }

        return Inertia::render('BotSettings', [
            'connection' => [
                'id' => $facebookPageConnection->id,
                'page_id' => $facebookPageConnection->page_id,
                'page_name' => $facebookPageConnection->page_name,
                'status' => $facebookPageConnection->status,
                'updated_at' => $facebookPageConnection->updated_at?->toIso8601String(),
            ],
            'botSettings' => $this->botSettingsService->getSettings($facebookPageConnection),
            'facebookAccountName' => $request->session()->get('messenger.facebook_account.name', $user->name),
        ]);
    }

    public function update(Request $request, FacebookPageConnection $facebookPageConnection): RedirectResponse
    {
        $user = $request->user();

        if (! $user || $facebookPageConnection->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validate([
            'enabled' => ['required', 'boolean'],
            'reply_mode' => ['required', 'string', 'in:auto,smart,manual'],
            'default_reply' => ['required', 'string', 'max:1000'],
            'delay_seconds' => ['required', 'integer', 'in:0,5,10'],
            'welcome_message' => ['required', 'string', 'max:1000'],
        ]);

        $this->botSettingsService->updateSettings($facebookPageConnection, [
            'enabled' => (bool) $validated['enabled'],
            'reply_mode' => (string) $validated['reply_mode'],
            'default_reply' => (string) $validated['default_reply'],
            'delay_seconds' => (int) $validated['delay_seconds'],
            'welcome_message' => (string) $validated['welcome_message'],
        ]);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Bot settings updated successfully.',
        ]);

        return redirect()->route('dashboard.accounts.bot-settings.show', $facebookPageConnection);
    }
}

