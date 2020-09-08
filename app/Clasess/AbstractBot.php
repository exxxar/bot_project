<?php


namespace App\Clasess;


use App\Classes\tBotConversation;
use App\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Laravel\BotCashBack\Models\BotUserInfo;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Objects\Update;

abstract class AbstractBot
{
    use tBotConversation, tLotusModelBot;

    protected $bot;

    protected $list;

    protected $current_ask;

    protected $query;

    protected $telegram_user;

    protected $bot_params;

    protected $message_id;

    private function hears($path, $func)
    {
        array_push($this->list, ["path" => $path, "function" => $func]);

        return $this;
    }

    private function ask($name, $func)
    {
        array_push($this->current_ask, ["name" => $name, "func" => $func]);
        return $this;
    }

    private function fallback($func)
    {
        array_push($this->list, ["path" => null, "function" => $func]);
        return $this;
    }

    public function initBot()
    {
        $this->list = [];

        $this->current_ask = [];

        try {
            $this->bot = new Api(config("app.debug") ?
                env("TELEGRAM_DEBUG_BOT") :
                env("TELEGRAM_PRODUCTION_BOT")
                , false);


        } catch (TelegramSDKException $e) {
            Log::error($e->getMessage() . " " . $e->getLine());

        }

        include_once base_path('routes/bot.php');

        return $this;
    }

    public function setTelegramUser($data)
    {
        $this->telegram_user = (object)[
            "id" => $data->id,
            "first_name" => $data->first_name ?? '',
            "last_name" => $data->last_name ?? '',
            "username" => $data->username ?? '',
        ];
    }

    public function handler(Update $data)
    {
        $update = json_decode($data);

        if (isset($update->inline_query)) {
            $messageId = $update->inline_query->id;

            $this->setTelegramUser($update->inline_query->from);

            $users =
                BotUserInfo::where("is_admin", true)
                    ->where("is_working", true)
                    ->orderBy("id", "DESC")
                    ->take(8)
                    ->skip(0)
                    ->get();
            $button_list = [];

            $this->reply("Ищем активных администраторов....");
            if (count($users) > 0) {
                foreach ($users as $user) {

                    $tmp_user_id = (string)$user->chat_id;
                    while (strlen($tmp_user_id) < 10)
                        $tmp_user_id = "0" . $tmp_user_id;

                    $code = base64_encode("005" . $tmp_user_id);
                    $url_link = "https://t.me/" . env("APP_BOT_NAME") . "?start=$code";

                    $tmp_button = [
                        'type' => 'article',
                        'id' => uniqid(),
                        'title' => "Запрос к Администратору",
                        'input_message_content' => [
                            'message_text' => sprintf("Администратор #%s %s (%s)", $user->id, ($user->fio ?? $user->account_name ?? $user->chat_id), ($user->phone ?? 'Без телефона')),
                        ],
                        'reply_markup' => [
                            'inline_keyboard' => [
                                [
                                    ['text' => "\xF0\x9F\x91\x89Послать запрос Администратору", "url" => "$url_link"],
                                ],

                            ]
                        ],
                        'thumb_url' => "https://sun2.48276.userapi.com/c848536/v848536665/177130/tzf1fK-6aio.jpg",
                        'url' => env("APP_URL"),
                        'description' => sprintf("Администратор #%s %s (%s)", $user->id, ($user->fio ?? $user->account_name ?? $user->chat_id), ($user->phone ?? 'Без телефона')),
                        'hide_url' => true
                    ];

                    array_push($button_list, $tmp_button);
                }
                $this->sendAnswerInlineQuery($messageId, $button_list);
            } else
                $this->reply("К сожалению активных администраторов не найдено...");

            return;
        }

        if (isset($update->channel_post))
            return;


        $this->message_id = $update->message->message_id ?? $update->callback_query->message->message_id ?? null;

        if (is_null($this->message_id)) {
            Log::info("Ошибочка");
            return "Только для запросов бота";
        }

        $this->updated_message_id = $update->callback_query->message->message_id ?? null;
        $this->setTelegramUser($update->message->from ?? $update->callback_query->from);
        $this->query = $update->message->text ?? $update->callback_query->data ?? '';

        if (isset($update->message->photo)) {
            $photos = $update->message->photo;

            if (!$this->isConversationActive()) {
                $this->sendMessage("В данный момент загрузить фотографии нет возможности!");
                return;
            }
            foreach ($photos as $photo) {
                $file_id = $photo->file_id;

                $response = Http::get(sprintf("https://api.telegram.org/bot%s/getFile?file_id=%s",
                    config("app.debug") ?
                        env("TELEGRAM_DEBUG_BOT") :
                        env("TELEGRAM_PRODUCTION_BOT"),
                    $file_id
                ));

                $images = json_decode($this->storeGet("images", "[]"), true);

                $path = sprintf("https://api.telegram.org/file/bot%s/%s",
                    config("app.debug") ?
                        env("TELEGRAM_DEBUG_BOT") :
                        env("TELEGRAM_PRODUCTION_BOT"),
                    $response->json()["result"]["file_path"]
                );


                array_push($images, $path);

                $this->setParams([
                    "images" => json_encode($images)
                ]);
            }
            $this->sendMessage("Фотографии успешно загружены!");
            return;
        };


        if (env("APP_MAINTENANCE_MODE")) {
            $keyboard = [
                [
                    ["text" => "Перейти в канал", "url" => "https://t.me/" . env("LMA_CHANNEL_LINK")]
                ]
            ];
            $this->sendPhoto("",
                "https://sun9-16.userapi.com/c858232/v858232349/173635/lTlP7wMcZEA.jpg", $keyboard);
            $this->sendMenu("Сервер находится на техническом обслуживании!", $this->keyboard_fallback_2);
            return;
        }

        $this->createNewBotUser();


        if (is_null($this->query))
            return;

        $find = false;

        $matches = [];
        $arguments = [];

        if ($this->isConversationActive()) {
            $object = $this->currentActiveConversation();
            $is_conversation_find = false;

            foreach ($this->current_ask as $item) {
                if (is_null($item["name"]))
                    break;

                if ($item["name"] == $object->name) {
                    $item["func"]($this, $this->query);
                    $this->editReplyKeyboard();
                    $is_conversation_find = true;
                }
            }

            if ($is_conversation_find)
                return;


            if (!$is_conversation_find)
                $this->stopConversation();
        }


        $maxErrorIteration = count($this->list);

        foreach ($this->list as $item) {

            if ($maxErrorIteration === 0)
                break;

            $maxErrorIteration--;

            if (is_null($item["path"]))
                continue;

            $maxErrorIteration = count($this->list);

            if (preg_match($item["path"] . "$/i", $this->query, $matches) != false) {
                foreach ($matches as $match)
                    array_push($arguments, $match);

                try {

                    $item["function"]($this, ... $arguments);

                    $find = true;
                } catch (\Exception $e) {
                    Log::error($e->getMessage() . " " . $e->getLine());
                }

                break;
            }
        }


        if (!$find) {
            foreach ($this->list as $item) {
                if (!is_null($item["path"]))
                    continue;

                try {

                    $item["function"]($this);
                } catch (\Exception $e) {
                    Log::error($e->getMessage() . " " . $e->getLine());
                    $this->sendMenu("Произошла ошибка!", $this->keyboard_fallback_2);
                }

            }

        }


        return response()
            ->json([
                "message" => "success",
                "status" => 200
            ]);

    }

