<?php

namespace Laravel\BotCashBack\Models;

use App\Profile;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Log;

class BotUserInfo extends Model
{
    protected $table = "bot_user_info";

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
        'parent_id',
        'user_id'
    ];

    protected $appends = [
        'discount', 'photo_count','user_profile'
    ];

    public function getUserProfileAttribute(){
        return $this->profile()->first();
    }
    public function getPhotoCountAttribute()
    {
        return $this->user()->first()->ps_photo_count ?? 0;
    }

    public function getDiscountAttribute()
    {
        return $this->user()->first()->ps_discount ?? 0;
    }

    public function profile()
    {
        return $this->hasOne(Profile::class, 'user_id', 'user_id');
    }


    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }


    public function parent()
    {
        return $this->hasOne(BotUserInfo::class, 'id', 'parent_id');
    }
}
