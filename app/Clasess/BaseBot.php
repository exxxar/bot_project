<?php


namespace App\Clasess;


use App\Classes\tBotConversation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\BotCashBack\Models\BotUserInfo;
use ReflectionMethod;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\Update;

class BaseBot extends AbstractBot implements iBaseBot
{
    use tBotConversation, tLotusModelBot;

    protected $keyboard_fallback = [
        [
            "Попробовать снова"
        ],
    ];

    public function __construct()
    {
        $this->initBot();
    }


    public function getUser(array $params = [])
    {
        return (count($params) == 0 ?
                BotUserInfo::where("chat_id", $this->telegram_user->id)->first() :
                BotUserInfo::with($params)->where("chat_id", $this->telegram_user->id)->first()) ?? null;

    }


    public function reply($message, $keyboard = [], $parseMode = 'Markdown')
    {
        $this->sendMessage($message, $keyboard, $parseMode);
    }

    public function sendMessage($message, $keyboard = [], $parseMode = 'Markdown')
    {

        if (is_null($this->bot))
            return;

        $this->bot->sendMessage([
            "chat_id" => $this->telegram_user->id,
            "text" => $message,
            'parse_mode' => $parseMode,
            'reply_markup' => json_encode([
                'inline_keyboard' => $keyboard
            ])
        ]);

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

    public function sendPhoto($message, $photoUrl, $keyboard = [], $parseMode = 'Markdown')
    {
        if (is_null($this->bot))
            return;

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

    public function sendLocation($latitude, $longitude, array $keyboard = [])
    {
        // TODO: Implement sendLocation() method.
    }

    public function createNewBotUser()
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
                'fio_from_telegram' => "$firstName $lastName",
                'source' => "000",
                'telegram_chat_id' => $id,
                'referrals_count' => 0,
                'referral_bonus_count' => 10,
                'cashback_bonus_count' => 0,
                'is_admin' => false,
            ]);

            // event(new AchievementEvent(AchievementTriggers::MaxReferralBonusCount, 10, $user));
            return true;


        }

        if (!$this->getUser()->onRefferal()) {
            $skidobot = User::where("email", "skidobot@gmail.com")->first();
            if ($skidobot) {
                $skidobot->referrals_count += 1;
                $skidobot->save();

                $user = $this->getUser();
                $user->parent_id = $skidobot->id;
                $user->save();
            }

        }
        return false;
    }


    public function pagination($message, $keyboard = [], $parseMode = 'Markdown')
    {
        // TODO: Implement pagination() method.
    }
}
