<?php


namespace App\Clasess;


use Telegram\Bot\Objects\Update;

interface iBaseBot
{

    public function getUser(array $params = []);

    public function createNewBotUser();

    public function sendMessage($message, $keyboard = [], $parseMode = 'Markdown');

    public function sendPhoto($message, $photoUrl, $keyboard = [], $parseMode = 'Markdown');

    public function sendLocation($latitude, $longitude, array $keyboard = []);

    public function sendMenu($message, $keyboard);

    public function editMessageText($text = "empty");

    public function editReplyKeyboard($keyboard = []);

    public function pagination($message, $keyboard = [], $parseMode = 'Markdown');

}
