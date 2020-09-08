<?php


namespace App\Conversations;


use App\Enums\ProductTypeEnum;
use App\Order;
use App\PhotoProject;
use App\Product;
use App\Profile;
use Illuminate\Support\Facades\Log;
use Laravel\BotCashBack\Models\BotUserInfo;

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
                ["text" => "\xF0\x9F\x8E\xB4Моя анкета", "callback_data" => "/current_profile"]
            ]
        ];

     /*   $work_admin_count = BotUserInfo::where("is_admin", true)
                ->where("is_working", true)
                ->get()
                ->count() ?? 0;

        if ($work_admin_count > 0)
            array_push($keyboard, [
                ['text' => "Запрос Администратора", 'switch_inline_query_current_chat' => ""],
            ]);*/


        if ($user->is_admin)
            array_push($keyboard, [
                ["text" => "Админ. панель", "callback_data" => "/admin_panel"]
            ]);

        $tmp_id = (string)$user->user_id;
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
                . "\xF0\x9F\x94\xB9Ваш возраст: %s (%s.%s)\n"
                . "\xF0\x9F\x94\xB9Ваш город: %s\n"
                . "\xF0\x9F\x94\xB9Ваш пол: %s\n"
                . "\xF0\x9F\x94\xB9Обучались ли Вы в модельной школе: %s\n"
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
                ($profile->wish_learn ?? "Не указан")

            ), $keyboard);
    }

    public static function admin($bot)
    {
        $bot->reply("Панель администратора");
    }

    public function products($bot, $type, $page, $link)
    {


        $products = Product::where("type", $type)
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
                    ["text" => "\xF0\x9F\x92\xA1Подробнее", "callback_data" => "/more_info $product->id"]
                ],
                [
                    ["text" => "\xF0\x9F\x92\xB3Добавить в корзину ($product->price ₽)", "callback_data" => "/add_to_cart $product->id"]
                ]
            ];
            $bot->sendPhoto(sprintf("*%s*\n_%s..._",
                $product->title,
                mb_strcut($product->description,0,250)),
                $product->image, $keyboard);
        }
        $bot->pagination($link, $products, $page, "Наша продукция");
    }

    public static function brandedGoods($bot, ...$d)
    {
        $page = isset($d[1]) ? intval($d[1]) : 0;
        (new self())->products($bot, ProductTypeEnum::Items, $page, "/product_list");
    }

    public static function lmaCourses($bot, ...$d)
    {
        $page = isset($d[1]) ? intval($d[1]) : 0;
        (new self())->products($bot, ProductTypeEnum::LMACourses, $page, "/lma_courses_list");
    }

    public static function lkcCourses($bot, ...$d)
    {
        $page = isset($d[1]) ? intval($d[1]) : 0;
        (new self())->products($bot, ProductTypeEnum::LKCCourses, $page, "/lkc_courses_list");
    }

    public static function equipmentRent($bot, ...$d)
    {
        $page = isset($d[1]) ? intval($d[1]) : 0;
        (new self())->products($bot, ProductTypeEnum::Services, $page, "/equipment_rent_list");
    }

    public static function moreInfo($bot, ...$d)
    {
        $id = isset($d[1]) ? intval($d[1]) : 0;
        $user = $bot->getUser();

        $product = Product::find($id);

        if (is_null($product)) {
            $bot->reply("Товар не найден!");
            return;
        }
        $keyboard = [
            [
                ["text" => "\xF0\x9F\x92\xB3Добавить в корзину ($product->price ₽)", "callback_data" => "/add_to_cart $product->id"]
            ]
        ];
       // $bot->sendPhoto($product->title,$product->image,$keyboard);
        $bot->sendMessage("*$product->title*\n_$product->description _",$keyboard);

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
                    ["text" => "\xF0\x9F\x92\xA1Подробнее", "callback_data" => "/more_info ".$order->product->id]
                ],
                [
                    ["text" => "\xE2\x9D\x8CУдалить товар из корзины", "callback_data" => "/remove_product_from_cart $order->id"]
                ]
            ];

            $bot->sendPhoto(sprintf("*%s*\n_%s..._",
                $order->product->title,
                mb_strcut($order->product->description,0,255)),
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
        $bot->reply("Скоро будет доступно!");
    }


    public static function askQuestion($bot)
    {
        $keyboard = [
            [
                ["text" => "\xF0\x9F\x93\x9DВопрос администратору", "callback_data" => "/ask_question LMA"]
            ],
            [
                ["text" => "\xF0\x9F\x93\x96Частые вопросы", "url" => "https://telegra.ph/Uslugi-01-06"]
            ]
        ];

        $bot->sendMessage("Вопросы и ответы!", $keyboard);
    }

    public static function monthPhotoprojects($bot, ...$d)
    {
        $page = isset($d[1]) ? intval($d[1]) : 0;

        $products = PhotoProject::orderBy("position", "asc")
            ->take(env("PAGINATION_PER_PAGE"))
            ->skip($page * env("PAGINATION_PER_PAGE"))
            ->get();

        if (count($products) === 0) {
            $bot->sendMessage("К сожалению, фотопроектов еще нет, но они появятся в скором времени!");
            return;
        }

        foreach ($products as $product) {

            $price = $product->price == 0 ? "Индивидуально" : "$product->price ₽";

            $keyboard = [
                [
                    ["text" => "Записаться на проект (цена: $price)", "callback_data" => "/i_want_to_photo_project $product->id"]
                ]
            ];


            $tmp = "Название проекта: *%s*\n\xF0\x9F\x94\xB9Дата и время проведения: *%s* в *%s*\n\xF0\x9F\x94\xB9Место проведения: *%s*\n\xF0\x9F\x94\xB9Организатор: *%s*\n\xF0\x9F\x94\xB9Фотограф: *%s*\n\xF0\x9F\x94\xB9Преподаватель: *%s*\n";

            $bot->sendPhoto(sprintf($tmp,
                $product->title,
                $product->date,
                $product->time,
                $product->place,
                $product->sponsor,
                $product->photographer,
                $product->teacher),
                $product->image, $keyboard);
        }
        $bot->pagination("/test", $products, $page, "Наша продукция");
    }

    public static function goToChannel($bot, ...$d)
    {
        $type = isset($d[1]) ? $d[1] : 'LMA';

        $bot->reply($type);

       /* $channels = [
            "LMA" => ["link" => env("LMA_CHANNEL_LINK"), "logo" => "http://lotus-model.ru/assets/app/img/lotus%20agency.png"],
            "LKC" => ["link" => env("LKC_CHANNEL_LINK"), "logo" => "http://lotus-model.ru/assets/app/img/lotus%20kids.png"],
            "LD" => ["link" => env("LD_CHANNEL_LINK"), "logo" => "http://lotus-model.ru/assets/app/img/lotus%20agency.png"],
            "LC" => ["link" => env("LC_CHANNEL_LINK"), "logo" => "http://lotus-model.ru/assets/app/img/lotus%20camp.jpg"],
            "LP" => ["link" => env("LP_CHANNEL_LINK"), "logo" => "http://lotus-model.ru/assets/app/img/lotus%20photo.png"]
        ];

        $keyboard = [
            [
                ["text" => "Перейти в канал $type", "url" => $channels[$type]["link"]]
            ]
        ];
        $bot->sendPhoto("Ссылка на переход в канал", $channels[$type]["logo"], $keyboard);*/


    }
}
