<?php


namespace App\Conversations;


use App\Ticket;
use Illuminate\Support\Facades\Log;

class QuestionConversation
{

    public static function start($bot, ...$d)
    {
        $type = isset($d[1]) ? intval($d[1]) : 'LMA';

        $bot->getFallbackMenu("Диалог с администратором $type.\n\xF0\x9F\x94\xB8Введите ваше имя:");
        $bot->startConversation("question_name");
    }

    public static function name($bot, $message)
    {
        if (QuestionConversation::fallback($bot,$message))
            return;

        if (mb_strlen($message) === 0) {
            $bot->reply("Нужно ввести Ваше имя!");
            $bot->next("question_name");
            return;
        }
        $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Введите ваш вопрос:");
        $bot->next("question_text",[
            "name"=>$message
        ]);
    }

    public static function text($bot, $message)
    {
        if (QuestionConversation::fallback($bot,$message))
            return;

        if (mb_strlen($message) === 0) {
            $bot->reply("Нужно ввести текст Вашего вопроса!");
            $bot->next("question_text");
            return;
        }

        $user = $bot->getUser();

        $name = $bot->storeGet("name");

        $bot->getMainMenu("Текст вашего вопроса: *$message*\xE2\x9C\x85\nСпасибо! Ваш вопрос принят в обработку.");
        $bot->stopConversation();

        Ticket::create([
            "chat_id"=>$user->chat_id,
            "question_type"=>0,
            "name"=>$name,
            "message"=>$message,
            'answered_by_id'=>null
        ]);

        $bot->sendMessageToChat(
            env("LOTUS_BASE_CHANEL_ID"),
            sprintf("*Новый вопрос*:\nОт:_ $name _\nВопрос: _ $message _\n*Для ответа перейдите в раздел администратора*")
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
