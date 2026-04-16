<?php

namespace App\Services\Bots;

use App\Models\FacebookPageConnection;

class BotSettingsService
{
    /**
     * @return array{
     *     enabled: bool,
     *     reply_mode: string,
     *     default_reply: string,
     *     delay_seconds: int,
     *     welcome_message: string
     * }
     */
    public function getSettings(FacebookPageConnection $connection): array
    {
        $context = is_array($connection->bot_context) ? $connection->bot_context : [];
        $storedSettings = is_array($context['settings'] ?? null) ? $context['settings'] : [];

        return array_merge($this->defaults(), $storedSettings);
    }

    /**
     * @param array{
     *     enabled: bool,
     *     reply_mode: string,
     *     default_reply: string,
     *     delay_seconds: int,
     *     welcome_message: string
     * } $settings
     */
    public function updateSettings(FacebookPageConnection $connection, array $settings): void
    {
        $context = is_array($connection->bot_context) ? $connection->bot_context : [];
        $context['settings'] = array_merge($this->defaults(), $settings);

        $connection->forceFill([
            'bot_context' => $context,
        ])->save();
    }

    /**
     * @return array{
     *     enabled: bool,
     *     reply_mode: string,
     *     default_reply: string,
     *     delay_seconds: int,
     *     welcome_message: string
     * }
     */
    public function defaults(): array
    {
        return [
            'enabled' => true,
            'reply_mode' => 'auto',
            'default_reply' => 'Thank you for contacting us. We will get back to you shortly.',
            'delay_seconds' => 0,
            'welcome_message' => 'Welcome to our page. How can we help you today?',
        ];
    }
}

