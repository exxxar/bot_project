<?php


namespace App\Conversations;


use ErrorException;
use Illuminate\Support\Facades\Log;

class SearchModelConversation extends Conversation
{
    public static function start($bot)
    {

        $bot->getFallbackMenu("Поиск моделей по любому параметру!\n\xF0\x9F\x94\xB8Введите значение параметра (например: Виктория или же 18):");
        $bot->startConversation("search_text");
    }

    public static function text($bot, $message)
    {

        $bot->getMainMenu("Текст ответа: *$message*\xE2\x9C\x85\nСпасибо! Ищем моделей!");
        $bot->stopConversation();

        $bot->reply("Вы ввели: $message \nРезультат:");

        $query = "text=$message";

        try {
            $context = stream_context_create(array(
                'http' => array(
                    'method' => 'POST',
                    'header' => 'Content-Type: application/x-www-form-urlencoded' . PHP_EOL,
                    'content' => $query
                ),
            ));
            ini_set('max_execution_time', 1000000);
            $content = file_get_contents(
                $file = 'http://lotus-model.ru/search-models.php',
                $use_include_path = false,
                $context);
            ini_set('max_execution_time', 60);
        } catch (ErrorException $e) {
            $content = [];
        }
        $list_models = json_decode($content);

        $result_list = array_slice($list_models,0,5);
        foreach ($result_list as $model) {

           $bot->sendPhoto(sprintf("*Модель #%s*\nИмя:%s\nСсылка на профиль: http://lotus-model.ru/%s\n",
               $model->id,
               $model->username,
               $model->uri
           ),sprintf("http://lotus-model.ru/%s", $model->user_data->main_photo));


        }

        $keyboard = [
            [
                ["text" => "Перейти на сайте для поиска", "url" => "http://lotus-model.ru/test-search.php"],

            ],
            [
                ["text" => "Найти еще", "callback_data" => "/search_model_in_bot"]
            ]
        ];

        $bot->reply("По запросу \"$message\" найдено " . count($list_models) . " моделей, показано ".count($result_list)." результатов. Другие результаты можно посмотреть на сайте...", $keyboard);

    }


}
