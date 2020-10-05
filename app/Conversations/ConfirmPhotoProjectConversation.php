<?php


namespace App\Conversations;


use App\Order;
use App\PhotoProject;
use App\Ticket;
use Illuminate\Support\Facades\Log;

class ConfirmPhotoProjectConversation extends Conversation
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

        $user = $bot->getUser();

        $needName = mb_strlen($user->user_profile->full_name) === 0;


        if ($needName) {
            $bot->getFallbackMenu("Заявка на фотопроект:\n\xF0\x9F\x94\xB8$tmp\nВведите ваше имя:");
            $bot->startConversation("photo_project_name");
        }
        else {
            $bot->getFallbackMenuWithPhone("Заявка на фотопроект:\n\xF0\x9F\x94\xB8$tmp\nВведите ваш номер телефона(в формате 071XXXXXXX):");
            $bot->startConversation("photo_project_phone", [
                "name" => $user->user_profile->full_name ??
                    $user->fio ??
                    $user->account_name ??
                    $user->chat_id
            ]);
        }
    }

    public static function name($bot, $message)
    {

        $bot->getFallbackMenuWithPhone("Вы ввели: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Введите ваш номер телефона:");
        $bot->next("photo_project_phone", [
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
            $bot->reply("Вы неверно ввели телефонный номер! Попробуйте еще раз.");
            $bot->next("photo_project_phone");
            return;
        }

        $bot->getFallbackMenu("Ваш номер телефона: *$tmp_phone*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Введите ваш комментарий к заказу:");

        $bot->next("photo_project_comment", [
            "phone" => $tmp_phone
        ]);
    }

    public static function comment($bot, $message)
    {

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




}
