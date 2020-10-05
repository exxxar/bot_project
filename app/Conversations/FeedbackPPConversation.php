<?php


namespace App\Conversations;


use App\Ticket;
use Illuminate\Support\Facades\Log;

class FeedbackPPConversation extends Conversation
{

    public static function start($bot, ...$d)
    {
        $keyboard = [
            [
                ["text" => "\xE2\x9C\x85Да", "callback_data" => "Да"],
                ["text" => "\xE2\x9D\x8EНет", "callback_data" => "Нет"],
            ]
        ];

        $bot->getFallbackMenu("Оставить отзыв.");
        $bot->reply("\xF0\x9F\x94\xB8Понравилось ли работать с фотографом?", $keyboard);
        $bot->startConversation("fb2_question_1");
    }

    public static function question1($bot, $message)
    {
        $keyboard = [
            [
                ["text" => "\xE2\x9C\x85Да", "callback_data" => "Да"],
                ["text" => "\xE2\x9D\x8EНет", "callback_data" => "Нет"],
            ]
        ];

        $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\n\xF0\x9F\x94\xB8Понравился ли макияж\прическа?", $keyboard);
        $bot->next("fb2_question_2", [
            "question_1" => $message
        ]);
    }

    public static function question2($bot, $message)
    {
        $keyboard = [
            [
                ["text" => "\xE2\x9C\x85Да", "callback_data" => "Да"],
                ["text" => "\xE2\x9D\x8EНет", "callback_data" => "Нет"],
            ]
        ];

        $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\n\xF0\x9F\x94\xB8Понравилась ли атмосфера на съемке?", $keyboard);
        $bot->next("fb2_question_3", [
            "question_2" => $message
        ]);
    }

    public static function question3($bot, $message)
    {
        $keyboard = [
            [
                ["text" => "\x30\xE2\x83\xA3", "callback_data" => "0"],
                ["text" => "\x31\xE2\x83\xA3", "callback_data" => "1"],
                ["text" => "\x32\xE2\x83\xA3", "callback_data" => "2"],
                ["text" => "\x33\xE2\x83\xA3", "callback_data" => "3"],
                ["text" => "\x34\xE2\x83\xA3", "callback_data" => "4"],
                ["text" => "\x35\xE2\x83\xA3", "callback_data" => "5"],
            ]
        ];

        $bot->reply("Вы выбрали: *$message* баллов.\xE2\x9C\x85\n\n\xF0\x9F\x94\xB8Насколько хороша организация?", $keyboard);
        $bot->next("fb2_question_4", [
            "question_3" => $message
        ]);
    }

    public static function question4($bot, $message)
    {
        $keyboard = [
            [
                ["text" => "\xE2\x9C\x85Да", "callback_data" => "Да"],
                ["text" => "\xE2\x9D\x8EНет", "callback_data" => "Нет"],
            ]
        ];

        $bot->reply("Вы выбрали оценку: *$message*\xE2\x9C\x85\n\n\xF0\x9F\x94\xB8Порекомендовали бы вы другу?", $keyboard);
        $bot->next("fb2_question_5", [
            "question_4" => $message
        ]);
    }

    public static function question5($bot, $message)
    {
        $keyboard = [
            [
                ["text" => "\xE2\x9C\x85Да", "callback_data" => "Да"],
                ["text" => "\xE2\x9D\x8EНет", "callback_data" => "Нет"],
            ]
        ];

        $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\n\xF0\x9F\x94\xB8Хотели бы еще поучаствовать?", $keyboard);
        $bot->next("fb2_question_6", [
            "question_5" => $message
        ]);
    }

    public static function question6($bot, $message)
    {
        $keyboard = [
            [
                ["text" => "\x30\xE2\x83\xA3", "callback_data" => "0"],
                ["text" => "\x31\xE2\x83\xA3", "callback_data" => "1"],
                ["text" => "\x32\xE2\x83\xA3", "callback_data" => "2"],
                ["text" => "\x33\xE2\x83\xA3", "callback_data" => "3"],
                ["text" => "\x34\xE2\x83\xA3", "callback_data" => "4"],
                ["text" => "\x35\xE2\x83\xA3", "callback_data" => "5"],
            ]
        ];

        $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\n\xF0\x9F\x94\xB8Оцените локацию", $keyboard);
        $bot->next("fb2_question_7", [
            "question_6" => $message
        ]);
    }

    public static function question7($bot, $message)
    {
        $bot->reply("Вы выбрали оценку: *$message*.\xE2\x9C\x85\n\n\xF0\x9F\x94\xB8Что бы вы хотели улучшить в проведении съемки? ");
        $bot->next("fb2_question_8", [
            "question_7" => $message
        ]);
    }

    public static function question8($bot, $message)
    {

        $user = $bot->getUser();
        $question_1 = $bot->storeGet("question_1");
        $question_2 = $bot->storeGet("question_2");
        $question_3 = $bot->storeGet("question_3");
        $question_4 = $bot->storeGet("question_4");
        $question_5 = $bot->storeGet("question_5");
        $question_6 = $bot->storeGet("question_6");
        $question_7 = $bot->storeGet("question_7");
        $question_8 = $message;


        $bot->getMainMenu("Вы ввели: *$message*\xE2\x9C\x85\nСпасибо что уделили своё время! Ваши ответы помогают нам становиться лучше!");
        $bot->stopConversation();

        $bot->sendMessageToChat(
            env("LP_ADMIN_CHANNEL_ID"),
            sprintf("Отзыв пользователя о фотопроекте #%s:\n"
                . "\xF0\x9F\x94\xB9Понравилось ли работать с фотографом?\n_%s_\n"
                . "\xF0\x9F\x94\xB9Понравился ли макияж\прическа?\n_%s_\n"
                . "\xF0\x9F\x94\xB9Понравилась ли атмосфера на съемке?\n_%s_\n"
                . "\xF0\x9F\x94\xB9Насколько хороша организация?\n_%s баллов_\n"
                . "\xF0\x9F\x94\xB9Порекомендовали бы вы другу?\n_%s_\n"
                . "\xF0\x9F\x94\xB9Хотели бы еще поучаствовать?\n_%s_\n"
                . "\xF0\x9F\x94\xB9Оцените локацию:\n_%s баллов_\n"
                . "\xF0\x9F\x94\xB9Что бы вы хотели улучшить в проведении съемки?\n_%s_",
                $user->user_id,
                $question_1,
                $question_2,
                $question_3,
                $question_4,
                $question_5,
                $question_6,
                $question_7,
                $question_8
            )
        );


    }


}
