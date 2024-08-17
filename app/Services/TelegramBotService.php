<?php

namespace App\Services;

use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request as TelegramRequest;
use Longman\TelegramBot\Telegram;

class TelegramBotService
{
    protected string $botApiKey;
    protected string $botUsername;
    protected array $botReceivers;
    protected Telegram $telegram;

    /**
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function __construct()
    {
        $this->botApiKey = config('app.telegram_bot.api_key');
        $this->botUsername = config('app.telegram_bot.username');
        $this->botReceivers = config('app.telegram_bot.receivers');

        // Create Telegram API object
        $this->telegram = new Telegram($this->botApiKey, $this->botUsername);
        $this->telegram->useGetUpdatesWithoutDatabase();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
//            $updates = $this->getUpdates();
//            dd($updates);

            $messageResults = $this->sendMessage($this->botReceivers, 'Test message 😜');
            dd($messageResults);
        } catch (TelegramException $e) {
            dd($e->getMessage());
        }
    }

    /**
     * @return \Longman\TelegramBot\Entities\ServerResponse
     *
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function getUpdates(): ServerResponse
    {
        $allowed_updates = [
            Update::TYPE_MESSAGE,
            Update::TYPE_CHANNEL_POST,
            Update::TYPE_INLINE_QUERY,
            Update::TYPE_CALLBACK_QUERY,
            // etc.
        ];
        $allowed_updates = '*';

        return $this->telegram->handleGetUpdates();
    }

    /**
     * @param array $data
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse[]
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function sendMessage(array $data): array
    {
        $originalData = $data['original'];
        $responses = [];
        foreach ($data['other']['receivers'] as $botReceiver) {
            $originalData['chat_id'] = $botReceiver;
            $responses[] = TelegramRequest::sendMessage($originalData);
        }

        return $responses;
    }

    public function confirmUsers()
    {
        $updates = $this->getUpdates();
        foreach ($updates->getResult() as $update) {
            $data = $update->raw_data;
            dd($data);
            dd($data['callback_query']['data']);
        }
    }
}
