<?php

namespace App\Listeners;

use App\Services\TelegramBotService;
use App\Services\TelegramBotServiceGuzzle;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Longman\TelegramBot\Exception\TelegramException;

class SendUserToTelegram /*implements ShouldQueue*/
{
    protected TelegramBotServiceGuzzle $telegramBotService;

    /**
     * Create the event listener.
     */
    public function __construct(TelegramBotServiceGuzzle $telegramBotService)
    {
        $this->telegramBotService = $telegramBotService;
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $user = $event->user;
        $receivers = config('app.telegram_bot.receivers');

        $message = 'Помилка: користувач намагався зареєструватися, але щось пішло не так. Перевірте логи серверу.';
        if ($user !== null) {
            $message = "Зареєструвався новий користувач 😊";
            $message .= PHP_EOL . $user->phone;
            $message .= PHP_EOL . "Дата реєстрації: {$user->created_at}";
        }

        try {
            foreach ($receivers as $receiver) {
                $this->telegramBotService->sendMessage([
                    'chat_id' => $receiver,
                    'text' => $message,
                    'reply_markup' => $this->getInlineKeyboard($user->phone),
                ]);
            }
        } catch (TelegramException $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * @param string $phone
     *
     * @return array[]
     */
    protected function getInlineKeyboard(string $phone): array
    {
        return [
            'inline_keyboard' => [
                [
                    [
                        "text" => "Підтвердити",
                        "callback_data" => json_encode([
                            'action' => 'confirm',
                            'value' => $phone,
                        ]),
                    ],
                ]
            ],
        ];
    }
}
