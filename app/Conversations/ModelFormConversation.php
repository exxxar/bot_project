<?php


namespace App\Conversations;


use App\Profile;
use Illuminate\Support\Facades\Log;

class ModelFormConversation
{
    public static function start($bot)
    {
        $bot->getFallbackMenu("Формируем анкету модели.\n\xF0\x9F\x94\xB8Введите ваше Ф.И.О:");
        $bot->startConversation("mf_full_name");
    }

    public static function name($bot, $message)
    {
        if (ModelFormConversation::fallback($bot, $message))
            return;

        if (mb_strlen($message) <= 3) {
            $bot->reply("Нужно ввести Ваше полное Ф.И.О!");
            $bot->next("mf_full_name");
            return;
        }

        $keyboard = [
            [
                ["text" => "Парень", "callback_data" => "муж"],
                ["text" => "Девушка", "callback_data" => "жен"],
            ]
        ];
        $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Выберите ваш пол:", $keyboard);
        $bot->next("mf_sex", [
            "full_name" => $message
        ]);
    }


    public static function sex($bot, $message)
    {
        if (ModelFormConversation::fallback($bot, $message))
            return;

        $bot->reply("Вы выбрал: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Введите ваш номер телефона:");
        $bot->next("mf_phone", [
            "sex" => $message
        ]);
    }

    public static function phone($bot, $message)
    {
        if (ModelFormConversation::fallback($bot, $message))
            return;

        $pattern = "/^\+380\d{3}\d{2}\d{2}\d{2}$/";
        $tmp_phone = str_replace(["(", ")", "-", " "], "", $message);
        $tmp_phone = strpos($tmp_phone, "+38") === false ?
            "+38$tmp_phone" : $tmp_phone;

        if (preg_match($pattern, $tmp_phone) == 0) {
            $bot->reply("Вы неверно ввели телефонный номер! Попробуйте еще раз.");
            $bot->next("mf_phone");
            return;
        }

        $keyboard = [
            [
                ["text" => "\xF0\x9F\x93\x8DДонецк", "callback_data" => "Донецк"], ["text" => "\xF0\x9F\x93\x8DМакеевка", "callback_data" => "Макеевка"],
            ],
            [
                ["text" => "\xF0\x9F\x93\x8DЯсиноватая", "callback_data" => "Ясиноватая"], ["text" => "\xF0\x9F\x93\x8DХарцызск", "callback_data" => "Харцызск"],
            ],
            [
                ["text" => "\xF0\x9F\x8F\xA1Ввести свой город", "callback_data" => "other"],
            ],
        ];

        $bot->reply("Ваш номер телефона: *$tmp_phone*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Выберите город, в котором вы проживаете:", $keyboard);

        $bot->next("mf_select_city", [
            "phone" => $tmp_phone
        ]);
    }

    public static function selectCity($bot, $message)
    {
        if (ModelFormConversation::fallback($bot, $message))
            return;

        $cities = ["Донецк", "Макеевка", "Ясиноватая", "Харцызск"];


        if (!in_array($message, $cities)) {
            $bot->reply("Введите свой город:");
            $bot->next("mf_ask_city");
            return;
        }

        $bot->reply("Вы выбрали: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Введите ваш возраст:");
        $bot->next("mf_age", [
            "city" => $message
        ]);
    }

    public static function askCity($bot, $message)
    {
        if (ModelFormConversation::fallback($bot, $message))
            return;

        $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Введите ваш возраст:");
        $bot->next("mf_age", [
            "city" => $message
        ]);
    }

    public static function age($bot, $message)
    {
        if (ModelFormConversation::fallback($bot, $message))
            return;

        if (!is_numeric($message)) {
            $bot->reply("Попробуйте ввести возраст числом, например: 18");
            $bot->next("mf_age");
            return;
        }

        $keyboard = [];
        $tmp_keyboard_row = [];
        for ($i = 1; $i <= 31; $i++) {

            array_push($tmp_keyboard_row, ["text" => $i, "callback_data" => $i]);
            if ($i % 7 === 0) {
                array_push($keyboard, $tmp_keyboard_row);
                $tmp_keyboard_row = [];
            }
        }
        array_push($keyboard, $tmp_keyboard_row);

        $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Выберите день вашего рождения:", $keyboard);
        $bot->next("mf_birth_day", [
            "age" => $message
        ]);
    }

    public static function birthDay($bot, $message)
    {
        if (ModelFormConversation::fallback($bot, $message))
            return;

        if (intval($message) < 1 || intval($message) > 31) {
            $bot->reply("Попробуйте выбрать день из предложенных вариантов");
            $bot->next("mf_birth_day");
            return;
        }

        $months = [
            "Январь",
            "Февраль",
            "Март",
            "Апрель",
            "Май",
            "Июнь",
            "Июль",
            "Август",
            "Сентябрь",
            "Октябрь",
            "Ноябрь",
            "Декабрь",
        ];

        $keyboard = [];
        $tmp_keyboard_row = [];
        for ($i = 0; $i < 12; $i++) {
            array_push($tmp_keyboard_row, ["text" => $months[$i], "callback_data" => $i + 1]);
            if ($i % 3 === 0) {
                array_push($keyboard, $tmp_keyboard_row);
                $tmp_keyboard_row = [];
            }

        }
        array_push($keyboard, $tmp_keyboard_row);

        $birth_day = mb_strlen($message) === 1 ? "0$message" : $message;
        $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Выберите ваш месяц рождения:", $keyboard);
        $bot->next("mf_birth_month", [
            "birth_day" => $birth_day
        ]);
    }

