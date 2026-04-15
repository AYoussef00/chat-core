<?php

namespace App\Http\Controllers\Channels;

use App\Models\FacebookPageConnection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class MessengerWebhookController extends Controller
{
    public function verify(Request $request): Response
    {
        $mode = (string) $request->query('hub_mode');
        $token = (string) $request->query('hub_verify_token');
        $challenge = (string) $request->query('hub_challenge');

        if ($mode === 'subscribe' && $token !== '' && hash_equals((string) config('services.facebook.webhook_verify_token'), $token)) {
            return response($challenge, 200);
        }

        return response('Forbidden', 403);
    }

    public function handle(Request $request): JsonResponse
    {
        if (! $this->isValidSignature($request)) {
            return response()->json(['ok' => false, 'error' => 'invalid_signature'], 403);
        }

        $payload = $request->all();

        if (($payload['object'] ?? null) !== 'page') {
            return response()->json(['ok' => true]);
        }

        foreach (($payload['entry'] ?? []) as $entry) {
            foreach (($entry['messaging'] ?? []) as $event) {
                $pageId = (string) data_get($event, 'recipient.id', '');
                $senderId = (string) data_get($event, 'sender.id', '');
                $messageText = (string) data_get($event, 'message.text', '');

                if ($pageId === '' || $senderId === '') {
                    continue;
                }

                $connection = FacebookPageConnection::query()
                    ->where('page_id', $pageId)
                    ->where('status', 'active')
                    ->first();

                if (! $connection) {
                    continue;
                }

                logger()->info('Messenger webhook event received.', [
                    'connection_id' => $connection->id,
                    'user_id' => $connection->user_id,
                    'page_id' => $pageId,
                    'sender_id' => $senderId,
                    'message_text' => $messageText,
                    'event' => $event,
                ]);
            }
        }

        return response()->json(['ok' => true]);
    }

    private function isValidSignature(Request $request): bool
    {
        $appSecret = (string) config('services.facebook.client_secret');
        if ($appSecret === '') {
            return false;
        }

        $signatureHeader = (string) $request->header('X-Hub-Signature-256', '');
        if (! str_starts_with($signatureHeader, 'sha256=')) {
            return false;
        }

        $signature = substr($signatureHeader, 7);
        $expected = hash_hmac('sha256', (string) $request->getContent(), $appSecret);

        return hash_equals($expected, $signature);
    }
}
