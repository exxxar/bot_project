<?php


namespace App\Clasess;


use App\Order;

trait tLotusModelBot
{
    protected $main_menu_keyboard = [
        ["\xE2\xAD\x90Мой профиль", "\xF0\x9F\x91\x9BКорзина(%s)"],
        ["\xF0\x9F\x91\xB8Lotus Model Agency"],
        ["\xF0\x9F\x93\xB7Lotus Photostudio", "\xF0\x9F\x8C\x9FLotus Kids"],
        ["\xF0\x9F\x8E\xAALotus Camp", "\xF0\x9F\x93\xB9Combo Photoproject"],
        ["\xF0\x9F\x92\x83Lotus Dance", "\xF0\x9F\x91\x95Фирменная продукция"],

        ["\xF0\x9F\x91\x89F.A.Q."],
    ];

    protected $lma_menu_keyboard = [
        ["\xF0\x9F\x92\xACПерейти в канал LMA", "\xF0\x9F\x93\x83Анкета модели"],
        ["\xF0\x9F\x8E\x93Курсы LMA", "\xF0\x9F\x94\x8DНайти модель"],
        ["\xF0\x9F\x91\x89Задать вопрос LMA"],
        ["\xF0\x9F\x93\x83Оставить анонимный отзыв"],
        ["\xF0\x9F\x94\x99Главное меню"],
    ];

    protected $lkc_menu_keyboard = [
        ["\xF0\x9F\x8E\x93Курсы LKC", "\xF0\x9F\x92\xACПерейти в канал LKC"],
        ["\xF0\x9F\x91\x89Задать вопрос LKC"],
        ["\xF0\x9F\x93\x83Оставить анонимный отзыв"],
        ["\xF0\x9F\x94\x99Главное меню"],
    ];

    protected $lc_menu_keyboard = [
        ["\xF0\x9F\x93\x9DЗаписаться в Лагерь", "\xF0\x9F\x92\xACПерейти в канал LC"],
        ["\xF0\x9F\x91\x89Задать вопрос LC"],
        ["\xF0\x9F\x94\x99Главное меню"],
    ];

    protected $lcp_menu_keyboard = [
        ["\xF0\x9F\x93\x9DХочу на проект", "\xF0\x9F\x92\xACПерейти в канал LCP"],
        ["\xF0\x9F\x91\x89Задать вопрос LCP"],
        ["\xF0\x9F\x94\x99Главное меню"],
    ];

    protected $lp_menu_keyboard = [
        ["\xE2\x9A\xA1Аренда помещений \ оборудования"],
        ["\xE2\x8F\xB3Фотопроекты на месяц", "\xF0\x9F\x92\xACПерейти в канал LP"],
        ["\xF0\x9F\x93\x83Оставить отзыв о фотопроекте"],
        ["\xF0\x9F\x91\x89Задать вопрос LP"],
        ["\xF0\x9F\x94\x99Главное меню"],
    ];

    protected $ld_menu_keyboard = [
        ["\xF0\x9F\x93\x9DХочу танцевать", "\xF0\x9F\x92\xACПерейти в канал LD"],
        ["\xF0\x9F\x92\xAAХочу на Фитнес для взрослых!"],
        ["\xF0\x9F\x91\x89Задать вопрос LD"],
        ["\xF0\x9F\x94\x99Главное меню"],
    ];

    protected $admin_menu_keyboard = [
        ["\xF0\x9F\x94\xB8Управление товарами", "\xF0\x9F\x94\xB8Управление анкетами"],
        ["\xF0\x9F\x94\xB8Управление опросами", "\xF0\x9F\x94\xB8Управление вопросами"],
        ["\xF0\x9F\x94\xB8Управление фотопроектами"],
        ["\xF0\x9F\x94\xB8Статистика по боту"],
        ["\xF0\x9F\x93\xA3Каналы администраторов"],
        /*       ["\xF0\x9F\x93\xA9СМС Рассылки"],*/
        ["\xF0\x9F\x94\x99Главное меню"],
    ];

    protected $admin_lp_menu_keyboard = [
        ["Добавить фотопроект"],
        ["Массовое обнуление скидок"],
        ["\xF0\x9F\x94\x99Главное меню"],

    ];

    protected $keyboard_fallback = [
        [
            "Продолжить позже"
        ],
    ];

    protected $keyboard_fallback_2 = [
        [
            "Попробовать опять"
        ],
    ];

    protected $keyboard_fallback_3 = [
        [
            "Продолжить позже"
        ],
        [
            ["text" => "Отправить мой номер",
            "request_contact" => true]
        ]
    ];


    protected $keyboard_basket = [
        ["\xF0\x9F\x93\xA6Посмотреть мои товары", "\xF0\x9F\x92\xB3Оформить заказ"],
        ["\xE2\x9D\x8CОчистить корзину"],
        ["\xF0\x9F\x91\x95Фирменная продукция"],
        ["\xF0\x9F\x94\x99Главное меню"],
    ];


    public function getBasketMenu($message)
    {
        $this->sendMenu($message, $this->keyboard_basket);
    }

    public function getMainMenu($message)
    {
        $user = $this->getUser();
        $inBasketCount = Order::where("user_id", $user->user_id)
            ->where("is_confirmed", false)
            ->count();
        $this->main_menu_keyboard[0][1] = sprintf($this->main_menu_keyboard[0][1], $inBasketCount);
        $this->sendMenu($message, $this->main_menu_keyboard);
    }

    public function getAdminMenu($message)
    {
        $tmp_menu = $this->admin_menu_keyboard;
        $user = $this->getUser();
        if (!is_null($user))
            if ($user->is_admin) {
                $this->sendMenu($message, $tmp_menu);
                return;
            }
        $this->getMainMenu($message);
    }


    public function getLMAMenu($message)
    {
        $tmp_menu = $this->lma_menu_keyboard;
        $this->sendMenu($message, $tmp_menu);
    }

    public function getLCPMenu($message)
    {
        $tmp_menu = $this->lcp_menu_keyboard;
        $this->sendMenu($message, $tmp_menu);
    }

    public function getLKCMenu($message)
    {
        $tmp_menu = $this->lkc_menu_keyboard;
        $this->sendMenu($message, $tmp_menu);
    }

    public function getLCMenu($message)
    {
        $tmp_menu = $this->lc_menu_keyboard;
        $this->sendMenu($message, $tmp_menu);
    }

    public function getLPMenu($message)
    {
        $tmp_menu = $this->lp_menu_keyboard;
        $this->sendMenu($message, $tmp_menu);
    }

    public function getLDMenu($message)
    {
        $tmp_menu = $this->ld_menu_keyboard;
        $this->sendMenu($message, $tmp_menu);
    }

    public function getFallbackMenu($message)
    {
        $this->sendMenu($message, $this->keyboard_fallback);
    }

    public function getFallbackMenuWithPhone($message)
    {
        $this->sendMenu($message, $this->keyboard_fallback_3);
    }

}
