<?php


namespace App\Clasess;


use Telegram\Bot\Objects\Update;

interface iBaseBot
{

    public function reply($message, $keyboard = [], $parseMode = 'Markdown');

    public function pagination($message, $model,$page, $resultMessage);

}
