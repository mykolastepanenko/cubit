<?php

use App\Events\UserRegistered;
use App\Http\Controllers\TelegramWebhookController;
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

Route::post('/webhook/telegram', [TelegramWebhookController::class, 'webhookAction']);
