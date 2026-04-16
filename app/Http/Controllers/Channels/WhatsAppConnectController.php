<?php

namespace App\Http\Controllers\Channels;

use App\Models\WhatsAppConnection;
use App\Services\WhatsApp\WhatsAppService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Inertia\Response;

class WhatsAppConnectController extends Controller
{
    public function __construct(
        private readonly WhatsAppService $whatsAppService,
    ) {}

    public function show(Request $request): Response
    {
        $user = $request->user();

        $connections = [];
        if ($user) {
            $connections = WhatsAppConnection::query()
                ->where('user_id', $user->id)
                ->orderByDesc('updated_at')
                ->get(['id', 'waba_id', 'phone_number_id', 'display_phone_number', 'verified_name', 'status', 'updated_at'])
                ->toArray();
        }

        return Inertia::render('ConnectWhatsApp', [
            'metaAppId' => config('services.whatsapp.app_id'),
            'embeddedSignupConfigId' => config('services.whatsapp.embedded_signup_config_id'),
            'connections' => $connections,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string'],
            'waba_id' => ['required', 'string'],
            'phone_number_id' => ['required', 'string'],
        ]);

        $user = $request->user();
        if (! $user) {
            return redirect()->route('login');
        }

        logger()->info('WhatsApp embedded signup completion requested.', [
            'user_id' => $user->id,
            'waba_id' => $validated['waba_id'],
            'phone_number_id' => $validated['phone_number_id'],
        ]);

        try {
            $tokenResponse = $this->whatsAppService->exchangeCodeForBusinessToken($validated['code']);
            $businessToken = (string) ($tokenResponse['access_token'] ?? '');

            if ($businessToken === '') {
                throw new \RuntimeException('Meta did not return a WhatsApp business token.');
            }

            $phoneNumber = $this->whatsAppService->getPhoneNumber($validated['phone_number_id'], $businessToken);
            $this->whatsAppService->subscribeWaba($validated['waba_id'], $businessToken);

            $connection = WhatsAppConnection::query()->updateOrCreate(
                [
                    'user_id' => $user->id,
                    'phone_number_id' => $validated['phone_number_id'],
                ],
                [
                    'waba_id' => $validated['waba_id'],
                    'display_phone_number' => (string) ($phoneNumber['display_phone_number'] ?? ''),
                    'verified_name' => (string) ($phoneNumber['verified_name'] ?? ''),
                    'access_token' => $businessToken,
                    'status' => 'active',
                    'bot_context' => [
                        'platform' => 'whatsapp',
                        'connected_at' => now()->toIso8601String(),
                    ],
                ],
            );

            logger()->info('WhatsApp connection saved.', [
                'connection_id' => $connection->id,
                'user_id' => $user->id,
                'waba_id' => $connection->waba_id,
                'phone_number_id' => $connection->phone_number_id,
            ]);
        } catch (RequestException|\RuntimeException $e) {
            logger()->error('WhatsApp embedded signup failed.', [
                'user_id' => $user->id,
                'waba_id' => $validated['waba_id'],
                'phone_number_id' => $validated['phone_number_id'],
                'message' => $e->getMessage(),
                'response_body' => $e instanceof RequestException ? $e->response?->body() : null,
            ]);

            report($e);

            Inertia::flash('toast', [
                'type' => 'error',
                'message' => 'Could not connect your WhatsApp number. Please verify your Meta setup and try again.',
            ]);

            return redirect()->route('channels.connect.whatsapp');
        }

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'WhatsApp number connected successfully.',
        ]);

        return redirect()->route('dashboard');
    }
}

