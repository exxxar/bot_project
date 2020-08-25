<?php


namespace App\Clasess;


use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\Update;

abstract class AbstractBot
{
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
                , true);


        } catch (TelegramSDKException $e) {
            Log::error($e->getMessage() . " " . $e->getLine());
        }

        include_once base_path('routes/bot.php');

        return $this;
    }

    public function handler(Update $data)
    {
        $update = json_decode($data);

        if (isset($update->channel_post))
            return;


        $this->message_id = $update->message->message_id ?? $update->callback_query->message->message_id;
        $this->updated_message_id = $update->callback_query->message->message_id ?? null;
        $this->telegram_user = (object)[
            "id" => $update->message->from->id ?? $update->callback_query->from->id,
            "first_name" => $update->message->from->first_name ?? $update->callback_query->from->first_name ?? '',
            "last_name" => $update->message->from->last_name ?? $update->callback_query->from->last_name ?? '',
            "username" => $update->message->from->username ?? $update->callback_query->from->username ?? '',
        ];
        $this->query = $update->message->text ?? $update->callback_query->data;

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

        foreach ($this->list as $item) {

            if (is_null($item["path"]))
                continue;

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
                }

            }

        }


        return response()
            ->json([
                "message" => "success",
                "status" => 200
            ]);

    }

    public function getChatId()
    {
        return $this->telegram_user->id;
    }

    public function sendMenu($message, $keyboard)
    {
        if (is_null($this->bot))
            return;

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
    }
}
