<?php

namespace App\Services\Facebook;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class FacebookService
{
    /**
     * @return array<int, array{id: string, name: string, access_token: string}>
     *
     * @throws RequestException
     */
    public function getPagesForUserToken(string $userAccessToken): array
    {
        $response = Http::acceptJson()
            ->timeout(20)
            ->get('https://graph.facebook.com/me/accounts', [
                'access_token' => $userAccessToken,
            ])
            ->throw()
            ->json();

        return collect($response['data'] ?? [])
            ->map(fn (array $page): array => [
                'id' => (string) ($page['id'] ?? ''),
                'name' => (string) ($page['name'] ?? ''),
                'access_token' => (string) ($page['access_token'] ?? ''),
            ])
            ->filter(fn (array $page): bool => $page['id'] !== '' && $page['name'] !== '' && $page['access_token'] !== '')
            ->values()
            ->all();
    }

    /**
     * @throws RequestException
     */
    public function subscribePage(string $pageId, string $pageAccessToken): array
    {
        return Http::acceptJson()
            ->timeout(20)
            ->asForm()
            ->post("https://graph.facebook.com/{$pageId}/subscribed_apps", [
                'access_token' => $pageAccessToken,
                'subscribed_fields' => 'messages,messaging_postbacks',
            ])
            ->throw()
            ->json();
    }

    /**
     * @throws RequestException
     */
    public function sendMessage(string $pageAccessToken, string $recipientId, string $text): array
    {
        $version = (string) config('services.facebook.graph_version', 'v19.0');

        return Http::acceptJson()
            ->timeout(20)
            ->post("https://graph.facebook.com/{$version}/me/messages", [
                'access_token' => $pageAccessToken,
                'recipient' => [
                    'id' => $recipientId,
                ],
                'message' => [
                    'text' => $text,
                ],
            ])
            ->throw()
            ->json();
    }
}
