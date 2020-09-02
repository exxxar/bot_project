<?php


namespace App\Conversations;


use App\Enums\ProductTypeEnum;
use App\Order;
use App\Product;
use App\Profile;
use Illuminate\Support\Facades\Log;

class MainConversation
{

    public static function start($bot)
    {
        $bot->stopConversation();
        $bot->getMainMenu("Добро пожаловать в модельное агенство Lotus!");
    }

    public static function profile($bot)
    {
        $user = $bot->getUser();

        $keyboard = [
            [
                ["text" => "\xF0\x9F\x8E\xB4Моя анкета", "callback_data" => "/user_profile"]
            ]
        ];

        if ($user->is_admin)
            array_push($keyboard, [
                ["text" => "Админ. панель", "callback_data" => "/admin"]
            ]);

        $tmp_id = (string)$user->id;
        while (strlen($tmp_id) < 10)
            $tmp_id = "0" . $tmp_id;

        $code = base64_encode("001" . $tmp_id);
        $qr_url = env("QR_URL") . "https://t.me/" . env("APP_BOT_NAME") . "?start=$code";
        $bot->sendPhoto(sprintf("_Покажите QR-код администратору!_\n*Ваш профиль*\nСкидка %s %% за %s фотосъемок!",
            $user->discount ?? 0, $user->photo_count ?? 0), "$qr_url", $keyboard);

    }

    public static function currentProfile($bot)
    {

        $user = $bot->getUser();

        $profile = Profile::where("user_id", $user->user_id)->first();

        $keyboard = [
            [
                ["text" => is_null($profile) ? "\xF0\x9F\x93\x9DСоздать анкету" : "\xF0\x9F\x93\x9DИзменить анкету", "callback_data" => "/edit_current_prof"]
            ]
        ];

        $bot->sendMessage(
            sprintf("*Ваши анкетные данные:*\n"
                . "\xF0\x9F\x94\xB9Ф.И.О.: _%s_\n"
                . "\xF0\x9F\x94\xB9Номер телефона: _%s_\n"
                . "\xF0\x9F\x94\xB9Ваш рост: %s\n"
                . "\xF0\x9F\x94\xB9Ваш возраст: %s\n"
                . "\xF0\x9F\x94\xB9Ваш город: %s\n"
                . "\xF0\x9F\x94\xB9Ваш пол: %s\n"
                . "\xF0\x9F\x94\xB9Обучались ли Вы в модельной школе: %s\n"
                . "\xF0\x9F\x94\xB9Желаете обучаться: %s\n",
                $profile->full_name ?? "Без имени",
                $profile->phone ?? "Не указан",
                $profile->height ?? "Не указан",
                $profile->age ?? "Не указан",
                $profile->city ?? "Не указан",
                $profile->sex ?? "Не указан",
                $profile->model_school_education ?? "Не указан",
                $profile->wish_learn ?? "Не указан"

            ), $keyboard);
    }

    public static function admin($bot)
    {
        $bot->reply("Панель администратора");
    }

    public static function products($bot, ...$d)
    {
        $page = isset($d[1]) ? intval($d[1]) : 0;

        $products = Product::where("type", ProductTypeEnum::Items)
            ->take(env("PAGINATION_PER_PAGE"))
            ->skip($page * env("PAGINATION_PER_PAGE"))
            ->get();

        if (count($products) === 0) {
            $bot->sendMessage("К сожалению, товаров еще нет, но они появятся в скором времени!");
            return;
        }

        foreach ($products as $product) {
            $keyboard = [
                [
                    ["text" => "Добавить в корзину ($product->price ₽)", "callback_data" => "/add_to_cart $product->id"]
                ]
            ];
            $bot->sendPhoto(sprintf("*%s*\n_%s_",
                $product->title,
                $product->description),
                $product->image, $keyboard);
        }
        $bot->pagination("/product_list", $products, $page, "Наша продукция");
    }

