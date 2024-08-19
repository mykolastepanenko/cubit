<?php

use App\Events\UserRegistered;
use App\Http\Requests\RegisterRequest;
use App\Models\TelegramBotUpdate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', function(RegisterRequest $request) {
    $user = User::create(['phone' => $request->phone]);
    event(new UserRegistered($user));

    return response()->json(['status' => 'ok', 'message' => 'Ви успішно залишили заявку!']);
});

Route::post('/webhook/telegram', function (Request $request) {
    \Illuminate\Support\Facades\Log::info(json_encode($request->all()));

    $updates = [$request->all()];

    $updates = $this->telegramBotService->getUpdates();
    if (count($updates) === 0) {
        return;
    }

    $updateRecord = TelegramBotUpdate::find(1);

    $lastUpdate = TelegramBotUpdate::query()->latest()->first();
    $hasNewUpdates = true;
    $updateIndex = 0;
    foreach ($updates as $index => $update) {
        if (!property_exists($update, 'callback_query')) {
            continue;
        }

        if ($update->update_id === $lastUpdate->last_update_id) {
            if ($index === count($updates) - 1) {
                $hasNewUpdates = false;
                dump('it is old update. fake info');
                break;
            }
            dump("index={$index}");
            $updateIndex = $index + 1;
            dump("has old items!, index={$updateIndex}", "update_id,", $update->update_id);
            break;
        }
    }

    if (!$hasNewUpdates) {
        dump("No new updates.");
        return;
    } else {
        dump('all new iters');
    }

    dump("index {$updateIndex}");

    for ($i = $updateIndex; $i < count($updates); $i++) {
        $update = $updates[$i];
        if (!property_exists($update, 'callback_query')) {
            continue;
        }

        $dataJson = $update->callback_query->data;
        $data = json_decode($dataJson);

        if ($data->action === 'confirm') {
            $user = User::wherePhone($data->value)->first();

            if ((bool)$user->confirmed === false) {
                $user->confirmed = true;
                $user->save();

                $this->telegramBotService->sendMessage([
                    'chat_id' => $update->callback_query->from->id,
                    'text' => "Користувача успішно підтверджено!\n{$data->value}",
                ]);
            } else {
                $this->telegramBotService->sendMessage([
                    'chat_id' => $update->callback_query->from->id,
                    'text' => "Користувача вже підтвердили раніше!\n{$data->value}\nКористувача підтвердив @{$update->callback_query->from->username}",
                ]);
            }
        }

        $updateRecord->last_update_id = $update->update_id;
        $updateRecord->save();
    }

    return response()->json(true);
});
