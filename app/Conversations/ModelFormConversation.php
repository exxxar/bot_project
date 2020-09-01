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
        $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Введите ваш номер телефона:");
        $bot->next("mf_phone", [
            "full_name" => $message
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

        $bot->reply("Вы ввели: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Введите ваш рост:");
        $bot->next("mf_height", [
            "age" => $message
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
        $bot->reply("Вы выбрали: *$message*\xE2\x9C\x85\n\xF0\x9F\x94\xB8Желаете обучаться модельному делу?", $keyboard);
        $bot->next("mf_question_2", [
            "question_1" => $message
        ]);
    }

    public static function question2($bot, $message)
    {
        if (ModelFormConversation::fallback($bot, $message))
            return;

        $user = $bot->getUser();

        $profile = Profile::where("user_id", $user->user_id)->first();

        if (is_null($profile))
            Profile::create([
                'full_name' => $bot->storeGet("full_name"),
                'phone' => $bot->storeGet("phone"),
                'height' => $bot->storeGet("height"),
                'age' => $bot->storeGet("age"),
                'sex' => $bot->storeGet("sex"),
                'model_school_education' => $bot->storeGet("question_1"),
                'wish_learn' => $message,
                'city' => $bot->storeGet("city"),
                "user_id" => $user->user_id
            ]);
        else {
            $profile->full_name = $bot->storeGet("full_name");
            $profile->phone = $bot->storeGet("phone");
            $profile->height = $bot->storeGet("height");
            $profile->age = $bot->storeGet("age");
            $profile->sex = $bot->storeGet("sex");
            $profile->model_school_education = $bot->storeGet("question_1");
            $profile->wish_learn = $message;
            $profile->city = $bot->storeGet("city");
            $profile->save();
        }
        $bot->stopConversation();

        $keyboard = [
            [
                ["text"=>"\xF0\x9F\x8E\xB4Моя анкета","callback_data"=>"/current_profile"]
            ]
        ];
        $bot->sendMessage("Вы выбрали: *$message*\xE2\x9C\x85\nСпасибо! Ваши данные приняты в обработку!",$keyboard);
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
