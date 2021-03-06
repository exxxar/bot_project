<?php


namespace App\Conversations;


use App\Enums\ProductTypeEnum;
use App\Order;
use App\Poll;
use App\Product;
use App\Profile;
use App\Ticket;
use App\User;
use Illuminate\Support\Facades\Log;

class AdminConversation extends Conversation
{

    public static function start($bot)
    {
        $bot->stopConversation();
        $bot->getAdminMenu("Добро пожаловать в админ. панель");
    }


    public static function channels($bot)
    {
        $keyboard = [
            [
                ["text" => "\xF0\x9F\x91\x89LMA Admin channel", "url" => env("LMA_ADMIN_CHANNEL_LINK")]
            ],
            [
                ["text" => "\xF0\x9F\x91\x89LKC Admin channel", "url" => env("LKC_ADMIN_CHANNEL_LINK")]
            ],
            [
                ["text" => "\xF0\x9F\x91\x89LC Admin channel", "url" => env("LC_ADMIN_CHANNEL_LINK")]
            ],
            [
                ["text" => "\xF0\x9F\x91\x89LP Admin channel", "url" => env("LP_ADMIN_CHANNEL_LINK")]
            ],
            [
                ["text" => "\xF0\x9F\x91\x89LD Admin channel", "url" => env("LD_ADMIN_CHANNEL_LINK")]
            ],
            [
                ["text" => "\xF0\x9F\x91\x89LCP Admin channel", "url" => env("LCP_ADMIN_CHANNEL_LINK")]
            ],

        ];

        $bot->sendMessage("Переход в административные каналы", $keyboard);

    }

    public static function profiles($bot, ...$d)
    {
        $page = isset($d[1]) ? intval($d[1]) : 0;


        $profiles = Profile::skip($page * env("PAGINATION_PER_PAGE"))->take(env("PAGINATION_PER_PAGE"))->get();

        if (count($profiles) === 0) {
            $bot->sendMessage("К сожалению, анкет еще нет, но они появятся в скором времени!");
            return;
        }
        $bot->reply("Список анкет пользователей. Текущая страница " . ($page + 1));

        foreach ($profiles as $profile) {
            $bot->sendMessage(
                sprintf("*Анкетные данные #%s:*\n"
                    . "\xF0\x9F\x94\xB9Ф.И.О.: _%s_\n"
                    . "\xF0\x9F\x94\xB9Номер телефона: _%s_\n"
                    . "\xF0\x9F\x94\xB9Ваш рост: %s\n"
                    . "\xF0\x9F\x94\xB9Ваш возраст: %s\n"
                    . "\xF0\x9F\x94\xB9Ваш город: %s\n"
                    . "\xF0\x9F\x94\xB9Ваш пол: %s\n"
                    . "\xF0\x9F\x94\xB9Обучались ли Вы в модельной школе: %s\n"
                    . "\xF0\x9F\x94\xB9Желаете обучаться: %s\n",
                    $profile->id,
                    $profile->full_name ?? "Без имени",
                    $profile->phone ?? "Не указан",
                    $profile->height ?? "Не указан",
                    $profile->age ?? "Не указан",
                    $profile->city ?? "Не указан",
                    $profile->sex ?? "Не указан",
                    $profile->model_school_education ?? "Не указан",
                    $profile->wish_learn ?? "Не указан"

                ));
        }
        $bot->pagination("/all_profile_list", $profiles, $page, "Список анкет пользователей");
    }

