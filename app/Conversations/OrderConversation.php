<?php


namespace App\Conversations;


use Illuminate\Support\Facades\Log;

class OrderConversation
{
    public static function phone($bot, $message)
    {

        if ($message!=="test") {
            $bot->reply("ASK NOT PHONE MESSAGE $message");
            $bot->next("order_phone");
            return;
        }
        else
            $bot->next("order_name");

        $bot->reply("ASK phone MESSAGE $message");

        (new self)->prepareSomeData($bot);
    }

    public static function name($bot, $message)
    {
        Log::info("ASK name MESSAGE $message");

        $bot->reply("ASK name MESSAGE $message");
        $bot->stopConversation();
    }

    public function prepareSomeData($bot)
    {
        $bot->reply("prepare some data");
    }
}
