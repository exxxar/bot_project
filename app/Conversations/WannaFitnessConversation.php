<?php


namespace App\Conversations;


use App\Ticket;
use Illuminate\Support\Facades\Log;

class WannaFitnessConversation
{

    public static function start($bot)
    {
        $bot->getFallbackMenu("Хочу записаться на фитнес.\n\xF0\x9F\x94\xB8Введите ваше имя:");
        $bot->startConversation("wf_name");
    }

    public static function name($bot, $message)
    {
        if (WannaFitnessConversation::fallback($bot, $message))
            return;


        $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Введите ваш возраст:");
        $bot->next("wf_age", [
            "name" => $message
        ]);
    }

    public static function age($bot, $message)
    {
        if (WannaFitnessConversation::fallback($bot, $message))
            return;

        if (!is_numeric($message)){
            $bot->reply("Ошибочка! Вы ведите свой возраст цифрами!:");
            $bot->next("wf_age");
            return;
        }

        $bot->reply("Вы ввели: *$message* лет\xE2\x9C\x85\n\xF0\x9F\x94\xB8Введите ваш номер телефона:");
        $bot->next("wf_phone", [
            "age" => $message
        ]);
    }

    public static function phone($bot, $message)
    {
        if (WannaFitnessConversation::fallback($bot, $message))
            return;

        $pattern = "/^\+380\d{3}\d{2}\d{2}\d{2}$/";
        $tmp_phone = str_replace(["(", ")", "-", " "], "", $message);
        $tmp_phone = strpos($tmp_phone, "+38") === false ?
            "+38$tmp_phone" : $tmp_phone;

        if (preg_match($pattern, $tmp_phone) == 0) {
            $bot->reply("Вы неверно ввели телефонный номер! Попробуйте еще раз.");
            $bot->next("wf_name");
            return;
        }

        $bot->reply("Ваш номер телефона: *$tmp_phone*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Дополнительно к заявке:");

        $bot->next("wf_text", [
            "phone" => $tmp_phone
        ]);
    }

    public static function text($bot, $message)
    {
        if (WannaFitnessConversation::fallback($bot, $message))
            return;

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
