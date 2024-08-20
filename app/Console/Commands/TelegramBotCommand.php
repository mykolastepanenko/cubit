<?php

namespace App\Console\Commands;

use App\Models\TelegramBotUpdate;
use App\Models\User;
use App\Services\TelegramBotService;
use App\Services\TelegramBotServiceGuzzle;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request as TelegramRequest;
use Longman\TelegramBot\Telegram;

class TelegramBotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tg-bot:getUpdates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Telegram bot get updates (new messages)';

    protected TelegramBotServiceGuzzle $telegramBotService;

    /**
     * @param \App\Services\TelegramBotServiceGuzzle $telegramBotService
     */
    public function __construct(TelegramBotServiceGuzzle $telegramBotService)
    {
        parent::__construct();
        $this->telegramBotService = $telegramBotService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $updates = $this->telegramBotService->getUpdates();
        if (count($updates) === 0) {
            return;
        }

        $updateRecord = TelegramBotUpdate::find(1);

        $lastUpdate = TelegramBotUpdate::query()->latest()->first();
        $hasNewUpdates = true;
        $updateIndex = 0;
        foreach ($updates as $index => $update) {
            if (!array_key_exists('callback_query', $update)) {
                continue;
            }

            if ($update['update_id'] === $lastUpdate->last_update_id) {
                if ($index === count($updates) - 1) {
                    $hasNewUpdates = false;
                    break;
                }
                $updateIndex = $index + 1;
                break;
            }
        }

        if (!$hasNewUpdates) {
            return;
        }

        for ($i = $updateIndex; $i < count($updates); $i++) {
            $update = $updates[$i];
            if (!array_key_exists('callback_query', $update)) {
                continue;
            }

            $dataJson = $update['callback_query']['data'];
            $data = json_decode($dataJson);

            if ($data->action === 'confirm') {
                $user = User::wherePhone($data->value)->first();

                if ((bool)$user->confirmed === false) {
                    $user->confirmed = true;
                    $user->confirmed_by = '@' . $update['callback_query']['from']['username'];
                    $user->save();

                    $this->telegramBotService->sendMessage([
                        'chat_id' => $update['callback_query']['from']['id'],
                        'text' => "Користувача успішно підтверджено!\n{$data->value}",
                    ]);
                } else {
                    $this->telegramBotService->sendMessage([
                        'chat_id' => $update['callback_query']['from']['id'],
                        'text' => "Користувача вже підтвердили раніше!\n{$data->value}\nКористувача підтвердив {$user->confirmed_by}",
                    ]);
                }
            }

            $updateRecord->last_update_id = $update['update_id'];
            $updateRecord->save();
        }
    }
}
