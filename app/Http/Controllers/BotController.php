<?php

namespace App\Http\Controllers;

use App\Clasess\BaseBot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;

class BotController extends Controller
{
    private $bot;


    public function test()
    {
   //  $this->bot->handler("test");
    }

    public function __construct(BaseBot $bot)
    {
        $this->bot = $bot;
    }

    //
    public function handle(Request $request)
    {
        try {
            $updates = Telegram::getWebhookUpdates();
            $this->bot->handler($updates);
        } catch (\Exception $e) {
            $error_message = sprintf("%s %s %s",
                $e->getFile(),
                $e->getLine(),
                $e->getMessage()
            );

            Log::info($error_message);

            return response()->json([
                "message" => $error_message
            ]);
        }
        return response()->json([
            "message" => "Success!"
        ]);

    }
}