    public static function birthMonth($bot, $message)
    {
        if (ModelFormConversation::fallback($bot, $message))
            return;

        if (intval($message) < 0 || intval($message) > 12) {
            $bot->reply("Попробуйте выбрать месяц из предложенных вариантов");
            $bot->next("mf_birth_day");
            return;
        }
        $birth_month = mb_strlen($message) === 1 ? "0$message" : $message;
        $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Введите ваш рост:");
        $bot->next("mf_height", [
            "birth_month" => $birth_month
        ]);
    }

    public static function height($bot, $message)
    {
        if (ModelFormConversation::fallback($bot, $message))
            return;

        if (!is_numeric($message)) {
            $bot->reply("Попробуйте ввести рост числом, например: 180");
            $bot->next("mf_height");
            return;
        }

        $keyboard = [
            [
                ["text" => "\xE2\x9C\x85Да", "callback_data" => "Да"], ["text" => "\xE2\x9D\x8EНет", "callback_data" => "Нет"],
            ],
        ];
        $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Обучались ли вы в модельной школе?", $keyboard);
        $bot->next("mf_question_1", [
            "height" => $message
        ]);
    }

    public static function question1($bot, $message)
    {
        if (ModelFormConversation::fallback($bot, $message))
            return;

        $keyboard = [
            [
                ["text" => "\xE2\x9C\x85Да", "callback_data" => "Да"], ["text" => "\xE2\x9D\x8EНет", "callback_data" => "Нет"],
            ],
        ];
        $bot->reply("Вы выбрали: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Хотели бы вы принимать участие в фотопроектах?", $keyboard);
        $bot->next("mf_question_3", [
            "question_1" => $message
        ]);
    }

    public static function question3($bot, $message)
    {
        if (ModelFormConversation::fallback($bot, $message))
            return;

        $keyboard = [
            [
                ["text" => "\xE2\x9C\x85Да", "callback_data" => "Да"], ["text" => "\xE2\x9D\x8EНет", "callback_data" => "Нет"],
            ],
        ];
        $bot->reply("Вы выбрали: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Желаете обучаться модельному делу?", $keyboard);
        $bot->next("mf_question_2", [
            "question_3" => $message
        ]);
    }

    public static function question2($bot, $message)
    {
        if (ModelFormConversation::fallback($bot, $message))
            return;

        $user = $bot->getUser();

        $profile = Profile::where("user_id", $user->user_id)->first();

        if (is_null($profile)) {
            Profile::create([
                'full_name' => $bot->storeGet("full_name"),
                'phone' => $bot->storeGet("phone"),
                'height' => $bot->storeGet("height"),
                'age' => $bot->storeGet("age"),
                'sex' => $bot->storeGet("sex"),
                'model_school_education' => $bot->storeGet("question_1"),
                'wish_learn' => $message,
                'city' => $bot->storeGet("city"),
                'birth_month' => $bot->storeGet("birth_month"),
                'birth_day' => $bot->storeGet("birth_day"),
                "user_id" => $user->user_id
            ]);

            $bot->sendMessageToChat(
                env("LMA_ADMIN_CHANNEL_ID"),
                sprintf("*Новые анкетные данные:*\n"
                    . "\xF0\x9F\x94\xB9Ф.И.О.: _%s_\n"
                    . "\xF0\x9F\x94\xB9Номер телефона: _%s_\n"
                    . "\xF0\x9F\x94\xB9Ваш рост: %s\n"
                    . "\xF0\x9F\x94\xB9Ваш возраст: %s (%s.%s)\n"
                    . "\xF0\x9F\x94\xB9Ваш город: %s\n"
                    . "\xF0\x9F\x94\xB9Ваш пол: %s\n"
                    . "\xF0\x9F\x94\xB9Обучались ли Вы в модельной школе: %s\n"
                    . "\xF0\x9F\x94\xB9Хотели бы вы участвовать в фотопроектах?: %s\n"
                    . "\xF0\x9F\x94\xB9Желаете обучаться: %s\n",
                    ($profile->full_name ?? "Без имени"),
                    ($profile->phone ?? "Не указан"),
                    ($profile->height ?? "Не указан"),
                    ($profile->age ?? "Не указан"),
                    ($profile->birth_day ?? "Не указан"),
                    ($profile->birth_month ?? "Не указан"),
                    ($profile->city ?? "Не указан"),
                    ($profile->sex ?? "Не указан"),
                    ($profile->model_school_education ?? "Не указан"),
                    ($profile->wish_photoproject ?? "Не указан"),
                    ($profile->wish_learn ?? "Не указан")
                ));
        } else {
            $profile->full_name = $bot->storeGet("full_name");
            $profile->phone = $bot->storeGet("phone");
            $profile->height = $bot->storeGet("height");
            $profile->age = $bot->storeGet("age");
            $profile->sex = $bot->storeGet("sex");
            $profile->model_school_education = $bot->storeGet("question_1");
            $profile->wish_learn = $message;
            $profile->wish_photoproject = $bot->storeGet("question_3");
            $profile->city = $bot->storeGet("city");
            $profile->birth_month = $bot->storeGet("birth_month");
            $profile->birth_day = $bot->storeGet("birth_day");
            $profile->save();
        }
        $bot->stopConversation();


        $keyboard = [
            [
                ["text" => "\xF0\x9F\x8E\xB4Моя анкета", "callback_data" => "/current_profile"]
            ]
        ];
        $bot->sendMessage("Вы выбрали: *$message*\xE2\x9C\x85\nСпасибо! Ваши данные приняты в обработку!", $keyboard);
        $bot->getMainMenu("Главное меню");
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
