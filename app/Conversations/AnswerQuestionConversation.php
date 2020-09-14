<?php


namespace App\Conversations;


use App\Ticket;
use Illuminate\Support\Facades\Log;

class AnswerQuestionConversation
{

    public static function start($bot, ...$d)
    {
        $id = isset($d[1]) ? intval($d[1]) : 0;

        $ticket = Ticket::find($id);

        $bot->getFallbackMenu("Ответ на вопрос пользователя:\n_ $ticket->message _\n\xF0\x9F\x94\xB8Введите ответ:");
        $bot->startConversation("answer_response", [
            "chat_id" => $ticket->chat_id,
            "ticket_id" => $id
        ]);
    }

    public static function response($bot, $message)
    {
        if (AnswerQuestionConversation::fallback($bot, $message))
            return;

        if (mb_strlen($message) === 0) {
            $bot->reply("Нужно ввести текст Вашего ответа!");
            $bot->next("answer_response");
            return;
        }

        $user = $bot->getUser();

        $ticket_id = $bot->storeGet("ticket_id");
        $chat_id = $bot->storeGet("chat_id");

        $ticket = Ticket::find($ticket_id);
        $ticket->answered_by_id = $user->user_id;
        $ticket->save();

        $bot->getMainMenu("Текст ответа: *$message*\xE2\x9C\x85\nСпасибо! Ответ отправлен пользователю");
        $bot->stopConversation();

        $question_type_ids = ["LMC", "LKC", "LP", "LC", "LD", "LCP"];

        $keyboard = [
            [
                ["text" => "Задать еще вопрос", "callback_data" => "/ask_question " . $question_type_ids[$ticket->question_type]]
            ]
        ];

        $bot->sendMessageToChat(
            $chat_id,
            "Вам ответили!\nВопрос:\n_ $ticket->message _\nОтвет:\n_ $message _",
            $keyboard
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