    public function getUser(array $params = [])
    {
        return (count($params) == 0 ?
                BotUserInfo::where("chat_id", $this->telegram_user->id)->first() :
                BotUserInfo::with($params)->where("chat_id", $this->telegram_user->id)->first()) ?? null;

    }

    public function getChatId()
    {
        return $this->telegram_user->id;
    }

    public function sendMenu($message, $keyboard)
    {
        if (is_null($this->bot))
            return;
        try {
            $this->bot->sendMessage([
                "chat_id" => $this->telegram_user->id,
                "text" => $message,
                'parse_mode' => 'Markdown',
                'reply_markup' => json_encode([
                    'keyboard' => $keyboard,
                    'one_time_keyboard' => false,
                    'resize_keyboard' => true
                ])
            ]);

        } catch (\Exception $e) {
            Log::info(sprintf("%s %s %s",
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ));
        }
    }

    public function createNewBotUser($parent_id = null)
    {
        $id = $this->telegram_user->id;
        $username = $this->telegram_user->username;
        $lastName = $this->telegram_user->last_name;
        $firstName = $this->telegram_user->first_name;

        if ($id == null)
            return false;

        if ($this->getUser() == null) {
            $user = User::create([
                'name' => $username ?? "$id",
                'email' => "$id@t.me",
                'password' => bcrypt($id),
            ]);

            BotUserInfo::create([
                'chat_id' => $id,
                'account_name' => $username,
                'fio' => "$firstName $lastName",
                'cash_back' => 0,
                'phone' => null,
                'is_vip' => false,
                'is_admin' => false,
                'is_developer' => false,
                'is_working' => false,
                'parent_id' => $parent_id,
                'user_id' => $user->id
            ]);

            return true;
        }
        return false;
    }

    public function sendMessageToChat($chatId, $message, $keyboard = [], $parseMode = 'Markdown')
    {

        if (is_null($this->bot))
            return;
        try {
            $this->bot->sendMessage([
                "chat_id" => $chatId,
                "text" => $message,
                'parse_mode' => $parseMode,
                'reply_markup' => json_encode([
                    'inline_keyboard' => $keyboard
                ])
            ]);
        } catch (\Exception $e) {
            Log::info(sprintf("%s %s %s",
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ));
        }

    }


