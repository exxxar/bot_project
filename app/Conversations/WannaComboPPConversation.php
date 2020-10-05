<?php


namespace App\Conversations;


use App\Ticket;
use Illuminate\Support\Facades\Log;

class WannaComboPPConversation extends Conversation
{

    public static function start($bot)
    {

        $user = $bot->getUser();

        $needName = mb_strlen($user->user_profile->full_name) === 0;

        $bot->getFallbackMenu("Хочу записаться на фитнес.\n\xF0\x9F\x94\xB8"
            . ($needName ? "Введите ваше имя:" : "Введите ваш возраст:")
        );

        if ($needName)
            $bot->startConversation("wcpp_name");
        else {
            $bot->startConversation("wcpp_name", [
                "name" => $user->user_profile->full_name ??
                    $user->fio ??
                    $user->account_name ??
                    $user->chat_id
            ]);
        }
    }

    public static function name($bot, $message)
    {

        $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\n\xF0\x9F\x94\xB8Введите ваш возраст:");
        $bot->next("wcpp_age", [
            "name" => $message
        ]);
    }

    public static function age($bot, $message)
    {

        if (intval($message)<8 || intval($message)>50){
            $bot->next("wcpp_age");
            $bot->reply("Возраст не совсем подходящий...");
            return;
        }
        $bot->getFallbackMenuWithPhone("Вы ввели: *$message* лет\xE2\x9C\x85\n\n\xF0\x9F\x94\xB8Введите ваш номер телефона:");
        $bot->next("wcpp_phone", [
            "age" => $message
        ]);
    }

    public static function phone($bot, $message)
    {
        $pattern = "/^\+380\d{3}\d{2}\d{2}\d{2}$/";
        $tmp_phone = str_replace(["(", ")", "-", " "], "", $message);
        $tmp_phone = strpos($tmp_phone, "+38") === false ?
            "+38$tmp_phone" : $tmp_phone;

        if (preg_match($pattern, $tmp_phone) == 0) {
            $bot->reply("Вы неверно ввели телефонный номер! Попробуйте еще раз.");
            $bot->next("wcpp_phone");
            return;
        }

        $bot->getFallbackMenu("Ваш номер телефона: *$tmp_phone*\xE2\x9C\x85\n\n\xF0\x9F\x94\xB8Дополнительно к заявке:");

        $bot->next("wcpp_text", [
            "phone" => $tmp_phone
        ]);
    }

    public static function text($bot, $message)
    {
        $phone = $bot->storeGet("phone");
        $name = $bot->storeGet("name");
        $age = $bot->storeGet("age");
        $text = $message;

        $bot->getMainMenu("Вы ввели: *$message*\xE2\x9C\x85\nСпасибо! Ваш запрос принят в обработку.");
        $bot->stopConversation();


        $bot->sendMessageToChat(
            env("LD_ADMIN_CHANNEL_ID"),
            "*Новый запрос на фитнес*:\nОт:_ $name _\nВозраст:_ $age _\nТелефон:_ $phone _\nКомменатрий: _ $text _\n"
        );


    }


}
