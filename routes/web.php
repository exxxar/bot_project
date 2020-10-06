<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Api;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Objects\Animation;
use Telegram\Bot\Objects\Audio;
use Telegram\Bot\Objects\CallbackQuery;
use Telegram\Bot\Objects\Chat;
use Telegram\Bot\Objects\ChosenInlineResult;
use Telegram\Bot\Objects\Contact;
use Telegram\Bot\Objects\Document;
use Telegram\Bot\Objects\EditedMessage;
use Telegram\Bot\Objects\Game;
use Telegram\Bot\Objects\InlineQuery;
use Telegram\Bot\Objects\Location;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\MessageEntity;
use Telegram\Bot\Objects\Passport\PassportData;
use Telegram\Bot\Objects\Payments\Invoice;
use Telegram\Bot\Objects\Payments\PreCheckoutQuery;
use Telegram\Bot\Objects\Payments\ShippingQuery;
use Telegram\Bot\Objects\Payments\SuccessfulPayment;
use Telegram\Bot\Objects\PhotoSize;
use Telegram\Bot\Objects\Poll;
use Telegram\Bot\Objects\Sticker;
use Telegram\Bot\Objects\User;
use Telegram\Bot\Objects\Venue;
use Telegram\Bot\Objects\Video;
use Telegram\Bot\Objects\VideoNote;
use Telegram\Bot\Objects\Voice;

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


Route::get("/b",function (){

    Log::info("ТЕСТОВЫЙ МЕТОД");
    $result = Http::post("http://localhost:8000/bot/lotus", [
        'message'              => [
            'from'               => [
                "id"=>"484698703",
                "first_name"=>"v1",
                "last_name"=>"v2",
                "username"=>"v21",
                "is_bot"=>false,
                "language_code"=>"ru",
            ],
            'chat'               => [
                "id"=>"484698703",
                "first_name"=>"v1",
                "last_name"=>"v2",
                "username"=>"v21",
                "type"=>"private",
            ],
            'date'               => Carbon\Carbon::now()->timestamp,
            'text'               => "/start",

        ],

    ]);

   dd(print_r($result,true));
});
