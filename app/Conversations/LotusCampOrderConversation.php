<?php


namespace App\Conversations;


use App\Order;
use App\PhotoProject;
use App\Ticket;
use Illuminate\Support\Facades\Log;

class LotusCampOrderConversation
{

    public static function start($bot)
    {
        $bot->getFallbackMenu("Диалог оформления заявки Lotus Camp");
        $keyboard = [
            [
                ["text" => "10 рабочих дней", "callback_data" => "10 дней"]
            ],
            [
                ["text" => "5 рабочих дней", "callback_data" => "5 дней"]
            ],
            [
                ["text" => "По одному дню", "callback_data" => "1 день"]
            ]
        ];
        $bot->sendMessage("Выберите вариант посещения:", $keyboard);
        $bot->startConversation("lotus_camp_order_type");
    }

    public static function type($bot, $message)
    {
        if (LotusCampOrderConversation::fallback($bot, $message))
            return;

        $bot->reply("Вы выбрали: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Введите ваше имя:");
        $bot->next("lotus_camp_order_name", [
            "type" => $message
        ]);
    }

    public static function name($bot, $message)
    {
        if (LotusCampOrderConversation::fallback($bot, $message))
            return;

        if (mb_strlen($message) === 0) {
            $bot->reply("Нужно ввести Ваше имя!");
            $bot->next("lotus_camp_order_name");
            return;
        }
        $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Введите ваш возраст:");
        $bot->next("lotus_camp_order_age", [
            "name" => $message
        ]);
    }

    public static function age($bot, $message)
    {
        if (LotusCampOrderConversation::fallback($bot, $message))
            return;

        $bot->reply("Вы ввели: *$message* лет\xE2\x9C\x85\n\xF0\x9F\x94\xB8Введите ваш номер телефона:");
        $bot->next("lotus_camp_order_phone", [
            "age" => $message
        ]);
    }


    public static function phone($bot, $message)
    {
        if (LotusCampOrderConversation::fallback($bot, $message))
            return;

        $pattern = "/^\+380\d{3}\d{2}\d{2}\d{2}$/";
        $tmp_phone = str_replace(["(", ")", "-", " "], "", $message);
        $tmp_phone = strpos($tmp_phone, "+38") === false ?
            "+38$tmp_phone" : $tmp_phone;

        if (preg_match($pattern, $tmp_phone) == 0) {
            $bot->reply("Вы неверно ввели телефонный номер! Попробуйте еще раз.");
            $bot->next("lotus_camp_order_phone");
            return;
        }

        $bot->reply("Ваш номер телефона: *$tmp_phone*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Введите ваш комментарий к заказу:");

        $bot->next("lotus_camp_order_comment", [
            "phone" => $tmp_phone
        ]);
    }

    public static function comment($bot, $message)
    {
        if (LotusCampOrderConversation::fallback($bot, $message))
            return;

        if (mb_strlen($message) === 0) {
            $bot->reply("Нужно ввести текст Вашего комментария!");
            $bot->next("photo_project_comment");
            return;
        }

        $user = $bot->getUser();

        $name = $bot->storeGet("name");
        $phone = $bot->storeGet("phone");
        $type = $bot->storeGet("type");
        $age = $bot->storeGet("age");

        $bot->stopConversation();

        $tmp = "Заявка на Lotus Camp:\nТип: *$type*\nОт: *$name*\nВозраст: *$age* лет\nТелефон: _ $phone _\nКомментарий: _ $message _\n";

        $bot->sendMessageToChat(
            env("LOTUS_BASE_CHANEL_ID"),
            "$tmp"
        );

        $bot->getMainMenu("Текст вашего комментария: *$message*\xE2\x9C\x85\nСпасибо! Ваша заявка принята в обработку.");

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