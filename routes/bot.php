<?php


use Illuminate\Support\Facades\Log;

$this->hears("/test ([0-9]+) ([a-zA-Z]+)", function ($bot, ...$d) {
    $bot->test();
    Log::info("test " . ($d[2] ?? 'empty'));
});


$this->hears("/start|*.Главное меню", function ($bot) {
    $bot->getMainMenu("Добро пожаловать в модельное агенство Lotus!");
   // $bot->reply("conversation started");
});

$this->hears(".*Lotus Model Agency", function ($bot) {
    $bot->getLMAMenu("Lotus Model Agency меню!");
    // $bot->reply("conversation started");
});

$this->fallback(function ($bot) {

    $bot->reply("Fallback");
});

$this->hears("/std", function ($bot, ...$d) {
    $message = "TEST!!!";
    $keyboard = [
        [
            ["text" => "test", "callback_data" => "arka"]
        ]
    ];

    $bot->reply($message, $keyboard);
    $this->startConversation("order_name");


});

//объявляем какие мы хотим диалоги
$this->ask("order_phone", \App\Conversations\OrderConversation::class."::phone");
$this->ask("order_name", \App\Conversations\OrderConversation::class."::name");

$this->ask("test", function ($bot, $message) {
    Log::info("ASK 2 MESSAGE $message");
    $bot->reply("ASK 2 MESSAGE $message");
    $this->stopConversation();

});

$this->ask("dddd", function ($bot, $message) {
    Log::info("dddd 1 MESSAGE $message");
    $bot->reply("dddd 1 MESSAGE $message");
    $bot->reply("Напишите свой следующий вопрос");
});

$this->ask("dddd", function ($bot, $message) {
    Log::info("dddd 2 MESSAGE $message");
    $bot->reply("dddd 2 MESSAGE $message");
    $bot->reply("Спасибо за ответы!");
    $this->stopConversation();
});

/*$this->conversation("order_conversation","/test",function ($bot,... $d){
    //начали конверсейшен
    //далее добавляем вопрос
    $message = "TEST!!!";
    $keyboard = [
        [
            ["text"=>"test","callback_data"=>"/dddd"]
        ]
    ];

    $bot->ask($message,$keyboard,function ($bot, ...$d){

       $bot->reply("ASK MESSAGE");

    });
});*/
