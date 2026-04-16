<?php

namespace App\Services\WhatsApp;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    /**
     * @return array<string, mixed>
     *
     * @throws RequestException
     */
    public function exchangeCodeForBusinessToken(string $code): array
    {
        $version = (string) config('services.whatsapp.graph_version', 'v21.0');

        return Http::acceptJson()
            ->timeout(20)
            ->get("https://graph.facebook.com/{$version}/oauth/access_token", [
                'client_id' => config('services.whatsapp.app_id'),
                'client_secret' => config('services.whatsapp.app_secret'),
                'code' => $code,
            ])
            ->throw()
            ->json();
    }

    /**
     * @return array<string, mixed>
     *
     * @throws RequestException
     */
    public function subscribeWaba(string $wabaId, string $businessToken): array
    {
        $version = (string) config('services.whatsapp.graph_version', 'v21.0');

        return Http::acceptJson()
            ->timeout(20)
            ->asForm()
            ->post("https://graph.facebook.com/{$version}/{$wabaId}/subscribed_apps", [
                'access_token' => $businessToken,
            ])
            ->throw()
            ->json();
    }

    /**
     * @return array<string, mixed>
     *
     * @throws RequestException
     */
    public function getPhoneNumber(string $phoneNumberId, string $businessToken): array
    {
        $version = (string) config('services.whatsapp.graph_version', 'v21.0');

        return Http::acceptJson()
            ->timeout(20)
            ->get("https://graph.facebook.com/{$version}/{$phoneNumberId}", [
                'access_token' => $businessToken,
                'fields' => 'display_phone_number,verified_name,quality_rating,code_verification_status',
            ])
            ->throw()
            ->json();
    }

    /**
     * @return array<string, mixed>
     *
     * @throws RequestException
     */
    public function sendTextMessage(string $phoneNumberId, string $businessToken, string $to, string $text): array
    {
        $version = (string) config('services.whatsapp.graph_version', 'v21.0');

        return Http::acceptJson()
            ->timeout(20)
            ->withToken($businessToken)
            ->post("https://graph.facebook.com/{$version}/{$phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $to,
                'type' => 'text',
                'text' => [
                    'body' => $text,
                ],
            ])
            ->throw()
            ->json();
    }
}

