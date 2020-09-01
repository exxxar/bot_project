<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        "chat_id",
        "question_type",
        "name",
        "message",
        'answered_by_id'
    ];

    public function admin(){
        return $this->hasOne(User::class,'id','answered_by_id');
    }
}