    public function sendMessage($message, $keyboard = [], $parseMode = 'Markdown')
    {

        if (is_null($this->bot))
            return;

        try {
            $this->bot->sendMessage([
                "chat_id" => $this->telegram_user->id,
                "text" => $message,
                'parse_mode' => $parseMode,
                'reply_markup' => json_encode([
                    'inline_keyboard' => $keyboard
                ])
            ]);
        } catch (\Exception $e) {
            Log::info(sprintf("%s %s %s",
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ));
        }

    }

    public function sendAnswerInlineQuery($id, $result)
    {

        if (is_null($this->bot))
            return;

        try {
            $this->bot->answerInlineQuery([
                'cache_time' => 300,
                "inline_query_id" => $id,
                "results" => json_encode($result)
            ]);
        } catch (\Exception $e) {
            Log::info(sprintf("%s %s %s",
                $e->getMessage(),
                $e->getFile(),
                $e->getLine()
            ));
        }

    }

    public function getMessageId(){
        return $this->message_id;
    }

    public function deleteMessage($id = null)
    {
        if (is_null($this->bot))
            return;

        try {
            $this->bot->deleteMessage([
                'chat_id' => $id ?? $this->getChatId(),
                "message_id" => $this->message_id
            ]);
        } catch (\Exception $e) {

        }
    }

    public function editMessageCaption($caption = "empty")
    {
        if (is_null($this->bot))
            return;

        try {
            $this->bot->editMessageCaption([
                'caption ' => $caption,
                'chat_id' => $this->getChatId(),
                "message_id" => $this->message_id
            ]);
        } catch (\Exception $e) {

        }
    }


    public function editMessageText($text = "empty")
    {
        if (is_null($this->bot))
            return;

        try {
            $this->bot->editMessageText([
                'text' => $text,
                'chat_id' => $this->getChatId(),
                "message_id" => $this->message_id
            ]);
        } catch (\Exception $e) {

        }
    }

    public function editReplyKeyboard($keyboard = [])
    {

        if (is_null($this->bot) || is_null($this->updated_message_id))
            return;

        try {
            $this->bot->editMessageReplyMarkup([
                'chat_id' => $this->getChatId(),
                "message_id" => $this->updated_message_id,
                'reply_markup' => json_encode([
                    'inline_keyboard' => $keyboard,
                ])
            ]);
        } catch (\Exception $e) {

        }

    }

    public function sendQuiz($question, $options, $correct_option_id, $chatId = null, $keyboard = [], $parseMode = 'Markdown')
    {
        if (is_null($this->bot))
            return;

        $this->bot->sendPoll([
            'chat_id' => is_null($chatId) ? $this->telegram_user->id : $chatId,
            'parse_mode' => $parseMode,
            'question' => $question,
            'options' => $options,
            'is_anonymous' => false,
            'type' => "quiz",
            'correct_option_id' => $correct_option_id,
            'allows_multiple_answers' => false,
            'disable_notification' => 'true',
            'reply_markup' => json_encode([
                'inline_keyboard' => $keyboard
            ])
        ]);
    }


    public function sendPoll($question, $options, $is_anonymous = ture, $allows_multiple_answers = false, $chatId = null, $keyboard = [], $parseMode = 'Markdown')
    {
        if (is_null($this->bot))
            return;


        $this->bot->sendPoll([
            'chat_id' => is_null($chatId) ? $this->telegram_user->id : $chatId,
            'parse_mode' => $parseMode,
            'question' => $question,
            'options' => $options,
            'is_anonymous' => $is_anonymous,
            'allows_multiple_answers' => $allows_multiple_answers,
            'disable_notification' => 'true',
            'reply_markup' => json_encode([
                'inline_keyboard' => $keyboard
            ])
        ]);

    }

    public function sendPhoto($message, $photoUrl, $keyboard = [], $parseMode = 'Markdown')
    {
        if (is_null($this->bot))
            return;

        try {
            $this->bot->sendPhoto([
                'chat_id' => $this->telegram_user->id,
                'parse_mode' => $parseMode,
                'caption' => $message,
                'photo' => InputFile::create($photoUrl),
                'disable_notification' => 'true',
                'reply_markup' => json_encode([
                    'inline_keyboard' => $keyboard
                ])
            ]);
        } catch (\Exception $e) {
            $tmp = mb_strlen($message) === 0 ? "Нет текста!" : $message;
            //$this->sendMessage($tmp, $keyboard, $parseMode);
            $this->sendPhoto($tmp, base_path() . "/public/img/noimage.jpg", $keyboard, $parseMode);
        }
    }

    public function sendAction($action = 'typing')
    {
        if (is_null($this->bot))
            return;

        $this->bot->sendChatAction([
            'chat_id' => $this->telegram_user->id,
            'action' => $action,
        ]);
    }


    public function sendLocation($latitude, $longitude, array $keyboard = [])
    {
        // TODO: Implement sendLocation() method.
    }


}
