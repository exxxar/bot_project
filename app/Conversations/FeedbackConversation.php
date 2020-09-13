<?php


namespace App\Conversations;


use App\Ticket;
use Illuminate\Support\Facades\Log;

class FeedbackConversation
{

    public static function start($bot, ...$d)
    {


        $bot->getFallbackMenu("Оставить отзыв.\n\xF0\x9F\x94\xB8Какой предмет?");
        $bot->startConversation("fb1_question_1");
    }

    public static function question1($bot, $message)
    {
        if (FeedbackConversation::fallback($bot, $message))
            return;

        $keyboard = [
          [
              ["text"=>"\xE2\x9C\x85Да","callback_data"=>"Да"],
              ["text"=>"\xE2\x9D\x8EНет","callback_data"=>"Нет"],
          ]
        ];

        $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Было ли занятие полезным?",$keyboard);
        $bot->next("fb1_question_2", [
            "question_1" => $message
        ]);
    }

    public static function question2($bot, $message)
    {
        if (FeedbackConversation::fallback($bot, $message))
            return;


        $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Кто вёл занятие(Имя)?");
        $bot->next("fb1_question_3", [
            "question_2" => $message
        ]);
    }

    public static function question3($bot, $message)
    {
        if (FeedbackConversation::fallback($bot, $message))
            return;


        $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Что понравилось?");
        $bot->next("fb1_question_4", [
            "question_3" => $message
        ]);
    }

    public static function question4($bot, $message)
    {
        if (FeedbackConversation::fallback($bot, $message))
            return;


        $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Что НЕ понравилось?");
        $bot->next("fb1_question_5", [
            "question_4" => $message
        ]);
    }

    public static function question5($bot, $message)
    {
        if (FeedbackConversation::fallback($bot, $message))
            return;


        $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Что лучше всего запомнилось?");
        $bot->next("fb1_question_6", [
            "question_5" => $message
        ]);
    }

    public static function question6($bot, $message)
    {
        if (FeedbackConversation::fallback($bot, $message))
            return;


        $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Что хотелось бы изменить?");
        $bot->next("fb1_question_7", [
            "question_6" => $message
        ]);
    }




    public static function question7($bot, $message)
    {
        if (FeedbackConversation::fallback($bot, $message))
            return;


        $user = $bot->getUser();
        $question_1 = $bot->storeGet("question_1");
        $question_2 = $bot->storeGet("question_2");
        $question_3 = $bot->storeGet("question_3");
        $question_4 = $bot->storeGet("question_4");
        $question_5 = $bot->storeGet("question_5");
        $question_6 = $bot->storeGet("question_6");
        $question_7 = $message;


        $bot->getMainMenu("Вы ввели: *$message*\xE2\x9C\x85\nСпасибо! Для нас Ваш отзыв очен ценный! Вместе с мы становимся лучше.");
        $bot->stopConversation();

        $bot->sendMessageToChat(
            env("LOTUS_BASE_CHANEL_ID"),
            sprintf("Анонимный отзыв пользователя #%s:\n"
                ."\xF0\x9F\x94\xB9Какой предмет?\n_%s_\n"
                ."\xF0\x9F\x94\xB9Было ли занятие полезным?\n_%s_\n"
                ."\xF0\x9F\x94\xB9Кто вёл занятие(Имя)?\n_%s_\n"
                ."\xF0\x9F\x94\xB9Что понравилось?\n_%s_\n"
                ."\xF0\x9F\x94\xB9Что НЕ понравилось?\n_%s_\n"
                ."\xF0\x9F\x94\xB9Что лучше всего запомнилось?\n_%s_\n"
                ."\xF0\x9F\x94\xB9Что хотелось бы изменить?\n_%s_",
            $user->user_id,
            $question_1,
            $question_2,
            $question_3,
            $question_4,
            $question_5,
            $question_6,
            $question_7
            )
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
