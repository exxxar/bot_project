<?php


namespace App\Conversations;


use App\Order;
use App\PhotoProject;
use App\Ticket;
use Illuminate\Support\Facades\Log;

class LotusCampOrderConversation extends Conversation
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

        $bot->reply("Вы выбрали: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Введите Ф.И.О. ребенка:");
        $bot->next("lotus_camp_order_child_name", [
            "type" => $message
        ]);
    }

    public static function childName($bot, $message)
    {
        $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Введите возраст ребенка:");
        $bot->next("lotus_camp_order_age", [
            "child_name" => $message
        ]);
    }

    public static function age($bot, $message)
    {

        if (intval($message)<8||intval($message)>=18){
            $bot->reply("Ваш ребенок не подходит по возрасту! Lotus Camp для детей от 8 до 17 лет.");
            $bot->next("lotus_camp_order_age");
            return;
        }

        $bot->reply("Вы ввели: *$message* лет\xE2\x9C\x85\n\xF0\x9F\x94\xB8Введите Ф.И.О. родителя:");
        $bot->next("lotus_camp_order_parent_name", [
            "age" => $message
        ]);
    }

    public static function parentName($bot, $message)
    {
        $bot->getFallbackMenuWithPhone("Вы ввели: *$message* лет\xE2\x9C\x85\n\xF0\x9F\x94\xB8Введите ваш номер телефона:");
        $bot->next("lotus_camp_order_phone", [
            "parent_name" => $message
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
            $bot->next("lotus_camp_order_phone");
            return;
        }

        $bot->getFallbackMenu("Ваш номер телефона: *$tmp_phone*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Введите ваш комментарий к заказу:");

        $bot->next("lotus_camp_order_comment", [
            "phone" => $tmp_phone
        ]);
    }

    public static function comment($bot, $message)
    {

        $childName = $bot->storeGet("child_name");
        $parentName = $bot->storeGet("parent_name");
        $phone = $bot->storeGet("phone");
        $type = $bot->storeGet("type");
        $age = $bot->storeGet("age");

        $bot->stopConversation();

        $tmp = "Заявка на Lotus Camp:\nТип: *$type*\nФ.И.О. ребенка: *$childName*\nФ.И.О. родителя: *$parentName*\nВозраст ребенка: *$age* лет\nТелефон: _ $phone _\nКомментарий: _ $message _\n";

        $bot->sendMessageToChat(
            env("LC_ADMIN_CHANNEL_ID"),
            "$tmp"
        );

        $bot->getMainMenu("Текст вашего комментария: *$message*\xE2\x9C\x85\nСпасибо! Ваша заявка принята в обработку.");

    }


}
