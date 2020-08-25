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

        $tmp = array_filter($tmp, function ($item) use ($key) {
            return isset($item[$key]);
        });

        if (count($tmp) == 0) {
            array_push($tmp, ["$key" => $value]);
        } else
            $tmp[0][$key] = $value;

        Cache::forget($this->getChatId());
        Cache::add($this->getChatId(), json_encode($tmp));

    }

    public function hasInStorage($key)
    {
        $tmp = json_decode(Cache::get($this->getChatId(), "[]"), true);

        $is_exist = false;

        foreach ($tmp as $item) {

            if (array_key_exists("$key", $item))
                $is_exist = true;
        }


        return $is_exist;
    }

    public function getFromStorage($key, $default = null)
    {
        $tmp = json_decode(Cache::get($this->getChatId(), "[]"), true);


        $items = array_filter($tmp, function ($item) use ($key) {
            return isset($item[$key]);
        });

        return count($items) > 0 ? $tmp[0][$key] : $default;
    }
}
