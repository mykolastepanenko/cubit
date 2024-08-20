<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramBotServiceGuzzle
{
    /**
     * @var string
     */
    protected const BASE_URL_PATTERN = 'https://api.telegram.org/bot%s';

    /**
     * @var string
     */
    protected string $baseUrl;

    public function __construct()
    {
        $apiToken = config('app.telegram_bot.api_key');
        $this->baseUrl = sprintf(self::BASE_URL_PATTERN, $apiToken);
    }

    /**
     * @return array
     */
    public function getUpdates(): array
    {
        $url = $this->baseUrl . '/getUpdates';
        $response = Http::get($url);

        if (!$response->successful()) {
            return [];
        }

        return $response->json()['result'];
    }

    /**
     * @param array $data
     *
     * @return void
     */
    public function sendMessage(array $data): void
    {
        $url = $this->baseUrl . '/sendMessage';
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($url, $data);
    }
}
