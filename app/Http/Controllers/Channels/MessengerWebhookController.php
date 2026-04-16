<?php

namespace App\Http\Controllers\Channels;

use App\Models\FacebookPageConnection;
use App\Models\WhatsAppConnection;
use App\Services\Bots\BotSettingsService;
use App\Services\Facebook\FacebookService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class MessengerWebhookController extends Controller
{
    public function __construct(
        private readonly FacebookService $facebookService,
        private readonly BotSettingsService $botSettingsService,
    ) {}

    public function verify(Request $request): Response
    {
        $mode = (string) $request->query('hub_mode');
        $token = (string) $request->query('hub_verify_token');
        $challenge = (string) $request->query('hub_challenge');

        $validTokens = array_filter([
            (string) config('services.facebook.webhook_verify_token'),
            (string) config('services.whatsapp.webhook_verify_token'),
        ]);

        if ($mode === 'subscribe' && $token !== '' && collect($validTokens)->contains(fn (string $validToken) => hash_equals($validToken, $token))) {
            return response($challenge, 200);
        }

        return response('Forbidden', 403);
    }

    public function handle(Request $request): JsonResponse
    {
        logger()->info('Messenger webhook request received.', [
            'headers' => [
                'x_hub_signature_256' => $request->header('X-Hub-Signature-256'),
                'content_type' => $request->header('Content-Type'),
                'user_agent' => $request->header('User-Agent'),
            ],
            'payload' => $request->all(),
        ]);

        if (! $this->isValidSignature($request)) {
            logger()->warning('Messenger webhook signature validation failed.', [
                'header_signature' => $request->header('X-Hub-Signature-256'),
                'payload' => $request->all(),
            ]);

            return response()->json(['ok' => false, 'error' => 'invalid_signature'], 403);
        }

        $payload = $request->all();

        if (($payload['object'] ?? null) === 'whatsapp_business_account') {
            $this->handleWhatsAppWebhook($payload);

            return response()->json(['ok' => true]);
        }

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

                $isEcho = (bool) data_get($event, 'message.is_echo', false);
                if ($isEcho) {
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

                $settings = $this->botSettingsService->getSettings($connection);
                if (! $settings['enabled'] || $settings['reply_mode'] === 'manual') {
                    continue;
                }

                $replyText = trim((string) ($settings['welcome_message'] ?: $settings['default_reply']));
                if ($replyText === '') {
                    continue;
                }

                $delaySeconds = max(0, (int) ($settings['delay_seconds'] ?? 0));
                if ($delaySeconds > 0) {
                    sleep($delaySeconds);
                }

                try {
                    $this->facebookService->sendMessage(
                        (string) $connection->page_access_token,
                        $senderId,
                        $replyText,
                    );
                } catch (RequestException $e) {
                    report($e);

                    logger()->error('Messenger bot auto-reply failed.', [
                        'connection_id' => $connection->id,
                        'page_id' => $pageId,
                        'sender_id' => $senderId,
                        'reply_mode' => $settings['reply_mode'],
                        'response_body' => $e->response?->body(),
                    ]);
                }
            }
        }

        return response()->json(['ok' => true]);
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function handleWhatsAppWebhook(array $payload): void
    {
        foreach (($payload['entry'] ?? []) as $entry) {
            foreach ((array) data_get($entry, 'changes', []) as $change) {
                $phoneNumberId = (string) data_get($change, 'value.metadata.phone_number_id', '');
                $displayPhoneNumber = (string) data_get($change, 'value.metadata.display_phone_number', '');

                $connection = WhatsAppConnection::query()
                    ->where('phone_number_id', $phoneNumberId)
                    ->where('status', 'active')
                    ->first();

                logger()->info('WhatsApp webhook event received.', [
                    'phone_number_id' => $phoneNumberId,
                    'display_phone_number' => $displayPhoneNumber,
                    'connection_id' => $connection?->id,
                    'user_id' => $connection?->user_id,
                    'change' => $change,
                ]);
            }
        }
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