    public static function addProductToCart($bot, ...$d)
    {
        $id = isset($d[1]) ? intval($d[1]) : 0;
        $user = $bot->getUser();

        $product = Product::find($id);

        if (is_null($product)) {
            $bot->reply("Товар не найден!");
            return;
        }

        $productInOrder = Order::where("user_id", $user->user_id)
            ->where("product_id", $product->id)
            ->where("is_confirmed", false)
            ->count() === 0 ? false : true;

        if ($productInOrder) {
            $bot->reply("Продукт уже в корзине!");
            return;
        }

        Order::create([
            "user_id" => $user->user_id,
            "product_id" => $product->id,
            "comment" => "",
            "is_confirmed" => false,
        ]);
        $bot->deleteMessage();
        $bot->getMainMenu("Ваш товар успешно добавлен в корзину!");

    }

    public static function showProductInCart($bot, ...$d)
    {
        $user = $bot->getUser();

        $orders = Order::with(["product"])
            ->where("user_id", $user->user_id)
            ->where("is_confirmed", false)
            ->get();

        if (count($orders) === 0) {
            $bot->getMainMenu("Ваша корзина пуста");
            return;
        }

        foreach ($orders as $order) {
            $keyboard = [
                [
                    ["text" => "\xE2\x9D\x8CУдалить товар из корзины", "callback_data" => "/remove_product_from_cart $order->id"]
                ]
            ];

            $bot->sendPhoto(sprintf("*%s*\n_%s_",
                $order->product->title,
                $order->product->description),
                $order->product->image, $keyboard);


        }
    }

    public static function cart($bot, ...$d)
    {

        $user = $bot->getUser();

        $orders = Order::with(["product"])
            ->where("user_id", $user->user_id)
            ->where("is_confirmed", false)
            ->get();

        if (count($orders) === 0) {
            $bot->getMainMenu("Ваша корзина пуста");
            return;
        }

        $price = 0;
        $tmp = sprintf("*Корзина с товарами (%s ед.)*:\n", count($orders));
        foreach ($orders as $order) {
            $tmp .= sprintf("\xF0\x9F\x94\xB9 #%s *%s* (%s руб.)\n",
                $order->product->id,
                $order->product->title,
                $order->product->price
            );
            $price += $order->product->price;
        }

        $tmp .= "Суммарно к оплате: *$price руб.*";
        $bot->getBasketMenu($tmp);
    }

    public static function clearCart($bot, ...$d)
    {
        $user = $bot->getUser();

        $orders = Order::where("user_id", $user->user_id)
            ->where("is_confirmed", false)
            ->get();

        if (count($orders) === 0) {
            $bot->getMainMenu("Корзина пуста!");
            return;
        }

        foreach ($orders as $order)
            $order->delete();
        $bot->getMainMenu("Корзина успешно очищена!");
    }

    public static function removeFromCart($bot, ...$d)
    {
        $id = isset($d[1]) ? intval($d[1]) : 0;

        $order = Order::where("id", $id)
            ->where("is_confirmed", false)
            ->first();

        if (is_null($order)) {
            $bot->reply("Данный заказ не найден!");
            return;
        }
        $bot->deleteMessage();
        $order->delete();
        $bot->reply("Данный заказ успешно удален!");

    }

    public static function lmaMenu($bot)
    {
        $bot->getLMAMenu("Lotus Model Agency меню!");
    }

    public static function lpMenu($bot)
    {
        $bot->getLPMenu("Lotus Photostudio меню!");
    }

    public static function lcMenu($bot)
    {
        $bot->getLCMenu("Lotus Camp меню!");
    }

    public static function ldMenu($bot)
    {
        $bot->getLDMenu("Lotus Dance меню!");
    }

    public static function lkMenu($bot)
    {
        $bot->getLKCMenu("Lotus Kids меню!");
    }


    public static function cpMenu($bot)
    {
        $bot->reply("Lotus Combo projekt меню!");
    }


    public static function askQuestion($bot)
    {
        $keyboard = [
            [
                ["text" => "\xF0\x9F\x93\x9DЗадать вопрос", "callback_data" => "/ask_question"]
            ],
            [
                ["text" => "\xF0\x9F\x93\x96Частые вопросы", "url" => "https://telegra.ph/Uslugi-01-06"]
            ]
        ];

        $bot->sendMessage("Вопросы и ответы!", $keyboard);
    }
}
