<?php

namespace App\Services\Alerts;

use App\Models\Integration;
use Illuminate\Support\Facades\Http;

class TelegramBotService
{
    public function __construct(protected ?string $token = null)
    {

        $token = config('services.telegram.bot_token');
        if (!$token) {
            $integration = Integration::where('type', 'telegram')->first();
            $token = $integration['config']['bot_token'] ?? null;
        }
        $this->token = $token;
    }

    public function setWebhook(string $url): void
    {
        Http::withoutVerifying()->post($this->endpoint('setWebhook'), [
            'url' => $url,
        ])->throw();
    }

    public function deleteWebhook(): void
    {
        Http::withoutVerifying()->post($this->endpoint('deleteWebhook'))->throw();
    }

    public function sendMessage(string $chatId, string $message): void
    {
        Http::withoutVerifying()->post($this->endpoint('sendMessage'), [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'MarkdownV2',
        ])->throw();
    }

    protected function endpoint(string $method): string
    {
        if (! $this->token) {
            throw new \RuntimeException('Telegram bot token not configured');
        }

        return sprintf('https://api.telegram.org/bot%s/%s', $this->token, $method);
    }
}
