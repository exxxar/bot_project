<?php


namespace App\Conversations;


use App\Order;
use App\PhotoProject;
use App\Ticket;
use Illuminate\Support\Facades\Log;

class ConfirmPhotoProjectConversation
{

    public static function start($bot, ...$d)
    {
        $id = isset($d[1]) ? intval($d[1]) : 0;

        $project = PhotoProject::find($id);

        if (is_null($project)) {
            $bot->reply("Хм, фотопроект отсутствует!");
            return;
        }

        $tmp = "#$project->id *$project->title* $project->date в $project->time";
        $bot->getFallbackMenu("Заявка на фотопроект:\n$tmp\n\xF0\x9F\x94\xB8Введите ваше имя:");
        $bot->startConversation("photo_project_name", [
            "id" => $project->id
        ]);
    }

    public static function name($bot, $message)
    {
        if (ConfirmPhotoProjectConversation::fallback($bot, $message))
            return;

        if (mb_strlen($message) === 0) {
            $bot->reply("Нужно ввести Ваше имя!");
            $bot->next("photo_project_name");
            return;
        }
        $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Введите ваш номер телефона:");
        $bot->next("photo_project_phone", [
            "name" => $message
        ]);
    }

    public static function phone($bot, $message)
    {
        if (ConfirmPhotoProjectConversation::fallback($bot, $message))
            return;

        $pattern = "/^\+380\d{3}\d{2}\d{2}\d{2}$/";
        $tmp_phone = str_replace(["(", ")", "-", " "], "", $message);
        $tmp_phone = strpos($tmp_phone, "+38") === false ?
            "+38$tmp_phone" : $tmp_phone;

        if (preg_match($pattern, $tmp_phone) == 0) {
            $bot->reply("Вы неверно ввели телефонный номер! Попробуйте еще раз.");
            $bot->next("photo_project_phone");
            return;
        }

        $bot->reply("Ваш номер телефона: *$tmp_phone*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Введите ваш комментарий к заказу:");

        $bot->next("photo_project_comment", [
            "phone" => $tmp_phone
        ]);
    }

    public static function comment($bot, $message)
    {
        if (ConfirmPhotoProjectConversation::fallback($bot, $message))
            return;

        if (mb_strlen($message) === 0) {
            $bot->reply("Нужно ввести текст Вашего комментария!");
            $bot->next("photo_project_comment");
            return;
        }

        $user = $bot->getUser();

        $name = $bot->storeGet("name");
        $phone = $bot->storeGet("phone");
        $projectId = $bot->storeGet("id");

        $bot->stopConversation();

        $project = PhotoProject::find($projectId);

        if (is_null($project)) {
            $bot->reply("Хм, проект не найден!");
            return;
        }

        $tmp_project = sprintf("\xF0\x9F\x94\xB9 #%s *%s* (%s руб.)",
            $project->id,
            $project->title,
            $project->price
        );

        $tmp = "Заявка на фотопроект:\n$tmp_project\nОт: *$name*\nТелефон: _ $phone _\nКомментарий: _ $message _\n";

        $bot->sendMessageToChat(
            env("LP_ADMIN_CHANNEL_ID"),
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
