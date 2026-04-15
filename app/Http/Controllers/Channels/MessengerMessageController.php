<?php

namespace App\Http\Controllers\Channels;

use App\Models\FacebookPageConnection;
use App\Services\Facebook\FacebookService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MessengerMessageController extends Controller
{
    public function __construct(
        private readonly FacebookService $facebookService,
    ) {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'facebook_page_connection_id' => ['required', 'integer'],
            'recipient_id' => ['required', 'string'],
            'text' => ['required', 'string', 'max:1000'],
        ]);

        $user = $request->user();
        if (! $user) {
            return response()->json(['ok' => false, 'message' => 'Unauthorized.'], 401);
        }

        $connection = FacebookPageConnection::query()
            ->where('id', $validated['facebook_page_connection_id'])
            ->where('user_id', $user->id)
            ->first();

        if (! $connection) {
            return response()->json(['ok' => false, 'message' => 'Page connection not found.'], 404);
        }

        try {
            $graphResponse = $this->facebookService->sendMessage(
                $connection->page_access_token,
                $validated['recipient_id'],
                $validated['text'],
            );
        } catch (RequestException $e) {
            report($e);

            return response()->json([
                'ok' => false,
                'message' => 'Failed to send message to Facebook Messenger.',
                'graph_error' => $e->response?->json(),
            ], 422);
        }

        return response()->json([
            'ok' => true,
            'message' => 'Message sent successfully.',
            'data' => $graphResponse,
        ]);
    }
}
