<?php

use Illuminate\Support\Facades\Route;
use Telegram\Bot\Api;
use Telegram\Bot\Laravel\Facades\Telegram;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', 'BotController@test');
Route::any('/bot', 'BotController@handle');
Route::any('/bot/{bot}', 'BotController@handle');


Route::get("/setw", function () {

    $bots = \App\Bot::all();

    foreach ($bots as $bot) {
        $telegram = new Api(env("APP_DEBUG") ? $bot->token_dev : $bot->token_prod);
        $telegram->setWebhook(['url' => env("APP_URL") . '/bot/' . $bot->bot_url]);
        sleep(3);
    }

    return "Success";
});
