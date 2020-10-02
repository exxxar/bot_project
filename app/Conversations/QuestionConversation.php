<?php


namespace App\Conversations;


use App\Ticket;
use Illuminate\Support\Facades\Log;

class QuestionConversation extends Conversation
{

    public static function start($bot, ...$d)
    {
        $type = isset($d[2]) ? $d[2] : 'LMA';

        $user = $bot->getUser();

        $needName = mb_strlen($user->user_profile->full_name) === 0;

        $bot->getFallbackMenu("Диалог с администратором $type.\n\xF0\x9F\x94\xB8"
            . ($needName ? "Введите ваше имя:" : "Введите ваш вопрос:")
        );


        if ($needName)
            $bot->startConversation("question_name", [
                "type" => $type
            ]);
        else {
            $bot->startConversation("question_text", [
                "type" => $type,
                "name" => $user->user_profile->full_name ??
                    $user->fio ??
                    $user->account_name ??
                    $user->chat_id
            ]);
        }
    }

    public static function name($bot, $message)
    {
        $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Введите ваш вопрос:");
        $bot->next("question_text", [
            "name" => $message
        ]);
    }

    public static function text($bot, $message)
    {
        $user = $bot->getUser();
        $type = $bot->storeGet("type");
        $name = $bot->storeGet("name");

        $bot->getMainMenu("Текст вашего вопроса: *$message*\xE2\x9C\x85\nСпасибо! Ваш вопрос принят в обработку.");
        $bot->stopConversation();

        $question_type_ids = ["LMA" => 0, "LKC" => 1, "LP" => 2, "LC" => 3, "LD" => 4, "LCP" => 5];

        $channels = [
            env("LMA_CHANNEL_ID"),
            env("LKC_CHANNEL_ID"),
            env("LP_CHANNEL_ID"),
            env("LC_CHANNEL_ID"),
            env("LD_CHANNEL_ID"),
            env("LCP_CHANNEL_ID"),
        ];

        $ticket = Ticket::create([
            "chat_id" => $user->chat_id,
            "question_type" => $question_type_ids[$type] ?? 0,
            "name" => $name,
            "message" => $message,
            'answered_by_id' => null
        ]);


        $tmp_id = (string)$ticket->id;
        while (strlen($tmp_id) < 10)
            $tmp_id = "0" . $tmp_id;

        $code = base64_encode("002" . $tmp_id);
        $url_link = "https://t.me/" . env("APP_BOT_NAME") . "?start=$code";

        $keyboard = [
            [
                [
                    "text" => "Ответит на вопрос", "url" => "$url_link"
                ]
            ]
        ];
        $bot->sendMessageToChat(
            $channels[($question_type_ids[$type] ?? 0)],
            "*Новый вопрос ($type)*:\nОт:_ $name _ (#*$user->user_id*)\nВопрос: _ $message _\n",
            $keyboard
        );


    }


}
