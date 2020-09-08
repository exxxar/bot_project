<?php


namespace App\Conversations;


use App\Enums\ProductTypeEnum;
use App\Poll;
use App\Ticket;
use Illuminate\Support\Facades\Log;

class ProductFormConversation
{

    public static function start($bot)
    {
        $bot->getFallbackMenu("Диалог создания товара");



        $keyboard = [
            [
                ["text" => "Продукция", "callback_data" => 0], ["text" => "LKC", "callback_data" => 1],
            ],
            [
                ["text" => "LMA", "callback_data" => 2], ["text" => "LC", "callback_data" => 3],
            ],
            [
                ["text" => "Сервис", "callback_data" => 4],
            ],
        ];

        $bot->reply("Выберите категорию:",$keyboard);
        $bot->startConversation("product_conversation_type");
    }

    public static function type($bot, $message)
    {
        if (ProductFormConversation::fallback($bot, $message))
            return;

        $type_array = [
            "Продукция",
            "LKC",
            "LMA",
            "LC",
            "Сервис"
        ];

        $type = $type_array[ProductTypeEnum::getInstance(intval($message))->value];

        $bot->reply("Вы выбрали: *$type*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Введите название товара:");
        $bot->next("product_conversation_title", [
            "type" => $message
        ]);
    }

    public static function question($bot, $message)
    {
        if (ProductFormConversation::fallback($bot, $message))
            return;

        $keyboard = [
            [
                ["text" => "\xE2\x9C\x85Да", "callback_data" => "Да"], ["text" => "\xE2\x9D\x8EНет", "callback_data" => "Нет"],
            ],
        ];

        $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Разрешить несколько ответов?:", $keyboard);
        $bot->next("poll_allows_multiple_answers", [
            "question" => $message
        ]);
    }

    /*    public static function changeAnonymous($bot, $message)
        {
            if (PollsFormConversation::fallback($bot, $message))
                return;

            $keyboard = [
                [
                    ["text" => "\xE2\x9C\x85Да", "callback_data" => "Да"], ["text" => "\xE2\x9D\x8EНет", "callback_data" => "Нет"],
                ],
            ];

            $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Разрешить несколько ответов?:", $keyboard);
            $bot->next("poll_allows_multiple_answers", [
                "is_anonymous" => $message
            ]);
        }*/

    public static function changeAllowsMultipleAnswers($bot, $message)
    {
        if (ProductFormConversation::fallback($bot, $message))
            return;

        $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Ведите варианты ответа:");
        $bot->next("poll_option", [
            "allows_multiple_answers" => $message
        ]);
    }

    public static function option($bot, $message)
    {
        if (ProductFormConversation::fallback($bot, $message))
            return;

        $options = json_decode($bot->storeGet("options", '[]'), true);

        if ($message === "завершить" || count($options) === 10) {
            $user = $bot->getUser();

            if (count($options) === 1) {
                $keyboard = [
                    [
                        ["text" => "\xE2\x9C\x85Завершить?", "callback_data" => "завершить"],
                    ],
                ];

                $bot->reply("Требуется ввести минимум 2а варианта ответа!\nВы ввели: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Введите следующий вариант ответа:", $keyboard);
                $bot->next("poll_option");
                return;
            }
            Poll::create([
                "question" => $bot->storeGet("question"),
                "options" => $bot->storeGet("options"),
                "is_anonymous" => $bot->storeGet("is_anonymous") === "Да",
                "allows_multiple_answers" => $bot->storeGet("allows_multiple_answers") === "Да",
                "created_by_id" => $user->user_id
            ]);
            $bot->stopConversation();
            $bot->getAdminMenu("Спасибо! Ваш опрос успешно создан!");
            return;
        }
        $keyboard = [
            [
                ["text" => "\xE2\x9C\x85Завершить?", "callback_data" => "завершить"],
            ],
        ];

        $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Введите следующий вариант ответа:", $keyboard);


        array_push($options, $message);

        $bot->next("poll_option", [
            "options" => json_encode($options)
        ]);


    }

    public static function fallback($bot, $message)
    {
        if ($message === "Продолжить позже") {
            $bot->getAdminMenu("Хорошо! Продолжим позже!");
            $bot->stopConversation();
            return true;
        } else
            return false;
    }

    public static function show($bot, ...$d)
    {
        $id = isset($d[1]) ? intval($d[1]) : null;

        $poll = Poll::find($id);

        if (is_null($id) || is_null($poll)) {
            $bot->reply("Опрос не найден!");
            return;
        }

        $user = $bot->getUser();

        $bot->sendPoll(
            $poll->question,
            \GuzzleHttp\json_decode($poll->options, true),
            $poll->is_anonymous ? true : false,
            $poll->allows_multiple_answers ? true : false,
            $user->chat_id
        );

    }


    public static function sendPoll($bot, ...$d)
    {
        $type = isset($d[1]) ? intval($d[1]) : 0;
        $id = isset($d[2]) ? intval($d[2]) : 0;

        Log::info("ID=$id Type=$type");
        $poll = Poll::find($id);

        if (is_null($id) || is_null($poll)) {
            $bot->reply("Опрос не найден!");
            return;
        }


        $index = $type >= 0 && $type <= 4 ? $type : 0;
        $channels = [
            env("LOTUS_BASE_CHANEL_ID"),
            env("LOTUS_BASE_CHANEL_ID"),
            env("LOTUS_BASE_CHANEL_ID"),
            env("LOTUS_BASE_CHANEL_ID")
        ];

        $bot->sendPoll(
            $poll->question,
            \GuzzleHttp\json_decode($poll->options, true),
            $poll->is_anonymous ? true : false,
            $poll->allows_multiple_answers ? true : false,
            $channels[$index]
        );

        $bot->reply("Опрос успешно отправлен в канал!");
    }

    public static function remove($bot, ...$d)
    {
        $id = isset($d[1]) ? intval($d[1]) : 0;

        $poll = Poll::find($id);

        if (is_null($id) || is_null($poll)) {
            $bot->reply("Опрос не найден!");
            return;
        }

        $keyboard = [
            [
                ["text" => "\xE2\x9C\x85Да", "callback_data" => "Да"], ["text" => "\xE2\x9D\x8EНет", "callback_data" => "Нет"],
            ],
        ];

        $bot->sendMessage(sprintf("Вы действительно хотите удалить *%s*(%s) вопрос?",
            $poll->question,
            $poll->id
        ), $keyboard);

        $bot->startConversation("poll_remove", [
            "id" => $id
        ]);
    }

    public static function removeAccept($bot, $message)
    {
        if ($message === "Да") {
            $id = $bot->storeGet("id") ?? -1;
            $poll = Poll::find($id);
            $poll->delete();
            $bot->reply("Опрос #$id успешно удален!");
            $bot->stopConversation();
            return;
        }
        $bot->reply("Отмена удаления опроса!");
        $bot->stopConversation();
    }

}
