<?php


namespace App\Clasess;


trait tLotusModelBot
{
    protected $main_menu_keyboard = [
        ["Мой профиль", "Корзина(%s)"],
        ["Фирменная продукция"],
        ["Lotus Model Agency", "Lotus Kids"],
        ["Lotus Photostudio", "Combo Photoprojekt"],
        ["Lotus Camp", "Lotus Dance"],
    ];

    protected $lma_menu_keyboard = [
        ["Перейти в канал LMA"],
        ["Анкета модели"],
        ["Курсы LMA"],
        ["Найти модель"],
        ["Задать вопрос"],
        ["Главное меню"],
    ];

    protected $lkc_menu_keyboard = [
        ["Курсы"],
        ["Перейти в канал LKC"],
        ["Задать вопросы"],
        ["Главное меню"],
    ];

    protected $lc_menu_keyboard = [
        ["Записаться"],
        ["Канал Lotus Camp"],
        ["Задать вопросы"],
        ["Главное меню"],
    ];

    protected $lp_menu_keyboard = [
        ["Аренда помещений \ оборудования"],
        ["Фотопроекты на месяц"],
        ["Канал фотостудии"],
        ["Задать вопросы"],
        ["Главное меню"],
    ];

    protected $ld_menu_keyboard = [
        ["Канал Lotus Dance"],
        ["Задать вопросы"],
        ["Главное меню"],
    ];

    protected $admin_menu_keyboard = [
        ["Управление товарами"],
        ["Управление анкетами"],
        ["Управление опросами"],
        ["Каналы администраторов"],
        ["Главное меню"]
    ];

    protected $admin_lp_menu_keyboard = [
        ["Управление фотопроектами"],
        ["Массовое обнуление скидок"],

    ];

    public function getMainMenu($message)
    {
        $inBasketCount = 0;
        $this->main_menu_keyboard[0][1] = sprintf($this->main_menu_keyboard[0][1], $inBasketCount);
        $this->sendMenu($message, $this->main_menu_keyboard);
    }

    public function getLMAMenu($message)
    {
        $tmp_menu = $this->lma_menu_keyboard;
        $user = $this->getUser();
        if ($user->is_admin)
            array_push($tmp_menu, ["Админ LMA Panel"]);
        $this->sendMenu($message, $tmp_menu);
    }

    public function getLKCMenu($message)
    {
        $tmp_menu = $this->lkc_menu_keyboard;
        $user = $this->getUser();
        if ($user->is_admin)
            array_push($tmp_menu, ["Админ LKC Panel"]);
        $this->sendMenu($message, $tmp_menu);
    }

    public function getLCMenu($message)
    {
        $tmp_menu = $this->lc_menu_keyboard;
        $user = $this->getUser();
        if ($user->is_admin)
            array_push($tmp_menu, ["Админ LC Panel"]);
        $this->sendMenu($message, $tmp_menu);
    }

    public function getLPMenu($message)
    {
        $tmp_menu = $this->lp_menu_keyboard;
        $user = $this->getUser();
        if ($user->is_admin)
            array_push($tmp_menu, ["Админ LP Panel"]);
        $this->sendMenu($message, $tmp_menu);
    }

    public function getLDMenu($message)
    {
        $tmp_menu = $this->ld_menu_keyboard;
        $user = $this->getUser();
        if ($user->is_admin)
            array_push($tmp_menu, ["Админ LD Panel"]);
        $this->sendMenu($message, $tmp_menu);
    }

    public function getAdminMenu($message)
    {
        $tmp_menu = array_merge($this->admin_menu_keyboard,
            $this->admin_lp_menu_keyboard);
        $this->sendMenu($message, $tmp_menu);
    }
}
