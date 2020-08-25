<?php


namespace App\Classes;


use Illuminate\Support\Facades\Log;

trait tBotConversation
{
    use tBotStorage;

    public function currentActiveConversation(){
        return (object)json_decode($this->getFromStorage("is_conversation_active"));
    }

    public function next($name,$params = []){
        $this->addToStorage("is_conversation_active", json_encode([
            "name"=>$name,
        ]));
    }

    public function startConversation($name)
    {
        $this->next($name);
    }

    public function stopConversation()
    {
        $this->clearStorage();
    }

    public function isConversationActive()
    {
        return $this->hasInStorage("is_conversation_active");
    }


}