    public static function polls($bot, ...$d)
    {
        $page = isset($d[1]) ? intval($d[1]) : 0;

        $main_keyboard = [
            [
                ["text" => "Добавить опрос", "callback_data" => "/add_poll"]
            ],

        ];


        $polls = Poll::skip($page * env("PAGINATION_PER_PAGE"))->take(env("PAGINATION_PER_PAGE"))->get();

        if (count($polls) === 0) {
            $bot->sendMessage("К сожалению, опросов еще нет, но они появятся в скором времени!", $main_keyboard);
            return;
        }


        foreach ($polls as $poll) {

            $tmp = "";
            $tmp_options = json_decode($poll->options);
            foreach ($tmp_options as $tmp_option)
                $tmp .= "\xF0\x9F\x94\xB9" . $tmp_option . "\n";


            $keyboard = [
                [
                    ["text" => "LMA", "callback_data" => "/send_poll LMA $poll->id"],
                    ["text" => "LKC", "callback_data" => "/send_poll LKC $poll->id"],
                    ["text" => "LP", "callback_data" => "/send_poll LP $poll->id"],
                ],
                [
                    ["text" => "LC", "callback_data" => "/send_poll LC $poll->id"],
                    ["text" => "LD", "callback_data" => "/send_poll LD $poll->id"],
                    ["text" => "LCP", "callback_data" => "/send_poll LCP $poll->id"]
                ],
                [
                    ["text" => "Посмотреть опрос", "callback_data" => "/show_poll $poll->id"],
                    ["text" => "Удалить опрос", "callback_data" => "/remove_poll $poll->id"]
                ]
            ];
            $bot->sendMessage(
                sprintf("*Опрос #%s:*\n"
                    . "Текст вопроса: \n_%s_\n"
                    . "Ответы:\n_%s_"
                    . "Анонимный опрос: *%s*\n"
                    . "Множественный выбор: *%s*\n",
                    $poll->id,
                    $poll->question ?? "Не указан",
                    $tmp ?? "Не указан",
                    $poll->is_anonymous ? "Да" : "Нет",
                    $poll->allows_multiple_answers ? "Да" : "Нет"

                ), $keyboard);
        }
        $bot->pagination("/all_polls_list", $polls, $page, "Список опросов для пользователей");

        $bot->sendMessage("Список опросов пользователей. Текущая страница " . ($page + 1), $main_keyboard);
    }

    public static function questions($bot, ...$d)
    {
        $page = isset($d[1]) ? intval($d[1]) : 0;

        $questions = Ticket::whereNull("answered_by_id")
            ->orderBy("id", "desc")
            ->skip($page * env("PAGINATION_PER_PAGE"))
            ->take(env("PAGINATION_PER_PAGE"))
            ->get();


        if (count($questions) === 0) {
            $bot->sendMessage("К сожалению, вопросов еще нет, но они появятся в скором времени!");
            return;
        }


        foreach ($questions as $question) {
            $keyboard = [
                [
                    ["text" => "Ответить на вопрос", "callback_data" => "/answer_this_questions $question->id"],
                ]
            ];
            $bot->sendMessage(
                sprintf("*Вопрос #%s:*\nИмя пользователя: %s\nТекст вопроса: \n_%s_\n",
                    $question->id,
                    $question->name,
                    $question->message
                ), $keyboard);
        }
        $bot->pagination("/all_question_list", $questions, $page, "Список вопросов от пользователей");

        $bot->sendMessage("Список вопросов пользователей. Текущая страница " . ($page + 1));
    }

    public static function products($bot, ...$d)
    {

        $page = isset($d[1]) ? intval($d[1]) : 0;

        $products = Product::skip($page * env("PAGINATION_PER_PAGE"))
            ->take(env("PAGINATION_PER_PAGE"))
            ->get();

        if (count($products) === 0) {
            $bot->sendMessage("К сожалению, товаров еще нет, но они появятся в скором времени!");
            return;
        }


        foreach ($products as $product) {
            $keyboard = [
                [
                    ["text" => "Удалить товар", "callback_data" => "/remove_product $product->id"],
                ]
            ];

            $type_array = [
                "Продукция",
                "LKC",
                "LMA",
                "LC",
                "Сервис"
            ];

            $bot->sendMediaGroup($product->images);
            $bot->sendMessage(
                sprintf("*#%s:*\nНазвание: %s\nЦена: _%s_ руб.\nОписание: _%s_\nКатегория товара: %s",
                    $product->id,
                    $product->title,
                    $product->price,
                    $product->description,
                    $type_array[ProductTypeEnum::getInstance($product->type)->value]
                ), $keyboard);
        }
        $bot->pagination("/all_products_list", $products, $page, "Список товаров");

        $keyboard = [
            [
                ["text" => "Добавить товар", "callback_data" => "/add_new_product"]
            ]
        ];
        $bot->sendMessage("Список товаров. Текущая страница " . ($page + 1), $keyboard);
    }

    public static function statistic($bot)
    {
        $user = $bot->getUser();
        if (!$user->is_admin) {
            $bot->reply("Вы не администратор!");
            return;
        }

        $userCount = User::all()->count();
        $ordersCount = Order::where("is_confirmed", true)->count();

        $bot->reply(
            sprintf(
                "Всего пользователей в системе: *%s*\nВсего заказов: *%s*"
                ,
                $userCount,
                $ordersCount
            ));
    }

}
