<?php


namespace App\Conversations;


use App\Order;
use App\Ticket;
use Illuminate\Support\Facades\Log;

class ConfirmOrderConversation
{

    public static function start($bot)
    {
        $user = $bot->getUser();

        $needName = mb_strlen($user->user_profile->full_name) === 0;

        $bot->getFallbackMenu("Диалог оформления заказа.\n\xF0\x9F\x94\xB8"
            . ($needName ? "Введите ваше имя:" : "Введите ваш номер телефона(в формате 071XXXXXXX):")
        );


        if ($needName)
            $bot->startConversation("confirm_order_name");
        else {
            $bot->startConversation("confirm_order_phone", [
                "name" => $user->user_profile->full_name ??
                    $user->fio ??
                    $user->account_name ??
                    $user->chat_id
            ]);
        }
    }

    public static function name($bot, $message)
    {

        if (mb_strlen($message) === 0) {
            $bot->reply("Нужно ввести Ваше имя!");
            $bot->next("confirm_order_name");
            return;
        }
        $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Введите ваш номер телефона(в формате 071XXXXXXX)):");
        $bot->next("confirm_order_phone", [
            "name" => $message
        ]);
    }

    public static function phone($bot, $message)
    {


        $pattern = "/^\+380\d{3}\d{2}\d{2}\d{2}$/";
        $tmp_phone = str_replace(["(", ")", "-", " "], "", $message);
        $tmp_phone = strpos($tmp_phone, "+38") === false ?
            "+38$tmp_phone" : $tmp_phone;

        if (preg_match($pattern, $tmp_phone) == 0) {
            $bot->reply("Вы неверно ввели телефонный номер! Попробуйте ввести в формате 071XXXXXXX.");
            $bot->next("confirm_order_phone");
            return;
        }

        $bot->reply("Ваш номер телефона: *$tmp_phone*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Введите ваш комментарий к заказу:");

        $bot->next("confirm_order_comment", [
            "phone" => $tmp_phone
        ]);
    }

    public static function comment($bot, $message)
    {


        if (mb_strlen($message) === 0) {
            $bot->reply("Нужно ввести текст Вашего комментария!");
            $bot->next("confirm_order_comment");
            return;
        }

        $user = $bot->getUser();

        $name = $bot->storeGet("name");
        $phone = $bot->storeGet("phone");


        $bot->stopConversation();

        $orders = Order::with(["product"])
            ->where("user_id", $user->user_id)
            ->where("is_confirmed", false)
            ->get();
        $price = 0;
        $tmp = "Имя заказчика: *$name*\nТелефон: _ $phone _\nКомментарий: _ $message _\n";

        foreach ($orders as $order) {
            $order->comment = $message;
            $order->is_confirmed = true;
            $order->save();

            $tmp .= sprintf("\xF0\x9F\x94\xB9 #%s *%s* (%s руб.)\n",
                $order->product->id,
                $order->product->title,
                $order->product->price
            );
            $price += $order->product->price;


        }

        $tmp .= "Суммарно к оплате: *$price руб.*";

        $bot->sendMessageToChat(
            env("LMA_ADMIN_CHANNEL_ID"),
            "$tmp"
        );

        $bot->getMainMenu("Текст вашего комментария: *$message*\xE2\x9C\x85\n\n_Спасибо! Ваш заказ принят в обработку.\n\nБудем благодарны если после получения Вы напишитие всё ли понравилось и подошло!\n\nСпасибо за Ваш заказ!_\n\n#лучшиеснами");

    }

    public static function fallback($bot, $message, $error= null)
    {
        if ($message === "Продолжить позже") {
            $bot->getBasketMenu("Хорошо! Продолжим позже!");
            $bot->stopConversation();
            return true;
        } else
            return false;
    }


}
