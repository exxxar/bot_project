<?php


namespace App\Conversations;


use App\Enums\ProductTypeEnum;
use App\Order;
use App\Product;
use App\Profile;
use Illuminate\Support\Facades\Log;
use Laravel\BotCashBack\Models\BotUserInfo;

class StartDataConversation
{

    public static function start($bot, ...$d)
    {
        $bot->stopConversation();
        $data = isset($d[1]) ? $d[1] : null;

        $pattern = "/([0-9]{3})([0-9]{10})/";
        $string = base64_decode($data);

        $is_valid = preg_match_all($pattern, $string, $matches);

        if (!$is_valid) {
            $bot->mainMenu("Добро пожаловать в модельное агенство Lotus!");
            return;
        }

        $code = $matches[1][0];
        $request_user_id = $matches[2][0];

        $user = $bot->getUser();

        if (!$user->is_admin) {
            $bot->getMainMenu("Добро пожаловать в модельное агенство Lotus!");
            return;
        }
        $bot->getFallbackMenu("Добро пожаловать в админ. панель");

        if ($code === "001") {
            $bot->startConversation("admin_action_handler");
            StartDataConversation::askForAction($bot,$request_user_id);
        }
    }

    public static function askForAction($bot,$user_id)
    {

        $requestUser = BotUserInfo::with(["user"])->where("user_id", $user_id)->first();

        $ps_discount = $requestUser->user->ps_discount ?? 0;
        $ps_photo_count = $requestUser->user->ps_photo_count ?? 0;

        $keyboard = [
            [
                ["text" => "\xF0\x9F\x92\xA1Назначить администратора", "callback_data" => "action_admin_up"],

            ],
            [
                ["text" => "\xF0\x9F\x92\xA1Убрать администратора", "callback_data" => "action_admin_down"],
            ],
            [
                ["text" => "\xF0\x9F\x92\xA1Изменить скидку пользователя", "callback_data" => "set_sale"]
            ],
            [
                ["text" => "\xF0\x9F\x92\xA1Изменить кол-во фотосъемок", "callback_data" => "set_count_photo"]
            ],
            [
                ["text" => "\xF0\x9F\x92\xA1Завершить работу администратора", "callback_data" => "end"]
            ],
        ];

        $bot->sendMessage("Запрос действия администратора:", $keyboard);


        $bot->next("admin_action_handler",[
            "request_user_id" => $user_id,
            "ps_discount"=>$ps_discount,
            "ps_photo_count"=>$ps_photo_count,

        ]);
    }

    public static function actionHandler($bot, $message)
    {
        if (StartDataConversation::fallback($bot, $message))
            return;

        $ps_discount = $bot->storeGet("ps_discount");
        $ps_photo_count = $bot->storeGet("ps_photo_count");

        $bot->deleteMessage();

        switch ($message) {
            case "action_admin_up":
                StartDataConversation::adminUp($bot,$message);
                break;
            case "action_admin_down":
                StartDataConversation::adminDown($bot,$message);
                break;
            case "set_sale":
                $bot->reply("Текущая скикдка *$ps_discount%*\nВведие величину скидки(числом, например, 20):\n");
                $bot->next("admin_action_set_sale");
                break;
            case "set_count_photo":
                $bot->reply("Текущее число фотосъемок *$ps_photo_count* ед.\nВведие число фотосъемок(числом, например, 2):\n");
                $bot->next("admin_action_set_count_photo");
                break;
            case "end":
                $bot->sendMainMenu("Спасибо, что воспользовались админ. панелью!");
                $bot->stopConversation();
                break;
        }
    }

    public static function adminUp($bot,$message)
    {
        if (StartDataConversation::fallback($bot, $message))
            return;
        $user = $bot->getUser();

        if (!$user->is_admin) {
            $bot->stopConversation();
            $bot->getMainMenu("Вы не являетесь администратором!");
            return;
        }

        $request_user_id = $bot->storeGet("request_user_id");

        $requestUser = BotUserInfo::where("user_id", $request_user_id)->first();

        if (!is_null($requestUser)) {
            $bot->reply("Пользователь не найден!");
            $bot->next("admin_ask_for_action");
            return;
        }

        $requestUser->is_admin = true;
        $requestUser->save();

        $bot->sendMessageToChat($requestUser->chat_id, "Вы назначены администратором!");
        $bot->reply("Пользотваель #$requestUser->user_id назначен администратором!");
        $bot->next("admin_ask_for_action");
    }

    public static function adminDown($bot, $message)
    {
        if (StartDataConversation::fallback($bot, $message))
            return;

        $user = $bot->getUser();

        if (!$user->is_admin) {
            $bot->stopConversation();
            $bot->getMainMenu("Вы не являетесь администратором!");
            return;
        }

        $request_user_id = $bot->storeGet("request_user_id");

        $requestUser = BotUserInfo::where("user_id", $request_user_id)->first();

        if (!is_null($requestUser)) {
            $bot->reply("Пользователь не найден!");
            $bot->next("admin_ask_for_action");
            return;
        }

        $requestUser->is_admin = false;
        $requestUser->save();

        $bot->sendMessageToChat($requestUser->chat_id, "Вы разжалованы из администраторов!");
        $bot->reply("Пользотваель #$requestUser->user_id разжалован из администраторов!");
        $bot->next("admin_ask_for_action");
    }

    public static function setSale($bot, $message)
    {

        if (StartDataConversation::fallback($bot, $message))
            return;

        $user = $bot->getUser();

        if (!$user->is_admin) {
            $bot->stopConversation();
            $bot->getMainMenu("Вы не являетесь администратором!");
            return;
        }

        $request_user_id = $bot->storeGet("request_user_id");

        $requestUser = BotUserInfo::with(["user"])->where("user_id", $request_user_id)->first();

        if (!is_null($requestUser)) {
            $bot->reply("Пользователь не найден!");
            $bot->next("admin_ask_for_action");
            return;
        }

        $requestUser->user->ps_discount = intval($message) ?? 0;
        $requestUser->save();

        $bot->sendMessageToChat($requestUser->chat_id, "Вам установлена скидка " . $requestUser->user->ps_discount . "%");
        $bot->reply("Пользотваелю #$requestUser->user_id назначена скидка " . $requestUser->user->ps_discount . "%!");
        $bot->next("admin_ask_for_action");
    }

    public static function setCountPhoto($bot, $message)
    {

        if (StartDataConversation::fallback($bot, $message))
            return;

        $user = $bot->getUser();

        if (!$user->is_admin) {
            $bot->stopConversation();
            $bot->getMainMenu("Вы не являетесь администратором!");
            return;
        }

        $request_user_id = $bot->storeGet("request_user_id");

        $requestUser = BotUserInfo::with(["user"])->where("user_id", $request_user_id)->first();

        if (!is_null($requestUser)) {
            $bot->reply("Пользователь не найден!");
            $bot->next("admin_ask_for_action");
            return;
        }

        $requestUser->user->ps_photo_count = intval($message) ?? 0;
        $requestUser->save();

        $bot->sendMessageToChat($requestUser->chat_id, "Вам установлено " . $requestUser->user->ps_discount . " пройденных фотосессий!");
        $bot->reply("Пользотваелю #$requestUser->user_id установлено " . $requestUser->user->ps_discount . " фотосессий!");
        $bot->next("admin_ask_for_action");
    }

    public static function fallback($bot, $message)
    {

        if ($message === "Продолжить позже") {
            $bot->getMainMenu("Хорошо! Продолжим позже!");

            $bot->stopConversation();
            return true;
        } else
            return false;
    }
}
