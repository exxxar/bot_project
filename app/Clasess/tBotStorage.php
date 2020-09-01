<?php


namespace App\Classes;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

trait tBotStorage
{

    public function clearStorage()
    {
        Cache::forget($this->getChatId());
    }

    public function addToStorage($key, $value)
    {
        $tmp = json_decode(Cache::get($this->getChatId(), "[]"), true);
        $tmp[$key] = $value;
        Cache::forget($this->getChatId());
        Cache::add($this->getChatId(), json_encode($tmp));

    }


    public function hasInStorage($key)
    {
        $tmp = json_decode(Cache::get($this->getChatId(), "[]"), true);

        return array_key_exists($key,$tmp);
    }

    public function getFromStorage($key, $default = null)
    {
        $tmp = json_decode(Cache::get($this->getChatId(), "[]"), true);


        return count($tmp) > 0 ? $tmp[$key] : $default;
    }
}
