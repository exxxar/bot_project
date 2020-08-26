<?php

namespace Laravel\BotCashBack\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class BotUserInfo extends Model
{
    protected $table="bot_user_infos";

    protected $fillable = [
        'chat_id',
        'account_name',
        'fio',
        'cash_back',
        'phone',
        'is_vip',
        'is_admin',
        'is_developer',
        'is_working',
        'parent_id'
    ];



    public function parent()
    {
        return $this->hasOne(BotUserInfo::class,'id','parent_id');
    }
}
